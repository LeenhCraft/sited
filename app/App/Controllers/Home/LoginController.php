<?php

namespace App\Controllers\Home;

use App\Core\Controller;

class LoginController extends Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index($request, $response)
    {
        return $this->render($response, "Home.Login", [
            "titulo_web" => "Iniciar Sesión",
            "url" => $request->getUri()->getPath(),
            // "css" => [
            //     "/assets/vendor/css/pages/ui-carousel.css",
            //     "/assets/vendor/libs/swiper/swiper.css"
            // ],
            // "js" => [
            //     "/assets/vendor/libs/swiper/swiper.js",
            //     "/assets/js/ui-carousel.js",
            // ]
        ]);
    }
}
