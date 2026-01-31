<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CatalogoInstituto extends Model
{
    use HasFactory;

    protected $table = 'catalogo_institutos';
    protected $primaryKey = 'id';
    
    protected $fillable = ['codigo', 'nombre', 'activo'];
    
    protected $casts = [
        'activo' => 'boolean',
    ];
    
    // Relación con clientes
    public function clientes()
    {
        return $this->hasMany(Cliente::class, 'instituto_id');
    }
    
    // Relación con regimenes
    public function regimenes()
    {
        return $this->hasMany(CatalogoRegimen::class, 'instituto_id');
    }
}
