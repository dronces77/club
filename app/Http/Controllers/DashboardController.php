<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Instituto;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Display the dashboard.
     */
    public function index()
    {
        // Total de clientes - EXCLUYE ELIMINADOS
        $totalClientes = Cliente::whereNull('eliminado_en')->count();
        
        // Clientes activos - EXCLUYE ELIMINADOS
        $clientesActivos = Cliente::whereNull('eliminado_en')
            ->where('estatus', 'Activo')
            ->count();
        
        // Clientes pendientes - EXCLUYE ELIMINADOS
        $clientesPendientes = Cliente::whereNull('eliminado_en')
            ->where('estatus', 'pendiente')
            ->count();
        
        // Clientes con pensión - EXCLUYE ELIMINADOS
        // Ajusta esta lógica según tu definición de "con pensión"
        $clientesConPension = Cliente::whereNull('eliminado_en')
            ->where(function($query) {
                $query->whereNotNull('pension_default')
                      ->orWhereNotNull('pension_normal')
                      ->orWhere('pension_default', '>', 0)
                      ->orWhere('pension_normal', '>', 0);
            })
            ->count();
        
        // Clientes por institución - EXCLUYE ELIMINADOS
        $clientesIMSS = Cliente::whereNull('eliminado_en')
            ->where(function($q) {
                $q->where('instituto_id', 1) // IMSS
                  ->orWhere('instituto2_id', 1);
            })
            ->count();
        
        $clientesISSSTE = Cliente::whereNull('eliminado_en')
            ->where(function($q) {
                $q->where('instituto_id', 2) // ISSSTE
                  ->orWhere('instituto2_id', 2);
            })
            ->count();
        
        // Clientes agregados este mes - EXCLUYE ELIMINADOS
        $clientesMes = Cliente::whereNull('eliminado_en')
            ->whereMonth('creado_en', Carbon::now()->month)
            ->whereYear('creado_en', Carbon::now()->year)
            ->count();
        
        // Clientes recientes (últimos 10) - EXCLUYE ELIMINADOS
        $clientesRecientes = Cliente::with(['instituto', 'instituto2'])
            ->whereNull('eliminado_en')
            ->orderBy('creado_en', 'desc')
            ->limit(10)
            ->get();
        
        return view('dashboard.index', compact(
            'totalClientes',
            'clientesActivos',
            'clientesPendientes',
            'clientesConPension',
            'clientesIMSS',
            'clientesISSSTE',
            'clientesMes',
            'clientesRecientes'
        ));
    }

    /**
     * API para actualizar estadísticas del dashboard (AJAX)
     */
    public function estadisticas()
    {
        // Total de clientes - EXCLUYE ELIMINADOS
        $totalClientes = Cliente::whereNull('eliminado_en')->count();
        
        // Clientes activos - EXCLUYE ELIMINADOS
        $clientesActivos = Cliente::whereNull('eliminado_en')
            ->where('estatus', 'Activo')
            ->count();
        
        // Clientes pendientes - EXCLUYE ELIMINADOS
        $clientesPendientes = Cliente::whereNull('eliminado_en')
            ->where('estatus', 'pendiente')
            ->count();
        
        // Clientes con pensión - EXCLUYE ELIMINADOS
        $clientesConPension = Cliente::whereNull('eliminado_en')
            ->where(function($query) {
                $query->whereNotNull('pension_default')
                      ->orWhereNotNull('pension_normal')
                      ->orWhere('pension_default', '>', 0)
                      ->orWhere('pension_normal', '>', 0);
            })
            ->count();
        
        // Clientes por institución - EXCLUYE ELIMINADOS
        $clientesIMSS = Cliente::whereNull('eliminado_en')
            ->where(function($q) {
                $q->where('instituto_id', 1)
                  ->orWhere('instituto2_id', 1);
            })
            ->count();
        
        $clientesISSSTE = Cliente::whereNull('eliminado_en')
            ->where(function($q) {
                $q->where('instituto_id', 2)
                  ->orWhere('instituto2_id', 2);
            })
            ->count();
        
        // Clientes agregados este mes - EXCLUYE ELIMINADOS
        $clientesMes = Cliente::whereNull('eliminado_en')
            ->whereMonth('creado_en', Carbon::now()->month)
            ->whereYear('creado_en', Carbon::now()->year)
            ->count();
        
        return response()->json([
            'totalClientes' => $totalClientes,
            'clientesActivos' => $clientesActivos,
            'clientesPendientes' => $clientesPendientes,
            'clientesConPension' => $clientesConPension,
            'clientesIMSS' => $clientesIMSS,
            'clientesISSSTE' => $clientesISSSTE,
            'clientesMes' => $clientesMes,
            'success' => true,
            'timestamp' => now()->format('Y-m-d H:i:s')
        ]);
    }

    /**
     * Display the user profile.
     */
    public function perfil()
    {
        $user = auth()->user();
        return view('dashboard.perfil', compact('user'));
    }

    /**
     * Update the user profile.
     */
    public function actualizarPerfil(Request $request)
    {
        $user = auth()->user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
        ]);
        
        $user->name = $request->name;
        $user->email = $request->email;
        
        if ($request->filled('password')) {
            $user->password = bcrypt($request->password);
        }
        
        $user->save();
        
        return redirect()->route('perfil')
            ->with('success', 'Perfil actualizado exitosamente.');
    }
}
