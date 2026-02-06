<?php require dirname(__DIR__) . '/layouts/header.php'; ?>

<div class="page-header">
    <div>
        <h1 class="page-title">
            <i class="fas fa-cloud-upload-alt"></i>
            Dokumentua igo / Subir documento
        </h1>
        <p class="page-subtitle">
            Dokumentu berria igo sistemara / Subir nuevo documento al sistema
        </p>
    </div>
    <div class="page-actions">
        <a href="/documents" class="btn-industrial btn-secondary-industrial">
            <i class="fas fa-arrow-left"></i>
            Atzera / Volver
        </a>
    </div>
</div>

<div class="widget-card-industrial">
    <div class="widget-header">
        <h3 class="widget-title">
            <i class="fas fa-file-upload"></i>
            Dokumentuaren datuak / Datos del documento
        </h3>
    </div>
    <div class="widget-body">
        <form method="POST" action="/documents/upload" enctype="multipart/form-data" class="form-industrial">
            <!-- Document Type -->
            <div class="form-row">
                <div class="form-group">
                    <label for="type" class="form-label required">Mota / Tipo</label>
                    <select name="type" id="type" class="form-control" required>
                        <option value="">Hautatu mota / Selecciona tipo...</option>
                        <?php foreach ($categories as $key => $names): ?>
                            <option value="<?= $key ?>">
                                <?= $names['eu'] ?> / <?= $names['es'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <!-- Employee or Public -->
            <div class="form-group">
                <label class="form-label">Norentzat / Para quién</label>
                <div style="display: flex; gap: var(--space-4); margin-top: var(--space-3);">
                    <label style="display: flex; align-items: center; gap: var(--space-2); cursor: pointer;">
                        <input type="radio" name="is_public" value="0" checked onchange="toggleEmployeeSelect(false)">
                        <span>Langile zehatza / Empleado específico</span>
                    </label>
                    <label style="display: flex; align-items: center; gap: var(--space-2); cursor: pointer;">
                        <input type="radio" name="is_public" value="1" onchange="toggleEmployeeSelect(true)">
                        <span>Dokumentu publikoa / Documento público</span>
                    </label>
                </div>
            </div>

            <!-- Employee Selection -->
            <div class="form-group" id="employee-select">
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

            <!-- File Upload -->
            <div class="form-group">
                <label for="file" class="form-label required">Fitxategia / Archivo</label>
                <div id="drop-zone" style="border: 2px dashed var(--border-color); border-radius: var(--radius-lg); padding: 40px; text-align: center; transition: all 0.3s; cursor: pointer; background: var(--bg-elevated);">
                    <input type="file" name="file" id="file" required style="display: none;" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                    <div id="drop-zone-content">
                        <i class="fas fa-cloud-upload-alt" style="font-size: 3rem; color: var(--primary); margin-bottom: var(--space-3);"></i>
                        <div style="font-size: var(--text-lg); font-weight: 600; margin-bottom: var(--space-2);">
                            Klik egin edo arrastatu fitxategia hona
                        </div>
                        <div style="font-size: var(--text-sm); color: var(--text-tertiary);">
                            Haz clic o arrastra el archivo aquí
                        </div>
                        <div style="font-size: var(--text-xs); color: var(--text-tertiary); margin-top: var(--space-3);">
                            PDF, DOC, DOCX, JPG, PNG (Max: 10MB)
                        </div>
                    </div>
                    <div id="file-preview" style="display: none;">
                        <i class="fas fa-file" style="font-size: 2rem; color: var(--success); margin-bottom: var(--space-2);"></i>
                        <div id="file-name" style="font-size: var(--text-lg); font-weight: 600; margin-bottom: var(--space-2);"></div>
                        <div id="file-size" style="font-size: var(--text-sm); color: var(--text-tertiary);"></div>
                        <button type="button" onclick="clearFile()" class="btn-ghost-industrial mt-3">
                            <i class="fas fa-times"></i>
                            Kendu / Eliminar
                        </button>
                    </div>
                </div>
            </div>

            <!-- Description -->
            <div class="form-group">
                <label for="description" class="form-label">Deskribapena / Descripción</label>
                <textarea name="description" id="description" class="form-control" rows="4" placeholder="Gehitu deskribapena hemen... / Añade descripción aquí..."></textarea>
                <small style="color: var(--text-tertiary); font-size: var(--text-xs); display: block; margin-top: var(--space-2);">
                    <i class="fas fa-info-circle"></i>
                    Aukerakoa / Opcional
                </small>
            </div>

            <!-- Actions -->
            <div class="form-actions">
                <button type="submit" class="btn-industrial btn-primary-industrial" id="submit-btn" disabled>
                    <i class="fas fa-upload"></i>
                    Igo dokumentua / Subir documento
                </button>
                <a href="/documents" class="btn-industrial btn-secondary-industrial">
                    <i class="fas fa-times"></i>
                    Utzi / Cancelar
                </a>
            </div>
        </form>
    </div>
</div>

<script>
// Toggle employee select based on public/private selection
function toggleEmployeeSelect(isPublic) {
    const employeeSelect = document.getElementById('employee-select');
    const employeeInput = document.getElementById('employee_id');
    
    if (isPublic) {
        employeeSelect.style.display = 'none';
        employeeInput.required = false;
        employeeInput.value = '';
    } else {
        employeeSelect.style.display = 'block';
        employeeInput.required = true;
    }
}

// File upload handling
const dropZone = document.getElementById('drop-zone');
const fileInput = document.getElementById('file');
const dropZoneContent = document.getElementById('drop-zone-content');
const filePreview = document.getElementById('file-preview');
const submitBtn = document.getElementById('submit-btn');

// Click to select file
dropZone.addEventListener('click', () => {
    fileInput.click();
});

// Drag and drop
dropZone.addEventListener('dragover', (e) => {
    e.preventDefault();
    dropZone.style.borderColor = 'var(--primary)';
    dropZone.style.background = 'var(--primary-light)';
});

dropZone.addEventListener('dragleave', () => {
    dropZone.style.borderColor = 'var(--border-color)';
    dropZone.style.background = 'var(--bg-elevated)';
});

dropZone.addEventListener('drop', (e) => {
    e.preventDefault();
    dropZone.style.borderColor = 'var(--border-color)';
    dropZone.style.background = 'var(--bg-elevated)';
    
    const files = e.dataTransfer.files;
    if (files.length > 0) {
        fileInput.files = files;
        showFilePreview(files[0]);
    }
});

// File input change
fileInput.addEventListener('change', (e) => {
    if (e.target.files.length > 0) {
        showFilePreview(e.target.files[0]);
    }
});

// Show file preview
function showFilePreview(file) {
    // Validate file size
    const maxSize = 10485760; // 10MB
    if (file.size > maxSize) {
        alert('El archivo es demasiado grande. Tamaño máximo: 10MB\nFitxategia handiegia da. Gehienezko tamaina: 10MB');
        clearFile();
        return;
    }
    
    // Validate file type
    const allowedTypes = ['pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png'];
    const extension = file.name.split('.').pop().toLowerCase();
    if (!allowedTypes.includes(extension)) {
        alert('Tipo de archivo no permitido\nFitxategi mota ez dago baimenduta');
        clearFile();
        return;
    }
    
    dropZoneContent.style.display = 'none';
    filePreview.style.display = 'block';
    
    // Update file preview
    document.getElementById('file-name').textContent = file.name;
    
    // Format file size
    const size = file.size;
    const units = ['B', 'KB', 'MB', 'GB'];
    let unit = 0;
    let formattedSize = size;
    while (formattedSize >= 1024 && unit < units.length - 1) {
        formattedSize /= 1024;
        unit++;
    }
    document.getElementById('file-size').textContent = formattedSize.toFixed(2) + ' ' + units[unit];
    
    // Update icon based on file type
    const icon = filePreview.querySelector('.fa-file');
    icon.className = 'fas fa-file';
    let iconColor = 'var(--success)';
    
    if (extension === 'pdf') {
        icon.className = 'fas fa-file-pdf';
        iconColor = '#DC2626';
    } else if (['doc', 'docx'].includes(extension)) {
        icon.className = 'fas fa-file-word';
        iconColor = '#0EA5E9';
    } else if (['jpg', 'jpeg', 'png'].includes(extension)) {
        icon.className = 'fas fa-file-image';
        iconColor = '#059669';
    }
    icon.style.color = iconColor;
    
    // Enable submit button
    submitBtn.disabled = false;
}

// Clear file
function clearFile() {
    fileInput.value = '';
    dropZoneContent.style.display = 'block';
    filePreview.style.display = 'none';
    submitBtn.disabled = true;
}

// Form validation
document.querySelector('.form-industrial').addEventListener('submit', (e) => {
    if (!fileInput.files || fileInput.files.length === 0) {
        e.preventDefault();
        alert('Fitxategia hautatu / Selecciona un archivo');
        return false;
    }
});
</script>

<?php require dirname(__DIR__) . '/layouts/footer.php'; ?>
