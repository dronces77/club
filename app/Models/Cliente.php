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

    // ✅ REGLAS CREATE - CORREGIDAS para Prospectos
    public static $rulesCreate = [
        'nombre' => 'required|string|max:100',
        'apellido_paterno' => 'nullable|string|max:100',
        'apellido_materno' => 'nullable|string|max:100',
        'fecha_nacimiento' => 'nullable|date',
        'curp' => 'nullable|string|max:18',
        'celular1' => 'nullable|string|max:10',
        // NO INCLUIR tipo_cliente, estatus, no_cliente - se asignan automáticamente
    ];

    // ✅ REGLAS UPDATE - CORREGIDAS
    public static $rulesUpdate = [
        'no_cliente' => 'sometimes|nullable|string|max:50',
        'tipo_cliente' => 'sometimes|required|in:B,C,I,P,S',
        'nombre' => 'sometimes|required|string|max:100',
        'apellido_paterno' => 'sometimes|nullable|string|max:100',
        'apellido_materno' => 'sometimes|nullable|string|max:100',
        'fecha_nacimiento' => 'sometimes|nullable|date',
        'edad' => 'sometimes|nullable|integer',
        'instituto_id' => 'sometimes|nullable|exists:catalogo_institutos,id',
        'instituto2_id' => 'sometimes|nullable|exists:catalogo_institutos,id',
        'regimen_id' => 'sometimes|nullable|exists:catalogo_regimenes,id',
        'regimen2_id' => 'sometimes|nullable|exists:catalogo_regimenes,id',
        'semanas_imss' => 'sometimes|nullable|integer',
        'anios_servicio_issste' => 'sometimes|nullable|numeric',
        'nss_issste' => 'sometimes|nullable|string|max:20',
        'tramite_id' => 'sometimes|nullable|exists:catalogo_tramites,id',
        'tramite2_id' => 'sometimes|nullable|exists:catalogo_tramites,id',
        'modalidad_id' => 'sometimes|nullable|exists:catalogo_modalidades,id',
        'modalidad_issste' => 'sometimes|nullable|string|max:50',
        'pension_default' => 'sometimes|nullable|numeric',
        'pension_normal' => 'sometimes|nullable|numeric',
        'comision' => 'sometimes|nullable|numeric',
        'honorarios' => 'sometimes|nullable|numeric',
        'fecha_alta' => 'sometimes|nullable|date',
        'fecha_baja' => 'sometimes|nullable|date',
        'fecha_alta_issste' => 'sometimes|nullable|date',
        'fecha_baja_issste' => 'sometimes|nullable|date',
        'fecha_contrato' => 'sometimes|nullable|date',
        'estatus' => 'sometimes|nullable|in:Activo,Baja,Suspendido,Terminado',
        'cliente_referidor_id' => 'sometimes|nullable|exists:clientes,id',
        'creado_por' => 'sometimes|nullable|exists:usuarios,id',
        'actualizado_por' => 'sometimes|nullable|exists:usuarios,id',
    ];

    /**
     * Boot method para manejar eventos del modelo
     */
    protected static function boot()
    {
        parent::boot();

        // Al crear un nuevo registro: siempre es Prospecto
        static::creating(function ($cliente) {
            $cliente->tipo_cliente = 'P'; // Siempre Prospecto al crear
            $cliente->estatus = null; // Prospectos no tienen estatus
            $cliente->no_cliente = null; // Sin número de cliente
            $cliente->creado_por = auth()->id() ?? 1;
            
            // Calcular edad si hay fecha de nacimiento
            if ($cliente->fecha_nacimiento) {
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

    /**
     * Generar número de cliente único
     */
    public static function generarNumeroCliente()
    {
        $prefix = 'CP-';
        $year = date('y');
        $month = date('m');
        
        // Buscar el último número secuencial
        $ultimoCliente = self::where('no_cliente', 'LIKE', $prefix . $year . $month . '%')
            ->orderBy('no_cliente', 'desc')
            ->first();
        
        if ($ultimoCliente && preg_match('/CP-\d{2}\d{2}(\d{4})/', $ultimoCliente->no_cliente, $matches)) {
            $secuencial = intval($matches[1]) + 1;
        } else {
            $secuencial = 1;
        }
        
        $numero = $prefix . $year . $month . str_pad($secuencial, 4, '0', STR_PAD_LEFT);
        
        // Verificar que no exista (por si acaso)
        $existe = self::where('no_cliente', $numero)->exists();
        $intentos = 0;
        
        while ($existe && $intentos < 10) {
            $secuencial++;
            $numero = $prefix . $year . $month . str_pad($secuencial, 4, '0', STR_PAD_LEFT);
            $existe = self::where('no_cliente', $numero)->exists();
            $intentos++;
        }
        
        return $numero;
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

	/** SCOPE: Obtener por tipo específico 	*/
	public function scopePorTipo($query, $tipo)
	{
		return $query->where('tipo_cliente', $tipo);
	}

    /**
     * Relaciones (mantener las que ya tienes)
     */
    public function instituto()
    {
        return $this->belongsTo(CatalogoInstituto::class, 'instituto_id', 'id');
    }

    public function instituto2()
    {
        return $this->belongsTo(CatalogoInstituto::class, 'instituto2_id', 'id');
    }

    public function regimen()
    {
        return $this->belongsTo(CatalogoRegimen::class, 'regimen_id', 'id');
    }

    public function regimen2()
    {
        return $this->belongsTo(CatalogoRegimen::class, 'regimen2_id', 'id');
    }

    public function tramite()
    {
        return $this->belongsTo(CatalogoTramite::class, 'tramite_id', 'id');
    }

    public function tramite2()
    {
        return $this->belongsTo(CatalogoTramite::class, 'tramite2_id', 'id');
    }

    public function modalidad()
    {
        return $this->belongsTo(CatalogoModalidad::class, 'modalidad_id', 'id');
    }

    public function referidor()
    {
        return $this->belongsTo(Cliente::class, 'cliente_referidor_id', 'id');
    }

    public function creadoPor()
    {
        return $this->belongsTo(Usuario::class, 'creado_por', 'id');
    }

    public function actualizadoPor()
    {
        return $this->belongsTo(Usuario::class, 'actualizado_por', 'id');
    }

    public function curps()
    {
        return $this->hasMany(ClienteCurp::class, 'cliente_id', 'id');
    }

    public function rfcs()
    {
        return $this->hasMany(ClienteRfc::class, 'cliente_id', 'id');
    }

    public function nss()
    {
        return $this->hasMany(ClienteNss::class, 'cliente_id', 'id');
    }

    public function contactos()
    {
        return $this->hasMany(ClienteContacto::class, 'cliente_id', 'id');
    }

    /**
     * Accessors
     */
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
            'P' => 'Prospecto',
            'C' => 'Cliente',
            'I' => 'Imposible',
            'B' => 'Baja',
            'S' => 'Suspendido'
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