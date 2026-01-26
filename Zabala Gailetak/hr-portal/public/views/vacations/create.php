<?php require dirname(__DIR__) . '/layouts/header.php'; ?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Solicitar Vacaciones</h1>
</div>

<?php if (isset($errors['system'])): ?>
    <div class="alert alert-danger">
        <?= htmlspecialchars($errors['system']) ?>
    </div>
<?php endif; ?>

    <div class="card">
    <div class="card-body">
        <form action="/vacations/request" method="POST" class="row g-3">
            <div class="col-md-6">
                <label for="start_date" class="form-label">Fecha Inicio</label>
                <input type="date" class="form-control" id="start_date" name="start_date" required min="<?= date('Y-m-d') ?>" value="<?= htmlspecialchars($old['start_date'] ?? '') ?>">
                <?php if (isset($errors['start_date'])): ?>
                    <div class="text-danger small mt-1"><?= htmlspecialchars($errors['start_date']) ?></div>
                <?php endif; ?>
            </div>
            <div class="col-md-6">
                <label for="end_date" class="form-label">Fecha Fin</label>
                <input type="date" class="form-control" id="end_date" name="end_date" required min="<?= date('Y-m-d') ?>" value="<?= htmlspecialchars($old['end_date'] ?? '') ?>">
                <?php if (isset($errors['end_date'])): ?>
                    <div class="text-danger small mt-1"><?= htmlspecialchars($errors['end_date']) ?></div>
                <?php endif; ?>
            </div>
            <div class="col-12">
                <label for="notes" class="form-label">Notas / Motivo (Opcional)</label>
                <textarea class="form-control" id="notes" name="notes" rows="3" placeholder="Indica el motivo de tus vacaciones si lo deseas..."><?= htmlspecialchars($old['notes'] ?? '') ?></textarea>
            </div>
            
            <div class="col-12 mt-4">
                <button class="btn btn-primary" type="submit">
                    <i class="fas fa-paper-plane"></i> Enviar Solicitud
                </button>
                <a href="/vacations" class="btn btn-outline-secondary">
                    <i class="fas fa-times"></i> Cancelar
                </a>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const startDate = document.getElementById('start_date');
    const endDate = document.getElementById('end_date');
    
    startDate.addEventListener('change', function() {
        endDate.min = this.value;
        if (endDate.value && endDate.value < this.value) {
            endDate.value = this.value;
        }
    });
    
    // Add date validation
    endDate.addEventListener('change', function() {
        if (this.value && startDate.value && this.value < startDate.value) {
            this.setCustomValidity('La fecha fin no puede ser anterior a la fecha de inicio');
        } else {
            this.setCustomValidity('');
        }
    });
});
</script>

<?php require dirname(__DIR__) . '/layouts/footer.php'; ?>
