<?php

namespace App\Http\Controllers;

use App\Models\HistorialClinico;
use App\Models\Cita;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HistorialClinicoController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $rol = strtolower($user->rol->rol ?? '');
        
        // Construir query base
        $query = HistorialClinico::with(['cita.doctor.especialidad', 'cita.paciente', 'cita.estado']);
        
        // Si es doctor, solo mostrar sus historiales
        if ($rol === 'doctor') {
            $doctor = $user->doctor;
            if (!$doctor) {
                return redirect()->route('dashboard')
                    ->with('error', 'Tu usuario no está vinculado a un doctor');
            }
            
            $query->whereHas('cita', function($q) use ($doctor) {
                $q->where('id_doctor', $doctor->id_doctor);
            });
        }
        
        // Filtro por paciente
        if ($request->filled('filtro_paciente')) {
            $searchTerm = $request->filtro_paciente;
            $query->whereHas('cita.paciente', function($q) use ($searchTerm) {
                $q->where('nombres', 'like', "%{$searchTerm}%")
                  ->orWhere('apellidos', 'like', "%{$searchTerm}%")
                  ->orWhere('dni', 'like', "%{$searchTerm}%");
            });
        }
        
        $historiales = $query->orderBy('id_historial', 'DESC')->get();

        return view('historial.index', compact('historiales', 'rol'));
    }

    public function create(Request $request)
    {
        $user = Auth::user();
        $doctor = $user->doctor;
        
        if (!$doctor) {
            return redirect()->route('dashboard')
                ->with('error', 'Tu usuario no está vinculado a un doctor');
        }
        
        // Solo citas atendidas del doctor que no tengan historial
        $citas = Cita::with(['doctor', 'paciente', 'estado', 'historial'])
            ->where('id_doctor', $doctor->id_doctor)
            ->whereHas('estado', function($q) {
                $q->where('estado', 'Atendida');
            })
            ->whereDoesntHave('historial')
            ->get();
        
        // Si viene desde una cita específica
        $id_cita = $request->get('id_cita');
        $citaSeleccionada = null;
        if ($id_cita) {
            $citaSeleccionada = Cita::with(['doctor', 'paciente', 'estado', 'historial'])
                ->where('id_doctor', $doctor->id_doctor)
                ->where('id_cita', $id_cita)
                ->whereHas('estado', function($q) {
                    $q->where('estado', 'Atendida');
                })
                ->whereDoesntHave('historial')
                ->first();
            
            if ($citaSeleccionada && !$citas->contains('id_cita', $id_cita)) {
                $citas->push($citaSeleccionada);
            }
        }

        return view('historial.create', compact('citas', 'citaSeleccionada'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $doctor = $user->doctor;
        
        if (!$doctor) {
            return redirect()->route('dashboard')
                ->with('error', 'Tu usuario no está vinculado a un doctor');
        }
        
        $request->validate([
            'diagnostico'   => 'nullable|max:255',
            'tratamiento'   => 'nullable|max:255',
            'observaciones' => 'nullable',
        ]);
        
        // Verificar que la cita sea del doctor y esté atendida
        $cita = Cita::with('estado')->findOrFail($request->id_cita);
        
        if ($cita->id_doctor !== $doctor->id_doctor) {
            abort(403, 'No tienes permiso para agregar historial a esta cita');
        }
        
        if (strtolower($cita->estado->estado) !== 'atendida') {
            return redirect()->back()
                ->with('error', 'Solo se puede agregar historial a citas atendidas');
        }
        
        // Verificar que no tenga historial ya
        if ($cita->historial) {
            return redirect()->back()
                ->with('error', 'Esta cita ya tiene un historial clínico');
        }

        HistorialClinico::create([
            'id_cita'       => $request->id_cita,
            'diagnostico'   => $request->diagnostico,
            'tratamiento'   => $request->tratamiento,
            'observaciones' => $request->observaciones,
            'fecha_registro'=> now()
        ]);

        return redirect()->route('historial.index')
                         ->with('success', 'Registro clínico creado correctamente');
    }

    public function show($id)
    {
        $user = Auth::user();
        $rol = strtolower($user->rol->rol ?? '');
        
        $historial = HistorialClinico::with([
            'cita.doctor.especialidad', 
            'cita.paciente', 
            'cita.estado'
        ])->findOrFail($id);
        
        // Si es doctor, solo puede ver sus historiales
        if ($rol === 'doctor') {
            $doctor = $user->doctor;
            if (!$doctor || $historial->cita->id_doctor !== $doctor->id_doctor) {
                abort(403, 'No tienes permiso para ver este historial');
            }
        }
        
        // Recepcionista solo ve datos generales, no detalles clínicos completos
        $mostrarDetallesCompletos = ($rol === 'admin' || $rol === 'doctor');

        return view('historial.show', compact('historial', 'rol', 'mostrarDetallesCompletos'));
    }

    public function edit($id)
    {
        $user = Auth::user();
        $doctor = $user->doctor;
        
        if (!$doctor) {
            return redirect()->route('dashboard')
                ->with('error', 'Tu usuario no está vinculado a un doctor');
        }
        
        $historial = HistorialClinico::with(['cita.doctor', 'cita.paciente'])->findOrFail($id);
        
        // Solo el doctor dueño puede editar
        if ($historial->cita->id_doctor !== $doctor->id_doctor) {
            abort(403, 'No tienes permiso para editar este historial');
        }

        return view('historial.edit', compact('historial'));
    }

    public function update(Request $request, $id)
    {
        $user = Auth::user();
        $doctor = $user->doctor;
        
        if (!$doctor) {
            return redirect()->route('dashboard')
                ->with('error', 'Tu usuario no está vinculado a un doctor');
        }
        
        $historial = HistorialClinico::with('cita')->findOrFail($id);
        
        // Solo el doctor dueño puede editar
        if ($historial->cita->id_doctor !== $doctor->id_doctor) {
            abort(403, 'No tienes permiso para editar este historial');
        }
        
        $request->validate([
            'diagnostico'   => 'nullable|max:255',
            'tratamiento'   => 'nullable|max:255',
            'observaciones' => 'nullable',
        ]);

        $historial->update([
            'diagnostico'   => $request->diagnostico,
            'tratamiento'   => $request->tratamiento,
            'observaciones' => $request->observaciones
        ]);

        return redirect()->route('historial.index')
                         ->with('success', 'Historial clínico actualizado correctamente');
    }

    public function destroy($id)
    {
        $user = Auth::user();
        $doctor = $user->doctor;
        
        if (!$doctor) {
            return redirect()->route('dashboard')
                ->with('error', 'Tu usuario no está vinculado a un doctor');
        }
        
        $historial = HistorialClinico::with('cita')->findOrFail($id);
        
        // Solo el doctor dueño puede eliminar
        if ($historial->cita->id_doctor !== $doctor->id_doctor) {
            abort(403, 'No tienes permiso para eliminar este historial');
        }
        
        $historial->delete();

        return redirect()->route('historial.index')
                         ->with('success', 'Historial clínico eliminado correctamente');
    }
}
