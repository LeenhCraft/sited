/**
 *  Pages Authentication
 */
"use strict";

document.addEventListener("DOMContentLoaded", function () {
  (() => {
    // Seleccionar todos los elementos de alternancia de contraseña
    const togglePasswordElements = document.querySelectorAll(
      ".form-password-toggle .input-group-text"
    );

    // Añadir evento click a cada elemento
    togglePasswordElements.forEach((element) => {
      element.addEventListener("click", function () {
        // Encontrar el campo de contraseña asociado
        const passwordInput = this.previousElementSibling;

        // Alternar tipo de input entre password y text
        if (passwordInput.type === "password") {
          passwordInput.type = "text";
          // Cambiar icono a "mostrar"
          this.querySelector("i").classList.remove("bx-hide");
          this.querySelector("i").classList.add("bx-show");
        } else {
          passwordInput.type = "password";
          // Cambiar icono a "ocultar"
          this.querySelector("i").classList.remove("bx-show");
          this.querySelector("i").classList.add("bx-hide");
        }
      });
    });

    const formEditProfile = document.querySelector("#editProfileForm");
    if (formEditProfile && typeof FormValidation !== "undefined") {
      const fvp = FormValidation.formValidation(formEditProfile, {
        fields: {
          dni: {
            validators: {
              notEmpty: {
                message: "Por favor, ingrese su DNI",
              },
              stringLength: {
                min: 8,
                message: "EL DNI debe tener 8 caracteres",
              },
            },
          },
          nombre: {
            validators: {
              notEmpty: {
                message: "Por favor, ingrese su nombre",
              },
            },
          },
          edad: {
            validators: {
              notEmpty: {
                message: "Por favor, ingrese su edad",
              },
              integer: {
                message: "El valor debe ser un número entero",
              },
              greaterThan: {
                min: 0,
                inclusive: false,
                message: "La edad debe ser mayor a cero",
              },
              between: {
                min: 0,
                max: 200,
                message: "La edad debe estar entre 0 y 200 años",
              },
            },
          },
          sexo: {
            validators: {
              notEmpty: {
                message: "Por favor, seleccione su sexo",
              },
            },
          },
          peso: {
            validators: {
              notEmpty: {
                message: "Por favor, ingrese su peso",
              },
              greaterThan: {
                min: 0,
                inclusive: false,
                message: "La edad debe ser mayor a cero",
              },
              // debe aceptar decimales
              numeric: {
                message: "El valor debe ser un número",
              },

              between: {
                min: 0,
                max: 500,
                message: "El peso debe estar entre 0 y 500 kg",
              },
            },
          },
          altura: {
            validators: {
              notEmpty: {
                message: "Por favor, ingrese su altura",
              },
              greaterThan: {
                min: 0,
                inclusive: false,
                message: "La edad debe ser mayor a cero",
              },
              between: {
                min: 0,
                max: 500,
                message: "La altura debe estar entre 0 y 500 cm",
              },
            },
          },
          email: {
            validators: {
              notEmpty: {
                message: "Por favor, ingrese su correo electrónico",
              },
              emailAddress: {
                message: "El correo electrónico no es válido",
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

      fvp.on("core.form.valid", function (event) {
        const formData = new FormData(formEditProfile);

        // Mostrar un indicador de carga en el botón de envío
        const submitBtn = formEditProfile.querySelector(
          'button[type="submit"]'
        );
        const originalBtnText = submitBtn.innerHTML;
        submitBtn.innerHTML =
          '<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Enviando...';
        submitBtn.disabled = true;

        fetch("/perfil/actualizar", {
          method: "POST",
          body: formData,
        })
          .then((response) => response.json())
          .then((data) => {
            if (data.status) {
              Swal.fire({
                icon: "success",
                title: "Perfil actualizado",
                text: "Tu perfil ha sido actualizado correctamente, le recomendaría que actualice la página para ver los cambios.",
                confirmButtonText: "OK",
              }).then((result) => {
                if (result.isConfirmed) {
                  location.reload();
                }
              });

              // Cerrar modal
              const modal = bootstrap.Modal.getInstance(
                document.getElementById("editProfileModal")
              );
              modal.hide();
            } else {
              submitBtn.innerHTML = originalBtnText;
              submitBtn.disabled = false;
              Swal.fire({
                icon: "warning",
                title: data.message || "Error al actualizar el perfil",
                text: "Por favor, inténtalo de nuevo",
                confirmButtonText: "OK",
              });
            }
          })
          .catch((error) => {
            submitBtn.innerHTML = originalBtnText;
            submitBtn.disabled = false;
            console.error("Error:", error);
            Toast.fire({
              icon: "error",
              title: "Error al comunicarse con el servidor",
            });
          });
      });
    } else {
      console.log("No se encontró el formulario de edición de perfil");
    }

    // Cambiar contraseña
    const formChangePasswordForm = document.querySelector(
      "#changePasswordForm"
    );
    if (formChangePasswordForm && typeof FormValidation !== "undefined") {
      const fvp = FormValidation.formValidation(changePasswordForm, {
        fields: {
          currentPassword: {
            validators: {
              notEmpty: {
                message: "Por favor, ingrese su contraseña actual",
              },
            },
          },
          newPassword: {
            validators: {
              notEmpty: {
                message: "Por favor, ingrese su nueva contraseña",
              },
              stringLength: {
                min: 6,
                message: "La contraseña debe tener al menos 6 caracteres",
              },
            },
          },
          confirmPassword: {
            validators: {
              notEmpty: {
                message: "Por favor, confirme su nueva contraseña",
              },
              identical: {
                compare: function () {
                  return formChangePasswordForm.querySelector(
                    'input[name="newPassword"]'
                  ).value;
                },
                message: "Las contraseñas no coinciden",
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

      fvp.on("core.form.valid", function (event) {
        const formData = new FormData(formChangePasswordForm);

        // Mostrar un indicador de carga en el botón de envío
        const submitBtn = formChangePasswordForm.querySelector(
          'button[type="submit"]'
        );
        const originalBtnText = submitBtn.innerHTML;
        submitBtn.innerHTML =
          '<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Enviando...';
        submitBtn.disabled = true;

        fetch("/perfil/cambiar-passwords", {
          method: "POST",
          body: formData,
        })
          .then((response) => response.json())
          .then((data) => {
            console.log(data);

            if (data.status) {
              Swal.fire({
                icon: "success",
                title: "Perfil actualizado",
                text: "La contraseña han sido actualizadas correctamente",
                confirmButtonText: "OK",
              }).then((result) => {
                if (result.isConfirmed) {
                  location.reload();
                }
              });
              // Cerrar modal
              const modal = bootstrap.Modal.getInstance(
                document.getElementById("changePasswordModal")
              );
              modal.hide();
            } else {
              submitBtn.innerHTML = originalBtnText;
              submitBtn.disabled = false;
              Swal.fire({
                icon: "warning",
                title: data.message || "Error al actualizar el perfil",
                text: "Por favor, inténtalo de nuevo",
                confirmButtonText: "OK",
              });
            }
          })
          .catch((error) => {
            submitBtn.innerHTML = originalBtnText;
            submitBtn.disabled = false;
            console.error("Error:", error);
            Toast.fire({
              icon: "error",
              title: "Error al comunicarse con el servidor",
            });
          });
      });
    } else {
      console.log("No se encontró el formulario de cambio de contraseña");
    }

    // Eliminar cuenta
    document
      .getElementById("deleteAccountBtn")
      .addEventListener("click", function () {
        const form = document.getElementById("deleteAccountForm");
        const formData = new FormData(form);

        // Validar que el checkbox esté marcado
        if (!document.getElementById("confirm-deletion").checked) {
          Toast.fire({
            icon: "warning",
            title: "Debes confirmar que deseas eliminar tu cuenta",
          });
          return;
        }

        fetch("/perfil/eliminar", {
          method: "POST",
          body: formData,
        })
          .then((response) => response.json())
          .then((data) => {
            if (data.status) {
              Swal.fire({
                title: "Cuenta eliminada",
                text: "Tu cuenta ha sido eliminada correctamente. Serás redirigido en breve.",
                icon: "success",
                confirmButtonText: "Entendido",
              }).then(() => {
                window.location.href = "/iniciar-sesion";
              });
            } else {
              Toast.fire({
                icon: "error",
                title: data.message || "Error al eliminar la cuenta",
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
      });
  })();
});
