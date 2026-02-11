# Zabala Gailetak - Giza Baliabideen Ataria

Barneko giza baliabideen kudeaketa sistema Zabala Gailetak-entzat.

## 📋 Proiektuaren Laburpena

**Mota**: Barneko GB kudeaketa ataria  
**Plataformak**: Web (PHP) + Android (Kotlin)  
**Datu-basea**: PostgreSQL 16  
**Egoera**: Garapenean - 1. Fasea (Oinarria)  
**Hasiera data**: 2026ko urtarrila  
**Amaiera data estimatua**: 2026ko abendua

## 🎯 Proiektuaren Esparrua

Giza baliabideen kudeaketa sistema osoa, honakoak barne:

- ✅ Langileen kudeaketa (altak, bajak, aldaketak)
- ✅ Opor sistema (eskariak, onarpenak, egutegia)
- ✅ Nominen kontsulta
- ✅ Dokumentuen kudeaketa
- ✅ Barne txata (GB eta sailka)
- ✅ Kexa eta iradokizunen sistema
- ✅ Autentikazio aurreratua (MFA + Passkey)
- ✅ Auditorio osoa

## 🏗️ Arkitektura

### Backend - PHP Vanilla

Kokapena: `Zabala Gailetak/hr-portal/`

- **Bertsioa**: PHP 8.4
- **Estandarrak**: PSR-1, PSR-4, PSR-7, PSR-11, PSR-15, PSR-17
- **Datu-basea**: PostgreSQL 16
- **Cachea**: Redis 7
- **Web Zerbitzaria**: Nginx

[Ikusi backend-aren README →](Zabala%20Gailetak/hr-portal/README.md)

### Mobile App - Android (Kotlin)

Kokapena: `Zabala Gailetak/android-app/`

- **Hizkuntza**: Kotlin 2.0
- **UI**: Jetpack Compose + Material 3
- **Arkitektura**: Clean Architecture + MVI
- **Min SDK**: 26 (Android 8.0)
- **Target SDK**: 35 (Android 15)

[Ikusi Android-aren README →](Zabala%20Gailetak/android-app/README.md)

## 🚀 Hasiera Azkarra

### Aurrebaldintzak

- Docker >= 20.10
- Docker Compose >= 2.0
- PHP >= 8.4 (tokiko garapenerako)
- Android Studio (aplikazio mugikorrerako)

### Instalazioa

#### 1. Backend (GB Ataria)

```bash
# Clonar repositorio
git clone <repository-url>
cd "Zabala Gailetak/hr-portal"

# Kopiatu ingurune fitxategia
cp .env.example .env

# Editatu .env zure konfigurazioekin
nano .env

# Abiarazi zerbitzuak Docker-ekin
cd ..
docker-compose -f docker-compose.hrportal.yml up -d

# Exekutatu migrazioak
cd hr-portal
chmod +x scripts/migrate.sh
./scripts/migrate.sh

# Edo Makefile erabili
make up
make migrate
```

Web ataria eskuragarri egongo da: `http://localhost:8080`

#### 2. Android App

```bash
cd android-app

# Ireki Android Studio-n
# Sync Gradle
# Exekutatu emuladorean edo gailuan
```

## 📁 Proiektuaren Egitura

```
/
├── hr-portal/              # Backend PHP
│   ├── config/            # Konfigurazioa
│   ├── public/            # Entry point
│   ├── src/               # PSR-4 iturburu kodea
│   ├── migrations/        # DB migrazioak
│   ├── tests/             # PHPUnit testak
│   └── Dockerfile         # PHP kontenedorea
│
├── android-app/           # Android aplikazio mugikorra
│   ├── app/src/main/      # Iturburu kodea
│   ├── build.gradle.kts   # Gradle konfigurazioa
│   └── README.md          # Android dokumentazioa
│
├── nginx/                 # Nginx konfigurazioa
│   └── nginx-hrportal.conf
│
├── docker-compose.hrportal.yml  # Docker orkestrazioa
│
└── MIGRATION_PLAN.md      # Migrazio plan osoa
```

## 📚 Dokumentazioa

### Dokumentu Nagusiak

- [Migrazio Plana](MIGRATION_PLAN.md) - Implementazio plan osoa
- [Hasiera Azkarreko Gida](QUICK_START_GUIDE.md) - Konfigurazio gida azkarra
- [Dokumentazioaren Indizea](DOCUMENTATION_INDEX.md) - Indize osoa
- [Implementazio Txostena](IMPLEMENTATION_REPORT.md) - Uneko egoera

