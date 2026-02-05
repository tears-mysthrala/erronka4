<?php require dirname(__DIR__) . '/layouts/header.php'; ?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Editar Empleado</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="/employees/show/<?= $employee['id'] ?>" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Volver
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

<form action="/employees/edit/<?= $employee['id'] ?>" method="POST" class="row g-3">
    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token ?? '') ?>">
    <div class="col-md-6">
        <label class="form-label">Nombre</label>
        <input type="text" class="form-control" name="first_name" value="<?= htmlspecialchars($old['first_name'] ?? $employee['first_name']) ?>" required>
    </div>
    <div class="col-md-6">
        <label class="form-label">Apellido</label>
        <input type="text" class="form-control" name="last_name" value="<?= htmlspecialchars($old['last_name'] ?? $employee['last_name']) ?>" required>
    </div>
    <div class="col-md-6">
        <label class="form-label">NIF</label>
        <input type="text" class="form-control" name="nif" value="<?= htmlspecialchars($old['nif'] ?? $employee['nif']) ?>" required>
    </div>
    <div class="col-md-6">
        <label class="form-label">Email</label>
        <input type="email" class="form-control" name="email" value="<?= htmlspecialchars($old['email'] ?? $employee['email']) ?>" required>
        <?php if ($auth['role'] !== 'admin'): ?>
            <div class="form-text">Solo administradores pueden cambiar el email.</div>
        <?php endif; ?>
    </div>
    <div class="col-md-6">
        <label class="form-label">Puesto</label>
        <input type="text" class="form-control" name="position" value="<?= htmlspecialchars($old['position'] ?? $employee['position']) ?>">
    </div>
    <?php if ($auth['role'] === 'admin'): ?>
        <div class="col-md-6">
            <label class="form-label">Rol en el Sistema</label>
            <select class="form-select" name="role">
                <option value="employee" <?= ($old['role'] ?? $employee['role']) === 'employee' ? 'selected' : '' ?>>Empleado</option>
                <option value="department_head" <?= ($old['role'] ?? $employee['role']) === 'department_head' ? 'selected' : '' ?>>Jefe de Departamento</option>
                <option value="hr_manager" <?= ($old['role'] ?? $employee['role']) === 'hr_manager' ? 'selected' : '' ?>>Responsable de RRHH</option>
                <option value="admin" <?= ($old['role'] ?? $employee['role']) === 'admin' ? 'selected' : '' ?>>Administrador</option>
            </select>
            <div class="form-text">⚠️ Cambiar el rol afecta los permisos del usuario en todo el sistema.</div>
        </div>
    <?php endif; ?>
    <div class="col-md-6">
        <label class="form-label">Departamento</label>
        <select class="form-select" name="department_id">
            <?php foreach ($departments as $dept): ?>
                <option value="<?= $dept['id'] ?>" <?= $dept['id'] == $employee['department_id'] ? 'selected' : '' ?>><?= htmlspecialchars($dept['name']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="col-md-6">
        <label class="form-label">Salario</label>
        <input type="number" step="0.01" class="form-control" name="salary" value="<?= $employee['salary'] ?>">
    </div>
    <div class="col-12">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="active" id="active" <?= $employee['is_active'] ? 'checked' : '' ?>>
            <label class="form-check-label" for="active">Empleado Activo</label>
        </div>
    </div>
    <div class="col-12 mt-4">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save"></i> Guardar Cambios
        </button>
        <a href="/employees/show/<?= $employee['id'] ?>" class="btn btn-outline-secondary">
            <i class="bi bi-x-circle"></i> Cancelar
        </a>
    </div>
</form>

<?php if ($auth['role'] === 'admin' && $employee['role'] !== 'admin'): ?>
    <div class="mt-5 pt-3 border-top">
        <div class="card border-danger">
            <div class="card-header bg-danger text-white">
                <h5 class="card-title mb-0">
                    <i class="bi bi-exclamation-triangle"></i> Zona Peligrosa
                </h5>
            </div>
            <div class="card-body">
                <p class="mb-3">La eliminación de un empleado es una acción que desactivará su acceso al sistema. El empleado no podrá iniciar sesión pero sus datos permanecerán en el sistema para registros históricos.</p>

                <div class="alert alert-warning" role="alert">
                    <i class="bi bi-info-circle"></i>
                    <strong>Importante:</strong> Esta acción es reversible. Un administrador puede reactivar la cuenta del empleado si es necesario.
                </div>

                <form action="/employees/delete/<?= $employee['id'] ?>" method="POST" onsubmit="return confirm('¿Estás seguro de que deseas desactivar al empleado '<?= htmlspecialchars($employee['first_name'] . ' ' . $employee['last_name']) ?>\'?');">
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-person-x"></i> Desactivar Empleado
                    </button>
                </form>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php require dirname(__DIR__) . '/layouts/footer.php'; ?>