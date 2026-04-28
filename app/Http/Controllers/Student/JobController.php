<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\JobPosting;
use App\Notifications\NewApplicationNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class JobController extends Controller
{
    public function index(Request $request)
    {
        $query = JobPosting::active()->with('company');

        // Filtros
        if ($request->filled('area')) {
            $query->where('area', $request->area);
        }
        if ($request->filled('modality')) {
            $query->where('modality', $request->modality);
        }
        if ($request->filled('contract_type')) {
            $query->where('contract_type', $request->contract_type);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhereHas('company', fn($c) => $c->where('company_name', 'like', "%{$search}%"));
            });
        }

        $jobPostings = $query->latest()->paginate(12)->withQueryString();

        // IDs de vacantes a las que ya se postuló el estudiante
        $appliedIds = Application::where('user_id', Auth::id())
            ->pluck('job_posting_id')
            ->toArray();

        $areas = JobPosting::active()->distinct()->pluck('area')->filter()->values();

        return view('student.jobs.index', compact('jobPostings', 'appliedIds', 'areas'));
    }

    public function show(JobPosting $jobPosting)
    {
        if (!$jobPosting->isActive()) {
            abort(404);
        }

        $jobPosting->load('company');
        $hasApplied = Application::where('user_id', Auth::id())
            ->where('job_posting_id', $jobPosting->id)
            ->exists();

        return view('student.jobs.show', compact('jobPosting', 'hasApplied'));
    }

    public function apply(Request $request, JobPosting $jobPosting)
    {
        $user = Auth::user();

        // Verificar que no haya postulado antes
        $existing = Application::where('user_id', $user->id)
            ->where('job_posting_id', $jobPosting->id)
            ->first();

        if ($existing) {
            return back()->with('error', 'Ya te has postulado a esta vacante.');
        }

        // Verificar que la vacante esté activa
        if (!$jobPosting->isActive()) {
            return back()->with('error', 'Esta vacante ya no está disponible.');
        }

        $request->validate([
            'cover_letter' => 'nullable|string|max:2000',
            'cv'           => $jobPosting->requires_cv
                ? 'required|file|mimes:pdf|max:5120'
                : 'nullable|file|mimes:pdf|max:5120',
        ], [
            'cv.required' => 'La hoja de vida es obligatoria para esta vacante.',
            'cv.max' => 'El archivo de la hoja de vida excede el tamaño permitido (5MB). No es posible adjuntar el archivo.',
            'cv.mimes' => 'La hoja de vida debe ser un archivo en formato PDF.',
        ]);

        $cvPath = null;

        // Usar CV adjunto o el del perfil
        if ($request->hasFile('cv')) {
            $cvPath = $request->file('cv')->store('cvs', 'public');
        } elseif ($user->studentProfile && $user->studentProfile->cv_path) {
            $cvPath = $user->studentProfile->cv_path;
        }

        $application = Application::create([
            'user_id'        => $user->id,
            'job_posting_id' => $jobPosting->id,
            'cover_letter'   => $request->cover_letter,
            'cv_path'        => $cvPath,
            'status'         => 'pending',
        ]);

        // Notificar a la empresa
        $jobPosting->company->user->notify(new NewApplicationNotification($application));

        return redirect()->route('student.applications')
            ->with('success', '¡Te has postulado exitosamente a "' . $jobPosting->title . '"!');
    }
}
