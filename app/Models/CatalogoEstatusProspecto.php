<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CatalogoEstatusProspecto extends Model
{
    protected $table = 'catalogo_estatus_prospectos';

    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'activo'
    ];
}
