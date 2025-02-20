<?php header_web('Template.HeaderDashboard', $data); ?>
<style>
    .dataTables_wrapper .table {
        width: 100% !important;
        margin: 0 !important;
    }

    .dataTables_scrollHead,
    .dataTables_scrollBody {
        width: 100% !important;
    }

    .dataTables_scrollHead table,
    .dataTables_scrollBody table {
        margin: 0 !important;
        width: 100% !important;
    }

    .table th,
    .table td {
        white-space: nowrap;
    }
</style>
<!-- Main content -->
<div class="content-wrapper">
    <!-- Content Header -->
    <section class="content-header">
        <div class="container-fluid p-0">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h3><i class="fas fa-key me-2"></i>Gestión de Permisos</h3>
                </div>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0"><i class="fas fa-key me-2"></i>Asignar Permisos</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 mb-3 mb-md-0">
                        <label for="selectRol">Seleccionar Rol:</label>
                        <select class="form-control" id="selectRol">
                            <option value="">Seleccione un rol...</option>
                        </select>
                    </div>
                    <!-- Select de Recurso -->
                    <div class="col-md-3 mb-3 mb-md-0">
                        <div class="form-group">
                            <label for="selectRecurso">Seleccionar Recurso:</label>
                            <select class="form-control" id="selectRecurso">
                                <option value="">Seleccione un recurso...</option>
                            </select>
                        </div>
                    </div>
                    <!-- Select de Acción -->
                    <div class="col-md-3 mb-3 mb-md-0">
                        <div class="form-group">
                            <label for="selectAccion">Seleccionar Acción:</label>
                            <select class="form-control" id="selectAccion">
                                <option value="">Seleccione una acción...</option>
                            </select>
                        </div>
                    </div>
                    <!-- Botones -->
                    <div class="col-md-3 mb-3 mb-md-0">
                        <div class="btn-group">
                            <button class="btn btn-primary" onclick="cargarPermisosRol()">
                                <i class="fas fa-eye me-1"></i> Ver Permisos
                            </button>
                            <button class="btn btn-success" onclick="agregarNuevoPermiso()">
                                <i class="fas fa-plus me-1"></i> Agregar Permiso
                            </button>
                        </div>
                    </div>
                </div>
                <div id="permisosPorRecurso" class="mt-4">
                </div>
            </div>
        </div>
        <div class="nav-align-top nav-tabs-shadow">
            <style>
                .nav-tabs-wrapper::-webkit-scrollbar {
                    display: none;
                    /* Chrome/Safari/Opera */
                }
            </style>
            <div class="nav-tabs-wrapper" style="overflow-x: auto; white-space: nowrap;">
                <ul class="nav nav-tabs" role="tablist" style="min-width: max-content;">
                    <li class="nav-item">
                        <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#recursos" aria-controls="recursos" aria-selected="false">
                            <i class="fas fa-cubes me-2"></i>Recursos
                        </button>
                    </li>
                    <li class="nav-item">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#acciones" aria-controls="acciones" aria-selected="false">
                            <i class="fas fa-cogs me-2"></i>Acciones
                        </button>
                    </li>
                </ul>
            </div>

            <div class="tab-content">
                <div class="tab-pane fade show active" id="recursos" role="tabpanel">
                    <div class="d-flex justify-content-end mb-3">
                        <button class="btn btn-primary" onclick="abrirModalRecurso()">
                            <i class="fas fa-plus me-1"></i> Nuevo Recurso
                        </button>
                    </div>
                    <div class="table-responsive">
                        <table id="tablaRecursos" class="table table-hover table-bordered w-100">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nombre</th>
                                    <th>Tipo</th>
                                    <th>Identificador</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>

                <div class="tab-pane fade" id="acciones" role="tabpanel">
                    <div class="d-flex justify-content-end mb-3">
                        <button class="btn btn-primary" onclick="abrirModalAccion()">
                            <i class="fas fa-plus me-1"></i> Nueva Acción
                        </button>
                    </div>
                    <div class="table-responsive">
                        <table id="tablaAcciones" class="table table-hover table-bordered w-100">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nombre</th>
                                    <th>Identificador</th>
                                    <th>Descripción</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Modales -->
