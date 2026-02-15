<?php

namespace App\Http\Controllers\Config;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use Illuminate\Http\Request;

class RollbackController extends Controller
{
    public function index()
    {
        $clientes = Cliente::all(); // lista de clientes que se pueden volver prospecto
        return view('config.rollback.index', compact('clientes'));
    }

    public function rollback($id)
    {
        $cliente = Cliente::findOrFail($id);
        // Aquí pones la lógica para convertir cliente a prospecto
        $cliente->estatus = 'prospecto';
        $cliente->save();
        return redirect()->route('config.rollback.index');
    }
}
