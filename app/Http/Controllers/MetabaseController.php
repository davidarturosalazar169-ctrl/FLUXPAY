<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class MetabaseController extends Controller
{
    public function token(): JsonResponse
    {
        $clave = '01e569a5c8a496327939dd75c5434668b2e82c0d99a462d732e2156100a2d68f';

        $ahora = time();

        $base64Url = function ($datos) {
            return rtrim(
                strtr(base64_encode($datos), '+/', '-_'),
                '='
            );
        };

        $header = $base64Url(json_encode([
            'typ' => 'JWT',
            'alg' => 'HS256',
        ]));

        $payload = $base64Url(json_encode([
            'resource' => [
                'dashboard' => 98,
            ],
            'params' => (object) [],
            'iat' => $ahora,
            'exp' => $ahora + 600,
        ]));

        $firma = hash_hmac(
            'sha256',
            "$header.$payload",
            $clave,
            true
        );

        $token = "$header.$payload." . $base64Url($firma);

        return response()->json([
            'token' => $token,
            'instanceUrl' => 'http://localhost:3000',
        ]);
    }
}