<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

// Controllers
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\NegocioController;
use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\TicketController;
use App\Http\Controllers\TarjetaClienteController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\SesionController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\MarcaController;
use App\Http\Controllers\Api\TiendaClienteDashController;

/*
|--------------------------------------------------------------------------
| TEST
|--------------------------------------------------------------------------
*/
Route::get('/test', function () {
    return response()->json(['mensaje' => 'API funcionando']);
});

/*
|--------------------------------------------------------------------------
| AUTENTICACIÓN (PÚBLICAS)
|--------------------------------------------------------------------------
*/
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
use App\Http\Controllers\Api\DashboardController;

// MUEVELA AQUÍ (Fuera de cualquier Route::group o middleware)
Route::get('/dashboard', [DashboardController::class, 'index']);
/*
|--------------------------------------------------------------------------
| RUTAS PROTEGIDAS (SANCTUM)
|--------------------------------------------------------------------------
*/
Route::middleware('auth:sanctum')->group(function () {

    /*
    |-------------------------------
    | 👤 USUARIO AUTENTICADO
    |-------------------------------
    */
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    /*
    |-------------------------------
    | 👨‍💼 ADMIN
    |-------------------------------
    */
    Route::get('/admin', [AdminController::class, 'show']);
    Route::put('/admin', [AdminController::class, 'update']);

    /*
    |-------------------------------
    | 🏪 NEGOCIOS
    |-------------------------------
    */
    Route::apiResource('negocios', NegocioController::class);

    /*
    |-------------------------------
    | 🎫 TICKETS (🔥 PRINCIPAL)
    |-------------------------------
    */
   Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('tickets', TicketController::class);
});

    /*
    |-------------------------------
    | 🚪 LOGOUT
    |-------------------------------
    */
    Route::post('/logout', [AuthController::class, 'logout']);
});

/*
|--------------------------------------------------------------------------
| SOLO ADMIN
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:sanctum', 'rol:1'])->group(function () {
    Route::get('/admin-negocios', [NegocioController::class, 'index']);
});

/*
|--------------------------------------------------------------------------
| TARJETAS
|--------------------------------------------------------------------------
*/
Route::get('/tarjetas', [TarjetaClienteController::class, 'listado']);
Route::post('/tarjetas', [TarjetaClienteController::class, 'guardar']);
Route::delete('/tarjetas/{id}', [TarjetaClienteController::class, 'eliminar']);

/*
|--------------------------------------------------------------------------
| CLIENTES
|--------------------------------------------------------------------------
*/
Route::get('/historial', [ClienteController::class, 'historialCliente']);
Route::get('/cliente/configuracion', [ClienteController::class, 'configuracion']);
Route::put('/cliente/actualizar', [ClienteController::class, 'actualizar']);

// CRUD clientes (temporal público)
Route::get('/clientes', [ClienteController::class, 'index']);
Route::post('/clientes', [ClienteController::class, 'store']);
Route::put('/clientes/{id}', [ClienteController::class, 'update']);
Route::delete('/clientes/{id}', [ClienteController::class, 'destroy']);

/*
|--------------------------------------------------------------------------
| PRODUCTOS Y MARCAS
|--------------------------------------------------------------------------
*/
Route::apiResource('productos', ProductoController::class);
Route::apiResource('marcas', MarcaController::class);

/*
|--------------------------------------------------------------------------
| DASHBOARD TIENDA
|--------------------------------------------------------------------------
*/

Route::prefix('tienda/dashboard')->group(function () {
    Route::get('productos', [TiendaClienteDashController::class, 'productos']);
    Route::get('ingresos', [TiendaClienteDashController::class, 'ingresos']);
    Route::get('resumen', [TiendaClienteDashController::class, 'resumen']);
});