<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Models\TableModel;

class PermisosEspecialesController extends Controller
{
    protected $permisos = [];

    public function __construct()
    {
        parent::__construct();
        $this->permisos = getPermisos($this->className($this));
    }

    public function index($request, $response, $args)
    {
        return $this->render($response, 'App.Permisos.PermisosAdicionales', [
            'titulo_web' => 'Permisos Especiales:.',
            "url" => $request->getUri()->getPath(),
            "css" => [
                "/vendor/select2/select2/dist/css/select2.min.css",
                "/css/select2-bootstrap4.min.css",
                "/css/select2.min.css",
                "/css/select2-custom.css",
            ],
            'js' => [
                // '/js/admin/permisosEspeciales.js' . '?v=' . time()
                "/vendor/select2/select2/dist/js/select2.full.min.js",
            ],
            "permisos" => $this->permisos
        ]);
    }

    public function getAcciones($request, $response)
    {
        $model = new TableModel();
        $model->setTable("sis_acciones");
        $model->setId("idaccion");

        return $this->respondWithJson($response, $model
            ->select(
                "idaccion as id",
                "nombre",
                "identificador",
                "descripcion",
                "estado"
            )
            ->get());
    }

    public function getRecursos($request, $response)
    {
        $model = new TableModel();
        $model->setTable("sis_recursos");
        $model->setId("idrecurso");
        return $this->respondWithJson($response, $model
            ->select(
                "idrecurso as id",
                "nombre",
                "tipo",
                "identificador",
                "estado"
            )
            ->orderBy("id")
            ->get());
    }

    public function getRoles($request, $response)
    {
        $model = new TableModel();
        $model->setTable("sis_rol");
        $model->setId("idrol");
        return $this->respondWithJson($response, $model
            ->select(
                "idrol as id",
                "rol_nombre as nombre",
                "rol_estado as estado"
            )
            ->orderBy("rol_nombre")
            ->get());
    }

    public function getPermisosPorRol($request, $response, $args)
    {
        $data = $this->sanitize($args);
        $model = new TableModel();
        $model->setTable("sis_permisos_extras");
        $model->setId("idpermiso");
        $arrData = $model
            ->select(
                "sis_permisos_extras.idpermiso as id",
                "sis_permisos_extras.idrecurso",
                "sis_permisos_extras.idaccion",
                "sis_permisos_extras.estado",
                "sis_recursos.nombre as recurso",
                "sis_acciones.nombre as accion"
            )
            ->join("sis_recursos", "sis_recursos.idrecurso", "sis_permisos_extras.idrecurso and sis_recursos.estado = 1")
            ->join("sis_acciones", "sis_acciones.idaccion", "sis_permisos_extras.idaccion and sis_acciones.estado = 1")
            ->where("sis_permisos_extras.idrol", $data["id"])
            ->orderBy("sis_recursos.nombre")
            ->orderBy("sis_acciones.nombre")
            ->get();
        return $this->respondWithJson($response, $arrData);
    }

    public function searchRecurso($request, $response, $args)
    {
        $data = $this->sanitize($args);
        $model = new TableModel();
        $model->setTable("sis_recursos");
        $model->setId("idrecurso");
        $existe = $model
            ->where("idrecurso", $data["id"])
            ->first();
        if ($existe) {
            $arrData["idRecurso"] = $existe["idrecurso"];
            $arrData["nombreRecurso"] = $existe["nombre"];
            $arrData["tipoRecurso"] = $existe["tipo"];
            $arrData["identificadorRecurso"] = $existe["identificador"];
            $arrData["estadoRecurso"] = $existe["estado"];
            return $this->respondWithJson($response, $arrData);
        }
        return $this->respondWithError($response, "Recurso no encontrado");
    }

    public function searchAccion($request, $response, $args)
    {
        $data = $this->sanitize($args);
        $model = new TableModel();
        $model->setTable("sis_acciones");
        $model->setId("idaccion");
        $existe = $model
            ->where("idaccion", $data["id"])
            ->first();
        if ($existe) {
            $arrData["idAccion"] = $existe["idaccion"];
            $arrData["nombreAccion"] = $existe["nombre"];
            $arrData["identificadorAccion"] = $existe["identificador"];
            $arrData["descripcionAccion"] = $existe["descripcion"];
            $arrData["estadoAccion"] = $existe["estado"];
            return $this->respondWithJson($response, $arrData);
        }
        return $this->respondWithError($response, "Recurso no encontrado");
    }

