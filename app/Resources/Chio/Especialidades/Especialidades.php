<?php header_web('Template.HeaderDashboard', $data);
$permisos = $data['permisos'];
$permissions = $data["permission"];
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
                        <h4 class="m-0 mb-2 mb-md-0">Gestión de Especialidades</h4>
                    </div>
                    <?php if (isset($permisos[$permissions]["create"]) && $permisos[$permissions]["create"]) : ?>
                        <button type="button" class="btn btn-primary btn-nuevo">
                            <i class="bx bx-plus me-1"></i> Nueva Especialidad
                        </button>
                    <?php endif; ?>
                </div>
            </div>

            <div class="card-body">
                <div class="row mb-4">
                    <?php if (isset($permisos[$permissions]["developer"]) && $permisos[$permissions]["developer"]) : ?>
                        <div class="col-md-3">
                            <label class="form-label">Estado:</label>
                            <select class="form-select" id="filtro_estado" name="filtro_estado">
                                <option value="0">Activos</option>
                                <option value="1">Eliminados</option>
                                <option value="">Todos</option>
                            </select>
                        </div>
                    <?php endif; ?>
                    <div class="col-md-4">
                        <label class="form-label">Búsqueda:</label>
                        <input type="text" class="form-control" id="filtro_search" name="filtro_search"
                            placeholder="Buscar por nombre o descripción...">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label d-none d-md-block">&nbsp;</label>
                        <button type="button" class="btn btn-primary w-100" id="btnFiltrar">
                            <i class="bx bx-search me-1"></i> Filtrar
                        </button>
                    </div>
                </div>

                <div class="table-responsive">
                    <table id="tbl" class="table table-hover table-striped">
                        <thead>
                            <tr>
                                <th>Especialidad</th>
                                <th width="150px" class="text-center">Acciones</th>
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
<div class="modal fade" id="mdlEspecialidad" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title text-white fw-bold"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formEspecialidad" class="needs-validation" novalidate>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Nombre <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="nombre" name="nombre" required>
                            <div class="invalid-feedback">Por favor ingrese el nombre</div>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Descripción</label>
                            <textarea class="form-control" id="descripcion" name="descripcion" rows="3"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary btn-save">
                        <i class="bx bx-save me-1"></i> Guardar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php footer_web('Template.FooterDashboard', $data); ?>