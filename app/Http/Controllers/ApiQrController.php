<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PedidoApiKey;
use App\Models\ApiKeyNegocio;   
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
    // Validar los datos recibidos
    $datos = $request->validate([
        'monto' => 'required|numeric|min:1',
        'referencia_externa' => 'required|string|max:100',
    ]);

    // Obtener API Key enviada en los encabezados
    $apiKey = $request->header('X-API-KEY');

    if (!$apiKey) {
        return response()->json([
            'success' => false,
            'mensaje' => 'API Key no proporcionada'
        ], 401);
    }

    // Buscar la API Key mediante su hash
    $apiKeyHash = hash('sha256', $apiKey);

    $clave = ApiKeyNegocio::where('api_key_hash', $apiKeyHash)
        ->where('activa', true)
        ->first();

    if (!$clave) {
        return response()->json([
            'success' => false,
            'mensaje' => 'API Key inválida o revocada'
        ], 401);
    }

    // Actualizar último uso
    $clave->update([
        'ultimo_uso' => now()
    ]);

    // Crear pedido externo
    $pedido = PedidoApiKey::create([
        'api_key_negocio_id' => $clave->id,
        'negocio_externo_id' => $clave->negocio_externo_id,
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
