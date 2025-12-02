@extends('layouts.app')
@section('title','Doctores')

@section('content')
@php
    $rol = strtolower(Auth::user()->rol->rol ?? '');
@endphp

<div class="container-fluid px-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <h2 class="mb-1">
                        <i class="bi bi-person-badge text-primary me-2"></i>
                        Gestión de Doctores
                    </h2>
                    <p class="text-muted mb-0">
                        <i class="bi bi-people me-1"></i>
                        Personal médico de la clínica
                    </p>
                </div>
                @if($rol === 'admin')
                <div>
                    <a href="{{ route('doctores.create') }}" class="btn btn-primary btn-lg shadow-sm">
                        <i class="bi bi-plus-circle me-2"></i>Nuevo Doctor
                    </a>
                </div>
                @endif
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
                <form method="GET" action="{{ route('doctores.index') }}" class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">
                            <i class="bi bi-star text-primary me-1"></i>Especialidad
                        </label>
                        <select name="filtro_especialidad" class="form-select">
                            <option value="">Todas las especialidades</option>
                            @foreach($especialidades as $esp)
                                <option value="{{ $esp->id_especialidad }}" 
                                    {{ request('filtro_especialidad') == $esp->id_especialidad ? 'selected' : '' }}>
                                    {{ $esp->especialidad }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">
                            <i class="bi bi-search text-primary me-1"></i>Nombre del Doctor
                        </label>
                        <input type="text" 
                               name="filtro_nombre" 
                               class="form-control" 
                               value="{{ request('filtro_nombre') }}" 
                               placeholder="Buscar por nombre...">
                    </div>
                    
                    <div class="col-12">
                        <div class="d-flex gap-2 flex-wrap">
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="bi bi-search me-2"></i>Buscar
                            </button>
                            <a href="{{ route('doctores.index') }}" class="btn btn-outline-secondary px-4">
                                <i class="bi bi-arrow-clockwise me-2"></i>Limpiar Filtros
                            </a>
                            @if(request()->hasAny(['filtro_especialidad', 'filtro_nombre']))
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

    <!-- Tabla de Doctores -->
    @if($doctores->count() > 0)
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white border-bottom py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="bi bi-list-ul text-primary me-2"></i>
                    Lista de Doctores
                    <span class="badge bg-primary ms-2">{{ $doctores->count() }}</span>
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
                                <i class="bi bi-person me-1"></i>Doctor
                            </th>
                            <th class="py-3">
                                <i class="bi bi-star me-1"></i>Especialidad
                            </th>
                            <th class="py-3">
                                <i class="bi bi-telephone me-1"></i>Contacto
                            </th>
                            <th class="py-3">
                                <i class="bi bi-person-circle me-1"></i>Usuario
                            </th>
                            <th class="text-center py-3">
                                <i class="bi bi-gear me-1"></i>Acciones
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($doctores as $d)
                        <tr class="border-bottom">
                            <td class="px-4 fw-semibold">#{{ $d->id_doctor }}</td>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="avatar-circle bg-primary bg-opacity-10">
                                        <i class="bi bi-person-fill text-primary"></i>
                                    </div>
                                    <div>
                                        <div class="fw-semibold">{{ $d->nombre_doctor }}</div>
                                        @if($d->correo)
                                        <small class="text-muted">
                                            <i class="bi bi-envelope me-1"></i>{{ $d->correo }}
                                        </small>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-info bg-opacity-10 text-info px-3 py-2">
                                    <i class="bi bi-star-fill me-1"></i>
                                    {{ $d->especialidad->especialidad }}
                                </span>
                            </td>
                            <td>
                                @if($d->telefono)
                                <div class="d-flex align-items-center gap-2">
                                    <i class="bi bi-telephone-fill text-success"></i>
                                    <span>{{ $d->telefono }}</span>
                                </div>
                                @else
                                <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if($d->usuario)
                                <div class="d-flex align-items-center gap-2">
                                    <i class="bi bi-person-check-fill text-primary"></i>
                                    <span>{{ $d->usuario->usuario }}</span>
                                </div>
                                @else
                                <span class="badge bg-warning">Sin usuario</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex justify-content-center gap-1">
                                    <a href="{{ route('doctores.show', $d->id_doctor) }}" 
                                       class="btn btn-sm btn-outline-info" 
                                       title="Ver detalles"
                                       data-bs-toggle="tooltip">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    @if($rol === 'admin')
                                        <a href="{{ route('doctores.edit', $d->id_doctor) }}" 
                                           class="btn btn-sm btn-outline-primary" 
                                           title="Editar doctor"
                                           data-bs-toggle="tooltip">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form method="POST" 
                                              action="{{ route('doctores.destroy', $d->id_doctor) }}" 
                                              style="display:inline-block;" 
                                              class="delete-form">
                                            @csrf @method('DELETE')
                                            <button type="submit" 
                                                    class="btn btn-sm btn-outline-danger" 
                                                    title="Eliminar doctor"
                                                    data-bs-toggle="tooltip">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    @endif
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
                <i class="bi bi-person-badge text-muted" style="font-size: 4rem;"></i>
            </div>
            <h4 class="text-muted mb-2">No se encontraron doctores</h4>
            <p class="text-muted mb-4">
                @if(request()->hasAny(['filtro_especialidad', 'filtro_nombre']))
                    No hay doctores que coincidan con los filtros aplicados.
                @else
                    Aún no hay doctores registrados en el sistema.
                @endif
            </p>
            <div class="d-flex gap-2 justify-content-center">
                @if(request()->hasAny(['filtro_especialidad', 'filtro_nombre']))
                    <a href="{{ route('doctores.index') }}" class="btn btn-outline-primary">
                        <i class="bi bi-arrow-clockwise me-2"></i>Limpiar Filtros
                    </a>
                @endif
                @if($rol === 'admin')
                    <a href="{{ route('doctores.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-2"></i>Registrar Primer Doctor
                    </a>
                @endif
            </div>
        </div>
    </div>
    @endif
</div>

<style>
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

.card {
    border-radius: 0.5rem;
}

.badge {
    font-weight: 500;
    font-size: 0.85rem;
}

.avatar-circle {
    width: 45px;
    height: 45px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
}

.btn {
    transition: all 0.2s ease;
}

.btn:hover {
    transform: translateY(-1px);
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
            if (confirm('¿Está seguro de eliminar este doctor?\n\nEsta acción no se puede deshacer y afectará las citas asociadas.')) {
                this.submit();
            }
        });
    });
});
</script>
@endsection