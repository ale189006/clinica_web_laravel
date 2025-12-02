<?php

namespace App\Http\Controllers;

use App\Models\Paciente;
use App\Models\Cita;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PacienteController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $rol = strtolower($user->rol->rol ?? '');
        
        // Construir query base
        $query = Paciente::query();
        
        // Si es doctor, solo mostrar sus pacientes
        if ($rol === 'doctor') {
            $doctor = $user->doctor;
            if (!$doctor) {
                return redirect()->route('dashboard')
                    ->with('error', 'Tu usuario no está vinculado a un doctor');
            }
            
            // Obtener IDs de pacientes que tienen citas con este doctor
            $pacienteIds = Cita::where('id_doctor', $doctor->id_doctor)
                ->distinct()
                ->pluck('id_paciente');
            
            $query->whereIn('id_paciente', $pacienteIds);
        }
        
        // Filtros
        if ($request->filled('filtro_nombre')) {
            $searchTerm = $request->filtro_nombre;
            $query->where(function($q) use ($searchTerm) {
                $q->where('nombres', 'like', "%{$searchTerm}%")
                  ->orWhere('apellidos', 'like', "%{$searchTerm}%");
            });
        }
        
        if ($request->filled('filtro_dni')) {
            $query->where('dni', 'like', "%{$request->filtro_dni}%");
        }
        
        $pacientes = $query->orderBy('id_paciente', 'DESC')->get();

        return view('pacientes.index', compact('pacientes'));
    }

    public function create()
    {
        return view('pacientes.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'dni'        => 'nullable|max:15|unique:paciente,dni',
            'nombres'    => 'required|max:150',
            'apellidos'  => 'nullable|max:150',
            'edad'       => 'nullable|integer|min:0|max:120',
            'telefono'   => 'nullable|max:20',
            'direccion'  => 'nullable|max:200',
        ]);

        Paciente::create($request->all());

        return redirect()->route('pacientes.index')
                         ->with('success', 'Paciente registrado correctamente');
    }

    public function edit($id)
    {
        $paciente = Paciente::findOrFail($id);

        return view('pacientes.edit', compact('paciente'));
    }

    public function update(Request $request, $id)
    {
        $paciente = Paciente::findOrFail($id);

        $request->validate([
            'dni'        => 'nullable|max:15|unique:paciente,dni,' . $id . ',id_paciente',
            'nombres'    => 'required|max:150',
            'apellidos'  => 'nullable|max:150',
            'edad'       => 'nullable|integer|min:0|max:120',
            'telefono'   => 'nullable|max:20',
            'direccion'  => 'nullable|max:200',
        ]);

        $paciente->update($request->all());

        return redirect()->route('pacientes.index')
                         ->with('success', 'Paciente actualizado correctamente');
    }

    public function destroy($id)
    {
        $user = Auth::user();
        $rol = strtolower($user->rol->rol ?? '');
        
        // Recepcionista no puede eliminar
        if ($rol === 'recepcionista') {
            abort(403, 'No tienes permiso para eliminar pacientes');
        }
        
        $paciente = Paciente::findOrFail($id);

        // Si el paciente tiene citas, no podrá eliminarse (por integridad referencial)
        if ($paciente->citas()->count() > 0) {
            return redirect()->route('pacientes.index')
                             ->with('error', 'No se puede eliminar: el paciente tiene citas registradas.');
        }

        $paciente->delete();

        return redirect()->route('pacientes.index')
                         ->with('success', 'Paciente eliminado correctamente');
    }
}
