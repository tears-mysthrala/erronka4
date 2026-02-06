<?php require dirname(__DIR__) . '/layouts/header.php'; ?>

<div class="dashboard-container">
    <!-- Page Header -->
    <div class="page-header">
        <div>
            <h1 class="page-title">
                <i class="fas fa-umbrella-beach"></i>
                Vacaciones <?= $year ?>
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

    <!-- Summary Cards -->
    <div class="stats-grid">
        <?php if (!empty($isAdmin)): ?>
            <div class="stat-card-industrial">
                <div class="stat-icon-wrapper" style="background: linear-gradient(135deg, rgba(59, 130, 246, 0.1), rgba(59, 130, 246, 0.05));">
                    <i class="fas fa-users" style="color: var(--color-blue);"></i>
                </div>
                <div class="stat-details">
                    <div class="stat-label">Empleados</div>
                    <div class="stat-value"><?= number_format((float)($companySummary['employee_count'] ?? 0), 0) ?></div>
                    <div class="stat-trend stat-trend-neutral">
                        <i class="fas fa-building"></i>
                        Total compañía
                    </div>
                </div>
            </div>
            <div class="stat-card-industrial">
                <div class="stat-icon-wrapper" style="background: linear-gradient(135deg, rgba(234, 88, 12, 0.1), rgba(234, 88, 12, 0.05));">
                    <i class="fas fa-calendar-check" style="color: var(--accent);"></i>
                </div>
                <div class="stat-details">
                    <div class="stat-label">Días Usados</div>
                    <div class="stat-value"><?= number_format((float)($companySummary['used_days'] ?? 0), 1) ?></div>
                    <div class="stat-trend stat-trend-neutral">
                        <i class="fas fa-check"></i>
                        Consumidos
                    </div>
                </div>
            </div>
            <div class="stat-card-industrial">
                <div class="stat-icon-wrapper" style="background: linear-gradient(135deg, rgba(34, 197, 94, 0.1), rgba(34, 197, 94, 0.05));">
                    <i class="fas fa-calendar-plus" style="color: var(--color-green);"></i>
                </div>
                <div class="stat-details">
                    <div class="stat-label">Días Pendientes</div>
                    <div class="stat-value"><?= number_format((float)($companySummary['pending_days'] ?? 0), 1) ?></div>
                    <div class="stat-trend stat-trend-positive">
                        <i class="fas fa-hourglass-half"></i>
                        En espera
                    </div>
                </div>
            </div>
        <?php else: ?>
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
            <div class="stat-card-industrial">
                <div class="stat-icon-wrapper" style="background: linear-gradient(135deg, rgba(34, 197, 94, 0.1), rgba(34, 197, 94, 0.05));">
                    <i class="fas fa-calendar-plus" style="color: var(--color-green);"></i>
                </div>
                <div class="stat-details">
                    <div class="stat-label">Disponibles</div>
                    <div class="stat-value" style="color: var(--color-green);"><?= number_format($balance->availableDays ?? 22, 1) ?></div>
                    <div class="stat-trend stat-trend-positive">
                        <i class="fas fa-arrow-up"></i>
                        Libres
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <?php if (!empty($isAdmin) || !empty($isDepartmentHead)): ?>
        <?php $pendingCount = is_array($pendingApprovals ?? null) ? count($pendingApprovals) : 0; ?>
        <!-- Pending Approvals -->
        <div class="widget-card-industrial" style="margin-top: var(--space-6);">
            <div class="widget-header">
                <h3 class="widget-title">
                    <i class="fas fa-hourglass-half"></i>
                    Solicitudes Pendientes de Aprobación
                </h3>
                <span class="badge-industrial badge-warning-industrial">
                    <?= $pendingCount ?> pendientes
                </span>
            </div>
            <div class="widget-body" style="padding: var(--space-4);">
                <div class="filters-grid">
                    <div class="filter-item">
                        <label for="pending-name-filter">Nombre</label>
                           <input id="pending-name-filter" type="text" class="form-input-industrial" placeholder="Nombre o apellidos"
                               value="<?= htmlspecialchars($pendingFilters['name'] ?? '') ?>">
                    </div>
                    <div class="filter-item">
                        <label for="pending-email-filter">Email</label>
                           <input id="pending-email-filter" type="text" class="form-input-industrial" placeholder="correo@empresa.com"
                               value="<?= htmlspecialchars($pendingFilters['email'] ?? '') ?>">
                    </div>
                </div>
            </div>
            <div class="widget-body" style="padding: 0;">
                <div class="table-container-industrial">
                    <table class="table-industrial">
                        <thead>
                            <tr>
                                <th><i class="fas fa-user"></i> Empleado</th>
                                <th><i class="fas fa-envelope"></i> Email</th>
                                <th><i class="fas fa-sitemap"></i> Departamento</th>
                                <th><i class="fas fa-calendar-day"></i> Desde</th>
                                <th><i class="fas fa-calendar-day"></i> Hasta</th>
                                <th><i class="fas fa-clock"></i> Días</th>
                                <th><i class="fas fa-comment"></i> Motivo</th>
                                <th><i class="fas fa-cog"></i> Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="pending-approvals-body">
                            <?php if (!empty($pendingApprovals)): ?>
                                <?php foreach ($pendingApprovals as $approval): ?>
                                    <tr>
                                        <td>
                                            <div class="table-user">
                                                <div class="table-avatar">
                                                    <?= strtoupper(substr(($approval->employeeFirstName ?? 'U'), 0, 1)) ?>
                                                </div>
                                                <span class="table-user-name">
                                                    <?= htmlspecialchars(trim(($approval->employeeFirstName ?? '') . ' ' . ($approval->employeeLastName ?? ''))) ?>
                                                </span>
                                            </div>
                                        </td>
                                        <td><?= htmlspecialchars($approval->employeeEmail ?? '-') ?></td>
                                        <td><?= htmlspecialchars($approval->employeeDepartment ?? '-') ?></td>
                                        <td><?= $approval->startDate ? date('d/m/Y', strtotime($approval->startDate)) : '-' ?></td>
                                        <td><?= $approval->endDate ? date('d/m/Y', strtotime($approval->endDate)) : '-' ?></td>
                                        <td>
                                            <span class="table-badge table-badge-secondary">
                                                <?= number_format($approval->totalDays ?? 0, 1) ?> días
                                            </span>
                                        </td>
                                        <td style="max-width: 220px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                            <?= htmlspecialchars($approval->notes ?? 'Sin motivo') ?>
                                        </td>
                                        <td>
                                            <div class="table-actions">
                                                <button class="btn-industrial btn-success-industrial btn-sm" onclick="approveRequest('<?= $approval->id ?>')">
                                                    <i class="fas fa-check"></i>
                                                    Aprobar
                                                </button>
                                                <button class="btn-industrial btn-danger-industrial btn-sm" onclick="rejectRequest('<?= $approval->id ?>')">
                                                    <i class="fas fa-times"></i>
                                                    Rechazar
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="8" class="table-empty">No hay solicitudes pendientes.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- History Approvals -->
        <div class="widget-card-industrial" style="margin-top: var(--space-6);">
            <div class="widget-header">
                <h3 class="widget-title">
                    <i class="fas fa-list"></i>
                    Historial de Solicitudes
                </h3>
            </div>
            <div class="widget-body" style="padding: var(--space-4);">
                <div class="filters-grid">
                    <div class="filter-item">
                        <label for="history-name-filter">Nombre</label>
                           <input id="history-name-filter" type="text" class="form-input-industrial" placeholder="Nombre o apellidos"
                               value="<?= htmlspecialchars($historyFilters['name'] ?? '') ?>">
                    </div>
                    <div class="filter-item">
                        <label for="history-email-filter">Email</label>
                           <input id="history-email-filter" type="text" class="form-input-industrial" placeholder="correo@empresa.com"
                               value="<?= htmlspecialchars($historyFilters['email'] ?? '') ?>">
                    </div>
                </div>
            </div>
            <div class="widget-body" style="padding: 0;">
                <div class="table-container-industrial">
                    <table class="table-industrial">
                        <thead>
                            <tr>
                                <th><i class="fas fa-user"></i> Empleado</th>
                                <th><i class="fas fa-envelope"></i> Email</th>
                                <th><i class="fas fa-sitemap"></i> Departamento</th>
                                <th><i class="fas fa-calendar-day"></i> Desde</th>
                                <th><i class="fas fa-calendar-day"></i> Hasta</th>
                                <th><i class="fas fa-clock"></i> Días</th>
                                <th><i class="fas fa-info-circle"></i> Estado</th>
                                <th><i class="fas fa-calendar-plus"></i> Solicitado</th>
                            </tr>
                        </thead>
                        <tbody id="history-approvals-body">
                            <?php if (!empty($historyApprovals)): ?>
                                <?php foreach ($historyApprovals as $req): ?>
                                    <tr>
                                        <td><?= htmlspecialchars(trim(($req->employeeFirstName ?? '') . ' ' . ($req->employeeLastName ?? ''))) ?></td>
                                        <td><?= htmlspecialchars($req->employeeEmail ?? '-') ?></td>
                                        <td><?= htmlspecialchars($req->employeeDepartment ?? '-') ?></td>
                                        <td><?= $req->startDate ? date('d/m/Y', strtotime($req->startDate)) : '-' ?></td>
                                        <td><?= $req->endDate ? date('d/m/Y', strtotime($req->endDate)) : '-' ?></td>
                                        <td>
                                            <span class="table-badge table-badge-secondary">
                                                <?= number_format($req->totalDays ?? 0, 1) ?> días
                                            </span>
                                        </td>
                                        <td>
                                            <?php
                                            $statusMap = [
                                                'PENDING' => ['warning', 'Pendiente', 'fa-hourglass-half'],
                                                'MANAGER_APPROVED' => ['primary', 'Aprobado jefe', 'fa-user-check'],
                                                'APPROVED' => ['success', 'Aprobado', 'fa-check-circle'],
                                                'REJECTED' => ['danger', 'Rechazado', 'fa-times-circle'],
                                                'CANCELLED' => ['secondary', 'Cancelado', 'fa-ban']
                                            ];
                                            $statusKey = $req->status ?? 'PENDING';
                                            $statusMeta = $statusMap[$statusKey] ?? ['secondary', $statusKey, 'fa-info-circle'];
                                            ?>
                                            <span class="table-badge table-badge-<?= $statusMeta[0] ?>">
                                                <i class="fas <?= $statusMeta[2] ?>"></i>
                                                <?= $statusMeta[1] ?>
                                            </span>
                                        </td>
                                        <td><?= $req->requestDate ? date('d/m/Y', strtotime($req->requestDate)) : '-' ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="8" class="table-empty">No hay solicitudes registradas.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- My Vacation Requests -->
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
                        <?php if (!empty($requests)): ?>
                            <?php foreach ($requests as $vacation): ?>
                                <?php
                                $statusMap = [
                                    'PENDING' => ['warning', 'Pendiente', 'fa-hourglass-half'],
                                    'MANAGER_APPROVED' => ['primary', 'Aprobado jefe', 'fa-user-check'],
                                    'APPROVED' => ['success', 'Aprobado', 'fa-check-circle'],
                                    'REJECTED' => ['danger', 'Rechazado', 'fa-times-circle'],
                                    'CANCELLED' => ['secondary', 'Cancelado', 'fa-ban']
                                ];
                                $statusKey = $vacation->status ?? 'PENDING';
                                $statusMeta = $statusMap[$statusKey] ?? ['secondary', $statusKey, 'fa-info-circle'];
                                ?>
                                <tr>
                                    <td><?= $vacation->startDate ? date('d/m/Y', strtotime($vacation->startDate)) : '-' ?></td>
                                    <td><?= $vacation->endDate ? date('d/m/Y', strtotime($vacation->endDate)) : '-' ?></td>
                                    <td>
                                        <span class="table-badge table-badge-secondary">
                                            <?= number_format($vacation->totalDays ?? 0, 1) ?> días
                                        </span>
                                    </td>
                                    <td style="max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                        <?= htmlspecialchars($vacation->notes ?? 'Sin motivo') ?>
                                    </td>
                                    <td>
                                        <span class="table-badge table-badge-<?= $statusMeta[0] ?>">
                                            <i class="fas <?= $statusMeta[2] ?>"></i>
                                            <?= $statusMeta[1] ?>
                                        </span>
                                    </td>
                                    <td><?= $vacation->requestDate ? date('d/m/Y', strtotime($vacation->requestDate)) : '-' ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="table-empty">No hay solicitudes registradas.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <?php endif; ?>
