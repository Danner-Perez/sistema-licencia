<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Postulante;
use App\Models\Examen;

class ProcesoLicencia extends Model
{
    use HasFactory;

    protected $table = 'procesos_licencia';

    protected $primaryKey = 'id';

    protected $fillable = [
        'postulante_id',
        'tipo_licencia',
        'fecha_inicio',
        'estado',
    ];

    /* ===============================
     |  CONSTANTES DE ESTADO
     |===============================*/
    public const EN_PROCESO = 'EN_PROCESO';
    public const APROBADO   = 'APROBADO';
    public const ANULADO    = 'ANULADO';

    /* ===============================
     |  RELACIONES
     |===============================*/

    /**
     * Un proceso pertenece a un postulante
     */
    public function postulante()
    {
        return $this->belongsTo(
            Postulante::class,
            'postulante_id',
            'id_postulante'
        );
    }

    /**
     * Un proceso puede tener varios exámenes
     * (relación por postulante)
     */
    public function examenes()
    {
        return $this->hasMany(
            Examen::class,
            'id_postulante',   // FK en examenes
            'postulante_id'    // FK en procesos
        );
    }

    /* ===============================
     |  SCOPES
     |===============================*/

    /**
     * Procesos activos
     */
    public function scopeActivos($query)
    {
        return $query->where('estado', self::EN_PROCESO);
    }

    /**
     * Procesos aprobados
     */
    public function scopeAprobados($query)
    {
        return $query->where('estado', self::APROBADO);
    }

    /**
     * Procesos anulados
     */
    public function scopeAnulados($query)
    {
        return $query->where('estado', self::ANULADO);
    }

    /* ===============================
     |  MÉTODOS DE NEGOCIO
     |===============================*/

    /**
     * Verifica si el proceso tiene al menos
     * un examen aprobado
     */
    public function tieneExamenAprobado(): bool
    {
        return $this->examenes()
            ->where('resultado', 'APROBADO')
            ->exists();
    }

    /**
     * Verifica si el proceso puede aprobarse
     */
    public function puedeAprobarse(): bool
    {
        return $this->estado === self::EN_PROCESO
            && $this->tieneExamenAprobado();
    }
}