<!-- Modal Recurso -->
<div class="modal fade" id="modalRecurso" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tituloModalRecurso">Nuevo Recurso</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formRecurso" onsubmit="return guardarRecurso(event)">
                <div class="modal-body">
                    <input type="hidden" id="idRecurso" name="idRecurso" value="">
                    <div class="form-group">
                        <label for="nombreRecurso">Nombre <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nombreRecurso" name="nombreRecurso" required>
                    </div>
                    <div class="form-group">
                        <label for="tipoRecurso">Tipo <span class="text-danger">*</span></label>
                        <select class="form-control" id="tipoRecurso" name="tipoRecurso" required>
                            <option value="ruta">Ruta</option>
                            <option value="accion">Acción</option>
                            <option value="reporte">Reporte</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="identificadorRecurso">Identificador <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="identificadorRecurso" name="identificadorRecurso" required>
                    </div>
                    <div class="form-group">
                        <label for="estadoRecurso">Estado</label>
                        <select class="form-control" id="estadoRecurso" name="estadoRecurso">
                            <option value="1">Activo</option>
                            <option value="0">Inactivo</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Acción -->
<div class="modal fade" id="modalAccion" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tituloModalAccion">Nueva Acción</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formAccion" onsubmit="return guardarAccion(event)">
                <div class="modal-body">
                    <input type="hidden" id="idAccion" name="idAccion" value="">
                    <div class="form-group">
                        <label for="nombreAccion">Nombre <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nombreAccion" name="nombreAccion" required>
                    </div>
                    <div class="form-group">
                        <label for="identificadorAccion">Identificador <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="identificadorAccion" name="identificadorAccion" required>
                    </div>
                    <div class="form-group">
                        <label for="descripcionAccion">Descripción</label>
                        <textarea class="form-control" id="descripcionAccion" name="descripcionAccion" rows="3"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="estadoAccion">Estado</label>
                        <select class="form-control" id="estadoAccion" name="estadoAccion">
                            <option value="1">Activo</option>
                            <option value="0">Inactivo</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php footer_web('Template.FooterDashboard', $data); ?>
