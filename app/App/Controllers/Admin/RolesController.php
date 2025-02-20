<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Models\TableModel;
use Exception;

class RolesController extends Controller
{
    private $table = "sis_rol";
    private $id = "idrol";
    private const PERMISSION = "ruta.roles";

    public function __construct()
    {
        parent::__construct();
    }

    public function index($request, $response)
    {
        return $this->render($response, 'App.Usuarios.Roles', [
            'titulo_web' => 'Gestión de Roles',
            "url" => $request->getUri()->getPath(),
            'permisos' => $this->permisos_extras,
            "permission" => self::PERMISSION,
            "js" => [
                "/js/chio/roles.js?v=" . time()
            ]
        ]);
    }

    public function list($request, $response)
    {
        try {
            $data = $this->sanitize($request->getParsedBody());

            $model = new TableModel();
            $model->setTable($this->table);
            $model->setId($this->id);

            $query = $model->select(
                "idrol",
                "rol_cod",
                "rol_nombre",
                "rol_descripcion",
                "rol_estado",
                "rol_fecha"
            );

            // Filtro por estado
            if (!empty($data['filtro_estado'])) {
                $query->where('rol_estado', $data['filtro_estado']);
            }

            // Búsqueda general
            if (!empty($data['filtro_search'])) {
                $search = $data['filtro_search'];
                $query->where(function ($q) use ($search) {
                    $q->where('rol_cod', 'LIKE', "%$search%")
                        ->orWhere('rol_nombre', 'LIKE', "%$search%")
                        ->orWhere('rol_descripcion', 'LIKE', "%$search%");
                });
            }

            $arrData = $query->orderBy('rol_nombre')->get();

            return $this->respondWithJson($response, $arrData);
        } catch (Exception $e) {
            return $this->respondWithError($response, $e->getMessage());
        }
    }

    public function store($request, $response)
    {
        try {
            $this->checkPermission(self::PERMISSION, "create");

            $data = $this->sanitize($request->getParsedBody());

            if (!$this->validateData($data)) {
                return $this->respondWithError($response, "Los campos con (*) son obligatorios");
            }

            $model = new TableModel();
            $model->setTable($this->table);
            $model->setId($this->id);

            // Verificar código duplicado
            $existingRol = $model->where('rol_cod', $data['codigo'])->first();
            if ($existingRol) {
                return $this->respondWithError($response, "El código ya se encuentra registrado");
            }

            $rq = $model->create([
                "rol_cod" => $data["codigo"],
                "rol_nombre" => trim($data["nombre"]),
                "rol_descripcion" => trim($data["descripcion"]),
                "rol_estado" => 1
            ]);

            return $rq
                ? $this->respondWithSuccess($response, "Rol registrado correctamente")
                : $this->respondWithError($response, "Error al registrar el rol");
        } catch (Exception $e) {
            return $this->respondWithError($response, $e->getMessage());
        }
    }

    public function search($request, $response, $args)
    {
        try {
            $this->checkPermission(self::PERMISSION, "update");

            $id = $args['id'];
            $model = new TableModel();
            $model->setTable($this->table);
            $model->setId($this->id);

            $rol = $model
                ->select(
                    "idrol",
                    "rol_cod as codigo",
                    "rol_nombre as nombre",
                    "rol_descripcion as descripcion",
                    "rol_estado as estado"
                )
                ->where("idrol", $id)
                ->first();

            if (!$rol) {
                return $this->respondWithError($response, "Rol no encontrado");
            }

            return $this->respondWithJson($response, [
                "success" => true,
                "rol" => $rol
            ]);
        } catch (Exception $e) {
            return $this->respondWithError($response, $e->getMessage());
        }
    }

    public function update($request, $response, $args)
    {
        try {
            $this->checkPermission(self::PERMISSION, "update");

            $id = $args['id'];
            $data = $this->sanitize($request->getParsedBody());

            if (!$this->validateData($data)) {
                return $this->respondWithError($response, "Los campos con (*) son obligatorios");
            }

            $model = new TableModel();
            $model->setTable($this->table);
            $model->setId($this->id);

            // Verificar código duplicado
            $existingRol = $model->where('rol_cod', $data['codigo'])
                ->where('idrol', '!=', $id)
                ->first();
            if ($existingRol) {
                return $this->respondWithError($response, "El código ya se encuentra registrado en otro rol");
            }

            $rq = $model->update($id, [
                "rol_cod" => $data["codigo"],
                "rol_nombre" => trim($data["nombre"]),
                "rol_descripcion" => trim($data["descripcion"])
            ]);

            return $rq
                ? $this->respondWithSuccess($response, "Rol actualizado correctamente")
                : $this->respondWithError($response, "Error al actualizar el rol");
        } catch (Exception $e) {
            return $this->respondWithError($response, $e->getMessage());
        }
    }

    public function delete($request, $response, $args)
    {
        try {
            $this->checkPermission(self::PERMISSION, "delete");

            $id = $args['id'];
            $model = new TableModel();
            $model->setTable($this->table);
            $model->setId($this->id);

            // Verificar si el rol existe
            $rol = $model->find($id);
            if (!$rol) {
                return $this->respondWithError($response, "Rol no encontrado");
            }

            // Verificar si tiene usuarios asociados
            $usuariosModel = new TableModel();
            $usuariosModel->setTable("sis_usuarios");
            $hasUsers = $usuariosModel->where('idrol', $id)->first();

            if ($hasUsers) {
                return $this->respondWithError($response, "No se puede eliminar el rol porque tiene usuarios asociados");
            }

            $rq = $model->update($id, [
                "rol_estado" => 0
            ]);

            return $rq
                ? $this->respondWithSuccess($response, "Rol eliminado correctamente")
                : $this->respondWithError($response, "Error al eliminar el rol");
        } catch (Exception $e) {
            return $this->respondWithError($response, $e->getMessage());
        }
    }

    private function validateData($data)
    {
        $required = [
            'codigo',
            'nombre'
        ];

        foreach ($required as $field) {
            if (empty($data[$field])) return false;
        }
        return true;
    }
}
