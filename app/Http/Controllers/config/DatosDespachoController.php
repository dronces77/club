<?php

namespace App\Http\Controllers\Config;

use App\Http\Controllers\Controller;
use App\Models\DatosDespacho;
use Illuminate\Http\Request;

class DatosDespachoController extends Controller
{
    public function index()
    {
        $datos = DatosDespacho::all();
        return view('config.datos-despacho.index', compact('datos'));
    }

    public function create()
    {
        return view('config.datos-despacho.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'direccion' => 'nullable|string|max:255',
            'telefono' => 'nullable|string|max:50',
            'correo' => 'nullable|email'
        ]);

        DatosDespacho::create($request->all());
        return redirect()->route('config.datos-despacho.index');
    }

    public function edit($id)
    {
        $dato = DatosDespacho::findOrFail($id);
        return view('config.datos-despacho.create', compact('dato'));
    }

    public function update(Request $request, $id)
    {
        $dato = DatosDespacho::findOrFail($id);
        $dato->update($request->all());
        return redirect()->route('config.datos-despacho.index');
    }

    public function destroy($id)
    {
        DatosDespacho::destroy($id);
        return redirect()->route('config.datos-despacho.index');
    }
}
