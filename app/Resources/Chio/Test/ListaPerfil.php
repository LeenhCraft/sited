<?php header_web('Template.Header', $data); ?>
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

<?php footer_web('Template.Footer', $data); ?>