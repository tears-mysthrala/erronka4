<?php
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso Denegado - HR Portal</title>
    <link rel="stylesheet" href="/assets/vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card border-danger">
                    <div class="card-header bg-danger text-white">
                        <h3 class="mb-0">
                            <i class="bi bi-exclamation-triangle-fill"></i>
                            Acceso Denegado
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-danger" role="alert">
                            <i class="bi bi-shield-exclamation"></i>
                            <strong>Error 403:</strong> <?= htmlspecialchars($message ?? 'No tienes permisos para acceder a este recurso.') ?>
                        </div>
                        
                        <div class="mt-4">
                            <h5>¿Qué puedo hacer?</h5>
                            <ul>
                                <li>Contacta con tu administrador si crees que deberías tener acceso</li>
                                <li>Verifica que tienes el rol correcto en el sistema</li>
                                <li>Vuelve a la página anterior y prueba otra acción</li>
                            </ul>
                        </div>
                        
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                            <a href="javascript:history.back()" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left"></i> Volver
                            </a>
                            <a href="/dashboard" class="btn btn-primary">
                                <i class="bi bi-house"></i> Ir al Dashboard
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>