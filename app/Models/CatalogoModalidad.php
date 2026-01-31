<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CatalogoModalidad extends Model
{
    use HasFactory;

    protected $table = 'catalogo_modalidades';
    protected $primaryKey = 'id';
    
    protected $fillable = ['codigo', 'nombre', 'descripcion', 'activo'];
    
    protected $casts = [
        'activo' => 'boolean',
    ];
    
    // Relación con clientes
    public function clientes()
    {
        return $this->hasMany(Cliente::class, 'modalidad_id');
    }
}
