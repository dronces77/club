<?php

namespace App\Http\Controllers\Config;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\Request;

class RolesController extends Controller
{
    public function index()
    {
        $roles = Role::all();
        return view('config.roles.index', compact('roles'));
    }

    public function create()
    {
        return view('config.roles.create');
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);
        Role::create($request->all());
        return redirect()->route('config.roles.index');
    }

    public function edit($id)
    {
        $role = Role::findOrFail($id);
        return view('config.roles.create', compact('role'));
    }

    public function update(Request $request, $id)
    {
        $role = Role::findOrFail($id);
        $role->update($request->all());
        return redirect()->route('config.roles.index');
    }

    public function destroy($id)
    {
        Role::destroy($id);
        return redirect()->route('config.roles.index');
    }
}
