class Pacientes {
  constructor() {
    this.permisos = permisos;
    this.pacientes = [];
    this.tabla = null;
    this.modal = $("#mdlPaciente");
    this.modalView = $("#mdlVerPaciente");
    this.modalPDF = $("#mdlPDF");
    this.form = $("#formPacientes");
    this.btnNuevo = $(".btn-nuevo");
    this.btnBuscarDocumento = $("#buscarDocumento");
    this.btnGuardar = $(".btn-save");
    this.btnCancelar = $(".btn-cancel");
    this.btnPrint = $(".btn-print");
    this.btnPrintPDF = $(".btn-print-pdf");
    this.btnFiltrar = $("#btnFiltrar");
    this.btnLimpiar = $("#btnLimpiar");
    this.currentId = null;
    this.initTable();
    this.initEvents();
  }

  async initTable() {
    this.tabla = $("#tbl").DataTable({
      language: {
        url: base_url + "js/dataTable.Spanish.json",
      },
      ajax: {
        url: base_url + "admin/pacientes",
        method: "POST",
        data: (d) => ({
          ...d,
          fecha_inicio: $("#fecha_inicio").val(),
          fecha_fin: $("#fecha_fin").val(),
          filtro_sexo: $("#filtro_sexo").val(),
          filtro_estado: $("#filtro_estado").val(),
          filtro_search: $("#filtro_search").val(),
        }),
        dataSrc: "",
      },
      columns: [
        {
          render: (data, type, row) => {
            let html = `
                <div class="d-flex flex-column">
                    <div class="mb-1">
                        <strong>${row.dni}</strong>
                    </div>
                    <div class="mb-1">
                        ${row.nombre}
                    </div>
                    <div class="text-muted small">
                        Tel: ${row.celular}
                    </div>
                    <div class="mt-1">
                        ${this.getStatusBadge(row.eliminado)}
                    </div>
                    <div class="text-muted small mt-1">
                        Registro: ${this.formatDate(row.fecha_registro)}
                    </div>
                    ${
                      row.eliminado
                        ? `<div class="text-muted small">
                            Eliminado: ${this.formatDate(row.fecha_eliminacion)}
                        </div>`
                        : ""
                    }
                </div>
            `;
            return html;
          },
        },
        {
          className: "text-center",
          render: (data, type, row) => {
            let buttons = '<div class="btn-group">';

            // Botón de ver - solo si tiene permiso de view
            if (this.permisos?.["ruta.paciente"]?.["view"]) {
              buttons += `
                      <button type="button" class="btn btn-sm btn-info btn-view" 
                              data-id="${row.idpaciente}" title="Ver detalles">
                          <i class='bx bx-search-alt-2'></i>
                      </button>
                  `;
            }

            // Botones de editar y eliminar solo para registros no eliminados
            if (!row.eliminado) {
              // Botón de editar - solo si tiene permiso de update
              if (this.permisos?.["ruta.paciente"]?.["update"]) {
                buttons += `
                          <button type="button" class="btn btn-sm btn-primary btn-edit" 
                                  data-id="${row.idpaciente}" title="Editar">
                              <i class="bx bx-pencil"></i>
                          </button>
                      `;
              }

              // Botón de eliminar - solo si tiene permiso de delete
              if (this.permisos?.["ruta.paciente"]?.["delete"]) {
                buttons += `
                          <button type="button" class="btn btn-sm btn-danger btn-delete" 
                                  data-id="${row.idpaciente}" title="Eliminar">
                              <i class="bx bx-trash"></i>
                          </button>
                      `;
              }
            }

            buttons += "</div>";
            return buttons;
          },
        },
      ],
      order: [],
      scrollX: true,
      searching: false,
    });
  }

  formatDate(dateString) {
    if (!dateString) return "";
    const date = new Date(dateString);
    return date.toLocaleString("es-ES", {
      day: "2-digit",
      month: "2-digit",
      year: "numeric",
      hour: "2-digit",
      minute: "2-digit",
    });
  }

  getStatusBadge(eliminado) {
    return eliminado == "1"
      ? '<span class="badge bg-label-danger">Eliminado</span>'
      : '<span class="badge bg-label-success">Activo</span>';
  }

  initEvents() {
    this.btnNuevo.on("click", () => {
      this.resetForm();
      this.modal.modal("show");
      setTimeout(() => {
        $("#documento").focus();
      }, 500);
    });

    this.form.on("submit", (e) => {
      e.preventDefault();
      this.save();
    });

    this.btnCancelar.on("click", () => {
      this.modal.modal("hide");
    });

    this.tabla.on("click", ".btn-edit", (e) => {
      const id = $(e.currentTarget).data("id");
      this.edit(id);
    });

    this.tabla.on("click", ".btn-delete", (e) => {
      const id = $(e.currentTarget).data("id");
      this.delete(id);
    });

    this.tabla.on("click", ".btn-view", (e) => {
      const id = $(e.currentTarget).data("id");
      this.view(id);
    });

    this.btnPrint.on("click", () => this.printPatient());

    this.btnPrintPDF.on("click", () => {
      const iframe = document.getElementById("pdfViewer");
      iframe.contentWindow.print();
    });

    this.btnBuscarDocumento.on("click", () => {
      const dni = $("#documento").val();
      if (dni) this.searchByDNI(dni);
    });

    this.btnFiltrar.on("click", () => this.reloadTable());

    this.btnLimpiar.on("click", () => this.limpiarFiltros());
  }

