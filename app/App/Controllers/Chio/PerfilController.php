<?php

namespace App\Controllers\Chio;

use App\Core\Controller;
use App\Models\TableModel;

class PerfilController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index($request, $response)
    {
        $model = new TableModel();
        $model->setTable("sis_usuarios u");
        $model->setId("idusuario");

        $userData = $model
            ->select(
                "p.per_nombre as nombre",
                "p.per_email as email",
                "pa.edad",
                "pa.sexo",
                "pa.peso",
                "pa.altura",
                "u.ultima_actualizacion"
            )
            ->join("sis_personal p", "p.idpersona", "u.idpersona")
            ->join("sd_pacientes pa", "pa.dni", "p.per_dni")
            ->where("u.idusuario", $_SESSION["web_id"])
            ->first();

        return $this->render($response, "Chio.Perfil-usuario.Perfil", [
            "titulo_web" => "Mi Perfil",
            "url" => $request->getUri()->getPath(),
            "css" => [
                "/assets/vendor/css/pages/ui-carousel.css",
                "/assets/vendor/libs/swiper/swiper.css"
            ],
            "js" => [
                "/assets/vendor/libs/swiper/swiper.js",
                "/assets/js/ui-carousel.js",
                "/js/chio/login-user.js?v=" . time()
            ],
            "user" => $userData
        ]);
    }

    public function indexLista($request, $response)
    {
        $model = new TableModel();
        $model->setTable("sis_usuarios u");
        $model->setId("idusuario");

        $userData = $model
            ->select(
                "pa.idpaciente as id_paciente",
                "p.per_nombre as nombre",
                "p.per_email as email",
                "pa.edad",
                "pa.sexo",
                "pa.peso",
                "pa.altura",
                "u.ultima_actualizacion"
            )
            ->join("sis_personal p", "p.idpersona", "u.idpersona")
            ->join("sd_pacientes pa", "pa.dni", "p.per_dni")
            ->where("u.idusuario", $_SESSION["web_id"])
            ->first();

        $model = new TableModel();
        $model->setTable("sd_test");
        $model->setId("idtest");

        $modelPaciente = new TableModel();
        $modelPaciente->setTable("sd_test");
        $modelPaciente->setId("idtest");

        $testData = $model
            ->where("eliminado", "0")
            // ->where("idusuario", $_SESSION["web_id"])
            ->where("idpaciente", $userData["id_paciente"])
            ->orderBy("fecha_hora", "DESC")
            ->get();

        return $this->render($response, "Chio.Test.ListaPerfil", [
            "titulo_web" => "Mis Tests",
            "url" => $request->getUri()->getPath(),
            "css" => [
                "/assets/vendor/css/pages/ui-carousel.css",
                "/assets/vendor/libs/swiper/swiper.css"
            ],
            "js" => [
                "/assets/vendor/libs/swiper/swiper.js",
                "/assets/js/ui-carousel.js",
                "/js/chio/lista-test.js?v=" . time()
            ],
            "tests" => $testData
        ]);
    }

    public function update($reques, $response)
    {
        $data = $this->sanitize($reques->getParsedBody());
        return $this->respondWithJson($response, $data);
    }
}
