<?php require dirname(__DIR__) . '/layouts/header.php'; ?>

<div class="dashboard-container">
    <!-- Page Header -->
    <div class="page-header">
        <div>
            <h1 class="page-title">
                <i class="fas fa-receipt"></i>
                Nominak <?= $selected_year ? '/ ' . $selected_year : '' ?>
            </h1>
            <p class="page-subtitle">Kontsultatu zure nominak / Consulta tus nóminas</p>
        </div>
        <div class="page-actions">
            <?php if ($user['role'] === 'ADMIN' || $user['role'] === 'RRHH_MGR'): ?>
                <a href="/payslips/create" class="btn-industrial btn-primary-industrial">
                    <i class="fas fa-plus-circle"></i>
                    Sortu nomina / Crear nómina
                </a>
            <?php endif; ?>
        </div>
    </div>

    <?php if (isset($_SESSION['flash_success'])): ?>
        <div class="alert-industrial alert-success-industrial">
            <i class="fas fa-check-circle"></i>
            <?= htmlspecialchars($_SESSION['flash_success']) ?>
        </div>
        <?php unset($_SESSION['flash_success']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['flash_error'])): ?>
        <div class="alert-industrial alert-danger-industrial">
            <i class="fas fa-times-circle"></i>
            <?= htmlspecialchars($_SESSION['flash_error']) ?>
        </div>
        <?php unset($_SESSION['flash_error']); ?>
    <?php endif; ?>

    <!-- Stats Summary -->
    <?php if ($summary): ?>
        <div class="stats-grid">
            <div class="stat-card-industrial">
                <div class="stat-icon-wrapper" style="background: linear-gradient(135deg, rgba(59, 130, 246, 0.1), rgba(59, 130, 246, 0.05));">
                    <i class="fas fa-file-invoice" style="color: var(--color-blue);"></i>
                </div>
                <div class="stat-details">
                    <div class="stat-label">Nominak / Nóminas</div>
                    <div class="stat-value"><?= $summary['total_count'] ?? 0 ?></div>
                    <div class="stat-trend stat-trend-neutral">
                        <i class="fas fa-list"></i>
                        Guztira / Total
                    </div>
                </div>
            </div>
            <div class="stat-card-industrial">
                <div class="stat-icon-wrapper" style="background: linear-gradient(135deg, rgba(34, 197, 94, 0.1), rgba(34, 197, 94, 0.05));">
                    <i class="fas fa-euro-sign" style="color: var(--color-green);"></i>
                </div>
                <div class="stat-details">
                    <div class="stat-label">Garbia / Neto</div>
                    <div class="stat-value" style="font-size: 1.5rem;"><?= number_format($summary['ytd_net'] ?? 0, 0, ',', '.') ?> €</div>
                    <div class="stat-trend stat-trend-positive">
                        <i class="fas fa-arrow-up"></i>
                        Urteko / Anual
                    </div>
                </div>
            </div>
            <div class="stat-card-industrial">
                <div class="stat-icon-wrapper" style="background: linear-gradient(135deg, rgba(234, 88, 12, 0.1), rgba(234, 88, 12, 0.05));">
                    <i class="fas fa-calendar-alt" style="color: var(--accent);"></i>
                </div>
                <div class="stat-details">
                    <div class="stat-label">Azkena / Última</div>
                    <div class="stat-value" style="font-size: 1.25rem;">
                        <?php if ($summary['latest_payslip']): ?>
                            <?php
                            $latestDate = new DateTime($summary['latest_payslip']['period_start']);
                            $months = ['Urt', 'Ots', 'Mar', 'Api', 'Mai', 'Eka', 'Uzt', 'Abu', 'Ira', 'Urr', 'Aza', 'Abe'];
                            echo $months[(int)$latestDate->format('n') - 1] . ' ' . $latestDate->format('Y');
                            ?>
                        <?php else: ?>
                            -
                        <?php endif; ?>
                    </div>
                    <div class="stat-trend stat-trend-neutral">
                        <i class="fas fa-clock"></i>
                        Hilabete / Mes
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Filters -->
    <div class="widget-card-industrial" style="margin-top: var(--space-6);">
        <div class="widget-header">
            <h3 class="widget-title">
                <i class="fas fa-filter"></i>
                Iragazi / Filtrar
            </h3>
        </div>
        <div class="widget-body" style="padding: var(--space-4);">
            <form method="GET" action="/payslips">
                <div class="filters-grid">
                    <div class="filter-item">
                        <label for="year">Urtea / Año</label>
                        <select name="year" id="year" class="form-input-industrial">
                            <option value="">Guztiak / Todos</option>
                            <?php foreach ($years as $year): ?>
                                <option value="<?= $year ?>" <?= ($selected_year == $year) ? 'selected' : '' ?>>
                                    <?= $year ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="filter-item">
                        <label for="month">Hilabetea / Mes</label>
                        <select name="month" id="month" class="form-input-industrial">
                            <option value="">Guztiak / Todos</option>
                            <?php
                            $months = [
                                1 => 'Urtarrila / Enero', 2 => 'Otsaila / Febrero', 3 => 'Martxoa / Marzo',
                                4 => 'Apirila / Abril', 5 => 'Maiatza / Mayo', 6 => 'Ekaina / Junio',
                                7 => 'Uztaila / Julio', 8 => 'Abuztua / Agosto', 9 => 'Iraila / Septiembre',
                                10 => 'Urria / Octubre', 11 => 'Azaroa / Noviembre', 12 => 'Abendua / Diciembre'
                            ];
                            foreach ($months as $num => $name): ?>
                                <option value="<?= $num ?>" <?= ($selected_month == $num) ? 'selected' : '' ?>>
                                    <?= $name ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="filter-item" style="display: flex; align-items: flex-end; gap: var(--space-3);">
                        <button type="submit" class="btn-industrial btn-primary-industrial">
                            <i class="fas fa-filter"></i>
                            Iragazi / Filtrar
                        </button>
                        <a href="/payslips" class="btn-industrial btn-secondary-industrial">
                            <i class="fas fa-times"></i>
                            Garbitu / Limpiar
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Payslips Table -->
    <div class="widget-card-industrial" style="margin-top: var(--space-6);">
        <div class="widget-header">
            <h3 class="widget-title">
                <i class="fas fa-list"></i>
                Nomina zerrenda / Listado de nóminas
            </h3>
            <span class="badge-industrial badge-info-industrial">
                <?= count($payslips) ?> nominak
            </span>
        </div>
        <div class="widget-body" style="padding: 0;">
            <div class="table-container-industrial">
                <?php if (!empty($payslips)): ?>
                    <table class="table-industrial">
                        <thead>
                            <tr>
                                <th><i class="fas fa-calendar"></i> Aldia / Período</th>
                                <?php if ($user['role'] === 'ADMIN' || $user['role'] === 'RRHH_MGR'): ?>
                                    <th><i class="fas fa-user"></i> Langilea / Empleado</th>
                                <?php endif; ?>
                                <th><i class="fas fa-money-bill"></i> Oinarria / Base</th>
                                <th><i class="fas fa-plus"></i> Gehigarriak / Extras</th>
                                <th><i class="fas fa-minus"></i> Kenkariak / Deducciones</th>
                                <th><i class="fas fa-euro-sign"></i> Garbia / Neto</th>
                                <th><i class="fas fa-cog"></i> Ekintzak / Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($payslips as $payslip): ?>
                                <tr>
                                    <td>
                                        <strong>
                                            <?php
                                            $date = new DateTime($payslip['period_start']);
                                            $monthNames = ['Urt', 'Ots', 'Mar', 'Api', 'Mai', 'Eka', 'Uzt', 'Abu', 'Ira', 'Urr', 'Aza', 'Abe'];
                                            echo $monthNames[(int)$date->format('n') - 1] . ' ' . $date->format('Y');
                                            ?>
                                        </strong>
                                        <br>
                                        <small style="color: var(--text-tertiary);">
                                            <?= date('d/m/Y', strtotime($payslip['period_start'])) ?> - 
                                            <?= date('d/m/Y', strtotime($payslip['period_end'])) ?>
                                        </small>
                                    </td>
                                    <?php if ($user['role'] === 'ADMIN' || $user['role'] === 'RRHH_MGR'): ?>
                                        <td><?= htmlspecialchars($payslip['employee_name'] ?? 'N/A') ?></td>
                                    <?php endif; ?>
                                    <td><?= number_format($payslip['base_salary'], 2, ',', '.') ?> €</td>
                                    <td>
                                        <?php $extras = $payslip['extra_hours'] + $payslip['bonuses'] + $payslip['commissions']; ?>
                                        <?php if ($extras > 0): ?>
                                            <span style="color: var(--color-green); font-weight: 600;">
                                                +<?= number_format($extras, 2, ',', '.') ?> €
                                            </span>
                                        <?php else: ?>
                                            <span style="color: var(--text-tertiary);">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php $deductions = $payslip['taxes'] + $payslip['social_security'] + $payslip['deductions'] + $payslip['other_deductions']; ?>
                                        <span style="color: var(--color-red); font-weight: 600;">
                                            -<?= number_format($deductions, 2, ',', '.') ?> €
                                        </span>
                                    </td>
                                    <td>
                                        <strong style="color: var(--primary); font-size: 1.1rem;">
                                            <?= number_format($payslip['net_salary'], 2, ',', '.') ?> €
                                        </strong>
                                    </td>
                                    <td>
                                        <div class="table-actions">
                                            <a href="/payslips/<?= $payslip['id'] ?>" class="btn-industrial btn-sm btn-secondary-industrial" title="Ikusi / Ver">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="/api/payroll/<?= $payslip['id'] ?>/download" class="btn-industrial btn-sm btn-primary-industrial" title="Deskargatu / Descargar">
                                                <i class="fas fa-download"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <div class="table-empty">
                        <i class="fas fa-inbox" style="font-size: 3rem; color: var(--text-tertiary); margin-bottom: var(--space-4);"></i>
                        <p>Ez dago nominarik / No hay nóminas disponibles</p>
                        <?php if ($selected_year || $selected_month): ?>
                            <a href="/payslips" class="btn-industrial btn-secondary-industrial" style="margin-top: var(--space-4);">
                                <i class="fas fa-times"></i>
                                Iragazkiak garbitu / Limpiar filtros
                            </a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php require dirname(__DIR__) . '/layouts/footer.php'; ?>
