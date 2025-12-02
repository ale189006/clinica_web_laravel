<?php

namespace App\Http\Controllers;

use App\Models\Especialidad;
use Illuminate\Http\Request;

class EspecialidadController extends Controller
{
    public function index(Request $request)
    {
        // Construir query base
        $query = Especialidad::query();
        
        // Filtro por nombre
        if ($request->filled('filtro_nombre')) {
            $searchTerm = $request->filtro_nombre;
            $query->where('especialidad', 'like', "%{$searchTerm}%");
        }
        
        // Lista todas las especialidades
        $especialidades = $query->orderBy('id_especialidad', 'DESC')->get();

        return view('especialidades.index', compact('especialidades'));
    }

    public function create()
    {
        return view('especialidades.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'especialidad' => 'required|max:100|unique:especialidad,especialidad'
        ]);

        Especialidad::create([
            'especialidad' => $request->especialidad
        ]);

        return redirect()->route('especialidades.index')
                         ->with('success', 'Especialidad registrada correctamente');
    }

    public function edit($id)
    {
        $especialidad = Especialidad::findOrFail($id);

        return view('especialidades.edit', compact('especialidad'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'especialidad' => 'required|max:100|unique:especialidad,especialidad,' . $id . ',id_especialidad'
        ]);

        $especialidad = Especialidad::findOrFail($id);

        $especialidad->update([
            'especialidad' => $request->especialidad
        ]);

        return redirect()->route('especialidades.index')
                         ->with('success', 'Especialidad actualizada correctamente');
    }

    public function destroy($id)
    {
        $especialidad = Especialidad::findOrFail($id);

        // Si hay doctores asociados, se bloquea porque tu migración tiene RESTRICT
        if ($especialidad->doctors()->count() > 0) {
            return redirect()->route('especialidades.index')
                             ->with('error', 'No se puede eliminar: hay doctores que usan esta especialidad.');
        }

        $especialidad->delete();

        return redirect()->route('especialidades.index')
                         ->with('success', 'Especialidad eliminada correctamente');
    }
}
