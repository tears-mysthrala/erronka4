# Zabala Gailetak - Portal RRHH

Sistema interno de gestión de recursos humanos para Zabala Gailetak.

## 📋 Resumen del Proyecto

**Tipo**: Portal interno de gestión de RRHH  
**Plataformas**: Web (PHP) + Android (Kotlin)  
**Base de datos**: PostgreSQL 16  
**Estado**: En desarrollo - Fase 1 (Fundación)  
**Fecha inicio**: Enero 2026  
**Fecha estimada finalización**: Diciembre 2026

## 🎯 Alcance del Proyecto

Sistema completo de gestión de recursos humanos que incluye:

- ✅ Gestión de empleados (altas, bajas, modificaciones)
- ✅ Sistema de vacaciones (solicitudes, aprobaciones, calendario)
- ✅ Consulta de nóminas
- ✅ Gestión documental
- ✅ Chat interno (RRHH y por departamento)
- ✅ Sistema de quejas y sugerencias
- ✅ Autenticación avanzada (MFA + Passkey)
- ✅ Auditoría completa

## 🏗️ Arquitectura

### Backend - PHP Vanilla

Ubicación: `Zabala Gailetak/hr-portal/`

- **Versión**: PHP 8.4
- **Estándares**: PSR-1, PSR-4, PSR-7, PSR-11, PSR-15, PSR-17
- **Base de datos**: PostgreSQL 16
- **Caché**: Redis 7
- **Web Server**: Nginx

[Ver README del backend →](Zabala%20Gailetak/hr-portal/README.md)

### Mobile App - Android (Kotlin)

Ubicación: `Zabala Gailetak/android-app/`

- **Lenguaje**: Kotlin 2.0
- **UI**: Jetpack Compose + Material 3
- **Arquitectura**: Clean Architecture + MVI
- **Min SDK**: 26 (Android 8.0)
- **Target SDK**: 35 (Android 15)

[Ver README de Android →](Zabala%20Gailetak/android-app/README.md)

## 🚀 Inicio Rápido

### Prerequisitos

- Docker >= 20.10
- Docker Compose >= 2.0
- PHP >= 8.4 (para desarrollo local)
- Android Studio (para la app móvil)

### Instalación

#### 1. Backend (HR Portal)

```bash
# Clonar repositorio
git clone <repository-url>
cd "Zabala Gailetak/hr-portal"

# Copiar archivo de entorno
cp .env.example .env

# Editar .env con tus configuraciones
nano .env

# Iniciar servicios con Docker
cd ..
docker-compose -f docker-compose.hrportal.yml up -d

# Ejecutar migraciones
cd hr-portal
chmod +x scripts/migrate.sh
./scripts/migrate.sh

# O usar Makefile
make up
make migrate
```

El portal web estará disponible en: `http://localhost:8080`

#### 2. Android App

```bash
cd android-app

# Abrir en Android Studio
# Sync Gradle
# Ejecutar en emulador o dispositivo
```

## 📁 Estructura del Proyecto

```
/
├── hr-portal/              # Backend PHP
│   ├── config/            # Configuración
│   ├── public/            # Entry point
│   ├── src/               # Código fuente PSR-4
│   ├── migrations/        # Migraciones DB
│   ├── tests/             # Tests PHPUnit
│   └── Dockerfile         # Contenedor PHP
│
├── android-app/           # App móvil Android
│   ├── app/src/main/      # Código fuente
│   ├── build.gradle.kts   # Configuración Gradle
│   └── README.md          # Documentación Android
│
├── nginx/                 # Configuración Nginx
│   └── nginx-hrportal.conf
│
├── docker-compose.hrportal.yml  # Orquestación Docker
│
└── MIGRATION_PLAN.md      # Plan de migración completo
```

## 📚 Documentación

### Documentos Principales

- [Plan de Migración](MIGRATION_PLAN.md) - Plan completo de implementación
- [Guía de Inicio Rápido](QUICK_START_GUIDE.md) - Guía rápida de setup
- [Índice de Documentación](DOCUMENTATION_INDEX.md) - Índice completo
- [Reporte de Implementación](IMPLEMENTATION_REPORT.md) - Estado actual

