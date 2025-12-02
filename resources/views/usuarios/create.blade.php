@extends('layouts.app')
@section('title','Nuevo Usuario')

@section('content')
<div class="container-fluid px-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-1">
                        <i class="bi bi-person-plus text-primary me-2"></i>Nuevo Usuario
                    </h2>
                    <p class="text-muted mb-0">Registre un nuevo usuario en el sistema</p>
                </div>
                <a href="{{ route('usuarios.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i>Volver
                </a>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <form method="POST" action="{{ route('usuarios.store') }}" id="formUsuario">
                @csrf

                <div class="card shadow-sm border-0">
                    <div class="card-header bg-primary text-white py-3">
                        <h5 class="mb-0">
                            <i class="bi bi-person-lines-fill me-2"></i>Información del Usuario
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-person text-primary me-1"></i>Usuario
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       name="usuario" 
                                       class="form-control @error('usuario') is-invalid @enderror" 
                                       value="{{ old('usuario') }}" 
                                       required
                                       placeholder="Nombre de usuario">
                                @error('usuario')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-envelope text-primary me-1"></i>Correo Electrónico
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="email" 
                                       name="correo" 
                                       class="form-control @error('correo') is-invalid @enderror" 
                                       value="{{ old('correo') }}" 
                                       required
                                       placeholder="correo@ejemplo.com">
                                @error('correo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-lock text-primary me-1"></i>Contraseña
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="password" 
                                       name="password" 
                                       class="form-control @error('password') is-invalid @enderror" 
                                       required
                                       minlength="6"
                                       placeholder="Mínimo 6 caracteres">
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Mínimo 6 caracteres</small>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-shield-check text-primary me-1"></i>Rol
                                    <span class="text-danger">*</span>
                                </label>
                                <select name="id_rol" 
                                        class="form-select @error('id_rol') is-invalid @enderror" 
                                        id="selectRol"
                                        required>
                                    <option value="" selected disabled>Seleccione un rol...</option>
                                    @foreach($roles as $r)
                                    <option value="{{ $r->id_rol }}" {{ old('id_rol') == $r->id_rol ? 'selected' : '' }}>
                                        {{ ucfirst($r->rol) }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('id_rol')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Mensaje informativo cuando se selecciona rol doctor -->
                        <div id="infoRolDoctor" class="alert alert-info mt-3 d-none">
                            <i class="bi bi-info-circle me-2"></i>
                            <strong>Información importante:</strong>
                            <p class="mb-1 mt-2">
                                Al crear un usuario con rol "Doctor", podrás vincularlo posteriormente a un doctor en el módulo de Doctores.
                                El usuario con rol doctor podrá acceder al sistema y gestionar sus citas e historiales clínicos.
                            </p>
                            <a href="{{ route('doctores.index') }}" class="btn btn-sm btn-outline-primary mt-2">
                                <i class="bi bi-person-badge me-1"></i>Ir a Gestión de Doctores
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm border-0 mt-4">
                    <div class="card-body p-4">
                        <div class="d-flex gap-2 justify-content-end">
                            <a href="{{ route('usuarios.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-x-circle me-2"></i>Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle me-2"></i>Guardar Usuario
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectRol = document.getElementById('selectRol');
    const infoRolDoctor = document.getElementById('infoRolDoctor');
    
    if (selectRol && infoRolDoctor) {
        // Mostrar información cuando se selecciona rol doctor
        selectRol.addEventListener('change', function() {
            const selectedText = this.options[this.selectedIndex].text.toLowerCase();
            
            if (selectedText.includes('doctor')) {
                infoRolDoctor.classList.remove('d-none');
            } else {
                infoRolDoctor.classList.add('d-none');
            }
        });
        
        // Verificar si ya hay un valor seleccionado (por ejemplo, si hay error de validación)
        if (selectRol.value) {
            const selectedText = selectRol.options[selectRol.selectedIndex].text.toLowerCase();
            if (selectedText.includes('doctor')) {
                infoRolDoctor.classList.remove('d-none');
            }
        }
    }
});
</script>
@endsection
