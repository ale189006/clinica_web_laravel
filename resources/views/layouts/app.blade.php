<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clínica - @yield('title')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        :root {
            --gradient-primary: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --gradient-success: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            --gradient-info: linear-gradient(135deg, #2193b0 0%, #6dd5ed 100%);
            --gradient-warning: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            --gradient-dark: linear-gradient(135deg, #141e30 0%, #243b55 100%);
        }

        body {
            background: linear-gradient(to bottom, #f8f9fa 0%, #e9ecef 100%);
            min-height: 100vh;
        }

        /* Navbar con degradado moderno */
        .navbar-custom {
            background: var(--gradient-primary);
            box-shadow: 0 4px 20px rgba(102, 126, 234, 0.4);
            padding: 1rem 0;
            backdrop-filter: blur(10px);
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
            color: white !important;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
            display: flex;
            align-items: center;
            gap: 10px;
            transition: all 0.3s ease;
        }

        .navbar-brand:hover {
            transform: scale(1.05);
        }

        .navbar-brand i {
            font-size: 1.8rem;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }

        .nav-link {
            color: rgba(255, 255, 255, 0.9) !important;
            font-weight: 500;
            padding: 0.5rem 1rem !important;
            margin: 0 0.2rem;
            border-radius: 8px;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .nav-link::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.1);
            transform: translateX(-100%);
            transition: transform 0.3s ease;
        }

        .nav-link:hover::before {
            transform: translateX(0);
        }

        .nav-link:hover {
            color: white !important;
            background: rgba(255, 255, 255, 0.15);
            transform: translateY(-2px);
        }

        .nav-link.active {
            background: rgba(255, 255, 255, 0.2);
            color: white !important;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
        }

        .nav-link i {
            margin-right: 5px;
            font-size: 1.1rem;
        }

        /* Dropdown personalizado */
        .dropdown-toggle {
            background: rgba(255, 255, 255, 0.15) !important;
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 25px;
            padding: 0.5rem 1.2rem !important;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .dropdown-toggle:hover {
            background: rgba(255, 255, 255, 0.25) !important;
            border-color: rgba(255, 255, 255, 0.5);
        }

        .dropdown-toggle i {
            font-size: 1.3rem;
        }

        .dropdown-menu {
            background: white;
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
            padding: 0.5rem;
            margin-top: 0.5rem !important;
            min-width: 250px;
        }

        .dropdown-item {
            border-radius: 8px;
            padding: 0.7rem 1rem;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .dropdown-item i {
            font-size: 1.1rem;
            width: 20px;
        }

        .dropdown-item:hover {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            transform: translateX(5px);
        }

        .dropdown-divider {
            margin: 0.5rem 0;
            border-color: rgba(0, 0, 0, 0.1);
        }

        /* Badge de rol */
        .role-badge {
            background: rgba(255, 255, 255, 0.25);
            border: 1px solid rgba(255, 255, 255, 0.3);
            padding: 0.2rem 0.8rem;
            border-radius: 15px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-left: 8px;
            display: inline-block;
        }

        /* Alertas mejoradas */
        .alert {
            border: none;
            border-radius: 15px;
            padding: 1rem 1.5rem;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            display: flex;
            align-items: center;
            gap: 15px;
            animation: slideInDown 0.4s ease;
        }

        @keyframes slideInDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .alert-success {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            color: white;
        }

        .alert-danger {
            background: linear-gradient(135deg, #eb3349 0%, #f45c43 100%);
            color: white;
        }

        .alert::before {
            font-family: 'bootstrap-icons';
            font-size: 1.5rem;
        }

        .alert-success::before {
            content: '\F26A'; /* bi-check-circle-fill */
        }

        .alert-danger::before {
            content: '\F623'; /* bi-x-circle-fill */
        }

        /* Container mejorado */
        .container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.05);
            padding: 2rem;
            margin-top: 2rem !important;
            margin-bottom: 2rem;
        }

        /* Navbar toggler mejorado */
        .navbar-toggler {
            border: 2px solid rgba(255, 255, 255, 0.5);
            border-radius: 8px;
            padding: 0.5rem;
        }

        .navbar-toggler:focus {
            box-shadow: 0 0 0 0.2rem rgba(255, 255, 255, 0.3);
        }

        .navbar-toggler-icon {
            filter: brightness(0) invert(1);
        }

        /* Responsive adjustments */
        @media (max-width: 991px) {
            .navbar-nav {
                padding: 1rem 0;
            }

            .nav-link {
                margin: 0.2rem 0;
            }

            .dropdown-toggle {
                justify-content: center;
                margin-top: 0.5rem;
            }
        }

        /* Smooth scroll */
        html {
            scroll-behavior: smooth;
        }

        /* Logo icon animation */
        .logo-icon {
            display: inline-block;
            background: linear-gradient(135deg, #fff 0%, #f0f0f0 100%);
            border-radius: 50%;
            padding: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }
    </style>
</head>
<body>

@auth
<nav class="navbar navbar-expand-lg navbar-custom">
    <div class="container-fluid px-4">
        <a href="{{ route('dashboard') }}" class="navbar-brand">
            <span class="logo-icon">
                <i class="bi bi-heart-pulse-fill" style="color: #667eea;"></i>
            </span>
            Clínica Médica
        </a>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                @php
                    $rol = strtolower(Auth::user()->rol->rol ?? '');
                    $currentRoute = request()->route()->getName();
                @endphp
                
                @if($rol === 'admin')
                    <li class="nav-item">
                        <a href="{{ route('doctores.index') }}" 
                           class="nav-link {{ str_contains($currentRoute, 'doctores') ? 'active' : '' }}">
                            <i class="bi bi-person-badge"></i>Doctores
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('especialidades.index') }}" 
                           class="nav-link {{ str_contains($currentRoute, 'especialidades') ? 'active' : '' }}">
                            <i class="bi bi-star"></i>Especialidades
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('pacientes.index') }}" 
                           class="nav-link {{ str_contains($currentRoute, 'pacientes') ? 'active' : '' }}">
                            <i class="bi bi-people"></i>Pacientes
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('citas.index') }}" 
                           class="nav-link {{ str_contains($currentRoute, 'citas') ? 'active' : '' }}">
                            <i class="bi bi-calendar-check"></i>Citas
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('reportes.index') }}" 
                           class="nav-link {{ str_contains($currentRoute, 'reportes') ? 'active' : '' }}">
                            <i class="bi bi-graph-up"></i>Reportes
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('historial.index') }}" 
                           class="nav-link {{ str_contains($currentRoute, 'historial') ? 'active' : '' }}">
                            <i class="bi bi-file-medical"></i>Historial
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('usuarios.index') }}" 
                           class="nav-link {{ str_contains($currentRoute, 'usuarios') ? 'active' : '' }}">
                            <i class="bi bi-person-circle"></i>Usuarios
                        </a>
                    </li>
                @elseif($rol === 'doctor')
                    <li class="nav-item">
                        <a href="{{ route('pacientes.index') }}" 
                           class="nav-link {{ str_contains($currentRoute, 'pacientes') ? 'active' : '' }}">
                            <i class="bi bi-people"></i>Mis Pacientes
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('citas.index') }}" 
                           class="nav-link {{ str_contains($currentRoute, 'citas') ? 'active' : '' }}">
                            <i class="bi bi-calendar-check"></i>Mis Citas
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('historial.index') }}" 
                           class="nav-link {{ str_contains($currentRoute, 'historial') ? 'active' : '' }}">
                            <i class="bi bi-file-medical"></i>Historiales
                        </a>
                    </li>
                @elseif($rol === 'recepcionista')
                    <li class="nav-item">
                        <a href="{{ route('doctores.index') }}" 
                           class="nav-link {{ str_contains($currentRoute, 'doctores') ? 'active' : '' }}">
                            <i class="bi bi-person-badge"></i>Doctores
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('pacientes.index') }}" 
                           class="nav-link {{ str_contains($currentRoute, 'pacientes') ? 'active' : '' }}">
                            <i class="bi bi-people"></i>Pacientes
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('citas.index') }}" 
                           class="nav-link {{ str_contains($currentRoute, 'citas') ? 'active' : '' }}">
                            <i class="bi bi-calendar-check"></i>Citas
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('reportes.index') }}" 
                           class="nav-link {{ str_contains($currentRoute, 'reportes') ? 'active' : '' }}">
                            <i class="bi bi-graph-up"></i>Reportes
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('historial.index') }}" 
                           class="nav-link {{ str_contains($currentRoute, 'historial') ? 'active' : '' }}">
                            <i class="bi bi-file-medical"></i>Historial
                        </a>
                    </li>
                @endif
            </ul>
            
            <ul class="navbar-nav">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-person-circle"></i> 
                        <span>{{ Auth::user()->usuario }}</span>
                        <span class="role-badge">{{ Auth::user()->rol->rol ?? 'Sin rol' }}</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item" href="{{ route('dashboard') }}">
                                <i class="bi bi-speedometer2"></i>
                                <span>Dashboard</span>
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="#">
                                <i class="bi bi-person"></i>
                                <span>Mi Perfil</span>
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="#">
                                <i class="bi bi-gear"></i>
                                <span>Configuración</span>
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}" class="d-inline w-100">
                                @csrf
                                <button type="submit" class="dropdown-item">
                                    <i class="bi bi-box-arrow-right"></i>
                                    <span>Cerrar Sesión</span>
                                </button>
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>
@endauth

<div class="container mt-4">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <div>
                <strong>¡Éxito!</strong> {{ session('success') }}
            </div>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <div>
                <strong>¡Error!</strong> {{ session('error') }}
            </div>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @yield('content')
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
// Auto-cerrar alertas después de 5 segundos
document.addEventListener('DOMContentLoaded', function() {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }, 5000);
    });
});
</script>

@stack('scripts')
</body>
</html>