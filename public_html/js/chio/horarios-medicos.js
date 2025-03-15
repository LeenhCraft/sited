/**
 * Gestión de Horarios Médicos
 * Script para manejar la interfaz de usuario de horarios médicos
 */
(function () {
  "use strict";

  // Variables del módulo
  var tablaHorarios;
  var formHorario;
  var modalHorario;
  var btnNuevoHorario;
  var btnGuardarHorario;
  var btnFiltrar;
  var isEditing = false;
  var pickerInicio;
  var pickerFin;

  // Inicialización del módulo
  function init() {
    // Cachear elementos DOM
    formHorario = $("#formHorario");
    modalHorario = $("#modalHorario");
    btnNuevoHorario = $("#btnNuevoHorario");
    btnGuardarHorario = $("#btnGuardarHorario");
    btnFiltrar = $("#btnFiltrar");

    // Inicializar componentes
    inicializarTabla();
    inicializarSelect2();
    inicializarFlatpickr();
    configurarEventos();
  }

  // Inicializar DataTable
  function inicializarTabla() {
    obtenerDatosAgrupados(function (datosAgrupados) {
      // Inicializar la tabla con los datos agrupados
      tablaHorarios = $("#tablaHorarios").DataTable({
        responsive: true,
        processing: true,
        serverSide: false,
        searching: false,
        data: datosAgrupados,
        order: [],
        columns: [
          { data: "medico" },
          { data: "especialidad" },
          { data: "horario_formato" },
          {
            data: null,
            orderable: false,
            className: "text-center",
            render: function (data, type, row) {
              var btnEditar = "";
              var btnEliminar = "";
              var buttons = "";
              row.horarios.forEach(function (horario) {
                // btnEditar +=
                //   '<button type="button" class="btn btn-sm btn-warning btn-editar mx-1" data-id="' +
                //   horario.id_horaio_medico +
                //   '" title="Editar"><i class="fa fa-edit"></i></button>';
                // btnEliminar +=
                //   '<button type="button" class="btn btn-sm btn-danger btn-eliminar mx-1" data-id="' +
                //   horario.id_horaio_medico +
                //   '" title="Eliminar"><i class="fa fa-trash"></i></button>';

                buttons +=
                  '<button type="button" class="btn btn-sm btn-warning btn-editar mx-1" data-id="' +
                  horario.id +
                  '" title="Editar"><i class="fa fa-edit"></i></button><button type="button" class="btn btn-sm btn-danger btn-eliminar mx-1" data-id="' +
                  horario.id +
                  '" title="Eliminar"><i class="fa fa-trash"></i></button><br>';
              });

              return buttons;
            },
          },
        ],
        language: {
          url: "/js/dataTable.Spanish.json",
        },
      });
    });
  }

  // Inicializar Select2 para médicos
  function inicializarSelect2() {
    // Select2 para médicos
    $("#idMedico").select2({
      placeholder: "Seleccione un médico",
      allowClear: true,
      minimumInputLength: 2,
      language: {
        inputTooShort: function () {
          return "Ingrese al menos 2 caracteres";
        },
        noResults: function () {
          return "No se encontraron resultados";
        },
        searching: function () {
          return "Buscando...";
        },
      },
      dropdownParent: $("#modalHorario"),
      ajax: {
        url: "/admin/horario-medico/medicos",
        dataType: "json",
        delay: 250,
        data: function (params) {
          return {
            term: params.term || "",
            page: params.page || 1,
          };
        },
        processResults: function (data, params) {
          params.page = params.page || 1;
          return {
            results: data.results,
            pagination: data.pagination,
          };
        },
        cache: true,
      },
      templateResult: formatMedicoResult,
      templateSelection: formatMedicoSelection,
    });

    // Cuando cambia el médico seleccionado, actualizar la especialidad
    $("#idMedico").on("change", function () {
      var data = $(this).select2("data")[0];
      if (data) {
        $("#especialidadLabel").text(data.especialidad || "-");
        $("#idEspecialidad").val(data.id_especialidad || "");
      } else {
        $("#especialidadLabel").text("-");
        $("#idEspecialidad").val("");
      }
    });
  }

  // Inicializar Flatpickr para selección de horas
  function inicializarFlatpickr() {
    // Configuración común para ambos pickers
    var configComun = {
      enableTime: true,
      noCalendar: true,
      dateFormat: "H:i",
      time_24hr: false,
      minuteIncrement: 15,
      allowInput: true,
      static: true,
    };

    // Inicializar hora de inicio
    pickerInicio = flatpickr("#horaInicio", {
      ...configComun,
      defaultHour: 8,
      defaultMinute: 0,
      onChange: function (selectedDates, dateStr) {
        // Actualizar mínimo para hora fin
        if (pickerFin) {
          pickerFin.set("minTime", dateStr);

          // Si la hora fin es menor que la de inicio, actualizar hora fin
          var horaFinActual = $("#horaFin").val();
          if (horaFinActual && horaFinActual <= dateStr) {
            // Calcular una hora después de la hora de inicio
            var horaInicioDate = new Date(`2000-01-01T${dateStr}`);
            horaInicioDate.setHours(horaInicioDate.getHours() + 1);
            var nuevaHoraFin =
              horaInicioDate.getHours() +
              ":" +
              (horaInicioDate.getMinutes() < 10 ? "0" : "") +
              horaInicioDate.getMinutes();

            pickerFin.setDate(nuevaHoraFin);
          }
        }
      },
    });

    // Inicializar hora de fin
    pickerFin = flatpickr("#horaFin", {
      ...configComun,
      defaultHour: 17,
      defaultMinute: 0,
      onOpen: function () {
        // Actualizar mínimo al abrir basado en hora inicio actual
        var horaInicio = $("#horaInicio").val();
        if (horaInicio) {
          this.set("minTime", horaInicio);
        }
      },
    });
  }

  // Formatear la visualización de resultados en el dropdown
  function formatMedicoResult(medico) {
    if (!medico.id) return medico.text;
    return $(
      "<span><strong>" +
        medico.text +
        "</strong> - " +
        (medico.especialidad || "") +
        "</span>"
    );
  }

  // Formatear la visualización del médico seleccionado
  function formatMedicoSelection(medico) {
    if (!medico.id) return medico.text;
    return medico.text;
  }

  // Configurar todos los eventos
  function configurarEventos() {
    // Evento para abrir modal de nuevo horario
    btnNuevoHorario.on("click", function () {
      isEditing = false;
      limpiarFormulario();
      $("#modalTitle").text("Nuevo Horario");
      modalHorario.modal("show");
    });

    // Evento para guardar horario
    btnGuardarHorario.on("click", function () {
      if (validarFormulario()) {
        guardarHorario();
      }
    });

    // Evento para filtrar tabla
    btnFiltrar.on("click", function () {
      recargarTabla();
    });

    // Evento para filtro rápido con Enter
    $("#filtroSearch").on("keypress", function (e) {
      if (e.which === 13) {
        recargarTabla();
      }
    });

    // Evento para cambiar estado
    $("#filtroEstado").on("change", function () {
      recargarTabla();
    });

    // Evento para editar horario
    $("#tablaHorarios").on("click", ".btn-editar", function () {
      var id = $(this).data("id");
      cargarHorario(id);
    });

    // Evento para eliminar horario
    $("#tablaHorarios").on("click", ".btn-eliminar", function () {
      var id = $(this).data("id");
      confirmarEliminar(id);
    });

    // Evento para reset del formulario
    modalHorario.on("hidden.bs.modal", function () {
      limpiarFormulario();
    });
  }

  // Cargar datos de un horario para editar
  function cargarHorario(id) {
    $.ajax({
      url: "/admin/horario-medico/search/" + id,
      type: "GET",
      dataType: "json",
      beforeSend: function () {
        showLoading();
      },
      success: function (response) {
        if (response.success) {
          var horarios = response.horario;
          isEditing = true;

          // Llenar el formulario con el primer horario (puedes ajustar esto según tus necesidades)
          var primerHorario = horarios[0];

          // Llenar el formulario
          $("#idHorario").val(id);
          $("#modalTitle").text("Editar Horario");

          // Seleccionar médico con su especialidad
          var medicoOption = new Option(
            primerHorario.nombre_medico,
            primerHorario.id_medico,
            true,
            true
          );
          // Añadir la información de especialidad al objeto de datos
          $(medicoOption).data("especialidad", primerHorario.especialidad);
          $("#idMedico").append(medicoOption).trigger("change");

          // Mostrar especialidad en el label
          $("#especialidadLabel").text(primerHorario.especialidad || "-");
          $("#idEspecialidad").val(primerHorario.id_especialidad || "");

          // Marcar días seleccionados
          $('input[name="dias[]"]').prop("checked", false);
          horarios.forEach(function (horario) {
            var dia = horario.dias_atencion.toLowerCase();
            var diaMap = {
              lunes: "1",
              martes: "2",
              miercoles: "3",
              jueves: "4",
              viernes: "5",
              sabado: "6",
              domingo: "0",
            };
            var diaValue = diaMap[dia];
            if (diaValue !== undefined) {
              $("#dia_" + diaValue).prop("checked", true);
            }
          });

          // Establecer horas con los valores del primer horario
          $("#horaInicio").val(primerHorario.hora_inicio);
          $("#horaFin").val(primerHorario.hora_fin);

          modalHorario.modal("show");
          hideLoading();
        } else {
          mostrarError(response.message || "Error al cargar el horario");
        }
      },
      error: function (xhr, status, error) {
        hideLoading();
        mostrarError("Error al cargar el horario: " + error);
      },
    });
  }

  // Validar formulario
  function validarFormulario() {
    var idMedico = $("#idMedico").val();
    var diasSeleccionados = $('input[name="dias[]"]:checked').length;
    var horaInicio = $("#horaInicio").val();
    var horaFin = $("#horaFin").val();

    // Validar campos obligatorios
    if (!idMedico) {
      mostrarError("Debe seleccionar un médico");
      return false;
    }

    if (diasSeleccionados === 0) {
      mostrarError("Debe seleccionar al menos un día de atención");
      return false;
    }

    if (!horaInicio) {
      mostrarError("Debe ingresar una hora de inicio");
      return false;
    }

    if (!horaFin) {
      mostrarError("Debe ingresar una hora de fin");
      return false;
    }

    // Validar que hora fin sea mayor a hora inicio
    if (horaInicio >= horaFin) {
      mostrarError("La hora de fin debe ser mayor a la hora de inicio");
      return false;
    }

    return true;
  }

  // Guardar horario (nuevo o editar)
  function guardarHorario() {
    var id = $("#idHorario").val();
    var url = isEditing
      ? "/admin/horario-medico/update/" + id
      : "/admin/horario-medico/save";
    var diasSeleccionados = [];

    // Recopilar días seleccionados
    $('input[name="dias[]"]:checked').each(function () {
      diasSeleccionados.push($(this).val());
    });

    var data = {
      id_medico: $("#idMedico").val(),
      dias: diasSeleccionados,
      hora_inicio: $("#horaInicio").val(),
      hora_fin: $("#horaFin").val(),
    };

    $.ajax({
      url: url,
      type: "POST",
      data: data,
      beforeSend: function () {
        showLoading();
      },
      success: function (response) {
        hideLoading();
        if (response.status) {
          // Mostrar mensaje de éxito
          Swal.fire({
            icon: "success",
            title: "¡Éxito!",
            text: response.message,
            showConfirmButton: true,
            timer: 1500,
          });

          // Cerrar modal y recargar tabla
          modalHorario.modal("hide");
          recargarTabla();
        } else {
          mostrarError(response.message || "Error al guardar el horario");
        }
      },
      error: function (xhr, status, error) {
        hideLoading();
        mostrarError("Error al guardar el horario: " + error);
      },
    });
  }

  // Confirmar eliminación
  function confirmarEliminar(id) {
    Swal.fire({
      title: "¿Está seguro?",
      text: "Esta acción eliminará el horario seleccionado",
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "Sí, eliminar",
      cancelButtonText: "Cancelar",
    }).then((result) => {
      if (result.isConfirmed) {
        eliminarHorario(id);
      }
    });
  }

  // Eliminar horario
  function eliminarHorario(id) {
    $.ajax({
      url: "/admin/horario-medico/delete/" + id,
      type: "post",
      beforeSend: function () {
        showLoading();
      },
      success: function (response) {
        hideLoading();
        if (response.status) {
          Swal.fire({
            icon: "success",
            title: "¡Éxito!",
            text: response.message,
            showConfirmButton: false,
            timer: 1500,
          });
          recargarTabla();
        } else {
          mostrarError(response.message || "Error al eliminar el horario");
        }
      },
      error: function (xhr, status, error) {
        hideLoading();
        mostrarError("Error al eliminar el horario: " + error);
      },
    });
  }

  // Limpiar formulario
  function limpiarFormulario() {
    formHorario[0].reset();
    $("#idHorario").val("");
    $("#idMedico").val(null).trigger("change");
    $("#idEspecialidad").val("");
    $("#especialidadLabel").text("-");
    $('input[name="dias[]"]').prop("checked", false);

    // Resetear flatpickr a valores predeterminados
    pickerInicio.setDate("08:00");
    pickerFin.setDate("17:00");
  }

  // Función para obtener y agrupar los datos
  function obtenerDatosAgrupados(callback) {
    $.ajax({
      url: "/admin/horario-medico",
      type: "POST",
      data: {
        filtro_estado: $("#filtroEstado").val(),
        filtro_search: $("#filtroSearch").val(),
      },
      success: function (data) {
        // Agrupar los horarios por médico
        var horariosAgrupados = {};
        data.forEach(function (horario) {
          if (!horariosAgrupados[horario.nombre_medico]) {
            horariosAgrupados[horario.nombre_medico] = {
              id_medico: horario.id_medico,
              id_horario_medico: horario.id_horario_medico,
              nombre_medico: horario.nombre_medico,
              especialidad: horario.especialidad,
              horarios: [],
            };
          }
          horariosAgrupados[horario.nombre_medico].horarios.push({
            text: horario.horario_formato,
            id: horario.id_horario_medico,
          });
        });

        // Convertir el objeto agrupado a un array
        var datosAgrupados = Object.values(horariosAgrupados).map(function (
          medico
        ) {
          return {
            id_medico: medico.id_medico,
            id_horario_medico: medico.id_horario_medico,
            medico: medico.nombre_medico,
            especialidad: medico.especialidad,
            horario_formato: medico.horarios
              .map(function (horario) {
                return horario.text;
              })
              .join("<br>"),
            horarios: medico.horarios,
          };
        });

        // Llamar al callback con los datos agrupados
        callback(datosAgrupados);
      },
    });
  }

  // Recargar tabla
  function recargarTabla() {
    obtenerDatosAgrupados(function (datosAgrupados) {
      // Limpiar y actualizar la tabla con los nuevos datos
      tablaHorarios.clear().rows.add(datosAgrupados).draw();
    });
  }

  // Mostrar error
  function mostrarError(mensaje) {
    Swal.fire({
      icon: "error",
      title: "Error",
      text: mensaje,
    });
  }

  // Mostrar loading
  function showLoading() {
    Swal.fire({
      title: "Procesando...",
      text: "Por favor espere",
      allowOutsideClick: false,
      allowEscapeKey: false,
      showConfirmButton: false,
      didOpen: () => {
        Swal.showLoading();
      },
    });
  }

  // Ocultar loading
  function hideLoading() {
    Swal.close();
  }

  // Iniciar cuando el documento esté listo
  $(document).ready(init);
})();
