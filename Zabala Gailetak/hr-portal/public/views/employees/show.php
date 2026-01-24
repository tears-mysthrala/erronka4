<?php require dirname(__DIR__) . '/layouts/header.php'; ?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Ficha de Empleado</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="/employees/edit/<?= $employee['id'] ?>" class="btn btn-sm btn-outline-primary me-2">Editar</a>
        <a href="/employees" class="btn btn-sm btn-outline-secondary">Volver</a>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-body text-center">
                <img src="https://ui-avatars.com/api/?name=<?= urlencode($employee['first_name'] . ' ' . $employee['last_name']) ?>&size=128" alt="avatar" class="rounded-circle img-fluid" style="width: 150px;">
                <h5 class="my-3"><?= htmlspecialchars($employee['first_name'] . ' ' . $employee['last_name']) ?></h5>
                <p class="text-muted mb-1"><?= htmlspecialchars($employee['position']) ?></p>
                <p class="text-muted mb-4"><?= htmlspecialchars($employee['department_name'] ?? 'Sin departamento') ?></p>
                <div class="d-flex justify-content-center mb-2">
                    <?php if ($employee['is_active']): ?>
                        <span class="badge bg-success">Cuenta Activa</span>
                    <?php else: ?>
                        <span class="badge bg-danger">Cuenta Inactiva</span>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-3"><p class="mb-0">Nombre Completo</p></div>
                    <div class="col-sm-9"><p class="text-muted mb-0"><?= htmlspecialchars($employee['first_name'] . ' ' . $employee['last_name']) ?></p></div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-sm-3"><p class="mb-0">Email</p></div>
                    <div class="col-sm-9"><p class="text-muted mb-0"><?= htmlspecialchars($employee['email']) ?></p></div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-sm-3"><p class="mb-0">NIF/NIE</p></div>
                    <div class="col-sm-9"><p class="text-muted mb-0"><?= htmlspecialchars($employee['nif']) ?></p></div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-sm-3"><p class="mb-0">ID Empleado</p></div>
                    <div class="col-sm-9"><p class="text-muted mb-0"><?= htmlspecialchars($employee['employee_number']) ?></p></div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-sm-3"><p class="mb-0">Fecha Contratación</p></div>
                    <div class="col-sm-9"><p class="text-muted mb-0"><?= htmlspecialchars($employee['hire_date']) ?></p></div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require dirname(__DIR__) . '/layouts/footer.php'; ?>
