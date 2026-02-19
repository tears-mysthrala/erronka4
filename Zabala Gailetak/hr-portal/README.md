# Zabala Gailetak - HR Portal 🏢

Giza baliabideen kudeaketarako barne sistema segurtasun aurreratua inplementatuta.

## 🎯 Proiektuaren Egoera

**Uneko Fasea**: ✅ 3. Fasea Osatuta - Employee CRUD Full Stack
**Azken Eguneratzea**: 2026ko urtarrilaren 15a

### Osaturiko Faseak

- ✅ **1. Fasea**: Oinarrizko Egitura eta Migrazioak
- ✅ **2. Fasea**: Autentifikazio Aurreratua (JWT + MFA + RBAC)
- ✅ **3. Fasea**: Employee CRUD Full Stack
  - ✅ PHP API Backend osoa
  - ✅ Datu espainiarra baliozkotzea aurreratua (NIF/NIE, IBAN, telefonoa, PK)
  - ✅ Sarrera automatikoki garbitzea
  - ✅ Audit Trail / Aldaketa historiala
  - ✅ 82/82 proba unitario gaindituak
  - ✅ 8 API endpoint funtzional
  - ✅ React web interfazea styled-components-ekin (~1.400 lerro)
  - ✅ React Native mugikorreko app Expo-rekin (~1.300 lerro)

---

## 🚀 Hasiera Azkarra

### Aurrebaldintza

- Docker & Docker Compose
- Arch Linux (edo bateragarria)
- Ataka erabilgarriak: 8080 (HTTP), 8443 (HTTPS), 5432 (PostgreSQL), 6379 (Redis)

### Hasiera Azkarra

```bash
# 1. Klonatu repositorioa
cd "Zabala Gailetak"

# 2. Konfiguratu ingurune aldagaiak
cd hr-portal
cp .env.example .env
# Editatu .env zure sekretuekin

# 3. Abiarazi zerbitzuak
cd ..
docker-compose -f docker-compose.hrportal.yml up -d

# 4. Instalatu PHP dependentziak
docker-compose -f docker-compose.hrportal.yml exec php composer install

# 5. Exekutatu migrazioak
docker-compose -f docker-compose.hrportal.yml exec postgres psql -U hr_user -d hr_portal -f /docker-entrypoint-initdb.d/001_init_schema.sql

# 6. Abiarazi web interfazea (aukerakoa)
cd hr-portal/web
npm install
npm run dev
# Web eskuragarri hemen: http://localhost:3001

# 7. Egiaztatu instalazioa
curl http://localhost:8080/api/health
```

---

## 📋 Arkitektura

### Teknologia Stack-a

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

### Docker Zerbitzuak

| Zerbitzua | Ataka | Egoera | Deskribapena |
|----------|--------|--------|-------------|
| nginx | 8080, 8443 | ✅ Running | Reverse proxy eta SSL |
| php | 9000 | ✅ Running | PHP-FPM 8.4 |
| postgres | 5432 | ✅ Healthy | Datu-base nagusia |
| redis | 6379 | ✅ Healthy | Cache eta saioak |

---

## 🔐 Autentifikazioa eta Segurtasuna

### Inplementaturiko Ezaugarriak

- ✅ **JWT Tokens**: Access token-ak (1h) eta refresh token-ak (7e)
- ✅ **MFA/TOTP**: Bi faktoreko autentifikazioa QR kodeekin
- ✅ **RBAC**: Roletan oinarritutako sarbide kontrola (4 rol, 43 baimen)
- ✅ **Session Management**: Redis-en saio iraunkorrak
- ✅ **Rate Limiting**: Indar brutoaren aurkako babesa
- ✅ **Account Locking**: Blokeo huts egindako saiakeren ondoren
- ✅ **Backup Codes**: MFA-rako babes kodeak

### Rolak eta Baimenak

| Rola | Baimenak | Deskribapena |
|-----|----------|-------------|
| **admin** | 43 (guztiak) | Sistemara sarbide osoa |
| **hr_manager** | 31 | GIZA baliabideen kudeaketa |
| **department_head** | 15 | Departamentuaren kudeaketa |
| **employee** | 7 | Oinarrizko sarbidea |

---

## 🔌 API Endpoints

Ikusi dokumentazio osoa hemen:
- [FASE_2_COMPLETADA.md](./FASE_2_COMPLETADA.md) - Autentifikazioa
- [FASE_3_EMPLOYEE_CRUD.md](./FASE_3_EMPLOYEE_CRUD.md) - Employee CRUD

### Publikoak
- `GET /api/health` - Health check
- `POST /api/auth/login` - Login
- `POST /api/auth/refresh` - Berritu token-a

### Babestuak - Auth
- `GET /api/auth/me` - Uneko erabiltzailea
- `POST /api/auth/logout` - Itxi saioa
- `POST /api/auth/mfa/setup` - Konfiguratu MFA
- `POST /api/auth/mfa/enable` - Aktibatu MFA
- `POST /api/auth/mfa/verify` - Egiaztatu MFA
- `POST /api/auth/mfa/disable` - Desaktibatu MFA

