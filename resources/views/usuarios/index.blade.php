@extends('layouts.app')
@section('title','Usuarios')

@section('content')
<div class="container-fluid px-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <h2 class="mb-1">
                        <i class="bi bi-people-fill text-primary me-2"></i>
                        Gestión de Usuarios
                    </h2>
                    <p class="text-muted mb-0">
                        <i class="bi bi-clock me-1"></i>
                        {{ \Carbon\Carbon::now()->isoFormat('dddd, D [de] MMMM [de] YYYY') }}
                    </p>
                </div>
                <div>
                    <a href="{{ route('usuarios.create') }}" class="btn btn-primary btn-lg shadow-sm">
                        <i class="bi bi-plus-circle me-2"></i>Nuevo Usuario
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Estadísticas Rápidas -->
    <div class="row g-3 mb-4">
        @php
            $totalUsuarios = \App\Models\Usuario::count();
            $admins = \App\Models\Usuario::whereHas('rol', function($q) {
                $q->whereRaw('LOWER(rol) = ?', ['admin']);
            })->count();
            $doctores = \App\Models\Usuario::whereHas('rol', function($q) {
                $q->whereRaw('LOWER(rol) = ?', ['doctor']);
            })->count();
            $recepcionistas = \App\Models\Usuario::whereHas('rol', function($q) {
                $q->whereRaw('LOWER(rol) = ?', ['recepcionista']);
            })->count();
        @endphp
        
        <div class="col-lg-3 col-md-6">
            <div class="card border-0 shadow-sm h-100 border-start border-primary border-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small">Total de Usuarios</p>
                            <h3 class="mb-0 fw-bold">{{ $totalUsuarios }}</h3>
                        </div>
                        <div class="bg-primary bg-opacity-10 p-3 rounded-circle">
                            <i class="bi bi-people text-primary fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card border-0 shadow-sm h-100 border-start border-danger border-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small">Administradores</p>
                            <h3 class="mb-0 fw-bold">{{ $admins }}</h3>
                        </div>
                        <div class="bg-danger bg-opacity-10 p-3 rounded-circle">
                            <i class="bi bi-shield-check text-danger fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card border-0 shadow-sm h-100 border-start border-info border-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small">Doctores</p>
                            <h3 class="mb-0 fw-bold">{{ $doctores }}</h3>
                        </div>
                        <div class="bg-info bg-opacity-10 p-3 rounded-circle">
                            <i class="bi bi-person-badge text-info fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card border-0 shadow-sm h-100 border-start border-success border-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small">Recepcionistas</p>
                            <h3 class="mb-0 fw-bold">{{ $recepcionistas }}</h3>
                        </div>
                        <div class="bg-success bg-opacity-10 p-3 rounded-circle">
                            <i class="bi bi-person-workspace text-success fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros de Búsqueda -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white border-bottom py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="bi bi-funnel text-primary me-2"></i>Filtros de Búsqueda
                </h5>
                <button class="btn btn-sm btn-outline-primary" type="button" data-bs-toggle="collapse" data-bs-target="#filtrosCollapse">
                    <i class="bi bi-chevron-down"></i>
                </button>
            </div>
        </div>
        <div class="collapse show" id="filtrosCollapse">
            <div class="card-body p-4">
                <form method="GET" action="{{ route('usuarios.index') }}" class="row g-3">
                    <div class="col-lg-6 col-md-6">
                        <label class="form-label fw-semibold">
                            <i class="bi bi-search text-primary me-1"></i>Usuario o Correo
                        </label>
                        <input type="text" name="filtro_usuario" class="form-control" 
                               value="{{ request('filtro_usuario') }}" 
                               placeholder="Buscar por usuario o correo...">
                    </div>
                    
                    <div class="col-lg-6 col-md-6">
                        <label class="form-label fw-semibold">
                            <i class="bi bi-person-badge text-primary me-1"></i>Rol
                        </label>
                        <select name="filtro_rol" class="form-select">
                            <option value="">Todos los roles</option>
                            @foreach($roles as $rol)
                                <option value="{{ $rol->id_rol }}" 
                                    {{ request('filtro_rol') == $rol->id_rol ? 'selected' : '' }}>
                                    {{ $rol->rol }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="col-12">
                        <div class="d-flex gap-2 flex-wrap">
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="bi bi-search me-2"></i>Buscar
                            </button>
                            <a href="{{ route('usuarios.index') }}" class="btn btn-outline-secondary px-4">
                                <i class="bi bi-arrow-clockwise me-2"></i>Limpiar Filtros
                            </a>
                            @if(request()->hasAny(['filtro_usuario', 'filtro_rol']))
                            <span class="badge bg-primary align-self-center px-3 py-2">
                                <i class="bi bi-funnel-fill me-1"></i>Filtros activos
                            </span>
                            @endif
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Tabla de Usuarios -->
    @if($usuarios->count() > 0)
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white border-bottom py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="bi bi-list-ul text-primary me-2"></i>
                    Lista de Usuarios
                    <span class="badge bg-primary ms-2">{{ $usuarios->count() }}</span>
                </h5>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="px-4 py-3">
                                <i class="bi bi-hash me-1"></i>ID
                            </th>
                            <th class="py-3">
                                <i class="bi bi-person me-1"></i>Usuario
                            </th>
                            <th class="py-3">
                                <i class="bi bi-envelope me-1"></i>Correo
                            </th>
                            <th class="py-3">
                                <i class="bi bi-person-badge me-1"></i>Rol
                            </th>
                            <th class="text-center py-3">
                                <i class="bi bi-gear me-1"></i>Acciones
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($usuarios as $u)
                        <tr class="border-bottom">
                            <td class="px-4 fw-semibold">#{{ $u->id_usuario }}</td>
                            <td class="fw-semibold">{{ $u->usuario }}</td>
                            <td>{{ $u->correo }}</td>
                            <td>
                                @php
                                    $rolNombre = strtolower($u->rol->rol ?? '');
                                    $badgeClass = match($rolNombre) {
                                        'admin' => 'danger',
                                        'doctor' => 'primary',
                                        'recepcionista' => 'success',
                                        default => 'secondary'
                                    };
                                @endphp
                                <span class="badge bg-{{ $badgeClass }} px-3 py-2">
                                    {{ $u->rol->rol ?? '-' }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex justify-content-center gap-1">
                                    <a href="{{ route('usuarios.edit', $u->id_usuario) }}" 
                                       class="btn btn-sm btn-outline-primary" 
                                       title="Editar usuario"
                                       data-bs-toggle="tooltip">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form method="POST" 
                                          action="{{ route('usuarios.destroy', $u->id_usuario) }}" 
                                          style="display:inline-block;" 
                                          class="delete-form">
                                        @csrf @method('DELETE')
                                        <button type="submit" 
                                                class="btn btn-sm btn-outline-danger" 
                                                title="Eliminar usuario"
                                                data-bs-toggle="tooltip">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @else
    <div class="card shadow-sm border-0">
        <div class="card-body text-center py-5">
            <div class="mb-4">
                <i class="bi bi-inbox text-muted" style="font-size: 4rem;"></i>
            </div>
            <h4 class="text-muted mb-2">No se encontraron usuarios</h4>
            <p class="text-muted mb-4">
                @if(request()->hasAny(['filtro_usuario', 'filtro_rol']))
                    No hay usuarios que coincidan con los filtros aplicados.
                @else
                    Aún no hay usuarios registrados en el sistema.
                @endif
            </p>
            <div class="d-flex gap-2 justify-content-center">
                @if(request()->hasAny(['filtro_usuario', 'filtro_rol']))
                    <a href="{{ route('usuarios.index') }}" class="btn btn-outline-primary">
                        <i class="bi bi-arrow-clockwise me-2"></i>Limpiar Filtros
                    </a>
                @endif
                <a href="{{ route('usuarios.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-2"></i>Crear Primer Usuario
                </a>
            </div>
        </div>
    </div>
    @endif
</div>

<style>
.border-4 {
    border-width: 4px !important;
}

.table th {
    font-size: 0.875rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: #6c757d;
}

.table td {
    vertical-align: middle;
}

.table-hover tbody tr:hover {
    background-color: rgba(13, 110, 253, 0.05);
}

.btn {
    transition: all 0.2s ease;
}

.btn:hover {
    transform: translateY(-2px);
}

.card {
    border-radius: 0.5rem;
}

.badge {
    font-weight: 500;
    font-size: 0.8rem;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Inicializar tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
    // Confirmación de eliminación
    const deleteForms = document.querySelectorAll('.delete-form');
    deleteForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            if (confirm('¿Está seguro de eliminar este usuario?\n\nEsta acción no se puede deshacer.')) {
                this.submit();
            }
        });
    });
});
</script>
@endsection
