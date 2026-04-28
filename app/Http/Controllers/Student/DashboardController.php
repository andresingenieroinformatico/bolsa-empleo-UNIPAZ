<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\JobPosting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $profile = $user->studentProfile;

        $recentApplications = Application::where('user_id', $user->id)
            ->with(['jobPosting.company'])
            ->latest()
            ->take(5)
            ->get();

        $totalApplications = Application::where('user_id', $user->id)->count();
        $acceptedApplications = Application::where('user_id', $user->id)
            ->where('status', 'accepted')->count();
        $interviewApplications = Application::where('user_id', $user->id)
            ->where('status', 'interview')->count();

        $latestVacantes = JobPosting::active()
            ->with('company')
            ->latest()
            ->take(6)
            ->get();

        return view('student.dashboard', compact(
            'user', 'profile', 'recentApplications',
            'totalApplications', 'acceptedApplications',
            'interviewApplications', 'latestVacantes'
        ));
    }

    public function profile()
    {
        $user = Auth::user();
        $profile = $user->studentProfile;
        return view('student.profile', compact('user', 'profile'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'program'      => 'required|string|max:150',
            'semester'     => 'nullable|string|max:20',
            'phone'        => 'nullable|string|max:20',
            'about'        => 'nullable|string|max:1000',
            'linkedin'     => 'nullable|url|max:255',
            'student_code' => 'nullable|string|max:30',
            'cv'           => 'nullable|file|mimes:pdf|max:5120', // 5MB máx
        ], [
            'cv.max' => 'El archivo de la hoja de vida excede el tamaño permitido (5MB). No es posible adjuntar el archivo.',
            'cv.mimes' => 'La hoja de vida debe ser un archivo en formato PDF.',
        ]);

        $data = $request->only(['program', 'semester', 'phone', 'about', 'linkedin', 'student_code']);

        // Subir CV si se proporcionó
        if ($request->hasFile('cv')) {
            // Eliminar el anterior si existe
            if ($user->studentProfile->cv_path) {
                Storage::disk('public')->delete($user->studentProfile->cv_path);
            }
            $data['cv_path'] = $request->file('cv')->store('cvs', 'public');
        }

        $user->studentProfile()->updateOrCreate(
            ['user_id' => $user->id],
            $data
        );

        return back()->with('success', 'Perfil actualizado correctamente.');
    }

    public function myApplications()
    {
        $user = Auth::user();
        $applications = Application::where('user_id', $user->id)
            ->with(['jobPosting.company'])
            ->latest()
            ->paginate(10);

        return view('student.applications', compact('applications'));
    }
}
