<?php require dirname(__DIR__) . '/layouts/header.php'; ?>

<div class="page-header">
    <div>
        <h1 class="page-title">
            <i class="fas fa-plus-circle"></i>
            Nomina berria sortu / Crear nueva nómina
        </h1>
        <p class="page-subtitle">
            Langilearen nomina berria sortu / Crear nueva nómina para empleado
        </p>
    </div>
    <div class="page-actions">
        <a href="/payslips" class="btn-industrial btn-secondary-industrial">
            <i class="fas fa-arrow-left"></i>
            Atzera / Volver
        </a>
    </div>
</div>

<div class="widget-card-industrial">
    <div class="widget-header">
        <h3 class="widget-title">
            <i class="fas fa-file-invoice-dollar"></i>
            Nominaren datuak / Datos de la nómina
        </h3>
    </div>
    <div class="widget-body">
        <form method="POST" action="/payslips/create" class="form-industrial">
            <!-- Employee Selection -->
            <div class="form-group">
                <label for="employee_id" class="form-label required">Langilea / Empleado</label>
                <select name="employee_id" id="employee_id" class="form-control" required>
                    <option value="">Hautatu langilea / Selecciona empleado...</option>
                    <?php foreach ($employees as $emp): ?>
                        <option value="<?= $emp['id'] ?>">
                            <?= htmlspecialchars($emp['first_name'] . ' ' . $emp['last_name']) ?> (<?= htmlspecialchars($emp['email']) ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Period -->
            <div class="form-row">
                <div class="form-group">
                    <label for="period_start" class="form-label required">Hasiera data / Fecha inicio</label>
                    <input type="date" name="period_start" id="period_start" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="period_end" class="form-label required">Amaiera data / Fecha fin</label>
                    <input type="date" name="period_end" id="period_end" class="form-control" required>
                </div>
            </div>

            <!-- Salary Components -->
            <div class="form-section">
                <h4 class="form-section-title">
                    <i class="fas fa-money-bill-wave"></i>
                    Soldata osagaiak / Componentes del salario
                </h4>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="base_salary" class="form-label required">Oinarrizko soldata / Salario base (€)</label>
                        <input type="number" name="base_salary" id="base_salary" class="form-control" step="0.01" min="0" required>
                    </div>
                    <div class="form-group">
                        <label for="extra_hours" class="form-label">Ordubete extra / Horas extra (€)</label>
                        <input type="number" name="extra_hours" id="extra_hours" class="form-control" step="0.01" min="0" value="0">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="bonuses" class="form-label">Bonuak / Bonificaciones (€)</label>
                        <input type="number" name="bonuses" id="bonuses" class="form-control" step="0.01" min="0" value="0">
                    </div>
                    <div class="form-group">
                        <label for="commissions" class="form-label">Komisioiak / Comisiones (€)</label>
                        <input type="number" name="commissions" id="commissions" class="form-control" step="0.01" min="0" value="0">
                    </div>
                </div>
            </div>

            <!-- Deductions -->
            <div class="form-section">
                <h4 class="form-section-title">
                    <i class="fas fa-minus-circle"></i>
                    Kenkariak / Deducciones
                </h4>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="social_security" class="form-label">Gizarte Segurantza / Seguridad Social (€)</label>
                        <input type="number" name="social_security" id="social_security" class="form-control" step="0.01" min="0" value="0">
                    </div>
                    <div class="form-group">
                        <label for="taxes" class="form-label">IRPF / Impuestos (€)</label>
                        <input type="number" name="taxes" id="taxes" class="form-control" step="0.01" min="0" value="0">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="deductions" class="form-label">Kenkariak / Deducciones (€)</label>
                        <input type="number" name="deductions" id="deductions" class="form-control" step="0.01" min="0" value="0">
                    </div>
                    <div class="form-group">
                        <label for="other_deductions" class="form-label">Beste kenkariak / Otras deducciones (€)</label>
                        <input type="number" name="other_deductions" id="other_deductions" class="form-control" step="0.01" min="0" value="0">
                    </div>
                </div>
            </div>

            <!-- Net Salary (Calculated) -->
            <div class="form-section">
                <h4 class="form-section-title">
                    <i class="fas fa-calculator"></i>
                    Kalkulu automatikoa / Cálculo automático
                </h4>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="calculated_gross" class="form-label">Soldata gordina (kalkulatua) / Salario bruto (calculado)</label>
                        <input type="text" id="calculated_gross" class="form-control" readonly style="background: var(--bg-elevated); font-weight: 600;">
                    </div>
                    <div class="form-group">
                        <label for="net_salary" class="form-label required">Soldata garbia / Salario neto (€)</label>
                        <input type="number" name="net_salary" id="net_salary" class="form-control" step="0.01" min="0" required readonly style="background: var(--success-light); color: white; font-weight: 700; font-size: 1.1rem;">
                    </div>
                </div>
            </div>

            <!-- Notes -->
            <div class="form-group">
                <label for="notes" class="form-label">Oharrak / Notas</label>
                <textarea name="notes" id="notes" class="form-control" rows="4" placeholder="Gehitu oharrak hemen... / Añade notas aquí..."></textarea>
            </div>

            <!-- Actions -->
            <div class="form-actions">
                <button type="submit" class="btn-industrial btn-primary-industrial">
                    <i class="fas fa-save"></i>
                    Gorde nomina / Guardar nómina
                </button>
                <a href="/payslips" class="btn-industrial btn-secondary-industrial">
                    <i class="fas fa-times"></i>
                    Utzi / Cancelar
                </a>
            </div>
        </form>
    </div>
</div>

<script>
// Auto-calculate gross and net salary
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('.form-industrial');
    const baseSalary = document.getElementById('base_salary');
    const extraHours = document.getElementById('extra_hours');
    const bonuses = document.getElementById('bonuses');
    const commissions = document.getElementById('commissions');
    const socialSecurity = document.getElementById('social_security');
    const taxes = document.getElementById('taxes');
    const deductions = document.getElementById('deductions');
    const otherDeductions = document.getElementById('other_deductions');
    const calculatedGross = document.getElementById('calculated_gross');
    const netSalary = document.getElementById('net_salary');

    function calculateSalary() {
        const base = parseFloat(baseSalary.value) || 0;
        const extra = parseFloat(extraHours.value) || 0;
        const bonus = parseFloat(bonuses.value) || 0;
        const commission = parseFloat(commissions.value) || 0;
        const ss = parseFloat(socialSecurity.value) || 0;
        const tax = parseFloat(taxes.value) || 0;
        const ded = parseFloat(deductions.value) || 0;
        const otherDed = parseFloat(otherDeductions.value) || 0;

        const gross = base + extra + bonus + commission;
        const net = gross - ss - tax - ded - otherDed;

        calculatedGross.value = gross.toFixed(2) + ' €';
        netSalary.value = net.toFixed(2);
    }

    // Add event listeners to all numeric inputs
    [baseSalary, extraHours, bonuses, commissions, socialSecurity, taxes, deductions, otherDeductions].forEach(input => {
        input.addEventListener('input', calculateSalary);
    });

    // Auto-calculate SS and IRPF based on base salary (simplified)
    baseSalary.addEventListener('blur', function() {
        if (socialSecurity.value === '0' || socialSecurity.value === '') {
            const base = parseFloat(this.value) || 0;
            // Approximate SS: 6.35% of gross
            socialSecurity.value = (base * 0.0635).toFixed(2);
        }
        if (taxes.value === '0' || taxes.value === '') {
            const base = parseFloat(this.value) || 0;
            // Approximate IRPF: 15% (simplified)
            taxes.value = (base * 0.15).toFixed(2);
        }
        calculateSalary();
    });

    // Initial calculation
    calculateSalary();
});
</script>

<?php require dirname(__DIR__) . '/layouts/footer.php'; ?>
