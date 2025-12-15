<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Verificacion extends Model
{
    protected $primaryKey = 'id_verificacion';

    protected $fillable = [
        'id_postulante','fecha','placa',
        'tipo_vehiculo','marca','modelo','verificado_por'
    ];
}
