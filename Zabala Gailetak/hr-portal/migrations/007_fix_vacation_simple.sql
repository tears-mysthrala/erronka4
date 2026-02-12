-- MySQL Fix for vacation_balances - Simple Version
-- Run this in phpMyAdmin step by step

-- PASO 1: Solo agregar la columna available_days (sin tocar la PK)
-- Si da error, la columna ya existe y puedes ignorarlo
ALTER TABLE vacation_balances 
ADD COLUMN available_days DECIMAL(5,2) AS (total_days - used_days - pending_days) STORED;

-- PASO 2: Ver cuántos empleados tenemos
SELECT COUNT(*) as total_empleados FROM employees;

-- PASO 3: Ver cuántos balances existen para este año
SELECT COUNT(*) as balances_existentes FROM vacation_balances WHERE year = YEAR(CURDATE());

-- PASO 4: Crear balances para TODOS los empleados del año actual
-- Esto inserta solo si no existe (evita duplicados)
INSERT IGNORE INTO vacation_balances (employee_id, year, total_days, used_days, pending_days)
SELECT id, YEAR(CURDATE()), 22.0, 0.0, 0.0 FROM employees;

-- PASO 5: Verificar que se crearon correctamente
SELECT 
    vb.id,
    vb.employee_id,
    vb.year,
    vb.total_days,
    vb.used_days,
    vb.pending_days,
    vb.available_days,
    e.first_name,
    e.last_name
FROM vacation_balances vb
JOIN employees e ON vb.employee_id = e.id
WHERE vb.year = YEAR(CURDATE())
LIMIT 10;
