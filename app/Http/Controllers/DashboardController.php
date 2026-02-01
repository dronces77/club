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
        $totalClientes = Cliente::count();
        
        // Clientes activos (tipo 'C' y estatus 'Activo')
        $clientesActivos = Cliente::where('tipo_cliente', 'C')
            ->where('estatus', 'Activo')
            ->count();
        
        // Clientes pendientes (tipo 'P' - Prospectos)
        $clientesPendientes = Cliente::where('tipo_cliente', 'P')->count();
        
        // Clientes con pensión (todos IMSS/ISSSTE)
        $clientesConPension = Cliente::whereHas('instituto', function($query) {
            $query->whereIn('codigo', ['IMSS', 'ISSSTE']);
        })->count();
        
        // Clientes por institución (IMSS e ISSSTE)
        $clientesIMSS = Cliente::whereHas('instituto', function($query) {
            $query->where('codigo', 'IMSS');
        })->count();
        
        $clientesISSSTE = Cliente::whereHas('instituto', function($query) {
            $query->where('codigo', 'ISSSTE');
        })->count();
        
        // Clientes agregados en el mes actual
        $inicioMes = Carbon::now()->startOfMonth();
        $finMes = Carbon::now()->endOfMonth();
        $clientesMes = Cliente::whereBetween('creado_en', [$inicioMes, $finMes])->count();
        
        // Clientes recientes (últimos 10)
        $clientesRecientes = Cliente::with('instituto')
            ->orderBy('creado_en', 'desc')
            ->take(10)
            ->get();
        
        return view('dashboard.index', [
            'totalClientes' => $totalClientes,
            'clientesActivos' => $clientesActivos,
            'clientesPendientes' => $clientesPendientes,
            'clientesConPension' => $clientesConPension,
            'clientesIMSS' => $clientesIMSS,
            'clientesISSSTE' => $clientesISSSTE,
            'clientesMes' => $clientesMes,
            'clientesRecientes' => $clientesRecientes,
        ]);
    }
    
    // Método para API/AJAX que actualiza estadísticas
    public function estadisticas()
    {
        // Total de clientes
        $totalClientes = Cliente::count();
        
        // Clientes activos (tipo 'C' y estatus 'Activo')
        $clientesActivos = Cliente::where('tipo_cliente', 'C')
            ->where('estatus', 'Activo')
            ->count();
        
        // Clientes pendientes (tipo 'P' - Prospectos)
        $clientesPendientes = Cliente::where('tipo_cliente', 'P')->count();
        
        // Clientes con pensión (todos IMSS/ISSSTE)
        $clientesConPension = Cliente::whereHas('instituto', function($query) {
            $query->whereIn('codigo', ['IMSS', 'ISSSTE']);
        })->count();
        
        // Clientes por institución (IMSS e ISSSTE)
        $clientesIMSS = Cliente::whereHas('instituto', function($query) {
            $query->where('codigo', 'IMSS');
        })->count();
        
        $clientesISSSTE = Cliente::whereHas('instituto', function($query) {
            $query->where('codigo', 'ISSSTE');
        })->count();
        
        // Clientes agregados en el mes actual
        $inicioMes = Carbon::now()->startOfMonth();
        $finMes = Carbon::now()->endOfMonth();
        $clientesMes = Cliente::whereBetween('creado_en', [$inicioMes, $finMes])->count();
        
        return response()->json([
            'totalClientes' => $totalClientes,
            'clientesActivos' => $clientesActivos,
            'clientesPendientes' => $clientesPendientes,
            'clientesConPension' => $clientesConPension,
            'clientesMes' => $clientesMes,
            'clientesIMSS' => $clientesIMSS,
            'clientesISSSTE' => $clientesISSSTE,
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
