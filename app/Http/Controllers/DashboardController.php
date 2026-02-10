<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Prospecto;
use App\Models\CatalogoInstituto;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Display the dashboard.
     */
public function index()
{
    // 🔒 CLIENTES (pendiente de implementar)
    $totalClientes = 0;
    $clientesActivos = 0;
    $clientesPendientes = 0;
    $clientesConPension = 0;
    $clientesIMSS = 0;
    $clientesISSSTE = 0;
    $clientesMes = 0;
    $clientesRecientes = collect();

    // ✅ PROSPECTOS
    $prospectos = Prospecto::latest()->take(5)->get();

    // ✅ ESTATUS (placeholder para no romper vista)
    $estatusLista = collect(); // 👈 ESTA ES LA CLAVE

    return view('dashboard.index', compact(
        'totalClientes',
        'clientesActivos',
        'clientesPendientes',
        'clientesConPension',
        'clientesIMSS',
        'clientesISSSTE',
        'clientesMes',
        'clientesRecientes',
        'prospectos',
        'estatusLista'
    ));
}


    /**
     * API para actualizar estadísticas del dashboard (AJAX)
     */
    public function estadisticas()
    {
        $totalClientes = Cliente::count();
        $clientesActivos = 0; // temporal
        $clientesPendientes = 0; // temporal
        
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
