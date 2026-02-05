<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página No Encontrada - HR Portal</title>
    <link rel="stylesheet" href="/assets/vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card border-warning">
                    <div class="card-header bg-warning text-dark">
                        <h3 class="mb-0">
                            <i class="bi bi-exclamation-circle-fill"></i>
                            Página No Encontrada
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-warning" role="alert">
                            <i class="bi bi-question-circle"></i>
                            <strong>Error 404:</strong> <?= htmlspecialchars($message ?? 'La página que buscas no existe o ha sido movida.') ?>
                        </div>
                        
                        <div class="mt-4">
                            <h5>¿Qué puedo hacer?</h5>
                            <ul>
                                <li>Verifica que la URL esté escrita correctamente</li>
                                <li>Usa el menú de navegación para encontrar lo que buscas</li>
                                <li>Contacta con soporte si el problema persiste</li>
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