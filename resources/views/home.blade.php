@extends('layouts.app')
@section('title', 'Inicio')

@push('styles')
<style>
    /* ── Hero ── */
    .hero-section {
        background: linear-gradient(135deg, #273475 0%, #1d2659 60%, #162050 100%);
        position: relative;
        overflow: hidden;
        padding: 5rem 0 4rem;
    }
    .hero-section::before {
        content: '';
        position: absolute;
        top: -60px; right: -80px;
        width: 420px; height: 420px;
        background: rgba(0,150,63,.12);
        border-radius: 50%;
    }
    .hero-section::after {
        content: '';
        position: absolute;
        bottom: -80px; left: -60px;
        width: 300px; height: 300px;
        background: rgba(255,255,255,.04);
        border-radius: 50%;
    }
    .hero-section .container { position: relative; z-index: 1; }

    .hero-badge {
        display: inline-flex;
        align-items: center;
        gap: .5rem;
        background: rgba(0,150,63,.25);
        border: 1px solid rgba(0,150,63,.45);
        color: #6ee7a8;
        border-radius: 20px;
        padding: .35rem 1rem;
        font-size: .82rem;
        font-weight: 600;
        letter-spacing: .3px;
    }
    .hero-badge .dot {
        width: 7px; height: 7px;
        background: #00963F;
        border-radius: 50%;
        animation: pulse-dot 1.8s infinite;
    }
    @keyframes pulse-dot {
        0%, 100% { opacity: 1; transform: scale(1); }
        50%       { opacity: .5; transform: scale(.85); }
    }

    .hero-title {
        font-size: clamp(2rem, 4.5vw, 3.2rem);
        font-weight: 800;
        line-height: 1.15;
        color: #fff;
        letter-spacing: -.5px;
    }
    .hero-title .highlight { color: #4ade80; }

    .hero-divider {
        width: 48px; height: 4px;
        background: #00963F;
        border-radius: 2px;
        margin: 1.2rem 0;
    }

    .hero-lead {
        font-size: 1.05rem;
        color: rgba(255,255,255,.72);
        line-height: 1.65;
        max-width: 540px;
    }

    /* ── Stat chips en hero ── */
    .stat-chip {
        background: rgba(255,255,255,.08);
        border: 1px solid rgba(255,255,255,.12);
        border-radius: 12px;
        padding: 1rem 1.5rem;
        text-align: center;
        min-width: 120px;
        backdrop-filter: blur(4px);
    }
    .stat-chip .stat-number {
        font-size: 1.9rem;
        font-weight: 800;
        color: #fff;
        line-height: 1;
    }
    .stat-chip .stat-number span { color: #4ade80; }
    .stat-chip .stat-label {
        font-size: .75rem;
        color: rgba(255,255,255,.6);
        margin-top: .25rem;
        text-transform: uppercase;
        letter-spacing: .5px;
    }

    /* ── Botones hero ── */
    .btn-hero-primary {
        background: #00963F;
        color: #fff;
        border: none;
        border-radius: 10px;
        padding: .8rem 1.8rem;
        font-weight: 700;
        font-size: 1rem;
        transition: all .2s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: .5rem;
    }
    .btn-hero-primary:hover {
        background: #007832;
        color: #fff;
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(0,150,63,.35);
    }
    .btn-hero-secondary {
        background: transparent;
        color: rgba(255,255,255,.9);
        border: 1.5px solid rgba(255,255,255,.35);
        border-radius: 10px;
        padding: .8rem 1.8rem;
        font-weight: 600;
        font-size: 1rem;
        transition: all .2s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: .5rem;
    }
    .btn-hero-secondary:hover {
        background: rgba(255,255,255,.1);
        border-color: rgba(255,255,255,.65);
        color: #fff;
    }

    /* ── Wave separator ── */
    .wave-sep {
        line-height: 0;
        background: #273475;
    }
    .wave-sep svg { display: block; }

    /* ── Sección de vacantes ── */
    .section-jobs { padding: 3.5rem 0; }
    .section-header { margin-bottom: 2rem; }
    .section-header .section-tag {
        font-size: .75rem;
        font-weight: 700;
        letter-spacing: 1.5px;
        text-transform: uppercase;
        color: #00963F;
        margin-bottom: .5rem;
    }
    .section-header h2 {
        font-size: 1.75rem;
        font-weight: 800;
        color: #1a1f36;
        line-height: 1.2;
    }

    /* ── Job cards ── */
    .job-card {
        border: 1px solid #eef0f9 !important;
        border-radius: 14px;
        background: #fff;
        transition: all .22s ease;
        cursor: default;
        overflow: hidden;
    }
    .job-card:hover {
        border-color: #273475 !important;
        transform: translateY(-3px);
        box-shadow: 0 12px 32px rgba(39,52,117,.12) !important;
    }
    .job-card .card-body { padding: 1.25rem; }
    .job-card .company-logo {
        width: 46px; height: 46px;
        border-radius: 10px;
        object-fit: cover;
        border: 1.5px solid #eef0f9;
        background: #f4f5f7;
        flex-shrink: 0;
    }
    .job-card .job-title {
        font-size: .93rem;
        font-weight: 700;
        color: #1a1f36;
        line-height: 1.3;
        margin-bottom: .15rem;
    }
    .job-card .company-name {
        font-size: .78rem;
        color: #6b7280;
        font-weight: 400;
    }
    .job-card .badge-modality {
        font-size: .72rem;
        font-weight: 600;
        padding: .3rem .65rem;
        border-radius: 6px;
    }
    .job-card .badge-location {
        font-size: .72rem;
        background: #f4f5f7;
        color: #4b5563;
        padding: .3rem .65rem;
        border-radius: 6px;
        font-weight: 500;
    }
    .job-card .badge-days {
        font-size: .72rem;
        background: #fef3c7;
        color: #92400e;
        padding: .3rem .65rem;
        border-radius: 6px;
        font-weight: 600;
    }
    .job-card .job-desc {
        font-size: .8rem;
        color: #6b7280;
        line-height: 1.55;
        margin-top: .75rem;
        margin-bottom: 0;
    }
    .job-card .salary-label {
        font-size: .82rem;
        font-weight: 700;
        color: #00963F;
    }

    /* ── Card de acceso bloqueado ── */
    .login-prompt-card {
        border: 2px dashed #c7d2fe !important;
        background: #f8f9ff;
        border-radius: 14px;
        text-align: center;
        padding: 2.5rem 1.5rem;
    }

    /* ── CTA empresa ── */
    .cta-empresa {
        background: linear-gradient(135deg, #273475 0%, #00963F 100%);
        border-radius: 16px;
        padding: 3rem 2.5rem;
        color: #fff;
        position: relative;
        overflow: hidden;
    }
    .cta-empresa::before {
        content: '';
        position: absolute;
        top: -40px; right: -40px;
        width: 200px; height: 200px;
        background: rgba(255,255,255,.06);
        border-radius: 50%;
    }
    .cta-empresa::after {
        content: '';
        position: absolute;
        bottom: -50px; left: 30px;
        width: 140px; height: 140px;
        background: rgba(255,255,255,.04);
        border-radius: 50%;
    }
    .cta-empresa > * { position: relative; z-index: 1; }

    /* ── How it works ── */
    .how-step {
        text-align: center;
        padding: 1.5rem 1rem;
    }
    .how-step .step-icon {
        width: 64px; height: 64px;
        border-radius: 16px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 1.6rem;
        margin-bottom: 1rem;
    }
    .how-step .step-number {
        font-size: .7rem;
        font-weight: 700;
        letter-spacing: 1px;
        text-transform: uppercase;
        color: #00963F;
        margin-bottom: .3rem;
    }
    .how-step h5 { font-size: .95rem; font-weight: 700; color: #1a1f36; }
    .how-step p { font-size: .82rem; color: #6b7280; }
</style>
@endpush

@section('content')

{{-- ═══════════════════════════════════════════ --}}
{{-- HERO                                        --}}
{{-- ═══════════════════════════════════════════ --}}
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center g-4">
            <div class="col-lg-7">
                <img src="{{ asset('images/LogoWhite_.png') }}"
                    alt="UNIPAZ"
                    style="height: 75px; width: auto; margin-bottom: 1.5rem; display: block;">
                <div class="hero-badge mb-3">
                    <span class="dot"></span>
                    Plataforma oficial · UNIPAZ Barrancabermeja
                </div>
                <h1 class="hero-title">
                    Conectamos el talento<br>
                    universitario con las<br>
                    <span class="highlight">mejores oportunidades</span>
                </h1>
                <div class="hero-divider"></div>
                <p class="hero-lead mb-4">
                    La bolsa de empleo institucional del Instituto Universitario de la Paz. Postúlate a vacantes reales con empresas de la región del Magdalena Medio.
                </p>
                <div class="d-flex flex-wrap gap-3">
                    <a href="{{ route('login') }}" class="btn-hero-primary">
                        <i class="bi bi-mortarboard-fill"></i>
                        Ingresar a la plataforma
                    </a>
                    <a href="{{ route('login') }}" class="btn-hero-secondary">
                        <i class="bi bi-building"></i>
                        Soy empresa
                    </a>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="d-flex flex-wrap justify-content-center justify-content-lg-end gap-3 mt-4 mt-lg-0">
                    <div class="stat-chip">
                        <div class="stat-number">{{ $totalJobs }}<span>+</span></div>
                        <div class="stat-label">Vacantes activas</div>
                    </div>
                    <div class="stat-chip">
                        <div class="stat-number">{{ $totalCompanies }}</div>
                        <div class="stat-label">Empresas aliadas</div>
                    </div>
                    <div class="stat-chip">
                        <div class="stat-number">3<span>K+</span></div>
                        <div class="stat-label">Estudiantes activos</div>
                    </div>
                    <div class="stat-chip" style="width:100%; max-width:280px;">
                        <div class="d-flex align-items-center gap-2 justify-content-center">
                            <i class="bi bi-shield-check" style="color:#4ade80; font-size:1.1rem;"></i>
                            <span style="color:rgba(255,255,255,.8); font-size:.82rem; font-weight:500;">
                                Plataforma institucional verificada
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Wave --}}
<div class="wave-sep">
    <svg viewBox="0 0 1440 48" xmlns="http://www.w3.org/2000/svg">
        <path d="M0,32 C360,0 1080,64 1440,32 L1440,48 L0,48 Z" fill="#f4f5f7"/>
    </svg>
</div>

{{-- ═══════════════════════════════════════════ --}}
{{-- CÓMO FUNCIONA                               --}}
{{-- ═══════════════════════════════════════════ --}}
<section style="background:#fff; padding: 3rem 0;">
    <div class="container">
        <div class="section-header text-center">
            <div class="section-tag">¿Cómo funciona?</div>
            <h2>Simple, rápido y gratuito</h2>
        </div>
        <div class="row g-3 justify-content-center">
            <div class="col-sm-6 col-lg-3">
                <div class="how-step">
                    <div class="step-icon" style="background:#eef0f9;">
                        <i class="bi bi-google" style="color:#273475;"></i>
                    </div>
                    <div class="step-number">Paso 01</div>
                    <h5>Ingresa con tu correo</h5>
                    <p>Usa tu cuenta institucional <strong>@unipaz.edu.co</strong> para acceder de forma segura.</p>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="how-step">
                    <div class="step-icon" style="background:#e6f7ed;">
                        <i class="bi bi-person-badge" style="color:#00963F;"></i>
                    </div>
                    <div class="step-number">Paso 02</div>
                    <h5>Completa tu perfil</h5>
                    <p>Agrega tu programa académico, semestre, CV y habilidades para destacar.</p>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="how-step">
                    <div class="step-icon" style="background:#eef0f9;">
                        <i class="bi bi-search" style="color:#273475;"></i>
                    </div>
                    <div class="step-number">Paso 03</div>
                    <h5>Explora vacantes</h5>
                    <p>Filtra por área, modalidad y ubicación para encontrar tu oportunidad ideal.</p>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="how-step">
                    <div class="step-icon" style="background:#e6f7ed;">
                        <i class="bi bi-send-check" style="color:#00963F;"></i>
                    </div>
                    <div class="step-number">Paso 04</div>
                    <h5>Postúlate</h5>
                    <p>Envía tu postulación con un clic y haz seguimiento del proceso en tiempo real.</p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ═══════════════════════════════════════════ --}}
{{-- VACANTES RECIENTES                          --}}
{{-- ═══════════════════════════════════════════ --}}
<section class="section-jobs">
    <div class="container">
        <div class="d-flex justify-content-between align-items-end section-header mb-0">
            <div>
                <div class="section-tag">Oportunidades laborales</div>
                <h2>Vacantes recientes</h2>
            </div>
            <a href="{{ route('login') }}" class="btn btn-sm d-flex align-items-center gap-1"
               style="background:#eef0f9; color:#273475; border-radius:8px; font-weight:600; font-size:.82rem;">
                Ver todas <i class="bi bi-arrow-right"></i>
            </a>
        </div>

        <div class="row g-3 mt-1">
            @forelse($latestJobs as $job)
                <div class="col-md-6 col-lg-4">
                    <div class="card job-card h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-start gap-3 mb-3">
                                <img src="{{ $job->company->logo_url }}"
                                     class="company-logo"
                                     alt="{{ $job->company->company_name }}">
                                <div class="flex-grow-1 min-width-0">
                                    <div class="job-title">{{ $job->title }}</div>
                                    <div class="company-name">{{ $job->company->company_name }}</div>
                                </div>
                            </div>

                            <div class="d-flex flex-wrap gap-1 mb-2">
                                <span class="badge-location">
                                    <i class="bi bi-geo-alt me-1"></i>{{ $job->location }}
                                </span>
                                <span class="badge-modality {{ $job->modality_badge }} text-white">
                                    {{ ucfirst($job->modality) }}
                                </span>
                                <span class="badge-days">
                                    <i class="bi bi-clock me-1"></i>{{ $job->remaining_days }}d restantes
                                </span>
                            </div>

                            <p class="job-desc">{{ Str::limit($job->description, 95) }}</p>
                        </div>
                        <div class="card-footer bg-transparent border-0 d-flex justify-content-between align-items-center pt-0 px-3 pb-3">
                            <span class="salary-label">
                                <i class="bi bi-currency-dollar"></i>{{ $job->salary_label }}
                            </span>
                            <a href="{{ route('student.jobs.show', $job->id) }}"
                                class="btn btn-sm"
                                style="background:#eef0f9; color:#273475; border-radius:7px; font-weight:600; font-size:.78rem;">
                                Ver detalles
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="text-center py-5" style="color:#9ca3af;">
                        <i class="bi bi-briefcase fs-1 d-block mb-2 opacity-25"></i>
                        <p class="mb-0">No hay vacantes disponibles en este momento.</p>
                    </div>
                </div>
            @endforelse
        </div>

        {{-- Prompt de acceso --}}
        <div class="login-prompt-card mt-4">
            <i class="bi bi-lock-fill fs-2 mb-2" style="color:#a5b4fc;"></i>
            <h6 class="fw-bold" style="color:#273475;">¿Quieres ver todas las vacantes?</h6>
            <p class="text-muted small mb-3">Ingresa con tu correo institucional UNIPAZ para acceder al catálogo completo de oportunidades.</p>
            <a href="{{ route('login') }}" class="btn-hero-primary" style="font-size:.9rem; padding:.6rem 1.4rem;">
                <i class="bi bi-box-arrow-in-right"></i> Ingresar a la plataforma
            </a>
        </div>

        {{-- ─── CTA Empresa ─── --}}
        <div class="cta-empresa mt-5">
            <div class="row align-items-center g-3">
                <div class="col-md-8">
                    <div style="font-size:.75rem; font-weight:700; letter-spacing:1.2px; text-transform:uppercase; color:rgba(255,255,255,.6); margin-bottom:.4rem;">
                        Para empresas y microempresas
                    </div>
                    <h3 class="fw-bold mb-2" style="font-size:1.55rem;">¿Buscas talento universitario?</h3>
                    <p style="color:rgba(255,255,255,.75); font-size:.9rem; margin-bottom:0;">
                        Publica tus vacantes <strong>de forma gratuita</strong> y conecta con los mejores estudiantes y egresados del Magdalena Medio.
                    </p>
                </div>
                <div class="col-md-4 text-md-end">
                    <a href="{{ route('company.register') }}"
                        class="btn btn-light btn-lg px-4 fw-bold"
                        style="border-radius:10px; color:#273475;">
                        <i class="bi bi-building-add me-2"></i>Registrar mi empresa
                    </a>
                    <div class="mt-2" style="font-size:.75rem; color:rgba(255,255,255,.55);">
                        <i class="bi bi-check-circle me-1"></i>Sin costo · Aprobación en 24h
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<-- bloque para estudiantes -->

<-- fin de bloque  -->
@endsection
