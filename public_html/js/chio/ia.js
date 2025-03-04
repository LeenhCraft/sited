/**
 * Script para gestionar la carga de archivos Excel para entrenamiento
 * Utiliza patrón IIFE para encapsulamiento
 */
(function () {
  "use strict";

  // Se ejecuta cuando el DOM está completamente cargado
  document.addEventListener("DOMContentLoaded", initialize);

  // Referencias a elementos del DOM
  let uploadForm,
    uploadArea,
    fileInput,
    filesList,
    uploadButton,
    messageArea,
    datasetSummary,
    summaryContent;

  // Variables de estado
  let selectedFiles = [];
  let isUploading = false;

  /**
   * Inicializa la aplicación
   */
  function initialize() {
    // Obtener referencias a elementos
    uploadForm = document.getElementById("uploadForm");
    uploadArea = document.getElementById("uploadArea");
    fileInput = document.getElementById("excelFiles");
    filesList = document.getElementById("filesList");
    uploadButton = document.getElementById("uploadButton");
    messageArea = document.getElementById("messageArea");
    datasetSummary = document.getElementById("datasetSummary");
    summaryContent = document.getElementById("summaryContent");

    // Configurar eventos para drag & drop
    setupDragAndDrop();

    // Configurar manejadores de eventos
    uploadArea.addEventListener("click", handleUploadAreaClick);
    fileInput.addEventListener("change", handleFileInputChange);
    uploadForm.addEventListener("submit", handleFormSubmit);
  }

  /**
   * Configura eventos para arrastrar y soltar
   */
  function setupDragAndDrop() {
    ["dragenter", "dragover", "dragleave", "drop"].forEach((eventName) => {
      uploadArea.addEventListener(eventName, preventDefaults, false);
    });

    ["dragenter", "dragover"].forEach((eventName) => {
      uploadArea.addEventListener(eventName, highlight, false);
    });

    ["dragleave", "drop"].forEach((eventName) => {
      uploadArea.addEventListener(eventName, unhighlight, false);
    });

    uploadArea.addEventListener("drop", handleDrop, false);
  }

  /**
   * Previene comportamiento predeterminado de eventos
   */
  function preventDefaults(e) {
    e.preventDefault();
    e.stopPropagation();
  }

  /**
   * Resalta el área cuando se arrastra un archivo sobre ella
   */
  function highlight() {
    uploadArea.classList.add("active");
  }

  /**
   * Quita el resaltado del área de carga
   */
  function unhighlight() {
    uploadArea.classList.remove("active");
  }

  /**
   * Maneja el evento de soltar archivos
   */
  function handleDrop(e) {
    const dt = e.dataTransfer;
    const files = dt.files;
    handleFiles(files);
  }

  /**
   * Maneja clic en el área de carga
   */
  function handleUploadAreaClick() {
    fileInput.click();
  }

  /**
   * Maneja cambios en el input de archivos
   */
  function handleFileInputChange() {
    handleFiles(this.files);
  }

  /**
   * Procesa los archivos seleccionados
   */
  function handleFiles(files) {
    if (files.length === 0) return;

    // Filtrar para aceptar solo archivos Excel
    const validExtensions = [".xlsx", ".xls"];
    const newValidFiles = Array.from(files).filter((file) => {
      const extension = "." + file.name.split(".").pop().toLowerCase();
      return validExtensions.includes(extension);
    });

    if (newValidFiles.length === 0) {
      showMessage(
        "Por favor, seleccione solo archivos Excel (.xlsx, .xls)",
        "error"
      );
      return;
    }

    // Añadir nuevos archivos a la lista
    selectedFiles = [...selectedFiles, ...newValidFiles];

    // Actualizar la UI
    renderFileList();
  }

  /**
   * Muestra la lista de archivos en la UI
   */
  function renderFileList() {
    filesList.innerHTML = "";

    if (selectedFiles.length === 0) {
      filesList.innerHTML =
        '<p class="text-muted text-center">No hay archivos seleccionados</p>';
      return;
    }

    selectedFiles.forEach((file, index) => {
      const fileSize = formatFileSize(file.size);

      const fileItem = document.createElement("div");
      fileItem.className = "file-item";
      fileItem.innerHTML = `
                <div>
                    <div class="d-flex align-items-center">
                        <i class="bi bi-file-earmark-excel me-2 text-success"></i>
                        <strong>${file.name}</strong>
                    </div>
                    <small class="text-muted">${fileSize}</small>
                    <div class="progress file-progress" id="progress-${index}" style="display: none;">
                        <div class="progress-bar" role="progressbar" style="width: 0%"></div>
                    </div>
                </div>
                <button type="button" class="btn btn-sm btn-outline-danger remove-file" data-index="${index}">
                    <i class="bi bi-x-lg"></i>
                </button>
            `;

      filesList.appendChild(fileItem);
    });

    // Añadir event listeners para botones de eliminar
    document.querySelectorAll(".remove-file").forEach((button) => {
      button.addEventListener("click", function () {
        const index = parseInt(this.getAttribute("data-index"));
        selectedFiles = selectedFiles.filter((_, i) => i !== index);
        renderFileList();
      });
    });
  }

  /**
   * Formatea tamaño de archivo para mostrar
   */
  function formatFileSize(bytes) {
    if (bytes === 0) return "0 Bytes";

    const k = 1024;
    const sizes = ["Bytes", "KB", "MB", "GB"];
    const i = Math.floor(Math.log(bytes) / Math.log(k));

    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + " " + sizes[i];
  }

  /**
   * Muestra mensajes de éxito o error
   */
  function showMessage(message, type = "success") {
    messageArea.innerHTML = `
            <div class="alert alert-${
              type === "error" ? "danger" : "success"
            } alert-dismissible fade show" role="alert">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        `;

    // Desplazarse al mensaje
    messageArea.scrollIntoView({ behavior: "smooth" });
  }

  /**
   * Actualiza barra de progreso
   */
  function updateProgress(index, percent) {
    const progressBar = document.querySelector(
      `#progress-${index} .progress-bar`
    );
    if (progressBar) {
      progressBar.style.width = `${percent}%`;
      progressBar.setAttribute("aria-valuenow", percent);
    }
  }

  /**
   * Maneja el envío del formulario
   */
  async function handleFormSubmit(e) {
    e.preventDefault();

    if (isUploading) return;

    if (selectedFiles.length === 0) {
      showMessage(
        "Por favor, seleccione al menos un archivo Excel para cargar",
        "error"
      );
      return;
    }

    const datasetName = document.getElementById("datasetName").value.trim();
    if (!datasetName) {
      showMessage("Por favor, ingrese un nombre para el dataset", "error");
      return;
    }

    isUploading = true;
    uploadButton.disabled = true;
    uploadButton.innerHTML =
      '<i class="bi bi-arrow-repeat spin"></i> Procesando...';

    // Mostrar barras de progreso
    selectedFiles.forEach((_, index) => {
      const progressElement = document.getElementById(`progress-${index}`);
      if (progressElement) {
        progressElement.style.display = "block";
      }
    });

    try {
      await uploadFiles(datasetName);
    } catch (error) {
      console.error("Error:", error);
      showMessage("Error en la comunicación con el servidor", "error");
    } finally {
      isUploading = false;
      uploadButton.disabled = false;
      uploadButton.innerHTML =
        '<i class="bi bi-cloud-upload"></i> Cargar y Procesar Datos';
    }
  }

  /**
   * Sube los archivos al servidor
   */
  async function uploadFiles(datasetName) {
    // Crear FormData para enviar archivos
    const formData = new FormData();
    formData.append("datasetName", datasetName);
    formData.append(
      "datasetDescription",
      document.getElementById("datasetDescription").value
    );
    formData.append(
      "combineDatasets",
      document.getElementById("combineDatasets").checked ? "1" : "0"
    );

    // Añadir todos los archivos
    selectedFiles.forEach((file, index) => {
      formData.append(`excelFiles[]`, file);
      updateProgress(index, 10); // Iniciar progreso
    });

    // Simular progreso durante la carga
    const progressInterval = setInterval(() => {
      selectedFiles.forEach((_, index) => {
        const currentWidth = document.querySelector(
          `#progress-${index} .progress-bar`
        ).style.width;
        const currentPercent = parseInt(currentWidth) || 10;
        if (currentPercent < 90) {
          updateProgress(index, currentPercent + 5);
        }
      });
    }, 500);

    try {
      // Realizar la petición AJAX
      const response = await fetch("/admin/modelo-ia/dataset", {
        method: "POST",
        body: formData,
        // No establecer Content-Type, el navegador lo hará automáticamente con el límite correcto
      });

      clearInterval(progressInterval);

      // Actualizar progreso al 100%
      selectedFiles.forEach((_, index) => {
        updateProgress(index, 100);
      });

      const result = await response.json();

      if (response.ok) {
        // Éxito
        showMessage(`Dataset "${datasetName}" cargado exitosamente`, "success");

        // Mostrar resumen del dataset
        displayDatasetSummary(result.data);

        // Limpiar formulario
        uploadForm.reset();
        selectedFiles = [];
        renderFileList();
      } else {
        // Error
        showMessage(result.message || "Error al cargar los archivos", "error");
      }
    } finally {
      clearInterval(progressInterval);
    }
  }

  /**
   * Muestra el resumen del dataset
   */
  function displayDatasetSummary(data) {
    if (!data || !data.summary) return;

    const summary = data.summary;

    let html = `
            <h5 class="mb-3">Dataset: ${data.name}</h5>
            <div class="row mb-4">
                <div class="col-md-6">
                    <p><strong>ID:</strong> ${data.id}</p>
                    <p><strong>Archivos procesados:</strong> ${
                      summary.filesCount || 1
                    }</p>
                    <p><strong>Total de registros:</strong> ${summary.rowsCount.toLocaleString()}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Fecha de creación:</strong> ${formatDate(
                      data.creationDate
                    )}</p>
                    <p><strong>Estado:</strong> <span class="badge bg-success">Listo</span></p>
                </div>
            </div>
        `;

    if (summary.columnStatistics) {
      html += `
                <h6 class="mb-3">Estadísticas de columnas:</h6>
                <div class="table-responsive">
                    <table class="table table-sm table-bordered">
                        <thead>
                            <tr>
                                <th>Columna</th>
                                <th>Tipo</th>
                                <th>Valores únicos</th>
                                <th>Valores faltantes</th>
                            </tr>
                        </thead>
                        <tbody>
            `;

      Object.entries(summary.columnStatistics).forEach(([column, stats]) => {
        html += `
                    <tr>
                        <td>${column}</td>
                        <td>${stats.type}</td>
                        <td>${stats.uniqueCount}</td>
                        <td>${stats.missingCount} (${stats.missingPercentage}%)</td>
                    </tr>
                `;
      });

      html += `
                        </tbody>
                    </table>
                </div>
            `;
    }

    if (summary.tendencyDistribution) {
      html += `
                <h6 class="mb-3 mt-4">Distribución de tendencias:</h6>
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-sm table-bordered">
                            <thead>
                                <tr>
                                    <th>Tendencia</th>
                                    <th>Cantidad</th>
                                    <th>Porcentaje</th>
                                </tr>
                            </thead>
                            <tbody>
            `;

      Object.entries(summary.tendencyDistribution).forEach(
        ([tendency, stats]) => {
          html += `
                    <tr>
                        <td>${tendency}</td>
                        <td>${stats.count.toLocaleString()}</td>
                        <td>${stats.percentage}%</td>
                    </tr>
                `;
        }
      );

      html += `
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <!-- Aquí podría ir un gráfico en el futuro -->
                    </div>
                </div>
            `;
    }

    summaryContent.innerHTML = html;
    datasetSummary.style.display = "block";
    datasetSummary.scrollIntoView({ behavior: "smooth" });
  }

  /**
   * Formatea fechas para mostrar
   */
  function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleString();
  }
})();
