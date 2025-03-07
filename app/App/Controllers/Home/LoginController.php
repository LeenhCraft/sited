<?php

namespace App\Controllers\Home;

use App\Core\Controller;
use App\Models\TableModel;
use Exception;

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
            "js" => [
                "/js/chio/login-user.js?v=" . time()
            ]
        ]);
    }

    public function store($request, $response)
    {
        try {
            $data = $this->sanitize($request->getParsedBody());

            if (empty($data['email-username']) || empty($data['password'])) {
                return $this->respondWithJson($response, [
                    "status" => false,
                    "message" => 'Por favor, complete todos los campos'
                ]);
            }

            $model = new TableModel;
            $model->setTable("sis_usuarios");
            $model->setId("idusuario");
            $userData = $model
                ->select(
                    "sis_usuarios.idusuario",
                    "sis_usuarios.idrol",
                    "sis_usuarios.usu_usuario",
                    "sis_usuarios.usu_pass",
                    "sis_usuarios.usu_estado",
                    "sis_usuarios.usu_activo",
                    "sis_personal.per_nombre",
                )
                ->join("sis_personal", "sis_usuarios.idpersona", "sis_personal.idpersona")
                ->where('usu_usuario', "LIKE", $data['email-username'])
                ->first();

            if (empty($userData)) {
                return $this->respondWithError($response, "El usuario o contraseña son incorrectos.");
            }

            if (!password_verify($data["password"], $userData["usu_pass"])) {
                return $this->respondWithError($response, "El usuario o contraseña son incorrectos.");
            }

            if ($userData["usu_estado"] == 0 || $userData["usu_activo"] == 0) {
                return $this->respondWithError($response, "El usuario se encuentra deshabilitado.");
            }

            if ($userData["usu_estado"] == 1 && $userData["usu_activo"] == 1) {
                $_SESSION['web_id'] = $userData['idusuario'];
                $_SESSION['web_r'] = $userData['idrol'];
                $token = token();
                $_SESSION['web_session'] = $token;
                $_SESSION["web_user"] = $userData["per_nombre"];
                $_SESSION["web_activo"] = true;

                $model->emptyQuery();
                $model->setTable("sis_sesiones");
                $model->setId("idsesion");

                $params = $request->getServerParams();

                $model->create([
                    "idusuario" => $userData['idusuario'],
                    "session_token" => $token,
                    "tiempo_expiracion" => time() + $_ENV['SESSION_TIME'],
                    "ip" => $params['REMOTE_ADDR'] ?? null,
                    "fecha_registro" => date("Y-m-d H:i:s"),
                    "activo" => "1"
                ]);

                return $this->respondWithJson($response, ["status" => true, "message" => "Bienvenido!!", "data" => $userData["per_nombre"]]);
            }
            return $this->respondWithError($response, "Error inesperado.");
        } catch (Exception $e) {
            return $this->respondWithJson($response, [
                "status" => false,
                "message" => $e->getMessage()
            ]);
        }
    }
}
