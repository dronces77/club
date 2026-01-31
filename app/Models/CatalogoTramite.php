<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CatalogoTramite extends Model
{
    use HasFactory;

    protected $table = 'catalogo_tramites';
    protected $primaryKey = 'id';
    
    protected $fillable = ['codigo', 'nombre', 'descripcion', 'activo'];
    
    protected $casts = [
        'activo' => 'boolean',
    ];
    
    // Relación con clientes
    public function clientes()
    {
        return $this->hasMany(Cliente::class, 'tramite_id');
    }
}
