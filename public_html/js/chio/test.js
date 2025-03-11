"use strict";

// Patrón IIFE (Immediately Invoked Function Expression)
(function () {
  // Variables globales
  let preguntas = [];
  let preguntasTotales = 0;
  let respuestasUsuario = {};
  let pacienteSeleccionado = null;
  let datosPaciente = {
    edad: 0,
    peso: 0,
    altura: 0,
    imc: 0,
  };

  // Elementos del DOM
  let testContainer, btnFinalizar, progressBar;
  let templateContenedor, templatePregunta;

  // Función de inicialización
  function init() {
    // Inicializar Select2 para búsqueda de pacientes
    initSelect2();

    // Elementos del DOM para el test
    testContainer = document.getElementById("test-container");
    btnFinalizar = document.getElementById("btn-finalizar");
    progressBar = document.getElementById("progress-bar");

    // Templates
    templateContenedor = document.getElementById("template-contenedor");
    templatePregunta = document.getElementById("template-pregunta");

    // Event listeners para flujo del test
    document
      .getElementById("btn-continuar-datos")
      .addEventListener("click", mostrarPanelDatos);
    document
      .getElementById("btn-volver-paciente")
      .addEventListener("click", mostrarPanelPaciente);
    document
      .getElementById("form-datos-antropometricos")
      .addEventListener("submit", function (e) {
        e.preventDefault();
        iniciarTest();
      });
    document
      .getElementById("btn-volver-datos")
      .addEventListener("click", mostrarPanelDatos);
    document
      .getElementById("btn-finalizar")
      .addEventListener("click", finalizarTest);
    document
      .getElementById("btn-reiniciar")
      .addEventListener("click", reiniciarTest);
    document
      .getElementById("btn-imprimir")
      .addEventListener("click", imprimirResultados);

    // Event listeners para cálculo de IMC
    document.getElementById("peso").addEventListener("input", calcularIMC);
    document.getElementById("altura").addEventListener("input", calcularIMC);
  }

  /**
   * Inicializa el plugin Select2 para búsqueda de pacientes
   */
  function initSelect2() {
    $("#paciente-select")
      .select2({
        placeholder: "Escriba nombre o DNI del paciente",
        allowClear: true,
        minimumInputLength: 3,
        ajax: {
          url: "/admin/diagnosticos/buscar-pacientes",
          dataType: "json",
          delay: 250,
          data: function (params) {
            return {
              q: params.term,
            };
          },
          processResults: function (data) {
            return {
              results: data.map(function (item) {
                return {
                  id: item.idpaciente,
                  text: item.nombre + " - DNI: " + item.dni,
                  paciente: item,
                };
              }),
            };
          },
          cache: true,
        },
      })
      .on("select2:select", function (e) {
        // Guardar paciente seleccionado
        pacienteSeleccionado = e.params.data.paciente;

        // Mostrar información del paciente
        mostrarInfoPaciente(pacienteSeleccionado);

        // Habilitar botón de continuar
        document.getElementById("btn-continuar-datos").disabled = false;
      })
      .on("select2:clear", function () {
        // Ocultar información del paciente
        document
          .getElementById("info-paciente-container")
          .classList.add("d-none");

        // Deshabilitar botón de continuar
        document.getElementById("btn-continuar-datos").disabled = true;

        // Limpiar paciente seleccionado
        pacienteSeleccionado = null;
      });
  }

  /**
   * Muestra la información del paciente seleccionado
   */
  function mostrarInfoPaciente(paciente) {
    // Mostrar contenedor de información
    const container = document.getElementById("info-paciente-container");
    container.classList.remove("d-none");

    // Actualizar datos
    document.getElementById("info-nombre").textContent = paciente.nombre;
    document.getElementById("info-dni").textContent = paciente.dni;
    document.getElementById("info-edad").textContent = paciente.edad;
    document.getElementById("info-sexo").textContent = paciente.sexo;
    document.getElementById("info-telefono").textContent = paciente.celular;

    // Mostrar última fecha de registro si está disponible (simulado)
    const ultimoRegistro = "No disponible";
    document.getElementById("info-ultimo-registro").textContent =
      ultimoRegistro;
  }

  /**
   * Muestra el panel de datos antropométricos
   */
  function mostrarPanelDatos() {
    // Ocultar panel de preguntas
    document.getElementById("panel-test").classList.add("d-none");

    // Ocultar panel de paciente
    document.getElementById("panel-paciente").classList.add("d-none");

    // Mostrar panel de datos
    document.getElementById("panel-datos").classList.remove("d-none");

    // Actualizar información del paciente
    document.getElementById("datos-nombre-paciente").textContent =
      pacienteSeleccionado.nombre;

    // Actualizar la ultima edad registrada
    document.getElementById("ultima-edad").textContent =
      pacienteSeleccionado.edad;

    // Actualizar último peso y altura registrados
    document.getElementById("ultimo-peso").textContent =
      pacienteSeleccionado.peso
        ? pacienteSeleccionado.peso + " kg"
        : "No disponible";
    document.getElementById("ultima-altura").textContent =
      pacienteSeleccionado.altura
        ? pacienteSeleccionado.altura + " m"
        : "No disponible";

    // Prellenar los campos con los valores del paciente
    if (pacienteSeleccionado.edad) {
      document.getElementById("edad").value = pacienteSeleccionado.edad;
    }

    if (pacienteSeleccionado.peso) {
      document.getElementById("peso").value = pacienteSeleccionado.peso;
    }

    if (pacienteSeleccionado.altura) {
      document.getElementById("altura").value = pacienteSeleccionado.altura;
    }

    // Calcular IMC si hay datos
    calcularIMC();

    // Actualizar indicadores de progreso
    document.getElementById("step-paciente").classList.remove("active");
    document.getElementById("step-paciente").classList.add("completed");
    document.getElementById("step-datos").classList.add("active");
  }

  /**
   * Muestra el panel de selección de paciente
   */
  function mostrarPanelPaciente() {
    // Ocultar panel de datos
    document.getElementById("panel-datos").classList.add("d-none");

    // Mostrar panel de paciente
    document.getElementById("panel-paciente").classList.remove("d-none");

    // Actualizar indicadores de progreso
    document.getElementById("step-datos").classList.remove("active");
    document.getElementById("step-paciente").classList.add("active");
    document.getElementById("step-paciente").classList.remove("completed");
  }

  /**
   * Calcula el IMC y actualiza la interfaz
   */
  function calcularIMC() {
    const pesoInput = document.getElementById("peso");
    const alturaInput = document.getElementById("altura");

    if (pesoInput.value && alturaInput.value) {
      const peso = parseFloat(pesoInput.value);
      const altura = parseFloat(alturaInput.value);

      if (peso > 0 && altura > 0) {
        // Convertir altura de cm a m si es necesario
        const alturaEnMetros = altura > 3 ? altura / 100 : altura;

        const imc = peso / (alturaEnMetros * alturaEnMetros);

        // Guardar los valores
        datosPaciente.peso = peso;
        datosPaciente.altura = altura;
        datosPaciente.imc = imc;

        // Actualizar interfaz
        document.getElementById("valor-imc").textContent = imc.toFixed(1);

        // Determinar categoría del IMC
        let categoria, color;
        if (imc < 18.5) {
          categoria = "Bajo peso";
          color = "text-info";
        } else if (imc < 25) {
          categoria = "Normal";
          color = "text-success";
        } else if (imc < 30) {
          categoria = "Sobrepeso";
          color = "text-warning";
        } else if (imc < 35) {
          categoria = "Obesidad I";
          color = "text-danger";
        } else {
          categoria = "Obesidad II-III";
          color = "text-danger";
        }

        document.getElementById("categoria-imc").textContent = categoria;
        document.getElementById("categoria-imc").className = color;

        return imc;
      }
    }

    // Si no hay datos válidos
    document.getElementById("valor-imc").textContent = "--";
    document.getElementById("categoria-imc").textContent = "";

    return 0;
  }

  /**
   * Inicia el test después de completar los datos antropométricos
   */
  function iniciarTest() {
    // Validar que los datos estén completos
    const peso = document.getElementById("peso").value;
    const altura = document.getElementById("altura").value;
    const edad = document.getElementById("edad").value;

    if (!peso || !altura || !edad) {
      Swal.fire({
        title: "Datos incompletos",
        text: "Por favor, complete los campos de edad, peso y altura.",
        icon: "warning",
      });
      return;
    }

    // Actualizar datos del paciente
    datosPaciente.edad = parseInt(edad);
    datosPaciente.peso = parseFloat(peso);
    datosPaciente.altura = parseFloat(altura);
    datosPaciente.imc = calcularIMC();

    // Ocultar panel de datos
    document.getElementById("panel-datos").classList.add("d-none");

    // Mostrar panel de test
    document.getElementById("panel-test").classList.remove("d-none");

    // Actualizar información del paciente en el panel del test
    document.getElementById("test-nombre-paciente").textContent =
      pacienteSeleccionado.nombre;
    document.getElementById("test-imc").textContent =
      datosPaciente.imc.toFixed(1);

    // Actualizar indicadores de progreso
    document.getElementById("step-datos").classList.remove("active");
    document.getElementById("step-datos").classList.add("completed");
    document.getElementById("step-test").classList.add("active");

    // Cargar preguntas del test
    cargarPreguntas();
  }

  /**
   * Carga las preguntas desde el servidor
   */
  function cargarPreguntas() {
    fetch("/admin/diagnosticos/obtener-preguntas")
      .then((response) => response.json())
      .then((data) => {
        preguntas = data.preguntas;
        preguntasTotales = data.total_preguntas;

        // Limpiar el contenedor
        testContainer.innerHTML = "";

        // Crear contenedor desde template
        const contenedor = templateContenedor.content.cloneNode(true);
        const preguntasContainer = contenedor.querySelector(
          ".preguntas-container"
        );

        // Agregar cada pregunta al contenedor
        preguntas.forEach((pregunta, index) => {
          // Agregar separador cada 5 preguntas (excepto al inicio)
          if (index > 0 && index % 5 === 0) {
            const separador = document.createElement("div");
            separador.className =
              "my-4 py-2 border-top border-bottom text-start bg-light rounded";
            separador.innerHTML = `<h5 class="text-muted m-0">Sección ${Math.ceil(
              (index + 1) / 5
            )}</h5>`;
            preguntasContainer.appendChild(separador);
          }

          const preguntaEl = crearElementoPregunta(pregunta);
          preguntasContainer.appendChild(preguntaEl);
        });

        testContainer.appendChild(contenedor);

        // Actualizar UI
        actualizarProgreso();
      })
      .catch((error) => {
        console.error("Error al cargar preguntas:", error);
        testContainer.innerHTML = `
          <div class="alert alert-danger" role="alert">
            Error al cargar las preguntas. Por favor, intente nuevamente.
          </div>
        `;
      });
  }

  /**
   * Crea un elemento para una pregunta
   */
  function crearElementoPregunta(pregunta) {
    const preguntaEl = templatePregunta.content.cloneNode(true);
    const titulo = preguntaEl.querySelector(".pregunta-titulo");
    const opcionesContainer = preguntaEl.querySelector(".opciones-container");

    // Establecer título
    titulo.textContent = `${pregunta.orden}. ${pregunta.titulo}`;

    // Agregar atributo data-id
    const pregItem = preguntaEl.querySelector(".pregunta-item");
    pregItem.setAttribute("data-id", pregunta.id_pregunta);

    // Crear opciones de respuesta
    if (pregunta.respuestas && pregunta.respuestas.length > 0) {
      pregunta.respuestas.forEach((respuesta) => {
        const opcionBtn = document.createElement("button");
        opcionBtn.className = "opcion-btn";
        opcionBtn.textContent = respuesta.contenido;
        opcionBtn.setAttribute("data-id", respuesta.id_respuesta);
        opcionBtn.setAttribute("data-pregunta-id", pregunta.id_pregunta);

        // Marcar como seleccionada si ya fue respondida
        if (
          respuestasUsuario[pregunta.id_pregunta] === respuesta.id_respuesta
        ) {
          opcionBtn.classList.add("selected");
        }

        // Event listener para seleccionar respuesta
        opcionBtn.addEventListener("click", function () {
          // Deseleccionar todas las opciones de esta pregunta
          const opciones = opcionesContainer.querySelectorAll(".opcion-btn");
          opciones.forEach((op) => op.classList.remove("selected"));

          // Seleccionar esta opción
          this.classList.add("selected");

          // Guardar respuesta
          respuestasUsuario[pregunta.id_pregunta] = respuesta.id_respuesta;

          // Actualizar la barra de progreso
          actualizarProgreso();
        });

        opcionesContainer.appendChild(opcionBtn);
      });
    }

    return preguntaEl;
  }

  /**
   * Actualiza la barra de progreso
   */
  function actualizarProgreso() {
    const preguntasRespondidas = Object.keys(respuestasUsuario).length;
    const porcentaje = Math.floor(
      (preguntasRespondidas / preguntasTotales) * 100
    );

    progressBar.style.width = `${porcentaje}%`;
    progressBar.textContent = `${porcentaje}% (${preguntasRespondidas}/${preguntasTotales})`;
    progressBar.setAttribute("aria-valuenow", porcentaje);

    // Cambiar color según el progreso
    if (porcentaje < 30) {
      progressBar.className =
        "progress-bar progress-bar-striped progress-bar-animated bg-danger";
    } else if (porcentaje < 70) {
      progressBar.className =
        "progress-bar progress-bar-striped progress-bar-animated bg-warning";
    } else {
      progressBar.className =
        "progress-bar progress-bar-striped progress-bar-animated bg-success";
    }

    // Activar el botón de finalizar si hay al menos una respuesta
    btnFinalizar.disabled = preguntasRespondidas === 0;
  }

  /**
   * Finaliza el test y envía las respuestas
   */
  function finalizarTest() {
    const preguntasRespondidas = Object.keys(respuestasUsuario).length;

    // Validar que todas las preguntas estén respondidas
    if (preguntasRespondidas < preguntasTotales) {
      Swal.fire({
        title: "¡Atención!",
        text: `Has respondido ${preguntasRespondidas} de ${preguntasTotales} preguntas. ¿Deseas continuar y enviar tus respuestas?`,
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Sí, finalizar",
        cancelButtonText: "No, revisar respuestas",
      }).then((result) => {
        if (result.isConfirmed) {
          enviarRespuestas();
        } else {
          // Resaltar las preguntas no respondidas
          resaltarPreguntasSinResponder();
        }
      });
    } else {
      enviarRespuestas();
    }
  }

  /**
   * Resalta visualmente las preguntas que no han sido respondidas
   */
  function resaltarPreguntasSinResponder() {
    // Limpiar resaltados anteriores
    document.querySelectorAll(".pregunta-sin-responder").forEach((el) => {
      el.classList.remove("pregunta-sin-responder");
    });

    // Identificar preguntas sin responder
    preguntas.forEach((pregunta) => {
      if (!respuestasUsuario[pregunta.id_pregunta]) {
        const preguntaEl = document.querySelector(
          `.pregunta-item[data-id="${pregunta.id_pregunta}"]`
        );
        preguntaEl.classList.add("pregunta-sin-responder");

        // Scroll hacia la primera pregunta sin responder
        if (!document.querySelector(".scrolled-to")) {
          preguntaEl.scrollIntoView({ behavior: "smooth", block: "center" });
          preguntaEl.classList.add("scrolled-to");

          // Quitar la clase después de un tiempo
          setTimeout(() => {
            document.querySelectorAll(".scrolled-to").forEach((el) => {
              el.classList.remove("scrolled-to");
            });
          }, 2000);
        }
      }
    });
  }

  /**
   * Envía las respuestas al servidor
   */
  function enviarRespuestas() {
    // verificar que respuestasUsuario no esté vacío
    if (Object.keys(respuestasUsuario).length === 0) {
      Swal.fire({
        title: "Advertencia",
        text: "No hay respuestas para enviar.",
        icon: "warning",
      });
      console.error("No hay respuestas para enviar.");
      return;
    }

    // Preparar datos para enviar
    const respuestasArray = [];
    for (const idPregunta in respuestasUsuario) {
      respuestasArray.push({
        id_pregunta: idPregunta,
        id_respuesta: respuestasUsuario[idPregunta],
      });
    }

    // Crear el objeto de datos completo para enviar
    const datosCompletos = {
      respuestas: respuestasArray,
      paciente: {
        id_paciente: pacienteSeleccionado.idpaciente,
        edad: datosPaciente.edad,
        peso: datosPaciente.peso,
        altura: datosPaciente.altura,
        imc: datosPaciente.imc,
      },
    };

    // Mostrar loader
    Swal.fire({
      title: "Procesando respuestas",
      html: "Por favor espere...",
      allowOutsideClick: false,
      didOpen: () => {
        Swal.showLoading();
      },
    });

    console.log("Datos a enviar:", datosCompletos);

    // Enviar al servidor
    fetch("/admin/diagnosticos/guardar-respuestas", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify(datosCompletos),
    })
      .then((response) => response.json())
      .then((data) => {
        Swal.close();

        if (data.success) {
          // Mostrar panel de resultados
          mostrarPanelResultados();

          // Calcular resultado
          mostrarResultados(data.resultado);
        } else {
          Swal.fire({
            title: "Error",
            text: data.message || "Hubo un problema al guardar tus respuestas.",
            icon: "error",
          });
        }
      })
      .catch((error) => {
        console.error("Error al enviar respuestas:", error);
        Swal.fire({
          title: "Error",
          text: "Hubo un problema al comunicarse con el servidor.",
          icon: "error",
        });
      });
  }

  /**
   * Muestra el panel de resultados
   */
  function mostrarPanelResultados() {
    // Ocultar panel del test
    document.getElementById("panel-test").classList.add("d-none");

    // Mostrar panel de resultados
    document.getElementById("panel-resultados").classList.remove("d-none");

    // Actualizar indicadores de progreso
    document.getElementById("step-test").classList.remove("active");
    document.getElementById("step-test").classList.add("completed");
    document.getElementById("step-resultados").classList.add("active");
  }

  /**
   * Muestra los resultados del test
   */
  function mostrarResultados(resultadoData) {
    console.log(resultadoData);

    // Si no hay datos de resultado, mostrar error
    if (!resultadoData || !resultadoData.analisis) {
      Swal.fire({
        title: "Error",
        text: "No se pudieron obtener los resultados del test.",
        icon: "error",
      });
      return;
    }

    // Obtener análisis y probabilidades
    const analisis = resultadoData.analisis;
    const probabilidades = analisis.probabilidades;

    // Determinar el nivel de riesgo basado en las probabilidades
    let nivelRiesgo, mensaje, color, porcentajeRiesgo, recomendaciones;

    // Caso de empate (si hay probabilidades iguales)
    if (analisis.clasificacion.includes("/")) {
      // En caso de empate, tomamos el nivel más alto por seguridad
      const niveles = analisis.clasificacion.split("/");
      nivelRiesgo = niveles[niveles.length - 1]; // Tomamos el último (asumiendo que está ordenado de menor a mayor riesgo)

      // Mensaje especial para empate
      mensaje = `Tu evaluación muestra un riesgo compartido entre niveles ${analisis.clasificacion}. Por precaución, consideramos el nivel más alto.`;

      // Determinar color y porcentaje según el nivel más alto
      if (nivelRiesgo === "Alto") {
        color = "danger";
        porcentajeRiesgo = probabilidades.alto;
        recomendaciones = analisis.recomendaciones.alto;
      } else if (nivelRiesgo === "Moderado") {
        color = "warning";
        porcentajeRiesgo = probabilidades.moderado;
        recomendaciones = analisis.recomendaciones.moderado;
      } else {
        nivelRiesgo = "Bajo";
        color = "success";
        porcentajeRiesgo = probabilidades.bajo;
        recomendaciones = analisis.recomendaciones.bajo;
      }
    } else {
      // Caso sin empate
      nivelRiesgo = analisis.clasificacion;

      if (nivelRiesgo === "Bajo") {
        mensaje =
          "Tu riesgo de desarrollar diabetes es bajo. Mantén un estilo de vida saludable.";
        color = "success";
        porcentajeRiesgo = probabilidades.bajo;
        recomendaciones = analisis.recomendaciones.bajo;
      } else if (nivelRiesgo === "Moderado") {
        mensaje =
          "Tu riesgo de desarrollar diabetes es moderado. Considera revisar tu dieta y aumentar tu actividad física.";
        color = "warning";
        porcentajeRiesgo = probabilidades.moderado;
        recomendaciones = analisis.recomendaciones.moderado;
      } else {
        nivelRiesgo = "Alto";
        mensaje =
          "Tu riesgo de desarrollar diabetes es alto. Te recomendamos consultar con un profesional de la salud.";
        color = "danger";
        porcentajeRiesgo = probabilidades.alto;
        recomendaciones = analisis.recomendaciones.alto;
      }
    }

    // Formatear la fecha actual
    const ahora = new Date();
    const fechaFormateada = ahora.toLocaleDateString("es-ES", {
      day: "2-digit",
      month: "2-digit",
      year: "numeric",
    });

    // Crear HTML para las recomendaciones
    let recomendacionesHTML = "";
    if (recomendaciones && recomendaciones.length) {
      recomendacionesHTML =
        '<div class="mt-3"><strong>Recomendaciones:</strong><ul>';
      recomendaciones.forEach((rec) => {
        recomendacionesHTML += `<li>${rec}</li>`;
      });
      recomendacionesHTML += "</ul></div>";
    }

    // Mostrar en el modal
    const resultadoContenido = document.getElementById("resultado-contenido");

    resultadoContenido.innerHTML = `
    <div class="row mb-4">
      <div class="col-md-6">
        <h5>Datos del Paciente</h5>
        <table class="table table-bordered">
          <tbody>
            <tr>
              <th scope="row">Nombre:</th>
              <td>${pacienteSeleccionado.nombre}</td>
            </tr>
            <tr>
              <th scope="row">DNI:</th>
              <td>${pacienteSeleccionado.dni}</td>
            </tr>
            <tr>
              <th scope="row">Edad:</th>
              <td>${pacienteSeleccionado.edad} años</td>
            </tr>
            <tr>
              <th scope="row">Sexo:</th>
              <td>${pacienteSeleccionado.sexo}</td>
            </tr>
          </tbody>
        </table>
      </div>
      <div class="col-md-6">
        <h5>Datos Antropométricos</h5>
        <table class="table table-bordered">
          <tbody>
            <tr>
              <th scope="row">Peso:</th>
              <td>${datosPaciente.peso} kg</td>
            </tr>
            <tr>
              <th scope="row">Altura:</th>
              <td>${datosPaciente.altura} m</td>
            </tr>
            <tr>
              <th scope="row">IMC:</th>
              <td>${datosPaciente.imc.toFixed(1)}</td>
            </tr>
            <tr>
              <th scope="row">Fecha del test:</th>
              <td>${fechaFormateada}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
    
    <div class="row">
      <div class="col-md-5">
        <div class="card border-${color} mb-4">
          <div class="card-header bg-${color} text-white">
            <h5 class="card-title text-white fw-bold mb-0">Resultado: Riesgo ${nivelRiesgo}</h5>
          </div>
          <div class="card-body py-4">
            <div class="text-center mb-3">
              <div class="result-gauge">
                <div class="gauge-value display-4">${Math.round(
                  porcentajeRiesgo
                )}%</div>
                <div class="progress mt-2" style="height: 25px;">
                  <div class="progress-bar bg-${color}" role="progressbar" style="width: ${porcentajeRiesgo}%;" 
                    aria-valuenow="${porcentajeRiesgo}" aria-valuemin="0" aria-valuemax="100">
                  </div>
                </div>
              </div>
            </div>
            <p class="lead">${mensaje}</p>
            ${recomendacionesHTML}
            <div class="small text-muted mt-3">
              <strong>Análisis:</strong> Bajo: ${Math.round(
                probabilidades.bajo
              )}%, 
              Moderado: ${Math.round(probabilidades.moderado)}%, 
              Alto: ${Math.round(probabilidades.alto)}%
            </div>
          </div>
        </div>
        <div class="card mb-4">
          <div class="card-header bg-light">
            <h5 class="card-title mb-0">Gráfico de Riesgo</h5>
          </div>
          <div class="card-body">
            <div id="chartContainer" style="height: 250px;" class="mb-3"></div>
            <div class="small text-muted text-center">
              El gráfico muestra factores de riesgo significativos de acuerdo a las respuestas
            </div>
          </div>
        </div>
      </div>
      
      <div class="col-md-7">
        <div class="card">
          <div class="card-header bg-light">
            <h5 class="card-title mb-0">Resumen de Respuestas</h5>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-striped table-hover">
                <thead>
                  <tr>
                    <th>Pregunta</th>
                    <th>Respuesta</th>
                    <th class="text-center">Valor</th>
                  </tr>
                </thead>
                <tbody>
                  ${generarResumenRespuestas()}
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
    
    <div class="alert alert-info mt-3">
      <i class="fas fa-info-circle mr-2"></i>
      Este test es informativo y no reemplaza el diagnóstico médico profesional. Si tiene preocupaciones sobre su salud, consulte con un médico.
    </div>
  `;

    document.getElementById("btn-imprimir").dataset.id = resultadoData.id_test;

    // Inicializar el gráfico después de que el DOM esté listo
    setTimeout(() => {
      renderizarGrafico(resultadoData);
    }, 100);
  }

  /**
   * Renderiza el gráfico de probabilidades
   */
  function renderizarGrafico(resultadoData) {
    // Si no hay datos de análisis, salir
    if (
      !resultadoData ||
      !resultadoData.analisis ||
      !resultadoData.analisis.probabilidades
    ) {
      console.error("No hay datos de análisis para renderizar el gráfico");
      return;
    }

    const probabilidades = resultadoData.analisis.probabilidades;

    // Preparar datos para el gráfico
    const labels = ["Bajo", "Moderado", "Alto"];
    const data = [
      probabilidades.bajo,
      probabilidades.moderado,
      probabilidades.alto,
    ];

    // Determinar colores para cada nivel
    const colores = [
      "rgba(40, 167, 69, 0.7)", // Verde para bajo
      "rgba(255, 193, 7, 0.7)", // Amarillo para moderado
      "rgba(220, 53, 69, 0.7)", // Rojo para alto
    ];

    // Obtener el elemento canvas
    const container = document.getElementById("chartContainer");
    container.innerHTML =
      '<canvas id="riskChart" width="400" height="250"></canvas>';
    const ctx = document.getElementById("riskChart").getContext("2d");

    // Crear el gráfico
    new Chart(ctx, {
      type: "bar",
      data: {
        labels: labels,
        datasets: [
          {
            label: "Probabilidad (%)",
            data: data,
            backgroundColor: colores,
            borderColor: colores.map((color) => color.replace("0.7", "1")),
            borderWidth: 1,
          },
          {
            label: "Tendencia",
            data: data,
            type: "line",
            borderColor: "rgba(0, 0, 0, 0.8)",
            backgroundColor: "transparent",
            pointBackgroundColor: "rgba(0, 0, 0, 0.8)",
            tension: 0.4,
            borderWidth: 2,
          },
        ],
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          title: {
            display: true,
            text: "Tendencia más probable",
            font: {
              size: 14,
              weight: "bold",
            },
          },
          legend: {
            display: true,
            position: "top",
            labels: {
              filter: function (item) {
                // Ocultar la etiqueta para el dataset de barras
                return item.text !== "Probabilidad (%)";
              },
            },
          },
        },
        scales: {
          y: {
            beginAtZero: true,
            max: Math.max(...data) * 1.2, // 10% más alto que el valor máximo
            title: {
              display: true,
              text: "Probabilidad (%)",
            },
          },
          x: {
            title: {
              display: true,
              text: "Nivel de Riesgo",
            },
          },
        },
      },
    });
  }

  /**
   * Abrevia un texto si es demasiado largo
   */
  function abreviarTexto(texto, maxLength) {
    if (texto.length <= maxLength) return texto;
    return texto.substring(0, maxLength) + "...";
  }

  /**
   * Obtiene colores para el gráfico según el valor
   */
  function obtenerColoresSegunValor(valores) {
    return valores.map((valor) => {
      if (valor === 1) return "rgba(255, 193, 7, 0.7)"; // Amarillo
      if (valor === 2) return "rgba(255, 152, 0, 0.7)"; // Naranja
      return "rgba(220, 53, 69, 0.7)"; // Rojo
    });
  }

  /**
   * Genera el HTML para la tabla de resumen de respuestas
   */
  function generarResumenRespuestas() {
    let html = "";

    for (const idPregunta in respuestasUsuario) {
      const idRespuesta = respuestasUsuario[idPregunta];

      // Buscar la información de la pregunta y respuesta
      const pregunta = preguntas.find((p) => p.id_pregunta == idPregunta);
      if (!pregunta) continue;

      const respuesta = pregunta.respuestas.find(
        (r) => r.id_respuesta == idRespuesta
      );
      if (!respuesta) continue;

      // Obtener el valor de la respuesta
      const valor =
        respuesta.metadatos &&
        respuesta.metadatos.valor_seleccionado !== undefined
          ? respuesta.metadatos.valor_seleccionado
          : 0;

      // Determinar clase de color basada en el valor
      let valorClass = "";
      if (valor === 0) valorClass = "text-success";
      else if (valor === 1) valorClass = "text-warning";
      else if (valor >= 2) valorClass = "text-danger";

      html += `
        <tr>
          <td>${abreviarTexto(pregunta.titulo, 50)}</td>
          <td>${respuesta.contenido}</td>
          <td class="text-center ${valorClass} font-weight-bold">${valor}</td>
        </tr>
      `;
    }

    return html;
  }

  /**
   * Función para imprimir los resultados
   */
  function imprimirResultados() {
    const testId = document.getElementById("btn-imprimir").dataset.id;
    const url = `/admin/diagnosticos/print/${testId}`;
    window.open(url, "_blank");
  }

  /**
   * Reinicia el test
   */
  function reiniciarTest() {
    // Limpiar respuestas
    respuestasUsuario = {};

    // Restablecer variables
    pacienteSeleccionado = null;
    datosPaciente = {
      edad: 0,
      peso: 0,
      altura: 0,
      imc: 0,
    };

    // Limpiar select2
    $("#paciente-select").val(null).trigger("change");

    // Ocultar todos los paneles excepto el primero
    document.getElementById("panel-resultados").classList.add("d-none");
    document.getElementById("panel-test").classList.add("d-none");
    document.getElementById("panel-datos").classList.add("d-none");
    document.getElementById("panel-paciente").classList.remove("d-none");

    // Resetear indicadores de progreso
    document.querySelectorAll(".step-item").forEach((item) => {
      item.classList.remove("active", "completed");
    });
    document.getElementById("step-paciente").classList.add("active");

    // Limpiar información del paciente
    document.getElementById("info-paciente-container").classList.add("d-none");
    document.getElementById("btn-continuar-datos").disabled = true;
  }

  // Cuando el DOM esté cargado, inicializar
  document.addEventListener("DOMContentLoaded", init);
})();
