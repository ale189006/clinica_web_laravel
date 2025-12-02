@extends('layouts.app')
@section('title','Editar Cita')

@section('content')
<div class="container-fluid px-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-1"><i class="bi bi-pencil-square text-warning me-2"></i>Editar Cita #{{ $cita->id_cita }}</h2>
                    <p class="text-muted mb-0">Actualice la información de la cita médica</p>
                </div>
                <a href="{{ route('citas.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i>Volver
                </a>
            </div>
        </div>
    </div>

    <!-- Alert de Estado -->
    @php
        $estadoNombre = strtolower($cita->estado->estado ?? '');
    @endphp
    @if(in_array($estadoNombre, ['atendida', 'cancelada']))
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-triangle me-2"></i>
        <strong>Atención:</strong> Esta cita está en estado "{{ $cita->estado->estado }}". Considere si es necesario modificarla.
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <!-- Formulario -->
    <div class="row justify-content-center">
        <div class="col-lg-10 col-xl-8">
            <form method="POST" action="{{ route('citas.update', $cita->id_cita) }}" id="formEditarCita">
                @csrf 
                @method('PUT')

                <!-- Card: Estado Actual -->
                <div class="card shadow-sm border-0 mb-4 bg-light">
                    <div class="card-body p-3">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <small class="text-muted d-block mb-1">Estado Actual de la Cita:</small>
                                <span class="badge bg-{{ $estadoNombre === 'pendiente' ? 'warning' : ($estadoNombre === 'atendida' ? 'success' : ($estadoNombre === 'cancelada' ? 'danger' : 'info')) }} fs-6 px-3 py-2">
                                    <i class="bi bi-circle-fill me-1" style="font-size: 8px;"></i>
                                    {{ $cita->estado->estado }}
                                </span>
                            </div>
                            <div class="col-md-4 text-md-end mt-2 mt-md-0">
                                <small class="text-muted">
                                    <i class="bi bi-calendar-event me-1"></i>
                                    {{ \Carbon\Carbon::parse($cita->fecha)->format('d/m/Y') }} - 
                                    {{ \Carbon\Carbon::parse($cita->hora)->format('H:i') }}
                                </small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card Principal -->
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-warning bg-opacity-10 border-bottom border-warning py-3">
                        <h5 class="mb-0 text-warning-emphasis">
                            <i class="bi bi-pencil me-2"></i>Datos de la Cita
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">
                            <!-- Fecha y Hora -->
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-calendar3 text-warning me-1"></i>Fecha de la Cita
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="date" 
                                       name="fecha" 
                                       class="form-control form-control-lg @error('fecha') is-invalid @enderror" 
                                       value="{{ old('fecha', $cita->fecha) }}"
                                       required>
                                @error('fecha')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-clock text-warning me-1"></i>Hora de la Cita
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="time" 
                                       name="hora" 
                                       class="form-control form-control-lg @error('hora') is-invalid @enderror" 
                                       value="{{ old('hora', \Carbon\Carbon::parse($cita->hora)->format('H:i')) }}"
                                       required>
                                @error('hora')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Doctor -->
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-person-badge text-warning me-1"></i>Doctor
                                    <span class="text-danger">*</span>
                                </label>
                                <select name="id_doctor" 
                                        class="form-select form-select-lg @error('id_doctor') is-invalid @enderror" 
                                        required>
                                    @foreach($doctores as $d)
                                    <option value="{{ $d->id_doctor }}" 
                                            {{ (old('id_doctor', $cita->id_doctor) == $d->id_doctor) ? 'selected' : '' }}>
                                        {{ $d->nombre_doctor }} - {{ $d->especialidad->especialidad ?? 'Sin especialidad' }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('id_doctor')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Paciente -->
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-person text-warning me-1"></i>Paciente
                                    <span class="text-danger">*</span>
                                </label>
                                <select name="id_paciente" 
                                        class="form-select form-select-lg @error('id_paciente') is-invalid @enderror" 
                                        required>
                                    @foreach($pacientes as $p)
                                    <option value="{{ $p->id_paciente }}" 
                                            {{ (old('id_paciente', $cita->id_paciente) == $p->id_paciente) ? 'selected' : '' }}>
                                        {{ $p->nombres }} {{ $p->apellidos }} @if($p->dni) - DNI: {{ $p->dni }} @endif
                                    </option>
                                    @endforeach
                                </select>
                                @error('id_paciente')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Motivo -->
                            <div class="col-12">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-file-text text-warning me-1"></i>Motivo de la Consulta
                                </label>
                                <textarea name="motivo" 
                                          class="form-control @error('motivo') is-invalid @enderror" 
                                          rows="3"
                                          placeholder="Describa el motivo de la consulta...">{{ old('motivo', $cita->motivo) }}</textarea>
                                @error('motivo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Estado -->
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-flag text-warning me-1"></i>Estado de la Cita
                                    <span class="text-danger">*</span>
                                </label>
                                <select name="id_estado" 
                                        class="form-select form-select-lg @error('id_estado') is-invalid @enderror" 
                                        required
                                        id="selectEstado">
                                    @foreach($estados as $e)
                                    <option value="{{ $e->id_estado }}" 
                                            {{ (old('id_estado', $cita->id_estado) == $e->id_estado) ? 'selected' : '' }}>
                                        {{ $e->estado }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('id_estado')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Actualice el estado según corresponda</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Información del Historial -->
                @if($estadoNombre === 'atendida')
                <div class="card shadow-sm border-0 border-start border-4 border-info mb-4">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-info-circle-fill text-info fs-3 me-3"></i>
                            <div class="flex-grow-1">
                                <h6 class="mb-1">Cita Atendida</h6>
                                <p class="mb-0 small text-muted">
                                    Esta cita ya fue atendida. Si necesita agregar o ver el historial clínico, 
                                    <a href="{{ route('citas.show', $cita->id_cita) }}" class="text-decoration-none">
                                        haga clic aquí
                                    </a>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Cambios Realizados -->
                <div class="card shadow-sm border-0 mb-4 bg-light">
                    <div class="card-body p-4">
                        <h6 class="text-secondary mb-3">
                            <i class="bi bi-clock-history me-2"></i>Registro de Cambios
                        </h6>
                        <div class="row text-muted small">
                            <div class="col-md-6">
                                <strong>Fecha original:</strong> {{ \Carbon\Carbon::parse($cita->fecha)->format('d/m/Y') }}
                            </div>
                            <div class="col-md-6">
                                <strong>Hora original:</strong> {{ \Carbon\Carbon::parse($cita->hora)->format('H:i') }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Botones de Acción -->
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4">
                        <div class="d-flex gap-2 justify-content-between align-items-center flex-wrap">
                            <a href="{{ route('citas.show', $cita->id_cita) }}" class="btn btn-outline-info">
                                <i class="bi bi-eye me-2"></i>Ver Detalles
                            </a>
                            <div class="d-flex gap-2">
                                <a href="{{ route('citas.index') }}" class="btn btn-outline-secondary btn-lg px-4">
                                    <i class="bi bi-x-circle me-2"></i>Cancelar
                                </a>
                                <button type="submit" class="btn btn-warning btn-lg px-4 text-white">
                                    <i class="bi bi-check-circle me-2"></i>Guardar Cambios
                                </button>
                            </div>
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
    border-color: #ffc107;
    box-shadow: 0 0 0 0.25rem rgba(255, 193, 7, 0.15);
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

.badge {
    font-weight: 500;
}

.border-4 {
    border-width: 4px !important;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('formEditarCita');
    const selectEstado = document.getElementById('selectEstado');
    const estadoActual = '{{ $estadoNombre }}';
    
    // Advertencia al cambiar estado
    selectEstado.addEventListener('change', function() {
        const estadoSeleccionado = this.options[this.selectedIndex].text.toLowerCase();
        
        if (estadoActual === 'atendida' && estadoSeleccionado !== 'atendida') {
            if (!confirm('¿Está seguro de cambiar el estado de una cita que ya fue atendida?')) {
                this.value = '{{ $cita->id_estado }}';
            }
        }
        
        if (estadoSeleccionado === 'cancelada') {
            if (!confirm('¿Está seguro de cancelar esta cita? Esta acción puede requerir notificar al paciente.')) {
                this.value = '{{ $cita->id_estado }}';
            }
        }
    });
    
    // Confirmación antes de enviar
    form.addEventListener('submit', function(e) {
        if (!confirm('¿Confirma que desea guardar los cambios realizados a esta cita?')) {
            e.preventDefault();
        }
    });
});
</script>
@endsection