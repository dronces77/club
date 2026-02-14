<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Prospecto;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;

class DashboardController extends Controller
{
    /**
     * Mostrar el dashboard con todos los indicadores.
     */
    public function index()
    {
        // ------------------------------
        // CLIENTES
        // ------------------------------
        $totalClientes = Cliente::count();
        $clientesActivos = Cliente::where('estatus_cliente_id', 1)->count();
        $clientesBaja = Cliente::where('estatus_cliente_id', 2)->count();
        $clientesSuspendidos = Cliente::where('estatus_cliente_id', 3)->count();
        $clientesTerminados = Cliente::where('estatus_cliente_id', 4)->count();

        // Clientes por institución
        $clientesIMSS = Cliente::where(function($q){
            $q->where('instituto_id', 1)->orWhere('instituto2_id', 1);
        })->count();

        $clientesISSSTE = Cliente::where(function($q){
            $q->where('instituto_id', 2)->orWhere('instituto2_id', 2);
        })->count();

        $clientesMes = Cliente::whereMonth('created_at', Carbon::now()->month)
                              ->whereYear('created_at', Carbon::now()->year)
                              ->count();

        $clientesRecientes = Cliente::latest()->take(5)->get();

        // ------------------------------
        // PROSPECTOS (solo activos, convertido = 0)
        // ------------------------------
        $totalProspectos = Prospecto::where('convertido', 0)->count();
        $prospectosNuevos = Prospecto::where('estatus_prospecto_id', 1)->where('convertido', 0)->count();
        $prospectosContactados = Prospecto::where('estatus_prospecto_id', 2)->where('convertido', 0)->count();
        $prospectosInteresados = Prospecto::where('estatus_prospecto_id', 3)->where('convertido', 0)->count();
        $prospectosBaja = Prospecto::where('estatus_prospecto_id', 4)->where('convertido', 0)->count();
        $prospectosImposible = Prospecto::where('estatus_prospecto_id', 5)->where('convertido', 0)->count();
        $prospectosConvertidos = Prospecto::where('estatus_prospecto_id', 6)->count(); // no filtrar convertidos

        $prospectosRecientes = Prospecto::where('convertido', 0)->latest()->take(5)->get();

        // ------------------------------
        // CLIENTES POR RÉGIMEN
        // ------------------------------
        $regimen73 = Cliente::where('regimen_id', 1)->orWhere('regimen2_id', 1)->count();
        $regimen97 = Cliente::where('regimen_id', 2)->orWhere('regimen2_id', 2)->count();
        $regimenDT = Cliente::where('regimen_id', 3)->orWhere('regimen2_id', 3)->count();
        $regimenCI = Cliente::where('regimen_id', 4)->orWhere('regimen2_id', 4)->count();

        // ------------------------------
        // CLIENTES POR TRÁMITE
        // ------------------------------
        // Totales
        $tramitesIMSS = Cliente::whereNotNull('tramite_id')
            ->whereIn('tramite_id', function($q) {
                $q->select('id')->from('catalogo_tramites')->where('activo', 1);
            })->count();

        $tramitesISSSTE = Cliente::whereNotNull('tramite2_id')
            ->whereIn('tramite2_id', function($q) {
                $q->select('id')->from('catalogo_tramites_issste')->where('activo', 1);
            })->count();

		// Detalle por trámite IMSS
		$tramitesIMSSDetalle = DB::table('catalogo_tramites')
			->select('catalogo_tramites.nombre', DB::raw('COUNT(clientes.id) as conteo'))
			->leftJoin('clientes', 'clientes.tramite_id', '=', 'catalogo_tramites.id')
			->where('catalogo_tramites.activo', 1)
			->groupBy('catalogo_tramites.id', 'catalogo_tramites.nombre')
			->get();
		
		// Detalle por trámite ISSSTE
		$tramitesISSSTEDetalle = DB::table('catalogo_tramites_issste')
			->select('catalogo_tramites_issste.nombre', DB::raw('COUNT(clientes.id) as conteo'))
			->leftJoin('clientes', 'clientes.tramite2_id', '=', 'catalogo_tramites_issste.id')
			->where('catalogo_tramites_issste.activo', 1)
			->groupBy('catalogo_tramites_issste.id', 'catalogo_tramites_issste.nombre')
			->get();
		


        // ------------------------------
        // CLIENTES POR MODALIDAD
        // ------------------------------
        $modalidad10 = Cliente::where('modalidad_id', 1)->orWhere('modalidad2_id', 1)->count();
        $modalidad40 = Cliente::where('modalidad_id', 2)->orWhere('modalidad2_id', 2)->count();
        $modalidadCV = Cliente::where('modalidad_id', 3)->orWhere('modalidad2_id', 3)->count();

        // ------------------------------
        // RESUMEN DE ACCIONES
        // ------------------------------
        $documentosPendientes = 0;
        $notificaciones = 0;

        return view('dashboard.index', compact(
            'totalClientes',
            'clientesActivos',
            'clientesBaja',
            'clientesSuspendidos',
            'clientesTerminados',
            'clientesIMSS',
            'clientesISSSTE',
            'clientesMes',
            'clientesRecientes',
            'totalProspectos',
            'prospectosNuevos',
            'prospectosContactados',
            'prospectosInteresados',
            'prospectosBaja',
            'prospectosImposible',
            'prospectosConvertidos',
            'prospectosRecientes',
            'regimen73',
            'regimen97',
            'regimenDT',
            'regimenCI',
            'tramitesIMSS',
            'tramitesISSSTE',
            'tramitesIMSSDetalle',
            'tramitesISSSTEDetalle',
            'modalidad10',
            'modalidad40',
            'modalidadCV',
            'documentosPendientes',
            'notificaciones'
        ));
    }

