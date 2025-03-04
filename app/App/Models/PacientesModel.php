<?php

namespace App\Models;

use App\Models\TableModel;

class PacientesModel extends TableModel
{
    protected $table = 'sd_pacientes';
    protected $id = 'idpaciente';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Busca pacientes por nombre o DNI
     * 
     * @param string $busqueda Término de búsqueda
     * @return array Resultados de la búsqueda
     */
    public function buscarPacientes($busqueda)
    {
        $busqueda = "%{$busqueda}%";

        return $this->select(
            'idpaciente',
            'nombre',
            'dni',
            'celular',
            'edad',
            'sexo',
            'peso',
            'altura'
        )
            ->where(function ($query) use ($busqueda) {
                $query->where('nombre', 'LIKE', $busqueda)
                    ->orWhere('dni', 'LIKE', $busqueda);
            })
            ->where('eliminado', "0")
            ->limit(10)
            ->get();
    }

    /**
     * Actualiza los datos antropométricos del paciente
     * 
     * @param int $idPaciente ID del paciente
     * @param float $peso Peso en kg
     * @param float $altura Altura en metros
     * @return bool|array Resultado de la actualización
     */
    public function actualizarDatosPaciente($idPaciente, $edad, $peso, $altura)
    {
        $datos = [
            'edad' => $edad,
            'peso' => $peso,
            'altura' => $altura,
            'ultima_actualizacion' => date('Y-m-d H:i:s'),
            'actualizado_por' => $_SESSION["app_id"] ?? 1
        ];

        return $this->update($idPaciente, $datos);
    }
}
