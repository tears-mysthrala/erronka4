-- Migration: Fix vacation_balances for MySQL (Simple Version)
-- Date: 2026-02-12
-- Description: Add available_days column for MySQL

-- Add available_days column if it doesn't exist
-- For MySQL 5.7.6+, use GENERATED ALWAYS AS
-- For older MySQL versions, we'll create a regular column

-- Check if column exists first
DELIMITER $$

DROP PROCEDURE IF EXISTS AddAvailableDaysColumn$$

CREATE PROCEDURE AddAvailableDaysColumn()
BEGIN
    DECLARE col_exists INT DEFAULT 0;
    
    SELECT COUNT(*) INTO col_exists
    FROM INFORMATION_SCHEMA.COLUMNS
    WHERE TABLE_SCHEMA = DATABASE()
    AND TABLE_NAME = 'vacation_balances'
    AND COLUMN_NAME = 'available_days';
    
    IF col_exists = 0 THEN
        -- Try to add as generated column (MySQL 5.7.6+)
        SET @sql = 'ALTER TABLE vacation_balances 
                    ADD COLUMN available_days DECIMAL(5,2) 
                    GENERATED ALWAYS AS (total_days - used_days - pending_days) STORED';
        
        -- Prepare and execute
        PREPARE stmt FROM @sql;
        EXECUTE stmt;
        DEALLOCATE PREPARE stmt;
        
        SELECT 'Column available_days added successfully' AS result;
    ELSE
        SELECT 'Column available_days already exists' AS result;
    END IF;
END$$

DELIMITER ;

-- Execute the procedure
CALL AddAvailableDaysColumn();

-- Clean up
DROP PROCEDURE IF EXISTS AddAvailableDaysColumn;

-- Ensure all employees have a vacation balance for current year
INSERT INTO vacation_balances (employee_id, year, total_days, used_days, pending_days)
SELECT e.id, YEAR(CURDATE()), 22.0, 0.0, 0.0
FROM employees e
LEFT JOIN vacation_balances vb ON e.id = vb.employee_id AND vb.year = YEAR(CURDATE())
WHERE vb.id IS NULL;
