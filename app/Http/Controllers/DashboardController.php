<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Cita;
use App\Models\Paciente;
use App\Models\Doctor;
use App\Models\HistorialClinico;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        if (!$user->rol) {
            Auth::logout();
            return redirect()->route('login')->with('error', 'Tu usuario no tiene un rol asignado');
        }

        $rol = strtolower($user->rol->rol);

        // Datos para el dashboard según el rol
        $stats = [];
        
        if ($rol === 'admin' || $rol === 'recepcionista') {
            // Estadísticas generales para admin y recepcionista
            $stats = [
                'total_citas' => Cita::count(),
                'citas_hoy' => Cita::whereDate('fecha', Carbon::today())->count(),
                'total_pacientes' => Paciente::count(),
                'total_doctores' => Doctor::count(),
                'citas_pendientes' => Cita::whereHas('estado', function($q) {
                    $q->where('estado', 'Pendiente');
                })->count(),
                'citas_atendidas' => Cita::whereHas('estado', function($q) {
                    $q->where('estado', 'Atendida');
                })->count(),
            ];

            // Citas recientes
            $citas_recientes = Cita::with(['doctor.especialidad', 'paciente', 'estado'])
                ->orderBy('fecha', 'desc')
                ->orderBy('hora', 'desc')
                ->limit(5)
                ->get();
        } elseif ($rol === 'doctor') {
            // Estadísticas para doctor (solo sus datos)
            $doctor = $user->doctor;
            
            if (!$doctor) {
                return redirect()->route('login')
                    ->with('error', 'Tu usuario no está vinculado a un doctor');
            }

            $stats = [
                'total_citas' => Cita::where('id_doctor', $doctor->id_doctor)->count(),
                'citas_hoy' => Cita::where('id_doctor', $doctor->id_doctor)
                    ->whereDate('fecha', Carbon::today())
                    ->count(),
                'citas_pendientes' => Cita::where('id_doctor', $doctor->id_doctor)
                    ->whereHas('estado', function($q) {
                        $q->where('estado', 'Pendiente');
                    })->count(),
                'total_historiales' => HistorialClinico::whereHas('cita', function($q) use ($doctor) {
                    $q->where('id_doctor', $doctor->id_doctor);
                })->count(),
            ];

            // Citas recientes del doctor
            $citas_recientes = Cita::with(['doctor.especialidad', 'paciente', 'estado'])
                ->where('id_doctor', $doctor->id_doctor)
                ->orderBy('fecha', 'desc')
                ->orderBy('hora', 'desc')
                ->limit(5)
                ->get();
        }

        return view('dashboard.index', compact('stats', 'citas_recientes', 'rol'));
    }
}



