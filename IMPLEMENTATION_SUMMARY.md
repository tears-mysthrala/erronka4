# Zabala Gailetak - Segurtasun Sistema Aurreratua

Proiektu hau Zabala Gailetak enpresarako zibersegurtasun sistema aurreratua inplementatzen du, web eta mobil aplikazioak barne.

## Ezarritako Osagaiak

### 1. Backend API
- Express.js aplikazioa segurtasun middleware osatuekin
- MFA autentikazio sistema (TOTP)
- Rate limiting eta input validation
- Helmet, CORS, XSS protection

### 2. Mobile App (React Native)
- Oinarrizko egitura sortuta
- MFA balidazioa integratuta
- API service seguruak
- Login eta produktu pantailak

### 3. Web App (React)
- Modern React aplikazioa sortuta
- MFA autentikazio integratuta
- Product catalog eta order pages
- Responsive design eta styling
- Dashboard eta user management
- Secure API communication

### 3. DevOps eta CI/CD
- GitHub Actions pipeline
- Docker konfigurazioa
- Docker Compose-ekin multi-service deploy
- Nginx reverse proxy

### 4. Testing
- Jest unit eta integration test-ak
- API endpoint test-ak
- Authentication service test-ak
- Coverage reporting

### 5. Segurtasun Tresnak
- ESLint segurtasun rules-ekin
- SIEM sistema (ELK Stack)
- Logstash konfigurazioa
- Security scanning pipeline

### 6. Dokumentazioa eta SOP-ak
- Web app segurtasun hardening SOP
- Mobile app segurtasun SOP
- Network segmentation SOP
- Honeypot implementation SOP

## Hasieratzea

### Backend API

```bash
cd "Zabala Gailetak"
npm install
cp .env.example .env
# .env fitxategia editatu
npm run dev
```

### Docker-ekin Deploy

```bash
docker-compose up -d
```

### SIEM Sistema

```bash
cd "Zabala Gailetak/security/siem"
cp .env.example .env
docker-compose -f docker-compose.siem.yml up -d
```

## Proiektuaren Egitura

```
erronkak/
├── Zabala Gailetak/
│   ├── src/
│   │   ├── api/
│   │   │   ├── app.js              # Express aplikazio nagusia
│   │   │   └── middleware/
│   │   │       └── auth.js        # Auth eta MFA middleware
│   │   ├── mobile/
│   │   │   ├── App.js             # React Native app
│   │   │   ├── screens/           # Pantailak
│   │   │   └── services/         # API service-ak
│   │   └── web/
│   │       ├── app/               # React web aplikazioa
│   │       │   ├── pages/         # Web orrialak (Login, Products, Order, etc.)
│   │       │   ├── context/       # Auth context
│   │       │   ├── services/      # API services
│   │       │   └── styles/       # Global styles
│   │       └── index.html         # HTML template
│   ├── security/
│   │   ├── siem/
│   │   │   ├── logstash.conf
│   │   │   ├── docker-compose.siem.yml
│   │   │   └── elasticsearch-template.json
│   │   └── honeypot/
│   │       └── honeypot_implementation_sop.md
│   ├── infrastructure/
│   │   └── network/
│   │       └── network_segmentation_sop.md
│   ├── tests/
│   │   ├── api.test.js
│   │   └── auth.test.js
│   ├── package.json
│   ├── .eslintrc.js
│   └── .env.example
├── .github/
│   └── workflows/
│       └── ci-cd.yml
├── docker-compose.yml
├── Dockerfile
└── nginx/
    └── nginx.conf
```

## API Endpoints

### Autentikazioa
- `POST /api/auth/register` - Erabiltzaile berria sortu
- `POST /api/auth/login` - Saioa hasi
- `POST /api/auth/mfa/setup` - MFA konfiguratu (autentikazioa beharrezkoa)
- `POST /api/auth/mfa/verify` - MFA kodea balidatu (autentikazioa beharrezkoa)
- `POST /api/auth/mfa/disable` - MFA desgaitu (autentikazioa beharrezkoa)

