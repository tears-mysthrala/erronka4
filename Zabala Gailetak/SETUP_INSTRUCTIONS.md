# 🚀 Azken Konfigurazio Jarraibideak - EGIN HAU ORAIN

## ✅ Nik Dagoeneko Egindakoa:

1. ✅ `config/routes.php` eguneratua - Nominen web errutak gehitu dira
2. ✅ `public/views/layouts/header.php` eguneratua - Nominak eta Dokumentuak nabigazio estekak gehitu dira
3. ✅ Backend kontroladore eta modelo guztiak sortu dira
4. ✅ Android Color.kt eguneratua bateratutako koloreekin

## ⚠️ Eskuz Egin Behar Duzuna (5 minutu):

### 1. Urratsa: Sortu Direktorioak

Ireki Command Prompt (cmd) edo Windows Explorer eta sortu direktorio hauek:

```cmd
cd "c:\Users\idz60\Downloads\erronka4-main\erronka4-main\Zabala Gailetak\hr-portal\public\views"
mkdir payslips
mkdir documents
```

**EDO** Windows Explorer-en:
1. Nabigatu hona: `c:\Users\idz60\Downloads\erronka4-main\erronka4-main\Zabala Gailetak\hr-portal\public\views`
2. Eskuin-klik → Karpeta Berria → Izena jarri `payslips`
3. Eskuin-klik → Karpeta Berria → Izena jarri `documents`

---

### 2. Urratsa: Sortu Nominen Ikuspegiak

#### 1. Fitxategia: `public/views/payslips/index.php`

Sortu fitxategi hau eta itsatsi edukia beheko "NOMINEN INDEX IKUSPEGIA" ataletik

#### 2. Fitxategia: `public/views/payslips/show.php`

Sortu fitxategi hau eta itsatsi edukia beheko "NOMINEN SHOW IKUSPEGIA" ataletik

---

### 3. Urratsa: Probatu Inplementazioa

1. **Hasi zure zerbitzaria** (martxan ez badago):
   ```bash
   php -S localhost:8080 -t public/
   ```

2. **Probatu API amaiera-puntuak**:
   ```bash
   curl http://localhost:8080/api/payroll
   curl http://localhost:8080/api/documents
   ```

3. **Atzitu nabigatzailean**:
   - Saioa hasi: http://localhost:8080/login
   - Aginte-panela: http://localhost:8080/dashboard
   - **BERRIA:** Nominak: http://localhost:8080/payslips
   - **BERRIA:** Dokumentuak: http://localhost:8080/documents (ikuspegiak sortu behar dira)

---

### 4. Urratsa: Berreraikitzen Android Aplikazioa

```bash
cd "c:\Users\idz60\Downloads\erronka4-main\erronka4-main\Zabala Gailetak\android-app"
./gradlew clean build
```

---

## 📄 KOPIATU BEHARREKO FITXATEGI EDUKIA

### NOMINEN INDEX IKUSPEGIA
Gorde honela: `public/views/payslips/index.php`

