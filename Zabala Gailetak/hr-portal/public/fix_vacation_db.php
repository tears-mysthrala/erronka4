<?php
/**
 * Fix Vacation Database - Usa la configuración existente del proyecto
 * Access: https://zabala-gailetak.infinityfreeapp.com/fix_vacation_db.php?key=zabala-migrate-2026
 */

if (($_GET['key'] ?? '') !== 'zabala-migrate-2026') {
    http_response_code(403);
    die('Access denied');
}

header('Content-Type: application/json');
ini_set('display_errors', 1);
error_reporting(E_ALL);

define('ROOT_PATH', dirname(__DIR__));

try {
    // Cargar configuración
    $config = require ROOT_PATH . '/config/config.php';
    $dbConfig = $config['database'];
    
    // Conectar a la base de datos
    $dsn = sprintf(
        '%s:host=%s;dbname=%s;charset=%s',
        $dbConfig['driver'],
        $dbConfig['host'],
        $dbConfig['database'],
        $dbConfig['charset']
    );
    
    $pdo = new PDO($dsn, $dbConfig['username'], $dbConfig['password'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
    
    $results = [];
    
    // 1. Crear tabla si no existe
    $pdo->exec("CREATE TABLE IF NOT EXISTS vacation_balances (
        id INT AUTO_INCREMENT PRIMARY KEY,
        employee_id VARCHAR(36) NOT NULL,
        year INT NOT NULL,
        total_days DECIMAL(5,2) NOT NULL DEFAULT 22.0,
        used_days DECIMAL(5,2) NOT NULL DEFAULT 0.0,
        pending_days DECIMAL(5,2) NOT NULL DEFAULT 0.0,
        available_days DECIMAL(5,2),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        UNIQUE KEY unique_employee_year (employee_id, year)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
    $results[] = '✅ Tabla vacation_balances verificada/creada';
    
    // 2. Verificar si la columna available_days existe
    $stmt = $pdo->query("SHOW COLUMNS FROM vacation_balances LIKE 'available_days'");
    if (!$stmt->fetch()) {
        try {
            // Intentar agregar como columna generada (MySQL 5.7.6+)
            $pdo->exec("ALTER TABLE vacation_balances 
                       ADD COLUMN available_days DECIMAL(5,2) 
                       GENERATED ALWAYS AS (total_days - used_days - pending_days) STORED");
            $results[] = '✅ Columna available_days agregada (GENERATED)';
        } catch (PDOException $e) {
            // Fallback para MySQL antiguo
            $pdo->exec("ALTER TABLE vacation_balances 
                       ADD COLUMN available_days DECIMAL(5,2) DEFAULT 22.0");
            
            // Crear trigger para mantener actualizado el valor
            $pdo->exec("DROP TRIGGER IF EXISTS trg_update_available_days");
            $pdo->exec("CREATE TRIGGER trg_update_available_days 
                       BEFORE UPDATE ON vacation_balances
                       FOR EACH ROW
                       BEGIN
                           SET NEW.available_days = NEW.total_days - NEW.used_days - NEW.pending_days;
                       END");
            $results[] = '✅ Columna available_days agregada (con trigger)';
        }
    } else {
        $results[] = 'ℹ️ Columna available_days ya existe';
    }
    
    // 3. Actualizar valores existentes
    $pdo->exec("
        UPDATE vacation_balances 
        SET available_days = total_days - used_days - pending_days
        WHERE available_days IS NULL 
           OR available_days != (total_days - used_days - pending_days)
    ");
    $results[] = '✅ Valores actualizados';
    
    // 4. Crear balances para empleados sin ellos
    $year = date('Y');
    $stmt = $pdo->prepare("
        INSERT INTO vacation_balances (employee_id, year, total_days, used_days, pending_days)
        SELECT e.id, :year, 22.0, 0.0, 0.0
        FROM employees e
        LEFT JOIN vacation_balances vb ON e.id = vb.employee_id AND vb.year = :year2
        WHERE vb.id IS NULL
    ");
    $stmt->execute(['year' => $year, 'year2' => $year]);
    $newBalances = $stmt->rowCount();
    $results[] = "✅ {$newBalances} balances creados para el año {$year}";
    
    // 5. Verificar totales
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM vacation_balances WHERE year = $year");
    $total = $stmt->fetch()['total'] ?? 0;
    $results[] = "📊 Total de balances en {$year}: {$total}";
    
    echo json_encode([
        'success' => true,
        'message' => 'Base de datos actualizada correctamente',
        'results' => $results,
        'timestamp' => date('Y-m-d H:i:s')
    ], JSON_PRETTY_PRINT);
    
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Database error: ' . $e->getMessage(),
        'code' => $e->getCode()
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage(),
        'file' => basename($e->getFile()),
        'line' => $e->getLine()
    ]);
}
