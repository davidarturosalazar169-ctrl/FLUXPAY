<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class RenderControllerPrueba extends Controller
{
    public function ping(): JsonResponse
    {
        try {
            DB::connection()->getPdo();

            return response()->json([
                'status' => true,
                'mensaje' => 'Conexión exitosa',
                'database' => DB::connection()->getDatabaseName(),
                'driver' => DB::connection()->getDriverName(),
                'host' => env('DB_HOST'),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'mensaje' => 'Error de conexión',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}