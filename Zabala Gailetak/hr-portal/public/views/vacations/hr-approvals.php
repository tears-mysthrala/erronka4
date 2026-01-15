<?php
/**
 * HR Approvals View
 * 
 * Shows vacation requests pending HR final approval
 */

declare(strict_types=1);

$pageTitle = 'GIBH Onarpenak / Aprobaciones RRHH';
$currentPage = 'hr-approvals';

require_once __DIR__ . '/../layouts/header.php';
?>

<div class="container">
    <div class="page-header">
        <h1><?= htmlspecialchars($pageTitle) ?></h1>
        <div class="header-subtitle">Azken onarpena / Aprobación final</div>
    </div>

    <div id="error-message" class="error-message" style="display: none;"></div>
    <div id="success-message" class="success-message" style="display: none;"></div>
    <div id="loading-spinner" class="loading-spinner">Kargatzen... / Cargando...</div>

    <div id="pending-requests" class="section-card" style="display: none;">
        <h2>Arduradunak onartutako eskaerak / Solicitudes aprobadas por jefes</h2>
        <div id="requests-container">
            <div class="empty-state">Ez dago eskaera zaindunik / No hay solicitudes pendientes</div>
        </div>
    </div>
</div>

<!-- Modal for final approval/rejection -->
<div id="action-modal" class="modal" style="display: none;">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <h2 id="modal-title"></h2>
        <div id="modal-body"></div>
        
        <div class="form-group">
            <label for="action-notes">GIBH Oharrak / Notas RRHH</label>
            <textarea id="action-notes" rows="4" placeholder="Aukerakoa / Opcional"></textarea>
        </div>

        <div class="button-group">
            <button class="btn btn-secondary" onclick="closeModal()">Ezeztatu / Cancelar</button>
            <button class="btn btn-primary" id="confirm-action-btn" onclick="confirmAction()">Berretsi / Confirmar</button>
        </div>
    </div>
</div>

<style>
.container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 20px;
}

.page-header {
    margin-bottom: 30px;
}

.page-header h1 {
    font-size: 28px;
    color: #2c3e50;
    margin-bottom: 8px;
}

.header-subtitle {
    font-size: 16px;
    color: #7f8c8d;
}

.section-card {
    background: white;
    border-radius: 12px;
    padding: 24px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.section-card h2 {
    font-size: 20px;
    color: #2c3e50;
    margin-bottom: 20px;
}

.request-card {
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    padding: 20px;
    margin-bottom: 16px;
    background: white;
}

.request-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 16px;
}

.employee-info {
    flex: 1;
}

.employee-name {
    font-size: 18px;
    font-weight: 600;
    color: #2c3e50;
}

.employee-dept {
    font-size: 14px;
    color: #7f8c8d;
    margin-top: 4px;
}

.approval-status {
    background: #d1ecf1;
    color: #0c5460;
    padding: 8px 16px;
    border-radius: 6px;
    font-size: 14px;
    font-weight: 600;
}

.request-dates {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 16px;
    margin-bottom: 16px;
}

.date-box {
    background: #f8f9fa;
    padding: 12px;
    border-radius: 6px;
}

.date-label {
    font-size: 12px;
    color: #7f8c8d;
    margin-bottom: 4px;
}

.date-value {
    font-size: 16px;
    font-weight: 600;
    color: #2c3e50;
}

.approval-info {
    background: #e8f5e9;
    border-left: 4px solid #4caf50;
    padding: 12px;
    border-radius: 4px;
    margin-bottom: 16px;
}

.approval-info-title {
    font-size: 12px;
    color: #2e7d32;
    font-weight: 600;
    margin-bottom: 4px;
}

.approval-info-text {
    font-size: 14px;
    color: #2e7d32;
}

.request-notes {
    background: #fff9e6;
    padding: 12px;
    border-radius: 6px;
    margin-bottom: 12px;
}

.notes-label {
    font-size: 12px;
    color: #856404;
    font-weight: 600;
    margin-bottom: 4px;
}

.notes-text {
    font-size: 14px;
    color: #856404;
}

.action-buttons {
    display: flex;
    gap: 12px;
    justify-content: flex-end;
}

.btn {
    padding: 10px 20px;
    border: none;
    border-radius: 6px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
}

