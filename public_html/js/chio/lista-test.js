/**
 * Funciones para la gestión de tests de usuario
 */

let datosUsuario = {
  id: userID || 0,
  id_paciente: pacienteID || 0,
  nombre: userName || "Usuario",
  email: userEmail || "",
};

document.addEventListener("DOMContentLoaded", function () {
  // Inicializar DataTables si existe la tabla
  if (document.getElementById("testsTable")) {
    // initializeDataTable();
  }

  // Inicializar gráfica de puntaje si existe el elemento
  if (document.getElementById("scoreGauge")) {
    initializeScoreGauge();
  }

  // Configurar listeners para botones en la lista de tests
  setupTestListeners();

  // Configurar listeners para botones en la vista detalle
  setupDetailViewListeners();
});

/**
 * Inicializa la tabla con DataTables
 */
function initializeDataTable() {
  const table = $("#testsTable").DataTable({
    language: {
      url: "//cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json",
    },
    responsive: true,
    order: [[0, "desc"]], // Ordenar por fecha descendente
    dom: "Bfrtip",
    buttons: [
      {
        extend: "excel",
        text: '<i class="icon-base bx bx-file me-1"></i> Excel',
        className: "btn btn-sm btn-outline-success",
        exportOptions: {
          columns: [0, 1, 2, 3],
        },
      },
      {
        extend: "pdf",
        text: '<i class="icon-base bx bx-file-pdf me-1"></i> PDF',
        className: "btn btn-sm btn-outline-danger",
        exportOptions: {
          columns: [0, 1, 2, 3],
        },
      },
      {
        extend: "print",
        text: '<i class="icon-base bx bx-printer me-1"></i> Imprimir',
        className: "btn btn-sm btn-outline-primary",
        exportOptions: {
          columns: [0, 1, 2, 3],
        },
      },
    ],
  });
}

/**
 * Inicializa la gráfica radial del puntaje en la vista de detalle
 */
function initializeScoreGauge() {
  const scoreElement = document.getElementById("scoreGauge");
  if (!scoreElement) return;

  const score = scoreElement.dataset.score || 0;
  const scoreColor = getScoreColor(score);

  const scoreOptions = {
    series: [score],
    chart: {
      height: 180,
      type: "radialBar",
    },
    plotOptions: {
      radialBar: {
        hollow: {
          size: "70%",
        },
        dataLabels: {
          name: {
            show: false,
          },
          value: {
            offsetY: 5,
            fontSize: "24px",
            fontWeight: "bold",
            formatter: function (val) {
              return val + "%";
            },
          },
        },
        track: {
          background: "#f2f2f2",
        },
      },
    },
    fill: {
      colors: [scoreColor],
    },
    stroke: {
      lineCap: "round",
    },
  };

  const scoreChart = new ApexCharts(scoreElement, scoreOptions);
  scoreChart.render();
}

/**
 * Configura los listeners para la vista de lista de tests
 */
