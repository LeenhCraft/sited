<?php header_web('Template.HeaderDashboard', $data);
$permisos = $data['permisos'];
$permissions = $data["permission"];
?>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid p-0">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Test de Diabetes</h1>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid p-0">
            <!-- Flujo del Test -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <h3 class="card-title text-white">Proceso de Evaluación</h3>
                        </div>
                        <div class="card-body py-4">
                            <div class="d-flex justify-content-between flex-wrap">
                                <div class="step-item active" id="step-paciente">
                                    <div class="step-circle">1</div>
                                    <div class="step-text">Seleccionar Paciente</div>
                                </div>
                                <div class="step-connector"></div>
                                <div class="step-item" id="step-datos">
                                    <div class="step-circle">2</div>
                                    <div class="step-text">Datos Antropométricos</div>
                                </div>
                                <div class="step-connector"></div>
                                <div class="step-item" id="step-test">
                                    <div class="step-circle">3</div>
                                    <div class="step-text">Realizar Test</div>
                                </div>
                                <div class="step-connector"></div>
                                <div class="step-item" id="step-resultados">
                                    <div class="step-circle">4</div>
                                    <div class="step-text">Resultados</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Panel de Selección de Paciente -->
            <div class="row" id="panel-paciente">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Seleccionar Paciente</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-8 offset-md-2">
                                    <div class="form-group">
                                        <label for="paciente-select">Buscar paciente:</label>
                                        <select id="paciente-select" class="form-control select2" style="width: 100%;">
                                            <option value="">Escriba el nombre o DNI del paciente</option>
                                        </select>
                                    </div>
                                    <div class="info-paciente mt-4 d-none" id="info-paciente-container">
                                        <div class="card bg-light">
                                            <div class="card-body">
                                                <h5 class="card-title">Información del Paciente</h5>
                                                <hr>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <p><strong>Nombre:</strong> <span id="info-nombre"></span></p>
                                                        <p><strong>DNI:</strong> <span id="info-dni"></span></p>
                                                        <p><strong>Edad:</strong> <span id="info-edad"></span> años</p>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <p><strong>Sexo:</strong> <span id="info-sexo"></span></p>
                                                        <p><strong>Teléfono:</strong> <span id="info-telefono"></span></p>
                                                        <p><strong>Último registro:</strong> <span id="info-ultimo-registro"></span></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-center mt-4">
                                        <a href="/admin/pacientes" class="btn btn-outline-secondary mr-2">
                                            <i class="fas fa-user-plus me-2"></i> Registrar Nuevo Paciente
                                        </a>
                                        <button type="button" id="btn-continuar-datos" class="btn btn-primary" disabled>
                                            Continuar <i class="fas fa-arrow-right ms-2"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Panel de Datos Antropométricos -->
            <div class="row d-none" id="panel-datos">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Datos Antropométricos</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-8 offset-md-2">
                                    <div class="paciente-resumen mb-4">
                                        <h5>Paciente: <span id="datos-nombre-paciente"></span></h5>
                                    </div>
                                    <form id="form-datos-antropometricos">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="edad">Edad :</label>
                                                    <input type="number" class="form-control" id="edad" step="1" min="0" max="250" required>
                                                    <small class="form-text text-muted">Última edad registrado: <span id="ultima-edad">No disponible</span></small>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="peso">Peso (kg):</label>
                                                    <input type="number" class="form-control" id="peso" step="0.01" min="20" max="250" required>
                                                    <small class="form-text text-muted">Último peso registrado: <span id="ultimo-peso">No disponible</span></small>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="altura">Altura (cm):</label>
                                                    <input type="number" class="form-control" id="altura" step="0.1" min="0" max="300" required>
                                                    <small class="form-text text-muted">Última altura registrada: <span id="ultima-altura">No disponible</span></small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mt-3">
                                            <div class="col-12">
                                                <div class="card bg-light">
                                                    <div class="card-body text-center">
                                                        <h5 class="mb-3">Índice de Masa Corporal (IMC)</h5>
                                                        <div class="d-flex justify-content-center mb-2">
                                                            <h2 id="valor-imc">--</h2>
                                                            <span class="align-self-end ml-2 mb-2" id="categoria-imc"></span>
                                                        </div>
                                                        <div class="progress" style="height: 20px;">
                                                            <div class="progress-bar bg-success" role="progressbar" style="width: 18%" aria-valuenow="18" aria-valuemin="0" aria-valuemax="100">Bajo peso</div>
                                                            <div class="progress-bar bg-info" role="progressbar" style="width: 7%" aria-valuenow="7" aria-valuemin="0" aria-valuemax="100">Normal</div>
                                                            <div class="progress-bar bg-warning" role="progressbar" style="width: 7%" aria-valuenow="7" aria-valuemin="0" aria-valuemax="100">Sobrepeso</div>
                                                            <div class="progress-bar bg-danger" role="progressbar" style="width: 10%" aria-valuenow="10" aria-valuemin="0" aria-valuemax="100">Obesidad I</div>
                                                            <div class="progress-bar bg-danger" role="progressbar" style="width: 58%" aria-valuenow="58" aria-valuemin="0" aria-valuemax="100">Obesidad II-III</div>
                                                        </div>
                                                        <div class="d-flex justify-content-between mt-1">
                                                            <small>16</small>
                                                            <small>18.5</small>
                                                            <small>25</small>
                                                            <small>30</small>
                                                            <small>35</small>
                                                            <small>40+</small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="text-center mt-4">
                                            <button type="button" id="btn-volver-paciente" class="btn btn-outline-secondary mr-2">
                                                <i class="fas fa-arrow-left me-2"></i> Volver
                                            </button>
                                            <button type="submit" id="btn-continuar-test" class="btn btn-primary">
                                                Continuar <i class="fas fa-arrow-right ms-2"></i>
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Panel del Test -->
            <div class="row d-none" id="panel-test">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Evaluación de riesgo de diabetes</h3>
                        </div>
                        <div class="card-body">
                            <div class="paciente-datos-header mb-4">
                                <div class="row">
                                    <div class="col-md-8">
                                        <h5>Paciente: <span id="test-nombre-paciente"></span></h5>
                                    </div>
                                    <div class="col-md-4 text-right">
                                        <span class="badge badge-pill badge-primary p-2">IMC: <span id="test-imc"></span></span>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-12">
                                    <div class="progress" style="height: 30px;">
                                        <div id="progress-bar" class="progress-bar progress-bar-striped progress-bar-animated bg-success" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div>
                                    </div>
                                </div>
                            </div>

                            <div id="test-container">
                                <!-- Aquí se cargarán dinámicamente todas las preguntas -->
                                <div class="text-center py-5">
                                    <div class="spinner-border text-primary" role="status">
                                        <span class="visually-hidden">Cargando...</span>
                                    </div>
                                    <p class="mt-2">Cargando preguntas...</p>
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-12 text-center">
                                    <button type="button" id="btn-volver-datos" class="btn btn-outline-secondary mr-3">
                                        <i class="fas fa-arrow-left me-2"></i> Volver
                                    </button>
                                    <button type="button" id="btn-finalizar" class="btn btn-primary" disabled>
                                        Finalizar Test <i class="fas fa-check ms-2"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Panel de Resultados -->
            <div class="row d-none" id="panel-resultados">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <h3 class="card-title text-white">Resultados del Test</h3>
                        </div>
                        <div class="card-body py-4">
                            <div id="resultado-contenido">
                                <!-- Aquí se mostrarán los resultados dinámicamente -->
                                <div class="text-center py-5">
                                    <div class="spinner-border text-primary" role="status">
                                        <span class="visually-hidden">Calculando...</span>
                                    </div>
                                    <p class="mt-2">Calculando resultados...</p>
                                </div>
                            </div>

                            <div class="row mt-5">
                                <div class="col-md-6 text-md-left text-center">
                                    <button type="button" id="btn-imprimir" data-id="" class="btn btn-secondary">
                                        <i class="fas fa-print me-2"></i> Imprimir resultados
                                    </button>
                                </div>
                                <div class="col-md-6 text-md-right text-center mt-3 mt-md-0">
                                    <button type="button" id="btn-reiniciar" class="btn btn-primary">
                                        <i class="fas fa-redo me-2"></i> Realizar nuevo test
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Template para contenedor de preguntas -->
<template id="template-contenedor">
    <div class="preguntas-container fade-in">
        <!-- Aquí se insertan todas las preguntas -->
    </div>
