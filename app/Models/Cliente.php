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
    protected $primaryKey = 'id';

    /**
     * Campos fillable
     */
    protected $fillable = [
        // Sección 1 – IMSS
        'instituto_id',
        'tramite_id',
        'regimen_id',
        'modalidad_id',
        'semanas_imss',
        'fecha_alta',
        'fecha_baja',

        // Sección 2 – ISSSTE
        'instituto2_id',
        'tramite2_id',
        'regimen2_id',
        'modalidad2_id',
        'anios_servicio_issste',
        'nss_issste',
        'fecha_alta_issste',
        'fecha_baja_issste',

        // Información general
        'no_cliente',
        'tipo_cliente',
        'pension_default',
        'pension_normal',
        'comision',
        'honorarios',
        'fecha_contrato',
        'estatus_cliente_id',

        // Datos personales
        'nombre',
        'apellido_paterno',
        'apellido_materno',
        'fecha_nacimiento',
        'edad',

        // Sistema
        'cliente_referidor_id',
        'creado_por',
        'actualizado_por',
    ];

    protected $attributes = [
        'tipo_cliente' => 'P',
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

    /*
    |--------------------------------------------------------------------------
    | BOOT
    |--------------------------------------------------------------------------
    */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($cliente) {
            // Tipo cliente por defecto
            $cliente->tipo_cliente = $cliente->tipo_cliente ?? 'P';

            // Si es prospecto
            if ($cliente->tipo_cliente === 'P') {
                $cliente->no_cliente = null;
                $cliente->estatus_cliente_id = null;
            }

            // Usuario creador
            $cliente->creado_por = $cliente->creado_por ?? auth()->id() ?? 1;

            // Edad
            if ($cliente->fecha_nacimiento && empty($cliente->edad)) {
                $cliente->edad = Carbon::parse($cliente->fecha_nacimiento)->age;
            }

            // Instituciones
            $cliente->setInstitucionesNA();
        });

        static::updating(function ($cliente) {
            $cliente->actualizado_por = auth()->id() ?? 1;

            // Conversión a Cliente
            if ($cliente->isDirty('tipo_cliente') && $cliente->tipo_cliente === 'C') {
                $cliente->no_cliente = $cliente->no_cliente ?? self::generarNumeroCliente();
                $estatus = CatalogoEstatusCliente::where('nombre', 'Activo')->first();
                $cliente->estatus_cliente_id = $cliente->estatus_cliente_id ?? $estatus->id ?? null;
                $cliente->fecha_contrato = $cliente->fecha_contrato ?? Carbon::now();
            }

            // Si deja de ser cliente
            if ($cliente->isDirty('tipo_cliente') && $cliente->tipo_cliente !== 'C') {
                $cliente->estatus_cliente_id = null;
            }

            // Edad
            if ($cliente->isDirty('fecha_nacimiento') && $cliente->fecha_nacimiento) {
                $cliente->edad = Carbon::parse($cliente->fecha_nacimiento)->age;
            }

            // Instituciones
            $cliente->setInstitucionesNA();
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Métodos auxiliares
    |--------------------------------------------------------------------------
    */
    protected function setInstitucionesNA()
    {
        // IMSS
        if ($this->instituto_id && $this->instituto?->codigo !== 'IMS') {
            $this->tramite_id = null;
            $this->regimen_id = null;
            $this->modalidad_id = null;
            $this->semanas_imss = null;
            $this->fecha_alta = null;
            $this->fecha_baja = null;
        }

        // ISSSTE
        if ($this->instituto2_id && $this->instituto2?->codigo !== 'IST') {
            $this->tramite2_id = null;
            $this->regimen2_id = null;
            $this->modalidad2_id = null;
            $this->anios_servicio_issste = null;
            $this->nss_issste = null;
            $this->fecha_alta_issste = null;
            $this->fecha_baja_issste = null;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Generar número de cliente
    |--------------------------------------------------------------------------
    */
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

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */
    public function scopeProspectos($query) { return $query->where('tipo_cliente', '!=', 'C'); }
    public function scopeClientes($query) { return $query->where('tipo_cliente', 'C'); }
    public function scopePorTipo($query, $tipo) { return $query->where('tipo_cliente', $tipo); }
    public function scopeConEstatus($query, $nombre)
    {
        return $query->whereHas('estatusCliente', fn($q) => $q->where('nombre', $nombre));
    }

    /*
    |--------------------------------------------------------------------------
    | Relaciones
    |--------------------------------------------------------------------------
    */
    public function estatusCliente() { return $this->belongsTo(CatalogoEstatusCliente::class, 'estatus_cliente_id'); }
    public function instituto() { return $this->belongsTo(CatalogoInstituto::class, 'instituto_id'); }
    public function instituto2() { return $this->belongsTo(CatalogoInstituto::class, 'instituto2_id'); }
    public function regimen() { return $this->belongsTo(CatalogoRegimen::class, 'regimen_id'); }
    public function regimen2() { return $this->belongsTo(CatalogoRegimen::class, 'regimen2_id'); }
    public function tramite() { return $this->belongsTo(CatalogoTramite::class, 'tramite_id'); }
    public function tramite2() { return $this->belongsTo(CatalogoTramite::class, 'tramite2_id'); }
    public function modalidad() { return $this->belongsTo(CatalogoModalidad::class, 'modalidad_id'); }
    public function modalidad2() { return $this->belongsTo(CatalogoModalidad::class, 'modalidad2_id'); }

    public function contactos() { return $this->hasMany(ClienteContacto::class, 'cliente_id')->with('tipoContacto'); }
    public function curps() { return $this->hasMany(ClienteCurp::class, 'cliente_id'); }
    public function rfcs() { return $this->hasMany(ClienteRfc::class, 'cliente_id'); }
    public function nss() { return $this->hasMany(ClienteNss::class, 'cliente_id'); }
    public function referidor() { return $this->belongsTo(Cliente::class, 'cliente_referidor_id'); }
    public function creadoPor() { return $this->belongsTo(Usuario::class, 'creado_por'); }
    public function actualizadoPor() { return $this->belongsTo(Usuario::class, 'actualizado_por'); }

    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */
    public function getEsProspectoAttribute() { return $this->tipo_cliente !== 'C'; }
    public function getEsClienteAttribute() { return $this->tipo_cliente === 'C'; }
    public function getNombreCompletoAttribute() { return trim("{$this->nombre} {$this->apellido_paterno} {$this->apellido_materno}"); }
    public function getEstatusTextoAttribute() { return $this->estatusCliente->nombre ?? 'N/A'; }
    public function getFechaCreacionFormateadaAttribute() { return $this->created_at?->format('d/m/Y H:i') ?? 'N/A'; }
    public function getFechaActualizacionFormateadaAttribute() { return $this->updated_at?->format('d/m/Y H:i') ?? 'N/A'; }

    /*
    |--------------------------------------------------------------------------
    | Validaciones dinámicas
    |--------------------------------------------------------------------------
    */
    public static $rulesUpdate = [
        'nombre' => 'required|string|max:255',
        'apellido_paterno' => 'required|string|max:255',
        'apellido_materno' => 'nullable|string|max:255',
        'curps.*' => 'required|size:18',
        'rfcs.*' => 'required|size:13',
        'nss.*.nss' => 'required|digits:11',
        'contactos.*.tipo_contacto_id' => 'required|exists:catalogo_tipos_contacto,id',
        'contactos.*.valor' => 'required|string',

        // IMSS
        'instituto_id' => 'required|exists:catalogo_institutos,id',
        'regimen_id' => 'nullable|exists:catalogo_regimenes,id',
        'tramite_id' => 'nullable|exists:catalogo_tramites,id',
        'modalidad_id' => 'nullable|exists:catalogo_modalidades,id',
        'semanas_imss' => 'nullable|integer|min:0',
        'fecha_alta' => 'nullable|date',
        'fecha_baja' => 'nullable|date|after_or_equal:fecha_alta',

        // ISSSTE
        'instituto2_id' => 'nullable|exists:catalogo_institutos,id',
        'regimen2_id' => 'nullable|exists:catalogo_regimenes,id',
        'tramite2_id' => 'nullable|exists:catalogo_tramites,id',
        'modalidad2_id' => 'nullable|exists:catalogo_modalidades,id',
        'anios_servicio_issste' => 'nullable|integer|min:0',
        'nss_issste' => 'nullable|digits:11',
        'fecha_alta_issste' => 'nullable|date',
        'fecha_baja_issste' => 'nullable|date|after_or_equal:fecha_alta_issste',
    ];
}
