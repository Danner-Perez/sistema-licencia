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
    public function index(Request $request)
    {
        $fecha = $request->filled('fecha')
            ? Carbon::parse($request->fecha)->toDateString()
            : Carbon::today()->toDateString();

        $query = Postulante::whereHas('examenes', function ($q) use ($fecha) {
                $q->whereDate('fecha', $fecha)
                ->whereIn('resultado', ['APROBADO', 'NO APROBADO']);
            })
            ->with([
                'examenes' => function ($q) use ($fecha) {
                    $q->whereDate('fecha', $fecha)
                    ->latest()
                    ->limit(1);
                },
                'procesoActivo',
                'verificaciones' => function ($q) use ($fecha) {
                    $q->whereDate('fecha', $fecha)
                    ->latest()
                    ->limit(1);
                }
            ])
            ->orderBy('apellidos');

        // 🔢 CONTADOR
        $totalExamenes = (clone $query)->count();

        // 📄 PAGINACIÓN
        $postulantes = $query
            ->paginate(10)
            ->withQueryString();

        return view('examenes.index', compact(
            'postulantes',
            'fecha',
            'totalExamenes'
        ));
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
                    'proceso_id' => $p->procesoActivo?->id ?? null,
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
        ->whereHas('ultimaVerificacion', function ($q) {
            $q->whereDate('fecha', Carbon::today());
        })
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
                    'nombres'       => $p->nombres,
                    'apellidos'     => $p->apellidos,
                    'nombre'        => "{$p->nombres} {$p->apellidos}", // concatenado
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
            'id_postulante' => 'required|exists:postulantes,id_postulante',
            'resultado'     => 'required|in:APROBADO,NO APROBADO',
        ]);


        $hoy = Carbon::today();

        // ❌ SI NO TIENE VERIFICACIÓN HOY → BLOQUEAR
        $tieneVerificacionHoy = \App\Models\Verificacion::where('id_postulante', $request->id_postulante)
            ->whereDate('fecha', $hoy)
            ->exists();

        if (! $tieneVerificacionHoy) {
            return back()->withErrors([
                'id_postulante' => 'El postulante debe pasar verificación antes del examen.'
            ]);
        }

        // ❌ SI YA RINDIÓ HOY → BLOQUEAR
        $yaRindioHoy = Examen::where('id_postulante', $request->id_postulante)
            ->whereDate('fecha', $hoy)
            ->exists();

        if ($yaRindioHoy) {
            return back()->withErrors([
                'resultado' => 'El postulante ya rindió examen hoy.'
            ]);
        }

        // ✅ REGISTRAR EXAMEN
        Examen::create([
            'id_postulante' => $request->id_postulante,
            'fecha'         => now(),
            'resultado'     => $request->resultado,
        ]);

        return redirect()
            ->route('examenes.index')
            ->with('success', 'Resultado del examen registrado correctamente.');
    }
    public function edit(Examen $examen)
    {
        // 🔒 SOLO SI ES APROBADO O NO APROBADO
        if (!in_array($examen->resultado, ['APROBADO', 'NO APROBADO'])) {
            abort(403, 'Este examen no puede ser editado');
        }

        return view('examenes.edit', compact('examen'));
    }
    public function update(Request $request, Examen $examen)
    {
        if (!in_array($examen->resultado, ['APROBADO', 'NO APROBADO'])) {
            abort(403, 'Este examen no puede ser editado');
        }

        $request->validate([
            'resultado' => 'required|in:APROBADO,NO APROBADO',
            'observacion' => 'nullable|string|max:255',
        ]);

        $examen->update([
            'resultado'   => $request->resultado,
            'observacion' => $request->observacion,
        ]);

        return redirect()
            ->route('examenes.index')
            ->with('success', 'Examen actualizado correctamente.');
    }



}
