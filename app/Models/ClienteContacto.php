<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClienteContacto extends Model
{
    use HasFactory;

    protected $table = 'clientes_contacto';
    
    protected $primaryKey = 'id';
    
    public $incrementing = true;
    
    protected $keyType = 'int';
    
    public $timestamps = false;
    
    protected $fillable = [
        'cliente_id',
        'tipo',
        'valor',
        'es_principal',
        'creado_en'
    ];

    protected $casts = [
        'es_principal' => 'boolean',
        'creado_en' => 'datetime'
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id', 'cliente_id');
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
        // Desmarcar todos los del mismo tipo para este cliente
        self::where('cliente_id', $this->cliente_id)
            ->where('tipo', $this->tipo)
            ->update(['es_principal' => 0]);
        
        // Marcar este como principal
        $this->es_principal = 1;
        return $this->save();
    }
}
