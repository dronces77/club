<?php

namespace App\Http\Controllers\Config;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use Illuminate\Http\Request;

class UsuariosController extends Controller
{
    public function index()
    {
        $usuarios = Usuario::all();
        return view('config.usuarios.index', compact('usuarios'));
    }

    public function create()
    {
        return view('config.usuarios.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:usuarios',
            'password' => 'required|string|min:6'
        ]);

        $data = $request->all();
        $data['password'] = bcrypt($request->password);
        Usuario::create($data);
        return redirect()->route('config.usuarios.index');
    }

    public function edit($id)
    {
        $usuario = Usuario::findOrFail($id);
        return view('config.usuarios.create', compact('usuario'));
    }

    public function update(Request $request, $id)
    {
        $usuario = Usuario::findOrFail($id);
        $data = $request->all();
        if ($request->password) {
            $data['password'] = bcrypt($request->password);
        } else {
            unset($data['password']);
        }
        $usuario->update($data);
        return redirect()->route('config.usuarios.index');
    }

    public function destroy($id)
    {
        Usuario::destroy($id);
        return redirect()->route('config.usuarios.index');
    }
}
