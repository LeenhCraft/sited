<?php

namespace App\Controllers\Home;

use App\Core\Controller;
use App\Models\TableModel;

class verifyController extends Controller
{
    public function index($request, $response, $args)
    {
        $token = $args['url'];

        $usuarioModel = new TableModel();
        $usuarioModel->setTable("sis_usuarios");
        $usuarioModel->setId("idusuario");

        $dataUsuario = $usuarioModel
            ->where("usu_token", "LIKE", $token)
            ->where("usu_activo", "0")
            ->first();

        if (empty($dataUsuario)) {
            $msg = "El token no es valido";
            return $this->respondWithError($response, $msg);
        }

        // if (time() - $dataUsuario["usu_expire"] > 0) { // si el token ha expirado
        //     return $this->respondWithJson($response, ["status" => false, "message" => "El token ha expirado"]);
        // }

        $usuarioModel->update($dataUsuario["idusuario"], [
            "usu_token" => null,
            "usu_activo" => "1"
        ]);

        return $response
            ->withHeader('Location', base_url())
            ->withStatus(302);
    }
}
