<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClienteController extends Controller
{
    public function historialCliente()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!$user) {
            return response()->json(['mensaje' => 'No autenticado'], 401);
        }

        $movimientos = DB::table('movimiento as m')
            ->join('detalle_movimientos as d', 'm.id', '=', 'd.idmovimiento')
            ->join('productos as p', 'd.idproducto', '=', 'p.id')
            ->select(
                'm.fecha_movimiento',
                'p.nombre as producto',
                'm.monto_total',
                'm.status'
            )
            ->where('m.iduser', $user->id)
            ->orderBy('m.fecha_movimiento', 'desc')
            ->get();

        $data = $movimientos->map(function ($item) {
            return [
                date('d/m/Y', strtotime($item->fecha_movimiento)),
                $item->producto,
                'MXN ' . $item->monto_total,
                $item->status == 1 ? 'Completado' : 'Pendiente'
            ];
        });

        return response()->json($data);
    }

    public function configuracion()
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['mensaje' => 'No autenticado'], 401);
        }

        return response()->json([
            'nombre' => $user->name,
            'correo' => $user->email
        ]);
    }

    public function actualizar(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!$user) {
            return response()->json(['mensaje' => 'No autenticado'], 401);
        }

        // validar contraseña si viene
        if ($request->has('current_password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return response()->json([
                    'error' => 'Contraseña incorrecta'
                ], 400);
            }
        }

        // actualizar nombre
        if ($request->nombre) {
            $user->name = $request->nombre;
        }

        // actualizar correo
        if ($request->correo) {
            $user->email = $request->correo;
        }

        // actualizar contraseña
        if ($request->password) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return response()->json([
            'ok' => true
        ]);
    }
}