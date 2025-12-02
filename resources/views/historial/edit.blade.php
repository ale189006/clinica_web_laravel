@extends('layouts.app')
@section('title','Editar Historial')

@section('content')
<div class="row mb-3">
    <div class="col-md-8">
        <h2>Editar Historial Clínico</h2>
    </div>
    <div class="col-md-4 text-end">
        <a href="{{ route('historial.index') }}" class="btn btn-secondary">Volver</a>
    </div>
</div>

<form method="POST" action="{{ route('historial.update', $historial->id_historial) }}">
    @csrf @method('PUT')

    <div class="card mb-3">
        <div class="card-header bg-info text-white">
            <h5 class="mb-0">Información de la Cita</h5>
        </div>
        <div class="card-body">
            <p><strong>Fecha:</strong> {{ \Carbon\Carbon::parse($historial->cita->fecha)->format('d/m/Y') }}</p>
            <p><strong>Hora:</strong> {{ \Carbon\Carbon::parse($historial->cita->hora)->format('H:i') }}</p>
            <p><strong>Paciente:</strong> {{ $historial->cita->paciente->nombres }} {{ $historial->cita->paciente->apellidos }}</p>
            <p><strong>Doctor:</strong> {{ $historial->cita->doctor->nombre_doctor }}</p>
            <p><strong>Motivo:</strong> {{ $historial->cita->motivo ?? 'No especificado' }}</p>
            <p class="text-muted"><small>Nota: La cita asociada no se puede modificar.</small></p>
        </div>
    </div>

    <div class="card">
        <div class="card-header bg-warning text-dark">
            <h5 class="mb-0">Datos del Historial Clínico</h5>
        </div>
        <div class="card-body">
            <div class="mb-3">
                <label class="form-label">Diagnóstico</label>
                <textarea name="diagnostico" class="form-control" rows="3" placeholder="Ingrese el diagnóstico...">{{ $historial->diagnostico }}</textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Tratamiento</label>
                <textarea name="tratamiento" class="form-control" rows="3" placeholder="Ingrese el tratamiento...">{{ $historial->tratamiento }}</textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Observaciones</label>
                <textarea name="observaciones" class="form-control" rows="4" placeholder="Ingrese observaciones adicionales...">{{ $historial->observaciones }}</textarea>
            </div>

            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                <button type="submit" class="btn btn-primary">Actualizar Historial</button>
                <a href="{{ route('historial.index') }}" class="btn btn-secondary">Cancelar</a>
            </div>
        </div>
    </div>
</form>
@endsection
