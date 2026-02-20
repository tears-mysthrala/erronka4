<?php require dirname(__DIR__) . '/layouts/header.php'; ?>

<div class="dashboard-container">
    <!-- Page Header -->
    <div class="page-header">
        <div>
            <h1 class="page-title">
                <i class="fas fa-folder"></i>
                Dokumentuak / Documentos
            </h1>
            <p class="page-subtitle">Kontsultatu eta kudeatu zure dokumentuak / Consulta y gestiona tus documentos</p>
        </div>
        <div class="page-actions">
            <?php if ($user['role'] === 'ADMIN' || $user['role'] === 'RRHH_MGR'): ?>
                <a href="/documents/upload" class="btn-industrial btn-primary-industrial">
                    <i class="fas fa-upload"></i>
                    Igo dokumentua / Subir documento
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
            <i class="fas fa-times-circle"></i>
            <?= htmlspecialchars($_SESSION['flash_error']) ?>
        </div>
        <?php unset($_SESSION['flash_error']); ?>
    <?php endif; ?>

    <!-- Stats Summary -->
    <div class="stats-grid">
        <div class="stat-card-industrial">
            <div class="stat-icon-wrapper" style="background: linear-gradient(135deg, rgba(59, 130, 246, 0.1), rgba(59, 130, 246, 0.05));">
                <i class="fas fa-user" style="color: var(--color-blue);"></i>
            </div>
            <div class="stat-details">
                <div class="stat-label">Nireak / Míos</div>
                <div class="stat-value"><?= count($personal_documents) ?></div>
                <div class="stat-trend stat-trend-neutral">
                    <i class="fas fa-file"></i>
                    Pertsonalak / Personales
                </div>
            </div>
        </div>
        <div class="stat-card-industrial">
            <div class="stat-icon-wrapper" style="background: linear-gradient(135deg, rgba(34, 197, 94, 0.1), rgba(34, 197, 94, 0.05));">
                <i class="fas fa-globe" style="color: var(--color-green);"></i>
            </div>
            <div class="stat-details">
                <div class="stat-label">Publikoak / Públicos</div>
                <div class="stat-value"><?= count($public_documents) ?></div>
                <div class="stat-trend stat-trend-positive">
                    <i class="fas fa-share-alt"></i>
                    Partekatuak / Compartidos
                </div>
            </div>
        </div>
        <div class="stat-card-industrial">
            <div class="stat-icon-wrapper" style="background: linear-gradient(135deg, rgba(234, 88, 12, 0.1), rgba(234, 88, 12, 0.05));">
                <i class="fas fa-folder-open" style="color: var(--accent);"></i>
            </div>
            <div class="stat-details">
                <div class="stat-label">Guztira / Total</div>
                <div class="stat-value"><?= count($personal_documents) + count($public_documents) ?></div>
                <div class="stat-trend stat-trend-neutral">
                    <i class="fas fa-database"></i>
                    Dokumentuak / Documentos
                </div>
            </div>
        </div>
    </div>

    <!-- Tabs -->
    <div class="widget-card-industrial" style="margin-top: var(--space-6);">
        <div class="widget-body" style="padding: 0;">
            <div style="display: flex; border-bottom: 2px solid var(--border-color);">
                <a href="/documents?tab=personal" 
                   style="flex: 1; padding: var(--space-4); text-align: center; text-decoration: none; 
                          color: <?= $active_tab === 'personal' ? 'var(--primary)' : 'var(--text-secondary)' ?>; 
                          font-weight: 600; border-bottom: 3px solid <?= $active_tab === 'personal' ? 'var(--primary)' : 'transparent' ?>;
                          transition: all 0.2s;"
                   onmouseover="this.style.background='var(--bg-hover)'" 
                   onmouseout="this.style.background='transparent'">
                    <i class="fas fa-user"></i>
                    Nire dokumentuak / Mis documentos
                    <span class="badge-industrial badge-info-industrial" style="margin-left: 8px;">
                        <?= count($personal_documents) ?>
                    </span>
                </a>
                <a href="/documents?tab=public" 
                   style="flex: 1; padding: var(--space-4); text-align: center; text-decoration: none; 
                          color: <?= $active_tab === 'public' ? 'var(--primary)' : 'var(--text-secondary)' ?>; 
                          font-weight: 600; border-bottom: 3px solid <?= $active_tab === 'public' ? 'var(--primary)' : 'transparent' ?>;
                          transition: all 0.2s;"
                   onmouseover="this.style.background='var(--bg-hover)'" 
                   onmouseout="this.style.background='transparent'">
                    <i class="fas fa-globe"></i>
                    Publikoak / Públicos
                    <span class="badge-industrial badge-success-industrial" style="margin-left: 8px;">
                        <?= count($public_documents) ?>
                    </span>
                </a>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="widget-card-industrial" style="margin-top: var(--space-4);">
        <div class="widget-header">
            <h3 class="widget-title">
                <i class="fas fa-filter"></i>
                Iragazi / Filtrar
            </h3>
        </div>
        <div class="widget-body" style="padding: var(--space-4);">
            <form method="GET" action="/documents">
                <input type="hidden" name="tab" value="<?= htmlspecialchars($active_tab) ?>">
                <div class="filters-grid">
                    <div class="filter-item">
                        <label for="category">Kategoria / Categoría</label>
                        <select name="category" id="category" class="form-input-industrial">
                            <option value="">Guztiak / Todas</option>
                            <?php foreach ($categories as $key => $names): ?>
                                <option value="<?= $key ?>" <?= ($selected_category === $key) ? 'selected' : '' ?>>
                                    <?= $names['eu'] ?> / <?= $names['es'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="filter-item" style="display: flex; align-items: flex-end; gap: var(--space-3);">
                        <button type="submit" class="btn-industrial btn-primary-industrial">
                            <i class="fas fa-filter"></i>
                            Iragazi / Filtrar
                        </button>
                        <a href="/documents?tab=<?= htmlspecialchars($active_tab) ?>" class="btn-industrial btn-secondary-industrial">
                            <i class="fas fa-times"></i>
                            Garbitu / Limpiar
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Documents List -->
    <div class="widget-card-industrial" style="margin-top: var(--space-6);">
        <div class="widget-header">
            <h3 class="widget-title">
                <i class="fas fa-list"></i>
                <?php if ($active_tab === 'personal'): ?>
                    Nire dokumentu zerrenda / Listado de mis documentos
                <?php else: ?>
                    Dokumentu publiko zerrenda / Listado de documentos públicos
                <?php endif; ?>
            </h3>
        </div>
        <div class="widget-body" style="padding: var(--space-4);">
            <?php $documents = $active_tab === 'personal' ? $personal_documents : $public_documents; ?>
            
            <?php if (!empty($documents)): ?>
                <div class="table-container-industrial">
                    <table class="table-industrial">
                        <thead>
                            <tr>
                                <th><i class="fas fa-file"></i> Dokumentua / Documento</th>
                                <th><i class="fas fa-tag"></i> Kategoria / Categoría</th>
                                <th><i class="fas fa-weight-hanging"></i> Tamaina / Tamaño</th>
                                <th><i class="fas fa-user"></i> Igolea / Subido por</th>
                                <th><i class="fas fa-calendar"></i> Data / Fecha</th>
                                <th><i class="fas fa-cog"></i> Ekintzak / Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($documents as $doc): ?>
                                <?php
                                $ext = strtolower(pathinfo($doc['original_filename'], PATHINFO_EXTENSION));
                                $icon = match($ext) {
                                    'pdf' => 'fa-file-pdf',
                                    'doc', 'docx' => 'fa-file-word',
                                    'jpg', 'jpeg', 'png' => 'fa-file-image',
                                    default => 'fa-file'
                                };
                                $color = match($ext) {
                                    'pdf' => '#DC2626',
                                    'doc', 'docx' => '#0EA5E9',
                                    'jpg', 'jpeg', 'png' => '#059669',
                                    default => '#64748B'
                                };
                                $categoryColors = [
                                    'contract' => '#0EA5E9',
                                    'nif' => '#D97706',
                                    'payroll' => '#059669',
                                    'certificate' => '#9333EA',
                                    'other' => '#64748B'
                                ];
                                $categoryColor = $categoryColors[$doc['type']] ?? '#64748B';
                                
                                // Format file size
                                $size = $doc['file_size'];
                                $units = ['B', 'KB', 'MB', 'GB'];
                                $unit = 0;
                                while ($size >= 1024 && $unit < count($units) - 1) {
                                    $size /= 1024;
                                    $unit++;
                                }
                                $sizeStr = round($size, 2) . ' ' . $units[$unit];
                                ?>
                                <tr>
                                    <td>
                                        <div style="display: flex; align-items: center; gap: var(--space-3);">
                                            <i class="fas <?= $icon ?>" style="color: <?= $color ?>; font-size: 1.5rem;"></i>
                                            <div>
                                                <div style="font-weight: 600;"><?= htmlspecialchars($doc['original_filename']) ?></div>
                                                <?php if (!empty($doc['description'])): ?>
                                                    <small style="color: var(--text-tertiary);">
                                                        <?= htmlspecialchars(substr($doc['description'], 0, 50)) ?><?= strlen($doc['description']) > 50 ? '...' : '' ?>
                                                    </small>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span style="display: inline-flex; align-items: center; gap: 6px; padding: 4px 12px; background: <?= $categoryColor ?>20; color: <?= $categoryColor ?>; border-radius: 999px; font-size: var(--text-xs); font-weight: 600;">
                                            <i class="fas fa-tag"></i>
                                            <?= $categories[$doc['type']]['eu'] ?? 'Beste batzuk' ?>
                                        </span>
                                    </td>
                                    <td><?= $sizeStr ?></td>
                                    <td><?= htmlspecialchars($doc['uploaded_by_name'] ?? 'N/A') ?></td>
                                    <td><?= date('d/m/Y H:i', strtotime($doc['created_at'])) ?></td>
                                    <td>
                                        <div class="table-actions">
                                            <a href="/documents/<?= $doc['id'] ?>/download" class="btn-industrial btn-sm btn-primary-industrial" title="Deskargatu / Descargar">
                                                <i class="fas fa-download"></i>
                                            </a>
                                            <?php if ($user['role'] === 'ADMIN' || $user['role'] === 'RRHH_MGR'): ?>
                                                <form method="POST" action="/documents/<?= $doc['id'] ?>/delete" style="display: inline;" onsubmit="return confirm('Ziur zaude? / ¿Estás seguro?');">
                                                    <button type="submit" class="btn-industrial btn-sm btn-danger-industrial" title="Ezabatu / Eliminar">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="table-empty">
                    <i class="fas fa-inbox" style="font-size: 3rem; color: var(--text-tertiary); margin-bottom: var(--space-4);"></i>
                    <p>Ez dago dokumenturik / No hay documentos disponibles</p>
                    <?php if ($selected_category): ?>
                        <a href="/documents?tab=<?= htmlspecialchars($active_tab) ?>" class="btn-industrial btn-secondary-industrial" style="margin-top: var(--space-4);">
                            <i class="fas fa-times"></i>
                            Iragazkiak garbitu / Limpiar filtros
                        </a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require dirname(__DIR__) . '/layouts/footer.php'; ?>
