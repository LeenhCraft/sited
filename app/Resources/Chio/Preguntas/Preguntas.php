<?php header_web('Template.HeaderDashboard', $data);
$permisos = $data['permisos'];
$permissions = $data["permission"];
?>
<div class="container">
    <button class="btn btn-outline-primary" id="btn-nueva-pregunta">
        <i class="bx bx-plus bx-sm me-2"></i> Nueva Pregunta
    </button>
    <hr class="my-3">
    <h5 class="text-primary fw-semibold">Lista de preguntas registradas</h5>
    <div id="preguntas-container" class="row"></div>
</div>

<!-- Modal para nueva/editar pregunta -->
<div class="modal fade" id="modal-nueva-pregunta" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title" id="modal-nueva-pregunta-label">
                    <i class="bx bx-plus-circle me-1"></i>Nueva Pregunta
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="form-nueva-pregunta">
                    <div class="row">
                        <!-- Campo: Título de la pregunta -->
                        <div class="col-md-8 mb-3">
                            <label class="form-label fw-semibold">Título</label>
                            <input type="text" name="titulo" class="form-control"
                                placeholder="Ingrese un título descriptivo" required>
                            <div class="form-text">El título debe ser claro y conciso</div>
                        </div>

                        <!-- Campo: Orden de la pregunta -->
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-semibold">Orden</label>
                            <input type="number" name="orden" class="form-control" min="1"
                                placeholder="Posición" value="1">
                            <div class="form-text">Orden de aparición</div>
                        </div>

                        <!-- Campo: Contenido de la pregunta -->
                        <div class="col-12 mb-3">
                            <label class="form-label fw-semibold">Pregunta</label>
                            <textarea name="contenido" class="form-control" rows="3"
                                placeholder="Escriba aquí la pregunta completa" required></textarea>
                        </div>

                        <!-- Selección de Tipo de Respuesta -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Tipo de Respuesta</label>
                            <select name="id_tipo_respuesta" class="form-select" id="tipo-respuesta" required>
                                <option value="">Seleccione un tipo</option>
                            </select>
                            <div class="form-text">Esto determinará cómo se debe responder</div>
                        </div>

                        <!-- Estado de la pregunta -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Estado</label>
                            <select name="estado" class="form-select">
                                <option value="Activo" selected>Activo</option>
                                <option value="Inactivo">Inactivo</option>
                            </select>
                        </div>

                        <!-- Contenedor para campos dinámicos según el tipo de respuesta -->
                        <div class="col-12" id="dynamic-fields-container">
                            <!-- Aquí se cargarán dinámicamente los campos según el tipo seleccionado -->
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bx bx-x me-1"></i>Cancelar
                </button>
                <button type="button" class="btn btn-primary" id="btn-guardar-pregunta">
                    <i class="bx bx-save me-1"></i>Guardar
                </button>
            </div>
        </div>
    </div>
</div>
<?php footer_web('Template.FooterDashboard', $data); ?>