<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Stripe\Stripe;
use Stripe\PaymentIntent;


class StripeQRController extends Controller
{


    // CREAR PEDIDO ANTES DEL QR
    public function crearPedido(Request $request)
    {


        $pedido = DB::table('pedidos')->insertGetId([

            'negocio_id' => $request->idnegocio,

            'user_id' => $request->iduser,

            'total' => $request->total,

            'status' => 'pendiente',

            'created_at' => now(),

            'updated_at' => now()

        ]);




        foreach($request->productos as $producto){


            DB::table('pedido_detalles')->insert([


                'pedido_id' => $pedido,


                'producto_nombre' => $producto['nombre'],


                'cantidad' => $producto['cantidad'],


                'precio' => $producto['precio'],


                'created_at' => now(),


                'updated_at' => now()


            ]);


        }



        return response()->json([

            "pedido_id" => $pedido

        ]);

    }





    // OBTENER PEDIDO DESDE EL QR
    public function obtenerPedido($id)
    {


        $pedido = DB::table('pedidos')
            ->where('id',$id)
            ->first();



        $productos = DB::table('pedido_detalles')
            ->where('pedido_id',$id)
            ->get();



        return response()->json([

            "pedido"=>$pedido,

            "productos"=>$productos

        ]);


    }






    public function crearCheckout(Request $request)
    {

        Stripe::setApiKey(env('STRIPE_SECRET'));


        $productos = $request->productos;


        $monto = $request->total * 100;



        $paymentIntent = PaymentIntent::create([


            'amount'=>$monto,


            'currency'=>'mxn',


            'payment_method_types'=>[

                'card'

            ],



            'metadata'=>[


                'idnegocio'=>$request->idnegocio,


                'iduser'=>$request->iduser,


                'pedido_id'=>$request->pedido_id,


                'productos'=>json_encode($productos)


            ]


        ]);




        return response()->json([

            'client_secret'=>$paymentIntent->client_secret,

            'payment_intent_id'=>$paymentIntent->id

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

                "status"=>$paymentIntent->status

            ]);


        }



        $existe = DB::table('movimiento')
        ->where(
            'stripe_payment_intent',
            $paymentIntent->id
        )
        ->first();



        if($existe){


            return response()->json([

                "status"=>"already_saved",

                "movimiento_id"=>$existe->id

            ]);


        }



        $metadata = $paymentIntent->metadata;



        $productos=json_decode(

            $metadata->productos,

            true

        );




        $movimiento=DB::table('movimiento')->insertGetId([


            'idnegocio'=>$metadata->idnegocio,


            'iduser'=>$metadata->iduser,


            'monto_total'=>$paymentIntent->amount / 100,


            'comision'=>0,


            'metodo_pago'=>'tarjeta',


            'referencia_pago'=>$paymentIntent->id,


            'stripe_payment_intent'=>$paymentIntent->id,


            'stripe_status'=>$paymentIntent->status,


            'status'=>1,


            'fecha_movimiento'=>now(),


            'created_at'=>now(),


            'updated_at'=>now()


        ]);





        foreach($productos as $producto){



            DB::table('detalle_movimientos')->insert([


                'idmovimiento'=>$movimiento,


                'idproducto'=>$producto['idproducto'],


                'cantidad'=>$producto['cantidad'],


                'precio_unitario'=>$producto['precio'],


                'subtotal'=>$producto['subtotal'],


                'status'=>1,


                'created_at'=>now(),


                'updated_at'=>now()


            ]);


        }




        return response()->json([

            "status"=>"succeeded",

            "movimiento_id"=>$movimiento

        ]);



    }


}