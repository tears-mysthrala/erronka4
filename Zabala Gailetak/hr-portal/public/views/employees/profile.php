<?php require dirname(__DIR__) . '/layouts/header.php'; ?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Mi Perfil</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="/employees/profile/edit" class="btn btn-sm btn-primary">
            <i class="bi bi-pencil"></i> Editar Perfil
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Información Personal</h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-sm-3"><strong>Número Empleado:</strong></div>
                    <div class="col-sm-9"><?= htmlspecialchars($employee['employee_number']) ?></div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-3"><strong>Nombre:</strong></div>
                    <div class="col-sm-9"><?= htmlspecialchars($employee['first_name'] . ' ' . $employee['last_name']) ?></div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-3"><strong>NIF:</strong></div>
                    <div class="col-sm-9"><?= htmlspecialchars($employee['nif']) ?></div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-3"><strong>Email:</strong></div>
                    <div class="col-sm-9"><?= htmlspecialchars($employee['email']) ?></div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-3"><strong>Puesto:</strong></div>
                    <div class="col-sm-9"><?= htmlspecialchars($employee['position']) ?></div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-3"><strong>Departamento:</strong></div>
                    <div class="col-sm-9"><?= htmlspecialchars($employee['department_name'] ?? 'N/A') ?></div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-3"><strong>Teléfono:</strong></div>
                    <div class="col-sm-9"><?= htmlspecialchars($employee['phone'] ?? 'N/A') ?></div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-3"><strong>Dirección:</strong></div>
                    <div class="col-sm-9">
                        <?php if (!empty($employee['address'])): ?>
                            <?= htmlspecialchars($employee['address']) ?><br>
                            <?php if (!empty($employee['postal_code']) || !empty($employee['city'])): ?>
                                <?= htmlspecialchars($employee['postal_code'] ?? '') ?> <?= htmlspecialchars($employee['city'] ?? '') ?><br>
                            <?php endif; ?>
                            <?= htmlspecialchars($employee['country'] ?? 'España') ?>
                        <?php else: ?>
                            N/A
                        <?php endif; ?>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-3"><strong>Fecha Contratación:</strong></div>
                    <div class="col-sm-9"><?= date('d/m/Y', strtotime($employee['hire_date'])) ?></div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-3"><strong>Rol Sistema:</strong></div>
                    <div class="col-sm-9">
                        <span class="badge bg-<?= $employee['role'] === 'admin' ? 'danger' : ($employee['role'] === 'hr_manager' ? 'warning' : 'info') ?>">
                            <?= htmlspecialchars(ucfirst(str_replace('_', ' ', $employee['role']))) ?>
                        </span>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-3"><strong>Estado:</strong></div>
                    <div class="col-sm-9">
                        <?php if ($employee['is_active']): ?>
                            <span class="badge bg-success">Activo</span>
                        <?php else: ?>
                            <span class="badge bg-secondary">Inactivo</span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Acciones Rápidas</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <?php if ($canEdit): ?>
                        <a href="/employees/profile/edit" class="btn btn-primary">
                            <i class="bi bi-pencil"></i> Editar Perfil
                        </a>
                    <?php endif; ?>
                    
                    <?php if ($auth['role'] === 'admin'): ?>
                        <a href="/employees" class="btn btn-outline-secondary">
                            <i class="bi bi-people"></i> Ver Todos los Empleados
                        </a>
                    <?php endif; ?>
                    
                    <a href="/dashboard" class="btn btn-outline-info">
                        <i class="bi bi-speedometer2"></i> Dashboard
                    </a>
                </div>
            </div>
        </div>
        
        <?php if ($auth['role'] === 'admin'): ?>
        <div class="card mt-3 border-warning">
            <div class="card-header bg-warning text-dark">
                <h6 class="card-title mb-0">
                    <i class="bi bi-shield-check"></i> Permisos Administrador
                </h6>
            </div>
            <div class="card-body">
                <small class="text-muted">
                    Como administrador, tienes acceso completo a todas las funciones del sistema incluyendo:
                    gestión de empleados, usuarios, vacaciones, documentos e informes.
                </small>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php require dirname(__DIR__) . '/layouts/footer.php'; ?>