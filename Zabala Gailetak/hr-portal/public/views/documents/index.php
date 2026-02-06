<?php require dirname(__DIR__) . '/layouts/header.php'; ?>

<div class="page-header">
    <div>
        <h1 class="page-title">
            <i class="fas fa-folder"></i>
            Dokumentuak / Documentos
        </h1>
        <p class="page-subtitle">
            Zure dokumentuak kontsultatu eta kudeatu / Consulta y gestiona tus documentos
        </p>
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

<!-- Tabs -->
<div class="widget-card-industrial mb-6">
    <div class="widget-body" style="padding: 0;">
        <div style="display: flex; border-bottom: 2px solid var(--border-color);">
            <a href="/documents?tab=personal" 
               class="tab-link <?= $active_tab === 'personal' ? 'active' : '' ?>"
               style="flex: 1; padding: var(--space-4); text-align: center; text-decoration: none; color: <?= $active_tab === 'personal' ? 'var(--primary)' : 'var(--text-secondary)' ?>; font-weight: 600; border-bottom: 3px solid <?= $active_tab === 'personal' ? 'var(--primary)' : 'transparent' ?>;">
                <i class="fas fa-user"></i>
                Nire dokumentuak / Mis documentos
                <span style="background: var(--primary); color: white; padding: 4px 8px; border-radius: 999px; font-size: 0.75rem; margin-left: 8px;">
                    <?= count($personal_documents) ?>
                </span>
            </a>
            <a href="/documents?tab=public" 
               class="tab-link <?= $active_tab === 'public' ? 'active' : '' ?>"
               style="flex: 1; padding: var(--space-4); text-align: center; text-decoration: none; color: <?= $active_tab === 'public' ? 'var(--primary)' : 'var(--text-secondary)' ?>; font-weight: 600; border-bottom: 3px solid <?= $active_tab === 'public' ? 'var(--primary)' : 'transparent' ?>;">
                <i class="fas fa-globe"></i>
                Dokumentu publikoak / Documentos públicos
                <span style="background: var(--accent); color: white; padding: 4px 8px; border-radius: 999px; font-size: 0.75rem; margin-left: 8px;">
                    <?= count($public_documents) ?>
                </span>
            </a>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="widget-card-industrial mb-6">
    <div class="widget-body">
        <form method="GET" action="/documents" class="filter-form">
            <input type="hidden" name="tab" value="<?= htmlspecialchars($active_tab) ?>">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: var(--space-4); align-items: end;">
                <div class="form-group">
                    <label for="category" class="form-label">Kategoria / Categoría</label>
                    <select name="category" id="category" class="form-control">
                        <option value="">Guztiak / Todas</option>
                        <?php foreach ($categories as $key => $names): ?>
                            <option value="<?= $key ?>" <?= ($selected_category === $key) ? 'selected' : '' ?>>
                                <?= $names['eu'] ?> / <?= $names['es'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div style="display: flex; gap: var(--space-3);">
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
<div class="widget-card-industrial">
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
    <div class="widget-body">
        <?php
        $documents = $active_tab === 'personal' ? $personal_documents : $public_documents;
        ?>
        
        <?php if (!empty($documents)): ?>
            <div class="documents-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: var(--space-4);">
                <?php foreach ($documents as $doc): ?>
                    <div class="widget-card-industrial" style="background: var(--bg-elevated); transition: transform 0.2s, box-shadow 0.2s; cursor: pointer;">
                        <div style="padding: var(--space-5);">
                            <!-- Icon and Type -->
                            <div style="display: flex; align-items: center; gap: var(--space-3); margin-bottom: var(--space-4);">
                                <div style="width: 48px; height: 48px; border-radius: var(--radius-md); background: var(--primary-light); display: flex; align-items: center; justify-content: center; color: white; font-size: 1.5rem;">
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
                                    ?>
                                    <i class="fas <?= $icon ?>" style="color: <?= $color ?>"></i>
                                </div>
                                <div style="flex: 1; min-width: 0;">
                                    <div style="font-weight: 700; font-size: var(--text-lg); margin-bottom: 4px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                        <?= htmlspecialchars($doc['original_filename']) ?>
                                    </div>
                                    <div style="font-size: var(--text-xs); color: var(--text-tertiary);">
                                        <?php
                                        $size = $doc['file_size'];
                                        $units = ['B', 'KB', 'MB', 'GB'];
                                        $unit = 0;
                                        while ($size >= 1024 && $unit < count($units) - 1) {
                                            $size /= 1024;
                                            $unit++;
                                        }
                                        echo round($size, 2) . ' ' . $units[$unit];
                                        ?>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Category Badge -->
                            <div style="margin-bottom: var(--space-4);">
                                <?php
                                $categoryColors = [
                                    'contract' => '#0EA5E9',
                                    'nif' => '#D97706',
                                    'payroll' => '#059669',
                                    'certificate' => '#9333EA',
                                    'other' => '#64748B'
                                ];
                                $categoryColor = $categoryColors[$doc['type']] ?? '#64748B';
                                ?>
                                <span style="display: inline-flex; align-items: center; gap: 6px; padding: 6px 12px; background: <?= $categoryColor ?>20; color: <?= $categoryColor ?>; border-radius: 999px; font-size: var(--text-xs); font-weight: 700;">
                                    <i class="fas fa-tag"></i>
                                    <?= $categories[$doc['type']]['eu'] ?? 'Beste batzuk' ?>
                                </span>
                            </div>
                            
                            <!-- Description -->
                            <?php if (!empty($doc['description'])): ?>
                                <div style="margin-bottom: var(--space-4); color: var(--text-secondary); font-size: var(--text-sm); line-height: 1.5;">
                                    <?= htmlspecialchars(substr($doc['description'], 0, 100)) ?><?= strlen($doc['description']) > 100 ? '...' : '' ?>
                                </div>
                            <?php endif; ?>
                            
                            <!-- Metadata -->
                            <div style="margin-bottom: var(--space-4); padding-top: var(--space-4); border-top: 1px solid var(--border-color);">
                                <div style="font-size: var(--text-xs); color: var(--text-tertiary); margin-bottom: 8px;">
                                    <i class="fas fa-user"></i>
                                    <?= htmlspecialchars($doc['uploaded_by_name'] ?? 'N/A') ?>
                                </div>
                                <div style="font-size: var(--text-xs); color: var(--text-tertiary);">
                                    <i class="fas fa-clock"></i>
                                    <?= date('d/m/Y H:i', strtotime($doc['created_at'])) ?>
                                </div>
                            </div>
                            
                            <!-- Actions -->
                            <div style="display: flex; gap: var(--space-2);">
                                <a href="/documents/<?= $doc['id'] ?>/download" class="btn-industrial btn-primary-industrial" style="flex: 1; text-align: center;">
                                    <i class="fas fa-download"></i>
                                    Deskargatu
                                </a>
                                <?php if ($user['role'] === 'ADMIN' || $user['role'] === 'RRHH_MGR'): ?>
                                    <form method="POST" action="/documents/<?= $doc['id'] ?>/delete" style="display: inline;" onsubmit="return confirm('Ziur zaude? / ¿Estás seguro?');">
                                        <button type="submit" class="btn-ghost-industrial" style="color: var(--danger);" title="Ezabatu / Eliminar">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="empty-state">
                <i class="fas fa-inbox"></i>
                <p>Ez dago dokumenturik / No hay documentos disponibles</p>
                <?php if ($selected_category): ?>
                    <a href="/documents?tab=<?= htmlspecialchars($active_tab) ?>" class="btn-industrial btn-secondary-industrial mt-4">
                        <i class="fas fa-times"></i>
                        Iragazkiak garbitu / Limpiar filtros
                    </a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
.documents-grid > div:hover {
    transform: translateY(-4px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
}

.tab-link:hover {
    background: var(--bg-hover);
}
</style>

<?php require dirname(__DIR__) . '/layouts/footer.php'; ?>
