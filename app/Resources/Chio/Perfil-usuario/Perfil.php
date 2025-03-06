<?php header_web('Template.Header', $data); ?>
<div data-bs-spy="scroll" class="scrollspy-example">
    <section id="profile" class="section-py">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="card mb-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h4 class="mb-0">Mi Perfil</h4>
                            <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editProfileModal">
                                <i class="icon-base bx bx-edit"></i> Editar Perfil
                            </button>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4 col-12 mb-4">
                                    <div class="d-flex flex-column align-items-center">
                                        <div class="avatar avatar-xl mb-3">
                                            <img src="/img/default.png" alt="Avatar" class="rounded-circle">
                                        </div>
                                        <h5 class="text-center text-primary mb-1" id="profile-name">
                                            <?php echo isset($data['user']['nombre']) ? $data['user']['nombre'] : 'Usuario'; ?>
                                        </h5>
                                        <p class="text-muted fw-bold">
                                            <?php echo isset($data['user']['email']) ? $data['user']['email'] : 'email@ejemplo.com'; ?>
                                        </p>
                                    </div>
                                </div>

                                <div class="col-md-8 col-12">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <div class="card bg-label-info">
                                                <div class="card-body">
                                                    <h6>Información Personal</h6>
                                                    <dl class="row mb-0">
                                                        <dt class="col-sm-4">Nombre:</dt>
                                                        <dd class="col-sm-8" id="profile-full-name"><?php echo isset($data['user']['nombre']) ? $data['user']['nombre'] : 'No especificado'; ?></dd>

                                                        <dt class="col-sm-4">Edad:</dt>
                                                        <dd class="col-sm-8" id="profile-age"><?php echo isset($data['user']['edad']) ? $data['user']['edad'] . ' años' : 'No especificado'; ?></dd>

                                                        <dt class="col-sm-4">Sexo:</dt>
                                                        <dd class="col-sm-8" id="profile-gender"><?php echo isset($data['user']['sexo']) ? $data['user']['sexo'] : 'No especificado'; ?></dd>

                                                        <dt class="col-sm-4">Correo:</dt>
                                                        <dd class="col-sm-8" id="profile-email"><?php echo isset($data['user']['email']) ? $data['user']['email'] : 'No especificado'; ?></dd>
                                                    </dl>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <div class="card bg-label-info">
                                                <div class="card-body">
                                                    <h6>Información Física</h6>
                                                    <dl class="row mb-0">
                                                        <dt class="col-sm-4">Peso:</dt>
                                                        <dd class="col-sm-8" id="profile-weight"><?php echo isset($data['user']['peso']) ? $data['user']['peso'] . ' kg' : 'No especificado'; ?></dd>

                                                        <dt class="col-sm-4">Altura:</dt>
                                                        <dd class="col-sm-8" id="profile-height"><?php echo isset($data['user']['altura']) ? $data['user']['altura'] . ' cm' : 'No especificado'; ?></dd>

                                                        <dt class="col-sm-4">IMC:</dt>
                                                        <dd class="col-sm-8" id="profile-bmi">
                                                            <?php
                                                            if (isset($data['user']['peso']) && isset($data['user']['altura']) && $data['user']['altura'] > 0) {
                                                                $heightInMeters = $data['user']['altura'];

                                                                // Si la altura es mayor a 3, asumimos que está en centímetros
                                                                if ($heightInMeters > 3) {
                                                                    $heightInMeters = $heightInMeters / 100;
                                                                }

                                                                $bmi = $data['user']['peso'] / ($heightInMeters * $heightInMeters);
                                                                echo number_format($bmi, 2);
                                                            } else {
                                                                echo 'No calculable';
                                                            }
                                                            ?>
                                                        </dd>
                                                    </dl>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 mb-3">
                                            <div class="card bg-light">
                                                <div class="card-body">
                                                    <div class="">
                                                        <h6 class="mb-2">Mis Tests Realizados</h6>
                                                        <div class="d-flex gap-2">
                                                            <a href="/sited/test" class="btn btn-sm btn-primary">
                                                                <i class="icon-base bx bxs-folder-plus me-2"></i>Nuevo Test
                                                            </a>
                                                            <a href="/perfil/mis-tests" class="btn btn-sm btn-outline-primary">
                                                                <i class="icon-base bx bx-list-check"></i> Ver todos mis tests
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-12">
                                            <div class="card bg-label-warning">
                                                <div class="card-body">
                                                    <h6>Seguridad</h6>
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <div>
                                                            <p class="mb-0">Contraseña</p>
                                                            <small class="text-muted">Última actualización: <?php echo isset($data['user']['ultima_actualizacion']) ? $data['user']['ultima_actualizacion'] : 'No disponible'; ?></small>
                                                        </div>
                                                        <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#changePasswordModal">
                                                            Cambiar contraseña
                                                        </button>
                                                    </div>
                                                    <hr>
                                                    <div class="d-flex justify-content-between align-items-center text-danger">
                                                        <span>Eliminar cuenta</span>
                                                        <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteAccountModal">
                                                            Eliminar mi cuenta
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Modal Editar Perfil -->
<div class="modal fade" id="editProfileModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Editar Perfil</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editProfileForm" class="row g-3">
                    <div class="col-12">
                        <label for="edit-name" class="form-label">Nombre completo</label>
                        <input type="text" class="form-control" id="edit-name" name="nombre" value="<?php echo isset($data['user']['nombre']) ? $data['user']['nombre'] : ''; ?>">
                    </div>

                    <div class="col-md-6">
                        <label for="edit-age" class="form-label">Edad</label>
                        <input type="number" class="form-control" id="edit-age" name="edad" value="<?php echo isset($data['user']['edad']) ? $data['user']['edad'] : ''; ?>">
                    </div>

                    <div class="col-md-6">
                        <label for="edit-gender" class="form-label">Sexo</label>
                        <select class="form-select" id="edit-gender" name="sexo">
                            <option value="">Seleccionar</option>
                            <option value="M" <?php echo (isset($data['user']['sexo']) && $data['user']['sexo'] == 'M') ? 'selected' : ''; ?>>Masculino</option>
                            <option value="F" <?php echo (isset($data['user']['sexo']) && $data['user']['sexo'] == 'F') ? 'selected' : ''; ?>>Femenino</option>
                            <option value="O" <?php echo (isset($data['user']['sexo']) && $data['user']['sexo'] == 'O') ? 'selected' : ''; ?>>Otro</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="edit-weight" class="form-label">Peso (kg)</label>
                        <input type="number" step="0.1" class="form-control" id="edit-weight" name="peso" value="<?php echo isset($data['user']['peso']) ? $data['user']['peso'] : ''; ?>">
                    </div>

                    <div class="col-md-6">
                        <label for="edit-height" class="form-label">Altura (cm)</label>
                        <input type="number" class="form-control" id="edit-height" name="altura" value="<?php echo isset($data['user']['altura']) ? $data['user']['altura'] : ''; ?>">
                    </div>

                    <div class="col-12">
                        <label for="edit-email" class="form-label">Correo electrónico</label>
                        <input type="email" class="form-control" id="edit-email" name="email" value="<?php echo isset($data['user']['email']) ? $data['user']['email'] : ''; ?>">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="saveProfileBtn">Guardar cambios</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Cambiar Contraseña -->
