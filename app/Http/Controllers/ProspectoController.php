<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\ClienteContacto;
use App\Models\ClienteCurp;
use App\Models\ClienteNss;
use App\Models\Prospecto;
use App\Models\CatalogoEstatusProspecto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProspectoController extends Controller
{
    public function index(Request $request)
    {
        $estatusSeleccionado = $request->get('estatus');

        // Ordenamiento
        $sort = $request->get('sort', 'created_at');
        $direction = $request->get('direction', 'desc');

        $allowedSorts = ['id', 'nombre', 'curp', 'nss', 'celular', 'estatus_prospecto_id', 'created_at'];
        if (!in_array($sort, $allowedSorts)) $sort = 'created_at';
        if (!in_array($direction, ['asc', 'desc'])) $direction = 'desc';

        $estatus = CatalogoEstatusProspecto::where('activo', 1)->orderBy('orden')->get();
        $query = Prospecto::with('estatus');

        // Filtro por estatus
        if ($estatusSeleccionado) {
            $estatusObj = CatalogoEstatusProspecto::find($estatusSeleccionado);

            if ($estatusObj) {
                if ($estatusObj->nombre === 'Convertido') {
                    $query->where('convertido', 1);
                } else {
                    $query->where('estatus_prospecto_id', $estatusObj->id)->where('convertido', 0);
                }
            }
        } else {
            $query->where('convertido', 0);
        }

        $prospectos = $query->orderBy($sort, $direction)->paginate(15)->appends($request->except('page'));

        return view('prospectos.index', compact(
            'prospectos',
            'estatus',
            'estatusSeleccionado',
            'sort',
            'direction'
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
            'celular'          => 'nullable|string|max:13',
            'notas'            => 'nullable|string|max:250',
        ]);

        // Guardar prospecto
        $prospecto = Prospecto::create([
            'nombre'               => $request->nombre,
            'apellido_paterno'     => $request->apellido_paterno,
            'apellido_materno'     => $request->apellido_materno,
            'curp'                 => $request->curp,
            'nss'                  => $request->nss,
            'celular'              => $request->celular,
            'notas'                => $request->notas,
            'estatus_prospecto_id' => 1,
            'convertido'           => 0,
        ]);

        // Redirigir con mensaje de éxito
        return redirect()
            ->route('prospectos.index')
            ->with('success', "Prospecto creado correctamente: {$prospecto->nombre} {$prospecto->apellido_paterno} {$prospecto->apellido_materno}");
    }

    public function updateEstatus(Request $request, Prospecto $prospecto)
    {
        if ($prospecto->convertido) {
            return back()->withErrors('El prospecto ya fue convertido');
        }

        $request->validate([
            'estatus_prospecto_id' => 'required|exists:catalogo_estatus_prospectos,id',
        ]);

        $prospecto->update(['estatus_prospecto_id' => $request->estatus_prospecto_id]);

        return back()->with('success', "Estatus actualizado correctamente: {$prospecto->nombre} {$prospecto->apellido_paterno} {$prospecto->apellido_materno}");
    }

    public function convertir($id)
    {
        DB::beginTransaction();

        try {
            $prospecto = Prospecto::lockForUpdate()->findOrFail($id);

            if ($prospecto->convertido) {
                return back()->withErrors('Este prospecto ya fue convertido.');
            }

            // Generar número de cliente
            $ultimo = Cliente::where('no_cliente', 'LIKE', 'CP-%')
                ->orderByRaw("CAST(SUBSTRING(no_cliente, 4) AS UNSIGNED) DESC")
                ->lockForUpdate()
                ->first();

            $siguienteNumero = $ultimo ? ((int) str_replace('CP-', '', $ultimo->no_cliente)) + 1 : 1;
            $noCliente = 'CP-' . $siguienteNumero;

            // Crear cliente
            $cliente = Cliente::create([
                'no_cliente'        => $noCliente,
                'tipo_cliente'      => 'C',
                'nombre'            => $prospecto->nombre,
                'apellido_paterno'  => $prospecto->apellido_paterno,
                'apellido_materno'  => $prospecto->apellido_materno,
                'creado_por'        => auth()->id(),
                'estatus_cliente_id' => 1,
            ]);

            // Crear CURP
            if ($prospecto->curp) {
                ClienteCurp::create([
                    'cliente_id'   => $cliente->id,
                    'curp'         => $prospecto->curp,
                    'es_principal' => 1,
                ]);
            }

            // Crear NSS
            if ($prospecto->nss) {
                ClienteNss::create([
                    'cliente_id'   => $cliente->id,
                    'nss'          => $prospecto->nss,
                    'es_principal' => 1,
                ]);
            }

            // Crear celular
            if ($prospecto->celular) {
                ClienteContacto::create([
                    'cliente_id'   => $cliente->id,
                    'tipo'         => 'celular',
                    'valor'        => $prospecto->celular,
                    'es_principal' => 1,
                ]);
            }

            // Actualizar prospecto como convertido
            $prospecto->update([
                'convertido' => 1,
                'cliente_id' => $cliente->id,
            ]);

            DB::commit();

            // Mensaje de éxito unificado
            $mensaje = "Prospecto convertido correctamente a cliente {$noCliente}: {$prospecto->nombre} {$prospecto->apellido_paterno} {$prospecto->apellido_materno}";

            return redirect()->route('prospectos.index')->with('success', $mensaje);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors('Error al convertir prospecto: ' . $e->getMessage());
        }
    }
}
