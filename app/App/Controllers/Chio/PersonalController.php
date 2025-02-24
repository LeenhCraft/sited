<?php

namespace App\Controllers\Chio;

use App\Core\Controller;
use App\Models\TableModel;
use Exception;

class PersonalController extends Controller
{
    private $table = "sd_personal_medico";
    private $id = "idpersonal";
    private const PERMISSION = "ruta.medicos";

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
        $roles = $modelRol
            ->where('rol_estado', "1")
            ->get();

        return $this->render($response, 'Chio.Personal.Medicos', [
            'titulo_web' => 'Gestión de Personal Médico',
            "url" => $request->getUri()->getPath(),
            'permisos' => $this->permisos_extras,
            "permission" => self::PERMISSION,
            "css" => [
                "/vendor/select2/select2/dist/css/select2.min.css",
                "/css/select2-custom.css",
            ],
            "js" => [
                "/vendor/select2/select2/dist/js/select2.full.min.js",
                "/js/chio/personal.js?v=" . time()
            ],
            'roles' => $roles,
        ]);
    }

    public function list($request, $response)
    {
        try {
            $data = $this->sanitize($request->getParsedBody());

            $this->table = "sd_personal_medico pm";
            $model = new TableModel();
            $model->setTable($this->table);
            $model->setId($this->id);

            $query = $model->select(
                "pm.idpersonal",
                "pm.dni",
                "pm.nombre",
                "pm.celular",
                "pm.edad",
                "pm.sexo",
                "pm.direccion",
                "pm.fecha_registro",
                "pm.ultima_actualizacion",
                "pm.eliminado",
                "pm.fecha_eliminacion",
                "e.nombre as especialidad"
            )
                ->leftJoin("sd_personal_especialidad pe", "pe.idpersonal", "pm.idpersonal")
                ->leftJoin("sd_especialidades e", "e.idespecialidad", "pe.idespecialidad");

            // Aplicar filtros
            if (
                isset($this->permisos_extras[self::PERMISSION]["developer"]) &&
                $this->permisos_extras[self::PERMISSION]["developer"] == "1"
            ) {
                if (!empty($data['filtro_estado'])) {
                    switch ($data['filtro_estado']) {
                        case 'activos':
                            $query->where('pm.eliminado', "0");
                            break;
                        case 'eliminados':
                            $query->where('pm.eliminado', "1");
                            break;
                    }
                } else {
                    $query->where('pm.eliminado', "0");
                }
            } else {
                $query->where('pm.eliminado', "0");
            }

            // Filtros de fecha
            if (!empty($data['fecha_inicio'])) {
                $query->where('pm.fecha_registro', '>=', $data['fecha_inicio'] . ' 00:00:00');
            }
            if (!empty($data['fecha_fin'])) {
                $query->where('pm.fecha_registro', '<=', $data['fecha_fin'] . ' 23:59:59');
            }

            // Filtro por sexo
            if (!empty($data['filtro_sexo'])) {
                $query->where('pm.sexo', $data['filtro_sexo']);
            }

            // Filtro por especialidad
            if (!empty($data['filtro_especialidad'])) {
                $query->where('e.idespecialidad', $data['filtro_especialidad']);
            }

            // Búsqueda general
            if (!empty($data['filtro_search'])) {
                $search = $data['filtro_search'];
                $query->where(function ($q) use ($search) {
                    $q->where('pm.dni', 'LIKE', "%$search%")
                        ->orWhere('pm.nombre', 'LIKE', "%$search%")
                        ->orWhere('pm.celular', 'LIKE', "%$search%")
                        ->orWhere('e.idespecialidad', 'LIKE', "%$search%");
                });
            }

            $arrData = $query->orderBy('pm.nombre')->get();

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

            if (!$this->validateDNI($data['documento'])) {
                return $this->respondWithError($response, "El DNI debe tener 8 dígitos");
            }

            if (!$this->validateCelular($data['celular'])) {
                return $this->respondWithError($response, "El celular debe tener 9 dígitos");
            }

            $marcaTiempo = date('Y-m-d H:i:s');

            try {
                // Verificar DNI duplicado en sd_personal_medico
                $modelMedico = new TableModel();
                $modelMedico->setTable($this->table);
                $modelMedico->setId($this->id);

                $personal = $modelMedico
                    ->where('dni', $data['documento'])
                    ->where("eliminado", "0")
                    ->first();
                if ($personal) {
                    throw new Exception("El DNI ya se encuentra registrado en personal médico");
                }

                // 1. Insertar en sis_personal
                $modelPersonal = new TableModel();
                $modelPersonal->setTable("sis_personal");
                $modelPersonal->setId("idpersona");

                // Verificar DNI duplicado en sis_personal
                $existingPersonal = $modelPersonal
                    ->where('per_dni', $data['documento'])
                    ->where("per_estado", "1")
                    ->first();

                $idPersona = $existingPersonal ? $existingPersonal : null;
                if ($existingPersonal) {
                    throw new Exception("El DNI ya se encuentra registrado en personal del sistema");
                }

                if (!$existingPersonal) {
                    $idPersona = $modelPersonal->create([
                        "per_dni" => $data["documento"],
                        "per_nombre" => trim($data["nombre"]),
                        "per_celular" => $data["celular"],
                        "per_direcc" => $data["direccion"],
                        "per_email" => null, // No es obligatorio
                        "per_foto" => null, // Se inicializa como null
                        "per_estado" => "1",
                        "per_fecha" => $marcaTiempo
                    ]);

                    if (!$idPersona) {
                        throw new Exception("Error al registrar en sis_personal");
                    }
                }

                $password = null;
                if (isset($data["crear_usuario"]) && $data["crear_usuario"] == "on") {

                    // validar datos de usuario
                    if (empty($data["usuario"])) {
                        throw new Exception("El nombre de usuario es obligatorio");
                    }

                    if (empty($data["idrol"])) {
                        throw new Exception("El rol de usuario es obligatorio");
                    }

                    // 2. Insertar en sis_usuarios
                    $modelUsuario = new TableModel();
                    $modelUsuario->setTable("sis_usuarios");
                    $modelUsuario->setId("idusuario");

                    // Verificar si ya existe el usuario
                    $existingUser = $modelUsuario
                        ->where('usu_usuario', $data["usuario"])
                        ->where('usu_estado', "1")
                        ->where('usu_activo', "1")
                        ->first();

                    if ($existingUser) {
                        throw new Exception("El nombre de usuario ya existe");
                    }

                    // Generar contraseña
                    $password = $this->generatePassword();
                    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

                    $idUsuario = $modelUsuario->create([
                        "idrol" => $data["idrol"],
                        "idpersona" => $idPersona["idpersona"],
                        "usu_usuario" => $data["usuario"],
                        "usu_pass" => $passwordHash,
                        "usu_token" => null,
                        "usu_activo" => "1",
                        "usu_estado" => "1",
                        "usu_primera" => "1",
                        "usu_twoauth" => "0",
                        "usu_code_twoauth" => "",
                        "usu_fecha" => $marcaTiempo
                    ]);

                    if (!$idUsuario) {
                        throw new Exception("Error al registrar en sis_usuarios");
                    }
                }

                // 3. Insertar en sd_personal_medico
                $idMedico = $modelMedico->create([
                    "dni" => $data["documento"],
                    "nombre" => trim($data["nombre"]),
                    "celular" => $data["celular"],
                    "edad" => $data["edad"],
                    "sexo" => $data["sexo"],
                    "direccion" => $data["direccion"],
                    "fecha_registro" => $marcaTiempo,
                    "creado_por" => $_SESSION["app_id"]
                ]);

                if (!$idMedico) {
                    throw new Exception("Error al registrar en sd_personal_medico");
                }

                // 4. Registrar especialidad
                $modelEspecialidad = new TableModel();
                $modelEspecialidad->setTable("sd_personal_especialidad");
                $modelEspecialidad->setId("id_per_esp");

                $modelEspecialidad->create([
                    "idespecialidad" => $data["especialidad"],
                    "idpersonal" => $idMedico["idpersonal"],
                ]);

                return $this->respondWithJson($response, [
                    "status" => true,
                    "message" => "Personal médico registrado correctamente",
                    "password" => $password // Esta contraseña deberá ser mostrada al usuario
                ]);
            } catch (Exception $e) {
                // Si hay error, revertir la transacción
                // $modelPersonal->rollBack();
                throw $e;
            }
        } catch (Exception $e) {
            return $this->respondWithError($response, $e->getMessage());
        }
    }

    public function search($request, $response, $args)
    {
        try {
            $this->checkPermission(self::PERMISSION, "update");

            $id = $args['id'];
            $this->table = "sd_personal_medico pm";
            $model = new TableModel();
            $model->setTable($this->table);
            $model->setId($this->id);

            $model
                ->select(
                    "pm.idpersonal",
                    "pm.dni as documento",
                    "pm.nombre",
                    "pm.celular",
                    "pm.edad",
                    "pm.sexo",
                    "pm.direccion",
                    "e.idespecialidad",
                    "e.nombre as especialidad",
                )
                ->leftJoin("sd_personal_especialidad pe", "pe.idpersonal", "pm.idpersonal")
                ->leftJoin("sd_especialidades e", "e.idespecialidad", "pe.idespecialidad")
                ->where("pm.idpersonal", $id);
            if (
                !isset($this->permisos_extras[self::PERMISSION]["developer"]) ||
                $this->permisos_extras[self::PERMISSION]["developer"] != "1"
            ) {
                $model->where('pm.eliminado', "0");
            }

            $personal = $model->first();

            // Buscar en sis_personal
            $modelPersonal = new TableModel();
            $modelPersonal->setTable("sis_personal");
            $modelPersonal->setId("idpersona");

            $sisPersonal = $modelPersonal
                ->where('per_dni', $personal['documento'])
                ->where("per_estado", "1")
                ->first();
            // Buscar en sis_usuarios si existe el personal
            $datosUsuario = [];
            if ($sisPersonal) {
                $modelUsuario = new TableModel();
                $modelUsuario->setTable("sis_usuarios");
                $modelUsuario->setId("idusuario");

                $usuario = $modelUsuario
                    ->where('idpersona', $sisPersonal['idpersona'])
                    ->where("usu_estado", "1")
                    ->first();

                if ($usuario) {
                    $datosUsuario = [
                        "idrol" => $usuario['idrol'],
                        "usuario" => $usuario['usu_usuario']
                    ];
                }
            }

            if (!$personal) {
                return $this->respondWithError($response, "Personal médico no encontrado");
            }

            // Combinar la información
            $personal = array_merge($personal, $datosUsuario);

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

            if (!$this->validateDNI($data['documento'])) {
                return $this->respondWithError($response, "El DNI debe tener 8 dígitos");
            }

            if (!$this->validateCelular($data['celular'])) {
                return $this->respondWithError($response, "El celular debe tener 9 dígitos");
            }

            try {
                // 1. Buscar el personal médico actual
                $modelMedico = new TableModel();
                $modelMedico->setTable($this->table);
                $modelMedico->setId($this->id);

                $personalMedico = $modelMedico->find($id);
                if (!$personalMedico) {
                    throw new Exception("Personal médico no encontrado");
                }

                // Verificar si existe otro personal con el mismo DNI (excluyendo el actual)
                $existingPersonal = $modelMedico
                    ->where('dni', $data['documento'])
                    ->where('idpersonal', '!=', $id)
                    ->where("eliminado", "0")
                    ->first();
                if ($existingPersonal) {
                    throw new Exception("El DNI ya se encuentra registrado en otro personal médico");
                }

                // 2. Actualizar en sis_personal
                $modelPersonal = new TableModel();
                $modelPersonal->setTable("sis_personal");
                $modelPersonal->setId("idpersona");

                // Buscar el registro en sis_personal por DNI
                $personal = $modelPersonal
                    ->where('per_dni', $personalMedico['dni'])
                    ->where("per_estado", "1")
                    ->first();
                if (!$personal) {
                    throw new Exception("Registro de personal no encontrado en el sistema");
                }

                // Verificar si hay otro personal con el mismo DNI nuevo
                if ($personalMedico['dni'] != $data['documento']) {
                    $existingPersonalSys = $modelPersonal
                        ->where('per_dni', $data['documento'])
                        ->where('idpersona', '!=', $personal['idpersona'])
                        ->where("per_estado", "1")
                        ->first();
                    if ($existingPersonalSys) {
                        throw new Exception("El DNI ya se encuentra registrado en otro personal del sistema");
                    }
                }

                // Actualizar sis_personal
                $modelPersonal->update($personal['idpersona'], [
                    "per_dni" => $data["documento"],
                    "per_nombre" => trim($data["nombre"]),
                    "per_celular" => $data["celular"],
                    "per_direcc" => $data["direccion"]
                ]);

                // 3. Actualizar sis_usuarios (solo si se cambió el rol)
                $modelUsuario = new TableModel();
                $modelUsuario->setTable("sis_usuarios");
                $modelUsuario->setId("idusuario");

                // Buscar usuario por idpersona
                $usuario = $modelUsuario
                    ->where('idpersona', $personal['idpersona'])
                    ->where("usu_estado", "1")
                    ->first();
                if ($usuario) {
                    // Verificar si el nombre de usuario ya existe (excluyendo el actual)
                    if ($usuario['usu_usuario'] != $data['usuario']) {
                        $existingUsername = $modelUsuario
                            ->where('usu_usuario', $data['usuario'])
                            ->where('idusuario', '!=', $usuario['idusuario'])
                            ->where("usu_estado", "1")
                            ->first();
                        if ($existingUsername) {
                            throw new Exception("El nombre de usuario ya existe");
                        }
                    }

                    // Actualizar usuario
                    $modelUsuario->update($usuario['idusuario'], [
                        "idrol" => $data["idrol"],
                        "usu_usuario" => $data["usuario"]
                    ]);
                }

                // 4. Actualizar sd_personal_medico
                $marcaTiempo = date('Y-m-d H:i:s');
                $rq = $modelMedico->update($id, [
                    "dni" => $data["documento"],
                    "nombre" => trim($data["nombre"]),
                    "celular" => $data["celular"],
                    "edad" => $data["edad"],
                    "sexo" => $data["sexo"],
                    "direccion" => $data["direccion"],
                    "ultima_actualizacion" => $marcaTiempo,
                    "actualizado_por" => $_SESSION["app_id"],
                ]);

                // 5. Atualiizar especialidad
                $modelEspecialidad = new TableModel();
                $modelEspecialidad->setTable("sd_personal_especialidad");
                $modelEspecialidad->setId("id_per_esp");

                $especialidad = $modelEspecialidad
                    ->where('idpersonal', $id)
                    ->first();
                if ($especialidad) {
                    $modelEspecialidad->update($especialidad['id_per_esp'], [
                        "idespecialidad" => $data["especialidad"]
                    ]);
                }

                return $this->respondWithSuccess($response, "Personal médico actualizado correctamente");
            } catch (Exception $e) {
                throw $e;
            }
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

            $personal = $model
                ->where('idpersonal', $id)
                ->where('eliminado', "0")
                ->first();
            if (!$personal) {
                return $this->respondWithError($response, "Personal médico no encontrado");
            }

            // 2. Buscar en sis_personal por DNI
            $modelPersonal = new TableModel();
            $modelPersonal->setTable("sis_personal");
            $modelPersonal->setId("idpersona");

            $sisPersonal = $modelPersonal
                ->where('per_dni', $personal['dni'])
                ->where("per_estado", "1")
                ->first();
            if (!$sisPersonal) {
                throw new Exception("No se encontró el registro en el sistema de personal");
            }

            // 3. Buscar usuario por idpersona
            $modelUsuario = new TableModel();
            $modelUsuario->setTable("sis_usuarios");
            $modelUsuario->setId("idusuario");

            $usuario = $modelUsuario
                ->where('idpersona', $sisPersonal['idpersona'])
                ->where("usu_estado", "1")
                ->first();
            if ($usuario) {
                // 5. Desactivar en sis_usuarios
                $modelUsuario->update($usuario['idusuario'], [
                    "usu_activo" => "0",
                    "usu_estado" => "0"
                ]);
            }

            $marcaTiempo = date('Y-m-d H:i:s');

            // 4. Desactivar en sis_personal
            $modelPersonal->update($sisPersonal['idpersona'], [
                "per_estado" => "0"
            ]);

            // 6. Marcar como eliminado en sd_personal_medico
            $rq = $model->update($id, [
                "eliminado" => "1",
                "fecha_eliminacion" => $marcaTiempo,
                "eliminado_por" => $_SESSION["app_id"]
            ]);

            return $rq
                ? $this->respondWithSuccess($response, "Personal médico eliminado correctamente")
                : $this->respondWithError($response, "Error al eliminar el personal médico");
        } catch (Exception $e) {
            return $this->respondWithError($response, $e->getMessage());
        }
    }

    public function search_select($request, $response)
    {
        try {
            $data = $this->sanitize($request->getParsedBody());
            $search = $data['search'] ?? '';

            $model = new TableModel();
            $model->setTable("sd_especialidades");
            $model->setId("idespecialidad");

            $query = $model->select("idespecialidad as id", "nombre as text")
                ->where('eliminado', "0");

            if (!empty($search)) {
                $query->where('nombre', 'LIKE', "%$search%");
            }

            $results = $query->orderBy('nombre')->get();

            return $this->respondWithJson($response, [
                'results' => $results
            ]);
        } catch (Exception $e) {
            return $this->respondWithError($response, $e->getMessage());
        }
    }

    private function validateData($data)
    {
        $required = [
            'documento',
            'nombre',
            'celular',
            'edad',
            'sexo',
            'direccion',
            'especialidad',
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

    public function generatePDF($request, $response, $args)
    {
        try {
            $this->checkPermission(self::PERMISSION, "print");
            $id = $args['id'];
            $model = new TableModel();
            $model->setTable($this->table);
            $model->setId($this->id);

            $personal = $model
                ->select(
                    "idpersonal",
                    "dni as documento",
                    "nombre",
                    "celular",
                    "edad",
                    "sexo",
                    "direccion",
                    "'especialidad' as especialidad",
                    "fecha_registro"
                )
                ->where("idpersonal", $id)
                ->first();

            if (!$personal) {
                return $this->respondWithError($response, "Personal médico no encontrado");
            }

            $mpdf = new \Mpdf\Mpdf([
                'margin_left' => 20,
                'margin_right' => 20,
                'margin_top' => 20,
                'margin_bottom' => 20,
            ]);

            $css = '
            .header { 
                text-align: center;
                margin-bottom: 20px;
            }
            .logo {
                max-width: 150px;
                margin-bottom: 10px;
            }
            .title {
                font-size: 24px;
                font-weight: bold;
                color: #2C3E50;
                margin-bottom: 30px;
            }
            .info-container {
                width: 100%;
                margin-bottom: 20px;
            }
            .info-row {
                margin-bottom: 10px;
                border-bottom: 1px solid #eee;
                padding: 8px 0;
            }
            .label {
                font-weight: bold;
                color: #34495E;
                width: 150px;
                display: inline-block;
            }
            .value {
                color: #2C3E50;
            }
            .footer {
                position: fixed;
                bottom: 0;
                width: 100%;
                text-align: center;
                font-size: 12px;
                color: #7F8C8D;
                border-top: 1px solid #eee;
                padding-top: 10px;
            }
        ';

            $html = "
            <div class='header'>
                <img src='/img/logo.png' class='logo'>
                <div class='title'>FICHA DEL PERSONAL MÉDICO</div>
            </div>
            
            <div class='info-container'>
                <div class='info-row'>
                    <span class='label'>DNI:</span>
                    <span class='value'>{$personal['documento']}</span>
                </div>
                
                <div class='info-row'>
                    <span class='label'>Nombre:</span>
                    <span class='value'>{$personal['nombre']}</span>
                </div>
                
                <div class='info-row'>
                    <span class='label'>Especialidad:</span>
                    <span class='value'>{$personal['especialidad']}</span>
                </div>
                
                <div class='info-row'>
                    <span class='label'>Celular:</span>
                    <span class='value'>{$personal['celular']}</span>
                </div>
                
                <div class='info-row'>
                    <span class='label'>Edad:</span>
                    <span class='value'>{$personal['edad']} años</span>
                </div>
                
                <div class='info-row'>
                    <span class='label'>Sexo:</span>
                    <span class='value'>" . ($personal['sexo'] === 'M' ? 'Masculino' : 'Femenino') . "</span>
                </div>
                
                <div class='info-row'>
                    <span class='label'>Dirección:</span>
                    <span class='value'>{$personal['direccion']}</span>
                </div>
                
                <div class='info-row'>
                    <span class='label'>Fecha Registro:</span>
                    <span class='value'>" . date('d/m/Y H:i', strtotime($personal['fecha_registro'])) . "</span>
                </div>
            </div>
            
            <div class='footer'>
                Documento generado el " . date('d/m/Y H:i:s') . "
            </div>
        ";

            $mpdf->WriteHTML($css, \Mpdf\HTMLParserMode::HEADER_CSS);
            $mpdf->WriteHTML($html, \Mpdf\HTMLParserMode::HTML_BODY);

            $pdfContent = $mpdf->Output('', 'S');

            $response = $response->withHeader('Content-Type', 'application/pdf');
            $response = $response->withHeader('Content-Disposition', 'inline; filename="personal_medico.pdf"');
            $response->getBody()->write($pdfContent);

            return $response;
        } catch (Exception $e) {
            return $this->respondWithError($response, $e->getMessage());
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
