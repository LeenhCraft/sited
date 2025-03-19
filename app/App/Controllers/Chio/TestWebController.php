<?php

namespace App\Controllers\Chio;

use App\Core\Controller;
use App\Models\CitasModel;
use App\Models\PacientesModel;
use App\Models\PreguntasModel;
use App\Models\RespuestasModel;
use App\Models\TableModel;
use App\Models\TestModel;
use Exception;
use Mpdf\Mpdf;

class TestWebController extends Controller
{
    private $citasModel;

    public function __construct()
    {
        parent::__construct();
        $this->citasModel = new CitasModel();
    }

    public function index($request, $response)
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

        $model->emptyQuery();
        $model->setTable("sd_especialidades");
        $model->setId("idespecialidad");

        $espeData = $model
            ->select("idespecialidad as id", "nombre")
            ->where("eliminado", "0")
            ->get();

        return $this->render($response, "Chio.Test.TestWeb", [
            "titulo_web" => "Test SITED",
            "url" => $request->getUri()->getPath(),
            "css" => [
                "/assets/vendor/css/pages/ui-carousel.css",
                "/assets/vendor/libs/swiper/swiper.css",
                "/node_modules/flatpickr/dist/flatpickr.min.css",
                "/vendor/select2/select2/dist/css/select2.min.css",
                "/css/select2-custom.css",
            ],
            "js" => [
                "/assets/vendor/libs/swiper/swiper.js",
                "/assets/js/ui-carousel.js",
                "/node_modules/moment/min/moment.min.js",
                "/node_modules/moment/locale/es.js",
                "/node_modules/flatpickr/dist/flatpickr.min.js",
                "/node_modules/flatpickr/dist/l10n/es.js",
                "/vendor/select2/select2/dist/js/select2.full.min.js",
                "/js/chio/test-web.js?v=" . time()
            ],
            "user" => $userData,
            "especialidades" => $espeData
        ]);
    }

    public function verTest($request, $response, $args)
    {
        $model = new TableModel();
        $model->setTable("sis_usuarios u");
        $model->setId("idusuario");

        $userData = $model
            ->select(
                "pa.idpaciente as id_paciente",
                "p.idpersona",
                "u.idusuario",
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

        $testData = $model
            ->where("eliminado", "0")
            ->where("idtest", $args['id'] ?? '0')
            ->where("idpaciente", $userData['id_paciente'] ?? '0')
            ->orderBy("fecha_hora", "DESC")
            ->first();

        $model = new TableModel();
        $model->setTable("sd_test_preguntas tp");
        $model->setId("id_test_pregunta");

        $preguntas = $model
            ->select(
                "tp.*",
                "p.titulo as pregunta_texto",
                "r.contenido as respuesta_texto",
                "r.metadatos"
            )
            ->join("pr_preguntas p", "p.id_pregunta", "tp.id_pregunta")
            ->join("pr_respuestas r", "r.id_respuesta", "tp.id_respuesta")
            ->where("tp.idtest", $testData['idtest'] ?? '0')
            ->orderBy("tp.id_pregunta", "ASC")
            ->get();

        return $this->render($response, "Chio.Test.VerTest", [
            "titulo_web" => "Test SITED",
            "url" => $request->getUri()->getPath(),
            "css" => [
                "/assets/vendor/css/pages/ui-carousel.css",
                "/assets/vendor/libs/swiper/swiper.css"
            ],
            "js" => [
                "https://cdn.jsdelivr.net/npm/apexcharts",
                "/assets/vendor/libs/swiper/swiper.js",
                "/assets/js/ui-carousel.js",
            ],
            "user" => $userData,
            "test" => $testData,
            "preguntas" => $preguntas
        ]);
    }

    public function obtenerPreguntas($request, $response)
    {
        $preguntasModel = new PreguntasModel();
        $respuestasModel = new RespuestasModel();

        // Obtener todas las preguntas activas
        $preguntas = $preguntasModel->obtenerPreguntasActivas();

        // Obtener las respuestas para cada pregunta
        foreach ($preguntas as &$pregunta) {
            $pregunta['respuestas'] = $respuestasModel->obtenerRespuestasPorPregunta($pregunta['id_pregunta']);
        }

        $totalPreguntas = count($preguntas);

        $resultado = [
            'preguntas' => $preguntas,
            'total_preguntas' => $totalPreguntas
        ];

        return $this->respondWithJson($response, $resultado);
    }

    /**
     * Guarda las respuestas del test y los datos del paciente
     */
    public function procesarRespuestas($request, $response)
    {
        $datos = $request->getParsedBody();
        // return $this->respondWithJson($response, $datos);

        // Validar los datos recibidos
        if (!isset($datos['respuestas']) || !is_array($datos['respuestas'])) {
            return $this->respondWithError($response, 'Datos de respuestas inválidos', 400);
        }

        if (!isset($datos['usuario']) || !isset($datos['usuario']['id_usuario'])) {
            return $this->respondWithError($response, 'Datos del paciente inválidos', 400);
        }

        // Extraer datos
        $respuestas = $datos['respuestas'];
        $pacienteData = $datos['usuario'];
        $pacienteData['imc'] = round($pacienteData['imc'], 1);

        $idUsuario = $_SESSION["web_id"];
        $idPaciente = $pacienteData['id_paciente'];

        try {
            // Iniciar transacción
            // $this->db->beginTransaction();

            // Actualizar datos antropométricos
            $pacientesModel = new PacientesModel();
            $pacienteActualizado = $pacientesModel->actualizarDatosPaciente(
                $idPaciente,
                $pacienteData['edad'],
                $pacienteData['peso'],
                $pacienteData['altura'],
                $_SESSION["web_id"]
            );

            if (!$pacienteActualizado) {
                throw new \Exception("Error al actualizar los datos del paciente");
            }

            // Crear nuevo test
            $testModel = new TestModel();
            $datosTest = [
                'idpaciente' => $idPaciente,
                'idusuario' => $idUsuario,
                'peso' => $pacienteData['peso'],
                'altura' => $pacienteData['altura'],
                'imc' => $pacienteData['imc'],
                'fecha_hora' => date('Y-m-d H:i:s'),
                'tendencia_label' => "",
                'tendencia_modelo' => 'manual', // Por ahora es manual
                'procesado_modelo' => 0,
                'creado_por' => $idUsuario
            ];

            $nuevoTest = $testModel->crearTest($datosTest);

            if (!$nuevoTest || !isset($nuevoTest['idtest'])) {
                throw new \Exception("Error al crear el test");
            }

            $idTest = $nuevoTest['idtest'];

            // Guardar las respuestas
            $respuestasModel = new RespuestasModel();
            $resultados = [];

            foreach ($respuestas as $respuesta) {
                if (!isset($respuesta['id_pregunta']) || !isset($respuesta['id_respuesta'])) {
                    continue;
                }

                $guardado = $respuestasModel->guardarRespuestaTest(
                    $idTest,
                    $respuesta['id_pregunta'],
                    $respuesta['id_respuesta']
                );

                if (!$guardado) {
                    throw new \Exception("Error al guardar la respuesta para la pregunta {$respuesta['id_pregunta']}");
                }

                $resultados[] = [
                    'id_pregunta' => $respuesta['id_pregunta'],
                    'guardado' => true
                ];
            }

            // Datos antropométricos para el modelo
            $datosAntropometricos = [
                'edad' => $pacienteData['edad'],
                'imc' => $pacienteData['imc']
            ];

            // Convertir respuestas para el modelo Naive Bayes
            $entradasModelo = $respuestasModel->convertirRespuestasParaModelo($respuestas, $datosAntropometricos);

            // Aplicar el clasificador de Naive Bayes
            $clasificador = new DiabetesRiskClassifier($entradasModelo);
            $analisis = $clasificador->analizar();

            // Guardar la clasificación en el test
            $testModel->actualizarTendencia($idTest, $analisis);

            // Calcular puntuación
            // $puntuacion = $this->calcularPuntuacion($respuestas);

            // Confirmar transacción
            // $this->db->commit();

            return $this->respondWithJson($response, [
                'success' => true,
                'mensaje' => 'Test guardado correctamente',
                'resultados' => $resultados,
                'resultado' => [
                    'id_test' => $idTest,
                    // 'puntuacion' => $puntuacion,
                    'clasificacion' => $analisis["clasificacion"],
                    'analisis' => $analisis
                ]
            ]);
        } catch (\Exception $e) {
            // Revertir transacción en caso de error
            // $this->db->rollBack();

            return $this->respondWithError(
                $response,
                'Error al guardar el test: ' . $e->getMessage(),
                500
            );
        }
    }

    /**
     * Exportar datos en formato PDF
     */
    public function exportPdf($request, $response, $args)
    {
        $model = new TableModel();
        $model->setTable("sis_usuarios u");
        $model->setId("idusuario");

        $userData = $model
            ->select(
                "pa.idpaciente as id_paciente",
                "p.idpersona",
                "u.idusuario",
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


        if (!$userData) {
            return $this->respondWithError($response, 'Usuario no encontrado', 404);
        }

        $model = new TableModel();
        $model->setTable("sd_test");
        $model->setId("idtest");

        $testData = $model
            ->where("eliminado", "0")
            ->where("idtest", $args['id'])
            ->where("idpaciente", $userData['id_paciente'])
            ->orderBy("fecha_hora", "DESC")
            ->first();

        $model = new TableModel();
        $model->setTable("sd_test_preguntas tp");
        $model->setId("id_test_pregunta");

        if (!$testData) {
            return $this->respondWithError($response, 'Test no encontrado', 404);
        }

        $preguntas = $model
            ->select(
                "tp.*",
                "p.titulo as pregunta_texto",
                "r.contenido as respuesta_texto",
                // "r.valor"
            )
            ->join("pr_preguntas p", "p.id_pregunta", "tp.id_pregunta")
            ->join("pr_respuestas r", "r.id_respuesta", "tp.id_respuesta")
            ->where("tp.idtest", $testData['idtest'])
            ->orderBy("tp.id_pregunta", "ASC")
            ->get();

        // Crear instancia mPDF
        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4', // Landscape
            'margin_left' => 15,
            'margin_right' => 15,
            'margin_top' => 15,
            'margin_bottom' => 15,
        ]);

        // Añadir estilos CSS
        $stylesheet = file_get_contents('./css/boxicons.css');
        $mpdf->WriteHTML($stylesheet, \Mpdf\HTMLParserMode::HEADER_CSS);

        // Datos del test
        $html = $this->view('Pdf.TestPdf', [
            "test" => [
                'user' => $userData,
                'test' => $testData,
                'preguntas' => $preguntas
            ]
        ]);

        // dep($html, 1);

        // Escribir HTML
        $mpdf->WriteHTML($html);

        // Generar PDF
        $pdfContent = $mpdf->Output('', 'S');

        // Establecer headers para descarga
        $fileName = 'Reporte_Tests_Diabetes_' . date('Ymd_His') . '.pdf';

        $response = $response->withHeader('Content-Type', 'application/pdf')
            ->withHeader('Content-Disposition', 'inline; filename="' . $fileName . '"')
            ->withHeader('Cache-Control', 'max-age=0, no-cache, no-store, must-revalidate')
            ->withHeader('Pragma', 'no-cache')
            ->withHeader('Expires', '0');

        $response->getBody()->write($pdfContent);

        return $response;
    }

    /**
     * Metodo para agenda una cita
     */

    public function agendarCita($request, $response)
    {
        $data = $this->sanitize($request->getParsedBody());
        try {
            $data = $this->sanitize($request->getParsedBody());

            // Validar datos requeridos
            $this->validateCitaData($data);

            // Verificar si ya existe una cita para este médico, fecha y hora
            if ($this->citasModel->citaExistente(null, $data['fecha'], $data['hora'])) {
                throw new Exception("Ya existe una cita programada para este médico en la fecha y hora seleccionada");
            }

            // Agregar campos adicionales
            $data['idusuario'] = $_SESSION["web_id"] ?? "0";
            $data['fecha_registro'] = date('Y-m-d H:i:s');
            $data['creado_por'] = $_SESSION["web_id"] ?? "0";
            $data['id_estado_cita'] = 1;

            $dataInsert = [
                "idusuario" => $data['idusuario'] ?? "0",
                "idpaciente" => $data['id_paciente'] ?? "0",
                "idpersonal" => $data['medico'] ?? "0",
                "id_estado_cita" => $data['id_estado_cita'] ?? "0",
                "fecha" => $data['fecha'] ?? "0000-00-00",
                "hora" => $data['hora'] ?? "00:00:00",
                "observaciones" => $data['observaciones'] ?? "",
                "fecha_registro" => $data['fecha_registro'] ?? date('Y-m-d H:i:s'),
                "creado_por" => $data['creado_por'] ?? "0",
            ];

            // Guardar cita
            $cita = $this->citasModel->create($dataInsert);
            return $this->respondWithJson($response, [
                'success' => true,
                'message' => 'Cita guardada exitosamente',
                'data' => $cita
            ]);
        } catch (Exception $e) {
            return $this->respondWithError($response, $e->getMessage());
        }
    }

    /**
     * Valida los datos de una cita médica
     */
    private function validateCitaData($data)
    {
        $requiredFields = [
            'id_paciente' => 'Paciente',
            'medico' => 'Médico',
            'fecha' => 'Fecha',
            'hora' => 'Hora'
        ];

        foreach ($requiredFields as $field => $label) {
            if (empty($data[$field])) {
                throw new Exception("El campo {$label} es obligatorio");
            }
        }

        // Validar fecha
        if (!empty($data['fecha']) && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $data['fecha'])) {
            throw new Exception("El formato de fecha debe ser YYYY-MM-DD");
        }

        // Validar hora
        if (!empty($data['hora']) && !preg_match('/^\d{2}:\d{2}:\d{2}$/', $data['hora'])) {
            throw new Exception("El formato de hora debe ser HH:MM:SS");
        }
    }
}
