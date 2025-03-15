<?php

namespace App\Controllers\Chio;

use App\Core\Controller;
use App\Models\TableModel;
use Exception;

class HorarioController extends Controller
{
    private $table = "sd_horarios_medicos";
    private $id = "id_horario_medico";
    private const PERMISSION = "ruta.horario-medico";

    public function __construct()
    {
        parent::__construct();
    }

    public function index($request, $response)
    {
        return $this->render($response, 'Chio.Horarios.Horarios', [
            'titulo_web' => 'Gestión de Horarios Médicos',
            "url" => $request->getUri()->getPath(),
            'permisos' => $this->permisos_extras,
            "permission" => self::PERMISSION,
            "css" => [
                "/node_modules/flatpickr/dist/flatpickr.min.css",
                "/vendor/select2/select2/dist/css/select2.min.css",
                "/css/select2-custom.css",
            ],
            "js" => [
                "/node_modules/flatpickr/dist/flatpickr.min.js",
                "/node_modules/flatpickr/dist/l10n/es.js",
                "/vendor/select2/select2/dist/js/select2.full.min.js",
                "/js/chio/horarios-medicos.js?v=" . time()
            ]
        ]);
    }

    public function list($request, $response)
    {
        try {
            $data = $this->sanitize($request->getParsedBody());

            $model = new TableModel();
            $model->setTable("sd_horarios_medicos h");
            $model->setId("id_horario_medico");

            $query = $model
                ->select(
                    "h.id_horario_medico",
                    "m.idpersonal as id_medico",
                    "m.nombre as nombre_medico",
                    "s.nombre as especialidad",
                    "d.nombre as dias_atencion",
                    "h.hora_inicio",
                    "h.hora_fin"
                )
                ->leftJoin("sd_dias_semana d", "h.iddia", "d.iddia")
                ->leftJoin("sd_personal_medico m", "h.idpersonal", "m.idpersonal")
                ->leftJoin("sd_personal_especialidad e", "m.idpersonal", "e.idpersonal")
                ->leftJoin("sd_especialidades s", "e.idespecialidad", "s.idespecialidad")
                ->where("m.eliminado", "0")
                ->where("h.activo", "1")
                ->where("s.eliminado", "0");

            // Filtro por estado
            if (isset($data['filtro_estado']) && $data['filtro_estado'] !== '') {
                $query->where('h.eliminado', $data['filtro_estado']);
            } else {
                $query->where('h.eliminado', "0");
            }

            // Búsqueda general
            if (!empty($data['filtro_search'])) {
                $search = $data['filtro_search'];
                $query->where(function ($q) use ($search) {
                    $q->where('m.nombre', 'LIKE', "%$search%")
                        ->orWhere('s.nombre', 'LIKE', "%$search%");
                });
            }

            $horarios = $query->orderBy('m.nombre')->get();

            // Formatear horarios para mostrar en tabla
            foreach ($horarios as &$horario) {
                $horaInicio = date("h:i A", strtotime($horario['hora_inicio']));
                $horaFin = date("h:i A", strtotime($horario['hora_fin']));
                $horario['horario_formato'] = "{$horario["dias_atencion"]} de {$horaInicio} a {$horaFin}";
            }

            return $this->respondWithJson($response, $horarios);
        } catch (Exception $e) {
            return $this->respondWithError($response, $e->getMessage());
        }
    }

