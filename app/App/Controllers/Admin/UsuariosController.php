<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Models\TableModel;
use Exception;

class UsuariosController extends Controller
{
    private $table = "sis_usuarios";
    private $id = "idusuario";
    private const PERMISSION = "ruta.usuarios";

    public function __construct()
    {
        parent::__construct();
    }

    public function index($request, $response)
    {
        // Obtener roles para el select
        $modelRol = new TableModel();
        $modelRol->setTable("sis_rol");
        $modelRol->setId("idrol");

        $roles = $modelRol->where('rol_estado', 1)->get();

        return $this->render($response, 'App.Usuarios.Usuarios', [
            'titulo_web' => 'Usuarios',
            "url" => $request->getUri()->getPath(),
            "permisos" => $this->permisos_extras,
            "permission" => self::PERMISSION,
            'roles' => $roles,
            "css" => [
                "/vendor/select2/select2/dist/css/select2.min.css",
                "/css/select2-custom.css",
            ],
            "js" => [
                "/vendor/select2/select2/dist/js/select2.full.min.js",
                // "/js/admin/nw_usu.js",
                "/js/chio/usuarios.js?v=" . time()
            ],
        ]);
    }

    public function list($request, $response)
    {
        try {
            $data = $this->sanitize($request->getParsedBody());

            $model = new TableModel();
            $this->table = "sis_usuarios u";
            $model->setTable($this->table);
            $model->setId($this->id);

            $query = $model->select(
                "u.idusuario",
                "u.usu_usuario",
                "u.usu_activo",
                "u.usu_estado",
                "u.usu_primera",
                "u.usu_twoauth",
                "u.usu_fecha",
                "p.per_nombre",
                "p.per_dni",
                "p.per_foto",
                "r.rol_nombre"
            )
                ->join("sis_personal p", "u.idpersona", "=", "p.idpersona")
                ->join("sis_rol r", "u.idrol", "=", "r.idrol");

            // Filtro por estado
            if (isset($data['filtro_estado'])) {
                $query->where('u.usu_estado', $data['filtro_estado']);
            } else {
                $query->where('u.usu_estado', 1);
            }

            // Filtro por rol
            if (!empty($data['filtro_rol'])) {
                $query->where('u.idrol', $data['filtro_rol']);
            }

            // Búsqueda general
            if (!empty($data['filtro_search'])) {
                $search = $data['filtro_search'];
                $query->where(function ($q) use ($search) {
                    $q->where('u.usu_usuario', 'LIKE', "%$search%")
                        ->orWhere('p.per_nombre', 'LIKE', "%$search%")
                        ->orWhere('p.per_dni', 'LIKE', "%$search%")
                        ->orWhere('r.rol_nombre', 'LIKE', "%$search%");
                });
            }

            $arrData = $query->orderBy('p.per_nombre')->get();
            // dep($model->previewSql(), 1);

            return $this->respondWithJson($response, $arrData);
        } catch (Exception $e) {
            return $this->respondWithError($response, $e->getMessage());
        }
    }

