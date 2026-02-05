<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Zabala Gailetak HR Portal</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer">
    <link rel="stylesheet" href="/assets/css/industrial.php">
</head>
<body>

<div class="login-container">
    <div class="login-card">
        <div class="login-header">
            <div class="login-logo">
                <i class="fas fa-industry"></i>
            </div>
            <h1 class="login-title">Zabala Gailetak</h1>
            <p class="login-subtitle">Sistema de Gestión de Recursos Humanos</p>
        </div>

        <?php if (isset($error)): ?>
            <div class="alert-industrial alert-danger">
                <i class="fas fa-exclamation-triangle"></i>
                <span><?= htmlspecialchars($error) ?></span>
            </div>
        <?php endif; ?>

        <form action="/login" method="POST">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token ?? '') ?>">
            
            <div class="form-group">
                <label for="email" class="form-label">
                    <i class="fas fa-envelope"></i>
                    Correo Electrónico
                </label>
                <input type="email" 
                       class="form-control-industrial" 
                       id="email" 
                       name="email" 
                       placeholder="usuario@zabalagailetak.eus"
                       required 
                       autofocus>
            </div>
            
            <div class="form-group">
                <label for="password" class="form-label">
                    <i class="fas fa-lock"></i>
                    Contraseña
                </label>
                <input type="password" 
                       class="form-control-industrial" 
                       id="password" 
                       name="password" 
                       placeholder="••••••••"
                       required>
            </div>
            
            <button type="submit" class="btn-industrial btn-primary-industrial" style="width: 100%;">
                <i class="fas fa-sign-in-alt"></i>
                Iniciar Sesión
            </button>
        </form>

        <div style="margin-top: var(--space-6); padding-top: var(--space-6); border-top: 1px solid var(--border-base); text-align: center;">
            <p style="font-size: var(--text-sm); color: var(--text-tertiary);">
                <i class="fas fa-shield-alt"></i>
                ISO 27001 | GDPR | IEC 62443 Compliant
            </p>
        </div>
    </div>
</div>

</body>
</html>