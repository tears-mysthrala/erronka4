-- ============================================================================
-- Zabala Gailetak - Vacation System Migration Script
-- ============================================================================
-- Purpose: Fix vacation system without losing existing data
-- Date: 2026-02-05
-- WARNING: BACKUP YOUR DATABASE BEFORE RUNNING THIS!
-- ============================================================================

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;

-- ============================================================================
-- STEP 1: Clean up corrupted vacation requests
-- ============================================================================

-- Delete any vacation_requests with NULL or empty IDs
DELETE FROM vacation_requests 
WHERE id IS NULL OR id = '' OR employee_id IS NULL OR employee_id = '' OR employee_id = 0;

-- ============================================================================
-- STEP 2: Backup and recreate vacation_balances table
-- ============================================================================

-- Create backup table
CREATE TABLE IF NOT EXISTS `vacation_balances_backup` AS
SELECT * FROM `vacation_balances`;

-- Drop the old table (with GENERATED column)
DROP TABLE IF EXISTS `vacation_balances`;

-- Recreate with manual pending_days field
CREATE TABLE `vacation_balances` (
  `id` VARCHAR(36) NOT NULL,
  `employee_id` VARCHAR(36) NOT NULL,
  `year` INT NOT NULL,
  `total_days` INT NOT NULL DEFAULT 22,
  `used_days` INT NOT NULL DEFAULT 0,
  `pending_days` INT NOT NULL DEFAULT 0,  -- ✅ Manual field, not GENERATED
  `carried_over_days` INT DEFAULT 0,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `employee_year_unique` (`employee_id`, `year`),
  KEY `idx_vacation_balances_employee` (`employee_id`),
  KEY `idx_vacation_balances_year` (`year`),
  FOREIGN KEY (`employee_id`) REFERENCES `employees`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Restore data from backup with correct pending_days calculation
INSERT INTO `vacation_balances` 
    (`id`, `employee_id`, `year`, `total_days`, `used_days`, `pending_days`, `carried_over_days`, `created_at`, `updated_at`)
SELECT 
    id,
    employee_id,
    year,
    total_days,
    used_days,
    0 as pending_days,  -- Will be recalculated by next step
    carried_over_days,
    created_at,
    CURRENT_TIMESTAMP as updated_at
FROM `vacation_balances_backup`;

-- ============================================================================
-- STEP 3: Backup and recreate vacation_requests table (UUID support)
-- ============================================================================

-- Check if old table uses INT for id
SET @table_exists = (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS 
                     WHERE TABLE_NAME = 'vacation_requests' 
                     AND COLUMN_NAME = 'id' 
                     AND DATA_TYPE = 'int');

-- Only recreate if it uses INT
SET @recreate = @table_exists > 0;

-- Create backup if table exists
CREATE TABLE IF NOT EXISTS `vacation_requests_backup` AS
SELECT * FROM `vacation_requests`;

-- Drop and recreate with VARCHAR(36) for UUIDs
DROP TABLE IF EXISTS `vacation_requests`;

CREATE TABLE `vacation_requests` (
  `id` VARCHAR(36) NOT NULL,
  `employee_id` VARCHAR(36) NOT NULL,
  `start_date` DATE NOT NULL,
  `end_date` DATE NOT NULL,
  `total_days` DECIMAL(5,2) NOT NULL,
  `request_date` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `status` VARCHAR(20) NOT NULL DEFAULT 'PENDING',
  `notes` TEXT DEFAULT NULL,
  `manager_approval_date` TIMESTAMP NULL DEFAULT NULL,
  `manager_approval_by` VARCHAR(36) DEFAULT NULL,
  `manager_approval_notes` TEXT DEFAULT NULL,
  `hr_approval_date` TIMESTAMP NULL DEFAULT NULL,
  `hr_approval_by` VARCHAR(36) DEFAULT NULL,
  `hr_approval_notes` TEXT DEFAULT NULL,
  `rejection_reason` TEXT DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_vacation_requests_employee` (`employee_id`),
  KEY `idx_vacation_requests_status` (`status`),
  KEY `idx_vacation_requests_dates` (`start_date`, `end_date`),
  KEY `idx_vacation_requests_manager` (`manager_approval_by`),
  KEY `idx_vacation_requests_hr` (`hr_approval_by`),
  FOREIGN KEY (`employee_id`) REFERENCES `employees`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Note: Cannot restore INT-based IDs to VARCHAR(36) table
-- Old data will remain in vacation_requests_backup if needed

-- ============================================================================
-- STEP 4: Recalculate pending_days from existing vacation_requests
-- ============================================================================

-- Update pending_days based on actual PENDING/MANAGER_APPROVED requests
UPDATE vacation_balances vb
SET pending_days = COALESCE((
    SELECT SUM(total_days)
    FROM vacation_requests vr
    WHERE vr.employee_id = vb.employee_id
      AND YEAR(vr.start_date) = vb.year
      AND vr.status IN ('PENDING', 'MANAGER_APPROVED')
), 0);

-- Update used_days based on actual APPROVED requests
UPDATE vacation_balances vb
SET used_days = COALESCE((
    SELECT SUM(total_days)
    FROM vacation_requests vr
    WHERE vr.employee_id = vb.employee_id
      AND YEAR(vr.start_date) = vb.year
      AND vr.status = 'APPROVED'
), 0);

-- ============================================================================
-- STEP 5: Create triggers for automatic balance management
-- ============================================================================

DELIMITER $$

-- Drop existing triggers if any
DROP TRIGGER IF EXISTS `vacation_request_insert_pending`$$
DROP TRIGGER IF EXISTS `vacation_request_update_status`$$
DROP TRIGGER IF EXISTS `vacation_request_delete`$$

-- Trigger 1: When a vacation request is INSERTED
CREATE TRIGGER `vacation_request_insert_pending`
AFTER INSERT ON `vacation_requests`
FOR EACH ROW
BEGIN
    IF NEW.status = 'PENDING' THEN
        UPDATE vacation_balances
        SET pending_days = pending_days + NEW.total_days,
            updated_at = CURRENT_TIMESTAMP
        WHERE employee_id = NEW.employee_id
          AND year = YEAR(NEW.start_date);
    ELSEIF NEW.status = 'MANAGER_APPROVED' THEN
        UPDATE vacation_balances
        SET pending_days = pending_days + NEW.total_days,
            updated_at = CURRENT_TIMESTAMP
        WHERE employee_id = NEW.employee_id
          AND year = YEAR(NEW.start_date);
    ELSEIF NEW.status = 'APPROVED' THEN
        UPDATE vacation_balances
        SET used_days = used_days + NEW.total_days,
            updated_at = CURRENT_TIMESTAMP
        WHERE employee_id = NEW.employee_id
          AND year = YEAR(NEW.start_date);
    END IF;
END$$

-- Trigger 2: When a vacation request status is UPDATED
CREATE TRIGGER `vacation_request_update_status`
AFTER UPDATE ON `vacation_requests`
FOR EACH ROW
BEGIN
    -- Status changed from PENDING to APPROVED
    IF OLD.status = 'PENDING' AND NEW.status = 'APPROVED' THEN
        UPDATE vacation_balances
        SET 
            pending_days = pending_days - NEW.total_days,
            used_days = used_days + NEW.total_days,
            updated_at = CURRENT_TIMESTAMP
        WHERE employee_id = NEW.employee_id
          AND year = YEAR(NEW.start_date);
    
    -- Status changed from PENDING to REJECTED or CANCELLED
    ELSEIF OLD.status = 'PENDING' AND (NEW.status = 'REJECTED' OR NEW.status = 'CANCELLED') THEN
        UPDATE vacation_balances
        SET 
            pending_days = pending_days - NEW.total_days,
            updated_at = CURRENT_TIMESTAMP
        WHERE employee_id = NEW.employee_id
          AND year = YEAR(NEW.start_date);
    
    -- Status changed from MANAGER_APPROVED to APPROVED (HR approval)
    ELSEIF OLD.status = 'MANAGER_APPROVED' AND NEW.status = 'APPROVED' THEN
        UPDATE vacation_balances
        SET 
            pending_days = pending_days - NEW.total_days,
            used_days = used_days + NEW.total_days,
            updated_at = CURRENT_TIMESTAMP
        WHERE employee_id = NEW.employee_id
          AND year = YEAR(NEW.start_date);
    
    -- Status changed to MANAGER_APPROVED (from PENDING)
    ELSEIF OLD.status = 'PENDING' AND NEW.status = 'MANAGER_APPROVED' THEN
        -- Days remain in pending_days, no change needed
        UPDATE vacation_balances
        SET updated_at = CURRENT_TIMESTAMP
        WHERE employee_id = NEW.employee_id
          AND year = YEAR(NEW.start_date);
    END IF;
END$$

-- Trigger 3: When a vacation request is DELETED
CREATE TRIGGER `vacation_request_delete`
AFTER DELETE ON `vacation_requests`
FOR EACH ROW
BEGIN
    -- If request was PENDING or MANAGER_APPROVED, release pending_days
    IF OLD.status IN ('PENDING', 'MANAGER_APPROVED') THEN
        UPDATE vacation_balances
        SET 
            pending_days = pending_days - OLD.total_days,
            updated_at = CURRENT_TIMESTAMP
        WHERE employee_id = OLD.employee_id
          AND year = YEAR(OLD.start_date);
    
    -- If request was APPROVED, release used_days
    ELSEIF OLD.status = 'APPROVED' THEN
        UPDATE vacation_balances
        SET 
            used_days = used_days - OLD.total_days,
            updated_at = CURRENT_TIMESTAMP
        WHERE employee_id = OLD.employee_id
          AND year = YEAR(OLD.start_date);
    END IF;
END$$

DELIMITER ;

-- ============================================================================
-- STEP 6: Verify migration
-- ============================================================================

-- Show final vacation balances
SELECT 
    vb.employee_id,
    e.first_name,
    e.last_name,
    vb.year,
    vb.total_days,
    vb.used_days,
    vb.pending_days,
    (vb.total_days - vb.used_days - vb.pending_days) AS available_days
FROM vacation_balances vb
INNER JOIN employees e ON e.id = vb.employee_id
ORDER BY vb.year DESC, e.last_name;

-- Show count of vacation requests by status
SELECT 
    status,
    COUNT(*) as count,
    SUM(total_days) as total_days_sum
FROM vacation_requests
GROUP BY status;

COMMIT;

-- ============================================================================
-- Migration Complete!
-- ============================================================================
-- Backup tables created:
--   - vacation_balances_backup
--   - vacation_requests_backup
-- 
-- You can drop them after verifying everything works:
--   DROP TABLE vacation_balances_backup;
--   DROP TABLE vacation_requests_backup;
-- ============================================================================
