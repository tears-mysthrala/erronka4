<?php
/**
 * MySQL Migration Script for InfinityFree
 * Run this script to fix the vacation_balances table
 * Access: https://yourdomain.com/migrate_mysql.php
 */

require_once __DIR__ . '/../vendor/autoload.php';

use ZabalaGailetak\HrPortal\Database\Database;
use ZabalaGailetak\HrPortal\Utils\Logger;

// Security: Only allow local access or with secret key
$secretKey = $_GET['key'] ?? '';
$expectedKey = $_ENV['MIGRATE_KEY'] ?? 'zabala-migrate-2026';

if ($secretKey !== $expectedKey) {
    http_response_code(403);
    echo json_encode(['error' => 'Access denied. Use ?key=zabala-migrate-2026']);
    exit;
}

header('Content-Type: application/json');

try {
    $db = Database::getInstance();
    $pdo = $db->getConnection();
    
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
            $results[] = 'Column available_days added as GENERATED column';
        } catch (PDOException $e) {
            // If generated column fails (older MySQL), add as regular column
            $pdo->exec("ALTER TABLE vacation_balances 
                       ADD COLUMN available_days DECIMAL(5,2) DEFAULT 22.0");
            $results[] = 'Column available_days added as regular column (older MySQL)';
            
            // Create trigger to update available_days
            $pdo->exec("DROP TRIGGER IF EXISTS trg_update_available_days");
            $pdo->exec("CREATE TRIGGER trg_update_available_days 
                       BEFORE UPDATE ON vacation_balances
                       FOR EACH ROW
                       BEGIN
                           SET NEW.available_days = NEW.total_days - NEW.used_days - NEW.pending_days;
                       END");
            $results[] = 'Trigger created to update available_days';
        }
    } else {
        $results[] = 'Column available_days already exists';
    }
    
    // 2. Ensure all employees have vacation balance for current year
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
    $results[] = "Created {$insertedCount} vacation balance records for year {$currentYear}";
    
    // 3. Update existing records without available_days calculated
    if (!$columnExists || true) {
        $pdo->exec("
            UPDATE vacation_balances 
            SET available_days = total_days - used_days - pending_days
            WHERE available_days IS NULL 
               OR available_days != (total_days - used_days - pending_days)
        ");
        $results[] = 'Updated available_days calculations';
    }
    
    // 4. Verify the fix
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM vacation_balances WHERE year = {$currentYear}");
    $balanceCount = $stmt->fetch()['count'] ?? 0;
    $results[] = "Total vacation balance records for {$currentYear}: {$balanceCount}";
    
    echo json_encode([
        'success' => true,
        'message' => 'Migration completed successfully',
        'results' => $results,
        'timestamp' => date('Y-m-d H:i:s')
    ], JSON_PRETTY_PRINT);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage(),
        'trace' => $e->getTraceAsString()
    ], JSON_PRETTY_PRINT);
}
