class Usuarios {
  constructor() {
    this.permisos = permisos;
    this.tabla = null;
    this.modal = $("#mdlUsuario");
    this.modalPassword = $("#mdlPassword");
    this.form = $("#formUsuario");
    this.initDataTable();
    this.initEvents();
    this.loadPersonal();
  }

  initDataTable() {
    this.tabla = $("#tbl").DataTable({
      language: { url: base_url + "js/dataTable.Spanish.json" },
      ajax: {
        url: base_url + "admin/usuarios",
        method: "POST",
        data: (d) => ({
          ...d,
          filtro_rol: $("#filtro_rol").val(),
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
              <div class="fw-bold">${data.usu_usuario}</div>
              <div>
                  ${
                    data.usu_activo == 1
                      ? '<span class="badge bg-success">Activo</span>'
                      : '<span class="badge bg-warning">Inactivo</span>'
                  }
                  ${
                    data.usu_twoauth == 1
                      ? '<span class="badge bg-info ms-1">2FA</span>'
                      : ""
                  }
                  ${
                    data.usu_primera == 1
                      ? '<span class="badge bg-warning ms-1">Cambio pendiente</span>'
                      : ""
                  }
              </div>
          </div>`,
        },
        {
          data: null,
          render: (data) => `
          <div class="d-flex">
              <img src="${
                data.per_foto != "" && data.per_foto != null
                  ? "/uploads/personal/" + data.per_foto
                  : "/img/default.png"
              }" 
                  class="rounded me-2" style="width: 40px; height: 40px; object-fit: cover;">
              <div>
                  <div>${data.per_nombre}</div>
                  <div class="small text-muted">${data.per_dni}</div>
              </div>
          </div>`,
        },
        { data: "rol_nombre" },
        {
          data: "usu_estado",
          render: (estado) => `
          <span class="badge bg-${estado == 1 ? "success" : "danger"}">
              ${estado == 1 ? "Activo" : "Inactivo"}
          </span>`,
        },
        {
          data: null,
          className: "text-center",
          render: (data) => {
            let buttons = '<div class="btn-group">';

            if (this.permisos?.["ruta.usuarios"]?.["update"]) {
              buttons += `
              <button class="btn btn-sm btn-primary btn-edit" data-id="${data.idusuario}">
                  <i class="bx bx-edit-alt"></i>
              </button>`;
            }

            if (this.permisos?.["ruta.usuarios"]?.["delete"]) {
              buttons += `
              <button class="btn btn-sm btn-danger btn-delete" data-id="${data.idusuario}">
                  <i class="bx bx-trash"></i>
              </button>`;
            }

            return buttons + "</div>";
          },
        },
      ],
    });
  }

  async loadPersonal() {
    try {
      const response = await fetch(`${base_url}admin/usuarios/personal`);
      const data = await response.json();

      if (data.success) {
        const select = $("#idpersona");
        select.find("option:not(:first)").remove();

        data.personal.forEach((p) => {
          select.append(
            `<option value="${p.idpersona}">${p.per_nombre} - ${p.per_dni}</option>`
          );
        });
      }
    } catch (error) {
      console.error(error);
    }
  }

  initEvents() {
    $(".btn-nuevo").on("click", () => {
      this.currentId = null;
      this.form[0].reset();
      $(".select-personal").show();
      $(".edit-options").addClass("d-none");
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

    $("#btnCopyPassword").on("click", () => {
      const password = $("#tempPassword").text();
      navigator.clipboard.writeText(password);
      Swal.fire({
        title: "Copiado",
        text: "Contraseña copiada al portapapeles",
        icon: "success",
        timer: 1500,
        showConfirmButton: false,
      });
    });

    $("#btnFiltrar").on("click", () => this.tabla.ajax.reload());

    $("#btnLimpiar").on("click", () => {
      $("#filtro_rol").val("");
      $("#filtro_estado").val("1");
      $("#filtro_search").val("");
      this.tabla.ajax.reload();
    });
  }

  async save() {
    try {
      const formData = new FormData(this.form[0]);
      const url = this.currentId
        ? `${base_url}admin/usuarios/update/${this.currentId}`
        : `${base_url}admin/usuarios/save`;

      const response = await fetch(url, {
        method: "POST",
        body: formData,
      });

      const data = await response.json();

      if (data.status) {
        this.modal.modal("hide");
        this.tabla.ajax.reload();

        if (data.password) {
          $("#tempPassword").text(data.password);
          this.modalPassword.modal("show");
        } else {
          Swal.fire("Éxito", data.message, "success");
        }
      } else {
        Swal.fire("Error", data.message, "error");
      }
    } catch (error) {
      console.error(error);
      Swal.fire("Error", "Error al guardar el usuario", "error");
    }
  }

  async edit(id) {
    try {
      const response = await fetch(`${base_url}admin/usuarios/search/${id}`);
      const data = await response.json();

      if (data.success) {
        this.currentId = id;
        const usuario = data.usuario;

        $(".select-personal").hide();
        $(".edit-options").removeClass("d-none");

        $("#idrol").val(usuario.idrol);
        $("#usuario").val(usuario.usuario);
        $("#activo").prop("checked", usuario.activo == 1);
        $("#twoauth").prop("checked", usuario.twoauth == 1);

        this.modal.modal("show");
      } else {
        Swal.fire("Error", "Usuario no encontrado", "error");
      }
    } catch (error) {
      console.error(error);
      Swal.fire("Error", "Error al cargar el usuario", "error");
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
        const response = await fetch(`${base_url}admin/usuarios/delete/${id}`, {
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
      Swal.fire("Error", "Error al eliminar el usuario", "error");
    }
  }
}

document.addEventListener("DOMContentLoaded", () => new Usuarios());
