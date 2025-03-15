<?php header_web('Template.HeaderDashboard', $data);
$permisos = $data['permisos'];
$permissions = $data['permission'];
?>
<style>
    .dataTables_wrapper {
        padding: 0;
    }
</style>
<div class="page-content-wrapper py-3">
    <div class="container-fluid p-0">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-6">
                        <h5>Gestión de Horarios Médicos</h5>
                    </div>
                    <div class="col-md-6 text-end">
                        <?php if (isset($permisos[$permissions]["create"]) && $permisos[$permissions]["create"]) : ?>
                            <button type="button" class="btn btn-primary" id="btnNuevoHorario">
                                <i class="fa fa-plus me-2"></i> Nuevo Horario
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Estado</label>
                            <select class="form-control" id="filtroEstado">
                                <option value="0">Activos</option>
                                <option value="1">Eliminados</option>
                                <option value="">Todos</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Búsqueda</label>
                            <input type="text" class="form-control" id="filtroSearch" placeholder="Buscar por médico o especialidad...">
                        </div>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="button" class="btn btn-outline-primary w-100" id="btnFiltrar">
                            <i class="fa fa-search me-2"></i> Filtrar
                        </button>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered table-striped dt-responsive nowrap w-100" id="tablaHorarios">
                        <thead>
                            <tr>
                                <th>Médico</th>
                                <th>Especialidad</th>
                                <th>Horario</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para crear/editar horario -->
<div class="modal fade" id="modalHorario" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Nuevo Horario</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formHorario">
                    <input type="hidden" id="idHorario" value="">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Médico <span class="text-danger">*</span></label>
                                <select class="form-control" id="idMedico" name="idMedico" style="width: 100%"></select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Especialidad</label>
                                <div class="form-control-plaintext text-primary" id="especialidadLabel">-</div>
                                <input type="hidden" id="idEspecialidad" name="idEspecialidad">
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-12">
                            <label>Días de atención <span class="text-danger">*</span></label>
                            <div class="dias-semana mt-2">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="dias[]" id="dia_1" value="1">
                                    <label class="form-check-label" for="dia_1">Lunes</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="dias[]" id="dia_2" value="2">
                                    <label class="form-check-label" for="dia_2">Martes</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="dias[]" id="dia_3" value="3">
                                    <label class="form-check-label" for="dia_3">Miércoles</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="dias[]" id="dia_4" value="4">
                                    <label class="form-check-label" for="dia_4">Jueves</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="dias[]" id="dia_5" value="5">
                                    <label class="form-check-label" for="dia_5">Viernes</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="dias[]" id="dia_6" value="6">
                                    <label class="form-check-label" for="dia_6">Sábado</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="dias[]" id="dia_0" value="0">
                                    <label class="form-check-label" for="dia_0">Domingo</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Hora de inicio <span class="text-danger">*</span></label>
                                <input type="time" class="form-control" id="horaInicio" name="horaInicio">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Hora de fin <span class="text-danger">*</span></label>
                                <input type="time" class="form-control" id="horaFin" name="horaFin">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="btnGuardarHorario">Guardar</button>
            </div>
        </div>
    </div>
</div>

<?php footer_web('Template.FooterDashboard', $data); ?>