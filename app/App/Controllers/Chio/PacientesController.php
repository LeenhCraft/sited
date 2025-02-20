<?php

namespace App\Controllers\Chio;

use App\Core\Controller;
use App\Models\TableModel;
use Exception;

class PacientesController extends Controller
{
    private $table = "sd_pacientes";
    private $id = "idpaciente";
    private const PERMISSION = "ruta.paciente";

    public function __construct()
    {
        parent::__construct();
    }

    public function index($request, $response)
    {
        return $this->render($response, 'Chio.Pacientes.Pacientes', [
            'titulo_web' => 'Gestión de Pacientes',
            "url" => $request->getUri()->getPath(),
            'permisos' => $this->permisos_extras,
            "js" => [
                "/js/chio/pacientes.js?v=" . time()
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
                "idpaciente",
                "dni",
                "nombre",
                "celular",
                "edad",
                "sexo",
                "peso",
                "altura",
                "fecha_registro",
                "ultima_actualizacion",
                "eliminado",
                "fecha_eliminacion"
            );

            // Aplicar filtros
            // Estado (solo si tiene permiso de desarrollador)
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
                            // caso 'todos': no aplicamos filtro
                    }
                } else {
                    $query->where('eliminado', "0"); // Por defecto solo activos
                }
            } else {
                $query->where('eliminado', "0"); // Usuarios sin permiso solo ven activos
            }

            // Filtro por fechas
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

