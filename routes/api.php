<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

/* "litipk/php-bignumbers": "^0.8.6",
"simplesoftwareio/simple-qrcode": "^4.2.0",
"setasign/fpdf": "^1.8.6", */

$router->get('/', function () use ($router) {
    return view("test");
});


$router->group(['middleware' => ['cors']], function () use ($router) {
    // AUTH
    Route::controller(AuthController::class)->prefix('auth')->group(function () {
        Route::get('login', 'authenticate');
        Route::post('logout', 'cerrarSesion');
        Route::post('version', 'version');
      });
    // TEST
    $router->group(['prefix' => 'clientes'], function () use ($router) {
        $router->get('/', ['uses' => 'ClienteController@getCliente']);
        $router->post('/agregar', ['uses' => 'ClienteController@agregar']);
    });
    $router->group(['prefix' => 'tarjetas'], function () use ($router) {
        $router->get('/', ['uses' => 'TarjetaController@getTarjeta']);
        $router->get('/listar', ['uses' => 'TarjetaController@getTarjetas']);
        $router->post('/agregar', ['uses' => 'TarjetaController@agregar']);
    });
    $router->group(['prefix' => 'catalogos'], function () use ($router) {
        $router->get('/', ['uses' => 'ClienteController@getUsuarios']);
        $router->post('/', ['uses' => 'ClienteController@getUsuarios']);
    });

    //$router->get('clientes/getClientes', ['uses' => 'ClienteController@getUsuarios']);
});
