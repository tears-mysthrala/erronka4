-- ============================================================================
-- Zabala Gailetak HR Portal - MySQL Database Schema
-- ============================================================================
-- Version: 2.0.0
-- Date: 2026-02-05
-- Purpose: Complete MySQL-compatible schema with proper UUID handling and 
--          vacation system triggers
-- ============================================================================

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

-- ============================================================================
-- DROP EXISTING TABLES (CAUTION: This will delete all data!)
-- ============================================================================

DROP TABLE IF EXISTS `audit_logs`;
DROP TABLE IF EXISTS `complaint_updates`;
DROP TABLE IF EXISTS `complaints`;
DROP TABLE IF EXISTS `messages`;
DROP TABLE IF EXISTS `conversation_participants`;
DROP TABLE IF EXISTS `conversations`;
DROP TABLE IF EXISTS `document_requests`;
DROP TABLE IF EXISTS `documents`;
DROP TABLE IF EXISTS `payroll`;
DROP TABLE IF EXISTS `vacation_requests`;
DROP TABLE IF EXISTS `vacation_balances`;
DROP TABLE IF EXISTS `vacation_types`;
DROP TABLE IF EXISTS `vacations`;
DROP TABLE IF EXISTS `notifications`;
DROP TABLE IF EXISTS `employees`;
DROP TABLE IF EXISTS `sessions`;
DROP TABLE IF EXISTS `users`;
DROP TABLE IF EXISTS `departments`;

-- ============================================================================
-- TABLE: departments
-- ============================================================================

