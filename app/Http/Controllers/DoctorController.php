<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\Especialidad;
use App\Models\Usuario;
use App\Models\Rol;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DoctorController extends Controller
{
    public function index(Request $request)
    {
        // Construir query base
        $query = Doctor::with(['especialidad', 'usuario']);
        
        // Filtros
        if ($request->filled('filtro_especialidad')) {
            $query->where('id_especialidad', $request->filtro_especialidad);
        }
        
        if ($request->filled('filtro_nombre')) {
            $searchTerm = $request->filtro_nombre;
            $query->where('nombre_doctor', 'like', "%{$searchTerm}%");
        }
        
        $doctores = $query->orderBy('id_doctor', 'DESC')->get();
        
        // Datos para filtros
        $especialidades = Especialidad::all();
        
        return view('doctores.index', compact('doctores', 'especialidades'));
    }

    public function show($id)
    {
        $doctor = Doctor::with([
            'especialidad', 
            'usuario',
            'citas.paciente',
            'citas.estado',
            'citas.historial'
        ])->findOrFail($id);
        return view('doctores.show', compact('doctor'));
    }

    public function create()
    {
        $especialidades = Especialidad::all();
        
        // Obtener el ID del rol "doctor" de forma más segura
        $rolDoctor = Rol::whereRaw('LOWER(rol) = ?', ['doctor'])->first();
        
        if (!$rolDoctor) {
            return redirect()->route('doctores.index')
                ->with('error', 'No existe el rol "doctor" en el sistema. Por favor, asegúrese de crear el rol "doctor" primero.');
        }
        
        // Solo usuarios con rol doctor que no tengan doctor asociado
        // Primero obtenemos los IDs de usuarios que ya tienen doctor
        $usuariosConDoctor = Doctor::whereNotNull('id_usuario')->pluck('id_usuario')->toArray();
        
        // Obtener todos los usuarios con rol doctor
        $query = Usuario::where('id_rol', $rolDoctor->id_rol);
        
        // Excluir usuarios que ya tienen doctor asociado
        if (!empty($usuariosConDoctor)) {
            $query->whereNotIn('id_usuario', $usuariosConDoctor);
        }
        
        $usuarios = $query->with('rol')
            ->orderBy('usuario', 'asc')
            ->get();

        return view('doctores.create', compact('especialidades', 'usuarios', 'rolDoctor'));
    }

    public function store(Request $request)
    {
        // Validar que haya usuarios disponibles
        $rolDoctor = Rol::whereRaw('LOWER(rol) = ?', ['doctor'])->first();
        if (!$rolDoctor) {
            return redirect()->route('doctores.index')
                ->with('error', 'No existe el rol "doctor" en el sistema.');
        }
        
        $request->validate([
            'nombre_doctor'  => 'required|max:150',
            'telefono'       => 'nullable|max:20',
            'correo'         => 'nullable|email|max:100',
            'id_especialidad'=> 'required|exists:especialidad,id_especialidad',
            'id_usuario'     => [
                'required',
                'exists:usuario,id_usuario',
                'unique:doctor,id_usuario',
                function ($attribute, $value, $fail) use ($rolDoctor) {
                    $usuario = Usuario::find($value);
                    if ($usuario && $usuario->id_rol !== $rolDoctor->id_rol) {
                        $fail('El usuario seleccionado no tiene el rol de doctor.');
                    }
                },
            ],
        ]);

        Doctor::create($request->all());

        return redirect()->route('doctores.index')
                         ->with('success', 'Doctor registrado correctamente');
    }

    public function edit($id)
    {
        $doctor = Doctor::findOrFail($id);
        $especialidades = Especialidad::all();
        // Obtener el ID del rol "doctor"
        $rolDoctor = Rol::whereRaw('LOWER(rol) = ?', ['doctor'])->first();
        
        if (!$rolDoctor) {
            return redirect()->route('doctores.index')
                ->with('error', 'No existe el rol "doctor" en el sistema.');
        }
        
        // Solo usuarios con rol doctor
        // Primero obtenemos los IDs de usuarios que ya tienen doctor (excepto el actual)
        $usuariosConDoctor = Doctor::where('id_doctor', '!=', $doctor->id_doctor)
            ->pluck('id_usuario')
            ->toArray();
        
        // Luego obtenemos usuarios con rol doctor que NO estén en esa lista o sean el usuario actual
        $usuarios = Usuario::where('id_rol', $rolDoctor->id_rol)
            ->where(function($q) use ($doctor, $usuariosConDoctor) {
                $q->where('id_usuario', $doctor->id_usuario)
                  ->orWhereNotIn('id_usuario', $usuariosConDoctor);
            })
            ->with('rol')
            ->orderBy('usuario', 'asc')
            ->get();

        return view('doctores.edit', compact('doctor', 'especialidades', 'usuarios'));
    }

    public function update(Request $request, $id)
    {
        $doctor = Doctor::findOrFail($id);
        
        $request->validate([
            'nombre_doctor'  => 'required|max:150',
            'telefono'       => 'nullable|max:20',
            'correo'         => 'nullable|email|max:100',
            'id_especialidad'=> 'required|exists:especialidad,id_especialidad',
            'id_usuario'     => 'required|exists:usuario,id_usuario|unique:doctor,id_usuario,' . $id . ',id_doctor',
        ]);

        $doctor->update($request->all());

        return redirect()->route('doctores.index')
                         ->with('success', 'Doctor actualizado correctamente');
    }

    public function destroy($id)
    {
        $doctor = Doctor::findOrFail($id);

        // IMPORTANTE: si tiene citas no podrá eliminar por restricción
        try {
            $doctor->delete();
        } catch (\Throwable $e) {
            return redirect()->route('doctores.index')
                             ->with('error', 'No se puede eliminar. El doctor tiene citas asociadas.');
        }

        return redirect()->route('doctores.index')
                         ->with('success', 'Doctor eliminado correctamente');
    }
}