### Babestuak - Employees (3. Fasea ✨)
- `GET /api/employees` - Zerrendatu langileak (paginazioarekin)
- `GET /api/employees/{id}` - Langilearen xehetasuna
- `POST /api/employees` - Sortu langiilea
- `PUT /api/employees/{id}` - Eguneratu langiilea
- `DELETE /api/employees/{id}` - Baja logikoa (soft delete)
- `POST /api/employees/{id}/restore` - Berraktibatu langiilea
- `GET /api/employees/{id}/history` - Aldaketa historiala (Audit Trail)
- `GET /api/audit/user/{userId}` - Erabiltzailearen jarduera

---

## 🧪 Probak

```bash
# Proba unitarioak
docker-compose -f docker-compose.hrportal.yml exec php ./vendor/bin/phpunit --testdox

# Egoera: ✅ 82/82 proba gaindituak
# - TokenManager: 11/11 proba (2. Fasea)
# - EmployeeController: 11/11 proba (3. Fasea)
# - EmployeeValidator: 40/40 proba (3. Fasea)
# - AuditLogger: 11/11 proba (3. Fasea - Audit Trail)
# - AuditController: 9/9 proba (3. Fasea - Audit Trail)
```

### Proben Estaldura

**Autentifikazioa** (11 proba):
- JWT token sortze/balidazioa
- MFA/TOTP konfigurazioa
- Saioen kudeaketa

**Employee CRUD** (11 proba):
- CRUD eragiketak RBAC-ekin
- Paginazioa eta filtroak
- Soft delete eta berrezartzea

**Datuen Balidazioa** (40 proba):
- NIF/NIE espainiarra letra zuzena
- IBAN checksum baliodunarekin
- Telefono espainiarra (+34)
- Posta kode espainiarra (00000-52999)
- Email RFC5322
- Pasahitz sendoa
- XSS garbitzea

**Audit Trail** (20 proba):
- AuditLogger (11 proba): Create, Update, Delete, Restore logging
- AuditController (9 proba): Entitateen historiala, erabiltzaileen jarduera

---

## 👥 Proba Erabiltzailea

```
Email: admin@zabalagailetak.com
Password: password
Rola: admin
```

---

## 📚 Dokumentazioa

### Backend
- [FASE_2_COMPLETADA.md](./FASE_2_COMPLETADA.md) - Autentifikazioa JWT + MFA + RBAC
- [FASE_3_EMPLOYEE_CRUD.md](./FASE_3_EMPLOYEE_CRUD.md) - Employee CRUD Backend + Probak

### Frontend
- [FASE_3_WEB_INTERFACE.md](./FASE_3_WEB_INTERFACE.md) - React Web Interfazea (~1.400 lerro)
- [FASE_3_MOBILE.md](./FASE_3_MOBILE.md) - React Native Mugikorreko App (~1.300 lerro)
- [web/README.md](./web/README.md) - React app konfigurazioa eta erabilera
- [mobile/README.md](./mobile/README.md) - Mugikorreko app konfigurazioa eta erabilera

### Orokorra
- [API_DOCUMENTATION.md](../API_DOCUMENTATION.md) - API osoa
- [MIGRATION_PLAN.md](../MIGRATION_PLAN.md) - Migrazio plana
- [CORRECCIONES_TIPOS_SEGURIDAD.md](./CORRECCIONES_TIPOS_SEGURIDAD.md) - ✨ Tipo eta segurtasun zuzenketak

---

## 📱 Bezero Eskuragarriak

### 1. Web App (React)

```bash
cd web
npm install
npm start  # Ireki http://localhost:3001
```

**Ezaugarriak**:
- ✅ React 18.2 + React Router v6
- ✅ Styled-components estiloetarako
- ✅ 4 orri: Login, Zerrenda, Xehetasuna, Formularioa
- ✅ Paginazioa (10 elementu/orri)
- ✅ Audit Trail denboraren timeline bisuala
- ✅ Interfazea euskeraz

Ikusi [FASE_3_WEB_INTERFACE.md](./FASE_3_WEB_INTERFACE.md) xehetasunetarako.

### 2. Mobile App (React Native)

```bash
cd mobile
npm install
npm start  # Ireki Expo DevTools
```

**Ezaugarriak**:
- ✅ Expo 50.0.0 + React Native 0.73.2
- ✅ React Navigation 6.1.9 (Stack Navigator)
- ✅ 4 pantaila: Login, Zerrenda, Xehetasuna, Formularioa
- ✅ Pull-to-refresh
- ✅ AsyncStorage token-etarako
- ✅ iOS eta Android prest
- ✅ Interfazea euskeraz

Ikusi [FASE_3_MOBILE.md](./FASE_3_MOBILE.md) xehetasunetarako.

---

**Bertsioa**: 1.0.0-alpha
**Egoera**: Garapen aktiboan
**Lizentzia**: Proprietary - Zabala Gailetak
