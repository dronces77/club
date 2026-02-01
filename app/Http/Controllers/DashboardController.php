<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\CatalogoInstituto;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Total de clientes
        $total_clientes = Cliente::count();
        
        // Clientes activos (tipo 'C' y estatus 'Activo')
        $clientes_activos = Cliente::where('tipo_cliente', 'C')
            ->where('estatus', 'Activo')
            ->count();
        
        // Clientes pendientes (tipo 'P' - Prospectos)
        $clientes_pendientes = Cliente::where('tipo_cliente', 'P')->count();
        
        // Clientes con pensión (todos IMSS/ISSSTE)
        $clientes_con_pension = Cliente::whereHas('instituto', function($query) {
            $query->whereIn('codigo', ['IMSS', 'ISSSTE']);
        })->count();
        
        // Clientes por institución (IMSS e ISSSTE)
        $clientes_imss = Cliente::whereHas('instituto', function($query) {
            $query->where('codigo', 'IMSS');
        })->count();
        
        $clientes_issste = Cliente::whereHas('instituto', function($query) {
            $query->where('codigo', 'ISSSTE');
        })->count();
        
        // Clientes agregados en el mes actual
        $inicio_mes = Carbon::now()->startOfMonth();
        $fin_mes = Carbon::now()->endOfMonth();
        $clientes_mes = Cliente::whereBetween('creado_en', [$inicio_mes, $fin_mes])->count();
        
        // Clientes recientes (últimos 10)
        $clientes_recientes = Cliente::with('instituto')
            ->orderBy('creado_en', 'desc')
            ->take(10)
            ->get();
        
        // Datos para la vista
        $estadisticas = [
            'total_clientes' => $total_clientes,
            'clientes_activos' => $clientes_activos,
            'clientes_pendientes' => $clientes_pendientes,
            'clientes_con_pension' => $clientes_con_pension,
            'clientes_mes' => $clientes_mes,
            'clientes_institucion' => [
                'imss' => $clientes_imss,
                'issste' => $clientes_issste,
            ]
        ];
        
        return view('dashboard.index', [
            'estadisticas' => $estadisticas,
            'clientes_recientes' => $clientes_recientes,
        ]);
    }
    
    // Método para API/AJAX que actualiza estadísticas
    public function estadisticas()
    {
        // Total de clientes
        $total_clientes = Cliente::count();
        
        // Clientes activos (tipo 'C' y estatus 'Activo')
        $clientes_activos = Cliente::where('tipo_cliente', 'C')
            ->where('estatus', 'Activo')
            ->count();
        
        // Clientes pendientes (tipo 'P' - Prospectos)
        $clientes_pendientes = Cliente::where('tipo_cliente', 'P')->count();
        
        // Clientes con pensión (todos IMSS/ISSSTE)
        $clientes_con_pension = Cliente::whereHas('instituto', function($query) {
            $query->whereIn('codigo', ['IMSS', 'ISSSTE']);
        })->count();
        
        // Clientes por institución (IMSS e ISSSTE)
        $clientes_imss = Cliente::whereHas('instituto', function($query) {
            $query->where('codigo', 'IMSS');
        })->count();
        
        $clientes_issste = Cliente::whereHas('instituto', function($query) {
            $query->where('codigo', 'ISSSTE');
        })->count();
        
        // Clientes agregados en el mes actual
        $inicio_mes = Carbon::now()->startOfMonth();
        $fin_mes = Carbon::now()->endOfMonth();
        $clientes_mes = Cliente::whereBetween('creado_en', [$inicio_mes, $fin_mes])->count();
        
        return response()->json([
            'total_clientes' => $total_clientes,
            'clientes_activos' => $clientes_activos,
            'clientes_pendientes' => $clientes_pendientes,
            'clientes_con_pension' => $clientes_con_pension,
            'clientes_mes' => $clientes_mes,
            'clientes_institucion' => [
                'imss' => $clientes_imss,
                'issste' => $clientes_issste,
            ]
        ]);
    }
    
    // Métodos para perfil (placeholder)
    public function perfil()
    {
        return view('perfil.index');
    }
    
    public function actualizarPerfil()
    {
        // Implementar lógica de actualización de perfil
        return back()->with('success', 'Perfil actualizado correctamente');
    }
}
