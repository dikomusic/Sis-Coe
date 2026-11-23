<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'rol',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'rol' => 'string',
    ];

    // Métodos útiles para verificar rol
    public function isAdmin(): bool
    {
        return $this->rol === 'ADMIN';
    }

    public function isUsuario(): bool
    {
        return $this->rol === 'USUARIO';
    }

    public function isInvitado(): bool
    {
        return $this->rol === 'INVITADO';
    }

    public function isCajero(): bool
    {
        return in_array($this->rol, ['CAJERO', 'CAJAS', 'ADMIN']);
    }
}