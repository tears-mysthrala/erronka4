<?php
/**
 * Fix Database - Super simple version
 * No dependencies, no Composer
 * Just copy this file to /public/ and run
 * URL: https://yourdomain.com/fix_db.php?key=zabala-migrate-2026
 */

// Security check
if (($_GET['key'] ?? '') !== 'zabala-migrate-2026') {
    die('Access denied. Use ?key=zabala-migrate-2026');
}

// Mostrar errores para debug
ini_set('display_errors', 1);
error_reporting(E_ALL);
header('Content-Type: text/plain');

echo "=== DATABASE FIX SCRIPT ===\n\n";

try {
    // Leer configuración de .env file
    $envFile = __DIR__ . '/../.env';
    $config = [];
    
    if (file_exists($envFile)) {
        $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            if (strpos($line, '#') === 0) continue;
            if (strpos($line, '=') === false) continue;
            list($k, $v) = explode('=', $line, 2);
            $config[trim($k)] = trim($v, '"\' ');
        }
    }
    
    // Configuración de BD
    $host = $config['DB_HOST'] ?? 'sql306.infinityfree.com';
    $db   = $config['DB_NAME'] ?? 'if0_40982238_zabala_gailetak';
    $user = $config['DB_USER'] ?? 'if0_40982238';
    $pass = $config['DB_PASSWORD'] ?? '';
    
    echo "Connecting to: $host / $db\n";
    echo "User: $user\n\n";
    
    // Conectar
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
    
    echo "✅ Connected successfully!\n\n";
    
    // 1. Verificar si la columna existe
    echo "1. Checking available_days column...\n";
    $stmt = $pdo->query("SHOW COLUMNS FROM vacation_balances LIKE 'available_days'");
    if ($stmt->fetch()) {
        echo "   ℹ️ Column already exists\n";
    } else {
        echo "   📝 Adding column...\n";
        try {
            $pdo->exec("ALTER TABLE vacation_balances 
                       ADD COLUMN available_days DECIMAL(5,2) 
                       AS (total_days - used_days - pending_days) STORED");
            echo "   ✅ Column added (GENERATED)\n";
        } catch (PDOException $e) {
            // MySQL antiguo
            $pdo->exec("ALTER TABLE vacation_balances 
                       ADD COLUMN available_days DECIMAL(5,2) DEFAULT 22");
            echo "   ✅ Column added (DEFAULT)\n";
        }
    }
    
    // 2. Crear tabla si no existe
    echo "\n2. Checking vacation_balances table...\n";
    $pdo->exec("CREATE TABLE IF NOT EXISTS vacation_balances (
        id INT AUTO_INCREMENT PRIMARY KEY,
        employee_id VARCHAR(36) NOT NULL,
        year INT NOT NULL,
        total_days DECIMAL(5,2) NOT NULL DEFAULT 22,
        used_days DECIMAL(5,2) NOT NULL DEFAULT 0,
        pending_days DECIMAL(5,2) NOT NULL DEFAULT 0,
        available_days DECIMAL(5,2),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        UNIQUE KEY uk_emp_year (employee_id, year)
    ) ENGINE=InnoDB");
    echo "   ✅ Table ready\n";
    
    // 3. Insertar balances
    echo "\n3. Creating vacation balances...\n";
    $year = date('Y');
    $stmt = $pdo->prepare("INSERT IGNORE INTO vacation_balances 
        (employee_id, year, total_days, used_days, pending_days)
        SELECT id, ?, 22, 0, 0 FROM employees");
    $stmt->execute([$year]);
    $inserted = $stmt->rowCount();
    echo "   ✅ Created $inserted new balance(s) for year $year\n";
    
    // 4. Actualizar valores
    echo "\n4. Updating calculations...\n";
    $pdo->exec("UPDATE vacation_balances 
                SET available_days = total_days - used_days - pending_days
                WHERE available_days IS NULL");
    echo "   ✅ Calculations updated\n";
    
    // 5. Resumen
    echo "\n=== SUMMARY ===\n";
    $stmt = $pdo->query("SELECT COUNT(*) FROM vacation_balances WHERE year = $year");
    $total = $stmt->fetchColumn();
    echo "Total balances for $year: $total\n";
    
    $stmt = $pdo->query("SELECT COUNT(*) FROM employees");
    $emps = $stmt->fetchColumn();
    echo "Total employees: $emps\n";
    
    if ($total >= $emps) {
        echo "\n✅ SUCCESS! All employees have vacation balances.\n";
    } else {
        echo "\n⚠️ WARNING: Some employees may be missing balances.\n";
    }
    
    echo "\n=== FIX COMPLETE ===\n";
    echo "You can now access /vacations\n";
    
} catch (Exception $e) {
    echo "\n❌ ERROR:\n";
    echo $e->getMessage() . "\n";
    echo "File: " . basename($e->getFile()) . " Line: " . $e->getLine() . "\n";
}
