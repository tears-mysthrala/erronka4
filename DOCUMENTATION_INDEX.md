# Dokumentazioaren Indizea - Zabala Gailetak HR Portal

## 📚 Dokumentazio Orokorra

### Erabiltzailearen Gidak
- [README Nagusia](README.md) - Proiektuaren ikuspegi orokorra
- [Hasiera Azkar Gida](QUICK_START_GUIDE.md) - Konfigurazioa 5 minututan
- [Proiektuaren Testuingurua (AGENTS.md)](AGENTS.md) - Arkitektura, compliance eta garapen gida

### Dokumentazio Akademikoa
- [ER4.md](ER4.md) - Erronka akademikoaren eskakizunak
- [Errubrika (Excel)](Errubrika_Ziber_E4_25-26_t4.xlsx) - Ebaluazioaren errubrika

### Dokumentazio Teknikoa
- [API REST](API_DOCUMENTATION.md) - Endpointen erreferentzia
- [Proiektuaren Dokumentazioa](Zabala%20Gailetak/docs/PROJECT_DOCUMENTATION.md) - Dokumentazio tekniko osoa
- [Kolore Paleta](Zabala%20Gailetak/docs/COLOR_PALETTE.md) - Diseinu sistema
- [Kostuak eta Baliabideak](Zabala%20Gailetak/docs/COSTES_RECURSOS_IMPLEMENTACION.md) - Analisi finantzarioa
- [Aurrekontu Plana](Zabala%20Gailetak/docs/PLAN_IMPLEMENTACION_PRESUPUESTO_ZABALA_GAILETAK.md) - Aurrekontuarekin inplementazio plana
- [Segurtasun Plana](Zabala%20Gailetak/docs/security_plan.md) - Segurtasun plana
- [Garapen Seguruaren SOP](Zabala%20Gailetak/docs/sop_secure_development.md) - Garapen seguruaren prozedimentua

## 🔧 Backend (PHP)

### Backend Dokumentazioa
- [Backend README](Zabala%20Gailetak/hr-portal/README.md) - Backendaren gida osoa
- [Konfigurazioa](Zabala%20Gailetak/hr-portal/config/config.php) - Konfigurazio fitxategia
- [Routes](Zabala%20Gailetak/hr-portal/config/routes.php) - Bideen definizioa
- [Migrazioak](Zabala%20Gailetak/hr-portal/migrations/) - Datu-basearen eskema

### Kodearen Egitura
```
hr-portal/
├── src/
│   ├── App.php                    # Aplikazio nagusia
│   ├── Auth/                      # Autentifikazioa
│   ├── Database/                  # DB geruza
│   ├── Http/                      # Request/Response
│   ├── Middleware/                # Middleware
│   ├── Routing/                   # Bideen sistema
│   └── Security/                  # Segurtasuna
├── config/                        # Konfigurazioa
├── public/                        # Entry point
├── migrations/                    # SQL migrazioak
└── tests/                         # PHPUnit testak
```

## 📱 Android App

### Android Dokumentazioa
- [Android README](Zabala%20Gailetak/android-app/README.md) - Apparen gida osoa
- [Mobile Gida](Zabala%20Gailetak/MOBILE_APP_GUIDE.md) - Aplikazio mugikorraren gida
- [Build Configuration](Zabala%20Gailetak/android-app/app/build.gradle.kts) - Gradle konfigurazioa

### Kodearen Egitura
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
- [docker-compose.hrportal.yml](Zabala%20Gailetak/docker-compose.hrportal.yml) - Zerbitzuen orkestrazioa
- [Dockerfile PHP](Zabala%20Gailetak/hr-portal/Dockerfile) - PHP irudia
- [Nginx Config](Zabala%20Gailetak/nginx/nginx-hrportal.conf) - Nginx konfigurazioa

### Scriptak
- [Migrate Script](Zabala%20Gailetak/hr-portal/scripts/migrate.php) - Migrazioen scripta
- [Seed Admin](Zabala%20Gailetak/hr-portal/scripts/seed_admin_profile.php) - Seeding scripta
- [Makefile](Zabala%20Gailetak/hr-portal/Makefile) - Komando erabilgarriak
- [Verify Implementation](scripts/verify_implementation.sh) - Compliance egiaztapena

## 🗄️ Datu-basea

### PostgreSQL
- [Eskema Hasierakoa](hr-portal/migrations/001_init_schema.sql) - Eskema osoa honekin:
  - Erabiltzaileen eta langileen taulak
  - Oporren sistema
  - Dokumentuen kudeaketa
  - Nominen sistema
  - Chat eta mezularitza
  - Kexen sistema
  - Auditoria
  - Jakinarazpenak

### Diagramak
- Ikusi [Zabala Gailetak/docs/network_diagrams/](Zabala%20Gailetak/docs/network_diagrams/) sare diagramak ikusteko

## 🔐 Segurtasuna

