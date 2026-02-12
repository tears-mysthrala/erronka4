<?php
/**
 * API Test - Script simple de diagnóstico
 * Accede: https://zabala-gailetak.infinityfreeapp.com/api_test.php
 */

header('Content-Type: application/json');
ini_set('display_errors', 0);

$result = [
    'timestamp' => date('Y-m-d H:i:s'),
    'php_version' => PHP_VERSION,
    'tests' => []
];

// Test 1: Cargar .env
try {
    $envFile = __DIR__ . '/../.env';
    if (file_exists($envFile)) {
        $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            if (strpos($line, '#') === 0) continue;
            if (strpos($line, '=') === false) continue;
            list($k, $v) = explode('=', $line, 2);
            $_ENV[trim($k)] = trim($v, '"\' ');
        }
        $result['tests']['env'] = ['status' => 'ok', 'message' => 'Environment loaded'];
    } else {
        $result['tests']['env'] = ['status' => 'error', 'message' => '.env file not found'];
    }
} catch (Exception $e) {
    $result['tests']['env'] = ['status' => 'error', 'message' => $e->getMessage()];
}

// Test 2: Conectar a BD
try {
    $host = $_ENV['DB_HOST'] ?? 'localhost';
    $db = $_ENV['DB_NAME'] ?? '';
    $user = $_ENV['DB_USER'] ?? '';
    $pass = $_ENV['DB_PASSWORD'] ?? '';
    
    if (empty($db) || empty($user)) {
        throw new Exception('Database credentials not configured');
    }
    
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
    
    $result['tests']['database'] = ['status' => 'ok', 'message' => 'Connected to ' . $db];
    
    // Test 3: Verificar tabla vacation_balances
    try {
        $stmt = $pdo->query("SHOW TABLES LIKE 'vacation_balances'");
        if ($stmt->fetch()) {
            $result['tests']['vacation_table'] = ['status' => 'ok', 'message' => 'Table exists'];
            
            // Verificar columnas
            $stmt = $pdo->query("SHOW COLUMNS FROM vacation_balances");
            $columns = $stmt->fetchAll(PDO::FETCH_COLUMN);
            $result['tests']['vacation_columns'] = ['status' => 'ok', 'columns' => $columns];
            
            // Contar registros
            $stmt = $pdo->query("SELECT COUNT(*) FROM vacation_balances WHERE year = YEAR(CURDATE())");
            $count = $stmt->fetchColumn();
            $result['tests']['vacation_records'] = ['status' => 'ok', 'count' => $count];
        } else {
            $result['tests']['vacation_table'] = ['status' => 'error', 'message' => 'Table does not exist'];
        }
    } catch (Exception $e) {
        $result['tests']['vacation_table'] = ['status' => 'error', 'message' => $e->getMessage()];
    }
    
    // Test 4: Verificar usuario admin
    try {
        $stmt = $pdo->prepare("SELECT id, email, role FROM users WHERE email = ?");
        $stmt->execute(['admin@zabalagailetak.com']);
        $admin = $stmt->fetch();
        if ($admin) {
            $result['tests']['admin_user'] = ['status' => 'ok', 'user' => $admin];
        } else {
            $result['tests']['admin_user'] = ['status' => 'error', 'message' => 'Admin user not found'];
        }
    } catch (Exception $e) {
        $result['tests']['admin_user'] = ['status' => 'error', 'message' => $e->getMessage()];
    }
    
} catch (Exception $e) {
    $result['tests']['database'] = ['status' => 'error', 'message' => $e->getMessage()];
}

// Test 5: Verificar archivos del framework
try {
    $requiredFiles = [
        '../vendor/autoload.php' => 'Composer autoload',
        '../src/Core/ClassLoader.php' => 'Native autoloader',
        '../config/config.php' => 'Config',
        '../config/routes.php' => 'Routes'
    ];
    
    foreach ($requiredFiles as $file => $name) {
        $path = __DIR__ . '/' . $file;
        if (file_exists($path)) {
            $result['tests']['files'][$name] = ['status' => 'ok'];
        } else {
            $result['tests']['files'][$name] = ['status' => 'error', 'message' => 'File not found: ' . $file];
        }
    }
} catch (Exception $e) {
    $result['tests']['files'] = ['status' => 'error', 'message' => $e->getMessage()];
}

// Summary
$errors = 0;
foreach ($result['tests'] as $test) {
    if (is_array($test) && isset($test['status']) && $test['status'] === 'error') {
        $errors++;
    }
    if (is_array($test) && !isset($test['status'])) {
        foreach ($test as $subtest) {
            if (isset($subtest['status']) && $subtest['status'] === 'error') {
                $errors++;
            }
        }
    }
}

$result['summary'] = [
    'total_tests' => count($result['tests']),
    'errors' => $errors,
    'status' => $errors === 0 ? 'all_ok' : 'has_errors'
];

echo json_encode($result, JSON_PRETTY_PRINT);
