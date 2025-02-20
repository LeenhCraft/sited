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
        return $this->render($response, 'Chio.Personal.Medicos', [
            'titulo_web' => 'Gestión de Personal Médico',
            "url" => $request->getUri()->getPath(),
            'permisos' => $this->permisos_extras,
            "permission" => self::PERMISSION,
            "css" => [
                "/vendor/select2/select2/dist/css/select2.min.css",
                "/css/select2-bootstrap4.min.css",
                "/css/select2.min.css",
                "/css/select2-custom.css",
            ],
            "js" => [
                "/vendor/select2/select2/dist/js/select2.min.js",
                "/js/chio/personal.js?v=" . time()
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
                "idpersonal",
                "dni",
                "nombre",
                "celular",
                "edad",
                "sexo",
                "direccion",
                "'especialidad' as especialidad",
                "fecha_registro",
                "ultima_actualizacion",
                "eliminado",
                "fecha_eliminacion"
            );

            // Aplicar filtros
            if (
                isset($this->permisos_extras[self::PERMISSION]["developer"]) &&
                $this->permisos_extras[self::PERMISSION]["developer"] == "1"
            ) {
                if (!empty($data['filtro_estado'])) {
                    switch ($data['filtro_estado']) {
                        case 'activos':
                            $query->where('eliminado', "0");
                            break;
                        case 'eliminados':
                            $query->where('eliminado', "1");
                            break;
                    }
                } else {
                    $query->where('eliminado', "0");
                }
            } else {
                $query->where('eliminado', "0");
            }

            // Filtros de fecha
            if (!empty($data['fecha_inicio'])) {
                $query->where('fecha_registro', '>=', $data['fecha_inicio'] . ' 00:00:00');
            }
            if (!empty($data['fecha_fin'])) {
                $query->where('fecha_registro', '<=', $data['fecha_fin'] . ' 23:59:59');
            }

            // Filtro por sexo
            if (!empty($data['filtro_sexo'])) {
                $query->where('sexo', $data['filtro_sexo']);
            }

            // Filtro por especialidad
            if (!empty($data['filtro_especialidad'])) {
                $query->where('especialidad', $data['filtro_especialidad']);
            }

            // Búsqueda general
            if (!empty($data['filtro_search'])) {
                $search = $data['filtro_search'];
                $query->where(function ($q) use ($search) {
                    $q->where('dni', 'LIKE', "%$search%")
                        ->orWhere('nombre', 'LIKE', "%$search%")
                        ->orWhere('celular', 'LIKE', "%$search%")
                        ->orWhere('especialidad', 'LIKE', "%$search%");
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

            if (!$this->validateDNI($data['documento'])) {
                return $this->respondWithError($response, "El DNI debe tener 8 dígitos");
            }

            if (!$this->validateCelular($data['celular'])) {
                return $this->respondWithError($response, "El celular debe tener 9 dígitos");
            }

            $marcaTiempo = date('Y-m-d H:i:s');
            $model = new TableModel();
            $model->setTable($this->table);
            $model->setId($this->id);

            // Verificar DNI duplicado
            $personal = $model->where('dni', $data['documento'])->first();
            if ($personal) {
                return $this->respondWithError($response, "El DNI ya se encuentra registrado");
            }

            $rq = $model->create([
                "dni" => $data["documento"],
                "nombre" => trim($data["nombre"]),
                "celular" => $data["celular"],
                "edad" => $data["edad"],
                "sexo" => $data["sexo"],
                "direccion" => $data["direccion"],
                // "especialidad" => $data["especialidad"],
                "fecha_registro" => $marcaTiempo,
                "creado_por" => $_SESSION["app_id"],
            ]);

            return $rq
                ? $this->respondWithSuccess($response, "Personal médico registrado correctamente")
                : $this->respondWithError($response, "Error al registrar el personal médico");
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

            $model
                ->select(
                    "idpersonal",
                    "dni as documento",
                    "nombre",
                    "celular",
                    "edad",
                    "sexo",
                    "direccion",
                    "'especialidad' as especialidad",
                )
                ->where("idpersonal", $id);
            if (
                !isset($this->permisos_extras[self::PERMISSION]["developer"]) ||
                $this->permisos_extras[self::PERMISSION]["developer"] != "1"
            ) {
                $model->where('eliminado', "0");
            }

            $personal = $model->first();

            if (!$personal) {
                return $this->respondWithError($response, "Personal médico no encontrado");
            }

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

            $model = new TableModel();
            $model->setTable($this->table);
            $model->setId($this->id);

            // Verificar si existe otro personal con el mismo DNI (excluyendo el actual)
            $existingPersonal = $model->where('dni', $data['documento'])
                ->where('idpersonal', '!=', $id)
                ->first();

            if ($existingPersonal) {
                return $this->respondWithError($response, "El DNI ya se encuentra registrado en otro personal");
            }

            $marcaTiempo = date('Y-m-d H:i:s');

            $rq = $model->update($id, [
                "dni" => $data["documento"],
                "nombre" => trim($data["nombre"]),
                "celular" => $data["celular"],
                "edad" => $data["edad"],
                "sexo" => $data["sexo"],
                "direccion" => $data["direccion"],
                // "especialidad" => $data["especialidad"],
                "ultima_actualizacion" => $marcaTiempo,
                "actualizado_por" => $_SESSION["app_id"],
            ]);

            return $rq
                ? $this->respondWithSuccess($response, "Personal médico actualizado correctamente")
                : $this->respondWithError($response, "Error al actualizar el personal médico");
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

            $personal = $model->where('idpersonal', $id)
                ->where('eliminado', false)
                ->first();

            if (!$personal) {
                return $this->respondWithError($response, "Personal médico no encontrado");
            }

            // Aquí podrías agregar verificaciones adicionales
            // Por ejemplo, verificar si el personal tiene citas asignadas

            $marcaTiempo = date('Y-m-d H:i:s');

            $rq = $model->update($id, [
                "eliminado" => true,
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
            'especialidad'
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
}
