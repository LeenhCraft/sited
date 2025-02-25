<?php

// use Slim\App;

use App\Controllers\Admin\BuscarDocController;
use Slim\Routing\RouteCollectorProxy;

// Controllers
use App\Controllers\Home\HomeController;
use App\Controllers\Home\LoginController;
use App\Controllers\Home\RegistrarseController;

// Middlewares

$app->get('/', HomeController::class . ':index');

$app->group('/iniciar-sesion', function (RouteCollectorProxy $iniciarsesion) {
    $iniciarsesion->get('', LoginController::class . ':index');
    $iniciarsesion->post('', LoginController::class . ':store');
});
$app->group('/registrarse', function (RouteCollectorProxy $registrarse) {
    $registrarse->get('', RegistrarseController::class . ':index');
    $registrarse->post('/save', RegistrarseController::class . ':store');
});

$app->group('/doc', function (RouteCollectorProxy $doc) {
    $doc->get('/dni/{dni}', BuscarDocController::class . ':buscarDni');
    $doc->get('/ruc/{ruc}', BuscarDocController::class . ':buscarRuc');
});
