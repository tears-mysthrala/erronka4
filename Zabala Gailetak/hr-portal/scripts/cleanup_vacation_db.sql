-- ============================================
-- SCRIPT DE LIMPIEZA DE BASE DE DATOS
-- Zabala Gailetak - Vacation System Fix
-- ============================================

-- 1. ELIMINAR registros corruptos con ID vacío
DELETE FROM vacation_balances WHERE id = '' OR id IS NULL;
DELETE FROM vacation_requests WHERE id = '' OR id IS NULL;

-- 2. VERIFICAR registros restantes
SELECT 'vacation_balances' as tabla, COUNT(*) as registros FROM vacation_balances
UNION ALL
SELECT 'vacation_requests' as tabla, COUNT(*) as registros FROM vacation_requests;

-- 3. VERIFICAR estructura de las tablas
SHOW CREATE TABLE vacation_balances;
SHOW CREATE TABLE vacation_requests;

-- 4. VERIFICAR que cada empleado tenga su balance
SELECT 
    e.id as employee_id,
    e.first_name,
    e.last_name,
    vb.id as balance_id,
    vb.year,
    vb.total_days,
    vb.used_days,
    vb.pending_days,
    (vb.total_days - vb.used_days - vb.pending_days) as available_days
FROM employees e
LEFT JOIN vacation_balances vb ON e.id = vb.employee_id AND vb.year = 2026
ORDER BY e.first_name;

-- 5. SI NECESITAS CREAR BALANCES MANUALMENTE (solo si no existen):
-- Descomenta y ejecuta si es necesario
/*
INSERT INTO vacation_balances (id, employee_id, year, total_days, used_days, pending_days)
SELECT 
    UUID() as id,
    e.id as employee_id,
    2026 as year,
    22.0 as total_days,
    0.0 as used_days,
    0.0 as pending_days
FROM employees e
WHERE NOT EXISTS (
    SELECT 1 FROM vacation_balances vb 
    WHERE vb.employee_id = e.id AND vb.year = 2026
);
*/

-- 6. VERIFICAR el resultado
SELECT 
    'Total empleados' as descripcion,
    COUNT(*) as cantidad
FROM employees
UNION ALL
SELECT 
    'Balances 2026' as descripcion,
    COUNT(*) as cantidad
FROM vacation_balances WHERE year = 2026;
