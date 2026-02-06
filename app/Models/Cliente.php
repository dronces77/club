<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class Cliente extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'clientes';
    protected $primaryKey = 'id';

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
        'actualizado_por',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $attributes = [
        'tipo_cliente' => 'P', // Prospecto por defecto
    ];

    protected $casts = [
        'fecha_nacimiento' => 'date',
        'fecha_alta' => 'date',
        'fecha_baja' => 'date',
        'fecha_alta_issste' => 'date',
        'fecha_baja_issste' => 'date',
        'fecha_contrato' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    // ======================
    // BOOT
    // ======================
    protected static function boot()
    {
        parent::boot();

        // Al crear un nuevo registro: siempre es Prospecto
static::creating(function ($cliente) {

    // 👉 SOLO si NO viene definido
    if (!isset($cliente->tipo_cliente)) {
        $cliente->tipo_cliente = 'P';
    }

    // 👉 SOLO prospectos se limpian
    if ($cliente->tipo_cliente === 'P') {
        $cliente->estatus = null;
        $cliente->no_cliente = null;
    }

    if (empty($cliente->creado_por)) {
        $cliente->creado_por = auth()->id() ?? 1;
    }

    if ($cliente->fecha_nacimiento && empty($cliente->edad)) {
        $cliente->edad = Carbon::parse($cliente->fecha_nacimiento)->age;
    }
});


        // Al actualizar: verificar conversión a Cliente
        static::updating(function ($cliente) {
            $cliente->actualizado_por = auth()->id() ?? 1;
            
            // Si está cambiando a Cliente y aún no tiene número
            if ($cliente->isDirty('tipo_cliente') && $cliente->tipo_cliente === 'C') {
                // Solo para nuevos clientes
                if (empty($cliente->no_cliente)) {
                    $cliente->no_cliente = self::generarNumeroCliente();
                }
                
                // Asignar estatus Activo por defecto
                if (empty($cliente->estatus)) {
                    $cliente->estatus = 'Activo';
                }
                
                // Asignar fecha de contrato si no tiene
                if (empty($cliente->fecha_contrato)) {
                    $cliente->fecha_contrato = Carbon::now();
                }
            }
            
            // Si deja de ser cliente, quitar estatus
            if ($cliente->isDirty('tipo_cliente') && $cliente->tipo_cliente !== 'C') {
                $cliente->estatus = null;
            }
            
            // Calcular edad si cambió la fecha de nacimiento
            if ($cliente->isDirty('fecha_nacimiento') && $cliente->fecha_nacimiento) {
                $cliente->edad = Carbon::parse($cliente->fecha_nacimiento)->age;
            }
        });
    }

    // ======================
    // Generar número de cliente
    // ======================
    public static function generarNumeroCliente()
    {
        $ultimo = self::whereNotNull('no_cliente')
            ->orderByRaw("CAST(SUBSTRING(no_cliente, 4) AS UNSIGNED) DESC")
            ->first();

        if ($ultimo && preg_match('/CP-(\d+)/', $ultimo->no_cliente, $m)) {
            $siguiente = intval($m[1]) + 1;
        } else {
            $siguiente = 1;
        }

        return 'CP-' . $siguiente;
    }

    /**
     * SCOPE: Obtener solo prospectos (tipo_cliente != 'C')
     */
    public function scopeProspectos($query)
    {
        return $query->where('tipo_cliente', '!=', 'C');
    }

    /**
     * SCOPE: Obtener solo clientes (tipo_cliente = 'C')
     */
    public function scopeClientes($query)
    {
        return $query->where('tipo_cliente', 'C');
    }

    /** SCOPE: Obtener por tipo específico */
    public function scopePorTipo($query, $tipo)
    {
        return $query->where('tipo_cliente', $tipo);
    }

    // ======================
    // 🔥 FIX: Relaciones necesarias
    // ======================

    public function instituto()
    {
        return $this->belongsTo(CatalogoInstituto::class, 'instituto_id');
    }

    public function instituto2()
    {
        return $this->belongsTo(CatalogoInstituto::class, 'instituto2_id');
    }

    public function regimen()
    {
        return $this->belongsTo(CatalogoRegimen::class, 'regimen_id');
    }

    public function regimen2()
    {
        return $this->belongsTo(CatalogoRegimen::class, 'regimen2_id');
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
		return $this->belongsTo(CatalogoTramite::class, 'modalidad_id');
	}

	public function referidor()
	{
		return $this->belongsTo(CatalogoTramite::class, 'cliente_referidor_id');
	}
	
	public function creadoPor()
	{
		return $this->belongsTo(Usuario::class, 'creado_por');
	}
	
	public function actualizadoPor()
	{
		return $this->belongsTo(Usuario::class, 'actualizado_por');
	}

	
    // ======================
    // Accessors
    // ======================
    public function getEsProspectoAttribute()
    {
        return $this->tipo_cliente !== 'C';
    }

    public function getEsClienteAttribute()
    {
        return $this->tipo_cliente === 'C';
    }

    public function getTipoClienteTextoAttribute()
    {
        $tipos = [
            'C' => 'Cliente',
        ];
        
        return $tipos[$this->tipo_cliente] ?? 'Desconocido';
    }

    public function getEstatusTextoAttribute()
    {
        if (!$this->estatus) {
            return 'N/A';
        }
        
        return $this->estatus;
    }

    public function getFechaCreacionFormateadaAttribute()
    {
        return $this->created_at ? Carbon::parse($this->created_at)->format('d/m/Y H:i') : 'N/A';
    }

    public function getFechaActualizacionFormateadaAttribute()
    {
        return $this->updated_at ? Carbon::parse($this->updated_at)->format('d/m/Y H:i') : 'N/A';
    }

    public function getNombreCompletoAttribute()
    {
        return trim($this->nombre . ' ' . $this->apellido_paterno . ' ' . $this->apellido_materno);
    }
}
