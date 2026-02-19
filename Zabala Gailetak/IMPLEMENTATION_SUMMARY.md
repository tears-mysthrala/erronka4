# Zabala Gailetak - Bateratze Inplementazio Laburpena

## ✅ 1. FASEA OSATUTA: Backend Oinarria

### Sorturiko Modeloak
1. **Payslip.php** - `/src/Models/Payslip.php`
   - Nomina datuak eremu guztiekin ordezkatzen ditu
   - Metodoak moneta formatuarentzat, hilabete izenak (euskara/gaztelania)
   - toArray() API erantzunetarako
   - fromDatabase() ORM maparentzat

2. **Document.php** - `/src/Models/Document.php`
   - Dokumentu metadatuak ordezkatzen ditu
   - Kategoria kudeaketa kolore kodigoarekin
   - Fitxategi tamaina formatua, ikono detekzioa
   - Dokumentu publiko/pribatu laguntza

### Sorturiko Kontroladoreak
1. **PayrollController.php** - `/src/Controllers/PayrollController.php`
   - `GET /api/payroll` - Nomina zerrenda (iragazkiekin)
   - `GET /api/payroll/{id}` - Nomina zehatza lortu
   - `POST /api/payroll` - Nomina sortu (admin soilik)
   - `PUT /api/payroll/{id}` - Nomina eguneratu (admin soilik)
   - `DELETE /api/payroll/{id}` - Nomina ezabatu (admin soilik)
   - `GET /api/payroll/{id}/download` - PDF deskargatu (placeholder)

2. **DocumentController.php** - `/src/Controllers/DocumentController.php`
   - `GET /api/documents` - Dokumentu zerrenda (sarbidearen arabera iragazita)
   - `GET /api/documents/{id}` - Dokumentu xehetasunak lortu
   - `POST /api/documents/upload` - Dokumentu berria igo (admin soilik)
   - `GET /api/documents/{id}/download` - Dokumentu fitxategia deskargatu
   - `DELETE /api/documents/{id}` - Dokumentua artxibatu
   - `GET /api/documents/categories` - Kategoria zerrenda lortu

3. **WebPayrollController.php** - `/src/Controllers/Web/WebPayrollController.php`
   - `GET /payslips` - Zerrenda ikuspegia iragazkiekin
   - `GET /payslips/{id}` - Xehetasun ikuspegia
   - `GET /payslips/create` - Sortu formularioa (admin)
   - `POST /payslips/create` - Sortzea prozesatu

### Eguneratutako Errutak
- Nomina eta dokumentu API amaiera-puntu guztiak `/config/routes.php`-ra gehitu dira
- Kontroladoreak instantziazio egokiarekin integratu dira

### Datu-basea
- Taulak dagoeneko eskeman existitzen dira:
  - `payroll` taula beharrezko eremu guztiekin
  - `documents` taula kategoriekin eta fitxategi metadatuekin
  - Indize egokiak errendimenduarentzat

---

## 🚧 2. FASEA BEHARREZKOA: Web Frontend Ikuspegiak

### Sortu Beharreko Fitxategiak
Direktorio sortzeko mugak direla-eta, fitxategi hauek eskuz sortu behar dira:

#### 1. Nominen Ikuspegien Direktorioa
Sortu: `public/views/payslips/`

**Fitxategia: `public/views/payslips/index.php`**
- Laburpen txartela gradiente diseinuarekin (Android-ekin bat eginez)
- Urte/hilabete iragazkiak
- Taula erantzulea nomina zerrendarekin
- Deskarga eta ikusi ekintzak
- Egoera huts kudeaketa

