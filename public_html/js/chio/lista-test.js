/**
 * Funciones para la gestión de tests de usuario
 */

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
