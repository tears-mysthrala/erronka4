#!/bin/bash
# ZG-Data Configuration Script
# PostgreSQL 16 + Redis 7

set -e

echo "=========================================="
echo "🔧 CONFIGURANDO ZG-DATA"
echo "=========================================="

# Configurar IP estática
cat > /etc/network/interfaces <<'EOF'
source /etc/network/interfaces.d/*

auto lo
iface lo inet loopback

allow-hotplug ens18
iface ens18 inet static
    address 192.168.20.20
    netmask 255.255.255.0
    gateway 192.168.20.1
    dns-nameservers 8.8.8.8 1.1.1.1
EOF

systemctl restart networking || true

# Instalar Docker
echo "📦 Instalando Docker..."
apt update
apt install -y ca-certificates curl gnupg
install -m 0755 -d /etc/apt/keyrings
curl -fsSL https://download.docker.com/linux/debian/gpg | gpg --dearmor -o /etc/apt/keyrings/docker.gpg
chmod a+r /etc/apt/keyrings/docker.gpg

echo "deb [arch="$(dpkg --print-architecture)" signed-by=/etc/apt/keyrings/docker.gpg] https://download.docker.com/linux/debian "$(. /etc/os-release && echo "$VERSION_CODENAME")" stable" | tee /etc/apt/sources.list.d/docker.list > /dev/null

apt update
apt install -y docker-ce docker-ce-cli containerd.io docker-buildx-plugin docker-compose-plugin

# Crear directorio de datos
mkdir -p /opt/zabala-data
cd /opt/zabala-data

# Docker Compose para PostgreSQL y Redis
cat > docker-compose.yml <<'EOF'
version: '3.8'

services:
  postgres:
    image: postgres:16-alpine
    container_name: zabala-postgres
    restart: always
    environment:
      POSTGRES_USER: zabala_user
      POSTGRES_PASSWORD: ZabalaSecure2026!
      POSTGRES_DB: zabala_hr_portal
    volumes:
      - ./pgdata:/var/lib/postgresql/data
      - ./init:/docker-entrypoint-initdb.d
    ports:
      - "5432:5432"
    networks:
      - zabala_data_net
    healthcheck:
      test: ["CMD-SHELL", "pg_isready -U zabala_user -d zabala_hr_portal"]
      interval: 10s
      timeout: 5s
      retries: 5

  redis:
    image: redis:7-alpine
    container_name: zabala-redis
    restart: always
    command: redis-server --requirepass ZabalaRedis2026! --appendonly yes
    volumes:
      - ./redisdata:/data
    ports:
      - "6379:6379"
    networks:
      - zabala_data_net
    healthcheck:
      test: ["CMD", "redis-cli", "-a", "ZabalaRedis2026!", "ping"]
      interval: 10s
      timeout: 3s
      retries: 5

networks:
  zabala_data_net:
    driver: bridge
EOF

# Crear directorio para inicialización
mkdir -p init

# Script SQL de inicialización
cat > init/01_init.sql <<'SQLEOF'
-- Zabala Gailetak HR Portal - Database Schema

-- Tabla de usuarios
CREATE TABLE IF NOT EXISTS users (
    id SERIAL PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    role VARCHAR(20) NOT NULL DEFAULT 'EMPLEADO',
    department VARCHAR(50),
    first_name VARCHAR(50),
    last_name VARCHAR(50),
    totp_secret VARCHAR(32),
    webauthn_credential VARCHAR(500),
    is_active BOOLEAN DEFAULT true,
    last_login TIMESTAMP,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabla de empleados
CREATE TABLE IF NOT EXISTS employees (
    id SERIAL PRIMARY KEY,
    user_id INTEGER REFERENCES users(id),
    employee_code VARCHAR(20) UNIQUE,
    hire_date DATE,
    position VARCHAR(100),
    section VARCHAR(50),
    salary DECIMAL(10,2),
    phone VARCHAR(20),
    address TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabla de vacaciones
CREATE TABLE IF NOT EXISTS vacation_requests (
    id SERIAL PRIMARY KEY,
    employee_id INTEGER REFERENCES employees(id),
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    days_requested INTEGER,
    status VARCHAR(20) DEFAULT 'PENDING',
    approved_by INTEGER REFERENCES users(id),
    comments TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabla de nóminas
CREATE TABLE IF NOT EXISTS payslips (
    id SERIAL PRIMARY KEY,
    employee_id INTEGER REFERENCES employees(id),
    year INTEGER NOT NULL,
    month INTEGER NOT NULL,
    period_start DATE,
    period_end DATE,
    gross_salary DECIMAL(10,2),
    net_salary DECIMAL(10,2),
    taxes DECIMAL(10,2),
    social_security DECIMAL(10,2),
    bonuses DECIMAL(10,2) DEFAULT 0,
    extra_hours DECIMAL(10,2) DEFAULT 0,
    other_deductions DECIMAL(10,2) DEFAULT 0,
    notes TEXT,
    file_path VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabla de documentos
CREATE TABLE IF NOT EXISTS documents (
    id SERIAL PRIMARY KEY,
    employee_id INTEGER REFERENCES employees(id),
    document_type VARCHAR(50),
    title VARCHAR(200),
    file_path VARCHAR(255),
    file_size INTEGER,
    mime_type VARCHAR(100),
    uploaded_by INTEGER REFERENCES users(id),
    is_encrypted BOOLEAN DEFAULT true,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabla de auditoría
CREATE TABLE IF NOT EXISTS audit_logs (
    id SERIAL PRIMARY KEY,
    user_id INTEGER REFERENCES users(id),
    action VARCHAR(50),
    resource VARCHAR(100),
    resource_id INTEGER,
    ip_address INET,
    user_agent TEXT,
    details JSONB,
    timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Índices
CREATE INDEX idx_users_email ON users(email);
CREATE INDEX idx_users_role ON users(role);
CREATE INDEX idx_employees_code ON employees(employee_code);
CREATE INDEX idx_payslips_employee ON payslips(employee_id);
CREATE INDEX idx_payslips_period ON payslips(year, month);
CREATE INDEX idx_audit_user ON audit_logs(user_id);
CREATE INDEX idx_audit_timestamp ON audit_logs(timestamp);

-- Datos de prueba
INSERT INTO users (username, email, password_hash, role, first_name, last_name) VALUES
('admin', 'admin@zabalagailetak.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'ADMIN', 'Administrador', 'Sistema'),
('rrhh', 'rrhh@zabalagailetak.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'RRHH_MGR', 'Jefe', 'RRHH'),
('empleado1', 'empleado1@zabalagailetak.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'EMPLEADO', 'Juan', 'García')
ON CONFLICT DO NOTHING;

SQLEOF

# Iniciar servicios
docker compose up -d

echo ""
echo "=========================================="
echo "✅ ZG-DATA CONFIGURADO"
echo "=========================================="
echo "Servicios:"
echo "  - PostgreSQL: 192.168.20.20:5432"
echo "  - Redis: 192.168.20.20:6379"
echo ""
echo "Credenciales:"
echo "  PostgreSQL: zabala_user / ZabalaSecure2026!"
echo "  Redis: ZabalaRedis2026!"
echo ""
echo "Base de datos: zabala_hr_portal"
