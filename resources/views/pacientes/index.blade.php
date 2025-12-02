@extends('layouts.app')
@section('title','Pacientes')

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
                        <i class="bi bi-people text-primary me-2"></i>
                        Gestión de Pacientes @if($rol === 'doctor') - Mis Pacientes @endif
                    </h2>
                    <p class="text-muted mb-0">
                        <i class="bi bi-clock me-1"></i>
                        {{ \Carbon\Carbon::now()->isoFormat('dddd, D [de] MMMM [de] YYYY') }}
                    </p>
                </div>
                @if($rol !== 'doctor')
                <div>
                    <a href="{{ route('pacientes.create') }}" class="btn btn-primary btn-lg shadow-sm">
                        <i class="bi bi-plus-circle me-2"></i>Nuevo Paciente
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Estadísticas Rápidas -->
    <div class="row g-3 mb-4">
        @php
            $totalPacientes = \App\Models\Paciente::count();
            $conCitas = \App\Models\Paciente::whereHas('citas')->count();
            $sinCitas = $totalPacientes - $conCitas;
            $hoy = \App\Models\Paciente::whereDate('created_at', today())->count();
        @endphp
        
        <div class="col-lg-3 col-md-6">
            <div class="card border-0 shadow-sm h-100 border-start border-primary border-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small">Total de Pacientes</p>
                            <h3 class="mb-0 fw-bold">{{ $totalPacientes }}</h3>
                        </div>
                        <div class="bg-primary bg-opacity-10 p-3 rounded-circle">
                            <i class="bi bi-people text-primary fs-4"></i>
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
                            <p class="text-muted mb-1 small">Con Citas</p>
                            <h3 class="mb-0 fw-bold">{{ $conCitas }}</h3>
                        </div>
                        <div class="bg-success bg-opacity-10 p-3 rounded-circle">
                            <i class="bi bi-calendar-check text-success fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card border-0 shadow-sm h-100 border-start border-warning border-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small">Sin Citas</p>
                            <h3 class="mb-0 fw-bold">{{ $sinCitas }}</h3>
                        </div>
                        <div class="bg-warning bg-opacity-10 p-3 rounded-circle">
                            <i class="bi bi-person-x text-warning fs-4"></i>
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
                            <p class="text-muted mb-1 small">Registrados Hoy</p>
                            <h3 class="mb-0 fw-bold">{{ $hoy }}</h3>
                        </div>
                        <div class="bg-info bg-opacity-10 p-3 rounded-circle">
                            <i class="bi bi-calendar-day text-info fs-4"></i>
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
                <form method="GET" action="{{ route('pacientes.index') }}" class="row g-3">
                    <div class="col-lg-6 col-md-6">
                        <label class="form-label fw-semibold">
                            <i class="bi bi-person text-primary me-1"></i>Nombre (Nombres o Apellidos)
                        </label>
                        <input type="text" name="filtro_nombre" class="form-control" 
                               value="{{ request('filtro_nombre') }}" 
                               placeholder="Buscar por nombre...">
                    </div>
                    
                    <div class="col-lg-6 col-md-6">
                        <label class="form-label fw-semibold">
                            <i class="bi bi-card-text text-primary me-1"></i>DNI
                        </label>
                        <input type="text" name="filtro_dni" class="form-control" 
                               value="{{ request('filtro_dni') }}" 
                               placeholder="Buscar por DNI...">
                    </div>
                    
                    <div class="col-12">
                        <div class="d-flex gap-2 flex-wrap">
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="bi bi-search me-2"></i>Buscar
                            </button>
                            <a href="{{ route('pacientes.index') }}" class="btn btn-outline-secondary px-4">
                                <i class="bi bi-arrow-clockwise me-2"></i>Limpiar Filtros
                            </a>
                            @if(request()->hasAny(['filtro_nombre', 'filtro_dni']))
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

    <!-- Tabla de Pacientes -->
    @if($pacientes->count() > 0)
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white border-bottom py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="bi bi-list-ul text-primary me-2"></i>
                    Lista de Pacientes
                    <span class="badge bg-primary ms-2">{{ $pacientes->count() }}</span>
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
                                <i class="bi bi-card-text me-1"></i>DNI
                            </th>
                            <th class="py-3">
                                <i class="bi bi-person me-1"></i>Nombres
                            </th>
                            <th class="py-3">
                                <i class="bi bi-person me-1"></i>Apellidos
                            </th>
                            <th class="py-3">
                                <i class="bi bi-calendar me-1"></i>Edad
                            </th>
                            <th class="py-3">
                                <i class="bi bi-telephone me-1"></i>Teléfono
                            </th>
                            <th class="py-3">
                                <i class="bi bi-geo-alt me-1"></i>Dirección
                            </th>
                            <th class="text-center py-3">
                                <i class="bi bi-gear me-1"></i>Acciones
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pacientes as $p)
                        <tr class="border-bottom">
                            <td class="px-4 fw-semibold">#{{ $p->id_paciente }}</td>
                            <td>{{ $p->dni ?? '-' }}</td>
                            <td class="fw-semibold">{{ $p->nombres }}</td>
                            <td>{{ $p->apellidos ?? '-' }}</td>
                            <td>
                                @if($p->edad)
                                    {{ $p->edad }} años
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>{{ $p->telefono ?? '-' }}</td>
                            <td>
                                <span class="d-inline-block text-truncate" style="max-width: 200px;" 
                                      title="{{ $p->direccion ?? '-' }}">
                                    {{ $p->direccion ?? '-' }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex justify-content-center gap-1">
                                    <a href="{{ route('pacientes.edit', $p->id_paciente) }}" 
                                       class="btn btn-sm btn-outline-primary" 
                                       title="Editar paciente"
                                       data-bs-toggle="tooltip">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    @if($rol !== 'recepcionista')
                                        <form method="POST" 
                                              action="{{ route('pacientes.destroy', $p->id_paciente) }}" 
                                              style="display:inline-block;" 
                                              class="delete-form">
                                            @csrf @method('DELETE')
                                            <button type="submit" 
                                                    class="btn btn-sm btn-outline-danger" 
                                                    title="Eliminar paciente"
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
                <i class="bi bi-inbox text-muted" style="font-size: 4rem;"></i>
            </div>
            <h4 class="text-muted mb-2">No se encontraron pacientes</h4>
            <p class="text-muted mb-4">
                @if(request()->hasAny(['filtro_nombre', 'filtro_dni']))
                    No hay pacientes que coincidan con los filtros aplicados.
                @else
                    Aún no hay pacientes registrados en el sistema.
                @endif
            </p>
            <div class="d-flex gap-2 justify-content-center">
                @if(request()->hasAny(['filtro_nombre', 'filtro_dni']))
                    <a href="{{ route('pacientes.index') }}" class="btn btn-outline-primary">
                        <i class="bi bi-arrow-clockwise me-2"></i>Limpiar Filtros
                    </a>
                @endif
                @if($rol !== 'doctor')
                    <a href="{{ route('pacientes.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-2"></i>Crear Primer Paciente
                    </a>
                @endif
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
            if (confirm('¿Está seguro de eliminar este paciente?\n\nEsta acción no se puede deshacer.')) {
                this.submit();
            }
        });
    });
});
</script>
@endsection
