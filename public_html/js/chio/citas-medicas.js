"use strict";

document.addEventListener("DOMContentLoaded", function () {
  // Módulo principal utilizando patrón IIFE
  const CitasModule = (() => {
    // Variables privadas del módulo
    let tablaCitas;
    let citaId = 0;
    let fechaPickr = null;
    let horaPickr = null;

    // URLs para las peticiones AJAX
    const URL = {
      SAVE: "/admin/citas/save",
      UPDATE: "/admin/citas/update",
      DELETE: "/admin/citas/delete",
      SEARCH: "/admin/citas/search",
      GET_ESPECIALIDADES: "/admin/citas/getEspecialidades",
      GET_ESTADOS: "/admin/citas/getEstados",
      GET_MEDICOS_POR_ESPECIALIDAD: "/admin/citas/getMedicosPorEspecialidad",
      GET_HORARIOS_DISPONIBLES: "/admin/citas/getHorariosDisponibles",
      GET_CITA: "/admin/citas/getCita",
      GET_DISPONIBILIDAD_HORARIOS: "/admin/citas/getDisponibilidadHorarios",
      GET_PROXIMAS_CITAS_DISPONIBLES:
        "/admin/citas/getProximasCitasDisponibles",
      EXPORT_PDF: "/admin/citas/exportPdf",
      SEARCH_PACIENTES: "/admin/citas/searchPacientes",
      SEARCH_MEDICOS: "/admin/citas/searchMedicos",
    };

    /**
     * Formatea un objeto paciente para mostrar en el Select2
     * @param {Object} paciente - Objeto con los datos del paciente
     * @returns {string|Object} Markup HTML o el objeto
     */
    const formatPaciente = (paciente) => {
      if (paciente.loading) return paciente.text;
      if (!paciente.id) return paciente;

      return $(`
            <div class="select2-result-paciente">
                <div class="select2-result-paciente__title">
                    ${paciente.nombre} (${paciente.dni})
                </div>
                <div class="select2-result-paciente__meta">
                    ${paciente.edad} años - ${paciente.sexo}
                </div>
            </div>
        `);
    };

    /**
     * Formatea un objeto paciente seleccionado
     * @param {Object} paciente - Objeto con los datos del paciente seleccionado
     * @returns {string} Texto a mostrar
     */
    const formatPacienteSelection = (paciente) => {
      return paciente.id
        ? `${paciente.nombre} (${paciente.dni})`
        : paciente.text;
    };

    /**
     * Formatea un objeto médico para mostrar en el Select2
     * @param {Object} medico - Objeto con los datos del médico
     * @returns {string|Object} Markup HTML o el objeto
     */
    const formatMedico = (medico) => {
      if (medico.loading) return medico.text;
      if (!medico.id) return medico;

      return $(`
            <div class="select2-result-medico">
                <div class="select2-result-medico__title">
                    ${medico.nombre} (${medico.dni})
                </div>
                <div class="select2-result-medico__meta">
                    ${medico.especialidad}
                </div>
            </div>
        `);
    };

    /**
     * Formatea un objeto médico seleccionado
     * @param {Object} medico - Objeto con los datos del médico seleccionado
     * @returns {string} Texto a mostrar
     */
    const formatMedicoSelection = (medico) => {
      return medico.id ? `${medico.nombre} (${medico.dni})` : medico.text;
    };

    /**
     * Formatea una fecha en formato ISO a dd/mm/yyyy
     * @param {string} dateString - Fecha en formato ISO
     * @returns {string} Fecha formateada
     */
    const formatDate = (dateString) => {
      if (!dateString) return "";
      return moment(dateString).format("DD/MM/YYYY");
    };

    /**
     * Formatea el estado de la cita con un badge de color
     * @param {Object} estado - Objeto con datos del estado
     * @returns {string} HTML del badge
     */
    const formatEstadoCita = (estado) => {
      let color = "secondary";
      switch (estado.toLowerCase()) {
        case "programada":
        case "confirmada":
          color = "primary";
          break;
        case "completada":
          color = "success";
          break;
        case "cancelada":
          color = "danger";
          break;
        case "reprogramada":
          color = "warning";
          break;
        case "en espera":
          color = "info";
          break;
        case "no asistió":
        case "bloqueada":
          color = "dark";
          break;
        case "en proceso":
          color = "primary";
          break;
        case "emergencia":
          color = "danger";
          break;
      }

      return `<span class="badge bg-label-${color}">${estado}</span>`;
    };

    /**
     * Trunca un texto a la longitud especificada
     * @param {string} text - Texto a truncar
     * @param {number} length - Longitud máxima
     * @returns {string} Texto truncado
     */
    const truncateText = (text, length) => {
      if (!text) return "";
      return text.length > length ? text.substring(0, length) + "..." : text;
    };

    /**
     * Renderiza los botones de acciones para cada fila
     * @param {Object} data - Datos de la fila
     * @returns {string} HTML de los botones
     */
    const renderAcciones = (data) => {
      const idCita = data.idcita;

      return `
            <div class="dropdown">
                <button class="btn btn-sm btn-primary dropdown-toggle" type="button" id="dropdownMenuButton${idCita}" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Acciones
                </button>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton${idCita}">
                    <a class="dropdown-item btn-ver" href="javascript:void(0);" data-id="${idCita}">
                        <i class="fas fa-eye mr-2"></i> Ver
                    </a>
                    <a class="dropdown-item btn-editar" href="javascript:void(0);" data-id="${idCita}">
                        <i class="fas fa-edit mr-2"></i> Editar
                    </a>
                    <a class="dropdown-item btn-exportar-individual" href="javascript:void(0);" data-id="${idCita}">
                        <i class="fas fa-file-pdf mr-2"></i> Exportar PDF
                    </a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item btn-eliminar text-danger" href="javascript:void(0);" data-id="${idCita}">
                        <i class="fas fa-trash-alt mr-2"></i> Eliminar
                    </a>
                </div>
            </div>
        `;
    };

    /**
     * Muestra un mensaje de error con SweetAlert2
     * @param {string} message - Mensaje de error
     */
    const showErrorMessage = (message) => {
      Swal.fire({
        icon: "error",
        title: "Error",
        text: message,
        confirmButtonText: "Aceptar",
      });
    };

    /**
     * Muestra un mensaje de éxito con SweetAlert2
     * @param {string} message - Mensaje de éxito
     */
    const showSuccessMessage = (message) => {
      Swal.fire({
        icon: "success",
        title: "Éxito",
        text: message,
        confirmButtonText: "Aceptar",
      });
    };

    /**
     * Muestra un mensaje de confirmación con SweetAlert2
     * @param {string} message - Mensaje de confirmación
     * @param {Function} callback - Función a ejecutar si se confirma
     */
    const showConfirmMessage = (message, callback) => {
      Swal.fire({
        icon: "question",
        title: "Confirmación",
        text: message,
        showCancelButton: true,
        confirmButtonText: "Sí, continuar",
        cancelButtonText: "Cancelar",
      }).then((result) => {
        if (result.isConfirmed && typeof callback === "function") {
          callback();
        }
      });
    };

    /**
     * Popula un select con opciones desde un array
     * @param {string} selectId - ID del select
     * @param {Array} data - Array de objetos para las opciones
     */
    const populateSelect = (selectId, data) => {
      const select = $(selectId);
      const currentValue = select.val();

      // Mantener la opción por defecto
      const defaultOption = select.find("option:first");
      select.empty().append(defaultOption);

      if (data && data.length > 0 && selectId === "#especialidadModal") {
        // Agregar la opción "Ver todos los médicos" solo si hay registros
        const verTodosOption = $("<option></option>")
          .attr("value", "0") // Valor especial para "Ver todos los médicos"
          .text("Ver todos los médicos");
        select.append(verTodosOption);
      }

      // Agregar las nuevas opciones
      $.each(data, function (index, item) {
        const option = $("<option></option>")
          .attr("value", item.id)
          .text(item.nombre);

        select.append(option);
      });

      // Restaurar el valor seleccionado si existía
      if (currentValue) {
        select.val(currentValue);
      }

      const config = {
        placeholder: "Seleccione una opción",
        allowClear: true,
      };

      if (selectId === "#especialidadModal") {
        config.dropdownParent = $("#modalCita");
      }

      if (selectId === "#estadoCitaModal") {
        config.dropdownParent = $("#modalCita");
      }
      // Inicializar o actualizar el select2 (si lo estás usando)
      select.select2({
        ...config,
      });
    };

    /**
     * Formatea una fecha para mostrar como Y-m-d (compatible con input type="date")
     * utilizando Moment.js.
     */
    const formatDateForInput = (date) => {
      // Usa Moment.js para formatear la fecha
      return moment(date).format("YYYY-MM-DD");
    };

    /**
     * Inicializa los componentes Select2
     */
    const initSelect2Components = () => {
      // Inicializar los selects con Select2
      $(".select2-pacientes").select2({
        placeholder: "Buscar paciente...",
        allowClear: true,
        minimumInputLength: 2,
        language: {
          inputTooShort: function () {
            return "Por favor ingrese al menos 2 caracteres";
          },
        },
        ajax: {
          url: URL.SEARCH_PACIENTES,
          dataType: "json",
          delay: 250,
          data: function (params) {
            return {
              q: params.term,
              page: params.page || 1,
            };
          },
          processResults: function (data, params) {
            params.page = params.page || 1;
            return {
              results: data.items,
              pagination: {
                more: params.page * 30 < data.total_count,
              },
            };
          },
          cache: true,
        },
        escapeMarkup: function (markup) {
          return markup;
        },
        templateResult: formatPaciente,
        templateSelection: formatPacienteSelection,
      });

      $(".select2-medicos").select2({
        placeholder: "Buscar médico...",
        allowClear: true,
        minimumInputLength: 2,
        language: {
          inputTooShort: function () {
            return "Por favor ingrese al menos 2 caracteres";
          },
        },
        ajax: {
          url: URL.SEARCH_MEDICOS,
          dataType: "json",
          delay: 250,
          data: function (params) {
            return {
              q: params.term,
              especialidad: $("#especialidad").val(),
              page: params.page || 1,
            };
          },
          processResults: function (data, params) {
            params.page = params.page || 1;
            return {
              results: data.items,
              pagination: {
                more: params.page * 30 < data.total_count,
              },
            };
          },
          cache: true,
        },
        escapeMarkup: function (markup) {
          return markup;
        },
        templateResult: formatMedico,
        templateSelection: formatMedicoSelection,
      });

      // Inicializar Select2 para el modal
      $(".select2-pacientes-modal").select2({
        dropdownParent: $("#modalCita"),
        placeholder: "Buscar paciente...",
        allowClear: true,
        minimumInputLength: 2,
        language: {
          inputTooShort: function () {
            return "Por favor ingrese al menos 2 caracteres";
          },
        },
        ajax: {
          url: URL.SEARCH_PACIENTES,
          dataType: "json",
          delay: 250,
          data: function (params) {
            return {
              q: params.term,
              page: params.page || 1,
            };
          },
          processResults: function (data, params) {
            params.page = params.page || 1;
            return {
              results: data.items,
              pagination: {
                more: params.page * 30 < data.total_count,
              },
            };
          },
          cache: true,
        },
        escapeMarkup: function (markup) {
          return markup;
        },
        templateResult: formatPaciente,
        templateSelection: formatPacienteSelection,
      });
    };

    /**
     * Inicializa la tabla DataTable
     */
    const initDataTable = () => {
      tablaCitas = $("#tablaCitas").DataTable({
        responsive: true,
        processing: true,
        serverSide: true,
        dom:
          "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
          "<'row'<'col-sm-12'tr>>" +
          "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
        ajax: {
          url: URL.SEARCH,
          type: "GET",
          data: function (d) {
            d.fechaInicio = $("#fechaInicio").val();
            d.fechaFin = $("#fechaFin").val();
            d.especialidad = $("#especialidad").val();
            d.paciente = $("#paciente").val();
            d.estadoCita = $("#estadoCita").val();
            d.medico = $("#medico").val();
          },
        },
        columns: [
          { data: "idcita" },
          { data: "paciente" },
          { data: "medico" },
          { data: "especialidad" },
          {
            data: "fecha",
            render: function (data) {
              return formatDate(data);
            },
          },
          { data: "hora" },
          {
            data: "estado",
            render: function (data) {
              return formatEstadoCita(data);
            },
          },
          {
            data: "observaciones",
            render: function (data) {
              return truncateText(data, 30);
            },
          },
          {
            data: null,
            orderable: false,
            className: "text-center",
            render: function (data) {
              return renderAcciones(data);
            },
          },
        ],
        order: [],
        scrollX: true,
        language: {
          url: "/js/dataTable.Spanish.json",
        },
      });
    };

    /**
     * Abre el modal para crear o editar una cita
     * @param {number} id - ID de la cita a editar, 0 para nueva cita
     */
    const openCitaModal = (id = 0) => {
      citaId = id;

      // Resetear el formulario
      $("#formCita")[0].reset();
      $("#idCita").val(id);

      // Reiniciar los select2
      $(".select2-pacientes-modal").val(null).trigger("change");

      // Cambiar el título según si es nueva o edición
      const title = id === 0 ? "Nueva Cita Médica" : "Editar Cita Médica";
      $("#modalCitaLabel").text(title);

      $("#especialidadModal").val("").trigger("change");
      $("#fechaModal").val("");
      $("#horariosDisponibles").empty();
      $("#estadoCitaModal").val("1").trigger("change");

      if (id > 0) {
        // Cargar datos de la cita
        loadCitaData(id);
      }

      // Mostrar el modal
      $("#modalCita").modal("show");
    };

    /**
     * Carga los datos de una cita para edición
     * @param {number} id - ID de la cita
     */
    const loadCitaData = (id) => {
      $.ajax({
        url: URL.GET_CITA,
        type: "GET",
        data: { id: id },
        beforeSend: function () {
          // Mostrar spinner o bloquear interfaz
          Swal.fire({
            title: "Procesando respuestas",
            html: "Por favor espere...",
            allowOutsideClick: false,
            didOpen: () => {
              Swal.showLoading();
            },
          });
        },
        success: function (response) {
          if (response.success) {
            const cita = response.data;

            // Establecer datos en el formulario
            $("#idCita").val(cita.idcita);

            // Seleccionar paciente y cargar datos
            const $pacienteSelect = $("#pacienteModal");
            const pacienteData = {
              id: cita.paciente.idpaciente,
              text: cita.paciente.nombre,
              nombre: cita.paciente.nombre,
              dni: cita.paciente.dni || "",
            };
            $pacienteSelect.empty();
            const select2Data = $pacienteSelect.data("select2");
            if (select2Data) {
              select2Data.dataAdapter.select(pacienteData);
            }

            // Seleccionar especialidad y cargar médicos
            $("#especialidadModal")
              .val(cita.medico.idespecialidad)
              .trigger("change");

            // Una vez cargados los médicos, seleccionar el médico
            setTimeout(function () {
              $("#medicoModal").val(cita.medico.idpersonal).trigger("change");
              // Establecer fecha con flatpickr
              setTimeout(function () {
                if (fechaPickr) {
                  fechaPickr.setDate(cita.fecha);
                  $("#fechaModal").trigger("change");
                }
                // Cargar horas disponibles y seleccionar la hora existente
                setTimeout(function () {
                  const horaSeleccionada = cita.hora;
                  $("#horariosDisponibles")
                    .val(horaSeleccionada)
                    .trigger("change");
                }, 500);
              }, 500);
            }, 500);

            // Establecer estado y observaciones
            $("#estadoCitaModal").val(cita.id_estado_cita);
            $("#observacionesModal").val(cita.observaciones);
          } else {
            showErrorMessage("Error al cargar los datos de la cita");
            $("#modalCita").modal("hide");
          }
        },
        error: function () {
          showErrorMessage("Error al comunicarse con el servidor");
          $("#modalCita").modal("hide");
        },
        complete: function () {
          // Ocultar spinner o desbloquear interfaz
          Swal.close();
        },
      });
    };

    /**
     * Carga los médicos disponibles según la especialidad seleccionada
     */
    const cargarMedicosPorEspecialidad = () => {
      const especialidadId = $("#especialidadModal").val();

      if (!especialidadId) {
        $("#medicoModal")
          .empty()
          .append('<option value="">Seleccione un médico</option>');
        return;
      }

      $.ajax({
        url: URL.GET_MEDICOS_POR_ESPECIALIDAD,
        type: "GET",
        data: { idespecialidad: especialidadId },
        success: function (response) {
          if (response.success) {
            // Limpiar select de médicos
            $("#medicoModal")
              .empty()
              .append('<option value="">Seleccione un médico</option>');

            // Agregar médicos al select
            $.each(response.data, function (index, medico) {
              $("#medicoModal").append(
                $("<option></option>")
                  .attr("value", medico.idpersonal)
                  .text(medico.nombre)
              );
            });

            // Inicializar o actualizar el select2
            $("#medicoModal").select2({
              placeholder: "Seleccione un médico",
              allowClear: true,
              dropdownParent: $("#modalCita"),
            });
          }
        },
        error: function () {
          showErrorMessage("Error al cargar los médicos");
        },
      });
    };

    /**
     * Carga los horarios disponibles según el médico y la fecha seleccionada
     */
    const cargarHorariosDisponibles = () => {
      const medicoId = $("#medicoModal").val();
      const fecha = $("#fechaModal").val();
      const citaId = $("#idCita").val();

      if (!medicoId || !fecha) {
        $("#horariosDisponibles")
          .empty()
          .append('<option value="">Seleccione un horario</option>');
        return;
      }

      $.ajax({
        url: URL.GET_HORARIOS_DISPONIBLES,
        type: "GET",
        data: {
          idpersonal: medicoId,
          fecha: fecha,
          idcita: citaId,
        },
        success: function (response) {
          if (response.success) {
            // Limpiar select de horarios
            $("#horariosDisponibles")
              .empty()
              .append('<option value="">Seleccione un horario</option>');

            if (response.data.length === 0) {
              $("#horariosDisponibles").append(
                $("<option></option>")
                  .attr("disabled", true)
                  .text("No hay horarios disponibles para esta fecha")
              );
              return;
            }

            // Agregar horarios al select
            $.each(response.data, function (index, horario) {
              $("#horariosDisponibles").append(
                $("<option></option>")
                  .attr("value", horario.hora)
                  .text(formatTime(horario.hora))
              );
            });
          }
        },
        error: function () {
          showErrorMessage("Error al cargar los horarios disponibles");
        },
      });
    };

    /**
     * Formatea una hora en formato 24h a formato 12h AM/PM
     * @param {string} time - Hora en formato HH:MM:SS
     * @returns {string} Hora formateada
     */
    const formatTime = (time) => {
      if (!time) return "";
      const timeParts = time.split(":");
      let hours = parseInt(timeParts[0]);
      const minutes = timeParts[1];
      const ampm = hours >= 12 ? "PM" : "AM";

      hours = hours % 12;
      hours = hours ? hours : 12; // la hora '0' debe ser '12'

      return hours + ":" + minutes + " " + ampm;
    };

    /**
     * Guarda una cita (nueva o edición)
     */
    const saveCita = () => {
      // Validar formulario
      if (!validateCitaForm()) {
        return;
      }

      const formData = new FormData(document.getElementById("formCita"));
      const citaId = $("#idCita").val();
      const url = citaId === "0" ? URL.SAVE : URL.UPDATE;

      $.ajax({
        url: url,
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        beforeSend: function () {
          // Mostrar spinner o bloquear interfaz
          $("#btnGuardarCita")
            .prop("disabled", true)
            .html('<i class="fas fa-spinner fa-spin me-2"></i> Guardando...');
        },
        success: function (response) {
          if (response.success) {
            $("#modalCita").modal("hide");
            showSuccessMessage(
              response.message || "Cita guardada exitosamente"
            );
            tablaCitas.ajax.reload();
          } else {
            showErrorMessage(response.message || "Error al guardar la cita");
          }
        },
        error: function (xhr) {
          let errorMsg = "Error al comunicarse con el servidor";
          if (xhr.responseJSON && xhr.responseJSON.message) {
            errorMsg = xhr.responseJSON.message;
          }
          showErrorMessage(errorMsg);
        },
        complete: function () {
          // Ocultar spinner o desbloquear interfaz
          $("#btnGuardarCita")
            .prop("disabled", false)
            .html('<i class="fas fa-save me-2"></i> Guardar');
        },
      });
    };

    /**
     * Valida el formulario de cita
     * @returns {boolean} True si es válido, False si no
     */
    const validateCitaForm = () => {
      const paciente = $("#pacienteModal").val();
      const medico = $("#medicoModal").val();
      const fecha = $("#fechaModal").val();
      const hora = $("#horariosDisponibles").val();
      const estado = $("#estadoCitaModal").val();

      if (!paciente) {
        showErrorMessage("Debe seleccionar un paciente");
        return false;
      }

      if (!medico) {
        showErrorMessage("Debe seleccionar un médico");
        return false;
      }

      if (!fecha) {
        showErrorMessage("Debe seleccionar una fecha");
        return false;
      }

      if (!hora) {
        showErrorMessage("Debe seleccionar un horario disponible");
        return false;
      }

      if (!estado) {
        showErrorMessage("Debe seleccionar un estado para la cita");
        return false;
      }

      return true;
    };

    /**
     * Prepara el modal de confirmación para eliminar una cita
     * @param {number} id - ID de la cita a eliminar
     */
    const confirmDeleteCita = (id) => {
      $("#idCitaEliminar").val(id);
      $("#modalConfirmDelete").modal("show");
    };

    /**
     * Elimina una cita
     */
    const deleteCita = () => {
      const id = $("#idCitaEliminar").val();

      $.ajax({
        url: URL.DELETE,
        type: "POST",
        data: { idcita: id },
        beforeSend: function () {
          $("#btnConfirmDelete")
            .prop("disabled", true)
            .html('<i class="fas fa-spinner fa-spin mr-2"></i> Eliminando...');
        },
        success: function (response) {
          $("#modalConfirmDelete").modal("hide");

          if (response.success) {
            showSuccessMessage(
              response.message || "Cita eliminada exitosamente"
            );
            tablaCitas.ajax.reload();
          } else {
            showErrorMessage(response.message || "Error al eliminar la cita");
          }
        },
        error: function () {
          $("#modalConfirmDelete").modal("hide");
          showErrorMessage("Error al comunicarse con el servidor");
        },
        complete: function () {
          $("#btnConfirmDelete")
            .prop("disabled", false)
            .html('<i class="fas fa-trash-alt mr-2"></i> Eliminar');
        },
      });
    };

    /**
     * Exporta a PDF las citas según los filtros seleccionados
     */
    const exportarPDF = () => {
      const fechaInicio = $("#fechaInicio").val();
      const fechaFin = $("#fechaFin").val();

      // Validar rango de fechas (máximo 3 meses)
      if (fechaInicio && fechaFin) {
        const inicio = new Date(fechaInicio);
        const fin = new Date(fechaFin);
        const diff = Math.ceil((fin - inicio) / (1000 * 60 * 60 * 24));

        if (diff > 90) {
          showErrorMessage("El rango de fechas no puede ser mayor a 3 meses");
          return;
        }

        if (fin < inicio) {
          showErrorMessage(
            "La fecha de fin debe ser posterior a la fecha de inicio"
          );
          return;
        }
      }

      // Construir la URL con los parámetros
      let url = URL.EXPORT_PDF + "?";
      const params = {
        fechaInicio: $("#fechaInicio").val(),
        fechaFin: $("#fechaFin").val(),
        especialidad: $("#especialidad").val(),
        paciente: $("#paciente").val(),
        estadoCita: $("#estadoCita").val(),
        medico: $("#medico").val(),
      };

      // Agregar solo parámetros con valor
      Object.keys(params).forEach((key) => {
        if (params[key]) {
          url += `${key}=${encodeURIComponent(params[key])}&`;
        }
      });

      // Eliminar el último &
      url = url.slice(0, -1);

      // Abrir en una nueva ventana
      window.open(url, "_blank");
    };

    /**
     * Exporta a PDF una cita individual
     * @param {number} id - ID de la cita a exportar
     */
    const exportarCitaIndividual = (id) => {
      const url = `${URL.EXPORT_PDF}?idcita=${id}`;
      window.open(url, "_blank");
    };

    /**
     * Carga y muestra las horas disponibles para la fecha seleccionada
     * @param {number} medicoId - ID del médico seleccionado
     * @param {string} fecha - Fecha seleccionada en formato YYYY-MM-DD
     */
    const cargarHorasDisponiblesEnFlatpickr = (medicoId, fecha) => {
      // Mostrar indicador de carga
      $("#horariosDisponibles").html(
        '<option value="">Cargando horarios disponibles...</option>'
      );

      if (!medicoId || !fecha) {
        $("#horariosDisponibles").html(
          '<option value="">Seleccione un médico y una fecha primero</option>'
        );
        $("#horaInput").val("");
        return;
      }

      // Obtener horarios disponibles del servidor
      $.ajax({
        url: URL.GET_HORARIOS_DISPONIBLES,
        type: "GET",
        data: {
          idpersonal: medicoId,
          fecha: fecha,
          idcita: $("#idCita").val() || 0,
        },
        success: function (response) {
          if (response.success && response.data.length > 0) {
            // Limpiar el select antes de agregar nuevas opciones
            $("#horariosDisponibles").html(
              '<option value="">Seleccione un horario</option>'
            );

            // Crear opciones de horarios
            response.data.forEach((slot) => {
              const horaFormateada = formatTime(slot.hora);
              $("#horariosDisponibles").append(
                `<option value="${slot.hora}">${horaFormateada}</option>`
              );
            });

            // Inicializar o actualizar el select2
            $("#horariosDisponibles").select2({
              placeholder: "Seleccione un horario",
              allowClear: true,
              dropdownParent: $("#modalCita"),
            });

            // Recuperar hora previamente seleccionada (si existe)
            const horaPrevia = $("#horaInput").val();
            if (horaPrevia) {
              $("#horariosDisponibles").val(horaPrevia).trigger("change");
            }
          } else {
            $("#horariosDisponibles").html(
              '<option value="">No hay horarios disponibles para esta fecha</option>'
            );
            $("#horaInput").val("").trigger("change");
          }
        },
        error: function () {
          $("#horariosDisponibles").html(
            '<option value="">Error al cargar horarios disponibles</option>'
          );
          $("#horaInput").val("");
        },
      });
    };

    const initFechaFlatpickr = async () => {
      // Destruir instancia previa si existe
      if (fechaPickr) {
        fechaPickr.destroy();
      }

      // Obtener el médico seleccionado
      const medicoId = $("#medicoModal").val();
      if (!medicoId) {
        $("#fechaModal").val("");
        return;
      }

      // Obtener los días disponibles del médico
      $.ajax({
        url: URL.GET_DISPONIBILIDAD_HORARIOS,
        type: "GET",
        data: {
          idpersonal: medicoId,
          fechaInicio: moment().format("YYYY-MM-DD"),
          fechaFin: moment().add(2, "months").format("YYYY-MM-DD"),
        },
        success: function (response) {
          if (response.success) {
            const medico = response.data.find((m) => m.id == medicoId);
            if (medico && medico.horarios) {
              // Obtener fechas disponibles (convertir de objeto a array)
              const fechasDisponibles = Object.keys(medico.horarios);

              // Configurar flatpickr
              fechaPickr = flatpickr("#fechaModal", {
                locale: "es",
                dateFormat: "Y-m-d",
                minDate: "today",
                inline: false,
                enable: fechasDisponibles,
                onChange: function (selectedDates, dateStr) {
                  if (dateStr) {
                    // Actualizar las horas disponibles al cambiar la fecha
                    cargarHorasDisponiblesEnFlatpickr(medicoId, dateStr);
                  }
                },
              });
            } else {
              // Si no hay horarios disponibles
              fechaPickr = flatpickr("#fechaModal", {
                locale: "es",
                dateFormat: "Y-m-d",
                minDate: "today",
                inline: false,
                disable: [true], // Deshabilitar todas las fechas
                onChange: function () {
                  $("#horaContainer").html(
                    '<div class="alert alert-info">Seleccione primero una fecha válida</div>'
                  );
                },
              });

              showErrorMessage(
                "El médico seleccionado no tiene horarios disponibles"
              );
            }
          } else {
            showErrorMessage(
              response.message || "Error al cargar disponibilidad"
            );
          }
        },
        error: function () {
          showErrorMessage("Error al comunicarse con el servidor");
        },
      });
    };

    /**
     * Carga los datos iniciales (especialidades y estados)
     */
    const loadInitialData = () => {
      // Cargar especialidades
      $.ajax({
        url: URL.GET_ESPECIALIDADES,
        type: "GET",
        success: function (response) {
          if (response.success) {
            populateSelect("#especialidad", response.data);
            populateSelect("#especialidadModal", response.data);
          }
        },
        error: function () {
          showErrorMessage("Error al cargar las especialidades");
        },
      });

      // Cargar estados de cita
      $.ajax({
        url: URL.GET_ESTADOS,
        type: "GET",
        success: function (response) {
          if (response.success) {
            populateSelect("#estadoCita", response.data);
            populateSelect("#estadoCitaModal", response.data);
          }
        },
        error: function () {
          showErrorMessage("Error al cargar los estados de cita");
        },
      });
    };

    /**
     * Inicializa los eventos
     */
    const initEventListeners = () => {
      // Evento para el botón de buscar
      $("#btnBuscar").on("click", function () {
        tablaCitas.ajax.reload();
      });

      // Evento para el botón de limpiar filtros
      $("#btnLimpiar").on("click", function () {
        $("#searchForm")[0].reset();
        $(".select2-pacientes, .select2-medicos").val(null).trigger("change");
        tablaCitas.ajax.reload();
      });

      // Evento para el botón de exportar
      $("#btnExportar").on("click", function () {
        exportarPDF();
      });

      // Evento para el botón de nueva cita
      $("#btnNuevaCita").on("click", function () {
        openCitaModal();
      });

      // Evento para guardar cita
      $("#btnGuardarCita").on("click", function () {
        saveCita();
      });

      // Evento al cambiar la especialidad en el modal
      $("#especialidadModal").on("change", function () {
        cargarMedicosPorEspecialidad();
      });

      // Evento al cambiar el médico o la fecha en el modal
      $("#medicoModal, #fechaModal").on("change", async function () {
        // if ($("#medicoModal").val() && $("#fechaModal").val()) {
        // cargarHorariosDisponibles();
        // }

        await initFechaFlatpickr();

        // funcion para mostrar el horario disponible en base al medico
        cargarHorasDisponiblesEnFlatpickr(
          $("#medicoModal").val(),
          $("#fechaModal").val()
        );
      });

      // Evento para confirmar la eliminación
      $("#btnConfirmDelete").on("click", function () {
        deleteCita();
      });

      // Fecha mínima para los campos de fecha (hoy)
      const today = new Date().toISOString().split("T")[0];
      $("#fechaModal").attr("min", today);

      // Delegación de eventos para botones dinámicos en la tabla
      $(document).on("click", ".btn-ver", function () {
        const id = $(this).data("id");
        exportarCitaIndividual(id);
      });

      $(document).on("click", ".btn-editar", function () {
        const id = $(this).data("id");
        openCitaModal(id);
      });

      $(document).on("click", ".btn-exportar-individual", function () {
        const id = $(this).data("id");
        exportarCitaIndividual(id);
      });

      $(document).on("click", ".btn-eliminar", function () {
        const id = $(this).data("id");
        confirmDeleteCita(id);
      });

      // Tab de calendario
      $("#tabCalendario").on("click", function (e) {
        e.preventDefault();
        console.log("Calendario");

        $(this).tab("show");
        cargarDisponibilidadHorarios();
        cargarProximasCitasDisponibles();
      });

      $("#tabListado").on("click", function (e) {
        e.preventDefault();
        $(this).tab("show");
      });
    };

    /**
     * Renderiza las próximas citas disponibles
     */
    const renderizarProximasCitas = (data) => {
      if (data.length === 0) {
        $("#proximasCitasDisponibles").html(
          '<div class="alert alert-info">No hay próximas citas disponibles</div>'
        );
        return;
      }

      let html = `
  <div class="list-group">
      <div class="list-group-item active">
          <h6 class="mb-0">Próximas Citas Disponibles</h6>
      </div>
  `;

      data.forEach((item) => {
        const fecha = formatDateForDisplay(new Date(item.proximaCita.fecha));
        const hora = formatarHora(item.proximaCita.hora);

        html += `
      <div class="list-group-item list-group-item-action">
          <div class="d-flex w-100 justify-content-between">
              <h6 class="mb-1">${item.nombre}</h6>
              <small>${item.especialidad}</small>
          </div>
          <p class="mb-1">
              <i class="fas fa-calendar-alt mr-2"></i> ${fecha} 
              <i class="fas fa-clock ml-3 mr-2"></i> ${hora}
          </p>
          <button type="button" class="btn btn-sm btn-primary slot-horario text-primary" 
              data-medico="${item.id}"
              data-fecha="${item.proximaCita.fecha}" 
              data-hora="${item.proximaCita.hora}">
              <i class="fas fa-plus-circle me-1"></i> Agendar
          </button>
      </div>
      `;
      });

      html += `</div>`;

      $("#proximasCitasDisponibles").html(html);
    };

    /**
     * Carga las próximas citas disponibles
     */
    const cargarProximasCitasDisponibles = () => {
      const especialidadId = $("#especialidadCalendario").val();

      $.ajax({
        url: URL.GET_PROXIMAS_CITAS_DISPONIBLES,
        type: "GET",
        data: {
          idespecialidad: especialidadId,
        },
        beforeSend: function () {
          $("#proximasCitasDisponibles").html(
            '<div class="text-center p-3"><i class="fas fa-spinner fa-spin"></i> Cargando...</div>'
          );
        },
        success: function (response) {
          if (response.success) {
            renderizarProximasCitas(response.data);
          } else {
            $("#proximasCitasDisponibles").html(
              '<div class="alert alert-danger">Error al cargar próximas citas</div>'
            );
          }
        },
        error: function () {
          $("#proximasCitasDisponibles").html(
            '<div class="alert alert-danger">Error al comunicarse con el servidor</div>'
          );
        },
      });
    };

    /**
     * Formatea hora de HH:MM:SS a formato amigable (HH:MM AM/PM)
     */
    const formatarHora = (hora) => {
      const [hours, minutes] = hora.split(":");
      let h = parseInt(hours);
      const ampm = h >= 12 ? "PM" : "AM";
      h = h % 12;
      h = h ? h : 12; // La hora 0 debe ser 12
      return `${h}:${minutes} ${ampm}`;
    };

    /**
     * Formatea una fecha para mostrar como dd/mm/yyyy utilizando Moment.js.
     */
    const formatDateForDisplay = (date) => {
      // Usa Moment.js para formatear la fecha
      return moment(date).format("DD/MM/YYYY");
    };

    /**
     * Renderiza el calendario de disponibilidad
     */
    const renderizarCalendario = (data, periodo) => {
      if (data.length === 0) {
        $("#calendarioDisponibilidad").html(
          '<div class="alert alert-info">No hay horarios disponibles para los criterios seleccionados</div>'
        );
        return;
      }

      // Generar fechas del período
      const fechaInicio = new Date(periodo.inicio);
      const fechaFin = new Date(periodo.fin);
      const fechas = [];

      let currentDate = new Date(fechaInicio);
      while (currentDate <= fechaFin) {
        fechas.push(formatDateForDisplay(new Date(currentDate)));
        currentDate.setDate(currentDate.getDate() + 1);
      }

      // Construir el calendario
      let html = `
  <div class="calendario-container">
      <div class="calendario-header">
          <div class="row">
              <div class="col-12">
                  <h5 class="calendario-titulo">Calendario de Disponibilidad</h5>
                  <p class="calendario-periodo">Período: ${formatDateForDisplay(
                    fechaInicio
                  )} - ${formatDateForDisplay(fechaFin)}</p>
              </div>
          </div>
      </div>
  `;

      // Por cada médico
      data.forEach((medico) => {
        html += `
      <div class="calendario-medico mb-4">
          <div class="calendario-medico-header">
              <h6>${medico.nombre} - <span class="text-primary">${medico.especialidad}</span></h6>
          </div>
          <div class="calendario-dias">
      `;

        // Por cada fecha en el período
        let hayHorarios = false;

        fechas.forEach((fecha) => {
          const fechaYMD = fecha.split("/").reverse().join("-");
          const horarios = medico.horarios[fechaYMD] || [];

          if (horarios.length > 0) {
            hayHorarios = true;

            html += `
              <div class="calendario-dia">
                  <div class="calendario-fecha">${fecha}</div>
                  <div class="calendario-slots">
              `;

            horarios.forEach((hora) => {
              const horaFormateada = formatarHora(hora);
              html += `
                  <div class="slot-horario" data-medico="${medico.id}" data-fecha="${fechaYMD}" data-hora="${hora}">
                      ${horaFormateada}
                  </div>
                  `;
            });

            html += `
                  </div>
              </div>
              `;
          }
        });

        if (!hayHorarios) {
          html += `<div class="alert alert-info">No hay horarios disponibles para este médico en el período seleccionado</div>`;
        }

        html += `
          </div>
      </div>
      `;
      });

      html += `</div>`;

      // Insertar HTML
      $("#calendarioDisponibilidad").html(html);
    };

    /**
     * Carga la disponibilidad de horarios para el calendario
     */
    const cargarDisponibilidadHorarios = () => {
      const medicoId = $("#medicoCalendario").val();
      const especialidadId = $("#especialidadCalendario").val();
      const fechaInicio = $("#fechaInicioCalendario").val();
      const fechaFin = $("#fechaFinCalendario").val();

      // const urlParams =
      //   URL.GET_DISPONIBILIDAD_HORARIOS +
      //   `?idpersonal=${medicoId}&idespecialidad=${especialidadId}&fechaInicio=${fechaInicio}&fechaFin=${fechaFin}`;

      $.ajax({
        url: URL.GET_DISPONIBILIDAD_HORARIOS,
        type: "GET",
        data: {
          idpersonal: medicoId,
          idespecialidad: especialidadId,
          fechaInicio: fechaInicio,
          fechaFin: fechaFin,
        },
        beforeSend: function () {
          $("#calendarioDisponibilidad").html(
            '<div class="text-center p-5"><i class="fas fa-spinner fa-spin fa-3x"></i><p class="mt-3">Cargando calendario...</p></div>'
          );
        },
        success: function (response) {
          if (response.success) {
            renderizarCalendario(response.data, response.periodo);
          } else {
            showErrorMessage(
              response.message || "Error al cargar la disponibilidad"
            );
            $("#calendarioDisponibilidad").html(
              '<div class="alert alert-danger">Error al cargar el calendario de disponibilidad</div>'
            );
          }
        },
        error: function () {
          showErrorMessage("Error al comunicarse con el servidor");
          $("#calendarioDisponibilidad").html(
            '<div class="alert alert-danger">Error al comunicarse con el servidor</div>'
          );
        },
      });
    };

    /**
     * Inicializa el calendario de disponibilidad
     */
    const initCalendarioDisponibilidad = () => {
      // Configurar fecha actual para los selectores
      const hoy = moment();
      const inicioMes = hoy.clone().startOf("month");
      const finMes = hoy.clone().endOf("month");

      $("#fechaInicioCalendario").val(formatDateForInput(inicioMes));
      $("#fechaFinCalendario").val(formatDateForInput(finMes));

      // Cargar datos iniciales
      cargarDisponibilidadHorarios();
      cargarProximasCitasDisponibles();

      // Eventos para filtros
      $("#btnFiltrarCalendario").on("click", function () {
        cargarDisponibilidadHorarios();
      });

      $("#btnLimpiarFiltrosCalendario").on("click", function () {
        $("#medicoCalendario").val("");
        $("#especialidadCalendario").val("");
        $("#fechaInicioCalendario").val(formatDateForInput(inicioMes));
        $("#fechaFinCalendario").val(formatDateForInput(finMes));
        cargarDisponibilidadHorarios();
      });

      // Evento para seleccionar horario desde el calendario
      $(document).on("click", ".slot-horario", function () {
        const medicoId = $(this).data("medico");
        const fecha = $(this).data("fecha");
        const hora = $(this).data("hora");

        // Abrir modal de nueva cita con datos prellenados
        openCitaModal();

        // Esperar a que el modal esté cargado
        setTimeout(() => {
          // Prellenar el médico
          $("#medicoModal").val(medicoId).trigger("change");

          // Prellenar fecha
          $("#fechaModal").val(fecha).trigger("change");

          // Esperar a que se carguen los horarios
          setTimeout(() => {
            // Seleccionar la hora
            $("#horariosDisponibles").val(hora).trigger("change");
          }, 500);
        }, 500);
      });
    };

    /**
     * Inicializa los componentes del módulo
     */
    const initComponents = () => {
      initSelect2Components();
      initDataTable();
    };

    /**
     * Inicializa el módulo
     */
    const init = () => {
      initComponents();
      initCalendarioDisponibilidad();
      initEventListeners();
      loadInitialData();
    };

    // Retornar funciones públicas
    return {
      init: init,
      recargarTabla: function () {
        tablaCitas.ajax.reload();
      },
    };
  })();

  // Inicializar el módulo cuando el documento esté listo
  $(document).ready(function () {
    CitasModule.init();
  });
});
