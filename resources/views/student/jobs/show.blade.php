@extends('layouts.app')
@section('title', $jobPosting->title)

@section('content')
<div class="container py-4">
    <div class="row g-4">

        <!-- Detalle de la vacante -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body p-4">
                    <!-- Encabezado -->
                    <div class="d-flex align-items-center gap-4 mb-4">
                        <img src="{{ $jobPosting->company->logo_url }}" class="rounded border"
                             width="80" height="80" style="object-fit:cover;">
                        <div>
                            <h3 class="fw-bold mb-1">{{ $jobPosting->title }}</h3>
                            <p class="text-muted mb-2">{{ $jobPosting->company->company_name }}</p>
                            <div class="d-flex flex-wrap gap-2">
                                <span class="badge bg-primary">{{ $jobPosting->area }}</span>
                                <span class="badge {{ $jobPosting->modality_badge }} text-white">
                                    {{ match($jobPosting->modality) { 'onsite' => 'Presencial', 'remote' => 'Remoto', 'hybrid' => 'Híbrido', default => $jobPosting->modality } }}
                                </span>
                                <span class="badge bg-secondary">{{ $jobPosting->contract_type }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Info rápida -->
                    <div class="row g-3 mb-4 p-3 bg-light rounded-3">
                        <div class="col-6 col-md-3 text-center">
                            <i class="bi bi-geo-alt text-primary fs-4"></i>
                            <p class="small mb-0 fw-semibold">{{ $jobPosting->location }}</p>
                            <small class="text-muted">Ubicación</small>
                        </div>
                        <div class="col-6 col-md-3 text-center">
                            <i class="bi bi-cash-stack text-success fs-4"></i>
                            <p class="small mb-0 fw-semibold">{{ $jobPosting->salary_label }}</p>
                            <small class="text-muted">Salario</small>
                        </div>
                        <div class="col-6 col-md-3 text-center">
                            <i class="bi bi-people text-info fs-4"></i>
                            <p class="small mb-0 fw-semibold">{{ $jobPosting->positions }} plaza(s)</p>
                            <small class="text-muted">Vacantes</small>
                        </div>
                        <div class="col-6 col-md-3 text-center">
                            <i class="bi bi-calendar-x text-danger fs-4"></i>
                            <p class="small mb-0 fw-semibold">{{ $jobPosting->deadline->format('d/m/Y') }}</p>
                            <small class="text-muted">Fecha límite</small>
                        </div>
                    </div>

                    <h5 class="fw-bold">Descripción del cargo</h5>
                    <p class="text-muted">{!! nl2br(e($jobPosting->description)) !!}</p>

                    @if($jobPosting->responsibilities)
                        <h5 class="fw-bold mt-3">Responsabilidades</h5>
                        <p class="text-muted">{!! nl2br(e($jobPosting->responsibilities)) !!}</p>
                    @endif

                    <h5 class="fw-bold mt-3">Requisitos</h5>
                    <p class="text-muted">{!! nl2br(e($jobPosting->requirements)) !!}</p>

                    @if($jobPosting->programs_targeted)
                        <div class="alert alert-info mt-3">
                            <i class="bi bi-mortarboard me-2"></i>
                            <strong>Programas académicos preferidos:</strong> {{ $jobPosting->programs_targeted }}
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Panel de postulación -->
        <div class="col-lg-4">
            <div class="card sticky-top" style="top: 80px;">
                <div class="card-body p-4">
                    @if($hasApplied)
                        <div class="text-center py-3">
                            <i class="bi bi-check-circle-fill text-success fs-1"></i>
                            <h5 class="mt-3 fw-bold">¡Ya te postulaste!</h5>
                            <p class="text-muted small">Hemos enviado tu información a la empresa. Revisa el estado en "Mis postulaciones".</p>
                            <a href="{{ route('student.applications') }}" class="btn btn-outline-success w-100">
                                Ver mis postulaciones
                            </a>
                        </div>
                    @elseif($jobPosting->remaining_days === 0)
                        <div class="alert alert-danger text-center">
                            <i class="bi bi-x-circle fs-2 d-block mb-2"></i>
                            Esta vacante ha vencido
                        </div>
                    @else
                        <h5 class="fw-bold mb-3">Postularme a esta vacante</h5>
                        <div class="alert alert-warning small">
                            <i class="bi bi-clock me-1"></i>
                            Tiempo restante: <strong>{{ $jobPosting->remaining_days }} días</strong>
                        </div>

                        <form method="POST" action="{{ route('student.jobs.apply', $jobPosting) }}" enctype="multipart/form-data">
                            @csrf

                            <div class="mb-3">
                                <label class="form-label small fw-semibold">Carta de presentación (opcional)</label>
                                <textarea name="cover_letter" class="form-control @error('cover_letter') is-invalid @enderror"
                                          rows="4" placeholder="Cuéntale a la empresa por qué eres el candidato ideal...">{{ old('cover_letter') }}</textarea>
                                @error('cover_letter')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            @if(auth()->user()->studentProfile?->cv_path)
                                <div class="mb-3">
                                    <div class="alert alert-success small py-2">
                                        <i class="bi bi-file-earmark-pdf me-1"></i>
                                        Se usará tu hoja de vida del perfil. Puedes subir una diferente:
                                    </div>
                                </div>
                            @endif

                            <div class="mb-3">
                                <label class="form-label small fw-semibold">
                                    Hoja de vida (PDF)
                                    @if($jobPosting->requires_cv) <span class="text-danger">*</span> @endif
                                </label>
                                <input type="file" name="cv" id="cv_apply" accept=".pdf"
                                       class="form-control @error('cv') is-invalid @enderror"
                                       {{ $jobPosting->requires_cv && !auth()->user()->studentProfile?->cv_path ? 'required' : '' }}>
                                <small class="text-muted">PDF máximo 5MB</small>
                                @error('cv')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="bi bi-send me-2"></i>Enviar postulación
                                </button>
                            </div>
                        </form>
                    @endif

                    <hr>
                    <div class="text-center">
                        <small class="text-muted">
                            <i class="bi bi-shield-check me-1 text-success"></i>
                            Tu información está protegida y solo será compartida con esta empresa.
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
const cvInput = document.getElementById('cv_apply');
if (cvInput) {
    cvInput.addEventListener('change', function() {
        const file = this.files[0];
        if (file) {
            const fileSize = file.size / 1024 / 1024; // en MB
            if (fileSize > 5) {
                alert('El archivo excede el tamaño permitido (5MB). No es posible adjuntar el archivo.');
                this.value = ''; // Limpiar el input
            }
        }
    });
}
</script>
@endpush
@endsection
