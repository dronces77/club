<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Instituto extends Model  // <-- CAMBIA "CatalogoInstituto" por "Instituto"
{
    use HasFactory;

    protected $table = 'catalogo_institutos';  // <-- MANTIENE la tabla original
    protected $fillable = [
        'codigo',
        'nombre',
        'descripcion',
        'activo'  // <-- Cambié 'estatus' por 'activo' para coincidir con tu DB
    ];

    public function clientes()
    {
        return $this->hasMany(Cliente::class, 'instituto_id');
    }

    public function clientesSecundarios()
    {
        return $this->hasMany(Cliente::class, 'instituto2_id');
    }
}