### Backend-aren Dokumentazioa

- [Backend README](hr-portal/README.md)
- API Documentation (laster)
- Security Guidelines (laster)

### Android-aren Dokumentazioa

- [Android README](android-app/README.md)
- Architecture Guide (laster)

## 🔒 Segurtasuna

Sistemak segurtasun geruza anitzak implementatzen ditu:

- ✅ JWT autentikazioa
- ✅ MFA (TOTP) derrigorrezkoa
- ✅ Passkey/WebAuthn support
- ✅ Rate limiting
- ✅ CSRF babesa
- ✅ XSS babesa
- ✅ Security headers (CSP, X-Frame-Options, etc.)
- ✅ Password hashing (bcrypt)
- ✅ Prepared statements (SQL injection prebentzioa)
- ✅ Ekintzen auditorio osoa

## 👥 Erabiltzaile Rolak

| Rola | Deskribapena | Baimenak |
|------|--------------|----------|
| **ADMIN** | Sistemaren administratzailea | Sarbide osoa |
| **RRHH MGR** | GB arduraduna | Langileen kudeaketa, onarpenak |
| **JEFE SECCIÓN** | Sail burua | Bere taldearen kudeaketa |
| **EMPLEADO** | Erabiltzaile estandarra | Bere datuetara sarbidea |

Ikusi [baimen matrize osoa](MIGRATION_PLAN.md#23-matriz-de-permisos)

## 🧪 Testak

### Backend

```bash
cd hr-portal

# Exekutatu testak
composer test

# Estatistikarekin
composer test -- --coverage-html coverage/

# Test espezifikoak
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

## 📊 Proiektuaren Egoera

### Uneko Fasea: 1. Fasea - Oinarria (1-4 asteak)

✅ Osatua:
- PHP proiektuaren egitura
- Android proiektuaren egitura
- Docker konfigurazioa
- PostgreSQL datu-base eskema
- Routing sistema oinarrizkoa
- Segurtasun middleware-a

⏳ Garapenean:
- Oinarrizko autentikazioaren implementazioa
- API REST endpoints
- Web saioa hasteko interfazea
- Android saioa hasteko pantailak

📅 Hurrengo faseak:
- 2. Fasea: Autentikazio aurreratua (MFA + Passkey)
- 3. Fasea: Langileen kudeaketa
- 4. Fasea: Opor sistema
- [Ikusi plan osoa](MIGRATION_PLAN.md#-plan-de-implementaci%C3%B3n-por-fases)

## 🛠️ Komando Erabilgarriak

### Backend

```bash
# Makefile-ekin
make up          # Abiarazi zerbitzuak
make down        # Gelditu zerbitzuak
make logs        # Ikusi log-ak
make migrate     # Exekutatu migrazioak
make test        # Exekutatu testak
make lint        # Linter
make shell-php   # PHP kontenedorearen shell-a
make shell-db    # PostgreSQL-aren shell-a

# Makefile gabe
docker-compose -f docker-compose.hrportal.yml up -d
docker-compose -f docker-compose.hrportal.yml logs -f
```

### Android

```bash
./gradlew assembleDebug    # Build debug
./gradlew assembleRelease  # Build release
./gradlew test             # Testak
./gradlew lint             # Linter
```

## 📞 Laguntza

Laguntza teknikorako, jarri harremanetan:

- **IT Zabala Gailetak**: it@zabalagailetak.com
- **Project Manager**: [nombre]@zabalagailetak.com

## 📝 Lizentzia

Jabekoa - Zabala Gailetak  
Barne erabilera esklusiboa

## 📈 Changelog

### [1.0.0] - 2026-01-14

#### Gehituta
- PHP proiektuaren hasierako egitura
- Android proiektuaren hasierako egitura
- PostgreSQL datu-base sistema
- Docker konfigurazio osoa
- Routing eta middleware sistema
- Proiektuaren dokumentazioa

#### Ezabatuta
- Node.js/Express sistema zaharra
- React frontend zaharra
- React Native aplikazio mugikor zaharra
- MongoDB eta lotutako konfigurazioa

---

**Bertsioa**: 1.0.0  
**Azken eguneraketa**: 2026ko urtarrilaren 14a  
**Mantendua**: Zabala Gailetak IT Taldeak