    /**
     * API para actualizar estadísticas del dashboard (AJAX)
     */
    public function estadisticas()
    {
        $data = [
            // Clientes
            'totalClientes' => Cliente::count(),
            'clientesActivos' => Cliente::where('estatus_cliente_id', 1)->count(),
            'clientesBaja' => Cliente::where('estatus_cliente_id', 2)->count(),
            'clientesSuspendidos' => Cliente::where('estatus_cliente_id', 3)->count(),
            'clientesTerminados' => Cliente::where('estatus_cliente_id', 4)->count(),

            // Prospectos (solo activos)
            'totalProspectos' => Prospecto::where('convertido', 0)->count(),
            'prospectosNuevos' => Prospecto::where('estatus_prospecto_id', 1)->where('convertido', 0)->count(),
            'prospectosContactados' => Prospecto::where('estatus_prospecto_id', 2)->where('convertido', 0)->count(),
            'prospectosInteresados' => Prospecto::where('estatus_prospecto_id', 3)->where('convertido', 0)->count(),
            'prospectosBaja' => Prospecto::where('estatus_prospecto_id', 4)->where('convertido', 0)->count(),
            'prospectosImposible' => Prospecto::where('estatus_prospecto_id', 5)->where('convertido', 0)->count(),
            'prospectosConvertidos' => Prospecto::where('estatus_prospecto_id', 6)->count(),

            // Clientes por institución
            'clientesIMSS' => Cliente::where(function($q){
                $q->where('instituto_id', 1)->orWhere('instituto2_id', 1);
            })->count(),
            'clientesISSSTE' => Cliente::where(function($q){
                $q->where('instituto_id', 2)->orWhere('instituto2_id', 2);
            })->count(),

            // Clientes por régimen
            'regimen73' => Cliente::where('regimen_id', 1)->orWhere('regimen2_id', 1)->count(),
            'regimen97' => Cliente::where('regimen_id', 2)->orWhere('regimen2_id', 2)->count(),
            'regimenDT' => Cliente::where('regimen_id', 3)->orWhere('regimen2_id', 3)->count(),
            'regimenCI' => Cliente::where('regimen_id', 4)->orWhere('regimen2_id', 4)->count(),

            // Clientes por trámite
            'tramitesIMSS' => Cliente::whereNotNull('tramite_id')
                ->whereIn('tramite_id', function($q) {
                    $q->select('id')->from('catalogo_tramites')->where('activo', 1);
                })->count(),
            'tramitesISSSTE' => Cliente::whereNotNull('tramite2_id')
                ->whereIn('tramite2_id', function($q) {
                    $q->select('id')->from('catalogo_tramites_issste')->where('activo', 1);
                })->count(),

            // Detalle de trámites (opcional si quieres actualizar vía AJAX)
            'tramitesIMSSDetalle' => DB::table('catalogo_tramites')
                ->select('nombre', DB::raw('COUNT(clientes.id) as total'))
                ->leftJoin('clientes', 'clientes.tramite_id', '=', 'catalogo_tramites.id')
                ->where('catalogo_tramites.activo', 1)
                ->groupBy('catalogo_tramites.id', 'catalogo_tramites.nombre')
                ->get(),

            'tramitesISSSTEDetalle' => DB::table('catalogo_tramites_issste')
                ->select('nombre', DB::raw('COUNT(clientes.id) as total'))
                ->leftJoin('clientes', 'clientes.tramite2_id', '=', 'catalogo_tramites_issste.id')
                ->where('catalogo_tramites_issste.activo', 1)
                ->groupBy('catalogo_tramites_issste.id', 'catalogo_tramites_issste.nombre')
                ->get(),

            // Clientes por modalidad
            'modalidad10' => Cliente::where('modalidad_id', 1)->orWhere('modalidad2_id', 1)->count(),
            'modalidad40' => Cliente::where('modalidad_id', 2)->orWhere('modalidad2_id', 2)->count(),
            'modalidadCV' => Cliente::where('modalidad_id', 3)->orWhere('modalidad2_id', 3)->count(),

            // Acciones
            'documentosPendientes' => 0,
            'notificaciones' => 0,
        ];

        return response()->json($data);
    }

    /**
     * Perfil de usuario
     */
    public function perfil()
    {
        $user = auth()->user();
        return view('dashboard.perfil', compact('user'));
    }

    /**
     * Actualizar perfil de usuario
     */
    public function actualizarPerfil(Request $request)
    {
        $user = auth()->user();
        
        $request->validate([
            'nombre' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:usuarios,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
        ]);
        
        $user->nombre = $request->nombre;
        $user->email = $request->email;
        
        if ($request->filled('password')) {
            $user->password = bcrypt($request->password);
        }
        
        $user->save();
        
        return redirect()->route('perfil')
            ->with('success', 'Perfil actualizado exitosamente.');
    }
}
