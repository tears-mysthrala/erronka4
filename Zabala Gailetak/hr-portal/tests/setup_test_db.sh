#!/bin/bash

# Script para configurar base de datos de prueba para ejecutar tests de integración
# Este script crea una base de datos temporal PostgreSQL para testing

set -e

# Colores para output
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m' # No Color

echo -e "${YELLOW}=== Configurando Base de Datos de Prueba ===${NC}"

# Variables de configuración
DB_NAME="hr_portal_test"
DB_USER="hr_test_user"
DB_PASSWORD="hr_test_password"
DB_HOST="localhost"
DB_PORT="5432"

# Verificar si PostgreSQL está corriendo
if ! pg_isready -h $DB_HOST -p $DB_PORT > /dev/null 2>&1; then
    echo -e "${RED}Error: PostgreSQL no está corriendo${NC}"
    echo "Intenta iniciar PostgreSQL con: sudo systemctl start postgresql"
    exit 1
fi

echo -e "${GREEN}✓${NC} PostgreSQL está corriendo"

# Crear usuario si no existe
echo "Creando usuario de prueba..."
sudo -u postgres psql -c "DO \$\$
BEGIN
    IF NOT EXISTS (SELECT FROM pg_user WHERE usename = '$DB_USER') THEN
        CREATE USER $DB_USER WITH PASSWORD '$DB_PASSWORD';
    END IF;
END
\$\$;" 2>/dev/null || true

echo -e "${GREEN}✓${NC} Usuario creado/verificado"

# Eliminar base de datos si existe
echo "Eliminando base de datos anterior..."
sudo -u postgres psql -c "DROP DATABASE IF EXISTS $DB_NAME;" 2>/dev/null || true

# Crear base de datos
echo "Creando base de datos..."
sudo -u postgres psql -c "CREATE DATABASE $DB_NAME OWNER $DB_USER;"

echo -e "${GREEN}✓${NC} Base de datos creada"

# Otorgar permisos
sudo -u postgres psql -c "GRANT ALL PRIVILEGES ON DATABASE $DB_NAME TO $DB_USER;"

# Crear archivo .env.testing
ENV_FILE="../.env.testing"
cat > $ENV_FILE << EOF
# Configuración de prueba para PHPUnit
APP_ENV=testing
DB_HOST=$DB_HOST
DB_PORT=$DB_PORT
DB_NAME=$DB_NAME
DB_USER=$DB_USER
DB_PASSWORD=$DB_PASSWORD
REDIS_HOST=localhost
REDIS_PORT=6379
EOF

echo -e "${GREEN}✓${NC} Archivo .env.testing creado"

# Ejecutar migraciones
echo "Ejecutando migraciones..."
export $(cat $ENV_FILE | xargs)

# Crear esquema básico para tests
PGPASSWORD=$DB_PASSWORD psql -h $DB_HOST -p $DB_PORT -U $DB_USER -d $DB_NAME << 'EOSQL'

