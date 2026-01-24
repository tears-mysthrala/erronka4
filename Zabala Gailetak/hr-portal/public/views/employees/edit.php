<?php require dirname(__DIR__) . '/layouts/header.php'; ?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Editar Empleado</h1>
</div>

<form action="/employees/edit/<?= $employee['id'] ?>" method="POST" class="row g-3">
    <div class="col-md-6">
        <label class="form-label">Nombre</label>
        <input type="text" class="form-control" name="first_name" value="<?= htmlspecialchars($employee['first_name']) ?>" required>
    </div>
    <div class="col-md-6">
        <label class="form-label">Apellido</label>
        <input type="text" class="form-control" name="last_name" value="<?= htmlspecialchars($employee['last_name']) ?>" required>
    </div>
    <div class="col-md-6">
        <label class="form-label">NIF</label>
        <input type="text" class="form-control" name="nif" value="<?= htmlspecialchars($employee['nif']) ?>" required>
    </div>
    <div class="col-md-6">
        <label class="form-label">Puesto</label>
        <input type="text" class="form-control" name="position" value="<?= htmlspecialchars($employee['position']) ?>">
    </div>
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
        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
        <a href="/employees/show/<?= $employee['id'] ?>" class="btn btn-outline-secondary">Cancelar</a>
    </div>
</form>

<div class="mt-5 pt-3 border-top">
    <h3 class="text-danger">Zona Peligrosa</h3>
    <p>La eliminación de un empleado es una acción irreversible que desactivará su acceso al sistema.</p>
    <form action="/employees/delete/<?= $employee['id'] ?>" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar a este empleado?');">
        <button type="submit" class="btn btn-danger">Eliminar Empleado</button>
    </form>
</div>

<?php require dirname(__DIR__) . '/layouts/footer.php'; ?>
