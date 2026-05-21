<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // REGISTER
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'rol' => 'required'
        ]);

        $idrol = match($request->rol) {
            'admin' => 1,
            'negocio' => 8,
            'cliente' => 9,
            default => 9
        };

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,

            //
            'password' => $request->password,

            'idrol' => $idrol
        ]);

        return response()->json([
            'message' => 'Usuario registrado correctamente',
            'user' => $user
        ]);
    }

   public function login(Request $request)
{
    $request->validate([
        'email' => 'required|email',
        'password' => 'required'
    ]);

    $user = User::where('email', $request->email)->first();

    if (!$user || !Hash::check($request->password, $user->password)) {
        return response()->json(['message' => 'Credenciales incorrectas'], 401);
    }

    //
    $user->tokens()->delete();

    $token = $user->createToken('auth_token')->plainTextToken;

    return response()->json([
        'token' => $token,
        'user' => [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'idrol' => $user->idrol 
        ],
        'rol' => $user->idrol
    ]);
}

    // LOGOUT
    public function logout(Request $request)
    {
        if ($request->user()) {
            $request->user()->tokens()->delete();
        }

        return response()->json([
            'message' => 'Sesión cerrada correctamente'
        ]);
    }
}