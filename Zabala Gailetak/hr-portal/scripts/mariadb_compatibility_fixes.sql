-- ============================================================================
-- MariaDB/MySQL Compatibility Fixes for Zabala Gailetak HR Portal
-- ============================================================================
-- Este script verifica y corrige problemas de compatibilidad entre PostgreSQL
-- y MariaDB/MySQL. Ejecutar después de aplicar los cambios de código PHP.
-- ============================================================================

-- Verificar que las tablas existen
SHOW TABLES LIKE 'vacation_requests';
SHOW TABLES LIKE 'employees';
SHOW TABLES LIKE 'users';

-- ============================================================================
-- Índices recomendados para mejorar rendimiento de búsquedas
-- (especialmente importantes en MariaDB donde ILIKE no existe)
-- ============================================================================

-- Índice para búsquedas de empleados por nombre (case-insensitive en MySQL)
ALTER TABLE employees 
ADD INDEX idx_employees_first_name (first_name),
ADD INDEX idx_employees_last_name (last_name),
ADD INDEX idx_employees_active (is_active),
ADD INDEX idx_employees_department (department_id),
ADD INDEX idx_employees_user_id (user_id);

-- Índice para búsquedas de usuarios por email
ALTER TABLE users 
ADD INDEX idx_users_email (email),
ADD INDEX idx_users_role (role);

-- Índices para vacation_requests (crítico para el dashboard)
ALTER TABLE vacation_requests 
ADD INDEX idx_vr_status (status),
ADD INDEX idx_vr_start_date (start_date),
ADD INDEX idx_vr_employee_id (employee_id),
ADD INDEX idx_vr_status_start_date (status, start_date); -- Para consultas del dashboard

-- ============================================================================
-- Verificación de estructura de tablas críticas
-- ============================================================================

-- Verificar que vacation_requests tiene las columnas necesarias
DESCRIBE vacation_requests;

-- Verificar que employees tiene las columnas necesarias
DESCRIBE employees;

-- Verificar que users tiene las columnas necesarias  
DESCRIBE users;

-- Verificar que departments existe
DESCRIBE departments;

-- ============================================================================
-- Datos de prueba mínimos para verificar funcionamiento (opcional)
-- ============================================================================

-- Verificar que existe al menos un empleado activo
SELECT COUNT(*) as active_employees FROM employees WHERE is_active = TRUE;

-- Verificar solicitudes de vacaciones pendientes
SELECT COUNT(*) as pending_vacations FROM vacation_requests WHERE status = 'PENDING';

-- Verificar próximas vacaciones aprobadas (consulta similar a la del dashboard)
SELECT 
    e.first_name, 
    e.last_name, 
    vr.start_date, 
    vr.end_date
FROM vacation_requests vr
JOIN employees e ON vr.employee_id = e.id
WHERE vr.status = 'APPROVED' 
  AND vr.start_date >= CURRENT_DATE 
  AND vr.start_date <= DATE_ADD(CURRENT_DATE, INTERVAL 30 DAY)
ORDER BY vr.start_date ASC
LIMIT 5;

-- ============================================================================
-- Notas importantes sobre migración PostgreSQL -> MariaDB
-- ============================================================================

-- 1. Cambios de sintaxis aplicados en código PHP:
--    - ILIKE -> LIKE (MySQL/MariaDB es case-insensitive por defecto con collation utf8mb4_general_ci)
--    - || (concatenación) -> CONCAT()
--    - INTERVAL '30 days' -> INTERVAL 30 DAY
--    - EXTRACT(YEAR FROM date) -> YEAR(date)
--    - EXTRACT(MONTH FROM date) -> MONTH(date)
--    - CURRENT_DATE + INTERVAL -> DATE_ADD(CURRENT_DATE, INTERVAL)

-- 2. Collation recomendado para case-insensitive:
--    utf8mb4_general_ci o utf8mb4_unicode_ci

-- 3. Si las búsquedas case-insensitive no funcionan, verificar collation:
--    SHOW VARIABLES LIKE 'character_set%';
--    SHOW VARIABLES LIKE 'collation%';

-- ============================================================================
-- Rollback (solo en caso de problemas)
-- ============================================================================
-- Si necesitas eliminar los índices creados:
--
-- ALTER TABLE employees DROP INDEX idx_employees_first_name;
-- ALTER TABLE employees DROP INDEX idx_employees_last_name;
-- ALTER TABLE employees DROP INDEX idx_employees_active;
-- ALTER TABLE employees DROP INDEX idx_employees_department;
-- ALTER TABLE employees DROP INDEX idx_employees_user_id;
--
-- ALTER TABLE users DROP INDEX idx_users_email;
-- ALTER TABLE users DROP INDEX idx_users_role;
--
-- ALTER TABLE vacation_requests DROP INDEX idx_vr_status;
-- ALTER TABLE vacation_requests DROP INDEX idx_vr_start_date;
-- ALTER TABLE vacation_requests DROP INDEX idx_vr_employee_id;
-- ALTER TABLE vacation_requests DROP INDEX idx_vr_status_start_date;
-- ============================================================================