<div class="modal fade" id="changePasswordModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Cambiar Contraseña</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="changePasswordForm" class="row g-3">
                    <div class="col-12 form-password-toggle">
                        <label class="form-label" for="current-password">Contraseña actual</label>
                        <div class="input-group input-group-merge">
                            <input type="password" class="form-control" id="current-password" name="currentPassword" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;">
                            <span class="input-group-text cursor-pointer"><i class="icon-base bx bx-hide"></i></span>
                        </div>
                    </div>

                    <div class="col-12 form-password-toggle">
                        <label class="form-label" for="new-password">Nueva contraseña</label>
                        <div class="input-group input-group-merge">
                            <input type="password" class="form-control" id="new-password" name="newPassword" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;">
                            <span class="input-group-text cursor-pointer"><i class="icon-base bx bx-hide"></i></span>
                        </div>
                    </div>

                    <div class="col-12 form-password-toggle">
                        <label class="form-label" for="confirm-password">Confirmar nueva contraseña</label>
                        <div class="input-group input-group-merge">
                            <input type="password" class="form-control" id="confirm-password" name="confirmPassword" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;">
                            <span class="input-group-text cursor-pointer"><i class="icon-base bx bx-hide"></i></span>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="savePasswordBtn">Cambiar contraseña</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Eliminar Cuenta -->
