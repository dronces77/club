<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClienteContacto extends Model
{
    use HasFactory;

    protected $table = 'cliente_contactos';

    protected $fillable = [
        'cliente_id',
        'tipo_contacto_id',
        'valor',
        'es_principal'
    ];

    protected $casts = [
        'es_principal' => 'boolean',
        'created_at'   => 'datetime',
        'updated_at'   => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relaciones
    |--------------------------------------------------------------------------
    */

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function tipoContacto()
    {
        return $this->belongsTo(CatalogoTiposContacto::class, 'tipo_contacto_id');
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopePrincipales($query)
    {
        return $query->where('es_principal', true);
    }

    public function scopePorTipoId($query, $tipoId)
    {
        return $query->where('tipo_contacto_id', $tipoId);
    }

    /*
    |--------------------------------------------------------------------------
    | Lógica de negocio
    |--------------------------------------------------------------------------
    */

    public function marcarComoPrincipal()
    {
        self::where('cliente_id', $this->cliente_id)
            ->where('tipo_contacto_id', $this->tipo_contacto_id)
            ->update(['es_principal' => false]);

        $this->es_principal = true;

        return $this->save();
    }
}
