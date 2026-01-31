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
        // Si ya está autenticado, redirigir al dashboard
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        
        return view('auth.login');
    }

    /**
     * Procesar login
     */
    public function login(Request $request)
    {
        // DEPURACIÓN - Mostrar datos recibidos
        \Log::info('Intento de login:', [
            'email' => $request->email,
            'password_length' => strlen($request->password),
            'ip' => $request->ip()
        ]);

        // Validar datos
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        // Buscar usuario por email
        $usuario = Usuario::where('email', $credentials['email'])->first();
        
        // DEPURACIÓN - Mostrar usuario encontrado
        \Log::info('Usuario encontrado:', [
            'existe' => !!$usuario,
            'id' => $usuario ? $usuario->id : null,
            'activo' => $usuario ? $usuario->activo : null,
            'username' => $usuario ? $usuario->username : null,
            'hash_inicio' => $usuario ? substr($usuario->password_hash, 0, 20) . '...' : null
        ]);

        // Verificar si existe
        if (!$usuario) {
            \Log::warning('Usuario no encontrado: ' . $credentials['email']);
            return back()
                ->withInput($request->only('email', 'remember'))
                ->withErrors([
                    'email' => 'Las credenciales no coinciden con nuestros registros.',
                ]);
        }

        // Verificar si el usuario está activo
        if (!$usuario->activo) {
            \Log::warning('Usuario inactivo intentó login: ' . $usuario->email);
            return back()
                ->withInput($request->only('email', 'remember'))
                ->withErrors([
                    'email' => 'Tu cuenta está desactivada. Contacta al administrador.',
                ]);
        }

        // VERIFICACIÓN ESPECIAL PARA DEBUG
        // Primero probar password_verify normal
        $passwordMatch = password_verify($credentials['password'], $usuario->password_hash);
        
        // Si no funciona, probar bcrypt directo
        if (!$passwordMatch) {
            \Log::info('password_verify falló, probando comparación manual');
            
            // Generar nuevo hash con la contraseña proporcionada
            $newHash = bcrypt($credentials['password']);
            
            // Si el hash en BD es muy viejo o diferente formato, actualizarlo
            if (Hash::needsRehash($usuario->password_hash)) {
                \Log::info('Hash necesita rehash, actualizando...');
                $usuario->password_hash = $newHash;
                $usuario->save();
                $passwordMatch = true; // Al actualizarlo, damos por válido
            } else {
                // Comparar manualmente si son iguales (caso raro)
                $passwordMatch = ($usuario->password_hash === $newHash);
            }
        }

        // DEPURACIÓN - Resultado de verificación
        \Log::info('Resultado verificación:', [
            'password_match' => $passwordMatch,
            'hash_actual' => $usuario->password_hash,
            'password_provided' => $credentials['password']
        ]);

        if ($passwordMatch) {
            // Iniciar sesión manualmente
            Auth::login($usuario, $request->boolean('remember'));
            
            // Actualizar último login
            $usuario->ultimo_login = now();
            $usuario->save();
            
            // Regenerar sesión
            $request->session()->regenerate();
            
            \Log::info('Login exitoso para usuario: ' . $usuario->email);
            
            // Redirigir al dashboard
            return redirect()->intended(route('dashboard'))
                ->with('success', '¡Bienvenido ' . $usuario->nombre . '!');
        }

        // Si falla la autenticación
        \Log::warning('Login fallido para: ' . $credentials['email']);
        return back()
            ->withInput($request->only('email', 'remember'))
            ->withErrors([
                'email' => 'Las credenciales no coinciden con nuestros registros.',
            ]);
    }

    /**
     * Cerrar sesión
     */
    public function logout(Request $request)
    {
        \Log::info('Logout usuario: ' . Auth::user()->email);
        
        Auth::logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('login')
            ->with('info', 'Sesión cerrada correctamente.');
    }
}
