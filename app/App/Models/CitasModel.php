<?php

namespace App\Models;


class CitasModel extends TableModel
{
    protected $table = 'ac_citas';
    protected $id = 'idcita';
    protected $query;

    /**
     * Obtiene las citas médicas con filtrado
     *
     * @param array $filters Filtros para la búsqueda
     * @param int $start Inicio para paginación
     * @param int $length Cantidad de registros por página
     * @param string $orderBy Campo para ordenar
     * @param string $orderDir Dirección del ordenamiento (ASC, DESC)
     * @return array
     */
    public function getCitasPaginadas($filters, $start, $length, $orderBy, $orderDir)
    {
        $this->emptyQuery();
        $this->select(
            "ac.idcita",
            "ac.fecha",
            "ac.hora",
            "ac.observaciones",
            "p.nombre as paciente",
            "p.idpaciente",
            "m.nombre as medico",
            "m.idpersonal",
            "e.nombre as especialidad",
            "e.idespecialidad",
            "ec.nombre as estado",
            "ec.id_estado_cita"
        );

        $this->table = "{$this->table} ac";

        $this->join("sd_pacientes p", "ac.idpaciente", "p.idpaciente");
        $this->join("sd_personal_medico m", "ac.idpersonal", "m.idpersonal");
        $this->join("sd_personal_especialidad pe", "m.idpersonal", "pe.idpersonal");
        $this->join("sd_especialidades e", "pe.idespecialidad", "e.idespecialidad");
        $this->join("ac_estado_cita ec", "ac.id_estado_cita", "ec.id_estado_cita");

        $this->where("ac.eliminado", 0);

        // Aplicar filtros
        $this->applyFilters($filters);

        // Ordenamiento
        if ($orderBy && $orderDir) {
            $this->orderBy($orderBy, $orderDir);
        } else {
            $this->orderBy("ac.idcita", "DESC");
        }

        // Obtener total de registros (sin paginación)
        $countSql = $this->previewSql();
        $countQuery = "SELECT COUNT(*) as total FROM ({$countSql['sql']}) as filtered_results";
        $countResult = $this->query($countQuery, $countSql['values'])->first();
        $totalRecords = $countResult['total'];

        // Aplicar paginación
        $this->limit($length);
        if ($start > 0) {
            $this->offset($start);
        }

        $this->query = null;
        $data = $this->get();

        return [
            'data' => $data,
            'draw' => isset($_POST['draw']) ? intval($_POST['draw']) : 0,
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $totalRecords
        ];
    }

    /**
     * Aplica filtros para la búsqueda de citas
     *
     * @param array $filters
     */
    private function applyFilters($filters)
    {
        if (!empty($filters['fechaInicio']) && !empty($filters['fechaFin'])) {
            $this->where('ac.fecha', '>=', $filters['fechaInicio']);
            $this->where('ac.fecha', '<=', $filters['fechaFin']);
        } elseif (!empty($filters['fechaInicio'])) {
            $this->where('ac.fecha', '>=', $filters['fechaInicio']);
        } elseif (!empty($filters['fechaFin'])) {
            $this->where('ac.fecha', '<=', $filters['fechaFin']);
        }

        if (!empty($filters['especialidad'])) {
            $this->where('e.idespecialidad', $filters['especialidad']);
        }

        if (!empty($filters['paciente'])) {
            $this->where('ac.idpaciente', $filters['paciente']);
        }

        if (!empty($filters['estadoCita'])) {
            $this->where('ac.id_estado_cita', $filters['estadoCita']);
        }

        if (!empty($filters['medico'])) {
            $this->where('ac.idpersonal', $filters['medico']);
        }
    }

