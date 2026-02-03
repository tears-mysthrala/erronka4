-- Script para corregir roles a mayúsculas y verificar configuración admin
-- Ejecutar en phpMyAdmin de InfinityFree

-- 1. Mostrar estado actual
SELECT
    id,
    email,
    role,
    account_locked,
    mfa_enabled,
    created_at
FROM users
WHERE
    email = 'admin@zabalagailetak.com';

-- 2. Actualizar rol de admin a mayúsculas
UPDATE users
SET role = 'ADMIN'
WHERE
    email = 'admin@zabalagailetak.com'
    AND role = 'admin';

-- 3. Asegurar que la cuenta no esté bloqueada
UPDATE users
SET
    account_locked = 0,
    failed_login_attempts = 0
WHERE
    email = 'admin@zabalagailetak.com';

-- 4. Verificar cambios
SELECT
    id,
    email,
    role,
    account_locked,
    mfa_enabled,
    failed_login_attempts
FROM users
WHERE
    email = 'admin@zabalagailetak.com';

-- 5. Actualizar TODOS los roles a mayúsculas (por si hay más usuarios)
UPDATE users SET role = 'ADMIN' WHERE LOWER(role) = 'admin';

UPDATE users SET role = 'RRHH_MGR' WHERE LOWER(role) = 'rrhh_mgr';

UPDATE users
SET role = 'JEFE_SECCION'
WHERE
    LOWER(role) = 'jefe_seccion';

UPDATE users SET role = 'EMPLEADO' WHERE LOWER(role) = 'empleado';

UPDATE users SET role = 'AUDITOR' WHERE LOWER(role) = 'auditor';

-- 6. Verificar todos los usuarios
SELECT id, email, role, account_locked
FROM users
ORDER BY created_at;