-- Migration: Create vacation request tables
-- Date: 2026-01-15
-- Description: Fixed schema to use UUIDs and align with 001_init_schema

-- 1. Fix vacation_balances if it exists (from 001), or create it
DO $$ 
BEGIN
    -- Check if table exists
    IF EXISTS (SELECT FROM pg_tables WHERE schemaname = 'public' AND tablename = 'vacation_balances') THEN
        -- Alter columns to support decimals if they are integers
        ALTER TABLE vacation_balances ALTER COLUMN total_days TYPE DECIMAL(5,2);
        ALTER TABLE vacation_balances ALTER COLUMN used_days TYPE DECIMAL(5,2);
        
        -- Add pending_days if not exists (001 used generated pending_days? No, 001 had generated pending_days)
        -- 001: pending_days INTEGER GENERATED ALWAYS AS (total_days - used_days) STORED
        -- We want pending_days to be a real column we can update via triggers, OR keep it generated?
        -- The trigger update_vacation_balance tries to UPDATE pending_days. You cannot update a GENERATED column.
        -- So we must DROP the generated column and make it a normal column.
        
        ALTER TABLE vacation_balances DROP COLUMN IF EXISTS pending_days;
        ALTER TABLE vacation_balances ADD COLUMN IF NOT EXISTS pending_days DECIMAL(5,2) DEFAULT 0.0;
        
        -- Ensure available_days generated column exists
        ALTER TABLE vacation_balances DROP COLUMN IF EXISTS available_days;
        ALTER TABLE vacation_balances ADD COLUMN available_days DECIMAL(5,2) GENERATED ALWAYS AS (total_days - used_days - pending_days) STORED;
        
    ELSE
        -- Create table if it doesn't exist
        CREATE TABLE vacation_balances (
            id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
            employee_id UUID NOT NULL,
            year INTEGER NOT NULL,
            total_days DECIMAL(5,2) NOT NULL DEFAULT 22.0,
            used_days DECIMAL(5,2) NOT NULL DEFAULT 0.0,
            pending_days DECIMAL(5,2) NOT NULL DEFAULT 0.0,
            available_days DECIMAL(5,2) GENERATED ALWAYS AS (total_days - used_days - pending_days) STORED,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (employee_id) REFERENCES employees(id) ON DELETE CASCADE,
            UNIQUE(employee_id, year)
        );
    END IF;
END $$;

-- 2. Create vacation_requests (replaces 'vacations' concept from 001 if any, but we use a new table name to avoid conflict/confusion)
CREATE TABLE IF NOT EXISTS vacation_requests (
    id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
    employee_id UUID NOT NULL,
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    total_days DECIMAL(5,2) NOT NULL,
    request_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status VARCHAR(20) NOT NULL DEFAULT 'PENDING',
    notes TEXT,
    manager_approval_date TIMESTAMP,
    manager_approval_by UUID,
    manager_approval_notes TEXT,
    hr_approval_date TIMESTAMP,
    hr_approval_by UUID,
    hr_approval_notes TEXT,
    rejection_reason TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (employee_id) REFERENCES employees(id) ON DELETE CASCADE,
    FOREIGN KEY (manager_approval_by) REFERENCES employees(id),
    FOREIGN KEY (hr_approval_by) REFERENCES employees(id),
    CONSTRAINT chk_status CHECK (status IN ('PENDING', 'MANAGER_APPROVED', 'APPROVED', 'REJECTED', 'CANCELLED')),
    CONSTRAINT chk_dates CHECK (end_date >= start_date),
    CONSTRAINT chk_total_days CHECK (total_days > 0)
);

