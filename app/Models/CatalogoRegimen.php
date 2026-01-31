<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CatalogoRegimen extends Model
{
    use HasFactory;

    protected $table = 'catalogo_regimenes';
    protected $primaryKey = 'id';
    
    protected $fillable = ['instituto_id', 'codigo', 'nombre'];
    
    // Relación con instituto
    public function instituto()
    {
        return $this->belongsTo(CatalogoInstituto::class, 'instituto_id');
    }
    
    // Relación con clientes
    public function clientes()
    {
        return $this->hasMany(Cliente::class, 'regimen_id');
    }
}