    public function store($request, $response)
    {
        try {
            $this->checkPermission(self::PERMISSION, "create");

            $data = $this->sanitize($request->getParsedBody());

            // Validación de datos
            if (!$this->validateData($data)) {
                return $this->respondWithError($response, "Los campos con (*) son obligatorios");
            }

            // Verificar que la hora de fin sea mayor que la hora de inicio
            if (strtotime($data['hora_fin']) <= strtotime($data['hora_inicio'])) {
                return $this->respondWithError($response, "La hora de fin debe ser mayor que la hora de inicio");
            }

            $model = new TableModel();
            $model->setTable($this->table);
            $model->setId($this->id);

            // Verificar si hay días ya registrados para este médico
            $diasExistentes = [];
            foreach ($data['dias'] as $idDia) {
                $existing = $model->query(
                    "
                SELECT d.nombre 
                FROM {$this->table} h
                JOIN sd_dias_semana d ON h.iddia = d.iddia
                WHERE h.idpersonal = ? 
                AND h.iddia = ?
                AND h.eliminado = 0",
                    [$data['id_medico'], $idDia]
                )->first();

                if ($existing) {
                    $diasExistentes[] = $existing['nombre'];
                }
            }

            // Si todos los días ya existen, informar al usuario
            if (count($diasExistentes) === count($data['dias'])) {
                return $this->respondWithError($response, "No se pueden registrar los horarios porque todos los días seleccionados ya tienen horarios asignados: " . implode(", ", $diasExistentes));
            }

            // Si algunos días ya existen, informar al usuario y preguntar si desea continuar
            if (count($diasExistentes) > 0) {
                return $this->respondWithJson($response, [
                    "success" => false,
                    "warning" => true,
                    "dias_existentes" => $diasExistentes,
                    "message" => "Los siguientes días ya tienen horarios asignados: " . implode(", ", $diasExistentes) . ". ¿Desea continuar registrando los días restantes?"
                ]);
            }

            // Proceder con la creación de los horarios para los días seleccionados
            $successCount = 0;
            $totalDias = count($data['dias']);

            foreach ($data['dias'] as $idDia) {
                $rq = $model->create([
                    "idpersonal" => $data["id_medico"],
                    "iddia" => $idDia,
                    "hora_inicio" => $data["hora_inicio"],
                    "hora_fin" => $data["hora_fin"],
                    "creado_por" => $_SESSION["app_id"]
                ]);

                if ($rq) {
                    $successCount++;
                }
            }

            return $this->respondWithSuccess($response, "Se han registrado {$successCount} horarios correctamente");
        } catch (Exception $e) {
            return $this->respondWithError($response, $e->getMessage());
        }
    }

    public function search($request, $response, $args)
    {
        try {
            $this->checkPermission(self::PERMISSION, "update");

            $id = $args['id'];
            $model = new TableModel();
            $model->setTable($this->table . " h");
            $model->setId($this->id);

            $horarioData = $model
                ->select(
                    "m.idpersonal as id_medico",
                    "m.nombre as nombre_medico",
                    "s.nombre as especialidad",
                    "d.nombre as dias_atencion",
                    "h.hora_inicio",
                    "h.hora_fin"

                )
                ->leftJoin("sd_dias_semana d", "h.iddia", "d.iddia")
                ->leftJoin("sd_personal_medico m", "h.idpersonal", "m.idpersonal")
                ->leftJoin("sd_personal_especialidad e", "m.idpersonal", "e.idpersonal")
                ->leftJoin("sd_especialidades s", "e.idespecialidad", "s.idespecialidad")
                ->where("h.id_horario_medico", $id)
                ->where("m.eliminado", "0")
                ->where("h.eliminado", "0")
                ->get();

            if (empty($horarioData)) {
                return $this->respondWithJson(
                    $response,
                    [
                        "success" => false,
                        "message" => "Horario no encontrado"
                    ]
                );
            }

            return $this->respondWithJson($response, [
                "success" => true,
                "horario" => $horarioData
            ]);
        } catch (Exception $e) {
            return $this->respondWithError($response, $e->getMessage());
        }
    }

    public function update($request, $response, $args)
    {
        try {
            $this->checkPermission(self::PERMISSION, "update");

            $id = $args['id'];
            $data = $this->sanitize($request->getParsedBody());

            if (!$this->validateData($data)) {
                return $this->respondWithError($response, "Los campos con (*) son obligatorios");
            }

            // Verificar que la hora de fin sea mayor que la hora de inicio
            if (strtotime($data['hora_fin']) <= strtotime($data['hora_inicio'])) {
                return $this->respondWithError($response, "La hora de fin debe ser mayor que la hora de inicio");
            }

            $model = new TableModel();
            $model->setTable($this->table);
            $model->setId($this->id);

            // Obtener información del horario actual
            $horarioActual = $model->find($id);
            if (!$horarioActual) {
                return $this->respondWithError($response, "Horario no encontrado");
            }

            // Actualizar el horario
            $rq = $model->update($id, [
                // "idpersonal" => $data["id_medico"],
                "hora_inicio" => $data["hora_inicio"],
                "hora_fin" => $data["hora_fin"],
                "actualizado_por" => $_SESSION["app_id"]
            ]);

            return $rq
                ? $this->respondWithSuccess($response, "Horario actualizado correctamente")
                : $this->respondWithError($response, "Error al actualizar el horario");
        } catch (Exception $e) {
            return $this->respondWithError($response, $e->getMessage());
        }
    }

