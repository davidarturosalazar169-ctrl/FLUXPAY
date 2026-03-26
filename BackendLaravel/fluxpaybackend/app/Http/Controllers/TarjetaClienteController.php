<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TarjetaCliente;

class TarjetaClienteController extends Controller
{
    public function listado()
    {
        $tarjetas = TarjetaCliente::where('user_id', 1)->get();

        return response()->json($tarjetas);
    }

    public function guardar(Request $r)
    {
        $tarjeta = TarjetaCliente::create([
            'user_id'   => 1, // luego lo haces dinámico con auth
            'brand'     => $r->brand,
            'last4'     => $r->last4,
            'exp_month' => $r->exp_month,
            'exp_year'  => $r->exp_year,
            'name'      => $r->name,
        ]);

        return response()->json([
            'mensaje' => 'Tarjeta guardada',
            'data' => $tarjeta
            
        ]);
        dd($r->all());
    }

    public function eliminar($id)
    {
        $tarjeta = TarjetaCliente::findOrFail($id);
        $tarjeta->delete();

        return response()->json([
            'mensaje' => 'Tarjeta eliminada'
        ]);
    }
}