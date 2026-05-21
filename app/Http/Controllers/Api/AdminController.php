<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    // 🔥 OBTENER ADMIN LOGUEADO
    public function show(Request $request)
    {
        return response()->json($request->user());
    }

    // 🔥 ACTUALIZAR ADMIN
    public function update(Request $request)
    {
        $admin = $request->user();

        if ($request->name) {
            $admin->name = $request->name;
        }

        if ($request->email) {
            $admin->email = $request->email;
        }

        if ($request->telefono) {
            $admin->telefono = $request->telefono;
        }

        if ($request->password) {
            $admin->password = Hash::make($request->password);
        }

        $admin->save();

        return response()->json([
            "message" => "Actualizado correctamente",
            "user" => $admin
        ]);
    }
}