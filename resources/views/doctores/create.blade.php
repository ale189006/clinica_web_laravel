@extends('layouts.app')
@section('title','Nuevo Doctor')

@section('content')
<div class="container-fluid px-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-1"><i class="bi bi-person-badge-fill text-primary me-2"></i>Registrar Nuevo Doctor</h2>
                    <p class="text-muted mb-0">Complete la información del doctor para agregarlo al sistema</p>
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
            <form method="POST" action="{{ route('doctores.store') }}" id="formNuevoDoctor">
                @csrf

                <!-- Card Principal -->
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-primary text-white py-3">
                        <h5 class="mb-0"><i class="bi bi-person-lines-fill me-2"></i>Información Personal</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">
                            <!-- Nombre del Doctor -->
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-person text-primary me-1"></i>Nombre Completo del Doctor
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       name="nombre_doctor" 
                                       class="form-control form-control-lg @error('nombre_doctor') is-invalid @enderror" 
                                       value="{{ old('nombre_doctor') }}"
                                       placeholder="Ej: Dr. Juan Pérez García"
                                       required>
                                @error('nombre_doctor')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Especialidad -->
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-star text-primary me-1"></i>Especialidad
                                    <span class="text-danger">*</span>
                                </label>
                                <select name="id_especialidad" 
                                        class="form-select form-select-lg @error('id_especialidad') is-invalid @enderror" 
                                        required>
                                    <option value="" selected disabled>Seleccione una especialidad...</option>
                                    @foreach($especialidades as $e)
                                    <option value="{{ $e->id_especialidad }}" {{ old('id_especialidad') == $e->id_especialidad ? 'selected' : '' }}>
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
                                    <i class="bi bi-telephone text-primary me-1"></i>Teléfono
                                </label>
                                <input type="text" 
                                       name="telefono" 
                                       class="form-control form-control-lg @error('telefono') is-invalid @enderror" 
                                       value="{{ old('telefono') }}"
                                       placeholder="Ej: +51 987 654 321">
                                @error('telefono')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Opcional - Número de contacto del doctor</small>
                            </div>

                            <!-- Correo -->
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-envelope text-primary me-1"></i>Correo Electrónico
                                </label>
                                <input type="email" 
                                       name="correo" 
                                       class="form-control form-control-lg @error('correo') is-invalid @enderror" 
                                       value="{{ old('correo') }}"
                                       placeholder="Ej: doctor@clinica.com">
                                @error('correo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Opcional - Correo profesional del doctor</small>
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
                                @if($usuarios->count() > 0)
                                    <select name="id_usuario" 
                                            class="form-select form-select-lg @error('id_usuario') is-invalid @enderror" 
                                            required>
                                        <option value="" selected disabled>Seleccione un usuario con rol doctor...</option>
                                        @foreach($usuarios as $u)
                                        <option value="{{ $u->id_usuario }}" {{ old('id_usuario') == $u->id_usuario ? 'selected' : '' }}>
                                            {{ $u->usuario }} ({{ $u->correo }}) - Rol: {{ $u->rol->rol ?? 'N/A' }}
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('id_usuario')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">
                                        <i class="bi bi-info-circle me-1"></i>
                                        Este usuario podrá acceder al sistema con el rol de doctor
                                    </small>
                                @else
                                    <div class="alert alert-warning d-flex align-items-start">
                                        <i class="bi bi-exclamation-triangle me-3 fs-4"></i>
                                        <div class="flex-grow-1">
                                            <strong class="d-block mb-2">No hay usuarios disponibles</strong>
                                            <p class="mb-3">
                                                Para crear un doctor, primero debe registrar un usuario con el rol <strong>"doctor"</strong>. 
                                                Los usuarios con rol "doctor" que ya están vinculados a un doctor no aparecerán en esta lista.
                                            </p>
                                            <p class="small text-muted mb-3">
                                                <i class="bi bi-info-circle me-1"></i>
                                                <strong>Nota:</strong> Asegúrese de que el rol "doctor" exista en el sistema y que haya creado usuarios con ese rol específico.
                                            </p>
                                            <div class="d-flex gap-2 flex-wrap">
                                                <a href="{{ route('usuarios.create') }}" class="btn btn-primary">
                                                    <i class="bi bi-plus-circle me-1"></i>Crear Nuevo Usuario con Rol Doctor
                                                </a>
                                                <a href="{{ route('usuarios.index') }}" class="btn btn-outline-secondary">
                                                    <i class="bi bi-list-ul me-1"></i>Ver Usuarios Existentes
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endif
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
                                    <li>Los campos marcados con (*) son obligatorios</li>
                                    <li>El usuario debe tener rol de "Doctor"</li>
                                    <li>La especialidad debe estar registrada previamente</li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <h6 class="text-primary mb-2"><i class="bi bi-shield-check me-2"></i>Permisos</h6>
                                <p class="small text-muted mb-0">
                                    El doctor tendrá acceso a:
                                    <br>• Gestionar sus citas
                                    <br>• Ver y editar historiales clínicos
                                    <br>• Consultar información de pacientes
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Botones de Acción -->
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4">
                        <div class="d-flex gap-2 justify-content-end">
                            <a href="{{ route('doctores.index') }}" class="btn btn-outline-secondary btn-lg px-4">
                                <i class="bi bi-x-circle me-2"></i>Cancelar
                            </a>
                            @if($usuarios->count() > 0)
                                <button type="submit" class="btn btn-primary btn-lg px-4">
                                    <i class="bi bi-check-circle me-2"></i>Registrar Doctor
                                </button>
                            @else
                                <button type="button" class="btn btn-primary btn-lg px-4" disabled>
                                    <i class="bi bi-exclamation-triangle me-2"></i>No hay usuarios disponibles
                                </button>
                            @endif
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
}

.btn {
    border-radius: 0.375rem;
    transition: all 0.2s ease;
}

.btn:hover {
    transform: translateY(-1px);
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('formNuevoDoctor');
    
    if (form) {
        form.addEventListener('submit', function(e) {
            const nombreDoctor = document.querySelector('input[name="nombre_doctor"]');
            const idUsuario = document.querySelector('select[name="id_usuario"]');
            
            if (!nombreDoctor || nombreDoctor.value.trim().length < 3) {
                e.preventDefault();
                alert('El nombre del doctor debe tener al menos 3 caracteres');
                return false;
            }
            
            if (!idUsuario || !idUsuario.value) {
                e.preventDefault();
                alert('Debe seleccionar un usuario para vincular con el doctor');
                return false;
            }
        });
    }
});
</script>
@endsection