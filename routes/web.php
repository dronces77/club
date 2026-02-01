<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ClienteController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// ====================
// RUTAS PÚBLICAS
// ====================
Route::get('/', function () {
    return redirect()->route('login');
});

// Autenticación
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ====================
// RUTAS PROTEGIDAS (requieren autenticación)
// ====================
Route::middleware(['auth'])->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/estadisticas', [DashboardController::class, 'estadisticas'])->name('dashboard.estadisticas');
    
    // Clientes (CRUD completo)
    Route::resource('clientes', ClienteController::class);
    
    // Nueva ruta para búsqueda en tiempo real
    Route::get('/clientes/search', [ClienteController::class, 'search'])->name('clientes.search');
    
    // Rutas adicionales para clientes
    Route::get('/clientes/{cliente}/cambiar-estatus', [ClienteController::class, 'cambiarEstatus'])->name('clientes.cambiar-estatus');
    Route::put('/clientes/{cliente}/cambiar-estatus', [ClienteController::class, 'cambiarEstatusUpdate'])->name('clientes.cambiar-estatus.update');
    Route::get('/clientes/exportar', [ClienteController::class, 'exportar'])->name('clientes.exportar');
    Route::get('/clientes/estadisticas', [ClienteController::class, 'estadisticas'])->name('clientes.estadisticas');
    Route::get('/regimenes-por-instituto/{institutoId}', [ClienteController::class, 'getRegimenesPorInstituto'])->name('regimenes.por-instituto');
    
    // Perfil de usuario
    Route::get('/perfil', [DashboardController::class, 'perfil'])->name('perfil');
    Route::put('/perfil', [DashboardController::class, 'actualizarPerfil'])->name('perfil.update');
});

// ====================
// RUTAS DE PRUEBA (temporal)
// ====================
Route::get('/test', function () {
    return "✅ Sistema funcionando correctamente!";
});

Route::get('/test-auth', function () {
    if (auth()->check()) {
        return "✅ Usuario autenticado: " . auth()->user()->email;
    } else {
        return "❌ No autenticado";
    }
});
