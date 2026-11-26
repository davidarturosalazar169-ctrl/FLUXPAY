<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PedidoApiKey;
use Illuminate\Support\Facades\DB;

class ApiQrController extends Controller
{
    public function prueba()
    {
        return response()->json([
            'success' => true,
            'mensaje' => 'API QR de ImpulsaPay funcionando correctamente'
        ]);
    }

public function recibirSolicitud(Request $request)
{
    $datos = $request->validate([
        'monto' => 'required|numeric|min:1',
        'referencia_externa' => 'required|string|max:100',
    ]);

    return response()->json([
        'success' => true,
        'mensaje' => 'Solicitud recibida y validada correctamente',
        'datos_recibidos' => $datos,
    ]);
}

public function crear(Request $request)
{
    $datos = $request->validate([
        'monto' => 'required|numeric|min:1',
        'referencia_externa' => 'required|string|max:100',
        'negocio_externo_id' => 'required|string|max:100',
    ]);

    $pedido = PedidoApiKey::create([
        'api_key_negocio_id' => 1,
        'negocio_externo_id' => $datos['negocio_externo_id'],
        'referencia_externa' => $datos['referencia_externa'],
        'monto' => $datos['monto'],
        'status' => 'pendiente',
    ]);

    return response()->json([
        'success' => true,
        'mensaje' => 'Pedido externo creado correctamente',
        'pedido_id' => $pedido->id,
        'monto' => $pedido->monto,
        'referencia_externa' => $pedido->referencia_externa,
        'status' => $pedido->status,
    ], 201);
}


}
