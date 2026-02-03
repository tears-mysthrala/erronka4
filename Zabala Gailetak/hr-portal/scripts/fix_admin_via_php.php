<?php
/**
 * Script para corregir roles y configuración del usuario admin
 * 
 * Subir este archivo a: https://zabala-gailetak.infinityfreeapp.com/fix-admin.php
 * Ejecutar una vez y luego ELIMINAR por seguridad
 */

// Mostrar errores
error_reporting(E_ALL);
ini_set('display_errors', '1');

echo "<h1>🔧 Fix Admin Configuration</h1>";
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
}

// Database connection
$driver = $_ENV['DB_DRIVER'] ?? 'mysql';
$host = $_ENV['DB_HOST'] ?? 'sql107.infinityfree.com';
$port = $_ENV['DB_PORT'] ?? 3306;
$dbname = $_ENV['DB_NAME'] ?? 'if0_40982238_zabala_gailetak';
$user = $_ENV['DB_USER'] ?? 'if0_40982238';
$pass = $_ENV['DB_PASSWORD'] ?? '';

try {
    $dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4";
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
    
    echo "✅ Conexión a base de datos exitosa<br><br>";
    
    // Step 1: Check current state
    echo "<h2>📋 Estado Actual:</h2>";
    $stmt = $pdo->prepare('SELECT id, email, role, account_locked, mfa_enabled, failed_login_attempts FROM users WHERE email = ?');
    $stmt->execute(['admin@zabalagailetak.com']);
    $admin = $stmt->fetch();
    
    if (!$admin) {
        echo "❌ Usuario admin NO encontrado<br>";
        exit;
    }
    
    echo "<pre>";
    print_r($admin);
    echo "</pre>";
    
    // Step 2: Fix role to uppercase
    echo "<h2>🔧 Actualizando rol a mayúsculas...</h2>";
    $stmt = $pdo->prepare('UPDATE users SET role = ? WHERE email = ? AND role != ?');
    $stmt->execute(['ADMIN', 'admin@zabalagailetak.com', 'ADMIN']);
    echo "✅ Rol actualizado (filas afectadas: " . $stmt->rowCount() . ")<br><br>";
    
    // Step 3: Unlock account and reset failed attempts
    echo "<h2>🔓 Desbloqueando cuenta y reseteando intentos fallidos...</h2>";
    $stmt = $pdo->prepare('UPDATE users SET account_locked = 0, failed_login_attempts = 0 WHERE email = ?');
    $stmt->execute(['admin@zabalagailetak.com']);
    echo "✅ Cuenta desbloqueada (filas afectadas: " . $stmt->rowCount() . ")<br><br>";
    
    // Step 4: Verify password hash
    echo "<h2>🔑 Verificando contraseña...</h2>";
    $stmt = $pdo->prepare('SELECT password_hash FROM users WHERE email = ?');
    $stmt->execute(['admin@zabalagailetak.com']);
    $result = $stmt->fetch();
    
    $testPassword = 'secure_password_123';
    $hashMatch = password_verify($testPassword, $result['password_hash']);
    
    echo "Hash en BD: <code>" . htmlspecialchars(substr($result['password_hash'], 0, 30)) . "...</code><br>";
    echo "Contraseña de prueba: <code>$testPassword</code><br>";
    echo "Verificación: " . ($hashMatch ? "✅ <strong>CORRECTA</strong>" : "❌ <strong>INCORRECTA</strong>") . "<br><br>";
    
    // If password doesn't match, regenerate it
    if (!$hashMatch) {
        echo "<h2>🔄 Regenerando hash de contraseña...</h2>";
        $newHash = password_hash($testPassword, PASSWORD_BCRYPT, ['cost' => 12]);
        $stmt = $pdo->prepare('UPDATE users SET password_hash = ? WHERE email = ?');
        $stmt->execute([$newHash, 'admin@zabalagailetak.com']);
        echo "✅ Contraseña actualizada<br>";
        echo "Nueva contraseña: <code>$testPassword</code><br><br>";
    }
    
    // Step 5: Update all roles to uppercase
    echo "<h2>🔄 Actualizando todos los roles a mayúsculas...</h2>";
    $roleUpdates = [
        'admin' => 'ADMIN',
        'rrhh_mgr' => 'RRHH_MGR',
        'jefe_seccion' => 'JEFE_SECCION',
        'empleado' => 'EMPLEADO',
        'auditor' => 'AUDITOR'
    ];
    
    foreach ($roleUpdates as $old => $new) {
        $stmt = $pdo->prepare('UPDATE users SET role = ? WHERE LOWER(role) = ?');
        $stmt->execute([$new, $old]);
        if ($stmt->rowCount() > 0) {
            echo "  ✅ $old → $new (filas: " . $stmt->rowCount() . ")<br>";
        }
    }
    echo "<br>";
    
    // Step 6: Show final state
    echo "<h2>✅ Estado Final:</h2>";
    $stmt = $pdo->prepare('SELECT id, email, role, account_locked, mfa_enabled, failed_login_attempts FROM users WHERE email = ?');
    $stmt->execute(['admin@zabalagailetak.com']);
    $admin = $stmt->fetch();
    
    echo "<pre>";
    print_r($admin);
    echo "</pre>";
    
    // Step 7: Show all users
    echo "<h2>👥 Todos los usuarios:</h2>";
    $stmt = $pdo->query('SELECT id, email, role, account_locked FROM users ORDER BY created_at');
    $users = $stmt->fetchAll();
    
    echo "<table border='1' cellpadding='5' cellspacing='0'>";
    echo "<tr><th>Email</th><th>Role</th><th>Locked</th></tr>";
    foreach ($users as $u) {
        $locked = $u['account_locked'] ? '🔒' : '✅';
        echo "<tr>";
        echo "<td>{$u['email']}</td>";
        echo "<td><strong>{$u['role']}</strong></td>";
        echo "<td>{$locked}</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    echo "<hr>";
    echo "<h2 style='color: green;'>✅ ¡Configuración completada!</h2>";
    echo "<p>Ahora puedes:</p>";
    echo "<ol>";
    echo "<li>Eliminar este archivo por seguridad</li>";
    echo "<li>Probar el login con: <code>admin@zabalagailetak.com</code> / <code>secure_password_123</code></li>";
    echo "</ol>";
    
} catch (PDOException $e) {
    echo "<h2 style='color: red;'>❌ Error:</h2>";
    echo "<pre style='color: red;'>" . htmlspecialchars($e->getMessage()) . "</pre>";
}

echo "<hr>";
echo "<small><strong>⚠️ IMPORTANTE:</strong> Elimina este archivo después de ejecutarlo</small>";
