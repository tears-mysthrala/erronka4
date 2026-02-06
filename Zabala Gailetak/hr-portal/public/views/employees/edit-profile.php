<?php require dirname(__DIR__) . '/layouts/header.php'; ?>

<div class="dashboard-container">
    <div class="page-header">
        <div>
            <h1 class="page-title">
                <i class="fas fa-user-edit"></i>
                Editar Mi Perfil
            </h1>
            <p class="page-subtitle">Actualiza tus datos personales</p>
        </div>
        <div class="page-actions">
            <a href="/employees/profile" class="btn-industrial btn-secondary-industrial">
                <i class="fas fa-arrow-left"></i>
                Volver al Perfil
            </a>
        </div>
    </div>

    <?php if (!empty($errors)): ?>
        <div class="alert-industrial alert-danger">
            <i class="fas fa-exclamation-triangle"></i>
            <div>
                <strong>Se encontraron errores:</strong>
                <ul style="margin: var(--space-2) 0 0 var(--space-5);">
                    <?php foreach ($errors as $field => $error): ?>
                        <li><?= htmlspecialchars($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    <?php endif; ?>

    <div style="display: grid; grid-template-columns: minmax(0, 2fr) minmax(0, 1fr); gap: var(--space-6); align-items: start;">
        <form action="/employees/profile/update" method="POST" class="needs-validation" novalidate>
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token ?? '') ?>">

            <div class="widget-card-industrial" style="margin-bottom: var(--space-6);">
                <div class="widget-header">
                    <h3 class="widget-title">
                        <i class="fas fa-id-card"></i>
                        Información Personal
                    </h3>
                </div>
                <div class="widget-body" style="display: grid; gap: var(--space-4);">
                    <div style="display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: var(--space-4);">
                        <div class="form-group-industrial">
                            <label for="first_name" class="form-label-industrial">Nombre</label>
                            <input type="text" class="form-input-industrial" id="first_name" name="first_name"
                                value="<?= htmlspecialchars($old['first_name'] ?? $employee['first_name']) ?>" required>
                        </div>
                        <div class="form-group-industrial">
                            <label for="last_name" class="form-label-industrial">Apellido</label>
                            <input type="text" class="form-input-industrial" id="last_name" name="last_name"
                                value="<?= htmlspecialchars($old['last_name'] ?? $employee['last_name']) ?>" required>
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: var(--space-4);">
                        <div class="form-group-industrial">
                            <label for="phone" class="form-label-industrial">Teléfono</label>
                            <input type="tel" class="form-input-industrial" id="phone" name="phone"
                                value="<?= htmlspecialchars($old['phone'] ?? $employee['phone'] ?? '') ?>"
                                placeholder="Ej: 612345678">
                            <small class="form-text">Formato español: 6/7/8/9 + 8 dígitos</small>
                        </div>
                        <div class="form-group-industrial">
                            <label for="email" class="form-label-industrial">Email</label>
                            <input type="email" class="form-input-industrial" id="email"
                                value="<?= htmlspecialchars($employee['email']) ?>" disabled>
                            <small class="form-text">El email no puede modificarse. Contacta con administración.</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="widget-card-industrial" style="margin-bottom: var(--space-6);">
                <div class="widget-header">
                    <h3 class="widget-title">
                        <i class="fas fa-map-marker-alt"></i>
                        Dirección
                    </h3>
                </div>
                <div class="widget-body" style="display: grid; gap: var(--space-4);">
                    <div class="form-group-industrial">
                        <label for="address" class="form-label-industrial">Dirección</label>
                        <textarea class="form-textarea-industrial" id="address" name="address" rows="2"><?= htmlspecialchars($old['address'] ?? $employee['address'] ?? '') ?></textarea>
                    </div>

                    <div style="display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: var(--space-4);">
                        <div class="form-group-industrial">
                            <label for="postal_code" class="form-label-industrial">Código Postal</label>
                            <input type="text" class="form-input-industrial" id="postal_code" name="postal_code"
                                value="<?= htmlspecialchars($old['postal_code'] ?? $employee['postal_code'] ?? '') ?>"
                                pattern="[0-9]{5}" maxlength="5">
                            <small class="form-text">5 dígitos</small>
                        </div>
                        <div class="form-group-industrial">
                            <label for="city" class="form-label-industrial">Ciudad</label>
                            <input type="text" class="form-input-industrial" id="city" name="city"
                                value="<?= htmlspecialchars($old['city'] ?? $employee['city'] ?? '') ?>">
                        </div>
                        <div class="form-group-industrial">
                            <label for="country" class="form-label-industrial">País</label>
                            <input type="text" class="form-input-industrial" id="country" name="country"
                                value="<?= htmlspecialchars($old['country'] ?? $employee['country'] ?? 'España') ?>">
                        </div>
                    </div>
                </div>
            </div>

            <div class="widget-card-industrial">
                <div class="widget-header">
                    <h3 class="widget-title">
                        <i class="fas fa-briefcase"></i>
                        Información Laboral (Solo Lectura)
                    </h3>
                </div>
                <div class="widget-body" style="display: grid; gap: var(--space-4);">
                    <div style="display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: var(--space-4);">
                        <div class="form-group-industrial">
                            <label for="employee_number" class="form-label-industrial">Número Empleado</label>
                            <input type="text" class="form-input-industrial" id="employee_number"
                                value="<?= htmlspecialchars($employee['employee_number']) ?>" readonly>
                        </div>
                        <div class="form-group-industrial">
                            <label for="nif" class="form-label-industrial">NIF</label>
                            <input type="text" class="form-input-industrial" id="nif"
                                value="<?= htmlspecialchars($employee['nif']) ?>" readonly>
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: var(--space-4);">
                        <div class="form-group-industrial">
                            <label for="position" class="form-label-industrial">Puesto</label>
                            <input type="text" class="form-input-industrial" id="position"
                                value="<?= htmlspecialchars($employee['position']) ?>" readonly>
                        </div>
                        <div class="form-group-industrial">
                            <label for="department" class="form-label-industrial">Departamento</label>
                            <input type="text" class="form-input-industrial" id="department"
                                value="<?= htmlspecialchars($employee['department_name'] ?? 'N/A') ?>" readonly>
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: var(--space-4);">
                        <div class="form-group-industrial">
                            <label for="hire_date" class="form-label-industrial">Fecha Contratación</label>
                            <input type="date" class="form-input-industrial" id="hire_date"
                                value="<?= $employee['hire_date'] ?>" readonly>
                        </div>
                        <div class="form-group-industrial">
                            <label for="role" class="form-label-industrial">Rol Sistema</label>
                            <input type="text" class="form-input-industrial" id="role"
                                value="<?= htmlspecialchars(ucfirst(str_replace('_', ' ', $employee['role']))) ?>" readonly>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-actions-industrial" style="margin-top: var(--space-6);">
                <button type="submit" class="btn-industrial btn-primary-industrial">
                    <i class="fas fa-save"></i>
                    Guardar Cambios
                </button>
                <a href="/employees/profile" class="btn-industrial btn-secondary-industrial">
                    <i class="fas fa-times-circle"></i>
                    Cancelar
                </a>
            </div>
        </form>

        <div style="display: grid; gap: var(--space-6); align-items: start; max-width: 360px; width: 100%;">
            <div class="widget-card-industrial" style="border-left: 4px solid var(--primary);">
                <div class="widget-header">
                    <h3 class="widget-title">
                        <i class="fas fa-info-circle"></i>
                        Información Importante
                    </h3>
                </div>
                <div class="widget-body">
                    <h4 style="font-size: var(--text-sm); color: var(--text-primary); margin-bottom: var(--space-2);">Campos Editables</h4>
                    <ul style="margin: 0 0 var(--space-4) var(--space-5); color: var(--text-secondary); font-size: var(--text-sm);">
                        <li>Nombre y Apellido</li>
                        <li>Teléfono</li>
                        <li>Dirección completa</li>
                        <li>Ciudad y Código Postal</li>
                    </ul>

                    <h4 style="font-size: var(--text-sm); color: var(--text-primary); margin-bottom: var(--space-2);">Campos No Editables</h4>
                    <ul style="margin: 0 0 var(--space-4) var(--space-5); color: var(--text-secondary); font-size: var(--text-sm);">
                        <li>Email (requiere autorización)</li>
                        <li>NIF</li>
                        <li>Número de empleado</li>
                        <li>Puesto y Departamento</li>
                        <li>Información salarial</li>
                    </ul>

                    <div class="info-box-industrial">
                        <p><strong>Nota:</strong> Para cambiar campos no editables, contacta con el departamento de Recursos Humanos.</p>
                    </div>
                </div>
            </div>

            <?php if ($auth['role'] === 'admin'): ?>
                <div class="widget-card-industrial" style="border-left: 4px solid var(--warning);">
                    <div class="widget-header">
                        <h3 class="widget-title">
                            <i class="fas fa-shield-alt"></i>
                            Acceso Administrador
                        </h3>
                    </div>
                    <div class="widget-body">
                        <p style="font-size: var(--text-sm); color: var(--text-secondary); line-height: 1.6;">
                            Como administrador, puedes acceder a la gestión completa de empleados desde:
                        </p>
                        <a href="/employees" class="btn-industrial btn-secondary-industrial" style="margin-top: var(--space-3);">
                            <i class="fas fa-users"></i>
                            Gestión de Empleados
                        </a>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

<script>
    // Form validation
    (function() {
        'use strict'

        var forms = document.querySelectorAll('.needs-validation')

        Array.prototype.slice.call(forms).forEach(function(form) {
            form.addEventListener('submit', function(event) {
                if (!form.checkValidity()) {
                    event.preventDefault()
                    event.stopPropagation()
                }

                form.classList.add('was-validated')
            }, false)
        })
    })()

    // Phone number formatting
    document.getElementById('phone').addEventListener('input', function(e) {
        let value = e.target.value.replace(/\s/g, '');

        // Format Spanish phone numbers
        if (value.length > 0 && !value.startsWith('+')) {
            // Remove any non-digit characters
            value = value.replace(/\D/g, '');

            // Limit to 9 digits for Spanish numbers
            if (value.length > 9) {
                value = value.substring(0, 9);
            }

            e.target.value = value;
        }
    });

    // Postal code validation
    document.getElementById('postal_code').addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');

        if (value.length > 5) {
            value = value.substring(0, 5);
        }

        e.target.value = value;
    });
</script>

<?php require dirname(__DIR__) . '/layouts/footer.php'; ?>