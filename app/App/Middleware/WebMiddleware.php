<?php

namespace App\Middleware;

use App\Models\TableModel;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

class WebMiddleware
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
        $response = $handler->handle($request);
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $userId = $_SESSION['web_id'] ?? null;
        $sessionToken = $_SESSION['web_session'] ?? null;
        if (isset($_SESSION['web_session']) && isset($_SESSION['web_id']) && $_SESSION['web_id'] != null) {
            $model = new TableModel();
            $model->setTable("sis_sesiones");
            $model->setId("idsesion");
            $user = $model
                ->join("sis_usuarios", "sis_sesiones.idusuario", "sis_usuarios.idusuario")
                ->where("sis_usuarios.usu_activo", "1")
                ->where("sis_usuarios.usu_estado", "1")
                ->where("sis_sesiones.idusuario", $userId)
                ->where("sis_sesiones.session_token", $sessionToken)
                ->where("sis_sesiones.activo", "1")
                ->first();

            if (!empty($user)) {
                return $response
                    ->withHeader('Location', base_url() . 'perfil')
                    ->withStatus(302);
            }
        }
        return $response;
    }
}
