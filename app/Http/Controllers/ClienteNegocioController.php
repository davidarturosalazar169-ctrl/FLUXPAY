<?php

namespace App\Http\Controllers;

use App\Models\ClienteNegocio;
use App\Models\Negocio;
use Illuminate\Http\Request;

class ClienteNegocioController extends Controller
{
    private function obtenerNegocioUsuario(Request $request)
    {
        // Configuración temporal para pruebas con el usuario 2.
        return Negocio::where('iduser', 2)->first();
    }

    // CONSULTAR: listar clientes del negocio.
    public function index(Request $request)
    {
        $negocio = $this->obtenerNegocioUsuario($request);

        if (!$negocio) {
            return response()->json([
                'error' => 'El usuario no tiene un negocio asociado.',
            ], 404);
        }

        $clientes = ClienteNegocio::where('idnegocio', $negocio->id)
            ->orderBy('nombreCliente')
            ->get();

        return response()->json($clientes);
    }

    // ALTA: registrar un cliente.
    public function store(Request $request)
    {
        $negocio = $this->obtenerNegocioUsuario($request);

        if (!$negocio) {
            return response()->json([
                'error' => 'El usuario no tiene un negocio asociado.',
            ], 404);
        }

        $validated = $request->validate([
            'nombreCliente' => 'required|string|max:255',
            'telefono' => 'nullable|string|max:20',
        ]);

        $cliente = ClienteNegocio::create([
            'nombreCliente' => $validated['nombreCliente'],
            'telefono' => $validated['telefono'] ?? null,
            'idnegocio' => $negocio->id,
            'puntos' => 0,
        ]);

        return response()->json([
            'mensaje' => 'Cliente registrado correctamente.',
            'cliente' => $cliente,
        ], 201);
    }

    // MODIFICAR: actualizar nombre o teléfono.
    public function update(Request $request, $id)
    {
        $negocio = $this->obtenerNegocioUsuario($request);

        if (!$negocio) {
            return response()->json([
                'error' => 'El negocio no existe.',
            ], 404);
        }

        $cliente = ClienteNegocio::where('id', $id)
            ->where('idnegocio', $negocio->id)
            ->first();

        if (!$cliente) {
            return response()->json([
                'error' => 'Cliente no encontrado en este negocio.',
            ], 404);
        }

        $validated = $request->validate([
            'nombreCliente' => 'sometimes|required|string|max:255',
            'telefono' => 'sometimes|nullable|string|max:20',
        ]);

        $cliente->fill($validated);
        $cliente->save();

        return response()->json([
            'mensaje' => 'Cliente actualizado correctamente.',
            'cliente' => $cliente,
        ]);
    }

    // BAJA: eliminar un cliente del negocio.
    public function destroy(Request $request, $id)
    {
        $negocio = $this->obtenerNegocioUsuario($request);

        if (!$negocio) {
            return response()->json([
                'error' => 'El negocio no existe.',
            ], 404);
        }

        $cliente = ClienteNegocio::where('id', $id)
            ->where('idnegocio', $negocio->id)
            ->first();

        if (!$cliente) {
            return response()->json([
                'error' => 'Cliente no encontrado en este negocio.',
            ], 404);
        }

        $cliente->delete();

        return response()->json([
            'mensaje' => 'Cliente eliminado correctamente.',
        ]);
    }
}