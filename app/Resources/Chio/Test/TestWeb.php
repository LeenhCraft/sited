<?php header_web('Template.Header', $data); ?>
<script>
    const userID = <?php echo $_SESSION['web_id']; ?>;
    const pacienteID = <?php echo $data['user']["id_paciente"]; ?>;
    const userName = '<?php echo $data["user"]["nombre"]; ?>';
    const userEmail = '<?php echo $data["user"]["email"]; ?>';
    const especialidades = <?php echo json_encode($data['especialidades']); ?>;
</script>

<div data-bs-spy="scroll" class="scrollspy-example">
    <section id="profile" class="section-py">
        <div class="container">
            <div class="content-header">
                <div class="container-fluid p-0">
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="text-center">
                                <h1 class="display-4 fw-bold">Evalúa tu Riesgo de Diabetes</h1>
                                <p class="lead text-muted">Este test te ayudará a conocer tu nivel de riesgo en solo unos minutos</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="content">
                <div class="container-fluid p-0">
                    <!-- Flujo del Test -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card shadow-sm border-0 rounded-lg">
                                <div class="card-body py-4">
                                    <ul class="nav nav-pills nav-justified custom-nav-wizard">
                                        <li class="nav-item">
                                            <a class="nav-link active" id="step-datos-tab">
                                                <div class="wizard-step-icon">1</div>
                                                <span>Datos Físicos</span>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link disabled" id="step-test-tab">
                                                <div class="wizard-step-icon">2</div>
                                                <span>Cuestionario</span>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link disabled" id="step-resultados-tab">
                                                <div class="wizard-step-icon">3</div>
                                                <span>Resultados</span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Panel de Datos Antropométricos -->
                    <div class="row" id="panel-datos">
                        <div class="col-12">
                            <div class="card shadow border-0 rounded-lg">
                                <div class="card-header bg-white">
                                    <h3 class="card-title fs-4 m-0">Tus Datos Físicos</h3>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-8 offset-md-2">
                                            <div class="alert alert-info border-0 rounded-lg shadow-sm">
                                                <div class="d-flex">
                                                    <div class="me-3">
                                                        <i class="fas fa-info-circle fa-2x"></i>
                                                    </div>
                                                    <div>
                                                        <h5 class="alert-heading">Datos necesarios</h5>
                                                        <p class="mb-0">Para calcular tu riesgo de diabetes, necesitamos algunos datos básicos. Por favor verifica que sean correctos.</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <form id="form-datos-antropometricos" class="mt-4">
                                                <div class="row g-4">
                                                    <div class="col-md-4">
                                                        <div class="form-floating">
                                                            <input type="number" class="form-control rounded-lg" id="edad" name="edad" step="1" min="0" max="120" value="<?php echo isset($data['user']['edad']) ? $data['user']['edad'] : ''; ?>" required>
                                                            <label for="edad">Edad (años)</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-floating">
                                                            <input type="number" class="form-control rounded-lg" id="peso" name="peso" step="0.01" min="20" max="250" value="<?php echo isset($data['user']['peso']) ? $data['user']['peso'] : ''; ?>" required>
                                                            <label for="peso">Peso (kg)</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-floating">
                                                            <input
                                                                type="number"
                                                                class="form-control rounded-lg"
                                                                id="altura"
                                                                name="altura"
                                                                step="0.1"
                                                                min="0"
                                                                max="300"
                                                                value="<?php echo isset($data['user']['altura']) && $data['user']['altura'] > 3 ? $data['user']['altura'] : (isset($data['user']['altura']) ? $data['user']['altura'] * 100 : ''); ?>" required>
                                                            <label for="altura">Altura (cm)</label>
                                                        </div>
                                                        <small class="form-text text-muted">Ingresa tu altura en centímetros (ejemplo: 170)</small>
                                                    </div>
                                                </div>
                                                <div class="row mt-4">
                                                    <div class="col-12">
                                                        <div class="card bg-light border-0 rounded-lg shadow-sm">
                                                            <div class="card-body text-center p-4">
                                                                <h5 class="mb-3 fw-bold">Tu Índice de Masa Corporal (IMC)</h5>
                                                                <div class="imc-display mb-3">
                                                                    <span id="valor-imc" class="display-4 fw-bold">--</span>
                                                                    <span id="categoria-imc" class="ms-2 fs-5"></span>
                                                                </div>
                                                                <div class="progress rounded-pill" style="height: 24px;">
                                                                    <div class="progress-bar bg-success rounded-start" role="progressbar" style="width: 18%" aria-valuenow="18" aria-valuemin="0" aria-valuemax="100">Bajo</div>
                                                                    <div class="progress-bar bg-info" role="progressbar" style="width: 7%" aria-valuenow="7" aria-valuemin="0" aria-valuemax="100">Normal</div>
                                                                    <div class="progress-bar bg-warning" role="progressbar" style="width: 7%" aria-valuenow="7" aria-valuemin="0" aria-valuemax="100">Sobrepeso</div>
                                                                    <div class="progress-bar bg-danger" role="progressbar" style="width: 10%" aria-valuenow="10" aria-valuemin="0" aria-valuemax="100">Obesidad I</div>
                                                                    <div class="progress-bar bg-danger rounded-end" role="progressbar" style="width: 58%" aria-valuenow="58" aria-valuemin="0" aria-valuemax="100">Obesidad II-III</div>
                                                                </div>
                                                                <div class="d-flex justify-content-between mt-2">
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
                                                <div class="text-center mt-5">
                                                    <button type="submit" id="btn-continuar-test" class="btn btn-primary btn-lg px-5 rounded-pill">
                                                        Continuar al Test <i class="fas fa-arrow-right ms-2"></i>
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
                            <div class="card shadow border-0 rounded-lg">
                                <div class="card-header bg-white">
                                    <h3 class="card-title fs-4 m-0">Cuestionario de Riesgo</h3>
                                </div>
                                <div class="card-body">
                                    <div class="paciente-datos-header mb-4">
                                        <div class="row">
                                            <div class="col-md-8">
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar me-3 bg-light rounded-circle p-2">
                                                        <i class="fas fa-user fa-lg"></i>
                                                    </div>
                                                    <h5 class="m-0"><?php echo isset($data['user']['name']) ? $data['user']['name'] : 'Usuario'; ?></h5>
                                                </div>
                                            </div>
                                            <div class="col-md-4 text-md-end">
                                                <span class="badge bg-primary rounded-pill px-3 py-2">
                                                    <i class="fas fa-calculator me-1"></i> IMC: <span id="test-imc" class="fw-bold"></span>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-4">
                                        <div class="col-12">
                                            <div class="progress rounded-pill" style="height: 30px;">
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

                                    <div class="row mt-5">
                                        <div class="col-12 text-center">
                                            <button type="button" id="btn-volver-datos" class="btn btn-light btn-lg px-4 me-2 rounded-pill">
                                                <i class="fas fa-arrow-left me-2"></i> Volver
                                            </button>
                                            <button type="button" id="btn-finalizar" class="btn btn-primary btn-lg px-5 rounded-pill" disabled>
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
                            <div class="card shadow border-0 rounded-lg">
                                <div class="card-header bg-primary text-white">
                                    <h3 class="card-title fs-4 m-0 text-white">Tus Resultados</h3>
                                </div>
                                <div class="card-body py-4">
                                    <div id="resultado-contenido">
                                        <!-- Aquí se mostrarán los resultados dinámicamente -->
                                        <div class="text-center py-5">
                                            <div class="spinner-border text-primary" role="status">
                                                <span class="visually-hidden">Calculando...</span>
                                            </div>
                                            <p class="mt-2">Calculando tus resultados...</p>
                                        </div>
                                    </div>

                                    <div class="row mt-5">
                                        <div class="col-md-4 text-md-start text-center">
                                            <button type="button" id="btn-imprimir" class="btn btn-secondary btn-lg rounded-pill px-4">
                                                <i class="fas fa-print me-2"></i> Imprimir
                                            </button>
                                        </div>
                                        <div class="col-md-4 text-center mb-3 mb-md-0">
                                            <button type="button" id="btn-agendar-cita" class="btn btn-success btn-lg rounded-pill px-4">
                                                <i class="fas fa-calendar-plus me-2"></i> Agendar Cita
                                            </button>
                                        </div>
                                        <div class="col-md-4 text-md-end text-center mt-3 mt-md-0">
                                            <button type="button" id="btn-reiniciar" class="btn btn-primary btn-lg rounded-pill px-4">
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
            <div class="pregunta-item mb-4 p-4 border-0 rounded-lg shadow-sm">
                <p class="pregunta-titulo fs-5 fw-bold"></p>
                <div class="opciones-container mt-3"></div>
            </div>
        </template>

        <style>
            /* Estilos generales */
            .content-wrapper {
                background-color: #f8f9fa;
            }

            /* Estilos para el flujo del proceso (wizard) */
            .custom-nav-wizard {
                background-color: #fff;
                padding: 10px;
                border-radius: 10px;
            }

            .custom-nav-wizard .nav-link {
                padding: 15px;
                border-radius: 10px;
                text-align: center;
                color: #6c757d;
                position: relative;
                transition: all 0.3s ease;
            }

            .custom-nav-wizard .nav-link.active {
                background-color: #f0f7ff;
                color: #0d6efd;
                font-weight: bold;
            }

            .custom-nav-wizard .nav-link.completed {
                background-color: #e8f5e9;
                color: #28a745;
            }

            .wizard-step-icon {
                width: 40px;
                height: 40px;
                border-radius: 50%;
                background-color: #e9ecef;
                color: #6c757d;
                display: flex;
                align-items: center;
                justify-content: center;
                margin: 0 auto 8px;
                font-weight: bold;
                transition: all 0.3s ease;
            }

            .nav-link.active .wizard-step-icon {
                background-color: #0d6efd;
                color: white;
            }

            .nav-link.completed .wizard-step-icon {
                background-color: #28a745;
                color: white;
            }

            /* Estilos para la visualización del IMC */
            .imc-display {
                padding: 15px;
                border-radius: 10px;
                background-color: white;
                box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
                display: inline-block;
                margin-bottom: 20px;
            }

            /* Estilos para las preguntas */
            .pregunta-item {
                background-color: white;
                transition: all 0.3s ease;
            }

            .pregunta-item:hover {
                transform: translateY(-3px);
                box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1) !important;
            }

            .opcion-btn {
                transition: all 0.2s ease;
                cursor: pointer;
                border-radius: 8px;
                margin-bottom: 10px;
                padding: 15px 20px;
                border: 2px solid #e9ecef;
                background-color: #f8f9fa;
                display: flex;
                align-items: center;
                width: 100%;
                text-align: left;
                font-size: 1rem;
            }

            .opcion-btn:hover {
                background-color: #e9ecef;
                border-color: #ced4da;
            }

            .opcion-btn.selected {
                background-color: #e8f5e9;
                border-color: #28a745;
                color: #155724;
                font-weight: 500;
            }

            .opcion-btn.selected::before {
                content: '\f058';
                font-family: 'Font Awesome 5 Free';
                font-weight: 900;
                margin-right: 10px;
                color: #28a745;
            }

            .pregunta-sin-responder {
                border: 2px solid #f8d7da !important;
                background-color: #fff8f8 !important;
            }

            /* Animaciones */
            .fade-in {
                animation: fadeIn 0.5s;
            }

            @keyframes fadeIn {
                0% {
                    opacity: 0;
                    transform: translateY(20px);
                }

                100% {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            .fade-out {
                animation: fadeOut 0.5s;
            }

            @keyframes fadeOut {
                0% {
                    opacity: 1;
                    transform: translateY(0);
                }

                100% {
                    opacity: 0;
                    transform: translateY(-20px);
                }
            }

            /* Responsive adjustments */
            @media (max-width: 768px) {
                .custom-nav-wizard .nav-link {
                    padding: 10px 5px;
                }

                .wizard-step-icon {
                    width: 30px;
                    height: 30px;
                    font-size: 14px;
                }

                .custom-nav-wizard .nav-link span {
                    font-size: 12px;
                }
            }
        </style>
    </section>
</div>

<div
    class="modal fade"
    id="modalAgendarCita"
    data-bs-backdrop="static" data-bs-keyboard="false"
    tabindex="-1"
    aria-labelledby="modalAgendarCitaLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalAgendarCitaLabel">
                    <i class="fas fa-calendar-plus me-2"></i> Agendar Cita Médica
                </h5>
                <button
                    type="button"
                    class="btn-close btn-close-white"
                    data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="form-agendar-cita">
                    <!-- Mensaje de asignación automática -->
                    <div class="alert alert-info mb-4">
                        <div class="d-flex">
                            <div class="me-3">
                                <i class="bx bx-info-circle bx-md"></i>
                            </div>
                            <div>
                                <h5 class="alert-heading">Asignación de especialista</h5>
                                <p class="mb-0">
                                    Basado en tu evaluación de riesgo, hemos preseleccionado la
                                    especialidad médica más adecuada para tu atención:
                                    <strong id="especialidad-recomendada">Endocrinología</strong>
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Campos ocultos para especialidad y médico -->
                    <input type="hidden" id="especialidad" value="" />
                    <input type="hidden" id="medico" value="" />

                    <!-- Fecha -->
                    <div class="mb-4">
                        <label for="fecha-cita" class="form-label fw-bold">
                            <i class="fas fa-calendar-alt me-1"></i> Fecha
                        </label>
                        <input
                            type="text"
                            class="form-control"
                            id="fecha-cita"
                            placeholder="Selecciona una fecha"
                            readonly
                            required />
                        <div class="form-text">
                            Solo se muestran fechas con disponibilidad
                        </div>
                    </div>

                    <!-- Hora -->
                    <div class="mb-4">
                        <label for="hora-cita" class="form-label fw-bold">
                            <i class="fas fa-clock me-1"></i> Hora
                        </label>
                        <select
                            class="form-select"
                            id="hora-cita"
                            disabled
                            required>
                            <option value="">Primero selecciona una fecha</option>
                        </select>
                        <div class="form-text">
                            Horarios disponibles para la fecha seleccionada
                        </div>
                    </div>

                    <!-- Observaciones -->
                    <div class="mb-3">
                        <label for="observaciones" class="form-label fw-bold">
                            <i class="fas fa-comment-medical me-1"></i> Observaciones
                            (opcional)
                        </label>
                        <textarea
                            class="form-control"
                            id="observaciones"
                            rows="3"
                            placeholder="Indica cualquier información adicional relevante para tu cita (síntomas, medicación actual, alergias, etc.)"></textarea>
                    </div>

                    <!-- Resumen de la cita -->
                    <div
                        class="card bg-light border-0 shadow-sm mt-4 d-none"
                        id="resumen-cita">
                        <div class="card-body">
                            <h5 class="card-title mb-3">
                                <i class="fas fa-clipboard-check me-2"></i> Resumen de tu cita
                            </h5>
                            <ul class="list-group list-group-flush">
                                <li
                                    class="list-group-item bg-transparent d-flex justify-content-between">
                                    <span class="fw-bold">Paciente:</span>
                                    <span id="resumen-paciente">-</span>
                                </li>
                                <li
                                    class="list-group-item bg-transparent d-flex justify-content-between">
                                    <span class="fw-bold">Especialidad:</span>
                                    <span id="resumen-especialidad">-</span>
                                </li>
                                <li
                                    class="list-group-item bg-transparent d-flex justify-content-between">
                                    <span class="fw-bold">Médico:</span>
                                    <span id="resumen-medico">-</span>
                                </li>
                                <li
                                    class="list-group-item bg-transparent d-flex justify-content-between">
                                    <span class="fw-bold">Fecha y hora:</span>
                                    <span id="resumen-fecha-hora">-</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i> Cancelar
                </button>
                <button
                    type="button"
                    class="btn btn-primary"
                    id="btn-confirmar-cita"
                    disabled>
                    <i class="fas fa-check me-1"></i> Confirmar Cita
                </button>
            </div>
        </div>
    </div>
</div>
<?php footer_web('Template.Footer', $data); ?>