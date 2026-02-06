<?php require dirname(__DIR__) . '/layouts/header.php'; ?>

<div class="page-header">
    <div>
        <h1 class="page-title">
            <i class="fas fa-receipt"></i>
            Nomina xehetasunak / Detalle de nómina
        </h1>
        <p class="page-subtitle">
            <?php
            $date = new DateTime($payslip['period_start']);
            $months_eu = ['Urtarrila', 'Otsaila', 'Martxoa', 'Apirila', 'Maiatza', 'Ekaina', 'Uztaila', 'Abuztua', 'Iraila', 'Urria', 'Azaroa', 'Abendua'];
            $months_es = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
            $month = (int)$date->format('n') - 1;
            echo $months_eu[$month] . ' / ' . $months_es[$month] . ' ' . $date->format('Y');
            ?>
        </p>
    </div>
    <div class="page-actions">
        <a href="/payslips" class="btn-industrial btn-secondary-industrial">
            <i class="fas fa-arrow-left"></i>
            Atzera / Volver
        </a>
        <?php if ($payslip['pdf_path']): ?>
            <a href="/api/payroll/<?= $payslip['id'] ?>/download" class="btn-industrial btn-primary-industrial">
                <i class="fas fa-download"></i>
                Deskargatu PDF
            </a>
        <?php endif; ?>
    </div>
</div>

<!-- Header Card with Gradient -->
<div class="widget-card-industrial mb-6" style="overflow: hidden;">
    <div class="widget-body p-0">
        <div style="background: linear-gradient(135deg, #667EEA 0%, #764BA2 100%); padding: 40px; border-radius: 20px; color: white;">
            <div style="font-size: 2rem; font-weight: 800; margin-bottom: 16px;">
                <?= $months_eu[$month] . ' / ' . $months_es[$month] ?> <?= $date->format('Y') ?>
            </div>
            <div style="font-size: 0.875rem; opacity: 0.9; margin-bottom: 8px;">
                Soldata garbia / Salario neto
            </div>
            <div style="font-size: 3.5rem; font-weight: 800; line-height: 1;">
                <?= number_format($payslip['net_salary'], 2, ',', '.') ?> €
            </div>
            <?php if ($user['role'] === 'ADMIN' || $user['role'] === 'RRHH_MGR'): ?>
                <div style="margin-top: 16px; font-size: 0.875rem; opacity: 0.9;">
                    <i class="fas fa-user"></i>
                    <?= htmlspecialchars($payslip['employee_name']) ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Period Information -->
<div class="widget-card-industrial mb-6">
    <div class="widget-header">
        <h3 class="widget-title">
            <i class="fas fa-calendar-alt"></i>
            Aldiaren informazioa / Información del período
        </h3>
    </div>
    <div class="widget-body">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: var(--space-6);">
            <div>
                <div style="font-size: var(--text-sm); color: var(--text-tertiary); margin-bottom: var(--space-2);">
                    Hasiera data / Fecha inicio
                </div>
                <div style="font-size: var(--text-lg); font-weight: 600;">
                    <?= date('d/m/Y', strtotime($payslip['period_start'])) ?>
                </div>
            </div>
            <div>
                <div style="font-size: var(--text-sm); color: var(--text-tertiary); margin-bottom: var(--space-2);">
                    Amaiera data / Fecha fin
                </div>
                <div style="font-size: var(--text-lg); font-weight: 600;">
                    <?= date('d/m/Y', strtotime($payslip['period_end'])) ?>
                </div>
            </div>
            <div>
                <div style="font-size: var(--text-sm); color: var(--text-tertiary); margin-bottom: var(--space-2);">
                    Sortze data / Fecha creación
                </div>
                <div style="font-size: var(--text-lg); font-weight: 600;">
                    <?= date('d/m/Y H:i', strtotime($payslip['created_at'])) ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Salary Breakdown -->
