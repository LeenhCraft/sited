<?php header_web('Template.HeaderDashboard', $data);
$permisos = $data['permisos'];
$permissions = $data["permission"];
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="/admin">Inicio</a></li>
                        <li class="breadcrumb-item active">Lista de Tests</li>
                    </ol>
                </div>
                <h4 class="page-title">Lista de Tests de Diabetes</h4>
            </div>
        </div>

    </div>
    <!-- Filtros y acciones -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="filter-date-range">Rango de Fechas</label>
                                <input type="text" class="form-control date-range-picker" id="filter-date-range" placeholder="Seleccionar rango">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="filter-paciente">Paciente</label>
                                <select class="form-control select2" id="filter-paciente">
                                    <option value="">Todos los pacientes</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="filter-tendencia">Tendencia</label>
                                <select class="form-control" id="filter-tendencia">
                                    <option value="">Todas</option>
                                    <option value="Bajo">Bajo</option>
                                    <option value="Moderado">Moderado</option>
                                    <option value="Alto">Alto</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <button type="button" id="btn-filter" class="btn btn-primary me-2">
                                <i class="bx bx-filter-alt"></i> Filtrar
                            </button>
                            <?php if (isset($permisos[$permissions]["print"]) && $permisos[$permissions]["print"]) : ?>
                                <div class="dropdown">
                                    <button class="btn btn-success dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="bx bx-export"></i> Exportar
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                        <a class="dropdown-item" href="#" id="export-pdf"><i class="bx bxs-file-pdf"></i> PDF</a>
                                        <a class="dropdown-item" href="#" id="export-excel"><i class="bx bxs-file-doc"></i> Excel</a>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Panel de estadísticas -->
    <div class="row mb-3">
        <div class="col-md-3">
            <div class="card mini-stats-wid">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-grow-1">
                            <p class="text-muted fw-medium">Total Tests</p>
                            <h4 class="mb-0" id="total-tests">0</h4>
                        </div>
                        <div class="mini-stat-icon avatar-sm rounded-circle bg-primary d-flex justify-content-center align-items-center align-self-center">
                            <span class="avatar-title rounded-circle bg-primary text-white">
                                <i class="bx bx-clipboard bx-sm"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card mini-stats-wid">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-grow-1">
                            <p class="text-muted fw-medium">Riesgo Alto</p>
                            <h4 class="mb-0" id="high-risk-tests">0</h4>
                        </div>
                        <div class="mini-stat-icon avatar-sm rounded-circle bg-danger d-flex justify-content-center align-items-center align-self-center">
                            <span class="avatar-title rounded-circle bg-danger text-white">
                                <i class="bx bx-error bx-sm"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card mini-stats-wid">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-grow-1">
                            <p class="text-muted fw-medium">Riesgo Moderado</p>
                            <h4 class="mb-0" id="medium-risk-tests">0</h4>
                        </div>
                        <div class="mini-stat-icon avatar-sm rounded-circle bg-warning d-flex justify-content-center align-items-center align-self-center">
                            <span class="avatar-title rounded-circle bg-warning text-white">
                                <i class="bx bx-error-circle bx-sm"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card mini-stats-wid">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-grow-1">
                            <p class="text-muted fw-medium">Riesgo Bajo</p>
                            <h4 class="mb-0" id="low-risk-tests">0</h4>
                        </div>
                        <div class="mini-stat-icon avatar-sm rounded-circle bg-success d-flex justify-content-center align-items-center align-self-center">
                            <span class="avatar-title rounded-circle bg-success text-white">
                                <i class="bx bx-check-circle bx-sm"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Gráfico de distribución -->
    <div class="row mb-3">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-4">Tendencia Mensual de Tests</h4>
                    <div style="height: 300px;">
                        <canvas id="monthlyTrendChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-4">Distribución de Riesgo</h4>
                    <div style="height: 300px;">
                        <canvas id="riskDistributionChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla de tests -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="tests-datatable" class="table table-bordered dt-responsive nowrap w-100">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Paciente</th>
                                    <th>DNI</th>
                                    <th>Edad</th>
                                    <th>Fecha</th>
                                    <th>IMC</th>
                                    <th>Tendencia</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Los datos se cargarán por AJAX -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para ver detalles del test -->
