<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Cita;
use App\Models\Paciente;
use App\Models\Doctor;
use App\Models\EstadoCita;
use App\Models\HistorialClinico;
use Carbon\Carbon;

class ReporteController extends Controller
{
    public function index()
    {
        return view('reportes.index');
    }

    public function resumenGeneral()
    {
        $user = Auth::user();
        $rol = strtolower($user->rol->rol ?? '');
        
        // Estadísticas generales
        $stats = [
            'total_citas' => Cita::count(),
            'total_pacientes' => Paciente::count(),
            'total_doctores' => Doctor::count(),
            'total_historiales' => HistorialClinico::count(),
            'citas_hoy' => Cita::whereDate('fecha', Carbon::today())->count(),
            'citas_mes' => Cita::whereMonth('fecha', Carbon::now()->month)
                             ->whereYear('fecha', Carbon::now()->year)
                             ->count(),
        ];
        
        // Citas por estado
        $citasPorEstado = EstadoCita::withCount('citas')->get();
        
        // Citas por mes (últimos 6 meses)
        $citasPorMes = Cita::select(
                DB::raw('DATE_FORMAT(fecha, "%Y-%m") as mes'),
                DB::raw('COUNT(*) as total')
            )
            ->where('fecha', '>=', Carbon::now()->subMonths(6))
            ->groupBy('mes')
            ->orderBy('mes')
            ->get();
        
        return view('reportes.resumen-general', compact('stats', 'citasPorEstado', 'citasPorMes'));
    }

    public function estadoCitas()
    {
        $user = Auth::user();
        $rol = strtolower($user->rol->rol ?? '');
        
        // Citas agrupadas por estado
        $estados = EstadoCita::with(['citas' => function($query) {
            $query->with(['doctor.especialidad', 'paciente'])
                  ->orderBy('fecha', 'desc')
                  ->orderBy('hora', 'desc');
        }])->get();
        
        // Total por estado
        $totalPorEstado = [];
        foreach ($estados as $estado) {
            $totalPorEstado[$estado->estado] = $estado->citas->count();
        }
        
        return view('reportes.estado-citas', compact('estados', 'totalPorEstado'));
    }

    public function pacientesMasCitas()
    {
        $user = Auth::user();
        $rol = strtolower($user->rol->rol ?? '');
        
        // Pacientes con más citas
        $pacientesMasCitas = Paciente::withCount('citas')
            ->orderBy('citas_count', 'desc')
            ->limit(10)
            ->get();
        
        // También incluir detalles de las citas
        foreach ($pacientesMasCitas as $paciente) {
            $paciente->citas_recientes = $paciente->citas()
                ->with(['doctor.especialidad', 'estado'])
                ->orderBy('fecha', 'desc')
                ->limit(5)
                ->get();
        }
        
        return view('reportes.pacientes-mas-citas', compact('pacientesMasCitas'));
    }

    public function estadisticasDoctor()
    {
        $user = Auth::user();
        $rol = strtolower($user->rol->rol ?? '');
        
        if ($rol !== 'admin') {
            abort(403, 'Solo los administradores pueden ver este reporte');
        }
        
        // Estadísticas por doctor
        $doctores = Doctor::with('especialidad')->get();
        
        $estadisticas = [];
        
        foreach ($doctores as $doctor) {
            $totalCitas = Cita::where('id_doctor', $doctor->id_doctor)->count();
            $citasAtendidas = Cita::where('id_doctor', $doctor->id_doctor)
                ->whereHas('estado', function($q) {
                    $q->where('estado', 'Atendida');
                })->count();
            $citasPendientes = Cita::where('id_doctor', $doctor->id_doctor)
                ->whereHas('estado', function($q) {
                    $q->where('estado', 'Pendiente');
                })->count();
            $citasCanceladas = Cita::where('id_doctor', $doctor->id_doctor)
                ->whereHas('estado', function($q) {
                    $q->where('estado', 'Cancelada');
                })->count();
            $totalHistoriales = HistorialClinico::whereHas('cita', function($q) use ($doctor) {
                $q->where('id_doctor', $doctor->id_doctor);
            })->count();
            
            $estadisticas[] = [
                'doctor' => $doctor,
                'total_citas' => $totalCitas,
                'citas_atendidas' => $citasAtendidas,
                'citas_pendientes' => $citasPendientes,
                'citas_canceladas' => $citasCanceladas,
                'total_historiales' => $totalHistoriales,
                'porcentaje_atendidas' => $totalCitas > 0 ? round(($citasAtendidas / $totalCitas) * 100, 2) : 0,
            ];
        }
        
        // Ordenar por total de citas descendente
        usort($estadisticas, function($a, $b) {
            return $b['total_citas'] - $a['total_citas'];
        });
        
        return view('reportes.estadisticas-doctor', compact('estadisticas'));
    }
}



