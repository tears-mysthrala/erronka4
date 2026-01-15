# Zabala Gailetak - HR Portal 🏢

Sistema interno de gestión de recursos humanos con seguridad avanzada implementada.

## 🎯 Estado del Proyecto

**Fase Actual**: ✅ Fase 3 Completa - Employee CRUD Full Stack  
**Última Actualización**: 15 de Enero, 2026

### Fases Completadas

- ✅ **Fase 1**: Estructura Base y Migraciones
- ✅ **Fase 2**: Autenticación Avanzada (JWT + MFA + RBAC)
- ✅ **Fase 3**: Employee CRUD Full Stack
  - ✅ Backend API PHP completo
  - ✅ Validación avanzada de datos españoles (NIF/NIE, IBAN, teléfono, CP)
  - ✅ Sanitización automática de entrada
  - ✅ Audit Trail / Historial de cambios
  - ✅ 82/82 tests unitarios pasando
  - ✅ 8 endpoints API funcionales
  - ✅ Interfaz web React con styled-components (~1,400 líneas)
  - ✅ App móvil React Native con Expo (~1,300 líneas)

---

## 🚀 Quick Start

### Prerequisitos

- Docker & Docker Compose
- Arch Linux (o compatible)
- Puertos disponibles: 8080 (HTTP), 8443 (HTTPS), 5432 (PostgreSQL), 6379 (Redis)

### Inicio Rápido

```bash
# 1. Clonar repositorio
cd "Zabala Gailetak"

# 2. Configurar variables de entorno
cd hr-portal
cp .env.example .env
# Editar .env con tus secretos

# 3. Iniciar servicios
cd ..
docker-compose -f docker-compose.hrportal.yml up -d

# 4. Instalar dependencias PHP
docker-compose -f docker-compose.hrportal.yml exec php composer install

# 5. Ejecutar migraciones
docker-compose -f docker-compose.hrportal.yml exec postgres psql -U hr_user -d hr_portal -f /docker-entrypoint-initdb.d/001_init_schema.sql

# 6. Iniciar interfaz web (opcional)
cd hr-portal/web
npm install
npm run dev
# Web disponible en: http://localhost:3001

# 7. Verificar instalación
curl http://localhost:8080/api/health
```

---

## 📋 Arquitectura

### Stack Tecnológico

**Backend**:
- PHP 8.4 (FPM Alpine)
- PostgreSQL 16 Alpine
- Redis 7 Alpine
- Nginx Alpine
- JWT (firebase/php-jwt)
- MFA/TOTP (spomky-labs/otphp)

**Frontend**:
- React 18
- React Router v6
- Styled Components
- Axios
- Vite

### Servicios Docker

| Servicio | Puerto | Estado | Descripción |
|----------|--------|--------|-------------|
| nginx | 8080, 8443 | ✅ Running | Reverse proxy y SSL |
| php | 9000 | ✅ Running | PHP-FPM 8.4 |
| postgres | 5432 | ✅ Healthy | Base de datos principal |
| redis | 6379 | ✅ Healthy | Cache y sesiones |

---

## 🔐 Autenticación y Seguridad

### Características Implementadas

- ✅ **JWT Tokens**: Access tokens (1h) y refresh tokens (7d)
- ✅ **MFA/TOTP**: Autenticación de dos factores con códigos QR
- ✅ **RBAC**: Control de acceso basado en roles (4 roles, 43 permisos)
- ✅ **Session Management**: Sesiones persistentes en Redis
- ✅ **Rate Limiting**: Protección contra fuerza bruta
- ✅ **Account Locking**: Bloqueo tras intentos fallidos
- ✅ **Backup Codes**: Códigos de respaldo para MFA

### Roles y Permisos

| Rol | Permisos | Descripción |
|-----|----------|-------------|
| **admin** | 43 (todos) | Acceso completo al sistema |
| **hr_manager** | 31 | Gestión de RRHH |
| **department_head** | 15 | Gestión de departamento |
| **employee** | 7 | Acceso básico |

---

## 🔌 API Endpoints

Ver documentación completa en:
- [FASE_2_COMPLETADA.md](./FASE_2_COMPLETADA.md) - Autenticación
- [FASE_3_EMPLOYEE_CRUD.md](./FASE_3_EMPLOYEE_CRUD.md) - Employee CRUD

### Públicos
- `GET /api/health` - Health check
- `POST /api/auth/login` - Login
- `POST /api/auth/refresh` - Renovar token