.btn-approve {
    background-color: #27ae60;
    color: white;
}

.btn-approve:hover {
    background-color: #229954;
}

.btn-reject {
    background-color: #e74c3c;
    color: white;
}

.btn-reject:hover {
    background-color: #c0392b;
}

.btn-primary {
    background-color: #3498db;
    color: white;
}

.btn-primary:hover {
    background-color: #2980b9;
}

.btn-secondary {
    background-color: #ecf0f1;
    color: #7f8c8d;
}

.btn-secondary:hover {
    background-color: #d5dbdb;
}

.modal {
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
}

.modal-content {
    background-color: white;
    margin: 10% auto;
    padding: 30px;
    border-radius: 12px;
    width: 90%;
    max-width: 500px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.close {
    color: #aaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
    cursor: pointer;
}

.close:hover {
    color: #000;
}

.form-group {
    margin: 20px 0;
}

.form-group label {
    display: block;
    font-weight: 600;
    margin-bottom: 8px;
    color: #2c3e50;
}

.form-group textarea {
    width: 100%;
    padding: 12px;
    border: 1px solid #dcdcdc;
    border-radius: 6px;
    font-size: 14px;
    font-family: inherit;
    resize: vertical;
    box-sizing: border-box;
}

.button-group {
    display: flex;
    gap: 12px;
    margin-top: 20px;
}

.button-group .btn {
    flex: 1;
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

.success-message {
    background-color: #d4edda;
    color: #155724;
    padding: 12px;
    border-radius: 6px;
    margin-bottom: 20px;
}
</style>

<script>
let currentRequestId = null;
let currentAction = null;

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
    document.getElementById('success-message').style.display = 'none';
}

function showSuccess(message) {
    const successDiv = document.getElementById('success-message');
    successDiv.textContent = message;
    successDiv.style.display = 'block';
    document.getElementById('error-message').style.display = 'none';
    
    setTimeout(() => {
        successDiv.style.display = 'none';
    }, 5000);
}

function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString('eu-ES', {
        weekday: 'short',
        year: 'numeric',
        month: 'short',
        day: 'numeric'
    });
}

function openApproveModal(requestId, employeeName) {
    currentRequestId = requestId;
    currentAction = 'approve';
    
    document.getElementById('modal-title').textContent = 'Azken onarpena / Aprobación final';
    document.getElementById('modal-body').innerHTML = `
        <p><strong>${employeeName}</strong>-ren eskaera azken onarpena eman / Dar aprobación final</p>
        <p style="color: #27ae60; font-weight: 600; margin-top: 12px;">
            Honek eskaera guztiz onartzen du eta egun deskontatuko dira.
        </p>
        <p style="color: #27ae60; margin-top: 4px;">
            Esto aprobará completamente la solicitud y se descontarán los días.
        </p>
    `;
    document.getElementById('action-notes').value = '';
    document.getElementById('confirm-action-btn').className = 'btn btn-approve';
    document.getElementById('action-modal').style.display = 'block';
}

function openRejectModal(requestId, employeeName) {
    currentRequestId = requestId;
    currentAction = 'reject';
    
    document.getElementById('modal-title').textContent = 'Eskaera ukatu / Rechazar solicitud';
    document.getElementById('modal-body').innerHTML = `
        <p><strong>${employeeName}</strong>-ren eskaera ukatu</p>
        <p style="color: #e74c3c; font-weight: 600; margin-top: 12px;">
            Ukatzeko arrazoia beharrezkoa da / El motivo del rechazo es obligatorio
        </p>
    `;
    document.getElementById('action-notes').value = '';
    document.getElementById('action-notes').required = true;
    document.getElementById('confirm-action-btn').className = 'btn btn-reject';
    document.getElementById('action-modal').style.display = 'block';
}

function closeModal() {
    document.getElementById('action-modal').style.display = 'none';
    currentRequestId = null;
    currentAction = null;
}