### Produktuak
- `GET /api/products` - Produktuen zerrenda

### Eskaerak
- `POST /api/orders` - Eskaera berria sortu

### Sistema
- `GET /api/health` - Egoera egiaztatu
- `GET /` - API informazioa

## Komandoak

### Garapena
```bash
npm run dev          # Development moduan abiarazi
npm test             # Test-ak exekutatu
npm run lint         # Linting egin
npm run lint:fix    # Linting arazoak konpondu
npm run web:start    # Web dev server abiarazi
npm run web:build    # Web production build
npm run web:lint     # Web linting egin
```

### Docker
```bash
docker-compose build      # Imagenak eraiki
docker-compose up -d     # Services abiarazi
docker-compose down       # Services gelditu
docker-compose logs -f    # Log-ak ikusi
```

### CI/CD
- Push-ak trigger egingo du CI/CD pipeline-a
- Security scanning, linting, testing eta automatic deployment-ak

## Segurtasun Konfigurazioa

### Environment Variables
- `JWT_SECRET` - JWT token-ak sinatzeko gakoa (production-ean aldatu)
- `MONGODB_URI` - MongoDB konexio string-a
- `ALLOWED_ORIGINS` - Baimendutako origenen zerrenda
- `MFA_ISSUER` - MFA issuer izena

### Rate Limiting
- Default: 100 eskaeri / 15 minutu / IP
- Production-ean doitzeko gai

### MFA
- Speakeasy erabilita TOTP-rako
- QR kodea generatzen da Google Authenticator-erako

## Monitorizazioa eta Logging

### SIEM System
- ELK Stack (Elasticsearch, Logstash, Kibana)
- Application log-ak automatikoki bidaltzen dira
- Dashboard-ak Kibana-n
- Alert-ak threshold-en arabera

### Log-ak
- Application log-ak `logs/app.log`-en
- Nginx log-ak `/var/log/nginx/`-en
- MongoDB log-ak `/var/log/mongodb/`-en

## Segurtasun Audit-ak

### Automated Scanning
- SAST: SonarQube
- SCA: OWASP Dependency Check
- DAST: OWASP ZAP
- Linting: ESLint segurtasun plugin-ekin

### Manual Testing
- Pentesting plan-a
- OWASP Top 10 compliance
- Network security audit-ak

## Honeypot

### Available Types
- Conpot (ICS/SCADA)
- Cowrie (SSH/Telnet)
- Dionaea (Malware)

### Configuration
- `security/honeypot/honeypot_implementation_sop.md`-n jarraibideak
- Docker Compose erabilgarria deployment-erako

## Maintenance

### Regular Tasks
- Hilabetero: Security scans egiaztatu
- Hiru hilabetero: Firewall rules berrikusi
- Urtero: Full pentesting audit
- Aldian-aldian: Honeypot log-ak aztertu

### Updates
- Dependentzia-ak eguneratu
- Segurtasun patch-ak aplikatu
- Log-ak rotatu eta archive

## Laguntza eta Erreferentziak

### Dokumentazioa
- `Zabala Gailetak/devops/sop_secure_development.md` - SSDLC prozesua
- `Zabala Gailetak/WEB_APP_GUIDE.md` - Web aplikazioaren gidabide osoa
- `Zabala Gailetak/security/web_hardening_sop.md` - Web app segurtasuna
- `Zabala Gailetak/security/mobile_security_sop.md` - Mobile app segurtasuna
- `Zabala Gailetak/infrastructure/network/network_segmentation_sop.md` - Sare segurtasuna
- `Zabala Gailetak/security/honeypot/honeypot_implementation_sop.md` - Honeypot-a

### Erreferentziak
- OWASP Top 10: https://owasp.org/www-project-top-ten/
- NIST Cybersecurity Framework
- IEC 62443 (Industrial Security)
- ISO 27001 (Information Security)

## Lizentzia

ISC

## Egileak

Zabala Gailetak Security Team