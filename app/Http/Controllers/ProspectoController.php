<?php

namespace App\Http\Controllers;

use App\Models\Prospecto;
use App\Models\Cliente;
use Illuminate\Http\Request;

class ProspectoController extends Controller
{
    /**
     * Listado de prospectos
     */
    public function index(Request $request)
    {
        $filtro = $request->get('filtro', 'no_convertidos');

        if ($filtro === 'convertidos') {
            $prospectos = Prospecto::where('convertido', 1)
                ->orderBy('fecha_creacion', 'desc')
                ->get();
        } else {
            $prospectos = Prospecto::where('convertido', 0)
                ->orderBy('fecha_creacion', 'desc')
                ->get();
        }

        return view('prospectos.index', compact('prospectos', 'filtro'));
    }

    /**
     * Formulario crear prospecto
     */
    public function create()
    {
        $clientes = Cliente::orderBy('nombre')->get();

        return view('prospectos.create', compact('clientes'));
    }

    /**
     * Guardar prospecto
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre'            => 'required|string|max:100',
            'apellido_paterno'  => 'required|string|max:100',
            'apellido_materno'  => 'required|string|max:100',
            'curp'              => 'required|string|size:18',
            'celular'           => 'required|string|max:20',
            'origen'            => 'required|string',
            'cliente_origen_id' => 'nullable|exists:clientes,id',
        ]);

        if ($validated['origen'] === 'N/A') {
            $validated['cliente_origen_id'] = null;
        }

        Prospecto::create($validated);

        return redirect()
            ->route('prospectos.index')
            ->with('success', 'Prospecto creado correctamente');
    }
}
