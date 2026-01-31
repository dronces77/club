<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cliente extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "clientes";
    protected $primaryKey = "id";
    
    const CREATED_AT = "creado_en";
    const UPDATED_AT = "actualizado_en";
    const DELETED_AT = "eliminado_en";
    
    protected $fillable = [
        "no_cliente",
        "tipo_cliente",
        "nombre",
        "apellido_paterno",
        "apellido_materno",
        "fecha_nacimiento",
        "edad",
        "instituto_id",
        "regimen_id",
        "semanas_imss",
        "semanas_issste",
        "tramite_id",
        "modalidad_id",
        "pension_default",
        "pension_normal",
        "comision",
        "honorarios",
        "fecha_alta",
        "fecha_baja",
        "estatus",
        "cliente_referidor_id",
        "creado_por",
        "actualizado_por",
        "observaciones",
    ];

    protected $casts = [
        "fecha_nacimiento" => "date",
        "fecha_alta" => "date",
        "fecha_baja" => "date",
        "pension_default" => "decimal:2",
        "pension_normal" => "decimal:2",
        "comision" => "decimal:2",
        "honorarios" => "decimal:2",
        "activo" => "boolean",
        "semanas_imss" => "integer",
        "semanas_issste" => "integer",
        "edad" => "integer",
        "creado_en" => "datetime",
        "actualizado_en" => "datetime",
        "eliminado_en" => "datetime",
    ];

    // ====================
    // RELACIONES PRINCIPALES
    // ====================
    
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

    public function referidor()
    {
        return $this->belongsTo(Cliente::class, 'cliente_referidor_id');
    }

    public function referidos()
    {
        return $this->hasMany(Cliente::class, 'cliente_referidor_id');
    }

    public function creadoPor()
    {
        return $this->belongsTo(Usuario::class, 'creado_por');
    }

    public function actualizadoPor()
    {
        return $this->belongsTo(Usuario::class, 'actualizado_por');
    }

    // ====================
    // RELACIONES DE IDENTIFICACIÓN
    // ====================
    
    public function curps()
    {
        return $this->hasMany(ClienteCurp::class, 'cliente_id');
    }

    public function curpPrincipal()
    {
        return $this->hasOne(ClienteCurp::class, 'cliente_id')->where('es_principal', true);
    }

    public function nsss()
    {
        return $this->hasMany(ClienteNss::class, 'cliente_id');
    }

    public function nssPrincipal()
    {
        return $this->hasOne(ClienteNss::class, 'cliente_id')->where('es_principal', true);
    }

    public function rfcs()
    {
        return $this->hasMany(ClienteRfc::class, 'cliente_id');
    }

    public function rfcPrincipal()
    {
        return $this->hasOne(ClienteRfc::class, 'cliente_id')->where('es_principal', true);
    }

    // ====================
    // RELACIONES DE CONTACTO Y DOCUMENTOS
    // ====================
    
    public function contactos()
    {
        return $this->hasMany(ClienteContacto::class, 'cliente_id');
    }

    public function contactoPrincipal()
    {
        return $this->hasOne(ClienteContacto::class, 'cliente_id')->where('es_principal', true);
    }

    public function accesosInstitucionales()
    {
        return $this->hasMany(ClienteAccesoInstitucional::class, 'cliente_id');
    }

    public function documentos()
    {
        return $this->hasMany(ClienteDocumento::class, 'cliente_id');
    }

    public function documentosObligatorios()
    {
        return $this->hasManyThrough(
            CatalogoDocumento::class,
            ClienteDocumento::class,
            'cliente_id', // Foreign key on ClienteDocumento table
            'id', // Foreign key on CatalogoDocumento table
            'id', // Local key on Cliente table
            'documento_id' // Local key on ClienteDocumento table
        )->where('obligatorio', true);
    }

    public function notas()
    {
        return $this->hasMany(ClienteNota::class, 'cliente_id');
    }

    public function notasImportantes()
    {
        return $this->hasMany(ClienteNota::class, 'cliente_id')->where('tipo', 'importante');
    }

    // ====================
    // RELACIONES CON FAMILIARES
    // ====================
    
    public function familiares()
    {
        return $this->hasMany(Familiar::class, 'cliente_id');
    }

    public function conyuge()
    {
        return $this->hasOne(Familiar::class, 'cliente_id')->where('parentesco', 'conyuge');
    }

    public function hijos()
    {
        return $this->hasMany(Familiar::class, 'cliente_id')->where('parentesco', 'hijo');
    }

    // ====================
    // MÉTODOS DE AYUDA
    // ====================
    
    /**
     * Obtener nombre completo del cliente
     */
    public function getNombreCompletoAttribute()
    {
        return trim("{$this->nombre} {$this->apellido_paterno} {$this->apellido_materno}");
    }

    /**
     * Obtener iniciales del cliente
     */
    public function getInicialesAttribute()
    {
        $iniciales = '';
        if ($this->nombre) $iniciales .= strtoupper(substr($this->nombre, 0, 1));
        if ($this->apellido_paterno) $iniciales .= strtoupper(substr($this->apellido_paterno, 0, 1));
        return $iniciales;
    }

    /**
     * Obtener descripción del tipo de cliente
     */
    public function getTipoClienteDescAttribute()
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

    /**
     * Verificar si el cliente está activo
     */
    public function getEstaActivoAttribute()
    {
        return $this->estatus === 'Activo';
    }

    /**
     * Obtener edad actual si no está en la base de datos
     */
    public function getEdadActualAttribute()
    {
        if ($this->edad) {
            return $this->edad;
        }
        
        if ($this->fecha_nacimiento) {
            return now()->diffInYears($this->fecha_nacimiento);
        }
        
        return null;
    }

    /**
     * Obtener total de pensión (suma de todas las pensiones)
     */
    public function getTotalPensionAttribute()
    {
        return ($this->pension_default ?? 0) + ($this->pension_normal ?? 0);
    }

    /**
     * Obtener datos de contacto principales
     */
    public function getContactoPrincipalAttribute()
    {
        return $this->contactos()->where('es_principal', true)->first();
    }

    // ====================
    // SCOPES DE CONSULTA
    // ====================
    
    public function scopeActivos($query)
    {
        return $query->where('estatus', 'Activo');
    }
    
    public function scopePendientes($query)
    {
        return $query->where('estatus', 'pendiente');
    }
    
    public function scopeSuspendidos($query)
    {
        return $query->where('estatus', 'Suspendido');
    }
    
    public function scopePorInstituto($query, $institutoId)
    {
        return $query->where('instituto_id', $institutoId);
    }
    
    public function scopePorTipo($query, $tipoCliente)
    {
        return $query->where('tipo_cliente', $tipoCliente);
    }
    
    public function scopeConPension($query, $minPension = 0)
    {
        return $query->where(function($q) use ($minPension) {
            $q->where('pension_default', '>', $minPension)
              ->orWhere('pension_normal', '>', $minPension);
        });
    }
    
    public function scopeSinDocumentos($query)
    {
        return $query->whereDoesntHave('documentos');
    }
    
    public function scopeRecientes($query, $dias = 30)
    {
        return $query->where('creado_en', '>=', now()->subDays($dias));
    }
    
    public function scopeBuscar($query, $termino)
    {
        return $query->where(function($q) use ($termino) {
            $q->where('nombre', 'like', "%{$termino}%")
              ->orWhere('apellido_paterno', 'like', "%{$termino}%")
              ->orWhere('apellido_materno', 'like', "%{$termino}%")
              ->orWhere('no_cliente', 'like', "%{$termino}%")
              ->orWhereHas('curps', function($q2) use ($termino) {
                  $q2->where('curp', 'like', "%{$termino}%");
              })
              ->orWhereHas('nsss', function($q2) use ($termino) {
                  $q2->where('nss', 'like', "%{$termino}%");
              });
        });
    }

    // ====================
    // EVENTOS DEL MODELO
    // ====================
    
    protected static function booted()
    {
        // Antes de crear, asegurar que los campos requeridos estén presentes
        static::creating(function ($cliente) {
            if (!$cliente->creado_por && auth()->check()) {
                $cliente->creado_por = auth()->id();
            }
            
            if (!$cliente->estatus) {
                $cliente->estatus = 'pendiente';
            }
        });
        
        // Antes de actualizar, registrar quién actualizó
        static::updating(function ($cliente) {
            if (auth()->check()) {
                $cliente->actualizado_por = auth()->id();
            }
        });
        
        // Al eliminar, registrar en bitácora (si existe)
        static::deleting(function ($cliente) {
            // Aquí podrías registrar la eliminación en bitácora
        });
    }
}
