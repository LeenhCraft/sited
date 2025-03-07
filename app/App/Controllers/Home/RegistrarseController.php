<?php

namespace App\Controllers\Home;

use App\Core\Controller;
use App\Helpers\TokenGenerator;
use App\Models\TableModel;
use Exception;

class RegistrarseController extends Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index($request, $response)
    {
        return $this->render($response, "Home.Registrarse", [
            "titulo_web" => "Iniciar Sesión",
            "url" => $request->getUri()->getPath(),
            // "css" => [
            //     "/assets/vendor/css/pages/ui-carousel.css",
            //     "/assets/vendor/libs/swiper/swiper.css"
            // ],
            "js" => [
                "/js/chio/register-user.js?v=" . time()
            ]
        ]);
    }

    public function store($request, $response)
    {
        try {
            $data = $this->sanitize($request->getParsedBody());

            if (!$this->validateData($data)) {
                return $this->respondWithError($response, "Los campos con (*) son obligatorios");
            }

            $marcatiempo = date("Y-m-d H:i:s");

            // verificamos si el dni ya esta en sis_personal y si ya tiene usuario en sis_usuario
            $model = new TableModel();
            $model->setTable('sis_personal p');
            $model->setId("idpersona");

            $existe = $model
                ->select(
                    "p.idpersona",
                    "u.idusuario"
                )
                ->leftJoin("sis_usuarios u", "p.idpersona", "u.idpersona")
                ->where("p.per_estado", "1")
                ->where("u.usu_estado", "1")
                ->where("p.per_dni", $data['dni'])
                ->first();
            if (!empty($existe)) {
                return $this->respondWithError($response, "Ya existe una cuenta con el DNI ingresado.");
            }

            // verificamos si el dni esta en sd_pacientes, si esta obtengo el id, si no esta creo el registro y obtengo el id
            $modelPacientes = new TableModel();
            $modelPacientes->setTable('sd_pacientes');
            $modelPacientes->setId("idpaciente");

            $existePaciente = $modelPacientes
                ->select("idpaciente")
                ->where("dni", $data['dni'])
                ->where("eliminado", "0")
                ->first();

            $idpaciente = "0";
            if (empty($existePaciente)) {
                $idpaciente = $modelPacientes->create([
                    'nombre' => $data['nombre_completo'],
                    'dni' => $data['dni'],
                    'celular' => "0",
                    'edad' => "0",
                    'sexo' => "0",
                    'peso' => "0",
                    'altura' => "0",
                    'fecha_registro' => $marcatiempo,
                    'creado_por' => "0",
                ]);
                $idpaciente = $idpaciente["idpaciente"];
            } else {
                $idpaciente = $existePaciente['idpaciente'];
            }

            // verifico si el nombre de usuario ya existe, si ya existe alerto al usuario, si no existe creo el registro
            $modelUsuarios = new TableModel();
            $modelUsuarios->setTable('sis_usuarios');
            $modelUsuarios->setId("idusuario");

            $existeUsuario = $modelUsuarios
                ->select("idusuario")
                ->where("usu_usuario", $data['username'])
                ->where("usu_estado", "1")
                ->first();
            if (!empty($existeUsuario)) {
                return $this->respondWithError($response, "El nombre de usuario ya existe. Por favor, elija otro.");
            }

            // verifico si el dni esta en sis_personal, si esta obtengo el id si no creo el registro y obtengo el id
            $model->emptyQuery();

            $model->setTable("sis_personal");
            $model->setId("idpersona");
            $existePersona = $model
                ->select(
                    "idpersona",
                )
                ->where("per_estado", "1")
                ->where("per_dni", $data['dni'])
                ->first();

            $idpersona = "0";
            if (empty($existePersona)) {
                $idpersona = $model->create([
                    'per_dni' => $data["dni"],
                    'per_nombre' => $data["nombre_completo"],
                    'per_celular' => null,
                    'per_email' => $data["email"],
                    'per_direcc' => null,
                    'per_foto' => null,
                    'per_estado' => "1",
                    'per_fecha' => $marcatiempo,
                ]);
                $idpersona = $idpersona["idpersona"];
            } else {
                $idpersona = $existePersona['idpersona'];
            }

            $tiempoExpiracion = time() + 24 * 60 * 60;
            // $tokenGenerator = new TokenGenerator(32, $tiempoExpiracion, $data["email"] . '_');
            // $token = $tokenGenerator->generate();
            $token = token();

            // creo el usuario
            $registroUsuario = $modelUsuarios->create([
                'idrol' => "4", // usuario web
                'idpersona' => $idpersona,
                'usu_usuario' => $data["username"],
                'usu_pass' => password_hash($data["password"], PASSWORD_DEFAULT),
                'usu_token' => $token,
                'usu_activo' => "0",
                'usu_estado' => "1",
                'usu_primera' => "1",
                'usu_twoauth' => "0",
                'usu_code_twoauth' => "",
                'usu_fecha' => $marcatiempo,
            ]);

            if (empty($registroUsuario)) {
                return $this->respondWithError($response, "Error al registrar el usuario");
            }

            $this->sendEmail([
                "nombre" => $data['nombre_completo'],
                "email" => $data['email'],
                "token" => $token,
                "expires" => $tiempoExpiracion,
            ]);

            return $this->respondWithJson($response, [
                "status" => true,
                "message" => "Usuario registrado correctamente",
            ]);
        } catch (Exception $e) {
            return $this->respondWithJson($response, [
                "status" => false,
                "message" => $e->getMessage()
            ]);
        }
    }

    private function validateData($data)
    {
        $required = [
            'dni',
            'nombre_completo',
            'username',
            'email',
            'password',
            'terms',
        ];

        foreach ($required as $field) {
            if (empty($data[$field])) return false;
        }
        return true;
    }

    public function sendEmail($data = [])
    {
        if (empty($data)) return false;

        $url_recovery = base_url() . 'verify-email/' . $data["token"] .
            // '?email=' . $data["email"] .
            '?expires=' . $data["expires"] .
            '&signature=' . generateSignature($data["token"], $data["expires"]);

        $dataUsuario = array(
            'nombre' => $data['nombre'],
            'email' => $data["email"],
            'asunto' => "Confirme su dirección de correo electrónico. " . date("d/m/Y H:i"),
            'url_recovery' => $url_recovery
        );
        $response_email = enviarEmail($dataUsuario, 'Confirmacion');
        return $response_email;
    }
}