    public function getPersonalSinUsuario($request, $response)
    {
        try {
            $model = new TableModel();
            $model->setTable("sis_personal");
            $model->setId("idpersona");

            // Consulta SQL para obtener personal sin usuario
            $sql = "SELECT p.idpersona, p.per_dni, p.per_nombre 
                    FROM sis_personal p 
                    WHERE p.per_estado = 1 
                    AND p.idpersona NOT IN (
                        SELECT idpersona 
                        FROM sis_usuarios 
                        WHERE usu_estado = 1
                    )
                    ORDER BY p.per_nombre";

            $personal = $model->query($sql)->get();

            return $this->respondWithJson($response, [
                "success" => true,
                "personal" => $personal
            ]);
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

            // Verificar username duplicado
            $existingUser = $model->where('usu_usuario', $data['usuario'])->first();
            if ($existingUser) {
                return $this->respondWithError($response, "El nombre de usuario ya está en uso");
            }

            // Verificar que la persona y el rol existan y estén activos
            if (!$this->validatePersonaRol($data['idpersona'], $data['idrol'])) {
                return $this->respondWithError($response, "La persona o el rol seleccionado no son válidos");
            }

            // Generar hash de la contraseña
            $password = $this->generatePassword();
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);

            $rq = $model->create([
                "idrol" => $data["idrol"],
                "idpersona" => $data["idpersona"],
                "usu_usuario" => strtolower(trim($data["usuario"])),
                "usu_pass" => $passwordHash,
                "usu_activo" => 1,
                "usu_estado" => 1,
                "usu_primera" => 1, // Primera vez que ingresa
                "usu_twoauth" => 0,
                "usu_code_twoauth" => ''
            ]);

            if ($rq) {
                return $this->respondWithJson($response, [
                    "status" => true,
                    "message" => "Usuario registrado correctamente",
                    "password" => $password // Enviar la contraseña temporal
                ]);
            }

            return $this->respondWithError($response, "Error al registrar el usuario");
        } catch (Exception $e) {
            return $this->respondWithError($response, $e->getMessage());
        }
    }

    public function search($request, $response, $args)
    {
        try {
            $this->checkPermission(self::PERMISSION, "update");

            $this->table = "sis_usuarios u";
            $id = $args['id'];
            $model = new TableModel();
            $model->setTable($this->table);
            $model->setId($this->id);

            $usuario = $model
                ->select(
                    "u.idusuario",
                    "u.idrol",
                    "u.idpersona",
                    "u.usu_usuario as usuario",
                    "u.usu_activo as activo",
                    "u.usu_twoauth as twoauth",
                    "p.per_nombre",
                    "p.per_dni",
                    "r.rol_nombre"
                )
                ->join("sis_personal p", "u.idpersona", "=", "p.idpersona")
                ->join("sis_rol r", "u.idrol", "=", "r.idrol")
                ->where("u.idusuario", $id)
                ->first();

            if (!$usuario) {
                return $this->respondWithError($response, "Usuario no encontrado");
            }

            return $this->respondWithJson($response, [
                "success" => true,
                "usuario" => $usuario
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

            if (!$this->validateData($data, false)) {
                return $this->respondWithError($response, "Los campos con (*) son obligatorios");
            }

            $model = new TableModel();
            $model->setTable($this->table);
            $model->setId($this->id);

            // Verificar username duplicado
            $existingUser = $model->where('usu_usuario', $data['usuario'])
                ->where('idusuario', '!=', $id)
                ->first();
            if ($existingUser) {
                return $this->respondWithError($response, "El nombre de usuario ya está en uso");
            }

            // Verificar que el rol exista y esté activo
            if (!$this->validateRol($data['idrol'])) {
                return $this->respondWithError($response, "El rol seleccionado no es válido");
            }

            $updateData = [
                "idrol" => $data["idrol"],
                "usu_usuario" => strtolower(trim($data["usuario"])),
                "usu_activo" => $data["activo"],
                "usu_twoauth" => $data["twoauth"] ?? 0
            ];

            // Si se solicita reset de contraseña
            if (!empty($data['reset_password'])) {
                $password = $this->generatePassword();
                $updateData["usu_pass"] = password_hash($password, PASSWORD_DEFAULT);
                $updateData["usu_primera"] = 1;
            }

            $rq = $model->update($id, $updateData);

            if ($rq) {
                $response = [
                    "status" => true,
                    "message" => "Usuario actualizado correctamente"
                ];

                if (isset($password)) {
                    $response["password"] = $password;
                }

                return $this->respondWithJson($response, $response);
            }

            return $this->respondWithError($response, "Error al actualizar el usuario");
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

            // No permitir eliminar el propio usuario
            if ($id == $_SESSION["app_id"]) {
                return $this->respondWithError($response, "No puede eliminar su propio usuario");
            }

            $rq = $model->update($id, [
                "usu_estado" => 0,
                "usu_activo" => 0
            ]);

            return $rq
                ? $this->respondWithSuccess($response, "Usuario eliminado correctamente")
                : $this->respondWithError($response, "Error al eliminar el usuario");
        } catch (Exception $e) {
            return $this->respondWithError($response, $e->getMessage());
        }
    }

    private function validateData($data, $isNew = true)
    {
        $required = ['idrol', 'usuario'];
        if ($isNew) {
            $required[] = 'idpersona';
        }

        foreach ($required as $field) {
            if (empty($data[$field])) return false;
        }
        return true;
    }

    private function validatePersonaRol($idpersona, $idrol)
    {
        try {
            // Verificar persona
            $modelPersona = new TableModel();
            $modelPersona->setTable("sis_personal");
            $persona = $modelPersona->where('idpersona', $idpersona)
                ->where('per_estado', 1)
                ->first();

            if (!$persona) return false;

            // Verificar rol
            return $this->validateRol($idrol);
        } catch (Exception $e) {
            return false;
        }
    }

    private function validateRol($idrol)
    {
        try {
            $modelRol = new TableModel();
            $modelRol->setTable("sis_rol");
            $rol = $modelRol->where('idrol', $idrol)
                ->where('rol_estado', 1)
                ->first();

            return !empty($rol);
        } catch (Exception $e) {
            return false;
        }
    }

    private function generatePassword($length = 8)
    {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $password = '';
        for ($i = 0; $i < $length; $i++) {
            $password .= $chars[rand(0, strlen($chars) - 1)];
        }
        return $password;
    }
}
