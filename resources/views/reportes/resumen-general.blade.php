@extends('layouts.app')

@section('title', 'Resumen General')

@section('content')
<div class="row mb-3">
    <div class="col-md-8">
        <h2>Resumen General</h2>
    </div>
    <div class="col-md-4 text-end">
        <a href="{{ route('reportes.index') }}" class="btn btn-secondary">Volver a Reportes</a>
    </div>
</div>

<!-- Tarjetas de Estadísticas -->
<div class="row mb-4">
    <div class="col-md-3 mb-3">
        <div class="card text-white bg-primary">
            <div class="card-body">
                <h5 class="card-title">Total Citas</h5>
                <h2 class="mb-0">{{ $stats['total_citas'] }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card text-white bg-success">
            <div class="card-body">
                <h5 class="card-title">Total Pacientes</h5>
                <h2 class="mb-0">{{ $stats['total_pacientes'] }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card text-white bg-info">
            <div class="card-body">
                <h5 class="card-title">Total Doctores</h5>
                <h2 class="mb-0">{{ $stats['total_doctores'] }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card text-white bg-secondary">
            <div class="card-body">
                <h5 class="card-title">Historiales Clínicos</h5>
                <h2 class="mb-0">{{ $stats['total_historiales'] }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-6 mb-3">
        <div class="card text-white bg-warning">
            <div class="card-body">
                <h5 class="card-title">Citas Hoy</h5>
                <h2 class="mb-0">{{ $stats['citas_hoy'] }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-6 mb-3">
        <div class="card text-white bg-dark">
            <div class="card-body">
                <h5 class="card-title">Citas del Mes</h5>
                <h2 class="mb-0">{{ $stats['citas_mes'] }}</h2>
            </div>
        </div>
    </div>
</div>

<!-- Citas por Estado -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Citas por Estado</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Estado</th>
                                <th>Total de Citas</th>
                                <th>Porcentaje</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $totalCitas = $stats['total_citas'];
                            @endphp
                            @foreach($citasPorEstado as $estado)
                            <tr>
                                <td>
                                    <span class="badge bg-{{ strtolower($estado->estado) === 'pendiente' ? 'warning' : (strtolower($estado->estado) === 'atendida' ? 'success' : (strtolower($estado->estado) === 'cancelada' ? 'danger' : 'info')) }}">
                                        {{ $estado->estado }}
                                    </span>
                                </td>
                                <td><strong>{{ $estado->citas_count }}</strong></td>
                                <td>
                                    @if($totalCitas > 0)
                                        {{ number_format(($estado->citas_count / $totalCitas) * 100, 2) }}%
                                    @else
                                        0%
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Citas por Mes -->
@if($citasPorMes->count() > 0)
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Citas por Mes (Últimos 6 meses)</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Mes</th>
                                <th>Total de Citas</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($citasPorMes as $mes)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($mes->mes . '-01')->format('F Y') }}</td>
                                <td><strong>{{ $mes->total }}</strong></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@endsection



