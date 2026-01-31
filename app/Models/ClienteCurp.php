<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClienteCurp extends Model
{
    use HasFactory;

    protected $table = 'clientes_curp';
    
    protected $fillable = ['cliente_id', 'curp', 'es_principal'];
    
    protected $casts = [
        'es_principal' => 'boolean',
    ];
    
    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }
}
