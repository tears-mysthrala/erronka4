<?php require dirname(__DIR__) . '/layouts/header.php'; ?>

<div class="page-header">
    <div>
        <h1 class="page-title">
            <i class="fas fa-receipt"></i>
            Nominak / Nóminas
        </h1>
        <p class="page-subtitle">
            Zure nominak kontsultatu / Consulta tus nóminas
        </p>
    </div>
    <div class="page-actions">
        <?php if ($user['role'] === 'ADMIN' || $user['role'] === 'RRHH_MGR'): ?>
            <a href="/payslips/create" class="btn-industrial btn-primary-industrial">
                <i class="fas fa-plus"></i>
                Sortu nomina / Crear nómina
            </a>
        <?php endif; ?>
    </div>
</div>

<?php if ($summary && $summary['latest_payslip']): ?>
<!-- Summary Card with Gradient -->
<div class="widget-card-industrial mb-6" style="overflow: hidden;">
    <div class="widget-body p-0">
        <div style="background: linear-gradient(135deg, #667EEA 0%, #764BA2 100%); padding: 40px; border-radius: 20px; color: white;">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 24px;">
                <div>
                    <div style="font-size: 0.875rem; opacity: 0.9; margin-bottom: 8px;">
                        Azken nomina / Última nómina
                    </div>
                    <div style="font-size: 2rem; font-weight: 800;">
                        <?php
                        $latestDate = new DateTime($summary['latest_payslip']['period_start']);
                        $months_eu = ['Urtarrila', 'Otsaila', 'Martxoa', 'Apirila', 'Maiatza', 'Ekaina', 'Uztaila', 'Abuztua', 'Iraila', 'Urria', 'Azaroa', 'Abendua'];
                        $months_es = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
                        $month = (int)$latestDate->format('n') - 1;
                        echo $months_eu[$month] . ' / ' . $months_es[$month] . ' ' . $latestDate->format('Y');
                        ?>
                    </div>
                </div>
                <div style="font-size: 0.875rem; opacity: 0.9;">
                    <i class="fas fa-file-invoice"></i>
                    <?= $summary['total_count'] ?> nómina(s)
                </div>
            </div>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 24px;">
                <div style="background: rgba(255, 255, 255, 0.15); backdrop-filter: blur(10px); padding: 24px; border-radius: 16px;">
                    <div style="font-size: 0.875rem; opacity: 0.9; margin-bottom: 8px;">
                        Soldata garbia / Salario neto
                    </div>
                    <div style="font-size: 2.5rem; font-weight: 800; line-height: 1;">
                        <?= number_format($summary['latest_payslip']['net_salary'], 2, ',', '.') ?> €
                    </div>
                </div>
                <div style="background: rgba(255, 255, 255, 0.15); backdrop-filter: blur(10px); padding: 24px; border-radius: 16px;">
                    <div style="font-size: 0.875rem; opacity: 0.9; margin-bottom: 8px;">
                        Urteko guztira / Total anual
                    </div>
                    <div style="font-size: 2.5rem; font-weight: 800; line-height: 1;">
                        <?= number_format($summary['ytd_net'], 2, ',', '.') ?> €
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Filters -->
<div class="widget-card-industrial mb-6">
    <div class="widget-body">
        <form method="GET" action="/payslips" class="filter-form">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: var(--space-4); align-items: end;">
                <div class="form-group">
                    <label for="year" class="form-label">Urtea / Año</label>
                    <select name="year" id="year" class="form-control">
                        <option value="">Guztiak / Todos</option>
                        <?php foreach ($years as $year): ?>
                            <option value="<?= $year ?>" <?= ($selected_year == $year) ? 'selected' : '' ?>>
                                <?= $year ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="month" class="form-label">Hilabetea / Mes</label>
                    <select name="month" id="month" class="form-control">
                        <option value="">Guztiak / Todos</option>
                        <?php
                        $months = [
                            1 => 'Urtarrila / Enero',
                            2 => 'Otsaila / Febrero',
                            3 => 'Martxoa / Marzo',
                            4 => 'Apirila / Abril',
                            5 => 'Maiatza / Mayo',
                            6 => 'Ekaina / Junio',
                            7 => 'Uztaila / Julio',
                            8 => 'Abuztua / Agosto',
                            9 => 'Iraila / Septiembre',
                            10 => 'Urria / Octubre',
                            11 => 'Azaroa / Noviembre',
                            12 => 'Abendua / Diciembre'
                        ];
                        foreach ($months as $num => $name): ?>
                            <option value="<?= $num ?>" <?= ($selected_month == $num) ? 'selected' : '' ?>>
                                <?= $name ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div style="display: flex; gap: var(--space-3);">
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

