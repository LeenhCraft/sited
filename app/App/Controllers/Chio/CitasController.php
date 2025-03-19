<?php

namespace App\Controllers\Chio;

use App\Core\Controller;
use App\Models\CitasModel;
use Exception;
use Mpdf\Mpdf;

class CitasController extends Controller
{
    private const PERMISSION = "ruta.citas-medicas";
    private $citasModel;

    public function __construct()
    {
        parent::__construct();
        $this->citasModel = new CitasModel();
    }

    public function  index($request, $response)
    {
        return $this->render($response, "Chio.Citas.Citas", [
            "titulo_web" => "Preguntas",
            "url" => $request->getUri()->getPath(),
            'permisos' => $this->permisos_extras,
            "permission" => self::PERMISSION,
            "css" => [
                "/node_modules/flatpickr/dist/flatpickr.min.css",
                "/vendor/select2/select2/dist/css/select2.min.css",
                "/css/select2-custom.css",
            ],
            "js" => [
                "/node_modules/moment/min/moment.min.js",
                "/node_modules/moment/locale/es.js",
                "/node_modules/flatpickr/dist/flatpickr.min.js",
                "/node_modules/flatpickr/dist/l10n/es.js",
                "/vendor/select2/select2/dist/js/select2.full.min.js",
                "/js/chio/citas-medicas.js?v=" . time()
            ]
        ]);
    }


    /**
     * Busca citas médicas con filtros para DataTables
     */
    public function search($request, $response)
    {
        try {
            // Obtener parámetros de la solicitud
            $params = $request->getQueryParams();

            // Configurar filtros
            $filters = [
                'fechaInicio' => $params['fechaInicio'] ?? null,
                'fechaFin' => $params['fechaFin'] ?? null,
                'especialidad' => $params['especialidad'] ?? null,
                'paciente' => $params['paciente'] ?? null,
                'estadoCita' => $params['estadoCita'] ?? null,
                'medico' => $params['medico'] ?? null,
            ];

            // Configurar paginación y ordenamiento
            $start = isset($params['start']) ? intval($params['start']) : 0;
            $length = isset($params['length']) ? intval($params['length']) : 10;

            // Obtener campo para ordenar
            $orderColumn = isset($params['order'][0]['column']) ? intval($params['order'][0]['column']) : 0;
            $orderDir = isset($params['order'][0]['dir']) ? $params['order'][0]['dir'] : 'desc';

            // Mapear índice de columna a nombre de campo
            $columns = [
                0 => 'ac.idcita',
                1 => 'p.nombre',
                2 => 'm.nombre',
                3 => 'e.nombre',
                4 => 'ac.fecha',
                5 => 'ac.hora',
                6 => 'ec.nombre',
                7 => 'ac.observaciones'
            ];

            $orderBy = $columns[$orderColumn] ?? 'ac.idcita';

            // dep([
            //     "filters" => $filters,
            //     "start" => $start,
            //     "length" => $length,
            //     "orderBy" => $orderBy,
            //     "orderDir" => $orderDir,
            // ], 1);

            // Obtener resultados
            $result = $this->citasModel->getCitasPaginadas($filters, $start, $length, $orderBy, $orderDir);

            return $this->respondWithJson($response, $result);
        } catch (Exception $e) {
            return $this->respondWithError($response, $e->getMessage());
        }
    }

