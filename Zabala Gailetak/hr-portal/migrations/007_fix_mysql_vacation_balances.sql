-- Migration: Fix vacation_balances for MySQL compatibility
-- Date: 2026-02-12
-- Description: Add available_days column for MySQL (InfinityFree)

-- Check if we're running on MySQL (not PostgreSQL)
-- In MySQL, we need to add available_days as a generated column or computed field

-- For MySQL 5.7.6+, we can use GENERATED ALWAYS AS
-- For older MySQL, we need to use a regular column with triggers

-- First, check if the column exists and add it if not
SET @dbname = DATABASE();
SET @tablename = 'vacation_balances';
SET @columnname = 'available_days';

SET @sql = CONCAT(
    'SELECT COUNT(*) INTO @column_exists 
    FROM INFORMATION_SCHEMA.COLUMNS 
    WHERE TABLE_SCHEMA = ''', @dbname, ''' 
    AND TABLE_NAME = ''', @tablename, ''' 
    AND COLUMN_NAME = ''', @columnname, ''''
);

PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Add the column if it doesn't exist
SET @add_column = IF(@column_exists = 0, 
    'ALTER TABLE vacation_balances ADD COLUMN available_days DECIMAL(5,2) AS (total_days - used_days - pending_days) STORED',
    'SELECT ''Column already exists'' as message'
);

PREPARE stmt FROM @add_column;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Create or update the vacation balance record for admin user if it doesn't exist
-- First, get the admin employee ID
SET @admin_employee_id = (SELECT id FROM employees WHERE email = 'admin@zabalagailetak.com' LIMIT 1);
SET @current_year = YEAR(CURDATE());

-- Insert balance record for admin if not exists
INSERT INTO vacation_balances (employee_id, year, total_days, used_days, pending_days)
SELECT @admin_employee_id, @current_year, 22.0, 0.0, 0.0
WHERE @admin_employee_id IS NOT NULL
ON DUPLICATE KEY UPDATE 
    total_days = VALUES(total_days),
    updated_at = CURRENT_TIMESTAMP;

-- Insert balance records for all other employees that don't have one
INSERT INTO vacation_balances (employee_id, year, total_days, used_days, pending_days)
SELECT e.id, @current_year, 22.0, 0.0, 0.0
FROM employees e
LEFT JOIN vacation_balances vb ON e.id = vb.employee_id AND vb.year = @current_year
WHERE vb.id IS NULL;
