<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Cliente extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'clientes';
    
    const CREATED_AT = 'creado_en';
    const UPDATED_AT = 'actualizado_en';
    
    protected $fillable = [
        // Datos básicos
        'tipo_cliente',
        'estatus',
        'nombre',
        'apellido_paterno',
        'apellido_materno',
        'fecha_nacimiento',
        'fecha_contrato',
        'edad',
        'no_cliente',
        
        // Referencia
        'cliente_referidor_id',
        
        // Institución principal
        'instituto_id',
        'regimen_id',
        'semanas_imss',
        'tramite_id',
        'modalidad_id',
        'fecha_alta',
        'fecha_baja',
        
        // Institución secundaria (ISSSTE)
        'instituto2_id',
        'regimen2_id',
        'anios_servicio_issste',
        'tramite2_id',
        'modalidad_issste',
        'nss_issste',
        'fecha_alta_issste',
        'fecha_baja_issste',
        
        // Datos económicos
        'pension_default',
        'pension_normal',
        'comision',
        'honorarios',
        
        // Auditoría
        'creado_por',
        'actualizado_por'
    ];

    protected $dates = [
        'fecha_nacimiento',
        'fecha_contrato',
        'fecha_alta',
        'fecha_baja',
        'fecha_alta_issste',
        'fecha_baja_issste',
        'creado_en',
        'actualizado_en',
        'eliminado_en'
    ];

    protected $casts = [
        'fecha_nacimiento' => 'date',
        'fecha_contrato' => 'date',
        'fecha_alta' => 'date',
        'fecha_baja' => 'date',
        'fecha_alta_issste' => 'date',
        'fecha_baja_issste' => 'date',
        'edad' => 'integer',
        'pension_default' => 'decimal:2',
        'pension_normal' => 'decimal:2',
        'comision' => 'decimal:2',
        'honorarios' => 'decimal:2',
        'semanas_imss' => 'integer',
        'anios_servicio_issste' => 'integer'
    ];

    public static $rulesCreate = [
        'tipo_cliente' => 'required|in:C,P,S,B,I',
        'estatus' => 'required|in:Activo,Suspendido,Terminado,Baja',
        'nombre' => 'required|string|max:100',
        'apellido_paterno' => 'required|string|max:100',
        'apellido_materno' => 'required|string|max:100',
        'fecha_nacimiento' => 'required|date',
        'fecha_contrato' => 'required|date',
        'cliente_referidor_id' => 'nullable|exists:clientes,id',
    ];

    public static $rulesUpdate = [
        'tipo_cliente' => 'required|in:C,P,S,B,I',
        'estatus' => 'required|in:Activo,Suspendido,Terminado,Baja',
        'nombre' => 'required|string|max:100',
        'apellido_paterno' => 'required|string|max:100',
        'apellido_materno' => 'required|string|max:100',
        'fecha_nacimiento' => 'required|date',
        'fecha_contrato' => 'required|date',
        'cliente_referidor_id' => 'nullable|exists:clientes,id',
        
        'curp' => 'required|string|max:18',
        'curp2' => 'nullable|string|max:18|different:curp',
        'curp3' => 'nullable|string|max:18|different:curp|different:curp2',
        'rfc' => 'required|string|max:13',
        'rfc2' => 'nullable|string|max:13|different:rfc',
        
        'celular1' => 'required|string|max:15',
        'celular2' => 'nullable|string|max:15',
        'tel_casa' => 'nullable|string|max:15',
        'correo1' => 'nullable|email|max:100',
        'correo2' => 'nullable|email|max:100',
        'correo_personal' => 'nullable|email|max:100',
        
        'instituto_id' => 'required|exists:catalogo_institutos,id',
        'regimen_id' => 'required|exists:catalogo_regimenes,id',
        'semanas_imss' => 'nullable|integer|min:0',
        'tramite_id' => 'required|exists:catalogo_tramites,id',
        'modalidad_id' => 'required|exists:catalogo_modalidades,id',
        'fecha_alta' => 'nullable|date',
        'fecha_baja' => 'nullable|date|after_or_equal:fecha_alta',
        'nss' => 'required|string|max:11',
        'nss2' => 'nullable|string|max:11|different:nss',
        'nss3' => 'nullable|string|max:11|different:nss|different:nss2',
        'nss4' => 'nullable|string|max:11|different:nss|different:nss2|different:nss3',
        
        'instituto2_id' => 'nullable|exists:catalogo_institutos,id',
        'regimen2_id' => 'nullable|required_if:instituto2_id,2|exists:catalogo_regimenes,id',
        'anios_servicio_issste' => 'nullable|integer|min:0',
        'tramite2_id' => 'nullable|required_if:instituto2_id,2|exists:catalogo_tramites,id',
        'modalidad_issste' => 'nullable|required_if:instituto2_id,2|string|max:50|in:NA,CV',
        'nss_issste' => 'nullable|string|max:11',
        'fecha_alta_issste' => 'nullable|date',
        'fecha_baja_issste' => 'nullable|date|after_or_equal:fecha_alta_issste',
        
        'pension_default' => 'required|numeric|min:0',
        'pension_normal' => 'required|numeric|min:0',
        'comision' => 'required|numeric|min:0',
        'honorarios' => 'required|numeric|min:0',
    ];

    // RELACIONES
    
    public function instituto()
    {
        return $this->belongsTo(CatalogoInstituto::class, 'instituto_id');
    }

    public function regimen()
    {
        return $this->belongsTo(CatalogoRegimen::class, 'regimen_id');
    }

    public function tramite()
    {
        return $this->belongsTo(CatalogoTramite::class, 'tramite_id');
    }

    public function modalidad()
    {
        return $this->belongsTo(CatalogoModalidad::class, 'modalidad_id');
    }
    
    public function instituto2()
    {
        return $this->belongsTo(CatalogoInstituto::class, 'instituto2_id');
    }

    public function regimen2()
    {
        return $this->belongsTo(CatalogoRegimen::class, 'regimen2_id');
    }

    public function tramite2()
    {
        return $this->belongsTo(CatalogoTramite::class, 'tramite2_id');
    }
    
    public function referidor()
    {
        return $this->belongsTo(Cliente::class, 'cliente_referidor_id');
    }

    public function referidos()
    {
        return $this->hasMany(Cliente::class, 'cliente_referidor_id');
    }
    
    public function curps()
    {
        return $this->hasMany(ClienteCurp::class);
    }

    public function rfcs()
    {
        return $this->hasMany(ClienteRfc::class);
    }

    public function nss()
    {
        return $this->hasMany(ClienteNss::class);
    }
    
    public function contactos()
    {
        return $this->hasMany(ClienteContacto::class);
    }
    
    public function creadoPor()
    {
        return $this->belongsTo(Usuario::class, 'creado_por');
    }

    public function actualizadoPor()
    {
        return $this->belongsTo(Usuario::class, 'actualizado_por');
    }

    // SCOPES
    public function scopeActivos($query)
    {
        return $query->where('estatus', 'Activo');
    }

    public function scopeProspectos($query)
    {
        return $query->where('tipo_cliente', 'P');
    }

    public function scopeClientes($query)
    {
        return $query->where('tipo_cliente', 'C');
    }

    public function scopeSuspendidos($query)
    {
        return $query->where('tipo_cliente', 'S');
    }

    public function scopeBajas($query)
    {
        return $query->where('tipo_cliente', 'B');
    }

    public function scopeImposibles($query)
    {
        return $query->where('tipo_cliente', 'I');
    }
    
    public function scopeConIssste($query)
    {
        return $query->whereNotNull('instituto2_id')
                     ->where('instituto2_id', 2);
    }

    // MÉTODOS DE AYUDA
    public function getNombreCompletoAttribute()
    {
        return trim("{$this->nombre} {$this->apellido_paterno} {$this->apellido_materno}");
    }

    public function getEdadCalculadaAttribute()
    {
        if (!$this->fecha_nacimiento) {
            return null;
        }
        
        return Carbon::parse($this->fecha_nacimiento)->age;
    }

    public function getTipoClienteCompletoAttribute()
    {
        $tipos = [
            'C' => 'Cliente',
            'P' => 'Prospecto',
            'S' => 'Suspendido',
            'B' => 'Baja',
            'I' => 'Imposible'
        ];
        
        return $tipos[$this->tipo_cliente] ?? $this->tipo_cliente;
    }
    
    public function getTieneIsssteAttribute()
    {
        return !is_null($this->instituto2_id) && $this->instituto2_id == 2;
    }
    
    public function getModalidadIsssteTextoAttribute()
    {
        $modalidades = [
            'NA' => 'No Aplica',
            'CV' => 'Continuación Voluntaria'
        ];
        
        return $modalidades[$this->modalidad_issste] ?? $this->modalidad_issste;
    }
    
    public function getEdadContratoAttribute()
    {
        if (!$this->fecha_contrato) {
            return null;
        }
        
        $fechaContrato = Carbon::parse($this->fecha_contrato);
        $fechaNacimiento = Carbon::parse($this->fecha_nacimiento);
        
        return $fechaContrato->diffInYears($fechaNacimiento);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($cliente) {
            if ($cliente->fecha_nacimiento) {
                $cliente->edad = Carbon::parse($cliente->fecha_nacimiento)->age;
            }
        });

        static::updating(function ($cliente) {
            if ($cliente->isDirty('fecha_nacimiento')) {
                $cliente->edad = Carbon::parse($cliente->fecha_nacimiento)->age;
            }
        });
    }
    
    public function getCurpPrincipalAttribute()
    {
        return $this->curps->where('es_principal', true)->first()->curp ?? null;
    }
    
    public function getRfcPrincipalAttribute()
    {
        return $this->rfcs->where('es_principal', true)->first()->rfc ?? null;
    }
    
    public function getNssPrincipalAttribute()
    {
        return $this->nss->where('es_principal', true)->first()->nss ?? null;
    }
    
    public function getCelularPrincipalAttribute()
    {
        return $this->contactos->where('tipo', 'celular1')->first()->valor ?? null;
    }
    
    public function getCorreoPrincipalAttribute()
    {
        return $this->contactos->where('tipo', 'correo1')->first()->valor ?? null;
    }
}
