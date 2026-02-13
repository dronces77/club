<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClienteNss extends Model
{
    use HasFactory;

    protected $table = 'cliente_nsss';

    protected $fillable = [
        'cliente_id',
        'nss',
        'es_principal'
    ];

    protected $casts = [
        'es_principal' => 'boolean',
        'created_at'   => 'datetime',
        'updated_at'   => 'datetime',
    ];

    public $timestamps = true;

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }
}