    public function delete($request, $response, $args)
    {
        try {
            $this->checkPermission(self::PERMISSION, "delete");

            $id = $args['id'];
            $model = new TableModel();
            $model->setTable($this->table);
            $model->setId($this->id);

            // Verificar si existe y no está eliminado
            $horario = $model
                ->where('id_horario_medico', $id)
                ->where('eliminado', "0")
                ->first();

            if (!$horario) {
                return $this->respondWithError($response, "Horario no encontrado");
            }

            $rq = $model->update($id, [
                "eliminado" => "1",
                "fecha_eliminacion" => date('Y-m-d H:i:s'),
                "eliminado_por" => $_SESSION["app_id"]
            ]);

            return $rq
                ? $this->respondWithSuccess($response, "Horario eliminado correctamente")
                : $this->respondWithError($response, "Error al eliminar el horario");
        } catch (Exception $e) {
            return $this->respondWithError($response, $e->getMessage());
        }
    }

    public function getMedicos($request, $response)
    {
        try {
            $param = $request->getQueryParams();
            $term = $param["term"] ?? '';
            $page = $param["page"] ?? 1;
            $limit = 10;
            $offset = ($page - 1) * $limit;

            $model = new TableModel();

            // Obtener total de registros filtrados
            $totalQuery = $model->query(
                "
                SELECT COUNT(*) as total
                FROM sd_personal_medico
                WHERE (nombre LIKE ? OR dni LIKE ?)
                AND eliminado = 0",
                ["%$term%", "%$term%"]
            );

            $total = $totalQuery->first()['total'];

            // Obtener registros paginados
            $medicos = $model->query(
                "
                SELECT 
                    m.idpersonal as id,
                    m.nombre as text,
                    s.nombre as especialidad
                FROM sd_personal_medico m
                LEFT JOIN sd_personal_especialidad e ON m.idpersonal = e.idpersonal
                LEFT JOIN sd_especialidades s ON e.idespecialidad = s.idespecialidad
                WHERE (m.nombre LIKE ? OR m.dni LIKE ?)
                AND m.eliminado = 0
                ORDER BY m.nombre ASC
                LIMIT ? OFFSET ?",
                ["%$term%", "%$term%", $limit, $offset]
            )->get();

            return $this->respondWithJson($response, [
                "status" => true,
                'results' => $medicos,
                'pagination' => [
                    'more' => ($page * $limit) < $total
                ]
            ]);
        } catch (Exception $e) {
            return $this->respondWithJson($response, [
                "status" => false,
                'results' => [],
                'pagination' => ['more' => false],
                'error' => $e->getMessage()
            ]);
        }
    }

    private function validateData($data)
    {
        return !empty($data['id_medico']) &&
            !empty($data['dias']) &&
            !empty($data['hora_inicio']) &&
            !empty($data['hora_fin']);
    }

    private function formatearDiasAtencion($diasStr)
    {
        $dias = explode(',', $diasStr);
        $diasSemana = [
            '0' => 'Domingo',
            '1' => 'Lunes',
            '2' => 'Martes',
            '3' => 'Miércoles',
            '4' => 'Jueves',
            '5' => 'Viernes',
            '6' => 'Sábado'
        ];

        $diasTexto = [];
        foreach ($dias as $dia) {
            if (isset($diasSemana[$dia])) {
                $diasTexto[] = $diasSemana[$dia];
            }
        }

        return implode(', ', $diasTexto);
    }
}
