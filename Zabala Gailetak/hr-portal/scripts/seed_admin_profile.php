<?php

/**
 * Web Admin Profile Seeder
 * 
 * Access via: /seed_admin_profile.php?key=seed_admin_2024
 * Creates the admin@zabalagailetak.com user with complete employee profile
 * Designed for web execution on shared hosting like InfinityFree
 */

// Set content type for better display
header('Content-Type: text/plain; charset=utf-8');

// Security: Only allow execution from specific IP or with a key
$securityKey = $_GET['key'] ?? '';
if ($securityKey !== 'seed_admin_2024') {
    http_response_code(403);
    echo "❌ Access denied. Security key required.\n";
    echo "Use: /seed_admin_profile.php?key=seed_admin_2024\n";
    exit(1);
}

echo "🌱 Starting admin profile seeder (Web Mode)...\n";
echo "📅 Date: " . date('Y-m-d H:i:s') . "\n";
echo "🌐 IP: " . ($_SERVER['REMOTE_ADDR'] ?? 'unknown') . "\n\n";

// No dependencies - pure PHP project
echo "✅ Pure PHP project - no external dependencies needed\n";

// Database configuration for InfinityFree
$dbConfig = [
    'host' => $_ENV['DB_HOST'] ?? 'sql107.infinityfree.com',
    'name' => $_ENV['DB_NAME'] ?? 'if0_40982238_zabala_gailetak',
    'user' => $_ENV['DB_USER'] ?? 'if0_40982238',
    'password' => $_ENV['DB_PASSWORD'] ?? '70fbmkPvaTRNl',
    'charset' => 'utf8mb4'
];

try {
    // Create PDO connection
    $dsn = "mysql:host={$dbConfig['host']};dbname={$dbConfig['name']};charset={$dbConfig['charset']}";
    $pdo = new PDO($dsn, $dbConfig['user'], $dbConfig['password'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false
    ]);
    
    echo "✅ Database connection successful\n";
    echo "📊 Database: {$dbConfig['name']}\n";
    echo "🖥️  Host: {$dbConfig['host']}\n\n";
    
} catch (Exception $e) {
    echo "❌ Database connection failed: " . $e->getMessage() . "\n";
    exit(1);
}

