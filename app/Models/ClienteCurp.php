<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClienteCurp extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'clientes_curp';
    
    // CONSTANTES CORRECTAS
    const CREATED_AT = 'creado_en';
    const UPDATED_AT = null; // No tenemos columna updated_at
    
    protected $fillable = [
        'cliente_id',
        'curp',
        'es_principal'
    ];

    protected $casts = [
        'es_principal' => 'boolean',
        'creado_en' => 'datetime'
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }
}
