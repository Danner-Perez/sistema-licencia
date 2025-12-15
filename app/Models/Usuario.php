<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    protected $primaryKey = 'id_usuario';

    protected $fillable = [
        'nombre','usuario','password','rol'
    ];

    public function postulantes() {
        return $this->hasMany(Postulante::class, 'registrado_por');
    }
}
