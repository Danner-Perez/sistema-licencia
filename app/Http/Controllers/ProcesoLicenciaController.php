<?php

namespace App\Http\Controllers;

use App\Models\Postulante;
use App\Models\ProcesoLicencia;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ProcesoLicenciaController extends Controller
{
    /**
     * Listar procesos de licencia
     */
    public function index()
    {
        $procesos = ProcesoLicencia::with('postulante')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('procesos.index', compact('procesos'));
    }

    /**
     * Formulario para iniciar proceso de licencia
     */
    public function create(Postulante $postulante)
    {
        return view('procesos.create', compact('postulante'));
    }

    /**
     * Guardar nuevo proceso de licencia
     */
    public function store(Request $request)
    {
        $request->validate([
            'postulante_id' => 'required|exists:postulantes,id_postulante',
            'tipo_licencia' => 'required|string|max:20',
        ]);

        $postulante = Postulante::findOrFail($request->postulante_id);

        // ✔ Psicosomático vigente
        if (! $postulante->psicosomaticoVigente()) {
            return back()->withErrors([
                'fecha_psicosomatico' =>
                'Psicosomático vencido (6 meses)'
            ]);
        }

        // ✔ Evitar duplicado activo
        $existe = ProcesoLicencia::where('postulante_id', $postulante->id_postulante)
            ->where('tipo_licencia', $request->tipo_licencia)
            ->where('estado', 'EN_PROCESO')
            ->exists();

        if ($existe) {
            return back()->withErrors([
                'tipo_licencia' => 'Ya existe un proceso activo'
            ]);
        }

        ProcesoLicencia::create([
            'postulante_id' => $postulante->id_postulante,
            'tipo_licencia' => $request->tipo_licencia,
            'fecha_inicio'  => now(),
        ]);

        return back()->with('success', 'Proceso iniciado');
    }


    /**
     * Cambiar estado del proceso
     */
    public function cambiarEstado(Request $request, ProcesoLicencia $proceso)
    {
        $request->validate([
            'estado' => 'required|in:APROBADO,ANULADO',
        ]);

        $proceso->update([
            'estado' => $request->estado,
        ]);

        return back()->with('success', 'Estado del proceso actualizado');
    }

    /**
     * Eliminar proceso
     */
    public function destroy(ProcesoLicencia $proceso)
    {
        $proceso->delete();

        return back()->with('success', 'Proceso eliminado correctamente');
    }
}
