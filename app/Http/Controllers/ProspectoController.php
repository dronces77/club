<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProspectoController extends Controller
{
    /**
     * Mostrar lista de prospectos (tipo_cliente != 'C')
     */
    public function index()
    {
        // ✅ SOLO registros donde tipo_cliente != 'C' (Prospecto, Imposible, Baja, Suspendido)
        $prospectos = Cliente::where('tipo_cliente', '!=', 'C')
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        return view('prospectos.index', compact('prospectos'));
    }

    /**
     * Actualizar tipo de cliente (convertir prospecto → cliente)
     */
    public function actualizarTipo(Request $request, $id)
    {
        $request->validate([
            'tipo_cliente' => 'required|in:P,C,I,B,S',
        ]);
        
        $cliente = Cliente::findOrFail($id);
        
        // Verificar si ya es cliente (no puede cambiar de tipo)
        if ($cliente->tipo_cliente === 'C') {
            return redirect()->route('prospectos.index')
                ->with('error', 'Los clientes no pueden cambiar su tipo. Solo pueden modificar su estatus.');
        }
        
        $tipoOriginal = $cliente->tipo_cliente;
        
        // Iniciar transacción
        DB::beginTransaction();
        
        try {
            // Actualizar tipo de cliente
            $cliente->tipo_cliente = $request->tipo_cliente;
            
            // Si se convierte a Cliente
            if ($request->tipo_cliente === 'C' && $tipoOriginal !== 'C') {
                // El modelo ya maneja la generación de no_cliente y estatus=Activo
                // gracias al evento updating en el modelo
                $mensaje = '¡Prospecto convertido a Cliente exitosamente!';
            } 
            // Si cambia a otro tipo que no sea Cliente
            else {
                $cliente->estatus = null; // No clientes no tienen estatus
                $mensaje = 'Tipo actualizado a: ' . $cliente->tipo_cliente_texto;
            }
            
            $cliente->save();
            
            DB::commit();
            
            // Mensaje adicional si se convirtió a cliente
            if ($request->tipo_cliente === 'C') {
                $mensaje .= ' Número de cliente: ' . $cliente->no_cliente;
            }
            
            return redirect()->route('prospectos.index')
                ->with('success', $mensaje);
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->route('prospectos.index')
                ->with('error', 'Error al actualizar: ' . $e->getMessage());
        }
    }

    /**
     * Obtener estadísticas de prospectos (tipo_cliente != 'C')
     */
    public function estadisticas()
    {
        $estadisticas = Cliente::selectRaw("
            tipo_cliente,
            COUNT(*) as total,
            MIN(created_at) as mas_antiguo,
            MAX(created_at) as mas_reciente
        ")
        ->where('tipo_cliente', '!=', 'C') // ✅ SOLO no clientes
        ->groupBy('tipo_cliente')
        ->get();
        
        return response()->json([
            'estadisticas' => $estadisticas,
            'total_prospectos' => Cliente::where('tipo_cliente', '!=', 'C')->count(),
            'success' => true
        ]);
    }
}