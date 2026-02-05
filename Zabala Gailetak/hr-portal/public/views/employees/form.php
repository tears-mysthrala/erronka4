<?php ?>
<!DOCTYPE html>
<html lang="eu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $isEdit ? 'Langilean Editatu' : 'Langile Berria Sortu' ?> - Zabala Gailetak HR</title>
    <link rel="stylesheet" href="/assets/vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
    <div class="header">
        <h1>📊 Zabala Gailetak - Giza Baliabideak</h1>
    </div>

    <nav class="nav">
        <a href="/dashboard">Hasiera</a>
        <a href="/employees">Langileak</a>
        <a href="/vacations">Oporrak</a>
        <a href="/payrolls">Nominen</a>
        <a href="/documents">Dokumentuak</a>
        <a href="/profile">Profila</a>
        <a href="/logout" style="margin-left: auto;">Irten</a>
    </nav>

    <div class="container">
        <div class="breadcrumb">
            <a href="/dashboard">Hasiera</a> / 
            <a href="/employees">Langileak</a> / 
            <span><?= $isEdit ? 'Editatu' : 'Berria Sortu' ?></span>
        </div>

        <div class="form-header">
            <h2><?= $isEdit ? '✏️ Langilean Editatu' : '➕ Langile Berria Sortu' ?></h2>
            <p><?= $isEdit ? 'Aldatu langilearen datuak behar bezala.' : 'Bete formulario hau langile berri bat sortzeko.' ?></p>
        </div>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-error">
                <strong>⚠️ Erroreak:</strong>
                <ul style="margin-top: 0.5rem; margin-left: 1.5rem;">
                    <?php foreach ($errors as $error): ?>
                        <li><?= htmlspecialchars($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form id="employee-form" method="POST" action="<?= $isEdit ? '/employees/' . $employee['id'] : '/employees' ?>" class="form-container">
            <?php if ($isEdit): ?>
                <input type="hidden" name="_method" value="PUT">
            <?php endif; ?>
            
            <!-- Personal Information -->
            <div class="form-section">
                <h3 class="section-title">📋 Informazio Pertsonala</h3>
                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label" for="first_name">
                            Izena <span class="required">*</span>
                        </label>
                        <input 
                            type="text" 
                            id="first_name" 
                            name="first_name" 
                            class="form-input"
                            value="<?= htmlspecialchars($employee['first_name'] ?? '') ?>"
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="last_name">
                            Abizena <span class="required">*</span>
                        </label>
                        <input 
                            type="text" 
                            id="last_name" 
                            name="last_name" 
                            class="form-input"
                            value="<?= htmlspecialchars($employee['last_name'] ?? '') ?>"
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="nif">
                            NAN <span class="required">*</span>
                        </label>
                        <input 
                            type="text" 
                            id="nif" 
                            name="nif" 
                            class="form-input"
                            value="<?= htmlspecialchars($employee['nif'] ?? '') ?>"
                            pattern="[0-9]{8}[A-Z]"
                            placeholder="12345678A"
                            required
                        >
                        <span class="form-help">Formatua: 8 zenbaki + letra</span>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="birth_date">
                            Jaiotza Data <span class="required">*</span>
                        </label>
                        <input 
                            type="date" 
                            id="birth_date" 
                            name="birth_date" 
                            class="form-input"
                            value="<?= $employee['birth_date'] ?? '' ?>"
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="gender">
                            Generoa
                        </label>
                        <select id="gender" name="gender" class="form-select">
                            <option value="">Aukeratu...</option>
                            <option value="M" <?= ($employee['gender'] ?? '') === 'M' ? 'selected' : '' ?>>Gizona</option>
                            <option value="F" <?= ($employee['gender'] ?? '') === 'F' ? 'selected' : '' ?>>Emakumea</option>
                            <option value="O" <?= ($employee['gender'] ?? '') === 'O' ? 'selected' : '' ?>>Bestea</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="nationality">
                            Nazionalitatea
                        </label>
                        <input 
                            type="text" 
                            id="nationality" 
                            name="nationality" 
                            class="form-input"
                            value="<?= htmlspecialchars($employee['nationality'] ?? '') ?>"
                            placeholder="Espainiako"
                        >
                    </div>
                </div>
            </div>

            <!-- Contact Information -->
            <div class="form-section">
                <h3 class="section-title">📞 Harremanetarako Datuak</h3>
                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label" for="email">
                            Email <span class="required">*</span>
                        </label>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            class="form-input"
                            value="<?= htmlspecialchars($employee['email'] ?? '') ?>"
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="phone">
                            Telefonoa
                        </label>
                        <input 
                            type="tel" 
                            id="phone" 
                            name="phone" 
                            class="form-input"
                            value="<?= htmlspecialchars($employee['phone'] ?? '') ?>"
                            placeholder="+34 600 000 000"
                        >
                    </div>

                    <div class="form-group full-width">
                        <label class="form-label" for="address">
                            Helbidea
                        </label>
                        <input 
                            type="text" 
                            id="address" 
                            name="address" 
                            class="form-input"
                            value="<?= htmlspecialchars($employee['address'] ?? '') ?>"
                            placeholder="Kale Nagusia, 123"
                        >
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="city">
                            Hiria
                        </label>
                        <input 
                            type="text" 
                            id="city" 
                            name="city" 
                            class="form-input"
                            value="<?= htmlspecialchars($employee['city'] ?? '') ?>"
                        >
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="postal_code">
                            Posta Kodea
                        </label>
                        <input 
                            type="text" 
                            id="postal_code" 
                            name="postal_code" 
                            class="form-input"
                            value="<?= htmlspecialchars($employee['postal_code'] ?? '') ?>"
                            pattern="[0-9]{5}"
                            placeholder="20000"
                        >
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="province">
                            Probintzia
                        </label>
                        <input 
                            type="text" 
                            id="province" 
                            name="province" 
                            class="form-input"
                            value="<?= htmlspecialchars($employee['province'] ?? '') ?>"
                        >
                    </div>
                </div>
            </div>

            <!-- Emergency Contact -->
            <div class="form-section">
                <h3 class="section-title">🚨 Larrialdietarako Kontaktua</h3>
                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label" for="emergency_contact_name">
                            Izena
                        </label>
                        <input 
                            type="text" 
                            id="emergency_contact_name" 
                            name="emergency_contact_name" 
                            class="form-input"
                            value="<?= htmlspecialchars($employee['emergency_contact_name'] ?? '') ?>"
                        >
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="emergency_contact_phone">
                            Telefonoa
                        </label>
                        <input 
                            type="tel" 
                            id="emergency_contact_phone" 
                            name="emergency_contact_phone" 
                            class="form-input"
                            value="<?= htmlspecialchars($employee['emergency_contact_phone'] ?? '') ?>"
                        >
                    </div>

                    <div class="form-group full-width">
                        <label class="form-label" for="emergency_contact_relationship">
                            Harremana
                        </label>
                        <input 
                            type="text" 
                            id="emergency_contact_relationship" 
                            name="emergency_contact_relationship" 
                            class="form-input"
                            value="<?= htmlspecialchars($employee['emergency_contact_relationship'] ?? '') ?>"
                            placeholder="Senarra, alaba, anaia..."
                        >
                    </div>
                </div>
            </div>

            <!-- Work Information -->
            <div class="form-section">
                <h3 class="section-title">💼 Lan Informazioa</h3>
                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label" for="department_id">
                            Saila <span class="required">*</span>
                        </label>
                        <select id="department_id" name="department_id" class="form-select" required>
                            <option value="">Aukeratu...</option>
                            <?php foreach ($departments as $dept): ?>
                                <option value="<?= $dept['id'] ?>" <?= ($employee['department_id'] ?? '') == $dept['id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($dept['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="position">
                            Lanpostua <span class="required">*</span>
                        </label>
                        <input 
                            type="text" 
                            id="position" 
                            name="position" 
                            class="form-input"
                            value="<?= htmlspecialchars($employee['position'] ?? '') ?>"
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="hire_date">
                            Hasiera Data <span class="required">*</span>
                        </label>
                        <input 
                            type="date" 
                            id="hire_date" 
                            name="hire_date" 
                            class="form-input"
                            value="<?= $employee['hire_date'] ?? '' ?>"
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="contract_type">
                            Kontratazio Mota
                        </label>
                        <select id="contract_type" name="contract_type" class="form-select">
                            <option value="">Aukeratu...</option>
                            <option value="permanent" <?= ($employee['contract_type'] ?? '') === 'permanent' ? 'selected' : '' ?>>Finkoa</option>
                            <option value="temporary" <?= ($employee['contract_type'] ?? '') === 'temporary' ? 'selected' : '' ?>>Behin-behinekoa</option>
                            <option value="internship" <?= ($employee['contract_type'] ?? '') === 'internship' ? 'selected' : '' ?>>Praktikak</option>
                            <option value="apprentice" <?= ($employee['contract_type'] ?? '') === 'apprentice' ? 'selected' : '' ?>>Ikaslan</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="salary">
                            Soldata (€)
                        </label>
                        <input 
                            type="number" 
                            id="salary" 
                            name="salary" 
                            class="form-input"
                            value="<?= htmlspecialchars($employee['salary'] ?? '') ?>"
                            min="0"
                            step="0.01"
                            placeholder="25000.00"
                        >
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="iban">
                            IBAN
                        </label>
                        <input 
                            type="text" 
                            id="iban" 
                            name="iban" 
                            class="form-input"
                            value="<?= htmlspecialchars($employee['iban'] ?? '') ?>"
                            pattern="[A-Z]{2}[0-9]{22}"
                            placeholder="ES0000000000000000000000"
                        >
                        <span class="form-help">Formatua: ES + 22 zenbaki</span>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="role">
                            Rola <span class="required">*</span>
                        </label>
                        <select id="role" name="role" class="form-select" required>
                            <option value="EMPLEADO" <?= ($employee['role'] ?? 'EMPLEADO') === 'EMPLEADO' ? 'selected' : '' ?>>Langilea</option>
                            <option value="RRHH" <?= ($employee['role'] ?? '') === 'RRHH' ? 'selected' : '' ?>>GIZA Baliabideak</option>
                            <option value="RRHH_MGR" <?= ($employee['role'] ?? '') === 'RRHH_MGR' ? 'selected' : '' ?>>GIZA Baliabide Kudeatzailea</option>
                            <option value="ADMIN" <?= ($employee['role'] ?? '') === 'ADMIN' ? 'selected' : '' ?>>Administratzailea</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label checkbox-group">
                            <input 
                                type="checkbox" 
                                id="active" 
                                name="active" 
                                value="1"
                                <?= ($employee['active'] ?? true) ? 'checked' : '' ?>
                            >
                            <span>Aktiboa</span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="form-actions">
                <a href="/employees" class="btn btn-secondary">❌ Ezeztatu</a>
                <button type="submit" class="btn btn-primary">
                    <?= $isEdit ? '💾 Gorde Aldaketak' : '➕ Sortu Langilean' ?>
                </button>
            </div>
        </form>
    </div>

    <script>
        // Form validation
        document.getElementById('employee-form').addEventListener('submit', function(e) {
            const requiredFields = this.querySelectorAll('[required]');
            let isValid = true;

            requiredFields.forEach(field => {
                field.classList.remove('input-error');
                if (!field.value.trim()) {
                    field.classList.add('input-error');
                    isValid = false;
                }
            });

            if (!isValid) {
                e.preventDefault();
                alert('Mesedez, bete derrigorrezko eremu guztiak.');
            }
        });

        // NIF validation
        document.getElementById('nif').addEventListener('blur', function() {
            const nif = this.value.toUpperCase();
            const nifRegex = /^[0-9]{8}[A-Z]$/;
            
            if (nif && !nifRegex.test(nif)) {
                this.classList.add('input-error');
                alert('NAN formatua okerra. Erabili: 12345678A');
            } else {
                this.classList.remove('input-error');
            }
        });

        // IBAN validation
        document.getElementById('iban').addEventListener('blur', function() {
            const iban = this.value.toUpperCase();
            const ibanRegex = /^[A-Z]{2}[0-9]{22}$/;
            
            if (iban && !ibanRegex.test(iban)) {
                this.classList.add('input-error');
                alert('IBAN formatua okerra. Erabili: ES0000000000000000000000');
            } else {
                this.classList.remove('input-error');
            }
        });

        // Auto-format IBAN
        document.getElementById('iban').addEventListener('input', function() {
            this.value = this.value.toUpperCase().replace(/[^A-Z0-9]/g, '');
        });

        // Auto-format NIF
        document.getElementById('nif').addEventListener('input', function() {
            this.value = this.value.toUpperCase().replace(/[^0-9A-Z]/g, '');
        });
    </script>
</body>
</html>