    /**
     * Obtiene los datos de una cita por su ID
     *
     * @param int $idCita
     * @return array|null
     */
    public function getCitaById($idCita)
    {
        $this->select(
            "ac.idcita",
            "ac.fecha",
            "ac.hora",
            "ac.observaciones",
            "ac.idpaciente",
            "ac.idpersonal",
            "ac.id_estado_cita",
            "p.nombre as paciente_nombre",
            "p.dni as paciente_dni",
            "m.nombre as medico_nombre",
            "m.dni as medico_dni",
            "pe.idespecialidad",
            "e.nombre as especialidad",
            "ec.nombre as estado"
        );

        $this->table = "{$this->table} ac";

        $this->join("sd_pacientes p", "ac.idpaciente", "p.idpaciente");
        $this->join("sd_personal_medico m", "ac.idpersonal", "m.idpersonal");
        $this->join("sd_personal_especialidad pe", "m.idpersonal", "pe.idpersonal");
        $this->join("sd_especialidades e", "pe.idespecialidad", "e.idespecialidad");
        $this->join("ac_estado_cita ec", "ac.id_estado_cita", "ec.id_estado_cita");

        $this->where("ac.idcita", $idCita);
        $this->where("ac.eliminado", 0);

        $cita = $this->first();

        if ($cita) {
            // Reformatear los datos para el frontend
            return [
                'idcita' => $cita['idcita'],
                'fecha' => $cita['fecha'],
                'hora' => $cita['hora'],
                'observaciones' => $cita['observaciones'],
                'id_estado_cita' => $cita['id_estado_cita'],
                'paciente' => [
                    'idpaciente' => $cita['idpaciente'],
                    'nombre' => $cita['paciente_nombre'],
                    'dni' => $cita['paciente_dni']
                ],
                'medico' => [
                    'idpersonal' => $cita['idpersonal'],
                    'nombre' => $cita['medico_nombre'],
                    'dni' => $cita['medico_dni'],
                    'idespecialidad' => $cita['idespecialidad']
                ],
                'especialidad' => $cita['especialidad'],
                'estado' => $cita['estado']
            ];
        }

        return null;
    }

    /**
     * Busca pacientes para el select2
     *
     * @param string $query Término de búsqueda
     * @param int $page Página actual
     * @param int $perPage Registros por página
     * @return array
     */
    public function searchPacientes($query, $page = 1, $perPage = 30)
    {
        $this->select("idpaciente as id", "nombre", "dni", "celular", "edad", "sexo");
        $this->table = "sd_pacientes";

        $this->where(function ($q) use ($query) {
            $q->where("nombre", "LIKE", "%{$query}%");
            $q->orWhere("dni", "LIKE", "%{$query}%");
        });

        $this->where("eliminado", "0");

        // Calcular total de registros
        $countSql = $this->previewSql();
        $countQuery = "SELECT COUNT(*) as total FROM ({$countSql['sql']}) as filtered_results";
        $countResult = $this->query($countQuery, $countSql['values'])->first();
        $total = $countResult['total'];

        // Aplicar paginación
        $offset = ($page - 1) * $perPage;
        // convertir el 0 en string
        $offsetValue = $offset == 0 ? "0" : $offset;
        $limitValue = $perPage == 0 ? "0" : $perPage;

        $this->limit($limitValue);
        $this->offset($offsetValue);

        $this->query = null;
        $items = $this->get();

        return [
            'items' => $items,
            'total_count' => $total
        ];
    }

    /**
     * Busca médicos para el select2
     *
     * @param string $query Término de búsqueda
     * @param int|null $especialidadId ID de especialidad para filtrar
     * @param int $page Página actual
     * @param int $perPage Registros por página
     * @return array
     */
    public function searchMedicos($query, $especialidadId = null, $page = 1, $perPage = 30)
    {
        $this->emptyQuery();
        $this->select(
            "m.idpersonal as id",
            "m.nombre",
            "m.dni",
            "m.celular",
            "e.nombre as especialidad"
        );

        $this->table = "sd_personal_medico m";
        $this->join("sd_personal_especialidad pe", "m.idpersonal", "pe.idpersonal");
        $this->join("sd_especialidades e", "pe.idespecialidad", "e.idespecialidad");

        $this->where(function ($q) use ($query) {
            $q->where("m.nombre", "LIKE", "%{$query}%");
            $q->orWhere("m.dni", "LIKE", "%{$query}%");
        });

        if ($especialidadId) {
            $this->where("e.idespecialidad", $especialidadId);
        }

        $this->where("m.eliminado", "0");
        $this->where("e.eliminado", "0");

        // Calcular total de registros
        $countSql = $this->previewSql();
        $countQuery = "SELECT COUNT(*) as total FROM ({$countSql['sql']}) as filtered_results";
        $countResult = $this->query($countQuery, $countSql['values'])->first();
        $total = $countResult['total'];

        // Aplicar paginación
        $offset = ($page - 1) * $perPage;

        $offsetValue = $offset == 0 ? "0" : $offset;
        $limitValue = $perPage == 0 ? "0" : $perPage;

        $this->limit($limitValue);
        $this->offset($offsetValue);

        $this->query = null;
        $items = $this->get();

        return [
            'items' => $items,
            'total_count' => $total
        ];
    }

