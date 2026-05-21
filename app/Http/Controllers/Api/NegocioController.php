<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Negocio;
use Illuminate\Http\Request;

class NegocioController extends Controller
{
    // LISTAR
    public function index(Request $request)
    {
        $query = Negocio::query();

        // ADMIN VE TODO
        if ($request->user()->idrol != 1) {
            $query->where('iduser', $request->user()->id);
        }

        return response()->json($query->get());
    }

    // CREAR
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required',
            'descripcion' => 'nullable',
            'telefono' => 'nullable',
            'status' => 'nullable',
        ]);

        $negocio = Negocio::create([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'telefono' => $request->telefono,
            'status' => $request->status ?? 1,
            'iduser' => $request->user()->id, // 🔥 toma el usuario logueado
        ]);

        return response()->json($negocio, 201);
    }

    // ACTUALIZAR
    public function update(Request $request, $id)
    {
        $query = Negocio::where('id', $id);

        if ($request->user()->idrol != 1) {
            $query->where('iduser', $request->user()->id);
        }

        $negocio = $query->firstOrFail();

        $negocio->update([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'telefono' => $request->telefono,
            'status' => $request->status ?? $negocio->status
        ]);

        return response()->json($negocio);
    }

    // ELIMINAR
    public function destroy(Request $request, $id)
    {
        $query = Negocio::where('id', $id);

        if ($request->user()->idrol != 1) {
            $query->where('iduser', $request->user()->id);
        }

        $negocio = $query->firstOrFail();
        $negocio->delete();

        return response()->json(['message' => 'Negocio eliminado']);
    }
}