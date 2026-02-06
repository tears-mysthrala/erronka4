<?php require dirname(__DIR__) . '/layouts/header.php'; ?>

<?php 
$canEdit = ($auth['role'] === 'admin') || 
          ($auth['role'] === 'hr_manager' && $employee['role'] !== 'admin') ||
          ($auth['role'] === 'department_head' && $employee['department_id'] === $auth['department_id']) ||
          ($employee['user_id'] === $auth['user_id']);
?>

<div class="dashboard-container">
    <div class="page-header">
        <div>
            <h1 class="page-title">
                <i class="fas fa-id-badge"></i>
                Ficha de Empleado
            </h1>
            <p class="page-subtitle">Detalle completo del empleado</p>
        </div>
        <div class="page-actions">
            <?php if ($canEdit): ?>
                <a href="/employees/edit/<?= $employee['id'] ?>" class="btn-industrial btn-secondary-industrial">
                    <i class="fas fa-pen"></i>
                    Editar
                </a>
            <?php endif; ?>

            <?php if ($auth['role'] === 'admin' && $employee['role'] !== 'admin'): ?>
                <a href="/employees/delete/<?= $employee['id'] ?>" class="btn-industrial btn-danger-industrial"
                   onclick="return confirm('¿Estás seguro de eliminar a este empleado? Esta acción desactivará su cuenta.')">
                    <i class="fas fa-trash"></i>
                    Eliminar
                </a>
            <?php endif; ?>

            <a href="/employees" class="btn-industrial btn-secondary-industrial">
                <i class="fas fa-arrow-left"></i>
                Volver
            </a>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: minmax(0, 1fr) minmax(0, 2fr); gap: var(--space-6); align-items: start;">
        <div class="widget-card-industrial" style="text-align: center;">
            <div class="widget-body">
                <img src="https://ui-avatars.com/api/?name=<?= urlencode($employee['first_name'] . ' ' . $employee['last_name']) ?>&size=128" alt="avatar" style="width: 120px; height: 120px; border-radius: 50%; border: 4px solid rgba(29, 78, 216, 0.15); margin-bottom: var(--space-4);">
                <h3 style="margin: 0 0 var(--space-2); font-size: var(--text-xl); color: var(--text-primary);">
                    <?= htmlspecialchars($employee['first_name'] . ' ' . $employee['last_name']) ?>
                </h3>
                <p style="margin: 0; color: var(--text-secondary);">
                    <?= htmlspecialchars($employee['position']) ?>
                </p>
                <p style="margin: var(--space-1) 0 var(--space-4); color: var(--text-tertiary);">
                    <?= htmlspecialchars($employee['department_name'] ?? 'Sin departamento') ?>
                </p>
                <?php if ($employee['is_active']): ?>
                    <span class="table-badge table-badge-success">Cuenta Activa</span>
                <?php else: ?>
                    <span class="table-badge table-badge-danger">Cuenta Inactiva</span>
                <?php endif; ?>
            </div>
        </div>

        <div class="widget-card-industrial">
            <div class="widget-header">
                <h3 class="widget-title">
                    <i class="fas fa-user"></i>
                    Datos del Empleado
                </h3>
            </div>
            <div class="widget-body" style="padding: 0;">
                <div class="table-container-industrial">
                    <table class="table-industrial">
                        <tbody>
                            <tr>
                                <td><strong>Nombre Completo</strong></td>
                                <td><?= htmlspecialchars($employee['first_name'] . ' ' . $employee['last_name']) ?></td>
                            </tr>
                            <tr>
                                <td><strong>Email</strong></td>
                                <td><?= htmlspecialchars($employee['email']) ?></td>
                            </tr>
                            <tr>
                                <td><strong>NIF/NIE</strong></td>
                                <td><?= htmlspecialchars($employee['nif']) ?></td>
                            </tr>
                            <tr>
                                <td><strong>ID Empleado</strong></td>
                                <td><?= htmlspecialchars($employee['employee_number']) ?></td>
                            </tr>
                            <tr>
                                <td><strong>Rol Sistema</strong></td>
                                <td>
                                    <?php
                                    $roleBadge = match ($employee['role']) {
                                        'admin' => 'danger',
                                        'hr_manager' => 'warning',
                                        'department_head' => 'primary',
                                        default => 'secondary'
                                    };
                                    ?>
                                    <span class="table-badge table-badge-<?= $roleBadge ?>">
                                        <?= htmlspecialchars(ucfirst(str_replace('_', ' ', $employee['role']))) ?>
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Puesto</strong></td>
                                <td><?= htmlspecialchars($employee['position']) ?></td>
                            </tr>
                            <tr>
                                <td><strong>Departamento</strong></td>
                                <td><?= htmlspecialchars($employee['department_name'] ?? 'Sin departamento') ?></td>
                            </tr>
                            <tr>
                                <td><strong>Fecha Contratación</strong></td>
                                <td><?= date('d/m/Y', strtotime($employee['hire_date'])) ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require dirname(__DIR__) . '/layouts/footer.php'; ?>
