<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CatalogoTramite extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'catalogo_tramites';
    protected $primaryKey = 'id';

    protected $fillable = ['codigo', 'nombre', 'descripcion', 'activo'];

    protected $casts = [
        'activo'     => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function clientes()
    {
        return $this->hasMany(Cliente::class, 'tramite_id');
    }
}