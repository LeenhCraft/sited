<?php header_web('Template.HeaderDashboard', $data);
$permisos = $data['permisos'];
$roles = $data['roles'];
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
                        <h4 class="m-0 mb-2 mb-md-0">Gestión de Usuarios</h4>
                    </div>
                    <?php if (isset($permisos[$permission]["create"]) && $permisos[$permission]["create"]) : ?>
                        <button type="button" class="btn btn-primary btn-nuevo">
                            <i class="bx bx-plus me-1"></i> Nuevo Usuario
                        </button>
                    <?php endif; ?>
                </div>
            </div>

            <div class="card-body">
                <!-- Filtros -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <label class="form-label">Rol:</label>
                        <select class="form-select" id="filtro_rol" name="filtro_rol">
                            <option value="">Todos</option>
                            <?php foreach ($roles as $rol) : ?>
                                <option value="<?= $rol['idrol'] ?>"><?= $rol['rol_nombre'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Estado:</label>
                        <select class="form-select" id="filtro_estado" name="filtro_estado">
                            <option value="1">Activos</option>
                            <option value="0">Inactivos</option>
                            <option value="">Todos</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Búsqueda:</label>
                        <input type="text" class="form-control" id="filtro_search" name="filtro_search"
                            placeholder="Usuario, nombre o DNI...">
                    </div>
                    <div class="col-md-3 d-flex">
                        <div class="align-self-end w-100">
                            <button type="button" class="btn btn-primary w-100 mb-2" id="btnFiltrar">
                                <i class="bx bx-search me-1"></i> Filtrar
                            </button>
                            <button type="button" class="btn btn-secondary w-100" id="btnLimpiar">
                                <i class="bx bx-reset me-1"></i> Limpiar
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Tabla -->
                <div class="table-responsive">
                    <table id="tbl" class="table table-hover table-striped" style="width:100%">
                        <thead>
                            <tr>
                                <th>Usuario</th>
                                <th>Personal</th>
                                <th>Rol</th>
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
<div class="modal fade" id="mdlUsuario" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Nuevo Usuario</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formUsuario" class="needs-validation" novalidate>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-12 select-personal">
                            <label class="form-label">Personal <span class="text-danger">*</span></label>
                            <select class="form-select" id="idpersona" name="idpersona" required>
                                <option value="">Seleccione personal</option>
                            </select>
                            <div class="invalid-feedback">Por favor seleccione el personal</div>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Rol <span class="text-danger">*</span></label>
                            <select class="form-select" id="idrol" name="idrol" required>
                                <option value="">Seleccione rol</option>
                                <?php foreach ($roles as $rol) : ?>
                                    <option value="<?= $rol['idrol'] ?>"><?= $rol['rol_nombre'] ?></option>
                                <?php endforeach; ?>
                            </select>
                            <div class="invalid-feedback">Por favor seleccione el rol</div>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Usuario <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="usuario" name="usuario" required
                                maxlength="255" placeholder="Nombre de usuario">
                            <div class="invalid-feedback">Por favor ingrese el usuario</div>
                        </div>

                        <div class="col-12 edit-options d-none">
                            <div class="form-check form-switch mb-2">
                                <input class="form-check-input" type="checkbox" id="activo" name="activo" checked>
                                <label class="form-check-label">Usuario Activo</label>
                            </div>
                            <div class="form-check form-switch mb-2">
                                <input class="form-check-input" type="checkbox" id="twoauth" name="twoauth">
                                <label class="form-check-label">Autenticación de dos factores</label>
                            </div>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="reset_password" name="reset_password">
                                <label class="form-check-label">Resetear contraseña</label>
                            </div>
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

<!-- Modal para mostrar contraseña -->
<div class="modal fade" id="mdlPassword" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Contraseña Temporal</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <p class="mb-2">La contraseña temporal es:</p>
                <h3 class="text-primary mb-3" id="tempPassword"></h3>
                <p class="text-muted small">Por favor, copie esta contraseña y compártala de forma segura con el usuario.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" id="btnCopyPassword">
                    <i class="bx bx-copy"></i> Copiar
                </button>
            </div>
        </div>
    </div>
</div>

<?php footer_web('Template.FooterDashboard', $data); ?>