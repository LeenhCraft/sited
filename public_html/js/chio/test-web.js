"use strict";

document.addEventListener("DOMContentLoaded", function () {
  // Variables globales
  let preguntas = [];
  let preguntasTotales = 0;
  let respuestasUsuario = {};
  let datosUsuario = {
    id: userID || 0,
    id_paciente: pacienteID || 0,
    nombre: userName || "Usuario",
    email: userEmail || "",
    edad: 0,
    peso: 0,
    altura: 0,
    imc: 0,
  };

  // Elementos del DOM
  const testContainer = document.getElementById("test-container");
  const btnFinalizar = document.getElementById("btn-finalizar");
  const progressBar = document.getElementById("progress-bar");
  const templateContenedor = document.getElementById("template-contenedor");
  const templatePregunta = document.getElementById("template-pregunta");

  // Elementos de navegación entre pasos
  const stepDatosTab = document.getElementById("step-datos-tab");
  const stepTestTab = document.getElementById("step-test-tab");
  const stepResultadosTab = document.getElementById("step-resultados-tab");

  // Event listeners
  document
    .getElementById("form-datos-antropometricos")
    .addEventListener("submit", function (e) {
      e.preventDefault();
      iniciarTest();
    });

  document
    .getElementById("btn-volver-datos")
    .addEventListener("click", function () {
      document.getElementById("panel-test").classList.add("d-none");
      document.getElementById("panel-datos").classList.remove("d-none");

      // Actualizar tabs
      stepTestTab.classList.remove("active");
      stepDatosTab.classList.add("active");
      stepTestTab.classList.add("disabled");
    });

  document
    .getElementById("btn-finalizar")
    .addEventListener("click", finalizarTest);
  document
    .getElementById("btn-reiniciar")
    .addEventListener("click", reiniciarTest);
  document
    .getElementById("btn-imprimir")
    .addEventListener("click", imprimirResultados);

  document
    .getElementById("btn-agendar-cita")
    .addEventListener("click", abrirModalAgendarCita);

  // Calcular IMC cuando cambian los valores
  document.getElementById("peso").addEventListener("input", calcularIMC);
  document.getElementById("altura").addEventListener("input", calcularIMC);
  document.getElementById("edad").addEventListener("input", function () {
    datosUsuario.edad = parseInt(this.value) || 0;
  });

  // Event listener para el botón de confirmación de cita
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
      const id_test = document.querySelector("#btn-agendar-cita").dataset.id;

      // Crear objeto con los datos de la cita
      const datosCita = {
        fecha: fecha,
        hora: hora,
        observaciones: observaciones,
        especialidad: especialidad,
        medico: medico,
        id_paciente: datosUsuario.id_paciente,
        id_usuario: datosUsuario.id,
        id_test: id_test,
        // nivel_riesgo: obtenerNivelRiesgoActual(),
      };

      // Enviar la solicitud de cita
      enviarSolicitudCita(datosCita);
    });

  // Calcular IMC inicial si hay datos
  calcularIMC();

  /**
   * Calcula el IMC y actualiza la interfaz
   */
  function calcularIMC() {
    const pesoInput = document.getElementById("peso");
    const alturaInput = document.getElementById("altura");

    if (pesoInput.value && alturaInput.value) {
      const peso = parseFloat(pesoInput.value);
      let altura = parseFloat(alturaInput.value);

      if (peso > 0 && altura > 0) {
        // Convertir altura de cm a m si es necesario
        const alturaEnMetros = altura > 3 ? altura / 100 : altura;

        const imc = peso / (alturaEnMetros * alturaEnMetros);

        // Guardar los valores
        datosUsuario.peso = peso;
        datosUsuario.altura = altura;
        datosUsuario.imc = imc;

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

    // Actualizar datos del usuario
    datosUsuario.edad = parseInt(edad);
    datosUsuario.peso = parseFloat(peso);
    datosUsuario.altura = parseFloat(altura);
    datosUsuario.imc = calcularIMC();

    // Ocultar panel de datos
    document.getElementById("panel-datos").classList.add("d-none");

    // Mostrar panel de test
    document.getElementById("panel-test").classList.remove("d-none");

    // Actualizar información del usuario en el panel del test
    document.getElementById("test-imc").textContent =
      datosUsuario.imc.toFixed(1);

    // Actualizar tabs
    stepDatosTab.classList.remove("active");
    stepDatosTab.classList.add("completed");
    stepTestTab.classList.remove("disabled");
    stepTestTab.classList.add("active");

    // Cargar preguntas del test
    cargarPreguntas();
  }

  /**
   * Carga las preguntas desde el servidor
   */
  function cargarPreguntas() {
    fetch("/sited/obtener-preguntas", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({ usuario: datosUsuario }),
    })
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
              "my-4 py-3 text-center bg-light rounded-lg shadow-sm";
            separador.innerHTML = `<h5 class="text-primary m-0">Sección ${Math.ceil(
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
                        <div class="d-flex">
                            <div class="me-3">
                                <i class="fas fa-exclamation-triangle fa-2x"></i>
                            </div>
                            <div>
                                <h5 class="alert-heading">Error al cargar preguntas</h5>
                                <p class="mb-0">No pudimos cargar las preguntas del test. Por favor, intenta nuevamente o contacta a soporte.</p>
                            </div>
                        </div>
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
      usuario: {
        id_usuario: datosUsuario.id,
        id_paciente: datosUsuario.id_paciente,
        nombre: datosUsuario.nombre,
        email: datosUsuario.email,
        edad: datosUsuario.edad,
        peso: datosUsuario.peso,
        altura: datosUsuario.altura,
        imc: datosUsuario.imc,
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

    // Enviar al servidor
    fetch("/sited/test/guardar-respuestas", {
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

          // Agregar el idtest al boton de imprimir
          document.querySelector("#btn-imprimir").dataset.id =
            data.resultado.id_test;

          // asignar el id del test al boton de agendar cita
          document.querySelector("#btn-agendar-cita").dataset.id =
            data.resultado.id_test;

          // Asegurar que la página se desplaza hacia arriba
          setTimeout(() => {
            window.scrollTo({ top: 0, behavior: "smooth" });
          }, 300);
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

    // Actualizar tabs
    stepTestTab.classList.remove("active");
    stepTestTab.classList.add("completed");
    stepResultadosTab.classList.remove("disabled");
    stepResultadosTab.classList.add("active");
  }

  /**
   * Muestra los resultados del test
   */
  function mostrarResultados(resultadoData) {
    console.log(resultadoData);

    // Si no hay datos de resultado, mostrar error
    if (!resultadoData || !resultadoData.analisis) {
      document.getElementById("resultado-contenido").innerHTML = `
                <div class="alert alert-danger">
                    <div class="d-flex">
                        <div class="me-3">
                            <i class="fas fa-exclamation-circle fa-2x"></i>
                        </div>
                        <div>
                            <h5 class="alert-heading">Error en los resultados</h5>
                            <p class="mb-0">No se pudieron obtener los resultados del test. Por favor, inténtalo nuevamente.</p>
                        </div>
                    </div>
                </div>
            `;
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
        '<div class="mt-4"><h6 class="fw-bold">Recomendaciones personalizadas:</h6><ul class="list-group list-group-flush">';
      recomendaciones.forEach((rec) => {
        recomendacionesHTML += `<li class="list-group-item bg-transparent border-0 ps-0"><i class="fas fa-check-circle text-${color} me-2"></i>${rec}</li>`;
      });
      recomendacionesHTML += "</ul></div>";
    }

    // Mostrar en el contenedor de resultados
    const resultadoContenido = document.getElementById("resultado-contenido");

    resultadoContenido.innerHTML = `
        <div class="row mb-4">
          <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-lg mb-4">
              <div class="card-header bg-white border-bottom-0">
                <h5 class="card-title m-0">Datos personales</h5>
              </div>
              <div class="card-body pt-0">
                <table class="table table-borderless">
                  <tbody>
                    <tr>
                      <th scope="row" width="40%">Nombre:</th>
                      <td>${datosUsuario.nombre}</td>
                    </tr>
                    <tr>
                      <th scope="row">Edad:</th>
                      <td>${datosUsuario.edad} años</td>
                    </tr>
                    <tr>
                      <th scope="row">Peso:</th>
                      <td>${datosUsuario.peso} kg</td>
                    </tr>
                    <tr>
                      <th scope="row">Altura:</th>
                      <td>${datosUsuario.altura} m</td>
                    </tr>
                    <tr>
                      <th scope="row">IMC:</th>
                      <td>${datosUsuario.imc.toFixed(1)}</td>
                    </tr>
                    <tr>
                      <th scope="row">Fecha del test:</th>
                      <td>${fechaFormateada}</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
          
          <div class="col-md-6">
            <div class="card border-${color} shadow-sm rounded-lg mb-4">
              <div class="card-header bg-${color} text-white">
                <h5 class="card-title m-0 text-white">
                  <i class="fas fa-chart-pie me-2"></i> Resultado: Riesgo ${nivelRiesgo}
                </h5>
              </div>
              <div class="card-body">
                <div class="text-center mb-4">
                  <div class="display-4 fw-bold text-${color}">
                  ${Math.round(porcentajeRiesgo)}%
                  </div>
                  <div class="progress mt-2" style="height: 25px;">
                    <div class="progress-bar bg-${color}" role="progressbar" style="width: ${porcentajeRiesgo}%;" 
                      aria-valuenow="${porcentajeRiesgo}" aria-valuemin="0" aria-valuemax="100">
                    </div>
                  </div>
                </div>
                <p class="lead">${mensaje}</p>
                ${recomendacionesHTML}
              </div>
            </div>
          </div>
        </div>
        
        <div class="row">
          <div class="col-12">
            <div class="card border-0 shadow-sm rounded-lg">
              <div class="card-header bg-white">
                <h5 class="card-title m-0">
                  <i class="fas fa-list-check me-2"></i> Resumen de respuestas
                </h5>
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
        
        <div class="alert alert-info mt-4 rounded-lg shadow-sm">
          <div class="d-flex">
            <div class="me-3">
              <i class="fas fa-info-circle fa-2x"></i>
            </div>
            <div>
              <h5 class="alert-heading">Información importante</h5>
              <p class="mb-0">Este test es informativo y no reemplaza el diagnóstico médico profesional. Si tienes preocupaciones sobre tu salud, consulta con un médico.</p>
            </div>
          </div>
        </div>
      `;
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
                <td class="text-center ${valorClass} fw-bold">${valor}</td>
              </tr>
            `;
    }

    return html;
  }

  /**
   * Abrevia un texto si es demasiado largo
   */
  function abreviarTexto(texto, maxLength) {
    if (texto.length <= maxLength) return texto;
    return texto.substring(0, maxLength) + "...";
  }

  /**
   * Función para imprimir los resultados
   */
  function imprimirResultados() {
    const id = document.querySelector("#btn-imprimir").dataset.id;
    const url = `/admin/lista-test/print/${id}`;
    window.open(url, "_blank");
  }

  /**
   * Reinicia el test
   */
  function reiniciarTest() {
    // Limpiar respuestas
    respuestasUsuario = {};

    // Ocultar todos los paneles excepto el primero
    document.getElementById("panel-resultados").classList.add("d-none");
    document.getElementById("panel-test").classList.add("d-none");
    document.getElementById("panel-datos").classList.remove("d-none");

    // Resetear indicadores de progreso
    stepResultadosTab.classList.remove("active");
    stepTestTab.classList.remove("active", "completed");
    stepDatosTab.classList.add("active");
    stepTestTab.classList.add("disabled");
    stepResultadosTab.classList.add("disabled");

    // Volver a calcular el IMC por si los datos han cambiado
    calcularIMC();
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
            // Si hay múltiples médicos, seleccionamos el primero
            const idMedico = medicosIds.split(",")[0];
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

  // Función para abrir el modal de agendar cita
  async function abrirModalAgendarCita() {
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

  // Función para obtener la fecha mínima (día siguiente)
  function obtenerFechaMinima() {
    const hoy = new Date();
    const manana = new Date(hoy);
    manana.setDate(manana.getDate() + 1);
    return manana.toISOString().split("T")[0];
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

  // Función para formatear la fecha
  function formatearFecha(fechaStr) {
    const fecha = new Date(fechaStr);
    return fecha.toLocaleDateString("es-ES", {
      weekday: "long",
      year: "numeric",
      month: "long",
      day: "numeric",
    });
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
    document.getElementById("resumen-paciente").textContent =
      datosUsuario.nombre;
    document.getElementById("resumen-especialidad").textContent =
      document.getElementById("especialidad-recomendada").textContent;
    document.getElementById("resumen-medico").textContent =
      nombreMedico || "Asignación automática";
    document.getElementById(
      "resumen-fecha-hora"
    ).textContent = `${formatearFecha(
      fechaSeleccionada
    )} - ${horaSeleccionada}`;

    // Establecer el médico seleccionado
    document.getElementById("medico").value = idMedico;

    // Mostrar el resumen
    document.getElementById("resumen-cita").classList.remove("d-none");
  }
});
