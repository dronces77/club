<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cliente;
use App\Models\CatalogoInstituto;
use App\Models\CatalogoRegimen;
use App\Models\CatalogoTramite;
use App\Models\CatalogoModalidad;
use Illuminate\Support\Facades\DB;

class ClienteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = Cliente::with(['instituto', 'regimen', 'tramite', 'modalidad'])
            ->orderBy('creado_en', 'desc');
        
        // Filtros
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
        
        // Estadísticas
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

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $institutos = CatalogoInstituto::where('activo', true)->get();
        $regimenes = CatalogoRegimen::all();
        $tramites = CatalogoTramite::where('activo', true)->get();
        $modalidades = CatalogoModalidad::where('activo', true)->get();
        
        return view('clientes.create', compact('institutos', 'regimenes', 'tramites', 'modalidades'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'tipo_cliente' => 'required|in:C,P,S,B,I',
            'nombre' => 'required|string|max:100',
            'apellido_paterno' => 'required|string|max:100',
            'apellido_materno' => 'required|string|max:100',
            'fecha_nacimiento' => 'nullable|date',
            'instituto_id' => 'nullable|exists:catalogo_institutos,id',
            'regimen_id' => 'nullable|exists:catalogo_regimenes,id',
            'semanas_imss' => 'nullable|integer|min:0',
            'semanas_issste' => 'nullable|integer|min:0',
            'tramite_id' => 'nullable|exists:catalogo_tramites,id',
            'modalidad_id' => 'nullable|exists:catalogo_modalidades,id',
            'pension_default' => 'nullable|numeric|min:0',
            'pension_normal' => 'nullable|numeric|min:0',
            'comision' => 'nullable|numeric|min:0',
            'honorarios' => 'nullable|numeric|min:0',
            'fecha_alta' => 'nullable|date',
            'fecha_baja' => 'nullable|date|after_or_equal:fecha_alta',
            'estatus' => 'required|in:Activo,Suspendido,Terminado,Baja',
            'cliente_referidor_id' => 'nullable|exists:clientes,id',
            'observaciones' => 'nullable|string|max:500',
        ]);

        // Calcular edad si se proporcionó fecha de nacimiento
        if ($request->filled('fecha_nacimiento')) {
            $validated['edad'] = now()->diffInYears($request->fecha_nacimiento);
        }

        // Asignar usuario que crea
        $validated['creado_por'] = auth()->id();

        // Crear cliente (el trigger generará automáticamente no_cliente)
        try {
            DB::beginTransaction();
            
            $cliente = Cliente::create($validated);
            
            DB::commit();
            
            return redirect()->route('clientes.show', $cliente)
                ->with('success', 'Cliente creado exitosamente. No. Cliente: ' . ($cliente->no_cliente ?? 'N/A'));
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()
                ->withInput()
                ->with('error', 'Error al crear el cliente: ' . $e->getMessage());
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
            'referidor',
            'referidos',
            'creadoPor',
            'actualizadoPor'
        ]);
        
        // Cargar identificaciones si existen las relaciones
        try {
            $cliente->load(['curps', 'nsss', 'rfcs', 'contactos']);
        } catch (\Exception $e) {
            // Si las relaciones no existen aún, continuar sin ellas
        }
        
        return view('clientes.show', compact('cliente'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Cliente $cliente)
    {
        $institutos = CatalogoInstituto::where('activo', true)->get();
        $regimenes = CatalogoRegimen::all();
        $tramites = CatalogoTramite::where('activo', true)->get();
        $modalidades = CatalogoModalidad::where('activo', true)->get();
        
        // Obtener clientes para referidor (excluyendo el actual)
        $clientesReferidores = Cliente::where('id', '!=', $cliente->id)
            ->orderBy('nombre')
            ->get();
        
        return view('clientes.edit', compact(
            'cliente', 
            'institutos', 
            'regimenes', 
            'tramites', 
            'modalidades',
            'clientesReferidores'
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Cliente $cliente)
    {
        $validated = $request->validate([
            'tipo_cliente' => 'required|in:C,P,S,B,I',
            'nombre' => 'required|string|max:100',
            'apellido_paterno' => 'required|string|max:100',
            'apellido_materno' => 'required|string|max:100',
            'fecha_nacimiento' => 'nullable|date',
            'instituto_id' => 'nullable|exists:catalogo_institutos,id',
            'regimen_id' => 'nullable|exists:catalogo_regimenes,id',
            'semanas_imss' => 'nullable|integer|min:0',
            'semanas_issste' => 'nullable|integer|min:0',
            'tramite_id' => 'nullable|exists:catalogo_tramites,id',
            'modalidad_id' => 'nullable|exists:catalogo_modalidades,id',
            'pension_default' => 'nullable|numeric|min:0',
            'pension_normal' => 'nullable|numeric|min:0',
            'comision' => 'nullable|numeric|min:0',
            'honorarios' => 'nullable|numeric|min:0',
            'fecha_alta' => 'nullable|date',
            'fecha_baja' => 'nullable|date|after_or_equal:fecha_alta',
            'estatus' => 'required|in:Activo,Suspendido,Terminado,Baja',
            'cliente_referidor_id' => 'nullable|exists:clientes,id',
            'observaciones' => 'nullable|string|max:500',
        ]);

        // Calcular edad si se actualizó fecha de nacimiento
        if ($request->filled('fecha_nacimiento') && 
            (!$cliente->fecha_nacimiento || $cliente->fecha_nacimiento->format('Y-m-d') != $request->fecha_nacimiento)) {
            $validated['edad'] = now()->diffInYears($request->fecha_nacimiento);
        }

        // Asignar usuario que actualiza
        $validated['actualizado_por'] = auth()->id();

        try {
            DB::beginTransaction();
            
            $cliente->update($validated);
            
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
     * Exportar clientes a CSV
     */
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
            
            // Encabezados
            fputcsv($file, [
                'No. Cliente',
                'Tipo Cliente',
                'Nombre',
                'Apellido Paterno',
                'Apellido Materno',
                'Fecha Nacimiento',
                'Edad',
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
            
            // Datos
            foreach ($clientes as $cliente) {
                fputcsv($file, [
                    $cliente->no_cliente ?? '',
                    $cliente->tipo_cliente ?? '',
                    $cliente->nombre ?? '',
                    $cliente->apellido_paterno ?? '',
                    $cliente->apellido_materno ?? '',
                    $cliente->fecha_nacimiento ? $cliente->fecha_nacimiento->format('Y-m-d') : '',
                    $cliente->edad ?? '',
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

    /**
     * Cambiar estatus rápido
     */
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

    /**
     * Obtener estadísticas
     */
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
}
