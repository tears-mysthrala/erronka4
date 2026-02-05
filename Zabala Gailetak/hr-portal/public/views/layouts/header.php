<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zabala Gailetak - HR Portal</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&family=JetBrains+Mono:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Industrial Design System -->
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
            --text-primary: #FAFAFA;
            --text-secondary: #A1A1AA;
            --text-tertiary: #71717A;
            --border-base: rgba(255, 255, 255, 0.08);
            --border-hover: rgba(255, 255, 255, 0.12);
            --shadow-xl: 0 16px 40px rgba(0, 0, 0, 0.8);
            --space-2: 8px;
            --space-3: 12px;
            --space-4: 16px;
            --space-5: 20px;
            --space-6: 24px;
            --radius-md: 10px;
            --radius-lg: 14px;
            --radius-xl: 20px;
            --radius-full: 9999px;
            --font-base: 'Outfit', -apple-system, BlinkMacSystemFont, sans-serif;
            --font-mono: 'JetBrains Mono', monospace;
            --text-sm: 0.875rem;
            --text-base: 1rem;
            --text-lg: 1.125rem;
            --text-xl: 1.25rem;
            --transition-base: 250ms cubic-bezier(0.4, 0, 0.2, 1);
        }
    </style>
</head>

<body>
    <?php if (isset($_SESSION['user_id'])): ?>
        <nav class="navbar-industrial">
            <div style="max-width: 1400px; margin: 0 auto; padding: 0 var(--space-6); display: flex; align-items: center; justify-content: space-between;">
                <a class="navbar-brand-industrial" href="/dashboard">
                    <div class="brand-icon">
                        <i class="fas fa-industry"></i>
                    </div>
                    Zabala Gailetak
                </a>
                
                <ul class="nav-links-industrial">
                    <li>
                        <a class="nav-link-industrial <?= ($_SERVER['REQUEST_URI'] === '/dashboard' ? 'active' : '') ?>" href="/dashboard">
                            <i class="fas fa-chart-line"></i>
                            Dashboard
                        </a>
                    </li>
                    <li>
                        <a class="nav-link-industrial <?= (strpos($_SERVER['REQUEST_URI'], '/employees') !== false ? 'active' : '') ?>" href="/employees">
                            <i class="fas fa-users"></i>
                            Empleados
                        </a>
                    </li>
                    <li>
                        <a class="nav-link-industrial <?= (strpos($_SERVER['REQUEST_URI'], '/vacations') !== false ? 'active' : '') ?>" href="/vacations">
                            <i class="fas fa-umbrella-beach"></i>
                            Vacaciones
                        </a>
                    </li>
                </ul>
                
                <div class="user-profile">
                    <div class="user-avatar">
                        <?= strtoupper(substr($_SESSION['user_name'] ?? 'A', 0, 1)) ?>
                    </div>
                    <div class="user-info">
                        <div class="user-name"><?= htmlspecialchars($_SESSION['user_name'] ?? 'Admin') ?></div>
                        <div class="user-role"><?= htmlspecialchars($_SESSION['user_role'] ?? 'Administrator') ?></div>
                    </div>
                    <a href="/logout" class="btn-ghost-industrial" style="margin-left: var(--space-4);">
                        <i class="fas fa-sign-out-alt"></i>
                    </a>
                </div>
            </div>
        </nav>
    <?php endif; ?>