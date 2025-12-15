<?php

namespace App\Http\Controllers;

use App\Models\Postulante;
use App\Models\Verificacion;
use Illuminate\Http\Request;

class VerificacionController extends Controller
{
    public function index()
    {
        $verificaciones = Verificacion::with('postulante')
            ->latest()
            ->get();

        return view('verificaciones.registrar', compact('verificaciones'));
    }

    public function store(Request $request)
    {
        $postulante = Postulante::where('dni', $request->dni)->first();

        if (!$postulante) {
            return back()->with('error','Postulante no encontrado');
        }

        Verificacion::create([
            'postulante_id' => $postulante->id,
            'placa' => strtoupper($request->placa)
        ]);

        return back()->with('success','Verificación registrada');
    }
}
