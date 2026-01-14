<?php

declare(strict_types=1);

/**
 * Front Controller - Entry point for all requests
 * 
 * @package HrPortal
 * @author Zabala Gailetak
 */

use HrPortal\App;

// Define root path
define('ROOT_PATH', dirname(__DIR__));

// Load Composer autoloader
require ROOT_PATH . '/vendor/autoload.php';

// Load environment variables
$dotenv = \Dotenv\Dotenv::createImmutable(ROOT_PATH);
$dotenv->load();

// Error handling based on environment
if ($_ENV['APP_ENV'] === 'production') {
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
    if ($_ENV['APP_DEBUG'] === 'true') {
        echo '<h1>Error</h1>';
        echo '<pre>' . $e->getMessage() . '</pre>';
        echo '<pre>' . $e->getTraceAsString() . '</pre>';
    } else {
        echo '<h1>500 Internal Server Error</h1>';
        echo '<p>An unexpected error occurred. Please try again later.</p>';
    }
}
