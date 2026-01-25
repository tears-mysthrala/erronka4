<?php

declare(strict_types=1);

/**
 * Front Controller - Entry point for all requests
 * 
 * @package HrPortal
 * @author Zabala Gailetak
 */

// Initialize session for web interface
if (session_status() === PHP_SESSION_NONE) {
    session_start([
        'cookie_httponly' => true,
        'cookie_secure' => isset($_SERVER['HTTPS']),
        'cookie_samesite' => 'Lax',
    ]);
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
$env = function($key, $default = null) {
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
    // Log error
    error_log($e->getMessage());
    
    // Show error page
    http_response_code(500);
    
    if ($appDebug) {
        echo '<h1>Error</h1>';
        echo '<pre>' . htmlspecialchars($e->getMessage()) . '</pre>';
        echo '<pre>' . htmlspecialchars($e->getTraceAsString()) . '</pre>';
    } else {
        // Friendly error page for missing config
        if (str_contains($e->getMessage(), 'SQLSTATE') || str_contains($e->getMessage(), 'Connection refused')) {
            echo '<h1>Servicio No Disponible</h1>';
            echo '<p>No se ha podido conectar con la base de datos. Por favor, verifica el archivo <code>.env</code>.</p>';
            echo '<hr>';
            echo '<small>Si eres el administrador, sube el archivo .env via FTP.</small>';
        } else {
            echo '<h1>500 Internal Server Error</h1>';
            echo '<p>An unexpected error occurred. Please try again later.</p>';
        }
    }
}
