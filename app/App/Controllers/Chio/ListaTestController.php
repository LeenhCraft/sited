<?php

namespace App\Controllers\Chio;

use App\Core\Controller;
use App\Models\PacientesModel;
use App\Models\PreguntasModel;
use App\Models\TestModel;
use Mpdf\Mpdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class ListaTestController extends Controller
{
    private const PERMISSION = "ruta.lista";
    private $testModel;
    private $pacienteModel;
    private $preguntaModel;

    public function __construct()
    {
        parent::__construct();
        $this->testModel = new TestModel();
        $this->pacienteModel = new PacientesModel();
        $this->preguntaModel = new PreguntasModel();
    }

    public function  index($request, $response)
    {
        return $this->render($response, "Chio.Test.Lista", [
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
                "/node_modules/flatpickr/dist/flatpickr.min.js",
                "/node_modules/flatpickr/dist/l10n/es.js",
                "/vendor/select2/select2/dist/js/select2.full.min.js",
                "https://cdn.jsdelivr.net/npm/chart.js",
                "/js/chio/lista.js?v=" . time()
            ]
        ]);
    }

    /**
     * Obtener todos los tests
     */
    public function getTests($request, $response)
    {
        $tests = $this->testModel->getAllTests();

        // Enriquecer datos con información de pacientes
        foreach ($tests as &$test) {
            $paciente = $this->pacienteModel->find($test['idpaciente']);
            if ($paciente) {
                $test['nombre'] = $paciente['nombre'];
                $test['dni'] = $paciente['dni'];
                $test['edad'] = $paciente['edad'];
                $test['sexo'] = $paciente['sexo'];
                $test['celular'] = $paciente['celular'];
            }
        }

        return $this->respondWithJson($response, [
            'status' => true,
            'data' => $tests
        ]);
    }

    /**
     * Obtener detalles de un test específico
     */
    public function getTestDetails($request, $response, $args)
    {
        $testId = $args['id'];

        // Obtener el test
        $test = $this->testModel->find($testId);
        if (!$test) {
            return $this->respondWithJson($response, [
                'status' => false,
                'message' => 'Test no encontrado'
            ]);
        }

        // Obtener el paciente
        $paciente = $this->pacienteModel->find($test['idpaciente']);

        // Obtener las preguntas y respuestas
        $preguntas = $this->testModel->getTestPreguntas($testId);

        return $this->respondWithJson($response, [
            'status' => true,
            'data' => [
                'test' => $test,
                'paciente' => $paciente,
                'preguntas' => $preguntas
            ]
        ]);
    }

    /**
     * Generar PDF de un test
     */
    public function printTest($request, $response, $args)
    {
        $testId = $args['id'];

        // Obtener datos del test
        $testData = $this->testModel->find($testId);
        if (!$testData) {
            return $this->respondWithJson($response, [
                'status' => false,
                'message' => 'Test no encontrado'
            ]);
        }

        $userData = $this->pacienteModel
            ->select(
                "dni",
                "nombre",
                "edad",
                "sexo"
            )
            ->find($testData['idpaciente']);
        $preguntas = $this->testModel->getTestPreguntas($testId);

        // Crear instancia mPDF
        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'margin_left' => 15,
            'margin_right' => 15,
            'margin_top' => 15,
            'margin_bottom' => 15
        ]);

        // Añadir estilos CSS
        $stylesheet = file_get_contents('./css/boxicons.css');
        $mpdf->WriteHTML($stylesheet, \Mpdf\HTMLParserMode::HEADER_CSS);

        // Datos del test
        $html = $this->view('Pdf.TestPdf', [
            "test" => [
                'user' => $userData,
                'test' => $testData,
                'preguntas' => $preguntas
            ]
        ]);

        // Escribir HTML
        $mpdf->WriteHTML($html);

        // Generar PDF
        $pdfContent = $mpdf->Output('', 'S');

        // Establecer headers para descarga
        $fileName = 'Test_Diabetes_' . $userData['dni'] . '_' . date('Ymd') . '.pdf';

        $response = $response->withHeader('Content-Type', 'application/pdf')
            ->withHeader('Content-Disposition', 'inline; filename="' . $fileName . '"')
            ->withHeader('Cache-Control', 'max-age=0, no-cache, no-store, must-revalidate')
            ->withHeader('Pragma', 'no-cache')
            ->withHeader('Expires', '0');

        $response->getBody()->write($pdfContent);

        return $response;
    }

    /**
     * Exportar datos en formato Excel
     */
    public function exportExcel($request, $response, $args)
    {
        // Obtener parámetros de filtrado
        $params = $request->getQueryParams();

        // Obtener todos los tests
        $tests = $this->testModel->getAllTests();

        // Aplicar filtros si es necesario
        $filteredTests = $this->applyFilters($tests, $params);

        // Crear un nuevo archivo Excel
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Tests de Diabetes');

        // Establecer encabezados
        $headers = ['ID', 'Paciente', 'DNI', 'Edad', 'Sexo', 'Fecha', 'Peso (kg)', 'Altura (m)', 'IMC', 'Riesgo'];
        $sheet->fromArray([$headers], null, 'A1');

        // Estilo para encabezados
        $headerStyle = [
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => '4472C4']],
            'borders' => [
                'allBorders' => ['borderStyle' => Border::BORDER_THIN]
            ],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
        ];

        $sheet->getStyle('A1:J1')->applyFromArray($headerStyle);

        // Agregar datos
        $row = 2;
        foreach ($filteredTests as $test) {
            $paciente = $this->pacienteModel->find($test['idpaciente']);

            if (!$paciente) {
                continue;
            }

            // Determinar nivel de riesgo
            /* $tendencia = floatval($test['tendencia_modelo']);
            $riesgo = 'Bajo'; */

            // if ($tendencia >= 60) {
            //     $riesgo = 'Alto';
            // } else if ($tendencia >= 40) {
            //     $riesgo = 'Moderado';
            // }

            $riesgo = $test["tendencia_label"];

            // Datos para esta fila
            $data = [
                $test['idtest'],
                $paciente['nombre'],
                $paciente['dni'],
                $paciente['edad'],
                $paciente['sexo'] === 'M' ? 'Masculino' : 'Femenino',
                date('d/m/Y H:i', strtotime($test['fecha_hora'])),
                $test['peso'],
                $test['altura'],
                number_format($test['imc'], 2),
                $riesgo
            ];

            $sheet->fromArray([$data], null, 'A' . $row);

            // Estilo condicional para el nivel de riesgo
            $riskCellStyle = [];

            if ($riesgo === 'Alto') {
                $riskCellStyle = [
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => 'FFCCCC']]
                ];
            } else if ($riesgo === 'Moderado') {
                $riskCellStyle = [
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => 'FFFFCC']]
                ];
            } else {
                $riskCellStyle = [
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => 'CCFFCC']]
                ];
            }

            $sheet->getStyle('J' . $row)->applyFromArray($riskCellStyle);

            $row++;
        }

        // Auto-ajustar el ancho de las columnas
        foreach (range('A', 'J') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Crear bordes en todas las celdas
        $lastRow = $row - 1;
        if ($lastRow >= 2) {
            $sheet->getStyle('A2:J' . $lastRow)->applyFromArray([
                'borders' => [
                    'allBorders' => ['borderStyle' => Border::BORDER_THIN]
                ]
            ]);
        }

        // Crear writer
        $writer = new Xlsx($spreadsheet);

        // Generar nombre de archivo
        $fileName = 'Tests_Diabetes_' . date('Ymd_His') . '.xlsx';

        // Crear archivo temporal
        $tempFile = tempnam(sys_get_temp_dir(), 'excel_');
        $writer->save($tempFile);

        // Leer contenido del archivo
        $fileContent = file_get_contents($tempFile);

        // Eliminar archivo temporal
        @unlink($tempFile);

        // Configurar cabeceras
        $response = $response->withHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')
            ->withHeader('Content-Disposition', 'attachment; filename="' . $fileName . '"')
            ->withHeader('Content-Length', strlen($fileContent))
            ->withHeader('Cache-Control', 'max-age=0, no-cache, no-store, must-revalidate')
            ->withHeader('Pragma', 'no-cache')
            ->withHeader('Expires', '0');

        // Escribir contenido
        $response->getBody()->write($fileContent);

        return $response;
    }

    /**
     * Exportar datos en formato PDF
     */
    public function exportPdf($request, $response, $args)
    {
        // Obtener parámetros de filtrado
        $params = $request->getQueryParams();

        // Obtener todos los tests
        $tests = $this->testModel->getAllTests();

        // Aplicar filtros si es necesario
        $filteredTests = $this->applyFilters($tests, $params);

        // Crear instancia mPDF
        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4-L', // Landscape
            'margin_left' => 15,
            'margin_right' => 15,
            'margin_top' => 15,
            'margin_bottom' => 15
        ]);

        // Añadir estilos CSS
        $stylesheet = file_get_contents('./css/pdf-styles.css');
        $mpdf->WriteHTML($stylesheet, \Mpdf\HTMLParserMode::HEADER_CSS);

        // Encabezado
        $html = '<div class="header">
            <h1>Reporte de Tests de Diabetes</h1>
            <p>Fecha de generación: ' . date('d/m/Y H:i:s') . '</p>
        </div>';

        // Tabla de datos
        $html .= '<div class="section">
            <h2>Lista de Tests</h2>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Paciente</th>
                        <th>DNI</th>
                        <th>Edad</th>
                        <th>Sexo</th>
                        <th>Fecha</th>
                        <th>Peso (kg)</th>
                        <th>Altura (m)</th>
                        <th>IMC</th>
                        <th>Riesgo</th>
                    </tr>
                </thead>
                <tbody>';

        // Agregar datos a la tabla
        foreach ($filteredTests as $test) {
            $paciente = $this->pacienteModel->find($test['idpaciente']);

            if (!$paciente) {
                continue;
            }

            // Determinar nivel de riesgo
            /* $tendencia = floatval($test['tendencia_modelo']);
            $riesgo = 'Bajo';
            $riesgoClass = 'nivel-bajo'; */

            /* if ($tendencia >= 60) {
                $riesgo = 'Alto';
                $riesgoClass = 'nivel-alto';
            } else if ($tendencia >= 40) {
                $riesgo = 'Moderado';
                $riesgoClass = 'nivel-medio';
            } */

            $riesgo = $test["tendencia_label"];
            $riesgoClass = 'nivel-bajo';

            if ($riesgo === 'Alto') {
                $riesgoClass = 'nivel-alto';
            } else if ($riesgo === 'Moderado') {
                $riesgoClass = 'nivel-medio';
            }

            $html .= '<tr>
                <td>' . $test['idtest'] . '</td>
                <td>' . $paciente['nombre'] . '</td>
                <td>' . $paciente['dni'] . '</td>
                <td>' . $paciente['edad'] . '</td>
                <td>' . ($paciente['sexo'] === 'M' ? 'Masculino' : 'Femenino') . '</td>
                <td>' . date('d/m/Y H:i', strtotime($test['fecha_hora'])) . '</td>
                <td>' . $test['peso'] . '</td>
                <td>' . $test['altura'] . '</td>
                <td>' . number_format($test['imc'], 2) . '</td>
                <td><span class="nivel ' . $riesgoClass . '">' . $riesgo . '</span></td>
            </tr>';
        }

        $html .= '</tbody>
            </table>
        </div>';

        // Estadísticas
        $totalTests = count($filteredTests);
        $riesgoBajo = 0;
        $riesgoModerado = 0;
        $riesgoAlto = 0;

        foreach ($filteredTests as $test) {
            /* $tendencia = floatval($test['tendencia_modelo']);

            if ($tendencia >= 60) {
                $riesgoAlto++;
            } else if ($tendencia >= 40) {
                $riesgoModerado++;
            } else {
                $riesgoBajo++;
            } */

            $riesgo = $test["tendencia_label"];

            if ($riesgo === 'Alto') {
                $riesgoAlto++;
            } else if ($riesgo === 'Moderado') {
                $riesgoModerado++;
            } else {
                $riesgoBajo++;
            }
        }

        $porcBajo = $totalTests > 0 ? ($riesgoBajo / $totalTests * 100) : 0;
        $porcModerado = $totalTests > 0 ? ($riesgoModerado / $totalTests * 100) : 0;
        $porcAlto = $totalTests > 0 ? ($riesgoAlto / $totalTests * 100) : 0;

        $html .= '<div class="section">
            <h2>Estadísticas</h2>
            <div class="stats-container">
                <div class="stat-item">
                    <h3>Total de Tests: ' . $totalTests . '</h3>
                </div>
                <div class="stat-item">
                    <h3>Distribución de Riesgo</h3>
                    <table class="stats-table">
                        <tr>
                            <th>Nivel de Riesgo</th>
                            <th>Cantidad</th>
                            <th>Porcentaje</th>
                        </tr>
                        <tr>
                            <td><span class="nivel nivel-bajo">Bajo</span></td>
                            <td>' . $riesgoBajo . '</td>
                            <td>' . number_format($porcBajo, 2) . '%</td>
                        </tr>
                        <tr>
                            <td><span class="nivel nivel-medio">Moderado</span></td>
                            <td>' . $riesgoModerado . '</td>
                            <td>' . number_format($porcModerado, 2) . '%</td>
                        </tr>
                        <tr>
                            <td><span class="nivel nivel-alto">Alto</span></td>
                            <td>' . $riesgoAlto . '</td>
                            <td>' . number_format($porcAlto, 2) . '%</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>';

        // Pie de página
        $html .= '<div class="footer">
            <p>Este reporte es generado automáticamente por el sistema de detección de diabetes.</p>
        </div>';

        // Escribir HTML
        $mpdf->WriteHTML($html);

        // Generar PDF
        $pdfContent = $mpdf->Output('', 'S');

        // Establecer headers para descarga
        $fileName = 'Reporte_Tests_Diabetes_' . date('Ymd_His') . '.pdf';

        $response = $response->withHeader('Content-Type', 'application/pdf')
            ->withHeader('Content-Disposition', 'attachment; filename="' . $fileName . '"')
            ->withHeader('Cache-Control', 'max-age=0, no-cache, no-store, must-revalidate')
            ->withHeader('Pragma', 'no-cache')
            ->withHeader('Expires', '0');

        $response->getBody()->write($pdfContent);

        return $response;
    }

    /**
     * Aplicar filtros a los datos
     */
    private function applyFilters($tests, $params)
    {
        $filteredTests = $tests;

        // Filtrar por rango de fechas
        if (isset($params['dateRange']) && !empty($params['dateRange'])) {
            $dateRange = $params['dateRange'];
            $dates = explode(' a ', $dateRange);

            $startDate = \DateTime::createFromFormat('d/m/Y', $dates[0]);
            $startDate->setTime(0, 0, 0);

            if (count($dates) > 1) {
                $endDate = \DateTime::createFromFormat('d/m/Y', $dates[1]);
                $endDate->setTime(23, 59, 59);
            } else {
                $endDate = clone $startDate;
                $endDate->setTime(23, 59, 59);
            }

            $filteredTests = array_filter($filteredTests, function ($test) use ($startDate, $endDate) {
                $testDate = new \DateTime($test['fecha_hora']);
                return $testDate >= $startDate && $testDate <= $endDate;
            });
        }

        // Filtrar por paciente
        if (isset($params['pacienteId']) && !empty($params['pacienteId'])) {
            $pacienteId = $params['pacienteId'];
            $filteredTests = array_filter($filteredTests, function ($test) use ($pacienteId) {
                return $test['idpaciente'] == $pacienteId;
            });
        }

        // Filtrar por tendencia
        if (isset($params['tendencia']) && !empty($params['tendencia'])) {
            $tendencia = $params['tendencia'];

            $filteredTests = array_filter($filteredTests, function ($test) use ($tendencia) {
                $value = floatval($test['tendencia_modelo']);

                if ($tendencia === 'Alto') {
                    return $value >= 60;
                } else if ($tendencia === 'Moderado') {
                    return $value >= 40 && $value < 60;
                } else if ($tendencia === 'Bajo') {
                    return $value < 40;
                }

                return true;
            });
        }

        return array_values($filteredTests);
    }
}
