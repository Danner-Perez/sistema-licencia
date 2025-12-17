<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Postulante;
use App\Models\Asistencia;
use Carbon\Carbon;

class AsistenciaController extends Controller
{
    // Mostrar listado de postulantes y asistencias del día
    public function index(Request $request)
    {
        $hoy = Carbon::today();
        $dni = $request->input('dni');

        $postulantes = Postulante::when($dni, function ($query, $dni) {
                $query->where('dni', 'like', "%{$dni}%");
            })
            ->orderBy('fecha_registro', 'asc')
            ->get();

        $asistenciasHoy = Asistencia::whereDate('fecha', $hoy)
            ->pluck('postulante_id')
            ->toArray();

        return view('asistencias.marcar', compact('postulantes', 'asistenciasHoy'));
    }

    // Registrar asistencia vía AJAX
    public function marcarAjax(Request $request)
    {
        $request->validate([
            'postulante_id' => 'required|exists:postulantes,id_postulante',
        ]);

        $postulanteId = $request->postulante_id;
        $hoy = Carbon::today();

        $asistenciaHoy = Asistencia::where('postulante_id', $postulanteId)
            ->whereDate('fecha', $hoy)
            ->first();

        if ($asistenciaHoy) {
            return response()->json([
                'status' => 'error',
                'message' => 'Asistencia ya registrada'
            ], 422);
        }

        $asistencia = Asistencia::create([
            'postulante_id' => $postulanteId,
            'hora_llegada' => Carbon::now(),
            'fecha' => $hoy,
            'registrado_por' => auth()->id(),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Asistencia registrada',
            'hora_llegada' => $asistencia->hora_llegada
        ]);
    }
}
