<?php require dirname(__DIR__) . '/layouts/header.php'; ?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Nuevo Empleado</h1>
</div>

<form action="/employees/create" method="POST" class="row g-3 needs-validation" novalidate>
    <div class="col-md-6">
        <label for="first_name" class="form-label">Nombre</label>
        <input type="text" class="form-control" id="first_name" name="first_name" required>
    </div>
    <div class="col-md-6">
        <label for="last_name" class="form-label">Apellido</label>
        <input type="text" class="form-control" id="last_name" name="last_name" required>
    </div>
    <div class="col-md-6">
        <label for="email" class="form-label">Email Corporativo</label>
        <input type="email" class="form-control" id="email" name="email" required>
    </div>
    <div class="col-md-6">
        <label for="nif" class="form-label">NIF</label>
        <input type="text" class="form-control" id="nif" name="nif" required>
    </div>
    <div class="col-md-6">
        <label for="position" class="form-label">Puesto</label>
        <input type="text" class="form-control" id="position" name="position">
    </div>
    <?php if ($auth['role'] === 'admin'): ?>
    <div class="col-md-6">
        <label for="role" class="form-label">Rol en el Sistema</label>
        <select class="form-select" id="role" name="role">
            <option value="employee">Empleado</option>
            <option value="department_head">Jefe de Departamento</option>
            <option value="hr_manager">Responsable de RRHH</option>
            <option value="admin">Administrador</option>
        </select>
        <div class="form-text">Selecciona el nivel de acceso que tendrá este usuario en el sistema.</div>
    </div>
    <?php else: ?>
    <input type="hidden" name="role" value="employee">
    <?php endif; ?>
    <div class="col-md-6">
        <label for="department_id" class="form-label">Departamento</label>
        <select class="form-select" id="department_id" name="department_id">
            <option selected disabled value="">Elegir...</option>
            <?php foreach ($departments as $dept): ?>
                <option value="<?= $dept['id'] ?>"><?= htmlspecialchars($dept['name']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="col-md-6">
        <label for="hire_date" class="form-label">Fecha Contratación</label>
        <input type="date" class="form-control" id="hire_date" name="hire_date" value="<?= date('Y-m-d') ?>">
    </div>
    <div class="col-md-6">
        <label for="salary" class="form-label">Salario Bruto Anual</label>
        <input type="number" class="form-control" id="salary" name="salary" step="0.01">
    </div>
    
    <div class="col-12">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="send_welcome_email" id="send_welcome_email" checked>
            <label class="form-check-label" for="send_welcome_email">
                Enviar email de bienvenida con credenciales
            </label>
            <div class="form-text">Se enviará un email con las instrucciones de acceso al sistema.</div>
        </div>
    </div>
    
    <div class="col-12 mt-4">
        <button class="btn btn-primary" type="submit">
            <i class="bi bi-person-plus"></i> Guardar Empleado
        </button>
        <a href="/employees" class="btn btn-outline-secondary">
            <i class="bi bi-x-circle"></i> Cancelar
        </a>
    </div>
</form>

<?php require dirname(__DIR__) . '/layouts/footer.php'; ?>
