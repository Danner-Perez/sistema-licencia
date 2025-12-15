<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Postulante extends Model
{
    protected $primaryKey = 'id_postulante';

    protected $fillable = [
        'dni','nombres','apellidos','tipo_licencia',
        'fecha_registro','validado_reniec','registrado_por'
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
}