    /**
     * Obtiene todas las especialidades
     *
     * @return array
     */
    public function getEspecialidades()
    {
        $this->select("idespecialidad as id", "nombre");
        $this->table = "sd_especialidades";
        $this->where("eliminado", "0");
        $this->orderBy("nombre", "ASC");

        return $this->get();
    }

    /**
     * Obtiene los médicos por especialidad
     *
     * @param int $especialidadId
     * @return array
     */
    public function getMedicosPorEspecialidad($especialidadId)
    {
        $this->select("m.idpersonal", "m.nombre");
        $this->table = "sd_personal_medico m";
        $this->join("sd_personal_especialidad pe", "m.idpersonal", "pe.idpersonal");

        if ($especialidadId != 0) {
            $this->where("pe.idespecialidad", $especialidadId);
        }

        $this->where("m.eliminado", 0);
        $this->orderBy("m.nombre", "ASC");

        return $this->get();
    }

    /**
     * Obtiene todos los estados de citas
     *
     * @return array
     */
    public function getEstadosCitas()
    {
        $this->select("id_estado_cita as id", "nombre");
        $this->table = "ac_estado_cita";
        $this->where("eliminado", "0");
        // $this->orderBy("nombre", "ASC");
        $this->orderBy("id_estado_cita", "ASC");

        return $this->get();
    }

    /**
     * Obtiene los horarios disponibles de un médico en una fecha específica
     *
     * @param int $medicoId ID del médico
     * @param string $fecha Fecha en formato Y-m-d
     * @param int|null $citaId ID de la cita actual (para excluirla)
     * @return array
     */
    public function getHorariosDisponibles($medicoId, $fecha, $citaId = null)
    {
        // Obtener día de la semana (1=lunes, 7=domingo)
        $diaSemana = date('N', strtotime($fecha));

        // Obtener horarios del médico para ese día
        $this->select("h.hora_inicio", "h.hora_fin");
        $this->table = "sd_horarios_medicos h";

        $this->where("h.idpersonal", $medicoId);
        $this->where("h.iddia", $diaSemana);
        $this->where("h.activo", 1);
        $this->where("h.eliminado", 0);

        $horarioMedico = $this->first();

        if (!$horarioMedico) {
            return [];
        }

        // Generar slots de tiempo (cada 30 minutos)
        $horaInicio = strtotime($horarioMedico['hora_inicio']);
        $horaFin = strtotime($horarioMedico['hora_fin']);
        $intervalo = 30 * 60; // 30 minutos en segundos

        $slots = [];
        for ($hora = $horaInicio; $hora < $horaFin; $hora += $intervalo) {
            $slots[] = date('H:i:00', $hora);
        }

        // Obtener citas ya programadas para este médico en esta fecha
        $this->emptyQuery();
        $this->select("hora");
        $this->table = "ac_citas";

        $this->where("idpersonal", $medicoId);
        $this->where("fecha", $fecha);
        $this->where("eliminado", 0);

        if ($citaId) {
            $this->where("idcita", "!=", $citaId);
        }

        $citasProgramadas = $this->get();

        // Filtrar slots ocupados
        $horasOcupadas = array_column($citasProgramadas, 'hora');
        $slotsDisponibles = array_filter($slots, function ($slot) use ($horasOcupadas) {
            return !in_array($slot, $horasOcupadas);
        });

        // Formatear resultado
        $resultado = [];
        foreach ($slotsDisponibles as $slot) {
            $resultado[] = ['hora' => $slot];
        }

        return $resultado;
    }

