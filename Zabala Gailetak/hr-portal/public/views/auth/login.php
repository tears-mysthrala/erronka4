<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Zabala Gailetak HR Portal</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="/assets/css/zabala-industrial.css">
    <style>
        :root {
            --primary: #B91C1C;
            --primary-dark: #7F1D1D;
            --primary-light: #DC2626;
            --primary-glow: rgba(185, 28, 28, 0.2);
            --secondary: #18181B;
            --accent: #EA580C;
            --accent-glow: rgba(234, 88, 12, 0.15);
            --bg-body: #0F0F11;
            --bg-surface: #18181B;
            --bg-card: #1C1C1F;
            --bg-elevated: #27272A;
            --glass-bg: rgba(24, 24, 27, 0.85);
            --glass-border: rgba(255, 255, 255, 0.08);
            --glass-backdrop: blur(16px);
            --text-primary: #FAFAFA;
            --text-secondary: #A1A1AA;
            --text-tertiary: #71717A;
            --border-base: rgba(255, 255, 255, 0.08);
            --shadow-xl: 0 16px 40px rgba(0, 0, 0, 0.8);
            --space-2: 8px;
            --space-3: 12px;
            --space-4: 16px;
            --space-5: 20px;
            --space-6: 24px;
            --space-8: 32px;
            --space-10: 40px;
            --radius-md: 10px;
            --radius-lg: 14px;
            --radius-xl: 20px;
            --radius-full: 9999px;
            --font-base: 'Outfit', -apple-system, BlinkMacSystemFont, sans-serif;
            --font-mono: 'JetBrains Mono', monospace;
            --text-sm: 0.875rem;
            --text-base: 1rem;
            --text-xl: 1.25rem;
            --text-3xl: 1.875rem;
            --transition-base: 250ms cubic-bezier(0.4, 0, 0.2, 1);
        }
    </style>
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