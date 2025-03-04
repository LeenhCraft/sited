<?php header_web('Template.HeaderDashboard', $data);
$permisos = $data['permisos'];
$permissions = $data["permission"];
?>
<style>
    .file-item {
        border: 1px solid #dee2e6;
        border-radius: 4px;
        padding: 10px;
        margin-bottom: 8px;
        background-color: #f8f9fa;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .file-progress {
        height: 5px;
        margin-top: 5px;
    }

    .upload-area {
        border: 2px dashed #ddd;
        border-radius: 8px;
        padding: 30px;
        text-align: center;
        cursor: pointer;
        transition: border-color 0.3s;
        background-color: #f8f9fa;
    }

    .upload-area:hover,
    .upload-area.active {
        border-color: #0d6efd;
    }

    .upload-area i {
        font-size: 48px;
        color: #6c757d;
    }

    .error-message {
        color: #dc3545;
        margin-top: 8px;
    }

    .success-message {
        color: #198754;
        margin-top: 8px;
    }
</style>
<div class="container mt-5">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Carga de Datos para Entrenamiento</h4>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <p class="text-muted">
                            Cargue uno o varios archivos Excel (.xlsx, .xls) que contengan los datos de entrenamiento para el modelo de predicción de diabetes.
                        </p>
                    </div>

                    <form id="uploadForm" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="datasetName" class="form-label">Nombre del Dataset:</label>
                            <input type="text" class="form-control" id="datasetName" name="datasetName" required>
                            <div class="form-text">Asigne un nombre descriptivo para este conjunto de datos.</div>
                        </div>

                        <div class="mb-3">
                            <label for="datasetDescription" class="form-label">Descripción:</label>
                            <textarea class="form-control" id="datasetDescription" name="datasetDescription" rows="2"></textarea>
                        </div>

                        <div class="mb-4">
                            <div id="uploadArea" class="upload-area">
                                <i class="bi bi-file-earmark-excel mb-3"></i>
                                <h5>Arrastre archivos Excel aquí</h5>
                                <p class="text-muted">O haga clic para seleccionar archivos</p>
                                <input type="file" id="excelFiles" name="excelFiles[]" multiple accept=".xlsx,.xls" class="d-none">
                            </div>
                        </div>

                        <div id="filesList" class="mb-4">
                            <!-- Los archivos seleccionados aparecerán aquí -->
                        </div>

                        <div class="form-check mb-4">
                            <input class="form-check-input" type="checkbox" id="combineDatasets" name="combineDatasets" checked>
                            <label class="form-check-label" for="combineDatasets">
                                Combinar múltiples archivos en un único dataset
                            </label>
                            <div class="form-text">Si se marca, todos los archivos se procesarán como un solo conjunto de datos.</div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary" id="uploadButton">
                                <i class="bi bi-cloud-upload"></i> Cargar y Procesar Datos
                            </button>
                        </div>
                    </form>

                    <div id="messageArea" class="mt-3">
                        <!-- Mensajes de éxito o error aparecerán aquí -->
                    </div>
                </div>
            </div>

            <div class="card shadow mt-4" id="datasetSummary" style="display: none;">
                <div class="card-header bg-success text-white">
                    <h4 class="mb-0">Resumen del Dataset</h4>
                </div>
                <div class="card-body" id="summaryContent">
                    <!-- El resumen del dataset aparecerá aquí -->
                </div>
            </div>
        </div>
    </div>
</div>
<?php footer_web('Template.FooterDashboard', $data); ?>