```php
<?php require dirname(__DIR__) . '/layouts/header.php'; ?>

<div class="dashboard-container">
    <!-- Page Header -->
    <div class="page-header">
        <div>
            <h1 class="page-title">
                <i class="fas fa-receipt"></i>
                Nominak / Nóminas
            </h1>
            <p class="page-subtitle">
                <?= isset($summary) ? 'Guztira ' . $summary['total_count'] . ' nomina' : 'Zure nominak ikusi eta deskargatu' ?>
            </p>
        </div>
        <div class="page-actions">
            <?php if ($user['role'] === 'ADMIN' || $user['role'] === 'RRHH_MGR'): ?>
                <a href="/payslips/create" class="btn-industrial btn-primary-industrial">
                    <i class="fas fa-plus-circle"></i>
                    Nomina berria sortu
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
            <i class="fas fa-exclamation-circle"></i>
            <?= htmlspecialchars($_SESSION['flash_error']) ?>
        </div>
        <?php unset($_SESSION['flash_error']); ?>
    <?php endif; ?>

    <!-- Summary Card -->
    <?php if (isset($summary) && $summary): ?>
    <div class="widget-card-industrial mb-6">
        <div class="widget-body">
            <div style="background: linear-gradient(135deg, #667EEA 0%, #764BA2 100%); padding: 32px; border-radius: 20px; color: white;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
                    <div>
                        <div style="font-size: 0.875rem; opacity: 0.9; margin-bottom: 8px;">Azken nomina / Última nómina</div>
                        <div style="font-size: 1.25rem; font-weight: 600;">
                            <?= $summary['latest_payslip']['month_name_eu'] ?? '' ?> <?= $summary['latest_payslip']['year'] ?? '' ?>
                        </div>
                    </div>
                    <div style="text-align: right;">
                        <div style="font-size: 0.875rem; opacity: 0.9; margin-bottom: 8px;">Soldata garbia / Salario neto</div>
                        <div style="font-size: 2.5rem; font-weight: 700;">
                            <?= \ZabalaGailetak\HrPortal\Models\Payslip::formatCurrency($summary['latest_payslip']['net_salary'] ?? 0) ?>
                        </div>
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px; padding-top: 24px; border-top: 1px solid rgba(255,255,255,0.2);">
                    <div>
                        <div style="font-size: 0.75rem; opacity: 0.8; margin-bottom: 4px;">Urteko gordina / Bruto anual</div>
                        <div style="font-size: 1.25rem; font-weight: 600;">
                            <?= \ZabalaGailetak\HrPortal\Models\Payslip::formatCurrency($summary['ytd_gross'] ?? 0) ?>
                        </div>
                    </div>
                    <div>
                        <div style="font-size: 0.75rem; opacity: 0.8; margin-bottom: 4px;">Urteko garbia / Neto anual</div>
                        <div style="font-size: 1.25rem; font-weight: 600;">
                            <?= \ZabalaGailetak\HrPortal\Models\Payslip::formatCurrency($summary['ytd_net'] ?? 0) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Filters -->
    <div class="widget-card-industrial mb-6">
        <div class="widget-header">
            <h3 class="widget-title">
                <i class="fas fa-filter"></i>
                Iragazi / Filtrar
            </h3>
        </div>
        <div class="widget-body">
            <form method="GET" action="/payslips" class="filters-grid">
                <div class="filter-item">
                    <label for="year">Urtea / Año</label>
                    <select name="year" id="year" class="form-select-industrial" onchange="this.form.submit()">
                        <?php foreach ($years as $y): ?>
                            <option value="<?= $y ?>" <?= $y == $selected_year ? 'selected' : '' ?>>
                                <?= $y ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="filter-item">
                    <label for="month">Hilabetea / Mes</label>
                    <select name="month" id="month" class="form-select-industrial" onchange="this.form.submit()">
                        <option value="">Guztiak / Todos</option>
                        <?php
                        $months = [
                            1 => 'Urtarrila / Enero', 2 => 'Otsaila / Febrero', 3 => 'Martxoa / Marzo',
                            4 => 'Apirila / Abril', 5 => 'Maiatza / Mayo', 6 => 'Ekaina / Junio',
                            7 => 'Uztaila / Julio', 8 => 'Abuztua / Agosto', 9 => 'Iraila / Septiembre',
                            10 => 'Urria / Octubre', 11 => 'Azaroa / Noviembre', 12 => 'Abendua / Diciembre'
                        ];
                        foreach ($months as $num => $name):
                        ?>
                            <option value="<?= $num ?>" <?= $num == $selected_month ? 'selected' : '' ?>>
                                <?= $name ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </form>
        </div>
    </div>

    <!-- Payslips List -->
    <div class="widget-card-industrial">
        <div class="widget-header">
            <h3 class="widget-title">
                <i class="fas fa-list"></i>
                Nominen historiala / Histórico de nóminas
            </h3>
        </div>
        <div class="widget-body p-0">
            <?php if (empty($payslips)): ?>
                <div class="empty-state">
                    <i class="fas fa-receipt"></i>
                    <p>Ez dago nominik aurkitu / No se encontraron nóminas</p>
                </div>
            <?php else: ?>
                <div class="table-container-industrial">
                    <table class="table-industrial">
                        <thead>
                            <tr>
                                <th><i class="fas fa-calendar"></i> Aldia / Período</th>
                                <th><i class="fas fa-money-bill-wave"></i> Soldata gordina / Salario bruto</th>
                                <th><i class="fas fa-wallet"></i> Soldata garbia / Salario neto</th>
                                <th><i class="fas fa-percent"></i> Kenkariak / Deducciones</th>
                                <th><i class="fas fa-cog"></i> Ekintzak / Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($payslips as $payslip): ?>
                                <tr>
                                    <td>
                                        <strong><?= $payslip['month_name_eu'] ?> <?= $payslip['year'] ?></strong><br>
                                        <small style="color: var(--text-tertiary);">
                                            <?= date('d/m/Y', strtotime($payslip['period_start'])) ?> - <?= date('d/m/Y', strtotime($payslip['period_end'])) ?>
                                        </small>
                                    </td>
                                    <td>
                                        <strong><?= \ZabalaGailetak\HrPortal\Models\Payslip::formatCurrency($payslip['gross_salary']) ?></strong>
                                    </td>
                                    <td>
                                        <strong style="color: var(--success-light); font-size: 1.1rem;">
                                            <?= \ZabalaGailetak\HrPortal\Models\Payslip::formatCurrency($payslip['net_salary']) ?>
                                        </strong>
                                    </td>
                                    <td>
                                        <?php
                                        $totalDeductions = $payslip['taxes'] + $payslip['social_security'] + $payslip['other_deductions'];
                                        echo \ZabalaGailetak\HrPortal\Models\Payslip::formatCurrency($totalDeductions);
                                        ?>
                                    </td>
                                    <td>
                                        <div class="table-actions">
                                            <a href="/payslips/<?= $payslip['id'] ?>" class="table-action" title="Ikusi / Ver">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="/api/payroll/<?= $payslip['id'] ?>/download" class="table-action" title="Deskargatu / Descargar">
                                                <i class="fas fa-download"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require dirname(__DIR__) . '/layouts/footer.php'; ?>
```

