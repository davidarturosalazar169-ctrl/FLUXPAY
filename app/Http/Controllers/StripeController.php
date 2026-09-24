<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use Stripe\Customer;
use Stripe\PaymentMethod;
use Illuminate\Support\Facades\DB;

class StripeController extends Controller
{
    /**
     * Paso 1: Inicializar o procesar el pago directamente
     */
    public function createPayment(Request $request)
    {
        // Forzar formato JSON
        Stripe::setApiKey(env('STRIPE_SECRET'));

        // El monto total enviado por el frontend (ej: 150.50). Stripe requiere enteros en centavos.
        $montoOriginal = $request->amount; 
        $amountInCents = Math.round($montoOriginal * 100); 
        $iduser = $request->iduser ?? 1; // ID del cliente (ficticio o autenticado)

        // 1. Buscar o crear el Customer de Stripe asociado a tu usuario de la DB
        $user = DB::table('users')->where('id', $iduser)->first();
        
        // Si no tienes la columna en tu tabla de usuarios, asegúrate de crearla en una migración
        $stripeCustomerId = $user->stripe_customer_id ?? null;

        if (!$stripeCustomerId) {
            $customer = Customer::create([
                'email' => $user->email ?? "cliente_{$iduser}@fluxapay.com",
                'metadata' => ['iduser' => $iduser]
            ]);
            $stripeCustomerId = $customer->id;

            // Guardar el id de Stripe en tu tabla local de usuarios
            DB::table('users')->where('id', $iduser)->update(['stripe_customer_id' => $stripeCustomerId]);
        }

        // 2. Comprobar si este cliente ya guardó alguna tarjeta anteriormente
        $paymentMethods = PaymentMethod::all([
            'customer' => $stripeCustomerId,
            'type' => 'card',
        ]);

        // CASO A: EL CLIENTE YA TIENE TARJETA GUARDADA (Pago Express sin formulario)
        if (count($paymentMethods->data) > 0) {
            try {
                $paymentMethodId = $paymentMethods->data[0]->id; // Tomamos la última tarjeta guardada

                $paymentIntent = PaymentIntent::create([
                    'amount' => $amountInCents,
                    'currency' => 'mxn',
                    'customer' => $stripeCustomerId,
                    'payment_method' => $paymentMethodId,
                    'off_session' => true, // Cobro sin que el usuario reescriba datos
                    'confirm' => true,      // Liquidar cargo inmediatamente
                ]);

                return response()->json([
                    'requiere_formulario' => false,
                    'status' => $paymentIntent->status,
                    'payment_intent_id' => $paymentIntent->id
                ]);

            } catch (\Stripe\Exception\CardException $e) {
                // Si la tarjeta guardada es rechazada, expiró, etc., caemos en pedir formulario nuevo
                return response()->json([
                    'requiere_formulario' => true,
                    'error_tarjeta_guardada' => $e->getMessage()
                ]);
            }
        }

        // CASO B: EL CLIENTE NO TIENE TARJETA (Generar pasarela para que la registre por primera vez)
        $paymentIntent = PaymentIntent::create([
            'amount' => $amountInCents,
            'currency' => 'mxn',
            'customer' => $stripeCustomerId,
            'setup_future_usage' => 'off_session', // <<< ESTO GUARDA LA TARJETA AUTOMÁTICAMENTE TRAS PAGAR
            'automatic_payment_methods' => [
                'enabled' => true,
            ],
        ]);

        return response()->json([
            'requiere_formulario' => true,
            'client_secret' => $paymentIntent->client_secret,
            'payment_intent_id' => $paymentIntent->id
        ]);
    }

    /**
     * Paso 2: Registrar la venta en tu base de datos (Tu función original adaptada)
     */
    public function saveMovimiento(Request $request)
    {
        $movimientoId = DB::table('movimiento')->insertGetId([
            'idnegocio' => $request->idnegocio ?? 1,
            'iduser' => $request->iduser ?? 1,
            'monto_total' => $request->monto_total,
            'comision' => $request->comision ?? 0,
            'metodo_pago' => 'stripe',
            'referencia_pago' => 'ref_' . time(),
            'fecha_movimiento' => now(),
            'status' => 1, // Exitoso directo ya que viene validado de stripe

            'stripe_payment_intent' => $request->payment_intent,
            'stripe_charge_id' => $request->charge_id ?? null,
            'stripe_status' => $request->stripe_status ?? 'succeeded',

            'created_at' => now(),
            'updated_at' => now()
        ]);

        // Agregar los detalles del carrito que mandes en el request
        if ($request->has('productos')) {
            foreach ($request->productos as $p) {
                DB::table('detalle_movimientos')->insert([
                    'idmovimiento' => $movimientoId,
                    'idproducto' => $p['id'],
                    'cantidad' => $p['cant'],
                    'precio_unitario' => $p['precio'],
                    'subtotal' => $p['precio'] * $p['cant'],
                    'status' => 1,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Movimiento guardado correctamente en la BD local',
            'id' => $movimientoId
        ]);
    }
}