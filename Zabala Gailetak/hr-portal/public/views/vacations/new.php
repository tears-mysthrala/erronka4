<?php
/**
 * New Vacation Request Form
 */

declare(strict_types=1);

$pageTitle = 'Opor Eskaera Berria / Nueva Solicitud de Vacaciones';
$currentPage = 'vacations';

require_once __DIR__ . '/../layouts/header.php';
?>

<div class="container">
    <div class="card">
        <h1><?= htmlspecialchars($pageTitle) ?></h1>

        <div id="error-message" class="error-message" style="display: none;"></div>
        <div id="success-message" class="success-message" style="display: none;"></div>

        <div class="info-box">
            <p>
                Opor eskaerak arduradunak eta gero GIBHek onartu behar dituzte. 
                Jakinarazpen bat jasoko duzu erabakia hartzen denean.
            </p>
            <p>
                Las solicitudes de vacaciones deben ser aprobadas por tu jefe de sección y después por RRHH. 
                Recibirás una notificación cuando se tome una decisión.
            </p>
        </div>

        <form id="vacation-form">
            <div class="form-group">
                <label for="start_date">Hasiera Data / Fecha Inicio *</label>
                <input 
                    type="date" 
                    id="start_date" 
                    name="start_date" 
                    required 
                    min="<?= date('Y-m-d') ?>"
                >
            </div>

            <div class="form-group">
                <label for="end_date">Amaiera Data / Fecha Fin *</label>
                <input 
                    type="date" 
                    id="end_date" 
                    name="end_date" 
                    required
                >
            </div>

            <div id="calculated-days" class="calculated-days" style="display: none;">
                <div class="days-label">Gutxi gorabeherako egun lanerako / Días laborables aproximados</div>
                <div class="days-value" id="days-value">0</div>
                <div class="days-note">(Jaiegunak kontuan hartu gabe / sin contar festivos)</div>
            </div>

            <div class="form-group">
                <label for="notes">Oharrak (aukerakoa) / Notas (opcional)</label>
                <textarea 
                    id="notes" 
                    name="notes" 
                    rows="4"
                    placeholder="Edozein informazio gehigarri... / Cualquier información adicional..."
                ></textarea>
            </div>

            <div class="button-group">
                <button type="button" class="btn btn-secondary" onclick="window.location.href='/vacations'">
                    Ezeztatu / Cancelar
                </button>
                <button type="submit" class="btn btn-primary" id="submit-btn">
                    Eskaera Bidali / Enviar Solicitud
                </button>
            </div>
        </form>
    </div>
</div>

<style>
.container {
    max-width: 800px;
    margin: 0 auto;
    padding: 20px;
}