### Documentación del Backend

- [README Backend](hr-portal/README.md)
- API Documentation (próximamente)
- Security Guidelines (próximamente)

### Documentación Android

- [README Android](android-app/README.md)
- Architecture Guide (próximamente)

## 🔒 Seguridad

El sistema implementa múltiples capas de seguridad:

- ✅ Autenticación JWT
- ✅ MFA (TOTP) obligatorio
- ✅ Passkey/WebAuthn support
- ✅ Rate limiting
- ✅ CSRF protection
- ✅ XSS protection
- ✅ Security headers (CSP, X-Frame-Options, etc.)
- ✅ Password hashing (bcrypt)
- ✅ Prepared statements (SQL injection prevention)
- ✅ Auditoría completa de acciones

## 👥 Roles de Usuario

| Rol | Descripción | Permisos |
|-----|-------------|----------|
| **ADMIN** | Administrador del sistema | Acceso completo |
| **RRHH MGR** | Responsable de RRHH | Gestión de empleados, aprobaciones |
| **JEFE SECCIÓN** | Jefe de departamento | Gestión de su equipo |
| **EMPLEADO** | Usuario estándar | Acceso a sus propios datos |

Ver [matriz completa de permisos](MIGRATION_PLAN.md#23-matriz-de-permisos)

## 🧪 Testing

### Backend

```bash
cd hr-portal

# Ejecutar tests
composer test

# Con cobertura
composer test -- --coverage-html coverage/

# Tests específicos
./vendor/bin/phpunit tests/Unit/Auth/SessionManagerTest.php
```

### Android

```bash
cd android-app

# Unit tests
./gradlew test

# Instrumented tests
./gradlew connectedAndroidTest
```

## 📊 Estado del Proyecto

### Fase Actual: Fase 1 - Fundación (Semanas 1-4)

✅ Completado:
- Estructura del proyecto PHP
- Estructura del proyecto Android
- Configuración Docker
- Schema de base de datos PostgreSQL
- Sistema de routing básico
- Middleware de seguridad

⏳ En progreso:
- Implementación de autenticación básica
- API REST endpoints
- Interfaz de login web
- Pantallas de login Android

📅 Próximas fases:
- Fase 2: Autenticación avanzada (MFA + Passkey)
- Fase 3: Gestión de empleados
- Fase 4: Sistema de vacaciones
- [Ver plan completo](MIGRATION_PLAN.md#-plan-de-implementaci%C3%B3n-por-fases)

## 🛠️ Comandos Útiles

### Backend

```bash
# Con Makefile
make up          # Iniciar servicios
make down        # Detener servicios
make logs        # Ver logs
make migrate     # Ejecutar migraciones
make test        # Ejecutar tests
make lint        # Linter
make shell-php   # Shell del contenedor PHP
make shell-db    # Shell de PostgreSQL

# Sin Makefile
docker-compose -f docker-compose.hrportal.yml up -d
docker-compose -f docker-compose.hrportal.yml logs -f
```

### Android

```bash
./gradlew assembleDebug    # Build debug
./gradlew assembleRelease  # Build release
./gradlew test             # Tests
./gradlew lint             # Linter
```

## 📞 Soporte

Para soporte técnico, contactar con:

- **IT Zabala Gailetak**: it@zabalagailetak.com
- **Project Manager**: [nombre]@zabalagailetak.com

## 📝 Licencia

Propietario - Zabala Gailetak  
Uso interno exclusivo

## 📈 Changelog

### [1.0.0] - 2026-01-14

#### Añadido
- Estructura inicial del proyecto PHP
- Estructura inicial del proyecto Android
- Sistema de base de datos PostgreSQL
- Configuración Docker completa
- Sistema de routing y middleware
- Documentación del proyecto

#### Eliminado
- Sistema antiguo Node.js/Express
- Frontend antiguo React
- App móvil antigua React Native
- MongoDB y configuración asociada

---

**Versión**: 1.0.0  
**Última actualización**: 14 de Enero de 2026  
**Mantenido por**: Equipo IT Zabala Gailetak
