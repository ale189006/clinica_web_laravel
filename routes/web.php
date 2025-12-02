<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\RolController;
use App\Http\Controllers\EspecialidadController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\PacienteController;
use App\Http\Controllers\CitaController;
use App\Http\Controllers\HistorialClinicoController;
use App\Http\Controllers\ReporteController;

// Rutas públicas
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Redireccionar raíz a dashboard o login
Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
});

// Rutas protegidas por autenticación
Route::middleware('auth')->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // RUTAS DE DOCTORES - IMPORTANTE: Orden específico para evitar conflictos
    // Las rutas específicas (create, edit) DEBEN ir ANTES de la ruta genérica {id}
    
    // 1. Index - todos pueden ver
    Route::middleware('role:admin,recepcionista')->get('doctores', [DoctorController::class, 'index'])->name('doctores.index');
    
    // 2. Create - solo admin
    Route::middleware('role:admin')->get('doctores/create', [DoctorController::class, 'create'])->name('doctores.create');
    Route::middleware('role:admin')->post('doctores', [DoctorController::class, 'store'])->name('doctores.store');
    
    // 3. Edit - solo admin (va antes de show para evitar conflictos)
    Route::middleware('role:admin')->get('doctores/{id}/edit', [DoctorController::class, 'edit'])->name('doctores.edit');
    Route::middleware('role:admin')->put('doctores/{id}', [DoctorController::class, 'update'])->name('doctores.update');
    Route::middleware('role:admin')->delete('doctores/{id}', [DoctorController::class, 'destroy'])->name('doctores.destroy');
    
    // 4. Show - al final, después de todas las rutas específicas
    Route::middleware('role:admin,recepcionista')->get('doctores/{id}', [DoctorController::class, 'show'])->name('doctores.show');
    
    // Rutas solo para ADMIN
    Route::middleware('role:admin')->group(function () {
        // Usuarios - Solo admin puede gestionar
        Route::resource('usuarios', UsuarioController::class);
        
        // Especialidades - Solo admin puede gestionar
        Route::resource('especialidades', EspecialidadController::class);
        
        // Reportes adicionales
        Route::get('reportes/estadisticas-doctor', [ReporteController::class, 'estadisticasDoctor'])->name('reportes.estadisticas-doctor');
    });
    
    // Rutas para ADMIN y RECEPCIONISTA
    Route::middleware('role:admin,recepcionista')->group(function () {
        
        // Reportes
        Route::get('reportes', [ReporteController::class, 'index'])->name('reportes.index');
        Route::get('reportes/resumen-general', [ReporteController::class, 'resumenGeneral'])->name('reportes.resumen-general');
        Route::get('reportes/estado-citas', [ReporteController::class, 'estadoCitas'])->name('reportes.estado-citas');
        Route::get('reportes/pacientes-mas-citas', [ReporteController::class, 'pacientesMasCitas'])->name('reportes.pacientes-mas-citas');
    });
    
    // Rutas para ADMIN, DOCTOR y RECEPCIONISTA
    Route::middleware('role:admin,doctor,recepcionista')->group(function () {
        // Pacientes
        Route::resource('pacientes', PacienteController::class);
        
        // Citas
        Route::resource('citas', CitaController::class);
        Route::post('citas/{id}/update-estado', [CitaController::class, 'updateEstado'])->name('citas.update-estado');
        
        // Historial Clínico - Ver e index
        Route::get('historial', [HistorialClinicoController::class, 'index'])->name('historial.index');
        Route::get('historial/{id}', [HistorialClinicoController::class, 'show'])->name('historial.show');
    });
    
    // Rutas solo para DOCTOR (historial clínico - CRUD)
    Route::middleware('role:doctor')->group(function () {
        Route::get('historial/create', [HistorialClinicoController::class, 'create'])->name('historial.create');
        Route::post('historial', [HistorialClinicoController::class, 'store'])->name('historial.store');
        Route::get('historial/{id}/edit', [HistorialClinicoController::class, 'edit'])->name('historial.edit');
        Route::put('historial/{id}', [HistorialClinicoController::class, 'update'])->name('historial.update');
        Route::delete('historial/{id}', [HistorialClinicoController::class, 'destroy'])->name('historial.destroy');
    });
});
