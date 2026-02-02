<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CatalogoRegimen extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'catalogo_regimenes';
    protected $primaryKey = 'id';

    protected $fillable = ['instituto_id', 'codigo', 'nombre'];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function instituto()
    {
        return $this->belongsTo(CatalogoInstituto::class, 'instituto_id');
    }

    public function clientes()
    {
        return $this->hasMany(Cliente::class, 'regimen_id');
    }
}
