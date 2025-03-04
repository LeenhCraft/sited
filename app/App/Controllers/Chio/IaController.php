<?php

namespace App\Controllers\Chio;

use App\Core\Controller;

class IaController extends Controller
{
    private const PERMISSION = "ruta.ia";

    public function __construct()
    {
        parent::__construct();
    }

    public function  index($request, $response)
    {
        return $this->render($response, "Chio.Ia.Ia", [
            "titulo_web" => "Preguntas",
            "url" => $request->getUri()->getPath(),
            'permisos' => $this->permisos_extras,
            "permission" => self::PERMISSION,
            "css" => [
                "/vendor/select2/select2/dist/css/select2.min.css",
                "/css/select2-custom.css",
            ],
            "js" => [
                "/vendor/select2/select2/dist/js/select2.full.min.js",
                "https://cdn.jsdelivr.net/npm/chart.js",
                "/js/chio/ia.js?v=" . time()
            ]
        ]);
    }
}