  async searchByDNI(dni) {
    try {
      this.showLoading();
      const response = await fetch(`${base_url}admin/doc/dni/${dni}`);
      const data = await response.json();
      if (data.success) {
        const { nombre_completo } = data.data;
        $("#nombre").val(nombre_completo);
        setTimeout(() => {
          $("#celular").focus();
        }, 500);
        // Rellenar otros campos según la respuesta
      } else {
        Swal.fire("Error", "No se encontró el documento", "error");
      }
    } catch (error) {
      console.error(error);
      Swal.fire("Error", "Error al buscar el documento", "error");
    } finally {
      this.hideLoading();
    }
  }

  async save() {
    try {
      const formData = new FormData(this.form[0]);
      const url = this.currentId
        ? `${base_url}admin/pacientes/update/${this.currentId}`
        : `${base_url}admin/pacientes/save`;

      const response = await fetch(url, {
        method: "POST",
        body: formData,
      });

      const data = await response.json();

      if (data.status) {
        Swal.fire("Éxito", data.message, "success");
        this.modal.modal("hide");
        this.tabla.ajax.reload();
      } else {
        Swal.fire("Atención", data.message, "info");
      }
    } catch (error) {
      console.error(error);
      Swal.fire("Error", "Error al guardar", "error");
    }
  }

  async edit(id) {
    try {
      const response = await fetch(`${base_url}admin/pacientes/search/${id}`);
      const data = await response.json();

      if (data.success) {
        this.currentId = id;
        this.fillForm(data.paciente);
        this.modal.modal("show");
      } else {
        Swal.fire("Error", "No se encontró el paciente", "error");
      }
    } catch (error) {
      console.error(error);
      Swal.fire("Error", "Error al cargar el paciente", "error");
    }
  }

  async delete(id) {
    try {
      const result = await Swal.fire({
        title: "¿Está seguro?",
        text: "Esta acción no se puede revertir",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Sí, eliminar",
        cancelButtonText: "Cancelar",
      });

      if (result.isConfirmed) {
        const response = await fetch(
          `${base_url}admin/pacientes/delete/${id}`,
          {
            method: "POST",
          }
        );
        const data = await response.json();

        if (data.status) {
          Swal.fire("Eliminado", data.message, "success");
          this.tabla.ajax.reload();
        } else {
          Swal.fire("Error", data.message, "error");
        }
      }
    } catch (error) {
      console.error(error);
      Swal.fire("Error", "Error al eliminar", "error");
    }
  }

  async view(id) {
    try {
      const response = await fetch(`${base_url}admin/pacientes/search/${id}`);
      const data = await response.json();

      if (data.success) {
        this.fillViewModal(data.paciente);
        this.modalView.data("currentId", id);
        this.modalView.modal("show");
      } else {
        Swal.fire("Error", "No se encontró el paciente", "error");
      }
    } catch (error) {
      console.error(error);
      Swal.fire("Error", "Error al cargar el paciente", "error");
    }
  }

  fillForm(data) {
    for (const [key, value] of Object.entries(data)) {
      $(`#${key}`).val(value);
    }
  }

  fillViewModal(data) {
    $("#view_documento").text(data.documento);
    $("#view_nombre").text(data.nombre);
    $("#view_celular").text(data.celular);
    $("#view_edad").text(data.edad + " años");
    $("#view_sexo").text(data.sexo === "M" ? "Masculino" : "Femenino");
    $("#view_peso").text(data.peso + " kg");
    $("#view_altura").text(data.altura + " cm");
  }

  printPatient() {
    try {
      const currentId = this.modalView.data("currentId");
      if (!currentId) {
        Swal.fire("Error", "No se pudo identificar al paciente", "error");
        return;
      }

      // Mostrar modal de PDF
      this.modalPDF.modal("show");

      // Cargar PDF en el iframe
      const iframe = document.getElementById("pdfViewer");
      iframe.src = `${base_url}admin/pacientes/pdf/${currentId}`;
    } catch (error) {
      console.error(error);
      Swal.fire("Error", "Error al generar el PDF", "error");
    }
  }

  resetForm() {
    this.form[0].reset();
    this.currentId = null;
    this.form.find("input, select, textarea").prop("disabled", false);
    this.btnGuardar.show();
  }

  reloadTable() {
    console.log("Recargando tabla...");
    this.tabla.ajax.reload();
  }

  limpiarFiltros() {
    $("#fecha_inicio").val("");
    $("#fecha_fin").val("");
    $("#filtro_sexo").val("");
    $("#filtro_estado").val("activos");
    $("#filtro_search").val("");
    this.reloadTable();
  }

  showLoading() {
    divLoading.css("display", "flex");
  }

  hideLoading() {
    divLoading.css("display", "none");
  }
}

document.addEventListener("DOMContentLoaded", function () {
  const pacientes = new Pacientes();
});
