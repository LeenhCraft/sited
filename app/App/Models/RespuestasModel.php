<?php

namespace App\Models;

use App\Models\TableModel;

class RespuestasModel extends TableModel
{
    protected $table = 'pr_respuestas';
    protected $id = 'id_respuesta';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Obtiene todas las respuestas para una pregunta específica
     */
    public function obtenerRespuestasPorPregunta($idPregunta)
    {
        $respuestas = $this
            ->select(
                'id_respuesta',
                'id_pregunta',
                'contenido',
                'contenido_alternativo',
                'metadatos'
            )
            ->where('id_pregunta', $idPregunta)
            ->where('es_respuesta_aceptada', 1)
            ->get();

        $this->emptyQuery();

        // Procesamos el campo metadatos JSON
        foreach ($respuestas as &$respuesta) {
            if (!empty($respuesta['metadatos'])) {
                $respuesta['metadatos'] = json_decode($respuesta['metadatos'], true);
            }
        }

        return $respuestas;
    }

    /**
     * Obtiene una respuesta específica por su ID
     * 
     * @param int $idRespuesta ID de la respuesta
     * @return array|bool Datos de la respuesta
     */
    public function obtenerRespuestaPorId($idRespuesta)
    {
        return $this->select(
            'id_respuesta',
            'id_pregunta',
            'contenido',
            'metadatos'
        )
            ->where('id_respuesta', $idRespuesta)
            ->first();
    }

    /**
     * Guarda la respuesta seleccionada por el usuario en un test
     * 
     * @param int $idTest ID del test
     * @param int $idPregunta ID de la pregunta
     * @param int $idRespuesta ID de la respuesta seleccionada
     * @return bool Resultado de la operación
     */
    public function guardarRespuestaTest($idTest, $idPregunta, $idRespuesta)
    {
        // Obtenemos la respuesta para guardar su contenido
        $respuesta = $this->obtenerRespuestaPorId($idRespuesta);
        $this->emptyQuery();

        if (!$respuesta) {
            return false;
        }

        $contenido = $respuesta['contenido'];

        // Preparar datos para insertar en la tabla sd_test_preguntas
        $testPreguntasModel = new TableModel();
        $testPreguntasModel->setTable('sd_test_preguntas');
        $testPreguntasModel->setId('id_test_pregunta');

        $datos = [
            'id_pregunta' => $idPregunta,
            'idtest' => $idTest,
            'respuesta_usuario' => $contenido,
            'id_respuesta' => $idRespuesta,
            'fecha_registro' => date('Y-m-d H:i:s')
        ];

        $resultado = $testPreguntasModel->create($datos);

        return $resultado ? true : false;
    }

    /**
     * Guarda la respuesta seleccionada por el usuario (método original)
     * Mantenido por compatibilidad
     */
    public function guardarRespuestaUsuario($idUsuario, $idPregunta, $idRespuesta)
    {
        // Simulamos que se guardó correctamente
        return true;
    }

    /**
     * Convierte las respuestas en valores para el clasificador Naive Bayes
     * 
     * @param array $respuestas Array de respuestas con id_pregunta e id_respuesta
     * @return array Array de 13 valores para el clasificador
     */
    public function convertirRespuestasParaModelo($respuestas, $datosAntropometricos)
    {

        // Inicializar array de entradas con valores por defecto
        $entradas = array_fill(0, 13, 0);

        // Procesar edad (índice 0)
        $edad = $datosAntropometricos['edad'];
        if ($edad < 45) {
            $entradas[0] = 0;
        } elseif ($edad >= 45 && $edad <= 54) {
            $entradas[0] = 1;
        } else {
            $entradas[0] = 2;
        }

        // Procesar IMC (índice 1)
        $imc = $datosAntropometricos['imc'];
        if ($imc < 25) {
            $entradas[1] = 0;
        } elseif ($imc >= 25 && $imc <= 30) {
            $entradas[1] = 1;
        } else {
            $entradas[1] = 2;
        }

        // Mapeo de id_pregunta a índice en el array de entradas
        // Comienza desde la 3ra pregunta (índice 2) ya que las dos primeras
        // son edad e IMC que ya fueron procesadas
        $mapeoPreguntas = [
            1 => 2,  // Ansiedad/estrés
            2 => 3,  // Consumo grasas
            3 => 4,  // Sed/hambre
            4 => 5,  // Antecedentes glucosa
            5 => 6,  // Visión borrosa
            6 => 7,  // Cicatrización
            7 => 8,  // Cansancio
            8 => 9, // Hormigueo
            9 => 10, // Actividad física
            10 => 11, // Frutas/verduras
            11 => 12  // Antecedentes familiares
        ];

        foreach ($respuestas as $respuesta) {
            $idPregunta = $respuesta['id_pregunta'];
            $idRespuesta = $respuesta['id_respuesta'];

            // Verificar si la pregunta es parte del modelo
            if (!isset($mapeoPreguntas[$idPregunta])) {
                continue;
            }

            // Obtener la respuesta
            $respuestaData = $this->obtenerRespuestaPorId($idRespuesta);
            $this->emptyQuery();

            if ($respuestaData && !empty($respuestaData['metadatos'])) {
                $metadatos = json_decode($respuestaData['metadatos'], true);

                if (isset($metadatos['valor_seleccionado'])) {
                    $entradas[$mapeoPreguntas[$idPregunta]] = (int)$metadatos['valor_seleccionado'];
                }
            }
        }

        return $entradas;
    }
}
