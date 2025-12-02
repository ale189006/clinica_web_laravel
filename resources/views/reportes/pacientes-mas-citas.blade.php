@extends('layouts.app')

@section('title', 'Pacientes con Más Citas')

@section('content')
<div class="row mb-3">
    <div class="col-md-8">
        <h2>Pacientes con Más Citas</h2>
        <p class="text-muted">Top 10 pacientes con mayor número de citas</p>
    </div>
    <div class="col-md-4 text-end">
        <a href="{{ route('reportes.index') }}" class="btn btn-secondary">Volver a Reportes</a>
    </div>
</div>

@if($pacientesMasCitas->count() > 0)
<div class="row mb-4">
    @foreach($pacientesMasCitas as $index => $paciente)
    <div class="col-md-6 mb-3">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">#{{ $index + 1 }} - {{ $paciente->nombres }} {{ $paciente->apellidos }}</h5>
            </div>
            <div class="card-body">
                <p><strong>DNI:</strong> {{ $paciente->dni ?? 'No especificado' }}</p>
                <p><strong>Teléfono:</strong> {{ $paciente->telefono ?? 'No especificado' }}</p>
                <p><strong>Total de Citas:</strong> <span class="badge bg-success fs-6">{{ $paciente->citas_count }}</span></p>
                
                @if($paciente->citas_recientes->count() > 0)
                    <hr>
                    <h6>Citas Recientes:</h6>
                    <ul class="list-group">
                        @foreach($paciente->citas_recientes as $cita)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>
                                {{ \Carbon\Carbon::parse($cita->fecha)->format('d/m/Y') }} - 
                                {{ $cita->doctor->nombre_doctor }} - 
                                {{ $cita->doctor->especialidad->especialidad }}
                            </span>
                            <span class="badge bg-{{ strtolower($cita->estado->estado) === 'pendiente' ? 'warning' : (strtolower($cita->estado->estado) === 'atendida' ? 'success' : (strtolower($cita->estado->estado) === 'cancelada' ? 'danger' : 'info')) }}">
                                {{ $cita->estado->estado }}
                            </span>
                        </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </div>
    @endforeach
</div>
@else
<div class="alert alert-info">
    No hay pacientes con citas registradas.
</div>
@endif
@endsection



