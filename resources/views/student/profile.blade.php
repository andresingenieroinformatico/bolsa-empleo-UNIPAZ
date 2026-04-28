@extends('layouts.app')
@section('title', 'Mi Perfil')

@section('content')
<div class="container py-4">
    <div class="row g-4">
        <div class="col-lg-4">
            <div class="card text-center">
                <div class="card-body py-4">
                    <img src="{{ $user->avatar_url }}" class="rounded-circle border border-3 border-primary mb-3"
                        width="100" height="100" style="object-fit:cover;">
                    <h5 class="fw-bold">{{ $user->name }}</h5>
                    <p class="text-muted small mb-1">{{ $user->email }}</p>
                    @if($profile)
                        <span class="badge bg-primary">{{ $profile->program ?? 'Sin programa' }}</span>
                        @if($profile->semester)
                            <span class="badge bg-secondary ms-1">Semestre {{ $profile->semester }}</span>
                        @endif
                        @if($profile->cv_path)
                            <div class="mt-3">
                                <a href="{{ asset('storage/' . $profile->cv_path) }}" target="_blank"
                                    class="btn btn-outline-danger btn-sm w-100">
                                    <i class="bi bi-file-earmark-pdf me-1"></i>Ver mi hoja de vida
                                </a>
                            </div>
                        @endif
                    @endif
                    @if($profile && $profile->linkedin)
                        <div class="mt-2">
                            <a href="{{ $profile->linkedin }}" target="_blank" class="btn btn-outline-primary btn-sm">
                                <i class="bi bi-linkedin me-1"></i>LinkedIn
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card">
                <div class="card-header bg-transparent fw-bold">
                    <i class="bi bi-pencil-square me-2 text-primary"></i>Editar mi perfil
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('student.profile.update') }}" enctype="multipart/form-data">
                        @csrf @method('PUT')

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Programa académico *</label>
                                <input type="text" name="program" class="form-control @error('program') is-invalid @enderror"
                                       value="{{ old('program', $profile?->program) }}"
                                       placeholder="Ej: Ingeniería de Sistemas" required>
                                @error('program')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-3">
                                <label class="form-label small fw-semibold">Semestre</label>
                                <select name="semester" class="form-select">
                                    <option value="">Seleccionar</option>
                                    @for($i = 1; $i <= 10; $i++)
                                        <option value="{{ $i }}" @selected(old('semester', $profile?->semester) == $i)>
                                            {{ $i }}° semestre
                                        </option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label small fw-semibold">Código estudiantil</label>
                                <input type="text" name="student_code" class="form-control"
                                       value="{{ old('student_code', $profile?->student_code) }}"
                                       placeholder="U2024001">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Teléfono de contacto</label>
                                <input type="text" name="phone" class="form-control"
                                       value="{{ old('phone', $profile?->phone) }}"
                                       placeholder="+57 300 000 0000">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">LinkedIn (opcional)</label>
                                <input type="url" name="linkedin" class="form-control @error('linkedin') is-invalid @enderror"
                                       value="{{ old('linkedin', $profile?->linkedin) }}"
                                       placeholder="https://linkedin.com/in/tu-perfil">
                                @error('linkedin')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-12">
                                <label class="form-label small fw-semibold">Sobre mí</label>
                                <textarea name="about" class="form-control" rows="3"
                                          placeholder="Cuéntanos un poco sobre ti, tus habilidades y objetivos profesionales...">{{ old('about', $profile?->about) }}</textarea>
                            </div>
                            <div class="col-12">
                                <label class="form-label small fw-semibold">
                                    Hoja de vida (PDF)
                                    @if($profile?->cv_path)
                                        <span class="text-success small ms-1"><i class="bi bi-check-circle"></i> Tienes una cargada</span>
                                    @endif
                                </label>
                                <input type="file" name="cv" id="cv_input" accept=".pdf"
                                       class="form-control @error('cv') is-invalid @enderror">
                                <small class="text-muted">PDF máximo 5MB. Reemplazará la anterior si subes una nueva.</small>
                                @error('cv')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary" id="submit_btn">
                                <i class="bi bi-check-lg me-2"></i>Guardar cambios
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.getElementById('cv_input').addEventListener('change', function() {
    const file = this.files[0];
    if (file) {
        const fileSize = file.size / 1024 / 1024; // en MB
        if (fileSize > 5) {
            alert('El archivo excede el tamaño permitido (5MB). No es posible adjuntar el archivo.');
            this.value = ''; // Limpiar el input
        }
    }
});
</script>
@endpush
@endsection
