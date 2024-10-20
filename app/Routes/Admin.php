<?php

// use Slim\App;

// Controllers
use App\Controllers\Login\LoginController;

// Middlewares

$app->get('/admin', LoginController::class . ':index');
