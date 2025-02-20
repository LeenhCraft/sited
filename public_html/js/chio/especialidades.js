class Especialidades {
  constructor() {
    this.permisos = permisos;
    this.tabla = null;
    this.modal = $("#mdlEspecialidad");
    this.form = $("#formEspecialidad");
    this.currentId = null;
    this.initTable();
    this.initEvents();
  }

  initTable() {
    this.tabla = $("#tbl").DataTable({
      language: {
        url: base_url + "js/dataTable.Spanish.json",
      },
      ajax: {
        url: base_url + "admin/especialidades",
        method: "POST",
        data: (d) => ({
          ...d,
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
                                    <strong>${row.nombre}</strong>
                                </div>
                                ${
                                  row.descripcion
                                    ? `
                                    <div class="text-muted mb-1">
                                        ${row.descripcion}
                                    </div>
                                `
                                    : ""
                                }
                                <div class="mt-1">
                                    ${this.getStatusBadge(row.eliminado)}
                                </div>
                                <div class="text-muted small mt-1">
                                    Registro: ${this.formatDate(
                                      row.fecha_registro
                                    )}
                                </div>
                                ${
                                  row.eliminado == 1
                                    ? `
                                    <div class="text-muted small">
                                        Eliminado: ${this.formatDate(
                                          row.fecha_eliminacion
                                        )}
                                    </div>
                                `
                                    : ""
                                }
                            </div>
                        `;
            return html;
          },
        },
        {
          className: "text-center",
          width: "150px",
          render: (data, type, row) => {
            let buttons = '<div class="btn-group">';

            if (!row.eliminado) {
              if (this.permisos?.["ruta.especialidades"]?.["update"]) {
                buttons += `
                                    <button type="button" class="btn btn-sm btn-primary btn-edit" 
                                            data-id="${row.idespecialidad}" title="Editar">
                                        <i class="bx bx-pencil"></i>
                                    </button>
                                `;
              }

              if (this.permisos?.["ruta.especialidades"]?.["delete"]) {
                buttons += `
                                    <button type="button" class="btn btn-sm btn-danger btn-delete" 
                                            data-id="${row.idespecialidad}" title="Eliminar">
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
      order: [[0, "asc"]],
      searching: false,
    });
  }

  initEvents() {
    $(".btn-nuevo").on("click", () => {
      this.resetForm();
      this.modal.modal("show");
      this.modal.find(".modal-title").text("Nueva Especialidad");
      setTimeout(() => $("#nombre").focus(), 500);
    });

    this.form.on("submit", (e) => {
      e.preventDefault();
      this.save();
    });

    this.tabla.on("click", ".btn-edit", (e) => {
      const id = $(e.currentTarget).data("id");
      this.edit(id);
    });

    this.tabla.on("click", ".btn-delete", (e) => {
      const id = $(e.currentTarget).data("id");
      this.delete(id);
    });

    $("#btnFiltrar").on("click", () => this.reloadTable());
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
    return eliminado == 1
      ? '<span class="badge bg-label-danger">Eliminado</span>'
      : '<span class="badge bg-label-success">Activo</span>';
  }

  async save() {
    try {
      const formData = new FormData(this.form[0]);
      const url = this.currentId
        ? `${base_url}admin/especialidades/update/${this.currentId}`
        : `${base_url}admin/especialidades/save`;

      this.showLoading();
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
      Swal.fire("Error", "Error al guardar la especialidad", "error");
    } finally {
      this.hideLoading();
    }
  }

  async edit(id) {
    try {
      this.showLoading();
      const response = await fetch(`${base_url}admin/especialidades/search/${id}`);
      const data = await response.json();

      if (data.success) {
        this.currentId = id;
        this.fillForm(data.especialidad);
        this.modal.find(".modal-title").text("Editar Especialidad");
        this.modal.modal("show");
      } else {
        Swal.fire("Error", "No se encontró la especialidad", "error");
      }
    } catch (error) {
      console.error(error);
      Swal.fire("Error", "Error al cargar los datos", "error");
    } finally {
      this.hideLoading();
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
        this.showLoading();
        const response = await fetch(
          `${base_url}admin/especialidades/delete/${id}`,
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
      Swal.fire("Error", "Error al eliminar la especialidad", "error");
    } finally {
      this.hideLoading();
    }
  }

  fillForm(data) {
    $("#nombre").val(data.nombre);
    $("#descripcion").val(data.descripcion);
  }

  resetForm() {
    this.form[0].reset();
    this.currentId = null;
  }

  reloadTable() {
    this.tabla.ajax.reload();
  }

  showLoading() {
    divLoading.css("display", "flex");
  }

  hideLoading() {
    divLoading.css("display", "none");
  }
}

document.addEventListener("DOMContentLoaded", function () {
  const especialidades = new Especialidades();
});
