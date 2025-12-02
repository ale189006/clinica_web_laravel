@extends('layouts.app')
@section('title','Historial Clínico')

@section('content')
@php
    $rolUsuario = strtolower(Auth::user()->rol->rol ?? '');
@endphp

<div class="container-fluid px-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <h2 class="mb-1">
                        <i class="bi bi-file-medical text-primary me-2"></i>
                        Historial Clínico @if($rolUsuario === 'doctor') - Mis Historiales @endif
                    </h2>
                    <p class="text-muted mb-0">
                        <i class="bi bi-clock me-1"></i>
                        {{ \Carbon\Carbon::now()->isoFormat('dddd, D [de] MMMM [de] YYYY') }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Estadísticas Rápidas -->
    <div class="row g-3 mb-4">
        @php
            $totalHistoriales = \App\Models\HistorialClinico::count();
            $hoy = \App\Models\HistorialClinico::whereDate('fecha_registro', today())->count();
        @endphp
        
        <div class="col-lg-6 col-md-6">
            <div class="card border-0 shadow-sm h-100 border-start border-primary border-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small">Total de Historiales</p>
                            <h3 class="mb-0 fw-bold">{{ $totalHistoriales }}</h3>
                        </div>
                        <div class="bg-primary bg-opacity-10 p-3 rounded-circle">
                            <i class="bi bi-file-medical text-primary fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6 col-md-6">
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
                <form method="GET" action="{{ route('historial.index') }}" class="row g-3">
                    <div class="col-lg-6 col-md-6">
                        <label class="form-label fw-semibold">
                            <i class="bi bi-person text-primary me-1"></i>Paciente (Nombre o DNI)
                        </label>
                        <input type="text" name="filtro_paciente" class="form-control" 
                               value="{{ request('filtro_paciente') }}" 
                               placeholder="Buscar por paciente...">
                    </div>
                    
                    <div class="col-12">
                        <div class="d-flex gap-2 flex-wrap">
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="bi bi-search me-2"></i>Buscar
                            </button>
                            <a href="{{ route('historial.index') }}" class="btn btn-outline-secondary px-4">
                                <i class="bi bi-arrow-clockwise me-2"></i>Limpiar Filtros
                            </a>
                            @if(request()->has('filtro_paciente'))
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

    <!-- Tabla de Historiales -->
    @if($historiales->count() > 0)
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white border-bottom py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="bi bi-list-ul text-primary me-2"></i>
                    Lista de Historiales Clínicos
                    <span class="badge bg-primary ms-2">{{ $historiales->count() }}</span>
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
                                <i class="bi bi-calendar-event me-1"></i>Fecha Cita
                            </th>
                            <th class="py-3">
                                <i class="bi bi-person-badge me-1"></i>Doctor
                            </th>
                            <th class="py-3">
                                <i class="bi bi-star me-1"></i>Especialidad
                            </th>
                            <th class="py-3">
                                <i class="bi bi-person me-1"></i>Paciente
                            </th>
                            <th class="py-3">
                                <i class="bi bi-clipboard-pulse me-1"></i>Diagnóstico
                            </th>
                            <th class="py-3">
                                <i class="bi bi-clock-history me-1"></i>Fecha Registro
                            </th>
                            <th class="text-center py-3">
                                <i class="bi bi-gear me-1"></i>Acciones
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($historiales as $h)
                        <tr class="border-bottom">
                            <td class="px-4 fw-semibold">#{{ $h->id_historial }}</td>
                            <td>
                                <div class="d-flex flex-column">
                                    <span class="fw-semibold">
                                        <i class="bi bi-calendar3 text-primary me-1"></i>
                                        {{ \Carbon\Carbon::parse($h->cita->fecha)->format('d/m/Y') }}
                                    </span>
                                    <small class="text-muted">
                                        <i class="bi bi-clock me-1"></i>
                                        {{ \Carbon\Carbon::parse($h->cita->hora)->format('H:i') }}
                                    </small>
                                </div>
                            </td>
                            <td>{{ $h->cita->doctor->nombre_doctor }}</td>
                            <td>
                                <span class="badge bg-info">
                                    <i class="bi bi-star-fill text-warning me-1"></i>
                                    {{ $h->cita->doctor->especialidad->especialidad }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex flex-column">
                                    <span>{{ $h->cita->paciente->nombres }} {{ $h->cita->paciente->apellidos }}</span>
                                    @if($h->cita->paciente->dni)
                                    <small class="text-muted">DNI: {{ $h->cita->paciente->dni }}</small>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <span class="d-inline-block text-truncate" style="max-width: 200px;" 
                                      title="{{ $h->diagnostico ?? 'No especificado' }}">
                                    {{ Str::limit($h->diagnostico ?? 'No especificado', 50) }}
                                </span>
                            </td>
                            <td>
                                <small class="text-muted">
                                    {{ \Carbon\Carbon::parse($h->fecha_registro)->format('d/m/Y H:i') }}
                                </small>
                            </td>
                            <td>
                                <div class="d-flex justify-content-center gap-1">
                                    <a href="{{ route('historial.show', $h->id_historial) }}" 
                                       class="btn btn-sm btn-outline-info" 
                                       title="Ver detalles"
                                       data-bs-toggle="tooltip">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    @if($rolUsuario === 'doctor')
                                        <a href="{{ route('historial.edit', $h->id_historial) }}" 
                                           class="btn btn-sm btn-outline-primary" 
                                           title="Editar historial"
                                           data-bs-toggle="tooltip">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form method="POST" 
                                              action="{{ route('historial.destroy', $h->id_historial) }}" 
                                              style="display:inline-block;" 
                                              class="delete-form">
                                            @csrf @method('DELETE')
                                            <button type="submit" 
                                                    class="btn btn-sm btn-outline-danger" 
                                                    title="Eliminar historial"
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
            <h4 class="text-muted mb-2">No se encontraron historiales clínicos</h4>
            <p class="text-muted mb-4">
                @if(request()->has('filtro_paciente'))
                    No hay historiales que coincidan con los filtros aplicados.
                @else
                    Aún no hay historiales clínicos registrados en el sistema.
                @endif
            </p>
            <div class="d-flex gap-2 justify-content-center">
                @if(request()->has('filtro_paciente'))
                    <a href="{{ route('historial.index') }}" class="btn btn-outline-primary">
                        <i class="bi bi-arrow-clockwise me-2"></i>Limpiar Filtros
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
            if (confirm('¿Está seguro de eliminar este historial clínico?\n\nEsta acción no se puede deshacer.')) {
                this.submit();
            }
        });
    });
});
</script>
@endsection
