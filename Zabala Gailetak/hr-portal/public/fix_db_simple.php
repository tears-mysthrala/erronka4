<?php
/**
 * MySQL Fix - Versión Mínima
 * Ejecuta: https://tu-dominio.com/fix_db_simple.php?key=zabala-migrate-2026
 */

if (($_GET['key'] ?? '') !== 'zabala-migrate-2026') {
    die('Access denied');
}

header('Content-Type: application/json');
ini_set('display_errors', 1);
error_reporting(E_ALL);

try {
    // Intentar cargar configuración desde diferentes fuentes
    $host = 'localhost';
    $db = '';
    $user = '';
    $pass = '';
    
    // 1. Intentar desde archivo .env
    $envFile = __DIR__ . '/../.env';
    if (file_exists($envFile)) {
        $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            if (strpos($line, '=') !== false && strpos($line, '#') !== 0) {
                list($k, $v) = explode('=', $line, 2);
                $k = trim($k);
                $v = trim($v, '"\'');
                if ($k === 'DB_HOST') $host = $v;
                if ($k === 'DB_NAME') $db = $v;
                if ($k === 'DB_USER') $user = $v;
                if ($k === 'DB_PASSWORD') $pass = $v;
            }
        }
    }
    
    // 2. Si no hay credenciales, mostrar error con instrucciones
    if (empty($db) || empty($user)) {
        echo json_encode([
            'error' => 'No database credentials found',
            'instructions' => [
                'Option 1: Create .env file in htdocs/ with:',
                'DB_HOST=your_mysql_host',
                'DB_NAME=your_database_name', 
                'DB_USER=your_username',
                'DB_PASSWORD=your_password',
                '',
                'Option 2: Run SQL directly in phpMyAdmin:',
                'ALTER TABLE vacation_balances ADD COLUMN available_days DECIMAL(5,2) AS (total_days - used_days - pending_days);'
            ]
        ]);
        exit;
    }
    
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
    
    $results = [];
    
    // Verificar si la columna existe
    $stmt = $pdo->query("SHOW COLUMNS FROM vacation_balances LIKE 'available_days'");
    if (!$stmt->fetch()) {
        // Agregar columna
        $pdo->exec("ALTER TABLE vacation_balances 
                   ADD COLUMN available_days DECIMAL(5,2) 
                   AS (total_days - used_days - pending_days) STORED");
        $results[] = 'Columna available_days agregada';
    } else {
        $results[] = 'La columna ya existe';
    }
    
    // Crear registros para empleados sin balance
    $year = date('Y');
    $pdo->exec("
        INSERT INTO vacation_balances (employee_id, year, total_days, used_days, pending_days)
        SELECT id, $year, 22, 0, 0 FROM employees
        WHERE id NOT IN (SELECT employee_id FROM vacation_balances WHERE year = $year)
    ");
    $results[] = 'Balances creados para empleados nuevos';
    
    echo json_encode(['success' => true, 'results' => $results]);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
