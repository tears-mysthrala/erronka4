<?php require dirname(__DIR__) . '/layouts/header.php'; ?>

<div class="dashboard-container">
    <div class="page-header">
        <div>
            <h1 class="page-title">
                <i class="fas fa-user-edit"></i>
                Editar Empleado
            </h1>
            <p class="page-subtitle">Actualiza la información del empleado</p>
        </div>
        <div class="page-actions">
            <a href="/employees/show/<?= $employee['id'] ?>" class="btn-industrial btn-secondary-industrial">
                <i class="fas fa-arrow-left"></i>
                Volver
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

    <form action="/employees/edit/<?= $employee['id'] ?>" method="POST">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token ?? '') ?>">

        <div class="widget-card-industrial" style="margin-bottom: var(--space-6);">
            <div class="widget-header">
                <h3 class="widget-title">
                    <i class="fas fa-id-card"></i>
                    Información Básica
                </h3>
            </div>
            <div class="widget-body" style="display: grid; gap: var(--space-4);">
                <div style="display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: var(--space-4);">
                    <div class="form-group-industrial">
                        <label class="form-label-industrial">Nombre</label>
                        <input type="text" class="form-input-industrial" name="first_name" value="<?= htmlspecialchars($old['first_name'] ?? $employee['first_name']) ?>" required>
                    </div>
                    <div class="form-group-industrial">
                        <label class="form-label-industrial">Apellido</label>
                        <input type="text" class="form-input-industrial" name="last_name" value="<?= htmlspecialchars($old['last_name'] ?? $employee['last_name']) ?>" required>
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: var(--space-4);">
                    <div class="form-group-industrial">
                        <label class="form-label-industrial">NIF</label>
                        <input type="text" class="form-input-industrial" name="nif" value="<?= htmlspecialchars($old['nif'] ?? $employee['nif']) ?>" required>
                    </div>
                    <div class="form-group-industrial">
                        <label class="form-label-industrial">Email</label>
                        <input type="email" class="form-input-industrial" name="email" value="<?= htmlspecialchars($old['email'] ?? $employee['email']) ?>" required>
                        <?php if ($auth['role'] !== 'admin'): ?>
                            <small class="form-text">Solo administradores pueden cambiar el email.</small>
                        <?php endif; ?>
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: var(--space-4);">
                    <div class="form-group-industrial">
                        <label class="form-label-industrial">Puesto</label>
                        <input type="text" class="form-input-industrial" name="position" value="<?= htmlspecialchars($old['position'] ?? $employee['position']) ?>">
                    </div>
                    <?php if ($auth['role'] === 'admin'): ?>
                        <div class="form-group-industrial">
                            <label class="form-label-industrial">Rol en el Sistema</label>
                            <select class="form-select-industrial" name="role">
                                <option value="employee" <?= ($old['role'] ?? $employee['role']) === 'employee' ? 'selected' : '' ?>>Empleado</option>
                                <option value="department_head" <?= ($old['role'] ?? $employee['role']) === 'department_head' ? 'selected' : '' ?>>Jefe de Departamento</option>
                                <option value="hr_manager" <?= ($old['role'] ?? $employee['role']) === 'hr_manager' ? 'selected' : '' ?>>Responsable de RRHH</option>
                                <option value="admin" <?= ($old['role'] ?? $employee['role']) === 'admin' ? 'selected' : '' ?>>Administrador</option>
                            </select>
                            <small class="form-text">Cambiar el rol afecta los permisos del usuario.</small>
                        </div>
                    <?php endif; ?>
                </div>

                <div style="display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: var(--space-4);">
                    <div class="form-group-industrial">
                        <label class="form-label-industrial">Departamento</label>
                        <select class="form-select-industrial" name="department_id">
                            <?php foreach ($departments as $dept): ?>
                                <option value="<?= $dept['id'] ?>" <?= $dept['id'] == $employee['department_id'] ? 'selected' : '' ?>><?= htmlspecialchars($dept['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group-industrial">
                        <label class="form-label-industrial">Salario</label>
                        <input type="number" step="0.01" class="form-input-industrial" name="salary" value="<?= $employee['salary'] ?>">
                    </div>
                </div>

                <label style="display: flex; align-items: center; gap: var(--space-2); font-size: var(--text-sm); color: var(--text-secondary);">
                    <input class="form-check-input" type="checkbox" name="active" id="active" <?= $employee['is_active'] ? 'checked' : '' ?>>
                    Empleado Activo
                </label>
            </div>
        </div>

        <div class="form-actions-industrial">
            <button type="submit" class="btn-industrial btn-primary-industrial">
                <i class="fas fa-save"></i>
                Guardar Cambios
            </button>
            <a href="/employees/show/<?= $employee['id'] ?>" class="btn-industrial btn-secondary-industrial">
                <i class="fas fa-times-circle"></i>
                Cancelar
            </a>
        </div>
    </form>

    <?php if ($auth['role'] === 'admin' && $employee['role'] !== 'admin'): ?>
        <div class="widget-card-industrial" style="margin-top: var(--space-8); border-left: 4px solid var(--danger);">
            <div class="widget-header">
                <h3 class="widget-title">
                    <i class="fas fa-exclamation-triangle"></i>
                    Zona Peligrosa
                </h3>
            </div>
            <div class="widget-body">
                <p style="margin-bottom: var(--space-4); color: var(--text-secondary);">
                    La eliminación de un empleado desactivará su acceso al sistema. Los datos permanecerán para registros históricos.
                </p>
                <div class="info-box-industrial" style="border-left-color: var(--warning);">
                    <p><strong>Importante:</strong> Esta acción es reversible. Un administrador puede reactivar la cuenta.</p>
                </div>
                <form action="/employees/delete/<?= $employee['id'] ?>" method="POST" onsubmit="return confirm('¿Estás seguro de que deseas desactivar al empleado <?= htmlspecialchars($employee['first_name'] . ' ' . $employee['last_name']) ?>?');">
                    <button type="submit" class="btn-industrial btn-danger-industrial">
                        <i class="fas fa-user-times"></i>
                        Desactivar Empleado
                    </button>
                </form>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php require dirname(__DIR__) . '/layouts/footer.php'; ?>