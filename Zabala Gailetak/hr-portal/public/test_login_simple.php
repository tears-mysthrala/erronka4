<?php
/**
 * Simple Login Test
 * Prueba el login de la API sin depender del framework
 * URL: /test_login_simple.php
 */

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET');
header('Access-Control-Allow-Headers: Content-Type');

ini_set('display_errors', 0);
error_reporting(0);

// Si es GET, mostrar instrucciones
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    echo json_encode([
        'message' => 'Login test endpoint',
        'usage' => 'Send POST request with JSON body: {"email":"admin@zabalagailetak.com","password":"your_password"}'
    ]);
    exit;
}

// Procesar POST
$input = file_get_contents('php://input');
$data = json_decode($input, true);

if (!$data) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid JSON', 'received' => $input]);
    exit;
}

$email = $data['email'] ?? '';
$password = $data['password'] ?? '';

try {
    // Cargar .env
    $envFile = __DIR__ . '/../.env';
    if (file_exists($envFile)) {
        $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            if (strpos($line, '#') === 0) continue;
            if (strpos($line, '=') === false) continue;
            list($k, $v) = explode('=', $line, 2);
            $_ENV[trim($k)] = trim($v, '"\' ');
        }
    }
    
    // Conectar a BD
    $host = $_ENV['DB_HOST'] ?? 'localhost';
    $db = $_ENV['DB_NAME'] ?? '';
    $user = $_ENV['DB_USER'] ?? '';
    $pass = $_ENV['DB_PASSWORD'] ?? '';
    
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
    
    // Buscar usuario
    $stmt = $pdo->prepare('SELECT id, email, password_hash, role, mfa_enabled, account_locked FROM users WHERE email = ?');
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    
    if (!$user) {
        http_response_code(401);
        echo json_encode(['error' => 'Usuario no encontrado', 'email' => $email]);
        exit;
    }
    
    if ($user['account_locked']) {
        http_response_code(403);
        echo json_encode(['error' => 'Cuenta bloqueada']);
        exit;
    }
    
    // Verificar contraseña
    $passwordMatch = password_verify($password, $user['password_hash']);
    
    if (!$passwordMatch) {
        http_response_code(401);
        echo json_encode([
            'error' => 'Contraseña incorrecta',
            'debug' => [
                'provided_password_length' => strlen($password),
                'hash_starts_with' => substr($user['password_hash'], 0, 7)
            ]
        ]);
        exit;
    }
    
    // Éxito
    echo json_encode([
        'success' => true,
        'message' => 'Login exitoso',
        'user' => [
            'id' => $user['id'],
            'email' => $user['email'],
            'role' => $user['role'],
            'mfa_enabled' => (bool)$user['mfa_enabled']
        ]
    ]);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'error' => 'Server error',
        'message' => $e->getMessage()
    ]);
}
