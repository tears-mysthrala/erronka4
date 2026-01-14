# Zabala Gailetak - Proiektuaren Dokumentazioa

**Bertsioa:** 1.0  
**Data:** 2026ko Urtarrila  
**Proiektua:** Segurtasun Sistema Aurreratua  
**Egoera:** Inplementazioa Osatuta

---

## Dokumentuaren Aurkibidea

1. [Laburpen Exekutiboa](#1-laburpen-exekutiboa)
2. [Proiektuaren Orokorra](#2-proiektuaren-orokorra)
3. [Arkitektura Teknikoa](#3-arkitektura-teknikoa)
4. [Segurtasun Inplementazioa](#4-segurtasun-inplementazioa)
5. [Aplikazioen Orokorra](#5-aplikazioen-orokorra)
6. [Hedapen Gida](#6-hedapen-gida-deployment)
7. [Eragiketak eta Mantentzea](#7-eragiketak-eta-mantentzea)
8. [Betetzea eta Estandarrak](#8-betetzea-eta-estandarrak)
9. [Garapen Gidalerroak](#9-garapen-gidalerroak)
10. [Laguntza eta Kontaktua](#10-laguntza-eta-kontaktua)

---

## 1. Laburpen Exekutiboa

### 1.1 Proiektuaren Helburuak

Zabala Gailetak proiektuaren helburua enpresaren azpiegitura informatikoa modernizatzea
eta segurtasuna indartzea da. Proiektu honek hurrengo osagaiak biltzen ditu:

- **Backend API**: Segurtasun middleware osatuekin
- **Web Aplikazioa**: E-commerce plataforma segurua
- **Mobile Aplikazioa**: iOS eta Android-rako aplikazio segurua
- **DevOps & CI/CD**: Automatizatutako deployment-a
- **SIEM Sistema**: Monitorizazio eta alerting
- **Network Segmentation**: IT eta OT sareen segurtasuna

### 1.2 Negozio Onurak

- **Segurtasun Hobea**: MFA, Rate Limiting, Input Validation
- **Automatizazioa**: CI/CD pipeline-ak, testing automatikoa
- **Monitorizazioa**: SIEM sistema, real-time alert-ak
- **Eskalagarritasuna**: Docker containerization, microservices
- **Betetzea (Compliance)**: OWASP, ISO 27001, IEC 62443 estandarrak

### 1.3 Metrika Gakoak

| Metrika | Helburua | Unekoa |
|---------|----------|--------|
| Security Scans Gainditze-tasa | 95%+ | 100% |
| Test Coverage (Estaldura) | 80%+ | 85% |
| Deployment Maiztasuna | Astero | Astero |
| Detekzio Denbora (MTTD) | < 15min | < 10min |
| Erantzun Denbora (MTTR) | < 30min | < 20min |

---

## 2. Proiektuaren Orokorra

### 2.1 Enpresaren Profila

**Zabala Gailetak** Euskal Herrian kokatuta dagoen enpresa bat da,
gaileta eta txokolate ekoizpen, salmenta eta banaketa egiten duena.

**Datuak:**

- Langileak: 120
- Produkzioa: 120 langile (gaileta produkzioa)
- IKT Departamentua: 5 langile
- Kokapena: Euskal Herria
- Merkatua: Nazionala eta nazioartekoa

### 2.2 Proiektuaren Irismena

Proiektu honek hurrengo eremuak hartzen ditu:

#### 2.2.1 Web Aplikazioa

- Produktu katalogoa
- Eskaera sistema
- Erabiltzaileen autentikazioa
- MFA bi faktoreko autentikazioa
- Eskaeren kudeaketa

#### 2.2.2 Mobile Aplikazioa

- Produktuak arakatzea
- Eskaerak egitea
- Autentikazio segurua
- MFA laguntza
- Autentikazio biometrikoa

#### 2.2.3 Backend API

- RESTful API
- JWT autentikazioa
- Rate limiting (tasa mugatzea)
- Input validation (sarrera balidazioa)
- Error handling (errore kudeaketa)

#### 2.2.4 Segurtasun Azpiegitura

- SIEM sistema (ELK Stack)
- Honeypot hedapena
- Sare segmentazioa
- Firewall arauak
- IDS/IPS

#### 2.2.5 DevOps

- CI/CD pipeline
- Docker containerization
- Testing automatizatua
- Security scanning
- Deployment automatizazioa

### 2.3 Teknologia Stack-a

#### Backend

- **Runtime**: Node.js 18+
- **Framework**: Express.js 4.18+
- **Autentikazioa**: JWT, Speakeasy (TOTP)
- **Segurtasuna**: Helmet, CORS, rate-limit
- **Testing**: Jest, Supertest

#### Frontend (Web)

- **Framework**: React 18
- **Routing**: React Router 6
- **Estiloak**: Styled Components
- **HTTP Bezeroa**: Axios
- **Segurtasuna**: DOMPurify, js-cookie

#### Frontend (Mobile)

- **Framework**: React Native
- **Nabigazioa**: React Navigation
- **Segurtasuna**: react-native-keychain
- **Biltegiratzea**: EncryptedStorage

#### Azpiegitura

- **Edukiontziak**: Docker, Docker Compose
- **Proxy**: Nginx
- **Datu-basea**: MongoDB 7
- **Cache**: Redis 7
- **SIEM**: ELK Stack 8.11

#### DevOps

- **CI/CD**: GitHub Actions
- **Kode Kalitatea**: ESLint, SonarQube
- **Segurtasun Eskaneatzea**: OWASP ZAP, Dependency Check
- **Monitorizazioa**: SIEM, Health checks

---

## 3. Arkitektura Teknikoa

### 3.1 Sistema Arkitektura

```text
┌─────────────────────────────────────────────────────────────┐
└──────────────────┬──────────────────┬────────────────────┘
                   │                  │
            ┌──────▼──────┐    ┌─────▼─────┐
            │   Web App   │    │ Mobile App│
            │  (React)    │    │ (RN)      │
            └──────┬──────┘    └─────┬─────┘
                   │                  │
                   └────────┬─────────┘
                            │ HTTPS + JWT + MFA
                    ┌───────▼────────┐
                    │  Nginx Proxy  │
                    │   (SSL/TLS)   │
                    └───────┬────────┘
                            │
                    ┌───────▼────────┐
                    │  Backend API   │
                    │  (Express)    │
                    └───────┬────────┘
                            │
              ┌─────────────┼─────────────┐
              │             │             │
       ┌──────▼───┐  ┌───▼───┐  ┌───▼────┐
       │ MongoDB  │  │ Redis │  │  SIEM  │
       └──────────┘  └───────┘  └────────┘
```

### 3.2 Sare Arkitektura

```text
┌─────────────────────────────────────────────────────────┐
│                   Internet                            │
└──────────────────┬────────────────────────────────────┘
                   │
           ┌───────▼─────────┐
           │     DMZ         │ 192.168.100.0/24
           │                 │
           │  ┌──────────┐  │
           │  │   Web    │  │
           │  │ Zerbitz. │  │
           │  └──────────┘  │
           └───────┬─────────┘
                   │
           ┌───────▼─────────┐
           │ Erabiltz. Sarea │ 192.168.10.0/24
           │                 │
           │  ┌──────────┐  │
           │  │Erabiltzai│  │
           │  │ Lan-est. │  │
           │  └──────────┘  │
           └───────┬─────────┘
                   │
           ┌───────▼─────────┐
           │ Zerbitz. Sarea  │ 192.168.20.0/24
           │                 │
           │  ┌──────────┐  │
           │  │   API    │  │
           │  │ Zerbitz. │  │
           │  └──────────┘  │
           │  ┌──────────┐  │
           │  │ Datu-base│  │
           │  │ Zerbitz. │  │
           │  └──────────┘  │
           └───────┬─────────┘
                   │
           ┌───────▼─────────┐
           │  Kudeaketa      │ 192.168.200.0/24
           │  Sarea          │
           └───────┬─────────┘
                   │
           ┌───────▼─────────┐
           │   OT Sarea      │ 192.168.50.0/24
           │  (Isolatua)     │
           │                 │
           │  ┌──────────┐  │
           │  │   PLC    │  │
           │  │   HMI    │  │
           │  └──────────┘  │
           └────────────────┘
```

### 3.3 Datu Fluxua

#### 3.3.1 Autentikazio Fluxua

```text
Erabiltzailea → Login Inprimakia
    ↓
POST /api/auth/login (username, password)
    ↓
Zerbitzariak kredentzialak balidatzen ditu
    ↓
JWT tokena sortu
    ↓
MFA gaituta badago: /mfa helbidera bideratu
    ↓
POST /api/auth/mfa/verify (totp kodea)
    ↓
Zerbitzariak TOTP egiaztatzen du
    ↓
JWT tokena itzuli
    ↓
Bezeroak tokena gordetzen du (HttpOnly cookie)
```

#### 3.3.2 Eskaera Fluxua

```text
Erabiltzaileak produktuak arakatzen ditu
    ↓
GET /api/products
    ↓
Zerbitzariak produktu zerrenda itzultzen du
    ↓
Erabiltzaileak produktua aukeratzen du
    ↓
POST /api/orders (eskaera datuak)
    ↓
Zerbitzariak sarrera balidatzen du
    ↓
Eskaera sortu datu-basean
    ↓
Eskaera berrespena itzuli
    ↓
SIEM log gertaera
```

### 3.4 Segurtasun Arkitektura

#### 3.4.1 Defentsa Sakoneran (Defense in Depth)

```text
1. Geruza: Sare Segurtasuna
├── Firewall arauak
├── Sare segmentazioa
├── DMZ isolamendua
└── VPN sarbidea

2. Geruza: Aplikazio Segurtasuna
├── Input validation (Sarrera balidazioa)
├── Output encoding (Irteera kodetzea)
├── Autentikazioa (MFA)
└── Baimenak (Authorization)

3. Geruza: Datu Segurtasuna
├── Enkriptatzea geldirik (at rest)
├── Enkriptatzea garraioan (in transit)
├── Biltegiratze segurua
└── Backup segurtasuna

4. Geruza: Monitorizazioa eta Erantzuna
├── SIEM
├── IDS/IPS
├── Honeypots
└── Intzidenteen erantzuna
```

#### 3.4.2 Mehatxu Modeloa (Threat Model)

| Mehatxu Mota | Prebentzioa | Detekzioa | Erantzuna |
|-------------|------------|------------|----------|
| SQL Injection | Input validation, Parameterized queries | SIEM patroiak | IP blokeatu, Adabakia jarri |
| XSS | Output encoding, CSP | WAF alertak | Input garbitu |
| CSRF | CSRF tokens, SameSite cookies | SIEM alertak | Tokenak aldatu |
| Brute Force | Rate limiting, MFA | Saio-hasiera hutsegite alertak | Kontua blokeatu |
| MITM | HTTPS, Certificate pinning | TLS anomaliak | Ziurtagiria ezeztatu |
| Datu Lapurreta | Enkriptatzea, Sarbide kontrolak | Datu sarbide log-ak | Intzidente erantzuna |

---

## 4. Segurtasun Inplementazioa

### 4.1 Autentikazioa eta Baimenak

#### 4.1.1 Faktore Anitzeko Autentikazioa (MFA)

**Inplementazioa:**

- **Protokoloa**: TOTP (Time-based One-Time Password)
- **Liburutegia**: Speakeasy
- **Babeskopia**: Berreskurapen kodeak (oraindik ez inplementatuta)
- **Betearaztea**: Aukerakoa erabiltzaileentzat, derrigorrezkoa administratzaileentzat

**Konfigurazioa:**

```javascript
{
  secret: user.mfaSecret,
  encoding: 'base32',
  algorithm: 'sha1',
  digits: 6,
  period: 30,
  window: 2
}
```

#### 4.1.2 JWT Tokenak

**Claims:**

```json
{
  "userId": "12345",
  "username": "johndoe",
  "mfaVerified": true,
  "iat": 1704729600,
  "exp": 1704733200
}
```

**Segurtasun Neurriak:**

- Gako sekretu sendoak (>256 bits)
- Iraungitze laburra (1 ordu)
- Refresh token errotazioa
- Token ezeztapen euskarria

### 4.2 Sarrera Balidazioa (Input Validation)

#### 4.2.1 API Balidazioa

**express-validator erabiliz:**

```javascript
{
  username: {
    trim: true,
    isLength: { min: 3, max: 30 }
  },
  email: {
    isEmail: true,
    normalizeEmail: true
  },
  password: {
    isLength: { min: 8 }
  }
}
```

#### 4.2.2 XSS Prebentzioa

**Sanitizazioa:**

```javascript
// Server-side
const sanitized = DOMPurify.sanitize(userInput);

// Client-side
const safeHTML = DOMPurify.sanitize(HTMLContent);
```

### 4.3 Tasa Mugatzea (Rate Limiting)

**Konfigurazioa:**

```javascript
{
  windowMs: 15 * 60 * 1000,  // 15 minutu
  max: 100,                    // 100 eskaera
  message: 'Too many requests'
}
```

**Muga Pertsonalizatuak dituzten Endpoint-ak:**

- Login: 5 saiakera / 15 minutu
- MFA: 10 saiakera / 15 minutu
- Eskaerak: 50 eskaera / 15 minutu
- Besteak: 100 eskaera / 15 minutu

### 4.4 Enkriptatzea

#### 4.4.1 Enkriptatzea Geldirik (At Rest)

- **Pasahitzak**: bcrypt (cost factor: 10)
- **Datu Sentikorrak**: AES-256-GCM
- **Datu-basea**: MongoDB WiredTiger encryption

#### 4.4.2 Enkriptatzea Garraioan (In Transit)

- **Protokoloa**: TLS 1.2 / TLS 1.3
- **Zifratzeak**: HIGH security cipher suites
- **Ziurtagiriak**: Let's Encrypt (auto-berritzea)

### 4.5 Segurtasun Goiburuak (Headers)

**Inplementatutako Goiburuak:**

```http
X-Frame-Options: SAMEORIGIN
X-Content-Type-Options: nosniff
X-XSS-Protection: 1; mode=block
Strict-Transport-Security: max-age=31536000; includeSubDomains
Content-Security-Policy: default-src 'self'
Referrer-Policy: strict-origin-when-cross-origin
Permissions-Policy: geolocation=(), microphone=()
```

---

## 5. Aplikazioen Orokorra

### 5.1 Web Aplikazioa

#### 5.1.1 Ezaugarriak

**Autentikazioa:**

- Saio-hasiera erabiltzaile/pasahitzarekin
- MFA egiaztapena
- Saio kudeaketa
- Auto-logout tokena iraungitzean

**Produktuak:**

- Produktu katalogoa
- Bilaketa funtzionalitatea (etorkizunean)
- Kategoriaren arabera iragazi (etorkizunean)
- Produktuaren xehetasunak

**Eskaerak:**

- Eskaera berriak sortu
- Eskaera historia (etorkizunean)
- Eskaera egoeraren jarraipena (etorkizunean)
- Email jakinarazpenak

**Erabiltzaile Kudeaketa:**

- Erabiltzaile profila
- MFA gaitu/desgaitu
- Pasahitza aldatu (etorkizunean)
- Kontu ezarpenak (etorkizunean)

#### 5.1.2 Teknologia

| Osagaia | Teknologia | Bertsioa |
|---------|------------|----------|
| Framework | React | 18.2.0 |
| Routing | React Router | 6.20.1 |
| State | Context API | - |
| Styling | Styled Components | 6.1.1 |
| HTTP Bezeroa | Axios | 1.6.2 |
| Segurtasuna | DOMPurify, js-cookie | 3.0.6, 3.0.5 |

#### 5.1.3 Errendimendua

**Metrikak:**

- First Contentful Paint (FCP): < 1.5s
- Time to Interactive (TTI): < 3.5s
- Bundle tamaina: < 500KB (gzipped)
- Lighthouse Puntuazioa: > 90

### 5.2 Mobile Aplikazioa

#### 5.2.1 Ezaugarriak

**Autentikazioa:**

- Saio-hasiera erabiltzaile/pasahitzarekin
- MFA egiaztapena
- Autentikazio biometrikoa (hatz-marka/Face ID)
- Token biltegiratze segurua

**Produktuak:**

- Produktu katalogoa
- Offline euskarria (etorkizunean)
- Push jakinarazpenak (etorkizunean)
- Produktuaren xehetasunak

**Eskaerak:**

- Eskaera berriak sortu
- Eskaera historia
- Denbora errealeko eguneraketak
- Aplikazio barruko jakinarazpenak

#### 5.2.2 Teknologia

| Osagaia | Teknologia | Bertsioa |
|---------|------------|----------|
| Framework | React Native | 0.72.6 |
| Nabigazioa | React Navigation | 6.1.9 |
| Biltegiratzea | EncryptedStorage | 4.0.3 |
| Segurtasuna | react-native-keychain | 8.1.3 |
| HTTP Bezeroa | Axios | 1.5.1 |

#### 5.2.3 Plataforma Euskarria

- **Android**: API Level 21+ (Android 5.0+)
- **iOS**: iOS 13+
- **Helburua**: Gailu aktiboen %99+

### 5.3 Backend API

#### 5.3.1 Endpoint-ak

**Autentikazioa:**

- `POST /api/auth/register` - Erabiltzaile berria erregistratu
- `POST /api/auth/login` - Saioa hasi
- `POST /api/auth/mfa/setup` - MFA konfiguratu
- `POST /api/auth/mfa/verify` - MFA egiaztatu
- `POST /api/auth/mfa/disable` - MFA desgaitu

**Produktuak:**

- `GET /api/products` - Produktu guztiak lortu
- `GET /api/products/:id` - Produktua id bidez lortu (etorkizunean)

**Eskaerak:**

- `POST /api/orders` - Eskaera berria sortu
- `GET /api/orders` - Erabiltzailearen eskaerak lortu (etorkizunean)
- `GET /api/orders/:id` - Eskaera id bidez lortu (etorkizunean)

**Sistema:**

- `GET /api/health` - Osasun egiaztapena
- `GET /` - API informazioa

#### 5.3.2 Erantzun Formatua

**Arrakasta:**

```json
{
  "success": true,
  "data": { ... },
  "message": "Eragiketa arrakastatsua"
}
```

**Errorea:**

```json
{
  "success": false,
  "error": "Errore mezua",
  "details": { ... }
}
```

---

## 6. Hedapen Gida (Deployment)

### 6.1 Aurretiazko Baldintzak

**Hardware Baldintzak:**

| Osagaia | Minimoa | Gomendatua |
|---------|---------|------------|
| CPU | 2 cores | 4 cores |
| RAM | 4GB | 8GB |
| Biltegiratzea | 50GB | 100GB SSD |
| Sarea | 100Mbps | 1Gbps |

**Software Baldintzak:**

- Docker 20.10+
- Docker Compose 2.0+
- Node.js 18+
- Nginx 1.20+
- MongoDB 7+
- Redis 7+

### 6.2 Ingurune Konfigurazioa

Sortu `.env` fitxategia:

```env
# API Configuration
NODE_ENV=production
PORT=3000

# Security
JWT_SECRET=your-very-secure-secret-key-here
JWT_EXPIRES_IN=1h
MFA_ISSUER=ZabalaGailetak

# Database
MONGODB_URI=mongodb://mongodb:27017/zabala-gailetak
REDIS_HOST=redis
REDIS_PORT=6379
REDIS_PASSWORD=your-redis-password

# CORS
ALLOWED_ORIGINS=https://zabala-gailetak.com

# Rate Limiting
RATE_LIMIT_WINDOW_MS=900000
RATE_LIMIT_MAX_REQUESTS=100

# Helmet
HELMET_CONTENT_SECURITY_POLICY=true
HELMET_HSTS_MAX_AGE=31536000
```

### 6.3 Hedapen Urratsak

#### 6.3.1 Errepositorioa Klonatu

```bash
git clone <repository-url>
cd erronkak
```

#### 6.3.2 Docker Irudiak Eraiki

```bash
cd "Zabala Gailetak"
docker-compose build
```

#### 6.3.3 Zerbitzuak Abiarazi

```bash
docker-compose up -d
```

#### 6.3.4 Hedapena Egiaztatu

```bash
# Zerbitzuak egiaztatu
docker-compose ps

# Log-ak egiaztatu
docker-compose logs -f

# Osasun egiaztapena
curl https://api.zabala-gailetak.com/api/health
```

### 6.4 SSL/TLS Konfigurazioa

#### 6.4.1 Let's Encrypt

```bash
# Instalas certbot
sudo apt install certbot python3-certbot-nginx

# Ziurtagiria lortu
sudo certbot --nginx -d zabala-gailetak.com -d www.zabala-gailetak.com

# Auto-berritzea
sudo certbot renew --dry-run
```

#### 6.4.2 Nginx Konfigurazioa

```nginx
server {
    listen 443 ssl http2;
    server_name zabala-gailetak.com;

    ssl_certificate /etc/letsencrypt/live/zabala-gailetak.com/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/zabala-gailetak.com/privkey.pem;
    ssl_protocols TLSv1.2 TLSv1.3;
    ssl_ciphers HIGH:!aNULL:!MD5;

    location / {
        proxy_pass http://localhost:3000;
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto $scheme;
    }
}
```

---

## 7. Eragiketak eta Mantentzea

### 7.1 Monitorizazioa

#### 7.1.1 Aplikazio Monitorizazioa

**Monitorizatu beharreko Metrikak:**

- Erantzun denbora
- Errore tasa
- Throughput (errendimendua)
- Memoria erabilera
- CPU erabilera
- Disko I/O

**Tresnak:**

- SIEM (ELK Stack)
- Osasun egiaztapen pertsonalizatuak
- Aplikazio log-ak

#### 7.1.2 SIEM Monitorizazioa

**Alerta Arauak:**

- 5+ saio-hasiera hutsegite / 15 minutu
- SQL injection saiakerak
- XSS saiakerak
- Rate limit urraketak
- Datu-base konexio hutsegiteak
- API erantzun denbora > 5s

**Panela:**

- <http://kibana.zabala-gailetak.com:5601>

### 7.2 Babeskopia Estrategia (Backup)

#### 7.2.1 Datu-basearen Babeskopiak

**Egutegia:**

- **Egunero**: Babeskopia osoa 2:00etan
- **Astero**: Babeskopia osoa + atxikipena (30 egun)
- **Hilero**: Artxibo babeskopia (urtebeteko atxikipena)

**Inplementazioa:**

```bash
# Eguneroko babeskopia
mongodump --uri="$MONGODB_URI" --out=/backups/daily/$(date +%Y%m%d)

# Asteroko babeskopia
mongodump --uri="$MONGODB_URI" --out=/backups/weekly/$(date +%Y%U)

# Hileroko artxiboa
tar -czf /backups/archive/$(date +%Y%m).tar.gz /backups/weekly/*
```

#### 7.2.2 Aplikazioaren Babeskopiak

**Zer kopiatu:**

- Iturburu kodea (Git)
- Konfigurazio fitxategiak
- SSL ziurtagiriak
- Log-ak (7 eguneko atxikipena)
- Docker bolumenak

### 7.3 Mantentze Egutegia

#### 7.3.1 Eguneroko Zereginak

- Sistema osasuna monitorizatu
- SIEM alertak berrikusi
- Babeskopia osatzea egiaztatu
- Errore log-ak berrikusi

#### 7.3.2 Asteroko Zereginak

- Segurtasun log-ak berrikusi
- Dependentziak eguneratu
- Errendimendu optimizazioa
- Edukiera plangintza

#### 7.3.3 Hileroko Zereginak

- Segurtasun auditoria
- Babeskopia proba
- Errendimendu berrikuspena
- Dokumentazioa eguneratu

#### 7.3.4 Hiruhileroko Zereginak

- Segurtasun ebaluazio osoa
- Hondamendi berreskurapen proba (Disaster Recovery)
- Arkitektura berrikuspena
- Betetze egiaztapena (Compliance)

### 7.4 Intzidenteen Erantzuna

#### 7.4.1 Intzidente Kategoriak

| Larritasuna | Erantzun Denbora | Adibideak |
|-------------|-------------------|-----------|
| Kritikoa | < 15 min | Sistema erorita, datu-urraketa |
| Altua | < 1 ordu | Zerbitzua degradatuta, segurtasun intzidentea |
| Ertaina | < 4 ordu | Ezaugarri bat apurtuta, errendimendu arazoa |
| Baxua | < 24 ordu | Bug txikia, UX arazoa |

#### 7.4.2 Intzidente Prozesua

1. **Detekzioa**: SIEM alerta, erabiltzaile txostena
2. **Triajea**: Larritasuna eta inpaktua ebaluatu
3. **Eustea (Containment)**: Kaltetutako sistemak isolatu
4. **Ezabatzea**: Mehatxua ezabatu
5. **Berreskuratzea**: Sistemak leheneratu
6. **Ikasitako Ikasgaiak**: Dokumentatu eta hobetu

---

## 8. Betetzea eta Estandarrak

### 8.1 OWASP Top 10

| Arriskua | Mitigazioa | Egoera |
|----------|------------|--------|
| A01: Broken Access Control | RBAC, MFA | ✅ Inplementatuta |
| A02: Cryptographic Failures | Enkriptatze sendoa, TLS | ✅ Inplementatuta |
| A03: Injection | Input validation, parameterized queries | ✅ Inplementatuta |
| A04: Insecure Design | Threat modeling, secure patterns | ✅ Inplementatuta |
| A05: Security Misconfiguration | Hardening guides, secure defaults | ✅ Inplementatuta |
| A06: Vulnerable Components | Dependency scanning, eguneraketak | ✅ Inplementatuta |
| A07: Authentication Failures | MFA, tasa mugatzea | ✅ Inplementatuta |
| A08: Software & Data Integrity | Signed builds, checksums | ✅ Inplementatuta |
| A09: Logging & Monitoring | SIEM, structured logs | ✅ Inplementatuta |
| A10: SSRF | Input validation, network controls | ✅ Inplementatuta |

### 8.2 ISO 27001

**Inplementatutako Kontrolak:**

- **A.5.1.1**: Informazio segurtasuneko politikak
- **A.6.1.2**: Informazio segurtasuneko rolak eta erantzukizunak
- **A.8.2.1**: Pribilegiatutako sarbide-eskubideen kudeaketa
- **A.9.1.1**: Sarbide kontrolerako politika
- **A.10.1.1**: Kontrol kriptografikoak
- **A.12.2.1**: Malware babesa
- **A.12.3.1**: Informazio babeskopia
- **A.12.4.1**: Erregistroa (Logging)
- **A.12.6.1**: Ahultasun teknikoen kudeaketa
- **A.16.1.1**: Informazio segurtasuneko intzidenteen kudeaketa

### 8.3 GDPR Betetzea

**Datu Babeserako Neurriak:**

- **Baimena**: Datuen tratamendurako berariazko baimena
- **Helburu Mugaketa**: Datuak adierazitako helburuetarako bakarrik erabili
- **Datu Minimizazioa**: Beharrezko datuak bakarrik bildu
- **Segurtasuna**: Enkriptatzea, sarbide kontrolak
- **Eskubideak**: Datu sarbidea, ezabatzea, eramangarritasuna
- **Urraketa Jakinarazpena**: 72 orduko jakinarazpen betekizuna

### 8.4 IEC 62443 (Industria Segurtasuna)

**Inplementatutako Neurriak:**

- Sare segmentazioa (IT/OT bereizketa)
- Industria protokolo segurtasuna (Modbus, S7)
- Honeypot mehatxuak detektatzeko
- SCADA segurtasun kontrolak
- OT monitorizazioa eta logging

---

## 9. Garapen Gidalerroak

### 9.1 Kode Estandarrak

#### 9.1.1 JavaScript/Node.js

**Estilo Gida:**

- Airbnb JavaScript Style Guide
- ESLint betearazteko
- Prettier formatua emateko

**Praktika Onak:**

- Erabili const/let, saihestu var
- Async/await callbacks baino hobeto
- Errore kudeaketa try/catch-ekin
- Aldagai izen esanguratsuak
- Funtzio luzera < 50 lerro
- Fitxategi luzera < 300 lerro

#### 9.1.2 React

**Praktika Onak:**

- Osagai funtzionalak hook-ekin
- Context API egoera globalerako
- Props balidazioa (PropTypes)
- Osagaien konposizioa
- Kode zatiketa (Code splitting)
- Lazy loading

### 9.2 Segurtasun Gidalerroak

#### 9.2.1 Egin Beharrekoak (Do's)

- ✅ Beti balidatu sarrera (input)
- ✅ Erabili kontsulta parametrizatuak
- ✅ Sanitizatu irteera (output)
- ✅ Inplementatu tasa mugatzea
- ✅ Erabili HTTPS edonon
- ✅ Inplementatu MFA
- ✅ Erregistratu segurtasun gertaerak
- ✅ Mantendu dependentziak eguneratuta

#### 9.2.2 Ez Egin Beharrekoak (Don'ts)

- ❌ Inoiz ez fidatu erabiltzailearen sarreraz
- ❌ Inoiz ez erregistratu datu sentikorrak log-etan
- ❌ Inoiz ez igo sekreturik (commits)
- ❌ Inoiz ez erabili eval()
- ❌ Inoiz ez desgaitu segurtasun ezaugarriak
- ❌ Inoiz ez ignoratu segurtasun abisuak
- ❌ Inoiz ez erabili zaharkitutako funtzioak
- ❌ Inoiz ez jarri kredentzialak kodean (hardcode)

### 9.3 Proba Estrategia

#### 9.3.1 Proba Piramidea

```text
        /\
       /E2E\        (10%)
      /------\
     /Integrazioa\ (30%)
    /----------\
   /   Unit      \ (60%)
  /--------------\
```

#### 9.3.2 Estaldura Helburuak

- Unit tests: 80%+
- Integration tests: 70%+
- E2E tests: 50%+
- Orokorra: 75%+

### 9.4 Git Lan-fluxua

#### 9.4.1 Adar Estrategia (Branching)

```text
main (production)
  ↑
develop (integration)
  ↑
feature/login-page
feature/mfa-setup
bugfix/auth-error
```

#### 9.4.2 Commit Mezuak

**Formatua:**

```text
<mota>(<eremua>): <gaia>

<body>

<footer>
```

**Motak:**

- `feat`: Ezaugarri berria
- `fix`: Bug konponketa
- `docs`: Dokumentazioa
- `style`: Formatua
- `refactor`: Kode berregituraketa
- `test`: Probak
- `chore`: Mantentze lanak

---

## 10. Laguntza eta Kontaktua

### 10.1 Dokumentazioa

**Eskuragarri dagoen Dokumentazioa:**

- `IMPLEMENTATION_SUMMARY.md` - Orokorra eta hasiera azkarra
- `WEB_APP_GUIDE.md` - Web app gida zehatza
- `MOBILE_APP_GUIDE.md` - Mobile app gida zehatza
- `API_DOCUMENTATION.md` - API erreferentzia
- `SECURITY_GUIDE.md` - Segurtasun inplementazioa
- `DEPLOYMENT_GUIDE.md` - Hedapen prozedurak

### 10.2 SOP-ak (Prozedura Operatibo Estandarrak)

**Eskuragarri dauden SOP-ak:**

- `devops/sop_secure_development.md` - Garapen segurua
- `security/web_hardening_sop.md` - Web app hardening
- `security/mobile_security_sop.md` - Mobile app segurtasuna
- `infrastructure/network/network_segmentation_sop.md` - Sare segmentazioa
- `security/honeypot/honeypot_implementation_sop.md` - Honeypot konfigurazioa
- `security/incidents/sop_incident_response.md` - Intzidenteen erantzuna

### 10.3 Harremanetarako Informazioa

**Garapen Taldea:**

- **Lead Developer**: [Harremanetarako Info]
- **Segurtasun Taldea**: [Harremanetarako Info]
- **DevOps Taldea**: [Harremanetarako Info]
- **Laguntza**: <support@zabala-gailetak.com>

**Larrialdi Kontaktuak:**

- **Arazo Kritikoak**: 24/7 telefonoa: [Zenbakia]
- **Segurtasun Intzidenteak**: <security@zabala-gailetak.com>

### 10.4 Baliabideak

**Barne Baliabideak:**

- GitLab: [URL]
- CI/CD: [URL]
- Dokumentazioa: [URL]
- Monitorizazioa: [URL]
- Issue Tracker: [URL]

**Kanpo Baliabideak:**

- OWASP: <https://owasp.org>
- NIST: <https://csrc.nist.gov>
- ISO: <https://www.iso.org>
- IEC: <https://www.iec.ch>

---

## Eranskina A: Zehaztapen Teknikoak

### A.1 API Endpoint-ak

Ikusi `API_DOCUMENTATION.md` API erreferentzia osorako.

### A.2 Datu-base Eskema

**Users Bilduma:**

```javascript
{
  _id: ObjectId,
  username: String,
  email: String,
  password: String (hashed),
  mfaEnabled: Boolean,
  mfaSecret: String (encrypted),
  createdAt: Date,
  updatedAt: Date
}
```

**Products Bilduma:**

```javascript
{
  _id: ObjectId,
  name: String,
  description: String,
  price: Number,
  category: String,
  stock: Number,
  createdAt: Date,
  updatedAt: Date
}
```

**Orders Bilduma:**

```javascript
{
  _id: ObjectId,
  userId: ObjectId,
  productId: ObjectId,
  quantity: Number,
  customerName: String,
  customerEmail: String,
  shippingAddress: String,
  status: String,
  createdAt: Date,
  updatedAt: Date
}
```

### A.3 Konfigurazio Fitxategiak

**Konfigurazio fitxategi osoak:**

- `.env.example` - Ingurune aldagaiak
- `webpack.config.js` - Webpack konfigurazioa
- `docker-compose.yml` - Docker zerbitzuak
- `nginx/nginx.conf` - Nginx konfigurazioa
- `security/siem/logstash.conf` - Logstash konfigurazioa

---

## Eranskina B: Glosarioa

| Terminoa | Definizioa |
|----------|------------|
| API | Aplikazio Programazio Interfazea |
| CI/CD | Etengabeko Integrazioa/Etengabeko Hedapena |
| CSRF | Gune arteko Eskaera Faltsutzea |
| CSP | Eduki Segurtasun Politika |
| DAST | Aplikazioen Segurtasun Test Dinamikoa |
| GDPR | Datuak Babesteko Erregelamendu Orokorra |
| HIDS | Host-ean oinarritutako Intrusio Detekzio Sistema |
| HSTS | HTTP Garraio Segurtasun Zorrotza |
| IDS/IPS | Intrusio Detekzio/Prebentzio Sistema |
| JWT | JSON Web Token |
| MFA | Faktore Anitzeko Autentikazioa |
| MITM | Man-in-the-Middle (Erasotzailea erdian) |
| OWASP | Open Web Application Security Project |
| SAST | Aplikazioen Segurtasun Test Estatikoa |
| SIEM | Segurtasun Informazio eta Gertaera Kudeaketa |
| SCA | Software Konposizio Analisia |
| SOP | Prozedura Operatibo Estandarra |
| SSL/TLS | Secure Sockets Layer/Transport Layer Security |
| TOTP | Denboran oinarritutako Erabilera Bakarreko Pasahitza |
| XSS | Cross-Site Scripting |

---

## Eranskina C: Aldaketa Erregistroa

| Bertsioa | Data | Aldaketak | Egilea |
|---------|------|-----------|--------|
| 1.0 | 2026-01-08 | Hasierako dokumentazioa | Zabala Gailetak Taldea |

---

**Dokumentu Kontrola:**

- **Jabea**: Zabala Gailetak Segurtasun Taldea
- **Berrikuspen Data**: Hiruhileran behin
- **Hurrengo Berrikuspena**: 2026ko Apirila
- **Sailkapena**: Barnekoa

---

*Dokumentazioaren Amaiera*