function setupTestListeners() {
  // Exportar prueba individual
  document.querySelectorAll(".export-test").forEach((btn) => {
    btn.addEventListener("click", function () {
      const testId = this.dataset.id;
      fetchTestDetails(testId, "export");
    });
  });

  // Imprimir prueba individual
  document.querySelectorAll(".print-test").forEach((btn) => {
    btn.addEventListener("click", function () {
      const testId = this.dataset.id;
      const url = `/sited/test/api/detalle/${testId}`;
      window.open(url, "_blank");
      // fetchTestDetails(testId, "print");
    });
  });

  document.querySelectorAll(".agendar-cita").forEach((btn) => {
    btn.addEventListener("click", function () {
      const testId = this.dataset.id;
      console.log("Agendar citas para el test", testId);
      abrirModalAgendarCita(testId);
      datosUsuario.id_test = testId;
    });
  });

  document
    .getElementById("btn-confirmar-cita")
    .addEventListener("click", function () {
      console.log("Confirmar cita");

      // Recopilar datos de la cita
      const fecha = document.getElementById("fecha-cita").value;
      const hora = document.getElementById("hora-cita").value;
      const observaciones = document.getElementById("observaciones").value;
      const especialidad = document.getElementById("especialidad").value || "1"; // Valor por defecto si no está establecido
      const medico = document.getElementById("medico").value;

      // Crear objeto con los datos de la cita
      const datosCita = {
        fecha: fecha,
        hora: hora,
        observaciones: observaciones,
        especialidad: especialidad,
        medico: medico,
        id_paciente: datosUsuario.id_paciente,
        id_usuario: datosUsuario.id,
        id_test: datosUsuario.id_test,
      };

      // Enviar la solicitud de cita
      enviarSolicitudCita(datosCita);
    });

  // Exportar todos los tests
  const exportAllBtn = document.getElementById("exportAllBtn");
  if (exportAllBtn) {
    exportAllBtn.addEventListener("click", function () {
      Swal.fire({
        title: "Exportar todos los tests",
        text: "Selecciona el formato para exportar",
        icon: "info",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "PDF",
        cancelButtonText: "Excel",
      }).then((result) => {
        if (result.isConfirmed) {
          // Exportar como PDF
          window.location.href = "/mis-tests/exportar?format=pdf";
        } else if (result.dismiss === Swal.DismissReason.cancel) {
          // Exportar como Excel
          window.location.href = "/mis-tests/exportar?format=excel";
        }
      });
    });
  }
}

/**
 * Configura los listeners para la vista de detalle de test
 */
function setupDetailViewListeners() {
  // Imprimir test
  const printTestBtn = document.getElementById("printTestBtn");
  if (printTestBtn) {
    printTestBtn.addEventListener("click", function () {
      const testData = JSON.parse(document.getElementById("test-data").value);
      printTestData(testData);
    });
  }

  // Eliminar test
  const deleteTestBtn = document.getElementById("deleteTestBtn");
  if (deleteTestBtn) {
    deleteTestBtn.addEventListener("click", function () {
      const testId = this.dataset.id;
      Swal.fire({
        title: "¿Estás seguro?",
        text: "Esta acción eliminará permanentemente este test de tu historial",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#3085d6",
        confirmButtonText: "Sí, eliminar",
        cancelButtonText: "Cancelar",
      }).then((result) => {
        if (result.isConfirmed) {
          deleteTest(testId);
        }
      });
    });
  }
}

/**
 * Obtiene los detalles de un test y ejecuta la acción indicada
 * @param {string} testId - ID del test
 * @param {string} action - Acción a realizar ('export' o 'print')
 */
function fetchTestDetails(testId, action) {
  fetch(`/sited/test/api/detalle/${testId}`)
    .then((response) => response.json())
    .then((data) => {
      if (data.status) {
        if (action === "export") {
          exportTestData(data.test);
        } else if (action === "print") {
          printTestData(data.test);
        }
      } else {
        Toast.fire({
          icon: "error",
          title: data.message || "Error al obtener los datos del test",
        });
      }
    })
    .catch((error) => {
      console.error("Error:", error);
      Toast.fire({
        icon: "error",
        title: "Error al comunicarse con el servidor",
      });
    });
}

/**
 * Muestra un diálogo para exportar un test
 * @param {Object} test - Datos del test
 */
function exportTestData(test) {
  Swal.fire({
    title: "Exportar test",
    text: "Selecciona el formato para exportar",
    icon: "info",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "PDF",
    cancelButtonText: "Excel",
  }).then((result) => {
    if (result.isConfirmed) {
      // Exportar como PDF
      window.location.href = `/test/exportar/${test.id}?format=pdf`;
    } else if (result.dismiss === Swal.DismissReason.cancel) {
      // Exportar como Excel
      window.location.href = `/test/exportar/${test.id}?format=excel`;
    }
  });
}

/**
 * Elimina un test
 * @param {string} testId - ID del test a eliminar
 */
function deleteTest(testId) {
  fetch(`/test/eliminar/${testId}`, {
    method: "POST",
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.status) {
        Swal.fire(
          "Eliminado",
          "El test ha sido eliminado correctamente",
          "success"
        ).then(() => {
          window.location.href = "/mis-tests";
        });
      } else {
        Toast.fire({
          icon: "error",
          title: data.message || "Error al eliminar el test",
        });
      }
    })
    .catch((error) => {
      console.error("Error:", error);
      Toast.fire({
        icon: "error",
        title: "Error al comunicarse con el servidor",
      });
    });
}