    public function storeRecurso($request, $response)
    {
        $data = $this->sanitize($request->getParsedBody());
        if (isset($data["idRecurso"]) && $data["idRecurso"] != "" && $data["idRecurso"] != null) {
            return $this->updateRecurso($request, $response);
        }
        $model = new TableModel();
        $model->setTable("sis_recursos");
        $model->setId("idrecurso");
        $existe = $model
            ->where("identificador", $data["identificadorRecurso"])
            ->where("estado", "1")
            ->get();
        if (!empty($existe)) {
            return $this->respondWithError($response, "El recurso ya existe");
        }

        $rq = $model->create([
            "nombre" => $data["nombreRecurso"],
            "descripcion" => null,
            "tipo" => $data["tipoRecurso"],
            "identificador" => $data["identificadorRecurso"],
            "estado" => $data["estadoRecurso"],
            "fecha_registro" => date("Y-m-d H:i:s"),
        ]);
        if ($rq) {
            return $this->respondWithSuccess($response, "Recurso creado correctamente");
        }
        return $this->respondWithError($response, "Error al crear recurso");
    }

    public function storeAccion($request, $response)
    {
        $data = $this->sanitize($request->getParsedBody());
        if (isset($data["idAccion"]) && $data["idAccion"] != "" && $data["idAccion"] != null) {
            return $this->updateAcciones($request, $response);
        }
        $model = new TableModel();
        $model->setTable("sis_acciones");
        $model->setId("idaccion");
        $existe = $model
            ->where("nombre", $data["nombreAccion"])
            ->where("estado", "1")
            ->get();
        if (!empty($existe)) {
            return $this->respondWithError($response, "La accion ya existe");
        }

        $rq = $model->create([
            "nombre" => $data["nombreAccion"],
            "identificador" => $data["identificadorAccion"],
            "descripcion" => $data["descripcionAccion"],
            "estado" => $data["estadoAccion"],
        ]);
        if ($rq) {
            return $this->respondWithSuccess($response, "Accion creado correctamente");
        }
        return $this->respondWithError($response, "Error al crear la accion");
    }

    public function storePermiso($request, $response)
    {
        $data = $this->sanitize($request->getParsedBody());
        if (empty($data["rolId"]) || empty($data["recursoId"]) || empty($data["accionId"])) {
            return $this->respondWithError($response, "Todos los campos con * son obligatorios");
        }
        $model = new TableModel();
        $model->setTable("sis_permisos_extras");
        $model->setId("idpermiso");
        $existe = $model
            ->where("idrol", $data["rolId"])
            ->where("idrecurso", $data["recursoId"])
            ->where("idaccion", $data["accionId"])
            ->first();
        if (!empty($existe)) {
            return $this->respondWithError($response, "El permiso ya existe");
        }
        $rq = $model->create([
            "idrol" => $data["rolId"],
            "idrecurso" => $data["recursoId"],
            "idaccion" => $data["accionId"],
            "estado" => $data["estado"],
            "fecha_registro" => date("Y-m-d H:i:s"),
        ]);
        if ($rq) {
            return $this->respondWithSuccess($response, "Permiso creado correctamente");
        }
        return $this->respondWithError($response, "Error al crear permiso");
    }

    public function updateRecurso($request, $response)
    {
        $data = $this->sanitize($request->getParsedBody());
        $model = new TableModel();
        $model->setTable("sis_recursos");
        $model->setId("idrecurso");
        $existe = $model
            ->where("idrecurso", "!=", $data["idRecurso"])
            ->where("identificador", $data["identificadorRecurso"])
            ->where("estado", "1")
            ->get();
        if (!empty($existe)) {
            return $this->respondWithError($response, "Existe un recursos con el mismo identificador");
        }

        $rq = $model->update($data["idRecurso"], [
            "nombre" => $data["nombreRecurso"],
            "descripcion" => null,
            "tipo" => $data["tipoRecurso"],
            "identificador" => $data["identificadorRecurso"],
            "estado" => $data["estadoRecurso"],
        ]);
        if ($rq) {
            return $this->respondWithSuccess($response, "Recurso actualizado correctamente");
        }
        return $this->respondWithError($response, "Error al crear recurso");
    }

