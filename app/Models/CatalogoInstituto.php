<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CatalogoInstituto extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'catalogo_institutos';
    protected $primaryKey = 'id';

    protected $fillable = [
        'codigo',
        'nombre',
        'descripcion',
        'activo'
    ];

    protected $casts = [
        'activo'     => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    // Relación con clientes (instituto principal)
    public function clientes()
    {
        return $this->hasMany(Cliente::class, 'instituto_id');
    }

    // Relación con clientes (instituto secundario)
    public function clientesSecundarios()
    {
        return $this->hasMany(Cliente::class, 'instituto2_id');
    }

    // Relación con regímenes
    public function regimenes()
    {
        return $this->hasMany(CatalogoRegimen::class, 'instituto_id');
    }
}
