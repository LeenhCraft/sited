<?php

namespace App\Models;

use App\Models\TableModel;

class TestModel extends TableModel
{
    protected $table = 'sd_test';
    protected $id = 'idtest';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Crea un nuevo test
     * 
     * @param array $datos Datos del test
     * @return array|bool Resultado de la creación
     */
    public function crearTest($datos)
    {
        return $this->create($datos);
    }

    /**
     * Actualiza la tendencia del test
     * 
     * @param int $idTest ID del test
     * @param int $puntuacion Puntuación del test
     * @return array|bool Resultado de la actualización
     */
    public function actualizarTendencia($idTest, $analisis)
    {
        // de los 3 análisis, se toma el que tenga la puntuación más alta
        $puntuacion = max($analisis['probabilidades']['bajo'], $analisis['probabilidades']['moderado'], $analisis['probabilidades']['alto']);
        $datos = [
            'tendencia_label' => $analisis['clasificacion'],
            'tendencia_modelo' => $puntuacion,
            'procesado_modelo' => 1,
            'respuesta_analisis' => json_encode($analisis),
            'ultima_actualizacion' => date('Y-m-d H:i:s'),
            'actualizado_por' => $_SESSION["app_id"] ?? 1
        ];

        return $this->update($idTest, $datos);
    }

    /**
     * Obtiene los tests de un paciente
     * 
     * @param int $idPaciente ID del paciente
     * @return array Tests del paciente
     */
    public function obtenerTestPorPaciente($idPaciente)
    {
        return $this->select(
            'idtest',
            'fecha_hora',
            'tendencia_modelo',
            'fecha_registro'
        )
            ->where('idpaciente', $idPaciente)
            ->where('eliminado', "0")
            ->orderBy('fecha_hora', 'DESC')
            ->get();
    }

    /**
     * Calcula el porcentaje de riesgo basado en la puntuación
     * 
     * @param int $puntuacion Puntuación del test
     * @return int Porcentaje de riesgo
     */
    private function calcularPorcentajeRiesgo($puntuacion)
    {
        // Máxima puntuación posible es 22 (según el diseño)
        $maxPuntuacion = 22;

        // Calcular porcentaje base
        $porcentajeBase = ($puntuacion / $maxPuntuacion) * 100;

        // Ajustar el porcentaje para que refleje mejor el riesgo
        if ($puntuacion <= 7) {
            // Riesgo bajo: 0-33%
            $porcentaje = ($puntuacion / 7) * 33;
        } elseif ($puntuacion <= 14) {
            // Riesgo moderado: 34-66%
            $porcentaje = 33 + (($puntuacion - 7) / 7) * 33;
        } else {
            // Riesgo alto: 67-100%
            $porcentaje = 66 + (($puntuacion - 14) / 8) * 34;
        }

        // Redondear y limitar a máximo 100%
        return min(round($porcentaje), 100);
    }

    /**
     * Obtener todos los tests
     */
    public function getAllTests()
    {
        $this->emptyQuery();
        return $this->select('*')
            ->where('eliminado', "0")
            ->orderBy('fecha_hora', 'DESC')
            ->get();
    }

    /**
     * Obtener preguntas y respuestas de un test
     */
    public function getTestPreguntas($testId)
    {
        $this->emptyQuery();
        $sql = "SELECT tp.*, p.titulo as pregunta_texto, p.contenido, r.contenido as respuesta_texto, 
                r.contenido_alternativo, r.metadatos as respuesta_metadata
                FROM sd_test_preguntas tp
                INNER JOIN pr_preguntas p ON tp.id_pregunta = p.id_pregunta
                INNER JOIN pr_respuestas r ON tp.id_respuesta = r.id_respuesta
                WHERE tp.idtest = ?
                ORDER BY p.orden ASC";

        return $this->query($sql, [$testId], 'i')->get();
    }

    /**
     * Obtener tests filtrados por paciente
     */
    public function getTestsByPaciente($idPaciente)
    {
        $this->emptyQuery();
        return $this->select('*')
            ->where('idpaciente', $idPaciente)
            ->where('eliminado', 0)
            ->orderBy('fecha_hora', 'DESC')
            ->get();
    }

    /**
     * Obtener estadísticas de tests por mes
     */
    public function getMonthlyStats($year = null)
    {
        if (!$year) {
            $year = date('Y');
        }

        $this->emptyQuery();
        $sql = "SELECT 
                MONTH(fecha_hora) as mes,
                COUNT(*) as total,
                SUM(CASE WHEN tendencia_modelo < 40 THEN 1 ELSE 0 END) as riesgo_bajo,
                SUM(CASE WHEN tendencia_modelo >= 40 AND tendencia_modelo < 60 THEN 1 ELSE 0 END) as riesgo_moderado,
                SUM(CASE WHEN tendencia_modelo >= 60 THEN 1 ELSE 0 END) as riesgo_alto
                FROM {$this->table}
                WHERE YEAR(fecha_hora) = ? AND eliminado = 0
                GROUP BY MONTH(fecha_hora)
                ORDER BY MONTH(fecha_hora)";

        return $this->query($sql, [$year], 'i')->get();
    }
}