### Protegidos - Auth
- `GET /api/auth/me` - Usuario actual
- `POST /api/auth/logout` - Cerrar sesión
- `POST /api/auth/mfa/setup` - Configurar MFA
- `POST /api/auth/mfa/enable` - Activar MFA
- `POST /api/auth/mfa/verify` - Verificar MFA
- `POST /api/auth/mfa/disable` - Desactivar MFA

### Protegidos - Employees (Fase 3 ✨)
- `GET /api/employees` - Listar empleados (con paginación)
- `GET /api/employees/{id}` - Detalle de empleado
- `POST /api/employees` - Crear empleado
- `PUT /api/employees/{id}` - Actualizar empleado
- `DELETE /api/employees/{id}` - Baja lógica (soft delete)
- `POST /api/employees/{id}/restore` - Reactivar empleado
- `GET /api/employees/{id}/history` - Historial de cambios (Audit Trail)
- `GET /api/audit/user/{userId}` - Actividad del usuario

---

## 🧪 Testing

```bash
# Tests unitarios
docker-compose -f docker-compose.hrportal.yml exec php ./vendor/bin/phpunit --testdox

# Estado: ✅ 82/82 tests passing
# - TokenManager: 11/11 tests (Fase 2)
# - EmployeeController: 11/11 tests (Fase 3)
# - EmployeeValidator: 40/40 tests (Fase 3)
# - AuditLogger: 11/11 tests (Fase 3 - Audit Trail)
# - AuditController: 9/9 tests (Fase 3 - Audit Trail)
```

### Cobertura de Tests

**Autenticación** (11 tests):
- JWT token generation/validation
- MFA/TOTP setup
- Session management

**Employee CRUD** (11 tests):
- CRUD operations con RBAC
- Paginación y filtros
- Soft delete y restauración

**Validación de Datos** (40 tests):
- NIF/NIE español con letra correcta
- IBAN con checksum válido
- Teléfono español (+34)
- Código postal español (00000-52999)
- Email RFC5322
- Contraseña fuerte
- Sanitización XSS

**Audit Trail** (20 tests):
- AuditLogger (11 tests): Create, Update, Delete, Restore logging
- AuditController (9 tests): Historial de entidades, actividad de usuarios

---

## 👥 Usuario de Prueba

```
Email: admin@zabalagailetak.com
Password: password
Rol: admin
```

---

## 📚 Documentación

### Backend
- [FASE_2_COMPLETADA.md](./FASE_2_COMPLETADA.md) - Autenticación JWT + MFA + RBAC
- [FASE_3_EMPLOYEE_CRUD.md](./FASE_3_EMPLOYEE_CRUD.md) - Employee CRUD Backend + Tests

### Frontend
- [FASE_3_WEB_INTERFACE.md](./FASE_3_WEB_INTERFACE.md) - Interfaz Web React (~1,400 líneas)
- [FASE_3_MOBILE.md](./FASE_3_MOBILE.md) - App Móvil React Native (~1,300 líneas)
- [web/README.md](./web/README.md) - Setup y uso de React app
- [mobile/README.md](./mobile/README.md) - Setup y uso de mobile app

### General
- [API_DOCUMENTATION.md](../API_DOCUMENTATION.md) - API completa
- [MIGRATION_PLAN.md](../MIGRATION_PLAN.md) - Plan de migración
- [CORRECCIONES_TIPOS_SEGURIDAD.md](./CORRECCIONES_TIPOS_SEGURIDAD.md) - ✨ Correcciones de tipos y seguridad

---

## 📱 Clientes Disponibles

### 1. Web App (React)

```bash
cd web
npm install
npm start  # Abre en http://localhost:3001
```

**Características**:
- ✅ React 18.2 + React Router v6
- ✅ Styled-components para estilos
- ✅ 4 páginas: Login, Lista, Detalle, Formulario
- ✅ Paginación (10 items/página)
- ✅ Audit Trail timeline visual
- ✅ Interfaz en euskera

Ver [FASE_3_WEB_INTERFACE.md](./FASE_3_WEB_INTERFACE.md) para detalles.

### 2. Mobile App (React Native)

```bash
cd mobile
npm install
npm start  # Abre Expo DevTools
```

**Características**:
- ✅ Expo 50.0.0 + React Native 0.73.2
- ✅ React Navigation 6.1.9 (Stack Navigator)
- ✅ 4 pantallas: Login, Lista, Detalle, Formulario
- ✅ Pull-to-refresh
- ✅ AsyncStorage para tokens
- ✅ iOS y Android ready
- ✅ Interfaz en euskera

Ver [FASE_3_MOBILE.md](./FASE_3_MOBILE.md) para detalles.

---

**Versión**: 1.0.0-alpha  
**Estado**: En desarrollo activo  
**Licencia**: Proprietary - Zabala Gailetak
