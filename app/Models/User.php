<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $table = 'users';

    protected $fillable = [
        'username',
        'email',
        'password_hash',  // Columna personalizada en tu BD
        'role',
        'is_active',
        'is_admin'
    ];

    protected $hidden = [
        'password_hash',  // Oculta en JSON/respuestas
        'remember_token'
    ];

    // Método para que Laravel use 'password_hash' como contraseña para auth/login
    public function getAuthPassword()
    {
        return $this->password_hash;
    }
}

