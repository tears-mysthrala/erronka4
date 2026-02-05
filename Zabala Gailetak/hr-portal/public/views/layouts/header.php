<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zabala Gailetak - HR Portal</title>
    <!-- Font Awesome (CDN permitido por CSP) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer">
    <!-- Industrial Design System (PHP con MIME correcto) -->
    <link rel="stylesheet" href="/assets/css/industrial.php">
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