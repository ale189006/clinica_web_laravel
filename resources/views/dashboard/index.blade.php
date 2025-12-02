@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<style>
    .dashboard-hero {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 20px;
        padding: 2rem;
        color: white;
        box-shadow: 0 10px 40px rgba(102, 126, 234, 0.3);
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
    }

    .dashboard-hero::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -20%;
        width: 400px;
        height: 400px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
        animation: float 6s ease-in-out infinite;
    }

    @keyframes float {
        0%, 100% { transform: translateY(0) rotate(0deg); }
        50% { transform: translateY(-20px) rotate(180deg); }
    }

    .dashboard-hero h1 {
        font-weight: 700;
        font-size: 2rem;
        margin-bottom: 0.5rem;
        position: relative;
        z-index: 1;
    }

    .dashboard-hero p {
        opacity: 0.9;
        position: relative;
        z-index: 1;
    }

    .stat-card {
        border: none;
        border-radius: 15px;
        overflow: hidden;
        transition: all 0.3s ease;
        height: 100%;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    }

    .stat-card .card-body {
        padding: 1.5rem;
        position: relative;
        overflow: hidden;
    }

    .stat-card .card-body::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 100px;
        height: 100px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
        transform: translate(30%, -30%);
    }

    .stat-icon {
        font-size: 2.5rem;
        opacity: 0.9;
        margin-bottom: 0.5rem;
    }

    .stat-value {
        font-size: 2.5rem;
        font-weight: 700;
        margin: 0;
        line-height: 1;
    }

    .stat-label {
        font-size: 0.9rem;
        opacity: 0.9;
        margin-top: 0.5rem;
        font-weight: 500;
    }

    /* Degradados personalizados para cada card */
    .gradient-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .gradient-info {
        background: linear-gradient(135deg, #2193b0 0%, #6dd5ed 100%);
        color: white;
    }

    .gradient-success {
        background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        color: white;
    }

    .gradient-warning {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        color: white;
    }

    .gradient-secondary {
        background: linear-gradient(135deg, #868f96 0%, #596164 100%);
        color: white;
    }

    .gradient-dark {
        background: linear-gradient(135deg, #141e30 0%, #243b55 100%);
        color: white;
    }

    .gradient-danger {
        background: linear-gradient(135deg, #eb3349 0%, #f45c43 100%);
        color: white;
    }

    .gradient-purple {
        background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);
        color: #333;
    }

    /* Tabla moderna */
    .modern-table-card {
        border: none;
        border-radius: 20px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        overflow: hidden;
    }

    .modern-table-card .card-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 1.5rem;
        border: none;
    }

    .modern-table-card .card-header h5 {
        margin: 0;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .table-modern {
        margin: 0;
    }

    .table-modern thead th {
        background: #f8f9fa;
        border: none;
        padding: 1rem;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.8rem;
        letter-spacing: 0.5px;
        color: #6c757d;
    }

    .table-modern tbody tr {
        border-bottom: 1px solid #f0f0f0;
        transition: all 0.2s ease;
    }

    .table-modern tbody tr:hover {
        background: rgba(102, 126, 234, 0.05);
        transform: scale(1.01);
    }

    .table-modern tbody td {
        padding: 1rem;
        vertical-align: middle;
    }

    /* Botones de acceso rápido */
    .quick-access-btn {
        border: 2px solid #667eea;
        border-radius: 15px;
        padding: 1.5rem;
        text-align: center;
        transition: all 0.3s ease;
        text-decoration: none;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 10px;
        background: white;
        color: #667eea;
        height: 100%;
        position: relative;
        overflow: hidden;
    }

    .quick-access-btn::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .quick-access-btn:hover::before {
        opacity: 1;
    }

    .quick-access-btn:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
        color: white;
        border-color: transparent;
    }

    .quick-access-btn i {
        font-size: 2.5rem;
        position: relative;
        z-index: 1;
    }

    .quick-access-btn span {
        font-weight: 600;
        position: relative;
        z-index: 1;
    }

    /* Badge personalizado */
    .badge-modern {
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-weight: 500;
        font-size: 0.85rem;
    }

    /* Animación de entrada */
    .fade-in-up {
        animation: fadeInUp 0.6s ease;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Stagger animation para cards */
    .stat-card:nth-child(1) { animation-delay: 0.1s; }
    .stat-card:nth-child(2) { animation-delay: 0.2s; }
    .stat-card:nth-child(3) { animation-delay: 0.3s; }
    .stat-card:nth-child(4) { animation-delay: 0.4s; }
    .stat-card:nth-child(5) { animation-delay: 0.5s; }
    .stat-card:nth-child(6) { animation-delay: 0.6s; }
</style>

<!-- Hero Section -->
<div class="dashboard-hero fade-in-up">
    <div class="row align-items-center">
        <div class="col-md-8">
            <h1>
                <i class="bi bi-emoji-smile"></i>
                ¡Bienvenido, {{ Auth::user()->usuario }}!
            </h1>
            <p class="mb-0">
                <i class="bi bi-shield-check me-2"></i>
                Rol: <strong>{{ Auth::user()->rol->rol }}</strong>
                <span class="mx-2">•</span>
                <i class="bi bi-calendar-event me-2"></i>
                {{ \Carbon\Carbon::now()->isoFormat('dddd, D [de] MMMM [de] YYYY') }}
            </p>
        </div>
        <div class="col-md-4 text-end d-none d-md-block">
            <i class="bi bi-heart-pulse-fill" style="font-size: 5rem; opacity: 0.3;"></i>
        </div>
    </div>
</div>

<!-- Estadísticas -->
@if(isset($stats))
<div class="row mb-4 g-3">
    @if($rol === 'admin' || $rol === 'recepcionista')
        <div class="col-lg-3 col-md-6">
            <div class="card stat-card gradient-primary fade-in-up">
                <div class="card-body text-center">
                    <i class="bi bi-calendar-check stat-icon"></i>
                    <h2 class="stat-value">{{ $stats['total_citas'] }}</h2>
                    <p class="stat-label mb-0">Total Citas</p>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card stat-card gradient-info fade-in-up">
                <div class="card-body text-center">
                    <i class="bi bi-calendar-day stat-icon"></i>
                    <h2 class="stat-value">{{ $stats['citas_hoy'] }}</h2>
                    <p class="stat-label mb-0">Citas Hoy</p>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card stat-card gradient-success fade-in-up">
                <div class="card-body text-center">
                    <i class="bi bi-people stat-icon"></i>
                    <h2 class="stat-value">{{ $stats['total_pacientes'] }}</h2>
                    <p class="stat-label mb-0">Total Pacientes</p>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card stat-card gradient-warning fade-in-up">
                <div class="card-body text-center">
                    <i class="bi bi-person-badge stat-icon"></i>
                    <h2 class="stat-value">{{ $stats['total_doctores'] }}</h2>
                    <p class="stat-label mb-0">Total Doctores</p>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6">
            <div class="card stat-card gradient-secondary fade-in-up">
                <div class="card-body text-center">
                    <i class="bi bi-clock-history stat-icon"></i>
                    <h2 class="stat-value">{{ $stats['citas_pendientes'] }}</h2>
                    <p class="stat-label mb-0">Citas Pendientes</p>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6">
            <div class="card stat-card gradient-dark fade-in-up">
                <div class="card-body text-center">
                    <i class="bi bi-check-circle stat-icon"></i>
                    <h2 class="stat-value">{{ $stats['citas_atendidas'] }}</h2>
                    <p class="stat-label mb-0">Citas Atendidas</p>
                </div>
            </div>
        </div>
    @elseif($rol === 'doctor')
        <div class="col-lg-3 col-md-6">
            <div class="card stat-card gradient-primary fade-in-up">
                <div class="card-body text-center">
                    <i class="bi bi-calendar-check stat-icon"></i>
                    <h2 class="stat-value">{{ $stats['total_citas'] }}</h2>
                    <p class="stat-label mb-0">Mis Citas</p>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card stat-card gradient-info fade-in-up">
                <div class="card-body text-center">
                    <i class="bi bi-calendar-day stat-icon"></i>
                    <h2 class="stat-value">{{ $stats['citas_hoy'] }}</h2>
                    <p class="stat-label mb-0">Citas Hoy</p>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card stat-card gradient-warning fade-in-up">
                <div class="card-body text-center">
                    <i class="bi bi-clock-history stat-icon"></i>
                    <h2 class="stat-value">{{ $stats['citas_pendientes'] }}</h2>
                    <p class="stat-label mb-0">Pendientes</p>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card stat-card gradient-success fade-in-up">
                <div class="card-body text-center">
                    <i class="bi bi-file-medical stat-icon"></i>
                    <h2 class="stat-value">{{ $stats['total_historiales'] }}</h2>
                    <p class="stat-label mb-0">Historiales</p>
                </div>
            </div>
        </div>
    @endif
</div>
@endif

<!-- Citas Recientes -->
@if(isset($citas_recientes) && $citas_recientes->count() > 0)
<div class="row mb-4">
    <div class="col-12">
        <div class="card modern-table-card fade-in-up">
            <div class="card-header">
                <h5>
                    <i class="bi bi-clock-history"></i>
                    Citas Recientes
                </h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-modern">
                        <thead>
                            <tr>
                                <th><i class="bi bi-calendar3 me-2"></i>Fecha</th>
                                <th><i class="bi bi-clock me-2"></i>Hora</th>
                                <th><i class="bi bi-person me-2"></i>Paciente</th>
                                <th><i class="bi bi-person-badge me-2"></i>Doctor</th>
                                <th><i class="bi bi-flag me-2"></i>Estado</th>
                                <th class="text-center"><i class="bi bi-gear me-2"></i>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($citas_recientes as $cita)
                            <tr>
                                <td>
                                    <strong>{{ \Carbon\Carbon::parse($cita->fecha)->format('d/m/Y') }}</strong>
                                </td>
                                <td>{{ \Carbon\Carbon::parse($cita->hora)->format('H:i') }}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="bi bi-person-circle text-primary"></i>
                                        {{ $cita->paciente->nombres }} {{ $cita->paciente->apellidos }}
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="bi bi-person-badge-fill text-info"></i>
                                        {{ $cita->doctor->nombre_doctor }}
                                    </div>
                                </td>
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
                                    <span class="badge bg-{{ $badgeClass }} badge-modern">
                                        {{ $cita->estado->estado }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('citas.show', $cita->id_cita) }}" 
                                       class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                        <i class="bi bi-eye me-1"></i>Ver Detalles
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

<!-- Accesos Rápidos -->
<div class="row">
    <div class="col-12">
        <div class="card modern-table-card fade-in-up">
            <div class="card-header">
                <h5>
                    <i class="bi bi-lightning-charge"></i>
                    Accesos Rápidos
                </h5>
            </div>
            <div class="card-body p-4">
                <div class="row g-3">
                    @if($rol === 'admin')
                        <div class="col-lg-3 col-md-4 col-sm-6">
                            <a href="{{ route('doctores.index') }}" class="quick-access-btn">
                                <i class="bi bi-person-badge"></i>
                                <span>Doctores</span>
                            </a>
                        </div>
                        <div class="col-lg-3 col-md-4 col-sm-6">
                            <a href="{{ route('especialidades.index') }}" class="quick-access-btn">
                                <i class="bi bi-star"></i>
                                <span>Especialidades</span>
                            </a>
                        </div>
                        <div class="col-lg-3 col-md-4 col-sm-6">
                            <a href="{{ route('pacientes.index') }}" class="quick-access-btn">
                                <i class="bi bi-people"></i>
                                <span>Pacientes</span>
                            </a>
                        </div>
                        <div class="col-lg-3 col-md-4 col-sm-6">
                            <a href="{{ route('citas.index') }}" class="quick-access-btn">
                                <i class="bi bi-calendar-check"></i>
                                <span>Citas</span>
                            </a>
                        </div>
                        <div class="col-lg-3 col-md-4 col-sm-6">
                            <a href="{{ route('reportes.index') }}" class="quick-access-btn">
                                <i class="bi bi-graph-up"></i>
                                <span>Reportes</span>
                            </a>
                        </div>
                        <div class="col-lg-3 col-md-4 col-sm-6">
                            <a href="{{ route('historial.index') }}" class="quick-access-btn">
                                <i class="bi bi-file-medical"></i>
                                <span>Historial</span>
                            </a>
                        </div>
                        <div class="col-lg-3 col-md-4 col-sm-6">
                            <a href="{{ route('usuarios.index') }}" class="quick-access-btn">
                                <i class="bi bi-people-fill"></i>
                                <span>Usuarios</span>
                            </a>
                        </div>
                    @elseif($rol === 'doctor')
                        <div class="col-lg-4 col-md-4 col-sm-6">
                            <a href="{{ route('pacientes.index') }}" class="quick-access-btn">
                                <i class="bi bi-people"></i>
                                <span>Mis Pacientes</span>
                            </a>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-6">
                            <a href="{{ route('citas.index') }}" class="quick-access-btn">
                                <i class="bi bi-calendar-check"></i>
                                <span>Mis Citas</span>
                            </a>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-6">
                            <a href="{{ route('historial.index') }}" class="quick-access-btn">
                                <i class="bi bi-file-medical"></i>
                                <span>Historiales</span>
                            </a>
                        </div>
                    @elseif($rol === 'recepcionista')
                        <div class="col-lg-3 col-md-4 col-sm-6">
                            <a href="{{ route('doctores.index') }}" class="quick-access-btn">
                                <i class="bi bi-person-badge"></i>
                                <span>Doctores</span>
                            </a>
                        </div>
                        <div class="col-lg-3 col-md-4 col-sm-6">
                            <a href="{{ route('pacientes.index') }}" class="quick-access-btn">
                                <i class="bi bi-people"></i>
                                <span>Pacientes</span>
                            </a>
                        </div>
                        <div class="col-lg-3 col-md-4 col-sm-6">
                            <a href="{{ route('citas.index') }}" class="quick-access-btn">
                                <i class="bi bi-calendar-check"></i>
                                <span>Citas</span>
                            </a>
                        </div>
                        <div class="col-lg-3 col-md-4 col-sm-6">
                            <a href="{{ route('reportes.index') }}" class="quick-access-btn">
                                <i class="bi bi-graph-up"></i>
                                <span>Reportes</span>
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

