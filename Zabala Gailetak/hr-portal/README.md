# Zabala Gailetak HR Portal

Sistema interno de gestión de recursos humanos para Zabala Gailetak.

## 🚀 Tecnologías

- **Backend**: PHP 8.4 Vanilla (PSR-4, PSR-7, PSR-15)
- **Base de Datos**: PostgreSQL 16
- **Caché**: Redis 7
- **Web Server**: Nginx
- **Containerización**: Docker & Docker Compose

## 📋 Requisitos

- Docker >= 20.10
- Docker Compose >= 2.0
- PHP >= 8.4 (para desarrollo local)
- Composer >= 2.0

## 🏗️ Instalación

### Con Docker (Recomendado)

```bash
# Clonar repositorio
git clone <repository-url>
cd hr-portal

# Copiar archivo de entorno
cp .env.example .env

# Editar .env con tus configuraciones
nano .env

# Iniciar servicios
docker-compose up -d

# Ejecutar migraciones
docker-compose exec php php /var/www/html/scripts/migrate.php
```

### Desarrollo Local

```bash
# Instalar dependencias
composer install

# Copiar archivo de entorno
cp .env.example .env

# Configurar base de datos en .env
# Ejecutar migraciones
php scripts/migrate.php

# Iniciar servidor de desarrollo
php -S localhost:8000 -t public/
```

## 📁 Estructura del Proyecto

```
hr-portal/
├── config/                 # Configuración
├── public/                 # Archivos públicos (entry point)
├── src/                    # Código fuente (PSR-4)
│   ├── Auth/              # Autenticación y autorización
│   ├── Database/          # Capa de base de datos
│   ├── Http/              # Request/Response (PSR-7)
│   ├── Middleware/        # Middleware (PSR-15)
│   ├── Models/            # Modelos de datos
│   ├── Repositories/      # Repositorios
│   ├── Routing/           # Sistema de rutas
│   ├── Security/          # Seguridad (CSRF, XSS, etc.)
│   ├── Services/          # Lógica de negocio
│   └── View/              # Sistema de templates
├── templates/             # Plantillas HTML
├── tests/                 # Tests (PHPUnit)
├── migrations/            # Migraciones de base de datos
├── logs/                  # Archivos de log
└── storage/               # Almacenamiento de archivos
```

## 🔧 Comandos Útiles

### Composer

```bash
# Instalar dependencias
composer install

# Actualizar dependencias
composer update

# Tests
composer test

# Análisis estático (PHPStan)
composer phpstan

# Code style check
composer cs-check

# Code style fix
composer cs-fix
```

### Docker

```bash
# Iniciar servicios
docker-compose up -d

# Ver logs
docker-compose logs -f

# Detener servicios
docker-compose down

# Reconstruir contenedores
docker-compose build --no-cache

# Ejecutar comando en contenedor PHP
docker-compose exec php <command>
```

## 🧪 Testing

```bash
# Ejecutar todos los tests
composer test

# Tests con cobertura
composer test -- --coverage-html coverage/

# Test específico
./vendor/bin/phpunit tests/Unit/Auth/SessionManagerTest.php
```

## 🔒 Seguridad

Este proyecto implementa múltiples capas de seguridad:

- ✅ Headers de seguridad (CSP, X-Frame-Options, etc.)
- ✅ Protección CSRF
- ✅ Protección XSS
- ✅ Rate limiting
- ✅ Autenticación JWT
- ✅ MFA (TOTP)
- ✅ Passkey/WebAuthn
- ✅ Password hashing (bcrypt)
- ✅ Auditoría completa
- ✅ Prepared statements (SQL injection prevention)

## 📚 API Documentation

La documentación completa de la API está disponible en `/docs/API.md`.

### Endpoints Principales

- `POST /api/auth/login` - Login
- `POST /api/auth/logout` - Logout
- `GET /api/employees` - Listar empleados
- `POST /api/vacations` - Solicitar vacaciones
- `GET /api/payroll` - Ver nóminas
- `POST /api/documents/upload` - Subir documentos
- `GET /api/chat/messages/{id}` - Mensajes de chat

## 🌐 Entorno de Producción

### Requisitos Mínimos

- CPU: 2 cores
- RAM: 4GB
- Disco: 20GB SSD
- PostgreSQL 16
- Redis 7
- PHP 8.4 con extensiones: pdo_pgsql, redis, gd, opcache

### Configuración

1. Configurar variables de entorno en `.env`
2. Establecer `APP_ENV=production` y `APP_DEBUG=false`
3. Configurar HTTPS/SSL
4. Configurar backups automáticos
5. Configurar monitoreo y alertas

## 👥 Contribuir

Ver [CONTRIBUTING.md](docs/CONTRIBUTING.md) para guías de contribución.

## 📝 Licencia

Propietario - Zabala Gailetak

## 📞 Soporte

Para soporte, contactar con el equipo de IT de Zabala Gailetak.

---

**Versión**: 1.0.0  
**Última actualización**: Enero 2026
