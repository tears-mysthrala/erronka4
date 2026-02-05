<!DOCTYPE html>
<html lang="eu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Langileak - Zabala Gailetak HR</title>
    <link rel="stylesheet" href="/assets/vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
    <div class="header">
        <h1>📊 Zabala Gailetak - Giza Baliabideak</h1>
    </div>

    <nav class="nav">
        <a href="/dashboard" class="active">Hasiera</a>
        <a href="/employees">Langileak</a>
        <a href="/vacations">Oporrak</a>
        <a href="/payrolls">Nominen</a>
        <a href="/documents">Dokumentuak</a>
        <a href="/profile">Profila</a>
        <a href="/logout" style="margin-left: auto;">Irten</a>
    </nav>

    <div class="container">
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success">
                ✅ <?= htmlspecialchars($_SESSION['success']) ?>
            </div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-error">
                ❌ <?= htmlspecialchars($_SESSION['error']) ?>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <div class="stats">
            <div class="stat-card">
                <h3>Langile Aktiboak</h3>
                <div class="value"><?= $stats['active'] ?? 0 ?></div>
            </div>
            <div class="stat-card">
                <h3>Sailak</h3>
                <div class="value"><?= $stats['departments'] ?? 0 ?></div>
            </div>
            <div class="stat-card">
                <h3>Langile Berriak (Hilabete)</h3>
                <div class="value"><?= $stats['new_this_month'] ?? 0 ?></div>
            </div>
            <div class="stat-card">
                <h3>Batez Besteko Adina</h3>
                <div class="value"><?= $stats['avg_age'] ?? 0 ?></div>
            </div>
        </div>

        <div class="actions-bar">
            <div class="search-box">
                <input 
                    type="text" 
                    id="searchInput" 
                    placeholder="Bilatu langileak (izena, NAN, email...)"
                    value="<?= htmlspecialchars($_GET['search'] ?? '') ?>"
                >
                <span class="search-icon">🔍</span>
            </div>

            <div class="filter-group">
                <select id="departmentFilter">
                    <option value="">Sail guztiak</option>
                    <?php foreach ($departments ?? [] as $dept): ?>
                        <option value="<?= htmlspecialchars($dept['id']) ?>" 
                            <?= (isset($_GET['department']) && $_GET['department'] == $dept['id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($dept['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <select id="statusFilter">
                    <option value="">Egoera guztiak</option>
                    <option value="1" <?= (isset($_GET['active']) && $_GET['active'] == '1') ? 'selected' : '' ?>>Aktiboak</option>
                    <option value="0" <?= (isset($_GET['active']) && $_GET['active'] == '0') ? 'selected' : '' ?>>Ez-aktiboak</option>
                </select>

                <button type="button" class="btn btn-secondary btn-sm" onclick="clearFilters()">
                    🔄 Garbitu
                </button>
            </div>

            <?php if (in_array($user['role'] ?? '', ['ADMIN', 'RRHH_MGR'])): ?>
                <a href="/employees/new" class="btn btn-primary">
                    ➕ Langile Berria
                </a>
            <?php endif; ?>

            <button type="button" class="btn btn-secondary" onclick="exportData()">
                📥 Esportatu CSV
            </button>
        </div>

        <div class="table-container">
            <?php if (empty($employees)): ?>
                <div class="empty-state">
                    <div class="empty-state-icon">👥</div>
                    <h3>Ez da langilerik aurkitu</h3>
                    <p>Bilaketa-irizpideak aldatu edo langile berri bat gehitu</p>
                </div>
            <?php else: ?>
                <table>
                    <thead>
                        <tr>
                            <th>Izena</th>
                            <th>NAN</th>
                            <th>Email</th>
                            <th>Saila</th>
                            <th>Rola</th>
                            <th>Egoera</th>
                            <th>Ekintzak</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($employees as $employee): ?>
                            <tr>
                                <td>
                                    <strong><?= htmlspecialchars($employee['first_name'] . ' ' . $employee['last_name']) ?></strong>
                                </td>
                                <td><?= htmlspecialchars($employee['nif'] ?? '-') ?></td>
                                <td><?= htmlspecialchars($employee['email']) ?></td>
                                <td><?= htmlspecialchars($employee['department_name'] ?? '-') ?></td>
                                <td><?= htmlspecialchars($employee['role'] ?? 'EMPLEADO') ?></td>
                                <td>
                                    <span class="status-badge <?= $employee['active'] ? 'status-active' : 'status-inactive' ?>">
                                        <?= $employee['active'] ? 'Aktiboa' : 'Ez-aktiboa' ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="actions">
                                        <a href="/employees/<?= $employee['id'] ?>" class="btn btn-sm btn-view">
                                            👁️ Ikusi
                                        </a>
                                        <?php if (in_array($user['role'] ?? '', ['ADMIN', 'RRHH_MGR'])): ?>
                                            <a href="/employees/<?= $employee['id'] ?>/edit" class="btn btn-sm btn-edit">
                                                ✏️ Editatu
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <?php if ($pagination['total_pages'] > 1): ?>
                    <div class="pagination">
                        <button 
                            onclick="goToPage(<?= max(1, $pagination['current_page'] - 1) ?>)"
                            <?= $pagination['current_page'] <= 1 ? 'disabled' : '' ?>>
                            ← Aurrekoa
                        </button>

                        <?php for ($i = 1; $i <= $pagination['total_pages']; $i++): ?>
                            <?php if ($i == 1 || $i == $pagination['total_pages'] || abs($i - $pagination['current_page']) <= 2): ?>
                                <button 
                                    onclick="goToPage(<?= $i ?>)"
                                    class="<?= $i == $pagination['current_page'] ? 'active' : '' ?>">
                                    <?= $i ?>
                                </button>
                            <?php elseif (abs($i - $pagination['current_page']) == 3): ?>
                                <span class="pagination-info">...</span>
                            <?php endif; ?>
                        <?php endfor; ?>

                        <button 
                            onclick="goToPage(<?= min($pagination['total_pages'], $pagination['current_page'] + 1) ?>)"
                            <?= $pagination['current_page'] >= $pagination['total_pages'] ? 'disabled' : '' ?>>
                            Hurrengoa →
                        </button>

                        <span class="pagination-info">
                            <?= $pagination['current_page'] ?> / <?= $pagination['total_pages'] ?> orriak
                        </span>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>

    <script>
        // Search with debounce
        let searchTimeout;
        document.getElementById('searchInput').addEventListener('input', function(e) {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                applyFilters();
            }, 500);
        });

        // Department filter
        document.getElementById('departmentFilter').addEventListener('change', applyFilters);

        // Status filter
        document.getElementById('statusFilter').addEventListener('change', applyFilters);

        function applyFilters() {
            const search = document.getElementById('searchInput').value;
            const department = document.getElementById('departmentFilter').value;
            const status = document.getElementById('statusFilter').value;

            const params = new URLSearchParams();
            if (search) params.set('search', search);
            if (department) params.set('department', department);
            if (status) params.set('active', status);

            window.location.href = '/employees?' + params.toString();
        }

        function clearFilters() {
            window.location.href = '/employees';
        }

        function goToPage(page) {
            const params = new URLSearchParams(window.location.search);
            params.set('page', page);
            window.location.href = '/employees?' + params.toString();
        }

        function exportData() {
            const params = new URLSearchParams(window.location.search);
            params.set('export', 'csv');
            window.location.href = '/employees/export?' + params.toString();
        }
    </script>
</body>
</html>
