<?php

namespace App\Controllers\Home;

use App\Core\Controller;

class HomeController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index($request,  $response, $args)
    {
        return $this->render($response, "Home.Home", [
            "titulo_web" => "Inicio",
            "css" => [
                "/css/style.css",
                "/css/home.css"
            ],
            "url" => $request->getUri()->getPath()
        ]);
    }
}
