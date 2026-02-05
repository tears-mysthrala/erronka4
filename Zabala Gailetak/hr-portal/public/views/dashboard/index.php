<?php require dirname(__DIR__) . '/layouts/header.php'; ?>

<div class="dashboard-container">
    <div class="dashboard-header">
        <h1 class="dashboard-title">Centro de Control</h1>
        <p class="dashboard-subtitle">Sistema de Gestión de Recursos Humanos - Zabala Gailetak</p>
    </div>

    <!-- Stats Grid -->
    <div class="stats-grid">
        <!-- Empleados Activos -->
        <div class="stat-card-industrial">
            <div class="stat-header">
                <div class="stat-icon">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-badge">ACTIVOS</div>
            </div>
            <div class="stat-label">Empleados</div>
            <div class="stat-value"><?= htmlspecialchars($stats['employees'] ?? '0') ?></div>
            <a href="/employees" class="stat-link">
                Ver listado completo
                <i class="fas fa-arrow-right"></i>
            </a>
        </div>

        <!-- Presentes Hoy -->
        <div class="stat-card-industrial">
            <div class="stat-header">
                <div class="stat-icon">
                    <i class="fas fa-clipboard-check"></i>
                </div>
                <div class="stat-badge">HOY</div>
            </div>
            <div class="stat-label">Presentes</div>
            <div class="stat-value">118</div>
            <div style="margin-top: var(--space-4); font-size: var(--text-sm); color: var(--text-tertiary);">
                <i class="fas fa-chart-line"></i>
                95% asistencia
            </div>
        </div>

        <!-- Vacaciones Pendientes -->
        <div class="stat-card-industrial">
            <div class="stat-header">
                <div class="stat-icon">
                    <i class="fas fa-umbrella-beach"></i>
                </div>
                <div class="stat-badge">PENDIENTES</div>
            </div>
            <div class="stat-label">Solicitudes</div>
            <div class="stat-value"><?= htmlspecialchars($stats['pending_vacations'] ?? '0') ?></div>
            <a href="/vacations" class="stat-link">
                Gestionar solicitudes
                <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    </div>

    <!-- Widgets Grid -->
    <div class="widget-grid">
        <!-- Próximas Vacaciones -->
        <div class="widget-card-industrial">
            <div class="widget-header">
                <h3 class="widget-title">
                    <i class="fas fa-calendar-alt"></i>
                    Próximas Vacaciones
                </h3>
                <span style="font-size: var(--text-xs); color: var(--text-tertiary);">
                    <i class="fas fa-clock"></i>
                    Próximos 30 días
                </span>
            </div>
            <div class="widget-body">
                <?php if (!empty($upcomingVacations)): ?>
                    <ul class="widget-list">
                        <?php foreach ($upcomingVacations as $vacation): ?>
                            <li class="widget-list-item">
                                <div class="item-user">
                                    <div class="item-avatar">
                                        <?= strtoupper(substr($vacation['first_name'], 0, 1)) ?>
                                    </div>
                                    <div class="item-details">
                                        <div class="item-name">
                                            <?= htmlspecialchars($vacation['first_name'] . ' ' . $vacation['last_name']) ?>
                                        </div>
                                        <div class="item-meta">
                                            <i class="fas fa-building"></i>
                                            Departamento
                                        </div>
                                    </div>
                                </div>
                                <div class="item-badge">
                                    <i class="fas fa-calendar"></i>
                                    <?= date('d/m', strtotime($vacation['start_date'])) ?> - <?= date('d/m', strtotime($vacation['end_date'])) ?>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <div class="empty-state">
                        <i class="fas fa-calendar-times"></i>
                        <p>No hay vacaciones programadas</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Actividad Reciente -->
        <div class="widget-card-industrial">
            <div class="widget-header">
                <h3 class="widget-title">
                    <i class="fas fa-history"></i>
                    Actividad Reciente
                </h3>
                <span style="font-size: var(--text-xs); color: var(--text-tertiary);">
                    <i class="fas fa-clock"></i>
                    Última hora
                </span>
            </div>
            <div class="widget-body">
                <ul class="widget-list">
                    <li class="widget-list-item">
                        <div class="item-user">
                            <div class="item-avatar">A</div>
                            <div class="item-details">
                                <div class="item-name">Nueva solicitud de vacaciones</div>
                                <div class="item-meta">
                                    <i class="fas fa-clock"></i>
                                    Hace 15 minutos
                                </div>
                            </div>
                        </div>
                        <div style="padding: 4px 12px; background: rgba(5, 150, 105, 0.15); color: #10B981; font-size: 11px; font-weight: 700; border-radius: 999px;">
                            NUEVO
                        </div>
                    </li>
                    <li class="widget-list-item">
                        <div class="item-user">
                            <div class="item-avatar">B</div>
                            <div class="item-details">
                                <div class="item-name">Empleado actualizado</div>
                                <div class="item-meta">
                                    <i class="fas fa-clock"></i>
                                    Hace 1 hora
                                </div>
                            </div>
                        </div>
                        <div style="padding: 4px 12px; background: rgba(2, 132, 199, 0.15); color: #0284C7; font-size: 11px; font-weight: 700; border-radius: 999px;">
                            INFO
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<?php require dirname(__DIR__) . '/layouts/footer.php'; ?>