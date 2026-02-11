# Zabala Gailetak HR Ataria - Hasiera Azkarreko Gida

**Bertsioa:** 1.0
**Data:** 2026ko urtarrilaren 23a

## ⚡ Konfigurazioa 5 minututan

### Aurrebaldintzak

Ziurtatu ondokoak instalatuta dituzula:
- ✅ **Docker Desktop** (Windows/Mac) edo Docker Engine (Linux)
- ✅ **Git** biltegia klonatzeko

### 1. Klonatu eta Konfiguratu

```bash
# Klonatu biltegia
git clone <repository-url> zabala-gailetak-hr
cd zabala-gailetak-hr/"Zabala Gailetak"

# Konfiguratu backend ingurunea
cd hr-portal
cp .env.example .env

# Editatu ingurune-aldagai garrantzitsuak (hautazkoa garapenerako)
# DB_PASSWORD=your_secure_db_password
# JWT_SECRET=your_256_bit_jwt_secret
# MFA_SECRET=your_secure_mfa_secret
```

### 2. Abiarazi Zerbitzuak

```bash
# Itzuli proiektuaren errorea
cd ..

# Abiarazi zerbitzu guztiak Docker Compose-ekin
docker-compose -f docker-compose.hrportal.yml up -d

# Monitoreatu abio-erregistroak
docker-compose -f docker-compose.hrportal.yml logs -f
```

### 3. Exekutatu Datu-basearen Migrazioak

```bash
# Exekutatu datu-basearen konfigurazioa
cd "Zabala Gailetak/hr-portal"
chmod +x scripts/migrate.sh
./scripts/migrate.sh
```

### 4. Atzitu Aplikazioa

- **🌐 Web Ataria**: http://localhost:8080
- **🔍 API Osasun Egiaztapena**: http://localhost:8080/api/health
- **📱 Mugikorrerako App-a**: Eraiki eta exekutatu Android aplikazioa

**Administratzaile Kontu Lehenetsia:**
- **E-posta**: `admin@zabalagailetak.com`
- **Pasahitza**: `Admin123!`
- **Rola**: Sistemaren Administratzailea

### 5. Hurrengo Urratsak

1. **Aldatu Pasahitz Lehenetsia**: Eguneratu admin kredentzialak berehala
2. **Sortu Erabiltzaile Probak**: Gehitu langile adibideak probetarako
3. **Arakatu API-a**: Probatu endpoint-ak http://localhost:8080/api/employees
4. **Berrikusi Dokumentazioa**: Ikusi [PROJECT_DOCUMENTATION.md](PROJECT_DOCUMENTATION.md)

---

## 🏗️ Arkitekturaren Ikuspegi Orokorra

### Sistemaren Osagaiak

```
Internet → Nginx (DMZ) → PHP App → PostgreSQL
                    ↓
             Redis (Cache) ← SIEM (ELK)
                    ↓
            OT Sarea (Isolatua)
```

### Teknologia Nagusiak

- **Backend**: PHP 8.4 PSR estandarrekin
- **Datu-basea**: PostgreSQL 16 enkriptatzearekin
- **Cache**: Redis 7 saioetarako
- **Web Zerbitzaria**: Nginx SSL/TLS-ekin
- **Segurtasuna**: MFA, JWT, RBAC, SIEM
- **Mugikorra**: Kotlin Android app-a

### Sare Zonak

- **DMZ (192.168.100.0/24)**: Web sarbide publikoa
- **Erabiltzaile Sarea (192.168.10.0/24)**: Langileen lanpostuak
- **Zerbitzari Sarea (192.168.20.0/24)**: Aplikazioen zerbitzariak
- **Kudeaketa (192.168.200.0/24)**: Admin sarbidea, monitoreoa
- **OT Sarea (192.168.50.0/24)**: Sistemak industrialeak (isolatua)

---

## 🚀 Garapen Konfigurazioa

### Backend Garapena

```bash
# Instalatu PHP dependentziak
cd "Zabala Gailetak/hr-portal"
composer install

# Abiarazi garapen zerbitzaria
php -S localhost:8000 -t public/

# Exekutatu probak
./vendor/bin/phpunit

# Kodearen kalitatearen egiaztapenak
./vendor/bin/phpcs --standard=PSR12 src/
./vendor/bin/phpstan analyse src/
```

### Frontend Garapena

