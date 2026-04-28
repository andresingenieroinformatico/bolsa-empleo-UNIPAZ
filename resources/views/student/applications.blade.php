@extends('layouts.app')
@section('title', 'Mis Postulaciones')

@section('content')
<div class="container py-4">
    <h2 class="fw-bold mb-4"><i class="bi bi-file-earmark-check text-primary me-2"></i>Mis Postulaciones</h2>

    @if($applications->isEmpty())
        <div class="card text-center py-5">
            <div class="card-body">
                <i class="bi bi-inbox fs-1 text-muted opacity-25"></i>
                <h5 class="mt-3 text-muted">Aún no te has postulado a ninguna vacante</h5>
                <a href="{{ route('student.jobs') }}" class="btn btn-primary mt-2">
                    <i class="bi bi-search me-2"></i>Explorar vacantes
                </a>
            </div>
        </div>
    @else
        <div class="card">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Empresa</th>
                            <th>Cargo</th>
                            <th>Modalidad</th>
                            <th>Postulado</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($applications as $app)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <img src="{{ $app->jobPosting->company->logo_url }}" class="rounded"
                                            width="36" height="36" style="object-fit:cover;">
                                        <small class="fw-semibold">{{ $app->jobPosting->company->company_name }}</small>
                                    </div>
                                </td>
                                <td>
                                    <p class="mb-0 fw-semibold small">{{ $app->jobPosting->title }}</p>
                                    <small class="text-muted">{{ $app->jobPosting->area }}</small>
                                </td>
                                <td>
                                    <span class="badge {{ $app->jobPosting->modality_badge }} text-white small">
                                        {{ match($app->jobPosting->modality) { 'onsite' => 'Presencial', 'remote' => 'Remoto', 'hybrid' => 'Híbrido', default => $app->jobPosting->modality } }}
                                    </span>
                                </td>
                                <td>
                                    <small class="text-muted">{{ $app->created_at->format('d/m/Y') }}</small>
                                </td>
                                <td>
                                    <span class="badge {{ $app->status_badge }}">{{ $app->status_label }}</span>
                                    @if($app->status === 'interview')
                                        <br><small class="text-warning"><i class="bi bi-bell-fill"></i> La empresa te contactará</small>
                                    @elseif($app->status === 'accepted')
                                        <br><small class="text-success"><i class="bi bi-check-circle-fill"></i> ¡Felicitaciones!</small>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('student.jobs.show', $app->jobPosting) }}" class="btn btn-outline-primary btn-sm">
                                        <i class="bi bi-eye"></i> Ver vacante
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-3 d-flex justify-content-center">
            {{ $applications->links() }}
        </div>
    @endif
</div>
@endsection