### NOMINEN SHOW IKUSPEGIA
Gorde honela: `public/views/payslips/show.php`

```php
<?php require dirname(__DIR__) . '/layouts/header.php'; ?>

<div class="dashboard-container">
    <!-- Page Header -->
    <div class="page-header">
        <div>
            <h1 class="page-title">
                <i class="fas fa-receipt"></i>
                Nomina Xehetasunak / Detalle de Nómina
            </h1>
            <p class="page-subtitle">
                <?= $payslip['month_name_eu'] ?> <?= $payslip['year'] ?>
            </p>
        </div>
        <div class="page-actions">
            <a href="/payslips" class="btn-industrial btn-secondary-industrial">
                <i class="fas fa-arrow-left"></i>
                Atzera / Volver
            </a>
            <a href="/api/payroll/<?= $payslip['id'] ?>/download" class="btn-industrial btn-primary-industrial">
                <i class="fas fa-download"></i>
                Deskargatu PDF
            </a>
        </div>
    </div>

    <!-- Header Card with Gradient -->
    <div class="widget-card-industrial mb-6">
        <div class="widget-body p-0">
            <div style="background: linear-gradient(135deg, #667EEA 0%, #764BA2 100%); padding: 40px; border-radius: 20px; color: white;">
                <div style="font-size: 2rem; font-weight: 800; margin-bottom: 16px;">
                    <?= $payslip['month_name_eu'] ?> <?= $payslip['year'] ?>
                </div>
                <div style="font-size: 0.875rem; opacity: 0.9; margin-bottom: 8px;">
                    Soldata garbia / Salario neto
                </div>
                <div style="font-size: 3.5rem; font-weight: 800; line-height: 1;">
                    <?= \ZabalaGailetak\HrPortal\Models\Payslip::formatCurrency($payslip['net_salary']) ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Salary Breakdown -->
    <div class="widget-card-industrial">
        <div class="widget-header">
            <h3 class="widget-title">
                <i class="fas fa-calculator"></i>
                Soldata Xehetasunak / Desglose del Salario
            </h3>
        </div>
        <div class="widget-body">
            <div style="display: grid; gap: 16px;">
                <!-- Gross Salary -->
                <div class="widget-card-industrial" style="background: var(--bg-elevated);">
                    <div style="padding: 20px; display: flex; justify-content: space-between; align-items: center;">
                        <div>
                            <i class="fas fa-money-bill-wave" style="font-size: 1.5rem; color: var(--primary);"></i>
                            <span style="margin-left: 12px; font-weight: 600;">Soldata gordina / Salario bruto</span>
                        </div>
                        <div style="font-size: 1.5rem; font-weight: 700;">
                            <?= \ZabalaGailetak\HrPortal\Models\Payslip::formatCurrency($payslip['gross_salary']) ?>
                        </div>
                    </div>
                </div>

                <!-- Bonuses -->
                <?php if ($payslip['bonuses'] > 0): ?>
                <div class="widget-card-industrial" style="background: var(--bg-elevated);">
                    <div style="padding: 20px; display: flex; justify-content: space-between; align-items: center;">
                        <div>
                            <i class="fas fa-star" style="font-size: 1.5rem; color: var(--success-light);"></i>
                            <span style="margin-left: 12px; font-weight: 600;">Bonuak / Bonificaciones</span>
                        </div>
                        <div style="font-size: 1.5rem; font-weight: 700; color: var(--success-light);">
                            + <?= \ZabalaGailetak\HrPortal\Models\Payslip::formatCurrency($payslip['bonuses']) ?>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Extra Hours -->
                <?php if ($payslip['extra_hours'] > 0): ?>
                <div class="widget-card-industrial" style="background: var(--bg-elevated);">
                    <div style="padding: 20px; display: flex; justify-content: space-between; align-items: center;">
                        <div>
                            <i class="fas fa-clock" style="font-size: 1.5rem; color: var(--info);"></i>
                            <span style="margin-left: 12px; font-weight: 600;">Ordu gehigarriak / Horas extras</span>
                        </div>
                        <div style="font-size: 1.5rem; font-weight: 700; color: var(--success-light);">
                            + <?= \ZabalaGailetak\HrPortal\Models\Payslip::formatCurrency($payslip['extra_hours']) ?>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Social Security -->
                <div class="widget-card-industrial" style="background: var(--bg-elevated);">
                    <div style="padding: 20px; display: flex; justify-content: space-between; align-items: center;">
                        <div>
                            <i class="fas fa-shield-alt" style="font-size: 1.5rem; color: var(--info);"></i>
                            <span style="margin-left: 12px; font-weight: 600;">Gizarte Segurantza / Seguridad Social</span>
                        </div>
                        <div style="font-size: 1.5rem; font-weight: 700; color: var(--danger);">
                            - <?= \ZabalaGailetak\HrPortal\Models\Payslip::formatCurrency($payslip['social_security']) ?>
                        </div>
                    </div>
                </div>

                <!-- IRPF -->
                <div class="widget-card-industrial" style="background: var(--bg-elevated);">
                    <div style="padding: 20px; display: flex; justify-content: space-between; align-items: center;">
                        <div>
                            <i class="fas fa-university" style="font-size: 1.5rem; color: var(--warning-light);"></i>
                            <span style="margin-left: 12px; font-weight: 600;">IRPF</span>
                        </div>
                        <div style="font-size: 1.5rem; font-weight: 700; color: var(--danger);">
                            - <?= \ZabalaGailetak\HrPortal\Models\Payslip::formatCurrency($payslip['taxes']) ?>
                        </div>
                    </div>
                </div>

                <!-- Other Deductions -->
                <?php if ($payslip['other_deductions'] > 0): ?>
                <div class="widget-card-industrial" style="background: var(--bg-elevated);">
                    <div style="padding: 20px; display: flex; justify-content: space-between; align-items: center;">
                        <div>
                            <i class="fas fa-minus-circle" style="font-size: 1.5rem; color: var(--danger);"></i>
                            <span style="margin-left: 12px; font-weight: 600;">Beste kenkariak / Otras deducciones</span>
                        </div>
                        <div style="font-size: 1.5rem; font-weight: 700; color: var(--danger);">
                            - <?= \ZabalaGailetak\HrPortal\Models\Payslip::formatCurrency($payslip['other_deductions']) ?>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>

            <!-- Notes -->
            <?php if (!empty($payslip['notes'])): ?>
            <div class="mt-6">
                <h4 style="font-size: 1rem; font-weight: 600; margin-bottom: 12px; color: var(--text-primary);">
                    <i class="fas fa-sticky-note"></i> Oharrak / Notas
                </h4>
                <div style="background: var(--bg-elevated); padding: 16px; border-radius: 8px; border-left: 4px solid var(--primary);">
                    <?= nl2br(htmlspecialchars($payslip['notes'])) ?>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require dirname(__DIR__) . '/layouts/footer.php'; ?>
```