async function confirmAction() {
    const notes = document.getElementById('action-notes').value;
    
    if (currentAction === 'reject' && !notes.trim()) {
        alert('Ukatzeko arrazoia beharrezkoa da / El motivo del rechazo es obligatorio');
        return;
    }

    try {
        const btn = document.getElementById('confirm-action-btn');
        btn.disabled = true;
        btn.textContent = 'Prozesatzen... / Procesando...';

        if (currentAction === 'approve') {
            await apiCall(`/vacations/requests/${currentRequestId}/approve-hr`, {
                method: 'POST',
                body: JSON.stringify({ notes: notes || null })
            });
            showSuccess('✓ Eskaera guztiz onartua! / ¡Solicitud aprobada completamente!');
        } else if (currentAction === 'reject') {
            await apiCall(`/vacations/requests/${currentRequestId}/reject`, {
                method: 'POST',
                body: JSON.stringify({ reason: notes })
            });
            showSuccess('✓ Eskaera ukatua / Solicitud rechazada');
        }

        closeModal();
        loadRequests();
    } catch (error) {
        alert(error.message || 'Errorea / Error');
        document.getElementById('confirm-action-btn').disabled = false;
        document.getElementById('confirm-action-btn').textContent = 'Berretsi / Confirmar';
    }
}

function renderRequests(requests) {
    const container = document.getElementById('requests-container');
    
    if (requests.length === 0) {
        container.innerHTML = '<div class="empty-state">Ez dago eskaera zaindunik / No hay solicitudes pendientes</div>';
        return;
    }

    let html = '';
    requests.forEach(request => {
        const employee = request.employee || {};
        const employeeName = `${employee.first_name || ''} ${employee.last_name || ''}`.trim() || 'Unknown';
        const department = employee.department || 'N/A';

        html += `
            <div class="request-card">
                <div class="request-header">
                    <div class="employee-info">
                        <div class="employee-name">${employeeName}</div>
                        <div class="employee-dept">Saila / Departamento: ${department}</div>
                    </div>
                    <div class="approval-status">✓ Arduradunak onartua / Jefe aprobó</div>
                </div>

                <div class="request-dates">
                    <div class="date-box">
                        <div class="date-label">Hasiera / Inicio</div>
                        <div class="date-value">${formatDate(request.start_date)}</div>
                    </div>
                    <div class="date-box">
                        <div class="date-label">Amaiera / Fin</div>
                        <div class="date-value">${formatDate(request.end_date)}</div>
                    </div>
                    <div class="date-box">
                        <div class="date-label">Egunak / Días</div>
                        <div class="date-value">${request.total_days} egun</div>
                    </div>
                    <div class="date-box">
                        <div class="date-label">Eskaera data / Fecha solicitud</div>
                        <div class="date-value">${formatDate(request.request_date)}</div>
                    </div>
                </div>

                ${request.manager_approval_date ? `
                    <div class="approval-info">
                        <div class="approval-info-title">Arduradunak onartua / Aprobado por jefe:</div>
                        <div class="approval-info-text">
                            ${formatDate(request.manager_approval_date)}
                            ${request.manager_approval_notes ? ` - ${request.manager_approval_notes}` : ''}
                        </div>
                    </div>
                ` : ''}

                ${request.notes ? `
                    <div class="request-notes">
                        <div class="notes-label">Langilearen oharrak / Notas del empleado:</div>
                        <div class="notes-text">${request.notes}</div>
                    </div>
                ` : ''}

                <div class="action-buttons">
                    <button class="btn btn-reject" onclick="openRejectModal(${request.id}, '${employeeName}')">
                        ✗ Ukatu / Rechazar
                    </button>
                    <button class="btn btn-approve" onclick="openApproveModal(${request.id}, '${employeeName}')">
                        ✓ Azken Onarpena / Aprobación Final
                    </button>
                </div>
            </div>
        `;
    });

    container.innerHTML = html;
}

async function loadRequests() {
    try {
        document.getElementById('loading-spinner').style.display = 'block';
        document.getElementById('pending-requests').style.display = 'none';

        const data = await apiCall('/vacations/pending/hr');
        
        renderRequests(data.requests || []);
        
        document.getElementById('loading-spinner').style.display = 'none';
        document.getElementById('pending-requests').style.display = 'block';
    } catch (error) {
        document.getElementById('loading-spinner').style.display = 'none';
        showError(error.message || 'Errorea datuak kargatzean / Error cargando datos');
    }
}

document.addEventListener('DOMContentLoaded', loadRequests);

window.onclick = function(event) {
    const modal = document.getElementById('action-modal');
    if (event.target === modal) {
        closeModal();
    }
}
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
