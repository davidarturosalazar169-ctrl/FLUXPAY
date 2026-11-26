<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\PaymentIntent;

class StripeOxxoController extends Controller
{
    public function crear(Request $request)
    {
        $request->validate([
            'total' => 'required|numeric|min:10|max:10000',
            'email' => 'required|email',
        ]);

        Stripe::setApiKey(config('services.stripe.secret') ?: env('STRIPE_SECRET'));

        $pago = PaymentIntent::create([
            'amount' => (int) round($request->total * 100),
            'currency' => 'mxn',
            'payment_method_types' => ['oxxo'],
            'payment_method_data' => [
                'type' => 'oxxo',
                'billing_details' => [
                    'name' => 'Cliente FLUXPAY',
                    'email' => $request->email,
                ],
            ],
            'confirm' => true,
        ]);

        $ficha = $pago->next_action->oxxo_display_details ?? null;

        return response()->json([
            'estado' => $pago->status,
            'ficha' => $ficha->hosted_voucher_url ?? null,
            'referencia' => $ficha->number ?? null,
        ]);
    }
}
