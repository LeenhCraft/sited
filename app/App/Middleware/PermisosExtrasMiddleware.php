<?php

namespace App\Middleware;

use App\Models\TableModel;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;
use Slim\Routing\RouteContext;

class PermisosExtrasMiddleware
{
    public function __invoke(Request $request, RequestHandler $handler)
    {
        // Obtener el método del controlador desde la ruta
        $method = $this->extractMethodFromRoute($request);

        // Mapear métodos a recursos
        $resourceMap = [
            'buscarDni' => 'doc.dni',
            'buscarRuc' => 'doc.ruc',
        ];

        // Obtener el recurso basado en el método
        $idrecurso = $resourceMap[$method] ?? null;

        // Si no se encuentra el recurso, devolver un error
        if (!$idrecurso) {
            return $this->createErrorResponse("Método no válido.");
        }

        // Verificar permisos
        if (!$this->hasPermission($_SESSION["app_r"], $idrecurso)) {
            return $this->createErrorResponse("No tiene permisos para acceder a esta funcionalidad.");
        }

        // Continuar con la solicitud si todo está bien
        return $handler->handle($request);
    }

    /**
     * Extrae el nombre del método del controlador desde la ruta.
     */
    private function extractMethodFromRoute(Request $request): string
    {
        $routeContext = RouteContext::fromRequest($request);
        $routeCallable = $routeContext->getRoute()->getCallable();
        $routeCallable = str_replace("\\", "/", $routeCallable);
        $routeArray = explode('/', $routeCallable);
        $explode = explode(':', end($routeArray));
        return $explode[1] ?? '';
    }

    /**
     * Verifica si el usuario tiene permisos para el recurso y acción dados.
     */
    private function hasPermission(int $roleId, string $resource): bool
    {
        $model = new TableModel;
        $sql = "SELECT r.identificador as recurso, a.identificador as accion, pe.* 
            FROM sis_permisos_extras pe 
            INNER JOIN sis_recursos r ON pe.idrecurso = r.idrecurso 
            INNER JOIN sis_acciones a ON pe.idaccion = a.idaccion 
            WHERE pe.idrol = ? AND pe.estado = ? AND r.identificador = ? AND a.identificador = ?";
        $datos = [$roleId, "1", $resource, "view"];
        $permisos = $model->query($sql, $datos)->first();

        return !empty($permisos);
    }

    /**
     * Crea una respuesta de error en formato JSON.
     */
    private function createErrorResponse(string $message): Response
    {
        $response = new Response();
        $payload = json_encode(["status" => false, "message" => $message]);
        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(200);
    }
}