CREATE TABLE `departments` (
  `id` VARCHAR(36) NOT NULL,
  `name` VARCHAR(100) NOT NULL,
  `description` TEXT DEFAULT NULL,
  `manager_id` VARCHAR(36) DEFAULT NULL,
  `parent_id` VARCHAR(36) DEFAULT NULL,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_departments_manager` (`manager_id`),
  KEY `idx_departments_parent` (`parent_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- TABLE: users
-- ============================================================================

CREATE TABLE `users` (
  `id` VARCHAR(36) NOT NULL,
  `email` VARCHAR(255) NOT NULL,
  `password_hash` VARCHAR(255) NOT NULL,
  `role` VARCHAR(50) NOT NULL DEFAULT 'employee',
  `mfa_enabled` TINYINT(1) NOT NULL DEFAULT 0,
  `mfa_secret` VARCHAR(255) DEFAULT NULL,
  `mfa_backup_codes` TEXT DEFAULT NULL,
  `passkey_credential_id` VARCHAR(500) DEFAULT NULL,
  `passkey_public_key` TEXT DEFAULT NULL,
  `passkey_counter` INT NOT NULL DEFAULT 0,
  `last_login` TIMESTAMP NULL DEFAULT NULL,
  `failed_login_attempts` INT NOT NULL DEFAULT 0,
  `account_locked` TINYINT(1) NOT NULL DEFAULT 0,
  `lock_until` TIMESTAMP NULL DEFAULT NULL,
  `password_changed_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  KEY `idx_users_email` (`email`),
  KEY `idx_users_role` (`role`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- TABLE: employees
-- ============================================================================

CREATE TABLE `employees` (
  `id` VARCHAR(36) NOT NULL,
  `user_id` VARCHAR(36) NOT NULL,
  `employee_number` VARCHAR(50) NOT NULL,
  `first_name` VARCHAR(100) NOT NULL,
  `last_name` VARCHAR(100) NOT NULL,
  `nif` VARCHAR(20) NOT NULL,
  `birth_date` DATE DEFAULT NULL,
  `gender` VARCHAR(20) DEFAULT NULL,
  `phone` VARCHAR(50) DEFAULT NULL,
  `personal_email` VARCHAR(255) DEFAULT NULL,
  `address` TEXT DEFAULT NULL,
  `department_id` VARCHAR(36) DEFAULT NULL,
  `position` VARCHAR(100) DEFAULT NULL,
  `hire_date` DATE NOT NULL,
  `termination_date` DATE DEFAULT NULL,
  `employment_type` VARCHAR(50) DEFAULT 'full_time',
  `contract_type` VARCHAR(50) DEFAULT NULL,
  `salary` DECIMAL(12,2) DEFAULT NULL,
  `vacation_days` INT NOT NULL DEFAULT 22,
  `vacation_days_used` INT NOT NULL DEFAULT 0,
  `emergency_contact_name` VARCHAR(200) DEFAULT NULL,
  `emergency_contact_phone` VARCHAR(50) DEFAULT NULL,
  `emergency_contact_relation` VARCHAR(100) DEFAULT NULL,
  `profile_photo_path` VARCHAR(500) DEFAULT NULL,
  `notes` TEXT DEFAULT NULL,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id` (`user_id`),
  UNIQUE KEY `employee_number` (`employee_number`),
  UNIQUE KEY `nif` (`nif`),
  KEY `idx_employees_user` (`user_id`),
  KEY `idx_employees_department` (`department_id`),
  KEY `idx_employees_name` (`last_name`, `first_name`),
  KEY `idx_employees_active` (`is_active`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`department_id`) REFERENCES `departments`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- TABLE: vacation_balances (CRITICAL FIX)
-- ============================================================================

CREATE TABLE `vacation_balances` (
  `id` VARCHAR(36) NOT NULL,
  `employee_id` VARCHAR(36) NOT NULL,
  `year` INT NOT NULL,
  `total_days` INT NOT NULL DEFAULT 22,
  `used_days` INT NOT NULL DEFAULT 0,
  `pending_days` INT NOT NULL DEFAULT 0,  -- ✅ NOT GENERATED - Manual field
  `carried_over_days` INT DEFAULT 0,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `employee_year_unique` (`employee_id`, `year`),
  KEY `idx_vacation_balances_employee` (`employee_id`),
  KEY `idx_vacation_balances_year` (`year`),
  FOREIGN KEY (`employee_id`) REFERENCES `employees`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- TABLE: vacation_requests (CRITICAL FIX - UUID support)
-- ============================================================================

CREATE TABLE `vacation_requests` (
  `id` VARCHAR(36) NOT NULL,  -- ✅ Changed from INT to VARCHAR(36) for UUID
  `employee_id` VARCHAR(36) NOT NULL,  -- ✅ Changed from INT to VARCHAR(36)
  `start_date` DATE NOT NULL,
  `end_date` DATE NOT NULL,
  `total_days` DECIMAL(5,2) NOT NULL,
  `request_date` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `status` VARCHAR(20) NOT NULL DEFAULT 'PENDING',
  `notes` TEXT DEFAULT NULL,
  `manager_approval_date` TIMESTAMP NULL DEFAULT NULL,
  `manager_approval_by` VARCHAR(36) DEFAULT NULL,  -- ✅ Changed from INT
  `manager_approval_notes` TEXT DEFAULT NULL,
  `hr_approval_date` TIMESTAMP NULL DEFAULT NULL,
  `hr_approval_by` VARCHAR(36) DEFAULT NULL,  -- ✅ Changed from INT
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
  FOREIGN KEY (`employee_id`) REFERENCES `employees`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`manager_approval_by`) REFERENCES `users`(`id`) ON DELETE SET NULL,
  FOREIGN KEY (`hr_approval_by`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- TABLE: vacation_types
-- ============================================================================

CREATE TABLE `vacation_types` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `code` VARCHAR(50) NOT NULL,
  `name_eu` VARCHAR(100) NOT NULL,
  `name_es` VARCHAR(100) NOT NULL,
  `requires_approval` TINYINT(1) DEFAULT 1,
  `max_consecutive_days` INT DEFAULT NULL,
  `is_active` TINYINT(1) DEFAULT 1,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- TABLE: vacations (Legacy - keeping for backward compatibility)
-- ============================================================================

CREATE TABLE `vacations` (
  `id` VARCHAR(36) NOT NULL,
  `employee_id` VARCHAR(36) NOT NULL,
  `start_date` DATE NOT NULL,
  `end_date` DATE NOT NULL,
  `type` VARCHAR(50) NOT NULL,
  `reason` TEXT DEFAULT NULL,
  `status` VARCHAR(50) NOT NULL DEFAULT 'pending',
  `approved_by` VARCHAR(36) DEFAULT NULL,
  `approved_at` TIMESTAMP NULL DEFAULT NULL,
  `rejection_reason` TEXT DEFAULT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_vacations_employee` (`employee_id`),
  KEY `idx_vacations_status` (`status`),
  KEY `idx_vacations_dates` (`start_date`, `end_date`),
  KEY `idx_vacations_approver` (`approved_by`),
  FOREIGN KEY (`employee_id`) REFERENCES `employees`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- TABLE: sessions
-- ============================================================================

CREATE TABLE `sessions` (
  `id` VARCHAR(36) NOT NULL,
  `user_id` VARCHAR(36) NOT NULL,
  `token` VARCHAR(500) NOT NULL,
  `ip_address` VARCHAR(45) DEFAULT NULL,
  `user_agent` TEXT DEFAULT NULL,
  `expires_at` TIMESTAMP NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_sessions_user` (`user_id`),
  KEY `idx_sessions_token` (`token`(255)),
  KEY `idx_sessions_expires` (`expires_at`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- TABLE: notifications
-- ============================================================================

CREATE TABLE `notifications` (
  `id` VARCHAR(36) NOT NULL,
  `user_id` VARCHAR(36) NOT NULL,
  `type` VARCHAR(100) NOT NULL,
  `title` VARCHAR(255) NOT NULL,
  `message` TEXT NOT NULL,
  `link` VARCHAR(500) DEFAULT NULL,
  `is_read` TINYINT(1) NOT NULL DEFAULT 0,
  `read_at` TIMESTAMP NULL DEFAULT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_notifications_user` (`user_id`),
  KEY `idx_notifications_read` (`is_read`),
  KEY `idx_notifications_created` (`created_at` DESC),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- TABLE: documents
-- ============================================================================

CREATE TABLE `documents` (
  `id` VARCHAR(36) NOT NULL,
  `employee_id` VARCHAR(36) NOT NULL,
  `type` VARCHAR(50) NOT NULL,
  `filename` VARCHAR(255) NOT NULL,
  `original_filename` VARCHAR(255) DEFAULT NULL,
  `file_path` VARCHAR(500) NOT NULL,
  `mime_type` VARCHAR(100) NOT NULL,
  `file_size` BIGINT NOT NULL,
  `checksum` VARCHAR(64) DEFAULT NULL,
  `description` TEXT DEFAULT NULL,
  `is_archived` TINYINT(1) NOT NULL DEFAULT 0,
  `archived_at` TIMESTAMP NULL DEFAULT NULL,
  `uploaded_by` VARCHAR(36) NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_documents_employee` (`employee_id`),
  KEY `idx_documents_type` (`type`),
  KEY `idx_documents_uploaded_by` (`uploaded_by`),
  FOREIGN KEY (`employee_id`) REFERENCES `employees`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`uploaded_by`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- TABLE: document_requests
-- ============================================================================

CREATE TABLE `document_requests` (
  `id` VARCHAR(36) NOT NULL,
  `employee_id` VARCHAR(36) NOT NULL,
  `requested_by` VARCHAR(36) NOT NULL,
  `requested_type` VARCHAR(50) NOT NULL,
  `description` TEXT DEFAULT NULL,
  `deadline` DATE DEFAULT NULL,
  `status` VARCHAR(50) NOT NULL DEFAULT 'pending',
  `submitted_document_id` VARCHAR(36) DEFAULT NULL,
  `submitted_at` TIMESTAMP NULL DEFAULT NULL,
  `rejection_reason` TEXT DEFAULT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_doc_requests_employee` (`employee_id`),
  KEY `idx_doc_requests_status` (`status`),
  KEY `idx_doc_requests_requested_by` (`requested_by`),
  KEY `submitted_document_id` (`submitted_document_id`),
  FOREIGN KEY (`employee_id`) REFERENCES `employees`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`requested_by`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- TABLE: payroll
-- ============================================================================

CREATE TABLE `payroll` (
  `id` VARCHAR(36) NOT NULL,
  `employee_id` VARCHAR(36) NOT NULL,
  `period_start` DATE NOT NULL,
  `period_end` DATE NOT NULL,
  `base_salary` DECIMAL(12,2) NOT NULL,
  `extra_hours` DECIMAL(10,2) DEFAULT 0.00,
  `bonuses` DECIMAL(10,2) DEFAULT 0.00,
  `commissions` DECIMAL(10,2) DEFAULT 0.00,
  `deductions` DECIMAL(10,2) DEFAULT 0.00,
  `taxes` DECIMAL(10,2) DEFAULT 0.00,
  `social_security` DECIMAL(10,2) DEFAULT 0.00,
  `other_deductions` DECIMAL(10,2) DEFAULT 0.00,
  `gross_salary` DECIMAL(12,2) GENERATED ALWAYS AS (`base_salary` + COALESCE(`extra_hours`,0) + COALESCE(`bonuses`,0) + COALESCE(`commissions`,0)) STORED,
  `net_salary` DECIMAL(12,2) NOT NULL,
  `pdf_path` VARCHAR(500) DEFAULT NULL,
  `pdf_filename` VARCHAR(255) DEFAULT NULL,
  `notes` TEXT DEFAULT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `employee_period_unique` (`employee_id`, `period_start`, `period_end`),
  KEY `idx_payroll_employee` (`employee_id`),
  KEY `idx_payroll_period` (`period_start`, `period_end`),
  FOREIGN KEY (`employee_id`) REFERENCES `employees`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- TABLE: conversations
-- ============================================================================

CREATE TABLE `conversations` (
  `id` VARCHAR(36) NOT NULL,
  `type` VARCHAR(50) NOT NULL,
  `department_id` VARCHAR(36) DEFAULT NULL,
  `title` VARCHAR(255) DEFAULT NULL,
  `last_message_at` TIMESTAMP NULL DEFAULT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_conversations_type` (`type`),
  KEY `idx_conversations_department` (`department_id`),
  KEY `idx_conversations_last_message` (`last_message_at` DESC),
  FOREIGN KEY (`department_id`) REFERENCES `departments`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- TABLE: conversation_participants
-- ============================================================================

CREATE TABLE `conversation_participants` (
  `id` VARCHAR(36) NOT NULL,
  `conversation_id` VARCHAR(36) NOT NULL,
  `user_id` VARCHAR(36) NOT NULL,
  `joined_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `left_at` TIMESTAMP NULL DEFAULT NULL,
  `is_muted` TINYINT(1) DEFAULT 0,
  `last_read_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `conversation_user_unique` (`conversation_id`, `user_id`),
  KEY `idx_conv_participants_user` (`user_id`),
  KEY `idx_conv_participants_conversation` (`conversation_id`),
  FOREIGN KEY (`conversation_id`) REFERENCES `conversations`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- TABLE: messages
-- ============================================================================

CREATE TABLE `messages` (
  `id` VARCHAR(36) NOT NULL,
  `conversation_id` VARCHAR(36) NOT NULL,
  `sender_id` VARCHAR(36) NOT NULL,
  `content` TEXT NOT NULL,
  `type` VARCHAR(50) NOT NULL DEFAULT 'text',
  `attachment_path` VARCHAR(500) DEFAULT NULL,
  `attachment_name` VARCHAR(255) DEFAULT NULL,
  `attachment_size` BIGINT DEFAULT NULL,
  `reply_to_id` VARCHAR(36) DEFAULT NULL,
  `is_edited` TINYINT(1) DEFAULT 0,
  `is_deleted` TINYINT(1) DEFAULT 0,
  `deleted_at` TIMESTAMP NULL DEFAULT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_messages_conversation` (`conversation_id`),
  KEY `idx_messages_sender` (`sender_id`),
  KEY `idx_messages_created` (`created_at` DESC),
  KEY `idx_messages_reply` (`reply_to_id`),
  FOREIGN KEY (`conversation_id`) REFERENCES `conversations`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`sender_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- TABLE: complaints
-- ============================================================================

CREATE TABLE `complaints` (
  `id` VARCHAR(36) NOT NULL,
  `employee_id` VARCHAR(36) NOT NULL,
  `type` VARCHAR(100) NOT NULL,
  `title` VARCHAR(255) NOT NULL,
  `description` TEXT NOT NULL,
  `status` VARCHAR(50) NOT NULL DEFAULT 'open',
  `priority` VARCHAR(50) NOT NULL DEFAULT 'normal',
  `assigned_to` VARCHAR(36) DEFAULT NULL,
  `resolved_at` TIMESTAMP NULL DEFAULT NULL,
  `resolution_summary` TEXT DEFAULT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_complaints_employee` (`employee_id`),
  KEY `idx_complaints_status` (`status`),
  KEY `idx_complaints_priority` (`priority`),
  KEY `idx_complaints_assigned` (`assigned_to`),
  FOREIGN KEY (`employee_id`) REFERENCES `employees`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`assigned_to`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- TABLE: complaint_updates
-- ============================================================================

CREATE TABLE `complaint_updates` (
  `id` VARCHAR(36) NOT NULL,
  `complaint_id` VARCHAR(36) NOT NULL,
  `user_id` VARCHAR(36) NOT NULL,
  `status` VARCHAR(50) DEFAULT NULL,
  `comment` TEXT DEFAULT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_complaint_updates_complaint` (`complaint_id`),
  KEY `user_id` (`user_id`),
  FOREIGN KEY (`complaint_id`) REFERENCES `complaints`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- TABLE: audit_logs
-- ============================================================================

CREATE TABLE `audit_logs` (
  `id` VARCHAR(36) NOT NULL,
  `user_id` VARCHAR(36) DEFAULT NULL,
  `action` VARCHAR(100) NOT NULL,
  `entity_type` VARCHAR(100) DEFAULT NULL,
  `entity_id` VARCHAR(36) DEFAULT NULL,
  `old_values` TEXT DEFAULT NULL,
  `new_values` TEXT DEFAULT NULL,
  `ip_address` VARCHAR(45) DEFAULT NULL,
  `user_agent` TEXT DEFAULT NULL,
  `request_path` VARCHAR(500) DEFAULT NULL,
  `request_method` VARCHAR(10) DEFAULT NULL,
  `status_code` INT DEFAULT NULL,
  `duration_ms` INT DEFAULT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_audit_logs_user` (`user_id`),
  KEY `idx_audit_logs_action` (`action`),
  KEY `idx_audit_logs_entity` (`entity_type`, `entity_id`),
  KEY `idx_audit_logs_created` (`created_at` DESC)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- SEED DATA: Departments
-- ============================================================================

INSERT INTO `departments` (`id`, `name`, `description`, `manager_id`, `parent_id`, `is_active`, `created_at`, `updated_at`) VALUES
('fab56d96-f92f-11f0-8063-4020ddf43117', 'Administración', 'Departamento de administración y gestión', NULL, NULL, 1, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
('fab56f01-f92f-11f0-8063-4020ddf43117', 'Producción', 'Departamento de producción', NULL, NULL, 1, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
('fab56f74-f92f-11f0-8063-4020ddf43117', 'Calidad', 'Departamento de control de calidad', NULL, NULL, 1, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
('fab56feb-f92f-11f0-8063-4020ddf43117', 'Mantenimiento', 'Departamento de mantenimiento', NULL, NULL, 1, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
('fab5703a-f92f-11f0-8063-4020ddf43117', 'Recursos Humanos', 'Departamento de recursos humanos', NULL, NULL, 1, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
('fab57085-f92f-11f0-8063-4020ddf43117', 'IT', 'Departamento de tecnologías de la información', NULL, NULL, 1, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
('d10e8400-e29b-41d4-a716-446655440001', 'Administrazioa', 'Enpresaren kudeaketa eta administrazio orokorra', NULL, NULL, 1, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
('d10e8400-e29b-41d4-a716-446655440002', 'Ekoizpena', 'Gaileten ekoizpen lerroak eta lantegia', NULL, NULL, 1, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
('d10e8400-e29b-41d4-a716-446655440003', 'IKT/Segurtasuna', 'Sistemen kudeaketa eta zibersegurtasuna', NULL, NULL, 1, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
('d10e8400-e29b-41d4-a716-446655440004', 'Giza Baliabideak', 'Langileen kudeaketa eta hautaketa', NULL, NULL, 1, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP);

-- ============================================================================
-- SEED DATA: Admin User
-- ============================================================================
-- Password: Admin123!

INSERT INTO `users` (`id`, `email`, `password_hash`, `role`, `mfa_enabled`, `mfa_secret`, `mfa_backup_codes`, `passkey_credential_id`, `passkey_public_key`, `passkey_counter`, `last_login`, `failed_login_attempts`, `account_locked`, `lock_until`, `password_changed_at`, `created_at`, `updated_at`) VALUES
('fab52102-f92f-11f0-8063-4020ddf43117', 'admin@zabalagailetak.com', '$2y$10$jI1dVY3IoVoSOEFCU1lpVeHvBwxD3xFAhABCEbADKEqyTwblQX8je', 'admin', 0, NULL, NULL, NULL, NULL, 0, NULL, 0, 0, NULL, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP);

-- ============================================================================
-- SEED DATA: Admin Employee
-- ============================================================================

INSERT INTO `employees` (`id`, `user_id`, `employee_number`, `first_name`, `last_name`, `nif`, `birth_date`, `gender`, `phone`, `personal_email`, `address`, `department_id`, `position`, `hire_date`, `termination_date`, `employment_type`, `contract_type`, `salary`, `vacation_days`, `vacation_days_used`, `emergency_contact_name`, `emergency_contact_phone`, `emergency_contact_relation`, `profile_photo_path`, `notes`, `is_active`, `created_at`, `updated_at`) VALUES
('ff15d24e-fa89-11f0-9b20-fab8ad3a19ce', 'fab52102-f92f-11f0-8063-4020ddf43117', 'ADM001', 'Administrador', 'Sistema', '44631980C', NULL, NULL, NULL, NULL, NULL, 'fab57085-f92f-11f0-8063-4020ddf43117', 'Administrador del Sistema', '2026-01-01', NULL, 'full_time', NULL, 0.00, 22, 0, NULL, NULL, NULL, NULL, NULL, 1, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP);

-- ============================================================================
-- SEED DATA: Admin Vacation Balance for 2026
-- ============================================================================

INSERT INTO `vacation_balances` (`id`, `employee_id`, `year`, `total_days`, `used_days`, `pending_days`, `carried_over_days`, `created_at`, `updated_at`) VALUES
(UUID(), 'ff15d24e-fa89-11f0-9b20-fab8ad3a19ce', 2026, 22, 0, 0, 0, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP);

-- ============================================================================
-- TRIGGERS: Vacation Balance Automation
-- ============================================================================

DELIMITER $$

-- Trigger 1: When a vacation request is INSERTED with status PENDING
DROP TRIGGER IF EXISTS `vacation_request_insert_pending`$$
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
    END IF;
END$$

-- Trigger 2: When a vacation request status is UPDATED
DROP TRIGGER IF EXISTS `vacation_request_update_status`$$
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
DROP TRIGGER IF EXISTS `vacation_request_delete`$$
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
-- AUDIT LOG: System Initialization
-- ============================================================================

INSERT INTO `audit_logs` (`id`, `user_id`, `action`, `entity_type`, `entity_id`, `old_values`, `new_values`, `ip_address`, `user_agent`, `request_path`, `request_method`, `status_code`, `duration_ms`, `created_at`) VALUES
(UUID(), 'fab52102-f92f-11f0-8063-4020ddf43117', 'system_initialized', 'system', 'fab52102-f92f-11f0-8063-4020ddf43117', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, CURRENT_TIMESTAMP);

-- ============================================================================
-- COMPLETION
-- ============================================================================

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- ============================================================================
-- Installation complete!
-- ============================================================================
-- Next steps:
-- 1. Import this file in phpMyAdmin: Import > Choose file > Go
-- 2. Test login: admin@zabalagailetak.com / Admin123!
-- 3. Check vacation balance calculation
-- 4. Create a test vacation request to verify triggers work
-- ============================================================================
