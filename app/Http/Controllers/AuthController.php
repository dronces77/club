<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Usuario;

class AuthController extends Controller
{
    /**
     * Mostrar formulario de login
     */
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        
        return view('auth.login');
    }

    /**
     * Procesar login - VERSIÓN CORREGIDA
     */
    public function login(Request $request)
    {
        // DEPURACIÓN
        \Log::info('Intento de login:', [
            'login' => $request->login,
            'ip' => $request->ip()
        ]);

        // ✅ CORREGIDO: Campo 'login' (acepta email O nombre)
        $request->validate([
            'login' => 'required|string',
            'password' => 'required',
        ]);

        // ✅ CORREGIDO: Buscar por email O nombre
        $usuario = Usuario::where('email', $request->login)
            ->orWhere('nombre', $request->login)
            ->first();
        
        // DEPURACIÓN
        \Log::info('Usuario encontrado:', [
            'existe' => !!$usuario,
            'id' => $usuario ? $usuario->id : null,  // ✅ 'id', no 'usuario_id'
            'estatus' => $usuario ? $usuario->estatus : null,
            'nombre' => $usuario ? $usuario->nombre : null,
            'email' => $usuario ? $usuario->email : null
        ]);

        // Verificar si existe
        if (!$usuario) {
            \Log::warning('Usuario no encontrado: ' . $request->login);
            return back()
                ->withInput($request->only('login', 'remember'))
                ->withErrors([
                    'login' => 'Las credenciales no coinciden con nuestros registros.',
                ]);
        }

        // ✅ CORREGIDO: Verificar estatus
        if ($usuario->estatus !== 'activo') {
            \Log::warning('Usuario inactivo intentó login: ' . $usuario->email);
            return back()
                ->withInput($request->only('login', 'remember'))
                ->withErrors([
                    'login' => 'Tu cuenta está desactivada. Contacta al administrador.',
                ]);
        }

        // ✅ CORREGIDO: Verificar contraseña MANUALMENTE (no usar Auth::validate)
        if (!Hash::check($request->password, $usuario->password)) {
            \Log::warning('Login fallido para: ' . $request->login);
            return back()
                ->withInput($request->only('login', 'remember'))
                ->withErrors([
                    'login' => 'Las credenciales no coinciden con nuestros registros.',
                ]);
        }

        // Iniciar sesión manualmente
        Auth::login($usuario, $request->boolean('remember'));
        
        // ✅ CORREGIDO: Actualizar último login
        $usuario->ultimo_login = now();
        $usuario->save();
        
        // Regenerar sesión
        $request->session()->regenerate();
        
        \Log::info('Login exitoso para usuario: ' . $usuario->nombre);
        
        // Redirigir al dashboard
        return redirect()->intended(route('dashboard'))
            ->with('success', '¡Bienvenido ' . $usuario->nombre . '!');
    }

    /**
     * Cerrar sesión
     */
    public function logout(Request $request)
    {
        if (Auth::check()) {
            \Log::info('Logout usuario: ' . Auth::user()->nombre);
        }
        
        Auth::logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('login')
            ->with('info', 'Sesión cerrada correctamente.');
    }
}
