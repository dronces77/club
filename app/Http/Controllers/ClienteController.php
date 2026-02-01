<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cliente;
use App\Models\Instituto;
use App\Models\CatalogoRegimen;
use App\Models\CatalogoTramite;
use App\Models\CatalogoModalidad;
use App\Models\ClienteCurp;
use App\Models\ClienteRfc;
use App\Models\ClienteNss;
use App\Models\ClienteContacto;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ClienteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Obtener todos los institutos para el filtro
        $institutos = Instituto::orderBy('nombre')->get();
        
        // Construir consulta base con relaciones necesarias - EXCLUYE ELIMINADOS
        $query = Cliente::with(['instituto', 'instituto2', 'curps', 'rfcs', 'nss', 'contactos'])
            ->whereNull('eliminado_en') // SOLO ESTA LÍNEA NUEVA
            ->orderBy('creado_en', 'desc');
        
        // Aplicar filtro de búsqueda general
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            
            $query->where(function($q) use ($searchTerm) {
                $q->where('nombre', 'like', '%' . $searchTerm . '%')
                  ->orWhere('apellido_paterno', 'like', '%' . $searchTerm . '%')
                  ->orWhere('apellido_materno', 'like', '%' . $searchTerm . '%')
                  ->orWhere('no_cliente', 'like', '%' . $searchTerm . '%')
                  ->orWhere('nss_issste', 'like', '%' . $searchTerm . '%')
                  ->orWhereHas('curps', function($curpQuery) use ($searchTerm) {
                      $curpQuery->where('curp', 'like', '%' . $searchTerm . '%');
                  })
                  ->orWhereHas('rfcs', function($rfcQuery) use ($searchTerm) {
                      $rfcQuery->where('rfc', 'like', '%' . $searchTerm . '%');
                  })
                  ->orWhereHas('nss', function($nssQuery) use ($searchTerm) {
                      $nssQuery->where('nss', 'like', '%' . $searchTerm . '%');
                  })
                  ->orWhereHas('contactos', function($contactoQuery) use ($searchTerm) {
                      $contactoQuery->whereIn('tipo', ['celular1', 'celular2', 'tel_casa'])
                                   ->where('valor', 'like', '%' . $searchTerm . '%');
                  });
            });
        }
        
        // Aplicar filtro de estatus
        if ($request->filled('estatus')) {
            $query->where('estatus', $request->estatus);
        }
        
        // Aplicar filtro de institución (IMSS/ISSSTE)
        if ($request->filled('instituto_id')) {
            $institutoId = $request->instituto_id;
            $query->where(function($q) use ($institutoId) {
                $q->where('instituto_id', $institutoId)
                  ->orWhere('instituto2_id', $institutoId);
            });
        }
        
        // Paginar resultados
        $clientes = $query->paginate(20);
        
        // Calcular estadísticas - TAMBIÉN EXCLUYE ELIMINADOS
        $totalClientes = Cliente::whereNull('eliminado_en')->count();
        $activosCount = Cliente::whereNull('eliminado_en')->where('estatus', 'Activo')->count();
        $pendientesCount = Cliente::whereNull('eliminado_en')->where('estatus', 'pendiente')->count();
        $imssCount = Cliente::whereNull('eliminado_en')
            ->where(function($q) {
                $q->where('instituto_id', 1)
                  ->orWhere('instituto2_id', 1);
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
     */
	public function search(Request $request)
	{
		try {
			$query = Cliente::with(['instituto', 'instituto2', 'curps', 'rfcs', 'nss'])
				->orderBy('creado_en', 'desc');
			
			if ($request->filled('q')) {
				$searchTerm = $request->q;
				
				$query->where(function($q) use ($searchTerm) {
					$q->where('nombre', 'like', '%' . $searchTerm . '%')
					->orWhere('apellido_paterno', 'like', '%' . $searchTerm . '%')
					->orWhere('apellido_materno', 'like', '%' . $searchTerm . '%')
					->orWhere('no_cliente', 'like', '%' . $searchTerm . '%')
					->orWhere('nss_issste', 'like', '%' . $searchTerm . '%')
					->orWhereHas('curps', function($curpQuery) use ($searchTerm) {
						$curpQuery->where('curp', 'like', '%' . $searchTerm . '%');
					})
					->orWhereHas('rfcs', function($rfcQuery) use ($searchTerm) {
						$rfcQuery->where('rfc', 'like', '%' . $searchTerm . '%');
					})
					->orWhereHas('nss', function($nssQuery) use ($searchTerm) {
						$nssQuery->where('nss', 'like', '%' . $searchTerm . '%');
					})
					->orWhereHas('contactos', function($contactoQuery) use ($searchTerm) {
						$contactoQuery->whereIn('tipo', ['celular1', 'celular2', 'tel_casa'])
									->where('valor', 'like', '%' . $searchTerm . '%');
					});
				});
			}
			
			// Aplicar filtros adicionales si existen
			if ($request->filled('estatus')) {
				$query->where('estatus', $request->estatus);
			}
			
			if ($request->filled('instituto_id')) {
				$institutoId = $request->instituto_id;
				$query->where(function($q) use ($institutoId) {
					$q->where('instituto_id', $institutoId)
					->orWhere('instituto2_id', $institutoId);
				});
			}
			
			$clientes = $query->limit(10)->get();
			
			return response()->json([
				'clientes' => $clientes->map(function($cliente) {
					// CORRECCIÓN CRÍTICA: manejo seguro de campos nulos
					$nombre = $cliente->nombre ?? '';
					$apellidoPaterno = $cliente->apellido_paterno ?? '';
					$apellidoMaterno = $cliente->apellido_materno ?? '';
					
					return [
						'id' => $cliente->id,
						'no_cliente' => $cliente->no_cliente ?? 'N/A',
						'nombre_completo' => trim("$nombre $apellidoPaterno $apellidoMaterno"),
						'institucion' => $cliente->instituto ? $cliente->instituto->codigo : null,
						'institucion2' => $cliente->instituto2 ? $cliente->instituto2->codigo : null,
						'estatus' => $cliente->estatus ?? 'N/A',
						'curp_principal' => $cliente->curps->where('es_principal', true)->first()->curp ?? null,
						'rfc_principal' => $cliente->rfcs->where('es_principal', true)->first()->rfc ?? null,
						'nss_principal' => $cliente->nss->where('es_principal', true)->first()->nss ?? null,
						'show_url' => route('clientes.show', $cliente->id),
						'edit_url' => route('clientes.edit', $cliente->id)
					];
				}),
				'total' => $clientes->count(),
				'success' => true
			]);
			
		} catch (\Exception $e) {
			\Log::error('Error en búsqueda autocomplete: ' . $e->getMessage());
			
			return response()->json([
				'clientes' => [],
				'total' => 0,
				'success' => false,
				'message' => 'Error en el servidor'
			], 500);
		}
	}

    public function create()
    {
        $institutos = Instituto::where('activo', true)->get();
        $regimenes = CatalogoRegimen::all();
        $tramites = CatalogoTramite::where('activo', true)->get();
        $modalidades = CatalogoModalidad::where('activo', true)->get();
        
        $clientesReferencia = Cliente::select('id', 'no_cliente', 'nombre', 'apellido_paterno', 'apellido_materno')
            ->whereNull('eliminado_en') // SOLO ESTA LÍNEA NUEVA
            ->orderBy('nombre')
            ->get()
            ->map(function($cliente) {
                $cliente->nombre_completo = "{$cliente->no_cliente} - {$cliente->nombre} {$cliente->apellido_paterno} {$cliente->apellido_materno}";
                return $cliente;
            });
        
        return view('clientes.create', compact('institutos', 'regimenes', 'tramites', 'modalidades', 'clientesReferencia'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate(Cliente::$rulesCreate);
        
        try {
            DB::beginTransaction();
            
            $validated['creado_por'] = auth()->id();
            
            if ($request->fecha_nacimiento) {
                $validated['edad'] = Carbon::parse($request->fecha_nacimiento)->age;
            }
            
            $cliente = Cliente::create($validated);
            
            // Guardar CURPs
            if ($request->filled('curp')) {
                ClienteCurp::create([
                    'cliente_id' => $cliente->id,
                    'curp' => $request->curp,
                    'es_principal' => true
                ]);
            }
            if ($request->filled('curp2')) {
                ClienteCurp::create([
                    'cliente_id' => $cliente->id,
                    'curp' => $request->curp2,
                    'es_principal' => false
                ]);
            }
            if ($request->filled('curp3')) {
                ClienteCurp::create([
                    'cliente_id' => $cliente->id,
                    'curp' => $request->curp3,
                    'es_principal' => false
                ]);
            }
            
            // Guardar RFCs
            if ($request->filled('rfc')) {
                ClienteRfc::create([
                    'cliente_id' => $cliente->id,
                    'rfc' => $request->rfc,
                    'es_principal' => true
                ]);
            }
            if ($request->filled('rfc2')) {
                ClienteRfc::create([
                    'cliente_id' => $cliente->id,
                    'rfc' => $request->rfc2,
                    'es_principal' => false
                ]);
            }
            
            // Guardar NSS
            if ($request->filled('nss')) {
                ClienteNss::create([
                    'cliente_id' => $cliente->id,
                    'nss' => $request->nss,
                    'es_principal' => true
                ]);
            }
            if ($request->filled('nss2')) {
                ClienteNss::create([
                    'cliente_id' => $cliente->id,
                    'nss' => $request->nss2,
                    'es_principal' => false
                ]);
            }
            if ($request->filled('nss3')) {
                ClienteNss::create([
                    'cliente_id' => $cliente->id,
                    'nss' => $request->nss3,
                    'es_principal' => false
                ]);
            }
            if ($request->filled('nss4')) {
                ClienteNss::create([
                    'cliente_id' => $cliente->id,
                    'nss' => $request->nss4,
                    'es_principal' => false
                ]);
            }
            
            // Guardar contactos
            $contactos = [
                'celular1' => $request->celular1,
                'celular2' => $request->celular2,
                'tel_casa' => $request->tel_casa,
                'correo1' => $request->correo1,
                'correo2' => $request->correo2,
                'correo_personal' => $request->correo_personal,
            ];
            
            foreach ($contactos as $tipo => $valor) {
                if (!empty($valor)) {
                    ClienteContacto::create([
                        'cliente_id' => $cliente->id,
                        'tipo' => $tipo,
                        'valor' => $valor,
                        'es_principal' => $tipo === 'celular1' || $tipo === 'correo1'
                    ]);
                }
            }
            
            DB::commit();
            
            // Emitir evento para actualizar dashboard
            echo "<script>
                window.dispatchEvent(new CustomEvent('cliente-creado'));
            </script>";
            
            return redirect()->route('clientes.index')
                ->with('success', "Cliente creado exitosamente, No. Cliente: {$cliente->no_cliente}")
                ->with('event_script', true);
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()
                ->withInput()
                ->with('error', 'Error al crear el cliente: ' . $e->getMessage());
        }
    }

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

    public function edit(Cliente $cliente)
    {
        // Verificar si el cliente está eliminado
        if ($cliente->eliminado_en) {
            return redirect()->route('clientes.index')
                ->with('error', 'No se puede editar un cliente eliminado.');
        }
        
        if ($cliente->tipo_cliente !== 'C') {
            return redirect()->route('clientes.show', $cliente)
                ->with('warning', 'Solo los clientes tipo "Cliente" pueden ser editados completamente.');
        }
        
        $institutos = Instituto::where('activo', true)->get();
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
            ->whereNull('eliminado_en') // SOLO ESTA LÍNEA NUEVA
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

    public function update(Request $request, Cliente $cliente)
    {
        // Verificar si el cliente está eliminado
        if ($cliente->eliminado_en) {
            return back()->with('error', 'No se puede actualizar un cliente eliminado.');
        }
        
        if ($cliente->tipo_cliente !== 'C') {
            return back()->with('warning', 'Solo los clientes tipo "Cliente" pueden ser editados completamente.');
        }
        
        $rules = Cliente::$rulesUpdate;
        
        // Validaciones únicas
        $rules['curp'] = ['required', 'max:18', function ($attribute, $value, $fail) use ($cliente) {
            $exists = ClienteCurp::where('curp', $value)
                ->where('cliente_id', '!=', $cliente->id)
                ->exists();
            if ($exists) {
                $fail('Esta CURP ya está registrada para otro cliente.');
            }
        }];
        
        $rules['rfc'] = ['required', 'max:13', function ($attribute, $value, $fail) use ($cliente) {
            $exists = ClienteRfc::where('rfc', $value)
                ->where('cliente_id', '!=', $cliente->id)
                ->exists();
            if ($exists) {
                $fail('Este RFC ya está registrado para otro cliente.');
            }
        }];
        
        $rules['nss'] = ['required', 'max:11', function ($attribute, $value, $fail) use ($cliente) {
            $exists = ClienteNss::where('nss', $value)
                ->where('cliente_id', '!=', $cliente->id)
                ->exists();
            if ($exists) {
                $fail('Este NSS ya está registrado para otro cliente.');
            }
        }];
        
        // IMPORTANTE: Si se selecciona ISSSTE, hacer obligatorios los campos
        if ($request->filled('instituto2_id') && $request->instituto2_id == 2) {
            $rules['regimen2_id'] = 'required|exists:catalogo_regimenes,id';
            $rules['tramite2_id'] = 'required|exists:catalogo_tramites,id';
            $rules['modalidad_issste'] = 'required|in:NA,CV';
        }
        
        $validated = $request->validate($rules);
        
        try {
            DB::beginTransaction();
            
            $validated['actualizado_por'] = auth()->id();
            
            if ($request->filled('fecha_nacimiento')) {
                $validated['edad'] = Carbon::parse($request->fecha_nacimiento)->age;
            }
            
            // IMPORTANTE: Limpiar campos ISSSTE si no se seleccionó ISSSTE (id=2)
            // También limpiar si se seleccionó N/A (valor vacío)
            if (!$request->filled('instituto2_id') || $request->instituto2_id != 2) {
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
            
            // ========== MANEJAR CURPs (CORREGIDO) ==========
            // 1. Buscar el CURP principal existente
            $curpPrincipalExistente = $cliente->curps()->where('es_principal', true)->first();
            
            // 2. Manejar CURP principal
            if ($request->filled('curp')) {
                if ($curpPrincipalExistente) {
                    // ACTUALIZAR el registro existente (no crear uno nuevo)
                    $curpPrincipalExistente->update(['curp' => $request->curp]);
                } else {
                    // CREAR solo si no existe
                    ClienteCurp::create([
                        'cliente_id' => $cliente->id,
                        'curp' => $request->curp,
                        'es_principal' => true
                    ]);
                }
            } elseif ($curpPrincipalExistente) {
                // Si el campo viene vacío pero existía, eliminarlo
                $curpPrincipalExistente->delete();
            }
            
            // 3. Manejar CURPs secundarias (curp2, curp3)
            // Para las secundarias, podemos borrar y crear porque no tienen restricción única entre sí
            $cliente->curps()->where('es_principal', false)->delete();
            
            if ($request->filled('curp2')) {
                ClienteCurp::create([
                    'cliente_id' => $cliente->id,
                    'curp' => $request->curp2,
                    'es_principal' => false
                ]);
            }
            
            if ($request->filled('curp3')) {
                ClienteCurp::create([
                    'cliente_id' => $cliente->id,
                    'curp' => $request->curp3,
                    'es_principal' => false
                ]);
            }
            
            // ========== MANEJAR RFCs (CORREGIDO) ==========
            // 1. Buscar el RFC principal existente
            $rfcPrincipalExistente = $cliente->rfcs()->where('es_principal', true)->first();
            
            // 2. Manejar RFC principal
            if ($request->filled('rfc')) {
                if ($rfcPrincipalExistente) {
                    // ACTUALIZAR el registro existente
                    $rfcPrincipalExistente->update(['rfc' => $request->rfc]);
                } else {
                    // CREAR solo si no existe
                    ClienteRfc::create([
                        'cliente_id' => $cliente->id,
                        'rfc' => $request->rfc,
                        'es_principal' => true
                    ]);
                }
            } elseif ($rfcPrincipalExistente) {
                $rfcPrincipalExistente->delete();
            }
            
            // 3. Manejar RFC secundario (rfc2)
            $cliente->rfcs()->where('es_principal', false)->delete();
            
            if ($request->filled('rfc2')) {
                ClienteRfc::create([
                    'cliente_id' => $cliente->id,
                    'rfc' => $request->rfc2,
                    'es_principal' => false
                ]);
            }
            
            // ========== MANEJAR NSS (CORREGIDO) ==========
            // 1. Buscar el NSS principal existente
            $nssPrincipalExistente = $cliente->nss()->where('es_principal', true)->first();
            
            // 2. Manejar NSS principal
            if ($request->filled('nss')) {
                if ($nssPrincipalExistente) {
                    // ACTUALIZAR el registro existente
                    $nssPrincipalExistente->update(['nss' => $request->nss]);
                } else {
                    // CREAR solo si no existe
                    ClienteNss::create([
                        'cliente_id' => $cliente->id,
                        'nss' => $request->nss,
                        'es_principal' => true
                    ]);
                }
            } elseif ($nssPrincipalExistente) {
                $nssPrincipalExistente->delete();
            }
            
            // 3. Manejar NSS secundarios (nss2, nss3, nss4)
            $cliente->nss()->where('es_principal', false)->delete();
            
            if ($request->filled('nss2')) {
                ClienteNss::create([
                    'cliente_id' => $cliente->id,
                    'nss' => $request->nss2,
                    'es_principal' => false
                ]);
            }
            
            if ($request->filled('nss3')) {
                ClienteNss::create([
                    'cliente_id' => $cliente->id,
                    'nss' => $request->nss3,
                    'es_principal' => false
                ]);
            }
            
            if ($request->filled('nss4')) {
                ClienteNss::create([
                    'cliente_id' => $cliente->id,
                    'nss' => $request->nss4,
                    'es_principal' => false
                ]);
            }
            
            // ========== MANEJAR CONTACTOS (MANTENER IGUAL) ==========
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
            
            DB::commit();
            
            // Emitir evento para actualizar dashboard
            echo "<script>
                window.dispatchEvent(new CustomEvent('cliente-actualizado'));
            </script>";
            
            return redirect()->route('clientes.show', $cliente)
                ->with('success', 'Cliente actualizado exitosamente.')
                ->with('event_script', true);
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()
                ->withInput()
                ->with('error', 'Error al actualizar el cliente: ' . $e->getMessage());
        }
    }

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

    public function exportar()
    {
        $clientes = Cliente::with(['instituto', 'regimen', 'tramite', 'modalidad'])
            ->whereNull('eliminado_en') // SOLO ESTA LÍNEA NUEVA
            ->orderBy('creado_en', 'desc')
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
                    $cliente->creado_en ? $cliente->creado_en->format('Y-m-d H:i') : '',
                    $cliente->actualizado_en ? $cliente->actualizado_en->format('Y-m-d H:i') : '',
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }

    public function cambiarEstatus(Request $request, Cliente $cliente)
    {
        // Verificar si el cliente está eliminado
        if ($cliente->eliminado_en) {
            return redirect()->route('clientes.index')
                ->with('error', 'No se puede cambiar el estatus de un cliente eliminado.');
        }
        
        return view('clientes.cambiar-estatus', compact('cliente'));
    }
    
    public function cambiarEstatusUpdate(Request $request, Cliente $cliente)
    {
        // Verificar si el cliente está eliminado
        if ($cliente->eliminado_en) {
            return redirect()->route('clientes.index')
                ->with('error', 'No se puede cambiar el estatus de un cliente eliminado.');
        }
        
        $request->validate([
            'estatus' => 'required|in:Activo,Suspendido,Terminado,Baja',
        ]);
        
        $cliente->estatus = $request->estatus;
        $cliente->actualizado_por = auth()->id();
        $cliente->save();
        
        // Emitir evento para actualizar dashboard
        echo "<script>
            window.dispatchEvent(new CustomEvent('cliente-actualizado'));
        </script>";
        
        return redirect()->route('clientes.show', $cliente)
            ->with('success', 'Estatus actualizado a: ' . $request->estatus)
            ->with('event_script', true);
    }

    public function estadisticas()
    {
        $estadisticas = [
            'total' => Cliente::whereNull('eliminado_en')->count(),
            'activos' => Cliente::whereNull('eliminado_en')->where('estatus', 'Activo')->count(),
            'pendientes' => Cliente::whereNull('eliminado_en')->where('estatus', 'pendiente')->count(),
            'suspendidos' => Cliente::whereNull('eliminado_en')->where('estatus', 'Suspendido')->count(),
            'por_instituto' => Cliente::select('instituto_id', DB::raw('count(*) as total'))
                ->whereNull('eliminado_en')
                ->groupBy('instituto_id')
                ->with('instituto')
                ->get(),
            'por_tipo' => Cliente::select('tipo_cliente', DB::raw('count(*) as total'))
                ->whereNull('eliminado_en')
                ->groupBy('tipo_cliente')
                ->get(),
            'creados_hoy' => Cliente::whereDate('creado_en', today())->whereNull('eliminado_en')->count(),
            'actualizados_hoy' => Cliente::whereDate('actualizado_en', today())->whereNull('eliminado_en')->count(),
        ];
        
        return response()->json($estadisticas);
    }

    public function getRegimenesPorInstituto($institutoId)
    {
        $regimenes = CatalogoRegimen::where('instituto_id', $institutoId)->get();
        return response()->json($regimenes);
    }
}