-- Crear tablas necesarias para tests
CREATE TABLE IF NOT EXISTS users (
    id SERIAL PRIMARY KEY,
    email VARCHAR(255) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    role VARCHAR(50) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS employees (
    id SERIAL PRIMARY KEY,
    user_id INTEGER REFERENCES users(id),
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    nif VARCHAR(20) UNIQUE NOT NULL,
    phone VARCHAR(20),
    address TEXT,
    postal_code VARCHAR(10),
    city VARCHAR(100),
    province VARCHAR(100),
    iban VARCHAR(34),
    salary DECIMAL(10,2),
    hire_date DATE,
    department_id INTEGER,
    position VARCHAR(100),
    manager_id INTEGER REFERENCES employees(id),
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP
);

CREATE TABLE IF NOT EXISTS departments (
    id SERIAL PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    manager_id INTEGER REFERENCES employees(id),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS vacation_balances (
    id SERIAL PRIMARY KEY,
    employee_id INTEGER REFERENCES employees(id) NOT NULL,
    year INTEGER NOT NULL,
    total_days DECIMAL(5,2) NOT NULL DEFAULT 22.0,
    used_days DECIMAL(5,2) NOT NULL DEFAULT 0.0,
    pending_days DECIMAL(5,2) NOT NULL DEFAULT 0.0,
    available_days DECIMAL(5,2) GENERATED ALWAYS AS (total_days - used_days - pending_days) STORED,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE(employee_id, year)
);

CREATE TABLE IF NOT EXISTS vacation_requests (
    id SERIAL PRIMARY KEY,
    employee_id INTEGER REFERENCES employees(id) NOT NULL,
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    total_days DECIMAL(5,2) NOT NULL,
    notes TEXT,
    status VARCHAR(50) NOT NULL DEFAULT 'PENDING',
    request_date DATE DEFAULT CURRENT_DATE,
    manager_approved_by INTEGER REFERENCES employees(id),
    manager_approved_at TIMESTAMP,
    manager_notes TEXT,
    hr_approved_by INTEGER REFERENCES employees(id),
    hr_approved_at TIMESTAMP,
    hr_notes TEXT,
    rejection_reason TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CHECK (end_date >= start_date),
    CHECK (total_days > 0),
    CHECK (status IN ('PENDING', 'MANAGER_APPROVED', 'APPROVED', 'REJECTED', 'CANCELLED'))
);

CREATE TABLE IF NOT EXISTS public_holidays (
    id SERIAL PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    date DATE NOT NULL UNIQUE,
    type VARCHAR(50) DEFAULT 'NATIONAL',
    province VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS audit_logs (
    id SERIAL PRIMARY KEY,
    user_id INTEGER REFERENCES users(id),
    entity_type VARCHAR(100) NOT NULL,
    entity_id INTEGER NOT NULL,
    action VARCHAR(50) NOT NULL,
    old_values JSONB,
    new_values JSONB,
    ip_address VARCHAR(45),
    user_agent TEXT,
    request_id VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insertar datos de prueba
INSERT INTO users (email, password_hash, role) VALUES
('admin@test.com', '$2y$12$LQv3c1yqBWVHxkd0LHAkCOYz6TtxMQJqhN8/LewY5gyZFgZZWwbhu', 'ADMIN'),
('manager@test.com', '$2y$12$LQv3c1yqBWVHxkd0LHAkCOYz6TtxMQJqhN8/LewY5gyZFgZZWwbhu', 'JEFE_SECCION'),
('employee@test.com', '$2y$12$LQv3c1yqBWVHxkd0LHAkCOYz6TtxMQJqhN8/LewY5gyZFgZZWwbhu', 'EMPLEADO'),
('hr@test.com', '$2y$12$LQv3c1yqBWVHxkd0LHAkCOYz6TtxMQJqhN8/LewY5gyZFgZZWwbhu', 'RRHH_MGR')
ON CONFLICT DO NOTHING;

INSERT INTO employees (user_id, first_name, last_name, nif, hire_date, department_id) VALUES
(1, 'Admin', 'User', '12345678A', '2020-01-01', 1),
(2, 'Manager', 'User', '23456789B', '2020-01-01', 1),
(3, 'Employee', 'User', '34567890C', '2021-01-01', 1),
(4, 'HR', 'Manager', '45678901D', '2020-01-01', 2)
ON CONFLICT DO NOTHING;

EOSQL

echo -e "${GREEN}✓${NC} Esquema de base de datos creado"
echo -e "${GREEN}✓${NC} Datos de prueba insertados"

echo -e "${GREEN}=== Configuración completada ===${NC}"
echo ""
echo "Configuración de la base de datos:"
echo "  Database: $DB_NAME"
echo "  User: $DB_USER"
echo "  Password: $DB_PASSWORD"
echo "  Host: $DB_HOST"
echo "  Port: $DB_PORT"
echo ""
echo "Ahora puedes ejecutar todos los tests con:"
echo -e "${YELLOW}  vendor/bin/phpunit${NC}"