    /**
     * Obtiene los datos de una cita médica por su ID
     */
    public function getCita($request, $response)
    {
        try {
            $params = $request->getQueryParams();
            $idCita = $params['id'] ?? 0;

            if (!$idCita) {
                throw new Exception("ID de cita no proporcionado");
            }

            $cita = $this->citasModel->getCitaById($idCita);

            if (!$cita) {
                throw new Exception("Cita no encontrada");
            }

            return $this->respondWithJson($response, [
                'success' => true,
                'data' => $cita
            ]);
        } catch (Exception $e) {
            return $this->respondWithJson($response, [
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Guarda una nueva cita médica
     */
    public function save($request, $response)
    {
        try {
            $this->checkPermission(self::PERMISSION, 'create');

            $data = $this->sanitize($request->getParsedBody());

            // Validar datos requeridos
            $this->validateCitaData($data);

            // Verificar si ya existe una cita para este médico, fecha y hora
            if ($this->citasModel->citaExistente($data['idpersonal'], $data['fecha'], $data['hora'])) {
                throw new Exception("Ya existe una cita programada para este médico en la fecha y hora seleccionada");
            }

            // Agregar campos adicionales
            $data['idusuario'] = $_SESSION["app_id"] ?? "0";
            $data['fecha_registro'] = date('Y-m-d H:i:s');
            $data['creado_por'] = $_SESSION["app_id"] ?? "0";

            $dataInsert = [
                "idusuario" => $data['idusuario'] ?? "0",
                "idpaciente" => $data['idpaciente'] ?? "0",
                "idpersonal" => $data['idpersonal'] ?? "0",
                "id_estado_cita" => $data['id_estado_cita'] ?? "0",
                "fecha" => $data['fecha'] ?? "0000-00-00",
                "hora" => $data['hora'] ?? "00:00:00",
                "observaciones" => $data['observaciones'] ?? "",
                "fecha_registro" => $data['fecha_registro'] ?? date('Y-m-d H:i:s'),
                "creado_por" => $data['creado_por'] ?? "0",
            ];

            // Guardar cita
            $cita = $this->citasModel->create($dataInsert);

            return $this->respondWithJson($response, [
                'success' => true,
                'message' => 'Cita guardada exitosamente',
                'data' => $cita
            ]);
        } catch (Exception $e) {
            return $this->respondWithJson($response, [
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Actualiza una cita médica existente
     */
    public function update($request, $response)
    {
        try {
            $this->checkPermission(self::PERMISSION, 'update');

            $data = $this->sanitize($request->getParsedBody());

            // Validar ID de cita
            if (empty($data['idcita'])) {
                throw new Exception("ID de cita no proporcionado");
            }

            $idCita = $data['idcita'];

            // Validar datos requeridos
            $this->validateCitaData($data);

            // Verificar si ya existe una cita para este médico, fecha y hora (excluyendo la cita actual)
            if ($this->citasModel->citaExistente($data['idpersonal'], $data['fecha'], $data['hora'], $idCita)) {
                throw new Exception("Ya existe una cita programada para este médico en la fecha y hora seleccionada");
            }

            // Agregar campos adicionales
            $data['ultima_actualizacion'] = date('Y-m-d H:i:s');
            $data['actualizado_por'] = $_SESSION["app_id"] ?? 0;

            $dataUpdate = [
                "idusuario" => $data['idusuario'] ?? "0",
                "idpaciente" => $data['idpaciente'] ?? "0",
                "idpersonal" => $data['idpersonal'] ?? "0",
                "id_estado_cita" => $data['id_estado_cita'] ?? "0",
                "fecha" => $data['fecha'] ?? "0000-00-00",
                "hora" => $data['hora'] ?? "00:00:00",
                "observaciones" => $data['observaciones'] ?? "",
                "ultima_actualizacion" => $data['ultima_actualizacion'] ?? date('Y-m-d H:i:s'),
                "actualizado_por" => $data['actualizado_por'] ?? "0",
            ];

            // Actualizar cita
            $cita = $this->citasModel->update($idCita, $dataUpdate);

            return $this->respondWithJson($response, [
                'success' => true,
                'message' => 'Cita actualizada exitosamente',
                'data' => $cita
            ]);
        } catch (Exception $e) {
            return $this->respondWithJson($response, [
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Elimina una cita médica (eliminación lógica)
     */
    public function delete($request, $response)
    {
        try {
            $this->checkPermission(self::PERMISSION, 'delete');

            $data = $this->sanitize($request->getParsedBody());

            // Validar ID de cita
            if (empty($data['idcita'])) {
                throw new Exception("ID de cita no proporcionado");
            }

            $idCita = $data['idcita'];

            // Datos para actualizar (eliminación lógica)
            $updateData = [
                'eliminado' => 1,
                'fecha_eliminacion' => date('Y-m-d H:i:s'),
                'eliminado_por' => $_SESSION["app_id"] ?? 0
            ];

            // Actualizar cita (eliminación lógica)
            $this->citasModel->update($idCita, $updateData);

            return $this->respondWithJson($response, [
                'success' => true,
                'message' => 'Cita eliminada exitosamente'
            ]);
        } catch (Exception $e) {
            return $this->respondWithJson($response, [
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Obtiene todas las especialidades médicas
     */
    public function getEspecialidades($request, $response)
    {
        try {
            $especialidades = $this->citasModel->getEspecialidades();

            return $this->respondWithJson($response, [
                'success' => true,
                'data' => $especialidades
            ]);
        } catch (Exception $e) {
            return $this->respondWithJson($response, [
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Obtiene todos los estados de citas médicas
     */
    public function getEstados($request, $response)
    {
        try {
            $estados = $this->citasModel->getEstadosCitas();

            return $this->respondWithJson($response, [
                'success' => true,
                'data' => $estados
            ]);
        } catch (Exception $e) {
            return $this->respondWithJson($response, [
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Obtiene los médicos por especialidad
     */
    public function getMedicosPorEspecialidad($request, $response)
    {
        try {
            $params = $request->getQueryParams();
            $idespecialidad = $params['idespecialidad'] ?? 0;

            $medicos = $this->citasModel->getMedicosPorEspecialidad($idespecialidad);

            return $this->respondWithJson($response, [
                'success' => true,
                'data' => $medicos
            ]);
        } catch (Exception $e) {
            return $this->respondWithJson($response, [
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Obtiene los horarios disponibles para un médico en una fecha específica
     */
    public function getHorariosDisponibles($request, $response)
    {
        try {
            $params = $request->getQueryParams();
            $idpersonal = $params['idpersonal'] ?? 0;
            $fecha = $params['fecha'] ?? '';
            $idcita = $params['idcita'] ?? null;

            if (!$idpersonal) {
                throw new Exception("ID de médico no proporcionado");
            }

            // if (!$fecha) {
            //     throw new Exception("Fecha no proporcionada");
            // }

            $horarios = $this->citasModel->getHorariosDisponibles($idpersonal, $fecha, $idcita);

            return $this->respondWithJson($response, [
                'success' => true,
                'data' => $horarios
            ]);
        } catch (Exception $e) {
            return $this->respondWithJson($response, [
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Busca pacientes para el select2
     */
    public function searchPacientes($request, $response)
    {
        try {
            $params = $request->getQueryParams();
            $query = $params['q'] ?? '';
            $page = $params['page'] ?? 1;

            if (empty($query) || strlen($query) < 2) {
                return $this->respondWithJson($response, [
                    'items' => [],
                    'total_count' => 0
                ]);
            }

            $resultados = $this->citasModel->searchPacientes($query, $page);

            return $this->respondWithJson($response, $resultados);
        } catch (Exception $e) {
            return $this->respondWithJson($response, [
                'success' => false,
                'message' => $e->getMessage(),
                'items' => [],
                'total_count' => 0
            ]);
        }
    }

    /**
     * Busca médicos para el select2
     */
    public function searchMedicos($request, $response)
    {
        try {
            $params = $request->getQueryParams();
            $query = $params['q'] ?? '';
            $especialidad = $params['especialidad'] ?? null;
            $page = $params['page'] ?? 1;

            if (empty($query) || strlen($query) < 2) {
                return $this->respondWithJson($response, [
                    'items' => [],
                    'total_count' => 0
                ]);
            }

            $resultados = $this->citasModel->searchMedicos($query, $especialidad, $page);

            return $this->respondWithJson($response, $resultados);
        } catch (Exception $e) {
            return $this->respondWithJson($response, [
                'success' => false,
                'message' => $e->getMessage(),
                'items' => [],
                'total_count' => 0
            ]);
        }
    }

    /**
     * Exporta citas médicas a PDF
     */
    public function exportPdf($request, $response)
    {
        try {
            $this->checkPermission(self::PERMISSION, 'print');

            $params = $request->getQueryParams();

            // Si se solicita una cita específica
            if (!empty($params['idcita'])) {
                $cita = $this->citasModel->getCitaById($params['idcita']);

                if (!$cita) {
                    throw new Exception("Cita no encontrada");
                }

                return $this->generateSingleCitaPdf($response, $cita);
            }

            // Filtros para exportación múltiple
            $filters = [
                'fechaInicio' => $params['fechaInicio'] ?? null,
                'fechaFin' => $params['fechaFin'] ?? null,
                'especialidad' => $params['especialidad'] ?? null,
                'paciente' => $params['paciente'] ?? null,
                'estadoCita' => $params['estadoCita'] ?? null,
                'medico' => $params['medico'] ?? null,
            ];

            // Validar rango de fechas (máximo 3 meses)
            if (!empty($filters['fechaInicio']) && !empty($filters['fechaFin'])) {
                $inicio = new \DateTime($filters['fechaInicio']);
                $fin = new \DateTime($filters['fechaFin']);
                $diff = $inicio->diff($fin);

                // 90 días
                if ($diff->days > 90) {
                    throw new Exception("El rango de fechas no puede ser mayor a 3 meses");
                }
            }

            $citas = $this->citasModel->getCitasParaExportar($filters);

            return $this->generateMultipleCitasPdf($response, $citas, $filters);
        } catch (Exception $e) {
            // Redirigir con mensaje de error
            $response->getBody()->write("<script>alert('Error: " . $e->getMessage() . "'); window.close();</script>");
            return $response
                ->withHeader('Content-Type', 'text/html')
                ->withStatus(200);
        }
    }

    /**
     * Genera PDF para una cita individual
     */
    private function generateSingleCitaPdf($response, $cita)
    {
        try {
            // Configurar mPDF
            $mpdf = new Mpdf([
                'mode' => 'utf-8',
                'format' => 'A4',
                'margin_header' => 5,
                'margin_top' => 20,
                'margin_bottom' => 20,
                'margin_left' => 15,
                'margin_right' => 15,
                'margin_footer' => 10,
            ]);

            // Establecer metadatos del documento
            $mpdf->SetTitle('Cita Médica #' . $cita['idcita']);
            $mpdf->SetAuthor('Sistema de Citas Médicas');

            // Cabecera y pie de página
            $mpdf->SetHTMLHeader($this->getPdfHeader('Detalle de Cita Médica'));
            $mpdf->SetHTMLFooter($this->getPdfFooter());

            // Generar contenido del PDF
            $fecha = date('d/m/Y', strtotime($cita['fecha']));
            $hora = date('h:i A', strtotime($cita['hora']));

            $html = '
            <style>
                body {
                    font-family: Arial, sans-serif;
                    font-size: 12pt;
                    line-height: 1.5;
                }
                .cita-card {
                    border: 1px solid #ccc;
                    border-radius: 8px;
                    padding: 15px;
                    margin-bottom: 20px;
                    background-color: #f9f9f9;
                }
                .cita-header {
                    background-color: #007bff;
                    color: white;
                    padding: 10px;
                    border-radius: 5px;
                    margin-bottom: 15px;
                }
                .cita-info {
                    margin-bottom: 20px;
                }
                .info-row {
                    display: table;
                    width: 100%;
                    margin-bottom: 5px;
                }
                .info-label {
                    display: table-cell;
                    width: 30%;
                    font-weight: bold;
                }
                .info-value {
                    display: table-cell;
                    width: 70%;
                }
                .observaciones {
                    margin-top: 20px;
                    background-color: #f5f5f5;
                    padding: 10px;
                    border-radius: 5px;
                    border-left: 4px solid #007bff;
                }
                .footer-note {
                    margin-top: 30px;
                    font-size: 10pt;
                    text-align: center;
                    color: #666;
                }
            </style>
            
            <div class="cita-card">
                <div class="cita-header">
                    <h1 style="margin: 0; font-size: 16pt;">Cita Médica #' . $cita['idcita'] . '</h1>
                </div>
                
                <div class="cita-info">
                    <div class="info-row">
                        <div class="info-label">Fecha:</div>
                        <div class="info-value">' . $fecha . '</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Hora:</div>
                        <div class="info-value">' . $hora . '</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Especialidad:</div>
                        <div class="info-value">' . $cita['especialidad'] . '</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Estado:</div>
                        <div class="info-value">' . $cita['estado'] . '</div>
                    </div>
                </div>
                
                <h2 style="font-size: 14pt; border-bottom: 1px solid #ccc; padding-bottom: 5px;">Información del Paciente</h2>
                <div class="cita-info">
                    <div class="info-row">
                        <div class="info-label">Nombre:</div>
                        <div class="info-value">' . $cita['paciente']['nombre'] . '</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">DNI:</div>
                        <div class="info-value">' . $cita['paciente']['dni'] . '</div>
                    </div>
                </div>
                
                <h2 style="font-size: 14pt; border-bottom: 1px solid #ccc; padding-bottom: 5px;">Información del Médico</h2>
                <div class="cita-info">
                    <div class="info-row">
                        <div class="info-label">Nombre:</div>
                        <div class="info-value">' . $cita['medico']['nombre'] . '</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">DNI:</div>
                        <div class="info-value">' . $cita['medico']['dni'] . '</div>
                    </div>
                </div>';

            // Agregar observaciones si existen
            if (!empty($cita['observaciones'])) {
                $html .= '
                <div class="observaciones">
                    <h2 style="font-size: 14pt; margin-top: 0;">Observaciones</h2>
                    <p>' . nl2br($cita['observaciones']) . '</p>
                </div>';
            }

            $html .= '
                <div class="footer-note">
                    <p>Este documento es un comprobante de su cita médica.</p>
                    <p>Por favor, preséntese 15 minutos antes de la hora programada.</p>
                </div>
            </div>';

            // Agregar contenido al PDF
            $mpdf->WriteHTML($html);

            // Generar PDF
            $pdfContent = $mpdf->Output('', 'S');

            // Enviar PDF como respuesta
            $response->getBody()->write($pdfContent);
            return $response
                ->withHeader('Content-Type', 'application/pdf')
                ->withHeader('Content-Disposition', 'inline; filename="cita_' . $cita['idcita'] . '.pdf"')
                ->withStatus(200);
        } catch (Exception $e) {
            throw new Exception("Error al generar el PDF: " . $e->getMessage());
        }
    }

    /**
     * Genera PDF para múltiples citas
     */
    private function generateMultipleCitasPdf($response, $citas, $filters)
    {
        try {
            // Configurar mPDF
            $mpdf = new Mpdf([
                'mode' => 'utf-8',
                'format' => 'A4-L', // Landscape
                'margin_header' => 5,
                'margin_top' => 20,
                'margin_bottom' => 20,
                'margin_left' => 15,
                'margin_right' => 15,
                'margin_footer' => 10,
            ]);

            // Establecer metadatos del documento
            $mpdf->SetTitle('Reporte de Citas Médicas');
            $mpdf->SetAuthor('Sistema de Citas Médicas');

            // Cabecera y pie de página
            $mpdf->SetHTMLHeader($this->getPdfHeader('Reporte de Citas Médicas'));
            $mpdf->SetHTMLFooter($this->getPdfFooter());

            // Generar contenido del PDF
            $html = '
            <style>
                body {
                    font-family: Arial, sans-serif;
                    font-size: 10pt;
                    line-height: 1.5;
                }
                .filters {
                    margin-bottom: 20px;
                    padding: 10px;
                    background-color: #f5f5f5;
                    border-radius: 5px;
                }
                .filter-title {
                    font-weight: bold;
                    margin-right: 5px;
                }
                .filter-item {
                    margin-right: 15px;
                    display: inline-block;
                }
                table {
                    width: 100%;
                    border-collapse: collapse;
                    margin-bottom: 20px;
                }
                th {
                    background-color: #007bff;
                    color: white;
                    font-weight: bold;
                    text-align: left;
                    padding: 8px;
                }
                td {
                    border-bottom: 1px solid #ddd;
                    padding: 8px;
                }
                tr:nth-child(even) {
                    background-color: #f9f9f9;
                }
                .footer-note {
                    margin-top: 30px;
                    font-size: 9pt;
                    text-align: center;
                    color: #666;
                }
                .no-results {
                    text-align: center;
                    padding: 20px;
                    color: #666;
                    font-style: italic;
                }
            </style>
            
            <div class="filters">
                <span class="filter-title">Filtros aplicados:</span>';

            // Mostrar filtros aplicados
            if (!empty($filters['fechaInicio']) && !empty($filters['fechaFin'])) {
                $html .= '<span class="filter-item">Período: ' . date('d/m/Y', strtotime($filters['fechaInicio'])) . ' - ' . date('d/m/Y', strtotime($filters['fechaFin'])) . '</span>';
            } elseif (!empty($filters['fechaInicio'])) {
                $html .= '<span class="filter-item">Desde: ' . date('d/m/Y', strtotime($filters['fechaInicio'])) . '</span>';
            } elseif (!empty($filters['fechaFin'])) {
                $html .= '<span class="filter-item">Hasta: ' . date('d/m/Y', strtotime($filters['fechaFin'])) . '</span>';
            }

            if (
                empty($filters['fechaInicio']) && empty($filters['fechaFin']) && empty($filters['especialidad']) &&
                empty($filters['paciente']) && empty($filters['estadoCita']) && empty($filters['medico'])
            ) {
                $html .= '<span class="filter-item">Sin filtros aplicados</span>';
            }

            $html .= '
            </div>
            
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Fecha</th>
                        <th>Hora</th>
                        <th>Paciente</th>
                        <th>DNI</th>
                        <th>Médico</th>
                        <th>Especialidad</th>
                        <th>Estado</th>
                        <th>Observaciones</th>
                    </tr>
                </thead>
                <tbody>';

            if (empty($citas)) {
                $html .= '
                <tr>
                    <td colspan="9" class="no-results">No se encontraron citas con los filtros seleccionados</td>
                </tr>';
            } else {
                foreach ($citas as $cita) {
                    $observaciones = !empty($cita['observaciones']) ?
                        (strlen($cita['observaciones']) > 50 ? substr($cita['observaciones'], 0, 47) . '...' : $cita['observaciones']) :
                        '-';

                    $html .= '
                    <tr>
                        <td>' . $cita['idcita'] . '</td>
                        <td>' . date('d/m/Y', strtotime($cita['fecha'])) . '</td>
                        <td>' . date('h:i A', strtotime($cita['hora'])) . '</td>
                        <td>' . $cita['paciente'] . '</td>
                        <td>' . $cita['paciente_dni'] . '</td>
                        <td>' . $cita['medico'] . '</td>
                        <td>' . $cita['especialidad'] . '</td>
                        <td>' . $cita['estado'] . '</td>
                        <td>' . $observaciones . '</td>
                    </tr>';
                }
            }

            $html .= '
                </tbody>
            </table>
            
            <div class="footer-note">
                <p>Este reporte fue generado el ' . date('d/m/Y H:i:s') . '</p>
                <p>Total de citas: ' . count($citas) . '</p>
            </div>';

            // Agregar contenido al PDF
            $mpdf->WriteHTML($html);

            // Generar PDF
            $pdfContent = $mpdf->Output('', 'S');

            // Enviar PDF como respuesta
            $response->getBody()->write($pdfContent);
            return $response
                ->withHeader('Content-Type', 'application/pdf')
                ->withHeader('Content-Disposition', 'inline; filename="reporte_citas.pdf"')
                ->withStatus(200);
        } catch (Exception $e) {
            throw new Exception("Error al generar el PDF: " . $e->getMessage());
        }
    }

    /**
     * Devuelve el HTML para la cabecera del PDF
     */
    private function getPdfHeader($title)
    {
        $currentDate = date('d/m/Y');

        return '
        <table width="100%" style="border-bottom: 1px solid #ddd; margin-bottom: 10px;">
            <tr>
                <td width="33%" style="text-align: left;">
                    <img src="' . $_ENV['APP_URL'] . '/img/logo.png" height="40">
                </td>
                <td width="33%" style="text-align: center; font-weight: bold; font-size: 16pt;">' . $title . '</td>
                <td width="33%" style="text-align: right; font-size: 10pt;">Fecha: ' . $currentDate . '</td>
            </tr>
        </table>';
    }

    /**
     * Devuelve el HTML para el pie de página del PDF
     */
    private function getPdfFooter()
    {
        return '
        <table width="100%" style="border-top: 1px solid #ddd; margin-top: 10px;">
            <tr>
                <td width="33%" style="text-align: left; font-size: 9pt;">Sistema de Citas Médicas</td>
                <td width="33%" style="text-align: center; font-size: 9pt;">Página {PAGENO} de {nbpg}</td>
                <td width="33%" style="text-align: right; font-size: 9pt;">' . $_ENV['APP_URL'] . '</td>
            </tr>
        </table>';
    }

    /**
     * Valida los datos de una cita médica
     */
    private function validateCitaData($data)
    {
        $requiredFields = [
            'idpaciente' => 'Paciente',
            'idpersonal' => 'Médico',
            'id_estado_cita' => 'Estado de cita',
            'fecha' => 'Fecha',
            'hora' => 'Hora'
        ];

        foreach ($requiredFields as $field => $label) {
            if (empty($data[$field])) {
                throw new Exception("El campo {$label} es obligatorio");
            }
        }

        // Validar fecha
        if (!empty($data['fecha']) && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $data['fecha'])) {
            throw new Exception("El formato de fecha debe ser YYYY-MM-DD");
        }

        // Validar hora
        if (!empty($data['hora']) && !preg_match('/^\d{2}:\d{2}:\d{2}$/', $data['hora'])) {
            throw new Exception("El formato de hora debe ser HH:MM:SS");
        }
    }

    /**
     * Obtiene la disponibilidad de horarios para un rango de fechas
     */
    public function getDisponibilidadHorarios($request, $response)
    {
        try {
            $params = $request->getQueryParams();

            // Parámetros
            $medicoId = isset($params['idpersonal']) && !empty($params['idpersonal']) ? intval($params['idpersonal']) : null;
            $especialidadId = isset($params['idespecialidad']) && !empty($params['idespecialidad']) ? intval($params['idespecialidad']) : null;

            // Fechas por defecto (mes actual)
            $hoy = new \DateTime();
            $fechaInicio = isset($params['fechaInicio']) && !empty($params['fechaInicio'])
                ? $params['fechaInicio']
                : $hoy->format('Y-m-01'); // Primer día del mes

            $finMes = clone $hoy;
            $finMes->modify('last day of this month');
            $fechaFin = isset($params['fechaFin']) && !empty($params['fechaFin'])
                ? $params['fechaFin']
                : $finMes->format('Y-m-d'); // Último día del mes

            $disponibilidad = $this->citasModel->getDisponibilidadHorarios(
                $medicoId,
                $fechaInicio,
                $fechaFin,
                $especialidadId
            );

            return $this->respondWithJson($response, [
                'success' => true,
                'data' => $disponibilidad,
                'periodo' => [
                    'inicio' => $fechaInicio,
                    'fin' => $fechaFin
                ]
            ]);
        } catch (Exception $e) {
            return $this->respondWithJson($response, [
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Obtiene las próximas citas disponibles para médicos o especialidades
     */
    public function getProximasCitasDisponibles($request, $response)
    {
        try {
            $params = $request->getQueryParams();

            // Parámetros
            $especialidadId = isset($params['idespecialidad']) && !empty($params['idespecialidad'])
                ? intval($params['idespecialidad'])
                : null;

            $diasFuturos = isset($params['dias']) && !empty($params['dias'])
                ? intval($params['dias'])
                : 30; // 30 días por defecto

            $proximasCitas = $this->citasModel->getProximasCitasDisponibles(
                $especialidadId,
                $diasFuturos
            );

            return $this->respondWithJson($response, [
                'success' => true,
                'data' => $proximasCitas
            ]);
        } catch (Exception $e) {
            return $this->respondWithJson($response, [
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
}
