#!/bin/bash
# ZG-App Configuration Script
# PHP 8.4 + Nginx

set -e

echo "=========================================="
echo "🔧 CONFIGURANDO ZG-APP"
echo "=========================================="

# Configurar IP estática
cat > /etc/network/interfaces <<'EOF'
source /etc/network/interfaces.d/*

auto lo
iface lo inet loopback

allow-hotplug ens18
iface ens18 inet static
    address 192.168.20.10
    netmask 255.255.255.0
    gateway 192.168.20.1
    dns-nameservers 8.8.8.8 1.1.1.1
EOF

systemctl restart networking || true

# Instalar dependencias
echo "📦 Instalando dependencias..."
apt update
apt install -y nginx curl git unzip

# Instalar PHP 8.4
echo "📦 Instalando PHP 8.4..."
apt install -y lsb-release ca-certificates curl gnupg
curl -sSLo /tmp/debsuryorg-archive-keyring.deb https://packages.sury.org/debsuryorg-archive-keyring.deb
dpkg -i /tmp/debsuryorg-archive-keyring.deb
sh -c 'echo "deb [signed-by=/usr/share/keyrings/deb.sury.org-php.gpg] https://packages.sury.org/php/ $(lsb_release -cs) main" > /etc/apt/sources.list.d/php.list'
apt update

apt install -y php8.4-fpm php8.4-pgsql php8.4-redis php8.4-mbstring php8.4-xml php8.4-curl php8.4-zip php8.4-gd php8.4-intl

# Instalar Composer
curl -sS https://getcomposer.org/installer | php
mv composer.phar /usr/local/bin/composer

# Configurar Nginx
cat > /etc/nginx/sites-available/zabala <<'EOF'
server {
    listen 80;
    server_name _;
    root /var/www/zabala/public;
    index index.php index.html;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/var/run/php/php8.4-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }

    # Headers de seguridad
    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header X-XSS-Protection "1; mode=block" always;
    add_header Referrer-Policy "strict-origin-when-cross-origin" always;
}
EOF

ln -sf /etc/nginx/sites-available/zabala /etc/nginx/sites-enabled/
rm -f /etc/nginx/sites-enabled/default

# Crear directorio de la aplicación
mkdir -p /var/www/zabala
cd /var/www/zabala

# Crear estructura básica del proyecto
mkdir -p {config,src/{Controllers,Models,Services,Middleware,Utils},public/{views/{layouts,employees,vacations,payslips,documents},assets/{css,js,images}},migrations,storage,logs}

# Archivo index.php básico
cat > public/index.php <<'PHPEOF'
<?php
/**
 * Zabala Gailetak HR Portal - Entry Point
 */

require_once __DIR__ . '/../vendor/autoload.php';

// Cargar configuración
$config = require __DIR__ . '/../config/app.php';

// Configurar error reporting
if ($config['debug']) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}

// Iniciar sesión
session_start();

// Router básico
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

// Simple routing
switch ($uri) {
    case '/':
    case '/dashboard':
        require __DIR__ . '/views/dashboard.php';
        break;
    case '/login':
        require __DIR__ . '/views/auth/login.php';
        break;
    case '/employees':
        require __DIR__ . '/views/employees/index.php';
        break;
    case '/vacations':
        require __DIR__ . '/views/vacations/index.php';
        break;
    case '/api/health':
        header('Content-Type: application/json');
        echo json_encode(['status' => 'ok', 'time' => date('c')]);
        break;
    default:
        http_response_code(404);
        echo "404 - Not Found";
}
PHPEOF

# Crear configuración
cat > config/app.php <<'PHPEOF'
<?php
return [
    'name' => 'Zabala Gailetak HR Portal',
    'env' => 'production',
    'debug' => false,
    'url' => 'http://192.168.20.10',
    'timezone' => 'Europe/Madrid',
    'locale' => 'eu',
    
    'database' => [
        'driver' => 'pgsql',
        'host' => '192.168.20.20',
        'port' => 5432,
        'database' => 'zabala_hr_portal',
        'username' => 'zabala_user',
        'password' => 'ZabalaSecure2026!',
        'charset' => 'utf8',
    ],
    
    'redis' => [
        'host' => '192.168.20.20',
        'port' => 6379,
        'password' => 'ZabalaRedis2026!',
    ],
    
    'jwt' => [
        'secret' => 'your-secret-key-change-in-production',
        'expiration' => 3600,
        'refresh_expiration' => 604800,
    ],
];
PHPEOF

