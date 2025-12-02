@extends('layouts.app')
@section('title','Nuevo Historial')

@section('content')
<div class="row mb-3">
    <div class="col-md-8">
        <h2>Registrar Historial Clínico</h2>
    </div>
    <div class="col-md-4 text-end">
        <a href="{{ route('historial.index') }}" class="btn btn-secondary">Volver</a>
    </div>
</div>

@if($citas->count() > 0 || isset($citaSeleccionada))
<form method="POST" action="{{ route('historial.store') }}">
    @csrf

    <div class="card mb-3">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Seleccionar Cita</h5>
        </div>
        <div class="card-body">
            @if(isset($citaSeleccionada))
                <div class="alert alert-info">
                    <strong>Cita seleccionada:</strong><br>
                    <strong>Fecha:</strong> {{ \Carbon\Carbon::parse($citaSeleccionada->fecha)->format('d/m/Y') }}<br>
                    <strong>Hora:</strong> {{ \Carbon\Carbon::parse($citaSeleccionada->hora)->format('H:i') }}<br>
                    <strong>Paciente:</strong> {{ $citaSeleccionada->paciente->nombres }} {{ $citaSeleccionada->paciente->apellidos }}<br>
                    <strong>Motivo:</strong> {{ $citaSeleccionada->motivo ?? 'No especificado' }}
                </div>
                <input type="hidden" name="id_cita" value="{{ $citaSeleccionada->id_cita }}">
            @else
                <label class="form-label">Cita Atendida (sin historial)</label>
                <select name="id_cita" class="form-select" required>
                    <option value="">Seleccione una cita</option>
                    @foreach($citas as $c)
                    <option value="{{ $c->id_cita }}">
                        {{ \Carbon\Carbon::parse($c->fecha)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($c->hora)->format('H:i') }} | 
                        Dr. {{ $c->doctor->nombre_doctor }} | 
                        Paciente: {{ $c->paciente->nombres }} {{ $c->paciente->apellidos }} |
                        Motivo: {{ Str::limit($c->motivo ?? 'No especificado', 30) }}
                    </option>
                    @endforeach
                </select>
                <small class="form-text text-muted">Solo se muestran citas atendidas que no tienen historial clínico asociado.</small>
            @endif
        </div>
    </div>

    <div class="card">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0">Datos del Historial Clínico</h5>
        </div>
        <div class="card-body">
            <div class="mb-3">
                <label class="form-label">Diagnóstico</label>
                <textarea name="diagnostico" class="form-control" rows="3" placeholder="Ingrese el diagnóstico..."></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Tratamiento</label>
                <textarea name="tratamiento" class="form-control" rows="3" placeholder="Ingrese el tratamiento..."></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Observaciones</label>
                <textarea name="observaciones" class="form-control" rows="4" placeholder="Ingrese observaciones adicionales..."></textarea>
            </div>

            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                <button type="submit" class="btn btn-success">Guardar Historial</button>
                <a href="{{ route('historial.index') }}" class="btn btn-secondary">Cancelar</a>
            </div>
        </div>
    </div>
</form>
@else
<div class="alert alert-warning">
    <h5>No hay citas disponibles</h5>
    <p>No tienes citas atendidas sin historial clínico para registrar.</p>
    <a href="{{ route('historial.index') }}" class="btn btn-secondary">Volver</a>
</div>
@endif
@endsection
