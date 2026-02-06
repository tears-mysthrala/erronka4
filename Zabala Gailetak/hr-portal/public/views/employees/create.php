<?php require dirname(__DIR__) . '/layouts/header.php'; ?>

<div class="dashboard-container">
    <div class="page-header">
        <div>
            <h1 class="page-title">
                <i class="fas fa-user-plus"></i>
                Nuevo Empleado
            </h1>
            <p class="page-subtitle">Crea un perfil de empleado y asigna permisos</p>
        </div>
        <div class="page-actions">
            <a href="/employees" class="btn-industrial btn-secondary-industrial">
                <i class="fas fa-arrow-left"></i>
                Volver
            </a>
        </div>
    </div>

    <form action="/employees/create" method="POST" class="needs-validation" novalidate>
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token ?? '') ?>">

        <div class="widget-card-industrial" style="margin-bottom: var(--space-6);">
            <div class="widget-header">
                <h3 class="widget-title">
                    <i class="fas fa-id-card"></i>
                    Datos personales
                </h3>
            </div>
            <div class="widget-body" style="display: grid; gap: var(--space-4);">
                <div style="display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: var(--space-4);">
                    <div class="form-group-industrial">
                        <label for="first_name" class="form-label-industrial">Nombre</label>
                        <input type="text" class="form-input-industrial" id="first_name" name="first_name" required>
                    </div>
                    <div class="form-group-industrial">
                        <label for="last_name" class="form-label-industrial">Apellido</label>
                        <input type="text" class="form-input-industrial" id="last_name" name="last_name" required>
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: var(--space-4);">
                    <div class="form-group-industrial">
                        <label for="email" class="form-label-industrial">Email Corporativo</label>
                        <input type="email" class="form-input-industrial" id="email" name="email" required>
                    </div>
                    <div class="form-group-industrial">
                        <label for="nif" class="form-label-industrial">NIF</label>
                        <input type="text" class="form-input-industrial" id="nif" name="nif" required>
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: var(--space-4);">
                    <div class="form-group-industrial">
                        <label for="position" class="form-label-industrial">Puesto</label>
                        <input type="text" class="form-input-industrial" id="position" name="position">
                    </div>
                    <?php if ($auth['role'] === 'admin'): ?>
                        <div class="form-group-industrial">
                            <label for="role" class="form-label-industrial">Rol en el Sistema</label>
                            <select class="form-select-industrial" id="role" name="role">
                                <option value="employee">Empleado</option>
                                <option value="department_head">Jefe de Departamento</option>
                                <option value="hr_manager">Responsable de RRHH</option>
                                <option value="admin">Administrador</option>
                            </select>
                            <small class="form-text">Selecciona el nivel de acceso para este usuario.</small>
                        </div>
                    <?php else: ?>
                        <input type="hidden" name="role" value="employee">
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="widget-card-industrial" style="margin-bottom: var(--space-6);">
            <div class="widget-header">
                <h3 class="widget-title">
                    <i class="fas fa-briefcase"></i>
                    Datos laborales
                </h3>
            </div>
            <div class="widget-body" style="display: grid; gap: var(--space-4);">
                <div style="display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: var(--space-4);">
                    <div class="form-group-industrial">
                        <label for="department_id" class="form-label-industrial">Departamento</label>
                        <select class="form-select-industrial" id="department_id" name="department_id">
                            <option selected disabled value="">Elegir...</option>
                            <?php foreach ($departments as $dept): ?>
                                <option value="<?= $dept['id'] ?>"><?= htmlspecialchars($dept['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group-industrial">
                        <label for="hire_date" class="form-label-industrial">Fecha Contratación</label>
                        <input type="date" class="form-input-industrial" id="hire_date" name="hire_date" value="<?= date('Y-m-d') ?>">
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: var(--space-4);">
                    <div class="form-group-industrial">
                        <label for="salary" class="form-label-industrial">Salario Bruto Anual</label>
                        <input type="number" class="form-input-industrial" id="salary" name="salary" step="0.01">
                    </div>
                    <div class="form-group-industrial" style="display: flex; flex-direction: column; justify-content: center;">
                        <label style="display: flex; align-items: center; gap: var(--space-2); font-size: var(--text-sm); color: var(--text-secondary);">
                            <input class="form-check-input" type="checkbox" name="send_welcome_email" id="send_welcome_email" checked>
                            Enviar email de bienvenida con credenciales
                        </label>
                        <small class="form-text">Se enviará un email con instrucciones de acceso.</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-actions-industrial">
            <button class="btn-industrial btn-primary-industrial" type="submit">
                <i class="fas fa-user-plus"></i>
                Guardar Empleado
            </button>
            <a href="/employees" class="btn-industrial btn-secondary-industrial">
                <i class="fas fa-times-circle"></i>
                Cancelar
            </a>
        </div>
    </form>
</div>

<?php require dirname(__DIR__) . '/layouts/footer.php'; ?>