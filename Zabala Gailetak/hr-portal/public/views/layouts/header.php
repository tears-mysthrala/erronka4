<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zabala Gailetak - HR Portal</title>
    <link rel="stylesheet" href="/assets/vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/css/style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body { background-color: var(--bg-secondary); }
        .navbar-brand { font-weight: 700; color: var(--text-white) !important; }
        .card { box-shadow: var(--shadow-md); border: none; }
    </style>
</head>
<body>
    <?php if (isset($_SESSION['user_id'])): ?>
    <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom mb-4">
        <div class="container">
            <a class="navbar-brand" href="/dashboard">Zabala Gailetak</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item"><a class="nav-link" href="/dashboard">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="/employees">Empleados</a></li>
                    <li class="nav-item"><a class="nav-link" href="/vacations">Vacaciones</a></li>
                </ul>
                <div class="d-flex">
                    <span class="navbar-text me-3">Hola, <?= htmlspecialchars($_SESSION['user_name'] ?? 'Usuario') ?></span>
                    <a href="/logout" class="btn btn-outline-danger btn-sm">Cerrar Sesión</a>
                </div>
            </div>
        </div>
    </nav>
    <?php endif; ?>
    <div class="container">
        <h1>Dashboard</h1>
        <p>¡Bienvenido al portal de recursos humanos!</p>
    </div>
</body>
</html>
