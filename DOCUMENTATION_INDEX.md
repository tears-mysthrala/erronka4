# Índice de Documentación - Zabala Gailetak HR Portal

## 📚 Documentación General

### Guías de Usuario
- [README Principal](README.md) - Visión general del proyecto
- [Guía de Inicio Rápido](QUICK_START_GUIDE.md) - Setup en 5 minutos
- [Contexto del Proyecto (AGENTS.md)](AGENTS.md) - Arquitectura, compliance y guía para desarrollo

### Documentación Académica
- [ER4.md](ER4.md) - Requisitos del reto académico
- [Errubrika (Excel)](Errubrika_Ziber_E4_25-26_t4.xlsx) - Rúbrica de evaluación

### Documentación Técnica
- [API REST](API_DOCUMENTATION.md) - Referencia de endpoints
- [Documentación del Proyecto](Zabala%20Gailetak/docs/PROJECT_DOCUMENTATION.md) - Documentación técnica completa
- [Paleta de Colores](Zabala%20Gailetak/docs/COLOR_PALETTE.md) - Sistema de diseño
- [Costes y Recursos](Zabala%20Gailetak/docs/COSTES_RECURSOS_IMPLEMENTACION.md) - Análisis financiero
- [Plan de Presupuesto](Zabala%20Gailetak/docs/PLAN_IMPLEMENTACION_PRESUPUESTO_ZABALA_GAILETAK.md) - Plan de implementación con presupuesto
- [Plan de Seguridad](Zabala%20Gailetak/docs/security_plan.md) - Plan de seguridad
- [SOP Desarrollo Seguro](Zabala%20Gailetak/docs/sop_secure_development.md) - Procedimiento de desarrollo seguro

## 🔧 Backend (PHP)

### Documentación Backend
- [README Backend](Zabala%20Gailetak/hr-portal/README.md) - Guía completa del backend
- [Configuración](Zabala%20Gailetak/hr-portal/config/config.php) - Archivo de configuración
- [Routes](Zabala%20Gailetak/hr-portal/config/routes.php) - Definición de rutas
- [Migraciones](Zabala%20Gailetak/hr-portal/migrations/) - Schema de base de datos

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
- [README Android](Zabala%20Gailetak/android-app/README.md) - Guía completa de la app
- [Guía Mobile](Zabala%20Gailetak/MOBILE_APP_GUIDE.md) - Guía de la aplicación móvil
- [Build Configuration](Zabala%20Gailetak/android-app/app/build.gradle.kts) - Configuración Gradle

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
- [docker-compose.hrportal.yml](Zabala%20Gailetak/docker-compose.hrportal.yml) - Orquestación de servicios
- [Dockerfile PHP](Zabala%20Gailetak/hr-portal/Dockerfile) - Imagen PHP
- [Nginx Config](Zabala%20Gailetak/nginx/nginx-hrportal.conf) - Configuración Nginx

### Scripts
- [Migrate Script](Zabala%20Gailetak/hr-portal/scripts/migrate.php) - Script de migraciones
- [Seed Admin](Zabala%20Gailetak/hr-portal/scripts/seed_admin_profile.php) - Script de seeding
- [Makefile](Zabala%20Gailetak/hr-portal/Makefile) - Comandos útiles
- [Verify Implementation](scripts/verify_implementation.sh) - Verificación de compliance

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
- Ver [Zabala Gailetak/docs/network_diagrams/](Zabala%20Gailetak/docs/network_diagrams/) para diagramas de red

## 🔐 Seguridad

### Documentos de Seguridad
- Políticas de Seguridad: `Zabala Gailetak/compliance/sgsi/`
- Plan de Seguridad: `Zabala Gailetak/docs/security_plan.md`
- Web Hardening: `Zabala Gailetak/security/web_hardening_sop.md`
- Mobile Security: `Zabala Gailetak/security/mobile_security_sop.md`

### Implementaciones de Seguridad
- CSRF Protection: [CSRFProtection.php](Zabala%20Gailetak/hr-portal/src/Security/CSRFProtection.php)
- Security Headers: [SecurityHeaders.php](Zabala%20Gailetak/hr-portal/src/Security/SecurityHeaders.php)
- Middleware: [SecurityHeadersMiddleware.php](Zabala%20Gailetak/hr-portal/src/Middleware/SecurityHeadersMiddleware.php)

### Compliance
- [Reporte de Cumplimiento ER4](Zabala%20Gailetak/compliance/ER4_COMPLIANCE_REPORT.md)
- [Evaluación de Compliance](Zabala%20Gailetak/compliance/COMPLIANCE_EVALUATION.md)
- [Auditoría de Documentación](Zabala%20Gailetak/compliance/auditoria_documentacion.md)
- [Plan de Compliance](Zabala%20Gailetak/compliance/compliance_plan.md)

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
- Tests ubicados en: `Zabala Gailetak/hr-portal/tests/`
- Framework: PHPUnit
- Comando: `composer test`

### Android Testing
- Tests ubicados en: `Zabala Gailetak/android-app/app/src/test/` y `androidTest/`
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

Consultar [AGENTS.md - Sección 6](AGENTS.md) para el estado actual de implementación.

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

### Archivos Archivados

Documentación de migración histórica disponible en `archive/migration/`.

---

**Última actualización**: 6 de Febrero de 2026  
**Versión**: 2.0.0  
**Mantenido por**: Equipo IT Zabala Gailetak
