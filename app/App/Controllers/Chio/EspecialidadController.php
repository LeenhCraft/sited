<?php

namespace App\Controllers\Chio;

use App\Core\Controller;
use App\Models\TableModel;
use Exception;

class EspecialidadController extends Controller
{
    private $table = "sd_especialidades";
    private $id = "idespecialidad";
    private const PERMISSION = "ruta.especialidades";

    public function __construct()
    {
        parent::__construct();
    }

    public function index($request, $response)
    {
        return $this->render($response, 'Chio.Especialidades.Especialidades', [
            'titulo_web' => 'Gestión de Especialidades',
            "url" => $request->getUri()->getPath(),
            'permisos' => $this->permisos_extras,
            "permission" => self::PERMISSION,
            "js" => [
                "/js/chio/especialidades.js?v=" . time()
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
                "idespecialidad",
                "nombre",
                "descripcion",
                "fecha_registro",
                "ultima_actualizacion",
                "eliminado",
                "fecha_eliminacion"
            );

            // Filtro por estado
            if (!empty($data['filtro_estado'])) {
                $query->where('eliminado', $data['filtro_estado']);
            } else {
                $query->where('eliminado', 0);
            }

            // Búsqueda general
            if (!empty($data['filtro_search'])) {
                $search = $data['filtro_search'];
                $query->where(function ($q) use ($search) {
                    $q->where('nombre', 'LIKE', "%$search%")
                        ->orWhere('descripcion', 'LIKE', "%$search%");
                });
            }

            $arrData = $query->orderBy('nombre')->get();

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

            // Verificar nombre duplicado
            $exists = $model->where('nombre', trim($data['nombre']))
                ->where('eliminado', 0)
                ->first();

            if ($exists) {
                return $this->respondWithError($response, "Ya existe una especialidad con este nombre");
            }

            $rq = $model->create([
                "nombre" => trim($data["nombre"]),
                "descripcion" => trim($data["descripcion"]),
                "creado_por" => $_SESSION["app_id"]
            ]);

            return $rq
                ? $this->respondWithSuccess($response, "Especialidad registrada correctamente")
                : $this->respondWithError($response, "Error al registrar la especialidad");
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

            $especialidad = $model
                ->where("idespecialidad", $id)
                ->where('eliminado', 0)
                ->first();

            if (!$especialidad) {
                return $this->respondWithError($response, "Especialidad no encontrada");
            }

            return $this->respondWithJson($response, [
                "success" => true,
                "especialidad" => $especialidad
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

            // Verificar nombre duplicado excluyendo el registro actual
            $exists = $model->where('nombre', trim($data['nombre']))
                ->where('eliminado', 0)
                ->where('idespecialidad', '!=', $id)
                ->first();

            if ($exists) {
                return $this->respondWithError($response, "Ya existe otra especialidad con este nombre");
            }

            $rq = $model->update($id, [
                "nombre" => trim($data["nombre"]),
                "descripcion" => trim($data["descripcion"]),
                "ultima_actualizacion" => date('Y-m-d H:i:s'),
                "actualizado_por" => $_SESSION["app_id"]
            ]);

            return $rq
                ? $this->respondWithSuccess($response, "Especialidad actualizada correctamente")
                : $this->respondWithError($response, "Error al actualizar la especialidad");
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

            // Verificar si existe y no está eliminada
            $especialidad = $model->where('idespecialidad', $id)
                ->where('eliminado', 0)
                ->first();

            if (!$especialidad) {
                return $this->respondWithError($response, "Especialidad no encontrada");
            }

            // Verificar si la especialidad está siendo utilizada por algún personal
            $personalEspecialidad = $model
                ->query("SELECT COUNT(*) as total FROM sd_personal_especialidad WHERE idespecialidad = ?", [$id])
                ->first();

            if ($personalEspecialidad && $personalEspecialidad["total"] > 0) {
                return $this->respondWithError($response, "No se puede eliminar la especialidad porque está asociada a personal médico");
            }

            $rq = $model->update($id, [
                "eliminado" => 1,
                "fecha_eliminacion" => date('Y-m-d H:i:s'),
                "eliminado_por" => $_SESSION["app_id"]
            ]);

            return $rq
                ? $this->respondWithSuccess($response, "Especialidad eliminada correctamente")
                : $this->respondWithError($response, "Error al eliminar la especialidad");
        } catch (Exception $e) {
            return $this->respondWithError($response, $e->getMessage());
        }
    }

    private function validateData($data)
    {
        return !empty($data['nombre']);
    }
}
