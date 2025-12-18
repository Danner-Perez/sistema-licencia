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
        $verificaciones = Verificacion::with('postulante', 'verificador')
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

        Verificacion::create([
            'id_postulante'  => $request->id_postulante,
            'fecha'          => Carbon::now(),
            'placa'          => $request->placa,
            'tipo_vehiculo'  => $request->tipo_vehiculo,
            'marca'          => $request->marca,
            'modelo'         => $request->modelo,
            'verificado_por' => auth()->id(),
        ]);

        return redirect()->route('verificaciones.index')
            ->with('success', 'Verificación registrada correctamente');
    }

    public function buscarPostulante(Request $request)
    {
        $query = $request->query('query');

        $postulantes = Postulante::where('dni', 'like', "%$query%")
            ->orWhere('nombres', 'like', "%$query%")
            ->orWhere('apellidos', 'like', "%$query%")
            ->take(10)
            ->get(['id_postulante', 'dni', 'nombres', 'apellidos']);

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

