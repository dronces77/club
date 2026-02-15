<?php

namespace App\Http\Controllers\Config;

use App\Http\Controllers\Controller;
use App\Models\Bitacora;
use Illuminate\Http\Request;

class BitacoraController extends Controller
{
    public function index()
    {
        $logs = Bitacora::all();
        return view('config.bitacora.index', compact('logs'));
    }

    public function create()
    {
        return view('config.bitacora.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'accion' => 'required|string',
            'usuario_id' => 'required|integer'
        ]);

        Bitacora::create($request->all());
        return redirect()->route('config.bitacora.index');
    }

    public function edit($id)
    {
        $log = Bitacora::findOrFail($id);
        return view('config.bitacora.create', compact('log'));
    }

    public function update(Request $request, $id)
    {
        $log = Bitacora::findOrFail($id);
        $log->update($request->all());
        return redirect()->route('config.bitacora.index');
    }

    public function destroy($id)
    {
        Bitacora::destroy($id);
        return redirect()->route('config.bitacora.index');
    }
}
