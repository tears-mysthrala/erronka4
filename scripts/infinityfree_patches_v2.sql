-- ============================================================================
-- InfinityFree Database Patches
-- ============================================================================
-- Version: 2.0.0
-- Date: 2026-02-06
-- Purpose: Update MySQL schema for Payslips and Documents modules
-- ============================================================================

-- Patch 1: Fix Documents Table - Allow NULL employee_id for Public Documents
-- This allows documents to be marked as "public" (employee_id = NULL)
-- Required for Documents module functionality

ALTER TABLE `documents` 
MODIFY COLUMN `employee_id` VARCHAR(36) DEFAULT NULL
COMMENT 'Employee ID (NULL for public documents)';

-- Drop existing foreign key to recreate it
-- MySQL does not support 'DROP FOREIGN KEY IF EXISTS', so check INFORMATION_SCHEMA first
SET @fk_name := (
    SELECT CONSTRAINT_NAME
    FROM INFORMATION_SCHEMA.REFERENTIAL_CONSTRAINTS
    WHERE CONSTRAINT_SCHEMA = DATABASE()
      AND TABLE_NAME = 'documents'
      AND CONSTRAINT_NAME = 'documents_ibfk_1'
);

SET @drop_fk_sql := IF(
    @fk_name IS NOT NULL,
    'ALTER TABLE `documents` DROP FOREIGN KEY `documents_ibfk_1`;',
    'SELECT ''Foreign key documents_ibfk_1 does not exist'' AS info;'
);

PREPARE drop_fk_stmt FROM @drop_fk_sql;
EXECUTE drop_fk_stmt;
DEALLOCATE PREPARE drop_fk_stmt;

-- Recreate foreign key to keep employee-scoped documents private
-- Note: If an employee is deleted, their employee-scoped documents are deleted (not made public)
-- This prevents sensitive documents from becoming publicly accessible
ALTER TABLE `documents`
ADD CONSTRAINT `documents_ibfk_1` 
FOREIGN KEY (`employee_id`) 
REFERENCES `employees`(`id`) 
ON DELETE CASCADE;

-- Verify the change
SELECT 
    COLUMN_NAME,
    IS_NULLABLE,
    COLUMN_DEFAULT,
    COLUMN_TYPE
FROM INFORMATION_SCHEMA.COLUMNS
WHERE TABLE_NAME = 'documents' 
  AND COLUMN_NAME = 'employee_id'
  AND TABLE_SCHEMA = DATABASE();

-- ============================================================================
-- Patch 2: Add Performance Indexes
-- ============================================================================

-- Index for filtering public/private documents
-- Use stored procedures to handle IF NOT EXISTS logic for broader compatibility
DELIMITER $$
DROP PROCEDURE IF EXISTS add_idx_documents_public $$
CREATE PROCEDURE add_idx_documents_public()
BEGIN
    IF NOT EXISTS (
        SELECT 1
        FROM INFORMATION_SCHEMA.STATISTICS
        WHERE TABLE_SCHEMA = DATABASE()
          AND TABLE_NAME = 'documents'
          AND INDEX_NAME = 'idx_documents_public'
    ) THEN
        ALTER TABLE `documents`
            ADD KEY `idx_documents_public` (`employee_id`, `is_archived`)
            COMMENT 'Optimize public/private document queries';
    END IF;
END $$

CALL add_idx_documents_public() $$
DROP PROCEDURE IF EXISTS add_idx_documents_public $$
DELIMITER ;

-- Composite index for payroll date range queries
DELIMITER $$
DROP PROCEDURE IF EXISTS add_idx_payroll_employee_period $$
CREATE PROCEDURE add_idx_payroll_employee_period()
BEGIN
    IF NOT EXISTS (
        SELECT 1
        FROM INFORMATION_SCHEMA.STATISTICS
        WHERE TABLE_SCHEMA = DATABASE()
          AND TABLE_NAME = 'payroll'
          AND INDEX_NAME = 'idx_payroll_employee_period'
    ) THEN
        ALTER TABLE `payroll`
            ADD KEY `idx_payroll_employee_period` (`employee_id`, `period_start`, `period_end`)
            COMMENT 'Optimize payroll list queries with filters';
    END IF;
END $$

CALL add_idx_payroll_employee_period() $$
DROP PROCEDURE IF EXISTS add_idx_payroll_employee_period $$
DELIMITER ;

-- Index for document type filtering
DELIMITER $$
DROP PROCEDURE IF EXISTS add_idx_documents_type_archived $$
CREATE PROCEDURE add_idx_documents_type_archived()
BEGIN
    IF NOT EXISTS (
        SELECT 1
        FROM INFORMATION_SCHEMA.STATISTICS
        WHERE TABLE_SCHEMA = DATABASE()
          AND TABLE_NAME = 'documents'
          AND INDEX_NAME = 'idx_documents_type_archived'
    ) THEN
        ALTER TABLE `documents`
            ADD KEY `idx_documents_type_archived` (`type`, `is_archived`)
            COMMENT 'Optimize category filter queries';
    END IF;
END $$

CALL add_idx_documents_type_archived() $$
DROP PROCEDURE IF EXISTS add_idx_documents_type_archived $$
DELIMITER ;

-- ============================================================================
-- Patch 3: Verify Table Structures
-- ============================================================================

-- Show payroll table structure
SHOW CREATE TABLE `payroll`;

-- Show documents table structure  
SHOW CREATE TABLE `documents`;

-- Show all indexes on documents
SHOW INDEXES FROM `documents`;

-- Show all indexes on payroll
SHOW INDEXES FROM `payroll`;

-- ============================================================================
-- Verification Queries
-- ============================================================================

-- Count documents by type (should work after patch)
SELECT 
    CASE 
        WHEN employee_id IS NULL THEN 'Public'
        ELSE 'Private'
    END as document_type,
    type,
    COUNT(*) as count
FROM documents
WHERE is_archived = 0
GROUP BY document_type, type;

-- Test payroll query performance (explain plan)
EXPLAIN SELECT * 
FROM payroll 
WHERE employee_id = 'test-uuid'
  AND period_start >= '2026-01-01'
  AND period_end <= '2026-12-31'
ORDER BY period_start DESC;

-- ============================================================================
-- Success Message
-- ============================================================================

SELECT 'Patches applied successfully!' as message,
       NOW() as applied_at,
       DATABASE() as database_name;

-- ============================================================================
-- NOTES:
-- ============================================================================
-- 
-- After applying these patches:
-- 1. Test document upload with "public" checkbox
-- 2. Verify public documents show in "Public Documents" tab
-- 3. Test payroll filtering by year/month
-- 4. Monitor query performance with EXPLAIN
-- 
-- If you encounter errors:
-- - Check if tables exist: SHOW TABLES LIKE 'documents';
-- - Check current structure: SHOW CREATE TABLE documents;
-- - Review error messages carefully
-- - Ensure you have ALTER privileges
-- 
-- To rollback (if needed):
-- ALTER TABLE `documents` 
-- MODIFY COLUMN `employee_id` VARCHAR(36) NOT NULL;
-- 
-- ============================================================================
