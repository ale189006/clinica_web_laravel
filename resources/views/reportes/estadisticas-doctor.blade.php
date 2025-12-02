@extends('layouts.app')

@section('title', 'Estadísticas por Doctor')

@section('content')
<div class="row mb-3">
    <div class="col-md-8">
        <h2>Estadísticas por Doctor</h2>
        <p class="text-muted">Rendimiento y estadísticas de cada doctor</p>
    </div>
    <div class="col-md-4 text-end">
        <a href="{{ route('reportes.index') }}" class="btn btn-secondary">Volver a Reportes</a>
    </div>
</div>

@if(count($estadisticas) > 0)
<div class="row">
    @foreach($estadisticas as $stat)
    <div class="col-md-6 mb-4">
        <div class="card h-100">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">{{ $stat['doctor']->nombre_doctor }}</h5>
                <small>{{ $stat['doctor']->especialidad->especialidad }}</small>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-6">
                        <div class="text-center p-3 bg-light rounded">
                            <h4 class="mb-0 text-primary">{{ $stat['total_citas'] }}</h4>
                            <small class="text-muted">Total Citas</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-center p-3 bg-light rounded">
                            <h4 class="mb-0 text-success">{{ $stat['citas_atendidas'] }}</h4>
                            <small class="text-muted">Atendidas</small>
                        </div>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-6">
                        <div class="text-center p-3 bg-light rounded">
                            <h4 class="mb-0 text-warning">{{ $stat['citas_pendientes'] }}</h4>
                            <small class="text-muted">Pendientes</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-center p-3 bg-light rounded">
                            <h4 class="mb-0 text-danger">{{ $stat['citas_canceladas'] }}</h4>
                            <small class="text-muted">Canceladas</small>
                        </div>
                    </div>
                </div>
                
                <hr>
                
                <div class="mb-3">
                    <p class="mb-1"><strong>Historiales Clínicos:</strong> {{ $stat['total_historiales'] }}</p>
                    <div class="progress" style="height: 25px;">
                        <div class="progress-bar {{ $stat['porcentaje_atendidas'] >= 80 ? 'bg-success' : ($stat['porcentaje_atendidas'] >= 50 ? 'bg-warning' : 'bg-danger') }}" 
                             role="progressbar" 
                             style="width: {{ $stat['porcentaje_atendidas'] }}%"
                             aria-valuenow="{{ $stat['porcentaje_atendidas'] }}" 
                             aria-valuemin="0" 
                             aria-valuemax="100">
                            {{ $stat['porcentaje_atendidas'] }}% Atendidas
                        </div>
                    </div>
                </div>
                
                <div class="text-center">
                    <a href="{{ route('doctores.show', $stat['doctor']->id_doctor) }}" class="btn btn-sm btn-primary">
                        Ver Detalles del Doctor
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>
@else
<div class="alert alert-info">
    No hay doctores registrados con estadísticas disponibles.
</div>
@endif
@endsection



