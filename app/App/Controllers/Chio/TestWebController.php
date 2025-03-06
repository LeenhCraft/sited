<?php

namespace App\Controllers\Chio;

use App\Core\Controller;
use App\Models\PacientesModel;
use App\Models\PreguntasModel;
use App\Models\RespuestasModel;
use App\Models\TableModel;
use App\Models\TestModel;

class TestWebController extends Controller
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
                "p.idpersona as id_paciente",
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

        return $this->render($response, "Chio.Test.TestWeb", [
            "titulo_web" => "Iniciar Sesión",
            "url" => $request->getUri()->getPath(),
            "css" => [
                "/assets/vendor/css/pages/ui-carousel.css",
                "/assets/vendor/libs/swiper/swiper.css"
            ],
            "js" => [
                "/assets/vendor/libs/swiper/swiper.js",
                "/assets/js/ui-carousel.js",
                "/js/chio/test-web.js?v=" . time()
            ],
            "user" => $userData
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
}
