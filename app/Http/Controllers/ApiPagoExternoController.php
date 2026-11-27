<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ApiKeyNegocio;
use App\Models\PedidoApiKey;
use Stripe\Stripe;
use Stripe\PaymentIntent;

class ApiPagoExternoController extends Controller
{
    public function crearPago(Request $request)
    {
        // Validar datos recibidos
        $datos = $request->validate([
            'pedido_id' => 'required|integer',
        ]);

        // Obtener API Key
        $apiKey = $request->header('X-API-KEY');

        if (!$apiKey) {
            return response()->json([
                'success' => false,
                'mensaje' => 'API Key no proporcionada'
            ], 401);
        }

        // Validar API Key
        $clave = ApiKeyNegocio::where(
            'api_key_hash',
            hash('sha256', $apiKey)
        )->where('activa', true)->first();

        if (!$clave) {
            return response()->json([
                'success' => false,
                'mensaje' => 'API Key inválida o revocada'
            ], 401);
        }

        // Buscar pedido perteneciente a esta API Key
        $pedido = PedidoApiKey::where('id', $datos['pedido_id'])
            ->where('api_key_negocio_id', $clave->id)
            ->where('status', 'pendiente')
            ->first();

        if (!$pedido) {
            return response()->json([
                'success' => false,
                'mensaje' => 'Pedido no encontrado o no disponible'
            ], 404);
        }

        try {
            Stripe::setApiKey(env('STRIPE_SECRET'));

            $paymentIntent = PaymentIntent::create([
                'amount' => (int) round($pedido->monto * 100),
                'currency' => 'mxn',
                'payment_method_types' => ['card'],
                'metadata' => [
                    'tipo' => 'pago_externo',
                    'pedido_api_id' => $pedido->id,
                    'negocio_externo_id' => $pedido->negocio_externo_id,
                    'referencia_externa' => $pedido->referencia_externa,
                ],
            ]);

            $pedido->update([
                'stripe_payment_intent' => $paymentIntent->id,
            ]);

            $clave->update([
                'ultimo_uso' => now(),
            ]);

            return response()->json([
                'success' => true,
                'mensaje' => 'Pago generado correctamente',
                'pedido_id' => $pedido->id,
                'monto' => $pedido->monto,
'client_secret' => $paymentIntent->client_secret,
'payment_intent_id' => $paymentIntent->id,
'checkout_url' => rtrim(env('FRONTEND_URL'), '/') .
    '/pagar-pedido-externo?pedido_id=' . $pedido->id,
'status' => $paymentIntent->status,
            ]);

        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'mensaje' => 'Error al generar el pago con Stripe',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

public function confirmarPago(Request $request)
{
    $datos = $request->validate([
        'pedido_id' => 'required|integer',
    ]);

    $pedido = PedidoApiKey::find($datos['pedido_id']);

    if (!$pedido || !$pedido->stripe_payment_intent) {
        return response()->json([
            'success' => false,
            'mensaje' => 'Pedido o pago no encontrado'
        ], 404);
    }

    try {
        Stripe::setApiKey(env('STRIPE_SECRET'));

        $paymentIntent = PaymentIntent::retrieve(
            $pedido->stripe_payment_intent
        );

        if (
            $paymentIntent->status !== 'succeeded' ||
            (int) $paymentIntent->amount !== (int) round($pedido->monto * 100) ||
            $paymentIntent->currency !== 'mxn' ||
            $paymentIntent->metadata->pedido_api_id != $pedido->id
        ) {
            return response()->json([
                'success' => false,
                'mensaje' => 'El pago todavía no está confirmado',
                'status' => $paymentIntent->status
            ], 400);
        }

        $pedido->update([
            'status' => 'pagado'
        ]);

        return response()->json([
            'success' => true,
            'mensaje' => 'Pago confirmado correctamente',
            'pedido_id' => $pedido->id,
            'referencia_externa' => $pedido->referencia_externa,
            'status' => 'pagado'
        ]);

    } catch (\Throwable $e) {
        return response()->json([
            'success' => false,
            'mensaje' => 'Error al verificar el pago'
        ], 500);
    }
}

public function consultarEstado($id)
{
    $apiKey = request()->header('X-API-KEY');

    $clave = ApiKeyNegocio::where(
        'api_key_hash',
        hash('sha256', $apiKey ?? '')
    )->where('activa', true)->first();

    if (!$clave) {
        return response()->json([
            'success' => false,
            'mensaje' => 'API Key inválida'
        ], 401);
    }

    $pedido = PedidoApiKey::where('id', $id)
        ->where('api_key_negocio_id', $clave->id)
        ->first();

    if (!$pedido) {
        return response()->json([
            'success' => false,
            'mensaje' => 'Pedido no encontrado'
        ], 404);
    }

    return response()->json([
        'success' => true,
        'pedido_id' => $pedido->id,
        'referencia_externa' => $pedido->referencia_externa,
        'monto' => $pedido->monto,
        'status' => $pedido->status
    ]);
}

public function obtenerPago($id)
{
    $pedido = PedidoApiKey::find($id);

    if (!$pedido || !$pedido->stripe_payment_intent) {
        return response()->json([
            'success' => false,
            'mensaje' => 'Pago no encontrado'
        ], 404);
    }

    try {
        Stripe::setApiKey(env('STRIPE_SECRET'));

        $paymentIntent = PaymentIntent::retrieve(
            $pedido->stripe_payment_intent
        );

        return response()->json([
            'success' => true,
            'pedido_id' => $pedido->id,
            'monto' => $pedido->monto,
            'client_secret' => $paymentIntent->client_secret,
            'status' => $paymentIntent->status
        ]);

    } catch (\Throwable $e) {
        return response()->json([
            'success' => false,
            'mensaje' => 'Error al recuperar el pago'
        ], 500);
    }
}

}