<?php

namespace App\Controllers\Chio;

use App\Core\Controller;
use App\Models\TableModel;
use Exception;

class PerfilController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index($request, $response)
    {
        $model = new TableModel();
        $model->setTable("sis_usuarios u");
        $model->setId("idusuario");

        $userData = $model
            ->select(
                "p.per_nombre as nombre",
                "p.per_email as email",
                "p.per_dni as dni",
                "pa.edad",
                "pa.sexo",
                "pa.peso",
                "pa.altura",
                "u.ultima_actualizacion"
            )
            ->join("sis_personal p", "p.idpersona", "u.idpersona")
            ->leftJoin("sd_pacientes pa", "pa.dni", "p.per_dni")
            ->where("u.idusuario", $_SESSION["web_id"])
            ->first();

        return $this->render($response, "Chio.Perfil-usuario.Perfil", [
            "titulo_web" => "Mi Perfil",
            "url" => $request->getUri()->getPath(),
            "js" => [
                "/assets/vendor/libs/@form-validation/popular.js",
                "/assets/vendor/libs/@form-validation/bootstrap5.js",
                "/assets/vendor/libs/@form-validation/auto-focus.js",
                "/js/chio/perfil.js?v=" . time()
            ],
            "user" => $userData
        ]);
    }

    public function indexLista($request, $response)
    {
        $model = new TableModel();
        $model->setTable("sis_usuarios u");
        $model->setId("idusuario");

        $userData = $model
            ->select(
                "pa.idpaciente as id_paciente",
                "p.per_nombre as nombre",
                "p.per_email as email",
                "pa.edad",
                "pa.sexo",
                "pa.peso",
                "pa.altura",
                "u.ultima_actualizacion"
            )
            ->join("sis_personal p", "p.idpersona", "u.idpersona")
            ->join("sd_pacientes pa", "pa.dni", "p.per_dni")
            ->where("u.idusuario", $_SESSION["web_id"])
            ->first();

        $model = new TableModel();
        $model->setTable("sd_test");
        $model->setId("idtest");

        $modelPaciente = new TableModel();
        $modelPaciente->setTable("sd_test");
        $modelPaciente->setId("idtest");

        $testData = $model
            ->where("eliminado", "0")
            // ->where("idusuario", $_SESSION["web_id"])
            ->where("idpaciente", $userData["id_paciente"])
            ->orderBy("fecha_hora", "DESC")
            ->get();

        return $this->render($response, "Chio.Test.ListaPerfil", [
            "titulo_web" => "Mis Tests",
            "url" => $request->getUri()->getPath(),
            "css" => [
                "/assets/vendor/css/pages/ui-carousel.css",
                "/assets/vendor/libs/swiper/swiper.css"
            ],
            "js" => [
                "/assets/vendor/libs/swiper/swiper.js",
                "/assets/js/ui-carousel.js",
                "/js/chio/lista-test.js?v=" . time()
            ],
            "tests" => $testData
        ]);
    }

    public function update($reques, $response)
    {
        try {
            $data = $this->sanitize($reques->getParsedBody());
            // valdiar los datos obligatorios
            if (!$this->validar($data)) {
                return $this->respondWithError($response, "Los campos con (*) son obligatorios");
            }
            // tomar el di de session
            $idusuario = $_SESSION["web_id"];
            $modelUsuario = new TableModel();
            $modelUsuario->setTable("sis_usuarios");
            $modelUsuario->setId("idusuario");

            $dataUsuario = $modelUsuario
                ->where("idusuario", $idusuario)
                ->where("usu_activo", "1")
                ->where("usu_estado", "1")
                ->first();
            if (empty($dataUsuario)) {
                return $this->respondWithError($response, "Usuario no encontrado");
            }

            // actualizar en sis_personal
            $modelPersonal = new TableModel();
            $modelPersonal->setTable("sis_personal");
            $modelPersonal->setId("idpersona");

            $dataPersonal = $modelPersonal
                ->where("idpersona", $dataUsuario["idpersona"])
                ->where("per_estado", "1")
                ->first();
            if (empty($dataPersonal)) {
                return $this->respondWithError($response, "Personal no encontrado");
            }

            // actualizar en sd_pacientes
            $modelPacientes = new TableModel();
            $modelPacientes->setTable("sd_pacientes");
            $modelPacientes->setId("idpaciente");

            $dataPaciente = $modelPacientes
                ->where("dni", $dataPersonal["per_dni"])
                ->where("eliminado", "0")
                ->first();
            if (empty($dataPaciente)) {
                return $this->respondWithError($response, "Paciente no encontrado");
            }

            $marcaTiempo = date("Y-m-d H:i:s");
            $updatePersonal = [
                "per_nombre" => $data["nombre"],
                "per_email" => $data["email"],
            ];

            $updatePaciente = [
                "edad" => $data["edad"],
                "sexo" => $data["sexo"],
                "peso" => $data["peso"],
                "altura" => $data["altura"],
                "ultima_actualizacion" => $marcaTiempo,
                "actualizado_por" => $idusuario
            ];

            $respuestaPersonal = $modelPersonal->update($dataPersonal["idpersona"], $updatePersonal);

            $respuestaPaciente = $modelPacientes->update($dataPaciente["idpaciente"], $updatePaciente);

            if (!$respuestaPersonal || !$respuestaPaciente) {
                return $this->respondWithError($response, "Error al actualizar los datos");
            }

            return $this->respondWithJson($response, [
                "status" => true,
                "message" => "Datos actualizados correctamente",
            ]);
        } catch (\Exception $e) {
            return $this->respondWithError($response, $e->getMessage());
        }
    }

    private function validar($data)
    {
        $required = [
            'nombre',
            'edad',
            'sexo',
            'peso',
            'altura',
            'email',
        ];

        foreach ($required as $field) {
            if (empty($data[$field])) return false;
        }
        return true;
    }

    public function updatePassword($reques, $response)
    {
        try {
            $data = $this->sanitize($reques->getParsedBody());
            if (!$this->validarPass($data)) {
                return $this->respondWithError($response, "Los campos con (*) son obligatorios");
            }
            // tomo el id de session
            $idusuario = $_SESSION["web_id"];
            $modelUsuario = new TableModel();
            $modelUsuario->setTable("sis_usuarios");
            $modelUsuario->setId("idusuario");
            // busco el usuario
            $dataUsuario = $modelUsuario
                ->where("idusuario", $idusuario)
                ->where("usu_activo", "1")
                ->where("usu_estado", "1")
                ->first();
            if (empty($dataUsuario)) {
                return $this->respondWithError($response, "Usuario no encontrado");
            }
            // comparamos las contraseñas actuales
            if (!password_verify($data["currentPassword"], $dataUsuario["usu_pass"])) {
                return $this->respondWithError($response, "La contraseña actual no coincide");
            }
            // comparamos las contraseñas nuevas
            if ($data["newPassword"] !== $data["confirmPassword"]) {
                return $this->respondWithError($response, "Las contraseñas no coinciden");
            }
            // actualizo la contraseña
            $dataUpdate = [
                "usu_pass" => password_hash($data["newPassword"], PASSWORD_DEFAULT),
                "ultima_actualizacion" => date("Y-m-d H:i:s"),
            ];
            $respuestaUsuario = $modelUsuario->update($dataUsuario["idusuario"], $dataUpdate);
            if (!$respuestaUsuario) {
                return $this->respondWithError($response, "Error al actualizar la contraseña");
            }
            return $this->respondWithJson($response, [
                "status" => true,
                "message" => "Contraseña actualizada correctamente",
            ]);
        } catch (\Exception $e) {
            return $this->respondWithError($response, $e->getMessage());
        }
    }

    private function validarPass($data)
    {
        $required = [
            'confirmPassword',
            'newPassword',
            'currentPassword',
        ];

        foreach ($required as $field) {
            if (empty($data[$field])) return false;
        }
        return true;
    }

    public function deleteAccount($reques, $response)
    {
        try {
            $data = $this->sanitize($reques->getParsedBody());
            // proceso para eliminar la cuenta
            $idusuario = $_SESSION["web_id"];
            $modelUsuario = new TableModel();
            $modelUsuario->setTable("sis_usuarios");
            $modelUsuario->setId("idusuario");
            $dataUsuario = $modelUsuario
                ->where("idusuario", $idusuario)
                ->where("usu_activo", "1")
                ->where("usu_estado", "1")
                ->first();
            if (empty($dataUsuario)) {
                return $this->respondWithError($response, "Usuario no encontrado");
            }
            // comprobar la contraseña
            if (!password_verify($data["password"], $dataUsuario["usu_pass"])) {
                return $this->respondWithError($response, "La contraseña no coincide");
            }
            $marcaTiempo = date("Y-m-d H:i:s");
            $updateUsuario = [
                "usu_activo" => "0",
                "usu_estado" => "0",
                "ultima_actualizacion" => $marcaTiempo
            ];
            $respuestaUsuario = $modelUsuario->update($dataUsuario["idusuario"], $updateUsuario);
            if (!$respuestaUsuario) {
                return $this->respondWithError($response, "Error al eliminar la cuenta");
            }
            return $this->respondWithJson($response, [
                "status" => true,
                "message" => "Cuenta eliminada correctamente",
            ]);
        } catch (Exception $e) {
            return $this->respondWithJson($response, [
                "status" => false,
                "message" => $e->getMessage(),
            ]);
        }
    }
}
