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
    public function actualizarDatosPaciente($idPaciente, $edad, $peso, $altura, $idUsuario = null)
    {
        $idUsuario = $idUsuario ?? $_SESSION["app_id"];
        $datos = [
            'edad' => $edad,
            'peso' => $peso,
            'altura' => $altura,
            'ultima_actualizacion' => date('Y-m-d H:i:s'),
            'actualizado_por' => $idUsuario
        ];

        return $this->update($idPaciente, $datos);
    }

    /**
     * Obtener todos los pacientes activos
     */
    public function getAllPacientes()
    {
        $this->emptyQuery();
        return $this->select('*')
            ->where('eliminado', "0")
            ->orderBy('nombre', 'ASC')
            ->get();
    }

    /**
     * Buscar pacientes por nombre o DNI
     */
    public function searchPacientes($search)
    {
        $this->emptyQuery();
        return $this->select('*')
            ->where(function ($query) use ($search) {
                $query->where('nombre', 'LIKE', "%{$search}%")
                    ->orWhere('dni', 'LIKE', "%{$search}%");
            })
            ->where('eliminado', "0")
            ->orderBy('nombre', 'ASC')
            ->get();
    }

    /**
     * Obtener paciente por DNI
     */
    public function getPacienteByDni($dni)
    {
        $this->emptyQuery();
        return $this->select('*')
            ->where('dni', $dni)
            ->where('eliminado', "0")
            ->first();
    }

    /**
     * Obtener estadísticas de pacientes con tests
     */
    public function getPacientesWithTests()
    {
        $this->emptyQuery();
        $sql = "SELECT p.*, COUNT(t.idtest) as total_tests,
                MAX(t.fecha_hora) as ultimo_test
                FROM {$this->table} p
                INNER JOIN sd_test t ON p.idpaciente = t.idpaciente
                WHERE p.eliminado = 0 AND t.eliminado = 0
                GROUP BY p.idpaciente
                ORDER BY ultimo_test DESC";

        return $this->query($sql)->get();
    }
}