<div class="modal fade" id="deleteAccountModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-danger">Eliminar mi cuenta</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning" role="alert">
                    <h6 class="alert-heading fw-bold mb-1">¿Estás seguro de que deseas eliminar tu cuenta?</h6>
                    <p class="mb-0">Una vez eliminada, toda tu información se perderá permanentemente. Esta acción no se puede deshacer.</p>
                </div>
                <form id="deleteAccountForm">
                    <div class="form-password-toggle mb-3">
                        <label class="form-label" for="delete-password">Ingresa tu contraseña para confirmar</label>
                        <div class="input-group input-group-merge">
                            <input type="password" class="form-control" id="delete-password" name="password" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;">
                            <span class="input-group-text cursor-pointer"><i class="icon-base bx bx-hide"></i></span>
                        </div>
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="confirm-deletion" name="confirmDeletion" required>
                        <label class="form-check-label" for="confirm-deletion">
                            Confirmo que deseo eliminar permanentemente mi cuenta
                        </label>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger" id="deleteAccountBtn">Eliminar cuenta</button>
            </div>
        </div>
    </div>
</div>

<?php footer_web('Template.Footer', $data); ?>
<!-- Script para manejar las acciones del perfil -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Guardar cambios de perfil
        document.getElementById('saveProfileBtn').addEventListener('click', function() {
            const form = document.getElementById('editProfileForm');
            const formData = new FormData(form);

            fetch('/perfil/actualizar', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status) {
                        Toast.fire({
                            icon: 'success',
                            title: 'Perfil actualizado con éxito'
                        });

                        // Actualizar los datos en la vista
                        document.getElementById('profile-name').textContent = formData.get('name');
                        document.getElementById('profile-full-name').textContent = formData.get('name');
                        document.getElementById('profile-age').textContent = formData.get('age') + ' años';
                        document.getElementById('profile-gender').textContent = formData.get('gender');
                        document.getElementById('profile-email').textContent = formData.get('email');
                        document.getElementById('profile-weight').textContent = formData.get('weight') + ' kg';
                        document.getElementById('profile-height').textContent = formData.get('height') + ' cm';

                        // Calcular IMC si hay peso y altura
                        if (formData.get('weight') && formData.get('height')) {
                            const weightKg = parseFloat(formData.get('weight'));
                            const heightM = parseFloat(formData.get('height')) / 100;
                            const bmi = weightKg / (heightM * heightM);
                            document.getElementById('profile-bmi').textContent = bmi.toFixed(2);
                        }

                        // Cerrar modal
                        const modal = bootstrap.Modal.getInstance(document.getElementById('editProfileModal'));
                        modal.hide();
                    } else {
                        Toast.fire({
                            icon: 'error',
                            title: data.message || 'Error al actualizar el perfil'
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Toast.fire({
                        icon: 'error',
                        title: 'Error al comunicarse con el servidor'
                    });
                });
        });

        // Cambiar contraseña
        document.getElementById('savePasswordBtn').addEventListener('click', function() {
            const form = document.getElementById('changePasswordForm');
            const formData = new FormData(form);

            // Validar que las contraseñas coincidan
            if (formData.get('newPassword') !== formData.get('confirmPassword')) {
                Toast.fire({
                    icon: 'error',
                    title: 'Las contraseñas no coinciden'
                });
                return;
            }

            fetch('/perfil/cambiar-password', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status) {
                        Toast.fire({
                            icon: 'success',
                            title: 'Contraseña actualizada con éxito'
                        });

                        // Limpiar formulario
                        form.reset();

                        // Cerrar modal
                        const modal = bootstrap.Modal.getInstance(document.getElementById('changePasswordModal'));
                        modal.hide();
                    } else {
                        Toast.fire({
                            icon: 'error',
                            title: data.message || 'Error al actualizar la contraseña'
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Toast.fire({
                        icon: 'error',
                        title: 'Error al comunicarse con el servidor'
                    });
                });
        });

        // Eliminar cuenta
        document.getElementById('deleteAccountBtn').addEventListener('click', function() {
            const form = document.getElementById('deleteAccountForm');
            const formData = new FormData(form);

            // Validar que el checkbox esté marcado
            if (!document.getElementById('confirm-deletion').checked) {
                Toast.fire({
                    icon: 'warning',
                    title: 'Debes confirmar que deseas eliminar tu cuenta'
                });
                return;
            }

            fetch('/perfil/eliminar', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status) {
                        Swal.fire({
                            title: 'Cuenta eliminada',
                            text: 'Tu cuenta ha sido eliminada correctamente. Serás redirigido en breve.',
                            icon: 'success',
                            confirmButtonText: 'Entendido'
                        }).then(() => {
                            window.location.href = '/iniciar-sesion';
                        });
                    } else {
                        Toast.fire({
                            icon: 'error',
                            title: data.message || 'Error al eliminar la cuenta'
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Toast.fire({
                        icon: 'error',
                        title: 'Error al comunicarse con el servidor'
                    });
                });
        });
    });
</script>