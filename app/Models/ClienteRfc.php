<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClienteRfc extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'clientes_rfc';
    
    const CREATED_AT = 'creado_en';
    const UPDATED_AT = null;
    
    protected $fillable = [
        'cliente_id',
        'rfc',
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
