<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Inventario;
use App\Models\Producto;
use Illuminate\Http\Request;

class InventarioController extends Controller
{
    // Mostrar inventario
   public function index()
{

    $inventario = Inventario::with([
        "producto",
        "negocio"
    ])->get();

    return response()->json([

        "productos"=>$inventario->count(),

        "stock_bajo"=>$inventario->where("estado","Bajo")->count(),

        "agotados"=>$inventario->where("estado","Agotado")->count(),

        "produccion"=>$inventario->sum("en_produccion"),

        "inventario"=>$inventario

    ]);

}

    public function update(Request $request, Inventario $inventario)
    {
        $validated = $request->validate([
            'stock' => 'required|integer|min:0',
            'stock_minimo' => 'required|integer|min:0',
            'en_produccion' => 'required|integer|min:0',
        ]);

        $inventario->fill($validated);
        $inventario->estado = $this->calcularEstado(
            $inventario->stock,
            $inventario->stock_minimo
        );
        $inventario->save();

        return response()->json($inventario->load(['producto', 'negocio']));
    }

    // Sincronizar productos existentes
    public function sincronizar()
    {
        $productos = Producto::all();

        foreach ($productos as $producto) {

            $existe = Inventario::where("idproducto", $producto->id)->first();

            if (!$existe) {

                Inventario::create([
                    "idproducto" => $producto->id,
                    "idnegocio" => $producto->idnegocio,
                    "stock" => 0,
                    "stock_minimo" => 10,
                    "en_produccion" => 0,
                    "estado" => "Agotado"
                ]);

            }

        }

        return response()->json([
            "message" => "Inventario sincronizado correctamente"
        ]);
    }

    private function calcularEstado($stock, $stockMinimo)
{
        if ($stock <= 0) {
            return "Agotado";
        }

        if ($stock <= $stockMinimo) {
            return "Bajo";
        }

        return "Disponible";

}
}