<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class Usuario extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    protected $table = 'usuarios';
    protected $primaryKey = 'id';
    
    // Nombre de las columnas de timestamps personalizadas
    const CREATED_AT = 'creado_en';
    const UPDATED_AT = 'actualizado_en';
    
    // Laravel espera 'password', pero tú usas 'password_hash'
    public function getAuthPassword()
    {
        return $this->password_hash;
    }
    
    protected $fillable = [
        'username',
        'email', 
        'password_hash',
        'nombre',
        'apellidos',
        'rol',
        'activo'
    ];

    protected $hidden = [
        'password_hash',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'activo' => 'boolean',
        'ultimo_login' => 'datetime',
        'creado_en' => 'datetime',
        'actualizado_en' => 'datetime',
    ];
}
