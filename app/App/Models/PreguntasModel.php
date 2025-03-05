<?php

namespace App\Models;

use App\Models\TableModel;

class PreguntasModel extends TableModel
{
    protected $table = 'pr_preguntas';
    protected $id = 'id_pregunta';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Obtiene todas las preguntas activas ordenadas por el campo 'orden'
     */
    public function obtenerPreguntasActivas()
    {
        return $this->select(
            'id_pregunta',
            'orden',
            'titulo',
            'contenido'
        )
            ->where('estado', 'Activo')
            ->where('eliminado', "0")
            ->orderBy('orden', 'ASC')
            ->get();
    }

    /**
     * Cuenta el número total de preguntas activas
     */
    public function contarPreguntasActivas()
    {
        $result = $this->select('COUNT(*) as total')
            ->where('estado', 'Activo')
            ->where('eliminado', "0")
            ->first();
        return $result['total'];
    }

    /**
     * Obtiene una pregunta específica por su ID
     */
    public function obtenerPreguntaPorId($id)
    {
        return $this->select(
            'id_pregunta',
            'orden',
            'titulo',
            'contenido'
        )
            ->where('id_pregunta', $id)
            ->where('estado', 'Activo')
            ->where('eliminado', "0")
            ->first();
    }

    /**
     * Obtener todas las preguntas activas
     */
    public function getAllPreguntas()
    {
        $this->emptyQuery();
        return $this->select('*')
            ->where('estado', 'Activo')
            ->where('eliminado', "0")
            ->orderBy('orden', 'ASC')
            ->get();
    }

    /**
     * Obtener respuestas posibles para una pregunta
     */
    public function getRespuestas($idPregunta)
    {
        $this->emptyQuery();
        $sql = "SELECT r.* 
                FROM pr_respuestas r
                WHERE r.id_pregunta = ? AND r.es_respuesta_aceptada = 1
                ORDER BY r.id_respuesta ASC";

        return $this->query($sql, [$idPregunta], 'i')->get();
    }

    /**
     * Obtener estadísticas de respuestas por nivel de riesgo
     */
    public function getRespuestasStats()
    {
        $this->emptyQuery();
        $sql = "SELECT p.id_pregunta, p.titulo, 
                SUM(CASE WHEN JSON_EXTRACT(r.metadatos, '$.valor_seleccionado') = 0 THEN 1 ELSE 0 END) as nivel_bajo,
                SUM(CASE WHEN JSON_EXTRACT(r.metadatos, '$.valor_seleccionado') = 1 THEN 1 ELSE 0 END) as nivel_medio,
                SUM(CASE WHEN JSON_EXTRACT(r.metadatos, '$.valor_seleccionado') = 2 THEN 1 ELSE 0 END) as nivel_alto,
                COUNT(tp.id_test_pregunta) as total
                FROM pr_preguntas p
                LEFT JOIN sd_test_preguntas tp ON p.id_pregunta = tp.id_pregunta
                LEFT JOIN pr_respuestas r ON tp.id_respuesta = r.id_respuesta
                WHERE p.eliminado = 0
                GROUP BY p.id_pregunta
                ORDER BY p.orden ASC";

        return $this->query($sql)->get();
    }

    /**
     * Obtener preguntas más frecuentes con respuestas de alto riesgo
     */
    public function getHighRiskPreguntas($limit = 5)
    {
        $this->emptyQuery();
        $sql = "SELECT p.id_pregunta, p.titulo, 
                COUNT(tp.id_test_pregunta) as total,
                (COUNT(CASE WHEN JSON_EXTRACT(r.metadatos, '$.valor_seleccionado') = 2 THEN 1 END) / COUNT(tp.id_test_pregunta)) * 100 as porcentaje_alto
                FROM pr_preguntas p
                LEFT JOIN sd_test_preguntas tp ON p.id_pregunta = tp.id_pregunta
                LEFT JOIN pr_respuestas r ON tp.id_respuesta = r.id_respuesta
                WHERE p.eliminado = 0
                GROUP BY p.id_pregunta
                HAVING porcentaje_alto > 0
                ORDER BY porcentaje_alto DESC
                LIMIT ?";

        return $this->query($sql, [$limit], 'i')->get();
    }
}
