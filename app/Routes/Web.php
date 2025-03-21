<?php

// use Slim\App;

use App\Controllers\Admin\BuscarDocController;
use App\Controllers\Chio\CitasController;
use App\Controllers\Chio\PerfilController;
use App\Controllers\Chio\TestWebController;
use Slim\Routing\RouteCollectorProxy;

// Controllers
use App\Controllers\Home\HomeController;
use App\Controllers\Home\LoginController;
use App\Controllers\Home\RegistrarseController;
use App\Controllers\Home\verifyController;
use App\Controllers\Login\LogoutController;

// Middlewares
use App\Middleware\LoginWebMiddleware;
use App\Middleware\WebMiddleware;

$app->get('/', HomeController::class . ':index');

$app->group('/doc', function (RouteCollectorProxy $doc) {
    $doc->get('/dni/{dni}', BuscarDocController::class . ':buscarDni');
    $doc->get('/ruc/{ruc}', BuscarDocController::class . ':buscarRuc');
});

$app->get('/cerrar-sesion', LogoutController::class . ':web');

$app->group('/iniciar-sesion', function (RouteCollectorProxy $iniciarsesion) {
    $iniciarsesion->get('', LoginController::class . ':index')->add(WebMiddleware::class);
    $iniciarsesion->post('', LoginController::class . ':store');
});

$app->group('/registrarse', function (RouteCollectorProxy $registrarse) {
    $registrarse->get('', RegistrarseController::class . ':index')->add(WebMiddleware::class);
    $registrarse->post('/save', RegistrarseController::class . ':store');
});

$app->get('/verify-email/{url}', verifyController::class . ':index');

$app->group('/perfil', function (RouteCollectorProxy $perfil) {
    $perfil->get('', PerfilController::class . ':index');
    $perfil->get('/mis-tests', PerfilController::class . ':indexLista');
    $perfil->post('/actualizar', PerfilController::class . ':update');
    $perfil->post('/cambiar-passwords', PerfilController::class . ':updatePassword');
    $perfil->post('/eliminar', PerfilController::class . ':deleteAccount');
})->add(LoginWebMiddleware::class);


$app->group('/sited', function (RouteCollectorProxy $sited) {
    $sited->group('/test', function (RouteCollectorProxy $test) {
        $test->get('', TestWebController::class . ':index');
        $test->post('/guardar-respuestas', TestWebController::class . ':procesarRespuestas');
        $test->get('/detalle/{id}', TestWebController::class . ':verTest');
        $test->get('/api/detalle/{id}', TestWebController::class . ':exportPdf');
    });
    $sited->post('/obtener-preguntas', TestWebController::class . ':obtenerPreguntas');
    $sited->get('/disponibilidad', CitasController::class . ':getDisponibilidadHorarios');
    $sited->post('/agendar-cita', TestWebController::class . ':agendarCita');
})->add(LoginWebMiddleware::class);
