<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Examen extends Model
{
    protected $table = 'examenes';
    protected $primaryKey = 'id_examen';

    protected $fillable = [
        'id_postulante',
        'fecha',
        'resultado',
        'observacion',
    ];

    public function postulante()
    {
        return $this->belongsTo(Postulante::class, 'id_postulante');
    }
}
