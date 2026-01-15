<?php
/**
 * Vacation Dashboard View
 * 
 * Shows employee vacation balance and request history
 */

declare(strict_types=1);

// This would be loaded by the router with proper authentication
$pageTitle = 'Nire Oporrak / Mis Vacaciones';
$currentPage = 'vacations';

require_once __DIR__ . '/../layouts/header.php';
?>

<div class="container">
    <div class="page-header">
        <h1><?= htmlspecialchars($pageTitle) ?></h1>
        <a href="/vacations/new" class="btn btn-primary">
            + Opor Eskaera Berria / Nueva Solicitud
        </a>
    </div>

    <div id="error-message" class="error-message" style="display: none;"></div>
    <div id="loading-spinner" class="loading-spinner">
        Kargatzen... / Cargando...
    </div>

    <!-- Balance Card -->
    <div id="balance-card" class="balance-card" style="display: none;">
        <h2>Opor Balantzea <span id="balance-year"></span> / Balance Vacaciones <span id="balance-year-2"></span></h2>
        <div class="balance-grid">
            <div class="balance-item">
                <div class="balance-label">Guztira egun / Total días</div>
                <div class="balance-value" id="total-days">0</div>
            </div>
            <div class="balance-item">
                <div class="balance-label">Erabilita / Usados</div>
                <div class="balance-value" id="used-days">0</div>
            </div>
            <div class="balance-item">
                <div class="balance-label">Zain / Pendientes</div>
                <div class="balance-value" id="pending-days">0</div>
            </div>
            <div class="balance-item">
                <div class="balance-label">Eskuragarri / Disponibles</div>
                <div class="balance-value available" id="available-days">0</div>
            </div>
        </div>
    </div>

    <!-- Requests Section -->
    <div class="requests-section">
        <h2>Nire Eskaerak / Mis Solicitudes</h2>
        <div id="requests-container">
            <div class="empty-state">
                Ez dago eskaera aterikorik / No hay solicitudes registradas
            </div>
        </div>
    </div>
</div>

<style>
.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
}

.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
}

.page-header h1 {
    font-size: 28px;
    color: #2c3e50;
    margin: 0;
}

.btn {
    padding: 12px 24px;
    border: none;
    border-radius: 6px;
    font-size: 16px;
    cursor: pointer;
    text-decoration: none;
    display: inline-block;
    transition: background-color 0.3s;
}

.btn-primary {
    background-color: #3498db;
    color: white;
}

.btn-primary:hover {
    background-color: #2980b9;
}

.balance-card {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 30px;
    border-radius: 12px;
    margin-bottom: 30px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.balance-card h2 {
    margin: 0 0 20px 0;
    font-size: 20px;
}

.balance-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
}

.balance-item {
    background: rgba(255, 255, 255, 0.2);
    padding: 20px;
    border-radius: 8px;
    text-align: center;
}

.balance-label {
    font-size: 14px;
    opacity: 0.9;
    margin-bottom: 8px;
}

.balance-value {
    font-size: 32px;
    font-weight: bold;
}

.balance-value.available {
    color: #2ecc71;
}

