<?php

declare(strict_types=1);

/**
 * Front Controller - Entry point for all requests
 * 
 * @package HrPortal
 * @author Zabala Gailetak
 */

// Preflight request: return 204 and exit early (for API clients)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header('Access-Control-Allow-Origin: https://zabala-gailetak.infinityfreeapp.com');
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
    header('Access-Control-Allow-Headers: Authorization, Content-Type, X-Requested-With, X-CSRF-Token');
    header('Access-Control-Allow-Credentials: false');
    http_response_code(204);
    exit;
}

// Initialize session for web interface
if (session_status() === PHP_SESSION_NONE) {
    session_start([
        'cookie_httponly' => true,
        'cookie_secure' => isset($_SERVER['HTTPS']),
        'cookie_samesite' => 'Lax',
    ]);
}

// Generate CSRF token if not exists
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

use ZabalaGailetak\HrPortal\App;

// Define root path
define('ROOT_PATH', dirname(__DIR__));

// Load Native Autoloader (Zero Trust)
require ROOT_PATH . '/src/Core/ClassLoader.php';
\ZabalaGailetak\HrPortal\Core\ClassLoader::register();

// Load environment variables (Native)
\ZabalaGailetak\HrPortal\Core\EnvLoader::load(ROOT_PATH);
\ZabalaGailetak\HrPortal\Core\EnvLoader::ensurePopulated();

// Safe Env Getter
$env = function ($key, $default = null) {
    return $_ENV[$key] ?? $_SERVER[$key] ?? getenv($key) ?: $default;
};

// Error handling based on environment
$appEnv = $env('APP_ENV', 'production');
$appDebug = filter_var($env('APP_DEBUG', false), FILTER_VALIDATE_BOOLEAN);

if ($appEnv === 'production') {
    error_reporting(0);
    ini_set('display_errors', '0');
} else {
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
}

// Initialize application
try {
    $app = new App();
    $app->run();
} catch (\Throwable $e) {
    // Log error internally
    error_log($e->getMessage());

    // Ensure JSON response for API consistency
    header('Content-Type: application/json');
    http_response_code(500);

    $response = [
        'error' => 'Internal Server Error',
        'code' => 500
    ];

    // If in debug mode, include detailed error information
    if ($appDebug) {
        $response['message'] = $e->getMessage();
        $response['trace'] = $e->getTraceAsString();
        $response['file'] = $e->getFile();
        $response['line'] = $e->getLine();
    } else {
        // User-friendly messages for production
        if (str_contains($e->getMessage(), 'SQLSTATE') || str_contains($e->getMessage(), 'Connection refused')) {
            $response['message'] = 'No se ha podido conectar con la base de datos.';
        } else {
            $response['message'] = 'Ha ocurrido un error inesperado.';
        }
    }

    echo json_encode($response);
    exit;
}
