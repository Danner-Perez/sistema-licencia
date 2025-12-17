<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Postulante extends Model
{
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
       RELACIONES
    ================================ */

    public function procesosLicencia()
    {
        return $this->hasMany(
            ProcesoLicencia::class,
            'postulante_id',
            'id_postulante'
        );
    }

    public function procesoActivo()
    {
        return $this->hasOne(
            ProcesoLicencia::class,
            'postulante_id',
            'id_postulante'
        )->where('estado', 'EN_PROCESO');
    }

    /* ==============================
       PSICOSOMÁTICO (REGLA DE NEGOCIO)
    ================================ */

    public function fechaVencimientoPsicosomatico()
    {
        return $this->fecha_psicosomatico->copy()->addMonths(6);
    }

    public function psicosomaticoVigente(): bool
    {
        return now()->lte($this->fechaVencimientoPsicosomatico());
    }

    public function diasRestantesPsicosomatico(): int
    {
        return now()->diffInDays(
            $this->fechaVencimientoPsicosomatico(),
            false
        );
    }
    public function getProcesoActivoAttribute()
    {
        return $this->procesosLicencia()->where('estado', 'EN_PROCESO')->first();
    }
    
}
