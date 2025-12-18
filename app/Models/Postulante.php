<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\Models\Verificacion;

class Postulante extends Model
{
    protected $table = 'postulantes';
    protected $primaryKey = 'id_postulante';

    protected $fillable = [
        'dni',
        'nombres',
        'apellidos',
        'fecha_psicosomatico',
        'validado_reniec',
        'registrado_por',
    ];

    protected $casts = [
        'fecha_psicosomatico' => 'date',
        'validado_reniec'     => 'boolean',
    ];
    /* ==============================
    VERIFICACIONES (PLACA)
    ================================ */

    public function verificaciones()
    {
        return $this->hasMany(
            \App\Models\Verificacion::class,
            'id_postulante',
            'id_postulante'
        );
    }

    public function ultimaVerificacion()
    {
        return $this->hasOne(Verificacion::class, 'id_postulante', 'id_postulante')
                    ->latestOfMany('id_verificacion'); // ⚠ especificar la PK correcta
    }



    /* ==============================
       RELACIONES
    ================================ */

    // 🔹 Todos los procesos del postulante
    public function procesosLicencia()
    {
        return $this->hasMany(
            ProcesoLicencia::class,
            'postulante_id',
            'id_postulante'
        );
    }

    // 🔹 Proceso activo (EN_PROCESO)
    public function procesoActivo()
    {
        return $this->hasOne(
            ProcesoLicencia::class,
            'postulante_id',
            'id_postulante'
        )->where('estado', 'EN_PROCESO');
    }

    // 🔹 TODOS los exámenes del postulante (🚨 ESTE ES EL QUE FALTABA)
    public function examenes()
    {
        return $this->hasMany(
            Examen::class,
            'id_postulante',
            'id_postulante'
        );
    }

    /* ==============================
       PSICOSOMÁTICO (REGLA DE NEGOCIO)
    ================================ */

    public function fechaVencimientoPsicosomatico()
    {
        if (!$this->fecha_psicosomatico) {
            return null;
        }

        return $this->fecha_psicosomatico->copy()->addMonths(6);
    }

    public function psicosomaticoVigente(): bool
    {
        if (!$this->fecha_psicosomatico) {
            return false;
        }

        return now()->lte($this->fechaVencimientoPsicosomatico());
    }

    public function diasRestantesPsicosomatico(): int
    {
        if (!$this->fecha_psicosomatico) {
            return 0;
        }

        return now()->diffInDays(
            $this->fechaVencimientoPsicosomatico(),
            false
        );
    }

    // Accessor opcional (si lo usas en vistas)
    public function getProcesoActivoAttribute()
    {
        return $this->procesosLicencia()
            ->where('estado', 'EN_PROCESO')
            ->first();
    }
}
