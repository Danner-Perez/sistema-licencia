<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IntentoExamen extends Model
{
    protected $table = 'intentos_examen';

    protected $fillable = [
        'proceso_licencia_id',
        'numero_intento',
        'fecha_intento',
        'resultado',
    ];

    protected $casts = [
        'fecha_intento' => 'date',
    ];

    public function procesoLicencia()
    {
        return $this->belongsTo(ProcesoLicencia::class);
    }
}
