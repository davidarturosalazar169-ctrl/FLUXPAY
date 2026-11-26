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

}
