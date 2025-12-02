@extends('layouts.app')
@section('title','Nueva Cita')

@section('content')
<div class="container-fluid px-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-1"><i class="bi bi-calendar-plus text-primary me-2"></i>Nueva Cita</h2>
                    <p class="text-muted mb-0">Complete los datos para agendar una nueva cita médica</p>
                </div>
                <a href="{{ route('citas.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i>Volver
                </a>
            </div>
        </div>
    </div>

    <!-- Formulario -->
    <div class="row justify-content-center">
        <div class="col-lg-10 col-xl-8">
            <form method="POST" action="{{ route('citas.store') }}" id="formNuevaCita">
                @csrf

                <!-- Card Principal -->
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-primary text-white py-3">
                        <h5 class="mb-0"><i class="bi bi-info-circle me-2"></i>Información de la Cita</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">
                            <!-- Fecha y Hora -->
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-calendar3 text-primary me-1"></i>Fecha de la Cita
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="date" 
                                       name="fecha" 
                                       class="form-control form-control-lg @error('fecha') is-invalid @enderror" 
                                       value="{{ old('fecha') }}"
                                       min="{{ date('Y-m-d') }}"
                                       required>
                                @error('fecha')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Seleccione la fecha de la consulta</small>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-clock text-primary me-1"></i>Hora de la Cita
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="time" 
                                       name="hora" 
                                       class="form-control form-control-lg @error('hora') is-invalid @enderror" 
                                       value="{{ old('hora') }}"
                                       required>
                                @error('hora')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Horario de atención: 08:00 - 20:00</small>
                            </div>

                            <!-- Doctor -->
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-person-badge text-primary me-1"></i>Doctor
                                    <span class="text-danger">*</span>
                                </label>
                                <select name="id_doctor" 
                                        class="form-select form-select-lg @error('id_doctor') is-invalid @enderror" 
                                        required
                                        id="selectDoctor">
                                    <option value="" selected disabled>Seleccione un doctor...</option>
                                    @foreach($doctores as $d)
                                    <option value="{{ $d->id_doctor }}" 
                                            {{ old('id_doctor') == $d->id_doctor ? 'selected' : '' }}
                                            data-especialidad="{{ $d->especialidad->especialidad ?? '' }}">
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
                                    <i class="bi bi-person text-primary me-1"></i>Paciente
                                    <span class="text-danger">*</span>
                                </label>
                                <select name="id_paciente" 
                                        class="form-select form-select-lg @error('id_paciente') is-invalid @enderror" 
                                        required
                                        id="selectPaciente">
                                    <option value="" selected disabled>Seleccione un paciente...</option>
                                    @foreach($pacientes as $p)
                                    <option value="{{ $p->id_paciente }}" 
                                            {{ old('id_paciente') == $p->id_paciente ? 'selected' : '' }}
                                            data-dni="{{ $p->dni ?? '' }}">
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
                                    <i class="bi bi-file-text text-primary me-1"></i>Motivo de la Consulta
                                </label>
                                <textarea name="motivo" 
                                          class="form-control @error('motivo') is-invalid @enderror" 
                                          rows="3"
                                          placeholder="Describa brevemente el motivo de la consulta...">{{ old('motivo') }}</textarea>
                                @error('motivo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Opcional: ayuda al doctor a prepararse para la consulta</small>
                            </div>

                            <!-- Estado -->
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-flag text-primary me-1"></i>Estado Inicial
                                    <span class="text-danger">*</span>
                                </label>
                                <select name="id_estado" 
                                        class="form-select form-select-lg @error('id_estado') is-invalid @enderror" 
                                        required>
                                    @foreach($estados as $e)
                                    <option value="{{ $e->id_estado }}" 
                                            {{ (old('id_estado') == $e->id_estado || ($loop->first && !old('id_estado'))) ? 'selected' : '' }}>
                                        {{ $e->estado }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('id_estado')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Información Adicional -->
                <div class="card shadow-sm border-0 mb-4 bg-light">
                    <div class="card-body p-4">
                        <div class="row">
                            <div class="col-md-6 mb-3 mb-md-0">
                                <h6 class="text-primary mb-2"><i class="bi bi-info-circle me-2"></i>Información Importante</h6>
                                <ul class="small text-muted mb-0 ps-3">
                                    <li>Llegue 10 minutos antes de su cita</li>
                                    <li>Traiga sus documentos de identificación</li>
                                    <li>Si tiene exámenes previos, tráigalos</li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <h6 class="text-primary mb-2"><i class="bi bi-telephone me-2"></i>Contacto</h6>
                                <p class="small text-muted mb-0">
                                    Para reprogramar o cancelar, comuníquese con recepción<br>
                                    <strong>Teléfono:</strong> (044) 123-4567
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Botones de Acción -->
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4">
                        <div class="d-flex gap-2 justify-content-end">
                            <a href="{{ route('citas.index') }}" class="btn btn-outline-secondary btn-lg px-4">
                                <i class="bi bi-x-circle me-2"></i>Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary btn-lg px-4">
                                <i class="bi bi-check-circle me-2"></i>Agendar Cita
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Validación adicional del formulario
    const form = document.getElementById('formNuevaCita');
    
    form.addEventListener('submit', function(e) {
        const fecha = document.querySelector('input[name="fecha"]').value;
        const hora = document.querySelector('input[name="hora"]').value;
        
        // Validar que la fecha no sea pasada
        const fechaSeleccionada = new Date(fecha);
        const hoy = new Date();
        hoy.setHours(0, 0, 0, 0);
        
        if (fechaSeleccionada < hoy) {
            e.preventDefault();
            alert('La fecha de la cita no puede ser anterior a hoy');
            return false;
        }
        
        // Validar horario de atención
        const [horas, minutos] = hora.split(':');
        const horaNum = parseInt(horas);
        
        if (horaNum < 8 || horaNum >= 20) {
            e.preventDefault();
            alert('El horario de atención es de 08:00 a 20:00');
            return false;
        }
    });
});
</script>
@endsection