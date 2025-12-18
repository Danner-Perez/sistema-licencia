<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Verificacion extends Model
{
    protected $table = 'verificaciones';
    protected $primaryKey = 'id_verificacion';
    protected $fillable = [
        'id_postulante',
        'fecha',
        'placa',
        'tipo_vehiculo',
        'marca',
        'modelo',
        'verificado_por',
    ];
    protected $casts = ['fecha' => 'datetime'];

    public function postulante(): BelongsTo
    {
        return $this->belongsTo(Postulante::class, 'id_postulante', 'id_postulante');
    }

    public function verificador(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verificado_por', 'id');
    }
}
