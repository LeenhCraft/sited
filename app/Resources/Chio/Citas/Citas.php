<?php header_web('Template.HeaderDashboard', $data);
$permisos = $data['permisos'];
$permissions = $data["permission"];
?>
<script>
    const permisos = <?php echo json_encode($permisos); ?>
</script>
<style>
    .dataTables_wrapper {
        padding: 0;
    }
</style>

<div class="container-fluid p-0">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title m-0 p-0"><i class="fas fa-calendar-alt me-2"></i> Gestión de Citas Médicas</h3>
                </div>
                <div class="card-body">
                    <!-- Navegación por pestañas -->
                    <ul class="nav nav-tabs" id="citasTabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="tabListado" data-toggle="tab" href="#listadoCitas" role="tab" aria-controls="listadoCitas" aria-selected="true">
                                <i class="fas fa-list me-2"></i> Listado
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="tabCalendario" data-toggle="tab" href="#calendarioCitas" role="tab" aria-controls="calendarioCitas" aria-selected="false">
                                <i class="fas fa-calendar-alt me-2"></i> Calendario de Disponibilidad
                            </a>
                        </li>
                    </ul>

                    <!-- Contenido de las pestañas -->
                    <div class="tab-content p-0" id="citasTabsContent">
                        <!-- Pestaña de listado -->
                        <div class="tab-pane fade show active" id="listadoCitas" role="tabpanel" aria-labelledby="tabListado">
                            <!-- Filtros para búsqueda y exportación -->
                            <div class="row mb-4 mt-4">
                                <div class="col-md-12">
                                    <div class="card">
                                        <div class="card-header bg-info text-white m-0 p-0">
                                            <h5 class="m-0 p-0">
                                                <button class="btn btn-link text-white" type="button" data-toggle="collapse" data-target="#collapseFilters" aria-expanded="true">
                                                    <i class="fas fa-filter me-2"></i> Filtros de Búsqueda
                                                </button>
                                            </h5>
                                        </div>
                                        <div id="collapseFilters" class="collapse show">
                                            <div class="card-body">
                                                <form id="searchForm" method="POST">
                                                    <div class="row">
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label for="fechaInicio">Fecha Inicio:</label>
                                                                <input type="date" class="form-control" id="fechaInicio" name="fechaInicio">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label for="fechaFin">Fecha Fin:</label>
                                                                <input type="date" class="form-control" id="fechaFin" name="fechaFin">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label for="especialidad">Especialidad:</label>
                                                                <select class="form-control" id="especialidad" name="especialidad">
                                                                    <option value="">Todas las especialidades</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label for="paciente">Paciente:</label>
                                                                <select class="form-control select2-pacientes" id="paciente" name="paciente">
                                                                    <option value="">Seleccione un paciente</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label for="estadoCita">Estado de Cita:</label>
                                                                <select class="form-control" id="estadoCita" name="estadoCita">
                                                                    <option value="">Todos los estados</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label for="medico">Médico:</label>
                                                                <select class="form-control select2-medicos" id="medico" name="medico">
                                                                    <option value="">Todos los médicos</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 d-flex align-items-end">
                                                            <div class="btn-group w-100">
                                                                <button type="button" id="btnBuscar" class="btn btn-primary">
                                                                    <i class="fas fa-search me-2"></i> Buscar
                                                                </button>
                                                                <button type="button" id="btnLimpiar" class="btn btn-secondary">
                                                                    <i class="fas fa-broom me-2"></i> Limpiar Filtros
                                                                </button>
                                                                <button type="button" id="btnExportar" class="btn btn-success">
                                                                    <i class="fas fa-file-pdf me-2"></i> Exportar PDF
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Botón para agregar nueva cita -->
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <button type="button" id="btnNuevaCita" class="btn btn-primary">
                                        <i class="fas fa-plus-circle me-2"></i> Nueva Cita
                                    </button>
                                </div>
                            </div>

                            <!-- Tabla de citas -->
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="table-responsivee">
                                        <table id="tablaCitas" class="table table-striped table-bordered table-hover" style="width:100%">
                                            <thead class="text-primary">
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Paciente</th>
                                                    <th>Médico</th>
                                                    <th>Especialidad</th>
                                                    <th>Fecha</th>
                                                    <th>Hora</th>
                                                    <th>Estado</th>
                                                    <th>Observaciones</th>
                                                    <th>Acciones</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <!-- Datos cargados dinámicamente -->
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Pestaña de calendario de disponibilidad -->
                        <div class="tab-pane fade" id="calendarioCitas" role="tabpanel" aria-labelledby="tabCalendario">
                            <div class="card mt-3">
                                <div class="card-header text-primary">
                                    <h5 class="card-title mb-0"><i class="fas fa-calendar-alt me-2"></i> Calendario de Disponibilidad</h5>
                                </div>
                                <div class="card-body">
                                    <!-- Filtros del calendario -->
                                    <div class="row mb-4">
                                        <div class="col-md-12">
                                            <div class="card">
                                                <div class="card-header bg-info text-white m-0 p-0">
                                                    <h5 class="mb-0">
                                                        <button class="btn btn-link text-white" type="button" data-toggle="collapse" data-target="#collapseCalendarioFilters" aria-expanded="true">
                                                            <i class="fas fa-filter me-2"></i> Filtros de Calendario
                                                        </button>
                                                    </h5>
                                                </div>
                                                <div id="collapseCalendarioFilters" class="collapse show">
                                                    <div class="card-body">
                                                        <form id="calendarioForm" method="POST">
                                                            <div class="row">
                                                                <div class="col-md-3 mb-md-3">
                                                                    <div class="form-group">
                                                                        <label for="medicoCalendario">Médico:</label>
                                                                        <select class="form-control select2-medicos" id="medicoCalendario" name="medicoCalendario">
                                                                            <option value="">Todos los médicos</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-3 mb-md-3">
                                                                    <div class="form-group">
                                                                        <label for="especialidadCalendario">Especialidad:</label>
                                                                        <select class="form-control" id="especialidadCalendario" name="especialidadCalendario">
                                                                            <option value="">Todas las especialidades</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-3 mb-md-3">
                                                                    <div class="form-group">
                                                                        <label for="fechaInicioCalendario">Fecha Inicio:</label>
                                                                        <input type="date" class="form-control" id="fechaInicioCalendario" name="fechaInicioCalendario">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-3 mb-md-3">
                                                                    <div class="form-group">
                                                                        <label for="fechaFinCalendario">Fecha Fin:</label>
                                                                        <input type="date" class="form-control" id="fechaFinCalendario" name="fechaFinCalendario">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-12 mb-md-3 d-flex justify-content-end">
                                                                    <button type="button" id="btnFiltrarCalendario" class="btn btn-primary me-2">
                                                                        <i class="fas fa-search me-2"></i> Buscar
                                                                    </button>
                                                                    <button type="button" id="btnLimpiarFiltrosCalendario" class="btn btn-secondary">
                                                                        <i class="fas fa-broom me-2"></i> Limpiar Filtros
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Contenedor principal -->
                                    <div class="row">
                                        <!-- Calendario de disponibilidad -->
                                        <div class="col-md-8">
                                            <div id="calendarioDisponibilidad" class="calendario-wrapper">
                                                <!-- Aquí se renderizará el calendario -->
                                                <div class="text-center p-5">
                                                    <i class="fas fa-calendar-plus fa-4x text-muted mb-3"></i>
                                                    <p>Seleccione los filtros y haga clic en Buscar para ver la disponibilidad</p>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Panel lateral con próximas citas disponibles -->
                                        <div class="col-md-4">
                                            <div class="card">
                                                <div class="card-header bg-success text-white">
                                                    <h5 class="card-title mb-0"><i class="fas fa-clock me-2"></i> Próximas Citas Disponibles</h5>
                                                </div>
                                                <div class="card-body p-0">
                                                    <div id="proximasCitasDisponibles">
                                                        <!-- Aquí se cargarán las próximas citas disponibles -->
                                                        <div class="text-center p-4">
                                                            <i class="fas fa-calendar-check fa-3x text-muted mb-3"></i>
                                                            <p>Cargando próximas citas disponibles...</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para registrar/editar cita -->
