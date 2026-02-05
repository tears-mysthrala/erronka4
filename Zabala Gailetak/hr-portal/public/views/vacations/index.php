<?php require dirname(__DIR__) . '/layouts/header.php'; ?>

<div class="dashboard-container">
    <!-- Page Header -->
    <div class="page-header">
        <div>
            <h1 class="page-title">
                <i class="fas fa-umbrella-beach"></i>
                Mis Vacaciones <?= $year ?>
            </h1>
            <p class="page-subtitle">Gestión de periodos vacacionales</p>
        </div>
        <div class="page-actions">
            <a href="/vacations/request" class="btn-industrial btn-primary-industrial">
                <i class="fas fa-plus-circle"></i>
                Solicitar Vacaciones
            </a>
        </div>
    </div>

    <?php if (isset($_SESSION['success_message'])): ?>
        <div class="alert-industrial alert-success-industrial">
            <i class="fas fa-check-circle"></i>
            <?= htmlspecialchars($_SESSION['success_message']) ?>
        </div>
        <?php unset($_SESSION['success_message']); ?>
    <?php endif; ?>

    <?php if (isset($error)): ?>
        <div class="alert-industrial alert-warning-industrial">
            <i class="fas fa-exclamation-triangle"></i>
            <?= htmlspecialchars($error) ?>
        </div>
    <?php else: ?>

    <!-- Balance Cards -->
    <div class="stats-grid">
        <!-- Total Days -->
        <div class="stat-card-industrial">
            <div class="stat-icon-wrapper" style="background: linear-gradient(135deg, rgba(59, 130, 246, 0.1), rgba(59, 130, 246, 0.05));">
                <i class="fas fa-calendar" style="color: var(--color-blue);"></i>
            </div>
            <div class="stat-details">
                <div class="stat-label">Días Totales</div>
                <div class="stat-value"><?= number_format($balance->totalDays ?? 22, 1) ?></div>
                <div class="stat-trend stat-trend-neutral">
                    <i class="fas fa-info-circle"></i>
                    Asignados este año
                </div>
            </div>
        </div>

        <!-- Used Days -->
        <div class="stat-card-industrial">
            <div class="stat-icon-wrapper" style="background: linear-gradient(135deg, rgba(234, 88, 12, 0.1), rgba(234, 88, 12, 0.05));">
                <i class="fas fa-calendar-check" style="color: var(--accent);"></i>
            </div>
            <div class="stat-details">
                <div class="stat-label">Disfrutados</div>
                <div class="stat-value"><?= number_format($balance->usedDays ?? 0, 1) ?></div>
                <div class="stat-trend stat-trend-neutral">
                    <i class="fas fa-check"></i>
                    Ya consumidos
                </div>
            </div>
        </div>

        <!-- Available Days -->
        <div class="stat-card-industrial">
            <div class="stat-icon-wrapper" style="background: linear-gradient(135deg, rgba(34, 197, 94, 0.1), rgba(34, 197, 94, 0.05));">
                <i class="fas fa-calendar-plus" style="color: var(--color-green);"></i>
            </div>
            <div class="stat-details">
                <div class="stat-label">Pendientes</div>
                <div class="stat-value" style="color: var(--color-green);"><?= number_format($balance->availableDays ?? 22, 1) ?></div>
                <div class="stat-trend stat-trend-positive">
                    <i class="fas fa-arrow-up"></i>
                    Disponibles
                </div>
            </div>
        </div>

        <!-- Pending Requests -->
        <div class="stat-card-industrial">
            <div class="stat-icon-wrapper" style="background: linear-gradient(135deg, rgba(168, 85, 247, 0.1), rgba(168, 85, 247, 0.05));">
                <i class="fas fa-clock" style="color: var(--color-purple);"></i>
            </div>
            <div class="stat-details">
                <div class="stat-label">En Espera</div>
                <div class="stat-value"><?= count($pendingApprovals ?? []) ?></div>
                <div class="stat-trend stat-trend-neutral">
                    <i class="fas fa-hourglass-half"></i>
                    Pendientes aprobación
                </div>
            </div>
        </div>
    </div>

    <?php if (!empty($pendingApprovals)): ?>
    <!-- Pending Approvals -->
    <div class="widget-card-industrial" style="margin-top: var(--space-6);">
        <div class="widget-header">
            <h3 class="widget-title">
                <i class="fas fa-hourglass-half"></i>
                Solicitudes Pendientes de Aprobación
            </h3>
            <span class="badge-industrial badge-warning-industrial">
                <?= count($pendingApprovals) ?> pendientes
            </span>
        </div>
        <div class="widget-body" style="padding: 0;">
            <div class="table-container-industrial">
                <table class="table-industrial">
                    <thead>
                        <tr>
                            <th><i class="fas fa-user"></i> Empleado</th>
                            <th><i class="fas fa-calendar-day"></i> Desde</th>
                            <th><i class="fas fa-calendar-day"></i> Hasta</th>
                            <th><i class="fas fa-clock"></i> Días</th>
                            <th><i class="fas fa-comment"></i> Motivo</th>
                            <th><i class="fas fa-cog"></i> Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($pendingApprovals as $approval): ?>
                        <tr>
                            <td>
                                <div class="table-user">
                                    <div class="table-avatar">
                                        <?= strtoupper(substr($approval['employee_name'], 0, 1)) ?>
                                    </div>
                                    <span class="table-user-name">
                                        <?= htmlspecialchars($approval['employee_name']) ?>
                                    </span>
                                </div>
                            </td>
                            <td><?= date('d/m/Y', strtotime($approval['start_date'])) ?></td>
                            <td><?= date('d/m/Y', strtotime($approval['end_date'])) ?></td>
                            <td>
                                <span class="table-badge table-badge-secondary">
                                    <?= $approval['days'] ?> días
                                </span>
                            </td>
                            <td style="max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                <?= htmlspecialchars($approval['reason'] ?? 'Sin motivo especificado') ?>
                            </td>
                            <td>
                                <div class="table-actions">
                                    <button class="btn-industrial btn-success-industrial btn-sm" 
                                            onclick="approveRequest(<?= $approval['id'] ?>)">
                                        <i class="fas fa-check"></i>
                                        Aprobar
                                    </button>
                                    <button class="btn-industrial btn-danger-industrial btn-sm" 
                                            onclick="rejectRequest(<?= $approval['id'] ?>)">
                                        <i class="fas fa-times"></i>
                                        Rechazar
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- My Vacation Requests -->
    <?php if (!empty($vacations)): ?>
    <div class="widget-card-industrial" style="margin-top: var(--space-6);">
        <div class="widget-header">
            <h3 class="widget-title">
                <i class="fas fa-list"></i>
                Mis Solicitudes de Vacaciones
            </h3>
        </div>
        <div class="widget-body" style="padding: 0;">
            <div class="table-container-industrial">
                <table class="table-industrial">
                    <thead>
                        <tr>
                            <th><i class="fas fa-calendar-day"></i> Desde</th>
                            <th><i class="fas fa-calendar-day"></i> Hasta</th>
                            <th><i class="fas fa-clock"></i> Días</th>
                            <th><i class="fas fa-comment"></i> Motivo</th>
                            <th><i class="fas fa-info-circle"></i> Estado</th>
                            <th><i class="fas fa-calendar-plus"></i> Solicitado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($vacations as $vacation): ?>
                        <tr>
                            <td><?= date('d/m/Y', strtotime($vacation['start_date'])) ?></td>
                            <td><?= date('d/m/Y', strtotime($vacation['end_date'])) ?></td>
                            <td>
                                <span class="table-badge table-badge-secondary">
                                    <?= $vacation['days'] ?> días
                                </span>
                            </td>
                            <td style="max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                <?= htmlspecialchars($vacation['reason'] ?? 'Sin motivo') ?>
                            </td>
                            <td>
                                <?php
                                $statusClasses = [
                                    'pending' => 'warning',
                                    'approved' => 'success',
                                    'rejected' => 'danger'
                                ];
                                $statusIcons = [
                                    'pending' => 'fa-hourglass-half',
                                    'approved' => 'fa-check-circle',
                                    'rejected' => 'fa-times-circle'
                                ];
                                $statusLabels = [
                                    'pending' => 'Pendiente',
                                    'approved' => 'Aprobado',
                                    'rejected' => 'Rechazado'
                                ];
                                $status = $vacation['status'] ?? 'pending';
                                ?>
                                <span class="table-badge table-badge-<?= $statusClasses[$status] ?>">
                                    <i class="fas <?= $statusIcons[$status] ?>"></i>
                                    <?= $statusLabels[$status] ?>
                                </span>
                            </td>
                            <td><?= date('d/m/Y H:i', strtotime($vacation['created_at'])) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <?php endif; ?>
</div>

<script>
function approveRequest(requestId) {
    if (confirm('¿Aprobar esta solicitud de vacaciones?')) {
        fetch(`/vacations/approve/${requestId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error al aprobar la solicitud');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al procesar la solicitud');
        });
    }
}

function rejectRequest(requestId) {
    const reason = prompt('Motivo del rechazo (opcional):');
    if (reason !== null) {
        fetch(`/vacations/reject/${requestId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ reason })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error al rechazar la solicitud');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al procesar la solicitud');
        });
    }
}
</script>

<?php require dirname(__DIR__) . '/layouts/footer.php'; ?>
