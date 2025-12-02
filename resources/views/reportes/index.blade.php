@extends('layouts.app')

@section('title', 'Reportes')

@section('content')
@php
    $rol = strtolower(Auth::user()->rol->rol ?? '');
@endphp

<div class="container-fluid px-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <h2 class="mb-1">
                        <i class="bi bi-graph-up text-primary me-2"></i>
                        Reportes y Estadísticas
                    </h2>
                    <p class="text-muted mb-0">
                        <i class="bi bi-clock me-1"></i>
                        {{ \Carbon\Carbon::now()->isoFormat('dddd, D [de] MMMM [de] YYYY') }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Resumen General -->
        <div class="col-lg-6 col-md-6">
            <div class="card shadow-sm border-0 h-100 border-start border-primary border-4">
                <div class="card-header bg-primary text-white py-3">
                    <h5 class="mb-0">
                        <i class="bi bi-graph-up-arrow me-2"></i>Resumen General
                    </h5>
                </div>
                <div class="card-body p-4">
                    <p class="text-muted mb-3">
                        Vista general de estadísticas del sistema incluyendo totales de citas, pacientes, doctores y citas por mes.
                    </p>
                    <a href="{{ route('reportes.resumen-general') }}" class="btn btn-primary w-100">
                        <i class="bi bi-arrow-right me-2"></i>Ver Reporte
                    </a>
                </div>
            </div>
        </div>

        <!-- Estado de Citas -->
        <div class="col-lg-6 col-md-6">
            <div class="card shadow-sm border-0 h-100 border-start border-info border-4">
                <div class="card-header bg-info text-white py-3">
                    <h5 class="mb-0">
                        <i class="bi bi-calendar-check me-2"></i>Estado de Citas
                    </h5>
                </div>
                <div class="card-body p-4">
                    <p class="text-muted mb-3">
                        Reporte detallado de las citas agrupadas por estado (Pendiente, Atendida, Cancelada, Reprogramada).
                    </p>
                    <a href="{{ route('reportes.estado-citas') }}" class="btn btn-info text-white w-100">
                        <i class="bi bi-arrow-right me-2"></i>Ver Reporte
                    </a>
                </div>
            </div>
        </div>

        <!-- Pacientes con Más Citas -->
        <div class="col-lg-6 col-md-6">
            <div class="card shadow-sm border-0 h-100 border-start border-success border-4">
                <div class="card-header bg-success text-white py-3">
                    <h5 class="mb-0">
                        <i class="bi bi-people me-2"></i>Pacientes con Más Citas
                    </h5>
                </div>
                <div class="card-body p-4">
                    <p class="text-muted mb-3">
                        Listado de los pacientes que han tenido más citas médicas en el sistema.
                    </p>
                    <a href="{{ route('reportes.pacientes-mas-citas') }}" class="btn btn-success w-100">
                        <i class="bi bi-arrow-right me-2"></i>Ver Reporte
                    </a>
                </div>
            </div>
        </div>

        @if($rol === 'admin')
        <!-- Estadísticas por Doctor -->
        <div class="col-lg-6 col-md-6">
            <div class="card shadow-sm border-0 h-100 border-start border-warning border-4">
                <div class="card-header bg-warning text-dark py-3">
                    <h5 class="mb-0">
                        <i class="bi bi-person-badge me-2"></i>Estadísticas por Doctor
                    </h5>
                </div>
                <div class="card-body p-4">
                    <p class="text-muted mb-3">
                        Reporte detallado de estadísticas y rendimiento de cada doctor en el sistema.
                    </p>
                    <a href="{{ route('reportes.estadisticas-doctor') }}" class="btn btn-warning w-100">
                        <i class="bi bi-arrow-right me-2"></i>Ver Reporte
                    </a>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

<style>
.border-4 {
    border-width: 4px !important;
}

.card {
    border-radius: 0.5rem;
    transition: transform 0.2s ease;
}

.card:hover {
    transform: translateY(-5px);
}

.btn {
    transition: all 0.2s ease;
}

.btn:hover {
    transform: translateY(-2px);
}
</style>
@endsection
