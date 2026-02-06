<?php

namespace App\Http\Controllers;

use App\Models\ClienteCurp;
use App\Models\ClienteNss;
use App\Models\ClienteContacto;
use App\Models\Prospecto;
use App\Models\Cliente;
use App\Models\CatalogoEstatusProspecto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProspectoController extends Controller
{
    public function index(Request $request)
    {
        $estatusSeleccionado = $request->get('estatus');

        $estatus = CatalogoEstatusProspecto::where('activo', 1)
            ->orderBy('orden')
            ->get();

        $query = Prospecto::with('estatus');

        if ($estatusSeleccionado) {
            $query->where('estatus_prospecto_id', $estatusSeleccionado);
        }

        $prospectos = $query
            ->orderBy('fecha_creacion', 'desc')
            ->paginate(15);

        return view('prospectos.index', compact(
            'prospectos',
            'estatus',
            'estatusSeleccionado'
        ));
    }

    public function create()
    {
        return view('prospectos.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre'           => 'required|string|max:100',
            'apellido_paterno' => 'required|string|max:100',
            'apellido_materno' => 'nullable|string|max:100',
            'curp'             => 'required|string|size:18|unique:prospectos,curp',
            'nss'              => 'nullable|string|size:11|unique:prospectos,nss',
            'celular'          => 'required|string|max:13',
            'notas'            => 'nullable|string|max:250',
        ]);

        Prospecto::create([
            'nombre'               => $request->nombre,
            'apellido_paterno'     => $request->apellido_paterno,
            'apellido_materno'     => $request->apellido_materno,
            'curp'                 => $request->curp,
            'nss'                  => $request->nss,
            'celular'              => $request->celular,
            'notas'                => $request->notas,
            'estatus_prospecto_id' => 1,
            'convertido'           => 0,
            'fecha_creacion'       => now(),
        ]);

        return redirect()->route('prospectos.index')
            ->with('success', 'Prospecto creado correctamente');
    }

    public function updateEstatus(Request $request, Prospecto $prospecto)
    {
        if ($prospecto->convertido) {
            return back()->withErrors('El prospecto ya fue convertido');
        }

        $request->validate([
            'estatus_prospecto_id' => 'required|exists:catalogo_estatus_prospectos,id'
        ]);

        $prospecto->update([
            'estatus_prospecto_id' => $request->estatus_prospecto_id
        ]);

        return back()->with('success', 'Estatus actualizado');
    }

    /**
     * 🔥 CONVERSIÓN A CLIENTE (nivel banco)
     */
    public function convertir($id)
    {
        DB::beginTransaction();

        try {
            $prospecto = Prospecto::lockForUpdate()->findOrFail($id);

            if ($prospecto->convertido) {
                throw new \Exception('Este prospecto ya fue convertido.');
            }

            $ultimo = Cliente::where('no_cliente', 'LIKE', 'CP-%')
                ->orderByRaw("CAST(SUBSTRING(no_cliente, 4) AS UNSIGNED) DESC")
                ->lockForUpdate()
                ->first();

            $siguienteNumero = $ultimo
                ? ((int) str_replace('CP-', '', $ultimo->no_cliente)) + 1
                : 1;

            $noCliente = 'CP-' . $siguienteNumero;

            $cliente = Cliente::create([
                'no_cliente'   => $noCliente,
                'nombre'       => $prospecto->nombre,
                'apellido_paterno'   => $prospecto->apellido_paterno,
                'apellido_materno'   => $prospecto->apellido_materno,
                'tipo_cliente' => 'C',
                'estatus'      => 'activo',
                'creado_por'   => auth()->id(),
            ]);

            // CURP PRINCIPAL
            if (!empty($prospecto->curp)) {
                ClienteCurp::create([
                    'cliente_id'   => $cliente->id,
                    'curp'         => $prospecto->curp,
                    'es_principal' => 1,
                ]);
            }

            // NSS PRINCIPAL
            if (!empty($prospecto->nss)) {
                ClienteNss::create([
                    'cliente_id'   => $cliente->id,
                    'nss'          => $prospecto->nss,
                    'es_principal' => 1,
                ]);
            }

            // 🔹 CONTACTO (FIX REAL)
            if (!empty($prospecto->celular)) {
                ClienteContacto::create([
                    'cliente_id'   => $cliente->id,
                    'tipo'         => 'celular',
                    'valor'        => $prospecto->celular,
                    'es_principal' => 1,
                ]);
            }

            $prospecto->update([
                'convertido' => 1,
                'cliente_id' => $cliente->id,
            ]);

            DB::commit();

            return redirect()
                ->route('clientes.show', $cliente->id)
                ->with('success', "Prospecto convertido correctamente a cliente {$noCliente}");

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