<div class="modal fade" id="testDetailModal" aria-labelledby="testDetailModalLabel" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="testDetailModalLabel">Detalles del Test</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <!-- Información del paciente -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h5>Información del Paciente</h5>
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered">
                                <tbody>
                                    <tr>
                                        <th width="30%">Nombre</th>
                                        <td id="detail-patient-name"></td>
                                    </tr>
                                    <tr>
                                        <th>DNI</th>
                                        <td id="detail-patient-dni"></td>
                                    </tr>
                                    <tr>
                                        <th>Edad</th>
                                        <td id="detail-patient-age"></td>
                                    </tr>
                                    <tr>
                                        <th>Sexo</th>
                                        <td id="detail-patient-sex"></td>
                                    </tr>
                                    <tr>
                                        <th>Teléfono</th>
                                        <td id="detail-patient-phone"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h5>Información del Test</h5>
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered">
                                <tbody>
                                    <tr>
                                        <th width="30%">ID</th>
                                        <td id="detail-test-id"></td>
                                    </tr>
                                    <tr>
                                        <th>Fecha</th>
                                        <td id="detail-test-date"></td>
                                    </tr>
                                    <tr>
                                        <th>Peso</th>
                                        <td id="detail-test-weight"></td>
                                    </tr>
                                    <tr>
                                        <th>Altura</th>
                                        <td id="detail-test-height"></td>
                                    </tr>
                                    <tr>
                                        <th>IMC</th>
                                        <td id="detail-test-imc"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Resultado y gráfico -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="card border">
                            <div class="card-body">
                                <h5 class="card-title">Resultado del Análisis</h5>
                                <div class="mt-3">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h6 class="mb-0">Riesgo Bajo:</h6>
                                        <div id="detail-low-risk" class="progress-bar-container">
                                            <div class="progress" style="height: 10px; width: 80%;">
                                                <div class="progress-bar bg-success" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                            <span class="ml-2">0%</span>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h6 class="mb-0">Riesgo Moderado:</h6>
                                        <div id="detail-medium-risk" class="progress-bar-container">
                                            <div class="progress" style="height: 10px; width: 80%;">
                                                <div class="progress-bar bg-warning" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                            <span class="ml-2">0%</span>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h6 class="mb-0">Riesgo Alto:</h6>
                                        <div id="detail-high-risk" class="progress-bar-container">
                                            <div class="progress" style="height: 10px; width: 80%;">
                                                <div class="progress-bar bg-danger" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                            <span class="ml-2">0%</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-4">
                                    <div class="alert" id="detail-risk-alert">
                                        <h6 class="alert-heading" id="detail-risk-text">Resultado</h6>
                                        <p id="detail-risk-description"></p>
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <h6>Recomendaciones:</h6>
                                    <ul id="detail-recommendations" class="pl-3">
                                        <!-- Recomendaciones se cargarán dinámicamente -->
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card border">
                            <div class="card-body">
                                <h5 class="card-title">Análisis de Respuestas</h5>
                                <div style="height: 350px;">
                                    <canvas id="responsesRadarChart"></canvas>
                                </div>
                                <div class="mt-3 text-center">
                                    <span class="badge badge-success p-2 mr-2">Respuestas Positivas</span>
                                    <span class="badge badge-danger p-2">Respuestas de Riesgo</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Detalle de respuestas -->
                <div class="row">
                    <div class="col-12">
                        <h5>Detalle de Respuestas</h5>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th width="5%">#</th>
                                        <th width="55%">Pregunta</th>
                                        <th width="25%">Respuesta</th>
                                        <th width="15%">Nivel</th>
                                    </tr>
                                </thead>
                                <tbody id="detail-answers-table">
                                    <!-- Respuestas se cargarán dinámicamente -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="btn-print-detail">Imprimir</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<?php footer_web('Template.FooterDashboard', $data); ?>