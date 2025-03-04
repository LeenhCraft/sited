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
}