            // Búsqueda general
            if (!empty($data['filtro_search'])) {
                $search = $data['filtro_search'];
                $query->where(function ($q) use ($search) {
                    $q->where('dni', 'LIKE', "%$search%")
                        ->orWhere('nombre', 'LIKE', "%$search%")
                        ->orWhere('celular', 'LIKE', "%$search%");
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

            // Validaciones específicas
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

            // verificar si el DNI ya existe
            $paciente = $model->where('dni', $data['documento'])->first();
            if ($paciente) {
                return $this->respondWithError($response, "El DNI ya se encuentra registrado");
            }

            $rq = $model->create([
                "dni" => $data["documento"],
                "nombre" => trim($data["nombre"]),
                "celular" => $data["celular"],
                "edad" => $data["edad"],
                "sexo" => $data["sexo"],
                "peso" => $data["peso"],
                "altura" => $data["altura"],
                "fecha_registro" => $marcaTiempo,
                "creado_por" => $_SESSION["app_id"],
            ]);

            return $rq
                ? $this->respondWithSuccess($response, "Paciente registrado correctamente")
                : $this->respondWithError($response, "Error al registrar el paciente");
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

            $paciente = $model
                ->select(
                    "idpaciente",
                    "dni as documento",
                    "nombre",
                    "celular",
                    "edad",
                    "sexo",
                    "peso",
                    "altura",
                )
                ->where("idpaciente", $id)
                ->where('eliminado', "0")
                ->first();

            if (!$paciente) {
                return $this->respondWithError($response, "Paciente no encontrado");
            }

            return $this->respondWithJson($response, [
                "success" => true,
                "paciente" => $paciente
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

            $marcaTiempo = date('Y-m-d H:i:s');

            $rq = $model->update($id, [
                "dni" => $data["documento"],
                "nombre" => trim($data["nombre"]),
                "celular" => $data["celular"],
                "edad" => $data["edad"],
                "sexo" => $data["sexo"],
                "peso" => $data["peso"],
                "altura" => $data["altura"],
                "ultima_actualizacion" => $marcaTiempo,
                "actualizado_por" => $_SESSION["app_id"],
            ]);

            return $rq
                ? $this->respondWithSuccess($response, "Paciente actualizado correctamente")
                : $this->respondWithError($response, "Error al actualizar el paciente");
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

            // Verificar si el paciente existe y no está eliminado
            $paciente = $model->where('idpaciente', $id)
                ->where('eliminado', false)
                ->first();

            if (!$paciente) {
                return $this->respondWithError($response, "Paciente no encontrado");
            }

            // Verificar si tiene registros relacionados
            // TODO: Agregar verificación de registros relacionados si es necesario

            $marcaTiempo = date('Y-m-d H:i:s');

            // Realizar soft delete
            $rq = $model->update($id, [
                "eliminado" => true,
                "fecha_eliminacion" => $marcaTiempo,
                "eliminado_por" => $_SESSION["app_id"]
            ]);

            return $rq
                ? $this->respondWithSuccess($response, "Paciente eliminado correctamente")
                : $this->respondWithError($response, "Error al eliminar el paciente");
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
            'peso',
            'altura'
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

    public function searchByDNI($request, $response, $args)
    {
        try {
            $dni = $args['dni'];

            if (!$this->validateDNI($dni)) {
                return $this->respondWithError($response, "DNI inválido");
            }

            // Aquí puedes integrar con una API de RENIEC o similar
            // Por ahora solo buscaremos en nuestra base de datos
            $model = new TableModel();
            $model->setTable($this->table);
            $model->setId($this->id);

            $paciente = $model
                ->where('dni', $dni)
                ->where('eliminado', "0")
                ->first();

            if ($paciente) {
                return $this->respondWithJson($response, [
                    "success" => true,
                    "paciente" => $paciente
                ]);
            }

            return $this->respondWithJson($response, [
                "success" => false,
                "message" => "No se encontró información del DNI"
            ]);
        } catch (Exception $e) {
            return $this->respondWithError($response, $e->getMessage());
        }
    }

    public function generatePDF($request, $response, $args)
    {
        try {
            $this->checkPermission(self::PERMISSION, "print");
            $id = $args['id'];
            $model = new TableModel();
            $model->setTable($this->table);
            $model->setId($this->id);

            $paciente = $model
                ->select(
                    "idpaciente",
                    "dni as documento",
                    "nombre",
                    "celular",
                    "edad",
                    "sexo",
                    "peso",
                    "altura",
                    "fecha_registro"
                )
                ->where("idpaciente", $id)
                ->first();

            if (!$paciente) {
                return $this->respondWithError($response, "Paciente no encontrado");
            }

            // Instanciar mPDF
            $mpdf = new \Mpdf\Mpdf([
                'margin_left' => 20,
                'margin_right' => 20,
                'margin_top' => 20,
                'margin_bottom' => 20,
            ]);

            // Estilos CSS para el PDF
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

            // HTML para el PDF
            $html = "
                <div class='header'>
                    <img src='/img/logo.png' class='logo'>
                    <div class='title'>FICHA DEL PACIENTE</div>
                </div>
                
                <div class='info-container'>
                    <div class='info-row'>
                        <span class='label'>DNI:</span>
                        <span class='value'>{$paciente['documento']}</span>
                    </div>
                    
                    <div class='info-row'>
                        <span class='label'>Nombre:</span>
                        <span class='value'>{$paciente['nombre']}</span>
                    </div>
                    
                    <div class='info-row'>
                        <span class='label'>Celular:</span>
                        <span class='value'>{$paciente['celular']}</span>
                    </div>
                    
                    <div class='info-row'>
                        <span class='label'>Edad:</span>
                        <span class='value'>{$paciente['edad']} años</span>
                    </div>
                    
                    <div class='info-row'>
                        <span class='label'>Sexo:</span>
                        <span class='value'>" . ($paciente['sexo'] === 'M' ? 'Masculino' : 'Femenino') . "</span>
                    </div>
                    
                    <div class='info-row'>
                        <span class='label'>Peso:</span>
                        <span class='value'>{$paciente['peso']} kg</span>
                    </div>
                    
                    <div class='info-row'>
                        <span class='label'>Altura:</span>
                        <span class='value'>{$paciente['altura']} cm</span>
                    </div>
                    
                    <div class='info-row'>
                        <span class='label'>Fecha Registro:</span>
                        <span class='value'>" . date('d/m/Y H:i', strtotime($paciente['fecha_registro'])) . "</span>
                    </div>
                </div>
                
                <div class='footer'>
                    Documento generado el " . date('d/m/Y H:i:s') . "
                </div>
            ";

            // Agregar CSS
            $mpdf->WriteHTML($css, \Mpdf\HTMLParserMode::HEADER_CSS);
            // Agregar contenido HTML
            $mpdf->WriteHTML($html, \Mpdf\HTMLParserMode::HTML_BODY);

            // Generar PDF
            $pdfContent = $mpdf->Output('', 'S');

            // Devolver el PDF como respuesta
            $response = $response->withHeader('Content-Type', 'application/pdf');
            $response = $response->withHeader('Content-Disposition', 'inline; filename="paciente.pdf"');
            $response->getBody()->write($pdfContent);

            return $response;
        } catch (Exception $e) {
            return $this->respondWithError($response, $e->getMessage());
        }
    }
}
