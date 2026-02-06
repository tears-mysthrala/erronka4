<?php require dirname(__DIR__) . '/layouts/header.php'; ?>

<div class="dashboard-container">
    <div class="page-header">
        <div>
            <h1 class="page-title">
                <i class="fas fa-user-circle"></i>
                Mi Perfil
            </h1>
            <p class="page-subtitle">Datos personales y configuración de cuenta</p>
        </div>
        <div class="page-actions">
            <?php if ($canEdit): ?>
                <a href="/employees/profile/edit" class="btn-industrial btn-primary-industrial">
                    <i class="fas fa-pen"></i>
                    Editar Perfil
                </a>
            <?php endif; ?>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: minmax(0, 2fr) minmax(0, 1fr); gap: var(--space-6); align-items: start;">
        <div class="widget-card-industrial">
            <div class="widget-header">
                <h3 class="widget-title">
                    <i class="fas fa-id-card"></i>
                    Información Personal
                </h3>
            </div>
            <div class="widget-body">
                <div class="table-container-industrial">
                    <table class="table-industrial">
                        <tbody>
                            <tr>
                                <td><strong>Número Empleado</strong></td>
                                <td><?= htmlspecialchars($employee['employee_number']) ?></td>
                            </tr>
                            <tr>
                                <td><strong>Nombre</strong></td>
                                <td><?= htmlspecialchars($employee['first_name'] . ' ' . $employee['last_name']) ?></td>
                            </tr>
                            <tr>
                                <td><strong>NIF</strong></td>
                                <td><?= htmlspecialchars($employee['nif']) ?></td>
                            </tr>
                            <tr>
                                <td><strong>Email</strong></td>
                                <td><?= htmlspecialchars($employee['email']) ?></td>
                            </tr>
                            <tr>
                                <td><strong>Puesto</strong></td>
                                <td><?= htmlspecialchars($employee['position']) ?></td>
                            </tr>
                            <tr>
                                <td><strong>Departamento</strong></td>
                                <td><?= htmlspecialchars($employee['department_name'] ?? 'N/A') ?></td>
                            </tr>
                            <tr>
                                <td><strong>Teléfono</strong></td>
                                <td><?= htmlspecialchars($employee['phone'] ?? 'N/A') ?></td>
                            </tr>
                            <tr>
                                <td><strong>Dirección</strong></td>
                                <td>
                                    <?php if (!empty($employee['address'])): ?>
                                        <?= htmlspecialchars($employee['address']) ?><br>
                                        <?php if (!empty($employee['postal_code']) || !empty($employee['city'])): ?>
                                            <?= htmlspecialchars($employee['postal_code'] ?? '') ?> <?= htmlspecialchars($employee['city'] ?? '') ?><br>
                                        <?php endif; ?>
                                        <?= htmlspecialchars($employee['country'] ?? 'España') ?>
                                    <?php else: ?>
                                        N/A
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Fecha Contratación</strong></td>
                                <td><?= date('d/m/Y', strtotime($employee['hire_date'])) ?></td>
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
                                <td><strong>Estado</strong></td>
                                <td>
                                    <?php if ($employee['is_active']): ?>
                                        <span class="table-badge table-badge-success">Activo</span>
                                    <?php else: ?>
                                        <span class="table-badge table-badge-secondary">Inactivo</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div style="display: grid; gap: var(--space-6); align-items: start; max-width: 360px; width: 100%;">
            <div class="widget-card-industrial">
                <div class="widget-header">
                    <h3 class="widget-title">
                        <i class="fas fa-bolt"></i>
                        Acciones Rápidas
                    </h3>
                </div>
                <div class="widget-body" style="display: grid; gap: var(--space-3);">
                    <?php if ($canEdit): ?>
                        <a href="/employees/profile/edit" class="btn-industrial btn-primary-industrial">
                            <i class="fas fa-pen"></i>
                            Editar Perfil
                        </a>
                    <?php endif; ?>

                    <?php if ($auth['role'] === 'admin'): ?>
                        <a href="/employees" class="btn-industrial btn-secondary-industrial">
                            <i class="fas fa-users"></i>
                            Ver Todos los Empleados
                        </a>
                    <?php endif; ?>

                    <a href="/dashboard" class="btn-industrial btn-secondary-industrial">
                        <i class="fas fa-chart-line"></i>
                        Dashboard
                    </a>
                </div>
            </div>

            <?php if ($auth['role'] === 'admin'): ?>
                <div class="widget-card-industrial">
                    <div class="widget-header">
                        <h3 class="widget-title">
                            <i class="fas fa-shield-alt"></i>
                            Permisos Administrador
                        </h3>
                    </div>
                    <div class="widget-body">
                        <p style="font-size: var(--text-sm); color: var(--text-secondary); margin: 0; line-height: 1.6;">
                            Como administrador, tienes acceso completo a todas las funciones del sistema incluyendo
                            gestión de empleados, usuarios, vacaciones, documentos e informes.
                        </p>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require dirname(__DIR__) . '/layouts/footer.php'; ?>