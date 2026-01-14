# Índice de Documentación - Zabala Gailetak HR Portal

## 📚 Documentación General

### Guías de Usuario
- [README Principal](README.md) - Visión general del proyecto
- [Guía de Inicio Rápido](QUICK_START_GUIDE.md) - Setup en 5 minutos
- [Plan de Migración](MIGRATION_PLAN.md) - Plan completo de implementación

### Reportes del Proyecto
- [Reporte de Implementación](IMPLEMENTATION_REPORT.md) - Estado actual
- [Resumen de Implementación](IMPLEMENTATION_SUMMARY.md) - Resumen ejecutivo
- [Costes y Recursos](COSTES_RECURSOS_IMPLEMENTACION.md) - Análisis financiero

### Documentación Técnica
- [Documentación del Proyecto](PROJECT_DOCUMENTATION.md) - Documentación técnica completa
- [Reporte de Cumplimiento ER4](ER4_COMPLIANCE_REPORT.md) - Compliance report
- [Resumen de Verificación ER4](ER4_VERIFICATION_SUMMARY.txt) - Verificación

## 🔧 Backend (PHP)

### Documentación Backend
- [README Backend](hr-portal/README.md) - Guía completa del backend
- [Configuración](hr-portal/config/config.php) - Archivo de configuración
- [Routes](hr-portal/config/routes.php) - Definición de rutas
- [Migraciones](hr-portal/migrations/) - Schema de base de datos

### Estructura del Código
```
hr-portal/
├── src/
│   ├── App.php                    # Aplicación principal
│   ├── Auth/                      # Autenticación
│   ├── Database/                  # Capa de BD
│   ├── Http/                      # Request/Response
│   ├── Middleware/                # Middleware
│   ├── Routing/                   # Sistema de rutas
│   └── Security/                  # Seguridad
├── config/                        # Configuración
├── public/                        # Entry point
├── migrations/                    # Migraciones SQL
└── tests/                         # Tests PHPUnit
```

## 📱 Android App

### Documentación Android
- [README Android](android-app/README.md) - Guía completa de la app
- [Build Configuration](android-app/app/build.gradle.kts) - Configuración Gradle
- [Manifest](android-app/app/src/main/AndroidManifest.xml) - Configuración de la app

### Estructura del Código
```
android-app/app/src/main/
├── java/com/zabalagailetak/hrapp/
│   ├── HrApplication.kt           # Application class
│   ├── data/                      # Data layer
│   │   ├── local/                 # Room database
│   │   ├── remote/                # Retrofit API
│   │   └── repository/            # Repositories
│   ├── domain/                    # Domain layer
│   │   ├── model/                 # Domain models
│   │   ├── repository/            # Repository interfaces
│   │   └── usecase/               # Use cases
│   └── presentation/              # Presentation layer
│       ├── ui/                    # Compose UI
│       ├── navigation/            # Navigation
│       └── viewmodel/             # ViewModels
├── res/                           # Resources
└── AndroidManifest.xml
```

## 🐳 DevOps & Infrastructure

### Docker
- [docker-compose.hrportal.yml](docker-compose.hrportal.yml) - Orquestación de servicios
- [Dockerfile PHP](hr-portal/Dockerfile) - Imagen PHP
- [Nginx Config](nginx/nginx-hrportal.conf) - Configuración Nginx

### Scripts
- [Migrate Script](hr-portal/scripts/migrate.sh) - Script de migraciones
- [Makefile](hr-portal/Makefile) - Comandos útiles

## 🗄️ Base de Datos

### PostgreSQL
- [Schema Inicial](hr-portal/migrations/001_init_schema.sql) - Schema completo con:
  - Tablas de usuarios y empleados
  - Sistema de vacaciones
  - Gestión documental
  - Sistema de nóminas
  - Chat y mensajería
  - Sistema de quejas
  - Auditoría
  - Notificaciones

