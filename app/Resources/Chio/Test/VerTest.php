<?php header_web('Template.Header', $data); ?>
<script>
    const userID = <?php echo $_SESSION['web_id']; ?>;
    const testID = <?php echo $data["test"]["idtest"]; ?>;
    const pacienteID = <?php echo $data['user']["id_paciente"]; ?>;
    const userName = '<?php echo $data["user"]["nombre"]; ?>';
    const userEmail = '<?php echo $data["user"]["email"]; ?>';
</script>
<?php
// Funciones auxiliares para la vista
function getScoreStatusClass($score)
{
    if ($score >= 80) return 'success';
    if ($score >= 60) return 'info';
    if ($score >= 40) return 'warning';
    return 'danger';
}

function getScoreMessage($tendencia)
{
    switch ($tendencia) {
        case 'Bajo':
            return "Riesgo Bajo";
        case 'Moderado':
            return "Riesgo Moderado";
        case 'Alto':
            return "Riesgo Alto";
        case 'Bajo/Moderado':
            return "Riesgo Moderado";
        default:
            return "No evaluado";
    }
}

function getTendenciaClass($tendencia)
{
    switch ($tendencia) {
        case 'Bajo':
            return 'success';
        case 'Moderado':
            return 'warning';
        case 'Alto':
            return 'danger';
        case 'Bajo/Moderado':
            return 'warning';
        default:
            return 'secondary';
    }
}

