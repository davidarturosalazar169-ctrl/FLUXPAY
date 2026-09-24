<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Exception;

class TiendaClienteDashController extends Controller
{
    /**
     * Función privada para obtener el ID real del negocio.
     * Basado en tu tabla 'negocio', buscamos el 'id' donde 'iduser' sea el del usuario actual.
     */
    private function getNegocioId(Request $request) 
    {
        $user = $request->user();
        
        if (!$user) {
            throw new \Exception("Usuario no autenticado.");
        }

        $negocio = DB::table('negocio')->where('iduser', $user->id)->first();
        
        if (!$negocio) {
            throw new \Exception("El usuario ID: " . $user->id . " no tiene nada en la tabla 'negocio' bajo la columna 'iduser'");
        }

        return $negocio->id;
    }

    // 1. PRODUCTOS: Basado en tu tabla 'productos'
    public function productos(Request $request)
    {
        try {
            $idNegocio = $this->getNegocioId($request);

            if (!$idNegocio) {
                return response()->json(['error' => 'No se encontró un negocio asociado'], 404);
            }

            $data = DB::table('productos')
                ->select('nombre as name', 'status as units', 'precio as income')
                ->where('idnegocio', $idNegocio) 
                ->get();

            return response()->json($data);
        } catch (Exception $e) {
            return response()->json(['error' => 'Error en Productos: ' . $e->getMessage()], 500);
        }
    }

    // 2. INGRESOS: Basado en tu tabla 'movimiento' (Para las gráficas)
    public function ingresos(Request $request)
    {
        try {
            $idNegocio = $this->getNegocioId($request);

            if (!$idNegocio) return response()->json([], 200);

            $data = DB::table('movimiento')
                ->select('metodo_pago', DB::raw('SUM(monto_total) as total'))
                ->where('idnegocio', $idNegocio)
                ->groupBy('metodo_pago')
                ->get();

            return response()->json($data);
        } catch (Exception $e) {
            return response()->json(['error' => 'Error en Ingresos: ' . $e->getMessage()], 500);
        }
    }

    // 3. RESUMEN: Calcula totales para las cards del dashboard incluyendo los 4 métodos
    public function resumen(Request $request)
    {
        try {
            $idNegocio = $this->getNegocioId($request);
            if (!$idNegocio) return response()->json(['total' => 0, 'efectivo' => 0, 'qr' => 0, 'tarjeta' => 0, 'transferencia' => 0]);

            // Agrupamos y obtenemos todo en una sola consulta para optimizar rendimiento
            $totalesPorMetodo = DB::table('movimiento')
                ->select('metodo_pago', DB::raw('SUM(monto_total) as total'))
                ->where('idnegocio', $idNegocio)
                ->groupBy('metodo_pago')
                ->get()
                ->keyBy(function($item) {
                    return strtolower($item->metodo_pago);
                });

            // Mapeamos controlando minúsculas/mayúsculas (soporta 'qr' y 'QR')
            $efectivo = (float)($totalesPorMetodo->get('efectivo')->total ?? 0);
            $qr = (float)($totalesPorMetodo->get('qr')->total ?? $totalesPorMetodo->get('QR')->total ?? 0);
            $tarjeta = (float)($totalesPorMetodo->get('tarjeta')->total ?? 0);
            $transferencia = (float)($totalesPorMetodo->get('transferencia')->total ?? 0);

            $totalGeneral = $efectivo + $qr + $tarjeta + $transferencia;

            return response()->json([
                'total' => $totalGeneral,
                'efectivo' => $efectivo,
                'qr' => $qr,
                'tarjeta' => $tarjeta,
                'transferencia' => $transferencia
            ]);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}