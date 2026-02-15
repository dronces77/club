<?php

namespace App\Http\Controllers\Config;

use App\Http\Controllers\Controller;
use App\Models\CatalogoAfore; // ejemplo de catálogo
use Illuminate\Http\Request;

class CatalogosController extends Controller
{
    public function index()
    {
        $catalogos = CatalogoAfore::all(); // puedes cambiar por cualquier catálogo
        return view('config.catalogos.index', compact('catalogos'));
    }

    public function create()
    {
        return view('config.catalogos.create');
    }

    public function store(Request $request)
    {
        $request->validate(['nombre' => 'required|string|max:255']);
        CatalogoAfore::create($request->all());
        return redirect()->route('config.catalogos.index');
    }

    public function edit($id)
    {
        $catalogo = CatalogoAfore::findOrFail($id);
        return view('config.catalogos.create', compact('catalogo'));
    }

    public function update(Request $request, $id)
    {
        $catalogo = CatalogoAfore::findOrFail($id);
        $catalogo->update($request->all());
        return redirect()->route('config.catalogos.index');
    }

    public function destroy($id)
    {
        CatalogoAfore::destroy($id);
        return redirect()->route('config.catalogos.index');
    }
}
