<?php
// Script de Migración Web para InfinityFree
// Advertencia: Eliminar después de usar

ini_set('display_errors', 1);
error_reporting(E_ALL);

define('ROOT_PATH', dirname(__DIR__));

// Load Core
require ROOT_PATH . '/src/Core/ClassLoader.php';
\ZabalaGailetak\HrPortal\Core\ClassLoader::register();
\ZabalaGailetak\HrPortal\Core\EnvLoader::load(ROOT_PATH);
\ZabalaGailetak\HrPortal\Core\EnvLoader::ensurePopulated();

use ZabalaGailetak\HrPortal\Database\Database;
use ZabalaGailetak\HrPortal\Services\AuditLogger; // Assuming AuditLogger is needed for migrations

echo "<h1>Migración de Base de Datos</h1>";

try {
    $db = new Database();
    $conn = $db->getConnection();
    echo "<p>✅ Conexión establecida</p>";
    
    // Get all sql files
    $migrationFiles = glob(ROOT_PATH . '/migrations/*.sql');
    sort($migrationFiles); // Ensure order (001, 002...)
    
    // Get the current database driver from config
    // Safely access configuration, fall back to default if missing
    $dbConfig = $db->config['database'] ?? [];
    $dbDriver = $dbConfig['driver'] ?? $_ENV['DB_DRIVER'] ?? 'pgsql'; // Use driver from .env or config

    echo "<h3>Driver detectado: " . htmlspecialchars($dbDriver) . "</h3>";

    // Clean up existing tables to prevent partial migration errors
    if ($dbDriver === 'mysql') {
        echo "<p>Limpiando tablas existentes...</p>";
        $tables = [
            'sessions', 'audit_logs', 'notifications', 'complaint_updates', 'complaints', 
            'messages', 'conversation_participants', 'conversations', 'payroll', 
            'document_requests', 'documents', 'vacations', 'vacation_balances', 
            'employees', 'departments', 'users'
        ];
        
        // Disable foreign key checks to allow dropping tables in any order
        $db->getConnection()->exec("SET FOREIGN_KEY_CHECKS = 0");
        foreach ($tables as $table) {
            $db->getConnection()->exec("DROP TABLE IF EXISTS $table");
        }
        // Try to drop the function if it exists (ignoring errors if no permission, but worth a try)
        try {
            $db->getConnection()->exec("DROP FUNCTION IF EXISTS uuid_generate_v4");
        } catch (\Throwable $e) {}
        
        $db->getConnection()->exec("SET FOREIGN_KEY_CHECKS = 1");
    }

    foreach ($migrationFiles as $file) {
        $filename = basename($file);
        echo "<hr><h3>Procesando: $filename</h3>";
        
        $sql = file_get_contents($file);
        
        if ($dbDriver === 'mysql') {
            echo "<p>Aplicando transformaciones para MySQL...</p>";
            
            // 0. Remove PostgreSQL Extensions FIRST (before they get corrupted by other replacements)
            // This regex is robust: matches CREATE EXTENSION [IF NOT EXISTS] "name"; or name;
            $sql = preg_replace('/CREATE EXTENSION\s+(IF NOT EXISTS\s+)?("?[^\";\s]+"?)(\s+WITH SCHEMA\s+\w+)?;/i', '', $sql);

            // 1. Replace UUID data type with VARCHAR(36)
            // Use word boundary \b to avoid replacing 'uuid' inside 'uuid_generate_v4'
            // This MUST be done before replacing function names to avoiding "VARCHAR(36)()".
            $sql = preg_replace('/\bUUID\b/i', 'VARCHAR(36)', $sql);

            // 2. Replace PG UUID functions with MySQL native UUID()
            $sql = str_ireplace('uuid_generate_v4()', 'UUID()', $sql);
            $sql = str_ireplace('gen_random_uuid()', 'UUID()', $sql);
            
            // 3. Remove DEFAULT UUID() from PRIMARY KEY definitions if we want to rely on auto-increment or app logic
            // But MySQL UUID() as default is only supported in recent versions (8.0.13+). 
            // If InfinityFree runs older MariaDB, DEFAULT UUID() might fail or be literal string.
            // Safe bet: Remove DEFAULT UUID() completely for PKs and let app/insert handle it.
            $sql = preg_replace('/DEFAULT\s+UUID\(\)/i', '', $sql);
            
            // Fix boolean defaults (PostgreSQL true/false -> MySQL 1/0)
            $sql = str_ireplace("DEFAULT true", "DEFAULT 1", $sql);
            $sql = str_ireplace("DEFAULT false", "DEFAULT 0", $sql);
            
            // Timestamps: PostgreSQL uses NOW(), MySQL uses CURRENT_TIMESTAMP (synonyms, but safest to standardize)
            $sql = str_ireplace("DEFAULT NOW()", "DEFAULT CURRENT_TIMESTAMP", $sql);
            $sql = str_ireplace("TIMESTAMP DEFAULT CURRENT_TIMESTAMP", "TIMESTAMP DEFAULT CURRENT_TIMESTAMP", $sql);
            
            // Remove ON CONFLICT (Use INSERT IGNORE or REPLACE INTO in application logic)
            // This regex attempts to remove ON CONFLICT clauses that end the statement.
            $sql = preg_replace('/ON CONFLICT.*?(\(\))? DO NOTHING;/is', ';', $sql);
            $sql = preg_replace('/ON CONFLICT.*?DO UPDATE SET.*;/is', ';', $sql);
            $sql = preg_replace('/ON CONFLICT.*?DO REPLACE;/is', ';', $sql); // For PostgreSQL older versions

            // Fix JSONB -> JSON
            $sql = str_ireplace('JSONB', 'JSON', $sql);

            // Fix PostgreSQL Array types (e.g. TEXT[]) -> JSON in MySQL
            // The previous regex was: preg_replace('/([a-zA-Z0-9_]+)\s*\[\]/', 'JSON', $sql);
            // If the error shows '[],' it implies the type name was removed but [] remained? 
            // Or maybe the input was "TEXT []".
            // Let's make it more specific and robust.
            // Matches: start of word, type name, optional space, [], end of word boundary/comma/space
            $sql = preg_replace('/\b[a-zA-Z0-9_]+\s*\[\]/i', 'JSON', $sql);

            // Use INSERT IGNORE for idempotency (replaces ON CONFLICT DO NOTHING)
            // This prevents "Duplicate entry" errors when re-running seed data
            // Matches "INSERT INTO" at start of line or string, case insensitive
            $sql = preg_replace('/^INSERT INTO/im', 'INSERT IGNORE INTO', $sql);

            // Execute SQL statements
            // Matches DO $ ... $; including newlines

            // Fix INET -> VARCHAR(45) (IPv6 max length)
            $sql = str_ireplace('INET', 'VARCHAR(45)', $sql);

            // Fix mismatch in seed data: employees table uses 'active' but schema has 'is_active'
            // Use regex to replace 'active' with 'is_active' ONLY inside the column list of INSERT INTO employees
            // This is safer than replacing the whole line which might not match byte-for-byte
            $sql = preg_replace(
                '/(INSERT\s+INTO\s+employees\s*\([^)]*?)\bactive\b([^)]*?\))/i', 
                '$1is_active$2', 
                $sql
            );
            
            // Fix admin password hash: Ensure it is a valid bcrypt hash generated by PHP
            // The placeholder in seed_data might be old or incompatible
            $validHash = password_hash('secure_password_123', PASSWORD_BCRYPT);
            // Look for the specific INSERT for admin user and replace the hash value
            // Original: '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'
            $sql = str_replace(
                "'$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'", 
                "'$validHash'", 
                $sql
            );

            // More generic fix for column name in insert statements if format varies
            // Replace ", active)" with ", is_active)" in INSERT INTO employees context
            // But str_ireplace specific string above is safer if we know the exact string.
            // Let's also add a more generic one just in case whitespace differs.
            $sql = preg_replace('/INSERT INTO employees\s*\((.*),\s*active\s*\)/i', 'INSERT INTO employees ($1, is_active)', $sql);

            // Execute SQL statements
            // Matches DO $$ ... $$; including newlines
            $sql = preg_replace('/DO \$\$[\s\S]*?\$\$;/m', '', $sql);

            // Convert Custom ENUM types to VARCHAR (simplest approach for migration script)
            // In PG: column_name user_role ...
            // In MySQL: column_name VARCHAR(50) ...
            // We need to replace known enum types with VARCHAR
            $enums = [
                'user_role', 'vacation_status', 'vacation_type', 'complaint_status', 
                'complaint_priority', 'chat_type', 'message_type', 'document_type', 
                'document_request_status'
            ];
            
            foreach ($enums as $enum) {
                // Regex to match the enum type usage in column definitions
                // Looks for the enum name followed by a space, comma, or end of line/statement
                $sql = preg_replace("/\b$enum\b/", "VARCHAR(50)", $sql);
            }
            
            // Remove CREATE TYPE statements if any remain (though DO block removal should catch them if inside)
            $sql = preg_replace('/CREATE TYPE .*? AS ENUM .*?;/s', '', $sql);
            
            // Remove triggers (CREATE TRIGGER, CREATE FUNCTION) as they are T-SQL/PLpgSQL specific
            // MySQL triggers are different enough to skip for this basic migration
            // Improve regex to catch CREATE FUNCTION ... RETURNS TRIGGER ... language 'plpgsql';
            $sql = preg_replace('/CREATE OR REPLACE FUNCTION[\s\S]*?RETURNS TRIGGER[\s\S]*?language \'plpgsql\';/i', '', $sql);
            $sql = preg_replace('/CREATE TRIGGER .*?;/s', '', $sql);

            // Remove WHERE clause from CREATE INDEX (Partial Indexes not supported in MySQL standard syntax)
            $sql = preg_replace('/CREATE INDEX .*? WHERE .*?;/i', ';', $sql);
            // Alternatively, just strip the WHERE part but keep the index (standard index instead of partial)
            // But stripping the whole index creation might be safer to avoid syntax errors if the index relies on the condition logic.
            // Let's try to just strip the WHERE clause to keep the index on the full table.
            $sql = preg_replace('/(CREATE INDEX .*?)\s+WHERE\s+.*?;/i', '$1;', $sql);

        }
        
        // For debugging: echo the transformed SQL
        // echo "<pre>Executing SQL:\n" . htmlspecialchars($sql) . "</pre>";

        // Execute SQL statements
        // PDO::exec is generally for single queries, but can work for DDL.
        // For complex SQL or multiple statements, we might need to split $sql.
        // For now, assuming simple DDL per file.
        try {
            // PDO::exec returns the number of affected rows or false on failure.
            // A better approach for multiple statements is to split and loop,
            // but for schema creation, exec might suffice if no complex syntax.
            // Let's try splitting by ';' as a fallback if exec fails.
            $statements = array_filter(explode(';', $sql), function($stmt) { return trim($stmt) !== ''; });
            
            if (count($statements) > 1) {
                echo "<p><em>Splitting SQL into multiple statements...</em></p>";
                $totalAffected = 0;
                foreach ($statements as $statement) {
                    $subStmt = $db->getConnection()->prepare($statement);
                    if ($subStmt->execute()) {
                        $totalAffected += $subStmt->rowCount();
                    } else {
                         throw new \PDOException("Failed statement: " . implode(', ', $subStmt->errorInfo()));
                    }
                }
                 echo "<p style='color:green'>✅ Ejecutado ($totalAffected statements)</p>";
            } else {
                // If only one statement, exec might be simpler
                $affected = $db->getConnection()->exec($sql);
                if ($affected !== false) {
                    echo "<p style='color:green'>✅ Ejecutado correctamente ($affected rows)</p>";
                } else {
                    throw new \PDOException("Migration failed for $filename. Error: " . implode(', ', $db->getConnection()->errorInfo()));
                }
            }
        } catch (PDOException $e) {
            // Log the specific error with SQL query
            error_log("Migration Error in $filename: " . $e->getMessage() . "\nSQL: " . $sql);
            throw new \RuntimeException("Error executing migration $filename: " . $e->getMessage());
        }
    }
    
    echo "<hr><h2>✅ Migración Completada</h2>";
    echo "<p>Ahora puedes borrar este archivo y acceder al <a href='/'>Portal</a></p>";

} catch (Throwable $e) {
    error_log("Migration Fatal Error: " . $e->getMessage() . "\nTrace: " . $e->getTraceAsString());
    http_response_code(500);
    
    // Friendly error page for migration failures
    echo "<h1>Error en la Migración</h1>";
    echo "<p>No se ha podido completar la inicialización de la base de datos.</p>";
    
    // Enable debug output ONLY IF APP_DEBUG is true
    $debug = false;
    $appEnv = $_ENV['APP_ENV'] ?? 'production';
    if ($appEnv !== 'production') { // Assume debug is true if not production
        $debug = true;
    }
    // Or check specific APP_DEBUG env var if set
    if (($_ENV['APP_DEBUG'] ?? 'false') === 'true') {
        $debug = true;
    }

    if ($debug) {
        echo "<pre>Error: " . htmlspecialchars($e->getMessage()) . "</pre>";
        echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
    } else {
        echo "<p>Por favor, contacta con el administrador.</p>";
    }
}