    /**
     * Verifica si una cita ya existe para un médico en una fecha y hora específica
     *
     * @param int $medicoId
     * @param string $fecha
     * @param string $hora
     * @param int|null $citaId Excluir esta cita de la verificación
     * @return bool
     */
    public function citaExistente($medicoId = null, $fecha = null, $hora = null, $citaId = null)
    {
        $this->emptyQuery();
        $this->table = "ac_citas";

        if ($medicoId) {
            $this->where("idpersonal", $medicoId);
        }
        $this->where("fecha", $fecha);
        $this->where("hora", $hora);
        $this->where("eliminado", "0");

        if ($citaId) {
            $this->where("idcita", "!=", $citaId);
        }

        $cita = $this->first();

        return $cita !== null;
    }

    /**
     * Obtiene las citas para exportar a PDF
     *
     * @param array $filters
     * @return array
     */
    public function getCitasParaExportar($filters)
    {
        $this->select(
            "ac.idcita",
            "ac.fecha",
            "ac.hora",
            "ac.observaciones",
            "p.nombre as paciente",
            "p.dni as paciente_dni",
            "m.nombre as medico",
            "m.dni as medico_dni",
            "e.nombre as especialidad",
            "ec.nombre as estado"
        );

        $this->table = "{$this->table} ac";

        $this->join("sd_pacientes p", "ac.idpaciente", "p.idpaciente");
        $this->join("sd_personal_medico m", "ac.idpersonal", "m.idpersonal");
        $this->join("sd_personal_especialidad pe", "m.idpersonal", "pe.idpersonal");
        $this->join("sd_especialidades e", "pe.idespecialidad", "e.idespecialidad");
        $this->join("ac_estado_cita ec", "ac.id_estado_cita", "ec.id_estado_cita");

        $this->where("ac.eliminado", 0);

        // Aplicar filtros
        $this->applyFilters($filters);

        // Ordenar por fecha y hora
        $this->orderBy("ac.fecha", "ASC");
        $this->orderBy("ac.hora", "ASC");

        return $this->get();
    }

    /**
     * Obtiene la disponibilidad de horarios para un período específico
     * 
     * @param int|null $medicoId ID del médico (opcional)
     * @param string $fechaInicio Fecha de inicio (formato Y-m-d)
     * @param string $fechaFin Fecha de fin (formato Y-m-d)
     * @param int|null $especialidadId ID de la especialidad (opcional)
     * @return array Array con la disponibilidad de horarios
     */
    public function getDisponibilidadHorarios($medicoId = null, $fechaInicio = null, $fechaFin = null, $especialidadId = null)
    {
        // Validar fechas
        $inicio = new \DateTime($fechaInicio);
        $fin = new \DateTime($fechaFin);

        // Limitar a tres meses como máximo
        $maxFin = clone $inicio;
        $maxFin->modify('+3 month');

        if ($fin > $maxFin) {
            $fin = $maxFin;
        }

        // Arreglo para almacenar resultados
        $disponibilidad = [];

        // Obtener médicos según filtros
        $medicos = $this->getMedicosParaCalendario($medicoId, $especialidadId);

        if (empty($medicos)) {
            return [];
        }

        // Para cada médico, obtener su disponibilidad en el período
        foreach ($medicos as $medico) {
            $dispMedico = [
                'id' => $medico['idpersonal'],
                'nombre' => $medico['nombre'],
                'especialidad' => $medico['especialidad'],
                'horarios' => []
            ];

            // Obtener horarios configurados del médico por día de la semana
            $horariosConfigurados = $this->getHorariosMedicoPorDia($medico['idpersonal']);

            // Obtener citas ya programadas
            $citasProgramadas = $this->getCitasProgramadasPeriodo($medico['idpersonal'], $fechaInicio, $fechaFin);

            // Procesar cada día en el rango
            $currentDate = clone $inicio;
            while ($currentDate <= $fin) {
                $fechaActual = $currentDate->format('Y-m-d');
                $diaSemana = $currentDate->format('N'); // 1 (lunes) a 7 (domingo)

                // Verificar si el médico tiene horario configurado para este día
                if (isset($horariosConfigurados[$diaSemana])) {
                    $horaInicio = $horariosConfigurados[$diaSemana]['hora_inicio'];
                    $horaFin = $horariosConfigurados[$diaSemana]['hora_fin'];

                    // Generar slots de 30 minutos
                    $slotsDisponibles = $this->generarSlotsTiempo($horaInicio, $horaFin, 30);

                    // Filtrar slots ocupados
                    $slotsOcupados = [];
                    if (isset($citasProgramadas[$fechaActual])) {
                        $slotsOcupados = $citasProgramadas[$fechaActual];
                    }

                    $slotsFinales = array_diff($slotsDisponibles, $slotsOcupados);

                    // Solo agregar el día si hay slots disponibles
                    if (!empty($slotsFinales)) {
                        $dispMedico['horarios'][$fechaActual] = $slotsFinales;
                    }
                }

                $currentDate->modify('+1 day');
            }

            // Solo agregar médico si tiene horarios disponibles
            if (!empty($dispMedico['horarios'])) {
                $disponibilidad[] = $dispMedico;
            }
        }

        return $disponibilidad;
    }

