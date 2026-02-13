<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CatalogoTiposContacto extends Model
{
    use SoftDeletes;

    protected $table = 'catalogo_tipos_contacto';

    protected $fillable = [
        'codigo',
        'nombre',
        'descripcion',
        'activo',
        'orden'
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relaciones
    |--------------------------------------------------------------------------
    */

    public function contactos()
    {
        return $this->hasMany(ClienteContacto::class, 'tipo_contacto_id');
    }

    /*
    |--------------------------------------------------------------------------
    | Helper elegante
    |--------------------------------------------------------------------------
    */

    public static function idPorCodigo(string $codigo): ?int
    {
        return self::where('codigo', $codigo)
            ->where('activo', 1)
            ->value('id');
    }
}
