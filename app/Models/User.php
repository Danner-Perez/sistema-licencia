<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Campos asignables
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'rol', // 👈 AÑADIDO
    ];

    /**
     * Campos ocultos
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Casts
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | HELPERS DE ROLES
    |--------------------------------------------------------------------------
    */

    public function isAdmin(): bool
    {
        return $this->rol === 'admin';
    }

    public function isExaminador(): bool
    {
        return $this->rol === 'examinador';
    }

    public function isAsistencia(): bool
    {
        return $this->rol === 'asistencia';
    }
}
