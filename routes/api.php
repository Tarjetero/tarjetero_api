<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TarjetaController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\CatalogoController;

/* "litipk/php-bignumbers": "^0.8.6",
"simplesoftwareio/simple-qrcode": "^4.2.0",
"setasign/fpdf": "^1.8.6", */

$router->get('/', function () use ($router) {
    return view("test");
});

/* Route::get('/enviar-correo', function () {
  $correo = new DemoEmail(); // Reemplaza con tu clase de correo

  Mail::to('destinatario@example.com')->send($correo);

  return "Correo enviado";
}); */


$router->group(['middleware' => ['cors']], function () use ($router) {
    // AUTH
    Route::controller(AuthController::class)->prefix('auth')->group(function () {
        Route::post('login', 'authenticate');
        Route::post('logout', 'cerrarSesion');
        Route::get('version', 'version');
    });
    // CLIENTES
    Route::controller(ClienteController::class)->prefix('clientes')->group(function () {
        Route::get('', 'getCliente');
        Route::post('registro', 'registro');
        Route::post('editarFoto', 'editarFoto');
        Route::post('editarPassword', 'editarPassword');
    });
    // TARJETAS
    Route::controller(TarjetaController::class)->prefix('tarjetas')->group(function () {
      Route::get('', 'getTarjetaInfo');
      Route::get('listar', 'getTarjetas');
      Route::post('agregar', 'agregar');
      Route::post('editar', 'editar');
      Route::post('eliminar', 'eliminar');
    });
    // CATALOGOS
    Route::controller(CatalogoController::class)->prefix('catalogos')->group(function () {
      Route::get('marcasTarjetas', 'getMarcasTarjetas');
    });
});