/**
 * Crea una ventana para imprimir un test
 * @param {Object} test - Datos del test
 */
function printTestData(test) {
  // Crear ventana de impresión
  const printWindow = window.open("", "_blank");

  // Contenido HTML para imprimir
  let preguntasHTML = "";

  if (test.preguntas && test.preguntas.length > 0) {
    test.preguntas.forEach((pregunta, index) => {
      let opcionesHTML = "";

      if (pregunta.opciones && pregunta.opciones.length > 0) {
        pregunta.opciones.forEach((opcion) => {
          const esCorrecta = opcion.correcta ? "correct" : "";
          const fueSeleccionada = opcion.seleccionada ? "✓ " : "";

          opcionesHTML += `
                        <div class="answer ${esCorrecta}">
                            ${fueSeleccionada}${opcion.texto}
                            ${opcion.correcta ? " (Respuesta correcta)" : ""}
                        </div>
                    `;
        });
      }

      let respuestaTextoHTML = "";
      if (pregunta.respuesta_texto) {
        respuestaTextoHTML = `
                    <div class="text-answer">
                        <strong>Tu respuesta:</strong>
                        <p>${pregunta.respuesta_texto}</p>
                    </div>
                `;
      }

      let feedbackHTML = "";
      if (pregunta.feedback) {
        feedbackHTML = `
                    <div class="feedback">
                        <strong>Retroalimentación:</strong>
                        <p>${pregunta.feedback}</p>
                    </div>
                `;
      }

      preguntasHTML += `
                <div class="question">
                    <h4>Pregunta ${index + 1}</h4>
                    <p><strong>${pregunta.texto}</strong></p>
                    ${opcionesHTML}
                    ${respuestaTextoHTML}
                    ${feedbackHTML}
                </div>
            `;
    });
  }

  // Recomendaciones
  let recomendacionesHTML = "";
  if (test.recomendaciones) {
    recomendacionesHTML = `
            <div class="recommendations">
                <h3>Recomendaciones</h3>
                <p>${test.recomendaciones}</p>
            </div>
        `;
  }

  const htmlContent = `
        <!DOCTYPE html>
        <html lang="es">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Resultados del Test - ${test.tipo_test}</title>
            <style>
                body { font-family: Arial, sans-serif; margin: 20px; }
                .header { text-align: center; margin-bottom: 30px; border-bottom: 1px solid #ddd; padding-bottom: 20px; }
                .test-info { margin-bottom: 20px; }
                .test-info p { margin: 5px 0; }
                .result-section { margin-bottom: 30px; text-align: center; }
                .result-score { font-size: 24px; font-weight: bold; margin: 20px 0; }
                .question { margin-bottom: 25px; padding: 15px; border-left: 3px solid #ccc; background-color: #f9f9f9; }
                .answer { margin-top: 5px; padding: 3px 0; }
                .correct { color: green; font-weight: bold; }
                .recommendations { margin-top: 30px; padding: 15px; background-color: #e6f7ff; border-left: 3px solid #1890ff; }
                .text-answer { margin-top: 10px; padding: 10px; background-color: #f0f0f0; }
                .feedback { margin-top: 10px; padding: 10px; background-color: #fffbe6; border-left: 3px solid #faad14; }
                @media print {
                    .no-print { display: none; }
                    body { margin: 0; }
                    .question { page-break-inside: avoid; }
                }
            </style>
        </head>
        <body>
            <div class="header">
                <h1>Resultados del Test</h1>
                <h2>${test.tipo_test}</h2>
            </div>
            
            <div class="test-info">
                <p><strong>Fecha:</strong> ${new Date(
                  test.fecha
                ).toLocaleString()}</p>
                <p><strong>Usuario:</strong> ${
                  test.nombre_usuario || "No disponible"
                }</p>
                <p><strong>Estado:</strong> ${
                  test.completado ? "Completado" : "En progreso"
                }</p>
                ${
                  test.duracion
                    ? `<p><strong>Duración:</strong> ${test.duracion}</p>`
                    : ""
                }
            </div>
            
            <div class="result-section">
                <h3>Resultado final</h3>
                <div class="result-score">Puntaje: ${test.puntaje || 0}%</div>
                <p>${getScoreMessage(test.puntaje || 0)}</p>
            </div>
            
            <h3>Detalle de respuestas</h3>
            ${
              preguntasHTML ||
              "<p>No hay detalles de respuestas disponibles</p>"
            }
            
            ${recomendacionesHTML}
            
            <div class="no-print" style="text-align: center; margin-top: 30px;">
                <button onclick="window.print();" style="padding: 10px 20px; background-color: #4CAF50; color: white; border: none; border-radius: 4px; cursor: pointer;">
                    Imprimir
                </button>
                <button onclick="window.close();" style="padding: 10px 20px; background-color: #f44336; color: white; border: none; border-radius: 4px; cursor: pointer; margin-left: 10px;">
                    Cerrar
                </button>
            </div>
        </body>
        </html>
    `;

  // Escribir el contenido y activar la impresión
  printWindow.document.write(htmlContent);
  printWindow.document.close();

  // Esperar a que el contenido cargue antes de mostrar el diálogo de impresión
  printWindow.onload = function () {
    setTimeout(function () {
      printWindow.focus();
    }, 500);
  };
}

