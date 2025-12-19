<?php
namespace App\Http\Controllers;

use App\Models\Verificacion;
use App\Models\Postulante;
use Illuminate\Http\Request;
use Carbon\Carbon;

class VerificacionController extends Controller
{
    public function index()
    {
        $verificaciones = Verificacion::with([
            'postulante.procesoActivo',
            'verificador'
        ])
        ->whereDate('fecha', now()->toDateString()) // 👈 solo verificaciones de HOY (opcional)
        ->orderBy('fecha', 'desc')
        ->get();

    return view('verificaciones.index', compact('verificaciones'));
    }

    public function create()
    {
        return view('verificaciones.create'); // No necesitamos traer todos los postulantes aquí
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_postulante' => 'required|exists:postulantes,id_postulante',
            'placa'         => 'required|string|max:20',
            'tipo_vehiculo' => 'nullable|string|max:50',
            'marca'         => 'nullable|string|max:50',
            'modelo'        => 'nullable|string|max:50',
        ]);

        $hoy = now()->toDateString();

        // 🔒 VALIDAR ASISTENCIA HOY
        $tieneAsistencia = \App\Models\Asistencia::where('postulante_id', $request->id_postulante)
            ->whereDate('fecha', $hoy)
            ->exists();

        if (!$tieneAsistencia) {
            return redirect()
                ->back()
                ->withErrors([
                    'id_postulante' => '❌ El postulante no tiene asistencia registrada hoy. Debe pasar primero por Asistencia.'
                ])
                ->withInput();
        }

        Verificacion::create([
            'id_postulante'  => $request->id_postulante,
            'fecha'          => now(),
            'placa'          => $request->placa,
            'tipo_vehiculo'  => $request->tipo_vehiculo,
            'marca'          => $request->marca,
            'modelo'         => $request->modelo,
            'verificado_por' => auth()->id(),
        ]);

        return redirect()
            ->route('verificaciones.index')
            ->with('success', '✅ Verificación registrada correctamente');
    }


    public function buscarPostulante(Request $request)
    {
        $query = trim($request->query('query'));
        $hoy = now()->toDateString();

        if (strlen($query) < 3) {
            return response()->json([]);
        }

        $postulantes = Postulante::whereDate('created_at', $hoy)
            ->where(function ($q) use ($query) {
                $q->where('dni', 'like', "%{$query}%")
                ->orWhere('nombres', 'like', "%{$query}%")
                ->orWhere('apellidos', 'like', "%{$query}%");
            })
            ->orderBy('nombres')
            ->limit(10)
            ->get([
                'id_postulante',
                'dni',
                'nombres',
                'apellidos'
            ]);

        return response()->json($postulantes);
    }


    public function edit(Verificacion $verificacion)
    {
        return view('verificaciones.edit', compact('verificacion'));
    }

    public function update(Request $request, Verificacion $verificacion)
    {
        $request->validate([
            'id_postulante' => 'required|exists:postulantes,id_postulante',
            'placa'         => 'required|string|max:20',
            'tipo_vehiculo' => 'nullable|string|max:50',
            'marca'         => 'nullable|string|max:50',
            'modelo'        => 'nullable|string|max:50',
        ]);

        $verificacion->update($request->all());

        return redirect()->route('verificaciones.index')
            ->with('success', 'Verificación actualizada correctamente');
    }

    public function destroy(Verificacion $verificacion)
    {
        $verificacion->delete();
        return redirect()->route('verificaciones.index')
            ->with('success', 'Verificación eliminada correctamente');
    }
}

