<?php

namespace App\Http\Controllers;
use App\Exports\ExamenesHoyExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use App\Models\Postulante;
use App\Models\Examen;
use Carbon\Carbon;

class ExamenController extends Controller
{
    /**
     * Vista principal de exámenes
     * Lista todos los postulantes con su último resultado
     */
    public function index()
    {
        $postulantes = Postulante::whereHas('examenes', function ($q) {
                $q->whereIn('resultado', ['APROBADO', 'NO APROBADO']);
            })
            ->with([
                'examenes' => function ($q) {
                    $q->latest()->limit(1);
                },
                'procesoActivo',
                'verificaciones' => function ($q) {
                    $q->latest()->limit(1);
                }
            ])
            ->orderBy('apellidos')
            ->get();

        return view('examenes.index', compact('postulantes'));
    }
    public function buscar(Request $request)
    {
        $request->validate([
            'query' => 'required|min:3'
        ]);

        $postulantes = Postulante::with([
            'procesoActivo',
            'examenes' => fn ($q) => $q->latest(),
            'verificacion'
        ])
        ->where('dni', 'like', "%{$request->query}%")
        ->get();

        return response()->json(
            $postulantes->map(function ($p) {

                $proceso = $p->procesoActivo;

                return [
                    'id_postulante' => $p->id_postulante,
                    'dni'           => $p->dni,
                    'nombre'        => "{$p->nombres} {$p->apellidos}",
                    'tipo_licencia' => $proceso?->tipo_licencia ?? 'N/D',
                    'proceso_id'    => $proceso?->id,
                    'placa'         => $p->verificacion?->placa ?? '—',
                    'resultado'     => $p->examenes->first()?->resultado ?? 'SIN EXAMEN',
                ];
            })
        );
    }
    public function exportarHoy()
    {
        $hoy = Carbon::today()->format('Y-m-d');

        return Excel::download(
            new ExamenesHoyExport,
            "examenes_hoy_{$hoy}.xlsx"
        );
    }



    public function create()
    {
        return view('examenes.registrar');
    }


    /**
     * 🔎 BUSCAR POSTULANTE (AXIOS)
     * Por DNI, nombres o apellidos
     */
    public function buscarPostulante(Request $request)
    {
        $request->validate([
            'query' => 'required|min:3'
        ]);

        $query = $request->query('query'); // ✅ aquí estaba el error

        $postulantes = Postulante::with([
            'procesoActivo',
            'examenes' => fn ($q) => $q->latest(),
            'ultimaVerificacion'
        ])
        ->where(function ($q) use ($query) {
            $q->where('dni', 'like', "%{$query}%")
            ->orWhere('nombres', 'like', "%{$query}%")
            ->orWhere('apellidos', 'like', "%{$query}%");
        })
        ->limit(10)
        ->get();

        return response()->json(
            $postulantes->map(function ($p) {
                return [
                    'id_postulante' => $p->id_postulante,
                    'dni'           => $p->dni,
                    'nombre'        => "{$p->nombres} {$p->apellidos}",
                    'proceso_id'    => optional($p->procesoActivo)->id,
                    'tipo_licencia' => optional($p->procesoActivo)->tipo_licencia ?? 'N/D',
                    'placa'         => optional($p->ultimaVerificacion)->placa ?? '—',
                    'resultado'     => optional($p->examenes->first())->resultado ?? 'SIN EXAMEN',
                ];
            })
        );
    }







    /**
     * 📝 REGISTRAR RESULTADO DE EXAMEN
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_postulante'        => 'required|exists:postulantes,id_postulante',
            'proceso_licencia_id'  => 'required|exists:procesos_licencia,id',
            'resultado'            => 'required|in:APROBADO,NO APROBADO',
        ]);

        // 🔒 Evitar doble registro el mismo día
        $yaRindioHoy = Examen::where('id_postulante', $request->id_postulante)
            ->whereDate('fecha', Carbon::today())
            ->exists();

        if ($yaRindioHoy) {
            return back()->withErrors([
                'resultado' => 'El postulante ya rindió examen hoy.'
            ]);
        }

        Examen::create([
            'id_postulante' => $request->id_postulante,
            'fecha'         => now(),
            'resultado'     => $request->resultado,
        ]);

        return redirect()
            ->route('examenes.index')
            ->with('success', 'Resultado del examen registrado correctamente.');
    }
}
