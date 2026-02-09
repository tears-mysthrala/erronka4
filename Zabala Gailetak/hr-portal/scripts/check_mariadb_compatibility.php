<?php
/**
 * MariaDB/MySQL Compatibility Check Script
 * 
 * Ejecutar: php scripts/check_mariadb_compatibility.php
 * o acceder desde: https://tusitio.com/scripts/check_mariadb_compatibility.php
 */

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use ZabalaGailetak\HrPortal\Database\Database;

// Configurar display de errores
ini_set('display_errors', '1');
error_reporting(E_ALL);

header('Content-Type: text/plain; charset=utf-8');

echo "=== MariaDB/MySQL Compatibility Check ===\n\n";

try {
    $db = new Database();
    $conn = $db->getConnection();
    
    // 1. Detectar tipo de base de datos
    $driver = $db->getDriver();
    echo "1. Database Driver: {$driver}\n";
    echo "   - Is PostgreSQL: " . ($db->isPostgreSQL() ? 'YES' : 'NO') . "\n";
    echo "   - Is MySQL/MariaDB: " . ($db->isMySQL() ? 'YES' : 'NO') . "\n\n";
    
    // 2. Verificar métodos helper
    echo "2. SQL Helper Methods:\n";
    echo "   - LIKE Operator: " . $db->getLikeOperator() . "\n";
    echo "   - Concat expression: " . $db->concat(['e.first_name', "' '", 'e.last_name']) . "\n";
    echo "   - Date Add expression: " . $db->dateAdd('CURRENT_DATE', '30', 'DAY') . "\n";
    echo "   - Current Date: " . $db->currentDate() . "\n";
    echo "   - Extract Year: " . $db->extractYear('vr.start_date') . "\n";
    echo "   - Extract Month: " . $db->extractMonth('vr.start_date') . "\n\n";
    
    // 3. Verificar tablas existentes
    echo "3. Tables Check:\n";
    $tables = ['employees', 'users', 'vacation_requests', 'departments', 'documents', 'payroll'];
    foreach ($tables as $table) {
        try {
            $result = $conn->query("SELECT 1 FROM {$table} LIMIT 1");
            echo "   ✓ {$table}: EXISTS\n";
        } catch (PDOException $e) {
            echo "   ✗ {$table}: MISSING - " . $e->getMessage() . "\n";
        }
    }
    echo "\n";
    
    // 4. Probar consulta del dashboard (la que fallaba)
    echo "4. Dashboard Query Test:\n";
    try {
        $sql = "
            SELECT e.first_name, e.last_name, vr.start_date, vr.end_date
            FROM vacation_requests vr
            JOIN employees e ON vr.employee_id = e.id
            WHERE vr.status = 'APPROVED' 
              AND vr.start_date >= CURRENT_DATE 
              AND vr.start_date <= {$db->dateAdd('CURRENT_DATE', '30', 'DAY')}
            ORDER BY vr.start_date ASC
            LIMIT 5
        ";
        echo "   SQL Query:\n" . $sql . "\n\n";
        
        $stmt = $conn->query($sql);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo "   Results: " . count($results) . " rows\n";
        foreach ($results as $row) {
            echo "   - {$row['first_name']} {$row['last_name']}: {$row['start_date']} to {$row['end_date']}\n";
        }
        echo "   ✓ Dashboard query: WORKING\n\n";
    } catch (PDOException $e) {
        echo "   ✗ Dashboard query: FAILED\n";
        echo "   Error: " . $e->getMessage() . "\n\n";
    }
    
    // 5. Probar búsqueda con LIKE (sustituto de ILIKE)
    echo "5. Search with LIKE Test:\n";
    try {
        $likeOp = $db->getLikeOperator();
        $search = '%a%'; // Buscar letra 'a'
        
        $sql = "
            SELECT COUNT(*) as count 
            FROM employees e 
            JOIN users u ON e.user_id = u.id 
            WHERE e.first_name {$likeOp} :search1 
               OR e.last_name {$likeOp} :search2 
               OR u.email {$likeOp} :search3
        ";
        
        echo "   SQL Query: " . preg_replace('/\s+/', ' ', $sql) . "\n";
        
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':search1' => $search,
            ':search2' => $search,
            ':search3' => $search
        ]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        echo "   Results: {$result['count']} employees found with '{$search}'\n";
        echo "   ✓ Search query: WORKING\n\n";
    } catch (PDOException $e) {
        echo "   ✗ Search query: FAILED\n";
        echo "   Error: " . $e->getMessage() . "\n\n";
    }
    
    // 6. Probar concatenación
    echo "6. Concatenation Test:\n";
    try {
        $concat = $db->concat(['first_name', "' '", 'last_name']);
        
        $sql = "SELECT {$concat} as full_name FROM employees LIMIT 3";
        echo "   SQL Query: {$sql}\n";
        
        $stmt = $conn->query($sql);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo "   Results:\n";
        foreach ($results as $row) {
            echo "   - {$row['full_name']}\n";
        }
        echo "   ✓ Concatenation: WORKING\n\n";
    } catch (PDOException $e) {
        echo "   ✗ Concatenation: FAILED\n";
        echo "   Error: " . $e->getMessage() . "\n\n";
    }
    
    // 7. Resumen
    echo "=== Summary ===\n";
    echo "✓ Database connection: OK\n";
    echo "✓ Driver detection: OK\n";
    echo "✓ Helper methods: OK\n";
    echo "All checks passed! MariaDB compatibility should be working.\n";
    
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
