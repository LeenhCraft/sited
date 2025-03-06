/**
 *  Pages Authentication
 */
"use strict";

document.addEventListener("DOMContentLoaded", function () {
  (() => {
    const formAuthentication = document.querySelector("#formAuthentication");

    // Agregar función para consultar DNI
    const buscarPorDNI = async (dni) => {
      try {
        const response = await fetch(`/doc/dni/${dni}`, {
          method: "GET",
          headers: {
            "Content-Type": "application/json",
          },
        });

        if (!response.ok) {
          throw new Error("No se pudo obtener la información del DNI");
        }

        const data = await response.json();

        if (data && data.data && data.data.nombre_completo) {
          // Llenar el campo de nombre automáticamente
          const nombreInput = document.querySelector("#nombre_completo");
          if (nombreInput) {
            nombreInput.value = data.data.nombre_completo;
            // Disparar evento de cambio para que la validación lo reconozca
            const event = new Event("input", { bubbles: true });
            nombreInput.dispatchEvent(event);
          }
          // focus en el siguiente campo
          const usernameInput = document.querySelector("#username");
          if (usernameInput) {
            usernameInput.focus();
          }
          return true;
        } else {
          throw new Error("Datos no encontrados");
        }
      } catch (error) {
        console.error("Error al consultar el DNI:", error);
        Toast.fire({
          icon: "warning",
          title: error.message,
        });
        return false;
      }
    };

    // Configurar el botón de búsqueda de DNI
    const setupDNISearch = () => {
      const dniInput = document.querySelector("#dni");
      const searchButton = document.querySelector("#buscarDNI");

      if (searchButton && dniInput) {
        searchButton.addEventListener("click", async (e) => {
          e.preventDefault();
          const dniValue = dniInput.value.trim();

          if (dniValue) {
            // Mostrar indicador de carga
            searchButton.innerHTML =
              '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>';
            searchButton.disabled = true;

            // Realizar la búsqueda
            await buscarPorDNI(dniValue);

            // Restaurar el botón
            searchButton.innerHTML = '<i class="icon-base bx bx-search"></i>';
            searchButton.disabled = false;
          } else {
            alert("Por favor, ingrese un DNI para buscar");
          }
        });
      }
    };

    // Llamar a la función de configuración
    setupDNISearch();

    // Form validation for Add new record
    if (formAuthentication && typeof FormValidation !== "undefined") {
      const fv = FormValidation.formValidation(formAuthentication, {
        fields: {
          dni: {
            validators: {
              notEmpty: {
                message: "Por favor, ingrese su DNI",
              },
            },
          },
          nombre_completo: {
            validators: {
              notEmpty: {
                message: "Por favor, ingrese su nombre completo",
              },
            },
          },
          username: {
            validators: {
              notEmpty: {
                message: "Por favor, ingrese su nombre de usuario",
              },
              stringLength: {
                min: 6,
                message: "El nombre de usuario debe tener más de 6 caracteres",
              },
            },
          },
          email: {
            validators: {
              notEmpty: {
                message: "Por favor, ingrese su correo electrónico",
              },
              emailAddress: {
                message: "Por favor, ingrese un correo electrónico válido",
              },
            },
          },
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
          "confirm-password": {
            validators: {
              notEmpty: {
                message: "Please confirm password",
              },
              identical: {
                compare: () =>
                  formAuthentication.querySelector('[name="password"]').value,
                message: "The password and its confirmation do not match",
              },
              stringLength: {
                min: 6,
                message: "Password must be more than 6 characters",
              },
            },
          },
          terms: {
            validators: {
              notEmpty: {
                message: "Debe aceptar los términos y condiciones",
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
        // Prevenir el envío normal del formulario
        // event.preventDefault();

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
        fetch("/registrarse/save", {
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
                showConfirmButton: false,
                timer: 3000,
              }).then(() => {
                // Redirigir a la página de inicio de sesión
                window.location.href = "/iniciar-sesion";
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

    // Two Steps Verification for numeral input mask
    const numeralMaskElements = document.querySelectorAll(".numeral-mask");

    // Format function for numeral mask
    const formatNumeral = (value) => value.replace(/\D/g, ""); // Only keep digits

    if (numeralMaskElements.length > 0) {
      numeralMaskElements.forEach((numeralMaskEl) => {
        numeralMaskEl.addEventListener("input", (event) => {
          numeralMaskEl.value = formatNumeral(event.target.value);
        });
      });
    }
  })();
});