<div class="modal fade" id="modalCita" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="modalCitaLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header text-primary">
                <h5 class="modal-title" id="modalCitaLabel">Nueva Cita Médica</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <form id="formCita">
                    <input type="hidden" id="idCita" name="idcita" value="0">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="pacienteModal">Paciente: <span class="text-danger">*</span></label>
                                <select class="form-control select2-pacientes-modal" id="pacienteModal" name="idpaciente" required>
                                    <option value="">Seleccione un paciente</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="especialidadModal">Especialidad: <span class="text-danger">*</span></label>
                                <select class="form-control" id="especialidadModal" name="especialidadModal" required>
                                    <option value="">Seleccione una especialidad</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="medicoModal">Médico: <span class="text-danger">*</span></label>
                                <select class="form-control" id="medicoModal" name="idpersonal" required>
                                    <option value="">Seleccione un médico</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="estadoCitaModal">Estado: <span class="text-danger">*</span></label>
                                <select class="form-control" id="estadoCitaModal" name="id_estado_cita" required>
                                    <option value="">Seleccione un estado</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="fechaModal">Fecha: <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="fechaModal" name="fecha" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="horariosDisponibles">Horarios Disponibles: <span class="text-danger">*</span></label>
                                <select class="form-control" id="horariosDisponibles" name="hora" required>
                                    <option value="">Seleccione un horario</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="observacionesModal">Observaciones:</label>
                                <textarea class="form-control" id="observacionesModal" name="observaciones" rows="3"></textarea>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i> Cancelar
                </button>
                <button type="button" class="btn btn-primary" id="btnGuardarCita">
                    <i class="fas fa-save me-2"></i> Guardar
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Vista Previa PDF -->
<div class="modal fade" id="modalPdfPreview" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="modalPdfPreviewLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header text-primary">
                <h5 class="modal-title" id="modalPdfPreviewLabel">Vista Previa de PDF</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <div class="embed-responsive embed-responsive-16by9">
                    <iframe class="embed-responsive-item" id="pdfFrame" src="" allowfullscreen></iframe>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <a href="#" id="btnDescargarPdf" class="btn btn-primary" target="_blank">
                    <i class="fas fa-download me-2"></i> Descargar
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Modal de confirmación de eliminación -->
<div class="modal fade" id="modalConfirmDelete" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="modalConfirmDeleteLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="modalConfirmDeleteLabel">Confirmar Eliminación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <p>¿Está seguro que desea eliminar esta cita médica? Esta acción no se puede deshacer.</p>
                <input type="hidden" id="idCitaEliminar">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger" id="btnConfirmDelete">
                    <i class="fas fa-trash-alt me-2"></i> Eliminar
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Estilos para el calendario -->
<style>
    .calendario-container {
        width: 100%;
        border: 1px solid #ddd;
        border-radius: 8px;
        overflow: hidden;
        background-color: #f9f9f9;
    }

    .calendario-header {
        background-color: #f0f0f0;
        padding: 15px;
        border-bottom: 1px solid #ddd;
    }

    .calendario-titulo {
        margin: 0;
        color: #007bff;
    }

    .calendario-periodo {
        margin: 5px 0 0;
        font-size: 0.9rem;
        color: #666;
    }

    .calendario-medico {
        background-color: #fff;
        border: 1px solid #e0e0e0;
        border-radius: 5px;
        margin: 10px;
        overflow: hidden;
    }

    .calendario-medico-header {
        background-color: #f5f5f5;
        padding: 10px 15px;
        border-bottom: 1px solid #e0e0e0;
    }

    .calendario-medico-header h6 {
        margin: 0;
        color: #333;
    }

    .calendario-dias {
        padding: 10px;
        display: flex;
        flex-wrap: wrap;
    }

    .calendario-dia {
        width: 220px;
        margin: 5px;
        background-color: #fff;
        border: 1px solid #e0e0e0;
        border-radius: 5px;
        overflow: hidden;
    }

    .calendario-fecha {
        background-color: #f8f9fa;
        padding: 8px 10px;
        text-align: center;
        border-bottom: 1px solid #e0e0e0;
        font-size: 0.9rem;
        font-weight: bold;
    }

    .calendario-slots {
        padding: 5px;
        max-height: 200px;
        overflow-y: auto;
    }

    .slot-horario {
        margin: 5px;
        padding: 6px 10px;
        background-color: #e9f7fe;
        border: 1px solid #b8e6ff;
        border-radius: 4px;
        text-align: center;
        font-size: 0.85rem;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .slot-horario:hover {
        background-color: #007bff;
        color: #fff;
        border-color: #0069d9;
    }

    .calendario-wrapper {
        max-height: 700px;
        overflow-y: auto;
        padding: 10px;
    }
</style>

<?php footer_web('Template.FooterDashboard', $data); ?>