class Personal {
  constructor() {
    this.permisos = permisos;
    this.tabla = null;
    this.modal = $("#mdlPersonal");
    this.modalView = $("#mdlVerPersonal");
    this.form = $("#formPersonal");
    this.currentId = null;
    this.initDataTable();
    this.initEvents();
  }

  initDataTable() {
    this.tabla = $("#tbl").DataTable({
      language: { url: base_url + "js/dataTable.Spanish.json" },
      ajax: {
        url: base_url + "admin/personas",
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
          <div class="d-flex">
              <img src="${
                data.per_foto
                  ? "/uploads/personal/" + data.per_foto
                  : "/img/default.png"
              }" 
                  class="rounded me-3" style="width: 50px; height: 50px; object-fit: cover;">
              <div>
                  <div class="fw-bold">${data.per_dni}</div>
                  <div>${data.per_nombre}</div>
              </div>
          </div>`,
        },
        {
          data: null,
          render: (data) => `
          <div>
              ${
                data.per_celular
                  ? `<div><i class="bx bx-phone"></i> ${data.per_celular}</div>`
                  : ""
              }
              ${
                data.per_email
                  ? `<div><i class="bx bx-envelope"></i> ${data.per_email}</div>`
                  : ""
              }
              ${
                data.per_direcc
                  ? `<div class="small text-muted"><i class="bx bx-map"></i> ${data.per_direcc}</div>`
                  : ""
              }
          </div>`,
        },
        {
          data: "per_estado",
          render: (estado, type, row) => `<div class="d-flex gap-1">
          <span class="badge bg-${estado == 1 ? "success" : "danger"}">
              ${estado == 1 ? "Activo" : "Inactivo"}
          </span>
          ${
            row.tiene_usuario
              ? '<span class="badge bg-label-info">Usuario</span>'
              : ""
          }</div>`,
        },
        {
          data: null,
          className: "text-center",
          render: (data) => {
            let buttons = '<div class="btn-group">';

            if (this.permisos?.["ruta.personal"]?.["view"]) {
              buttons += `
              <button class="btn btn-sm btn-info btn-view" data-id="${data.idpersona}">
                  <i class="bx bx-show"></i>
              </button>`;
            }

            if (this.permisos?.["ruta.personal"]?.["update"]) {
              buttons += `
              <button class="btn btn-sm btn-primary btn-edit" data-id="${data.idpersona}">
                  <i class="bx bx-edit-alt"></i>
              </button>`;
            }

            if (
              this.permisos?.["ruta.personal"]?.["delete"] &&
              !data.tiene_usuario
            ) {
              buttons += `
              <button class="btn btn-sm btn-danger btn-delete" data-id="${data.idpersona}">
                  <i class="bx bx-trash"></i>
              </button>`;
            }

            return buttons + "</div>";
          },
        },
      ],
    });
  }

  initEvents() {
    $(".btn-nuevo").on("click", () => {
      this.currentId = null;
      this.form[0].reset();
      this.resetPreview();
      this.modal.modal("show");
    });

    this.form.on("submit", (e) => {
      e.preventDefault();
      this.save();
    });

    $("#foto").on("change", (e) => this.previewImage(e));

    $("#buscarDNI").on("click", () => {
      const dni = $("#dni").val();
      if (dni) this.searchByDNI(dni);
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

    $("#btnFiltrar").on("click", () => this.tabla.ajax.reload());

    $("#btnLimpiar").on("click", () => {
      $("#filtro_estado").val("1");
      $("#filtro_search").val("");
      this.tabla.ajax.reload();
    });
  }

  previewImage(e) {
    const file = e.target.files[0];
    if (file) {
      const reader = new FileReader();
      reader.onload = (e) => {
        $("#previewContainer").removeClass("d-none");
        $("#imgPreview").attr("src", e.target.result);
      };
      reader.readAsDataURL(file);
    } else {
      this.resetPreview();
    }
  }

  resetPreview() {
    $("#previewContainer").addClass("d-none");
    $("#imgPreview").attr("src", "");
  }

  async searchByDNI(dni) {
    try {
      const response = await fetch(`${base_url}admin/doc/dni/${dni}`);
      const data = await response.json();

      if (data.success) {
        $("#nombre").val(data.data.nombre_completo);
      } else {
        Swal.fire("Error", "No se encontró información del DNI", "error");
      }
    } catch (error) {
      console.error(error);
      Swal.fire("Error", "Error al consultar el DNI", "error");
    }
  }

  async save() {
    try {
      const formData = new FormData(this.form[0]);
      const url = this.currentId
        ? `${base_url}admin/personas/update/${this.currentId}`
        : `${base_url}admin/personas/save`;

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
      Swal.fire("Error", "Error al guardar el personal", "error");
    }
  }

  async edit(id) {
    try {
      const response = await fetch(`${base_url}admin/personas/search/${id}`);
      const data = await response.json();

      if (data.success) {
        this.currentId = id;
        const personal = data.personal;

        $("#dni").val(personal.dni);
        $("#nombre").val(personal.nombre);
        $("#celular").val(personal.celular);
        $("#email").val(personal.email);
        $("#direccion").val(personal.direccion);

        if (personal.foto) {
          $("#previewContainer").removeClass("d-none");
          $("#imgPreview").attr("src", `/uploads/personal/${personal.foto}`);
        } else {
          this.resetPreview();
        }

        this.modal.modal("show");
      } else {
        Swal.fire("Error", "Personal no encontrado", "error");
      }
    } catch (error) {
      console.error(error);
      Swal.fire("Error", "Error al cargar el personal", "error");
    }
  }

  async view(id) {
    try {
      const response = await fetch(`${base_url}admin/personas/search/${id}`);
      const data = await response.json();

      if (data.success) {
        const personal = data.personal;

        $("#view_dni").text(personal.dni);
        $("#view_nombre").text(personal.nombre);
        $("#view_celular").text(personal.celular || "No registrado");
        $("#view_email").text(personal.email || "No registrado");
        $("#view_direccion").text(personal.direccion || "No registrado");
        $("#view_foto").attr(
          "src",
          personal.foto
            ? `/uploads/personal/${personal.foto}`
            : "/img/no-photo.jpg"
        );

        this.modalView.modal("show");
      } else {
        Swal.fire("Error", "Personal no encontrado", "error");
      }
    } catch (error) {
      console.error(error);
      Swal.fire("Error", "Error al cargar el personal", "error");
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
        const response = await fetch(`${base_url}admin/personas/delete/${id}`, {
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
      Swal.fire("Error", "Error al eliminar el personal", "error");
    }
  }
}

document.addEventListener("DOMContentLoaded", () => new Personal());
