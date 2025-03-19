<?php header_web('Template.Header', $data); ?>
<script>
    const userID = <?php echo $_SESSION['web_id']; ?>;
    const pacienteID = <?php echo $data['user']["id_paciente"]; ?>;
    const userName = '<?php echo $data["user"]["nombre"]; ?>';
    const userEmail = '<?php echo $data["user"]["email"]; ?>';
</script>
<div data-bs-spy="scroll" class="scrollspy-example">
    <section id="profile" class="section-py">
        <div class="container-xxl flex-grow-1 container-p-y">
            <h4 class="fw-bold py-3 mb-4">
                <span class="text-muted fw-light">Mi Perfil /</span> Mis Tests
            </h4>

            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Historial de Tests Realizados</h5>
                    <!-- <div>
                        <button type="button" class="btn btn-outline-primary" id="exportAllBtn">
                            <i class="icon-base bx bx-export"></i> Exportar Todos
                        </button>
                    </div> -->
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover" id="testsTable">
                            <thead>
                                <tr>
                                    <th>Fecha</th>
                                    <th>Tendecia</th>
                                    <th>Puntaje</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (isset($data['tests']) && count($data['tests']) > 0) { ?>
                                    <?php foreach ($data['tests'] as $test) { ?>
                                        <tr>
                                            <td><?= date('d/m/Y H:i', strtotime($test['fecha_hora'])) ?></td>
                                            <td>
                                                <?php
                                                $text = $test['tendencia_label'];
                                                $color = 'success';

                                                if ($test["tendencia_label"] === "Alto") {
                                                    $color = 'danger';
                                                } else if ($test["tendencia_label"] === "Moderado") {
                                                    $color = 'warning';
                                                }

                                                ?>
                                                <span class="badge bg-label-<?= $color ?>"><?= $text ?></span>
                                            </td>
                                            <td>
                                                <?php if ($test['tendencia_modelo'] !== null): ?>
                                                    <div class="d-flex align-items-center">
                                                        <div class="progress w-100 me-2" style="height: 8px;">
                                                            <div class="progress-bar bg-primary"
                                                                role="progressbar"
                                                                style="width: <?= $test['tendencia_modelo'] ?>%"
                                                                aria-valuenow="<?= $test['tendencia_modelo'] ?>"
                                                                aria-valuemin="0"
                                                                aria-valuemax="100">
                                                            </div>
                                                        </div>
                                                        <span class="fw-bold text-black"><?= round($test['tendencia_modelo'], 2) ?>%</span>
                                                    </div>
                                                <?php else: ?>
                                                    <span class="badge bg-label-secondary">No evaluado</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <div class="dropdown">
                                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                                        <i class="icon-base bx bx-dots-vertical-rounded"></i>
                                                    </button>
                                                    <div class="dropdown-menu">
                                                        <a class="dropdown-item view-test" href="/sited/test/detalle/<?= $test['idtest'] ?>" data-id="<?= $test['idtest'] ?>">
                                                            <i class="icon-base bx bx-show me-1"></i> Ver detalles
                                                        </a>
                                                        <!-- <a class="dropdown-item export-test" href="javascript:void(0);" data-id="<?= $test['idtest'] ?>">
                                                            <i class="icon-base bx bx-export me-1"></i> Exportar
                                                        </a> -->
                                                        <a class="dropdown-item print-test" href="javascript:void(0);" data-id="<?= $test['idtest'] ?>">
                                                            <i class="icon-base bx bx-printer me-1"></i> Imprimir
                                                        </a>
                                                        <a class="dropdown-item agendar-cita" href="javascript:void(0);" data-id="<?= $test['idtest'] ?>">
                                                            <i class="icon-base bx bx-time me-1"></i> Agendar Cita
                                                        </a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                <?php } else { ?>
                                    <tr>
                                        <td colspan="5" class="text-center py-4">
                                            <div class="d-flex flex-column align-items-center">
                                                <i class="icon-base bx bx-clipboard text-primary mb-2" style="font-size: 3rem;"></i>
                                                <h6>No has realizado ningún test aún</h6>
                                                <p class="mb-3">Realiza tu primer test para ver los resultados aquí</p>
                                                <a href="/sited/test" class="btn btn-primary">Realizar mi primer Test</a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
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