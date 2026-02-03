<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClienteRfc extends Model
{
    use HasFactory;

    protected $table = 'cliente_rfcs';

    protected $fillable = [
        'cliente_id',
        'rfc',
        'es_principal'
    ];

    protected $casts = [
        'es_principal' => 'boolean',
        'created_at'   => 'datetime',
        'updated_at'   => 'datetime',
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }
}