# Crear composer.json
cat > composer.json <<'PHPEOF'
{
    "name": "zabala/hr-portal",
    "description": "Zabala Gailetak HR Portal",
    "type": "project",
    "require": {
        "php": "^8.4",
        "firebase/php-jwt": "^6.10",
        "predis/predis": "^2.0",
        "bacon/bacon-qr-code": "^3.0",
        "spomky-labs/otphp": "^11.0"
    },
    "autoload": {
        "psr-4": {
            "ZabalaGailetak\\\\HrPortal\\\\": "src/"
        }
    },
    "config": {
        "optimize-autoloader": true
    }
}
PHPEOF

# Vista básica del dashboard
mkdir -p public/views
cat > public/views/dashboard.php <<'PHPEOF'
<!DOCTYPE html>
<html lang="eu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zabala Gailetak - HR Portal</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .container {
            background: white;
            padding: 3rem;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            max-width: 600px;
            width: 90%;
            text-align: center;
        }
        h1 {
            color: #333;
            margin-bottom: 1rem;
            font-size: 2.5rem;
        }
        .subtitle {
            color: #666;
            margin-bottom: 2rem;
        }
        .status {
            display: inline-block;
            background: #10b981;
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.875rem;
            margin-bottom: 2rem;
        }
        .features {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 1rem;
            margin-top: 2rem;
        }
        .feature {
            background: #f3f4f6;
            padding: 1rem;
            border-radius: 10px;
        }
        .feature-icon {
            font-size: 2rem;
            margin-bottom: 0.5rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🏭 Zabala Gailetak</h1>
        <p class="subtitle">Giza Baliabideen Ataria / Portal de Recursos Humanos</p>
        <span class="status">✅ Sistema Aktibo / Sistema Activo</span>
        
        <div class="features">
            <div class="feature">
                <div class="feature-icon">👥</div>
                <div>Langileak<br>Empleados</div>
            </div>
            <div class="feature">
                <div class="feature-icon">🏖️</div>
                <div>Oporrak<br>Vacaciones</div>
            </div>
            <div class="feature">
                <div class="feature-icon">💰</div>
                <div>Nominak<br>Nóminas</div>
            </div>
            <div class="feature">
                <div class="feature-icon">📄</div>
                <div>Dokumentuak<br>Documentos</div>
            </div>
        </div>
        
        <p style="margin-top: 2rem; color: #999; font-size: 0.875rem;">
            IP: <?= $_SERVER['SERVER_ADDR'] ?> | PHP: <?= phpversion() ?>
        </p>
    </div>
</body>
</html>
PHPEOF

# Instalar dependencias de Composer
composer install --no-dev --optimize-autoloader 2>/dev/null || true

# Permisos
chown -R www-data:www-data /var/www/zabala
chmod -R 755 /var/www/zabala/storage
chmod -R 755 /var/www/zabala/logs

# Reiniciar servicios
systemctl restart php8.4-fpm
systemctl restart nginx
systemctl enable nginx
systemctl enable php8.4-fpm

echo ""
echo "=========================================="
echo "✅ ZG-APP CONFIGURADO"
echo "=========================================="
echo "Servicios:"
echo "  - Nginx: http://192.168.20.10:80"
echo "  - PHP-FPM: 8.4"
echo ""
echo "Rutas:"
echo "  - /var/www/zabala"
echo "  - Logs: /var/log/nginx/"
echo ""
echo "Conexión a base de datos:"
echo "  - PostgreSQL: 192.168.20.20:5432"
echo "  - Redis: 192.168.20.20:6379"