```bash
# Instalatu web dependentziak
cd "Zabala Gailetak/hr-portal/web"
npm install

# Abiarazi garapen zerbitzaria
npm run dev

# Eraiki produkziorako
npm run build

# Exekutatu linting
npm run lint
```

### Mugikorrerako Garapena

```bash
# Ireki Android proiektua
cd "Zabala Gailetak/android-app"

# Android Studio erabiliz:
# 1. File → Open → Hautatu android-app karpeta
# 2. Itxaron Gradle sinkronizatzeko
# 3. Run → Run 'app' (Shift+F10)

# Eraiki APK-a
./gradlew assembleDebug
```

### Garapen Ingurune Osoa

```bash
# Abiarazi zerbitzu guztiak
docker-compose -f docker-compose.hrportal.yml up -d

# Exekutatu garapen zerbitzariak
# Backend: localhost:8080 (Docker bidez)
# Frontend: localhost:3000 (npm run dev)
# Mugikorra: Android Studio emulatzailea
```

---

## 🔧 Konfigurazioa

### Ingurune-Aldagaiak

**Beharrezkoak Produkziorako:**
```env
# Aplikazioa
APP_ENV=production
APP_DEBUG=false

# Datu-basea
DB_HOST=192.168.20.20
DB_PORT=5432
DB_NAME=hr_portal
DB_USER=hr_user
DB_PASSWORD=secure_password_here
DB_SSL_MODE=require

# Segurtasuna
JWT_SECRET=your_256_bit_secret_key_here
JWT_EXPIRES_IN=1h
MFA_ISSUER=Zabala Gailetak
MFA_SECRET=secure_mfa_secret_here

# Redis
REDIS_HOST=192.168.20.30
REDIS_PORT=6379
REDIS_PASSWORD=secure_redis_password
REDIS_SSL=true

# E-posta (jakinarazpenetarako)
SMTP_HOST=your_smtp_server
SMTP_PORT=587
SMTP_USER=your_email@domain.com
SMTP_PASS=your_email_password
SMTP_ENCRYPTION=tls

# Fitxategien Biltegia
UPLOAD_PATH=/var/www/uploads
MAX_FILE_SIZE=10485760  # 10MB
ALLOWED_EXTENSIONS=pdf,doc,docx,jpg,jpeg,png

# Segurtasun Goiburuak
CSP_DEFAULT_SRC=self
HSTS_MAX_AGE=31536000
```

### SSL/TLS Konfigurazioa

```bash
# Sortu autofirmatutako ziurtagiria (garapena)
openssl req -x509 -newkey rsa:4096 -keyout key.pem -out cert.pem -days 365 -nodes -subj "/C=ES/ST=Basque Country/L=Donostia/O=Zabala Gailetak/CN=localhost"

# Produkziorako - Let's Encrypt
certbot certonly --standalone -d hr.zabalagailetak.com

# Konfiguratu Nginx
server {
    listen 443 ssl http2;
    server_name hr.zabalagailetak.com;

    ssl_certificate /etc/letsencrypt/live/hr.zabalagailetak.com/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/hr.zabalagailetak.com/privkey.pem;
    ssl_protocols TLSv1.2 TLSv1.3;
    ssl_ciphers HIGH:!aNULL:!MD5;

    location / {
        proxy_pass http://localhost:8080;
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto $scheme;
    }
}
```

---

## 🧪 Probak

### Unitate Probak

```bash
# PHP unitate probak
cd "Zabala Gailetak/hr-portal"
./vendor/bin/phpunit --verbose

# Esteparekin
./vendor/bin/phpunit --coverage-html coverage/

# Fitxategi espezifikoak
./vendor/bin/phpunit tests/Controllers/AuthControllerTest.php
```

### Integrazio Probak

```bash
# API integrazio probak
./vendor/bin/phpunit tests/Integration/

# Datu-basearen integrazioa
./vendor/bin/phpunit tests/Database/
```

### Hasiera Amaierako Probak (E2E)

```bash
# Instalatu Playwright
npm install -g @playwright/test

# Instalatu nabigatzaileak
npx playwright install

# Exekutatu E2E probak
npx playwright test

# Nabigatzailea ikusgai
npx playwright test --headed

# Proba espezifikoa
npx playwright test tests/e2e/auth.spec.js
```

### Segurtasun Probak

