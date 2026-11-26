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
use App\Http\Controllers\Api\RolPermisoController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\GenerarTicketController;
use App\Http\Controllers\Api\MovimientoController;
use App\Http\Controllers\StripeController;
use App\Http\Controllers\Api\InventarioController;
use App\Http\Controllers\Api\AdminNotificationController;
use App\Http\Controllers\RenderControllerPrueba;

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

Route::get('/dashboard', [DashboardController::class, 'index']);

/*
|--------------------------------------------------------------------------
| RUTAS PROTEGIDAS (SANCTUM)
|--------------------------------------------------------------------------
*/
Route::middleware('auth:sanctum')->group(function () {

    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::get('/admin', [AdminController::class, 'show']);
    Route::put('/admin', [AdminController::class, 'update']);

    Route::apiResource('negocios', NegocioController::class);
    Route::apiResource('productos', ProductoController::class);

    Route::apiResource('tickets', TicketController::class);
    Route::get('/inventario', [InventarioController::class, 'index']);
    Route::put('/inventario/{inventario}', [InventarioController::class, 'update']);

    Route::post('/logout', [AuthController::class, 'logout']);
});

/*
|--------------------------------------------------------------------------
| SOLO ADMIN
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:sanctum', 'rol:1'])->group(function () {
    Route::get('/admin-negocios', [NegocioController::class, 'index']);
    Route::get('/admin/notificaciones', [AdminNotificationController::class, 'index']);
    Route::patch('/admin/notificaciones/{notification}/leer', [AdminNotificationController::class, 'markAsRead']);
    Route::patch('/admin/notificaciones/leer-todas', [AdminNotificationController::class, 'markAllAsRead']);
});
//INVENTARIO
Route::get('/inventario', [InventarioController::class, 'index']);
Route::get('/inventario/sincronizar', [InventarioController::class, 'sincronizar']);
/*
|--------------------------------------------------------------------------
| ROLES Y PERMISOS
|--------------------------------------------------------------------------
*/
Route::get("/roles", [RolPermisoController::class, "roles"]);
Route::get("/permisos", [RolPermisoController::class, "permisos"]);
Route::get("/roles/{id}/permisos", [RolPermisoController::class, "permisosRol"]);
Route::put("/roles/{id}/permisos", [RolPermisoController::class, "guardar"]);

/*
|--------------------------------------------------------------------------
| TARJETAS
|--------------------------------------------------------------------------
*/
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/tarjetas', [TarjetaClienteController::class, 'listado']);
    Route::post('/tarjetas', [TarjetaClienteController::class, 'guardar']);
    Route::delete('/tarjetas/{id}', [TarjetaClienteController::class, 'eliminar']);
});

/*
|--------------------------------------------------------------------------
| CLIENTES
|--------------------------------------------------------------------------
*/
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/historial', [ClienteController::class, 'historialCliente']);
    Route::get('/cliente/configuracion', [ClienteController::class, 'configuracion']);
    Route::put('/cliente/actualizar', [ClienteController::class, 'actualizar']);

    Route::get('/clientes', [ClienteController::class, 'index']);
    Route::post('/clientes', [ClienteController::class, 'store']);
});

Route::put('/clientes/{id}', [ClienteController::class, 'update']);
Route::delete('/clientes/{id}', [ClienteController::class, 'destroy']);

/*
|--------------------------------------------------------------------------
| PRODUCTOS Y MARCAS
|--------------------------------------------------------------------------
*/
Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('productos', ProductoController::class);
});

Route::apiResource('marcas', MarcaController::class);

/*
|--------------------------------------------------------------------------
| TIENDA DASHBOARD
|--------------------------------------------------------------------------
*/
Route::middleware('auth:sanctum')->prefix('tienda/dashboard')->group(function () {
    Route::get('productos', [TiendaClienteDashController::class, 'productos']);
    Route::get('ingresos', [TiendaClienteDashController::class, 'ingresos']);
    Route::get('resumen', [TiendaClienteDashController::class, 'resumen']);
});

/*
|--------------------------------------------------------------------------
| TICKETS
|--------------------------------------------------------------------------
*/
Route::get('/tickets', [GenerarTicketController::class, 'index']);
Route::post('/tickets', [GenerarTicketController::class, 'store']);

/*
|--------------------------------------------------------------------------
| MOVIMIENTOS
|--------------------------------------------------------------------------
*/
Route::get('/movimientos', [MovimientoController::class, 'index']);

/*
|--------------------------------------------------------------------------
| STRIPE
|--------------------------------------------------------------------------
*/
Route::post('/create-payment', [StripeController::class, 'createPayment']);
Route::post('/save-movimiento', [StripeController::class, 'saveMovimiento']);
Route::get('/crear-cuenta-prueba', [StripeController::class, 'crearCuentaPrueba']);


// Asegúrate de que las rutas apunten exactamente a tu controlador
Route::post('/api/create-payment', [StripeController::class, 'createPayment']);
Route::post('/api/save-movimiento', [StripeController::class, 'saveMovimiento']);

Route::get('/render-status', [RenderControllerPrueba::class, 'renderStatus']);

use App\Http\Controllers\StripeQRController;

Route::post(
    '/crear-pedido',
    [StripeQRController::class,'crearPedido']
);


Route::get(
    '/pedido/{id}',
    [StripeQRController::class,'obtenerPedido']
);

Route::post('/crear-checkout', [StripeQRController::class, 'crearCheckout']);

Route::post('/confirmar-pago-stripe', [StripeQRController::class, 'confirmarPago']);

use App\Http\Controllers\ClienteNegocioController;

Route::get('/clientes-negocios', [
    ClienteNegocioController::class,
    'index'
]);

Route::post('/clientes-negocios', [
    ClienteNegocioController::class,
    'store'
]);
Route::put('/clientes-negocios/{id}', [
    ClienteNegocioController::class,
    'update'
]);

Route::delete('/clientes-negocios/{id}', [
    ClienteNegocioController::class,
    'destroy'
]);


use App\Http\Controllers\PuntosClienteController;

// Sistema de puntos de clientes
Route::get('/clientes-negocios/{id}/puntos', [
    PuntosClienteController::class,
    'consultar'
]);

Route::post('/clientes-negocios/puntos/acumular', [
    PuntosClienteController::class,
    'acumular'
]);

Route::post('/clientes-negocios/puntos/canjear', [
    PuntosClienteController::class,
    'canjear'
]);

Route::get('/clientes-negocios/puntos/configuracion', [PuntosClienteController::class, 'consultarConfiguracion']);

Route::post('/clientes-negocios/puntos/configuracion', [PuntosClienteController::class, 'guardarConfiguracion']);

use App\Http\Controllers\MetabaseController;

Route::get('/metabase/token', [MetabaseController::class, 'token']);

use App\Http\Controllers\StripeOxxoController;

Route::post('/crear-pago-oxxo', [
    StripeOxxoController::class,
    'crear'
]);


use App\Http\Controllers\ApiKeyController;

Route::prefix('integraciones')->group(function () {

    Route::post('/api-keys', [ApiKeyController::class, 'generar']);

    Route::get('/api-keys', [ApiKeyController::class, 'listar']);

    Route::put('/api-keys/{id}/revocar', [ApiKeyController::class, 'revocar']);

});

use App\Http\Controllers\ApiQrController;

Route::post('/integraciones/crear-qr', [ApiQrController::class, 'crear']);
Route::get('/integraciones/prueba-qr', [ApiQrController::class, 'prueba']);