<?php

namespace App\Http\Controllers;

use App\Models\Postulante;
use App\Models\ProcesoLicencia;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PostulanteController extends Controller
{
    /**
     * Listar postulantes registrados hoy
     */
    public function index(Request $request)
    {
        $fecha = $request->get('fecha', now()->toDateString());

        $postulantes = Postulante::whereDate('created_at', $fecha)
            ->with('procesosLicencia')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('postulantes.index', compact('postulantes'));
    }

    /**
     * Formulario para crear nuevo postulante
     */
    public function create()
    {
        return view('postulantes.create');
    }

    /**
     * Guardar nuevo postulante y proceso de licencia automáticamente
     */
    public function store(Request $request)
    {
        $request->validate([
            'dni'                => 'required|digits:8',
            'nombres'            => 'required|string|max:100',
            'apellidos'          => 'required|string|max:100',
            'fecha_psicosomatico'=> 'required|date',
            'tipo_licencia'      => 'required|in:A1,A2A,A2B,A3',
        ]);

        $hoy = now()->toDateString();

        // ⚠ Verificar si ya hay un postulante con el mismo DNI registrado hoy
        $existeHoy = Postulante::where('dni', $request->dni)
                        ->whereDate('created_at', $hoy)
                        ->exists();

        if ($existeHoy) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['dni' => 'Este postulante ya fue registrado hoy.']);
        }

        // Crear postulante
        $postulante = Postulante::create([
            'dni'                => $request->dni,
            'nombres'            => $request->nombres,
            'apellidos'          => $request->apellidos,
            'fecha_psicosomatico'=> $request->fecha_psicosomatico,
            'registrado_por'     => auth()->id(),
        ]);

        // Crear proceso de licencia automáticamente
        ProcesoLicencia::create([
            'postulante_id' => $postulante->id_postulante,
            'tipo_licencia' => $request->tipo_licencia,
            'fecha_inicio'  => $hoy,
            'estado'        => 'EN_PROCESO',
        ]);

        return redirect()
            ->route('postulantes.index')
            ->with('success', 'Postulante y proceso de licencia registrados correctamente');
    }


    /**
     * Formulario para editar postulante
     */
    public function edit(Postulante $postulante)
    {
        return view('postulantes.edit', compact('postulante'));
    }

    /**
     * Actualizar datos del postulante y proceso activo
     */
    public function update(Request $request, Postulante $postulante)
    {
        $request->validate([
            'nombres'             => 'required|string|max:100',
            'apellidos'           => 'required|string|max:100',
            'fecha_psicosomatico' => 'required|date',
            'validado_reniec'     => 'required|boolean',
            'tipo_licencia'       => 'nullable|in:A1,A2A,A2B,A3',
        ]);

        // Actualizar datos del postulante
        $postulante->update([
            'nombres'             => $request->nombres,
            'apellidos'           => $request->apellidos,
            'fecha_psicosomatico' => $request->fecha_psicosomatico,
            'validado_reniec'     => $request->validado_reniec,
        ]);

        // Actualizar tipo de licencia del proceso activo
        if ($request->filled('tipo_licencia') && $postulante->procesoActivo) {
            $postulante->procesoActivo->update([
                'tipo_licencia' => $request->tipo_licencia,
            ]);
        }

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
