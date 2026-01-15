-- Audit Trail: Historial de cambios de empleados
-- Fecha: 2026-01-15

-- Tabla de logs de auditoría
CREATE TABLE IF NOT EXISTS audit_logs (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    
    -- Información del registro auditado
    entity_type VARCHAR(50) NOT NULL,  -- 'employee', 'user', etc.
    entity_id UUID NOT NULL,           -- ID del registro modificado
    
    -- Información del usuario que hizo el cambio
    user_id UUID NOT NULL,
    user_email VARCHAR(255) NOT NULL,
    user_role VARCHAR(50) NOT NULL,
    
    -- Tipo de acción
    action VARCHAR(20) NOT NULL,       -- 'create', 'update', 'delete', 'restore'
    
    -- Datos del cambio
    old_values JSONB,                  -- Valores anteriores (NULL en create)
    new_values JSONB,                  -- Valores nuevos (NULL en delete)
    changed_fields TEXT[],             -- Lista de campos modificados
    
    -- Metadata
    ip_address VARCHAR(45),            -- IPv4 o IPv6
    user_agent TEXT,                   -- Browser/client info
    request_id VARCHAR(100),           -- Para correlacionar requests
    
    -- Timestamps
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    -- Constraints
    CONSTRAINT fk_audit_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    CONSTRAINT chk_action CHECK (action IN ('create', 'update', 'delete', 'restore', 'login', 'logout'))
);

-- Índices para búsquedas eficientes
CREATE INDEX idx_audit_entity ON audit_logs(entity_type, entity_id);
CREATE INDEX idx_audit_user ON audit_logs(user_id);
CREATE INDEX idx_audit_action ON audit_logs(action);
CREATE INDEX idx_audit_created_at ON audit_logs(created_at DESC);
CREATE INDEX idx_audit_entity_created ON audit_logs(entity_type, entity_id, created_at DESC);

-- Índice para búsquedas JSONB
CREATE INDEX idx_audit_old_values ON audit_logs USING gin(old_values);
CREATE INDEX idx_audit_new_values ON audit_logs USING gin(new_values);

-- Comentarios
COMMENT ON TABLE audit_logs IS 'Registro de auditoría de todas las operaciones en el sistema';
COMMENT ON COLUMN audit_logs.entity_type IS 'Tipo de entidad auditada';
COMMENT ON COLUMN audit_logs.entity_id IS 'ID del registro auditado';
COMMENT ON COLUMN audit_logs.action IS 'Tipo de acción realizada';
COMMENT ON COLUMN audit_logs.old_values IS 'Valores anteriores en formato JSON (NULL en creación)';
COMMENT ON COLUMN audit_logs.new_values IS 'Valores nuevos en formato JSON (NULL en eliminación)';
COMMENT ON COLUMN audit_logs.changed_fields IS 'Array de campos que fueron modificados';

-- Vista para auditoría de empleados
CREATE OR REPLACE VIEW employee_audit_log AS
SELECT 
    al.id,
    al.entity_id as employee_id,
    e.employee_number,
    e.first_name || ' ' || e.last_name as employee_name,
    al.user_id,
    al.user_email,
    al.user_role,
    al.action,
    al.old_values,
    al.new_values,
    al.changed_fields,
    al.ip_address,
    al.created_at
FROM audit_logs al
LEFT JOIN employees e ON e.id = al.entity_id
WHERE al.entity_type = 'employee'
ORDER BY al.created_at DESC;

COMMENT ON VIEW employee_audit_log IS 'Vista simplificada del historial de cambios de empleados';
