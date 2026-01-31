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

class ClienteController extends Controller
{
    public function index()
    {
        $query = Cliente::with(['instituto', 'regimen', 'tramite', 'modalidad'])
            ->orderBy('creado_en', 'desc');
        
        if (request('search')) {
            $query->where(function($q) {
                $q->where('nombre', 'like', '%' . request('search') . '%')
                  ->orWhere('apellido_paterno', 'like', '%' . request('search') . '%')
                  ->orWhere('apellido_materno', 'like', '%' . request('search') . '%')
                  ->orWhere('no_cliente', 'like', '%' . request('search') . '%');
            });
        }
        
        if (request('estatus')) {
            $query->where('estatus', request('estatus'));
        }
        
        if (request('instituto_id')) {
            $query->where('instituto_id', request('instituto_id'));
        }
        
        if (request('tipo_cliente')) {
            $query->where('tipo_cliente', request('tipo_cliente'));
        }
        
        $clientes = $query->paginate(20)->withQueryString();
        
        $institutos = CatalogoInstituto::where('activo', true)->get();
        $totalClientes = Cliente::count();
        $activosCount = Cliente::where('estatus', 'Activo')->count();
        $pendientesCount = Cliente::where('estatus', 'pendiente')->count();
        $imssCount = Cliente::whereHas('instituto', function($q) {
            $q->where('codigo', 'IMSS');
        })->count();
        
        return view('clientes.index', compact(
            'clientes', 
            'institutos',
            'totalClientes',
            'activosCount',
            'pendientesCount',
            'imssCount'
        ));
    }

    public function create()
    {
        $institutos = CatalogoInstituto::where('activo', true)->get();
        $regimenes = CatalogoRegimen::all();
        $tramites = CatalogoTramite::where('activo', true)->get();
        $modalidades = CatalogoModalidad::where('activo', true)->get();
        
        $clientesReferencia = Cliente::select('id', 'no_cliente', 'nombre', 'apellido_paterno', 'apellido_materno')
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
            
            DB::commit();
            
            return redirect()->route('clientes.index')
                ->with('success', "Cliente creado exitosamente, No. Cliente: {$cliente->no_cliente}");
                
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

public function update(Request $request, Cliente $cliente)
{
    if ($cliente->tipo_cliente !== 'C') {
        return back()->with('warning', 'Solo los clientes tipo "Cliente" pueden ser editados completamente.');
    }
    
    $rules = Cliente::$rulesUpdate;
    
    // ... (resto de validaciones únicas)
    
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
        
        $this->manejarCurps($cliente, $request);
        $this->manejarRfcs($cliente, $request);
        $this->manejarNss($cliente, $request);
        $this->manejarContactos($cliente, $request);
        
        DB::commit();
        
        return redirect()->route('clientes.show', $cliente)
            ->with('success', 'Cliente actualizado exitosamente.');
            
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
        $request->validate([
            'estatus' => 'required|in:Activo,Suspendido,Terminado,Baja',
        ]);
        
        $cliente->estatus = $request->estatus;
        $cliente->actualizado_por = auth()->id();
        $cliente->save();
        
        return back()->with('success', 'Estatus actualizado a: ' . $request->estatus);
    }

    public function estadisticas()
    {
        $estadisticas = [
            'total' => Cliente::count(),
            'activos' => Cliente::where('estatus', 'Activo')->count(),
            'pendientes' => Cliente::where('estatus', 'pendiente')->count(),
            'suspendidos' => Cliente::where('estatus', 'Suspendido')->count(),
            'por_instituto' => Cliente::select('instituto_id', DB::raw('count(*) as total'))
                ->groupBy('instituto_id')
                ->with('instituto')
                ->get(),
            'por_tipo' => Cliente::select('tipo_cliente', DB::raw('count(*) as total'))
                ->groupBy('tipo_cliente')
                ->get(),
            'creados_hoy' => Cliente::whereDate('creado_en', today())->count(),
            'actualizados_hoy' => Cliente::whereDate('actualizado_en', today())->count(),
        ];
        
        return response()->json($estadisticas);
    }

    // MÉTODOS AUXILIARES CORREGIDOS
    private function manejarCurps($cliente, $request)
    {
        $cliente->curps()->delete();
        
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
    }
    
    private function manejarRfcs($cliente, $request)
    {
        $cliente->rfcs()->delete();
        
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
    }
    
    private function manejarNss($cliente, $request)
    {
        $cliente->nss()->delete();
        
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
    }
    
    private function manejarContactos($cliente, $request)
    {
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
    
    public function getRegimenesPorInstituto($institutoId)
    {
        $regimenes = CatalogoRegimen::where('instituto_id', $institutoId)->get();
        return response()->json($regimenes);
    }
}