try {
    // Start transaction
    $pdo->beginTransaction();
    echo "🔄 Transaction started\n";
    
    // 1. Check if admin user already exists
    $stmt = $pdo->prepare("SELECT id, email FROM users WHERE email = :email LIMIT 1");
    $stmt->execute(['email' => 'admin@zabalagailetak.com']);
    $existingUser = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($existingUser) {
        echo "ℹ️  Admin user already exists: " . $existingUser['email'] . "\n";
        echo "🆔 User ID: " . $existingUser['id'] . "\n";
        $userId = $existingUser['id'];
    } else {
        // 2. Create admin user
        echo "👤 Creating admin user...\n";
        
        // Generate UUID for user (MySQL compatible)
        $userId = $pdo->query("SELECT UUID() as uuid")->fetch(PDO::FETCH_ASSOC)['uuid'];
        
        // Calculate password hash for "secure_password_123" at runtime
        $password = 'secure_password_123';
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        
        $stmt = $pdo->prepare("
            INSERT INTO users (id, email, password_hash, role, mfa_enabled, is_active, created_at, updated_at)
            VALUES (:id, :email, :password_hash, :role, :mfa_enabled, :is_active, NOW(), NOW())
        ");
        
        $stmt->execute([
            'id' => $userId,
            'email' => 'admin@zabalagailetak.com',
            'password_hash' => $passwordHash,
            'role' => 'admin',
            'mfa_enabled' => false,
            'is_active' => true
        ]);
        
        echo "✅ Admin user created successfully\n";
        echo "🆔 User ID: " . $userId . "\n";
    }
    
    // 3. Check if employee profile already exists
    $stmt = $pdo->prepare("SELECT id FROM employees WHERE user_id = :user_id LIMIT 1");
    $stmt->execute(['user_id' => $userId]);
    $existingEmployee = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($existingEmployee) {
        echo "ℹ️  Employee profile already exists for admin user\n";
        echo "👔 Employee ID: " . $existingEmployee['id'] . "\n";
        $employeeId = $existingEmployee['id'];
    } else {
        // 4. Get or create IT department
        echo "🏢 Ensuring IT department exists...\n";
        
        $stmt = $pdo->prepare("SELECT id FROM departments WHERE name = 'IT' LIMIT 1");
        $stmt->execute();
        $department = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($department) {
            $departmentId = $department['id'];
            echo "✅ IT department found: " . $departmentId . "\n";
        } else {
            // Create IT department
            echo "🆕 Creating IT department...\n";
            $departmentId = $pdo->query("SELECT UUID() as uuid")->fetch(PDO::FETCH_ASSOC)['uuid'];
            
            $stmt = $pdo->prepare("
                INSERT INTO departments (id, name, description, created_at, updated_at)
                VALUES (:id, :name, :description, NOW(), NOW())
            ");
            
            $stmt->execute([
                'id' => $departmentId,
                'name' => 'IT',
                'description' => 'Departamento de tecnologías de la información'
            ]);
            
            echo "✅ IT department created: " . $departmentId . "\n";
        }
        
        // 5. Create employee profile
        echo "👔 Creating employee profile...\n";
        
        $employeeId = $pdo->query("SELECT UUID() as uuid")->fetch(PDO::FETCH_ASSOC)['uuid'];
        
        $stmt = $pdo->prepare("
            INSERT INTO employees (
                id, user_id, first_name, last_name, position, department_id, 
                hire_date, salary, is_active, created_at, updated_at
            ) VALUES (
                :id, :user_id, :first_name, :last_name, :position, :department_id,
                :hire_date, :salary, :is_active, NOW(), NOW()
            )
        ");
        
        $stmt->execute([
            'id' => $employeeId,
            'user_id' => $userId,
            'first_name' => 'Administrador',
            'last_name' => 'Sistema',
            'position' => 'Administrador del Sistema',
            'department_id' => $departmentId,
            'hire_date' => date('Y-m-d'), // Current date
            'salary' => 0.00, // Admin doesn't need salary in demo
            'is_active' => true
        ]);
        
        echo "✅ Employee profile created successfully\n";
        echo "👔 Employee ID: " . $employeeId . "\n";
    }
    
    // 6. Log the seeding action (minimal insert)
    echo "📋 Creating audit log entry...\n";
    
    $stmt = $pdo->prepare("
        INSERT INTO audit_logs (user_id, action, entity_type, entity_id, created_at)
        VALUES (:user_id, :action, :entity_type, :entity_id, NOW())
    ");
    
    $stmt->execute([
        'user_id' => $userId,
        'action' => 'admin_profile_seeded_web',
        'entity_type' => 'system',
        'entity_id' => $userId
    ]);
    
    // Commit transaction
    $pdo->commit();
    echo "✅ Transaction committed successfully\n";
    
    echo "\n" . str_repeat("=", 60) . "\n";
    echo "🎉 Admin profile seeding completed successfully!\n";
    echo str_repeat("=", 60) . "\n";
    echo "📧 Email: admin@zabalagailetak.com\n";
    echo "🔑 Password: secure_password_123\n";
    echo "👤 Role: admin\n";
    echo "🏢 Department: IT\n";
    echo "💼 Position: Administrador del Sistema\n";
    echo "🆔 User ID: " . $userId . "\n";
    echo "👔 Employee ID: " . $employeeId . "\n";
    echo "📅 Created: " . date('Y-m-d H:i:s') . "\n";
    echo str_repeat("=", 60) . "\n";
    echo "\n✨ Done! You can now login at /login with the admin credentials.\n";
    echo "🌐 Go to: https://your-domain.infinityfree.com/login\n";
    
} catch (Exception $e) {
    // Rollback on error
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
        echo "❌ Transaction rolled back due to error\n";
    }
    
    echo "\n❌ Seeding failed: " . $e->getMessage() . "\n";
    echo "📄 Error details: " . $e->getTraceAsString() . "\n";
    exit(1);
}

echo "\n🔒 Security reminder: Delete this file after use or restrict access!\n";