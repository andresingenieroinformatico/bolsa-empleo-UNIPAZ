<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class CompanyAuthController extends Controller
{
    public function showRegister()
    {
        return view('auth.company-register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name'         => 'required|string|max:255',
            'email'        => [
                'required', 
                'email', 
                'unique:users,email',
                function ($attribute, $value, $fail) {
                    if (str_ends_with(strtolower($value), '@unipaz.edu.co')) {
                        $fail('Los correos institucionales @unipaz.edu.co son exclusivos para estudiantes y deben ingresar por el botón de Google.');
                    }
                }
            ],
            'password'     => 'required|string|min:8|confirmed',
            'company_name' => 'required|string|max:255',
            'nit'          => 'nullable|string|max:20',
            'sector'       => 'required|string|max:100',
            'contact_person' => 'required|string|max:255',
            'phone'        => 'required|string|max:20',
            'address'      => 'nullable|string|max:255',
            'description'  => 'nullable|string|max:1000',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'company',
        ]);

        Company::create([
            'user_id'        => $user->id,
            'company_name'   => $request->company_name,
            'nit'            => $request->nit,
            'sector'         => $request->sector,
            'contact_person' => $request->contact_person,
            'phone'          => $request->phone,
            'address'        => $request->address,
            'description'    => $request->description,
            'status'         => 'pending', // Requiere aprobación del admin
        ]);

        return redirect()->route('login')
            ->with('success', 'Registro exitoso. Tu empresa está en proceso de verificación por parte de UNIPAZ. Recibirás un correo cuando sea aprobada.');
    }

    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $user = Auth::user();

            if (!$user->active) {
                Auth::logout();
                return back()->with('error', 'Tu cuenta ha sido desactivada.');
            }

            $request->session()->regenerate();

            return match ($user->role) {
                'admin'   => redirect()->route('admin.dashboard'),
                'company' => redirect()->route('company.dashboard'),
                'student' => redirect()->route('student.dashboard'),
                default   => redirect('/'),
            };
        }

        return back()->withErrors([
            'email' => 'Las credenciales no son correctas.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home');
    }
}
