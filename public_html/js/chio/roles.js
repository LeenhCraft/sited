class Roles {
  constructor() {
    this.permisos = permisos;
    this.tabla = null;
    this.modal = $("#mdlRol");
    this.form = $("#formRol");
    this.initDataTable();
    this.initEvents();
  }

  initDataTable() {
    this.tabla = $("#tbl").DataTable({
      language: { url: base_url + "js/dataTable.Spanish.json" },
      ajax: {
        url: base_url + "admin/roles",
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
          data: null,
          render: (data) => `
                        <div class="d-flex flex-column">
                            <div class="fw-bold">${data.rol_cod}</div>
                            <div>${data.rol_nombre}</div>
                        </div>`,
        },
        { data: "rol_descripcion" },
        {
          data: "rol_estado",
          render: (estado) => `
                        <span class="badge bg-label-${
                          estado == 1 ? "success" : "danger"
                        }">
                            ${estado == 1 ? "Activo" : "Inactivo"}
                        </span>`,
        },
        {
          data: null,
          className: "text-center",
          render: (data) => {
            let buttons = '<div class="btn-group">';

            if (this.permisos?.["ruta.roles"]?.["update"]) {
              buttons += `
                                <button class="btn btn-sm btn-primary btn-edit" data-id="${data.idrol}">
                                    <i class="bx bx-edit-alt"></i>
                                </button>`;
            }

            if (this.permisos?.["ruta.roles"]?.["delete"]) {
              buttons += `
                                <button class="btn btn-sm btn-danger btn-delete" data-id="${data.idrol}">
                                    <i class="bx bx-trash"></i>
                                </button>`;
            }

            return buttons + "</div>";
          },
        },
      ],
      order: [[0, "asc"]],
    });
  }

  initEvents() {
    $(".btn-nuevo").on("click", () => {
      this.currentId = null;
      this.form[0].reset();
      this.modal.modal("show");
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

    $("#btnFiltrar").on("click", () => this.tabla.ajax.reload());
    $("#btnLimpiar").on("click", () => {
      $("#filtro_estado").val("1");
      $("#filtro_search").val("");
      this.tabla.ajax.reload();
    });
  }

  async save() {
    try {
      const formData = new FormData(this.form[0]);
      const url = this.currentId
        ? `${base_url}admin/roles/update/${this.currentId}`
        : `${base_url}admin/roles/save`;

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
        Swal.fire("Error", data.message, "error");
      }
    } catch (error) {
      console.error(error);
      Swal.fire("Error", "Error al guardar el rol", "error");
    }
  }

  async edit(id) {
    try {
      const response = await fetch(`${base_url}admin/roles/search/${id}`);
      const data = await response.json();

      if (data.success) {
        this.currentId = id;
        const rol = data.rol;
        $("#codigo").val(rol.codigo);
        $("#nombre").val(rol.nombre);
        $("#descripcion").val(rol.descripcion);
        this.modal.modal("show");
      } else {
        Swal.fire("Error", "Rol no encontrado", "error");
      }
    } catch (error) {
      console.error(error);
      Swal.fire("Error", "Error al cargar el rol", "error");
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
        const response = await fetch(`${base_url}admin/roles/delete/${id}`, {
          method: "POST",
        });

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
      Swal.fire("Error", "Error al eliminar el rol", "error");
    }
  }
}

document.addEventListener("DOMContentLoaded", () => new Roles());