    /**
     * Obtiene la lista de médicos para el calendario
     *
     * @param int|null $medicoId ID del médico específico (opcional)
     * @param int|null $especialidadId ID de la especialidad (opcional)
     * @return array
     */
    private function getMedicosParaCalendario($medicoId = null, $especialidadId = null)
    {
        $this->emptyQuery();
        $this->select(
            "m.idpersonal",
            "m.nombre",
            "e.nombre as especialidad",
            "e.idespecialidad"
        );

        $this->table = "sd_personal_medico m";
        $this->join("sd_personal_especialidad pe", "m.idpersonal", "pe.idpersonal");
        $this->join("sd_especialidades e", "pe.idespecialidad", "e.idespecialidad");

        $this->where("m.eliminado", '0');

        if ($medicoId) {
            $this->where("m.idpersonal", $medicoId);
        }

        if ($especialidadId) {
            $this->where("e.idespecialidad", $especialidadId);
        }

        $this->orderBy("m.nombre", "ASC");

        return $this->get();
    }

    /**
     * Obtiene los horarios configurados de un médico agrupados por día de la semana
     *
     * @param int $medicoId
     * @return array
     */
    private function getHorariosMedicoPorDia($medicoId)
    {
        $this->emptyQuery();
        $this->select("h.iddia", "h.hora_inicio", "h.hora_fin");
        $this->table = "sd_horarios_medicos h";
        $this->where("h.idpersonal", $medicoId);
        $this->where("h.activo", '1');
        $this->where("h.eliminado", '0');

        $resultado = $this->get();

        // Agrupar por día de la semana
        $horariosPorDia = [];
        foreach ($resultado as $horario) {
            $horariosPorDia[$horario['iddia']] = [
                'hora_inicio' => $horario['hora_inicio'],
                'hora_fin' => $horario['hora_fin']
            ];
        }

        return $horariosPorDia;
    }

    /**
     * Obtiene las citas programadas para un médico en un período
     *
     * @param int $medicoId
     * @param string $fechaInicio
     * @param string $fechaFin
     * @return array
     */
    private function getCitasProgramadasPeriodo($medicoId, $fechaInicio, $fechaFin)
    {
        $this->emptyQuery();
        $this->select("fecha", "hora");
        $this->table = "ac_citas";
        $this->where("idpersonal", $medicoId);
        $this->where("fecha", ">=", $fechaInicio);
        $this->where("fecha", "<=", $fechaFin);
        $this->where("eliminado", 0);

        $citas = $this->get();

        // Agrupar por fecha
        $citasPorFecha = [];
        foreach ($citas as $cita) {
            if (!isset($citasPorFecha[$cita['fecha']])) {
                $citasPorFecha[$cita['fecha']] = [];
            }
            $citasPorFecha[$cita['fecha']][] = $cita['hora'];
        }

        return $citasPorFecha;
    }

