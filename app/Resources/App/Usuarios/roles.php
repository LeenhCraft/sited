<?php header_web('Template.HeaderDashboard', $data);
$permisos = $data['permisos'];
$permission = $data['permission'];
?>
<script>
    const permisos = <?php echo json_encode($permisos); ?>
</script>
<div class="row">
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-header">
                <div class="d-md-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="m-0 mb-2 mb-md-0">Gestión de Roles</h4>
                    </div>
                    <?php if (isset($permisos[$permission]["create"]) && $permisos[$permission]["create"]) : ?>
                        <button type="button" class="btn btn-primary btn-nuevo">
                            <i class="bx bx-plus me-1"></i> Nuevo Rol
                        </button>
                    <?php endif; ?>
                </div>
            </div>

            <div class="card-body">
                <!-- Filtros -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <label class="form-label">Estado:</label>
                        <select class="form-select" id="filtro_estado" name="filtro_estado">
                            <option value="1">Activos</option>
                            <option value="0">Inactivos</option>
                            <option value="">Todos</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Búsqueda:</label>
                        <input type="text" class="form-control" id="filtro_search" name="filtro_search"
                            placeholder="Buscar por código, nombre o descripción...">
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
                                <th>Rol</th>
                                <th>Descripción</th>
                                <th>Estado</th>
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
<div class="modal fade" id="mdlRol" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Nuevo Rol</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formRol" class="needs-validation" novalidate>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Código <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="codigo" name="codigo" required
                                maxlength="10" placeholder="Código del rol">
                            <div class="invalid-feedback">Por favor ingrese el código</div>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Nombre <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="nombre" name="nombre" required
                                maxlength="255" placeholder="Nombre del rol">
                            <div class="invalid-feedback">Por favor ingrese el nombre</div>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Descripción</label>
                            <textarea class="form-control" id="descripcion" name="descripcion" rows="3"
                                maxlength="255" placeholder="Descripción del rol"></textarea>
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

<?php footer_web('Template.FooterDashboard', $data); ?>