/**
 * Devuelve un mensaje según el puntaje obtenido
 * @param {number} score - Puntaje (0-100)
 * @return {string} Mensaje descriptivo
 */
function getScoreMessage(score) {
  if (score >= 90) return "Excelente";
  if (score >= 80) return "Muy bueno";
  if (score >= 70) return "Bueno";
  if (score >= 60) return "Aceptable";
  if (score >= 50) return "Regular";
  return "Necesita mejorar";
}

/**
 * Determina el color según el puntaje para las gráficas
 * @param {number} score - Puntaje (0-100)
 * @return {string} Código de color hexadecimal
 */
function getScoreColor(score) {
  if (score >= 80) return "#4CAF50";
  if (score >= 60) return "#2196F3";
  if (score >= 40) return "#FF9800";
  return "#F44336";
}

/**
 * Devuelve la clase de color según el puntaje para Bootstrap
 * @param {number} score - Puntaje (0-100)
 * @return {string} Nombre de la clase de color
 */
function getScoreColorClass(score) {
  if (score >= 80) return "success";
  if (score >= 60) return "info";
  if (score >= 40) return "warning";
  return "danger";
}

// Función que hace la petición al servidor
async function obtenerDisponibilidadHorarios() {
  try {
    const response = await fetch("/sited/disponibilidad", {
      method: "GET",
      headers: {
        "Content-Type": "application/json",
      },
    });

    if (!response.ok) {
      throw new Error("No se pudo obtener la disponibilidad");
    }

    const respuesta = await response.json();
    // Verificar si la respuesta es exitosa y contiene datos
    if (respuesta && respuesta.success && Array.isArray(respuesta.data)) {
      // Filtramos por especialidad si se proporciona
      const medicosEspecialidad = respuesta.data;

      // Si no hay médicos en la especialidad, devolver todos
      return medicosEspecialidad.length > 0
        ? medicosEspecialidad
        : respuesta.data;
    } else {
      throw new Error("Formato de respuesta inválido");
    }
  } catch (error) {
    console.error("Error al obtener disponibilidad:", error);
    throw error;
  }
}

