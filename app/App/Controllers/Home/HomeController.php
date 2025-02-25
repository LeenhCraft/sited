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
            "url" => $request->getUri()->getPath(),
            "css" => [
                "/assets/vendor/css/pages/ui-carousel.css",
                "/assets/vendor/libs/swiper/swiper.css"
            ],
            "js" => [
                "/assets/vendor/libs/swiper/swiper.js",
                "/assets/js/ui-carousel.js",
            ]
        ]);
    }
}
