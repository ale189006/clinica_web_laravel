<?php
namespace App\Http\Controllers;

use App\Models\Cita;
use App\Models\Doctor;
use App\Models\Paciente;
use App\Models\EstadoCita;
use App\Models\HistorialClinico;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CitaController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $rol = strtolower($user->rol->rol ?? '');
        
        // Construir query base
        $query = Cita::with(['doctor.especialidad', 'paciente', 'estado']);
        
        // Si es doctor, solo mostrar sus citas
        if ($rol === 'doctor') {
            $doctor = $user->doctor;
            if (!$doctor) {
                return redirect()->route('dashboard')
                    ->with('error', 'Tu usuario no está vinculado a un doctor');
            }
            $query->where('id_doctor', $doctor->id_doctor);
        }
        
        // Filtros
        if ($request->filled('filtro_doctor')) {
            $query->where('id_doctor', $request->filtro_doctor);
        }
        
        if ($request->filled('filtro_estado')) {
            $query->where('id_estado', $request->filtro_estado);
        }
        
        if ($request->filled('filtro_fecha')) {
            $query->whereDate('fecha', $request->filtro_fecha);
        }
        
        if ($request->filled('filtro_paciente')) {
            $searchTerm = $request->filtro_paciente;
            $query->whereHas('paciente', function($q) use ($searchTerm) {
                $q->where('nombres', 'like', "%{$searchTerm}%")
                  ->orWhere('apellidos', 'like', "%{$searchTerm}%")
                  ->orWhere('dni', 'like', "%{$searchTerm}%");
            });
        }
        
        // CAMBIO IMPORTANTE: usar paginate() en lugar de get()
        $citas = $query->orderBy('fecha', 'desc')
                      ->orderBy('hora', 'desc')
                      ->paginate(10); // 10 citas por página
        
        // Datos para filtros
        $doctores = Doctor::with('especialidad')->get();
        $estados = EstadoCita::all();
        
        return view('citas.index', compact('citas', 'doctores', 'estados'));
    }

    public function create()
    {
        $user = Auth::user();
        $rol = strtolower($user->rol->rol ?? '');
        
        // Si es doctor, solo puede crear citas para él mismo
        if ($rol === 'doctor') {
            $doctor = $user->doctor;
            if (!$doctor) {
                return redirect()->route('dashboard')
                    ->with('error', 'Tu usuario no está vinculado a un doctor');
            }
            $doctores = Doctor::where('id_doctor', $doctor->id_doctor)->get();
        } else {
            $doctores = Doctor::with('especialidad')->get();
        }
        
        $pacientes = Paciente::all();
        $estados = EstadoCita::all();
        
        return view('citas.create', compact('doctores', 'pacientes', 'estados'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $rol = strtolower($user->rol->rol ?? '');
        
        // Si es doctor, solo puede crear citas para él mismo
        if ($rol === 'doctor') {
            $doctor = $user->doctor;
            if (!$doctor) {
                return redirect()->route('dashboard')
                    ->with('error', 'Tu usuario no está vinculado a un doctor');
            }
            $request->merge(['id_doctor' => $doctor->id_doctor]);
        }
        
        $request->validate([
            'fecha' => 'required|date',
            'hora' => 'required',
            'id_doctor' => 'required|exists:doctor,id_doctor',
            'id_paciente' => 'required|exists:paciente,id_paciente',
            'id_estado' => 'required|exists:estado_cita,id_estado',
            'motivo' => 'nullable|max:255'
        ]);

        Cita::create($request->all());
        return redirect()->route('citas.index')->with('success','Cita creada correctamente');
    }

    public function show($id)
    {
        $user = Auth::user();
        $rol = strtolower($user->rol->rol ?? '');
        
        $cita = Cita::with(['doctor.especialidad', 'paciente', 'estado', 'historial'])
            ->findOrFail($id);
        
        // Si es doctor, solo puede ver sus citas
        if ($rol === 'doctor') {
            $doctor = $user->doctor;
            if (!$doctor || $cita->id_doctor !== $doctor->id_doctor) {
                abort(403, 'No tienes permiso para ver esta cita');
            }
        }
        
        // Verificar si tiene historial
        $tieneHistorial = $cita->historial ? true : false;
        
        // Estados que permiten edición
        $estadoNombre = strtolower($cita->estado->estado ?? '');
        $puedeEditar = in_array($estadoNombre, ['pendiente', 'reprogramada']);
        $estaAtendida = $estadoNombre === 'atendida';
        $estaCancelada = $estadoNombre === 'cancelada';
        
        // Admin, doctor y recepcionista pueden editar (si el estado lo permite)
        // En la vista se controlará si el botón aparece según el estado
        return view('citas.show', compact('cita', 'puedeEditar', 'estaAtendida', 'estaCancelada', 'tieneHistorial', 'rol'));
    }

    public function edit($id)
    {
        $user = Auth::user();
        $rol = strtolower($user->rol->rol ?? '');
        
        $cita = Cita::with(['doctor.especialidad', 'paciente', 'estado'])
            ->findOrFail($id);
        
        // Si es doctor, solo puede editar sus citas
        // Admin y recepcionista pueden editar cualquier cita
        if ($rol === 'doctor') {
            $doctor = $user->doctor;
            if (!$doctor || $cita->id_doctor !== $doctor->id_doctor) {
                abort(403, 'No tienes permiso para editar esta cita');
            }
        }
        
        // Verificar que el estado permita edición
        $estadoNombre = strtolower($cita->estado->estado ?? '');
        if (!in_array($estadoNombre, ['pendiente', 'reprogramada'])) {
            return redirect()->route('citas.show', $id)
                ->with('error', 'No se puede editar una cita ' . $cita->estado->estado);
        }
        
        // Si es doctor, solo puede seleccionar él mismo
        if ($rol === 'doctor') {
            $doctor = $user->doctor;
            $doctores = Doctor::where('id_doctor', $doctor->id_doctor)->get();
        } else {
            $doctores = Doctor::with('especialidad')->get();
        }
        
        $pacientes = Paciente::all();
        $estados = EstadoCita::all();
        
        return view('citas.edit', compact('cita', 'doctores', 'pacientes', 'estados'));
    }

    public function update(Request $request, $id)
    {
        $user = Auth::user();
        $rol = strtolower($user->rol->rol ?? '');
        
        $cita = Cita::findOrFail($id);
        
        // Si es doctor, solo puede editar sus citas
        // Admin y recepcionista pueden editar cualquier cita
        if ($rol === 'doctor') {
            $doctor = $user->doctor;
            if (!$doctor || $cita->id_doctor !== $doctor->id_doctor) {
                abort(403, 'No tienes permiso para editar esta cita');
            }
        }
        
        // Verificar que el estado permita edición
        $estadoNombre = strtolower($cita->estado->estado ?? '');
        if (!in_array($estadoNombre, ['pendiente', 'reprogramada'])) {
            return redirect()->route('citas.show', $id)
                ->with('error', 'No se puede editar una cita ' . $cita->estado->estado);
        }
        
        // Si es doctor, solo puede asignar a él mismo
        if ($rol === 'doctor') {
            $doctor = $user->doctor;
            $request->merge(['id_doctor' => $doctor->id_doctor]);
        }
        
        $request->validate([
            'fecha' => 'required|date',
            'hora' => 'required',
            'id_doctor' => 'required|exists:doctor,id_doctor',
            'id_paciente' => 'required|exists:paciente,id_paciente',
            'id_estado' => 'required|exists:estado_cita,id_estado',
            'motivo' => 'nullable|max:255'
        ]);

        $cita->update($request->only(['fecha', 'hora', 'id_doctor', 'id_paciente', 'id_estado', 'motivo']));
        return redirect()->route('citas.index')->with('success','Cita actualizada correctamente');
    }

    public function updateEstado(Request $request, $id)
    {
        $user = Auth::user();
        $rol = strtolower($user->rol->rol ?? '');
        
        $cita = Cita::findOrFail($id);
        
        // Si es doctor, solo puede cambiar estado de sus citas
        if ($rol === 'doctor') {
            $doctor = $user->doctor;
            if (!$doctor || $cita->id_doctor !== $doctor->id_doctor) {
                abort(403, 'No tienes permiso para modificar esta cita');
            }
        }
        
        $request->validate([
            'id_estado' => 'required|exists:estado_cita,id_estado'
        ]);
        
        $cita->update(['id_estado' => $request->id_estado]);
        
        return redirect()->route('citas.show', $id)
            ->with('success', 'Estado de la cita actualizado correctamente');
    }

    public function destroy($id)
    {
        $user = Auth::user();
        $rol = strtolower($user->rol->rol ?? '');
        
        $cita = Cita::findOrFail($id);
        
        // Si es doctor, solo puede eliminar sus citas
        if ($rol === 'doctor') {
            $doctor = $user->doctor;
            if (!$doctor || $cita->id_doctor !== $doctor->id_doctor) {
                abort(403, 'No tienes permiso para eliminar esta cita');
            }
        }
        
        $cita->delete();
        return redirect()->route('citas.index')->with('success','Cita eliminada correctamente');
    }
}