<?php header_web('Template.HeaderDashboard', $data);
$permisos = $data['permisos'];
$permissions = $data["permission"];
$roles = $data['roles'];
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
                        <h4 class="m-0 mb-2 mb-md-0">Registro de Personal Médico</h4>
                    </div>
                    <?php if (isset($permisos[$permissions]["create"]) && $permisos[$permissions]["create"]) : ?>
                        <button type="button" class="btn btn-primary btn-nuevo">
                            <i class="bx bx-plus me-1"></i> Nuevo Personal
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
                    <div class="col-md-3">
                        <label class="form-label">Especialidad:</label>
                        <select
                            class="form-select"
                            id="filtro_especialidad"
                            name="filtro_especialidad"
                            data-url="<?= base_url() ?>admin/personal/search_select">
                            <option value="">Todas</option>
                        </select>
                    </div>
                </div>
                <div class="row mb-4">
                    <?php if (isset($permisos[$permissions]["developer"]) && $permisos[$permissions]["developer"]) : ?>
                        <div class="col-md-3">
                            <label class="form-label">Estado:</label>
                            <select class="form-select" id="filtro_estado" name="filtro_estado">
                                <option value="activos">Activos</option>
                                <option value="eliminados">Eliminados</option>
                                <option value="todos">Todos</option>
                            </select>
                        </div>
                    <?php endif; ?>
                    <div class="col-md-4">
                        <label class="form-label">Búsqueda:</label>
                        <input type="text" class="form-control" id="filtro_search" name="filtro_search"
                            placeholder="Buscar por DNI, nombre, celular o especialidad...">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label d-none d-md-block">&nbsp;</label>
                        <button type="button" class="btn btn-primary w-100" id="btnFiltrar">
                            <i class="bx bx-search me-1"></i> Filtrar
                        </button>
                    </div>
                    <div class="col-md-2">
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
                                <th>Personal Médico</th>
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

<!-- Modal para registro/edición -->
<div class="modal fade" id="mdlPersonal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title title-modal text-white fw-bold"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formPersonal" class="needs-validation" novalidate>
                <div class="modal-body">
                    <h5 class="text-primary fw-semibold">Datos Personales</h5>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">DNI <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="documento" name="documento" required
                                    pattern="[0-9]{8}"
                                    maxlength="8"
                                    placeholder="Ingrese DNI">
                                <button class="btn btn-outline-primary" type="button" id="buscarDocumento">
                                    <i class="bx bx-search"></i>
                                </button>
                            </div>
                            <div class="invalid-feedback">Por favor ingrese un DNI válido de 8 dígitos</div>
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
                                pattern="9[0-9]{8}" placeholder="Número de celular" maxlength="9">
                            <div class="invalid-feedback">Ingrese un número válido que empiece con 9</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Edad <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="edad" name="edad"
                                min="18" max="80" placeholder="Edad" required>
                            <div class="invalid-feedback">Ingrese una edad válida entre 18 y 80 años</div>
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
                            <label class="form-label">Especialidad <span class="text-danger">*</span></label>
                            <select class="form-select select2-ajax" name="especialidad" id="especialidad" required
                                data-url="<?= base_url() ?>admin/personal/search_select">
                                <option value="">Seleccione</option>
                            </select>
                            <div class="invalid-feedback">Seleccione la especialidad</div>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Dirección <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="direccion" name="direccion"
                                placeholder="Dirección completa" required>
                            <div class="invalid-feedback">Por favor ingrese la dirección</div>
                        </div>
                    </div>
                    <hr>
                    <h5 class="text-primary fw-semibold">Datos para la Cuenta de Usuario</h5>
                    <div class="form-check form-switch mb-2">
                        <input class="form-check-input" type="checkbox" id="crear_usuario" name="crear_usuario">
                        <label for="crear_usuario" class="form-check-label">Crear Usuario</label>
                    </div>
                    <div class="row g-3 crear-usuario d-none">
                        <div class="col-12 col-md-6">
                            <label class="form-label">Rol <span class="text-danger fw-bold">*</span></label>
                            <select class="form-select" id="idrol" name="idrol">
                                <option value="">Seleccione una opción...</option>
                                <?php foreach ($roles as $rol) : ?>
                                    <option value="<?= $rol['idrol'] ?>"><?= $rol['rol_nombre'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label">Usuario <span class="text-danger fw-bold">*</span></label>
                            <input type="text" class="form-control" id="usuario" name="usuario" required maxlength="255" placeholder="Nombre de usuario">
                            <div class="invalid-feedback">Por favor ingrese el usuario</div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-cancel" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary btn-save">
                        <i class="bx bx-save"></i> Guardar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para ver personal -->
<div class="modal fade" id="mdlVerPersonal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title">Detalles del Personal Médico</h5>
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
                        <label class="form-label fw-bold">Especialidad:</label>
                        <p id="view_especialidad" class="mb-0"></p>
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
                    <div class="col-md-12">
                        <label class="form-label fw-bold">Dirección:</label>
                        <p id="view_direccion" class="mb-0"></p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bx bx-x me-1"></i> Cerrar
                </button>
                <?php if (isset($permisos[$permissions]["print"]) && $permisos[$permissions]["print"]) : ?>
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
                <?php if (isset($permisos[$permissions]["print"]) && $permisos[$permissions]["print"]) : ?>
                    <button type="button" class="btn btn-primary btn-print-pdf">
                        <i class="bx bx-printer me-1"></i> Imprimir
                    </button>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php footer_web('Template.FooterDashboard', $data); ?>