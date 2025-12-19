<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Postulante;
use App\Models\Examen;
use Carbon\Carbon;


class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard.dashboard'); // La vista debe existir
    }

    public function data(Request $request)
    {
        $fechaInicio = $request->fecha_inicio ?? now()->subDays(4)->toDateString();
        $fechaFin = $request->fecha_fin ?? now()->toDateString();

        $aprobados = Examen::whereBetween('fecha', [$fechaInicio, $fechaFin])
            ->where('resultado','APROBADO')->count();

        $noAprobados = Examen::whereBetween('fecha', [$fechaInicio, $fechaFin])
            ->where('resultado','NO APROBADO')->count();

        $fechas = [];
        $totales = [];
        for ($i = 4; $i >= 0; $i--) {
            $dia = now()->subDays($i)->toDateString();
            $fechas[] = $dia;
            $totales[] = Postulante::whereDate('created_at', $dia)->count();
        }

        return response()->json([
            'aprobados' => $aprobados,
            'noAprobados' => $noAprobados,
            'fechas' => $fechas,
            'totales' => $totales
        ]);
    }
}