// Función para extraer las fechas disponibles de todos los médicos
function obtenerFechasDisponibles(medicos) {
  // Verificar si tenemos médicos válidos
  if (!Array.isArray(medicos) || medicos.length === 0) {
    return [];
  }

  // Usar un Set para evitar duplicados
  let fechasDisponibles = new Set();

  // Recorrer cada médico
  medicos.forEach((medico) => {
    // Verificar si el médico tiene horarios
    if (medico && medico.horarios) {
      // Obtener todas las fechas (claves) del objeto horarios
      const fechasMedico = Object.keys(medico.horarios);

      // Para cada fecha, verificar si tiene horarios disponibles
      fechasMedico.forEach((fecha) => {
        const horariosEnFecha = medico.horarios[fecha];

        // Verificar si hay horarios disponibles en esta fecha
        if (Array.isArray(horariosEnFecha) && horariosEnFecha.length > 0) {
          // Si es un array con elementos, añadir la fecha
          fechasDisponibles.add(fecha);
        } else if (
          typeof horariosEnFecha === "object" &&
          Object.keys(horariosEnFecha).length > 0
        ) {
          // Si es un objeto con propiedades, añadir la fecha
          fechasDisponibles.add(fecha);
        }
      });
    }
  });

  // Convertir el Set a un array y ordenar las fechas
  return Array.from(fechasDisponibles).sort(
    (a, b) => new Date(a) - new Date(b)
  );
}

// Función para actualizar las horas disponibles según la fecha seleccionada
function actualizarHorasDisponibles(fecha, medicos) {
  // Verificar si tenemos datos válidos
  if (!fecha || !Array.isArray(medicos) || medicos.length === 0) {
    // Si no hay datos válidos, deshabilitar el selector de horas
    $("#hora-cita")
      .empty()
      .prop("disabled", true)
      .append('<option value="">No hay horarios disponibles</option>');
    $("#btn-confirmar-cita").prop("disabled", true);
    return;
  }

  // Encontrar qué médicos tienen disponibilidad en esta fecha
  const medicosDisponiblesEnFecha = medicos.filter(
    (medico) => medico.horarios && medico.horarios[fecha]
  );

  if (medicosDisponiblesEnFecha.length === 0) {
    // Si no hay médicos disponibles para esta fecha
    $("#hora-cita")
      .empty()
      .prop("disabled", true)
      .append(
        '<option value="">No hay disponibilidad para esta fecha</option>'
      );
    $("#btn-confirmar-cita").prop("disabled", true);
    return;
  }

  // Recopilar todas las horas disponibles para todos los médicos en esta fecha
  let todasHoras = [];

  medicosDisponiblesEnFecha.forEach((medico) => {
    // Verificar el formato de los horarios (pueden ser array u objeto)
    const horariosEnFecha = medico.horarios[fecha];

    if (Array.isArray(horariosEnFecha)) {
      // Si es un array, añadimos todas las horas
      todasHoras = [...todasHoras, ...horariosEnFecha];
    } else if (typeof horariosEnFecha === "object") {
      // Si es un objeto, añadimos todos los valores
      todasHoras = [...todasHoras, ...Object.values(horariosEnFecha)];
    }
  });

  // Eliminar duplicados y ordenar
  todasHoras = [...new Set(todasHoras)].sort();

  // Actualizar select de horas
  $("#hora-cita").empty().prop("disabled", false);

  if (todasHoras.length === 0) {
    $("#hora-cita").append(
      '<option value="">No hay horas disponibles</option>'
    );
    $("#hora-cita").prop("disabled", true);
    $("#btn-confirmar-cita").prop("disabled", true);
    return;
  }

  $("#hora-cita").append('<option value="">Selecciona una hora</option>');

  // Agregar las horas disponibles
  todasHoras.forEach((hora) => {
    // Formatear la hora para mostrar (de 24h a 12h)
    const horaFormateada = formatearHora(hora);

    // Encontrar qué médicos están disponibles en esta hora específica
    const medicosEnHora = medicosDisponiblesEnFecha.filter((medico) => {
      const horariosEnFecha = medico.horarios[fecha];

      if (Array.isArray(horariosEnFecha)) {
        return horariosEnFecha.includes(hora);
      } else if (typeof horariosEnFecha === "object") {
        return Object.values(horariosEnFecha).includes(hora);
      }
      return false;
    });

    // Guardar los IDs de médicos disponibles como atributo de datos
    const medicosIds = medicosEnHora.map((m) => m.id).join(",");
    const medicosNombres = medicosEnHora.map((m) => m.nombre).join("|");

    $("#hora-cita").append(
      `<option value="${hora}" data-medicos="${medicosIds}" data-nombres="${medicosNombres}">${horaFormateada}</option>`
    );
  });

  // Guardar la fecha seleccionada como atributo de datos para usar cuando se seleccione una hora
  $("#hora-cita").data("fecha-seleccionada", fecha);

  // inicializar select2
  $("#hora-cita").select2({
    placeholder: "Selecciona una hora",
    width: "100%",
    dropdownParent: $("#modalAgendarCita"),
  });

  // Asegurarnos de que el evento change se active correctamente
  $("#hora-cita")
    .off("change.horacita")
    .on("change.horacita", function () {
      const horaSeleccionada = $(this).val();

      if (horaSeleccionada) {
        const opcionSeleccionada = $(this).find("option:selected");
        const medicosIds = opcionSeleccionada.data("medicos");
        // Actualizar el campo oculto con el ID del médico
        if (medicosIds) {
          let idMedico;
          // Verificar si medicosIds es ya un valor único o necesita ser dividido
          if (typeof medicosIds === "string" && medicosIds.includes(",")) {
            // Si hay múltiples médicos separados por coma, seleccionamos el primero
            idMedico = medicosIds.split(",")[0];
          } else {
            // Si es un solo valor (string o número), lo usamos directamente
            idMedico = medicosIds;
          }
          $("#medico").val(idMedico);
          console.log("ID de médico seleccionado:", idMedico);
        } else {
          $("#medico").val("");
        }

        // Habilitar botón confirmar cita
        $("#btn-confirmar-cita").prop("disabled", false);

        // Mostrar el resumen de la cita
        actualizarResumenCita();
      } else {
        $("#btn-confirmar-cita").prop("disabled", true);
        $("#resumen-cita").addClass("d-none");
      }
    });

  // Disparar evento change para que select2 actualice
  $("#hora-cita").trigger("change");
}

