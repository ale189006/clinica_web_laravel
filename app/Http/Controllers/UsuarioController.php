<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use App\Models\Rol;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UsuarioController extends Controller
{
    public function index(Request $request)
    {
        // Construir query base
        $query = Usuario::with('rol');
        
        // Filtros
        if ($request->filled('filtro_usuario')) {
            $searchTerm = $request->filtro_usuario;
            $query->where('usuario', 'like', "%{$searchTerm}%")
                  ->orWhere('correo', 'like', "%{$searchTerm}%");
        }
        
        if ($request->filled('filtro_rol')) {
            $query->where('id_rol', $request->filtro_rol);
        }
        
        $usuarios = $query->orderBy('id_usuario', 'DESC')->get();
        
        // Datos para filtros
        $roles = Rol::all();
        
        return view('usuarios.index', compact('usuarios', 'roles'));
    }

    public function create()
    {
        // Para seleccionar el rol del usuario
        $roles = Rol::all();

        return view('usuarios.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'usuario' => 'required|max:50|unique:usuario,usuario',
            'correo'  => 'required|email|max:100|unique:usuario,correo',
            'password'=> 'required|min:6|max:255',
            'id_rol'  => 'required|exists:rol,id_rol'
        ]);

        Usuario::create([
            'usuario' => $request->usuario,
            'correo'  => $request->correo,
            'password'=> Hash::make($request->password),
            'id_rol'  => $request->id_rol,
        ]);

        return redirect()->route('usuarios.index')
                         ->with('success', 'Usuario registrado correctamente');
    }

    public function edit($id)
    {
        $usuario = Usuario::findOrFail($id);
        $roles = Rol::all();

        return view('usuarios.edit', compact('usuario', 'roles'));
    }

    public function update(Request $request, $id)
    {
        $usuario = Usuario::findOrFail($id);

        $request->validate([
            'usuario' => 'required|max:50|unique:usuario,usuario,' . $id . ',id_usuario',
            'correo'  => 'required|email|max:100|unique:usuario,correo,' . $id . ',id_usuario',
            'password'=> 'nullable|min:6|max:255',
            'id_rol'  => 'required|exists:rol,id_rol'
        ]);

        // Si no enviaron password, conservar la actual
        $password = $usuario->password;

        if ($request->filled('password')) {
            $password = Hash::make($request->password);
        }

        $usuario->update([
            'usuario' => $request->usuario,
            'correo'  => $request->correo,
            'password'=> $password,
            'id_rol'  => $request->id_rol,
        ]);

        return redirect()->route('usuarios.index')
                         ->with('success', 'Usuario actualizado correctamente');
    }

    public function destroy($id)
    {
        $usuario = Usuario::findOrFail($id);

        // Si tiene un doctor asociado, no se elimina
        if ($usuario->doctor()->count() > 0) {
            return redirect()->route('usuarios.index')
                             ->with('error', 'No se puede eliminar: este usuario tiene un doctor asociado.');
        }

        $usuario->delete();

        return redirect()->route('usuarios.index')
                         ->with('success', 'Usuario eliminado correctamente');
    }
}
