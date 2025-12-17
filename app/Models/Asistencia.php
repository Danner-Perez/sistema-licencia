<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Asistencia extends Model
{
    protected $primaryKey = 'id_asistencia';

    protected $fillable = [
        'postulante_id',
        'fecha',
        'hora_llegada',
        'hora_salida',
        'observacion',
        'registrado_por'
    ];

    protected $casts = [
        'fecha' => 'date',
        'hora_llegada' => 'datetime:H:i',
        'hora_salida' => 'datetime:H:i',
    ];


    public function postulante()
    {
        return $this->belongsTo(Postulante::class, 'postulante_id', 'id_postulante');
    }
}
