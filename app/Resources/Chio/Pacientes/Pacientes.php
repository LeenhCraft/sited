<?php header_web('Template.HeaderDashboard', $data);
$permisos = $data['permisos'];
?>
<script>
    const permisos = <?php echo json_encode($permisos); ?>
</script>
<style>
    .dataTables_wrapper {
        padding: 0;
    }
</style>
<div class="row">
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-header">
                <div class="d-md-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="m-0 mb-2 mb-md-0">Registro de Pacientes</h4>
                    </div>
                    <?php if (isset($permisos["ruta.paciente"]["create"]) && $permisos["ruta.paciente"]["create"]) : ?>
                        <button type="button" class="btn btn-primary btn-nuevo">
                            <i class="bx bx-plus me-1"></i> Nuevo Paciente
                        </button>
                    <?php endif; ?>
                </div>
            </div>

            <div class="card-body">
                <!-- Filtros -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <label class="form-label">Fecha Inicio:</label>
                        <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Fecha Fin:</label>
                        <input type="date" class="form-control" id="fecha_fin" name="fecha_fin">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Sexo:</label>
                        <select class="form-select" id="filtro_sexo" name="filtro_sexo">
                            <option value="">Todos</option>
                            <option value="M">Masculino</option>
                            <option value="F">Femenino</option>
                        </select>
                    </div>
                    <?php if (isset($permisos["ruta.paciente"]["developer"]) && $permisos["ruta.paciente"]["developer"]) : ?>
                        <div class="col-md-3">
                            <label class="form-label">Estado:</label>
                            <select class="form-select" id="filtro_estado" name="filtro_estado">
                                <option value="activos">Activos</option>
                                <option value="eliminados">Eliminados</option>
                                <option value="todos">Todos</option>
                            </select>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="row mb-4">
                    <div class="col-md-4 mb-2 mb-md-0">
                        <label class="form-label">Búsqueda:</label>
                        <input type="text" class="form-control" id="filtro_search" name="filtro_search"
                            placeholder="Buscar por DNI, nombre o celular...">
                    </div>
                    <div class="col-md-2 mb-2 mb-md-0">
                        <label class="form-label d-none d-md-block">&nbsp;</label>
                        <button type="button" class="btn btn-primary w-100" id="btnFiltrar">
                            <i class="bx bx-search me-1"></i> Filtrar
                        </button>
                    </div>
                    <div class="col-md-2 mb-2 mb-md-0">
                        <label class="form-label d-none d-md-block">&nbsp;</label>
                        <button type="button" class="btn btn-secondary w-100" id="btnLimpiar">
                            <i class="bx bx-reset me-1"></i> Limpiar
                        </button>
                    </div>
                </div>

                <!-- Tabla -->
                <div class="table-responsive">
                    <table id="tbl" class="table table-hover table-striped" style="width:100%">
                        <thead>
                            <tr>
                                <th>Paciente</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- modal para nuevos pacientes -->
<div class="modal fade" id="mdlPaciente" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title title-modal"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formPacientes" class="needs-validation" novalidate>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">DNI <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="documento" name="documento" required
                                    pattern="([0-9]{8}|[0-9]{11}|[A-Za-z0-9]{6,12})"
                                    maxlength="12"
                                    placeholder="Ingrese DNI o RUC">
                                <button class="btn btn-outline-primary" type="button" id="buscarDocumento">
                                    <i class="bx bx-search"></i>
                                </button>
                            </div>
                            <div class="invalid-feedback">Por favor ingrese un documento válido</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Nombre <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="nombre" name="nombre" required
                                placeholder="Nombre completo">
                            <div class="invalid-feedback">Por favor ingrese el nombre</div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Celular <span class="text-danger">*</span></label>
                            <input type="tel" class="form-control" id="celular" name="celular" required
                                pattern="[0-9]{9}" placeholder="Número de celular">
                            <div class="invalid-feedback">Ingrese un número válido de 9 dígitos</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Edad <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="edad" name="edad"
                                min="0" max="120" placeholder="Edad" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Sexo <span class="text-danger">*</span></label>
                            <select class="form-select" name="sexo" id="sexo" required>
                                <option value="">Seleccione</option>
                                <option value="M">Masculino</option>
                                <option value="F">Femenino</option>
                            </select>
                            <div class="invalid-feedback">Seleccione el sexo</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Peso (kg) <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="peso" name="peso" required
                                step="0.1" min="0" max="300" placeholder="Peso en kilogramos">
                            <div class="invalid-feedback">Ingrese un peso válido</div>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Altura (cm) <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="altura" name="altura"
                                step="0.1" min="0" max="300" placeholder="Altura en centímetros" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bx bx-save"></i> Guardar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para ver paciente -->
<div class="modal fade" id="mdlVerPaciente" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title">Detalles del Paciente</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold">DNI:</label>
                        <p id="view_documento" class="mb-0"></p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Nombre:</label>
                        <p id="view_nombre" class="mb-0"></p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Celular:</label>
                        <p id="view_celular" class="mb-0"></p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Edad:</label>
                        <p id="view_edad" class="mb-0"></p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Sexo:</label>
                        <p id="view_sexo" class="mb-0"></p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Peso:</label>
                        <p id="view_peso" class="mb-0"></p>
                    </div>
                    <div class="col-md-12">
                        <label class="form-label fw-bold">Altura:</label>
                        <p id="view_altura" class="mb-0"></p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bx bx-x me-1"></i> Cerrar
                </button>
                <?php if (isset($permisos["ruta.paciente"]["print"]) && $permisos["ruta.paciente"]["print"]) : ?>
                    <button type="button" class="btn btn-primary btn-print">
                        <i class="bx bx-printer me-1"></i> Imprimir
                    </button>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Modal para ver PDF -->
<div class="modal fade" id="mdlPDF" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title text-white fw-bold">Vista Previa de Impresión</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0">
                <iframe id="pdfViewer" style="width: 100%; height: 80vh; border: none;"></iframe>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bx bx-x me-1"></i> Cerrar
                </button>
                <?php if (isset($permisos["ruta.paciente"]["print"]) && $permisos["ruta.paciente"]["print"]) : ?>
                    <button type="button" class="btn btn-primary btn-print-pdf">
                        <i class="bx bx-printer me-1"></i> Imprimir
                    </button>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php footer_web('Template.FooterDashboard', $data); ?>