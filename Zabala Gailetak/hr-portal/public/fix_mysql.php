<?php
/**
 * MySQL Fix Script - Version Simple
 * No requiere Composer ni dependencias
 * Access: https://yourdomain.com/fix_mysql.php?key=zabala-migrate-2026
 */

// Security check
$secretKey = $_GET['key'] ?? '';
if ($secretKey !== 'zabala-migrate-2026') {
    http_response_code(403);
    die(json_encode(['error' => 'Access denied. Use ?key=zabala-migrate-2026']));
}

header('Content-Type: application/json');

// Enable error display for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

try {
    // Load config from config file or use defaults
    $configFile = __DIR__ . '/../config/database.php';
    $dbConfig = [
        'host' => 'localhost',
        'database' => '',
        'username' => '',
        'password' => '',
        'charset' => 'utf8mb4'
    ];
    
    // Try to read config from file
    if (file_exists($configFile)) {
        $content = file_get_contents($configFile);
        // Extract credentials using regex
        if (preg_match('/host["\']?\s*=>\s*["\']([^"\']+)/', $content, $m)) $dbConfig['host'] = $m[1];
        if (preg_match('/database["\']?\s*=>\s*["\']([^"\']+)/', $content, $m)) $dbConfig['database'] = $m[1];
        if (preg_match('/username["\']?\s*=>\s*["\']([^"\']+)/', $content, $m)) $dbConfig['username'] = $m[1];
        if (preg_match('/password["\']?\s*=>\s*["\']([^"\']*)/', $content, $m)) $dbConfig['password'] = $m[1];
    }
    
    // Check if credentials are available
    if (empty($dbConfig['database']) || empty($dbConfig['username'])) {
        // Try to get from environment variables (InfinityFree)
        $dbConfig['host'] = getenv('DB_HOST') ?: 'sql306.infinityfree.com';
        $dbConfig['database'] = getenv('DB_NAME') ?: getenv('MYSQL_DATABASE') ?: '';
        $dbConfig['username'] = getenv('DB_USER') ?: getenv('MYSQL_USERNAME') ?: '';
        $dbConfig['password'] = getenv('DB_PASSWORD') ?: getenv('MYSQL_PASSWORD') ?: '';
    }
    
    $dsn = "mysql:host={$dbConfig['host']};dbname={$dbConfig['database']};charset={$dbConfig['charset']}";
    $pdo = new PDO($dsn, $dbConfig['username'], $dbConfig['password'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
    
    $results = [];
    
    // 1. Check if available_days column exists
    $stmt = $pdo->query("SHOW COLUMNS FROM vacation_balances LIKE 'available_days'");
    $columnExists = $stmt->fetch() !== false;
    
    if (!$columnExists) {
        // Try to add as generated column (MySQL 5.7.6+)
        try {
            $pdo->exec("ALTER TABLE vacation_balances 
                       ADD COLUMN available_days DECIMAL(5,2) 
                       GENERATED ALWAYS AS (total_days - used_days - pending_days) STORED");
            $results[] = '✅ Column available_days added as GENERATED column';
        } catch (PDOException $e) {
            // If generated column fails (older MySQL), add as regular column
            $pdo->exec("ALTER TABLE vacation_balances 
                       ADD COLUMN available_days DECIMAL(5,2) DEFAULT 22.0");
            $results[] = '✅ Column available_days added as regular column';
            
            // Create trigger to update available_days
            $pdo->exec("DROP TRIGGER IF EXISTS trg_update_available_days");
            $pdo->exec("CREATE TRIGGER trg_update_available_days 
                       BEFORE UPDATE ON vacation_balances
                       FOR EACH ROW
                       BEGIN
                           SET NEW.available_days = NEW.total_days - NEW.used_days - NEW.pending_days;
                       END");
            $results[] = '✅ Trigger created';
        }
    } else {
        $results[] = 'ℹ️ Column available_days already exists';
    }
    
    // 2. Create vacation_balances table if not exists
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
    )");
    $results[] = '✅ Table vacation_balances verified/created';
    
    // 3. Ensure all employees have vacation balance for current year
    $currentYear = date('Y');
    $stmt = $pdo->prepare("
        INSERT INTO vacation_balances (employee_id, year, total_days, used_days, pending_days)
        SELECT e.id, :year, 22.0, 0.0, 0.0
        FROM employees e
        LEFT JOIN vacation_balances vb ON e.id = vb.employee_id AND vb.year = :year2
        WHERE vb.id IS NULL
    ");
    $stmt->execute(['year' => $currentYear, 'year2' => $currentYear]);
    $insertedCount = $stmt->rowCount();
    $results[] = "✅ Created {$insertedCount} vacation balance records for {$currentYear}";
    
    // 4. Update existing records
    $pdo->exec("
        UPDATE vacation_balances 
        SET available_days = total_days - used_days - pending_days
        WHERE available_days IS NULL 
           OR available_days != (total_days - used_days - pending_days)
    ");
    $results[] = '✅ Updated available_days calculations';
    
    // 5. Verify
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM vacation_balances WHERE year = {$currentYear}");
    $balanceCount = $stmt->fetch()['count'] ?? 0;
    $results[] = "📊 Total records for {$currentYear}: {$balanceCount}";
    
    echo json_encode([
        'success' => true,
        'message' => 'Database fix completed',
        'results' => $results,
        'timestamp' => date('Y-m-d H:i:s')
    ], JSON_PRETTY_PRINT);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage(),
        'file' => $e->getFile(),
        'line' => $e->getLine()
    ], JSON_PRETTY_PRINT);
}
