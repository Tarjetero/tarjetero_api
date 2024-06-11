<?php

use Illuminate\Support\Facades\Route;

/* Route::get('/', function () {
    return view('welcome');
}); */

$router->get('/', function () use ($router) {
    return view("welcome");
});

$router->group(['middleware' => ['cors']], function () use ($router) {
    // TEST
    $router->get('clientes/getClientes', ['uses' => 'ClienteController@getUsuarios']);
    // AUTH
    $router->group(['prefix' => 'auth'], function () use ($router) {
        $router->post('login', ['uses' => 'AuthController@authenticate']);
        $router->post('logout', ['uses' => 'AuthController@cerrarSesion']);
        $router->get('version', ['uses' => 'AuthController@version']);
    });
});
