<?php

namespace App\Http\Controllers\Config;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use Illuminate\Http\Request;

class PermisosController extends Controller
{
    public function index()
    {
        $permisos = Permission::all();
        return view('config.permisos.index', compact('permisos'));
    }

    public function create()
    {
        return view('config.permisos.create');
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);
        Permission::create($request->all());
        return redirect()->route('config.permisos.index');
    }

    public function edit($id)
    {
        $permiso = Permission::findOrFail($id);
        return view('config.permisos.create', compact('permiso'));
    }

    public function update(Request $request, $id)
    {
        $permiso = Permission::findOrFail($id);
        $permiso->update($request->all());
        return redirect()->route('config.permisos.index');
    }

    public function destroy($id)
    {
        Permission::destroy($id);
        return redirect()->route('config.permisos.index');
    }
}
