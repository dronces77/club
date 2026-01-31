<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\CatalogoInstituto;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Obtener estadísticas
        $total_clientes = Cliente::count();
        
        $clientes_activos = Cliente::where('estatus', 'Activo')->count();
        $clientes_suspendidos = Cliente::where('estatus', 'Suspendido')->count();
        $clientes_baja = Cliente::where('estatus', 'Baja')->count();
        
        // Clientes por tipo
        $clientes_tipo = Cliente::select('tipo_cliente', DB::raw('count(*) as total'))
            ->groupBy('tipo_cliente')
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->tipo_cliente => $item->total];
            });
        
        // Clientes por institución
        $clientes_instituto = Cliente::select('instituto_id', DB::raw('count(*) as total'))
            ->whereNotNull('instituto_id')
            ->groupBy('instituto_id')
            ->with('instituto')
            ->get();
        
        $clientes_hoy = Cliente::whereDate('creado_en', today())->count();
        $actualizados_hoy = Cliente::whereDate('actualizado_en', today())->count();
        
        $ultimos_clientes = Cliente::with('instituto')
            ->orderBy('creado_en', 'desc')
            ->take(5)
            ->get();
        
        $institutos = CatalogoInstituto::where('activo', true)->get();
        
        $tipo_labels = [
            'C' => 'Clientes',
            'P' => 'Prospectos',
            'S' => 'Suspendidos',
            'B' => 'Bajas',
            'I' => 'Imposibles'
        ];
        
        $tipo_data = [];
        $tipo_colors = [
            'C' => '#4e73df',
            'P' => '#1cc88a',
            'S' => '#f6c23e',
            'B' => '#e74a3b',
            'I' => '#6c757d'
        ];
        
        foreach ($tipo_labels as $key => $label) {
            $tipo_data[$label] = [
                'total' => $clientes_tipo[$key] ?? 0,
                'color' => $tipo_colors[$key] ?? '#6c757d'
            ];
        }
        
        return view('dashboard.index', compact(
            'total_clientes',
            'clientes_activos',
            'clientes_suspendidos',
            'clientes_baja',
            'clientes_instituto',
            'clientes_hoy',
            'actualizados_hoy',
            'ultimos_clientes',
            'institutos',
            'tipo_data'
        ));
    }
}
