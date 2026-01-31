<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClienteContacto extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'clientes_contacto';
    
    const CREATED_AT = 'creado_en';
    const UPDATED_AT = null;
    
    protected $fillable = [
        'cliente_id',
        'tipo',
        'valor',
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

    public function scopePrincipales($query)
    {
        return $query->where('es_principal', 1);
    }

    public function scopePorTipo($query, $tipo)
    {
        return $query->where('tipo', $tipo);
    }

    public function marcarComoPrincipal()
    {
        self::where('cliente_id', $this->cliente_id)
            ->where('tipo', $this->tipo)
            ->update(['es_principal' => 0]);
        
        $this->es_principal = 1;
        return $this->save();
    }
}
