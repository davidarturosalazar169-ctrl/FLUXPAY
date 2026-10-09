<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
<<<<<<< HEAD
use App\Models\Producto;
use App\Models\Inventario;
use App\Models\Negocio;

class ProductoController extends Controller
{
    /**
     * Obtener el negocio del usuario autenticado
     */
    private function obtenerNegocioUsuario(Request $request)
    {
        $user = $request->user();

        return Negocio::where('iduser', $user->id)->first();
    }

    /**
     * LISTAR PRODUCTOS
     */
    public function index(Request $request)
    {
        try {

            $negocio = $this->obtenerNegocioUsuario($request);

            if (!$negocio) {
                return response()->json([
                    'error' => 'El usuario no tiene un negocio asociado'
                ], 404);
            }

            $productos = Producto::with('marca')
                ->where('idnegocio', $negocio->id)
                ->get();

            return response()->json($productos);

        } catch (\Exception $e) {

            \Log::error('PRODUCTOS - ERROR INDEX', [
                'mensaje' => $e->getMessage(),
                'archivo' => $e->getFile(),
                'linea' => $e->getLine(),
            ]);

            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * CREAR PRODUCTO
     */
    public function store(Request $request)
    {
        try {

            $validated = $request->validate([
                'nombre'       => 'required|string',
                'idmarca'      => 'required|integer',
                'tipoProducto' => 'required|string',
                'precio'       => 'required|numeric',
            ]);

            // Obtener negocio real del usuario
            $negocio = $this->obtenerNegocioUsuario($request);

            if (!$negocio) {
                return response()->json([
                    'error' => 'El usuario no tiene un negocio asociado'
                ], 404);
            }

            // Asignar negocio correcto
            $validated['idnegocio'] = $negocio->id;

            // Forzar status a 1
            $validated['status'] = 1;

            // Crear producto
            $producto = Producto::create($validated);

            // Crear inventario automáticamente
            Inventario::create([
                'idproducto'    => $producto->id,
                'idnegocio'     => $negocio->id,
                'stock'         => 0,
                'stock_minimo'  => 10,
                'en_produccion' => 0,
                'estado'        => 'Agotado'
            ]);

            return response()->json($producto, 201);

        } catch (\Exception $e) {

            \Log::error('PRODUCTOS - ERROR STORE', [
                'mensaje' => $e->getMessage(),
                'archivo' => $e->getFile(),
                'linea' => $e->getLine(),
            ]);

            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
=======
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
>>>>>>> equipo-produccion
    }

    /**
     * EDITAR PRODUCTO
     */
    public function update(Request $request, $id)
    {
<<<<<<< HEAD
        try {

            // Obtener negocio real del usuario
            $negocio = $this->obtenerNegocioUsuario($request);

            if (!$negocio) {
                return response()->json([
                    'error' => 'El usuario no tiene un negocio asociado'
                ], 404);
            }

            // Buscar producto dentro del negocio
            $producto = Producto::where('id', $id)
                ->where('idnegocio', $negocio->id)
                ->first();

            if (!$producto) {
                return response()->json([
                    'error' => 'Producto no encontrado en el negocio'
                ], 404);
            }

            $validated = $request->validate([
                'nombre'       => 'required|string',
                'idmarca'      => 'required|integer',
                'tipoProducto' => 'required|string',
                'precio'       => 'required|numeric',
            ]);

            // Actualizar
            $producto->update($validated);

            return response()->json([
                'message' => 'Producto actualizado correctamente',
                'producto' => $producto
            ]);

        } catch (\Exception $e) {

            \Log::error('PRODUCTOS - ERROR UPDATE', [
                'id_producto' => $id,
                'mensaje' => $e->getMessage(),
                'archivo' => $e->getFile(),
                'linea' => $e->getLine(),
            ]);

            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * ELIMINAR PRODUCTO
     */
    public function destroy(Request $request, $id)
    {
        try {

            // Obtener negocio real del usuario
            $negocio = $this->obtenerNegocioUsuario($request);

            if (!$negocio) {
                return response()->json([
                    'error' => 'El usuario no tiene un negocio asociado'
                ], 404);
            }

            // Buscar producto dentro del negocio
            $producto = Producto::where('id', $id)
                ->where('idnegocio', $negocio->id)
                ->first();

            if (!$producto) {
                return response()->json([
                    'error' => 'Producto no encontrado en el negocio'
                ], 404);
            }

            // Eliminar inventario relacionado
            Inventario::where('idproducto', $producto->id)->delete();

            // Eliminar producto
            $producto->delete();

            return response()->json([
                'message' => 'Producto eliminado correctamente'
            ]);

        } catch (\Exception $e) {

            \Log::error('PRODUCTOS - ERROR DELETE', [
                'id_producto' => $id,
                'mensaje' => $e->getMessage(),
                'archivo' => $e->getFile(),
                'linea' => $e->getLine(),
            ]);

            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
=======
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
>>>>>>> equipo-produccion
    }
}