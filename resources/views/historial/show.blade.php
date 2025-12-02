@extends('layouts.app')

@section('title', 'Detalle Historial Clínico')

@section('content')
@php
    $rolUsuario = strtolower(Auth::user()->rol->rol ?? '');
@endphp

<div class="row mb-3">
    <div class="col-md-8">
        <h2>Detalle del Historial Clínico #{{ $historial->id_historial }}</h2>
    </div>
    <div class="col-md-4 text-end">
        <a href="{{ route('historial.index') }}" class="btn btn-secondary">Volver</a>
        @if($rolUsuario === 'doctor')
            <a href="{{ route('historial.edit', $historial->id_historial) }}" class="btn btn-primary">Editar</a>
        @endif
    </div>
</div>

<!-- Datos del Paciente -->
<div class="row mb-3">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="bi bi-person"></i> Datos del Paciente</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <p><strong>DNI:</strong> {{ $historial->cita->paciente->dni ?? 'No especificado' }}</p>
                    </div>
                    <div class="col-md-3">
                        <p><strong>Nombres:</strong> {{ $historial->cita->paciente->nombres }}</p>
                    </div>
                    <div class="col-md-3">
                        <p><strong>Apellidos:</strong> {{ $historial->cita->paciente->apellidos ?? 'No especificado' }}</p>
                    </div>
                    <div class="col-md-3">
                        <p><strong>Edad:</strong> {{ $historial->cita->paciente->edad ?? 'No especificado' }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Teléfono:</strong> {{ $historial->cita->paciente->telefono ?? 'No especificado' }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Dirección:</strong> {{ $historial->cita->paciente->direccion ?? 'No especificado' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Datos del Doctor -->
<div class="row mb-3">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="bi bi-person-badge"></i> Doctor Asignado</h5>
            </div>
            <div class="card-body">
                <p><strong>Nombre:</strong> {{ $historial->cita->doctor->nombre_doctor }}</p>
                <p><strong>Especialidad:</strong> {{ $historial->cita->doctor->especialidad->especialidad }}</p>
                <p><strong>Teléfono:</strong> {{ $historial->cita->doctor->telefono ?? 'No especificado' }}</p>
                <p><strong>Correo:</strong> {{ $historial->cita->doctor->correo ?? 'No especificado' }}</p>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0"><i class="bi bi-calendar-check"></i> Datos de la Cita</h5>
            </div>
            <div class="card-body">
                <p><strong>Fecha de la Cita:</strong> {{ \Carbon\Carbon::parse($historial->cita->fecha)->format('d/m/Y') }}</p>
                <p><strong>Hora de la Cita:</strong> {{ \Carbon\Carbon::parse($historial->cita->hora)->format('H:i') }}</p>
                <p><strong>Motivo:</strong> {{ $historial->cita->motivo ?? 'No especificado' }}</p>
                <p><strong>Fecha de Registro del Historial:</strong> {{ \Carbon\Carbon::parse($historial->fecha_registro)->format('d/m/Y H:i') }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Detalles Clínicos -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-warning text-dark">
                <h5 class="mb-0"><i class="bi bi-file-medical"></i> Detalles Clínicos</h5>
            </div>
            <div class="card-body">
                @if($mostrarDetallesCompletos || $rolUsuario === 'doctor')
                    <div class="mb-3">
                        <h6><strong>Diagnóstico:</strong></h6>
                        <p class="border p-3 rounded bg-light">{{ $historial->diagnostico ?? 'No especificado' }}</p>
                    </div>
                    
                    <div class="mb-3">
                        <h6><strong>Tratamiento:</strong></h6>
                        <p class="border p-3 rounded bg-light">{{ $historial->tratamiento ?? 'No especificado' }}</p>
                    </div>
                    
                    <div class="mb-3">
                        <h6><strong>Observaciones:</strong></h6>
                        <p class="border p-3 rounded bg-light">{{ $historial->observaciones ?? 'No especificado' }}</p>
                    </div>
                @else
                    <!-- Recepcionista solo ve datos generales -->
                    <div class="alert alert-info">
                        <p><strong>Diagnóstico:</strong> {{ Str::limit($historial->diagnostico ?? 'No especificado', 100) }}</p>
                        <p class="text-muted"><small>Los detalles completos del historial solo están disponibles para administradores y doctores.</small></p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
