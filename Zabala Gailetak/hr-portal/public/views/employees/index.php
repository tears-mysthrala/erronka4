<?php require dirname(__DIR__) . '/layouts/header.php'; ?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Empleados</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <?php if ($auth['role'] === 'admin' || $auth['role'] === 'hr_manager'): ?>
            <a href="/employees/export" class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-download"></i> Exportar
            </a>
        <?php endif; ?>
        
        <?php if ($auth['role'] === 'admin' || $auth['role'] === 'hr_manager'): ?>
            <a href="/employees/create" class="btn btn-sm btn-primary ms-2">
                <i class="bi bi-plus-circle"></i> Nuevo Empleado
            </a>
        <?php endif; ?>
        
        <a href="/employees/profile" class="btn btn-sm btn-outline-info ms-2">
            <i class="bi bi-person"></i> Mi Perfil
        </a>
    </div>
</div>

<div class="table-responsive">
    <table class="table table-striped table-sm">
        <thead>
            <tr>
                <th>#</th>
                <th>Nombre</th>
                <th>Email</th>
                <th>Puesto</th>
                <th>Departamento</th>
                <th>Rol</th>
                <th>Estado</th>
                <?php if ($auth['role'] === 'admin' || $auth['role'] === 'hr_manager'): ?>
                <th>Acciones</th>
                <?php endif; ?>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($employees as $employee): ?>
            <tr>
                <td><?= htmlspecialchars($employee['employee_number']) ?></td>
                <td><?= htmlspecialchars($employee['first_name'] . ' ' . $employee['last_name']) ?></td>
                <td><?= htmlspecialchars($employee['email']) ?></td>
                <td><?= htmlspecialchars($employee['position']) ?></td>
                <td><?= htmlspecialchars($employee['department_name'] ?? 'N/A') ?></td>
                <td>
                    <span class="badge bg-<?= $employee['role'] === 'admin' ? 'danger' : ($employee['role'] === 'hr_manager' ? 'warning' : ($employee['role'] === 'department_head' ? 'info' : 'secondary')) ?>">
                        <?= htmlspecialchars(ucfirst(str_replace('_', ' ', $employee['role']))) ?>
                    </span>
                </td>
                <td>
                    <?php if ($employee['is_active']): ?>
                        <span class="badge bg-success">Activo</span>
                    <?php else: ?>
                        <span class="badge bg-secondary">Inactivo</span>
                    <?php endif; ?>
                </td>
                <?php if ($auth['role'] === 'admin' || $auth['role'] === 'hr_manager'): ?>
                <td>
                    <div class="btn-group" role="group">
                        <a href="/employees/show/<?= $employee['id'] ?>" class="btn btn-sm btn-outline-info" title="Ver">
                            <i class="bi bi-eye"></i>
                        </a>
                        <?php if ($auth['role'] === 'admin' || ($auth['role'] === 'hr_manager' && $employee['role'] !== 'admin')): ?>
                            <a href="/employees/edit/<?= $employee['id'] ?>" class="btn btn-sm btn-outline-warning" title="Editar">
                                <i class="bi bi-pencil"></i>
                            </a>
                        <?php endif; ?>
                        <?php if ($auth['role'] === 'admin' && $employee['role'] !== 'admin'): ?>
                            <button class="btn btn-sm btn-outline-danger" onclick="confirmDelete('<?= $employee['id'] ?>', '<?= htmlspecialchars($employee['first_name'] . ' ' . $employee['last_name']) ?>')" title="Eliminar">
                                <i class="bi bi-trash"></i>
                            </button>
                        <?php endif; ?>
                    </div>
                </td>
                <?php endif; ?>
            </tr>
            <?php endforeach; ?>
            <?php if (empty($employees)): ?>
            <tr>
                <td colspan="<?= ($auth['role'] === 'admin' || $auth['role'] === 'hr_manager') ? 8 : 7 ?>" class="text-center">
                    No hay empleados registrados.
                </td>
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

<?php if ($auth['role'] === 'admin'): ?>
<script>
function confirmDelete(id, name) {
    if (confirm(`¿Estás seguro de que deseas eliminar al empleado "${name}"? Esta acción desactivará su cuenta y no podrá acceder al sistema.`)) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/employees/delete/${id}`;
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
<?php endif; ?>

<?php require dirname(__DIR__) . '/layouts/footer.php'; ?>