.requests-section {
    background: white;
    border-radius: 12px;
    padding: 24px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.requests-section h2 {
    font-size: 20px;
    color: #2c3e50;
    margin-bottom: 20px;
}

.requests-table {
    width: 100%;
    border-collapse: collapse;
}

.requests-table th {
    text-align: left;
    padding: 12px;
    border-bottom: 2px solid #ecf0f1;
    color: #7f8c8d;
    font-weight: 600;
    font-size: 14px;
}

.requests-table td {
    padding: 12px;
    border-bottom: 1px solid #ecf0f1;
}

.status-badge {
    display: inline-block;
    padding: 4px 12px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: 600;
}

.status-pending { background: #fff3cd; color: #856404; }
.status-manager-approved { background: #d1ecf1; color: #0c5460; }
.status-approved { background: #d4edda; color: #155724; }
.status-rejected { background: #f8d7da; color: #721c24; }
.status-cancelled { background: #e2e3e5; color: #383d41; }

.action-btn {
    background: none;
    border: none;
    color: #3498db;
    cursor: pointer;
    font-size: 14px;
    text-decoration: underline;
    padding: 4px 8px;
}

.action-btn:hover {
    color: #2980b9;
}

.empty-state {
    text-align: center;
    padding: 40px;
    color: #7f8c8d;
}

.loading-spinner {
    text-align: center;
    padding: 40px;
    color: #7f8c8d;
}

.error-message {
    background-color: #f8d7da;
    color: #721c24;
    padding: 12px;
    border-radius: 6px;
    margin-bottom: 20px;
}
</style>

<script>
// API helper
async function apiCall(url, options = {}) {
    const token = localStorage.getItem('auth_token');
    const response = await fetch('/api' + url, {
        ...options,
        headers: {
            'Content-Type': 'application/json',
            'Authorization': token ? `Bearer ${token}` : '',
            ...options.headers
        }
    });
    
    if (!response.ok) {
        const error = await response.json();
        throw new Error(error.error || 'Request failed');
    }
    
    return response.json();
}

function showError(message) {
    const errorDiv = document.getElementById('error-message');
    errorDiv.textContent = message;
    errorDiv.style.display = 'block';
}

function hideError() {
    document.getElementById('error-message').style.display = 'none';
}

function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString('eu-ES', {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
    });
}

function getStatusText(status) {
    const statusMap = {
        'PENDING': 'Zain / Pendiente',
        'MANAGER_APPROVED': 'Arduradunak onartua / Aprobado por jefe',
        'APPROVED': 'Onartua / Aprobada',
        'REJECTED': 'Ukatua / Rechazada',
        'CANCELLED': 'Ezeztatua / Cancelada'
    };
    return statusMap[status] || status;
}

function getStatusClass(status) {
    return 'status-' + status.toLowerCase().replace('_', '-');
}

async function cancelRequest(requestId) {
    if (!confirm('Ziur zaude eskaera ezeztatu nahi duzula? / ¿Seguro que deseas cancelar la solicitud?')) {
        return;
    }

    try {
        await apiCall(`/vacations/requests/${requestId}/cancel`, { method: 'POST' });
        loadData(); // Reload
    } catch (error) {
        alert(error.message || 'Error ezeztatzean / Error al cancelar');
    }
}

function renderRequests(requests) {
    const container = document.getElementById('requests-container');
    
    if (requests.length === 0) {
        container.innerHTML = '<div class="empty-state">Ez dago eskaera aterikorik / No hay solicitudes registradas</div>';
        return;
    }

    let html = `
        <table class="requests-table">
            <thead>
                <tr>
                    <th>Hasiera / Inicio</th>
                    <th>Amaiera / Fin</th>
                    <th>Egunak / Días</th>
                    <th>Egoera / Estado</th>
                    <th>Eskaera data / Fecha solicitud</th>
                    <th>Ekintzak / Acciones</th>
                </tr>
            </thead>
            <tbody>
    `;

    requests.forEach(request => {
        html += `
            <tr>
                <td>${formatDate(request.start_date)}</td>
                <td>${formatDate(request.end_date)}</td>
                <td>${request.total_days}</td>
                <td>
                    <span class="status-badge ${getStatusClass(request.status)}">
                        ${getStatusText(request.status)}
                    </span>
                </td>
                <td>${formatDate(request.request_date)}</td>
                <td>
                    ${request.status === 'PENDING' ? 
                        `<button class="action-btn" onclick="cancelRequest(${request.id})">Ezeztatu / Cancelar</button>` 
                        : ''}
                    <a href="/vacations/${request.id}" class="action-btn">Ikusi / Ver</a>
                </td>
            </tr>
        `;
    });

    html += '</tbody></table>';
    container.innerHTML = html;
}

async function loadData() {
    try {
        hideError();
        document.getElementById('loading-spinner').style.display = 'block';

        const [balanceData, requestsData] = await Promise.all([
            apiCall('/vacations/balance'),
            apiCall('/vacations/requests')
        ]);

        // Render balance
        document.getElementById('balance-year').textContent = balanceData.year;
        document.getElementById('balance-year-2').textContent = balanceData.year;
        document.getElementById('total-days').textContent = balanceData.total_days;
        document.getElementById('used-days').textContent = balanceData.used_days;
        document.getElementById('pending-days').textContent = balanceData.pending_days;
        document.getElementById('available-days').textContent = balanceData.available_days;
        document.getElementById('balance-card').style.display = 'block';

        // Render requests
        renderRequests(requestsData.requests || []);

        document.getElementById('loading-spinner').style.display = 'none';
    } catch (error) {
        document.getElementById('loading-spinner').style.display = 'none';
        showError(error.message || 'Errorea datuak kargatzean / Error cargando datos');
    }
}

// Load data on page load
document.addEventListener('DOMContentLoaded', loadData);
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