-- 3. Vacation Types
CREATE TABLE IF NOT EXISTS vacation_types (
    id SERIAL PRIMARY KEY,
    code VARCHAR(50) UNIQUE NOT NULL,
    name_eu VARCHAR(100) NOT NULL,
    name_es VARCHAR(100) NOT NULL,
    requires_approval BOOLEAN DEFAULT true,
    max_consecutive_days INTEGER,
    is_active BOOLEAN DEFAULT true,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO vacation_types (code, name_eu, name_es, requires_approval, max_consecutive_days) VALUES
('ANNUAL', 'Urteko oporrak', 'Vacaciones anuales', true, NULL),
('SICK', 'Gaixotasuna', 'Baja por enfermedad', false, NULL),
('PERSONAL', 'Asuntu propioak', 'Asuntos propios', true, 7),
('MATERNITY', 'Amatasuna', 'Maternidad', false, NULL),
('PATERNITY', 'Aitatasuna', 'Paternidad', false, NULL),
('MARRIAGE', 'Ezkontzak', 'Matrimonio', true, 15),
('FAMILY', 'Familia arrazoiak', 'Motivos familiares', true, 5)
ON CONFLICT (code) DO NOTHING;

-- 4. Public Holidays
CREATE TABLE IF NOT EXISTS public_holidays (
    id SERIAL PRIMARY KEY,
    holiday_date DATE NOT NULL,
    name_eu VARCHAR(100) NOT NULL,
    name_es VARCHAR(100) NOT NULL,
    is_national BOOLEAN DEFAULT false,
    is_regional BOOLEAN DEFAULT false,
    is_local BOOLEAN DEFAULT false,
    year INTEGER GENERATED ALWAYS AS (EXTRACT(YEAR FROM holiday_date)) STORED,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE(holiday_date)
);

INSERT INTO public_holidays (holiday_date, name_eu, name_es, is_national, is_regional) VALUES
('2026-01-01', 'Urteberri Eguna', 'Año Nuevo', true, false),
('2026-01-06', 'Erregeen Eguna', 'Reyes Magos', true, false),
('2026-04-03', 'Ostiral Santua', 'Viernes Santo', true, false),
('2026-04-06', 'Pazko Astelehena', 'Lunes de Pascua', false, true),
('2026-05-01', 'Langileen Eguna', 'Día del Trabajo', true, false),
('2026-07-25', 'Santiago Apostolua', 'Santiago Apóstol', true, false),
('2026-08-15', 'Andra Maria', 'Asunción', true, false),
('2026-10-12', 'Hispanitate Eguna', 'Fiesta Nacional', true, false),
('2026-11-01', 'Santu Guztien Eguna', 'Todos los Santos', true, false),
('2026-12-06', 'Konstituzioa', 'Constitución', true, false),
('2026-12-08', 'Sortzez Garbia', 'Inmaculada Concepción', true, false),
('2026-12-25', 'Gabonak', 'Navidad', true, false)
ON CONFLICT (holiday_date) DO NOTHING;

-- 5. Indexes
CREATE INDEX IF NOT EXISTS idx_vacation_balances_employee ON vacation_balances(employee_id);
CREATE INDEX IF NOT EXISTS idx_vacation_balances_year ON vacation_balances(year);
CREATE INDEX IF NOT EXISTS idx_vacation_requests_employee ON vacation_requests(employee_id);
CREATE INDEX IF NOT EXISTS idx_vacation_requests_status ON vacation_requests(status);
CREATE INDEX IF NOT EXISTS idx_vacation_requests_dates ON vacation_requests(start_date, end_date);
CREATE INDEX IF NOT EXISTS idx_public_holidays_date ON public_holidays(holiday_date);
CREATE INDEX IF NOT EXISTS idx_public_holidays_year ON public_holidays(year);

-- 6. Triggers
CREATE OR REPLACE FUNCTION update_vacation_balance()
RETURNS TRIGGER AS $$
BEGIN
    IF NEW.status = 'APPROVED' THEN
        UPDATE vacation_balances
        SET 
            pending_days = pending_days - NEW.total_days,
            used_days = used_days + NEW.total_days,
            updated_at = CURRENT_TIMESTAMP
        WHERE employee_id = NEW.employee_id 
          AND year = EXTRACT(YEAR FROM NEW.start_date);
    ELSIF NEW.status = 'REJECTED' OR NEW.status = 'CANCELLED' THEN
        UPDATE vacation_balances
        SET 
            pending_days = pending_days - NEW.total_days,
            updated_at = CURRENT_TIMESTAMP
        WHERE employee_id = NEW.employee_id 
          AND year = EXTRACT(YEAR FROM NEW.start_date);
    ELSIF (TG_OP = 'INSERT' OR OLD.status != 'PENDING') AND NEW.status = 'PENDING' THEN
        UPDATE vacation_balances
        SET 
            pending_days = pending_days + NEW.total_days,
            updated_at = CURRENT_TIMESTAMP
        WHERE employee_id = NEW.employee_id 
          AND year = EXTRACT(YEAR FROM NEW.start_date);
    END IF;
    
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

DROP TRIGGER IF EXISTS trg_vacation_request_status ON vacation_requests;
CREATE TRIGGER trg_vacation_request_status
AFTER INSERT OR UPDATE OF status ON vacation_requests
FOR EACH ROW
EXECUTE FUNCTION update_vacation_balance();

-- Function: Initialize vacation balance for new employees
CREATE OR REPLACE FUNCTION initialize_vacation_balance()
RETURNS TRIGGER AS $$
BEGIN
    INSERT INTO vacation_balances (employee_id, year, total_days)
    VALUES (NEW.id, EXTRACT(YEAR FROM CURRENT_DATE), 22.0)
    ON CONFLICT (employee_id, year) DO NOTHING;
    
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

-- Trigger on employees table (ensure it exists)
DROP TRIGGER IF EXISTS trg_employee_vacation_init ON employees;
CREATE TRIGGER trg_employee_vacation_init
AFTER INSERT ON employees
FOR EACH ROW
EXECUTE FUNCTION initialize_vacation_balance();

