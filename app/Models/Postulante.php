<?php

namespace App\Models;
use Carbon\Carbon;

use Illuminate\Database\Eloquent\Model;

class Postulante extends Model
{
    protected $primaryKey = 'id_postulante';

    protected $fillable = [
        'dni',
        'nombres',
        'apellidos',
        'tipo_licencia',
        'fecha_registro',
        'fecha_psicofisico', // ✅ NUEVO
        'validado_reniec',
        'registrado_por'
    ];


    public function asistencias() {
        return $this->hasMany(Asistencia::class, 'id_postulante');
    }

    public function verificacion() {
        return $this->hasOne(Verificacion::class, 'id_postulante');
    }

    public function examen() {
        return $this->hasOne(Examen::class, 'id_postulante');
    }
    public function getFechaVencimientoPsicofisicoAttribute()
{
    if (!$this->fecha_psicofisico) {
        return null;
    }

    return Carbon::parse($this->fecha_psicofisico)->addDays(60);
}

public function getDiasRestantesPsicofisicoAttribute()
{
    if (!$this->fecha_psicofisico) {
        return null;
    }

    return now()->diffInDays($this->fecha_vencimiento_psicofisico, false);
}

public function getEstadoPsicofisicoAttribute()
{
    if (!$this->fecha_psicofisico) {
        return 'SIN REGISTRO';
    }

    $dias = $this->dias_restantes_psicofisico;

    if ($dias < 0) {
        return 'VENCIDO';
    }

    if ($dias <= 7) {
        return 'POR VENCER';
    }

    return 'VIGENTE';
}

}
