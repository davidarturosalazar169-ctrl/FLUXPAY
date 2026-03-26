<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\NegocioController;
use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\TicketController;
// 🔥 TEST
Route::get('/test', function () {
    return response()->json(['mensaje' => 'API funcionando']);
});

// 🔓 PÚBLICAS
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// 🔒 PROTEGIDAS
Route::middleware('auth:sanctum')->group(function () {

    // 👤 usuario autenticado
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // 🔥 ADMIN (YA NO POR EMAIL)
    Route::get('/admin', [AdminController::class, 'show']);
    Route::put('/admin', [AdminController::class, 'update']);

    // 🔐 negocios
    Route::apiResource('negocios', NegocioController::class);

    // 🚪 logout
    Route::post('/logout', [AuthController::class, 'logout']);
});

// 🔥 SOLO ADMIN
Route::middleware(['auth:sanctum', 'rol:1'])->group(function () {
    Route::get('/admin-negocios', [NegocioController::class, 'index']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('negocios', NegocioController::class);
    Route::apiResource('tickets', TicketController::class);
});