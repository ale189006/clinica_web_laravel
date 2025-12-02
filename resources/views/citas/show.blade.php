@extends('layouts.app')

@section('title', 'Detalle de Cita')

@section('content')
@php
    $rol = strtolower(Auth::user()->rol->rol ?? '');
    $estadoNombre = strtolower($cita->estado->estado ?? '');
    $estaAtendida = $estadoNombre === 'atendida';
    $estaCancelada = $estadoNombre === 'cancelada';
    $badgeClass = match($estadoNombre) {
        'pendiente' => 'warning',
        'atendida' => 'success',
        'cancelada' => 'danger',
        'reprogramada' => 'info',
        default => 'secondary'
    };
@endphp

<div class="container-fluid px-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <h2 class="mb-1">
                        <i class="bi bi-file-text text-primary me-2"></i>
                        Detalle de Cita #{{ $cita->id_cita }}
                    </h2>
                    <p class="text-muted mb-0">
                        Información completa de la cita médica
                    </p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('citas.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-2"></i>Volver
                    </a>
                    @if($puedeEditar)
                        <a href="{{ route('citas.edit', $cita->id_cita) }}" class="btn btn-primary">
                            <i class="bi bi-pencil me-2"></i>Editar
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Estado Destacado -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="alert alert-{{ $badgeClass }} border-0 shadow-sm d-flex align-items-center" role="alert">
                <i class="bi bi-info-circle-fill fs-4 me-3"></i>
                <div class="flex-grow-1">
                    <h5 class="alert-heading mb-1">Estado: {{ $cita->estado->estado }}</h5>
                    <p class="mb-0 small">
                        @if($estaAtendida)
                            Esta cita ya fue atendida. Puede consultar o agregar el historial clínico.
                        @elseif($estaCancelada)
                            Esta cita fue cancelada y no puede ser modificada.
                        @else
                            Esta cita está {{ $estadoNombre }} y puede ser editada.
                        @endif
                    </p>
                </div>
                <span class="badge bg-white text-{{ $badgeClass }} px-4 py-3 fs-5">
                    {{ $cita->estado->estado }}
                </span>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Información de la Cita -->
        <div class="col-lg-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-primary text-white py-3">
                    <h5 class="mb-0">
                        <i class="bi bi-calendar-check me-2"></i>Información de la Cita
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="info-item">
                                <label class="text-muted small mb-1">
                                    <i class="bi bi-calendar3 me-1"></i>Fecha
                                </label>
                                <p class="fw-semibold mb-0 fs-5">
                                    {{ \Carbon\Carbon::parse($cita->fecha)->format('d/m/Y') }}
                                </p>
                                <small class="text-muted">
                                    {{ \Carbon\Carbon::parse($cita->fecha)->isoFormat('dddd') }}
                                </small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="info-item">
                                <label class="text-muted small mb-1">
                                    <i class="bi bi-clock me-1"></i>Hora
                                </label>
                                <p class="fw-semibold mb-0 fs-5">
                                    {{ \Carbon\Carbon::parse($cita->hora)->format('H:i') }}
                                </p>
                                <small class="text-muted">
                                    {{ \Carbon\Carbon::parse($cita->hora)->format('h:i A') }}
                                </small>
                            </div>
                        </div>
                        <div class="col-12">
                            <hr class="my-2">
                        </div>
                        <div class="col-12">
                            <div class="info-item">
                                <label class="text-muted small mb-2">
                                    <i class="bi bi-chat-left-text me-1"></i>Motivo de la Consulta
                                </label>
                                <div class="bg-light p-3 rounded">
                                    <p class="mb-0">
                                        {{ $cita->motivo ?? 'No especificado' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="info-item">
                                <label class="text-muted small mb-2">
                                    <i class="bi bi-clock-history me-1"></i>Registro
                                </label>
                                <small class="text-muted d-block">
                                    Creada: {{ $cita->created_at ? $cita->created_at->format('d/m/Y H:i') : '-' }}
                                </small>
                                @if($cita->updated_at && $cita->updated_at != $cita->created_at)
                                <small class="text-muted d-block">
                                    Última actualización: {{ $cita->updated_at->format('d/m/Y H:i') }}
                                </small>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Información del Doctor -->
        <div class="col-lg-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-info text-white py-3">
                    <h5 class="mb-0">
                        <i class="bi bi-person-badge me-2"></i>Información del Doctor
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="text-center mb-3">
                        <div class="avatar-circle bg-info bg-opacity-10 mx-auto mb-3">
                            <i class="bi bi-person-fill text-info fs-1"></i>
                        </div>
                        <h4 class="mb-1">{{ $cita->doctor->nombre_doctor }}</h4>
                        <p class="text-muted mb-0">
                            <i class="bi bi-star-fill text-warning me-1"></i>
                            {{ $cita->doctor->especialidad->especialidad }}
                        </p>
                    </div>
                    <hr>
                    <div class="row g-3">
                        <div class="col-12">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-telephone-fill text-info me-3 fs-5"></i>
                                <div>
                                    <small class="text-muted d-block">Teléfono</small>
                                    <span class="fw-semibold">
                                        {{ $cita->doctor->telefono ?? 'No especificado' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-envelope-fill text-info me-3 fs-5"></i>
                                <div>
                                    <small class="text-muted d-block">Correo Electrónico</small>
                                    <span class="fw-semibold">
                                        {{ $cita->doctor->correo ?? 'No especificado' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Información del Paciente -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-success text-white py-3">
                    <h5 class="mb-0">
                        <i class="bi bi-person me-2"></i>Información del Paciente
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="row g-4">
                        <div class="col-lg-2 col-md-4">
                            <div class="text-center">
                                <div class="avatar-circle bg-success bg-opacity-10 mx-auto mb-2">
                                    <i class="bi bi-person-fill text-success fs-1"></i>
                                </div>
                                <small class="text-muted">Paciente</small>
                            </div>
                        </div>
                        <div class="col-lg-10 col-md-8">
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <label class="text-muted small mb-1">
                                        <i class="bi bi-card-text me-1"></i>DNI
                                    </label>
                                    <p class="fw-semibold mb-0">
                                        {{ $cita->paciente->dni ?? 'No especificado' }}
                                    </p>
                                </div>
                                <div class="col-md-4">
                                    <label class="text-muted small mb-1">
                                        <i class="bi bi-person me-1"></i>Nombres
                                    </label>
                                    <p class="fw-semibold mb-0">
                                        {{ $cita->paciente->nombres }}
                                    </p>
                                </div>
                                <div class="col-md-3">
                                    <label class="text-muted small mb-1">
                                        <i class="bi bi-person me-1"></i>Apellidos
                                    </label>
                                    <p class="fw-semibold mb-0">
                                        {{ $cita->paciente->apellidos ?? 'No especificado' }}
                                    </p>
                                </div>
                                <div class="col-md-2">
                                    <label class="text-muted small mb-1">
                                        <i class="bi bi-calendar me-1"></i>Edad
                                    </label>
                                    <p class="fw-semibold mb-0">
                                        {{ $cita->paciente->edad ?? '-' }} años
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <label class="text-muted small mb-1">
                                        <i class="bi bi-telephone me-1"></i>Teléfono
                                    </label>
                                    <p class="fw-semibold mb-0">
                                        {{ $cita->paciente->telefono ?? 'No especificado' }}
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <label class="text-muted small mb-1">
                                        <i class="bi bi-geo-alt me-1"></i>Dirección
                                    </label>
                                    <p class="fw-semibold mb-0">
                                        {{ $cita->paciente->direccion ?? 'No especificado' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Historial Clínico -->
    @if($estaAtendida)
    <div class="row mt-4">
        <div class="col-12">
            <div class="card shadow-sm border-0 border-start border-warning border-4">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="mb-0 text-warning-emphasis">
                        <i class="bi bi-file-medical me-2"></i>Historial Clínico
                    </h5>
                </div>
                <div class="card-body p-4">
                    @if($tieneHistorial)
                        <div class="alert alert-success border-0 shadow-sm d-flex align-items-center">
                            <i class="bi bi-check-circle-fill fs-3 me-3"></i>
                            <div class="flex-grow-1">
                                <h6 class="mb-1">Historial Clínico Disponible</h6>
                                <p class="mb-0 small">El historial clínico de esta cita ha sido registrado correctamente.</p>
                            </div>
                            <a href="{{ route('historial.show', $cita->historial->id_historial) }}" 
                               class="btn btn-success">
                                <i class="bi bi-eye me-2"></i>Ver Historial Completo
                            </a>
                        </div>
                    @else
                        @if($rol === 'doctor')
                            <div class="alert alert-info border-0 shadow-sm d-flex align-items-center">
                                <i class="bi bi-info-circle-fill fs-3 me-3"></i>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">Agregar Historial Clínico</h6>
                                    <p class="mb-0 small">Puede agregar el historial clínico de esta cita atendida.</p>
                                </div>
                                <a href="{{ route('historial.create', ['id_cita' => $cita->id_cita]) }}" 
                                   class="btn btn-info text-white">
                                    <i class="bi bi-plus-circle me-2"></i>Agregar Historial
                                </a>
                            </div>
                        @else
                            <div class="alert alert-warning border-0 shadow-sm d-flex align-items-center">
                                <i class="bi bi-exclamation-triangle-fill fs-3 me-3"></i>
                                <div>
                                    <h6 class="mb-1">Sin Historial Clínico</h6>
                                    <p class="mb-0 small">El doctor aún no ha registrado el historial clínico para esta cita.</p>
                                </div>
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Cambiar Estado -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="mb-0">
                        <i class="bi bi-flag me-2"></i>Gestión de Estado
                    </h5>
                </div>
                <div class="card-body p-4">
                    @if(!$estaCancelada)
                        <form method="POST" action="{{ route('citas.update-estado', $cita->id_cita) }}" id="formEstado">
                            @csrf
                            
                            <div class="row g-3 align-items-end">
                                @php
                                    $estados = \App\Models\EstadoCita::all();
                                @endphp
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">
                                        <i class="bi bi-flag text-primary me-1"></i>Cambiar Estado de la Cita
                                    </label>
                                    <select name="id_estado" 
                                            class="form-select form-select-lg" 
                                            required 
                                            {{ ($estaAtendida || $estaCancelada) ? 'disabled' : '' }}
                                            id="selectEstadoCita">
                                        @foreach($estados as $estado)
                                            <option value="{{ $estado->id_estado }}" 
                                                {{ $cita->id_estado == $estado->id_estado ? 'selected' : '' }}>
                                                {{ $estado->estado }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @if($estaAtendida || $estaCancelada)
                                        <small class="text-muted d-block mt-2">
                                            <i class="bi bi-info-circle me-1"></i>
                                            No se puede cambiar el estado de una cita {{ $estadoNombre }}.
                                        </small>
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    <button type="submit" 
                                            class="btn btn-primary btn-lg w-100" 
                                            {{ ($estaAtendida || $estaCancelada) ? 'disabled' : '' }}>
                                        <i class="bi bi-arrow-repeat me-2"></i>Actualizar Estado
                                    </button>
                                </div>
                            </div>
                        </form>
                    @else
                        <div class="alert alert-danger border-0 shadow-sm mb-0">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-x-circle-fill fs-3 me-3"></i>
                                <div>
                                    <h6 class="mb-1">No se puede modificar el estado</h6>
                                    <p class="mb-0">Esta cita está <strong>{{ $cita->estado->estado }}</strong> y no puede ser modificada.</p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Acciones Adicionales -->
    <div class="row mt-4 mb-4">
        <div class="col-12">
            <div class="card shadow-sm border-0 bg-light">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                        <div>
                            <h6 class="mb-1">Acciones Disponibles</h6>
                            <small class="text-muted">Gestione la cita según sea necesario</small>
                        </div>
                        <div class="d-flex gap-2 flex-wrap">
                            @if($puedeEditar)
                                <a href="{{ route('citas.edit', $cita->id_cita) }}" class="btn btn-primary">
                                    <i class="bi bi-pencil me-2"></i>Editar Cita
                                </a>
                            @endif
                            <a href="{{ route('citas.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-list me-2"></i>Ver Todas las Citas
                            </a>
                            @if(!$estaCancelada && $rol !== 'recepcionista')
                                <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#eliminarModal">
                                    <i class="bi bi-trash me-2"></i>Eliminar
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Confirmación -->
<div class="modal fade" id="eliminarModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">
                    <i class="bi bi-exclamation-triangle me-2"></i>Confirmar Eliminación
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="mb-0">¿Está seguro de que desea eliminar esta cita?</p>
                <p class="text-danger mb-0"><strong>Esta acción no se puede deshacer.</strong></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <form method="POST" action="{{ route('citas.destroy', $cita->id_cita) }}" class="d-inline">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-trash me-2"></i>Sí, Eliminar
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
.avatar-circle {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.info-item label {
    font-size: 0.875rem;
    font-weight: 600;
    color: #6c757d;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.card {
    border-radius: 0.5rem;
    overflow: hidden;
}

.border-4 {
    border-width: 4px !important;
}

.btn {
    transition: all 0.2s ease;
}

.btn:hover {
    transform: translateY(-2px);
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const formEstado = document.getElementById('formEstado');
    
    if (formEstado) {
        formEstado.addEventListener('submit', function(e) {
            const select = document.getElementById('selectEstadoCita');
            const estadoSeleccionado = select.options[select.selectedIndex].text;
            
            if (!confirm(`¿Confirma cambiar el estado de la cita a "${estadoSeleccionado}"?`)) {
                e.preventDefault();
            }
        });
    }
});
</script>
@endsection