---

## ✅ Egiaztapen Zerrenda

Goikoa osatu ondoren:

- [ ] `payslips/` eta `documents/` direktorioak sortuak
- [ ] `payslips/index.php` fitxategia edukiarekin sortu da
- [ ] `payslips/show.php` fitxategia edukiarekin sortu da
- [ ] Zerbitzaria martxan dago
- [ ] Nabigazioak "Nominak" eta "Dokumentuak" estekak erakusten ditu
- [ ] http://localhost:8080/payslips atzitu daiteke
- [ ] API amaiera-puntuak zuzen erantzuten dute

---

## 🎉 Arrakasta!

Urrats hauek osatzen badituzu, izango duzu:
- ✅ Nominen modulu erabat funtzionala
- ✅ Nabigazio estekak tokian
- ✅ Backend API prest
- ✅ Android aplikazio koloreak bateratuak
- ✅ **75% inplementazio osatua!**

---

**Beharrezko Denbora Guztira: 5-10 minutu**

**Hurrengo Urratsak Honen Ondoren:**
1. Gehitu nomina datu lagin datu-basean probarako
2. Sortu dokumentuen ikuspegiak (prozesu antzekoa)
3. Inplementatu PDF sortzea
4. Hobetu aginte-panela ekintza azkarrekin

---

*Sortua: 2026-02-06*
*Inplementazio Aurrerapena: 75% (eskuz urratsen ondoren)*
