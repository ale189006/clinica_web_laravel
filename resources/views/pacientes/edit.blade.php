@extends('layouts.app')
@section('title','Editar Paciente')

@section('content')
<div class="container-fluid px-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-1"><i class="bi bi-person-gear text-primary me-2"></i>Editar Paciente</h2>
                    <p class="text-muted mb-0">Actualice la información del paciente</p>
                </div>
                <a href="{{ route('pacientes.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i>Volver
                </a>
            </div>
        </div>
    </div>

    <!-- Formulario -->
    <div class="row justify-content-center">
        <div class="col-lg-10 col-xl-8">
            <form method="POST" action="{{ route('pacientes.update', $paciente->id_paciente) }}" id="formEditarPaciente">
                @csrf @method('PUT')

                <!-- Card Principal -->
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-primary text-white py-3">
                        <h5 class="mb-0"><i class="bi bi-info-circle me-2"></i>Información del Paciente</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">
                            <!-- DNI -->
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-card-text text-primary me-1"></i>DNI
                                </label>
                                <input type="text" 
                                       name="dni" 
                                       class="form-control form-control-lg @error('dni') is-invalid @enderror" 
                                       value="{{ old('dni', $paciente->dni) }}"
                                       maxlength="15"
                                       placeholder="Ingrese el DNI">
                                @error('dni')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Opcional: Documento Nacional de Identidad</small>
                            </div>

                            <!-- Edad -->
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-calendar text-primary me-1"></i>Edad
                                </label>
                                <input type="number" 
                                       name="edad" 
                                       class="form-control form-control-lg @error('edad') is-invalid @enderror" 
                                       value="{{ old('edad', $paciente->edad) }}"
                                       min="0"
                                       max="120"
                                       placeholder="Ingrese la edad">
                                @error('edad')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Opcional: Edad en años</small>
                            </div>

                            <!-- Nombres -->
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-person text-primary me-1"></i>Nombres
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       name="nombres" 
                                       class="form-control form-control-lg @error('nombres') is-invalid @enderror" 
                                       value="{{ old('nombres', $paciente->nombres) }}"
                                       required
                                       maxlength="150"
                                       placeholder="Ingrese los nombres">
                                @error('nombres')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Apellidos -->
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-person text-primary me-1"></i>Apellidos
                                </label>
                                <input type="text" 
                                       name="apellidos" 
                                       class="form-control form-control-lg @error('apellidos') is-invalid @enderror" 
                                       value="{{ old('apellidos', $paciente->apellidos) }}"
                                       maxlength="150"
                                       placeholder="Ingrese los apellidos">
                                @error('apellidos')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Teléfono -->
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-telephone text-primary me-1"></i>Teléfono
                                </label>
                                <input type="text" 
                                       name="telefono" 
                                       class="form-control form-control-lg @error('telefono') is-invalid @enderror" 
                                       value="{{ old('telefono', $paciente->telefono) }}"
                                       maxlength="20"
                                       placeholder="Ingrese el teléfono">
                                @error('telefono')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Opcional: Número de contacto</small>
                            </div>

                            <!-- Dirección -->
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-geo-alt text-primary me-1"></i>Dirección
                                </label>
                                <textarea name="direccion" 
                                          class="form-control @error('direccion') is-invalid @enderror" 
                                          rows="3"
                                          maxlength="200"
                                          placeholder="Ingrese la dirección">{{ old('direccion', $paciente->direccion) }}</textarea>
                                @error('direccion')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Opcional: Dirección de residencia</small>
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
                                    <li>Los campos marcados con * son obligatorios</li>
                                    <li>El DNI debe ser único si se proporciona</li>
                                    <li>La edad debe estar entre 0 y 120 años</li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <h6 class="text-primary mb-2"><i class="bi bi-shield-check me-2"></i>Privacidad</h6>
                                <p class="small text-muted mb-0">
                                    Toda la información del paciente será tratada con confidencialidad y solo será accesible por personal autorizado.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Botones de Acción -->
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4">
                        <div class="d-flex gap-2 justify-content-end">
                            <a href="{{ route('pacientes.index') }}" class="btn btn-outline-secondary btn-lg px-4">
                                <i class="bi bi-x-circle me-2"></i>Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary btn-lg px-4">
                                <i class="bi bi-check-circle me-2"></i>Actualizar Paciente
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

.form-control:focus,
.form-select:focus {
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