// Decodificar el análisis JSON si existe
$analisis = isset($data['test']['respuesta_analisis']) ? json_decode($data['test']['respuesta_analisis'], true) : null;
?>
<div data-bs-spy="scroll" class="scrollspy-example">
    <section id="profile" class="section-py">
        <?php
        if (!isset($data["user"]) || empty($data["user"]) || !isset($data["test"]) || empty($data["test"]) || !isset($data["preguntas"]) || empty($data["preguntas"])) {
        ?>
            <div class='container-xxl flex-grow-1 container-p-y'>
                <h4 class='fw-bold py-3 mb-4'>
                    <span class='text-muted fw-light'>Tests /</span> Ver Resultados
                </h4>
                <div class='alert alert-warning mb-0'>
                    <div class='d-flex'>
                        <i class='icon-base bx bx-error-circle me-2'></i>
                        <span>
                            No se encontraron datos para mostrar. Por favor, vuelva a intentarlo más tarde.
                        </span>
                    </div>
                </div>
            </div>
        <?php
        } else {
        ?>
            <div class="container-xxl flex-grow-1 container-p-y">
                <h4 class="fw-bold py-3 mb-4">
                    <span class="text-muted fw-light">Tests /</span> Ver Resultados
                </h4>

                <!-- Información del test -->
                <div class="row">
                    <!-- Información general y puntaje -->
                    <div class="col-xl-4 col-lg-5 col-md-12">
                        <!-- Tarjeta de información general -->
                        <div class="card mb-4">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">Test de Riesgo de Diabetes</h5>
                                <div class="dropdown">
                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                        <i class="icon-base bx bx-dots-vertical-rounded"></i>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-end">
                                        <a class="dropdown-item" href="javascript:void(0);" id="printTestBtn">
                                            <i class="icon-base bx bx-printer me-1"></i> Imprimir
                                        </a>
                                        <!-- <a class="dropdown-item" href="/test/exportar/<?= $data['test']['idtest'] ?>?format=pdf">
                                        <i class="icon-base bx bxs-file-pdf me-1"></i> Exportar PDF
                                    </a>
                                    <a class="dropdown-item" href="/test/exportar/<?= $data['test']['idtest'] ?>?format=excel">
                                        <i class="icon-base bx bxs-calculator me-1"></i> Exportar Excel
                                    </a> -->
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="d-flex align-items-center flex-column">
                                    <div class="avatar avatar-lg mb-3">
                                        <div class="avatar-initial rounded-circle bg-label-primary">
                                            <i class="icon-base bx bx-clipboard"></i>
                                        </div>
                                    </div>
                                    <h5 class="mb-1 text-center"><?= $data['user']['nombre'] ?></h5>
                                    <div class="mb-3 text-center text-muted small">
                                        Realizado el <?= date('d/m/Y', strtotime($data['test']['fecha_hora'])) ?> a las <?= date('H:i', strtotime($data['test']['fecha_hora'])) ?>
                                    </div>
                                </div>

                                <div class="divider">
                                    <div class="divider-text">Información General</div>
                                </div>

                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                        <div>
                                            <i class="icon-base bx bx-calendar-check me-1 text-primary"></i>
                                            <span>Estado</span>
                                        </div>
                                        <?php if ($data['test']['procesado_modelo']): ?>
                                            <span class="badge bg-success">Completado</span>
                                        <?php else: ?>
                                            <span class="badge bg-warning">En progreso</span>
                                        <?php endif; ?>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                        <div>
                                            <i class="icon-base bx bx-body me-1 text-primary"></i>
                                            <span>IMC</span>
                                        </div>
                                        <span><?= number_format($data['test']['imc'], 1) ?> kg/m²</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                        <div>
                                            <i class="icon-base bx bx-line-chart me-1 text-primary"></i>
                                            <span>Tendencia</span>
                                        </div>
                                        <span class="badge bg-<?= getTendenciaClass($data['test']['tendencia_label']) ?>">
                                            <?= $data['test']['tendencia_label'] ?>
                                        </span>
                                    </li>
                                    <?php if (isset($analisis) && isset($analisis['probabilidades'])): ?>
                                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                            <div>
                                                <i class="icon-base bx bx-pie-chart-alt me-1 text-primary"></i>
                                                <span>Porcentaje</span>
                                            </div>
                                            <span><?= number_format(floatval($data['test']['tendencia_modelo']), 1) ?>%</span>
                                        </li>
                                    <?php endif; ?>
                                </ul>
                            </div>
                        </div>

                        <!-- Tarjeta de evaluación de riesgo -->
                        <div class="card mb-4">
                            <div class="card-body">
                                <h5 class="card-title text-center mb-3">Evaluación de Riesgo</h5>

                                <?php if (isset($data['test']['tendencia_modelo']) && $data['test']['procesado_modelo']): ?>
                                    <div class="d-flex justify-content-center mb-3">
                                        <div id="scoreGauge" data-score="<?= $data['test']['tendencia_modelo'] ?>" data-tendencia="<?= $data['test']['tendencia_label'] ?>"></div>
                                    </div>

                                    <div class="text-center">
                                        <h2 class="mb-1"><?= number_format(floatval($data['test']['tendencia_modelo']), 1) ?>%</h2>
                                        <p class="mb-0 text-<?= getTendenciaClass($data['test']['tendencia_label']) ?>">
                                            <?= getScoreMessage($data['test']['tendencia_label']) ?>
                                        </p>
                                    </div>

                                    <?php if (isset($analisis) && isset($analisis['probabilidades'])): ?>
                                        <div class="divider my-3">
                                            <div class="divider-text">Distribución</div>
                                        </div>

                                        <div class="mb-2">
                                            <div class="d-flex justify-content-between align-items-center mb-1">
                                                <span>Riesgo Bajo</span>
                                                <span><?= number_format($analisis['probabilidades']['bajo'], 1) ?>%</span>
                                            </div>
                                            <div class="progress" style="height: 8px;">
                                                <div class="progress-bar bg-success" role="progressbar"
                                                    style="width: <?= $analisis['probabilidades']['bajo'] ?>%"
                                                    aria-valuenow="<?= $analisis['probabilidades']['bajo'] ?>"
                                                    aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                        </div>

                                        <div class="mb-2">
                                            <div class="d-flex justify-content-between align-items-center mb-1">
                                                <span>Riesgo Moderado</span>
                                                <span><?= number_format($analisis['probabilidades']['moderado'], 1) ?>%</span>
                                            </div>
                                            <div class="progress" style="height: 8px;">
                                                <div class="progress-bar bg-warning" role="progressbar"
                                                    style="width: <?= $analisis['probabilidades']['moderado'] ?>%"
                                                    aria-valuenow="<?= $analisis['probabilidades']['moderado'] ?>"
                                                    aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                        </div>

                                        <div class="mb-2">
                                            <div class="d-flex justify-content-between align-items-center mb-1">
                                                <span>Riesgo Alto</span>
                                                <span><?= number_format($analisis['probabilidades']['alto'], 1) ?>%</span>
                                            </div>
                                            <div class="progress" style="height: 8px;">
                                                <div class="progress-bar bg-danger" role="progressbar"
                                                    style="width: <?= $analisis['probabilidades']['alto'] ?>%"
                                                    aria-valuenow="<?= $analisis['probabilidades']['alto'] ?>"
                                                    aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <div class="alert alert-warning mb-0">
                                        <div class="d-flex">
                                            <i class="icon-base bx bx-error-circle me-2"></i>
                                            <span>Este test aún no ha sido evaluado o no ha sido completado.</span>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Detalle del test -->
                    <div class="col-xl-8 col-lg-7 col-md-12">
                        <!-- Recomendaciones -->
                        <?php if (isset($analisis) && isset($analisis['recomendaciones']) && !empty($analisis['recomendaciones'])): ?>
                            <div class="card mb-4">
                                <div class="card-header d-flex align-items-center">
                                    <h5 class="mb-0">Recomendaciones</h5>
                                    <i class="icon-base bx bx-bulb text-warning ms-2"></i>
                                </div>
                                <div class="card-body">
                                    <div class="alert alert-primary mb-0">
                                        <ul class="mb-0">
                                            <?php foreach ($analisis['recomendaciones'] as $recomendacion): ?>
                                                <?php if (is_array($recomendacion)) {
                                                ?>
                                                    <?php foreach ($recomendacion as $re): ?>
                                                        <li><?= $re ?></li>
                                                    <?php endforeach; ?>
                                                <?php
                                                } else {
                                                ?>
                                                    <li><?= $recomendacion ?></li>
                                                <?php } ?>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>

                        <!-- Tarjeta de preguntas y respuestas -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0">Detalle de Respuestas</h5>
                            </div>
                            <div class="card-body">
                                <?php
                                $preguntas = $data["preguntas"];
                                ?>

                                <?php if (isset($preguntas) && count($preguntas) > 0): ?>
                                    <div class="accordion" id="accordionPreguntas">
                                        <?php foreach ($preguntas as $index => $pregunta): ?>
                                            <div class="accordion-item mb-3 border">
                                                <h2 class="accordion-header">
                                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                                        data-bs-target="#collapse<?= $index ?>" aria-expanded="false" aria-controls="collapse<?= $index ?>">
                                                        <div class="d-flex justify-content-between align-items-center w-100 me-3">
                                                            <div>
                                                                <span class="fw-bold">Pregunta <?= $index + 1 ?></span>
                                                                <?php if (isset($pregunta['pregunta_texto'])): ?>
                                                                    <?php if (strlen($pregunta['pregunta_texto']) > 60): ?>
                                                                        <span class="text-muted ms-2"><?= substr($pregunta['pregunta_texto'], 0, 60) ?>...</span>
                                                                    <?php else: ?>
                                                                        <span class="text-muted ms-2"><?= $pregunta['pregunta_texto'] ?></span>
                                                                    <?php endif; ?>
                                                                <?php endif; ?>
                                                            </div>

                                                            <?php
                                                            $valorClass = '';
                                                            if (isset($pregunta['respuesta_usuario'])) {
                                                                if ($pregunta['respuesta_usuario'] != "") $valorClass = 'success';
                                                                else $valorClass = 'warning';
                                                            }
                                                            ?>
                                                            <?php if (!empty($valorClass)): ?>
                                                                <span class="badge bg-<?= $valorClass ?>">
                                                                    <?= $pregunta["respuesta_usuario"] ?>
                                                                </span>
                                                            <?php else: ?>
                                                                <span class="badge bg-secondary">Sin evaluar</span>
                                                            <?php endif; ?>
                                                        </div>
                                                    </button>
                                                </h2>
                                                <div id="collapse<?= $index ?>" class="accordion-collapse collapse" data-bs-parent="#accordionPreguntas">
                                                    <div class="accordion-body">
                                                        <div class="mb-3">
                                                            <h6 class="fw-bold mb-2">Pregunta:</h6>
                                                            <p><?= $pregunta['pregunta_texto'] ?></p>
                                                        </div>

                                                        <div class="mb-3">
                                                            <h6 class="fw-bold mb-2">Tu respuesta:</h6>
                                                            <div class="p-3 bg-light rounded border">
                                                                <?= $pregunta['respuesta_texto'] ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php else: ?>
                                    <div class="text-center py-5">
                                        <i class="icon-base bx bx-help-circle text-primary mb-2" style="font-size: 3rem;"></i>
                                        <h6>No hay preguntas disponibles</h6>
                                        <p class="text-muted">No se encontraron preguntas para este test.</p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Datos antropométricos -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0">Datos Antropométricos</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <div class="card h-100 border shadow-none">
                                            <div class="card-body">
                                                <div class="d-flex align-items-center mb-2">
                                                    <div class="avatar avatar-sm me-2">
                                                        <div class="avatar-initial rounded bg-label-primary">
                                                            <i class="icon-base bx bx-male-female"></i>
                                                        </div>
                                                    </div>
                                                    <h6 class="mb-0">Edad</h6>
                                                </div>
                                                <h4 class="mb-0"><?= $data['user']['edad'] ?> años</h4>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="card h-100 border shadow-none">
                                            <div class="card-body">
                                                <div class="d-flex align-items-center mb-2">
                                                    <div class="avatar avatar-sm me-2">
                                                        <div class="avatar-initial rounded bg-label-primary">
                                                            <i class="icon-base bx bx-body"></i>
                                                        </div>
                                                    </div>
                                                    <h6 class="mb-0">IMC</h6>
                                                </div>
                                                <h4 class="mb-0"><?= number_format($data['test']['imc'], 1) ?> kg/m²</h4>
                                                <p class="text-muted mb-0">
                                                    <?php
                                                    $imcText = '';
                                                    $imcClass = '';
                                                    if ($data['test']['imc'] < 18.5) {
                                                        $imcText = 'Bajo peso';
                                                        $imcClass = 'warning';
                                                    } else if ($data['test']['imc'] < 25) {
                                                        $imcText = 'Peso normal';
                                                        $imcClass = 'success';
                                                    } else if ($data['test']['imc'] < 30) {
                                                        $imcText = 'Sobrepeso';
                                                        $imcClass = 'warning';
                                                    } else {
                                                        $imcText = 'Obesidad';
                                                        $imcClass = 'danger';
                                                    }
                                                    ?>
                                                    <span class="text-<?= $imcClass ?>"><?= $imcText ?></span>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="card h-100 border shadow-none">
                                            <div class="card-body">
                                                <div class="d-flex align-items-center mb-2">
                                                    <div class="avatar avatar-sm me-2">
                                                        <div class="avatar-initial rounded bg-label-primary">
                                                            <i class="icon-base bx bx-ruler"></i>
                                                        </div>
                                                    </div>
                                                    <h6 class="mb-0">Altura</h6>
                                                </div>
                                                <h4 class="mb-0"><?= number_format($data['test']['altura'], 2) ?> m</h4>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="card h-100 border shadow-none">
                                            <div class="card-body">
                                                <div class="d-flex align-items-center mb-2">
                                                    <div class="avatar avatar-sm me-2">
                                                        <div class="avatar-initial rounded bg-label-primary">
                                                            <i class="icon-base bx bx-weight"></i>
                                                        </div>
                                                    </div>
                                                    <h6 class="mb-0">Peso</h6>
                                                </div>
                                                <h4 class="mb-0"><?= number_format($data['test']['peso'], 1) ?> kg</h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Botones de acción -->
                <div class="text-end mb-4">
                    <a href="/perfil/mis-tests" class="btn btn-outline-secondary me-2">
                        <i class="icon-base bx bx-arrow-back me-1"></i> Volver a mis tests
                    </a>
                    <?php if ($data['test']['procesado_modelo']): ?>
                        <?php if (isset($data['test']['permitir_repetir']) && $data['test']['permitir_repetir']): ?>
                            <a href="/test/repetir/<?= $data['test']['idtest'] ?>" class="btn btn-primary">
                                <i class="icon-base bx bx-refresh me-1"></i> Repetir test
                            </a>
                        <?php endif; ?>
                    <?php else: ?>
                        <a href="/test/continuar/<?= $data['test']['idtest'] ?>" class="btn btn-primary">
                            <i class="icon-base bx bx-right-arrow-alt me-1"></i> Continuar test
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        <?php } ?>
    </section>
