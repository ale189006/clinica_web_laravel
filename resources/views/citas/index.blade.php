@extends('layouts.app')
@section('title','Citas')

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
                        <i class="bi bi-calendar-check text-primary me-2"></i>
                        Gestión de Citas @if($rol === 'doctor') - Mis Citas @endif
                    </h2>
                    <p class="text-muted mb-0">
                        <i class="bi bi-clock me-1"></i>
                        {{ \Carbon\Carbon::now()->isoFormat('dddd, D [de] MMMM [de] YYYY') }}
                    </p>
                </div>
                @if($rol !== 'doctor')
                <div>
                    <a href="{{ route('citas.create') }}" class="btn btn-primary btn-lg shadow-sm">
                        <i class="bi bi-plus-circle me-2"></i>Nueva Cita
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Estadísticas Rápidas -->
    <div class="row g-3 mb-4">
        @php
            // Estadísticas corregidas - usando count() en lugar de total()
            $totalCitas = \App\Models\Cita::count();
            $pendientes = \App\Models\Cita::whereHas('estado', function($q) {
                $q->where('estado', 'Pendiente');
            })->count();
            $atendidas = \App\Models\Cita::whereHas('estado', function($q) {
                $q->where('estado', 'Atendida');
            })->count();
            $hoy = \App\Models\Cita::whereDate('fecha', today())->count();
        @endphp
        
        <div class="col-lg-3 col-md-6">
            <div class="card border-0 shadow-sm h-100 border-start border-primary border-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small">Total de Citas</p>
                            <h3 class="mb-0 fw-bold">{{ $totalCitas }}</h3>
                        </div>
                        <div class="bg-primary bg-opacity-10 p-3 rounded-circle">
                            <i class="bi bi-calendar3 text-primary fs-4"></i>
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
                            <p class="text-muted mb-1 small">Pendientes</p>
                            <h3 class="mb-0 fw-bold">{{ $pendientes }}</h3>
                        </div>
                        <div class="bg-warning bg-opacity-10 p-3 rounded-circle">
                            <i class="bi bi-clock-history text-warning fs-4"></i>
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
                            <p class="text-muted mb-1 small">Atendidas</p>
                            <h3 class="mb-0 fw-bold">{{ $atendidas }}</h3>
                        </div>
                        <div class="bg-success bg-opacity-10 p-3 rounded-circle">
                            <i class="bi bi-check-circle text-success fs-4"></i>
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
                            <p class="text-muted mb-1 small">Hoy</p>
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
                <form method="GET" action="{{ route('citas.index') }}" class="row g-3">
                    @if($rol !== 'doctor')
                    <div class="col-lg-3 col-md-6">
                        <label class="form-label fw-semibold">
                            <i class="bi bi-person-badge text-primary me-1"></i>Doctor
                        </label>
                        <select name="filtro_doctor" class="form-select">
                            <option value="">Todos los doctores</option>
                            @foreach($doctores as $doctor)
                                <option value="{{ $doctor->id_doctor }}" 
                                    {{ request('filtro_doctor') == $doctor->id_doctor ? 'selected' : '' }}>
                                    {{ $doctor->nombre_doctor }} - {{ $doctor->especialidad->especialidad }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @endif
                    
                    <div class="col-lg-3 col-md-6">
                        <label class="form-label fw-semibold">
                            <i class="bi bi-flag text-primary me-1"></i>Estado
                        </label>
                        <select name="filtro_estado" class="form-select">
                            <option value="">Todos los estados</option>
                            @foreach($estados as $estado)
                                <option value="{{ $estado->id_estado }}" 
                                    {{ request('filtro_estado') == $estado->id_estado ? 'selected' : '' }}>
                                    {{ $estado->estado }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="col-lg-3 col-md-6">
                        <label class="form-label fw-semibold">
                            <i class="bi bi-calendar3 text-primary me-1"></i>Fecha
                        </label>
                        <input type="date" name="filtro_fecha" class="form-control" 
                               value="{{ request('filtro_fecha') }}">
                    </div>
                    
                    <div class="col-lg-3 col-md-6">
                        <label class="form-label fw-semibold">
                            <i class="bi bi-search text-primary me-1"></i>Paciente
                        </label>
                        <input type="text" name="filtro_paciente" class="form-control" 
                               value="{{ request('filtro_paciente') }}" 
                               placeholder="Nombre o DNI...">
                    </div>
                    
                    <div class="col-12">
                        <div class="d-flex gap-2 flex-wrap">
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="bi bi-search me-2"></i>Buscar
                            </button>
                            <a href="{{ route('citas.index') }}" class="btn btn-outline-secondary px-4">
                                <i class="bi bi-arrow-clockwise me-2"></i>Limpiar Filtros
                            </a>
                            @if(request()->hasAny(['filtro_doctor', 'filtro_estado', 'filtro_fecha', 'filtro_paciente']))
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

    <!-- Tabla de Citas -->
    @if($citas->count() > 0)
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white border-bottom py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="bi bi-list-ul text-primary me-2"></i>
                    Lista de Citas
                    <span class="badge bg-primary ms-2">{{ $citas->total() }}</span>
                </h5>
                <small class="text-muted">
                    Mostrando {{ $citas->firstItem() }} - {{ $citas->lastItem() }} de {{ $citas->total() }}
                </small>
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
                                <i class="bi bi-calendar-event me-1"></i>Fecha y Hora
                            </th>
                            <th class="py-3">
                                <i class="bi bi-person-badge me-1"></i>Doctor
                            </th>
                            <th class="py-3">
                                <i class="bi bi-person me-1"></i>Paciente
                            </th>
                            <th class="py-3">
                                <i class="bi bi-chat-left-text me-1"></i>Motivo
                            </th>
                            <th class="py-3">
                                <i class="bi bi-flag me-1"></i>Estado
                            </th>
                            <th class="text-center py-3">
                                <i class="bi bi-gear me-1"></i>Acciones
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($citas as $c)
                        <tr class="border-bottom">
                            <td class="px-4 fw-semibold">#{{ $c->id_cita }}</td>
                            <td>
                                <div class="d-flex flex-column">
                                    <span class="fw-semibold">
                                        <i class="bi bi-calendar3 text-primary me-1"></i>
                                        {{ \Carbon\Carbon::parse($c->fecha)->format('d/m/Y') }}
                                    </span>
                                    <small class="text-muted">
                                        <i class="bi bi-clock me-1"></i>
                                        {{ \Carbon\Carbon::parse($c->hora)->format('H:i') }}
                                    </small>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex flex-column">
                                    <span class="fw-semibold">{{ $c->doctor->nombre_doctor }}</span>
                                    <small class="text-muted">
                                        <i class="bi bi-star-fill text-warning me-1"></i>
                                        {{ $c->doctor->especialidad->especialidad }}
                                    </small>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex flex-column">
                                    <span>{{ $c->paciente->nombres }} {{ $c->paciente->apellidos }}</span>
                                    @if($c->paciente->dni)
                                    <small class="text-muted">DNI: {{ $c->paciente->dni }}</small>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <span class="d-inline-block text-truncate" style="max-width: 200px;" 
                                      title="{{ $c->motivo }}">
                                    {{ $c->motivo ?? '-' }}
                                </span>
                            </td>
                            <td>
                                @php
                                    $estadoNombre = strtolower($c->estado->estado ?? '');
                                    $badgeClass = match($estadoNombre) {
                                        'pendiente' => 'warning',
                                        'atendida' => 'success',
                                        'cancelada' => 'danger',
                                        'reprogramada' => 'info',
                                        default => 'secondary'
                                    };
                                    $iconClass = match($estadoNombre) {
                                        'pendiente' => 'clock',
                                        'atendida' => 'check-circle',
                                        'cancelada' => 'x-circle',
                                        'reprogramada' => 'arrow-clockwise',
                                        default => 'circle'
                                    };
                                @endphp
                                <span class="badge bg-{{ $badgeClass }} px-3 py-2">
                                    <i class="bi bi-{{ $iconClass }}-fill me-1"></i>
                                    {{ $c->estado->estado }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex justify-content-center gap-1">
                                    <a href="{{ route('citas.show', $c->id_cita) }}" 
                                       class="btn btn-sm btn-outline-info" 
                                       title="Ver detalles"
                                       data-bs-toggle="tooltip">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    @php
                                        $puedeEditar = in_array($estadoNombre, ['pendiente', 'reprogramada']);
                                    @endphp
                                    @if($puedeEditar)
                                        <a href="{{ route('citas.edit', $c->id_cita) }}" 
                                           class="btn btn-sm btn-outline-primary" 
                                           title="Editar cita"
                                           data-bs-toggle="tooltip">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                    @endif
                                    @if($rol === 'admin')
                                        <form method="POST" 
                                              action="{{ route('citas.destroy', $c->id_cita) }}" 
                                              style="display:inline-block;" 
                                              class="delete-form">
                                            @csrf @method('DELETE')
                                            <button type="submit" 
                                                    class="btn btn-sm btn-outline-danger" 
                                                    title="Eliminar cita"
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
        <div class="card-footer bg-white border-top py-3">
            <div class="d-flex justify-content-between align-items-center">
                <div class="text-muted small">
                    Mostrando <strong>{{ $citas->firstItem() }}</strong> a 
                    <strong>{{ $citas->lastItem() }}</strong> de 
                    <strong>{{ $citas->total() }}</strong> resultados
                </div>
                <div>
                    {{ $citas->links() }}
                </div>
            </div>
        </div>
    </div>
    @else
    <div class="card shadow-sm border-0">
        <div class="card-body text-center py-5">
            <div class="mb-4">
                <i class="bi bi-inbox text-muted" style="font-size: 4rem;"></i>
            </div>
            <h4 class="text-muted mb-2">No se encontraron citas</h4>
            <p class="text-muted mb-4">
                @if(request()->hasAny(['filtro_doctor', 'filtro_estado', 'filtro_fecha', 'filtro_paciente']))
                    No hay citas que coincidan con los filtros aplicados.
                @else
                    Aún no hay citas registradas en el sistema.
                @endif
            </p>
            <div class="d-flex gap-2 justify-content-center">
                @if(request()->hasAny(['filtro_doctor', 'filtro_estado', 'filtro_fecha', 'filtro_paciente']))
                    <a href="{{ route('citas.index') }}" class="btn btn-outline-primary">
                        <i class="bi bi-arrow-clockwise me-2"></i>Limpiar Filtros
                    </a>
                @endif
                @if($rol !== 'doctor')
                    <a href="{{ route('citas.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-2"></i>Crear Primera Cita
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

.btn-group .btn {
    transition: all 0.2s ease;
}

.btn-group .btn:hover {
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
            if (confirm('¿Está seguro de eliminar esta cita?\n\nEsta acción no se puede deshacer.')) {
                this.submit();
            }
        });
    });
});
</script>
@endsection