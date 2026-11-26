<?php

namespace App\Http\Controllers;

use App\Models\ApiKeyNegocio;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ApiKeyController extends Controller
{
    public function generar(Request $request)
    {
        $request->validate([
            'negocio_externo_id' => 'required|string|max:100',
            'nombre' => 'required|string|max:100',
        ]);

        // Generar clave única
        $apiKey = 'ip_' . Str::random(60);

        // Guardar únicamente el hash
        ApiKeyNegocio::create([
            'negocio_externo_id' => $request->negocio_externo_id,
            'nombre' => $request->nombre,
            'api_key_hash' => hash('sha256', $apiKey),
            'activa' => true,
        ]);

        // Mostrar la clave solamente al generarla
        return response()->json([
            'success' => true,
            'mensaje' => 'API Key generada correctamente',
            'api_key' => $apiKey,
        ], 201);
    }

public function revocar($id)
{
    $apiKey = ApiKeyNegocio::findOrFail($id);

    $apiKey->update([
        'activa' => false,
    ]);

    return response()->json([
        'success' => true,
        'mensaje' => 'API Key revocada correctamente',
    ]);
}


public function listar(Request $request)
{
    $request->validate([
        'negocio_externo_id' => 'nullable|string|max:100',
    ]);

    $consulta = ApiKeyNegocio::query();

    if ($request->filled('negocio_externo_id')) {
        $consulta->where(
            'negocio_externo_id',
            $request->negocio_externo_id
        );
    }

    $apiKeys = $consulta->latest()->get();

    return response()->json([
        'success' => true,
        'data' => $apiKeys,
    ]);
}

}