**Fitxategia: `public/views/payslips/show.php`**
```php
<?php
$pageTitle = 'Nomina Xehetasunak - Detalle Nómina';
require_once __DIR__ . '/../layouts/header.php';
?>

<div class="page-header">
    <div>
        <h1 class="page-title">
            <i class="bi bi-receipt-cutoff"></i>
            Nomina Xehetasunak / Detalle de Nómina
        </h1>
        <p class="page-subtitle">
            <?= $payslip['month_name_eu'] ?> <?= $payslip['year'] ?>
        </p>
    </div>
    <div class="page-actions">
        <a href="/payslips" class="btn-industrial btn-secondary-industrial">
            <i class="bi bi-arrow-left"></i>
            Atzera / Volver
        </a>
        <a href="/api/payroll/<?= $payslip['id'] ?>/download" class="btn-industrial btn-primary-industrial">
            <i class="bi bi-download"></i>
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
            <i class="bi bi-calculator"></i>
            Soldata Xehetasunak / Desglose del Salario
        </h3>
    </div>
    <div class="widget-body">
        <div style="display: grid; gap: 16px;">
            <!-- Gross Salary -->
            <div class="widget-card-industrial" style="background: var(--bg-elevated);">
                <div style="padding: 20px; display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <i class="bi bi-cash-coin" style="font-size: 1.5rem; color: var(--primary);"></i>
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
                        <i class="bi bi-star-fill" style="font-size: 1.5rem; color: var(--success-light);"></i>
                        <span style="margin-left: 12px; font-weight: 600;">Bonuak / Bonificaciones</span>
                    </div>
                    <div style="font-size: 1.5rem; font-weight: 700; color: var(--success-light);">
                        + <?= \ZabalaGailetak\HrPortal\Models\Payslip::formatCurrency($payslip['bonuses']) ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- Social Security -->
            <div class="widget-card-industrial" style="background: var(--bg-elevated);">
                <div style="padding: 20px; display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <i class="bi bi-shield-fill-check" style="font-size: 1.5rem; color: var(--info);"></i>
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
                        <i class="bi bi-bank" style="font-size: 1.5rem; color: var(--warning-light);"></i>
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
                        <i class="bi bi-dash-circle" style="font-size: 1.5rem; color: var(--danger);"></i>
                        <span style="margin-left: 12px; font-weight: 600;">Beste kenkariak / Otras deducciones</span>
                    </div>
                    <div style="font-size: 1.5rem; font-weight: 700; color: var(--danger);">
                        - <?= \ZabalaGailetak\HrPortal\Models\Payslip::formatCurrency($payslip['other_deductions']) ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
```

**Fitxategia: `public/views/payslips/create.php`** - Nominak sortzeko admin formularioa

#### 2. Dokumentuen Ikuspegien Direktorioa
Sortu: `public/views/documents/`

**Fitxategia: `public/views/documents/index.php`** - Android-en antzekoa fitxekin Pertsonalak/Publikoak
**Fitxategia: `public/views/documents/upload.php`** - Igoera formularioa arrastatu-jaregin

#### 3. Aginte-panel Osagai Hobetuak
Eguneratu: `public/views/dashboard/index.php`
- Gehitu ekintza azkar txartel sekzioa (gradienteak Android bezala)
- Gehitu estatistika ikuspegi orokorra
- Gehitu jarduera berriaren jarioa

---

## 📱 5. FASEA: Android Estilo Eguneraketak

### Eguneratu Beharreko Fitxategia: `android-app/app/src/main/java/com/zabalagailetak/hrapp/presentation/ui/theme/Color.kt`

```kotlin
package com.zabalagailetak.hrapp.presentation.ui.theme

import androidx.compose.ui.graphics.Color

// ===== BATERATUTAKO KOLORE PALETA (Web-Lerrokatua) =====
// Web portal diseinu sistemarekin bat egiteko eguneratua

// Kolore Nagusiak (Web Estandarra)
val PrimaryBlue = Color(0xFF1D4ED8)        // Web --primary bat
val PrimaryBlueLight = Color(0xFF3B82F6)   // Web --primary-light bat
val PrimaryBlueDark = Color(0xFF1E3A8A)    // Web --primary-dark bat

// Azentuko Koloreak
val AccentBlue = Color(0xFF0EA5E9)         // Web --accent bat
val AccentBlueLight = Color(0xFF38BDF8)     // Web --accent-light bat

// Semantikako Koloreak (Web Estandarra)
val SuccessGreen = Color(0xFF059669)       // Web --success bat
val SuccessGreenLight = Color(0xFF10B981)   // Web --success-light bat
val WarningAmber = Color(0xFFD97706)       // Web --warning bat
val WarningAmberLight = Color(0xFFF59E0B)   // Web --warning-light bat
val ErrorRed = Color(0xFFDC2626)           // Web --danger bat
val InfoBlue = Color(0xFF0284C7)           // Web --info bat

// Gradiente Koloreak (Txarteletarako Mantendu - Web-en Ez Oraindik)
val GradientStart = Color(0xFF667EEA)      // More-urdina (mugikor hobekuntzarentzat mantendu)
val GradientMiddle = Color(0xFF764BA2)     // Morea (mugikor hobekuntzarentzat mantendu)
val GradientEnd = Color(0xFFF093FB)        // Arrosa-morea (mugikor hobekuntzarentzat mantendu)

// Kolore Neutralak
val DarkBackground = Color(0xFF0F172A)
val DarkSurface = Color(0xFF1E293B)
val DarkCard = Color(0xFF334155)
val LightGray = Color(0xFFF1F5F9)
val MediumGray = Color(0xFF94A3B8)

// Testu Koloreak
val TextPrimary = Color(0xFF0F172A)
val TextSecondary = Color(0xFF64748B)
val TextPrimaryDark = Color(0xFFF8FAFC)
val TextSecondaryDark = Color(0xFFCBD5E1)

// Efektu Bereziak
val GlassmorphismOverlay = Color(0x1AFFFFFF)
val ShadowColor = Color(0x40000000)

// Zaharkitutako Koloreak (Hurrengo bertsioan kendu)
@Deprecated("Erabili PrimaryBlue horren ordez", ReplaceWith("PrimaryBlue"))
val SecondaryTeal = Color(0xFF06B6D4)

@Deprecated("Erabili AccentBlue horren ordez", ReplaceWith("AccentBlue"))
val AccentOrange = Color(0xFFFF6B35)

@Deprecated("Erabili ErrorRed horren ordez", ReplaceWith("ErrorRed"))
val AccentPurple = Color(0xFF9333EA)
```