### Segurtasun Dokumentuak
- Segurtasun Politikak: `Zabala Gailetak/compliance/sgsi/`
- Segurtasun Plana: `Zabala Gailetak/docs/security_plan.md`
- Web Hardening: `Zabala Gailetak/security/web_hardening_sop.md`
- Mobile Security: `Zabala Gailetak/security/mobile_security_sop.md`

### Segurtasun Inplementazioak
- CSRF Protection: [CSRFProtection.php](Zabala%20Gailetak/hr-portal/src/Security/CSRFProtection.php)
- Security Headers: [SecurityHeaders.php](Zabala%20Gailetak/hr-portal/src/Security/SecurityHeaders.php)
- Middleware: [SecurityHeadersMiddleware.php](Zabala%20Gailetak/hr-portal/src/Middleware/SecurityHeadersMiddleware.php)

### Compliance
- [ER4 Betetze Txostena](Zabala%20Gailetak/compliance/ER4_COMPLIANCE_REPORT.md)
- [Compliance Ebaluazioa](Zabala%20Gailetak/compliance/COMPLIANCE_EVALUATION.md)
- [Dokumentazio Auditoria](Zabala%20Gailetak/compliance/auditoria_documentacion.md)
- [Compliance Plana](Zabala%20Gailetak/compliance/compliance_plan.md)

### GDPR
Dokumentazioa `Zabala Gailetak/compliance/gdpr/`:
- Cookie Policy
- Data Breach Notification Template
- Data Processing Register
- Data Retention Schedule
- Data Subject Rights Procedures
- DPIA Template
- Privacy Notice

### SGSI (Informazioaren Segurtasunaren Kudeaketa Sistema)
Dokumentazioa `Zabala Gailetak/compliance/sgsi/`:
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
- Testak kokatuta: `Zabala Gailetak/hr-portal/tests/`
- Framework: PHPUnit
- Komandoa: `composer test`

### Android Testing
- Testak kokatuta: `Zabala Gailetak/android-app/app/src/test/` eta `androidTest/`
- Framework: JUnit + Espresso
- Komandoa: `./gradlew test`

## 📊 Azpiegitura

### Sarea
- Konfigurazioa: `Zabala Gailetak/infrastructure/network/`
- Network Segmentation SOP
- Network Inventory

### Sistemak
Dokumentazioa `Zabala Gailetak/infrastructure/systems/`:
- SOP Backup & Recovery
- SOP Change Management
- SOP Patch Management
- SOP Server Hardening
- SOP User Access

## 🎯 Roadmap

Kontsultatu [AGENTS.md - 6. Sekzioa](AGENTS.md) inplementazioaren uneko egoera jakiteko.

### Proiektuaren Faseak

| Fase | Iraupena | Egoera | Deskribapena |
|------|----------|--------|--------------|
| 1. Fasea | 4 aste | ✅ Martxan | Oinarria (oinarrizko azpiegitura) |
| 2. Fasea | 4 aste | ⏳ Zain | Autentifikazio aurreratua (MFA + Passkey) |
| 3. Fasea | 6 aste | ⏳ Zain | Langileen kudeaketa |
| 4. Fasea | 6 aste | ⏳ Zain | Oporren sistema |
| 5. Fasea | 4 aste | ⏳ Zain | Dokumentuen kudeaketa |
| 6. Fasea | 4 aste | ⏳ Zain | Nominak |
| 7. Fasea | 6 aste | ⏳ Zain | Barne chat |
| 8. Fasea | 4 aste | ⏳ Zain | Kexen sistema |
| 9. Fasea | 6 aste | ⏳ Zain | Extras eta produkzioa |

## 📞 Kontaktuak

### Laguntza Teknikoa
- Email: it@zabalagailetak.com
- Telefonoa: [Kontaktu zenbakia]

### Garapen Taldea
- Lead PHP Developer: [Izena]
- Lead Android Developer: [Izena]
- DevOps: [Izena]
- Project Manager: [Izena]

## 🔗 Lotura Erabilgarriak

### Kanpokoak
- [PHP 8.4 Documentation](https://www.php.net/docs.php)
- [PostgreSQL 16 Documentation](https://www.postgresql.org/docs/16/)
- [Kotlin Documentation](https://kotlinlang.org/docs/home.html)
- [Jetpack Compose](https://developer.android.com/jetpack/compose)
- [PSR Standards](https://www.php-fig.org/psr/)

### Barnekoak
- Git Repository: [Repositorioaren URL]
- Project Management: [Jira/Trello/etc URL]
- CI/CD Pipeline: [Jenkins/GitLab CI/etc URL]
- Documentation Wiki: [Barne wikiaren URL]

---

### Artxibatutako Fitxategiak

Migrazio historikoaren dokumentazioa eskuragarri `archive/migration/`.

---

**Azken eguneraketa**: 2026ko Otsailaren 6a  
**Bertsioa**: 2.0.0  
**Mantentzen du**: Zabala Gailetak IT Taldea
