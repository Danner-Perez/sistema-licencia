<?php

namespace App\Http\Controllers;

use App\Models\ProcesoLicencia;
use App\Models\IntentoExamen;
use Illuminate\Http\Request;
use Carbon\Carbon;

class IntentoExamenController extends Controller
{
    /**
     * Listar intentos de un proceso
     */
    public function index(ProcesoLicencia $proceso)
    {
        $intentos = $proceso->intentos()
            ->orderBy('numero_intento')
            ->get();

        return view('intentos.index', compact('proceso', 'intentos'));
    }

    /**
     * Formulario para registrar intento
     */
    public function create(ProcesoLicencia $proceso)
    {
        /*
        |--------------------------------------------------------------------------
        | VALIDACIÓN: SOLO PROCESOS EN CURSO
        |--------------------------------------------------------------------------
        */
        if ($proceso->estado !== 'EN_PROCESO') {
            abort(403, 'Este proceso ya no está activo');
        }

        /*
        |--------------------------------------------------------------------------
        | VALIDACIÓN: MÁXIMO 5 INTENTOS
        |--------------------------------------------------------------------------
        */
        if ($proceso->intentos()->count() >= 5) {
            abort(403, 'Se alcanzó el número máximo de intentos');
        }

        return view('intentos.create', compact('proceso'));
    }

    /**
     * Guardar intento de examen
     */
    public function store(Request $request)
    {
        $request->validate([
            'proceso_licencia_id' => 'required|exists:procesos_licencia,id',
            'resultado'           => 'required|in:APROBADO,DESAPROBADO',
        ]);

        $proceso = ProcesoLicencia::findOrFail($request->proceso_licencia_id);

        /*
        |--------------------------------------------------------------------------
        | VALIDACIÓN 1: PROCESO ACTIVO
        |--------------------------------------------------------------------------
        */
        if ($proceso->estado !== 'EN_PROCESO') {
            return back()->withErrors([
                'resultado' => 'Este proceso ya fue cerrado'
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | VALIDACIÓN 2: MÁXIMO 5 INTENTOS
        |--------------------------------------------------------------------------
        */
        $numeroIntento = $proceso->intentos()->count() + 1;

        if ($numeroIntento >= 5 && $request->resultado === 'DESAPROBADO') {
            $proceso->update([
                'estado' => 'ANULADO'
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | REGISTRAR INTENTO
        |--------------------------------------------------------------------------
        */
        IntentoExamen::create([
            'proceso_licencia_id' => $proceso->id,
            'numero_intento'      => $numeroIntento,
            'fecha_intento'       => Carbon::today(),
            'resultado'           => $request->resultado,
        ]);

        /*
        |--------------------------------------------------------------------------
        | SI APRUEBA → CERRAR PROCESO
        |--------------------------------------------------------------------------
        */
        if ($request->resultado === 'APROBADO') {
            $proceso->update([
                'estado' => 'APROBADO'
            ]);
        }

        return redirect()
            ->route('intentos.index', $proceso)
            ->with('success', 'Intento registrado correctamente');
    }

    /**
     * Eliminar intento (solo admin)
     */
    public function destroy(IntentoExamen $intento)
    {
        /*
        |--------------------------------------------------------------------------
        | SOLO ADMIN
        |--------------------------------------------------------------------------
        */
        if (auth()->user()->rol !== 'admin') {
            abort(403);
        }

        $intento->delete();

        return back()->with('success', 'Intento eliminado correctamente');
    }
}
