<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cliente;
use App\Models\CatalogoInstituto;
use App\Models\CatalogoRegimen;
use App\Models\CatalogoTramite;
use App\Models\CatalogoModalidad;
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
        // Obtener todos los institutos para el filtro
        $institutos = CatalogoInstituto::orderBy('nombre')->get();
        
        // ✅ INICIAL: Solo clientes (tipo_cliente = 'C')
        $query = Cliente::where('tipo_cliente', 'C')
            ->with(['instituto', 'instituto2', 'curps', 'rfcs', 'nss', 'contactos'])
            ->orderBy('created_at', 'desc');
        
        // 🔍 BÚSQUEDA EN CAJA DE TEXTO (FILTRO PRINCIPAL)
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            
            $query->where(function($q) use ($searchTerm) {
                // ✅ Buscar en TODOS los campos solicitados
                $q->where('no_cliente', 'like', '%' . $searchTerm . '%')        // No. Cliente
                  ->orWhere('nombre', 'like', '%' . $searchTerm . '%')          // Nombre
                  ->orWhere('apellido_paterno', 'like', '%' . $searchTerm . '%') // Apellido Paterno
                  ->orWhere('apellido_materno', 'like', '%' . $searchTerm . '%') // Apellido Materno
                  ->orWhere('nss_issste', 'like', '%' . $searchTerm . '%')      // NSS ISSSTE
                  
                  // ✅ CURP - tabla relacionada cliente_curps
                  ->orWhereHas('curps', function($curpQuery) use ($searchTerm) {
                      $curpQuery->where('curp', 'like', '%' . $searchTerm . '%')
                               ->where('es_principal', true);
                  })
                  
                  // ✅ NSS - tabla relacionada cliente_nss
                  ->orWhereHas('nss', function($nssQuery) use ($searchTerm) {
                      $nssQuery->where('nss', 'like', '%' . $searchTerm . '%')
                              ->where('es_principal', true);
                  });
            });
        }
        
        // 📊 FILTRO DE ESTATUS (BÚSQUEDA ANIDADA)
        if ($request->filled('estatus') && $request->estatus !== 'todos') {
            $query->where('estatus', $request->estatus);
        }
        
        // 🏢 FILTRO DE INSTITUCIÓN (BÚSQUEDA ANIDADA)
        if ($request->filled('instituto_id') && $request->instituto_id !== 'todos') {
            $institutoId = $request->instituto_id;
            $query->where(function($q) use ($institutoId) {
                $q->where('instituto_id', $institutoId)
                  ->orWhere('instituto2_id', $institutoId);
            });
        }
        
        // Paginar resultados (20 por página)
        $clientes = $query->paginate(20);
        
        // Mantener los filtros en la paginación
        $clientes->appends([
            'search' => $request->search,
            'estatus' => $request->estatus,
            'instituto_id' => $request->instituto_id
        ]);
        
        // Calcular estadísticas SOLO de CLIENTES
        $totalClientes = Cliente::where('tipo_cliente', 'C')->count();
        $activosCount = Cliente::where('tipo_cliente', 'C')->where('estatus', 'Activo')->count();
        $pendientesCount = Cliente::where('tipo_cliente', 'C')->where('estatus', 'pendiente')->count();
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
                $query->where('estatus', $request->estatus);
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
        $regimenes = CatalogoRegimen::all();
        $tramites = CatalogoTramite::where('activo', true)->get();
        $modalidades = CatalogoModalidad::where('activo', true)->get();
        
        // Clientes para referencia
        $clientesReferencia = Cliente::select('id', 'no_cliente', 'nombre', 'apellido_paterno', 'apellido_materno')
            ->where('tipo_cliente', 'C') // Solo clientes activos
            ->orderBy('nombre')
            ->get()
            ->map(function($cliente) {
                $cliente->nombre_completo = "{$cliente->no_cliente} - {$cliente->nombre} {$cliente->apellido_paterno} {$cliente->apellido_materno}";
                return $cliente;
            });
        
        return view('clientes.create', compact('institutos', 'regimenes', 'tramites', 'modalidades', 'clientesReferencia'));
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
                $query->orderBy('tipo');
            }
        ]);
        
        return view('clientes.show', compact('cliente'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Cliente $cliente)
    {
        // Verificar si el cliente está eliminado
        if ($cliente->deleted_at) {
            return redirect()->route('clientes.index')
                ->with('error', 'No se puede editar un cliente eliminado.');
        }
        
        // Solo clientes pueden ser editados completamente
        if ($cliente->tipo_cliente !== 'C') {
            return redirect()->route('clientes.show', $cliente)
                ->with('warning', 'Solo los clientes tipo "Cliente" pueden ser editados completamente.');
        }
        
        $institutos = CatalogoInstituto::where('activo', true)->get();
        $regimenes = CatalogoRegimen::all();
        $tramites = CatalogoTramite::where('activo', true)->get();
        
        $modalidadesImss = CatalogoModalidad::where('activo', true)
            ->whereIn('codigo', ['NA', 'M10', 'M40'])
            ->get();
            
        $modalidadesIssste = CatalogoModalidad::where('activo', true)
            ->whereIn('codigo', ['NA', 'CV'])
            ->get();
        
        $clientesReferencia = Cliente::select('id', 'no_cliente', 'nombre', 'apellido_paterno', 'apellido_materno')
            ->where('id', '!=', $cliente->id)
            ->orderBy('nombre')
            ->get()
            ->map(function($clienteRef) {
                $clienteRef->nombre_completo = "{$clienteRef->no_cliente} - {$clienteRef->nombre} {$clienteRef->apellido_paterno} {$clienteRef->apellido_materno}";
                return $clienteRef;
            });
        
        $cliente->load(['curps', 'rfcs', 'nss', 'contactos']);
        
        $curps = $cliente->curps->pluck('curp')->toArray();
        $rfcs = $cliente->rfcs->pluck('rfc')->toArray();
        $nss = $cliente->nss->pluck('nss')->toArray();
        
        $contactos = [];
        foreach ($cliente->contactos as $contacto) {
            $contactos[$contacto->tipo] = $contacto->valor;
        }
        
        return view('clientes.edit', compact(
            'cliente', 
            'institutos', 
            'regimenes', 
            'tramites', 
            'modalidadesImss',
            'modalidadesIssste',
            'clientesReferencia',
            'curps',
            'rfcs',
            'nss',
            'contactos'
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Cliente $cliente)
    {
        // Verificar si el cliente está eliminado
        if ($cliente->deleted_at) {
            return back()->with('error', 'No se puede actualizar un cliente eliminado.');
        }
        
        // Solo clientes pueden ser editados completamente
        if ($cliente->tipo_cliente !== 'C') {
            return back()->with('warning', 'Solo los clientes tipo "Cliente" pueden ser editados completamente.');
        }
        
        // ========== VALIDACIONES ==========
        $rules = Cliente::$rulesUpdate;
        
        // ✅ Validaciones únicas para todos los campos
        $this->agregarValidacionesUnicas($rules, $request, $cliente);
        
        $validated = $request->validate($rules);
        
        try {
            DB::beginTransaction();
            
            $validated['actualizado_por'] = auth()->id();
            
            if ($request->filled('fecha_nacimiento')) {
                $validated['edad'] = Carbon::parse($request->fecha_nacimiento)->age;
            }
            
            // Limpiar campos ISSSTE si no se seleccionó ISSSTE
            if (!$request->filled('instituto2_id') || $request->instituto2_id != 14) {
                $validated['instituto2_id'] = null;
                $validated['regimen2_id'] = null;
                $validated['tramite2_id'] = null;
                $validated['modalidad_issste'] = null;
                $validated['nss_issste'] = null;
                $validated['fecha_alta_issste'] = null;
                $validated['fecha_baja_issste'] = null;
                $validated['anios_servicio_issste'] = null;
            }
            
            $cliente->update($validated);
            
            // ========== MANEJAR CURPs ==========
            $this->manejarCurpsCorregido($cliente, $request);
            
            // ========== MANEJAR RFCs ==========
            $this->manejarRfcsCorregido($cliente, $request);
            
            // ========== MANEJAR NSS ==========
            $this->manejarNssCorregido($cliente, $request);
            
            // ========== MANEJAR CONTACTOS ==========
            $this->manejarContactosCorregido($cliente, $request);
            
            DB::commit();
            
            return redirect()->route('clientes.show', $cliente)
                ->with('success', 'Cliente actualizado exitosamente.');
                
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            throw $e;
        } catch (\Exception $e) {
            DB::rollBack();
            
            // Capturar error de duplicado
            if (strpos($e->getMessage(), 'Duplicate entry') !== false) {
                preg_match("/Duplicate entry '(.+?)' for key/", $e->getMessage(), $matches);
                $valorDuplicado = $matches[1] ?? 'desconocido';
                $campo = $this->detectarCampoDuplicado($e->getMessage());
                
                return back()
                    ->withInput()
                    ->with('error', "Error: El valor '{$valorDuplicado}' en el campo {$campo} ya está registrado para otro cliente. Por favor, verifica los datos.");
            }
            
            return back()
                ->withInput()
                ->with('error', 'Error al actualizar el cliente: ' . $e->getMessage());
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
        // ✅ TODAS las estadísticas SOLO para CLIENTES
        $estadisticas = [
            'total' => Cliente::where('tipo_cliente', 'C')->count(),
            'activos' => Cliente::where('tipo_cliente', 'C')->where('estatus', 'Activo')->count(),
            'pendientes' => Cliente::where('tipo_cliente', 'C')->where('estatus', 'pendiente')->count(),
            'suspendidos' => Cliente::where('tipo_cliente', 'C')->where('estatus', 'Suspendido')->count(),
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
     * Agregar validaciones únicas para todos los campos
     */
    private function agregarValidacionesUnicas(&$rules, Request $request, Cliente $cliente)
    {
        // Campos principales (obligatorios)
        $camposPrincipales = [
            'curp' => ['ClienteCurp', 'curp'],
            'rfc' => ['ClienteRfc', 'rfc'],
            'nss' => ['ClienteNss', 'nss']
        ];
        
        // Campos secundarios (opcionales)
        $camposSecundarios = [
            'curp2' => ['ClienteCurp', 'curp'],
            'curp3' => ['ClienteCurp', 'curp'],
            'rfc2' => ['ClienteRfc', 'rfc'],
            'nss2' => ['ClienteNss', 'nss'],
            'nss3' => ['ClienteNss', 'nss'],
            'nss4' => ['ClienteNss', 'nss']
        ];
        
        // Validar campos principales
        foreach ($camposPrincipales as $campo => [$modelo, $columna]) {
            if ($request->filled($campo)) {
                $rules[$campo] = [
                    'required',
                    'max:' . ($campo === 'curp' ? 18 : ($campo === 'rfc' ? 13 : 11)),
                    function ($attribute, $value, $fail) use ($modelo, $columna, $cliente, $campo) {
                        $clase = "App\\Models\\{$modelo}";
                        $existe = $clase::where($columna, $value)
                            ->where('cliente_id', '!=', $cliente->id)
                            ->exists();
                        
                        if ($existe) {
                            $fail("El {$campo} '{$value}' ya está registrado para otro cliente.");
                        }
                    }
                ];
            }
        }
        
        // Validar campos secundarios (si se proporcionan)
        foreach ($camposSecundarios as $campo => [$modelo, $columna]) {
            if ($request->filled($campo)) {
                $rules[$campo] = [
                    'nullable',
                    'max:' . (strpos($campo, 'curp') !== false ? 18 : (strpos($campo, 'rfc') !== false ? 13 : 11)),
                    function ($attribute, $value, $fail) use ($modelo, $columna, $cliente, $campo) {
                        $clase = "App\\Models\\{$modelo}";
                        $existe = $clase::where($columna, $value)
                            ->where('cliente_id', '!=', $cliente->id)
                            ->exists();
                        
                        if ($existe) {
                            $fail("El {$campo} '{$value}' ya está registrado para otro cliente.");
                        }
                    }
                ];
            }
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
    }

    /**
     * Manejar NSS del cliente (CORREGIDO - eliminación REAL)
     */
    private function manejarNssCorregido(Cliente $cliente, Request $request)
    {
        // Obtener todos los NSS actuales del cliente
        $nssActuales = $cliente->nss()->get();
        
        // Arrays para control
        $nssMantener = [];
        $nssEliminar = $nssActuales->pluck('id')->toArray();
        
        // 1. NSS principal (obligatorio)
        if ($request->filled('nss')) {
            $nssPrincipal = $nssActuales->where('nss', $request->nss)->first();
            
            if ($nssPrincipal) {
                // Ya existe, actualizar y mantener
                $nssPrincipal->update(['es_principal' => true]);
                $nssMantener[] = $nssPrincipal->id;
                $nssEliminar = array_diff($nssEliminar, [$nssPrincipal->id]);
            } else {
                // Crear nuevo NSS principal
                $nuevoNss = ClienteNss::create([
                    'cliente_id' => $cliente->id,
                    'nss' => $request->nss,
                    'es_principal' => true
                ]);
                $nssMantener[] = $nuevoNss->id;
            }
        }
        
        // 2. NSS2 (opcional)
        if ($request->filled('nss2')) {
            $nss2 = $nssActuales->where('nss', $request->nss2)->first();
            
            if ($nss2) {
                // Ya existe, asegurar que NO sea principal y mantener
                $nss2->update(['es_principal' => false]);
                $nssMantener[] = $nss2->id;
                $nssEliminar = array_diff($nssEliminar, [$nss2->id]);
            } else {
                // Crear nuevo NSS secundario
                $nuevoNss2 = ClienteNss::create([
                    'cliente_id' => $cliente->id,
                    'nss' => $request->nss2,
                    'es_principal' => false
                ]);
                $nssMantener[] = $nuevoNss2->id;
            }
        }
        
        // 3. NSS3 (opcional)
        if ($request->filled('nss3')) {
            $nss3 = $nssActuales->where('nss', $request->nss3)->first();
            
            if ($nss3) {
                // Ya existe, asegurar que NO sea principal y mantener
                $nss3->update(['es_principal' => false]);
                $nssMantener[] = $nss3->id;
                $nssEliminar = array_diff($nssEliminar, [$nss3->id]);
            } else {
                // Crear nuevo NSS secundario
                $nuevoNss3 = ClienteNss::create([
                    'cliente_id' => $cliente->id,
                    'nss' => $request->nss3,
                    'es_principal' => false
                ]);
                $nssMantener[] = $nuevoNss3->id;
            }
        }
        
        // 4. NSS4 (opcional)
        if ($request->filled('nss4')) {
            $nss4 = $nssActuales->where('nss', $request->nss4)->first();
            
            if ($nss4) {
                // Ya existe, asegurar que NO sea principal y mantener
                $nss4->update(['es_principal' => false]);
                $nssMantener[] = $nss4->id;
                $nssEliminar = array_diff($nssEliminar, [$nss4->id]);
            } else {
                // Crear nuevo NSS secundario
                $nuevoNss4 = ClienteNss::create([
                    'cliente_id' => $cliente->id,
                    'nss' => $request->nss4,
                    'es_principal' => false
                ]);
                $nssMantener[] = $nuevoNss4->id;
            }
        }
        
        // 5. Eliminar NSS que ya no se necesitan (ELIMINACIÓN REAL)
        if (!empty($nssEliminar)) {
            ClienteNss::whereIn('id', $nssEliminar)->delete();
        }
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
        } else {
            return 'campo';
        }
    }
}
