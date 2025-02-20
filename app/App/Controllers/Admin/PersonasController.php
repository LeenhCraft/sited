<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Models\TableModel;
use Exception;

class PersonasController extends Controller
{
    private $table = "sis_personal";
    private $id = "idpersona";
    private const PERMISSION = "ruta.personal";

    public function __construct()
    {
        parent::__construct();
    }

    public function index($request, $response)
    {
        return $this->render($response, 'App.Usuarios.Personal', [
            'titulo_web' => 'Personal',
            "url" => $request->getUri()->getPath(),
            'permisos' => $this->permisos_extras,
            "permission" => self::PERMISSION,
            "css" => [
                "/vendor/select2/select2/dist/css/select2.min.css",
                "/css/select2-custom.css",
            ],
            "js" => [
                "/vendor/select2/select2/dist/js/select2.full.min.js",
                // "/js/admin/nw_personal.js",
                "/js/chio/personas.js?v=" . time()
            ],
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
                "idpersona",
                "per_dni",
                "per_nombre",
                "per_celular",
                "per_email",
                "per_direcc",
                "per_foto",
                "per_estado",
                "per_fecha"
            );

            // Filtro por estado
            if (!empty($data['filtro_estado'])) {
                $query->where('per_estado', $data['filtro_estado']);
            } else {
                $query->where('per_estado', 1);
            }

            // Búsqueda general
            if (!empty($data['filtro_search'])) {
                $search = $data['filtro_search'];
                $query->where(function ($q) use ($search) {
                    $q->where('per_dni', 'LIKE', "%$search%")
                        ->orWhere('per_nombre', 'LIKE', "%$search%")
                        ->orWhere('per_email', 'LIKE', "%$search%")
                        ->orWhere('per_celular', 'LIKE', "%$search%");
                });
            }

            $arrData = $query->orderBy('per_nombre')->get();

            // Verificar si cada persona tiene un usuario asociado
            foreach ($arrData as &$persona) {
                $usuarioModel = new TableModel();
                $usuarioModel->setTable("sis_usuarios");
                $hasUser = $usuarioModel->where('idpersona', $persona['idpersona'])->first();
                $persona['tiene_usuario'] = !empty($hasUser);
            }

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

            if (!$this->validateDNI($data['dni'])) {
                return $this->respondWithError($response, "El DNI debe tener 8 dígitos");
            }

            if (!empty($data['celular']) && !$this->validateCelular($data['celular'])) {
                return $this->respondWithError($response, "El celular debe tener 9 dígitos");
            }

            if (!empty($data['email']) && !$this->validateEmail($data['email'])) {
                return $this->respondWithError($response, "El email no es válido");
            }

            $model = new TableModel();
            $model->setTable($this->table);
            $model->setId($this->id);

            // Verificar DNI duplicado
            $existingPersonal = $model->where('per_dni', $data['dni'])->first();
            if ($existingPersonal) {
                return $this->respondWithError($response, "El DNI ya se encuentra registrado");
            }

            // Procesamiento de la foto si existe
            $fotoPath = null;
            if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
                $fotoPath = $this->procesarFoto($_FILES['foto']);
                if (!$fotoPath) {
                    return $this->respondWithError($response, "Error al procesar la foto");
                }
            }

            $rq = $model->create([
                "per_dni" => $data["dni"],
                "per_nombre" => trim($data["nombre"]),
                "per_celular" => $data["celular"] ?: "0",
                "per_email" => $data["email"] ?? null,
                "per_direcc" => $data["direccion"] ?? null,
                "per_foto" => $fotoPath,
                "per_estado" => "1"
            ]);

            return $rq
                ? $this->respondWithSuccess($response, "Personal registrado correctamente")
                : $this->respondWithError($response, "Error al registrar el personal");
        } catch (Exception $e) {
            return $this->respondWithError($response, $e->getMessage());
        }
    }

    private function procesarFoto($foto)
    {
        try {
            $nombreArchivo = uniqid() . '_' . $foto['name'];
            $rutaDestino = 'uploads/personal/' . $nombreArchivo;

            if (!move_uploaded_file($foto['tmp_name'], $rutaDestino)) {
                return false;
            }

            return $nombreArchivo;
        } catch (Exception $e) {
            return false;
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

            $personal = $model
                ->select(
                    "idpersona",
                    "per_dni as dni",
                    "per_nombre as nombre",
                    "per_celular as celular",
                    "per_email as email",
                    "per_direcc as direccion",
                    "per_foto as foto",
                    "per_estado as estado"
                )
                ->where("idpersona", $id)
                ->first();

            if (!$personal) {
                return $this->respondWithError($response, "Personal no encontrado");
            }

            // Verificar si tiene usuario asociado
            $usuarioModel = new TableModel();
            $usuarioModel->setTable("sis_usuarios");
            $hasUser = $usuarioModel->where('idpersona', $id)->first();
            $personal['tiene_usuario'] = !empty($hasUser);

            return $this->respondWithJson($response, [
                "success" => true,
                "personal" => $personal
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

            if (!$this->validateDNI($data['dni'])) {
                return $this->respondWithError($response, "El DNI debe tener 8 dígitos");
            }

            if (!empty($data['celular']) && !$this->validateCelular($data['celular'])) {
                return $this->respondWithError($response, "El celular debe tener 9 dígitos");
            }

            if (!empty($data['email']) && !$this->validateEmail($data['email'])) {
                return $this->respondWithError($response, "El email no es válido");
            }

            $model = new TableModel();
            $model->setTable($this->table);
            $model->setId($this->id);

            // Verificar DNI duplicado excluyendo el registro actual
            $existingPersonal = $model->where('per_dni', $data['dni'])
                ->where('idpersona', '!=', $id)
                ->first();
            if ($existingPersonal) {
                return $this->respondWithError($response, "El DNI ya se encuentra registrado en otro personal");
            }

            // Obtener datos actuales para la foto
            $currentData = $model->find($id);
            $fotoPath = $currentData['per_foto'];

            // Procesar nueva foto si se subió una
            if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
                $newFotoPath = $this->procesarFoto($_FILES['foto']);
                if (!$newFotoPath) {
                    return $this->respondWithError($response, "Error al procesar la nueva foto");
                }

                // Eliminar foto anterior si existe
                if ($fotoPath && file_exists('uploads/personal/' . $fotoPath)) {
                    unlink('uploads/personal/' . $fotoPath);
                }

                $fotoPath = $newFotoPath;
            }

            $updateData = [
                "per_dni" => $data["dni"],
                "per_nombre" => trim($data["nombre"]),
                "per_celular" => $data["celular"] ?? "0",
                "per_email" => $data["email"] ?? null,
                "per_direcc" => $data["direccion"] ?? null,
            ];

            // Solo actualizar la foto si hay una nueva
            if ($fotoPath) {
                $updateData["per_foto"] = $fotoPath;
            }

            $rq = $model->update($id, $updateData);

            return $rq
                ? $this->respondWithSuccess($response, "Personal actualizado correctamente")
                : $this->respondWithError($response, "Error al actualizar el personal");
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

            // Verificar si tiene usuario asociado
            $usuarioModel = new TableModel();
            $usuarioModel->setTable("sis_usuarios");
            $hasUser = $usuarioModel->where('idpersona', $id)->first();

            if ($hasUser) {
                return $this->respondWithError($response, "No se puede eliminar el personal porque tiene un usuario asociado");
            }

            $personal = $model->find($id);
            if (!$personal) {
                return $this->respondWithError($response, "Personal no encontrado");
            }

            // Eliminar foto si existe
            if ($personal['per_foto'] && file_exists('uploads/personal/' . $personal['per_foto'])) {
                unlink('uploads/personal/' . $personal['per_foto']);
            }

            $rq = $model->update($id, [
                "per_estado" => 0
            ]);

            return $rq
                ? $this->respondWithSuccess($response, "Personal eliminado correctamente")
                : $this->respondWithError($response, "Error al eliminar el personal");
        } catch (Exception $e) {
            return $this->respondWithError($response, $e->getMessage());
        }
    }

    private function validateData($data)
    {
        $required = [
            'dni',
            'nombre'
        ];

        foreach ($required as $field) {
            if (empty($data[$field])) return false;
        }
        return true;
    }

    private function validateDNI($dni)
    {
        return preg_match('/^[0-9]{8}$/', $dni);
    }

    private function validateCelular($celular)
    {
        return preg_match('/^9[0-9]{8}$/', $celular);
    }

    private function validateEmail($email)
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }
}
