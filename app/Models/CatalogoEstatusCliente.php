<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CatalogoEstatusCliente extends Model
{
    use HasFactory;

    protected $table = 'catalogo_estatus_clientes';

    protected $fillable = [
        'nombre',
        'codigo',
        'activo'
    ];

    public function clientes()
    {
        return $this->hasMany(Cliente::class, 'estatus_cliente_id');
    }
}