```bash
# OWASP ZAP eskaneatzea
docker run -t owasp/zap2docker-stable zap-baseline.py \
  -t http://localhost:8080 \
  -r zap_report.html

# Dependentzia egiaztapena
./vendor/bin/composer audit

# Edukiontziaren segurtasuna
docker run --rm -v $(pwd):/src aquasecurity/trivy fs .
```

---

## 🔒 Segurtasun Ezaugarriak

### Autentifikazioa eta Baimena

**Autentifikazio Faktore Anitza (MFA):**
- TOTP Google Authenticator/Authy bidez
- WebAuthn (Passkeys) euskarria
- Berreskurapen kodeak kontua berreskuratzeko

**Rol Oinarritutako Sarbide Kontrola (RBAC):**
- **ADMIN**: Sistemaren sarbide osoa
- **RRHH MGR**: HR kudeaketa funtzioak
- **JEFE SECCIÓN**: Sailaren kudeaketa
- **EMPLEADO**: Sarbide pertsonala soilik

### Segurtasun Kontrolak

**Sarrera Baliozkotzea:**
- Zerbitzari aldeko baliozkotze osoa
- SQL injekzioaren prebentzioa (prestatutako esaldiak)
- XSS babesa (irteeraren kodeketa)
- CSRF babesa (cookie bikoitzeko bidalketa)

**Enkriptatzea:**
- AES-256-GCM atsedenean
- TLS 1.3 transmisioan
- Pasahitzen hash-ak (bcrypt, kostu faktorea 12+)

**Monitoreoa:**
- SIEM integrazioa (ELK Stack)
- Alerta erreala
- Auditoria erregistro osoa
- Honeypot mehatxu detekzioa

---

## 📊 Monitoreoa eta Erregistroak

### Aplikazioaren Erregistroak

```bash
# Ikusi aplikazioaren erregistroak
docker-compose -f docker-compose.hrportal.yml logs -f hr-portal

# PHP errore erregistroak
docker exec -it hr-portal tail -f /var/log/php/error.log

# Nginx sarbide erregistroak
docker exec -it nginx tail -f /var/log/nginx/access.log
```

### SIEM Panela

```bash
# Atzitu Kibana
open http://localhost:5601

# Kredentzial lehenetsiak
# Username: elastic
# Password: changeme (konfiguratu ingurunean)
```

### Osasun Egiaztapenak

```bash
# Aplikazioaren osasuna
curl http://localhost:8080/api/health

# Datu-basearen konektibitatea
docker exec -it postgres pg_isready -U hr_user -d hr_portal

# Redis konektibitatea
docker exec -it redis redis-cli ping
```

---

## 🚨 Arazketa

### Arazo Ohikoenak

#### 8080 Portua Jadanik Erabiltzen

```bash
# Aldatu portua docker-compose.hrportal.yml fitxategian
ports:
  - "8081:80"  # Aldatu 8080:80-tik

# Berrabiarazi zerbitzuak
docker-compose -f docker-compose.hrportal.yml down
docker-compose -f docker-compose.hrportal.yml up -d
```

#### Datu-basearen Konexioak Huts Egin Du

```bash
# Egiaztatu PostgreSQL erregistroak
docker-compose -f docker-compose.hrportal.yml logs postgres

# Egiaztatu ingurune-aldagaiak
cat hr-portal/.env | grep DB_

# Probatu datu-basearen konexioa
docker exec -it postgres psql -U hr_user -d hr_portal
```

#### Baimen Erroreak

```bash
# Konpondu script baimenak
chmod +x hr-portal/scripts/*.sh

# Konpondu fitxategi baimenak
sudo chown -R $USER:$USER "Zabala Gailetak/"
```

#### SSL Ziurtagiri Arazoak

```bash
# Egiaztatu ziurtagiriaren baliozkotasuna
openssl x509 -in cert.pem -text -noout

# Berrasortu autofirmatutako ziurtagiria
openssl req -x509 -newkey rsa:4096 -keyout key.pem -out cert.pem -days 365 -nodes
```

#### Memoria Arazoak

```bash
# Handitu Docker memoria muga
# Docker Desktop: Settings → Resources → Memory (4GB+ gomendatua)

# Egiaztatu edukiontziaren memoria erabilera
docker stats
```

### Errendimendu Arazoak

#### Aplikazio Eraztun Motela

```bash
# Egiaztatu PHP-FPM prozesuak
docker exec -it hr-portal ps aux | grep php

# Monitoreatu datu-basearen kontsultak
docker exec -it postgres psql -U hr_user -d hr_portal -c "SELECT * FROM pg_stat_activity;"

# Egiaztatu Redis errendimendua
docker exec -it redis redis-cli info stats
```

