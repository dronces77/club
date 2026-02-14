<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cliente;
use App\Models\CatalogoEstatusCliente;
use App\Models\CatalogoInstituto;
use App\Models\CatalogoRegimen;
use App\Models\CatalogoTramite;
use App\Models\CatalogoTramiteIssste;
use App\Models\CatalogoModalidad;
use App\Models\CatalogoTiposContacto;
use App\Models\ClienteCurp;
use App\Models\ClienteRfc;
use App\Models\ClienteNss;
use App\Models\ClienteContacto;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ClienteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
public function index(Request $request)
{
    $institutos = CatalogoInstituto::orderBy('nombre')->get();
    
    $query = Cliente::where('tipo_cliente', 'C')
        ->with(['instituto', 'instituto2', 'curps', 'rfcs', 'nss', 'contactos'])
        ->orderBy('created_at', 'desc');
    
    // Búsqueda principal
    if ($request->filled('search')) {
        $searchTerm = $request->search;
        $query->where(function($q) use ($searchTerm) {
            $q->where('no_cliente', 'like', '%' . $searchTerm . '%')
              ->orWhere('nombre', 'like', '%' . $searchTerm . '%')
              ->orWhere('apellido_paterno', 'like', '%' . $searchTerm . '%')
              ->orWhere('apellido_materno', 'like', '%' . $searchTerm . '%')
              ->orWhere('nss_issste', 'like', '%' . $searchTerm . '%')
              ->orWhereHas('curps', function($curpQuery) use ($searchTerm) {
                  $curpQuery->where('curp', 'like', '%' . $searchTerm . '%')
                           ->where('es_principal', true);
              })
              ->orWhereHas('nss', function($nssQuery) use ($searchTerm) {
                  $nssQuery->where('nss', 'like', '%' . $searchTerm . '%')
                           ->where('es_principal', true);
              });
        });
    }

    // Filtro de estatus
    if ($request->filled('estatus') && $request->estatus !== 'todos') {
        $query->conEstatus($request->estatus);
    }

    // Filtro por institución
    if ($request->filled('instituto_id') && $request->instituto_id !== 'todos') {
        $institutoId = $request->instituto_id;
        $query->where(function($q) use ($institutoId) {
            $q->where('instituto_id', $institutoId)
              ->orWhere('instituto2_id', $institutoId);
        });
    }

    $clientes = $query->paginate(20);
    $clientes->appends($request->only('search', 'estatus', 'instituto_id'));

    // Conteos corregidos
    $totalClientes = Cliente::where('tipo_cliente', 'C')->count();
    $activosCount = Cliente::where('tipo_cliente', 'C')->conEstatus('Activo')->count();
    $pendientesCount = Cliente::where('tipo_cliente', 'C')->conEstatus('pendiente')->count();
    $imssCount = Cliente::where('tipo_cliente', 'C')
        ->where(function($q) {
            $q->where('instituto_id', 13)
              ->orWhere('instituto2_id', 13);
        })
        ->count();

    return view('clientes.index', compact(
        'clientes',
        'institutos',
        'totalClientes',
        'activosCount',
        'pendientesCount',
        'imssCount'
    ));
}


    /**
     * Búsqueda para autocomplete (usado por el JavaScript)
     * SOLO busca en CLIENTES (tipo_cliente = 'C')
     */
    public function search(Request $request)
    {
        try {
            // ✅ INICIAL: Solo clientes (tipo_cliente = 'C')
            $query = Cliente::where('tipo_cliente', 'C')
                ->with(['instituto', 'instituto2', 'curps', 'rfcs', 'nss'])
                ->orderBy('created_at', 'desc');
            
            // 🔍 BÚSQUEDA EN CAJA DE TEXTO (TODOS LOS CAMPOS SOLICITADOS)
            if ($request->filled('q')) {
                $searchTerm = $request->q;
                
                $query->where(function($q) use ($searchTerm) {
                    // ✅ 1. Campos DIRECTOS de la tabla clientes
                    $q->where('no_cliente', 'like', '%' . $searchTerm . '%')        // No. Cliente
                      ->orWhere('nombre', 'like', '%' . $searchTerm . '%')          // Nombre
                      ->orWhere('apellido_paterno', 'like', '%' . $searchTerm . '%') // Apellido Paterno
                      ->orWhere('apellido_materno', 'like', '%' . $searchTerm . '%') // Apellido Materno
                      ->orWhere('nss_issste', 'like', '%' . $searchTerm . '%')      // NSS ISSSTE
                      
                      // ✅ 2. CURP - tabla relacionada cliente_curps (solo principal)
                      ->orWhereHas('curps', function($curpQuery) use ($searchTerm) {
                          $curpQuery->where('curp', 'like', '%' . $searchTerm . '%')
                                   ->where('es_principal', true);
                      })
                      
                      // ✅ 3. NSS - tabla relacionada cliente_nss (solo principal)
                      ->orWhereHas('nss', function($nssQuery) use ($searchTerm) {
                          $nssQuery->where('nss', 'like', '%' . $searchTerm . '%')
                                  ->where('es_principal', true);
                      });
                });
            }
            
            // 📊 FILTRO DE ESTATUS (BÚSQUEDA ANIDADA)
if ($request->filled('estatus') && $request->estatus !== 'todos') {
    $query->whereHas('estatusCliente', function($q) use ($request) {
        $q->where('nombre', $request->estatus);
    });
}

            
            // 🏢 FILTRO DE INSTITUCIÓN (BÚSQUEDA ANIDADA)
            if ($request->filled('instituto_id') && $request->instituto_id !== 'todos') {
                $institutoId = $request->instituto_id;
                $query->where(function($q) use ($institutoId) {
                    $q->where('instituto_id', $institutoId)
                      ->orWhere('instituto2_id', $institutoId);
                });
            }
            
            // Limitar resultados para autocomplete
            $clientes = $query->limit(15)->get();
            
            // Formatear respuesta
            $resultados = $clientes->map(function($cliente) {
                $nombre = $cliente->nombre ?? '';
                $apellidoPaterno = $cliente->apellido_paterno ?? '';
                $apellidoMaterno = $cliente->apellido_materno ?? '';
                
                // Obtener CURP principal
                $curpPrincipal = $cliente->curps
                    ->where('es_principal', true)
                    ->first();
                
                // Obtener NSS principal
                $nssPrincipal = $cliente->nss
                    ->where('es_principal', true)
                    ->first();
                
                return [
                    'id' => $cliente->id,
                    'no_cliente' => $cliente->no_cliente ?? 'N/A',
                    'nombre_completo' => trim("$nombre $apellidoPaterno $apellidoMaterno"),
                    'nombre' => $nombre,
                    'apellido_paterno' => $apellidoPaterno,
                    'apellido_materno' => $apellidoMaterno,
                    'curp' => $curpPrincipal->curp ?? null,
                    'nss' => $nssPrincipal->nss ?? null,
                    'estatus' => $cliente->estatus ?? 'N/A',
                    'institucion' => $cliente->instituto ? $cliente->instituto->codigo : null,
                    'institucion2' => $cliente->instituto2 ? $cliente->instituto2->codigo : null,
                    'show_url' => route('clientes.show', $cliente->id),
                    'edit_url' => route('clientes.edit', $cliente->id)
                ];
            });
            
            return response()->json([
                'clientes' => $resultados,
                'total' => $clientes->count(),
                'success' => true
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error en búsqueda autocomplete: ' . $e->getMessage());
            
            return response()->json([
                'clientes' => [],
                'total' => 0,
                'success' => false,
                'message' => 'Error en el servidor'
            ], 500);
        }
    }

    /**
     * Validar campo único (para AJAX)
     */
    public function validarCampoUnico(Request $request)
    {
        try {
            $campo = $request->campo; // 'curp', 'rfc', 'nss'
            $valor = $request->valor;
            $clienteId = $request->cliente_id;
            
            if (empty($valor)) {
                return response()->json(['disponible' => true]);
            }
            
            $disponible = true;
            $mensaje = '';
            
            switch ($campo) {
                case 'curp':
                case 'curp2':
                case 'curp3':
                    $existe = ClienteCurp::where('curp', $valor)
                        ->when($clienteId, function($query) use ($clienteId) {
                            return $query->where('cliente_id', '!=', $clienteId);
                        })
                        ->exists();
                    $disponible = !$existe;
                    $mensaje = $existe ? "La CURP '{$valor}' ya está registrada para otro cliente." : '';
                    break;
                    
                case 'rfc':
                case 'rfc2':
                    $existe = ClienteRfc::where('rfc', $valor)
                        ->when($clienteId, function($query) use ($clienteId) {
                            return $query->where('cliente_id', '!=', $clienteId);
                        })
                        ->exists();
                    $disponible = !$existe;
                    $mensaje = $existe ? "El RFC '{$valor}' ya está registrado para otro cliente." : '';
                    break;
                    
                case 'nss':
                case 'nss2':
                case 'nss3':
                case 'nss4':
                    $existe = ClienteNss::where('nss', $valor)
                        ->when($clienteId, function($query) use ($clienteId) {
                            return $query->where('cliente_id', '!=', $clienteId);
                        })
                        ->exists();
                    $disponible = !$existe;
                    $mensaje = $existe ? "El NSS '{$valor}' ya está registrado para otro cliente." : '';
                    break;
                    
                default:
                    return response()->json([
                        'disponible' => true,
                        'mensaje' => 'Campo no válido'
                    ], 400);
            }
            
            return response()->json([
                'disponible' => $disponible,
                'mensaje' => $mensaje,
                'campo' => $campo,
                'valor' => $valor
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error en validación única: ' . $e->getMessage());
            
            return response()->json([
                'disponible' => false,
                'mensaje' => 'Error en la validación'
            ], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
public function create()
{
    // Cargar datos necesarios
    $institutos = CatalogoInstituto::where('activo', true)->get();
    $regimenes = CatalogoRegimen::where('activo', true)->get();
    $tramites = CatalogoTramite::where('activo', true)->get();
    $modalidades = CatalogoModalidad::where('activo', true)->get();

    // 🔥 Modalidades IMSS (necesario para selects anidados)
    $modalidadesImss = CatalogoModalidad::where('activo', true)
        ->whereIn('codigo', ['NA','M10','M40'])
        ->get();

    // 🔥 Modalidades ISSSTE
    $modalidadesIssste = CatalogoModalidad::where('activo', true)
        ->whereIn('codigo', ['NA','CV'])
        ->get();

    // 🔥 Tabla pivote para selects anidados
    $combinaciones = DB::table('catalogo_modalidad_regimen_tramite')
        ->whereNull('deleted_at')
        ->where('activo', 1)
        ->get([
            'instituto_codigo',
            'regimen_codigo',
            'tramite_codigo',
            'modalidad_codigo'
        ]);

    // Clientes para referencia
    $clientesReferencia = Cliente::select('id', 'no_cliente', 'nombre', 'apellido_paterno', 'apellido_materno')
        ->where('tipo_cliente', 'C')
        ->orderBy('nombre')
        ->get()
        ->map(function($cliente) {
            $cliente->nombre_completo = "{$cliente->no_cliente} - {$cliente->nombre} {$cliente->apellido_paterno} {$cliente->apellido_materno}";
            return $cliente;
        });

    return view('clientes.create', compact(
        'institutos',
        'regimenes',
        'tramites',
        'modalidades',
        'modalidadesImss',
        'modalidadesIssste',
        'clientesReferencia',
        'combinaciones'
    ));
}


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validar solo campos básicos para prospecto
        $validated = $request->validate([
            'nombre' => 'required|string|max:100',
            'apellido_paterno' => 'nullable|string|max:100',
            'apellido_materno' => 'nullable|string|max:100',
            'fecha_nacimiento' => 'nullable|date',
        ]);
        
        try {
            DB::beginTransaction();
            
            // Datos básicos para el prospecto
            $clienteData = [
                'nombre' => $validated['nombre'],
                'apellido_paterno' => $validated['apellido_paterno'] ?? null,
                'apellido_materno' => $validated['apellido_materno'] ?? null,
                'fecha_nacimiento' => $validated['fecha_nacimiento'] ?? null,
                'tipo_cliente' => 'P', // Siempre Prospecto al crear
                'estatus' => null, // Prospectos no tienen estatus
                'no_cliente' => null, // Sin número de cliente
                'creado_por' => auth()->id() ?? 1,
                'fecha_contrato' => $request->fecha_contrato ?: null,
                'cliente_referidor_id' => $request->cliente_referidor_id ?: null,
            ];
            
            // Calcular edad si hay fecha de nacimiento
            if ($clienteData['fecha_nacimiento']) {
                $clienteData['edad'] = Carbon::parse($clienteData['fecha_nacimiento'])->age;
            }
            
            // Crear el cliente (automáticamente será Prospecto)
            $cliente = Cliente::create($clienteData);
            
            // Guardar CURP si se proporciona
            if ($request->filled('curp')) {
                ClienteCurp::create([
                    'cliente_id' => $cliente->id,
                    'curp' => $request->curp,
                    'es_principal' => true
                ]);
            }
            
            // Guardar contacto celular si se proporciona
            if ($request->filled('celular1')) {
                ClienteContacto::create([
                    'cliente_id' => $cliente->id,
                    'tipo' => 'celular1',
                    'valor' => $request->celular1,
                    'es_principal' => true
                ]);
            }
            
            DB::commit();
            
            return redirect()->route('prospectos.index')
                ->with('success', '✅ Prospecto creado exitosamente. Ahora puedes clasificarlo en la vista de Prospectos.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()
                ->withInput()
                ->with('error', '❌ Error al crear el prospecto: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
public function show(Cliente $cliente)
{
    // Cargamos todas las relaciones necesarias
    $cliente->load([
        'instituto',
        'regimen',
        'tramite',
        'modalidad',
        'instituto2',
        'regimen2',
        'tramite2',
        'referidor',
        'creadoPor',
        'actualizadoPor',
        'curps' => function($query) {
            $query->orderBy('es_principal', 'desc');
        },
        'rfcs' => function($query) {
            $query->orderBy('es_principal', 'desc');
        },
        'nss' => function($query) {
            $query->orderBy('es_principal', 'desc');
        },
        'contactos' => function($query) {
            // Ordenar por el nombre del tipo de contacto usando la relación tipoContacto
            $query->with('tipoContacto')
                  ->join('catalogo_tipos_contacto', 'cliente_contactos.tipo_contacto_id', '=', 'catalogo_tipos_contacto.id')
                  ->orderBy('catalogo_tipos_contacto.nombre', 'asc')
                  ->select('cliente_contactos.*'); // Muy importante para no romper el modelo
        }
    ]);

    return view('clientes.show', compact('cliente'));
}



    /**
     * Show the form for editing the specified resource.
     */
public function edit(Cliente $cliente)
{
    if ($cliente->deleted_at) {
        return redirect()->route('clientes.index')
            ->with('error', 'No se puede editar un cliente eliminado.');
    }

    if ($cliente->tipo_cliente !== 'C') {
        return redirect()->route('clientes.show', $cliente)
            ->with('warning', 'Solo los clientes tipo "Cliente" pueden ser editados completamente.');
    }

    // Catálogos
    //$institutos = CatalogoInstituto::where('activo', true)->get();
    //$regimenes = CatalogoRegimen::where('activo', true)->get();
    $tramites = CatalogoTramite::where('activo', true)->get();
    $tramitesISSSTE = CatalogoTramiteIssste::where('activo', true)->get();
    $estatuses = CatalogoEstatusCliente::where('activo', true)->orderBy('orden')->get();
    $tiposContacto = CatalogoTiposContacto::where('activo', true)->orderBy('orden')->get();

    $regimenes = CatalogoRegimen::where('activo', true)
        ->whereIn('codigo', ['R73','R97'])
        ->get();

    $regimenesISSSTE = CatalogoRegimen::where('activo', true)
        ->whereIn('codigo', ['DT','CI'])
        ->get();

    $institutos = CatalogoInstituto::where('activo', true)
        ->whereIn('codigo', ['INA','IMS'])
        ->get();

    $institutosISSSTE = CatalogoInstituto::where('activo', true)
        ->whereIn('codigo', ['INA','IST'])
        ->get();
		
    // Modalidades IMSS
    $modalidadesImss = CatalogoModalidad::where('activo', true)
        ->whereIn('codigo', ['MNA','M10','M40'])
        ->get();

    // Modalidades ISSSTE
    $modalidadesIssste = CatalogoModalidad::where('activo', true)
        ->whereIn('codigo', ['MNA','MCV'])
        ->get();

    // 🔥 Tabla pivote para selects anidados
    $combinaciones = DB::table('catalogo_modalidad_regimen_tramite')
        ->whereNull('deleted_at')
        ->where('activo', 1)
        ->get([
            'instituto_codigo',
            'regimen_codigo',
            'tramite_codigo',
            'modalidad_codigo'
        ]);

    // Clientes referencia
    $clientesReferencia = Cliente::select('id','no_cliente','nombre','apellido_paterno','apellido_materno')
        ->where('id','!=',$cliente->id)
        ->orderBy('nombre')
        ->get()
        ->map(function($c) {
            $c->nombre_completo = "{$c->no_cliente} - {$c->nombre} {$c->apellido_paterno} {$c->apellido_materno}";
            return $c;
        });

    // Cargar relaciones
    $cliente->load(['curps','rfcs','nss','contactos']);

    $curps = $cliente->curps->map(function($item) {
        return [
            'curp' => $item->curp,
            'es_principal' => $item->es_principal
        ];
    })->toArray();

    $rfcs = $cliente->rfcs->map(function($item) {
        return [
            'rfc' => $item->rfc,
            'es_principal' => $item->es_principal
        ];
    })->toArray();

    $nss = $cliente->nss->map(function($item) {
        return [
            'nss' => $item->nss,
            'es_principal' => $item->es_principal
        ];
    })->toArray();

    $contactos = [];
    foreach ($cliente->contactos as $contacto) {
        $contactos[] = [
            'id' => $contacto->id,
            'tipo_contacto_id' => $contacto->tipo_contacto_id,
            'valor' => $contacto->valor,
            'es_principal' => $contacto->es_principal
        ];
    }

    return view('clientes.edit', compact(
        'cliente',
        'institutos',
        'institutosISSSTE',
        'regimenes',
        'regimenesISSSTE',
        'tramites',
        'tramitesISSSTE',
        'estatuses',
        'tiposContacto',
        'modalidadesImss',
        'modalidadesIssste',
        'clientesReferencia',
        'curps',
        'rfcs',
        'nss',
        'contactos',
        'combinaciones'
    ));
}




    /**
     * Update the specified resource in storage.
     */
public function update(Request $request, Cliente $cliente)
{
    if ($cliente->deleted_at) {
        return redirect()->route('clientes.index')
            ->with('error', 'No se puede actualizar un cliente eliminado.');
    }

    if ($cliente->tipo_cliente !== 'C') {
        return redirect()->route('clientes.show', $cliente)
            ->with('warning', 'Solo los clientes tipo "Cliente" pueden ser actualizados completamente.');
    }

    // =============================================
    // 🚨 PASO 1: VALIDAR UNICIDAD ANTES DE TODO
    // =============================================
    
    // Validar CURPs duplicadas en OTROS clientes
    if ($request->has('curps') && is_array($request->curps)) {
        foreach ($request->curps as $curpItem) {
            if (!empty($curpItem['curp'])) {
                $curp = trim($curpItem['curp']);
                $existe = ClienteCurp::where('curp', $curp)
                    ->where('cliente_id', '!=', $cliente->id)
                    ->exists();
                
                if ($existe) {
                    return back()
                        ->withInput()
                        ->with('error', "❌ La CURP '{$curp}' ya está registrada para OTRO cliente. No se puede guardar.");
                }
            }
        }
    }

    // Validar RFCs duplicados en OTROS clientes
    if ($request->has('rfcs') && is_array($request->rfcs)) {
        foreach ($request->rfcs as $rfcItem) {
            if (!empty($rfcItem['rfc'])) {
                $rfc = trim($rfcItem['rfc']);
                $existe = ClienteRfc::where('rfc', $rfc)
                    ->where('cliente_id', '!=', $cliente->id)
                    ->exists();
                
                if ($existe) {
                    return back()
                        ->withInput()
                        ->with('error', "❌ El RFC '{$rfc}' ya está registrado para OTRO cliente. No se puede guardar.");
                }
            }
        }
    }

    // Validar NSS duplicados en OTROS clientes
    if ($request->has('nss') && is_array($request->nss)) {
        foreach ($request->nss as $nssItem) {
            if (!empty($nssItem['nss'])) {
                $nss = trim($nssItem['nss']);
                $existe = ClienteNss::where('nss', $nss)
                    ->where('cliente_id', '!=', $cliente->id)
                    ->exists();
                
                if ($existe) {
                    return back()
                        ->withInput()
                        ->with('error', "❌ El NSS '{$nss}' ya está registrado para OTRO cliente. No se puede guardar.");
                }
            }
        }
    }

    // =============================================
    // 🚨 PASO 2: VALIDAR CAMPOS DEL FORMULARIO
    // =============================================
    
    $validated = $request->validate([
        // Datos personales
        'nombre' => 'required|string|max:255',
        'apellido_paterno' => 'required|string|max:255',
        'apellido_materno' => 'nullable|string|max:255',
        'fecha_nacimiento' => 'nullable|date',
        'estatus_cliente_id' => 'required|exists:catalogo_estatus_clientes,id',
        'fecha_contrato' => 'nullable|date',
        'cliente_referidor_id' => 'nullable|exists:clientes,id',
        
        // Datos económicos
        'pension_default' => 'nullable|numeric',
        'pension_normal' => 'nullable|numeric',
        'comision' => 'nullable|numeric',
        'honorarios' => 'nullable|numeric',
        
        // IMSS
        'instituto_id' => 'nullable|exists:catalogo_institutos,id',
        'regimen_id' => 'nullable|exists:catalogo_regimenes,id',
        'tramite_id' => 'nullable|exists:catalogo_tramites,id',
        'modalidad_id' => 'nullable|exists:catalogo_modalidades,id',
        'semanas_imss' => 'nullable|integer',
        'fecha_alta' => 'nullable|date',
        'fecha_baja' => 'nullable|date',
        
        // ISSSTE
        'instituto2_id' => 'nullable|exists:catalogo_institutos,id',
        'regimen2_id' => 'nullable|exists:catalogo_regimenes,id',
        'tramite2_id' => 'nullable|exists:catalogo_tramites,id',
        'modalidad2_id' => 'nullable|string|max:10',
        'anios_servicio_issste' => 'nullable|integer',
        'fecha_alta_issste' => 'nullable|date',
        'fecha_baja_issste' => 'nullable|date',
        'nss_issste' => 'nullable|string|max:11',
        
        // Arrays dinámicos
        'curps' => 'nullable|array',
        'curps.*.curp' => 'required|string|max:18',
        'curps.*.es_principal' => 'nullable|boolean',
        
        'rfcs' => 'nullable|array',
        'rfcs.*.rfc' => 'required|string|max:13',
        'rfcs.*.es_principal' => 'nullable|boolean',
        
        'nss' => 'nullable|array',
        'nss.*.nss' => 'required|string|max:11',
        'nss.*.es_principal' => 'nullable|boolean',
        
		// ✅ VALIDACIÓN CORRECTA PARA CONTACTOS
		'contactos' => 'nullable|array',
		'contactos.*.tipo_contacto_id' => 'required|exists:catalogo_tipos_contacto,id',
		'contactos.*.valor' => 'required|string|max:255',
		'contactos.*.es_principal' => 'nullable|boolean',
    ]);

    // =============================================
    // 🚨 PASO 3: SOLO SI TODO ESTÁ BIEN, GUARDAR
    // =============================================
    
    try {
        DB::beginTransaction();

        // Actualizar cliente
        $cliente->update([
            'nombre' => $validated['nombre'],
            'apellido_paterno' => $validated['apellido_paterno'],
            'apellido_materno' => $validated['apellido_materno'] ?? null,
            'fecha_nacimiento' => $validated['fecha_nacimiento'] ?? null,
            'estatus_cliente_id' => $validated['estatus_cliente_id'],
            'fecha_contrato' => $validated['fecha_contrato'] ?? null,
            'cliente_referidor_id' => $validated['cliente_referidor_id'] ?? null,
            'pension_default' => $validated['pension_default'] ?? 0,
            'pension_normal' => $validated['pension_normal'] ?? 0,
            'comision' => $validated['comision'] ?? 0,
            'honorarios' => $validated['honorarios'] ?? 0,
            'instituto_id' => $validated['instituto_id'] ?? null,
            'regimen_id' => $validated['regimen_id'] ?? null,
            'tramite_id' => $validated['tramite_id'] ?? null,
            'modalidad_id' => $validated['modalidad_id'] ?? null,
            'semanas_imss' => $validated['semanas_imss'] ?? null,
            'fecha_alta' => $validated['fecha_alta'] ?? null,
            'fecha_baja' => $validated['fecha_baja'] ?? null,
            'instituto2_id' => $validated['instituto2_id'] ?? null,
            'regimen2_id' => $validated['regimen2_id'] ?? null,
            'tramite2_id' => $validated['tramite2_id'] ?? null,
            'modalidad2_id' => $validated['modalidad2_id'] ?? null,
            'anios_servicio_issste' => $validated['anios_servicio_issste'] ?? null,
            'fecha_alta_issste' => $validated['fecha_alta_issste'] ?? null,
            'fecha_baja_issste' => $validated['fecha_baja_issste'] ?? null,
            'nss_issste' => $validated['nss_issste'] ?? null,
        ]);

        // ✅ CURPs - Solo si pasó validación
        if ($request->has('curps')) {
            $cliente->curps()->delete();
            foreach ($validated['curps'] as $curpItem) {
                if (!empty($curpItem['curp'])) {
                    $cliente->curps()->create([
                        'curp' => $curpItem['curp'],
                        'es_principal' => $curpItem['es_principal'] ?? false
                    ]);
                }
            }
        }

        // ✅ RFCs
        if ($request->has('rfcs')) {
            $cliente->rfcs()->delete();
            foreach ($validated['rfcs'] as $rfcItem) {
                if (!empty($rfcItem['rfc'])) {
                    $cliente->rfcs()->create([
                        'rfc' => $rfcItem['rfc'],
                        'es_principal' => $rfcItem['es_principal'] ?? false
                    ]);
                }
            }
        }

        // ✅ NSS
        if ($request->has('nss')) {
            $cliente->nss()->delete();
            foreach ($validated['nss'] as $nssItem) {
                if (!empty($nssItem['nss'])) {
                    $cliente->nss()->create([
                        'nss' => $nssItem['nss'],
                        'es_principal' => $nssItem['es_principal'] ?? false
                    ]);
                }
            }
        }

		// ✅ CONTACTOS - Guardado CORRECTO
		if ($request->has('contactos')) {
			$cliente->contactos()->delete();
			foreach ($validated['contactos'] as $contacto) {
				if (!empty($contacto['valor']) && !empty($contacto['tipo_contacto_id'])) {
					$cliente->contactos()->create([
						'tipo_contacto_id' => $contacto['tipo_contacto_id'],  // ✅ BIEN
						'valor' => $contacto['valor'],
						'es_principal' => $contacto['es_principal'] ?? false
					]);
				}
			}
		}

        DB::commit();

        return redirect()->route('clientes.edit', $cliente)
            ->with('success', '✅ Cliente actualizado correctamente.');

    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Error al actualizar cliente: ' . $e->getMessage());
        
        // 🚨 Si el error es por duplicado, mostrar mensaje amigable
        if (strpos($e->getMessage(), 'Duplicate entry') !== false) {
            if (strpos($e->getMessage(), 'cliente_curps') !== false) {
                return back()->withInput()->with('error', '❌ La CURP ya está registrada para otro cliente.');
            }
            if (strpos($e->getMessage(), 'cliente_rfcs') !== false) {
                return back()->withInput()->with('error', '❌ El RFC ya está registrado para otro cliente.');
            }
            if (strpos($e->getMessage(), 'cliente_nsss') !== false) {
                return back()->withInput()->with('error', '❌ El NSS ya está registrado para otro cliente.');
            }
        }
        
        return back()
            ->withInput()
            ->with('error', '❌ Error al actualizar el cliente: ' . $e->getMessage());
    }
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cliente $cliente)
    {
        try {
            DB::beginTransaction();
            
            $cliente->delete();
            
            DB::commit();
            
            return redirect()->route('clientes.index')
                ->with('success', 'Cliente eliminado exitosamente.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()
                ->with('error', 'Error al eliminar el cliente: ' . $e->getMessage());
        }
    }

    /**
     * Exportar clientes a CSV (SOLO tipo_cliente = 'C')
     */
    public function exportar()
    {
        // ✅ Exportar SOLO CLIENTES
        $clientes = Cliente::where('tipo_cliente', 'C')
            ->with(['instituto', 'regimen', 'tramite', 'modalidad'])
            ->orderBy('created_at', 'desc')
            ->get();
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename=clientes_' . date('Y-m-d') . '.csv',
        ];
        
        $callback = function() use ($clientes) {
            $file = fopen('php://output', 'w');
            
            fputcsv($file, [
                'No. Cliente',
                'Tipo Cliente',
                'Nombre',
                'Apellido Paterno',
                'Apellido Materno',
                'Fecha Nacimiento',
                'Edad',
                'Fecha Contrato',
                'Institución',
                'Régimen',
                'Trámite',
                'Modalidad',
                'Estatus',
                'Pensión Default',
                'Pensión Normal',
                'Comisión',
                'Honorarios',
                'Fecha Alta',
                'Fecha Baja',
                'Creado',
                'Actualizado'
            ]);
            
            foreach ($clientes as $cliente) {
                fputcsv($file, [
                    $cliente->no_cliente ?? '',
                    $cliente->tipo_cliente ?? '',
                    $cliente->nombre ?? '',
                    $cliente->apellido_paterno ?? '',
                    $cliente->apellido_materno ?? '',
                    $cliente->fecha_nacimiento ? $cliente->fecha_nacimiento->format('Y-m-d') : '',
                    $cliente->edad ?? '',
                    $cliente->fecha_contrato ? $cliente->fecha_contrato->format('Y-m-d') : '',
                    $cliente->instituto->nombre ?? '',
                    $cliente->regimen->nombre ?? '',
                    $cliente->tramite->nombre ?? '',
                    $cliente->modalidad->nombre ?? '',
                    $cliente->estatus ?? '',
                    $cliente->pension_default ?? '0.00',
                    $cliente->pension_normal ?? '0.00',
                    $cliente->comision ?? '0.00',
                    $cliente->honorarios ?? '0.00',
                    $cliente->fecha_alta ? $cliente->fecha_alta->format('Y-m-d') : '',
                    $cliente->fecha_baja ? $cliente->fecha_baja->format('Y-m-d') : '',
                    $cliente->created_at ? $cliente->created_at->format('Y-m-d H:i') : '',
                    $cliente->updated_at ? $cliente->updated_at->format('Y-m-d H:i') : '',
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }

    /**
     * Cambiar estatus de cliente
     */
    public function cambiarEstatus(Request $request, Cliente $cliente)
    {
        if ($cliente->deleted_at) {
            return redirect()->route('clientes.index')
                ->with('error', 'No se puede cambiar el estatus de un cliente eliminado.');
        }
        
        return view('clientes.cambiar-estatus', compact('cliente'));
    }
    
    public function cambiarEstatusUpdate(Request $request, Cliente $cliente)
    {
        if ($cliente->deleted_at) {
            return redirect()->route('clientes.index')
                ->with('error', 'No se puede cambiar el estatus de un cliente eliminado.');
        }
        
        $request->validate([
            'estatus' => 'required|in:Activo,Suspendido,Terminado,Baja',
        ]);
        
        $cliente->estatus = $request->estatus;
        $cliente->actualizado_por = auth()->id();
        $cliente->save();
        
        echo "<script>
            window.dispatchEvent(new CustomEvent('cliente-actualizado'));
        </script>";
        
        return redirect()->route('clientes.show', $cliente)
            ->with('success', 'Estatus actualizado a: ' . $request->estatus)
            ->with('event_script', true);
    }

    /**
     * Obtener estadísticas de clientes (SOLO tipo_cliente = 'C')
     */
public function estadisticas()
{
    $estadisticas = [
        'total' => Cliente::where('tipo_cliente', 'C')->count(),
        'activos' => Cliente::where('tipo_cliente', 'C')->conEstatus('Activo')->count(),
        'pendientes' => Cliente::where('tipo_cliente', 'C')->conEstatus('pendiente')->count(),
        'suspendidos' => Cliente::where('tipo_cliente', 'C')->conEstatus('Suspendido')->count(),
        'por_instituto' => Cliente::where('tipo_cliente', 'C')
            ->select('instituto_id', DB::raw('count(*) as total'))
            ->groupBy('instituto_id')
            ->with('instituto')
            ->get(),
        'creados_hoy' => Cliente::where('tipo_cliente', 'C')
            ->whereDate('created_at', today())
            ->count(),
        'actualizados_hoy' => Cliente::where('tipo_cliente', 'C')
            ->whereDate('updated_at', today())
            ->count(),
    ];

    return response()->json($estadisticas);
}


    /**
     * Obtener regímenes por instituto
     */
    public function getRegimenesPorInstituto($institutoId)
    {
        $regimenes = CatalogoRegimen::where('instituto_id', $institutoId)->get();
        return response()->json($regimenes);
    }

    /**
     * Agregar validaciones COMPLETAS para todos los campos
     * Incluye: unicidad en BD y no repetición entre campos del mismo tipo
     */
    private function agregarValidacionesCompletas(&$rules, Request $request, Cliente $cliente)
    {
        // ========== VALIDACIONES CURP ==========
        $curps = [
            'curp' => $request->curp,
            'curp2' => $request->curp2,
            'curp3' => $request->curp3
        ];
        
        // CURP principal es obligatoria
        $rules['curp'] = [
            'required',
            'max:18',
            function ($attribute, $value, $fail) use ($curps, $cliente) {
                // 1. Validar que no se repita en otros clientes
                $existeEnBD = ClienteCurp::where('curp', $value)
                    ->where('cliente_id', '!=', $cliente->id)
                    ->exists();
                
                if ($existeEnBD) {
                    $fail("La CURP '{$value}' ya está registrada para otro cliente.");
                }
                
                // 2. Validar que no sea igual a CURP2 o CURP3
                foreach ($curps as $campo => $valor) {
                    if ($campo !== 'curp' && !empty($valor) && $valor === $value) {
                        $fail("La CURP principal no puede ser igual a {$campo}.");
                    }
                }
            }
        ];
        
        // Validar CURP2
        if ($request->filled('curp2')) {
            $rules['curp2'] = [
                'nullable',
                'max:18',
                function ($attribute, $value, $fail) use ($curps, $cliente) {
                    // 1. Validar que no se repita en otros clientes
                    $existeEnBD = ClienteCurp::where('curp', $value)
                        ->where('cliente_id', '!=', $cliente->id)
                        ->exists();
                    
                    if ($existeEnBD) {
                        $fail("La CURP2 '{$value}' ya está registrada para otro cliente.");
                    }
                    
                    // 2. Validar que no sea igual a CURP o CURP3
                    foreach ($curps as $campo => $valor) {
                        if ($campo !== 'curp2' && !empty($valor) && $valor === $value) {
                            $fail("La CURP2 no puede ser igual a {$campo}.");
                        }
                    }
                }
            ];
        }
        
        // Validar CURP3
        if ($request->filled('curp3')) {
            $rules['curp3'] = [
                'nullable',
                'max:18',
                function ($attribute, $value, $fail) use ($curps, $cliente) {
                    // 1. Validar que no se repita en otros clientes
                    $existeEnBD = ClienteCurp::where('curp', $value)
                        ->where('cliente_id', '!=', $cliente->id)
                        ->exists();
                    
                    if ($existeEnBD) {
                        $fail("La CURP3 '{$value}' ya está registrada para otro cliente.");
                    }
                    
                    // 2. Validar que no sea igual a CURP o CURP2
                    foreach ($curps as $campo => $valor) {
                        if ($campo !== 'curp3' && !empty($valor) && $valor === $value) {
                            $fail("La CURP3 no puede ser igual a {$campo}.");
                        }
                    }
                }
            ];
        }
        
        // ========== VALIDACIONES RFC ==========
        $rfcs = [
            'rfc' => $request->rfc,
            'rfc2' => $request->rfc2
        ];
        
        // RFC principal es obligatorio
        $rules['rfc'] = [
            'required',
            'max:13',
            function ($attribute, $value, $fail) use ($rfcs, $cliente) {
                // 1. Validar que no se repita en otros clientes
                $existeEnBD = ClienteRfc::where('rfc', $value)
                    ->where('cliente_id', '!=', $cliente->id)
                    ->exists();
                
                if ($existeEnBD) {
                    $fail("El RFC '{$value}' ya está registrado para otro cliente.");
                }
                
                // 2. Validar que no sea igual a RFC2
                if (!empty($rfcs['rfc2']) && $rfcs['rfc2'] === $value) {
                    $fail("El RFC principal no puede ser igual a RFC2.");
                }
            }
        ];
        
        // Validar RFC2
        if ($request->filled('rfc2')) {
            $rules['rfc2'] = [
                'nullable',
                'max:13',
                function ($attribute, $value, $fail) use ($rfcs, $cliente) {
                    // 1. Validar que no se repita en otros clientes
                    $existeEnBD = ClienteRfc::where('rfc', $value)
                        ->where('cliente_id', '!=', $cliente->id)
                        ->exists();
                    
                    if ($existeEnBD) {
                        $fail("El RFC2 '{$value}' ya está registrado para otro cliente.");
                    }
                    
                    // 2. Validar que no sea igual a RFC principal
                    if (!empty($rfcs['rfc']) && $rfcs['rfc'] === $value) {
                        $fail("El RFC2 no puede ser igual al RFC principal.");
                    }
                }
            ];
        }
        
        // ========== VALIDACIONES NSS ==========
        $nssArray = [
            'nss' => $request->nss,
            'nss2' => $request->nss2,
            'nss3' => $request->nss3,
            'nss4' => $request->nss4
        ];
        
        // NSS principal es obligatorio
        $rules['nss'] = [
            'required',
            'max:11',
            function ($attribute, $value, $fail) use ($nssArray, $cliente) {
                // 1. Validar que no se repita en otros clientes
                $existeEnBD = ClienteNss::where('nss', $value)
                    ->where('cliente_id', '!=', $cliente->id)
                    ->exists();
                
                if ($existeEnBD) {
                    $fail("El NSS '{$value}' ya está registrado para otro cliente.");
                }
                
                // 2. Validar que no sea igual a NSS2, NSS3 o NSS4
                foreach ($nssArray as $campo => $valor) {
                    if ($campo !== 'nss' && !empty($valor) && $valor === $value) {
                        $fail("El NSS principal no puede ser igual a {$campo}.");
                    }
                }
            }
        ];
        
        // Validar NSS2
        if ($request->filled('nss2')) {
            $rules['nss2'] = [
                'nullable',
                'max:11',
                function ($attribute, $value, $fail) use ($nssArray, $cliente) {
                    // 1. Validar que no se repita en otros clientes
                    $existeEnBD = ClienteNss::where('nss', $value)
                        ->where('cliente_id', '!=', $cliente->id)
                        ->exists();
                    
                    if ($existeEnBD) {
                        $fail("El NSS2 '{$value}' ya está registrado para otro cliente.");
                    }
                    
                    // 2. Validar que no sea igual a otros NSS
                    foreach ($nssArray as $campo => $valor) {
                        if ($campo !== 'nss2' && !empty($valor) && $valor === $value) {
                            $fail("El NSS2 no puede ser igual a {$campo}.");
                        }
                    }
                }
            ];
        }
        
        // Validar NSS3
        if ($request->filled('nss3')) {
            $rules['nss3'] = [
                'nullable',
                'max:11',
                function ($attribute, $value, $fail) use ($nssArray, $cliente) {
                    // 1. Validar que no se repita en otros clientes
                    $existeEnBD = ClienteNss::where('nss', $value)
                        ->where('cliente_id', '!=', $cliente->id)
                        ->exists();
                    
                    if ($existeEnBD) {
                        $fail("El NSS3 '{$value}' ya está registrado para otro cliente.");
                    }
                    
                    // 2. Validar que no sea igual a otros NSS
                    foreach ($nssArray as $campo => $valor) {
                        if ($campo !== 'nss3' && !empty($valor) && $valor === $value) {
                            $fail("El NSS3 no puede ser igual a {$campo}.");
                        }
                    }
                }
            ];
        }
        
        // Validar NSS4
        if ($request->filled('nss4')) {
            $rules['nss4'] = [
                'nullable',
                'max:11',
                function ($attribute, $value, $fail) use ($nssArray, $cliente) {
                    // 1. Validar que no se repita en otros clientes
                    $existeEnBD = ClienteNss::where('nss', $value)
                        ->where('cliente_id', '!=', $cliente->id)
                        ->exists();
                    
                    if ($existeEnBD) {
                        $fail("El NSS4 '{$value}' ya está registrado para otro cliente.");
                    }
                    
                    // 2. Validar que no sea igual a otros NSS
                    foreach ($nssArray as $campo => $valor) {
                        if ($campo !== 'nss4' && !empty($valor) && $valor === $value) {
                            $fail("El NSS4 no puede ser igual a {$campo}.");
                        }
                    }
                }
            ];
        }
    }

    /**
     * Manejar CURPs del cliente (CORREGIDO - eliminación REAL)
     */
    private function manejarCurpsCorregido(Cliente $cliente, Request $request)
    {
        // Obtener todos los CURPs actuales del cliente
        $curpsActuales = $cliente->curps()->get();
        
        // Arrays para control
        $curpsMantener = [];
        $curpsEliminar = $curpsActuales->pluck('id')->toArray();
        
        // 1. CURP principal (obligatoria)
        if ($request->filled('curp')) {
            $curpPrincipal = $curpsActuales->where('curp', $request->curp)->first();
            
            if ($curpPrincipal) {
                // Ya existe, actualizar y mantener
                $curpPrincipal->update(['es_principal' => true]);
                $curpsMantener[] = $curpPrincipal->id;
                $curpsEliminar = array_diff($curpsEliminar, [$curpPrincipal->id]);
            } else {
                // Crear nuevo CURP principal
                $nuevoCurp = ClienteCurp::create([
                    'cliente_id' => $cliente->id,
                    'curp' => $request->curp,
                    'es_principal' => true
                ]);
                $curpsMantener[] = $nuevoCurp->id;
            }
        }
        
        // 2. CURP2 (opcional)
        if ($request->filled('curp2')) {
            $curp2 = $curpsActuales->where('curp', $request->curp2)->first();
            
            if ($curp2) {
                // Ya existe, asegurar que NO sea principal y mantener
                $curp2->update(['es_principal' => false]);
                $curpsMantener[] = $curp2->id;
                $curpsEliminar = array_diff($curpsEliminar, [$curp2->id]);
            } else {
                // Crear nuevo CURP secundario
                $nuevoCurp2 = ClienteCurp::create([
                    'cliente_id' => $cliente->id,
                    'curp' => $request->curp2,
                    'es_principal' => false
                ]);
                $curpsMantener[] = $nuevoCurp2->id;
            }
        }
        
        // 3. CURP3 (opcional)
        if ($request->filled('curp3')) {
            $curp3 = $curpsActuales->where('curp', $request->curp3)->first();
            
            if ($curp3) {
                // Ya existe, asegurar que NO sea principal y mantener
                $curp3->update(['es_principal' => false]);
                $curpsMantener[] = $curp3->id;
                $curpsEliminar = array_diff($curpsEliminar, [$curp3->id]);
            } else {
                // Crear nuevo CURP secundario
                $nuevoCurp3 = ClienteCurp::create([
                    'cliente_id' => $cliente->id,
                    'curp' => $request->curp3,
                    'es_principal' => false
                ]);
                $curpsMantener[] = $nuevoCurp3->id;
            }
        }
        
        // 4. Eliminar CURPs que ya no se necesitan (ELIMINACIÓN REAL)
        if (!empty($curpsEliminar)) {
            ClienteCurp::whereIn('id', $curpsEliminar)->delete();
        }
        
        // Log de la operación
        Log::info('CURPs actualizadas para cliente', [
            'cliente_id' => $cliente->id,
            'curps_mantenidos' => $curpsMantener,
            'curps_eliminados' => $curpsEliminar,
            'curp_principal' => $request->curp,
            'curp2' => $request->curp2,
            'curp3' => $request->curp3
        ]);
    }

    /**
     * Manejar RFCs del cliente (CORREGIDO - eliminación REAL)
     */
    private function manejarRfcsCorregido(Cliente $cliente, Request $request)
    {
        // Obtener todos los RFCs actuales del cliente
        $rfcsActuales = $cliente->rfcs()->get();
        
        // Arrays para control
        $rfcsMantener = [];
        $rfcsEliminar = $rfcsActuales->pluck('id')->toArray();
        
        // 1. RFC principal (obligatorio)
        if ($request->filled('rfc')) {
            $rfcPrincipal = $rfcsActuales->where('rfc', $request->rfc)->first();
            
            if ($rfcPrincipal) {
                // Ya existe, actualizar y mantener
                $rfcPrincipal->update(['es_principal' => true]);
                $rfcsMantener[] = $rfcPrincipal->id;
                $rfcsEliminar = array_diff($rfcsEliminar, [$rfcPrincipal->id]);
            } else {
                // Crear nuevo RFC principal
                $nuevoRfc = ClienteRfc::create([
                    'cliente_id' => $cliente->id,
                    'rfc' => $request->rfc,
                    'es_principal' => true
                ]);
                $rfcsMantener[] = $nuevoRfc->id;
            }
        }
        
        // 2. RFC2 (opcional)
        if ($request->filled('rfc2')) {
            $rfc2 = $rfcsActuales->where('rfc', $request->rfc2)->first();
            
            if ($rfc2) {
                // Ya existe, asegurar que NO sea principal y mantener
                $rfc2->update(['es_principal' => false]);
                $rfcsMantener[] = $rfc2->id;
                $rfcsEliminar = array_diff($rfcsEliminar, [$rfc2->id]);
            } else {
                // Crear nuevo RFC secundario
                $nuevoRfc2 = ClienteRfc::create([
                    'cliente_id' => $cliente->id,
                    'rfc' => $request->rfc2,
                    'es_principal' => false
                ]);
                $rfcsMantener[] = $nuevoRfc2->id;
            }
        }
        
        // 3. Eliminar RFCs que ya no se necesitan (ELIMINACIÓN REAL)
        if (!empty($rfcsEliminar)) {
            ClienteRfc::whereIn('id', $rfcsEliminar)->delete();
        }
        
        // Log de la operación
        Log::info('RFCs actualizadas para cliente', [
            'cliente_id' => $cliente->id,
            'rfcs_mantenidos' => $rfcsMantener,
            'rfcs_eliminados' => $rfcsEliminar,
            'rfc_principal' => $request->rfc,
            'rfc2' => $request->rfc2
        ]);
    }

private function manejarNssCorregido(Cliente $cliente, Request $request)
{
    // Obtener todos los NSS actuales
    $nssActuales = $cliente->nss()->get();
    $nssEliminar = $nssActuales->pluck('id')->toArray();
    $nssMantener = [];

    // Recorrer los NSS enviados desde el formulario
    if ($request->filled('nss') && is_array($request->nss)) {
        foreach ($request->nss as $nssItem) {
            if (!empty($nssItem['nss'])) {
                $esPrincipal = isset($nssItem['es_principal']) ? $nssItem['es_principal'] : 0;

                // Buscar si ya existe
                $nssExistente = $nssActuales->where('nss', $nssItem['nss'])->first();

                if ($nssExistente) {
                    // Actualizar flag principal
                    $nssExistente->update(['es_principal' => $esPrincipal]);
                    $nssMantener[] = $nssExistente->id;
                    $nssEliminar = array_diff($nssEliminar, [$nssExistente->id]);
                } else {
                    // Crear nuevo NSS
                    $nuevoNss = ClienteNss::create([
                        'cliente_id' => $cliente->id,
                        'nss' => $nssItem['nss'],
                        'es_principal' => $esPrincipal
                    ]);
                    $nssMantener[] = $nuevoNss->id;
                }
            }
        }
    }

    // Eliminar NSS que ya no están en el formulario
    if (!empty($nssEliminar)) {
        ClienteNss::whereIn('id', $nssEliminar)->delete();
    }

    // Logging
    Log::info('NSS actualizados para cliente', [
        'cliente_id' => $cliente->id,
        'nss_mantenidos' => $nssMantener,
        'nss_eliminados' => $nssEliminar,
    ]);
}


    /**
     * Manejar contactos del cliente (CORREGIDO - eliminación REAL)
     */
    private function manejarContactosCorregido(Cliente $cliente, Request $request)
    {
        // Eliminar todos los contactos existentes (ELIMINACIÓN REAL)
        $cliente->contactos()->delete();
        
        $tiposContacto = [
            'celular1' => $request->celular1,
            'celular2' => $request->celular2,
            'tel_casa' => $request->tel_casa,
            'correo1' => $request->correo1,
            'correo2' => $request->correo2,
            'correo_personal' => $request->correo_personal,
        ];
        
        foreach ($tiposContacto as $tipo => $valor) {
            if (!empty($valor)) {
                ClienteContacto::create([
                    'cliente_id' => $cliente->id,
                    'tipo' => $tipo,
                    'valor' => $valor,
                    'es_principal' => $tipo === 'celular1' || $tipo === 'correo1'
                ]);
            }
        }
        
        // Log de la operación
        Log::info('Contactos actualizados para cliente', [
            'cliente_id' => $cliente->id,
            'contactos_creados' => array_filter($tiposContacto)
        ]);
    }

    /**
     * Detectar qué campo causó el error de duplicado
     */
    private function detectarCampoDuplicado($errorMessage)
    {
        if (strpos($errorMessage, 'cliente_curps') !== false) {
            return 'CURP';
        } elseif (strpos($errorMessage, 'cliente_rfcs') !== false) {
            return 'RFC';
        } elseif (strpos($errorMessage, 'cliente_nsss') !== false) {
            return 'NSS';
        } elseif (strpos($errorMessage, 'cliente_contactos') !== false) {
            return 'Contacto';
        } else {
            // Intentar extraer el valor duplicado del mensaje de error
            preg_match("/Duplicate entry '(.+?)' for key/", $errorMessage, $matches);
            return $matches[1] ?? 'campo desconocido';
        }
    }
}