/**
 * Módulo para la gestión de la lista de Tests de Diabetes
 */
document.addEventListener("DOMContentLoaded", function () {
  // Inicializar el módulo
  TestList.init();
});

const TestList = {
  // Variables globales
  dataTable: null,
  testData: [],
  charts: {
    monthlyTrend: null,
    riskDistribution: null,
    responsesRadar: null,
  },

  /**
   * Inicializar el módulo
   */
  init: function () {
    this.initDatatables();
    this.initDatepicker();
    this.initCharts();
    this.initEventListeners();
    this.loadData();
  },

  /**
   * Inicializar DataTables
   */
  initDatatables: function () {
    this.dataTable = $("#tests-datatable").DataTable({
      processing: true,
      serverSide: false,
      responsive: true,
      language: {
        url: "/js/dataTable.Spanish.json",
      },
      dom: "Bfrtip",
      buttons: ["copy", "excel", "pdf"],
      columns: [
        { data: "idtest" },
        { data: "nombre" },
        { data: "dni" },
        { data: "edad" },
        {
          data: "fecha_hora",
          render: function (data) {
            const date = new Date(data);
            return date.toLocaleString("es-ES");
          },
        },
        {
          data: "imc",
          render: function (data) {
            return parseFloat(data).toFixed(2);
          },
        },
        {
          data: "tendencia_label",
          className: "text-center",
          render: function (data) {
            let badgeClass = "bg-label-success";
            let text = "Bajo";

            if (data === "Moderado") {
              badgeClass = "bg-label-warning";
              text = "Moderado";
            } else if (data === "Alto") {
              badgeClass = "bg-label-danger";
              text = "Alto";
            }

            return `<span class="badge ${badgeClass}">${text}</span>`;
          },
        },
        {
          data: null,
          orderable: false,
          render: function (data) {
            return `
            <div class="btn-group">
                <button type="button" class="btn btn-primary btn-sm btn-view-test" data-id="${data.idtest}">
                    <i class="bx bx-search-alt"></i>
                </button>
                <button type="button" class="btn btn-info btn-sm btn-print-test" data-id="${data.idtest}">
                    <i class="bx bx-printer"></i>
                </button>
            </div>`;
          },
        },
      ],
      order: [],
    });
  },

  /**
   * Inicializar datepicker
   */
  initDatepicker: function () {
    if (flatpickr) {
      flatpickr("#filter-date-range", {
        mode: "range",
        dateFormat: "d/m/Y",
        locale: {
          rangeSeparator: " a ",
          firstDayOfWeek: 1,
          weekdays: {
            shorthand: ["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa"],
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
      });
    }

    // Inicializar select2
    if ($.fn.select2) {
      $(".select2").select2({
        placeholder: "Seleccionar...",
        allowClear: true,
        dropdownParent: $(".select2").parent(),
      });
    }
  },

  /**
   * Inicializar gráficos
   */
  initCharts: function () {
    // Esperar a que DOM esté completamente cargado
    this.initializeCharts();
    // setTimeout(() => {
    // }, 300);
  },

  initializeCharts: function () {
    // Gráfico de tendencia mensual
    const monthlyCtx = document.getElementById("monthlyTrendChart");
    if (monthlyCtx) {
      this.charts.monthlyTrend = new Chart(monthlyCtx, {
        responsive: true,
        maintainAspectRatio: false,
        type: "line",
        data: {
          labels: [],
          datasets: [
            {
              label: "Riesgo Alto",
              backgroundColor: "rgba(255, 99, 132, 0.2)",
              borderColor: "rgb(255, 99, 132)",
              borderWidth: 2,
              data: [],
              tension: 0.4,
            },
            {
              label: "Riesgo Moderado",
              backgroundColor: "rgba(255, 205, 86, 0.2)",
              borderColor: "rgb(255, 205, 86)",
              borderWidth: 2,
              data: [],
              tension: 0.4,
            },
            {
              label: "Riesgo Bajo",
              backgroundColor: "rgba(75, 192, 192, 0.2)",
              borderColor: "rgb(75, 192, 192)",
              borderWidth: 2,
              data: [],
              tension: 0.4,
            },
          ],
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: {
              position: "top",
            },
            tooltip: {
              mode: "index",
              intersect: false,
            },
          },
          scales: {
            y: {
              beginAtZero: true,
              ticks: {
                precision: 0,
              },
            },
          },
        },
      });
    }

    // Gráfico de distribución de riesgo
    const riskCtx = document.getElementById("riskDistributionChart");
    if (riskCtx) {
      this.charts.riskDistribution = new Chart(riskCtx, {
        responsive: true,
        maintainAspectRatio: false,
        type: "doughnut",
        data: {
          labels: ["Riesgo Bajo", "Riesgo Moderado", "Riesgo Alto"],
          datasets: [
            {
              data: [0, 0, 0],
              backgroundColor: [
                "rgba(75, 192, 192, 0.7)",
                "rgba(255, 205, 86, 0.7)",
                "rgba(255, 99, 132, 0.7)",
              ],
              borderColor: [
                "rgba(75, 192, 192, 1)",
                "rgba(255, 205, 86, 1)",
                "rgba(255, 99, 132, 1)",
              ],
              borderWidth: 1,
            },
          ],
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: {
              position: "bottom",
            },
          },
        },
      });
    }

    // Inicializar el gráfico de radar para respuestas (vacío inicialmente)
    const radarCtx = document.getElementById("responsesRadarChart");
    if (radarCtx) {
      this.charts.responsesRadar = new Chart(radarCtx, {
        responsive: true,
        maintainAspectRatio: false,
        type: "radar",
        data: {
          labels: [],
          datasets: [
            {
              label: "Respuestas",
              data: [],
              backgroundColor: "rgba(54, 162, 235, 0.2)",
              borderColor: "rgb(54, 162, 235)",
              borderWidth: 2,
              pointBackgroundColor: "rgb(54, 162, 235)",
              pointBorderColor: "#fff",
              pointHoverBackgroundColor: "#fff",
              pointHoverBorderColor: "rgb(54, 162, 235)",
            },
          ],
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          elements: {
            line: {
              tension: 0.1,
            },
          },
          scales: {
            r: {
              angleLines: {
                display: true,
              },
              suggestedMin: 0,
              suggestedMax: 2,
            },
          },
        },
      });
    }
  },

  /**
   * Inicializar eventos
   */
  initEventListeners: function () {
    // Botón de filtrar
    $("#btn-filter").on("click", () => {
      this.applyFilters();
    });

    // Ver detalles del test
    $(document).on("click", ".btn-view-test", (e) => {
      const testId = $(e.currentTarget).data("id");
      this.showTestDetails(testId);
    });

    // Imprimir test individual
    $(document).on("click", ".btn-print-test", (e) => {
      const testId = $(e.currentTarget).data("id");
      this.printTest(testId);
    });

    // Imprimir desde el modal de detalles
    $("#btn-print-detail").on("click", () => {
      const testId = $("#detail-test-id").text();
      this.printTest(testId);
    });

    // Exportar a PDF
    $("#export-pdf").on("click", () => {
      this.exportData("pdf");
    });

    // Exportar a Excel
    $("#export-excel").on("click", () => {
      this.exportData("excel");
    });
  },

  /**
   * Cargar datos iniciales
   */
  loadData: function () {
    $.ajax({
      url: "/admin/lista-test",
      type: "POST",
      dataType: "json",
      success: (response) => {
        if (response.status) {
          this.testData = response.data;
          console.log(this.testData);
          this.populateDatatable(this.testData);
          this.updateStatistics(this.testData);
          this.updateCharts(this.testData);
          this.populatePacienteFilter(this.testData);
        } else {
          toastr.error("Error al cargar datos", "Error");
        }
      },
      error: () => {
        toastr.error("Error de conexión al servidor", "Error");
      },
    });
  },

  /**
   * Poblar datatable con datos
   */
  populateDatatable: function (data) {
    this.dataTable.clear().rows.add(data).draw();
  },

  /**
   * Actualizar estadísticas del dashboard
   */
  updateStatistics: function (data) {
    let totalTests = data.length;
    let lowRisk = 0,
      mediumRisk = 0,
      highRisk = 0;

    data.forEach((test) => {
      const tendencia = test.tendencia_label;
      if (tendencia === "Alto") {
        highRisk++;
      } else if (tendencia === "Moderado") {
        mediumRisk++;
      } else {
        lowRisk++;
      }
    });

    $("#total-tests").text(totalTests);
    $("#high-risk-tests").text(highRisk);
    $("#medium-risk-tests").text(mediumRisk);
    $("#low-risk-tests").text(lowRisk);
  },

  /**
   * Actualizar gráficos
   */
  updateCharts: function (data) {
    // Actualizar gráfico de distribución
    let lowRisk = 0,
      mediumRisk = 0,
      highRisk = 0;

    data.forEach((test) => {
      console.log(test);

      const tendencia = test.tendencia_label;
      if (tendencia === "Alto") {
        highRisk++;
      } else if (tendencia === "Moderado") {
        mediumRisk++;
      } else {
        lowRisk++;
      }
    });

    if (this.charts.riskDistribution) {
      this.charts.riskDistribution.data.datasets[0].data = [
        lowRisk,
        mediumRisk,
        highRisk,
      ];
      this.charts.riskDistribution.update();
    }

    // Actualizar gráfico de tendencia mensual
    const monthlyData = this.getMonthlyData(data);

    if (this.charts.monthlyTrend) {
      this.charts.monthlyTrend.data.labels = monthlyData.labels;
      this.charts.monthlyTrend.data.datasets[0].data = monthlyData.high;
      this.charts.monthlyTrend.data.datasets[1].data = monthlyData.medium;
      this.charts.monthlyTrend.data.datasets[2].data = monthlyData.low;
      this.charts.monthlyTrend.update();
    }
  },

  /**
   * Obtener datos mensuales para gráfico
   */
  getMonthlyData: function (data) {
    const months = {};
    const monthNames = [
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
    ];

    // Agrupar por mes
    data.forEach((test) => {
      const date = new Date(test.fecha_hora);
      const monthYear = `${date.getMonth()}/${date.getFullYear()}`;

      if (!months[monthYear]) {
        months[monthYear] = {
          low: 0,
          medium: 0,
          high: 0,
          month: date.getMonth(),
          year: date.getFullYear(),
          order: date.getFullYear() * 12 + date.getMonth(), // Para ordenar
        };
      }

      const tendencia = test.tendencia_label;
      if (tendencia === "Alto") {
        months[monthYear].high++;
      } else if (tendencia === "Moderado") {
        months[monthYear].medium++;
      } else {
        months[monthYear].low++;
      }
    });

    // Convertir a array y ordenar
    const monthArray = Object.values(months);
    monthArray.sort((a, b) => a.order - b.order);

    // Preparar datos para el gráfico
    const result = {
      labels: [],
      low: [],
      medium: [],
      high: [],
    };

    monthArray.forEach((item) => {
      result.labels.push(`${monthNames[item.month]} ${item.year}`);
      result.low.push(item.low);
      result.medium.push(item.medium);
      result.high.push(item.high);
    });

    return result;
  },

  /**
   * Poblar select de pacientes
   */
  populatePacienteFilter: function (data) {
    const pacientes = new Map();

    // Obtener pacientes únicos
    data.forEach((test) => {
      if (!pacientes.has(test.idpaciente)) {
        pacientes.set(test.idpaciente, test.nombre);
      }
    });

    // Agregar opciones al select
    const $select = $("#filter-paciente");

    // Limpiar opciones anteriores, conservando la opción por defecto
    $select.find("option:not(:first-child)").remove();

    // Agregar pacientes
    pacientes.forEach((nombre, id) => {
      $select.append(`<option value="${id}">${nombre}</option>`);
    });

    // Reiniciar select2 si existe
    if ($.fn.select2) {
      $select.trigger("change");
    }
  },

  /**
   * Aplicar filtros a los datos
   */
  applyFilters: function () {
    const dateRange = $("#filter-date-range").val();
    const pacienteId = $("#filter-paciente").val();
    const tendencia = $("#filter-tendencia").val();

    // Filtrar datos
    let filteredData = [...this.testData];

    // Filtrar por fecha
    if (dateRange) {
      const dates = dateRange.split(" - ");
      const startDate = this.parseDate(dates[0]);
      const endDate = this.parseDate(dates[1]);

      // Ajustar endDate al final del día
      endDate.setHours(23, 59, 59, 999);

      filteredData = filteredData.filter((test) => {
        const testDate = new Date(test.fecha_hora);
        return testDate >= startDate && testDate <= endDate;
      });
    }

    // Filtrar por paciente
    if (pacienteId) {
      filteredData = filteredData.filter(
        (test) => test.idpaciente == pacienteId
      );
    }

    // Filtrar por tendencia
    if (tendencia) {
      filteredData = filteredData.filter((test) => {
        const value = test.tendencia_label;

        if (tendencia === "Alto") {
          return value;
        } else if (tendencia === "Moderado") {
          return value >= 40 && value < 60;
        } else if (tendencia === "Bajo") {
          return value < 40;
        }

        return true;
      });
    }

    // Actualizar datos
    this.populateDatatable(filteredData);
    this.updateStatistics(filteredData);
    this.updateCharts(filteredData);
  },

  /**
   * Convertir string de fecha dd/mm/yyyy a objeto Date
   */
  parseDate: function (dateStr) {
    const parts = dateStr.split("/");
    return new Date(parts[2], parts[1] - 1, parts[0]);
  },

  /**
   * Mostrar detalles de un test
   */
  showTestDetails: function (testId) {
    $.ajax({
      url: `/admin/lista-test/get-test-details/${testId}`,
      type: "GET",
      dataType: "json",
      success: (response) => {
        if (response.status) {
          this.renderTestDetails(response.data);
          $("#testDetailModal").modal("show");
        } else {
          toastr.error("Error al cargar detalles del test", "Error");
        }
      },
      error: () => {
        toastr.error("Error de conexión al servidor", "Error");
      },
    });
  },

  /**
   * Renderizar detalles de un test
   */
  renderTestDetails: function (data) {
    // Información del paciente
    $("#detail-patient-name").text(data.paciente.nombre);
    $("#detail-patient-dni").text(data.paciente.dni);
    $("#detail-patient-age").text(data.paciente.edad);
    $("#detail-patient-sex").text(
      data.paciente.sexo === "M" ? "Masculino" : "Femenino"
    );
    $("#detail-patient-phone").text(data.paciente.celular || "No registrado");

    // Información del test
    $("#detail-test-id").text(data.test.idtest);
    $("#detail-test-date").text(
      new Date(data.test.fecha_hora).toLocaleString("es-ES")
    );
    $("#detail-test-weight").text(`${data.test.peso} kg`);
    $("#detail-test-height").text(`${data.test.altura} m`);
    $("#detail-test-imc").text(`${parseFloat(data.test.imc).toFixed(2)} kg/m²`);

    // Analizar respuesta del test
    try {
      const analisis = JSON.parse(data.test.respuesta_analisis);

      // Actualizar barras de progreso
      const lowPercent = analisis.probabilidades.bajo;
      const mediumPercent = analisis.probabilidades.moderado;
      const highPercent = analisis.probabilidades.alto;

      $("#detail-low-risk .progress-bar").css("width", `${lowPercent}%`);
      $("#detail-low-risk .progress-bar").attr("aria-valuenow", lowPercent);
      $("#detail-low-risk span").text(`${lowPercent.toFixed(2)}%`);

      $("#detail-medium-risk .progress-bar").css("width", `${mediumPercent}%`);
      $("#detail-medium-risk .progress-bar").attr(
        "aria-valuenow",
        mediumPercent
      );
      $("#detail-medium-risk span").text(`${mediumPercent.toFixed(2)}%`);

      $("#detail-high-risk .progress-bar").css("width", `${highPercent}%`);
      $("#detail-high-risk .progress-bar").attr("aria-valuenow", highPercent);
      $("#detail-high-risk span").text(`${highPercent.toFixed(2)}%`);

      // Mostrar clasificación
      const clasificacion = analisis.clasificacion;
      let alertClass = "alert-success";

      if (clasificacion === "Alto") {
        alertClass = "alert-danger";
      } else if (clasificacion === "Moderado") {
        alertClass = "alert-warning";
      }

      $("#detail-risk-alert")
        .removeClass("alert-success alert-warning alert-danger")
        .addClass(alertClass);
      $("#detail-risk-text").text(`Resultado: Riesgo ${clasificacion}`);

      // Mostrar recomendaciones
      const $recommendations = $("#detail-recommendations");
      $recommendations.empty();

      if (analisis.recomendaciones && analisis.recomendaciones.length > 0) {
        analisis.recomendaciones.forEach((recomendacion) => {
          $recommendations.append(`<li>${recomendacion}</li>`);
        });
      } else {
        $recommendations.append("<li>No hay recomendaciones disponibles</li>");
      }

      // Actualizar gráfico de radar
      this.updateRadarChart(data.preguntas);

      // Mostrar tabla de respuestas
      this.renderAnswersTable(data.preguntas);
    } catch (error) {
      console.error("Error al parsear respuesta del análisis:", error);
      toastr.error("Error al procesar los datos del análisis", "Error");
    }
  },

  /**
   * Actualizar gráfico de radar con las respuestas
   */
  updateRadarChart: function (preguntas) {
    if (!this.charts.responsesRadar || !preguntas || preguntas.length === 0) {
      return;
    }

    const labels = [];
    const data = [];

    preguntas.forEach((pregunta, index) => {
      const label = `P${index + 1}`;
      const valorRespuesta = JSON.parse(
        pregunta.respuesta_metadata
      ).valor_seleccionado;

      labels.push(label);
      data.push(valorRespuesta);
    });

    this.charts.responsesRadar.data.labels = labels;
    this.charts.responsesRadar.data.datasets[0].data = data;
    this.charts.responsesRadar.update();
  },

  /**
   * Renderizar tabla de respuestas
   */
  renderAnswersTable: function (preguntas) {
    const $table = $("#detail-answers-table");
    $table.empty();

    preguntas.forEach((pregunta, index) => {
      const metadata = JSON.parse(pregunta.respuesta_metadata);
      const nivel = metadata.valor_seleccionado;

      let nivelClass = "bg-label-success";
      let nivelText = "Bajo";

      if (nivel === 2) {
        nivelClass = "bg-label-danger";
        nivelText = "Alto";
      } else if (nivel === 1) {
        nivelClass = "bg-label-warning";
        nivelText = "Medio";
      }

      $table.append(`
                <tr>
                    <td>${index + 1}</td>
                    <td>${pregunta.pregunta_texto}</td>
                    <td>${pregunta.respuesta_usuario}</td>
                    <td>
                      <span class="text-cener badeg bg-label-primary px-2">${nivel}</span>
                      <span class="text-center badge ${nivelClass}">${nivelText}</span>
                    </td>
                </tr>
            `);
    });
  },

  /**
   * Imprimir un test individual
   */
  printTest: function (testId) {
    const url = `/admin/lista-test/print/${testId}`;
    window.open(url, "_blank");
  },

  /**
   * Exportar datos
   */
  exportData: function (format) {
    const dateRange = $("#filter-date-range").val();
    const pacienteId = $("#filter-paciente").val();
    const tendencia = $("#filter-tendencia").val();

    let url = `/admin/lista-test/export/${format}?`;

    if (dateRange) {
      url += `&dateRange=${dateRange}`;
    }

    if (pacienteId) {
      url += `&pacienteId=${pacienteId}`;
    }

    if (tendencia) {
      url += `&tendencia=${tendencia}`;
    }

    window.open(url, "_blank");
  },
};
