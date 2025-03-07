<?php

namespace App\Controllers\Login;

use App\Core\Controller;
use App\Models\TableModel;

class LogoutController extends Controller
{
    public function index($request, $response, $args)
    {
        session_unset();
        session_destroy();
        return $response
            ->withHeader('Location', base_url())
            ->withStatus(302);
    }

    public function admin($request, $response, $args)
    {
        $model = new TableModel();
        $model->setTable("sis_sesiones");
        $model->setId("idsesion");
        $existe = $model->select("idsesion")
            ->where("session_token", $_SESSION['app_session'])
            ->first();
        if ($existe) {
            $model->update(
                $existe["idsesion"],
                ["activo" => "0"]
            );
        }

        unset($_SESSION['app_id']);
        unset($_SESSION['app_r']);
        unset($_SESSION['app_session']);
        unset($_SESSION["app_user"]);
        return $response
            ->withHeader('Location', base_url() . 'admin/login')
            ->withStatus(302);
    }

    public function web($request, $response, $args)
    {
        $model = new TableModel();
        $model->setTable("sis_sesiones");
        $model->setId("idsesion");
        $existe = $model->select("idsesion")
            ->where("session_token", $_SESSION['web_session'])
            ->first();
        if ($existe) {
            $model->update(
                $existe["idsesion"],
                ["activo" => "0"]
            );
        }

        // eliminar lnh y pe de session
        unset($_SESSION['lnh']);
        unset($_SESSION['pe']);
        // eliminar session
        unset($_SESSION['web_id']);
        unset($_SESSION['web_r']);
        unset($_SESSION['web_session']);
        unset($_SESSION["web_user"]);
        unset($_SESSION["web_activo"]);
        return $response
            ->withHeader('Location', base_url())
            ->withStatus(302);
    }
}
