<?php

namespace App\Middleware;

use App\Models\TableModel;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

class LoginAdminMiddleware
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
        $userId = $_SESSION['app_id'] ?? null;
        $sessionToken = $_SESSION['app_session'] ?? null;
        $model = new TableModel();
        $model->setTable("sis_sesiones");
        $model->setId("idsesion");
        if ($userId && $sessionToken) {
            // Verificar si el token de sesión coincide con el almacenado en la base de datos
            $user = $model->where("idusuario", $userId)
                ->where("session_token", $sessionToken)
                ->where("activo", "1")
                ->first();

            if (!empty($user) && $user['tiempo_expiracion'] > time()) {
                $model->update($user['idsesion'], [
                    "tiempo_expiracion" => time() + $_ENV['SESSION_TIME']
                ]);
                $response = $handler->handle($request);
                return $response;
            } else {
                $model->update($user['idsesion'], [
                    "activo" => "0"
                ]);
            }
        }

        $response = new Response();
        return $response
            ->withHeader('Location', base_url() . 'admin/login')
            ->withStatus(302);
    }
}
