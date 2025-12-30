<?php

namespace App\Http\Controllers;
use App\Services\ReniecService;

use App\Models\Postulante;
use App\Models\ProcesoLicencia;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PostulanteController extends Controller
{
    /**
     * Listar postulantes registrados hoy
     */
    


    public function index(Request $request)
    {
        $fecha = $request->get('fecha', now()->toDateString());

        $postulantes = Postulante::whereDate('created_at', $fecha)
            ->with('procesosLicencia')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('postulantes.index', compact('postulantes'));
    }

    /**
     * Formulario para crear nuevo postulante
     */
    public function create()
    {
        return view('postulantes.create');
    }

    /**
     * Guardar nuevo postulante y proceso de licencia automáticamente
     */
     public function store(Request $request, ReniecService $reniec)
    {
        $mensajeWarning = null;

        $request->validate([
            'dni'                 => 'required|digits:8',
            'nombres'             => 'nullable|string|max:100',
            'apellidos'           => 'nullable|string|max:100',
            'fecha_psicosomatico' => 'required|date',
            'tipo_licencia'       => 'required|in:A-I,A-IIa,A-IIb,A-IIIa,A-IIIb,A-IIIc',
        ]);

        $hoy = now()->toDateString();

        $existeHoy = Postulante::where('dni', $request->dni)
            ->whereDate('created_at', $hoy)
            ->exists();

        if ($existeHoy) {
            return back()
                ->withInput()
                ->withErrors([
                    'dni' => 'Este postulante ya fue registrado hoy.'
                ])
                ->with('openCreateModal', true);
        }

        // 🔍 CONSULTA RENIEC (CON DETECCIÓN DE INTERNET)
        if (empty($request->nombres) || empty($request->apellidos)) {

            $resultado = $reniec->consultarDni($request->dni);

            if ($resultado['status'] === 'OK') {
                $request->merge($resultado['data']);
            }

            if ($resultado['status'] === 'NO_INTERNET') {
                $mensajeWarning = 'No hay conexión a internet. Los datos fueron ingresados manualmente.';
            }

            if ($resultado['status'] === 'RENIEC_ERROR') {
                $mensajeWarning = 'RENIEC no respondió. Los datos fueron ingresados manualmente.';
            }
        }

        // 💾 CREAR POSTULANTE
        $postulante = Postulante::create([
            'dni'                 => $request->dni,
            'nombres'             => $request->nombres,
            'apellidos'           => $request->apellidos,
            'fecha_psicosomatico' => $request->fecha_psicosomatico,
            'registrado_por'      => auth()->id(),
        ]);

        // 📄 TIPO DE TRÁMITE
        $tipoTramite = $request->tipo_licencia === 'A-I'
            ? 'OBTENCIÓN'
            : 'RECATEGORIZACIÓN';

        // 📝 PROCESO LICENCIA
        ProcesoLicencia::create([
            'postulante_id' => $postulante->id_postulante,
            'tipo_licencia' => $request->tipo_licencia,
            'fecha_inicio'  => $hoy,
            'estado'        => 'EN_PROCESO',
            'tipo_tramite'  => $tipoTramite,
        ]);

        return redirect()
            ->route('postulantes.index')
            ->with('success', 'Postulante y proceso de licencia registrados correctamente')
            ->with('warning', $mensajeWarning);
    }





    /**
     * Formulario para editar postulante
     */
    public function edit(Postulante $postulante)
    {
        return view('postulantes.edit', compact('postulante'));
    }

    /**
     * Actualizar datos del postulante y proceso activo
     */
    public function update(Request $request, Postulante $postulante)
    {
        $request->validate([
            'nombres'             => 'required|string|max:100',
            'apellidos'           => 'required|string|max:100',
            'fecha_psicosomatico' => 'required|date',
            'tipo_licencia'       => 'required|in:A-I,A-IIa,A-IIb,A-IIIa,A-IIIb,A-IIIc',
        ]);

        $postulante->update([
            'nombres'             => $request->nombres,
            'apellidos'           => $request->apellidos,
            'fecha_psicosomatico' => $request->fecha_psicosomatico,
        ]);

        if ($postulante->procesoActivo) {
            $tipoTramite = $request->tipo_licencia === 'A-I' ? 'OBTENCIÓN' : 'RECATEGORIZACIÓN';

            $postulante->procesoActivo->update([
                'tipo_licencia' => $request->tipo_licencia,
                'tipo_tramite'  => $tipoTramite,
            ]);
        }


        return redirect()
            ->route('postulantes.index')
            ->with('success', 'Postulante actualizado correctamente');
    }
    public function buscarPorDni(Request $request, ReniecService $reniec)
    {
        $request->validate([
            'dni' => 'required|digits:8',
        ]);

        $apiData = $reniec->consultarDni($request->dni);

        if (!$apiData) {
            return response()->json(['error' => 'No se encontró el DNI'], 404);
        }

        return response()->json($apiData);
    }




    /**
     * Eliminar postulante
     */
    public function destroy(Postulante $postulante)
    {
        $postulante->delete();

        return redirect()
            ->route('postulantes.index')
            ->with('success', 'Postulante eliminado correctamente');
    }
}