</div>

<script>
const pendingNameInput = document.getElementById('pending-name-filter');
const pendingEmailInput = document.getElementById('pending-email-filter');
const historyNameInput = document.getElementById('history-name-filter');
const historyEmailInput = document.getElementById('history-email-filter');

const escapeHtml = (value) => {
    const map = {
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;'
    };
    return String(value ?? '').replace(/[&<>"']/g, (m) => map[m]);
};

const formatDate = (value) => {
    if (!value) return '-';
    const date = new Date(value);
    if (Number.isNaN(date.getTime())) return '-';
    return date.toLocaleDateString('es-ES');
};

const statusMap = {
    PENDING: { label: 'Pendiente', cls: 'warning', icon: 'fa-hourglass-half' },
    MANAGER_APPROVED: { label: 'Aprobado jefe', cls: 'primary', icon: 'fa-user-check' },
    APPROVED: { label: 'Aprobado', cls: 'success', icon: 'fa-check-circle' },
    REJECTED: { label: 'Rechazado', cls: 'danger', icon: 'fa-times-circle' },
    CANCELLED: { label: 'Cancelado', cls: 'secondary', icon: 'fa-ban' }
};

const renderPendingRows = (items) => {
    if (!Array.isArray(items) || items.length === 0) {
        return '<tr><td colspan="8" class="table-empty">No hay solicitudes pendientes.</td></tr>';
    }
    return items.map((item) => {
        const name = `${item.employee_first_name ?? ''} ${item.employee_last_name ?? ''}`.trim() || '-';
        const initial = (item.employee_first_name ?? 'U').substring(0, 1).toUpperCase();
        return `
            <tr>
                <td>
                    <div class="table-user">
                        <div class="table-avatar">${escapeHtml(initial)}</div>
                        <span class="table-user-name">${escapeHtml(name)}</span>
                    </div>
                </td>
                <td>${escapeHtml(item.employee_email ?? '-')}</td>
                <td>${escapeHtml(item.employee_department ?? '-')}</td>
                <td>${formatDate(item.start_date)}</td>
                <td>${formatDate(item.end_date)}</td>
                <td><span class="table-badge table-badge-secondary">${Number(item.total_days ?? 0).toFixed(1)} días</span></td>
                <td style="max-width: 220px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">${escapeHtml(item.notes ?? 'Sin motivo')}</td>
                <td>
                    <div class="table-actions">
                        <button class="btn-industrial btn-success-industrial btn-sm" onclick="approveRequest('${escapeHtml(item.id)}')">
                            <i class="fas fa-check"></i>
                            Aprobar
                        </button>
                        <button class="btn-industrial btn-danger-industrial btn-sm" onclick="rejectRequest('${escapeHtml(item.id)}')">
                            <i class="fas fa-times"></i>
                            Rechazar
                        </button>
                    </div>
                </td>
            </tr>
        `;
    }).join('');
};

const renderHistoryRows = (items) => {
    if (!Array.isArray(items) || items.length === 0) {
        return '<tr><td colspan="8" class="table-empty">No hay solicitudes registradas.</td></tr>';
    }
    return items.map((item) => {
        const name = `${item.employee_first_name ?? ''} ${item.employee_last_name ?? ''}`.trim() || '-';
        const status = statusMap[item.status] || { label: item.status, cls: 'secondary', icon: 'fa-info-circle' };
        return `
            <tr>
                <td>${escapeHtml(name)}</td>
                <td>${escapeHtml(item.employee_email ?? '-')}</td>
                <td>${escapeHtml(item.employee_department ?? '-')}</td>
                <td>${formatDate(item.start_date)}</td>
                <td>${formatDate(item.end_date)}</td>
                <td><span class="table-badge table-badge-secondary">${Number(item.total_days ?? 0).toFixed(1)} días</span></td>
                <td>
                    <span class="table-badge table-badge-${status.cls}">
                        <i class="fas ${status.icon}"></i>
                        ${status.label}
                    </span>
                </td>
                <td>${formatDate(item.request_date)}</td>
            </tr>
        `;
    }).join('');
};

const fetchPendingApprovals = async () => {
    if (!pendingNameInput && !pendingEmailInput) return;
    const params = new URLSearchParams({
        pending_name: pendingNameInput?.value ?? '',
        pending_email: pendingEmailInput?.value ?? ''
    });
    const response = await fetch(`/vacations/pending_ajax?${params.toString()}`);
    const data = await response.json();
    if (!data.success) return;
    const tbody = document.getElementById('pending-approvals-body');
    if (tbody) {
        tbody.innerHTML = renderPendingRows(data.data || []);
    }
};

const fetchHistoryApprovals = async () => {
    if (!historyNameInput && !historyEmailInput) return;
    const params = new URLSearchParams({
        history_name: historyNameInput?.value ?? '',
        history_email: historyEmailInput?.value ?? ''
    });
    const response = await fetch(`/vacations/history_ajax?${params.toString()}`);
    const data = await response.json();
    if (!data.success) return;
    const tbody = document.getElementById('history-approvals-body');
    if (tbody) {
        tbody.innerHTML = renderHistoryRows(data.data || []);
    }
};

let pendingTimer;
let historyTimer;

if (pendingNameInput) {
    pendingNameInput.addEventListener('input', () => {
        clearTimeout(pendingTimer);
        pendingTimer = setTimeout(fetchPendingApprovals, 300);
    });
}

if (pendingEmailInput) {
    pendingEmailInput.addEventListener('input', () => {
        clearTimeout(pendingTimer);
        pendingTimer = setTimeout(fetchPendingApprovals, 300);
    });
}

if (historyNameInput) {
    historyNameInput.addEventListener('input', () => {
        clearTimeout(historyTimer);
        historyTimer = setTimeout(fetchHistoryApprovals, 300);
    });
}

if (historyEmailInput) {
    historyEmailInput.addEventListener('input', () => {
        clearTimeout(historyTimer);
        historyTimer = setTimeout(fetchHistoryApprovals, 300);
    });
}

async function approveRequest(requestId) {
    if (!confirm('¿Aprobar esta solicitud de vacaciones?')) return;
    const response = await fetch(`/vacations/approve/${requestId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        }
    });
    const data = await response.json().catch(() => ({ success: false }));
    if (data.success) {
        await fetchPendingApprovals();
        await fetchHistoryApprovals();
    } else {
        alert(data.message || 'Error al aprobar la solicitud');
    }
}

async function rejectRequest(requestId) {
    const reason = prompt('Motivo del rechazo (opcional):');
    if (reason === null) return;
    const response = await fetch(`/vacations/reject/${requestId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify({ reason })
    });
    const data = await response.json().catch(() => ({ success: false }));
    if (data.success) {
        await fetchPendingApprovals();
        await fetchHistoryApprovals();
    } else {
        alert(data.message || 'Error al rechazar la solicitud');
    }
}
</script>

<?php require dirname(__DIR__) . '/layouts/footer.php'; ?>