function formatearFecha(fechaStr) {
  return moment(fechaStr).format("dddd, D [de] MMMM [de] YYYY");
}

// Función auxiliar para formatear hora de 24h a 12h
function formatearHora(hora24) {
  const [hora, minutos] = hora24.split(":");
  let periodo = "AM";
  let hora12 = parseInt(hora, 10);

  if (hora12 >= 12) {
    periodo = "PM";
    if (hora12 > 12) {
      hora12 -= 12;
    }
  }

  if (hora12 === 0) {
    hora12 = 12;
  }

  return `${hora12}:${minutos} ${periodo}`;
}

// Función para actualizar el resumen de la cita
function actualizarResumenCita() {
  const fechaSeleccionada = document.getElementById("fecha-cita").value;
  const horaSelect = document.getElementById("hora-cita");
  const horaSeleccionada = horaSelect.options[horaSelect.selectedIndex].text;

  // Obtener datos del médico seleccionado
  const medicosIds =
    horaSelect.options[horaSelect.selectedIndex].dataset.medicos;
  const medicosNombres =
    horaSelect.options[horaSelect.selectedIndex].dataset.nombres;

  // Si hay múltiples médicos, tomamos el primero
  const idMedico = medicosIds.split(",")[0];
  const nombreMedico = medicosNombres.split("|")[0];

  // Actualizar resumen
  document.getElementById("resumen-paciente").textContent = datosUsuario.nombre;
  document.getElementById("resumen-especialidad").textContent =
    document.getElementById("especialidad-recomendada").textContent;
  document.getElementById("resumen-medico").textContent =
    nombreMedico || "Asignación automática";
  document.getElementById("resumen-fecha-hora").textContent = `${formatearFecha(
    fechaSeleccionada
  )} - ${horaSeleccionada}`;

  // Establecer el médico seleccionado
  document.getElementById("medico").value = idMedico;

  // Mostrar el resumen
  document.getElementById("resumen-cita").classList.remove("d-none");
}

