<?php

namespace App\Http\Controllers\Config;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdatePerfilRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class PerfilController extends Controller
{
    public function show()
    {
        $usuario = auth()->user();
        return view('config.perfil.show', compact('usuario'));
    }

    public function edit()
    {
        $usuario = auth()->user();
        return view('config.perfil.edit', compact('usuario'));
    }

    public function update(UpdatePerfilRequest $request)
    {
        $usuario = auth()->user();

        $usuario->fill($request->only('nombre', 'email'));

        if ($request->filled('password')) {

            if (!Hash::check($request->password_actual, $usuario->password)) {
                return back()->withErrors([
                    'password_actual' => 'La contraseña actual es incorrecta.'
                ]);
            }

            $usuario->password = Hash::make($request->password);
        }

        $this->actualizarFoto($request, $usuario);

        $usuario->save();

        return redirect()->route('config.perfil.show')
            ->with('success', 'Perfil actualizado correctamente.');
    }

    private function actualizarFoto($request, $usuario)
    {
        if ($request->hasFile('foto')) {

            if ($usuario->foto && Storage::exists('public/perfiles/' . $usuario->foto)) {
                Storage::delete('public/perfiles/' . $usuario->foto);
            }

            $archivo = $request->file('foto');
            $nombreArchivo = time().'_'.$archivo->getClientOriginalName();
            $archivo->storeAs('public/perfiles', $nombreArchivo);

            $usuario->foto = $nombreArchivo;
        }
    }
}
