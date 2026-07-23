<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Stripe\Stripe;
use Stripe\PaymentIntent;


class StripeQRController extends Controller
{

    public function crearCheckout(Request $request)
    {

        Stripe::setApiKey(env('STRIPE_SECRET'));


        $productos = $request->productos;


        // Total en centavos para Stripe
        $monto = $request->total * 100;



        $paymentIntent = PaymentIntent::create([

            'amount' => $monto,

            'currency' => 'mxn',

            'payment_method_types' => [

                'card'

            ],


'metadata' => [

    'idnegocio' => $request->idnegocio,

    'iduser' => $request->iduser,

    'pedido_id' => $request->pedido_id,

    'productos' => json_encode($productos)

]
        ]);




        return response()->json([

            'client_secret' => $paymentIntent->client_secret,

            'payment_intent_id' => $paymentIntent->id

        ]);


    }
public function confirmarPago(Request $request)
{

    Stripe::setApiKey(env('STRIPE_SECRET'));


    $paymentIntent = PaymentIntent::retrieve(
        $request->payment_intent
    );


    if($paymentIntent->status != "succeeded"){

        return response()->json([

            "status" => $paymentIntent->status

        ]);

    }



    // Verificar si ya existe este pago
    $existe = DB::table('movimiento')
        ->where(
            'stripe_payment_intent',
            $paymentIntent->id
        )
        ->first();



    if($existe){

        return response()->json([

            "status" => "already_saved",

            "movimiento_id" => $existe->id

        ]);

    }




    $metadata = $paymentIntent->metadata;



    $productos = json_decode(
        $metadata->productos,
        true
    );




    // Crear movimiento UNA SOLA VEZ

    $movimiento = DB::table('movimiento')->insertGetId([

        'idnegocio' => $metadata->idnegocio,

        'iduser' => $metadata->iduser,

        'monto_total' => $paymentIntent->amount / 100,

        'comision' => 0,

        'metodo_pago' => 'tarjeta',

        'referencia_pago' => $paymentIntent->id,

        'stripe_payment_intent' => $paymentIntent->id,

        'stripe_status' => $paymentIntent->status,

        'status' => 1,

        'fecha_movimiento' => now(),

        'created_at' => now(),

        'updated_at' => now()

    ]);





    foreach($productos as $producto){


        DB::table('detalle_movimientos')->insert([


            'idmovimiento' => $movimiento,


            'idproducto' => $producto['idproducto'],


            'cantidad' => $producto['cantidad'],


            'precio_unitario' => $producto['precio'],


            'subtotal' => $producto['subtotal'],


            'status' => 1,


            'created_at' => now(),

            'updated_at' => now()


        ]);


    }




    return response()->json([


        "status" => "succeeded",

        "movimiento_id" => $movimiento


    ]);


}

}