    public function updateAcciones($request, $response)
    {
        $data = $this->sanitize($request->getParsedBody());
        $model = new TableModel();
        $model->setTable("sis_acciones");
        $model->setId("idaccion");
        $existe = $model
            ->where("idaccion", "!=", $data["idAccion"])
            ->where("nombre", $data["nombreAccion"])
            ->where("estado", "1")
            ->get();
        if (!empty($existe)) {
            return $this->respondWithError($response, "Existe una accion con el mismo nombre");
        }

        $rq = $model->update($data["idAccion"], [
            "nombre" => $data["nombreAccion"],
            "identificador" => $data["identificadorAccion"],
            "descripcion" => $data["descripcionAccion"],
            "estado" => $data["estadoAccion"],
        ]);
        if ($rq) {
            return $this->respondWithSuccess($response, "Accion actualizado correctamente");
        }
        return $this->respondWithError($response, "Error al actualizar la accion");
    }

    public function updatePermiso($request, $response)
    {
        $data = $this->sanitize($request->getParsedBody());
        if (empty($data["rolId"]) || empty($data["recursoId"]) || empty($data["accionId"])) {
            return $this->respondWithError($response, "Todos los campos con * son obligatorios");
        }
        $model = new TableModel();
        $model->setTable("sis_permisos_extras");
        $model->setId("idpermiso");
        $existe = $model
            ->where("idrol", $data["rolId"])
            ->where("idrecurso", $data["recursoId"])
            ->where("idaccion", $data["accionId"])
            ->first();
        if (empty($existe)) {
            return $this->respondWithError($response, "No existe el permiso");
        }
        $rq = $model->update($existe["idpermiso"], [
            "estado" => $data["estado"],
        ]);
        if ($rq) {
            return $this->respondWithSuccess($response, "Permiso creado correctamente");
        }
        return $this->respondWithError($response, "Error al crear permiso");
    }

    public function deleteRecurso($request, $response)
    {
        $data = $this->sanitize($request->getParsedBody());
        $model = new TableModel();
        $model->setTable("sis_recursos");
        $model->setId("idrecurso");
        $tienePermisos = $model
            ->join("sis_permisos_extras", "sis_permisos_extras.idrecurso", "sis_recursos.idrecurso")
            ->where("sis_recursos.idrecurso", $data["idRecurso"])
            ->get();
        if (!empty($tienePermisos)) {
            return $this->respondWithError($response, "El recurso tiene permisos asignados");
        }
        $rq = $model->delete($data["idRecurso"]);
        if ($rq) {
            return $this->respondWithSuccess($response, "Recurso eliminado correctamente");
        }
        return $this->respondWithError($response, "Error al eliminar recurso");
    }

    public function deleteAccion($request, $response)
    {
        $data = $this->sanitize($request->getParsedBody());
        $model = new TableModel();
        $model->setTable("sis_acciones");
        $model->setId("idaccion");
        $tienePermisos = $model
            ->join("sis_permisos_extras", "sis_permisos_extras.idaccion", "sis_acciones.idaccion")
            ->where("sis_acciones.idaccion", $data["idAccion"])
            ->get();
        if (!empty($tienePermisos)) {
            return $this->respondWithError($response, "La accion tiene permisos asignados");
        }
        $rq = $model->delete($data["idAccion"]);
        if ($rq) {
            return $this->respondWithSuccess($response, "Accion eliminado correctamente");
        }
        return $this->respondWithError($response, "Error al eliminar la accion");
    }

    public function deletePermiso($request, $response)
    {
        $data = $this->sanitize($request->getParsedBody());
        if (empty($data['rolId']) || empty($data['recursoId']) || empty($data['accionId'])) {
            return $response->withJson([
                'status' => false,
                'message' => 'Faltan datos requeridos'
            ]);
        }
        $rolId = (int)$data['rolId'];
        $recursoId = (int)$data['recursoId'];
        $accionId = (int)$data['accionId'];
        $model = new TableModel();
        $model->setTable("sis_permisos_extras");
        $model->setId("idpermiso");
        $registro = $model
            ->where('idrol', $rolId)
            ->where('idrecurso', $recursoId)
            ->where('idaccion', $accionId)
            ->first();
        if (empty($registro)) {
            return $this->respondWithError($response, "No se encontró el permiso");
        }
        $rq = $model->delete($registro['idpermiso']);
        if ($rq) {
            return $this->respondWithSuccess($response, "Permiso eliminado correctamente");
        }
        return $this->respondWithError($response, "Error al eliminar el permiso");
    }
}
