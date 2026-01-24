<?php require dirname(__DIR__) . '/layouts/header.php'; ?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Empleados</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="/employees/export" class="btn btn-sm btn-outline-secondary">
            Exportar
        </a>
        <a href="/employees/create" class="btn btn-sm btn-primary ms-2">
            Nuevo Empleado
        </a>
    </div>
</div>

<div class="table-responsive">
    <table class="table table-striped table-sm">
        <thead>
            <tr>
                <th>#</th>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>Puesto</th>
                <th>Departamento</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($employees as $employee): ?>
            <tr>
                <td><?= htmlspecialchars($employee['employee_number']) ?></td>
                <td><?= htmlspecialchars($employee['first_name']) ?></td>
                <td><?= htmlspecialchars($employee['last_name']) ?></td>
                <td><?= htmlspecialchars($employee['position']) ?></td>
                <td><?= htmlspecialchars($employee['department_name'] ?? 'N/A') ?></td>
                <td>
                    <?php if ($employee['is_active']): ?>
                        <span class="badge bg-success">Activo</span>
                    <?php else: ?>
                        <span class="badge bg-secondary">Inactivo</span>
                    <?php endif; ?>
                </td>
                <td>
                    <a href="/employees/show/<?= $employee['id'] ?>" class="btn btn-sm btn-outline-info">Ver</a>
                </td>
            </tr>
            <?php endforeach; ?>
            <?php if (empty($employees)): ?>
            <tr>
                <td colspan="6" class="text-center">No hay empleados registrados.</td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php if (isset($totalPages) && $totalPages > 1): ?>
<nav aria-label="Page navigation" class="mt-4">
  <ul class="pagination justify-content-center">
    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
    <li class="page-item <?= ($i === $page) ? 'active' : '' ?>">
        <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
    </li>
    <?php endfor; ?>
  </ul>
</nav>
<?php endif; ?>

<?php require dirname(__DIR__) . '/layouts/footer.php'; ?>