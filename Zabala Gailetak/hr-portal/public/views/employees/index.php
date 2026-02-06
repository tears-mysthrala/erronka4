<?php require dirname(__DIR__) . '/layouts/header.php'; ?>

<div class="dashboard-container">
    <!-- Page Header -->
    <div class="page-header">
        <div>
            <h1 class="page-title">
                <i class="fas fa-users"></i>
                Gestión de Empleados
            </h1>
            <p class="page-subtitle">Administración del personal de Zabala Gailetak</p>
        </div>
        <div class="page-actions">
            <?php if ($auth['role'] === 'admin' || $auth['role'] === 'hr_manager'): ?>
                <button class="btn-industrial btn-secondary-industrial">
                    <i class="fas fa-download"></i>
                    Exportar
                </button>
                <a href="/employees/create" class="btn-industrial btn-primary-industrial">
                    <i class="fas fa-user-plus"></i>
                    Nuevo Empleado
                </a>
            <?php endif; ?>
            <a href="/employees/profile" class="btn-industrial btn-ghost-industrial">
                <i class="fas fa-id-card"></i>
                Mi Perfil
            </a>
        </div>
    </div>

    <!-- Employees Table Card -->
    <div class="widget-card-industrial">
        <div class="widget-header">
            <h3 class="widget-title">
                <i class="fas fa-list"></i>
                Listado de Empleados
            </h3>
            <div style="display: flex; gap: var(--space-3); align-items: center;">
                <span style="font-size: var(--text-sm); color: var(--text-tertiary);">
                    <i class="fas fa-users"></i>
                    <?= count($employees) ?> empleados
                </span>
            </div>
        </div>
        <div class="widget-body" style="padding: 0;">
            <div class="table-container-industrial">
                <table class="table-industrial">
                    <thead>
                        <tr>
                            <th><i class="fas fa-hashtag"></i> ID</th>
                            <th><i class="fas fa-user"></i> Nombre</th>
                            <th><i class="fas fa-envelope"></i> Email</th>
                            <th><i class="fas fa-briefcase"></i> Puesto</th>
                            <th><i class="fas fa-building"></i> Departamento</th>
                            <th><i class="fas fa-shield-alt"></i> Rol</th>
                            <th><i class="fas fa-toggle-on"></i> Estado</th>
                            <?php if ($auth['role'] === 'admin' || $auth['role'] === 'hr_manager'): ?>
                            <th><i class="fas fa-cog"></i> Acciones</th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($employees as $employee): ?>
                        <tr>
                            <td>
                                <span class="table-badge table-badge-secondary">
                                    <?= htmlspecialchars($employee['employee_number']) ?>
                                </span>
                            </td>
                            <td>
                                <div class="table-user">
                                    <div class="table-avatar">
                                        <?= strtoupper(substr($employee['first_name'], 0, 1)) ?>
                                    </div>
                                    <span class="table-user-name">
                                        <?= htmlspecialchars($employee['first_name'] . ' ' . $employee['last_name']) ?>
                                    </span>
                                </div>
                            </td>
                            <td>
                                <a href="mailto:<?= htmlspecialchars($employee['email']) ?>" class="table-link">
                                    <?= htmlspecialchars($employee['email']) ?>
                                </a>
                            </td>
                            <td><?= htmlspecialchars($employee['position']) ?></td>
                            <td><?= htmlspecialchars($employee['department_name'] ?? 'N/A') ?></td>
                            <td>
                                <?php
                                $roleColors = [
                                    'admin' => 'danger',
                                    'hr_manager' => 'primary',
                                    'department_head' => 'accent',
                                    'employee' => 'secondary'
                                ];
                                $roleName = str_replace('_', ' ', ucfirst($employee['role']));
                                $roleColor = $roleColors[$employee['role']] ?? 'secondary';
                                ?>
                                <span class="table-badge table-badge-<?= $roleColor ?>">
                                    <?= htmlspecialchars($roleName) ?>
                                </span>
                            </td>
                            <td>
                                <?php if ($employee['is_active']): ?>
                                    <span class="table-badge table-badge-success">
                                        <i class="fas fa-check-circle"></i>
                                        Activo
                                    </span>
                                <?php else: ?>
                                    <span class="table-badge table-badge-secondary">
                                        <i class="fas fa-times-circle"></i>
                                        Inactivo
                                    </span>
                                <?php endif; ?>
                            </td>
                            <?php if ($auth['role'] === 'admin' || $auth['role'] === 'hr_manager'): ?>
                            <td>
                                <div class="table-actions">
                                    <a href="/employees/show/<?= $employee['id'] ?>" class="table-action" title="Ver">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="/employees/edit/<?= $employee['id'] ?>" class="table-action" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <?php if ($employee['id'] !== $auth['user_id']): ?>
                                    <button class="table-action table-action-danger" 
                                            onclick="confirmDelete(<?= $employee['id'] ?>)" 
                                            title="Eliminar">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <?php endif; ?>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
function confirmDelete(employeeId) {
    if (confirm('¿Estás seguro de que deseas eliminar este empleado?')) {
        window.location.href = `/employees/delete/${employeeId}`;
    }
}
</script>

<?php require dirname(__DIR__) . '/layouts/footer.php'; ?>