.card {
    background: white;
    border-radius: 12px;
    padding: 30px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.card h1 {
    font-size: 24px;
    color: #2c3e50;
    margin-bottom: 30px;
}

.info-box {
    background: #e3f2fd;
    border-left: 4px solid #2196f3;
    padding: 16px;
    border-radius: 4px;
    margin: 20px 0;
}

.info-box p {
    margin: 0;
    color: #1565c0;
    font-size: 14px;
}

.info-box p + p {
    margin-top: 8px;
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    font-size: 14px;
    font-weight: 600;
    color: #2c3e50;
    margin-bottom: 8px;
}

.form-group input,
.form-group textarea {
    width: 100%;
    padding: 12px;
    border: 1px solid #dcdcdc;
    border-radius: 6px;
    font-size: 16px;
    font-family: inherit;
    box-sizing: border-box;
    transition: border-color 0.3s;
}

.form-group input:focus,
.form-group textarea:focus {
    outline: none;
    border-color: #3498db;
}

.form-group textarea {
    resize: vertical;
}

.calculated-days {
    background: #f5f5f5;
    padding: 16px;
    border-radius: 6px;
    text-align: center;
    margin: 20px 0;
}

.days-label {
    font-size: 14px;
    color: #7f8c8d;
    margin-bottom: 4px;
}

.days-value {
    font-size: 32px;
    font-weight: bold;
    color: #2c3e50;
}

.days-note {
    font-size: 12px;
    color: #7f8c8d;
    margin-top: 8px;
}

.button-group {
    display: flex;
    gap: 12px;
    margin-top: 20px;
}

.btn {
    flex: 1;
    padding: 14px 24px;
    border: none;
    border-radius: 6px;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
}

.btn-primary {
    background-color: #3498db;
    color: white;
}

.btn-primary:hover:not(:disabled) {
    background-color: #2980b9;
}

.btn-secondary {
    background-color: #ecf0f1;
    color: #7f8c8d;
}

.btn-secondary:hover {
    background-color: #d5dbdb;
}

.btn:disabled {
    background-color: #95a5a6;
    cursor: not-allowed;
    opacity: 0.6;
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
}

function calculateBusinessDays(startDate, endDate) {
    const start = new Date(startDate);
    const end = new Date(endDate);
    
    if (end < start) {
        return null;
    }

    let days = 0;
    const current = new Date(start);

    while (current <= end) {
        const dayOfWeek = current.getDay();
        // Skip weekends (0 = Sunday, 6 = Saturday)
        if (dayOfWeek !== 0 && dayOfWeek !== 6) {
            days++;
        }
        current.setDate(current.getDate() + 1);
    }

    return days;
}

// Update calculated days when dates change
document.getElementById('start_date').addEventListener('change', updateCalculatedDays);
document.getElementById('end_date').addEventListener('change', updateCalculatedDays);

function updateCalculatedDays() {
    const startDate = document.getElementById('start_date').value;
    const endDate = document.getElementById('end_date').value;
    
    if (startDate && endDate) {
        const days = calculateBusinessDays(startDate, endDate);
        if (days !== null && days > 0) {
            document.getElementById('days-value').textContent = days;
            document.getElementById('calculated-days').style.display = 'block';
        } else {
            document.getElementById('calculated-days').style.display = 'none';
        }
    } else {
        document.getElementById('calculated-days').style.display = 'none';
    }

    // Update min date for end_date
    if (startDate) {
        document.getElementById('end_date').min = startDate;
    }
}

document.getElementById('vacation-form').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const submitBtn = document.getElementById('submit-btn');
    const startDate = document.getElementById('start_date').value;
    const endDate = document.getElementById('end_date').value;
    const notes = document.getElementById('notes').value;

    // Validate dates
    if (!startDate || !endDate) {
        showError('Hasiera eta amaiera datak beharrezkoak dira / Fechas de inicio y fin requeridas');
        return;
    }

    const start = new Date(startDate);
    const end = new Date(endDate);
    const today = new Date();
    today.setHours(0, 0, 0, 0);

    if (start < today) {
        showError('Hasiera data ezin da iraganekoa izan / La fecha de inicio no puede ser del pasado');
        return;
    }

    if (end < start) {
        showError('Amaiera data hasiera data baino beranduagoa izan behar da / La fecha de fin debe ser posterior al inicio');
        return;
    }

    try {
        submitBtn.disabled = true;
        submitBtn.textContent = 'Bidaltzen... / Enviando...';

        await apiCall('/vacations/requests', {
            method: 'POST',
            body: JSON.stringify({
                start_date: startDate,
                end_date: endDate,
                notes: notes || null
            })
        });

        showSuccess('✓ Eskaera ondo sortu da! Birbideratzen... / ¡Solicitud creada correctamente! Redirigiendo...');
        
        setTimeout(() => {
            window.location.href = '/vacations';
        }, 2000);
    } catch (error) {
        showError(error.message || 'Errorea eskaera sortzerakoan / Error al crear la solicitud');
        submitBtn.disabled = false;
        submitBtn.textContent = 'Eskaera Bidali / Enviar Solicitud';
    }
});
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
