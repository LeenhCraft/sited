<?php

namespace App\Controllers\Chio;

use App\Core\Controller;
use App\Models\PacientesModel;
use App\Models\PreguntasModel;
use App\Models\RespuestasModel;
use App\Models\TestModel;

class TestController extends Controller
{
    private const PERMISSION = "ruta.test";

    public function __construct()
    {
        parent::__construct();
    }

    public function index($request, $response)
    {
        return $this->render($response, "Chio.Test.Test", [
            "titulo_web" => "Preguntas",
            "url" => $request->getUri()->getPath(),
            'permisos' => $this->permisos_extras,
            "permission" => self::PERMISSION,
            "css" => [
                "/vendor/select2/select2/dist/css/select2.min.css",
                "/css/select2-custom.css",
            ],
            "js" => [
                "/vendor/select2/select2/dist/js/select2.full.min.js",
                "https://cdn.jsdelivr.net/npm/chart.js",
                "/js/chio/test.js?v=" . time()
            ]
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
     * Busca pacientes por nombre o DNI
     */
    public function buscarPacientes($request, $response)
    {
        $params = $request->getQueryParams();
        $busqueda = isset($params['q']) ? $params['q'] : '';

        // Validar longitud mínima de búsqueda
        if (strlen($busqueda) < 3) {
            return $this->respondWithJson($response, []);
        }

        // Buscar pacientes en la base de datos
        $pacientesModel = new PacientesModel();
        $pacientes = $pacientesModel->buscarPacientes($busqueda);

        return $this->respondWithJson($response, $pacientes);
    }

    /**
     * Guarda las respuestas del test y los datos del paciente
     */
    public function guardarRespuestas($request, $response)
    {
        $datos = $request->getParsedBody();
        // return $this->respondWithJson($response, $datos);

        // Validar los datos recibidos
        if (!isset($datos['respuestas']) || !is_array($datos['respuestas'])) {
            return $this->respondWithError($response, 'Datos de respuestas inválidos', 400);
        }

        if (!isset($datos['paciente']) || !isset($datos['paciente']['id_paciente'])) {
            return $this->respondWithError($response, 'Datos del paciente inválidos', 400);
        }

        // Extraer datos
        $respuestas = $datos['respuestas'];
        $pacienteData = $datos['paciente'];
        $pacienteData['imc'] = round($pacienteData['imc'], 1);

        $idUsuario = $_SESSION["app_id"];
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
                $pacienteData['altura']
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
     * Calcula la puntuación total del test basado en las respuestas
     * 
     * @param array $respuestas Array de respuestas con id_pregunta e id_respuesta
     * @return int Puntuación total
     */
    private function calcularPuntuacion($respuestas)
    {
        $respuestasModel = new RespuestasModel();
        $puntuacion = 0;

        foreach ($respuestas as $respuesta) {
            $idPregunta = $respuesta['id_pregunta'];
            $idRespuesta = $respuesta['id_respuesta'];

            // Obtener la respuesta
            $respuestaData = $respuestasModel->obtenerRespuestaPorId($idRespuesta);

            if ($respuestaData && !empty($respuestaData['metadatos'])) {
                $metadatos = json_decode($respuestaData['metadatos'], true);

                if (isset($metadatos['valor_seleccionado'])) {
                    $puntuacion += (int)$metadatos['valor_seleccionado'];
                }
            }
        }

        return $puntuacion;
    }
}
