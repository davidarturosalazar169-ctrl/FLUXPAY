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

    public function renderStatus(): JsonResponse
    {
        try {
            DB::select('SELECT 1');

            return response()->json([
                'status' => true,
                'mensaje' => ' SI ESTÁS CONECTADO AL RENDER',
                'database' => DB::connection()->getDatabaseName(),
                'driver' => DB::connection()->getDriverName(),
                'time' => now(),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'mensaje' => '🔴 NO ESTÁS CONECTADO AL RENDER',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    public function drivers(): JsonResponse
{
    return response()->json([
        'php' => PHP_VERSION,
        'pdo' => extension_loaded('pdo'),
        'pdo_pgsql' => extension_loaded('pdo_pgsql'),
        'pgsql' => extension_loaded('pgsql'),
        'drivers' => \PDO::getAvailableDrivers(),
    ]);
}
}