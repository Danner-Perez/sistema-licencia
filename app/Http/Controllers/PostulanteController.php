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
            'fecha_examen' => 'required|date',
        ]);

        Postulante::create($request->all());

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
            'dni' => 'required|digits:8|unique:postulantes,dni,' . $postulante->id,
            'nombres' => 'required|string|max:100',
            'apellidos' => 'required|string|max:100',
            'tipo_licencia' => 'required|string|max:10',
            'fecha_examen' => 'required|date',
        ]);

        $postulante->update($request->all());

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
