<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Examen extends Model
{
    protected $primaryKey = 'id_examen';

    protected $fillable = [
        'id_postulante','fecha','resultado',
        'observacion','evaluado_por'
    ];
}
