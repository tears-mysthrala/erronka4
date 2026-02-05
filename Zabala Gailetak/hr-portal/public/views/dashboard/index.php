<?php require dirname(__DIR__) . '/layouts/header.php'; ?>

<div class="row">
    <div class="col-md-12 mb-4">
        <h2>Dashboard</h2>
        <p class="text-muted">Bienvenido al panel de gestión de Zabala Gailetak.</p>
    </div>
</div>

<div class="dashboard-stats fade-in">
    <!-- Stats Cards -->
    <div class="stat-card card-primary">
        <i class="fas fa-users stat-icon"></i>
        <div class="card-body">
            <h5 class="card-title"><i class="fas fa-briefcase"></i> Empleados Activos</h5>
            <p class="card-text"><?= htmlspecialchars($stats['employees'] ?? 0) ?></p>
            <a href="/employees">
                Ver listado <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    </div>
    <div class="stat-card card-success">
        <i class="fas fa-check-circle stat-icon"></i>
        <div class="card-body">
            <h5 class="card-title"><i class="fas fa-calendar-check"></i> Presentes Hoy</h5>
            <p class="card-text">118</p>
        </div>
    </div>
    <div class="stat-card card-warning">
        <i class="fas fa-umbrella-beach stat-icon"></i>
        <div class="card-body">
            <h5 class="card-title"><i class="fas fa-clock"></i> Vacaciones Pendientes</h5>
            <p class="card-text"><?= htmlspecialchars($stats['pending_vacations'] ?? 0) ?></p>
            <a href="/vacations">
                Gestionar <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    </div>
</div>

<div class="dashboard-grid">
    <div class="widget-card">
        <div class="card-header">
            <i class="fas fa-calendar-alt"></i>
            Próximas Vacaciones
        </div>
        <ul class="list-group list-group-flush">
            <?php if (!empty($upcomingVacations)): ?>
                <?php foreach ($upcomingVacations as $vacation): ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span>
                            <i class="fas fa-user-circle"></i>
                            <?= htmlspecialchars($vacation['first_name'] . ' ' . $vacation['last_name']) ?>
                        </span>
                        <span class="badge bg-info rounded-pill">
                            <i class="fas fa-calendar"></i>
                            <?= date('d/m', strtotime($vacation['start_date'])) ?> - <?= date('d/m', strtotime($vacation['end_date'])) ?>
                        </span>
                    </li>
                <?php endforeach; ?>
            <?php else: ?>
                <li class="list-group-item">
                    <div class="empty-state">
                        <i class="fas fa-calendar-times"></i>
                        <p>No hay vacaciones próximas</p>
                    </div>
                </li>
            <?php endif; ?>
        </ul>
    </div>
</div>

<?php require dirname(__DIR__) . '/layouts/footer.php'; ?>
