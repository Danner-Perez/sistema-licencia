<?php

namespace App\Http\Controllers;

use App\Models\Postulante;
use App\Models\Examen;
use Illuminate\Http\Request;
use Rap2hpoutre\FastExcel\FastExcel;

class ExamenController extends Controller
{
    public function index()
    {
        $postulantes = Postulante::with('examen')->get();
        return view('examenes.index', compact('postulantes'));
    }

    public function resultado(Request $request)
    {
        Examen::updateOrCreate(
            ['postulante_id' => $request->id],
            [
                'resultado' => $request->resultado,
                'fecha_examen' => now()
            ]
        );

        return back()->with('success', 'Resultado guardado');
    }

    public function exportar(Request $request)
    {
        $examenes = Examen::with('postulante')
            ->when($request->fecha, fn ($q) =>
                $q->whereDate('fecha_examen', $request->fecha)
            )
            ->when($request->resultado, fn ($q) =>
                $q->where('resultado', $request->resultado)
            )
            ->get()
            ->map(function ($e) {
                return [
                    'DNI'           => $e->postulante->dni,
                    'Nombres'       => $e->postulante->nombres,
                    'Apellidos'     => $e->postulante->apellidos,
                    'Resultado'     => $e->resultado,
                    'Fecha Examen'  => $e->fecha_examen,
                ];
            });

        return (new FastExcel($examenes))
            ->download('resultados_examen.xlsx');
    }
}
