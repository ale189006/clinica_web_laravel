@extends('layouts.app')

@section('title', 'Estado de Citas')

@section('content')
<div class="row mb-3">
    <div class="col-md-8">
        <h2>Estado de Citas</h2>
    </div>
    <div class="col-md-4 text-end">
        <a href="{{ route('reportes.index') }}" class="btn btn-secondary">Volver a Reportes</a>
    </div>
</div>

<!-- Resumen por Estado -->
<div class="row mb-4">
    @foreach($estados as $estado)
    <div class="col-md-3 mb-3">
        <div class="card text-white bg-{{ strtolower($estado->estado) === 'pendiente' ? 'warning' : (strtolower($estado->estado) === 'atendida' ? 'success' : (strtolower($estado->estado) === 'cancelada' ? 'danger' : 'info')) }}">
            <div class="card-body">
                <h5 class="card-title">{{ $estado->estado }}</h5>
                <h2 class="mb-0">{{ $estado->citas->count() }}</h2>
            </div>
        </div>
    </div>
    @endforeach
</div>

<!-- Detalle por Estado -->
@foreach($estados as $estado)
    @if($estado->citas->count() > 0)
    <div class="card mb-4">
        <div class="card-header bg-{{ strtolower($estado->estado) === 'pendiente' ? 'warning' : (strtolower($estado->estado) === 'atendida' ? 'success' : (strtolower($estado->estado) === 'cancelada' ? 'danger' : 'info')) }} text-white">
            <h5 class="mb-0">{{ $estado->estado }} - {{ $estado->citas->count() }} cita(s)</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Hora</th>
                            <th>Paciente</th>
                            <th>Doctor</th>
                            <th>Especialidad</th>
                            <th>Motivo</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($estado->citas->sortByDesc('fecha') as $cita)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($cita->fecha)->format('d/m/Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($cita->hora)->format('H:i') }}</td>
                            <td>{{ $cita->paciente->nombres }} {{ $cita->paciente->apellidos }}</td>
                            <td>{{ $cita->doctor->nombre_doctor }}</td>
                            <td>{{ $cita->doctor->especialidad->especialidad }}</td>
                            <td>{{ Str::limit($cita->motivo ?? 'No especificado', 30) }}</td>
                            <td>
                                <a href="{{ route('citas.show', $cita->id_cita) }}" class="btn btn-sm btn-info">Ver</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif
@endforeach
@endsection



