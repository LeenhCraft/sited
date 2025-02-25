<?php

// use Slim\App;
use Slim\Routing\RouteCollectorProxy;

// Controllers
use App\Controllers\Home\HomeController;
use App\Controllers\Home\LoginController;
use App\Controllers\Home\RegistrarseController;

// Middlewares

$app->get('/', HomeController::class . ':index');

$app->group('/iniciar-sesion', function (RouteCollectorProxy $iniciarsesion) {
    $iniciarsesion->get('', LoginController::class . ':index');
});
$app->group('/registrarse', function (RouteCollectorProxy $registrarse) {
    $registrarse->get('', RegistrarseController::class . ':index');
});
