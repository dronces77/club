<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prospecto extends Model
{
    protected $table = 'prospectos';
    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'apellido_paterno',
        'apellido_materno',
        'curp',
        'nss',
        'celular',
        'notas',
        'estatus_prospecto_id',
        'convertido',
        'cliente_id',
        'no_cliente',
        'fecha_creacion'
    ];

    public function estatus()
    {
        return $this->belongsTo(CatalogoEstatusProspecto::class, 'estatus_prospecto_id');
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }
}