### Diagramas
- Ver [MIGRATION_PLAN.md - Sección 3.5](MIGRATION_PLAN.md#35-modelo-de-datos-postgresql) para diagramas ER

## 🔐 Seguridad

### Documentos de Seguridad
- Políticas de Seguridad: `Zabala Gailetak/compliance/sgsi/`
- Plan de Seguridad: `Zabala Gailetak/docs/security_plan.md`
- Web Hardening: `Zabala Gailetak/security/web_hardening_sop.md`
- Mobile Security: `Zabala Gailetak/security/mobile_security_sop.md`

### Implementaciones de Seguridad
- CSRF Protection: [CSRFProtection.php](hr-portal/src/Security/CSRFProtection.php)
- Security Headers: [SecurityHeaders.php](hr-portal/src/Security/SecurityHeaders.php)
- Middleware: [SecurityHeadersMiddleware.php](hr-portal/src/Middleware/SecurityHeadersMiddleware.php)

## 📋 Compliance

### GDPR
Documentación en `Zabala Gailetak/compliance/gdpr/`:
- Cookie Policy
- Data Breach Notification Template
- Data Processing Register
- Data Retention Schedule
- Data Subject Rights Procedures
- DPIA Template
- Privacy Notice

### SGSI (Sistema de Gestión de Seguridad de la Información)
Documentación en `Zabala Gailetak/compliance/sgsi/`:
- Acceptable Use Policy
- Asset Register
- Business Continuity Plan
- Communication Plan
- Information Security Policy
- Password Policy
- Risk Assessment
- Statement of Applicability

## 🧪 Testing

### Backend Testing
- Tests ubicados en: `hr-portal/tests/`
- Framework: PHPUnit
- Comando: `composer test`

### Android Testing
- Tests ubicados en: `android-app/app/src/test/` y `androidTest/`
- Framework: JUnit + Espresso
- Comando: `./gradlew test`

## 📊 Infraestructura

### Network
- Configuración: `Zabala Gailetak/infrastructure/network/`
- Network Segmentation SOP
- Network Inventory

### Systems
Documentación en `Zabala Gailetak/infrastructure/systems/`:
- SOP Backup & Recovery
- SOP Change Management
- SOP Patch Management
- SOP Server Hardening
- SOP User Access

## 🎯 Roadmap

Ver plan de implementación detallado por fases en:
- [MIGRATION_PLAN.md - Sección 7](MIGRATION_PLAN.md#-plan-de-implementaci%C3%B3n-por-fases)

### Fases del Proyecto

| Fase | Duración | Estado | Descripción |
|------|----------|--------|-------------|
| Fase 1 | 4 semanas | ✅ En curso | Fundación (infraestructura base) |
| Fase 2 | 4 semanas | ⏳ Pendiente | Autenticación avanzada (MFA + Passkey) |
| Fase 3 | 6 semanas | ⏳ Pendiente | Gestión de empleados |
| Fase 4 | 6 semanas | ⏳ Pendiente | Sistema de vacaciones |
| Fase 5 | 4 semanas | ⏳ Pendiente | Gestión documental |
| Fase 6 | 4 semanas | ⏳ Pendiente | Nóminas |
| Fase 7 | 6 semanas | ⏳ Pendiente | Chat interno |
| Fase 8 | 4 semanas | ⏳ Pendiente | Sistema de quejas |
| Fase 9 | 6 semanas | ⏳ Pendiente | Extras y producción |

## 📞 Contactos

### Soporte Técnico
- Email: it@zabalagailetak.com
- Teléfono: [Número de contacto]

### Equipo de Desarrollo
- Lead PHP Developer: [Nombre]
- Lead Android Developer: [Nombre]
- DevOps: [Nombre]
- Project Manager: [Nombre]

## 🔗 Enlaces Útiles

### Externos
- [PHP 8.4 Documentation](https://www.php.net/docs.php)
- [PostgreSQL 16 Documentation](https://www.postgresql.org/docs/16/)
- [Kotlin Documentation](https://kotlinlang.org/docs/home.html)
- [Jetpack Compose](https://developer.android.com/jetpack/compose)
- [PSR Standards](https://www.php-fig.org/psr/)

### Internos
- Git Repository: [URL del repositorio]
- Project Management: [URL Jira/Trello/etc]
- CI/CD Pipeline: [URL Jenkins/GitLab CI/etc]
- Documentation Wiki: [URL wiki interna]

---

**Última actualización**: 14 de Enero de 2026  
**Versión**: 1.0.0  
**Mantenido por**: Equipo IT Zabala Gailetak