    /**
     * Genera slots de tiempo entre dos horas con un intervalo específico
     *
     * @param string $horaInicio Hora de inicio (formato H:i:s)
     * @param string $horaFin Hora de fin (formato H:i:s)
     * @param int $intervaloMinutos Intervalo en minutos
     * @return array Array con los slots generados (formato H:i:s)
     */
    private function generarSlotsTiempo($horaInicio, $horaFin, $intervaloMinutos = 30)
    {
        $slots = [];
        $inicio = strtotime($horaInicio);
        $fin = strtotime($horaFin);
        $intervaloSegundos = $intervaloMinutos * 60;

        for ($hora = $inicio; $hora < $fin; $hora += $intervaloSegundos) {
            $slots[] = date('H:i:s', $hora);
        }

        return $slots;
    }

    /**
     * Obtiene la próxima cita disponible para cada médico o especialidad
     *
     * @param int|null $especialidadId ID de la especialidad (opcional)
     * @param int $diasFuturos Número de días futuros a revisar
     * @return array
     */
    public function getProximasCitasDisponibles($especialidadId = null, $diasFuturos = 30)
    {
        // Fechas para el período
        $fechaInicio = date('Y-m-d');
        $fechaFin = date('Y-m-d', strtotime("+{$diasFuturos} days"));

        // Obtener médicos según filtros
        $medicos = $this->getMedicosParaCalendario(null, $especialidadId);

        $proximasCitas = [];

        foreach ($medicos as $medico) {
            $proximaCita = [
                'id' => $medico['idpersonal'],
                'nombre' => $medico['nombre'],
                'especialidad' => $medico['especialidad'],
                'proximaCita' => null
            ];

            // Obtener horarios configurados del médico por día de la semana
            $horariosConfigurados = $this->getHorariosMedicoPorDia($medico['idpersonal']);

            // Obtener citas ya programadas
            $citasProgramadas = $this->getCitasProgramadasPeriodo($medico['idpersonal'], $fechaInicio, $fechaFin);

            // Procesar cada día en el rango
            $currentDate = new \DateTime($fechaInicio);
            $endDate = new \DateTime($fechaFin);

            while ($currentDate <= $endDate && !$proximaCita['proximaCita']) {
                $fechaActual = $currentDate->format('Y-m-d');
                $diaSemana = $currentDate->format('N'); // 1 (lunes) a 7 (domingo)

                // Verificar si el médico tiene horario configurado para este día
                if (isset($horariosConfigurados[$diaSemana])) {
                    $horaInicio = $horariosConfigurados[$diaSemana]['hora_inicio'];
                    $horaFin = $horariosConfigurados[$diaSemana]['hora_fin'];

                    // Generar slots de 30 minutos
                    $slotsDisponibles = $this->generarSlotsTiempo($horaInicio, $horaFin, 30);

                    // Filtrar slots ocupados
                    $slotsOcupados = [];
                    if (isset($citasProgramadas[$fechaActual])) {
                        $slotsOcupados = $citasProgramadas[$fechaActual];
                    }

                    $slotsFinales = array_diff($slotsDisponibles, $slotsOcupados);

                    // Si hay slots disponibles, tomar el primero como próxima cita
                    if (!empty($slotsFinales)) {
                        $proximaCita['proximaCita'] = [
                            'fecha' => $fechaActual,
                            'hora' => reset($slotsFinales)
                        ];
                    }
                }

                $currentDate->modify('+1 day');
            }

            // Solo agregar médico si tiene una próxima cita disponible
            if ($proximaCita['proximaCita']) {
                $proximasCitas[] = $proximaCita;
            }
        }

        return $proximasCitas;
    }
}