<div class="widget-card-industrial mb-6">
    <div class="widget-header">
        <h3 class="widget-title">
            <i class="fas fa-calculator"></i>
            Soldata xehetasunak / Desglose del salario
        </h3>
    </div>
    <div class="widget-body">
        <div style="display: grid; gap: 16px;">
            <!-- Base Salary -->
            <div class="widget-card-industrial" style="background: var(--bg-elevated); border: 2px solid var(--primary-light);">
                <div style="padding: 20px; display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <i class="fas fa-money-bill-wave" style="font-size: 1.5rem; color: var(--primary);"></i>
                        <span style="margin-left: 12px; font-weight: 600;">Oinarrizko soldata / Salario base</span>
                    </div>
                    <div style="font-size: 1.5rem; font-weight: 700;">
                        <?= number_format($payslip['base_salary'], 2, ',', '.') ?> €
                    </div>
                </div>
            </div>

            <!-- Extra Hours -->
            <?php if ($payslip['extra_hours'] > 0): ?>
            <div class="widget-card-industrial" style="background: var(--bg-elevated);">
                <div style="padding: 20px; display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <i class="fas fa-clock" style="font-size: 1.5rem; color: var(--accent);"></i>
                        <span style="margin-left: 12px; font-weight: 600;">Ordubete extra / Horas extra</span>
                    </div>
                    <div style="font-size: 1.5rem; font-weight: 700; color: var(--success);">
                        +<?= number_format($payslip['extra_hours'], 2, ',', '.') ?> €
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- Bonuses -->
            <?php if ($payslip['bonuses'] > 0): ?>
            <div class="widget-card-industrial" style="background: var(--bg-elevated);">
                <div style="padding: 20px; display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <i class="fas fa-gift" style="font-size: 1.5rem; color: var(--success);"></i>
                        <span style="margin-left: 12px; font-weight: 600;">Bonuak / Bonificaciones</span>
                    </div>
                    <div style="font-size: 1.5rem; font-weight: 700; color: var(--success);">
                        +<?= number_format($payslip['bonuses'], 2, ',', '.') ?> €
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- Commissions -->
            <?php if ($payslip['commissions'] > 0): ?>
            <div class="widget-card-industrial" style="background: var(--bg-elevated);">
                <div style="padding: 20px; display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <i class="fas fa-percentage" style="font-size: 1.5rem; color: var(--success);"></i>
                        <span style="margin-left: 12px; font-weight: 600;">Komisioiak / Comisiones</span>
                    </div>
                    <div style="font-size: 1.5rem; font-weight: 700; color: var(--success);">
                        +<?= number_format($payslip['commissions'], 2, ',', '.') ?> €
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- Gross Salary -->
            <div class="widget-card-industrial" style="background: var(--bg-elevated); border: 2px solid var(--accent-light);">
                <div style="padding: 20px; display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <i class="fas fa-coins" style="font-size: 1.5rem; color: var(--accent);"></i>
                        <span style="margin-left: 12px; font-weight: 700; font-size: 1.1rem;">Soldata gordina / Salario bruto</span>
                    </div>
                    <div style="font-size: 1.75rem; font-weight: 800; color: var(--accent);">
                        <?= number_format($payslip['gross_salary'], 2, ',', '.') ?> €
                    </div>
                </div>
            </div>

            <!-- Deductions Header -->
            <div style="margin-top: 16px; padding-bottom: 8px; border-bottom: 2px solid var(--border-color);">
                <h4 style="font-size: 1.1rem; font-weight: 700; color: var(--text-primary);">
                    <i class="fas fa-minus-circle"></i>
                    Kenkariak / Deducciones
                </h4>
            </div>

            <!-- Social Security -->
            <div class="widget-card-industrial" style="background: var(--bg-elevated);">
                <div style="padding: 20px; display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <i class="fas fa-shield-alt" style="font-size: 1.5rem; color: var(--info);"></i>
                        <span style="margin-left: 12px; font-weight: 600;">Gizarte Segurantza / Seguridad Social</span>
                    </div>
                    <div style="font-size: 1.5rem; font-weight: 700; color: var(--danger);">
                        -<?= number_format($payslip['social_security'], 2, ',', '.') ?> €
                    </div>
                </div>
            </div>

            <!-- Taxes (IRPF) -->
            <div class="widget-card-industrial" style="background: var(--bg-elevated);">
                <div style="padding: 20px; display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <i class="fas fa-landmark" style="font-size: 1.5rem; color: var(--warning);"></i>
                        <span style="margin-left: 12px; font-weight: 600;">IRPF (Zergak / Impuestos)</span>
                    </div>
                    <div style="font-size: 1.5rem; font-weight: 700; color: var(--danger);">
                        -<?= number_format($payslip['taxes'], 2, ',', '.') ?> €
                    </div>
                </div>
            </div>

            <!-- Other Deductions -->
            <?php if ($payslip['deductions'] > 0): ?>
            <div class="widget-card-industrial" style="background: var(--bg-elevated);">
                <div style="padding: 20px; display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <i class="fas fa-minus-square" style="font-size: 1.5rem; color: var(--danger);"></i>
                        <span style="margin-left: 12px; font-weight: 600;">Kenkariak / Deducciones</span>
                    </div>
                    <div style="font-size: 1.5rem; font-weight: 700; color: var(--danger);">
                        -<?= number_format($payslip['deductions'], 2, ',', '.') ?> €
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- Other Deductions -->
            <?php if ($payslip['other_deductions'] > 0): ?>
            <div class="widget-card-industrial" style="background: var(--bg-elevated);">
                <div style="padding: 20px; display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <i class="fas fa-minus-circle" style="font-size: 1.5rem; color: var(--danger);"></i>
                        <span style="margin-left: 12px; font-weight: 600;">Beste kenkariak / Otras deducciones</span>
                    </div>
                    <div style="font-size: 1.5rem; font-weight: 700; color: var(--danger);">
                        -<?= number_format($payslip['other_deductions'], 2, ',', '.') ?> €
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- Net Salary (Final) -->
            <div class="widget-card-industrial" style="background: linear-gradient(135deg, #059669 0%, #10B981 100%); border: none; margin-top: 16px;">
                <div style="padding: 30px; display: flex; justify-content: space-between; align-items: center; color: white;">
                    <div>
                        <i class="fas fa-hand-holding-usd" style="font-size: 2rem;"></i>
                        <span style="margin-left: 12px; font-weight: 800; font-size: 1.3rem;">SOLDATA GARBIA / SALARIO NETO</span>
                    </div>
                    <div style="font-size: 2.5rem; font-weight: 900;">
                        <?= number_format($payslip['net_salary'], 2, ',', '.') ?> €
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Notes (if any) -->
<?php if (!empty($payslip['notes'])): ?>
<div class="widget-card-industrial">
    <div class="widget-header">
        <h3 class="widget-title">
            <i class="fas fa-sticky-note"></i>
            Oharrak / Notas
        </h3>
    </div>
    <div class="widget-body">
        <div style="background: var(--bg-elevated); padding: var(--space-4); border-radius: var(--radius-md); border-left: 4px solid var(--primary);">
            <?= nl2br(htmlspecialchars($payslip['notes'])) ?>
        </div>
    </div>
</div>
<?php endif; ?>

<?php require dirname(__DIR__) . '/layouts/footer.php'; ?>