<!-- Payslips List -->
<div class="widget-card-industrial">
    <div class="widget-header">
        <h3 class="widget-title">
            <i class="fas fa-list"></i>
            Nomina zerrenda / Listado de nóminas
        </h3>
    </div>
    <div class="widget-body">
        <?php if (!empty($payslips)): ?>
            <div class="table-responsive">
                <table class="table-industrial">
                    <thead>
                        <tr>
                            <th>Aldia / Período</th>
                            <?php if ($user['role'] === 'ADMIN' || $user['role'] === 'RRHH_MGR'): ?>
                                <th>Langilea / Empleado</th>
                            <?php endif; ?>
                            <th>Oinarrizko soldata / Salario base</th>
                            <th>Gehiketa / Extra</th>
                            <th>Kenkariak / Deducciones</th>
                            <th>Garbia / Neto</th>
                            <th>Ekintzak / Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($payslips as $payslip): ?>
                            <tr>
                                <td>
                                    <strong>
                                        <?php
                                        $date = new DateTime($payslip['period_start']);
                                        $month = (int)$date->format('n') - 1;
                                        echo $months_eu[$month] . ' ' . $date->format('Y');
                                        ?>
                                    </strong>
                                    <br>
                                    <small class="text-muted">
                                        <?= date('d/m/Y', strtotime($payslip['period_start'])) ?> - 
                                        <?= date('d/m/Y', strtotime($payslip['period_end'])) ?>
                                    </small>
                                </td>
                                <?php if ($user['role'] === 'ADMIN' || $user['role'] === 'RRHH_MGR'): ?>
                                    <td><?= htmlspecialchars($payslip['employee_name'] ?? 'N/A') ?></td>
                                <?php endif; ?>
                                <td><?= number_format($payslip['base_salary'], 2, ',', '.') ?> €</td>
                                <td>
                                    <?php
                                    $extras = $payslip['extra_hours'] + $payslip['bonuses'] + $payslip['commissions'];
                                    if ($extras > 0):
                                    ?>
                                        <span style="color: var(--success); font-weight: 600;">
                                            +<?= number_format($extras, 2, ',', '.') ?> €
                                        </span>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php
                                    $totalDeductions = $payslip['taxes'] + $payslip['social_security'] + $payslip['deductions'] + $payslip['other_deductions'];
                                    ?>
                                    <span style="color: var(--danger); font-weight: 600;">
                                        -<?= number_format($totalDeductions, 2, ',', '.') ?> €
                                    </span>
                                </td>
                                <td>
                                    <strong style="font-size: 1.1rem; color: var(--primary);">
                                        <?= number_format($payslip['net_salary'], 2, ',', '.') ?> €
                                    </strong>
                                </td>
                                <td>
                                    <div style="display: flex; gap: var(--space-2);">
                                        <a href="/payslips/<?= $payslip['id'] ?>" class="btn-ghost-industrial" title="Ikusi xehetasunak / Ver detalles">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <?php if ($payslip['pdf_path']): ?>
                                            <a href="/api/payroll/<?= $payslip['id'] ?>/download" class="btn-ghost-industrial" title="Deskargatu PDF">
                                                <i class="fas fa-download"></i>
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="empty-state">
                <i class="fas fa-inbox"></i>
                <p>Ez dago nominarik / No hay nóminas disponibles</p>
                <?php if ($selected_year || $selected_month): ?>
                    <a href="/payslips" class="btn-industrial btn-secondary-industrial mt-4">
                        <i class="fas fa-times"></i>
                        Iragazkiak garbitu / Limpiar filtros
                    </a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require dirname(__DIR__) . '/layouts/footer.php'; ?>
