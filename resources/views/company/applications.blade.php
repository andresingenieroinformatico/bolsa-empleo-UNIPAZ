@extends('layouts.app')
@section('title', 'Postulaciones Recibidas')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0"><i class="bi bi-people text-primary me-2"></i>Postulaciones Recibidas</h4>
        <a href="{{ route('company.dashboard') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i>Dashboard
        </a>
    </div>

    <!-- Filtros -->
    <form method="GET" class="card mb-4">
        <div class="card-body py-3">
            <div class="row g-2">
                <div class="col-md-4">
                    <select name="job" class="form-select form-select-sm">
                        <option value="">Todas las vacantes</option>
                        @foreach($vacantes as $vacante)
                            <option value="{{ $vacante->id }}" @selected(request('job') == $vacante->id)>
                                {{ $vacante->title }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="status" class="form-select form-select-sm">
                        <option value="">Todos los estados</option>
                        <option value="pending"   @selected(request('status') === 'pending')>Pendiente</option>
                        <option value="reviewed"  @selected(request('status') === 'reviewed')>En revisión</option>
                        <option value="interview" @selected(request('status') === 'interview')>Entrevista</option>
                        <option value="accepted"  @selected(request('status') === 'accepted')>Aceptado</option>
                        <option value="rejected"  @selected(request('status') === 'rejected')>No seleccionado</option>
                    </select>
                </div>
                <div class="col-auto d-flex gap-2">
                    <button class="btn btn-primary btn-sm"><i class="bi bi-search me-1"></i>Filtrar</button>
                    <a href="{{ route('company.applications') }}" class="btn btn-outline-secondary btn-sm">Limpiar</a>
                </div>
            </div>
        </div>
    </form>

    <!-- Listado de postulaciones -->
    @forelse($applications as $app)
        <div class="card mb-3 shadow-sm">
            <div class="card-body py-3">
                <div class="row align-items-center">
                    <!-- Estudiante -->
                    <div class="col-md-4">
                        <div class="d-flex align-items-center gap-3">
                            <img src="{{ $app->user->avatar_url }}" class="rounded-circle" width="44" height="44" style="object-fit:cover;">
                            <div>
                                <p class="mb-0 fw-semibold">{{ $app->user->name }}</p>
                                <small class="text-muted">{{ $app->user->email }}</small>
                                @if($app->user->studentProfile)
                                    <br><small class="badge badge-area">{{ $app->user->studentProfile->program }}</small>
                                    @if($app->user->studentProfile->semester)
                                        <small class="text-muted"> · Sem. {{ $app->user->studentProfile->semester }}</small>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Vacante y fecha -->
                    <div class="col-md-3">
                        <p class="mb-0 fw-semibold small">{{ $app->jobPosting->title }}</p>
                        <small class="text-muted">Postulado el {{ $app->created_at->format('d/m/Y') }}</small>
                    </div>

                    <!-- Estado actual -->
                    <div class="col-md-2 text-center">
                        <span class="badge {{ $app->status_badge }} fs-6 px-3 py-2">
                            {{ $app->status_label }}
                        </span>
                    </div>

                    <!-- Acciones -->
                    <div class="col-md-3">
                        <div class="d-flex gap-2 flex-wrap justify-content-md-end">
                            <!-- CV -->
                            @if($app->cv_path)
                                <a href="{{ asset('storage/' . $app->cv_path) }}"
                                   target="_blank" class="btn btn-outline-primary btn-sm">
                                    <i class="bi bi-file-earmark-pdf me-1"></i>Ver CV
                                </a>
                            @endif

                            <!-- Cambiar estado -->
                            <button class="btn btn-outline-secondary btn-sm"
                                    data-bs-toggle="collapse"
                                    data-bs-target="#update-{{ $app->id }}">
                                <i class="bi bi-pencil me-1"></i>Estado
                            </button>
                        </div>

                        <!-- Panel actualizar estado -->
                        <div class="collapse mt-2" id="update-{{ $app->id }}">
                            <form method="POST" action="{{ route('company.applications.update', $app) }}">
                                @csrf
                                @method('PUT')
                                <select name="status" class="form-select form-select-sm mb-2" required>
                                    <option value="pending"   @selected($app->status === 'pending')>Pendiente</option>
                                    <option value="reviewed"  @selected($app->status === 'reviewed')>En revisión</option>
                                    <option value="interview" @selected($app->status === 'interview')>Entrevista programada</option>
                                    <option value="accepted"  @selected($app->status === 'accepted')>Aceptado</option>
                                    <option value="rejected"  @selected($app->status === 'rejected')>No seleccionado</option>
                                </select>
                                <textarea name="company_notes" class="form-control form-control-sm mb-2"
                                          rows="2" placeholder="Nota interna (opcional, no la ve el estudiante)">{{ $app->company_notes }}</textarea>
                                <button type="submit" class="btn btn-primary btn-sm w-100">
                                    <i class="bi bi-check me-1"></i>Guardar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Carta de presentación -->
                @if($app->cover_letter)
                    <div class="mt-3 pt-3 border-top">
                        <small class="text-muted fw-semibold d-block mb-1">
                            <i class="bi bi-chat-quote me-1"></i>Carta de presentación:
                        </small>
                        <p class="small mb-0 text-body fst-italic">"{{ $app->cover_letter }}"</p>
                    </div>
                @endif
            </div>
        </div>
    @empty
        <div class="text-center py-5">
            <i class="bi bi-inbox fs-1 text-muted d-block mb-3"></i>
            <h5 class="text-muted">No hay postulaciones</h5>
            <p class="text-muted small">Aún no has recibido postulaciones o no hay resultados con estos filtros.</p>
        </div>
    @endforelse

    <!-- Paginación -->
    <div class="d-flex justify-content-between align-items-center mt-3">
        <small class="text-muted">Total: {{ $applications->total() }} postulaciones</small>
        {{ $applications->links() }}
    </div>
</div>
@endsection
