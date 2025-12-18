<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;
use App\Models\Examen;
use App\Models\IntentoExamen;
use App\Models\ProcesoLicencia;
use Carbon\Carbon;

class ExamenController extends Controller
{
    /**
     * Mostrar vista de registro de examen
     */
    public function index()
    {
        // Procesos que NO tienen examen APROBADO
        $procesos = ProcesoLicencia::whereNotExists(function ($q) {
            $q->select(DB::raw(1))
              ->from('examenes')
              ->whereColumn(
                  'procesos_licencia.postulante_id',
                  'examenes.id_postulante'
              )
              ->where('resultado', 'APROBADO');
        })
        ->with('postulante') // opcional pero recomendado
        ->get();

        return view('examenes.index', compact('procesos'));
    }

    /**
     * Registrar intento de examen
     */
    public function store(Request $request)
    {
        $request->validate([
            'proceso_licencia_id' => 'required|exists:procesos_licencia,id',
            'resultado' => 'required|in:APROBADO,NO APROBADO',
            'observacion' => 'nullable|string'
        ]);

        // 🔹 Obtener el proceso
        $proceso = ProcesoLicencia::findOrFail($request->proceso_licencia_id);

        // 🔹 Crear examen usando el postulante del proceso
        Examen::create([
            'id_postulante' => $proceso->postulante_id, // ✅ AQUÍ ESTÁ LA CLAVE
            'fecha'         => now(),
            'resultado'     => $request->resultado,
            'observacion'   => $request->observacion,
        ]);

        return redirect()
            ->route('examenes.index')
            ->with('success', 'Resultado del examen registrado correctamente');
    }
}
