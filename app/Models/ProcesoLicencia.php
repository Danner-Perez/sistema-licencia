<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class ProcesoLicencia extends Model
{
    protected $table = 'procesos_licencia';

    protected $fillable = [
        'postulante_id',
        'tipo_licencia',
        'fecha_inicio',
        'estado',
    ];

    protected $casts = [
        'fecha_inicio'        => 'date',
        'fecha_psicosomatico' => 'date',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELACIONES
    |--------------------------------------------------------------------------
    */

    public function postulante()
    {
        return $this->belongsTo(
            Postulante::class,
            'postulante_id',
            'id_postulante'
        );
    }

    public function intentos()
    {
        return $this->hasMany(
            IntentoExamen::class,
            'proceso_licencia_id'
        );
    }
    public function intentosExamen()
    {
        return $this->hasMany(IntentoExamen::class);
    }

    /*
    |--------------------------------------------------------------------------
    | LÓGICA DEL PSICOSOMÁTICO (6 MESES)
    |--------------------------------------------------------------------------
    */

    public function getFechaVencimientoPsicosomaticoAttribute()
    {
        if (!$this->fecha_psicosomatico) {
            return null;
        }

        return $this->fecha_psicosomatico->copy()->addMonths(6);
    }

    public function getDiasRestantesPsicosomaticoAttribute()
    {
        if (!$this->fecha_psicosomatico) {
            return null;
        }

        return Carbon::today()->diffInDays(
            $this->fecha_vencimiento_psicosomatico,
            false
        );
    }

    public function getEstadoPsicosomaticoAttribute()
    {
        if (!$this->fecha_psicosomatico) {
            return 'SIN REGISTRO';
        }

        $dias = $this->dias_restantes_psicosomatico;

        if ($dias < 0) {
            return 'VENCIDO';
        }

        if ($dias <= 7) {
            return 'POR VENCER';
        }

        return 'VIGENTE';
    }

    /*
    |--------------------------------------------------------------------------
    | CONTROL DE INTENTOS
    |--------------------------------------------------------------------------
    */

    public function puedeRendirExamen(): bool
    {
        return $this->intentos()->count() < 5
            && $this->estado === 'EN_PROCESO'
            && $this->estado_psicosomatico === 'VIGENTE';
    }
}
