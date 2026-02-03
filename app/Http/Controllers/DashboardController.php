<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\CatalogoInstituto;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Display the dashboard.
     */
    public function index()
    {
        // Total de clientes
        $totalClientes = Cliente::count();
        
        // Clientes activos
        $clientesActivos = Cliente::where('estatus', 'Activo')->count();
        
        // No existe 'pendiente' en nuevo schema
        $clientesPendientes = 0;
        
        // Clientes con pensión
        $clientesConPension = Cliente::where(function($query) {
                $query->whereNotNull('pension_default')
                      ->orWhereNotNull('pension_normal')
                      ->orWhere('pension_default', '>', 0)
                      ->orWhere('pension_normal', '>', 0);
            })
            ->count();
        
        // IDs según schema: IMSS=13, ISSSTE=14
        $clientesIMSS = Cliente::where(function($q) {
                $q->where('instituto_id', 13)
                  ->orWhere('instituto2_id', 13);
            })
            ->count();
        
        $clientesISSSTE = Cliente::where(function($q) {
                $q->where('instituto_id', 14)
                  ->orWhere('instituto2_id', 14);
            })
            ->count();
        
        // Clientes agregados este mes
        $clientesMes = Cliente::whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->count();
        
        // Clientes recientes (últimos 10)
        $clientesRecientes = Cliente::with(['instituto', 'instituto2'])
            ->orderBy('created_at', 'desc')
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
        $totalClientes = Cliente::count();
        $clientesActivos = Cliente::where('estatus', 'Activo')->count();
        $clientesPendientes = 0;
        
        $clientesConPension = Cliente::where(function($query) {
                $query->whereNotNull('pension_default')
                      ->orWhereNotNull('pension_normal')
                      ->orWhere('pension_default', '>', 0)
                      ->orWhere('pension_normal', '>', 0);
            })
            ->count();
        
        $clientesIMSS = Cliente::where(function($q) {
                $q->where('instituto_id', 13)
                  ->orWhere('instituto2_id', 13);
            })
            ->count();
        
        $clientesISSSTE = Cliente::where(function($q) {
                $q->where('instituto_id', 14)
                  ->orWhere('instituto2_id', 14);
            })
            ->count();
        
        $clientesMes = Cliente::whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
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

    public function perfil()
    {
        $user = auth()->user();
        return view('dashboard.perfil', compact('user'));
    }

    public function actualizarPerfil(Request $request)
    {
        $user = auth()->user();
        
        $request->validate([
            'nombre' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:usuarios,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
        ]);
        
        $user->nombre = $request->nombre;
        $user->email = $request->email;
        
        if ($request->filled('password')) {
            $user->password = bcrypt($request->password);
        }
        
        $user->save();
        
        return redirect()->route('perfil')
            ->with('success', 'Perfil actualizado exitosamente.');
    }
}