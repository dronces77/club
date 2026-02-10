<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prospecto extends Model
{
    protected $table = 'prospectos';

    // ✅ La tabla SÍ tiene created_at / updated_at
    public $timestamps = true;

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
    ];

    public function estatus()
    {
        return $this->belongsTo(
            CatalogoEstatusProspecto::class,
            'estatus_prospecto_id'
        );
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }
}
