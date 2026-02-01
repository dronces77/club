<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Cliente extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The name of the "deleted at" column.
     * Laravel por defecto usa 'deleted_at', pero nuestra BD usa 'eliminado_en'
     *
     * @var string
     */
    const DELETED_AT = 'eliminado_en';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'no_cliente',
        'tipo_cliente',
        'nombre',
        'apellido_paterno',
        'apellido_materno',
        'fecha_nacimiento',
        'edad',
        'instituto_id',
        'instituto2_id',
        'regimen_id',
        'regimen2_id',
        'semanas_imss',
        'anios_servicio_issste',
        'nss_issste',
        'tramite_id',
        'tramite2_id',
        'modalidad_id',
        'modalidad_issste',
        'pension_default',
        'pension_normal',
        'comision',
        'honorarios',
        'fecha_alta',
        'fecha_baja',
        'fecha_alta_issste',
        'fecha_baja_issste',
        'fecha_contrato',
        'estatus',
        'cliente_referidor_id',
        'creado_por',
        'actualizado_por'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'fecha_nacimiento' => 'date',
        'fecha_alta' => 'date',
        'fecha_baja' => 'date',
        'fecha_alta_issste' => 'date',
        'fecha_baja_issste' => 'date',
        'fecha_contrato' => 'date',
        'creado_en' => 'datetime',
        'actualizado_en' => 'datetime',
        'eliminado_en' => 'datetime',
    ];

    // Reglas de validación
    public static $rulesCreate = [
        'no_cliente' => 'required|unique:clientes,no_cliente|max:20',
        'tipo_cliente' => 'required|in:C,E,O',
        'nombre' => 'required|max:100',
        'apellido_paterno' => 'required|max:100',
        'apellido_materno' => 'nullable|max:100',
        'fecha_nacimiento' => 'nullable|date',
        'instituto_id' => 'nullable|exists:catalogo_institutos,id',
        'instituto2_id' => 'nullable|exists:catalogo_institutos,id',
        'regimen_id' => 'nullable|exists:catalogo_regimenes,id',
        'regimen2_id' => 'nullable|exists:catalogo_regimenes,id',
        'tramite_id' => 'nullable|exists:catalogo_tramites,id',
        'tramite2_id' => 'nullable|exists:catalogo_tramites,id',
        'modalidad_id' => 'nullable|exists:catalogo_modalidades,id',
        'modalidad_issste' => 'nullable|in:NA,CV',
        'pension_default' => 'nullable|numeric|min:0',
        'pension_normal' => 'nullable|numeric|min:0',
        'comision' => 'nullable|numeric|min:0',
        'honorarios' => 'nullable|numeric|min:0',
        'fecha_alta' => 'nullable|date',
        'fecha_baja' => 'nullable|date|after_or_equal:fecha_alta',
        'fecha_alta_issste' => 'nullable|date',
        'fecha_baja_issste' => 'nullable|date|after_or_equal:fecha_alta_issste',
        'fecha_contrato' => 'nullable|date',
        'estatus' => 'required|in:Activo,pendiente,Suspendido,Terminado,Baja',
        'cliente_referidor_id' => 'nullable|exists:clientes,id',
        'curp' => 'required|max:18|unique:cliente_curps,curp',
        'rfc' => 'required|max:13|unique:cliente_rfcs,rfc',
        'nss' => 'required|max:11|unique:cliente_nsss,nss',
    ];

    public static $rulesUpdate = [
        'tipo_cliente' => 'required|in:C,E,O',
        'nombre' => 'required|max:100',
        'apellido_paterno' => 'required|max:100',
        'apellido_materno' => 'nullable|max:100',
        'fecha_nacimiento' => 'nullable|date',
        'instituto_id' => 'nullable|exists:catalogo_institutos,id',
        'instituto2_id' => 'nullable|exists:catalogo_institutos,id',
        'regimen_id' => 'nullable|exists:catalogo_regimenes,id',
        'regimen2_id' => 'nullable|exists:catalogo_regimenes,id',
        'tramite_id' => 'nullable|exists:catalogo_tramites,id',
        'tramite2_id' => 'nullable|exists:catalogo_tramites,id',
        'modalidad_id' => 'nullable|exists:catalogo_modalidades,id',
        'modalidad_issste' => 'nullable|in:NA,CV',
        'pension_default' => 'nullable|numeric|min:0',
        'pension_normal' => 'nullable|numeric|min:0',
        'comision' => 'nullable|numeric|min:0',
        'honorarios' => 'nullable|numeric|min:0',
        'fecha_alta' => 'nullable|date',
        'fecha_baja' => 'nullable|date|after_or_equal:fecha_alta',
        'fecha_alta_issste' => 'nullable|date',
        'fecha_baja_issste' => 'nullable|date|after_or_equal:fecha_alta_issste',
        'fecha_contrato' => 'nullable|date',
        'estatus' => 'required|in:Activo,pendiente,Suspendido,Terminado,Baja',
        'cliente_referidor_id' => 'nullable|exists:clientes,id',
    ];

    // Relaciones
    public function instituto()
    {
        return $this->belongsTo(Instituto::class, 'instituto_id');
    }

    public function instituto2()
    {
        return $this->belongsTo(Instituto::class, 'instituto2_id');
    }

    public function regimen()
    {
        return $this->belongsTo(CatalogoRegimen::class, 'regimen_id');
    }

    public function regimen2()
    {
        return $this->belongsTo(CatalogoRegimen::class, 'regimen2_id');
    }

    public function tramite()
    {
        return $this->belongsTo(CatalogoTramite::class, 'tramite_id');
    }

    public function tramite2()
    {
        return $this->belongsTo(CatalogoTramite::class, 'tramite2_id');
    }

    public function modalidad()
    {
        return $this->belongsTo(CatalogoModalidad::class, 'modalidad_id');
    }

    public function referidor()
    {
        return $this->belongsTo(Cliente::class, 'cliente_referidor_id');
    }

    public function creadoPor()
    {
        return $this->belongsTo(Usuario::class, 'creado_por');
    }

    public function actualizadoPor()
    {
        return $this->belongsTo(Usuario::class, 'actualizado_por');
    }

    public function curps()
    {
        return $this->hasMany(ClienteCurp::class, 'cliente_id');
    }

    public function rfcs()
    {
        return $this->hasMany(ClienteRfc::class, 'cliente_id');
    }

    public function nss()
    {
        return $this->hasMany(ClienteNss::class, 'cliente_id');
    }

    public function contactos()
    {
        return $this->hasMany(ClienteContacto::class, 'cliente_id');
    }

    /**
     * Scope para filtrar clientes activos (no eliminados).
     */
    public function scopeActivos($query)
    {
        return $query->whereNull('eliminado_en');
    }

    /**
     * Scope para incluir clientes eliminados.
     * Útil para reportes o administración.
     */
    public function scopeIncluirEliminados($query)
    {
        return $query->withoutGlobalScope('no_eliminados'); // Si decides usar el Global Scope
    }

    /**
     * Get the formatted created date.
     */
    public function getFechaCreacionFormateadaAttribute()
    {
        return $this->creado_en ? Carbon::parse($this->creado_en)->format('d/m/Y H:i') : 'N/A';
    }

    /**
     * Get the formatted updated date.
     */
    public function getFechaActualizacionFormateadaAttribute()
    {
        return $this->actualizado_en ? Carbon::parse($this->actualizado_en)->format('d/m/Y H:i') : 'N/A';
    }

    /**
     * Get the full name of the client.
     */
    public function getNombreCompletoAttribute()
    {
        return trim($this->nombre . ' ' . $this->apellido_paterno . ' ' . $this->apellido_materno);
    }
}
