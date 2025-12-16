<?php

namespace App\Http\Controllers;

use App\Models\Postulante;
use Illuminate\Http\Request;

class PostulanteController extends Controller
{
    /**
     * Mostrar listado de postulantes
     */
    public function index()
    {
        $postulantes = Postulante::orderBy('created_at', 'desc')->get();
        return view('postulantes.index', compact('postulantes'));
    }

    /**
     * Mostrar formulario de creación
     */
    public function create()
    {
        return view('postulantes.create');
    }

    /**
     * Guardar nuevo postulante
     */
    public function store(Request $request)
    {
        $request->validate([
            'dni' => 'required|digits:8|unique:postulantes,dni',
            'nombres' => 'required|string|max:100',
            'apellidos' => 'required|string|max:100',
            'tipo_licencia' => 'required|string|max:10',
            'fecha_psicofisico' => 'nullable|date',
        ]);

        Postulante::create([
            'dni' => $request->dni,
            'nombres' => $request->nombres,
            'apellidos' => $request->apellidos,
            'tipo_licencia' => $request->tipo_licencia,
            'fecha_registro' => now(),
            'fecha_psicofisico' => $request->fecha_psicofisico,
            'registrado_por' => auth()->id(),
        ]);

        return redirect()
            ->route('postulantes.index')
            ->with('success', 'Postulante registrado correctamente');
    }

    /**
     * Mostrar formulario de edición
     */
    public function edit(Postulante $postulante)
    {
        return view('postulantes.edit', compact('postulante'));
    }

    /**
     * Actualizar postulante
     */
    public function update(Request $request, Postulante $postulante)
    {
        $request->validate([
            'dni' => 'required|digits:8|unique:postulantes,dni,' 
                . $postulante->id_postulante . ',id_postulante',
            'nombres' => 'required|string|max:100',
            'apellidos' => 'required|string|max:100',
            'tipo_licencia' => 'required|string|max:10',
            'fecha_psicofisico' => 'nullable|date',
        ]);

        $postulante->update([
            'dni' => $request->dni,
            'nombres' => $request->nombres,
            'apellidos' => $request->apellidos,
            'tipo_licencia' => $request->tipo_licencia,
            'fecha_psicofisico' => $request->fecha_psicofisico,
        ]);

        return redirect()
            ->route('postulantes.index')
            ->with('success', 'Postulante actualizado correctamente');
    }

    /**
     * Eliminar postulante
     */
    public function destroy(Postulante $postulante)
    {
        $postulante->delete();

        return redirect()
            ->route('postulantes.index')
            ->with('success', 'Postulante eliminado correctamente');
    }
}
