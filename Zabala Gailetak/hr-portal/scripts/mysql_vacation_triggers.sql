-- ============================================
-- TRIGGER para MySQL - Actualización Automática de Vacation Balances
-- Zabala Gailetak HR Portal
-- ============================================

-- Este trigger reemplaza la funcionalidad de PostgreSQL
-- Actualiza automáticamente pending_days y used_days cuando cambia el estado de una solicitud

DELIMITER $$

-- Eliminar trigger existente si existe
DROP TRIGGER IF EXISTS trg_vacation_request_status$$

-- Crear el trigger
CREATE TRIGGER trg_vacation_request_status
AFTER INSERT ON vacation_requests
FOR EACH ROW
BEGIN
    -- Solo procesar si el estado es PENDING (nueva solicitud)
    IF NEW.status = 'PENDING' THEN
        -- Incrementar pending_days
        UPDATE vacation_balances
        SET 
            pending_days = pending_days + NEW.total_days,
            updated_at = CURRENT_TIMESTAMP
        WHERE employee_id = NEW.employee_id 
          AND year = YEAR(NEW.start_date);
    END IF;
END$$

-- Trigger para UPDATE de estado
DROP TRIGGER IF EXISTS trg_vacation_request_update$$

CREATE TRIGGER trg_vacation_request_update
AFTER UPDATE ON vacation_requests
FOR EACH ROW
BEGIN
    -- Si cambió de PENDING a APPROVED
    IF OLD.status = 'PENDING' AND NEW.status = 'APPROVED' THEN
        UPDATE vacation_balances
        SET 
            pending_days = pending_days - NEW.total_days,
            used_days = used_days + NEW.total_days,
            updated_at = CURRENT_TIMESTAMP
        WHERE employee_id = NEW.employee_id 
          AND year = YEAR(NEW.start_date);
    
    -- Si cambió de PENDING a REJECTED o CANCELLED
    ELSEIF OLD.status = 'PENDING' AND (NEW.status = 'REJECTED' OR NEW.status = 'CANCELLED') THEN
        UPDATE vacation_balances
        SET 
            pending_days = pending_days - NEW.total_days,
            updated_at = CURRENT_TIMESTAMP
        WHERE employee_id = NEW.employee_id 
          AND year = YEAR(NEW.start_date);
    
    -- Si cambió de MANAGER_APPROVED a APPROVED
    ELSEIF OLD.status = 'MANAGER_APPROVED' AND NEW.status = 'APPROVED' THEN
        UPDATE vacation_balances
        SET 
            used_days = used_days + NEW.total_days,
            updated_at = CURRENT_TIMESTAMP
        WHERE employee_id = NEW.employee_id 
          AND year = YEAR(NEW.start_date);
          
    -- Si cambió de MANAGER_APPROVED a REJECTED
    ELSEIF OLD.status = 'MANAGER_APPROVED' AND NEW.status = 'REJECTED' THEN
        UPDATE vacation_balances
        SET 
            pending_days = pending_days - NEW.total_days,
            updated_at = CURRENT_TIMESTAMP
        WHERE employee_id = NEW.employee_id 
          AND year = YEAR(NEW.start_date);
    END IF;
END$$

DELIMITER ;

-- ============================================
-- VERIFICACIÓN
-- ============================================

-- Ver los triggers creados
SHOW TRIGGERS WHERE `Table` = 'vacation_requests';

-- Verificar el estado actual
SELECT 
    'Estado actual del balance' as info,
    total_days,
    used_days,
    pending_days,
    (total_days - used_days - pending_days) as available_days
FROM vacation_balances 
WHERE year = 2026;
