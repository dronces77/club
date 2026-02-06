<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\ProspectoController;

/* RUTAS PÚBLICAS */
Route::get('/', fn () => redirect()->route('login'));

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/* RUTAS PROTEGIDAS */
Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/estadisticas', [DashboardController::class, 'estadisticas'])->name('dashboard.estadisticas');

    // Perfil
    Route::get('/perfil', [DashboardController::class, 'perfil'])->name('perfil');
    Route::put('/perfil', [DashboardController::class, 'actualizarPerfil'])->name('perfil.update');

    // Clientes
    Route::get('/clientes/search', [ClienteController::class, 'search'])->name('clientes.search');
    Route::get('/clientes/exportar', [ClienteController::class, 'exportar'])->name('clientes.exportar');
    Route::get('/clientes/estadisticas', [ClienteController::class, 'estadisticas'])->name('clientes.estadisticas');
    Route::get('/regimenes-por-instituto/{institutoId}', [ClienteController::class, 'getRegimenesPorInstituto'])->name('regimenes.por-instituto');

    Route::get('/clientes/{cliente}/cambiar-estatus', [ClienteController::class, 'cambiarEstatus'])->name('clientes.cambiar-estatus');
    Route::put('/clientes/{cliente}/cambiar-estatus', [ClienteController::class, 'cambiarEstatusUpdate'])->name('clientes.cambiar-estatus.update');

    Route::resource('clientes', ClienteController::class);

    // Prospectos
    Route::prefix('prospectos')->name('prospectos.')->group(function () {

        Route::get('/', [ProspectoController::class, 'index'])->name('index');
        Route::get('/create', [ProspectoController::class, 'create'])->name('create');
        Route::post('/', [ProspectoController::class, 'store'])->name('store');
        Route::put('{prospecto}/estatus', [ProspectoController::class, 'updateEstatus'])->name('updateEstatus');

        // ✅ ÚNICA LÍNEA CORREGIDA

		Route::post('{prospecto}/convertir', [ProspectoController::class, 'convertir'])->name('convertir');
    });
});
