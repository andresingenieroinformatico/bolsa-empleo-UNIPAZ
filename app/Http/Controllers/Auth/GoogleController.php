<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\StudentProfile;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    /**
     * Redirige al usuario a la página de autenticación de Google.
     */
    public function redirect()
    {
        return Socialite::driver('google')
            ->with(['hd' => 'unipaz.edu.co'])
            ->redirect();
    }

    /**
     * Obtiene la información del usuario desde Google y lo autentica.
     */
    public function callback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Google Login Error', [
                'message' => $e->getMessage(),
                'trace'   => $e->getTraceAsString()
            ]);
            return redirect()->route('login')
                ->with('error', 'Error al autenticar con Google. Detalle técnico: ' . $e->getMessage());
        }

        \Illuminate\Support\Facades\Log::info('Google Login Attempt', ['email' => $googleUser->getEmail()]);

        // Validar que sea correo institucional UNIPAZ (Case-insensitive)
        $email = strtolower(trim($googleUser->getEmail()));
        $isInstitutional = str_ends_with($email, '@unipaz.edu.co');

        \Illuminate\Support\Facades\Log::info('Google Domain Check', [
            'email' => $email,
            'is_institutional' => $isInstitutional
        ]);

        if (!$isInstitutional) {
            return redirect()->route('login')
                ->with('error', 'Solo se permite el ingreso con correo institucional @unipaz.edu.co. El correo detectado fue: ' . $email);
        }

        // Buscar o crear el usuario
        $user = User::where('google_id', $googleUser->getId())
                    ->orWhere('email', $googleUser->getEmail())
                    ->first();

        if (!$user) {
            // Nuevo estudiante
            $user = User::create([
                'name'              => $googleUser->getName(),
                'email'             => $googleUser->getEmail(),
                'google_id'         => $googleUser->getId(),
                'avatar'            => $googleUser->getAvatar(),
                'role'              => 'student',
                'email_verified_at' => now(),
            ]);

            // Crear perfil de estudiante vacío
            StudentProfile::create([
                'user_id' => $user->id,
                'program' => 'Sin especificar',
            ]);
        } else {
            // Actualizar datos de Google si ya existe
            $user->update([
                'google_id' => $googleUser->getId(),
                'avatar'    => $googleUser->getAvatar(),
            ]);
        }

        if (!$user->active) {
            return redirect()->route('login')
                ->with('error', 'Tu cuenta ha sido desactivada. Contacta al administrador.');
        }

        Auth::login($user, true);

        return redirect()->intended(route('student.dashboard'));
    }
}
