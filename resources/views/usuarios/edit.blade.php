@extends('layouts.app')
@section('title','Editar Usuario')

@section('content')
<div class="container-fluid px-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-1"><i class="bi bi-person-gear text-primary me-2"></i>Editar Usuario</h2>
                    <p class="text-muted mb-0">Actualice la información del usuario</p>
                </div>
                <a href="{{ route('usuarios.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i>Volver
                </a>
            </div>
        </div>
    </div>

    <!-- Formulario -->
    <div class="row justify-content-center">
        <div class="col-lg-8 col-xl-6">
            <form method="POST" action="{{ route('usuarios.update', $usuario->id_usuario) }}" id="formEditarUsuario">
                @csrf @method('PUT')

                <!-- Card Principal -->
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-primary text-white py-3">
                        <h5 class="mb-0"><i class="bi bi-person-vcard me-2"></i>Datos del Usuario</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-person-fill text-primary me-1"></i>Nombre de Usuario
                                <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="usuario" class="form-control form-control-lg @error('usuario') is-invalid @enderror" value="{{ old('usuario', $usuario->usuario) }}" required>
                            @error('usuario')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-envelope-at text-primary me-1"></i>Correo Electrónico
                                <span class="text-danger">*</span>
                            </label>
                            <input type="email" name="correo" class="form-control form-control-lg @error('correo') is-invalid @enderror" value="{{ old('correo', $usuario->correo) }}" required>
                            @error('correo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-key text-primary me-1"></i>Nueva Contraseña
                            </label>
                            <input type="password" name="password" class="form-control form-control-lg @error('password') is-invalid @enderror">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Deje en blanco si no desea cambiar la contraseña</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-person-gear text-primary me-1"></i>Rol
                                <span class="text-danger">*</span>
                            </label>
                            <select name="id_rol" class="form-select form-select-lg @error('id_rol') is-invalid @enderror" required>
                                <option value="" selected disabled>Seleccione un rol...</option>
                                @foreach($roles as $r)
                                <option value="{{ $r->id_rol }}" {{ old('id_rol', $usuario->id_rol) == $r->id_rol ? 'selected' : '' }}>
                                    {{ $r->rol }}
                                </option>
                                @endforeach
                            </select>
                            @error('id_rol')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Botones de Acción -->
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4">
                        <div class="d-flex gap-2 justify-content-end">
                            <a href="{{ route('usuarios.index') }}" class="btn btn-outline-secondary btn-lg px-4">
                                <i class="bi bi-x-circle me-2"></i>Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary btn-lg px-4">
                                <i class="bi bi-check-circle me-2"></i>Actualizar Usuario
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
