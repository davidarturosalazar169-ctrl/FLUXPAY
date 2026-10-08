<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Negocio;
use App\Models\Producto;
use App\Models\Inventario;
use Illuminate\Support\Facades\DB;

class ProductoController extends Controller
{
    private function negocioDelUsuario(Request $request): Negocio
    {
        abort_unless((int) $request->user()->idrol === 8, 403, 'Solo los negocios pueden administrar productos.');

        return Negocio::where('iduser', $request->user()->id)->firstOrFail();
    }

    public function index(Request $request)
    {
        $negocio = $this->negocioDelUsuario($request);

        return response()->json(
            Producto::with('marca')->where('idnegocio', $negocio->id)->get()
        );
    }

    public function update(Request $request, $id)
    {
        $negocio = $this->negocioDelUsuario($request);
        $producto = Producto::where('idnegocio', $negocio->id)->findOrFail($id);
        $validated = $request->validate([
            'nombre' => 'required|string|max:150',
            'idmarca' => 'required|integer|exists:marca,id',
            'tipoProducto' => 'required|string|max:50',
            'precio' => 'required|numeric|min:0',
        ]);

        $producto->update($validated);

        return response()->json($producto);
    }

    public function store(Request $request)
    {
        $negocio = $this->negocioDelUsuario($request);
        $validated = $request->validate([
            'nombre' => 'required|string|max:150',
            'idmarca' => 'required|integer|exists:marca,id',
            'tipoProducto' => 'required|string|max:50',
            'precio' => 'required|numeric|min:0',
        ]);

        $producto = DB::transaction(function () use ($validated, $negocio) {
            $producto = Producto::create([
                ...$validated,
                'idnegocio' => $negocio->id,
                'status' => 1
            ]);

            Inventario::create([
                'idproducto' => $producto->id,
                'idnegocio' => $negocio->id,
                'stock' => 0,
                'stock_minimo' => 10,
                'en_produccion' => 0,
                'estado' => 'Agotado'
            ]);

            return $producto;
        });

        return response()->json($producto, 201);
    }

    public function destroy(Request $request, $id)
    {
        $negocio = $this->negocioDelUsuario($request);
        $producto = Producto::where('idnegocio', $negocio->id)->findOrFail($id);
        $producto->delete();

        return response()->json(['message' => 'Eliminado correctamente']);
    }
}