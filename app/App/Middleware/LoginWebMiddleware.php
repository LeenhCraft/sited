<?php

namespace App\Middleware;

use App\Models\TableModel;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

class LoginWebMiddleware
{
    /**
     * Example middleware invokable class
     *
     * @param  ServerRequest  $request PSR-7 request
     * @param  RequestHandler $handler PSR-15 request handler
     *
     * @return Response
     */
    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $userId = $_SESSION['web_id'] ?? null;
        $sessionToken = $_SESSION['web_session'] ?? null;
        $model = new TableModel();
        $model->setTable("sis_sesiones");
        $model->setId("idsesion");
        if ($userId && $sessionToken) {
            // Verificar si el token de sesión coincide con el almacenado en la base de datos
            $user = $model
                ->join("sis_usuarios", "sis_sesiones.idusuario", "sis_usuarios.idusuario")
                ->where("sis_usuarios.usu_activo", "1")
                ->where("sis_usuarios.usu_estado", "1")
                ->where("sis_sesiones.idusuario", $userId)
                ->where("sis_sesiones.session_token", $sessionToken)
                ->where("sis_sesiones.activo", "1")
                ->first();

            if (!empty($user) && $user['tiempo_expiracion'] > time()) {
                $_SESSION["web_activo"] = true;
                $model->update($user['idsesion'], [
                    "tiempo_expiracion" => time() + $_ENV['SESSION_TIME']
                ]);
                $response = $handler->handle($request);
                return $response;
            } else {
                $_SESSION["web_activo"] = false;
                $model->update($user['idsesion'] ?? "0", [
                    "activo" => "0"
                ]);
            }
        }

        $_SESSION["web_activo"] = false;
        $response = new Response();
        return $response
            ->withHeader('Location', base_url() . 'iniciar-sesion')
            ->withStatus(302);
    }
}
