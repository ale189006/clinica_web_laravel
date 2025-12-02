@extends('layouts.app')
@section('title','Editar Especialidad')

@section('content')
<div class="container-fluid px-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-1"><i class="bi bi-star-fill text-primary me-2"></i>Editar Especialidad</h2>
                    <p class="text-muted mb-0">Actualice la información de la especialidad</p>
                </div>
                <a href="{{ route('especialidades.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i>Volver
                </a>
            </div>
        </div>
    </div>

    <!-- Formulario -->
    <div class="row justify-content-center">
        <div class="col-lg-10 col-xl-8">
            <form method="POST" action="{{ route('especialidades.update', $especialidad->id_especialidad) }}" id="formEditarEspecialidad">
                @csrf @method('PUT')

                <!-- Card Principal -->
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-primary text-white py-3">
                        <h5 class="mb-0"><i class="bi bi-info-circle me-2"></i>Información de la Especialidad</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">
                            <!-- Especialidad -->
                            <div class="col-12">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-star text-primary me-1"></i>Nombre de la Especialidad
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       name="especialidad" 
                                       class="form-control form-control-lg @error('especialidad') is-invalid @enderror" 
                                       value="{{ old('especialidad', $especialidad->especialidad) }}"
                                       required
                                       maxlength="100"
                                       placeholder="Ej: Cardiología, Dermatología, Pediatría...">
                                @error('especialidad')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Ingrese el nombre completo de la especialidad médica</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Información Adicional -->
                <div class="card shadow-sm border-0 mb-4 bg-light">
                    <div class="card-body p-4">
                        <div class="row">
                            <div class="col-md-6 mb-3 mb-md-0">
                                <h6 class="text-primary mb-2"><i class="bi bi-info-circle me-2"></i>Información Importante</h6>
                                <ul class="small text-muted mb-0 ps-3">
                                    <li>El nombre de la especialidad debe ser único</li>
                                    <li>Máximo 100 caracteres</li>
                                    <li>Actualmente tiene {{ $especialidad->doctors()->count() }} {{ $especialidad->doctors()->count() === 1 ? 'doctor' : 'doctores' }} asociados</li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <h6 class="text-primary mb-2"><i class="bi bi-lightbulb me-2"></i>Sugerencia</h6>
                                <p class="small text-muted mb-0">
                                    Use nombres claros y descriptivos para facilitar la búsqueda y selección por parte de los usuarios.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Botones de Acción -->
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4">
                        <div class="d-flex gap-2 justify-content-end">
                            <a href="{{ route('especialidades.index') }}" class="btn btn-outline-secondary btn-lg px-4">
                                <i class="bi bi-x-circle me-2"></i>Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary btn-lg px-4">
                                <i class="bi bi-check-circle me-2"></i>Actualizar Especialidad
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.form-label {
    margin-bottom: 0.5rem;
}

.form-control:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.15);
}

.card {
    border-radius: 0.5rem;
    transition: transform 0.2s ease;
}

.btn {
    border-radius: 0.375rem;
    transition: all 0.2s ease;
}

.btn:hover {
    transform: translateY(-1px);
}

.text-danger {
    color: #dc3545 !important;
}
</style>
@endsection