</div>

<!-- Campo oculto con los datos del test para JavaScript -->
<input type="hidden" id="test-data" value='<?= json_encode($data['test']) ?>'>

<?php footer_web('Template.Footer', $data); ?>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Inicializar el gráfico de gauge
        const scoreElement = document.getElementById('scoreGauge');
        if (scoreElement) {
            const score = parseFloat(scoreElement.getAttribute('data-score'));
            const label = scoreElement.getAttribute('data-tendencia');
            let color = '#00E396'; // rojo para alto

            if (label === "Alto") {
                color = '#FF4560'; // verde para bajo
            } else if (label === "Moderado") {
                color = '#FEB019'; // amarillo para medio
            } else if (label === "Bajo/Moderado") {
                color = '#FEB019'; // amarillo para medio
            }

            const options = {
                series: [score],
                chart: {
                    height: 200,
                    type: 'radialBar',
                    offsetY: -10
                },
                plotOptions: {
                    radialBar: {
                        startAngle: -135,
                        endAngle: 135,
                        dataLabels: {
                            name: {
                                fontSize: '16px',
                                color: undefined,
                                offsetY: 120
                            },
                            value: {
                                offsetY: 76,
                                fontSize: '22px',
                                color: undefined,
                                formatter: function(val) {
                                    return val.toFixed(1) + '%';
                                }
                            }
                        }
                    }
                },
                fill: {
                    type: 'gradient',
                    gradient: {
                        shade: 'dark',
                        shadeIntensity: 0.15,
                        inverseColors: false,
                        opacityFrom: 1,
                        opacityTo: 1,
                        stops: [0, 50, 65, 91]
                    },
                    colors: [color]
                },
                stroke: {
                    dashArray: 4
                },
                labels: ['Riesgo']
            };

            const chart = new ApexCharts(scoreElement, options);
            chart.render();
        }

        // Configurar el botón de impresión
        const printButton = document.getElementById('printTestBtn');
        if (printButton) {
            printButton.addEventListener('click', function() {
                // window.print();
                const testId = testID;
                const url = `/sited/test/api/detalle/${testId}`;
                window.open(url, "_blank");
            });
        }
    });
</script>