@extends('layouts.app')
@section('title', 'Mis Vacantes')

@push('styles')
<style>
    .panel-card {
        background: #fff;
        border-radius: 14px;
        border: 1px solid #eef0f9;
        box-shadow: 0 1px 6px rgba(0,0,0,.05);
        overflow: hidden;
    }
    .panel-header {
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid #f0f2fb;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .panel-title {
        font-weight: 800;
        font-size: 1.1rem;
        color: #1a1f36;
        margin: 0;
    }
    
    /* Job Card / Table */
    .table th {
        font-size: .72rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .6px;
        color: #9ca3af;
        background: #fafafa;
        border-bottom: 1px solid #eef0f9;
        padding: .9rem 1.5rem;
    }
    .table td { padding: 1.1rem 1.5rem; vertical-align: middle; border-color: #f5f5f7; }
    .table tbody tr:hover { background: #fafbff; }

    .status-pill {
        padding: .25rem .75rem;
        border-radius: 50px;
        font-size: .7rem;
        font-weight: 700;
        text-transform: uppercase;
    }
    .status-active { background: #e6f7ed; color: #00963F; }
    .status-paused { background: #fef3c7; color: #b45309; }
    .status-closed { background: #f3f4f6; color: #6b7280; }

    .btn-action {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: none;
        transition: all .2s;
        text-decoration: none;
    }
    .btn-edit { background: #eef0f9; color: #273475; }
    .btn-edit:hover { background: #273475; color: #fff; }
    .btn-delete { background: #fee2e2; color: #b91c1c; }
    .btn-delete:hover { background: #b91c1c; color: #fff; }

    .pagination-container {
        padding: 1rem 1.5rem;
        border-top: 1px solid #f0f2fb;
        background: #fafafa;
    }
</style>
@endpush

@section('content')
<div class="container py-4">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="fw-bold mb-1">Mis Vacantes</h4>
            <p class="text-muted small mb-0">Gestiona las ofertas laborales publicadas por tu empresa</p>
        </div>
        @if($company->isApproved())
            <a href="{{ route('company.jobs.create') }}" class="btn btn-unipaz d-inline-flex align-items-center gap-2">
                <i class="bi bi-plus-lg"></i> Nueva Vacante
            </a>
        @endif
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert" style="border-radius: 12px;">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="panel-card">
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>Título de la Vacante</th>
                        <th>Área / Modalidad</th>
                        <th>Postulaciones</th>
                        <th>Fecha Límite</th>
                        <th>Estado</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($jobPostings as $job)
                        <tr>
                            <td>
                                <div style="font-weight: 700; color: #1a1f36; font-size: .95rem;">{{ $job->title }}</div>
                                <div class="text-muted small"><i class="bi bi-geo-alt me-1"></i>{{ $job->location }}</div>
                            </td>
                            <td>
                                <div style="font-size: .85rem; color: #4b5563;">{{ $job->area }}</div>
                                <div class="badge bg-light text-dark fw-normal" style="font-size: .7rem;">
                                    {{ ucfirst($job->modality) }}
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="fw-bold" style="color: #273475;">{{ $job->applications_count }}</span>
                                    <span class="text-muted small">Candidatos</span>
                                </div>
                            </td>
                            <td>
                                <div style="font-size: .85rem; color: #4b5563;">
                                    {{ $job->deadline->format('d M, Y') }}
                                </div>
                                @if($job->deadline->isPast())
                                    <span class="text-danger small fw-600">Expirada</span>
                                @endif
                            </td>
                            <td>
                                @php
                                    $statusClass = match($job->status) {
                                        'active' => 'status-active',
                                        'paused' => 'status-paused',
                                        'closed' => 'status-closed',
                                        default => 'status-closed'
                                    };
                                    $statusLabel = match($job->status) {
                                        'active' => 'Activa',
                                        'paused' => 'Pausada',
                                        'closed' => 'Cerrada',
                                        default => $job->status
                                    };
                                @endphp
                                <span class="status-pill {{ $statusClass }}">
                                    {{ $statusLabel }}
                                </span>
                            </td>
                            <td class="text-end">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('company.jobs.edit', $job) }}" class="btn-action btn-edit" title="Editar">
                                        <i class="bi bi-pencil-fill"></i>
                                    </a>
                                    
                                    @if($job->status !== 'closed')
                                        <form action="{{ route('company.jobs.destroy', $job) }}" method="POST" onsubmit="return confirm('¿Estás seguro de que deseas cerrar esta vacante?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-action btn-delete" title="Cerrar Vacante">
                                                <i class="bi bi-x-circle-fill"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <div class="py-4">
                                    <i class="bi bi-briefcase text-muted opacity-25" style="font-size: 3.5rem;"></i>
                                    <h6 class="mt-3 fw-bold text-dark">No has publicado vacantes todavía</h6>
                                    <p class="text-muted small">Comienza a buscar talento publicando tu primera oferta laboral.</p>
                                    @if($company->isApproved())
                                        <a href="{{ route('company.jobs.create') }}" class="btn btn-unipaz mt-2">
                                            Publicar mi primera vacante
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($jobPostings->hasPages())
            <div class="pagination-container">
                {{ $jobPostings->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
