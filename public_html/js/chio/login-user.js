/**
 *  Pages Authentication
 */
"use strict";

document.addEventListener("DOMContentLoaded", function () {
  (() => {
    const formAuthentication = document.querySelector("#formAuthentication");

    // Form validation for Add new record
    if (formAuthentication && typeof FormValidation !== "undefined") {
      const fv = FormValidation.formValidation(formAuthentication, {
        fields: {
          "email-username": {
            validators: {
              notEmpty: {
                message:
                  "Por favor, ingrese su correo electrónico o nombre de usuario",
              },
              stringLength: {
                min: 6,
                message: "Username must be more than 6 characters",
              },
            },
          },
          password: {
            validators: {
              notEmpty: {
                message: "Por favor, ingrese su contraseña",
              },
              stringLength: {
                min: 6,
                message: "La contraseña debe tener más de 6 caracteres",
              },
            },
          },
        },
        plugins: {
          trigger: new FormValidation.plugins.Trigger(),
          bootstrap5: new FormValidation.plugins.Bootstrap5({
            eleValidClass: "",
            rowSelector: ".form-control-validation",
          }),
          submitButton: new FormValidation.plugins.SubmitButton(),
          // defaultSubmit: new FormValidation.plugins.DefaultSubmit(),
          autoFocus: new FormValidation.plugins.AutoFocus(),
        },
        init: (instance) => {
          instance.on("plugins.message.placed", (e) => {
            if (e.element.parentElement.classList.contains("input-group")) {
              e.element.parentElement.insertAdjacentElement(
                "afterend",
                e.messageElement
              );
            }
          });
        },
      });

      fv.on("core.form.valid", function (event) {
        // Crear FormData con los datos del formulario
        const formData = new FormData(formAuthentication);

        // Mostrar un indicador de carga en el botón de envío
        const submitBtn = formAuthentication.querySelector(
          'button[type="submit"]'
        );
        const originalBtnText = submitBtn.innerHTML;
        submitBtn.innerHTML =
          '<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Enviando...';
        submitBtn.disabled = true;

        // Enviar los datos mediante AJAX
        fetch("/iniciar-sesion", {
          method: "POST",
          body: formData,
          // No incluimos el header Content-Type porque FormData lo establecerá automáticamente con el boundary necesario
        })
          .then((response) => {
            if (!response.ok) {
              throw new Error("Error en el servidor: " + response.status);
            }
            return response.json();
          })
          .then((data) => {
            console.log("Respuesta del servidor:", data);
            if (data.status) {
              // Mostrar mensaje de éxito
              Swal.fire({
                icon: "success",
                title: "Registro exitoso",
                text: data.message,
              });
            } else {
              // Mostrar mensaje de error
              Swal.fire({
                icon: "warning",
                title: "Error al registrar",
                text: data.message,
              });
            }
          })
          .catch((error) => {
            console.error("Error al enviar el formulario:", error);
            alert("Error al procesar el registro: " + error.message);
          })
          .finally(() => {
            // Restaurar el botón
            submitBtn.innerHTML = originalBtnText;
            submitBtn.disabled = false;
          });
      });
    }
  })();
});
