<?php

namespace App\Middleware;

use App\Models\TableModel;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

class AdminMiddleware
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
        $userId = $_SESSION['app_id'] ?? null;
        $sessionToken = $_SESSION['app_session'] ?? null;
        if (isset($_SESSION['app_session']) && isset($_SESSION['app_id']) && $_SESSION['app_id'] != null) {
            $model = new TableModel();
            $model->setTable("sis_sesiones");
            $model->setId("idsesion");
            $user = $model->where("idusuario", $userId)
                ->where("session_token", $sessionToken)
                ->where("activo", "1")
                ->first();

            if (!empty($user)) {
                return $response
                    ->withHeader('Location', base_url() . 'admin')
                    ->withStatus(302);
            }
        }
        return $response;
    }
}