#### CPU Erabilera Handia

```bash
# Egiaztatu sistemaren baliabideak
docker stats

# Monitoreatu aplikazioaren erregistroak erroreetarako
docker-compose -f docker-compose.hrportal.yml logs --tail=100 hr-portal
```

---

## 📞 Laguntza eta Baliabideak

### Dokumentazioa

- **📋 Proiektuaren Dokumentazioa**: [PROJECT_DOCUMENTATION.md](PROJECT_DOCUMENTATION.md)
- **🔐 Segurtasun Gida**: [AGENTS.md](AGENTS.md) (betetze xehetasunekin)
- **🚀 Inplementazio Laburpena**: [IMPLEMENTATION_SUMMARY.md](IMPLEMENTATION_SUMMARY.md)
- **📱 Web App Gida**: [WEB_APP_GUIDE.md](WEB_APP_GUIDE.md)
- **📲 Mugikorrerako App Gida**: [MOBILE_APP_GUIDE.md](MOBILE_APP_GUIDE.md)
- **🔗 API Dokumentazioa**: [API_DOCUMENTATION.md](API_DOCUMENTATION.md)

### Larrialdi Kontaktuak

- **🚨 Segurtasun Gertakariak**: security@zabalagailetak.com | +34 XXX XXX XXX
- **🆘 Laguntza Teknikoa**: support@zabalagailetak.com | +34 XXX XXX XXX
- **📧 Kontsulta Orokorrak**: info@zabalagailetak.com

### Komunitate Baliabideak

- **OWASP**: https://owasp.org
- **PHP Dokumentazioa**: https://www.php.net/docs
- **PostgreSQL Eskuliburua**: https://www.postgresql.org/docs/
- **Docker Dokumentazioa**: https://docs.docker.com
- **Android Garatzaileak**: https://developer.android.com

---

## 🎯 Komando Azkarren Erreferentzia

```bash
# Abiarazi/Gelditu zerbitzuak
docker-compose -f docker-compose.hrportal.yml up -d    # Abiarazi
docker-compose -f docker-compose.hrportal.yml down     # Gelditu
docker-compose -f docker-compose.hrportal.yml logs -f  # Erregistroak

# Datu-base eragiketak
cd "Zabala Gailetak/hr-portal"
./scripts/migrate.sh  # Exekutatu migrazioak

# Garapena
cd "Zabala Gailetak/hr-portal/web"
npm run dev          # Abiarazi frontend garapen zerbitzaria

cd "Zabala Gailetak/android-app"
./gradlew assembleDebug  # Eraiki Android APK-a

# Probak
./vendor/bin/phpunit                    # PHP probak
npm test                              # Frontend probak
./gradlew test                        # Android probak

# Kodearen kalitatea
./vendor/bin/phpcs src/               # PHP linting
npm run lint                          # JS linting
./gradlew lint                        # Android linting
```

---

## 🔄 Eguneraketak eta Mantentzea

### Mantentze Zeregin Ohikoenak

**Egunero:**
- Monitoreatu sistemaren osasuna eta erregistroak
- Berrikusi segurtasun alertak
- Egiaztatu segurtasun kopiaren osatzea

**Astero:**
- Eguneratu dependentziak (segurtasun adabakiak)
- Berrikusi sistemaren errendimendua
- Egiaztatu disko espazioa eta baliabideak

**Hilero:**
- Segurtasun kopiaren proba osoa
- Segurtasun adabakien instalazioa
- Errendimendu optimizazioa

**Hilabetero:**
- Segurtasun ebaluazioak
- Betetze ikuskaketak
- Sistemaren eguneraketak

### Segurtasun Kopien Egiaztapena

```bash
# Probatu datu-base segurtasun kopiaren berrespena
pg_restore --create --clean -d postgres /backups/hr_portal_backup.sql

# Egiaztatu fitxategien osotasuna
find /backups -name "*.tar.gz" -exec tar -tzf {} \; | head -10
```

---

**Prest Zabala Gailetak HR Atarian garatzen hasteko! 🚀**

Dokumentazio tekniko xeheago lortzeko, ikusi [PROJECT_DOCUMENTATION.md](PROJECT_DOCUMENTATION.md).

*Azken eguneraketa: 2026ko urtarrilaren 23a*
