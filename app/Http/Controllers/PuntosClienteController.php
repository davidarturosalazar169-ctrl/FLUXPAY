<?php

namespace App\Http\Controllers;

use App\Models\ClienteNegocio;
use App\Models\ConfiguracionPuntos;
use App\Models\Negocio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PuntosClienteController extends Controller
{
    /**
     * Obtener el negocio del usuario autenticado.
     */
private function obtenerNegocio()
{
    $usuario = auth('sanctum')->user();

    if (!$usuario) {
        return null;
    }

    return Negocio::where('iduser', $usuario->id)->first();
}

    /**
     * Consultar los puntos disponibles de un cliente.
     */
    public function consultar($id)
    {
        $cliente = ClienteNegocio::find($id);

        if (!$cliente) {
            return response()->json([
                'message' => 'Cliente no encontrado.'
            ], 404);
        }

        return response()->json([
            'cliente_id' => $cliente->id,
            'nombreCliente' => $cliente->nombreCliente,
            'puntos' => (int) $cliente->puntos,
        ]);
    }

    /**
     * Consultar la configuración de puntos del negocio.
     */
    public function consultarConfiguracion()
    {
        $negocio = $this->obtenerNegocio();

        if (!$negocio) {
            return response()->json([
                'message' => 'No se encontró el negocio.'
            ], 404);
        }

        $configuracion = ConfiguracionPuntos::where(
            'idnegocio',
            $negocio->id
        )->first();

        if (!$configuracion) {
            return response()->json([
                'message' => 'El negocio todavía no tiene una configuración de puntos.'
            ], 404);
        }

        return response()->json($configuracion);
    }

    /**
     * Guardar o actualizar la configuración de puntos.
     */
    
public function guardarConfiguracion(Request $request)
{
    $datos = $request->validate([
        'puntos_por_compra' => ['required', 'integer', 'min:1'],
        'puntos_para_canjear' => ['required', 'integer', 'min:1'],
        'cantidad_de_pesos' => ['required', 'numeric', 'min:0.01'],
    ]);

    $negocio = $this->obtenerNegocio();

    if (!$negocio) {
        return response()->json(['message' => 'No se encontró el negocio.'], 404);
    }

    $configuracion = ConfiguracionPuntos::updateOrCreate(
        ['idnegocio' => $negocio->id],
        [
            'puntos_por_compra' => $datos['puntos_por_compra'],
            'puntos_para_canjear' => $datos['puntos_para_canjear'],
            'cantidad_de_pesos' => $datos['cantidad_de_pesos'],
        ]
    );

    return response()->json([
        'message' => 'Configuración de puntos guardada correctamente.',
        'configuracion' => $configuracion
    ]);
}


    /**
     * Acumular puntos según la configuración del negocio.
     */
    public function acumular(Request $request)
    {
        $datos = $request->validate([
            'cliente_id' => ['required', 'integer', 'min:1'],
        ]);

        return DB::transaction(function () use ($datos) {
            $cliente = ClienteNegocio::where(
                'id',
                $datos['cliente_id']
            )->lockForUpdate()->first();

            if (!$cliente) {
                return response()->json([
                    'message' => 'Cliente no encontrado.'
                ], 404);
            }

            $configuracion = ConfiguracionPuntos::where(
                'idnegocio',
                $cliente->idnegocio
            )->first();

            if (!$configuracion) {
                return response()->json([
                    'message' => 'El negocio no tiene configurada su equivalencia de puntos.'
                ], 422);
            }

$puntosAgregados = (int) $configuracion->puntos_por_compra;

            $cliente->puntos = (int) $cliente->puntos + $puntosAgregados;
            $cliente->save();

            return response()->json([
                'message' => 'Puntos acumulados correctamente.',
                'cliente_id' => $cliente->id,
                'puntos_agregados' => $puntosAgregados,
                'puntos_disponibles' => (int) $cliente->puntos,
            ]);
        });
    }

    /**
     * Canjear puntos según la configuración del negocio.
     */
    public function canjear(Request $request)
    {
        $datos = $request->validate([
            'cliente_id' => ['required', 'integer', 'min:1'],
            'puntos' => ['required', 'integer', 'min:1'],
        ]);

        return DB::transaction(function () use ($datos) {
            $cliente = ClienteNegocio::where(
                'id',
                $datos['cliente_id']
            )->lockForUpdate()->first();

            if (!$cliente) {
                return response()->json([
                    'message' => 'Cliente no encontrado.'
                ], 404);
            }

            $configuracion = ConfiguracionPuntos::where(
                'idnegocio',
                $cliente->idnegocio
            )->first();

            if (!$configuracion) {
                return response()->json([
                    'message' => 'El negocio no tiene configurada su equivalencia de puntos.'
                ], 422);
            }

            $puntosActuales = (int) $cliente->puntos;
            $puntosCanjeados = (int) $datos['puntos'];
            $puntosEquivalentes = (int) $configuracion->puntos_para_canjear;

            if ($puntosCanjeados % $puntosEquivalentes !== 0) {
                return response()->json([
                    'message' => 'Los puntos deben canjearse en múltiplos de '
                        . $puntosEquivalentes . '.'
                ], 422);
            }

            if ($puntosActuales < $puntosCanjeados) {
                return response()->json([
                    'message' => 'El cliente no tiene suficientes puntos.',
                    'puntos_disponibles' => $puntosActuales,
                ], 422);
            }

            $descuento = (
                $puntosCanjeados / $puntosEquivalentes
            ) * (float) $configuracion->cantidad_de_pesos;

            $cliente->puntos = $puntosActuales - $puntosCanjeados;
            $cliente->save();

            return response()->json([
                'message' => 'Puntos canjeados correctamente.',
                'cliente_id' => $cliente->id,
                'puntos_canjeados' => $puntosCanjeados,
                'descuento_mxn' => round($descuento, 2),
                'puntos_disponibles' => (int) $cliente->puntos,
            ]);
        });
    }
}
