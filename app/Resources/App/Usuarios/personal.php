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
                        <h4 class="m-0 mb-2 mb-md-0">Gestión de Personal</h4>
                    </div>
                    <?php if (isset($permisos[$permission]["create"]) && $permisos[$permission]["create"]) : ?>
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
                            placeholder="Buscar por DNI, nombre, email o celular...">
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
                                <th>Personal</th>
                                <th>Contacto</th>
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
<div class="modal fade" id="mdlPersonal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title text-white fw-bold">Nuevo Personal</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formPersonal" class="needs-validation" novalidate enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">DNI <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="dni" name="dni" required
                                    pattern="[0-9]{8}"
                                    maxlength="8"
                                    placeholder="Ingrese DNI">
                                <button class="btn btn-outline-primary" type="button" id="buscarDNI">
                                    <i class="bx bx-search"></i>
                                </button>
                            </div>
                            <div class="invalid-feedback">Por favor ingrese un DNI válido de 8 dígitos</div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Nombre <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="nombre" name="nombre" required
                                maxlength="200" placeholder="Nombre completo">
                            <div class="invalid-feedback">Por favor ingrese el nombre</div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Celular</label>
                            <input type="tel" class="form-control" id="celular" name="celular"
                                pattern="9[0-9]{8}" maxlength="9" placeholder="Número de celular">
                            <div class="invalid-feedback">Ingrese un número válido que empiece con 9</div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email"
                                maxlength="255" placeholder="Correo electrónico">
                            <div class="invalid-feedback">Ingrese un email válido</div>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Dirección</label>
                            <input type="text" class="form-control" id="direccion" name="direccion"
                                maxlength="200" placeholder="Dirección completa">
                        </div>

                        <div class="col-12">
                            <label class="form-label">Foto</label>
                            <input type="file" class="form-control" id="foto" name="foto"
                                accept="image/*">
                            <div class="form-text">Formatos permitidos: JPG, PNG. Tamaño máximo: 2MB</div>
                        </div>

                        <div id="previewContainer" class="col-12 text-center d-none">
                            <img id="imgPreview" src="" alt="Vista previa" class="img-thumbnail" style="max-height: 200px;">
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
                <h5 class="modal-title">Detalles del Personal</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4 text-center mb-3">
                        <img id="view_foto" src="/img/no-photo.jpg" alt="Foto" class="img-thumbnail" style="max-height: 200px;">
                    </div>
                    <div class="col-md-8">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">DNI:</label>
                                <p id="view_dni" class="mb-0"></p>
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
                                <label class="form-label fw-bold">Email:</label>
                                <p id="view_email" class="mb-0"></p>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-bold">Dirección:</label>
                                <p id="view_direccion" class="mb-0"></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bx bx-x me-1"></i> Cerrar
                </button>
            </div>
        </div>
    </div>
</div>

<?php footer_web('Template.FooterDashboard', $data); ?>