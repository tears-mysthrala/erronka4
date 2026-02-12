-- MySQL Fix for vacation_balances - Final Version
-- Run this in phpMyAdmin

-- 1. Fix table structure - make id AUTO_INCREMENT if not already
ALTER TABLE vacation_balances 
MODIFY COLUMN id INT AUTO_INCREMENT PRIMARY KEY;

-- 2. Add available_days column if not exists
SET @dbname = DATABASE();
SET @tablename = 'vacation_balances';
SET @columnname = 'available_days';

SET @sql = CONCAT(
    'SELECT COUNT(*) INTO @col_exists FROM INFORMATION_SCHEMA.COLUMNS ',
    'WHERE TABLE_SCHEMA = "', @dbname, '" ',
    'AND TABLE_NAME = "', @tablename, '" ',
    'AND COLUMN_NAME = "', @columnname, '"'
);

PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

SET @addcol = IF(@col_exists = 0,
    'ALTER TABLE vacation_balances ADD COLUMN available_days DECIMAL(5,2) AS (total_days - used_days - pending_days) STORED',
    'SELECT "Column already exists" as msg'
);

PREPARE stmt FROM @addcol;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- 3. Get admin employee ID
SET @admin_id = (SELECT e.id 
                 FROM employees e 
                 INNER JOIN users u ON e.user_id = u.id 
                 WHERE u.email = 'admin@zabalagailetak.com' 
                 LIMIT 1);

-- 4. Insert balance for admin using a simple INSERT (not INSERT SELECT)
SET @insert_admin = CONCAT(
    'INSERT INTO vacation_balances (employee_id, year, total_days, used_days, pending_days) ',
    'VALUES ("', @admin_id, '", YEAR(CURDATE()), 22.0, 0.0, 0.0) ',
    'ON DUPLICATE KEY UPDATE total_days = 22.0'
);

PREPARE stmt FROM @insert_admin;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- 5. Create balances for all other employees
-- First get current year
SET @current_year = YEAR(CURDATE());

-- Insert for employees without balance
INSERT IGNORE INTO vacation_balances (employee_id, year, total_days, used_days, pending_days)
SELECT e.id, @current_year, 22.0, 0.0, 0.0
FROM employees e
WHERE NOT EXISTS (
    SELECT 1 FROM vacation_balances vb 
    WHERE vb.employee_id = e.id AND vb.year = @current_year
);

-- 6. Verify results
SELECT 
    'Balances created:' as info,
    COUNT(*) as total
FROM vacation_balances 
WHERE year = YEAR(CURDATE());
