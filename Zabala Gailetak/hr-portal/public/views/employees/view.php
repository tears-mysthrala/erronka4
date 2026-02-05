?>
<!DOCTYPE html>
<html lang="eu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Langilearen Xehetasunak - Zabala Gailetak HR</title>
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
            <span><?= htmlspecialchars($employee['first_name'] . ' ' . $employee['last_name']) ?></span>
        </div>

        <div class="profile-header">
            <div class="profile-avatar">
                <?= strtoupper(substr($employee['first_name'], 0, 1) . substr($employee['last_name'], 0, 1)) ?>
            </div>
            <div class="profile-info">
                <div class="profile-name">
                    <?= htmlspecialchars($employee['first_name'] . ' ' . $employee['last_name']) ?>
                </div>
                <div class="profile-title">
                    <?= htmlspecialchars($employee['position'] ?? 'Langilean') ?> · <?= htmlspecialchars($employee['department_name'] ?? 'Saila zehaztugabea') ?>
                </div>
                <div class="profile-meta">
                    <div class="meta-item">
                        <span class="meta-label">Egoera:</span>
                        <span class="status-badge <?= $employee['active'] ? 'status-active' : 'status-inactive' ?>">
                            <?= $employee['active'] ? 'Aktiboa' : 'Ez-aktiboa' ?>
                        </span>
                    </div>
                    <div class="meta-item">
                        <span class="meta-label">Rola:</span>
                        <span class="meta-value"><?= htmlspecialchars($employee['role'] ?? 'EMPLEADO') ?></span>
                    </div>
                    <div class="meta-item">
                        <span class="meta-label">NAN:</span>
                        <span class="meta-value"><?= htmlspecialchars($employee['nif'] ?? '-') ?></span>
                    </div>
                </div>
                <?php if (in_array($user['role'] ?? '', ['ADMIN', 'RRHH_MGR'])): ?>
                    <div class="profile-actions">
                        <a href="/employees/<?= $employee['id'] ?>/edit" class="btn btn-primary">
                            ✏️ Editatu
                        </a>
                        <?php if ($employee['active']): ?>
                            <button onclick="deactivateEmployee(<?= $employee['id'] ?>)" class="btn btn-danger">
                                🚫 Desaktibatu
                            </button>
                        <?php else: ?>
                            <button onclick="activateEmployee(<?= $employee['id'] ?>)" class="btn btn-secondary">
                                ✅ Aktibatu
                            </button>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="tabs">
            <button class="tab active" onclick="showTab('info')">📋 Informazio Orokorra</button>
            <button class="tab" onclick="showTab('contact')">📞 Kontaktua</button>
            <button class="tab" onclick="showTab('work')">💼 Lan Informazioa</button>
            <button class="tab" onclick="showTab('history')">📜 Historia</button>
        </div>

        <div class="content-grid">
            <div>
                <!-- General Info Tab -->
                <div id="info-tab" class="tab-content active">
                    <div class="section">
                        <h3 class="section-title">Datu Pertsonalak</h3>
                        <div class="info-grid">
                            <div class="info-item">
                                <span class="info-label">Izena</span>
                                <span class="info-value"><?= htmlspecialchars($employee['first_name']) ?></span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Abizena</span>
                                <span class="info-value"><?= htmlspecialchars($employee['last_name']) ?></span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">NAN</span>
                                <span class="info-value"><?= htmlspecialchars($employee['nif'] ?? '-') ?></span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Jaiotza Data</span>
                                <span class="info-value"><?= $employee['birth_date'] ? date('Y-m-d', strtotime($employee['birth_date'])) : '-' ?></span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Generoa</span>
                                <span class="info-value"><?= htmlspecialchars($employee['gender'] ?? '-') ?></span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Nazionalitatea</span>
                                <span class="info-value"><?= htmlspecialchars($employee['nationality'] ?? '-') ?></span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contact Tab -->
                <div id="contact-tab" class="tab-content">
                    <div class="section">
                        <h3 class="section-title">Harremanetarako Datuak</h3>
                        <div class="info-grid">
                            <div class="info-item">
                                <span class="info-label">Email</span>
                                <span class="info-value"><?= htmlspecialchars($employee['email']) ?></span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Telefonoa</span>
                                <span class="info-value"><?= htmlspecialchars($employee['phone'] ?? '-') ?></span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Helbidea</span>
                                <span class="info-value"><?= htmlspecialchars($employee['address'] ?? '-') ?></span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Hiria</span>
                                <span class="info-value"><?= htmlspecialchars($employee['city'] ?? '-') ?></span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Posta Kodea</span>
                                <span class="info-value"><?= htmlspecialchars($employee['postal_code'] ?? '-') ?></span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Probintzia</span>
                                <span class="info-value"><?= htmlspecialchars($employee['province'] ?? '-') ?></span>
                            </div>
                        </div>
                    </div>

                    <div class="section">
                        <h3 class="section-title">Larrialdietarako Kontaktua</h3>
                        <div class="info-grid">
                            <div class="info-item">
                                <span class="info-label">Izena</span>
                                <span class="info-value"><?= htmlspecialchars($employee['emergency_contact_name'] ?? '-') ?></span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Telefonoa</span>
                                <span class="info-value"><?= htmlspecialchars($employee['emergency_contact_phone'] ?? '-') ?></span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Harremana</span>
                                <span class="info-value"><?= htmlspecialchars($employee['emergency_contact_relationship'] ?? '-') ?></span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Work Info Tab -->
                <div id="work-tab" class="tab-content">
                    <div class="section">
                        <h3 class="section-title">Lan Informazioa</h3>
                        <div class="info-grid">
                            <div class="info-item">
                                <span class="info-label">Saila</span>
                                <span class="info-value"><?= htmlspecialchars($employee['department_name'] ?? '-') ?></span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Lanpostua</span>
                                <span class="info-value"><?= htmlspecialchars($employee['position'] ?? '-') ?></span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Hasiera Data</span>
                                <span class="info-value"><?= $employee['hire_date'] ? date('Y-m-d', strtotime($employee['hire_date'])) : '-' ?></span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Kontratazio Mota</span>
                                <span class="info-value"><?= htmlspecialchars($employee['contract_type'] ?? '-') ?></span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Soldata</span>
                                <span class="info-value"><?= isset($employee['salary']) ? number_format($employee['salary'], 2) . '€' : '-' ?></span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">IBAN</span>
                                <span class="info-value"><?= htmlspecialchars($employee['iban'] ?? '-') ?></span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- History Tab -->
                <div id="history-tab" class="tab-content">
                    <div class="section">
                        <h3 class="section-title">Langilezararen Historia</h3>
                        <div class="timeline">
                            <?php foreach ($history ?? [] as $item): ?>
                                <div class="timeline-item">
                                    <div class="timeline-date">
                                        <?= date('Y-m-d H:i', strtotime($item['created_at'])) ?>
                                    </div>
                                    <div class="timeline-content">
                                        <?= htmlspecialchars($item['description']) ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                            
                            <?php if (empty($history)): ?>
                                <p style="color: #7f8c8d;">Ez dago historia-erregistrorik oraindik.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div>
                <div class="section">
                    <h3 class="section-title">Estatistika Azkarrak</h3>
                    <div class="quick-stats">
                        <div class="stat-box">
                            <h4>Opor Egunak</h4>
                            <div class="value"><?= $stats['vacation_days'] ?? 22 ?></div>
                        </div>
                        <div class="stat-box">
                            <h4>Enpresa Urteak</h4>
                            <div class="value"><?= $stats['years_in_company'] ?? 0 ?></div>
                        </div>
                        <div class="stat-box">
                            <h4>Dokumentuak</h4>
                            <div class="value"><?= $stats['documents_count'] ?? 0 ?></div>
                        </div>
                    </div>
                </div>

                <div class="section">
                    <h3 class="section-title">Ekintza Azkarrak</h3>
                    <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                        <a href="/vacations?employee=<?= $employee['id'] ?>" class="btn btn-primary" style="text-align: center;">
                            📅 Oporrak Ikusi
                        </a>
                        <a href="/payrolls?employee=<?= $employee['id'] ?>" class="btn btn-secondary" style="text-align: center;">
                            💰 Nomirak Ikusi
                        </a>
                        <a href="/documents?employee=<?= $employee['id'] ?>" class="btn btn-secondary" style="text-align: center;">
                            📄 Dokumentuak Ikusi
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function showTab(tabName) {
            // Hide all tabs
            document.querySelectorAll('.tab-content').forEach(tab => {
                tab.classList.remove('active');
            });
            document.querySelectorAll('.tab').forEach(btn => {
                btn.classList.remove('active');
            });

            // Show selected tab
            document.getElementById(tabName + '-tab').classList.add('active');
            event.target.classList.add('active');
        }

        function deactivateEmployee(id) {
            if (confirm('Ziur zaude langile hau desaktibatu nahi duzula?')) {
                fetch(`/employees/${id}/deactivate`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.location.reload();
                    } else {
                        alert('Errorea: ' + data.error);
                    }
                });
            }
        }

        function activateEmployee(id) {
            if (confirm('Ziur zaude langile hau aktibatu nahi duzula?')) {
                fetch(`/employees/${id}/activate`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.location.reload();
                    } else {
                        alert('Errorea: ' + data.error);
                    }
                });
            }
        }
    </script>
</body>
</html>
