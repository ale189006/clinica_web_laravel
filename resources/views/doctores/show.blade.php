@extends('layouts.app')

@section('title', 'Detalle del Doctor')

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
                        <i class="bi bi-file-person text-primary me-2"></i>
                        Perfil del Doctor
                    </h2>
                    <p class="text-muted mb-0">Información detallada y estadísticas</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('doctores.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-2"></i>Volver
                    </a>
                    @if($rol === 'admin')
                        <a href="{{ route('doctores.edit', $doctor->id_doctor) }}" class="btn btn-primary">
                            <i class="bi bi-pencil me-2"></i>Editar
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Información del Doctor -->
        <div class="col-lg-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-primary text-white py-3">
                    <h5 class="mb-0">
                        <i class="bi bi-person-badge me-2"></i>Información del Doctor
                    </h5>
                </div>
                <div class="card-body p-4">
                    <!-- Avatar y Nombre -->
                    <div class="text-center mb-4 pb-4 border-bottom">
                        <div class="avatar-large bg-primary bg-opacity-10 mx-auto mb-3">
                            <i class="bi bi-person-fill text-primary"></i>
                        </div>
                        <h3 class="mb-1">{{ $doctor->nombre_doctor }}</h3>
                        <p class="text-muted mb-2">
                            <i class="bi bi-star-fill text-warning me-1"></i>
                            {{ $doctor->especialidad->especialidad }}
                        </p>
                        <span class="badge bg-success px-3 py-2">
                            <i class="bi bi-check-circle me-1"></i>Doctor Activo
                        </span>
                    </div>

                    <!-- Datos de Contacto -->
                    <div class="row g-3">
                        <div class="col-12">
                            <div class="info-item">
                                <label class="text-muted small mb-1">
                                    <i class="bi bi-telephone me-1"></i>Teléfono
                                </label>
                                <p class="fw-semibold mb-0">
                                    {{ $doctor->telefono ?? 'No especificado' }}
                                </p>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="info-item">
                                <label class="text-muted small mb-1">
                                    <i class="bi bi-envelope me-1"></i>Correo Electrónico
                                </label>
                                <p class="fw-semibold mb-0">
                                    {{ $doctor->correo ?? 'No especificado' }}
                                </p>
                            </div>
                        </div>
                        
                        @if($doctor->usuario)
                        <div class="col-12">
                            <hr class="my-2">
                        </div>
                        <div class="col-12">
                            <div class="info-item">
                                <label class="text-muted small mb-1">
                                    <i class="bi bi-person-circle me-1"></i>Usuario del Sistema
                                </label>
                                <p class="fw-semibold mb-0">
                                    {{ $doctor->usuario->usuario }}
                                </p>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="info-item">
                                <label class="text-muted small mb-1">
                                    <i class="bi bi-envelope-at me-1"></i>Correo de Usuario
                                </label>
                                <p class="fw-semibold mb-0">
                                    {{ $doctor->usuario->correo }}
                                </p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Estadísticas y Citas Recientes -->
        <div class="col-lg-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-info text-white py-3">
                    <h5 class="mb-0">
                        <i class="bi bi-graph-up me-2"></i>Estadísticas
                    </h5>
                </div>
                <div class="card-body p-4">
                    @php
                        $totalCitas = $doctor->citas->count();
                        $citasAtendidas = $doctor->citas->where('estado.estado', 'Atendida')->count();
                        $citasPendientes = $doctor->citas->where('estado.estado', 'Pendiente')->count();
                    @endphp
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="text-center p-3 bg-primary bg-opacity-10 rounded">
                                <h3 class="mb-0 text-primary">{{ $totalCitas }}</h3>
                                <small class="text-muted">Total Citas</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center p-3 bg-success bg-opacity-10 rounded">
                                <h3 class="mb-0 text-success">{{ $citasAtendidas }}</h3>
                                <small class="text-muted">Atendidas</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center p-3 bg-warning bg-opacity-10 rounded">
                                <h3 class="mb-0 text-warning">{{ $citasPendientes }}</h3>
                                <small class="text-muted">Pendientes</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center p-3 bg-info bg-opacity-10 rounded">
                                <h3 class="mb-0 text-info">{{ $doctor->citas->where('historial', '!=', null)->count() }}</h3>
                                <small class="text-muted">Con Historial</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Citas Recientes -->
    @if($doctor->citas->count() > 0)
    <div class="row mt-4">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="mb-0">
                        <i class="bi bi-calendar-check me-2"></i>Citas Recientes
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="px-4 py-3">ID</th>
                                    <th class="py-3">Fecha</th>
                                    <th class="py-3">Paciente</th>
                                    <th class="py-3">Estado</th>
                                    <th class="text-center py-3">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($doctor->citas->take(5) as $cita)
                                <tr>
                                    <td class="px-4">#{{ $cita->id_cita }}</td>
                                    <td>
                                        {{ \Carbon\Carbon::parse($cita->fecha)->format('d/m/Y') }}
                                        <br>
                                        <small class="text-muted">{{ \Carbon\Carbon::parse($cita->hora)->format('H:i') }}</small>
                                    </td>
                                    <td>{{ $cita->paciente->nombres }} {{ $cita->paciente->apellidos }}</td>
                                    <td>
                                        @php
                                            $estadoNombre = strtolower($cita->estado->estado ?? '');
                                            $badgeClass = match($estadoNombre) {
                                                'pendiente' => 'warning',
                                                'atendida' => 'success',
                                                'cancelada' => 'danger',
                                                'reprogramada' => 'info',
                                                default => 'secondary'
                                            };
                                        @endphp
                                        <span class="badge bg-{{ $badgeClass }}">
                                            {{ $cita->estado->estado }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('citas.show', $cita->id_cita) }}" 
                                           class="btn btn-sm btn-outline-info" 
                                           title="Ver detalles">
                                            <i class="bi bi-eye"></i>
                                        </a>
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
    @endif
</div>

<style>
.avatar-large {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 3rem;
}

.info-item label {
    font-size: 0.875rem;
    font-weight: 600;
    color: #6c757d;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.card {
    border-radius: 0.5rem;
    overflow: hidden;
}

.btn {
    transition: all 0.2s ease;
}

.btn:hover {
    transform: translateY(-2px);
}
</style>
@endsection