-- Migration: Create vacation request tables
-- Date: 2026-01-15

-- Table: vacation_balances
-- Stores annual vacation days balance for each employee
CREATE TABLE IF NOT EXISTS vacation_balances (
    id SERIAL PRIMARY KEY,
    employee_id INTEGER NOT NULL,
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

-- Table: vacation_requests
-- Stores vacation requests from employees
CREATE TABLE IF NOT EXISTS vacation_requests (
    id SERIAL PRIMARY KEY,
    employee_id INTEGER NOT NULL,
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    total_days DECIMAL(5,2) NOT NULL,
    request_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status VARCHAR(20) NOT NULL DEFAULT 'PENDING',
    notes TEXT,
    manager_approval_date TIMESTAMP,
    manager_approval_by INTEGER,
    manager_approval_notes TEXT,
    hr_approval_date TIMESTAMP,
    hr_approval_by INTEGER,
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

-- Table: vacation_types
-- Catalog of vacation types (annual, sick leave, personal, etc.)
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

-- Insert default vacation types
INSERT INTO vacation_types (code, name_eu, name_es, requires_approval, max_consecutive_days) VALUES
('ANNUAL', 'Urteko oporrak', 'Vacaciones anuales', true, NULL),
('SICK', 'Gaixotasuna', 'Baja por enfermedad', false, NULL),
('PERSONAL', 'Asuntu propioak', 'Asuntos propios', true, 7),
('MATERNITY', 'Amatasuna', 'Maternidad', false, NULL),
('PATERNITY', 'Aitatasuna', 'Paternidad', false, NULL),
('MARRIAGE', 'Ezkontzak', 'Matrimonio', true, 15),
('FAMILY', 'Familia arrazoiak', 'Motivos familiares', true, 5)
ON CONFLICT (code) DO NOTHING;

-- Table: public_holidays
-- Spanish and Basque public holidays
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

-- Insert 2026 public holidays for Basque Country
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

-- Indexes for performance
CREATE INDEX idx_vacation_balances_employee ON vacation_balances(employee_id);
CREATE INDEX idx_vacation_balances_year ON vacation_balances(year);
CREATE INDEX idx_vacation_requests_employee ON vacation_requests(employee_id);
CREATE INDEX idx_vacation_requests_status ON vacation_requests(status);
CREATE INDEX idx_vacation_requests_dates ON vacation_requests(start_date, end_date);
CREATE INDEX idx_public_holidays_date ON public_holidays(holiday_date);
CREATE INDEX idx_public_holidays_year ON public_holidays(year);

-- Function: Update vacation balance
CREATE OR REPLACE FUNCTION update_vacation_balance()
RETURNS TRIGGER AS $$
BEGIN
    -- Recalculate pending and used days when request status changes
    IF NEW.status = 'APPROVED' THEN
        -- Move from pending to used
        UPDATE vacation_balances
        SET 
            pending_days = pending_days - NEW.total_days,
            used_days = used_days + NEW.total_days,
            updated_at = CURRENT_TIMESTAMP
        WHERE employee_id = NEW.employee_id 
          AND year = EXTRACT(YEAR FROM NEW.start_date);
    ELSIF NEW.status = 'REJECTED' OR NEW.status = 'CANCELLED' THEN
        -- Return days to available
        UPDATE vacation_balances
        SET 
            pending_days = pending_days - NEW.total_days,
            updated_at = CURRENT_TIMESTAMP
        WHERE employee_id = NEW.employee_id 
          AND year = EXTRACT(YEAR FROM NEW.start_date);
    ELSIF (TG_OP = 'INSERT' OR OLD.status != 'PENDING') AND NEW.status = 'PENDING' THEN
        -- Mark days as pending
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

-- Trigger: Update balance when request status changes
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

-- Trigger: Create vacation balance when employee is created
CREATE TRIGGER trg_employee_vacation_init
AFTER INSERT ON employees
FOR EACH ROW
EXECUTE FUNCTION initialize_vacation_balance();

COMMENT ON TABLE vacation_balances IS 'Annual vacation day balances per employee';
COMMENT ON TABLE vacation_requests IS 'Employee vacation requests with approval workflow';
COMMENT ON TABLE vacation_types IS 'Catalog of vacation/leave types';
COMMENT ON TABLE public_holidays IS 'Spanish and Basque Country public holidays';