// Función para abrir el modal de agendar cita
async function abrirModalAgendarCita(testId) {
  try {
    // Mostrar indicador de carga
    Swal.fire({
      title: "Cargando horarios disponibles",
      html: `
          <div class="d-flex justify-content-center">
            <div class="spinner-border text-primary" role="status">
              <span class="visually-hidden">Cargando...</span>
            </div>
          </div>
          <p class="mt-2">Estamos consultando la disponibilidad de citas...</p>
        `,
      showConfirmButton: false,
      allowOutsideClick: false,
      allowEscapeKey: false,
      didOpen: () => {
        Swal.showLoading();
      },
    });

    // AQUÍ ESTÁ LA PETICIÓN PARA OBTENER HORARIOS DISPONIBLES
    const horarios = await obtenerDisponibilidadHorarios();

    // Cerrar el Sweet Alert de carga
    Swal.close();

    // Crear y añadir el modal al DOM (código omitido para brevedad)...

    // AQUÍ ES DONDE SE CONFIGURA FLATPICKR CON LOS HORARIOS OBTENIDOS
    // Obtener todas las fechas disponibles de los horarios
    const fechasDisponiblesList = obtenerFechasDisponibles(horarios);

    // Inicializar flatpickr con las fechas disponibles
    const flatpickrInstance = flatpickr("#fecha-cita", {
      enableTime: false,
      dateFormat: "Y-m-d",
      minDate: "today",
      locale: {
        firstDayOfWeek: 1,
        weekdays: {
          shorthand: ["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sá"],
          longhand: [
            "Domingo",
            "Lunes",
            "Martes",
            "Miércoles",
            "Jueves",
            "Viernes",
            "Sábado",
          ],
        },
        months: {
          shorthand: [
            "Ene",
            "Feb",
            "Mar",
            "Abr",
            "May",
            "Jun",
            "Jul",
            "Ago",
            "Sep",
            "Oct",
            "Nov",
            "Dic",
          ],
          longhand: [
            "Enero",
            "Febrero",
            "Marzo",
            "Abril",
            "Mayo",
            "Junio",
            "Julio",
            "Agosto",
            "Septiembre",
            "Octubre",
            "Noviembre",
            "Diciembre",
          ],
        },
      },
      enable: fechasDisponiblesList, // Solo habilita las fechas que tienen disponibilidad
      onChange: function (selectedDates, dateStr) {
        actualizarHorasDisponibles(dateStr, horarios);
      },
    });

    // AQUÍ SE MUESTRA EL MODAL YA CONFIGURADO
    $("#modalAgendarCita").modal("show");
  } catch (error) {
    console.error("Error al abrir el modal:", error);
    Swal.fire({
      title: "Error",
      text: "No se pudieron cargar los horarios disponibles. Por favor, intenta nuevamente más tarde.",
      icon: "error",
      confirmButtonText: "Entendido",
    });
  }
}

// Función para enviar la solicitud de cita al servidor
function enviarSolicitudCita(datosCita) {
  // Mostrar un indicador de carga
  Swal.fire({
    title: "Procesando solicitud",
    html: "Estamos agendando tu cita...",
    allowOutsideClick: false,
    didOpen: () => {
      Swal.showLoading();
    },
  });

  // Enviar los datos al servidor
  fetch("/sited/agendar-cita", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify(datosCita),
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        Swal.fire({
          title: "¡Cita Agendada!",
          html: `
        <div class="alert alert-success">
          <p>Tu cita ha sido agendada exitosamente.</p>
          <p><strong>Fecha:</strong> ${formatearFecha(datosCita.fecha)}</p>
          <p><strong>Hora:</strong> ${datosCita.hora}</p>
        </div>
        <p class="d-none">Recibirás un correo electrónico con los detalles de tu cita.</p>
      `,
          icon: "success",
        }).then((confirm) => {
          if (confirm.isConfirmed) {
            // Redirigir a la página de inicio
            window.location.href = "/perfil/mis-tests";
          }
        });

        $("#modalAgendarCita").modal("hide");
      } else {
        Swal.fire({
          title: "Error",
          text:
            data.message ||
            "No pudimos agendar tu cita. Por favor, intenta nuevamente.",
          icon: "error",
        });
      }
    })
    .catch((error) => {
      console.error("Error al agendar cita:", error);
      Swal.fire({
        title: "Error",
        text: "Hubo un problema al comunicarse con el servidor.",
        icon: "error",
      });
    });
}
