<?php

namespace App\Controllers\Login;

use App\Core\Controller;

class LoginController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index($request,  $response, $args)
    {
        return $this->render($response, "LoginDashboard.Login", [
            "titulo_web" => "Iniciar Sesión",
            "url" => $request->getUri()->getPath()
        ]);
    }
}
