<?php require dirname(__DIR__) . '/layouts/header.php'; ?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Editar Mi Perfil</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="/employees/profile" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Volver al Perfil
        </a>
    </div>
</div>

<?php if (!empty($errors)): ?>
    <div class="alert alert-danger">
        <h6><i class="bi bi-exclamation-triangle"></i> Se encontraron errores:</h6>
        <ul class="mb-0">
            <?php foreach ($errors as $field => $error): ?>
                <li><?= htmlspecialchars($error) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<div class="row">
    <div class="col-md-8">
        <form action="/employees/profile/update" method="POST" class="row g-3 needs-validation" novalidate>
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token ?? '') ?>">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Información Personal</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="first_name" class="form-label">Nombre</label>
                            <input type="text" class="form-control" id="first_name" name="first_name"
                                value="<?= htmlspecialchars($old['first_name'] ?? $employee['first_name']) ?>" required>
                            <div class="invalid-feedback">
                                Por favor ingresa tu nombre.
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="last_name" class="form-label">Apellido</label>
                            <input type="text" class="form-control" id="last_name" name="last_name"
                                value="<?= htmlspecialchars($old['last_name'] ?? $employee['last_name']) ?>" required>
                            <div class="invalid-feedback">
                                Por favor ingresa tu apellido.
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="phone" class="form-label">Teléfono</label>
                            <input type="tel" class="form-control" id="phone" name="phone"
                                value="<?= htmlspecialchars($old['phone'] ?? $employee['phone'] ?? '') ?>"
                                placeholder="Ej: 612345678">
                            <div class="form-text">Formato español: 6/7/8/9 + 8 dígitos</div>
                        </div>
                        <div class="col-md-6">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email"
                                value="<?= htmlspecialchars($employee['email']) ?>" disabled>
                            <div class="form-text">El email no puede ser modificado. Contacta con administración si necesitas cambiarlo.</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Dirección</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="address" class="form-label">Dirección</label>
                        <textarea class="form-control" id="address" name="address" rows="2"><?= htmlspecialchars($old['address'] ?? $employee['address'] ?? '') ?></textarea>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="postal_code" class="form-label">Código Postal</label>
                            <input type="text" class="form-control" id="postal_code" name="postal_code"
                                value="<?= htmlspecialchars($old['postal_code'] ?? $employee['postal_code'] ?? '') ?>"
                                pattern="[0-9]{5}" maxlength="5">
                            <div class="form-text">5 dígitos</div>
                        </div>
                        <div class="col-md-4">
                            <label for="city" class="form-label">Ciudad</label>
                            <input type="text" class="form-control" id="city" name="city"
                                value="<?= htmlspecialchars($old['city'] ?? $employee['city'] ?? '') ?>">
                        </div>
                        <div class="col-md-4">
                            <label for="country" class="form-label">País</label>
                            <input type="text" class="form-control" id="country" name="country"
                                value="<?= htmlspecialchars($old['country'] ?? $employee['country'] ?? 'España') ?>">
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Información Laboral (Solo Lectura)</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="employee_number" class="form-label">Número Empleado</label>
                            <input type="text" class="form-control" id="employee_number"
                                value="<?= htmlspecialchars($employee['employee_number']) ?>" readonly>
                        </div>
                        <div class="col-md-6">
                            <label for="nif" class="form-label">NIF</label>
                            <input type="text" class="form-control" id="nif"
                                value="<?= htmlspecialchars($employee['nif']) ?>" readonly>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="position" class="form-label">Puesto</label>
                            <input type="text" class="form-control" id="position"
                                value="<?= htmlspecialchars($employee['position']) ?>" readonly>
                        </div>
                        <div class="col-md-6">
                            <label for="department" class="form-label">Departamento</label>
                            <input type="text" class="form-control" id="department"
                                value="<?= htmlspecialchars($employee['department_name'] ?? 'N/A') ?>" readonly>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="hire_date" class="form-label">Fecha Contratación</label>
                            <input type="date" class="form-control" id="hire_date"
                                value="<?= $employee['hire_date'] ?>" readonly>
                        </div>
                        <div class="col-md-6">
                            <label for="role" class="form-label">Rol Sistema</label>
                            <input type="text" class="form-control" id="role"
                                value="<?= htmlspecialchars(ucfirst(str_replace('_', ' ', $employee['role']))) ?>" readonly>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Guardar Cambios
                        </button>
                        <a href="/employees/profile" class="btn btn-outline-secondary">
                            <i class="bi bi-x-circle"></i> Cancelar
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <div class="col-md-4">
        <div class="card border-info">
            <div class="card-header bg-info text-white">
                <h6 class="card-title mb-0">
                    <i class="bi bi-info-circle"></i> Información Importante
                </h6>
            </div>
            <div class="card-body">
                <h6>Campos Editables:</h6>
                <ul class="small">
                    <li>✅ Nombre y Apellido</li>
                    <li>✅ Teléfono</li>
                    <li>✅ Dirección completa</li>
                    <li>✅ Ciudad y Código Postal</li>
                </ul>

                <h6 class="mt-3">Campos No Editables:</h6>
                <ul class="small">
                    <li>❌ Email (requiere autorización)</li>
                    <li>❌ NIF</li>
                    <li>❌ Número de empleado</li>
                    <li>❌ Puesto y Departamento</li>
                    <li>❌ Información salarial</li>
                </ul>

                <div class="alert alert-warning mt-3" role="alert">
                    <small>
                        <strong>Nota:</strong> Para cambiar campos no editables, contacta con el departamento de Recursos Humanos.
                    </small>
                </div>
            </div>
        </div>

        <?php if ($auth['role'] === 'admin'): ?>
            <div class="card mt-3 border-warning">
                <div class="card-header bg-warning text-dark">
                    <h6 class="card-title mb-0">
                        <i class="bi bi-shield-check"></i> Acceso Administrador
                    </h6>
                </div>
                <div class="card-body">
                    <small>
                        Como administrador, puedes acceder a la gestión completa de empleados desde:
                        <br><br>
                        <a href="/employees" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-people"></i> Gestión de Empleados
                        </a>
                    </small>
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