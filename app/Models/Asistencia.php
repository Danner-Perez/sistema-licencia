<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Asistencia extends Model
{
    protected $primaryKey = 'id_asistencia';

    protected $fillable = [
        'id_postulante','fecha','hora_llegada',
        'hora_salida','observacion','registrado_por'
    ];

    public function postulante() {
        return $this->belongsTo(Postulante::class, 'id_postulante');
    }
}
