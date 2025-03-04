"use strict";

(() => {
  class PreguntasApp {
    constructor() {
      this.container = document.getElementById("preguntas-container");
      this.formPregunta = document.getElementById("form-nueva-pregunta");
      this.tipoRespuestaSelect = document.getElementById("tipo-respuesta");
      this.modalPregunta = document.getElementById("modal-nueva-pregunta");
      this.modalTitle = document.getElementById("modal-nueva-pregunta-label");
      this.dynamicFieldsContainer = document.getElementById(
        "dynamic-fields-container"
      );
      this.currentPreguntaId = null;
      this.tiposRespuesta = [];

      this.headers = {
        "Content-Type": "application/json",
        "X-Requested-With": "XMLHttpRequest",
      };

      if (!this.container) {
        console.error("No se encontró el contenedor de preguntas.");
        return;
      }

      this.init();
    }

    init() {
      this.loadQuestions();
      this.loadTiposRespuesta();
      this.setupEventListeners();
    }

    async loadQuestions() {
      try {
        const response = await fetch("/admin/preguntas/list", {
          method: "POST",
          headers: this.headers,
          credentials: "same-origin",
        });

        if (!response.ok) {
          throw new Error("Error en la solicitud");
        }

        const data = await response.json();
        if (data.success) {
          this.renderQuestions(data.data);
        } else {
          this.showError("Error al cargar preguntas: " + data.message);
        }
      } catch (error) {
        this.showError("Error de conexión");
        console.error("Error:", error);
      }
    }

    async loadTiposRespuesta() {
      try {
        const response = await fetch("/admin/preguntas/tipos-respuesta", {
          method: "GET",
          headers: this.headers,
          credentials: "same-origin",
        });

        if (!response.ok) {
          throw new Error("Error en la solicitud");
        }

        const data = await response.json();
        if (data.success) {
          this.tiposRespuesta = data.data;
          this.renderTiposRespuesta(data.data);
        } else {
          console.error("Error al cargar tipos de respuesta:", data.message);
        }
      } catch (error) {
        console.error("Error de conexión:", error);
      }
    }

    renderTiposRespuesta(tipos) {
      this.tipoRespuestaSelect.innerHTML =
        '<option value="">Seleccione un tipo</option>';

      tipos.forEach((tipo) => {
        const option = document.createElement("option");
        option.value = tipo.id_tipo_respuesta;
        option.textContent = tipo.nombre;
        this.tipoRespuestaSelect.appendChild(option);
      });
    }

    // renderQuestions(preguntas) {
    //   if (!preguntas.length) {
    //     this.container.innerHTML = `
    //       <div class="col-12">
    //         <div class="alert alert-info">
    //           No hay preguntas registradas. ¡Crea la primera!
    //         </div>
    //       </div>
    //     `;
    //     return;
    //   }

    //   this.container.innerHTML = preguntas
    //     .map(
    //       (pregunta) => `
    //         <div class="col-md-6 col-lg-4 mb-3">
    //           <div class="card h-100 shadow-sm">
    //             <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
    //               <span class="fw-semibold">${pregunta.titulo}</span>
    //               <span class="badge ${
    //                 pregunta.estado === "Activo" ? "bg-success" : "bg-warning"
    //               }">${pregunta.estado}</span>
    //             </div>

    //             <div class="card-body">
    //               <p class="card-text">${pregunta.contenido}</p>

    //               ${this.renderRespuestas(
    //                 pregunta.respuestas,
    //                 pregunta.tipo_respuesta
    //               )}

    //               <div class="mt-3">
    //                 <span class="badge bg-secondary">${
    //                   pregunta.tipo_respuesta || "Sin tipo"
    //                 }</span>
    //               </div>
    //             </div>

    //             <div class="card-footer bg-transparent">
    //               <div class="btn-group w-100">
    //                 ${this.renderActionButtons(pregunta.id_pregunta)}
    //               </div>
    //             </div>
    //           </div>
    //         </div>
    //       `
    //     )
    //     .join("");
    // }

    // renderRespuestas(respuestas, tipo) {
    //   if (!respuestas || !respuestas.length)
    //     return "<p class='text-muted small fst-italic'>Sin opciones de respuesta</p>";

    //   return `
    //       <div class="respuestas mt-3">
    //         <p class="small text-muted mb-1">
    //           <i class="bx bx-list-check me-1"></i>Opciones de respuesta:
    //         </p>
    //         <div class="p-2 border rounded bg-light">
    //           ${
    //             tipo === "Selección múltiple"
    //               ? respuestas
    //                   .map(
    //                     (r) =>
    //                       `<span class="badge bg-primary me-1 mb-1">${r}</span>`
    //                   )
    //                   .join("")
    //               : `<div class="small">${
    //                   respuestas[0] || "Respuesta abierta"
    //                 }</div>`
    //           }
    //         </div>
    //       </div>
    //     `;
    // }

    renderActionButtons(id) {
      return `
          <button class="btn btn-sm btn-outline-primary btn-editar" data-id="${id}">
            <i class="bx bx-edit-alt"></i> Editar
          </button>
          <button class="btn btn-sm btn-outline-danger btn-eliminar" data-id="${id}">
            <i class="bx bx-trash-alt"></i> Eliminar
          </button>
        `;
    }

    renderCamposPorTipo(tipo) {
      console.log("Tipo seleccionado:", tipo);

      // Limpiar contenedor de campos dinámicos
      this.dynamicFieldsContainer.innerHTML = "";

      const nombre = tipo.nombre;
      const idTipo = tipo.id_tipo_respuesta;

      // Crear campos específicos según el tipo de respuesta
      let camposHTML = "";

      switch (nombre) {
        case "Opción múltiple":
          camposHTML = `
            <div class="mb-3">
              <label class="form-label fw-semibold">Opciones (separadas por coma)</label>
              <input type="text" name="opciones" class="form-control" 
                placeholder="Ej: Opción 1, Opción 2, Opción 3" required>
              <div class="form-text">Ingrese todas las opciones posibles separadas por comas</div>
            </div>
            <div class="mb-3">
              <label class="form-label fw-semibold">Opción correcta</label>
              <input type="number" name="opcion_correcta" class="form-control" min="1" 
                placeholder="Número de la opción correcta (1, 2, 3...)" required>
              <div class="form-text">Indique el número de la opción correcta (la primera es 1)</div>
            </div>
          `;
          break;

        case "Selección múltiple":
          camposHTML = `
            <div class="mb-3">
              <label class="form-label fw-semibold">Opciones (separadas por coma)</label>
              <input type="text" name="opciones" class="form-control" 
                placeholder="Ej: Opción 1, Opción 2, Opción 3" required>
              <div class="form-text">Ingrese todas las opciones posibles separadas por comas</div>
            </div>
            <div class="mb-3">
              <label class="form-label fw-semibold">Opciones correctas (separadas por coma)</label>
              <input type="text" name="opciones_correctas" class="form-control" 
                placeholder="Ej: 1, 3, 5">
              <div class="form-text">Indique los números de las opciones correctas separados por comas (opcional)</div>
            </div>
          `;
          break;

        case "Verdadero/Falso":
          camposHTML = `
            <div class="mb-3">
              <label class="form-label fw-semibold">Respuesta correcta</label>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="respuesta_correcta" value="true" id="respuesta-verdadero">
                <label class="form-check-label" for="respuesta-verdadero">Verdadero</label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="respuesta_correcta" value="false" id="respuesta-falso">
                <label class="form-check-label" for="respuesta-falso">Falso</label>
              </div>
            </div>
          `;
          break;

        case "Respuesta corta":
          camposHTML = `
            <div class="mb-3">
              <label class="form-label fw-semibold">Longitud máxima (caracteres)</label>
              <input type="number" name="longitud_maxima" class="form-control" min="1" max="500" 
                placeholder="Ej: 100" value="100">
              <div class="form-text">Máximo número de caracteres permitidos</div>
            </div>
            <div class="mb-3">
              <label class="form-label fw-semibold">Respuesta ejemplo</label>
              <textarea name="respuesta_ejemplo" class="form-control" rows="2"
                placeholder="Ejemplo o patrón de respuesta esperada"></textarea>
            </div>
          `;
          break;

        case "Escala":
          camposHTML = `
            <div class="row">
              <div class="col-md-6 mb-3">
                <label class="form-label fw-semibold">Valor mínimo</label>
                <input type="number" name="valor_minimo" class="form-control" min="0" max="100" 
                  placeholder="Ej: 1" value="1">
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label fw-semibold">Valor máximo</label>
                <input type="number" name="valor_maximo" class="form-control" min="1" max="100" 
                  placeholder="Ej: 10" value="10">
              </div>
            </div>
            <div class="mb-3">
              <label class="form-label fw-semibold">Descripción de la escala</label>
              <textarea name="descripcion_escala" class="form-control" rows="2"
                placeholder="Ej: 1 = Poco dolor, 10 = Dolor extremo"></textarea>
            </div>
          `;
          break;

        case "Desarrollo":
          camposHTML = `
            <div class="mb-3">
              <label class="form-label fw-semibold">Palabras mínimas</label>
              <input type="number" name="palabras_minimas" class="form-control" min="1" 
                placeholder="Ej: 50" value="50">
              <div class="form-text">Número mínimo de palabras requeridas</div>
            </div>
            <div class="mb-3">
              <label class="form-label fw-semibold">Guía de respuesta</label>
              <textarea name="guia_respuesta" class="form-control" rows="3"
                placeholder="Instrucciones para responder"></textarea>
            </div>
          `;
          break;

        case "Escala Likert":
          camposHTML = `
            <div class="mb-3">
              <label class="form-label fw-semibold">Opciones de escala (separadas por coma)</label>
              <input type="text" name="opciones_escala" class="form-control" 
                placeholder="Ej: Totalmente en desacuerdo, En desacuerdo, Neutral, De acuerdo, Totalmente de acuerdo" 
                value="Totalmente en desacuerdo, En desacuerdo, Neutral, De acuerdo, Totalmente de acuerdo">
            </div>
            <div class="mb-3">
              <label class="form-label fw-semibold">Valores asociados (separados por coma)</label>
              <input type="text" name="valores_escala" class="form-control" 
                placeholder="Ej: 1, 2, 3, 4, 5" value="1, 2, 3, 4, 5">
              <div class="form-text">Los valores numéricos asociados a cada opción (en el mismo orden)</div>
            </div>
          `;
          break;

        default:
          camposHTML = `
            <div class="mb-3">
              <label class="form-label fw-semibold">Contenido de respuesta</label>
              <textarea name="contenido_respuesta" class="form-control" rows="3"
                placeholder="Texto predeterminado o ejemplo de respuesta"></textarea>
            </div>
          `;
      }

      this.dynamicFieldsContainer.innerHTML = `
        <div class="border rounded p-3 mb-3 bg-light">
          <h6 class="mb-3">
            <i class="bx bx-customize me-1"></i>
            Configuración para: ${nombre}
          </h6>
          ${camposHTML}
        </div>
      `;
    }

    renderQuestions(preguntas) {
      if (!preguntas.length) {
        this.container.innerHTML = `
          <div class="col-12">
            <div class="alert alert-info">
              No hay preguntas registradas. ¡Crea la primera!
            </div>
          </div>
        `;
        return;
      }

      this.container.innerHTML = preguntas
        .map(
          (pregunta) => `
            <div class="col-md-6 col-lg-4 mb-3">
              <div class="card h-100 shadow-sm">
                <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
                  <span class="fw-semibold">${pregunta.titulo}</span>
                  <span class="badge ${
                    pregunta.estado === "Activo" ? "bg-success" : "bg-warning"
                  }">${pregunta.estado}</span>
                </div>
                
                <div class="card-body">
                  <div class="small text-muted mb-2">Orden: ${
                    pregunta.orden
                  }</div>
                  <p class="card-text">${pregunta.contenido}</p>
                  
                  ${this.renderRespuestas(
                    pregunta.respuestas,
                    pregunta.tipo_respuesta
                  )}
                  
                  <div class="mt-3">
                    <span class="badge bg-secondary">${
                      pregunta.tipo_respuesta || "Sin tipo"
                    }</span>
                  </div>
                </div>
                
                <div class="card-footer bg-transparent">
                  <div class="btn-group w-100">
                    ${this.renderActionButtons(pregunta.id_pregunta)}
                  </div>
                </div>
              </div>
            </div>
          `
        )
        .join("");
    }

    renderRespuestas(respuestas, tipo) {
      if (!respuestas || !respuestas.length)
        return "<p class='text-muted small fst-italic'>Sin opciones de respuesta</p>";

      // Renderizado específico según el tipo de respuesta
      switch (tipo) {
        case "Opción múltiple":
        case "Selección múltiple":
          return `
            <div class="respuestas mt-3">
              <p class="small text-muted mb-1">
                <i class="bx bx-list-check me-1"></i>Opciones de respuesta:
              </p>
              <div class="p-2 border rounded bg-light">
                ${respuestas
                  .map(
                    (r) =>
                      `<span class="badge bg-primary me-1 mb-1">${r}</span>`
                  )
                  .join("")}
              </div>
            </div>
          `;

        case "Verdadero/Falso":
          return `
            <div class="respuestas mt-3">
              <p class="small text-muted mb-1">
                <i class="bx bx-check-circle me-1"></i>Opciones:
              </p>
              <div class="p-2 border rounded bg-light">
                <div class="form-check form-check-inline">
                  <input class="form-check-input" type="radio" disabled>
                  <label class="form-check-label">Verdadero</label>
                </div>
                <div class="form-check form-check-inline">
                  <input class="form-check-input" type="radio" disabled>
                  <label class="form-check-label">Falso</label>
                </div>
              </div>
            </div>
          `;

        case "Escala":
        case "Escala Likert":
          return `
            <div class="respuestas mt-3">
              <p class="small text-muted mb-1">
                <i class="bx bx-slider me-1"></i>Escala:
              </p>
              <div class="p-2 border rounded bg-light">
                ${respuestas
                  .map(
                    (r) => `<span class="badge bg-info me-1 mb-1">${r}</span>`
                  )
                  .join("")}
              </div>
            </div>
          `;

        default:
          return `
            <div class="respuestas mt-3">
              <p class="small text-muted mb-1">
                <i class="bx bx-text me-1"></i>Tipo de respuesta:
              </p>
              <div class="p-2 border rounded bg-light">
                <div class="small">${tipo || "Respuesta abierta"}</div>
              </div>
            </div>
          `;
      }
    }

    setupEventListeners() {
      // Botón para abrir el modal de nueva pregunta
      document
        .getElementById("btn-nueva-pregunta")
        .addEventListener("click", () => {
          this.resetForm();
          this.modalTitle.innerHTML =
            '<i class="bx bx-plus-circle me-1"></i>Nueva Pregunta';
          this.currentPreguntaId = null;
          new bootstrap.Modal(this.modalPregunta).show();
        });

      // Botón para guardar la pregunta
      document
        .getElementById("btn-guardar-pregunta")
        .addEventListener("click", () => {
          this.guardarPregunta();
        });

      // Cambio de tipo de respuesta
      this.tipoRespuestaSelect.addEventListener("change", (e) => {
        const tipoId = e.target.value;

        if (!tipoId) {
          this.dynamicFieldsContainer.innerHTML = "";
          return;
        }

        // Encontrar el tipo seleccionado
        const tipoSeleccionado = this.tiposRespuesta.find(
          (tipo) => tipo.id_tipo_respuesta == tipoId
        );

        if (tipoSeleccionado) {
          // Renderizar campos según el tipo de respuesta
          this.renderCamposPorTipo(tipoSeleccionado);
        }
      });

      // Delegación de eventos para acciones
      this.container.addEventListener("click", (e) => {
        const target = e.target.closest("button");
        if (!target) return;

        const id = target.dataset.id;
        if (target.classList.contains("btn-editar")) this.handleEditar(id);
        if (target.classList.contains("btn-eliminar")) this.handleEliminar(id);
      });
    }

    resetForm() {
      this.formPregunta.reset();

      // Limpiar campos dinámicos
      this.dynamicFieldsContainer.innerHTML = "";

      // Reset select de tipo de respuesta
      this.tipoRespuestaSelect.innerHTML =
        '<option value="">Seleccione un tipo</option>';

      // Repoblar los tipos de respuesta
      if (this.tiposRespuesta.length > 0) {
        this.renderTiposRespuesta(this.tiposRespuesta);
      } else {
        this.loadTiposRespuesta();
      }

      // Reset ID actual
      this.currentPreguntaId = null;
    }

    prepararMetadatos(formData, tipoNombre) {
      let metadatos = {};

      switch (tipoNombre) {
        case "Opción múltiple":
          const opciones = formData
            .get("opciones")
            .split(",")
            .map((op) => op.trim());
          const opcionCorrecta =
            parseInt(formData.get("opcion_correcta"), 10) - 1; // Ajustar índice a base 0

          metadatos = {
            opciones: opciones,
            correcta: opcionCorrecta,
          };
          break;

        case "Selección múltiple":
          const opcionesMultiples = formData
            .get("opciones")
            .split(",")
            .map((op) => op.trim());

          metadatos = {
            opciones: opcionesMultiples,
          };

          // Opciones correctas (opcional)
          if (formData.get("opciones_correctas")) {
            // Convertir los índices a base 0 (el usuario ingresa 1,2,3 pero en el array son 0,1,2)
            const opcionesCorrectas = formData
              .get("opciones_correctas")
              .split(",")
              .map((num) => parseInt(num.trim(), 10) - 1)
              .filter((num) => !isNaN(num)); // Filtrar valores no numéricos

            metadatos.correctas = opcionesCorrectas;
          }
          break;

        case "Verdadero/Falso":
          metadatos = {
            correcta: formData.get("respuesta_correcta") === "true",
          };
          break;

        case "Respuesta corta":
          metadatos = {
            longitud_maxima: parseInt(formData.get("longitud_maxima"), 10),
          };

          // Incluir respuesta ejemplo si existe
          if (formData.get("respuesta_ejemplo")) {
            metadatos.ejemplo = formData.get("respuesta_ejemplo");
          }
          break;

        case "Escala":
          metadatos = {
            min: parseInt(formData.get("valor_minimo"), 10),
            max: parseInt(formData.get("valor_maximo"), 10),
          };

          // Descripción opcional
          if (formData.get("descripcion_escala")) {
            metadatos.descripcion = formData.get("descripcion_escala");
          }
          break;

        case "Desarrollo":
          metadatos = {
            palabras_minimas: parseInt(formData.get("palabras_minimas"), 10),
          };

          // Guía opcional
          if (formData.get("guia_respuesta")) {
            metadatos.guia = formData.get("guia_respuesta");
          }
          break;

        case "Escala Likert":
          const opcionesLikert = formData
            .get("opciones_escala")
            .split(",")
            .map((op) => op.trim());
          const valoresLikert = formData
            .get("valores_escala")
            .split(",")
            .map((val) => parseInt(val.trim(), 10));

          metadatos = {
            opciones: opcionesLikert,
            valores: valoresLikert,
          };
          break;

        default:
          metadatos = {};
      }

      return JSON.stringify(metadatos);
    }

    async guardarPregunta() {
      if (!this.validarFormulario()) {
        return;
      }

      try {
        // Preparar los datos según el tipo de respuesta
        const formData = new FormData(this.formPregunta);
        const jsonData = {
          titulo: formData.get("titulo"),
          contenido: formData.get("contenido"),
          id_tipo_respuesta: formData.get("id_tipo_respuesta"),
          estado: formData.get("estado"),
          orden: formData.get("orden") || 1,
        };

        // Obtener el tipo de respuesta
        const tipoRespuesta = this.tiposRespuesta.find(
          (t) => t.id_tipo_respuesta == jsonData.id_tipo_respuesta
        );

        if (!tipoRespuesta) {
          throw new Error("Tipo de respuesta no válido");
        }

        // Procesamiento específico según el tipo
        switch (tipoRespuesta.nombre) {
          case "Opción múltiple":
            jsonData.opciones = formData
              .get("opciones")
              .split(",")
              .map((op) => op.trim());
            break;
          case "Selección múltiple":
            jsonData.opciones = formData
              .get("opciones")
              .split(",")
              .map((op) => op.trim());
            break;
        }

        // Procesar metadatos específicos según el tipo
        jsonData.metadatos = this.prepararMetadatos(
          formData,
          tipoRespuesta.nombre
        );

        // Preparar contenido de respuesta
        switch (tipoRespuesta.nombre) {
          case "Opción múltiple":
          case "Selección múltiple":
            jsonData.contenido_respuesta = "";
            break;
          case "Verdadero/Falso":
            jsonData.contenido_respuesta =
              formData.get("respuesta_correcta") === "true"
                ? "Verdadero"
                : "Falso";
            break;
          case "Escala":
          case "Escala Likert":
            jsonData.contenido_respuesta =
              formData.get("descripcion_escala") || "";
            break;
          case "Respuesta corta":
          case "Desarrollo":
            jsonData.contenido_respuesta =
              formData.get("respuesta_ejemplo") ||
              formData.get("guia_respuesta") ||
              "";
            break;
          default:
            jsonData.contenido_respuesta =
              formData.get("contenido_respuesta") || "";
        }

        const url = this.currentPreguntaId
          ? `/admin/preguntas/actualizar/${this.currentPreguntaId}`
          : "/admin/preguntas/guardar";

        const response = await fetch(url, {
          method: "POST",
          headers: this.headers,
          body: JSON.stringify(jsonData),
          credentials: "same-origin",
        });

        if (!response.ok) {
          throw new Error("Error en la solicitud");
        }

        const data = await response.json();
        if (data.success) {
          // Mostrar mensaje de éxito
          this.showToast(data.message, "success");

          // Cerrar modal
          const modal = bootstrap.Modal.getInstance(this.modalPregunta);
          modal.hide();

          // Recargar preguntas
          this.loadQuestions();
        } else {
          this.showToast("Error: " + data.message, "danger");
        }
      } catch (error) {
        console.error("Error:", error);
        this.showToast("Error de conexión: " + error.message, "danger");
      }
    }

    validarFormulario() {
      const titulo = this.formPregunta
        .querySelector('[name="titulo"]')
        .value.trim();
      const contenido = this.formPregunta
        .querySelector('[name="contenido"]')
        .value.trim();
      const tipoRespuestaId = this.formPregunta.querySelector(
        '[name="id_tipo_respuesta"]'
      ).value;

      if (!titulo) {
        this.showToast("El título es requerido", "warning");
        return false;
      }

      if (!contenido) {
        this.showToast("El contenido de la pregunta es requerido", "warning");
        return false;
      }

      if (!tipoRespuestaId) {
        this.showToast("Debe seleccionar un tipo de respuesta", "warning");
        return false;
      }

      // Encontrar el tipo de respuesta seleccionado
      const tipoRespuesta = this.tiposRespuesta.find(
        (t) => t.id_tipo_respuesta == tipoRespuestaId
      );

      if (!tipoRespuesta) {
        this.showToast("Tipo de respuesta no válido", "warning");
        return false;
      }

      // Validaciones específicas según el tipo
      switch (tipoRespuesta.nombre) {
        case "Opción múltiple":
          const opciones = this.formPregunta
            .querySelector('[name="opciones"]')
            .value.trim();
          const opcionCorrecta = this.formPregunta
            .querySelector('[name="opcion_correcta"]')
            .value.trim();

          if (!opciones) {
            this.showToast("Debe ingresar opciones de respuesta", "warning");
            return false;
          }

          if (!opcionCorrecta) {
            this.showToast(
              "Debe indicar cuál es la opción correcta",
              "warning"
            );
            return false;
          }

          const numOpciones = opciones.split(",").length;
          const numCorrecto = parseInt(opcionCorrecta, 10);

          if (numCorrecto < 1 || numCorrecto > numOpciones) {
            this.showToast(
              `La opción correcta debe ser un número entre 1 y ${numOpciones}`,
              "warning"
            );
            return false;
          }
          break;

        case "Selección múltiple":
          const opcionesMulti = this.formPregunta
            .querySelector('[name="opciones"]')
            .value.trim();

          if (!opcionesMulti) {
            this.showToast("Debe ingresar opciones de respuesta", "warning");
            return false;
          }

          // Las opciones correctas son opcionales, pero si se ingresan deben ser válidas
          const opcionesCorrectas = this.formPregunta
            .querySelector('[name="opciones_correctas"]')
            .value.trim();
          if (opcionesCorrectas) {
            const numOpcionesMulti = opcionesMulti.split(",").length;
            const indicesCorrectos = opcionesCorrectas
              .split(",")
              .map((num) => parseInt(num.trim(), 10));

            for (const idx of indicesCorrectos) {
              if (isNaN(idx) || idx < 1 || idx > numOpcionesMulti) {
                this.showToast(
                  `Las opciones correctas deben ser números entre 1 y ${numOpcionesMulti}`,
                  "warning"
                );
                return false;
              }
            }
          }
          break;

        case "Verdadero/Falso":
          const respCorrecta = this.formPregunta.querySelector(
            'input[name="respuesta_correcta"]:checked'
          );

          if (!respCorrecta) {
            this.showToast(
              "Debe seleccionar la respuesta correcta (Verdadero o Falso)",
              "warning"
            );
            return false;
          }
          break;

        case "Respuesta corta":
          const longMax = this.formPregunta
            .querySelector('[name="longitud_maxima"]')
            .value.trim();

          if (
            !longMax ||
            isNaN(parseInt(longMax, 10)) ||
            parseInt(longMax, 10) <= 0
          ) {
            this.showToast(
              "Debe ingresar una longitud máxima válida",
              "warning"
            );
            return false;
          }
          break;

        case "Escala":
          const min = this.formPregunta
            .querySelector('[name="valor_minimo"]')
            .value.trim();
          const max = this.formPregunta
            .querySelector('[name="valor_maximo"]')
            .value.trim();

          if (!min || isNaN(parseInt(min, 10))) {
            this.showToast("Debe ingresar un valor mínimo válido", "warning");
            return false;
          }

          if (!max || isNaN(parseInt(max, 10))) {
            this.showToast("Debe ingresar un valor máximo válido", "warning");
            return false;
          }

          if (parseInt(min, 10) >= parseInt(max, 10)) {
            this.showToast(
              "El valor máximo debe ser mayor que el valor mínimo",
              "warning"
            );
            return false;
          }
          break;

        case "Desarrollo":
          const palabrasMin = this.formPregunta
            .querySelector('[name="palabras_minimas"]')
            .value.trim();

          if (
            !palabrasMin ||
            isNaN(parseInt(palabrasMin, 10)) ||
            parseInt(palabrasMin, 10) <= 0
          ) {
            this.showToast(
              "Debe ingresar un número mínimo de palabras válido",
              "warning"
            );
            return false;
          }
          break;

        case "Escala Likert":
          const opcionesLikert = this.formPregunta
            .querySelector('[name="opciones_escala"]')
            .value.trim();
          const valoresLikert = this.formPregunta
            .querySelector('[name="valores_escala"]')
            .value.trim();

          if (!opcionesLikert) {
            this.showToast("Debe ingresar opciones para la escala", "warning");
            return false;
          }

          if (!valoresLikert) {
            this.showToast(
              "Debe ingresar valores para las opciones",
              "warning"
            );
            return false;
          }

          const numOpcionesLikert = opcionesLikert.split(",").length;
          const numValoresLikert = valoresLikert.split(",").length;

          if (numOpcionesLikert !== numValoresLikert) {
            this.showToast(
              "Debe haber el mismo número de opciones y valores",
              "warning"
            );
            return false;
          }
          break;
      }

      return true;
    }

    async handleEditar(id) {
      try {
        const response = await fetch(`/admin/preguntas/obtener/${id}`, {
          method: "GET",
          headers: this.headers,
          credentials: "same-origin",
        });

        if (!response.ok) {
          throw new Error("Error en la solicitud");
        }

        const data = await response.json();
        if (data.success) {
          this.fillForm(data.data);
          this.modalTitle.innerHTML =
            '<i class="bx bx-edit-alt me-1"></i>Editar Pregunta';
          this.currentPreguntaId = id;
          new bootstrap.Modal(this.modalPregunta).show();
        } else {
          this.showToast("Error: " + data.message, "danger");
        }
      } catch (error) {
        console.error("Error:", error);
        this.showToast("Error de conexión", "danger");
      }
    }

    fillForm(data) {
      // Limpiar el formulario primero
      this.resetForm();

      // Llenar los campos básicos
      this.formPregunta.querySelector('[name="titulo"]').value =
        data.titulo || "";
      this.formPregunta.querySelector('[name="contenido"]').value =
        data.contenido || "";
      this.formPregunta.querySelector('[name="orden"]').value = data.orden || 1;

      // Seleccionar el estado
      if (data.estado) {
        const estadoSelect = this.formPregunta.querySelector('[name="estado"]');
        Array.from(estadoSelect.options).forEach((option) => {
          if (option.value === data.estado) {
            option.selected = true;
          }
        });
      }

      // Seleccionar tipo de respuesta
      if (data.id_tipo_respuesta) {
        this.tipoRespuestaSelect.value = data.id_tipo_respuesta;

        // Encontrar el tipo seleccionado
        const tipoSeleccionado = this.tiposRespuesta.find(
          (tipo) => tipo.id_tipo_respuesta == data.id_tipo_respuesta
        );

        if (tipoSeleccionado) {
          // Renderizar campos según el tipo de respuesta
          this.renderCamposPorTipo(tipoSeleccionado);

          // Llenar los campos específicos según el tipo
          this.fillSpecificFields(data, tipoSeleccionado.nombre);
        }
      }
    }

    fillSpecificFields(data, tipoNombre) {
      console.log("data:", data);

      // Obtener los metadatos
      let metadatos = {};
      try {
        if (data.metadatos_raw) {
          metadatos = JSON.parse(data.metadatos_raw);
        }
      } catch (error) {
        console.error("Error al analizar los metadatos:", error);
        metadatos = {};
      }

      switch (tipoNombre) {
        case "Opción múltiple":
          // Llenar opciones si existen
          if (data.contenido_respuesta && data.contenido_respuesta.length > 0) {
            this.formPregunta.querySelector('[name="opciones"]').value =
              data.contenido_respuesta.join(", ");
          } else if (metadatos.opciones) {
            this.formPregunta.querySelector('[name="opciones"]').value =
              metadatos.opciones.join(", ");
          }

          // Llenar opción correcta
          if (metadatos.correcta !== undefined) {
            // Convertir de base 0 a base 1 para mostrar al usuario
            this.formPregunta.querySelector('[name="opcion_correcta"]').value =
              metadatos.correcta + 1;
          }
          break;

        case "Selección múltiple":
          // Llenar opciones si existen
          if (data.contenido_respuesta && data.contenido_respuesta.length > 0) {
            this.formPregunta.querySelector('[name="opciones"]').value =
              data.contenido_respuesta.join(", ");
          } else if (metadatos.opciones) {
            this.formPregunta.querySelector('[name="opciones"]').value =
              metadatos.opciones.join(", ");
          }

          // Llenar opciones correctas
          if (metadatos.correctas && metadatos.correctas.length > 0) {
            // Convertir de base 0 a base 1 para mostrar al usuario
            const correctasBase1 = metadatos.correctas.map((idx) => idx + 1);
            this.formPregunta.querySelector(
              '[name="opciones_correctas"]'
            ).value = correctasBase1.join(", ");
          }
          break;

        case "Verdadero/Falso":
          // Seleccionar respuesta correcta
          if (metadatos.correcta !== undefined) {
            const selector = metadatos.correcta
              ? "#respuesta-verdadero"
              : "#respuesta-falso";
            this.formPregunta.querySelector(selector).checked = true;
          }
          break;

        case "Respuesta corta":
          // Longitud máxima
          if (metadatos.longitud_maxima) {
            this.formPregunta.querySelector('[name="longitud_maxima"]').value =
              metadatos.longitud_maxima;
          }

          // Ejemplo
          if (metadatos.ejemplo) {
            this.formPregunta.querySelector(
              '[name="respuesta_ejemplo"]'
            ).value = metadatos.ejemplo;
          } else if (data.contenido_respuesta) {
            this.formPregunta.querySelector(
              '[name="respuesta_ejemplo"]'
            ).value = data.contenido_respuesta;
          }
          break;

        case "Escala":
          // Valores mínimo y máximo
          if (metadatos.min !== undefined) {
            this.formPregunta.querySelector('[name="valor_minimo"]').value =
              metadatos.min;
          }

          if (metadatos.max !== undefined) {
            this.formPregunta.querySelector('[name="valor_maximo"]').value =
              metadatos.max;
          }

          // Descripción
          if (metadatos.descripcion) {
            this.formPregunta.querySelector(
              '[name="descripcion_escala"]'
            ).value = metadatos.descripcion;
          } else if (data.contenido_respuesta) {
            this.formPregunta.querySelector(
              '[name="descripcion_escala"]'
            ).value = data.contenido_respuesta;
          }
          break;

        case "Desarrollo":
          // Palabras mínimas
          if (metadatos.palabras_minimas) {
            this.formPregunta.querySelector('[name="palabras_minimas"]').value =
              metadatos.palabras_minimas;
          }

          // Guía
          if (metadatos.guia) {
            this.formPregunta.querySelector('[name="guia_respuesta"]').value =
              metadatos.guia;
          } else if (data.contenido_respuesta) {
            this.formPregunta.querySelector('[name="guia_respuesta"]').value =
              data.contenido_respuesta;
          }
          break;

        case "Escala Likert":
          // Opciones
          if (metadatos.opciones && metadatos.opciones.length > 0) {
            this.formPregunta.querySelector('[name="opciones_escala"]').value =
              metadatos.opciones.join(", ");
          }

          // Valores
          if (metadatos.valores && metadatos.valores.length > 0) {
            this.formPregunta.querySelector('[name="valores_escala"]').value =
              metadatos.valores.join(", ");
          }
          break;

        default:
          // Para otros tipos, llenar contenido de respuesta
          if (data.contenido_respuesta) {
            const contenidoRespuestaField = this.formPregunta.querySelector(
              '[name="contenido_respuesta"]'
            );
            if (contenidoRespuestaField) {
              contenidoRespuestaField.value = data.contenido_respuesta;
            }
          }
      }
    }

    async handleEliminar(id) {
      // Usar SweetAlert2 para la confirmación
      const confirmacion = await Swal.fire({
        title: "¿Estás seguro?",
        text: "La pregunta será eliminada y no podrás recuperarla",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Sí, eliminar",
        cancelButtonText: "Cancelar",
      });

      if (confirmacion.isConfirmed) {
        try {
          const response = await fetch(`/admin/preguntas/eliminar/${id}`, {
            method: "POST",
            headers: this.headers,
            credentials: "same-origin",
          });

          if (!response.ok) {
            throw new Error("Error en la solicitud");
          }

          const data = await response.json();
          if (data.success) {
            // Mostrar mensaje de éxito
            Swal.fire("¡Eliminado!", data.message, "success");

            // Recargar preguntas
            this.loadQuestions();
          } else {
            Swal.fire(
              "Error",
              "No se pudo eliminar la pregunta: " + data.message,
              "error"
            );
          }
        } catch (error) {
          console.error("Error:", error);
          Swal.fire("Error", "Error de conexión", "error");
        }
      }
    }

    // Método para mostrar mensajes de notificación
    showToast(message, type = "info") {
      // Mapear el tipo de Bootstrap a los tipos de SweetAlert2
      const iconMap = {
        success: "success",
        danger: "error",
        warning: "warning",
        info: "info",
      };

      // Obtener el icono adecuado
      const icon = iconMap[type] || "info";

      // Mostrar alerta con SweetAlert2
      Swal.fire({
        icon: icon,
        title: icon === "error" ? "¡Error!" : "",
        text: message,
        toast: true,
        position: "top-end",
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
          toast.addEventListener("mouseenter", Swal.stopTimer);
          toast.addEventListener("mouseleave", Swal.resumeTimer);
        },
      });
    }

    async confirmAction(message, confirmText = "Sí, eliminar") {
      const result = await Swal.fire({
        title: "¿Estás seguro?",
        text: message,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: confirmText,
        cancelButtonText: "Cancelar",
      });

      return result.isConfirmed;
    }

    showError(message) {
      Swal.fire({
        icon: "error",
        title: "Error",
        text: message,
        confirmButtonText: "Entendido",
      });

      this.container.innerHTML = `
        <div class="alert alert-danger" role="alert">
          ${message}
        </div>
      `;
    }
  }

  // Inicializar la aplicación cuando el DOM esté listo
  var preguntasManager = null;
  document.addEventListener("DOMContentLoaded", () => {
    preguntasManager = new PreguntasApp();
  });
})();
