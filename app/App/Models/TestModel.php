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
    public function actualizarTendencia($idTest, $puntuacion)
    {
        // Convertir puntuación a porcentaje de riesgo
        $porcentaje = $this->calcularPorcentajeRiesgo($puntuacion);

        $datos = [
            'tendencia_modelo' => $porcentaje . '%',
            'procesado_modelo' => 1,
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
}
