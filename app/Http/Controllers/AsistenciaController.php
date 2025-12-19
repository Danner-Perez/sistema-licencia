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
        $fechaHoy = now()->toDateString();
        $dni = $request->input('dni');

        $postulantes = Postulante::whereDate('created_at', $fechaHoy)
            ->when($dni, function ($query, $dni) {
                $query->where('dni', 'like', "%{$dni}%");
            })
            ->with([
                'procesoActivo',
                'asistencias' => function ($q) use ($fechaHoy) {
                    $q->whereDate('fecha', $fechaHoy);
                }
            ])
            ->orderBy('nombres')
            ->get();

        return view('asistencias.marcar', compact('postulantes', 'fechaHoy'));
    }



    // Registrar asistencia vía AJAX
    public function marcar(Request $request)
    {
        $request->validate([
            'postulante_id' => 'required|exists:postulantes,id_postulante',
        ]);

        $hoy = now()->toDateString();

        $existe = Asistencia::where('postulante_id', $request->postulante_id)
            ->whereDate('fecha', $hoy)
            ->exists();

        if ($existe) {
            return response()->json([
                'status' => 'error',
                'message' => 'Asistencia ya registrada hoy'
            ], 422);
        }

        Asistencia::create([
            'postulante_id' => $request->postulante_id,
            'fecha' => $hoy,
            'hora_llegada' => now(),
            'registrado_por' => auth()->id(),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Asistencia registrada correctamente'
        ]);
    }


}

