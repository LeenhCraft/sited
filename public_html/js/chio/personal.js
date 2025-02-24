class Personal {
  constructor() {
    this.permisos = permisos;
    this.personal = [];
    this.tabla = null;
    this.modal = $("#mdlPersonal");
    this.modalView = $("#mdlVerPersonal");
    this.modalPDF = $("#mdlPDF");
    this.form = $("#formPersonal");
    this.btnNuevo = $(".btn-nuevo");
    this.btnBuscarDocumento = $("#buscarDocumento");
    this.btnGuardar = $(".btn-save");
    this.btnCancelar = $(".btn-cancel");
    this.btnPrint = $(".btn-print");
    this.btnPrintPDF = $(".btn-print-pdf");
    this.btnFiltrar = $("#btnFiltrar");
    this.btnLimpiar = $("#btnLimpiar");
    this.crearUsuario = $("#crear_usuario");
    this.currentId = null;
    this.initTable();
    this.initSelect2();
    this.initEvents();
  }

  async initTable() {
    this.tabla = $("#tbl").DataTable({
      language: {
        url: base_url + "js/dataTable.Spanish.json",
      },
      ajax: {
        url: base_url + "admin/personal",
        method: "POST",
        data: (d) => ({
          ...d,
          fecha_inicio: $("#fecha_inicio").val(),
          fecha_fin: $("#fecha_fin").val(),
          filtro_sexo: $("#filtro_sexo").val(),
          filtro_estado: $("#filtro_estado").val(),
          filtro_especialidad: $("#filtro_especialidad").val(),
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
                                <div class="mb-1">
                                    <span class="badge bg-primary">${
                                      row.especialidad
                                    }</span>
                                </div>
                                <div class="text-muted small">
                                    Tel: ${row.celular}
                                </div>
                                <div class="mt-1">
                                    ${this.getStatusBadge(row.eliminado)}
                                </div>
                                <div class="text-muted small mt-1">
                                    Registro: ${this.formatDate(
                                      row.fecha_registro
                                    )}
                                </div>
                                ${
                                  row.eliminado
                                    ? `<div class="text-muted small">
                                            Eliminado: ${this.formatDate(
                                              row.fecha_eliminacion
                                            )}
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

            // Botón de ver
            if (this.permisos?.["ruta.medicos"]?.["view"]) {
              buttons += `
                                <button type="button" class="btn btn-sm btn-info btn-view" 
                                        data-id="${row.idpersonal}" title="Ver detalles">
                                    <i class='bx bx-search-alt-2'></i>
                                </button>
                            `;
            }

            if (!row.eliminado) {
              // Botón de editar
              if (this.permisos?.["ruta.medicos"]?.["update"]) {
                buttons += `
                                    <button type="button" class="btn btn-sm btn-primary btn-edit" 
                                            data-id="${row.idpersonal}" title="Editar">
                                        <i class="bx bx-pencil"></i>
                                    </button>
                                `;
              }

              // Botón de eliminar
              if (this.permisos?.["ruta.medicos"]?.["delete"]) {
                buttons += `
                                    <button type="button" class="btn btn-sm btn-danger btn-delete" 
                                            data-id="${row.idpersonal}" title="Eliminar">
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

  initSelect2() {
    $(".select2-ajax").select2({
      // theme: "bootstrap-5",
      width: "100%",
      ajax: {
        url: function () {
          return $(this).data("url");
        },
        method: "POST",
        dataType: "json",
        delay: 250,
        data: function (params) {
          return {
            search: params.term,
          };
        },
        processResults: function (data) {
          return data;
        },
        cache: true,
      },
      minimumInputLength: 0,
      placeholder: "Seleccione una especialidad",
      dropdownParent: $(".select2-ajax").parent(),
    });
    $("#filtro_especialidad").select2({
      placeholder: "Seleccione una especialidad",
      ajax: {
        url: function () {
          return $(this).data("url");
        },
        method: "POST",
        dataType: "json",
        delay: 250,
        data: function (params) {
          return {
            search: params.term,
          };
        },
        processResults: function (data) {
          return data;
        },
        cache: true,
      },
    });
  }

  initEvents() {
    this.btnNuevo.on("click", () => {
      this.resetForm();
      this.modal.modal("show");
      this.modal.find(".modal-title").text("Nuevo Personal Médico");
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

    this.btnPrint.on("click", () => this.printPersonal());

    this.btnPrintPDF.on("click", () => {
      const iframe = document.getElementById("pdfViewer");
      iframe.contentWindow.print();
    });

    this.btnBuscarDocumento.on("click", () => {
      const dni = $("#documento").val();
      if (dni) this.searchByDNI(dni);
    });

    this.crearUsuario.on("change", function () {
      if (!$(this).is(":checked")) {
        $(".crear-usuario").addClass("d-none");
      } else {
        $(".crear-usuario").removeClass("d-none");
        $("#usuario").focus();
      }
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
      } else {
        Swal.fire("Error", "No se encontró información del DNI", "error");
      }
    } catch (error) {
      console.error(error);
      Swal.fire("Error", "Error al consultar el DNI", "error");
    } finally {
      this.hideLoading();
    }
  }

  limpiarFiltros() {
    $("#fecha_inicio").val("");
    $("#fecha_fin").val("");
    $("#filtro_sexo").val("");
    $("#filtro_estado").val("activos");
    $("#filtro_especialidad").val("").trigger("change");
    $("#filtro_search").val("");
    this.reloadTable();
  }

  async save() {
    try {
      const formData = new FormData(this.form[0]);
      const url = this.currentId
        ? `${base_url}admin/personal/update/${this.currentId}`
        : `${base_url}admin/personal/save`;

      this.showLoading();
      const response = await fetch(url, {
        method: "POST",
        body: formData,
      });

      const data = await response.json();

      if (data.status) {
        // Si es un nuevo registro y existe una contraseña, mostrarla al usuario
        if (!this.currentId && data.password) {
          Swal.fire({
            title: "Usuario registrado correctamente",
            html: `
              <div class="text-left">
                <p>Personal médico registrado con éxito.</p>
                <p class="mt-2"><strong>Credenciales de acceso:</strong></p>
                <p><strong>Usuario:</strong> ${formData.get("usuario")}</p>
                <p><strong>Contraseña:</strong> ${data.password}</p>
                <p class="mt-2 text-warning">
                  <i class="bx bx-error-circle"></i> Guarde esta información. No se volverá a mostrar.
                </p>
              </div>
            `,
            icon: "success",
            confirmButtonText: "Entendido",
          });
        } else {
          Swal.fire("Éxito", data.message, "success");
        }
        this.modal.modal("hide");
        this.tabla.ajax.reload();
      } else {
        Swal.fire("Atención", data.message, "info");
      }
    } catch (error) {
      console.error(error);
      Swal.fire("Error", "Error al guardar el personal médico", "error");
    } finally {
      this.hideLoading();
    }
  }

  async edit(id) {
    try {
      this.showLoading();
      const response = await fetch(`${base_url}admin/personal/search/${id}`);
      const data = await response.json();

      if (data.success) {
        this.currentId = id;
        this.fillForm(data.personal);
        // Para el select2 con AJAX (especialidad)
        if (data.personal.especialidad && data.personal.idespecialidad) {
          // Crear una nueva opción con los valores recibidos
          const newOption = new Option(
            data.personal.especialidad, // texto a mostrar
            data.personal.idespecialidad, // valor
            true, // seleccionada
            true // ya existente
          );

          // Agregar la opción al select y seleccionarla
          $("#especialidad").empty().append(newOption).trigger("change");
        }

        // si esta declarado el usuario, mostrar el campo de contraseña
        if (data.personal.usuario) {
          $(".crear-usuario").removeClass("d-none");
          $("#crear_usuario").prop("checked", true);
        }

        this.modal.modal("show");
      } else {
        Swal.fire("Error", "No se encontró el personal médico", "error");
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
        const response = await fetch(`${base_url}admin/personal/delete/${id}`, {
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
      Swal.fire("Error", "Error al eliminar el personal médico", "error");
    } finally {
      this.hideLoading();
    }
  }

  async view(id) {
    try {
      this.showLoading();
      const response = await fetch(`${base_url}admin/personal/search/${id}`);
      const data = await response.json();

      if (data.success) {
        this.fillViewModal(data.personal);
        this.modalView.data("currentId", id);
        this.modalView.modal("show");
      } else {
        Swal.fire("Error", "No se encontró el personal médico", "error");
      }
    } catch (error) {
      console.error(error);
      Swal.fire("Error", "Error al cargar los datos", "error");
    } finally {
      this.hideLoading();
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
    $("#view_especialidad").text(data.especialidad);
    $("#view_celular").text(data.celular);
    $("#view_edad").text(data.edad + " años");
    $("#view_sexo").text(data.sexo === "M" ? "Masculino" : "Femenino");
    $("#view_direccion").text(data.direccion);
  }

  printPersonal() {
    try {
      const currentId = this.modalView.data("currentId");
      if (!currentId) {
        Swal.fire(
          "Error",
          "No se pudo identificar al personal médico",
          "error"
        );
        return;
      }

      this.modalPDF.modal("show");
      const iframe = document.getElementById("pdfViewer");
      iframe.src = `${base_url}admin/personal/pdf/${currentId}`;
    } catch (error) {
      console.error(error);
      Swal.fire("Error", "Error al generar el PDF", "error");
    }
  }

  resetForm() {
    this.form[0].reset();
    this.currentId = null;
    this.form.find("input, select, textarea").prop("disabled", false);
    $(".crear-usuario").addClass("d-none");
    this.btnGuardar.show();
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

// Inicialización de la clase cuando el DOM esté listo
document.addEventListener("DOMContentLoaded", function () {
  const personal = new Personal();
});