</template>

<!-- Template para pregunta individual -->
<template id="template-pregunta">
    <div class="pregunta-item mb-4 p-3 border rounded bg-light">
        <p class="pregunta-titulo font-weight-bold"></p>
        <div class="opciones-container mt-3"></div>
    </div>
</template>

<style>
    /* Estilos para el flujo del proceso */
    .step-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        width: 20%;
        position: relative;
    }

    .step-circle {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background-color: #f8f9fa;
        border: 2px solid #ced4da;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        margin-bottom: 10px;
        transition: all 0.3s;
    }

    .step-text {
        text-align: center;
        font-size: 14px;
        color: #6c757d;
        transition: all 0.3s;
    }

    .step-item.active .step-circle {
        background-color: #007bff;
        border-color: #007bff;
        color: white;
    }

    .step-item.active .step-text {
        color: #007bff;
        font-weight: bold;
    }

    .step-item.completed .step-circle {
        background-color: #28a745;
        border-color: #28a745;
        color: white;
    }

    .step-connector {
        flex-grow: 1;
        height: 2px;
        background-color: #ced4da;
        margin-top: 25px;
        max-width: 10%;
    }

    /* Estilos para las preguntas */
    .pregunta-item {
        transition: all 0.3s ease;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .pregunta-item:hover {
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        transform: translateY(-2px);
    }

    .opcion-btn {
        transition: all 0.2s ease;
        cursor: pointer;
        border-radius: 4px;
        margin-bottom: 8px;
        padding: 10px 15px;
        border: 2px solid #ddd;
        background-color: #f8f9fa;
        display: block;
        width: 100%;
        text-align: left;
    }

    .opcion-btn:hover {
        background-color: #e9ecef;
        border-color: #adb5bd;
    }

    .opcion-btn.selected {
        background-color: #d4edda;
        border-color: #28a745;
        color: #155724;
    }

    .pregunta-sin-responder {
        border: 2px solid #dc3545 !important;
        background-color: #fff8f8 !important;
    }

    /* Animaciones */
    .fade-in {
        animation: fadeIn 0.5s;
    }

    @keyframes fadeIn {
        0% {
            opacity: 0;
        }

        100% {
            opacity: 1;
        }
    }

    .fade-out {
        animation: fadeOut 0.5s;
    }

    @keyframes fadeOut {
        0% {
            opacity: 1;
        }

        100% {
            opacity: 0;
        }
    }
</style>

<?php footer_web('Template.FooterDashboard', $data); ?>