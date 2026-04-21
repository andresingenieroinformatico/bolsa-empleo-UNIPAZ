@extends('layouts.app')
@section('title', 'Iniciar sesión')

@push('styles')
<style>
    .login-page {
        min-height: calc(100vh - 150px);
        background: linear-gradient(160deg, #eef0f9 0%, #f4f5f7 50%, #e6f7ed 100%);
        display: flex;
        align-items: center;
        padding: 2.5rem 0;
    }
    .login-card {
        border-radius: 16px;
        border: none;
        box-shadow: 0 4px 32px rgba(39,52,117,.1);
        overflow: hidden;
    }
    .login-card .card-body { padding: 2.25rem; }

    /* Tarjeta estudiante */
    .student-card {
        background: linear-gradient(145deg, #273475, #1d2659);
        color: #fff;
    }
    .student-card .login-icon-wrap {
        width: 60px; height: 60px;
        background: rgba(0,150,63,.25);
        border: 1.5px solid rgba(0,150,63,.4);
        border-radius: 16px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        color: #6ee7a8;
        margin-bottom: 1.25rem;
    }
    .student-card h4 { font-weight: 800; color: #fff; }
    .student-card p { color: rgba(255,255,255,.65); font-size: .85rem; }
    .btn-google-unipaz {
        background: #fff;
        color: #273475;
        border: none;
        border-radius: 10px;
        padding: .75rem 1.25rem;
        font-weight: 700;
        font-size: .95rem;
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: .6rem;
        transition: all .2s;
        text-decoration: none;
    }
    .btn-google-unipaz:hover {
        background: #eef0f9;
        color: #1d2659;
        transform: translateY(-1px);
        box-shadow: 0 6px 20px rgba(0,0,0,.15);
    }
    .domain-tag {
        display: inline-block;
        background: rgba(0,150,63,.2);
        border: 1px solid rgba(0,150,63,.35);
        color: #6ee7a8;
        border-radius: 6px;
        padding: .2rem .6rem;
        font-size: .75rem;
        font-weight: 700;
        letter-spacing: .3px;
        margin-top: .75rem;
    }

    /* Tarjeta empresa */
    .company-card { background: #fff; }
    .company-card .login-icon-wrap {
        width: 60px; height: 60px;
        background: #e6f7ed;
        border: 1.5px solid #b3e6ca;
        border-radius: 16px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        color: #00963F;
        margin-bottom: 1.25rem;
    }
    .company-card h5 { font-weight: 700; color: #1a1f36; }
    .company-card .form-control:focus {
        border-color: #00963F;
        box-shadow: 0 0 0 3px rgba(0,150,63,.12);
    }
    .btn-company-login {
        background: #00963F;
        color: #fff;
        border: none;
        border-radius: 10px;
        padding: .7rem;
        font-weight: 700;
        width: 100%;
        transition: background .18s, transform .12s;
    }
    .btn-company-login:hover { background: #007832; color: #fff; transform: translateY(-1px); }

    /* Divider central */
    .login-or {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 2rem 0;
    }
    .login-or-inner {
        width: 36px; height: 36px;
        background: #fff;
        border: 2px solid #e5e7eb;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: .72rem;
        font-weight: 700;
        color: #9ca3af;
        letter-spacing: .5px;
    }
    @media (min-width: 768px) {
        .login-or { flex-direction: column; height: 100%; padding: 0; }
        .login-or::before, .login-or::after {
            content: '';
            flex: 1;
            width: 1px;
            background: #e5e7eb;
        }
    }
</style>
@endpush

@section('content')
<div class="login-page">
    <div class="container">

        {{-- Header institucional --}}
        <div class="text-center mb-4">
            <img src="{{ asset('images/LOGO-UNIPAZ-200.png') }}"
                 alt="UNIPAZ — Instituto Universitario de la Paz"
                 style="height: 64px; width: auto; margin-bottom: 1.1rem;">
            <h1 style="font-size:1.5rem; font-weight:800; color:#1a1f36;">Bolsa de Empleo</h1>
            <p class="text-muted" style="font-size:.88rem;">Selecciona tu tipo de acceso para continuar</p>
        </div>

        <div class="row justify-content-center g-0 align-items-stretch" style="max-width: 860px; margin: 0 auto;">

            {{-- ── Estudiante ── --}}
            <div class="col-md-5">
                <div class="card login-card student-card h-100">
                    <div class="card-body text-center">
                        <img src="{{ asset('images/Unipaz-Avanza.png') }}"
                             alt="UNIPAZ"
                             style="height: 52px; width: auto; margin-bottom: 1rem; filter: drop-shadow(0 2px 8px rgba(0,0,0,.2));">
                        <h4 class="mb-1">Soy Estudiante</h4>
                        <p class="mb-4">Accede con tu cuenta institucional UNIPAZ a través de Google.</p>

                        <a href="{{ route('auth.google') }}" class="btn-google-unipaz">
                            <svg width="20" height="20" viewBox="0 0 24 24">
                                <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                                <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                                <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                                <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                            </svg>
                            Continuar con Google
                        </a>

                        <div>
                            <span class="domain-tag">@unipaz.edu.co</span>
                        </div>
                        <p style="font-size:.75rem; color:rgba(255,255,255,.4); margin-top:1rem; margin-bottom:0;">
                            Solo se permite acceso con correo institucional verificado.
                        </p>
                    </div>
                </div>
            </div>

            {{-- ── Divisor ── --}}
            <div class="col-md-auto d-flex">
                <div class="login-or mx-3">
                    <span class="login-or-inner">O</span>
                </div>
            </div>

            {{-- ── Empresa / Admin ── --}}
            <div class="col-md-5">
                <div class="card login-card company-card h-100">
                    <div class="card-body">
                        <div class="text-center">
                            <div class="login-icon-wrap">
                                <i class="bi bi-building-fill"></i>
                            </div>
                            <h5 class="mb-1">Acceso con Credenciales</h5>
                            <p class="text-muted mb-4" style="font-size:.84rem;">
                                Inicia sesión con tu correo y contraseña registrados (Empresas, Admin o Estudiantes).
                            </p>
                        </div>

                        <form method="POST" action="{{ route('login') }}">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Correo electrónico</label>
                                <div class="input-group">
                                    <span class="input-group-text border-end-0 bg-white"
                                          style="border-radius:8px 0 0 8px; border:1.5px solid #e5e7eb; border-right:none;">
                                        <i class="bi bi-envelope" style="color:#9ca3af;"></i>
                                    </span>
                                    <input type="email" name="email"
                                           class="form-control border-start-0 @error('email') is-invalid @enderror"
                                           style="border-radius:0 8px 8px 0;"
                                           value="{{ old('email') }}"
                                           placeholder="correo@empresa.com"
                                           required autofocus>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Contraseña</label>
                                <div class="input-group">
                                    <span class="input-group-text border-end-0 bg-white"
                                          style="border-radius:8px 0 0 8px; border:1.5px solid #e5e7eb; border-right:none;">
                                        <i class="bi bi-lock" style="color:#9ca3af;"></i>
                                    </span>
                                    <input type="password" name="password"
                                           class="form-control border-start-0 @error('password') is-invalid @enderror"
                                           style="border-radius:0 8px 8px 0;"
                                           placeholder="••••••••" required>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <div class="form-check mb-0">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember">
                                    <label class="form-check-label" for="remember" style="font-size:.82rem; color:#6b7280;">
                                        Recordarme
                                    </label>
                                </div>
                            </div>
                            <button type="submit" class="btn-company-login">
                                <i class="bi bi-box-arrow-in-right me-2"></i>Ingresar
                            </button>
                        </form>

                        <div class="text-center mt-4 pt-3 border-top">
                            <span class="text-muted" style="font-size:.82rem;">¿Tu empresa aún no está registrada?</span>
                            <br>
                            <a href="{{ route('company.register') }}"
                               class="btn btn-sm mt-2"
                               style="background:#eef0f9; color:#273475; border-radius:8px; font-weight:600; font-size:.82rem;">
                                <i class="bi bi-building-add me-1"></i>Registrar empresa
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        {{-- Nota de seguridad --}}
        <div class="text-center mt-4">
            <small class="text-muted">
                <i class="bi bi-shield-lock me-1" style="color:#00963F;"></i>
                Plataforma segura · Datos protegidos · Instituto Universitario de la Paz — UNIPAZ
            </small>
        </div>

    </div>
</div>
@endsection
