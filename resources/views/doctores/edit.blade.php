@extends('layouts.app')
@section('title','Editar Doctor')

@section('content')
<div class="container-fluid px-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-1"><i class="bi bi-pencil-square text-warning me-2"></i>Editar Doctor</h2>
                    <p class="text-muted mb-0">Actualice la información del Dr. {{ $doctor->nombre_doctor }}</p>
                </div>
                <a href="{{ route('doctores.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i>Volver
                </a>
            </div>
        </div>
    </div>

    <!-- Formulario -->
    <div class="row justify-content-center">
        <div class="col-lg-10 col-xl-8">
            <form method="POST" action="{{ route('doctores.update', $doctor->id_doctor) }}" id="formEditarDoctor">
                @csrf 
                @method('PUT')

                <!-- Card: Información Actual -->
                <div class="card shadow-sm border-0 mb-4 bg-light">
                    <div class="card-body p-3">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <small class="text-muted d-block mb-1">Doctor Actual:</small>
                                <h5 class="mb-0">
                                    <i class="bi bi-person-badge-fill text-primary me-2"></i>
                                    {{ $doctor->nombre_doctor }}
                                </h5>
                            </div>
                            <div class="col-md-4 text-md-end mt-2 mt-md-0">
                                <span class="badge bg-info px-3 py-2">
                                    <i class="bi bi-star-fill me-1"></i>
                                    {{ $doctor->especialidad->especialidad }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card Principal -->
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-warning bg-opacity-10 border-bottom border-warning py-3">
                        <h5 class="mb-0 text-warning-emphasis">
                            <i class="bi bi-pencil me-2"></i>Información Personal
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">
                            <!-- Nombre del Doctor -->
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-person text-warning me-1"></i>Nombre Completo del Doctor
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       name="nombre_doctor" 
                                       class="form-control form-control-lg @error('nombre_doctor') is-invalid @enderror" 
                                       value="{{ old('nombre_doctor', $doctor->nombre_doctor) }}"
                                       required>
                                @error('nombre_doctor')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Especialidad -->
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-star text-warning me-1"></i>Especialidad
                                    <span class="text-danger">*</span>
                                </label>
                                <select name="id_especialidad" 
                                        class="form-select form-select-lg @error('id_especialidad') is-invalid @enderror" 
                                        required>
                                    @foreach($especialidades as $e)
                                    <option value="{{ $e->id_especialidad }}" 
                                        {{ old('id_especialidad', $doctor->id_especialidad) == $e->id_especialidad ? 'selected' : '' }}>
                                        {{ $e->especialidad }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('id_especialidad')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Teléfono -->
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-telephone text-warning me-1"></i>Teléfono
                                </label>
                                <input type="text" 
                                       name="telefono" 
                                       class="form-control form-control-lg @error('telefono') is-invalid @enderror" 
                                       value="{{ old('telefono', $doctor->telefono) }}"
                                       placeholder="Ej: +51 987 654 321">
                                @error('telefono')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Correo -->
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-envelope text-warning me-1"></i>Correo Electrónico
                                </label>
                                <input type="email" 
                                       name="correo" 
                                       class="form-control form-control-lg @error('correo') is-invalid @enderror" 
                                       value="{{ old('correo', $doctor->correo) }}"
                                       placeholder="Ej: doctor@clinica.com">
                                @error('correo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card de Usuario -->
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-info text-white py-3">
                        <h5 class="mb-0"><i class="bi bi-person-circle me-2"></i>Vinculación de Usuario</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-person-check text-info me-1"></i>Usuario del Sistema
                                    <span class="text-danger">*</span>
                                </label>
                                <select name="id_usuario" 
                                        class="form-select form-select-lg @error('id_usuario') is-invalid @enderror" 
                                        required>
                                    @foreach($usuarios as $u)
                                    <option value="{{ $u->id_usuario }}" 
                                        {{ old('id_usuario', $doctor->id_usuario) == $u->id_usuario ? 'selected' : '' }}>
                                        {{ $u->usuario }} - {{ $u->correo }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('id_usuario')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">
                                    <i class="bi bi-info-circle me-1"></i>
                                    Usuario actual: <strong>{{ $doctor->usuario->usuario ?? 'No asignado' }}</strong>
                                </small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Estadísticas del Doctor -->
                <div class="card shadow-sm border-0 mb-4 border-start border-4 border-info">
                    <div class="card-body p-4">
                        <h6 class="text-info mb-3">
                            <i class="bi bi-graph-up me-2"></i>Estadísticas del Doctor
                        </h6>
                        <div class="row text-center">
                            <div class="col-md-3 col-6 mb-3 mb-md-0">
                                <div class="stat-box">
                                    <i class="bi bi-calendar-check text-primary fs-3"></i>
                                    <h4 class="mt-2 mb-0">{{ $doctor->citas->count() }}</h4>
                                    <small class="text-muted">Total Citas</small>
                                </div>
                            </div>
                            <div class="col-md-3 col-6 mb-3 mb-md-0">
                                <div class="stat-box">
                                    <i class="bi bi-clock-history text-warning fs-3"></i>
                                    <h4 class="mt-2 mb-0">{{ $doctor->citas->filter(fn($c) => strtolower($c->estado->estado ?? '') === 'pendiente')->count() }}</h4>
                                    <small class="text-muted">Pendientes</small>
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="stat-box">
                                    <i class="bi bi-check-circle text-success fs-3"></i>
                                    <h4 class="mt-2 mb-0">{{ $doctor->citas->filter(fn($c) => strtolower($c->estado->estado ?? '') === 'atendida')->count() }}</h4>
                                    <small class="text-muted">Atendidas</small>
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="stat-box">
                                    <i class="bi bi-file-medical text-info fs-3"></i>
                                    <h4 class="mt-2 mb-0">{{ $doctor->citas->whereNotNull('historial')->count() }}</h4>
                                    <small class="text-muted">Historiales</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Botones de Acción -->
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4">
                        <div class="d-flex gap-2 justify-content-between align-items-center flex-wrap">
                            <a href="{{ route('doctores.show', $doctor->id_doctor) }}" class="btn btn-outline-info">
                                <i class="bi bi-eye me-2"></i>Ver Detalles
                            </a>
                            <div class="d-flex gap-2">
                                <a href="{{ route('doctores.index') }}" class="btn btn-outline-secondary btn-lg px-4">
                                    <i class="bi bi-x-circle me-2"></i>Cancelar
                                </a>
                                <button type="submit" class="btn btn-warning btn-lg px-4 text-white">
                                    <i class="bi bi-check-circle me-2"></i>Guardar Cambios
                                </button>
                            </div>
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
    border-color: #ffc107;
    box-shadow: 0 0 0 0.25rem rgba(255, 193, 7, 0.15);
}

.card {
    border-radius: 0.5rem;
}

.btn {
    border-radius: 0.375rem;
    transition: all 0.2s ease;
}

.btn:hover {
    transform: translateY(-1px);
}

.border-4 {
    border-width: 4px !important;
}

.stat-box {
    padding: 1rem;
    border-radius: 10px;
    transition: all 0.3s ease;
}

.stat-box:hover {
    background: rgba(102, 126, 234, 0.05);
    transform: translateY(-3px);
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('formEditarDoctor');
    
    form.addEventListener('submit', function(e) {
        if (!confirm('¿Está seguro de guardar los cambios realizados?')) {
            e.preventDefault();
        }
    });
});
</script>
@endsection