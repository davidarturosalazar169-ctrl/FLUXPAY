<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SupersetController extends Controller
{
    public function guestToken()
    {
        try {

            // DATOS DE CONEXIÓN A SUPERSET
            $supersetUrl = 'http://superset_app:8088';
            $username = 'admin';
            $password = 'admin';

            // 1. INICIAR SESIÓN EN SUPERSET
            $login = Http::timeout(30)->post(
                $supersetUrl . '/api/v1/security/login',
                [
                    'username' => $username,
                    'password' => $password,
                    'provider' => 'db',
                    'refresh' => false
                ]
            );

            if (!$login->successful()) {

                Log::error('Error al iniciar sesión en Superset', [
                    'status' => $login->status(),
                    'response' => $login->body()
                ]);

                return response()->json([
                    'message' => 'Error al iniciar sesión en Superset'
                ], 502);
            }

            $accessToken = $login->json('access_token');

            if (!$accessToken) {
                return response()->json([
                    'message' => 'Superset no devolvió el Access Token'
                ], 502);
            }

            // 2. GENERAR GUEST TOKEN
            $response = Http::timeout(30)
                ->withToken($accessToken)
                ->post(
                    $supersetUrl . '/api/v1/security/guest_token/',
                    [
                        'user' => [
                            'username' => 'impulsapay',
                            'first_name' => 'ImpulsaPay',
                            'last_name' => 'Administrador'
                        ],

                        'resources' => [
                            [
                                'type' => 'dashboard',
                                'id' => '50cd0105-20c3-4860-854b-d8f00c437fa1'
                            ]
                        ],

                        'rls' => []
                    ]
                );

            if (!$response->successful()) {

                Log::error('Error al generar Guest Token', [
                    'status' => $response->status(),
                    'response' => $response->body()
                ]);

                return response()->json([
                    'message' => 'Error al generar Guest Token'
                ], 502);
            }

            // 3. DEVOLVER TOKEN A REACT
            return response()->json([
                'token' => $response->json('token')
            ]);

        } catch (\Throwable $e) {

            Log::error('Error de conexión con Superset', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'message' => 'Error interno al conectar con Superset'
            ], 500);
        }
    }
}
