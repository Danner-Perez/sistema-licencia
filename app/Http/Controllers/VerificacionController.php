<?php
namespace App\Http\Controllers;

use App\Models\Verificacion;
use App\Models\Postulante;
use Illuminate\Http\Request;
use Carbon\Carbon;

class VerificacionController extends Controller
{
    public function index(Request $request)
    {
        $hoy = now()->toDateString();

        $query = Verificacion::with(['postulante.procesoActivo','verificador'])
            ->whereDate('fecha', $request->filled('fecha') ? $request->fecha : $hoy)
            ->orderBy('fecha', 'desc');

        // 🔍 Buscar por DNI o nombre
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('postulante', function ($q) use ($search) {
                $q->where('dni', 'like', "%{$search}%")
                ->orWhere('nombres', 'like', "%{$search}%")
                ->orWhere('apellidos', 'like', "%{$search}%");
            });
        }

        $verificaciones = $query->paginate(10)->withQueryString();

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

        // 🔒 VALIDAR VERIFICACIÓN DUPLICADA
        $verificacionHoy = Verificacion::where('id_postulante', $request->id_postulante)
            ->whereDate('fecha', $hoy)
            ->exists();

        if ($verificacionHoy) {
            return redirect()
                ->back()
                ->withErrors([
                    'id_postulante' => '❌ El postulante ya tiene una verificación registrada hoy.'
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


    public function edit($id)
    {
        $verificacion = Verificacion::with('postulante')->findOrFail($id);
        return view('verificaciones.edit', compact('verificacion'));
    }

    public function update(Request $request, $id)
    {
        // 1️⃣ Validación inicial
        $validated = $request->validate([
            'id_postulante' => 'required|exists:postulantes,id_postulante',
            'placa' => 'required|string|max:10',
        ]);

        $hoy = now()->toDateString();

        // 2️⃣ Verificar duplicado ignorando la verificación actual
        $verificacionDuplicada = Verificacion::where('id_postulante', $validated['id_postulante'])
            ->whereDate('fecha', $hoy)
            ->where('id_verificacion', '!=', $id) // ✅ PK correcta
            ->exists();

        if ($verificacionDuplicada) {
            return redirect()
                ->back()
                ->withErrors([
                    'id_postulante' => '❌ Ya existe otra verificación para este postulante hoy.'
                ])
                ->withInput();
        }

        // 3️⃣ Actualizar registro
        $verificacion = Verificacion::findOrFail($id);

        $verificacion->update([
            'id_postulante' => $validated['id_postulante'],
            'placa' => $validated['placa'],
        ]);

        // 4️⃣ Retornar con mensaje de éxito
        return redirect()->route('verificaciones.index')
                        ->with('success', '✅ Verificación actualizada correctamente');
    }



    public function destroy(Verificacion $verificacion)
    {
        $verificacion->delete();
        return redirect()->route('verificaciones.index')
            ->with('success', 'Verificación eliminada correctamente');
    }
}

