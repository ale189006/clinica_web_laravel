@extends('layouts.app')
@section('title','Especialidades')

@section('content')
<div class="container-fluid px-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <h2 class="mb-1">
                        <i class="bi bi-star text-primary me-2"></i>
                        Gestión de Especialidades
                    </h2>
                    <p class="text-muted mb-0">
                        <i class="bi bi-clock me-1"></i>
                        {{ \Carbon\Carbon::now()->isoFormat('dddd, D [de] MMMM [de] YYYY') }}
                    </p>
                </div>
                <div>
                    <a href="{{ route('especialidades.create') }}" class="btn btn-primary btn-lg shadow-sm">
                        <i class="bi bi-plus-circle me-2"></i>Nueva Especialidad
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Estadísticas Rápidas -->
    <div class="row g-3 mb-4">
        @php
            $totalEspecialidades = \App\Models\Especialidad::count();
            $conDoctores = \App\Models\Especialidad::whereHas('doctors')->count();
            $sinDoctores = $totalEspecialidades - $conDoctores;
        @endphp
        
        <div class="col-lg-4 col-md-6">
            <div class="card border-0 shadow-sm h-100 border-start border-primary border-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small">Total de Especialidades</p>
                            <h3 class="mb-0 fw-bold">{{ $totalEspecialidades }}</h3>
                        </div>
                        <div class="bg-primary bg-opacity-10 p-3 rounded-circle">
                            <i class="bi bi-star text-primary fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-6">
            <div class="card border-0 shadow-sm h-100 border-start border-success border-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small">Con Doctores</p>
                            <h3 class="mb-0 fw-bold">{{ $conDoctores }}</h3>
                        </div>
                        <div class="bg-success bg-opacity-10 p-3 rounded-circle">
                            <i class="bi bi-person-check text-success fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-6">
            <div class="card border-0 shadow-sm h-100 border-start border-warning border-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small">Sin Doctores</p>
                            <h3 class="mb-0 fw-bold">{{ $sinDoctores }}</h3>
                        </div>
                        <div class="bg-warning bg-opacity-10 p-3 rounded-circle">
                            <i class="bi bi-person-x text-warning fs-4"></i>
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
                <form method="GET" action="{{ route('especialidades.index') }}" class="row g-3">
                    <div class="col-lg-6 col-md-6">
                        <label class="form-label fw-semibold">
                            <i class="bi bi-search text-primary me-1"></i>Nombre de Especialidad
                        </label>
                        <input type="text" name="filtro_nombre" class="form-control" 
                               value="{{ request('filtro_nombre') }}" 
                               placeholder="Buscar por nombre...">
                    </div>
                    
                    <div class="col-12">
                        <div class="d-flex gap-2 flex-wrap">
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="bi bi-search me-2"></i>Buscar
                            </button>
                            <a href="{{ route('especialidades.index') }}" class="btn btn-outline-secondary px-4">
                                <i class="bi bi-arrow-clockwise me-2"></i>Limpiar Filtros
                            </a>
                            @if(request()->has('filtro_nombre'))
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

    <!-- Tabla de Especialidades -->
    @if($especialidades->count() > 0)
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white border-bottom py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="bi bi-list-ul text-primary me-2"></i>
                    Lista de Especialidades
                    <span class="badge bg-primary ms-2">{{ $especialidades->count() }}</span>
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
                                <i class="bi bi-star me-1"></i>Especialidad
                            </th>
                            <th class="py-3">
                                <i class="bi bi-people me-1"></i>Doctores Asociados
                            </th>
                            <th class="text-center py-3">
                                <i class="bi bi-gear me-1"></i>Acciones
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($especialidades as $e)
                        <tr class="border-bottom">
                            <td class="px-4 fw-semibold">#{{ $e->id_especialidad }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-star-fill text-warning me-2"></i>
                                    <span class="fw-semibold">{{ $e->especialidad }}</span>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-info">
                                    {{ $e->doctors()->count() }} {{ $e->doctors()->count() === 1 ? 'doctor' : 'doctores' }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex justify-content-center gap-1">
                                    <a href="{{ route('especialidades.edit', $e->id_especialidad) }}" 
                                       class="btn btn-sm btn-outline-primary" 
                                       title="Editar especialidad"
                                       data-bs-toggle="tooltip">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form method="POST" 
                                          action="{{ route('especialidades.destroy', $e->id_especialidad) }}" 
                                          style="display:inline-block;" 
                                          class="delete-form">
                                        @csrf @method('DELETE')
                                        <button type="submit" 
                                                class="btn btn-sm btn-outline-danger" 
                                                title="Eliminar especialidad"
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
            <h4 class="text-muted mb-2">No se encontraron especialidades</h4>
            <p class="text-muted mb-4">
                @if(request()->has('filtro_nombre'))
                    No hay especialidades que coincidan con los filtros aplicados.
                @else
                    Aún no hay especialidades registradas en el sistema.
                @endif
            </p>
            <div class="d-flex gap-2 justify-content-center">
                @if(request()->has('filtro_nombre'))
                    <a href="{{ route('especialidades.index') }}" class="btn btn-outline-primary">
                        <i class="bi bi-arrow-clockwise me-2"></i>Limpiar Filtros
                    </a>
                @endif
                <a href="{{ route('especialidades.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-2"></i>Crear Primera Especialidad
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
            if (confirm('¿Está seguro de eliminar esta especialidad?\n\nEsta acción no se puede deshacer.')) {
                this.submit();
            }
        });
    });
});
</script>
@endsection
