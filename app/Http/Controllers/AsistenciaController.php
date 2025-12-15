<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Postulante;
use App\Models\Asistencia;
use Carbon\Carbon;

class AsistenciaController extends Controller
{
    public function index()
    {
        $asistencias = Asistencia::with('postulante')
            ->orderBy('created_at','desc')
            ->get();

        return view('asistencias.marcar', compact('asistencias'));
    }

    public function store(Request $request)
    {
        $postulante = Postulante::where('dni', $request->dni)->first();

        if (!$postulante) {
            return back()->with('error','DNI no registrado');
        }

        if (Asistencia::where('postulante_id', $postulante->id)->exists()) {
            return back()->with('error','Asistencia ya registrada');
        }

        Asistencia::create([
            'postulante_id' => $postulante->id,
            'hora_llegada' => Carbon::now()
        ]);

        return back()->with('success','Asistencia registrada');
    }
}
