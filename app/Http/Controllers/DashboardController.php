<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cliente;
use App\Models\Usuario;

class DashboardController extends Controller
{
    /**
     * Mostrar dashboard principal
     */
    public function index()
    {
        // Estadísticas básicas
        $estadisticas = [
            'total_clientes' => Cliente::count(),
            'clientes_activos' => Cliente::where('estatus', 'Activo')->count(),
            'clientes_pendientes' => Cliente::where('estatus', 'pendiente')->count(),
            'clientes_institucion' => [
                'imss' => Cliente::whereHas('instituto', function($q) {
                    $q->where('codigo', 'IMSS');
                })->count(),
                'issste' => Cliente::whereHas('instituto', function($q) {
                    $q->where('codigo', 'ISSSTE');
                })->count(),
            ],
        ];

        // Clientes recientes
        $clientes_recientes = Cliente::with('instituto')
            ->orderBy('creado_en', 'desc')
            ->limit(10)
            ->get();

        return view('dashboard.index', compact('estadisticas', 'clientes_recientes'));
    }

    /**
     * Mostrar perfil de usuario
     */
    public function perfil()
    {
        $usuario = auth()->user();
        return view('dashboard.perfil', compact('usuario'));
    }

    /**
     * Actualizar perfil de usuario
     */
    public function actualizarPerfil(Request $request)
    {
        $usuario = auth()->user();
        
        $validated = $request->validate([
            'nombre' => 'required|string|max:100',
            'apellidos' => 'required|string|max:100',
            'email' => 'required|email|unique:usuarios,email,' . $usuario->id,
            'password_actual' => 'nullable|current_password:usuarios',
            'password' => 'nullable|min:6|confirmed',
        ]);

        // Actualizar datos básicos
        $usuario->nombre = $validated['nombre'];
        $usuario->apellidos = $validated['apellidos'];
        $usuario->email = $validated['email'];

        // Actualizar contraseña si se proporcionó
        if (!empty($validated['password'])) {
            $usuario->password_hash = bcrypt($validated['password']);
        }

        $usuario->save();

        return redirect()->route('perfil')
            ->with('success', 'Perfil actualizado correctamente.');
    }
}