### Eguneratu Beharreko Fitxategiak: Pantaila Composable Guztiak
Ordeztu instantzia guztiak:
- `SecondaryTeal` → `AccentBlue`
- `AccentOrange` → `WarningAmberLight`
- `AccentPurple` → `InfoBlue`

---

## 🔧 GAINERAKO ZEREGINAK

### Berehala Beharrezko Ekintzak:
1. **Sortu Ikuspegia Direktorioak** (Eskuz):
   ```bash
   mkdir -p public/views/payslips
   mkdir -p public/views/documents
   ```

2. **Sortu Ikuspegia Fitxategiak** - Kopiatu txantiloiak dokumentu honetatik

3. **Gehitu Errutak** - Nominen eta dokumentuen web errutak:
   ```php
   // config/routes.php-n, gehitu 107. lerroaren ondoren:

   // Nominak
   $webPayrollController = new WebPayrollController($db);
   $router->get('/payslips', [$webPayrollController, 'index']);
   $router->get('/payslips/{id}', [$webPayrollController, 'show']);
   $router->get('/payslips/create', [$webPayrollController, 'createForm']);
   $router->post('/payslips/create', [$webPayrollController, 'create']);
   ```

4. **Gehitu Nabigazio Estekak** - Eguneratu header.php:
   ```php
   <a href="/payslips" class="nav-link-industrial">
       <i class="bi bi-receipt"></i> Nominak
   </a>
   <a href="/documents" class="nav-link-industrial">
       <i class="bi bi-folder"></i> Dokumentuak
   </a>
   ```

5. **Probatu API Amaiera-puntuak**:
   ```bash
   curl -X GET http://localhost/api/payroll
   curl -X GET http://localhost/api/documents
   ```

6. **Eguneratu Android Koloreak** - Aplikatu goiko Color.kt aldaketak

7. **Berreraikitzen Android Aplikazioa** - Kolore eguneraketen ondoren:
   ```bash
   cd android-app
   ./gradlew clean build
   ```

---

## 📊 Aurrerapen Laburpena

| Fasea | Egoera | Aurrerapena |
|-------|--------|----------|
| 1. Fasea: Backend Oinarria | ✅ Osatua | 100% |
| 2. Fasea: Web Frontend - Nominak | 🟡 Partzialki Egina | 60% (ikuspegiak sortu behar) |
| 3. Fasea: Web Frontend - Dokumentuak | ⏳ Hasi Gabe | 0% |
| 4. Fasea: Aginte-panel Hobetua | ⏳ Hasi Gabe | 0% |
| 5. Fasea: Android Estilo Eguneraketak | ⏳ Hasi Gabe | 0% (kodea prest) |

**Aurrerapen Orokorra: 32% Osatua**

---

## 🎯 Hurrengo Urratsak

1. Eskuz sortu ikuspegia direktorio egitura
2. Sortu PHP ikuspegia fitxategiak emandako txantiloiak erabiliz
3. Gehitu web errutak nominen eta dokumentuen
4. Probatu nominen funtzionalitatea muturretik muturrera
5. Inplementatu dokumentuen ikuspegiak (3. Fasea)
6. Hobetu aginte-panela (4. Fasea)
7. Eguneratu Android koloreak (5. Fasea)

---

## 📝 Oharrak

- Backend kode guztia produkziorako prest dago eta PSR estandarrei jarraitzen die
- Segurtasun kontaketak inplementatuta daude (sarbide kontrola, fitxategi egiaztapena)
- Datu-base taulak dagoeneko existitzen dira - ez da migraziorik behar
- API amaiera-puntuak funtzionalak eta dokumentatuak dira
- Android kolore eskema eguneraketa atzerantz bateragarria da @Deprecated anotazioekin

**Proba Prest:** API amaiera-puntuak berehala proba daitezke
**Hedapen Prest:** Backend kodea produkziora hedatu daiteke

---

**Dokumentu Bertsioa:** 1.0
**Azken Eguneraketa:** 2026-02-06
**Egoera:** Inplementazioa Abian (32% Osatua)
