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
        'celular',
        'origen',
        'cliente_origen_id',
        'convertido',
        'cliente_id',
        'fecha_creacion'
    ];

    protected $casts = [
        'convertido' => 'boolean',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relaciones
    |--------------------------------------------------------------------------
    */

    // Cliente del cual proviene (referido)
    public function clienteOrigen()
    {
        return $this->belongsTo(Cliente::class, 'cliente_origen_id');
    }

    // Cliente creado a partir del prospecto
    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes (filtros reutilizables)
    |--------------------------------------------------------------------------
    */

    // Prospectos aún NO convertidos
    public function scopeNoConvertidos($query)
    {
        return $query->where('convertido', 0);
    }

    // Prospectos ya convertidos
    public function scopeConvertidos($query)
    {
        return $query->where('convertido', 1);
    }
}
