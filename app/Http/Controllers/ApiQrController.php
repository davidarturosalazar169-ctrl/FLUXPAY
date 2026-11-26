<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
    return response()->json([
        'success' => true,
        'mensaje' => 'POST recibido correctamente desde ImpulsaPay',
        'datos' => $request->all(),
    ]);
}
}
