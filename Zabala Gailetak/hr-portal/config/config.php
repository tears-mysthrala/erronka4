<?php

declare(strict_types=1);

// Helper to safely get env vars
$env = function($key, $default = null) {
    return $_ENV[$key] ?? $_SERVER[$key] ?? getenv($key) ?: $default;
};

return [
    'app' => [
        'name' => $env('APP_NAME', 'HR Portal'),
        'env' => $env('APP_ENV', 'production'),
        'debug' => filter_var($env('APP_DEBUG', false), FILTER_VALIDATE_BOOLEAN),
        'url' => $env('APP_URL', 'http://localhost'),
        'timezone' => 'Europe/Madrid',
        'locale' => 'eu',
    ],
    
    'database' => [
        'driver' => $env('DB_DRIVER', 'pgsql'),
        'host' => $env('DB_HOST', 'localhost'),
        'port' => (int)$env('DB_PORT', 5432),
        'database' => $env('DB_NAME', 'hr_portal'),
        'username' => $env('DB_USER', 'hr_user'),
        'password' => $env('DB_PASSWORD', ''),
        'charset' => 'utf8',
        'options' => [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ],
    ],
    
    'session' => [
        'lifetime' => (int)$env('SESSION_LIFETIME', 28800), // 8 hours
        'cookie_name' => 'HRPORTAL_SESSION',
        'cookie_httponly' => true,
        'cookie_secure' => $env('APP_ENV') === 'production',
        'cookie_samesite' => 'Lax',
    ],
    
    'security' => [
        'jwt_secret' => $env('JWT_SECRET', ''),
        'jwt_algorithm' => 'HS256',
        'jwt_expiration' => 3600, // 1 hour
        'password_pepper' => $env('PASSWORD_PEPPER', ''),
        'csrf_token_name' => $env('CSRF_TOKEN_NAME', 'csrf_token'),
        'max_login_attempts' => (int)$env('RATE_LIMIT_LOGIN', 5),
        'lockout_duration' => 900, // 15 minutes
    ],
    
    'upload' => [
        'max_size' => (int)$env('UPLOAD_MAX_SIZE', 10485760), // 10MB
        'allowed_types' => explode(',', $env('UPLOAD_ALLOWED_TYPES', 'pdf,doc,docx,jpg,jpeg,png')),
        'path' => $env('UPLOAD_PATH', ROOT_PATH . '/storage/uploads'),
    ],
    
    'mail' => [
        'host' => $env('MAIL_HOST', ''),
        'port' => (int)$env('MAIL_PORT', 587),
        'username' => $env('MAIL_USERNAME', ''),
        'password' => $env('MAIL_PASSWORD', ''),
        'from_address' => $env('MAIL_FROM_ADDRESS', 'noreply@example.com'),
        'from_name' => $env('MAIL_FROM_NAME', 'HR Portal'),
        'encryption' => 'tls',
    ],
    
    'logging' => [
        'level' => $env('LOG_LEVEL', 'info'),
        'path' => $env('LOG_PATH', ROOT_PATH . '/logs'),
        'channels' => [
            'application' => 'application.log',
            'security' => 'security.log',
            'audit' => 'audit.log',
            'error' => 'error.log',
        ],
    ],
    
    'webauthn' => [
        'name' => $env('WEBAUTHN_NAME', 'HR Portal'),
        'id' => $env('WEBAUTHN_ID', 'localhost'),
        'icon' => $env('WEBAUTHN_ICON', ''),
    ],
];
