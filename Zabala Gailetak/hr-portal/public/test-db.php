<?php
/**
 * Database Connection Test
 * Acceder a: https://zabala-gailetak.infinityfreeapp.com/test-db.php
 */

// Mostrar todos los errores para debugging
error_reporting(E_ALL);
ini_set('display_errors', '1');

echo "<h1>Database Connection Test</h1>";
echo "<hr>";

// Define root path
define('ROOT_PATH', dirname(__DIR__));

// Load environment variables
if (file_exists(ROOT_PATH . '/.env')) {
    $envFile = file_get_contents(ROOT_PATH . '/.env');
    $lines = explode("\n", $envFile);
    
    foreach ($lines as $line) {
        $line = trim($line);
        if (empty($line) || str_starts_with($line, '#')) {
            continue;
        }
        
        if (str_contains($line, '=')) {
            list($key, $value) = explode('=', $line, 2);
            $key = trim($key);
            $value = trim($value, " \t\n\r\0\x0B\"'");
            $_ENV[$key] = $value;
        }
    }
    echo "✅ Archivo .env cargado<br>";
} else {
    echo "❌ Archivo .env NO encontrado<br>";
}

echo "<hr>";

// Get database configuration
$driver = $_ENV['DB_DRIVER'] ?? 'mysql';
$host = $_ENV['DB_HOST'] ?? 'sql107.infinityfree.com';
$port = $_ENV['DB_PORT'] ?? 3306;
$dbname = $_ENV['DB_NAME'] ?? 'if0_40982238_zabala_gailetak';
$user = $_ENV['DB_USER'] ?? 'if0_40982238';
$pass = $_ENV['DB_PASSWORD'] ?? '';

echo "<h2>Configuration:</h2>";
echo "<pre>";
echo "Driver: $driver\n";
echo "Host: $host\n";
echo "Port: $port\n";
echo "Database: $dbname\n";
echo "User: $user\n";
echo "Password: " . (empty($pass) ? '(empty)' : '***') . "\n";
echo "</pre>";

echo "<hr>";
echo "<h2>Connection Test:</h2>";

try {
    if ($driver === 'mysql') {
        $dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4";
    } else {
        $dsn = "pgsql:host=$host;port=$port;dbname=$dbname";
    }
    
    echo "DSN: $dsn<br><br>";
    
    $pdo = new PDO(
        $dsn,
        $user,
        $pass,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );
    
    echo "✅ <strong>Conexión exitosa!</strong><br><br>";
    
    // Test query
    $stmt = $pdo->query('SELECT DATABASE() as db, VERSION() as version');
    $result = $stmt->fetch();
    
    echo "<h3>Server Info:</h3>";
    echo "<pre>";
    print_r($result);
    echo "</pre>";
    
    // Test users table
    echo "<h3>Users Table Test:</h3>";
    $stmt = $pdo->query('SELECT COUNT(*) as count FROM users');
    $count = $stmt->fetch();
    echo "Total users: " . $count['count'] . "<br>";
    
    // Test admin user
    $stmt = $pdo->prepare('SELECT id, email, role FROM users WHERE email = ?');
    $stmt->execute(['admin@zabalagailetak.com']);
    $admin = $stmt->fetch();
    
    if ($admin) {
        echo "<br>✅ Usuario admin encontrado:<br>";
        echo "<pre>";
        print_r($admin);
        echo "</pre>";
    } else {
        echo "<br>❌ Usuario admin NO encontrado<br>";
    }
    
} catch (PDOException $e) {
    echo "❌ <strong>Error de conexión:</strong><br>";
    echo "<pre style='color: red;'>";
    echo $e->getMessage();
    echo "</pre>";
}

echo "<hr>";
echo "<small>Delete this file after testing for security reasons.</small>";