<script>
    // Variables globales
    let tablaRoles, tablaRecursos, tablaAcciones;

    document.addEventListener("DOMContentLoaded", function() {
        initializeTables();
        loadInitialData();
    });

    function initializeTables() {
        const commonConfig = {
            language: {
                url: "/js/dataTable.Spanish.json",
            },
            responsive: false,
            pageLength: 10,
            aProcessing: true,
            aServerSide: true,
            dom: "Bfrtip",
            // buttons: ["copy", "csv", "excel", "pdf", "print"],
            order: [],
            scrollX: true,
        };

        tablaRecursos = $("#tablaRecursos").DataTable({
            ...commonConfig,
            ajax: {
                url: base_url + "admin/permisos-especiales/getrecursos",
                type: "POST",
                dataSrc: ""
            },
            columns: [{
                    data: "id"
                },
                {
                    data: "nombre"
                },
                {
                    data: "tipo"
                },
                {
                    data: "identificador"
                },
                {
                    data: "estado",
                    render: renderEstado
                },
                {
                    data: "id",
                    render: renderAccionesRecurso
                },
            ],
        });

        tablaAcciones = $("#tablaAcciones").DataTable({
            ...commonConfig,
            ajax: {
                type: "POST",
                url: base_url + "admin/permisos-especiales/getacciones",
                dataSrc: ""
            },
            columns: [{
                    data: "id"
                },
                {
                    data: "nombre"
                },
                {
                    data: "identificador"
                },
                {
                    data: "descripcion"
                },
                {
                    data: "estado",
                    render: renderEstado
                },
                {
                    data: "id",
                    render: renderAccionesAccion
                },
            ],
        });
    }

    // Render Functions
    function renderEstado(data) {
        return `<span class="badge bg-${data == 1 ? "success" : "danger"}">
                ${data == 1 ? "Activo" : "Inactivo"}
            </span>`;
    }

    function renderAccionesRol(id) {
        return `<div class="text-center">
                <button class="btn btn-sm btn-info" onclick="editarRol(${id})">
                    <i class="fas fa-edit"></i>
                </button>
                <button class="btn btn-sm btn-danger" onclick="eliminarRol(${id})">
                    <i class="fas fa-trash-alt"></i>
                </button>
            </div>`;
    }

    function renderAccionesRecurso(id) {
        return `<div class="text-center">
                <button class="btn btn-sm btn-info" onclick="editarRecurso(${id})">
                    <i class="fas fa-edit"></i>
                </button>
                <button class="btn btn-sm btn-danger" onclick="eliminarRecurso(${id})">
                    <i class="fas fa-trash-alt"></i>
                </button>
            </div>`;
    }

    function renderAccionesAccion(id) {
        return `<div class="text-center">
                <button class="btn btn-sm btn-info" onclick="editarAccion(${id})">
                    <i class="fas fa-edit"></i>
                </button>
                <button class="btn btn-sm btn-danger" onclick="eliminarAccion(${id})">
                    <i class="fas fa-trash-alt"></i>
                </button>
            </div>`;
    }

    // Utility Functions
    function resetForm(formId) {
        document.getElementById(formId).reset();
        document.querySelector(`#${formId} input[type="hidden"]`).value = "";
    }

    function setFormValues(formId, data) {
        for (let key in data) {
            const input = document.querySelector(`#${formId} [name="${key}"]`);
            if (input) input.value = data[key];
        }
    }

    function loadInitialData() {
        cargarRolesSelect();
        cargarRecursosSelect();
        cargarAccionesSelect();
    }

    // Modal Functions - Roles
    function abrirModalRol(id = null) {
        resetForm("formRol");
        if (id) {
            fetch(`${base_url}Permisos/getRol/${id}`)
                .then((r) => r.json())
                .then((data) => {
                    setFormValues("formRol", data);
                    $("#modalRol").modal("show");
                });
        } else {
            $("#modalRol").modal("show");
        }
    }

    async function guardarRol(e) {
        e.preventDefault();
        const formData = new FormData(e.target);

        try {
            const response = await fetch(`${base_url}Permisos/saveRol`, {
                method: "POST",
                body: formData,
            });
            const data = await response.json();

            if (data.status) {
                $("#modalRol").modal("hide");
                tablaRoles.ajax.reload();
                Swal.fire("Éxito", data.msg, "success");
            } else {
                Swal.fire("Error", data.msg, "error");
            }
        } catch (error) {
            console.error(error);
            Swal.fire("Error", "Ocurrió un error inesperado", "error");
        }
    }

    function eliminarRol(id) {
        Swal.fire({
            title: "¿Está seguro?",
            text: "Esta acción no se puede revertir",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Sí, eliminar",
            cancelButtonText: "Cancelar",
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`${base_url}Permisos/deleteRol/${id}`, {
                        method: "POST",
                    })
                    .then((r) => r.json())
                    .then((data) => {
                        if (data.status) {
                            tablaRoles.ajax.reload();
                            Swal.fire("Eliminado", data.msg, "success");
                        } else {
                            Swal.fire("Error", data.msg, "error");
                        }
                    });
            }
        });
    }

    function abrirModalRecurso(id = null) {
        resetForm("formRecurso");
        if (id) {
            fetch(`${base_url}admin/permisos-especiales/recurso/${id}`)
                .then((r) => r.json())
                .then((data) => {
                    setFormValues("formRecurso", data);
                    $("#modalRecurso").modal("show");
                });
        } else {
            $("#modalRecurso").modal("show");
        }
    }

    async function guardarRecurso(e) {
        e.preventDefault();
        const formData = new FormData(e.target);

        try {
            const response = await fetch(`${base_url}admin/permisos-especiales/saverecurso`, {
                method: "POST",
                body: formData,
            });
            const data = await response.json();

            if (data.status) {
                $("#modalRecurso").modal("hide");
                tablaRecursos.ajax.reload();
                Swal.fire("Éxito", data.message, "success");
            } else {
                Swal.fire("Error", data.message, "error");
            }
        } catch (error) {
            console.error(error);
            Swal.fire("Error", "Ocurrió un error inesperado", "error");
        }
    }

    function editarRecurso(id) {
        abrirModalRecurso(id);
    }

    function eliminarRecurso(id) {
        let formData = new FormData();
        formData.append("idRecurso", id);
        Swal.fire({
            title: "¿Está seguro?",
            text: "Esta acción no se puede revertir",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Sí, eliminar",
            cancelButtonText: "Cancelar",
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`${base_url}admin/permisos-especiales/deleterecurso`, {
                        method: "POST",
                        body: formData,
                    })
                    .then((r) => r.json())
                    .then((data) => {
                        if (data.status) {
                            tablaRecursos.ajax.reload();
                            Swal.fire("Eliminado", data.message, "success");
                        } else {
                            Swal.fire("Error", data.message, "error");
                        }
                    });
            }
        });
    }

    function abrirModalAccion(id = null) {
        resetForm("formAccion");
        if (id) {
            fetch(`${base_url}admin/permisos-especiales/accion/${id}`)
                .then((r) => r.json())
                .then((data) => {
                    setFormValues("formAccion", data);
                    $("#modalAccion").modal("show");
                });
        } else {
            $("#modalAccion").modal("show");
        }
    }

    async function guardarAccion(e) {
        e.preventDefault();
        const formData = new FormData(e.target);

        try {
            const response = await fetch(`${base_url}admin/permisos-especiales/saveaccion`, {
                method: "POST",
                body: formData,
            });
            const data = await response.json();

            if (data.status) {
                $("#modalAccion").modal("hide");
                tablaAcciones.ajax.reload();
                Swal.fire("Éxito", data.message, "success");
            } else {
                Swal.fire("Error", data.message, "error");
            }
        } catch (error) {
            console.error(error);
            Swal.fire("Error", "Ocurrió un error inesperado", "error");
        }
    }

    function editarAccion(id) {
        abrirModalAccion(id);
    }

    function eliminarAccion(id) {
        let formData = new FormData();
        formData.append("idAccion", id);
        Swal.fire({
            title: "¿Está seguro?",
            text: "Esta acción no se puede revertir",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Sí, eliminar",
            cancelButtonText: "Cancelar",
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`${base_url}admin/permisos-especiales/deleteaccion`, {
                        method: "POST",
                        body: formData,
                    })
                    .then((r) => r.json())
                    .then((data) => {
                        if (data.status) {
                            tablaAcciones.ajax.reload();
                            Swal.fire("Eliminado", data.message, "success");
                        } else {
                            Swal.fire("Error", data.message, "error");
                        }
                    });
            }
        });
    }

    // Cargar roles en select
    function cargarRolesSelect() {
        fetch(`${base_url}admin/permisos-especiales/getroles`)
            .then((r) => r.json())
            .then((data) => {
                const select = document.getElementById("selectRol");
                select.innerHTML = '<option value="">Seleccione un rol...</option>';
                data.forEach((rol) => {
                    if (rol.estado == 1) {
                        select.innerHTML += `<option value="${rol.id}">${rol.nombre}</option>`;
                    }
                });
                $("#selectRol").select2({
                    width: "100%",
                    placeholder: "Seleccione una opción",
                    dropdownParent: $("#selectRol").parent(),
                });
            });
    }

    // Cargar permisos por rol
    function cargarPermisosRol() {
        const rolId = document.getElementById("selectRol").value;
        if (!rolId) {
            document.getElementById("permisosPorRecurso").innerHTML = "";
            return;
        }

        fetch(`${base_url}admin/permisos-especiales/getpermisosporrol/${rolId}`)
            .then((r) => r.json())
            .then((data) => {
                renderPermisosRecurso(data);
            });
    }

    function renderPermisosRecurso(data) {
        let html = "";

        // Verificar si data está vacío, si está vacío mostrar mensaje de que no hay permisos
        if (data.length == 0) {
            html = `<div class="alert alert-warning">No hay permisos asignados</div>`;
            document.getElementById("permisosPorRecurso").innerHTML = html;
            return;
        }

        // Agrupar los permisos por recurso
        const permisosPorRecurso = data.reduce((acc, permiso) => {
            if (!acc[permiso.recurso]) {
                acc[permiso.recurso] = {
                    id: permiso.idrecurso,
                    nombre: permiso.recurso,
                    acciones: []
                };
            }
            acc[permiso.recurso].acciones.push({
                id: permiso.idaccion,
                nombre: permiso.accion,
                tiene_permiso: permiso.estado === 1
            });
            return acc;
        }, {});

        // Generar el HTML para cada recurso y sus acciones
        Object.values(permisosPorRecurso).forEach((recurso) => {
            html += `
            <div class="card mb-3">
                <div class="card-header pb-0">
                    <h5>${recurso.nombre}</h5>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        ${recurso.acciones.map((accion) => `
                            <div class="d-flex align-items-center mb-2">
                                <div class="custom-control custom-checkbox flex-grow-1">
                                    <input type="checkbox"
                                           class="custom-control-input"
                                           id="perm_${recurso.id}_${accion.id}"
                                           onchange="actualizarPermiso(${recurso.id}, ${accion.id})"
                                           ${accion.tiene_permiso ? "checked" : ""}>
                                    <label class="custom-control-label" for="perm_${recurso.id}_${accion.id}">
                                        ${accion.nombre}
                                    </label>
                                </div>
                                <button class="btn btn-sm btn-danger ms-2" 
                                        onclick="eliminarPermiso(${recurso.id}, ${accion.id})">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </div>`
                        ).join("")}
                    </div>
                </div>
            </div>`;
        });
        document.getElementById("permisosPorRecurso").innerHTML = html;
    }

    function cargarRecursosSelect() {
        fetch(`${base_url}admin/permisos-especiales/getrecursos`)
            .then((r) => r.json())
            .then((data) => {
                const select = document.getElementById("selectRecurso");
                select.innerHTML = '<option value="">Seleccione un recurso...</option>';
                data.forEach((recurso) => {
                    if (recurso.estado == 1) {
                        select.innerHTML += `<option value="${recurso.id}">${recurso.nombre}</option>`;
                    }
                });
                $("#selectRecurso").select2({
                    width: "100%",
                    placeholder: "Seleccione un recurso",
                    dropdownParent: $("#selectRecurso").parent()
                });
            });
    }

    function cargarAccionesSelect() {
        fetch(`${base_url}admin/permisos-especiales/getacciones`)
            .then((r) => r.json())
            .then((data) => {
                const select = document.getElementById("selectAccion");
                select.innerHTML = '<option value="">Seleccione una acción...</option>';
                data.forEach((accion) => {
                    if (accion.estado == 1) {
                        select.innerHTML += `<option value="${accion.id}">${accion.nombre}</option>`;
                    }
                });
                $("#selectAccion").select2({
                    width: "100%",
                    placeholder: "Seleccione una acción",
                    dropdownParent: $("#selectAccion").parent()
                });
            });
    }

    /*  async function guardarTodosLosPermisos() {
         const rolId = document.getElementById("selectRol").value;
         if (!rolId) {
             Swal.fire("Error", "Por favor seleccione un rol", "error");
             return;
         }

         const permisos = [];
         const checkboxes = document.querySelectorAll('[id^="perm_"]');

         checkboxes.forEach(checkbox => {
             const [_, recursoId, accionId] = checkbox.id.split('_');
             permisos.push({
                 rolId: parseInt(rolId),
                 recursoId: parseInt(recursoId),
                 accionId: parseInt(accionId),
                 estado: checkbox.checked ? 1 : 0
             });
         });

         try {
             const response = await fetch(`${base_url}Permisos/saveAllPermisos`, {
                 method: "POST",
                 headers: {
                     "Content-Type": "application/json",
                 },
                 body: JSON.stringify({
                     permisos
                 }),
             });

             const data = await response.json();
             if (data.status) {
                 Swal.fire("Éxito", "Permisos guardados correctamente", "success");
             } else {
                 Swal.fire("Error", data.msg || "Error al guardar los permisos", "error");
             }
         } catch (error) {
             console.error(error);
             Swal.fire("Error", "Ocurrió un error inesperado", "error");
         }
     } */

    async function actualizarPermiso(recursoId, accionId) {
        const rolId = document.getElementById("selectRol").value;
        const checkbox = document.getElementById(`perm_${recursoId}_${accionId}`);

        try {
            const response = await fetch(`${base_url}admin/permisos-especiales/updatepermiso`, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                },
                body: JSON.stringify({
                    rolId,
                    recursoId,
                    accionId,
                    estado: checkbox.checked ? 1 : 0,
                }),
            });

            const data = await response.json();
            if (!data.status) {
                Swal.fire("Error", data.message, "error");
                checkbox.checked = !checkbox.checked;
            }
        } catch (error) {
            console.error(error);
            Swal.fire("Error", "Ocurrió un error inesperado", "error");
            checkbox.checked = !checkbox.checked;
        }
    }

    async function agregarNuevoPermiso() {
        const rolId = document.getElementById("selectRol").value;
        const recursoId = document.getElementById("selectRecurso").value;
        const accionId = document.getElementById("selectAccion").value;

        // Validar selección
        if (!rolId || !recursoId || !accionId) {
            Swal.fire("Error", "Por favor seleccione rol, recurso y acción", "error");
            return;
        }

        try {
            const response = await fetch(`${base_url}admin/permisos-especiales/savepermiso`, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                },
                body: JSON.stringify({
                    rolId,
                    recursoId,
                    accionId,
                    estado: 1,
                }),
            });

            const data = await response.json();
            if (data.status) {
                Swal.fire("Éxito", "Permiso agregado correctamente", "success");
                // Recargar la vista de permisos
                cargarPermisosRol();
            } else {
                Swal.fire("Error", data.message || "Error al agregar el permiso", "error");
            }
        } catch (error) {
            console.error(error);
            Swal.fire("Error", "Ocurrió un error inesperado", "error");
        }
    }

    async function eliminarPermiso(recursoId, accionId) {
        const rolId = document.getElementById("selectRol").value;

        Swal.fire({
            title: "¿Está seguro?",
            text: "Esta acción eliminará el permiso permanentemente",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Sí, eliminar",
            cancelButtonText: "Cancelar",
        }).then(async (result) => {
            if (result.isConfirmed) {
                try {
                    const formData = new FormData();
                    formData.append("rolId", rolId);
                    formData.append("recursoId", recursoId);
                    formData.append("accionId", accionId);

                    const response = await fetch(`${base_url}admin/permisos-especiales/deletepermiso`, {
                        method: "POST",
                        body: formData
                    });

                    const data = await response.json();
                    if (data.status) {
                        Swal.fire("Éxito", "Permiso eliminado correctamente", "success");
                        cargarPermisosRol(); // Recargar la vista de permisos
                    } else {
                        Swal.fire("Error", data.message || "Error al eliminar el permiso", "error");
                    }
                } catch (error) {
                    console.error(error);
                    Swal.fire("Error", "Ocurrió un error inesperado", "error");
                }
            }
        });
    }
</script>