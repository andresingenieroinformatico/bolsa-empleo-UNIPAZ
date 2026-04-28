@extends('layouts.app')
@section('title', 'Dashboard Estudiante')

@push('styles')
<style>
    .welcome-card {
        background: linear-gradient(135deg, #273475 0%, #1d2659 100%);
        border-radius: 16px;
        border: none;
        position: relative;
        overflow: hidden;
    }
    .welcome-card::before {
        content: '';
        position: absolute;
        top: -30px; right: -30px;
        width: 180px; height: 180px;
        background: rgba(0,150,63,.15);
        border-radius: 50%;
    }
    .welcome-card::after {
        content: '';
        position: absolute;
        bottom: -40px; left: 20%;
        width: 120px; height: 120px;
        background: rgba(255,255,255,.04);
        border-radius: 50%;
    }
    .welcome-card > * { position: relative; z-index: 1; }
    .welcome-avatar {
        width: 66px; height: 66px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid rgba(0,150,63,.5);
    }
    .stat-card {
        border-radius: 12px;
        border: none;
        background: #fff;
        box-shadow: 0 1px 8px rgba(0,0,0,.06);
        padding: 1.25rem;
        transition: box-shadow .2s, transform .2s;
    }
    .stat-card:hover { box-shadow: 0 4px 18px rgba(0,0,0,.1); transform: translateY(-2px); }
    .stat-card .stat-icon {
        width: 44px; height: 44px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        flex-shrink: 0;
    }
    .stat-card .stat-value { font-size: 1.75rem; font-weight: 800; line-height: 1; color: #1a1f36; }
    .stat-card .stat-label { font-size: .78rem; color: #6b7280; margin-top: .2rem; font-weight: 500; }
    .panel-card {
        border-radius: 14px;
        border: 1px solid #eef0f9;
        background: #fff;
        box-shadow: 0 1px 6px rgba(0,0,0,.05);
    }
    .panel-card .panel-header {
        padding: 1rem 1.25rem;
        border-bottom: 1px solid #f0f2fb;
        font-weight: 700;
        font-size: .9rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        color: #1a1f36;
    }
    .panel-card .panel-header i { color: #273475; }
    .app-row {
        padding: .9rem 1.25rem;
        border-bottom: 1px solid #f9fafb;
        transition: background .12s;
    }
    .app-row:last-child { border-bottom: none; }
    .app-row:hover { background: #f8f9ff; }
    .app-title { font-size: .87rem; font-weight: 600; color: #1a1f36; }
    .app-company { font-size: .78rem; color: #6b7280; }
    .job-row {
        padding: .85rem 1.25rem;
        border-bottom: 1px solid #f9fafb;
        transition: background .12s;
    }
    .job-row:last-child { border-bottom: none; }
    .job-row:hover { background: #f8f9ff; }
    .job-logo {
        width: 38px; height: 38px;
        border-radius: 8px;
        object-fit: cover;
        border: 1px solid #eef0f9;
        flex-shrink: 0;
    }
    .job-row-title { font-size: .87rem; font-weight: 600; color: #1a1f36; }
    .job-row-sub { font-size: .76rem; color: #9ca3af; }
    .days-badge {
        font-size: .72rem;
        font-weight: 700;
        background: #fef3c7;
        color: #92400e;
        border-radius: 6px;
        padding: .25rem .55rem;
    }
    .btn-ver {
        font-size: .75rem;
        font-weight: 700;
        background: #eef0f9;
        color: #273475;
        border: none;
        border-radius: 7px;
        padding: .3rem .7rem;
        text-decoration: none;
        transition: background .15s;
    }
    .btn-ver:hover { background: #273475; color: #fff; }
</style>
@endpush

@section('content')
<div class="container py-4">

    {{-- ── Bienvenida ── --}}
    <div class="welcome-card mb-4">
        <div class="card-body py-4 px-4 text-white d-flex align-items-center gap-4">
            <img src="{{ $user->avatar_url }}" class="welcome-avatar" alt="avatar">
            <div class="flex-grow-1">
                <div style="font-size:.72rem; font-weight:600; letter-spacing:1px; text-transform:uppercase; color:rgba(255,255,255,.5); margin-bottom:.2rem;">
                    Estudiante UNIPAZ
                </div>
                <h4 class="fw-bold mb-1">¡Hola, {{ Str::limit(explode(' ', $user->name)[0], 20) }}!</h4>
                <p class="mb-0" style="font-size:.85rem; color:rgba(255,255,255,.65);">
                    <i class="bi bi-envelope me-1"></i>{{ $user->email }}
                    @if($profile && $profile->program && $profile->program !== 'Sin especificar')
                        <span class="mx-2 opacity-40">·</span>
                        <i class="bi bi-mortarboard me-1"></i>{{ $profile->program }}
                    @endif
                </p>
                @if(!$profile || !$profile->program || $profile->program === 'Sin especificar')
                    <a href="{{ route('student.profile') }}"
                        class="d-inline-flex align-items-center gap-1 mt-2"
                        style="background:rgba(0,150,63,.3); border:1px solid rgba(0,150,63,.5); color:#6ee7a8; border-radius:8px; padding:.3rem .8rem; font-size:.8rem; font-weight:600; text-decoration:none; transition:background .15s;">
                        <i class="bi bi-pencil-fill"></i>Completa tu perfil
                    </a>
                @endif
            </div>
            <div class="d-none d-md-block text-end" style="opacity:.15;">
                <i class="bi bi-mortarboard-fill" style="font-size:4rem;"></i>
            </div>
        </div>
    </div>

    {{-- ── Estadísticas ── --}}
    <div class="row g-3 mb-4">
        <div class="col-sm-4">
            <div class="stat-card d-flex align-items-center gap-3"
                style="border-left: 4px solid #273475;">
                <div class="stat-icon" style="background:#eef0f9; color:#273475;">
                    <i class="bi bi-file-earmark-text"></i>
                </div>
                <div>
                    <div class="stat-value">{{ $totalApplications }}</div>
                    <div class="stat-label">Mis postulaciones</div>
                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="stat-card d-flex align-items-center gap-3"
                style="border-left: 4px solid #f59e0b;">
                <div class="stat-icon" style="background:#fef3c7; color:#b45309;">
                    <i class="bi bi-camera-video"></i>
                </div>
                <div>
                    <div class="stat-value">{{ $interviewApplications }}</div>
                    <div class="stat-label">En entrevista</div>
                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="stat-card d-flex align-items-center gap-3"
                style="border-left: 4px solid #00963F;">
                <div class="stat-icon" style="background:#e6f7ed; color:#00963F;">
                    <i class="bi bi-check-circle-fill"></i>
                </div>
                <div>
                    <div class="stat-value">{{ $acceptedApplications }}</div>
                    <div class="stat-label">Aceptadas</div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Paneles ── --}}
    <div class="row g-4">

        {{-- Mis postulaciones --}}
        <div class="col-lg-5">
            <div class="panel-card h-100">
                <div class="panel-header">
                    <span><i class="bi bi-clock-history me-2"></i>Últimas postulaciones</span>
                    <a href="{{ route('student.applications') }}" class="btn-ver">Ver todas</a>
                </div>
                @forelse($recentApplications as $app)
                    <div class="app-row">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <div class="app-title">{{ $app->jobPosting->title }}</div>
                                <div class="app-company">{{ $app->jobPosting->company->company_name }}</div>
                            </div>
                            <span class="badge {{ $app->status_badge }} ms-2" style="font-size:.7rem;">
                                {{ $app->status_label }}
                            </span>
                        </div>
                        <div style="font-size:.74rem; color:#9ca3af; margin-top:.25rem;">
                            <i class="bi bi-calendar3 me-1"></i>{{ $app->created_at->diffForHumans() }}
                        </div>
                    </div>
                @empty
                    <div class="text-center py-5" style="color:#9ca3af;">
                        <i class="bi bi-inbox d-block fs-2 mb-2 opacity-25"></i>
                        <p class="small mb-2">Aún no te has postulado</p>
                        <a href="{{ route('student.jobs') }}"
                        class="btn-ver" style="padding:.4rem .9rem;">
                            Explorar vacantes
                        </a>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Vacantes disponibles --}}
        <div class="col-lg-7">
            <div class="panel-card h-100">
                <div class="panel-header">
                    <span><i class="bi bi-briefcase me-2"></i>Vacantes disponibles</span>
                    <a href="{{ route('student.jobs') }}"
                        class="btn btn-sm"
                        style="background:#273475; color:#fff; border-radius:8px; font-size:.78rem; font-weight:600; padding:.35rem .85rem;">
                        Ver todas
                    </a>
                </div>
                @foreach($latestVacantes as $job)
                    <div class="job-row d-flex align-items-center gap-3">
                        <img src="{{ $job->company->logo_url }}" class="job-logo" alt="logo">
                        <div class="flex-grow-1 min-width-0">
                            <div class="job-row-title">{{ $job->title }}</div>
                            <div class="job-row-sub">
                                {{ $job->company->company_name }}
                                <span class="mx-1">·</span>
                                {{ $job->location }}
                            </div>
                        </div>
                        <div class="d-flex flex-column align-items-end gap-1">
                            <span class="days-badge">{{ $job->remaining_days }}d</span>
                            <a href="{{ route('student.jobs.show', $job) }}" class="btn-ver">Ver</a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

    </div>
</div>
@endsection
