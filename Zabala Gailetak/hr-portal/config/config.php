<?php

declare(strict_types=1);

return [
    'app' => [
        'name' => $_ENV['APP_NAME'] ?? 'HR Portal',
        'env' => $_ENV['APP_ENV'] ?? 'production',
        'debug' => filter_var($_ENV['APP_DEBUG'] ?? false, FILTER_VALIDATE_BOOLEAN),
        'url' => $_ENV['APP_URL'] ?? 'http://localhost',
        'timezone' => 'Europe/Madrid',
        'locale' => 'eu',
    ],
    
    'database' => [
        'host' => $_ENV['DB_HOST'] ?? 'localhost',
        'port' => (int)($_ENV['DB_PORT'] ?? 5432),
        'database' => $_ENV['DB_NAME'] ?? 'hr_portal',
        'username' => $_ENV['DB_USER'] ?? 'hr_user',
        'password' => $_ENV['DB_PASSWORD'] ?? '',
        'charset' => 'utf8',
        'options' => [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ],
    ],
    
    'redis' => [
        'host' => $_ENV['REDIS_HOST'] ?? 'localhost',
        'port' => (int)($_ENV['REDIS_PORT'] ?? 6379),
        'password' => $_ENV['REDIS_PASSWORD'] ?? null,
        'database' => (int)($_ENV['REDIS_DB'] ?? 0),
    ],
    
    'session' => [
        'lifetime' => (int)($_ENV['SESSION_LIFETIME'] ?? 28800), // 8 hours
        'cookie_name' => 'HRPORTAL_SESSION',
        'cookie_httponly' => true,
        'cookie_secure' => $_ENV['APP_ENV'] === 'production',
        'cookie_samesite' => 'Lax',
    ],
    
    'security' => [
        'jwt_secret' => $_ENV['JWT_SECRET'] ?? '',
        'jwt_algorithm' => 'HS256',
        'jwt_expiration' => 3600, // 1 hour
        'password_pepper' => $_ENV['PASSWORD_PEPPER'] ?? '',
        'csrf_token_name' => $_ENV['CSRF_TOKEN_NAME'] ?? 'csrf_token',
        'max_login_attempts' => (int)($_ENV['RATE_LIMIT_LOGIN'] ?? 5),
        'lockout_duration' => 900, // 15 minutes
    ],
    
    'upload' => [
        'max_size' => (int)($_ENV['UPLOAD_MAX_SIZE'] ?? 10485760), // 10MB
        'allowed_types' => explode(',', $_ENV['UPLOAD_ALLOWED_TYPES'] ?? 'pdf,doc,docx,jpg,jpeg,png'),
        'path' => $_ENV['UPLOAD_PATH'] ?? ROOT_PATH . '/storage/uploads',
    ],
    
    'mail' => [
        'host' => $_ENV['MAIL_HOST'] ?? '',
        'port' => (int)($_ENV['MAIL_PORT'] ?? 587),
        'username' => $_ENV['MAIL_USERNAME'] ?? '',
        'password' => $_ENV['MAIL_PASSWORD'] ?? '',
        'from_address' => $_ENV['MAIL_FROM_ADDRESS'] ?? 'noreply@example.com',
        'from_name' => $_ENV['MAIL_FROM_NAME'] ?? 'HR Portal',
        'encryption' => 'tls',
    ],
    
    'logging' => [
        'level' => $_ENV['LOG_LEVEL'] ?? 'info',
        'path' => $_ENV['LOG_PATH'] ?? ROOT_PATH . '/logs',
        'channels' => [
            'application' => 'application.log',
            'security' => 'security.log',
            'audit' => 'audit.log',
            'error' => 'error.log',
        ],
    ],
    
    'webauthn' => [
        'name' => $_ENV['WEBAUTHN_NAME'] ?? 'HR Portal',
        'id' => $_ENV['WEBAUTHN_ID'] ?? 'localhost',
        'icon' => $_ENV['WEBAUTHN_ICON'] ?? '',
    ],
];
