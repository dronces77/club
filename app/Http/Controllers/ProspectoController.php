<?php

namespace App\Http\Controllers;

use App\Models\Prospecto;
use App\Models\Cliente;
use App\Models\ClienteCurp;
use App\Models\ClienteNss;
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

        $prospectos = Prospecto::with('estatus')
            ->when($estatusSeleccionado, function ($q) use ($estatusSeleccionado) {
                $q->where('estatus_prospecto_id', $estatusSeleccionado);
            })
            ->orderBy('fecha_creacion', 'desc')
            ->get();

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
            'celular'          => 'required|string|max:20',
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
            'estatus_prospecto_id' => 1, // Nuevo
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

    public function convertir(Prospecto $prospecto)
    {
        if ($prospecto->convertido) {
            return back()->withErrors('Este prospecto ya fue convertido');
        }

        DB::transaction(function () use ($prospecto) {

            /** ===============================
             *  Generar número de cliente
             *  =============================== */
            $ultimo = Cliente::whereNotNull('no_cliente')
                ->orderBy('id', 'desc')
                ->first();

            $numero = $ultimo
                ? ((int) substr($ultimo->no_cliente, 3)) + 1
                : 1;

            $noCliente = 'CP-' . str_pad($numero, 4, '0', STR_PAD_LEFT);

            /** ===============================
             *  Crear cliente
             *  =============================== */
            $cliente = Cliente::create([
                'no_cliente'       => $noCliente,
                'tipo_cliente'     => 'C',
                'nombre'           => $prospecto->nombre,
                'apellido_paterno' => $prospecto->apellido_paterno,
                'apellido_materno' => $prospecto->apellido_materno,
                'celular'          => $prospecto->celular,
                'notas'            => $prospecto->notas,
            ]);

            /** ===============================
             *  CURP principal
             *  =============================== */
            ClienteCurp::create([
                'cliente_id'   => $cliente->id,
                'curp'         => $prospecto->curp,
                'es_principal' => 1,
            ]);

            /** ===============================
             *  NSS principal (si existe)
             *  =============================== */
            if ($prospecto->nss) {
                ClienteNss::create([
                    'cliente_id'   => $cliente->id,
                    'nss'          => $prospecto->nss,
                    'es_principal' => 1,
                ]);
            }

            /** ===============================
             *  Actualizar prospecto
             *  =============================== */
            $prospecto->update([
                'convertido'           => 1,
                'cliente_id'           => $cliente->id,
                'estatus_prospecto_id' => CatalogoEstatusProspecto::where('nombre', 'Convertido')->value('id'),
            ]);
        });

        return redirect()->route('clientes.index')
            ->with('success', 'Prospecto convertido en cliente');
    }
}
