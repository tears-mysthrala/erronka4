# Web Aplikazioaren Inplementazioa - Gida Osoa

## Orokorra

Zabala Gailetak web aplikazioa React-en oinarritutako merkataritza elektroniko plataforma
moderno eta segurua da, mugikorrerako aplikazioaren segurtasun estandar berberekin eraikia.

## Arkitektura

```text
Web Aplikazioa (React)
    ↓ HTTPS + JWT + MFA
Backend API (Express)
    ↓
Datu-basea (MongoDB)
    ↓
SIEM (ELK Stack)
```

## Inplementatutako Ezaugarriak

### 1. Autentifikazioa eta Segurtasuna

- Saio-hasiera erabiltzaile/pasahitzarekin
- MFA (TOTP) euskarria Speakeasy erabiliz
- JWT token bidezko autentifikazioa
- Cookie kudeaketa segurua (HttpOnly, SameSite=Strict)
- CSRF babesa
- XSS prebentzioa DOMPurify-rekin
- Sarrera sanitizazioa

### 2. Oinarrizko Funtzionalitatea

- Produktu katalogoa prezioekin
- Eskaera sorrera balidazioarekin
- Erabiltzaile panela estatistikekin
- MFA gaitu/desgaitu funtzionalitatea
- Diseinu moldagarria (mugikorretarako egokia)

### 3. Segurtasun Ezaugarriak

- Content Security Policy (CSP)
- Helmet.js segurtasun goiburuak
- Tasa mugatzea APIan
- Sarrera balidazioa (express-validator)
- SQL injection prebentzioa
- XSS babesa
- CORS konfigurazioa
- HTTPS behartzea

## Proiektuaren Egitura

```text
src/web/
├── app/
│   ├── pages/
│   │   ├── Login.js           # Saio-hasiera orria diseinu gradientearekin
│   │   ├── MFA.js            # MFA egiaztapen orria
│   │   ├── Products.js        # Produktu katalogoa
│   │   ├── Order.js          # Eskaera sorrera
│   │   └── Dashboard.js      # Erabiltzaile panela
│   ├── context/
│   │   └── AuthContext.js    # Autentifikazio egoera kudeaketa
│   ├── services/
│   │   └── api.js           # API bezeroa interzeptoreekin
│   ├── styles/
│   │   └── global.css       # Estilo globalak
│   ├── index.js             # Aplikazioaren sarrera puntua
│   └── package.json         # Web aplikazioaren dependentziak
└── index.html              # HTML txantiloia
```

## Instalazioa

### 1. Dependentziak Instalatu

```bash
cd "Zabala Gailetak/src/web/app"
npm install
```

Edo errotik instalatu:

```bash
cd "Zabala Gailetak"
npm install
```

### 2. Ingurune Konfigurazioa

Sortu `.env` fitxategia erroan:

```env
REACT_APP_API_URL=http://localhost:3000/api
REACT_APP_MFA_ENABLED=true
```

### 3. Garapen Zerbitzaria

```bash
npm run web:start
```

Aplikazioa `http://localhost:3001` helbidean egongo da eskuragarri.

### 4. Produkzio Eraikuntza

```bash
npm run web:build
```

Irteera: `dist/web/bundle.[hash].js`

## Erabilera Gida

### Saio-hasiera Fluxua

1. Joan `/login` helbidera
2. Sartu erabiltzailea eta pasahitza
3. MFA gaituta badago, `/mfa` helbidera birbideratuko da
4. Sartu 6 digituko TOTP kodea
5. Panelera birbideratuko da

### Produktuak Arakatu

1. Joan `/products` helbidera saioa hasi ondoren
2. Arakatu eskuragarri dauden produktuak
3. Ikusi prezioak eta deskribapenak
4. Sakatu "Eskaera Egin" eskatzeko

### Eskaerak Sortu

1. Aukeratu produktu bat katalogotik
2. Bete eskaera formularioa:
   - Kantitatea (1-10)
   - Izen-abizenak
   - Posta elektronikoa
   - Bidalketa helbidea
3. Berrikusi guztizko prezioa
4. Bidali eskaera
5. Jaso eskaera baieztapena

### Panelaren Ezaugarriak

- Ikusi estatistikak (produktuak, eskaerak, diru-sarrerak)
- Ekintza azkarrak (produktuak, eskaerak, profila)
- MFA kudeaketa (gaitu/desgaitu)
- Erabiltzaile informazioa

## Segurtasun Inplementazioa

### 1. API Bezeroaren Segurtasuna

```javascript
// JWT tokena automatikoki txertatzen du
// 401 erroreak kudeatzen ditu
// CSRF token txertaketa
// Sarrera sanitizazioa

apiClient.interceptors.request.use((config) => {
  const token = localStorage.getItem('auth_token');
  if (token) {
    config.headers.Authorization = `Bearer ${token}`;
  }
  config.headers['X-CSRF-Token'] = getCsrfToken();
  return config;
});
```

### 2. Sarrera Sanitizazioa

```javascript
// DOMPurify XSS prebentziorako
// Kode maltzurra kentzen du
// HTML segurua mantentzen du

const sanitizeInput = (data) => {
  if (typeof data === 'string') {
    return DOMPurify.sanitize(data.trim());
  }
  // ... objektu kudeaketa
};
```

### 3. Auth Context Segurtasuna

```javascript
// Token biltegiratze segurua
// Saio-amaiera automatikoa 401 errorea jasotzean
// Cookie kudeaketa
// Token iraungitze kudeaketa

const login = async (username, password) => {
  const response = await apiService.login(username, password);
  if (response.token) {
    Cookies.set('auth_token', response.token, { 
      secure: true, 
      sameSite: 'strict',
      expires: 1
    });
  }
};
```

## Estilo Arkitektura

### Styled Components

```javascript
// Osagai-mailako estiloak
// Gai aldagaiak
// Diseinu moldagarria
// Hover egoerak

const Button = styled.button`
  padding: 12px;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: white;
  border: none;
  border-radius: 5px;
  font-size: 16px;
  font-weight: bold;
  cursor: pointer;
  transition: transform 0.2s, box-shadow 0.2s;

  &:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
  }
`;
```

## Webpack Konfigurazioa

### Produkzio Optimizazioak

- Kode zatiketa (saltzaileak, zati komunak)
- Minifikazioa
- Source maps
- Bundle analisia
- CSP txertaketa
- Konpresioa

### Garapen Ezaugarriak

- Hot Module Replacement (HMR)
- Source maps
- CORS goiburuak
- History API fallback
- Freskatze azkarra

## Probak

### Unitate Probak

```bash
cd src/web/app
npm test
```

### E2E Probak

```bash
# Cypress edo Playwright konfigurazioa behar du
npm run test:e2e
```

## Hedapena

### 1. Produkziorako Eraiki

```bash
npm run web:build
```

### 2. Fitxategi Estatikoak Zerbitzatu

Nginx-ekin:

```nginx
server {
    listen 443 ssl;
    server_name web.zabala-gailetak.com;
    
    ssl_certificate /etc/ssl/cert.pem;
    ssl_certificate_key /etc/ssl/key.pem;
    
    root /var/www/zabala-gailetak/dist/web;
    index index.html;
    
    location / {
        try_files $uri $uri/ /index.html;
    }
    
    location /api {
        proxy_pass http://api:3000;
    }
}
```

### 3. Docker Hedapena

```dockerfile
FROM node:18-alpine AS builder
WORKDIR /app
COPY package*.json ./
RUN npm ci
COPY . .
RUN npm run web:build

FROM nginx:alpine
COPY --from=builder /app/dist/web /usr/share/nginx/html
COPY nginx/web.conf /etc/nginx/conf.d/default.conf
EXPOSE 80
CMD ["nginx", "-g", "daemon off;"]
```

## Errendimendu Optimizazioa

### 1. Bundle Tamaina

- Kode zatiketa ibilbidearen arabera
- Tree shaking
- Hildako kodea ezabatzea
- Saltzaile zatiketa

### 2. Cache-a

- Nabigatzaile cache-a (Cache-Control goiburuak)
- Service Worker (PWA euskarria)
- CDN banaketa
- Aktibo estatikoen optimizazioa

### 3. Kargatze Errendimendua

- Ibilbideen karga alferra (Lazy loading)
- Irudi optimizazioa
- Letra-tipo optimizazioa
- Minifikazioa

## Nabigatzaile Bateragarritasuna

- Chrome 90+
- Firefox 88+
- Safari 14+
- Edge 90+
- Mugikorretako nabigatzaileak (iOS Safari, Chrome Mobile)

## Irisgarritasuna

- ARIA etiketak
- Teklatu bidezko nabigazioa
- Pantaila irakurle euskarria
- Kolore kontraste betetzea (WCAG AA)
- Foku adierazleak

## Monitorizazioa eta Analitika

### 1. Errore Jarraipena

```javascript
// Erroreak APIan erregistratu
window.addEventListener('error', (event) => {
  apiService.logError({
    message: event.message,
    stack: event.error?.stack,
    url: window.location.href
  });
});
```

### 2. Errendimendu Monitorizazioa

```javascript
// Orri karga denborak
// API erantzun denborak
// Erabiltzaile interakzio jarraipena
```

## Mantentze-lanak

### Aldizkako Eguneraketak

- Asteko dependentzia egiaztapenak
- Hileko segurtasun adabakiak
- Hiruhileko ezaugarri eguneraketak
- Urteroko bertsio nagusien berritzeak

### Segurtasun-kopia eta Berreskuratzea

- Datu-basearen segurtasun-kopiak (egunero)
- Aplikazioaren segurtasun-kopiak (astero)
- Hondamendi berreskuratze probak (hilero)

## Arazoak Konpontzea

### Ohiko Arazoak

1. **Saio-hasierak huts egiten du**: Egiaztatu API konexioa, egiaztatu JWT sekretua
2. **MFAk ez du funtzionatzen**: Egiaztatu ordu sinkronizazioa, egiaztatu TOTP aplikazioa
3. **Eraikuntzak huts egiten du**: Garbitu node_modules, berrinstalatu dependentziak
4. **Estiloak ez dira kargatzen**: Egiaztatu webpack konfigurazioa, egiaztatu CSS kargatzaileak

### Arazketa Modua

```javascript
// Arazketa erregistroa gaitu
localStorage.setItem('debug', 'zabala-gailetak:*');
```

## Segurtasun Kontrol Zerrenda

- [x] HTTPS gaituta
- [x] CSP goiburuak konfiguratuta
- [x] XSS babesa (DOMPurify)
- [x] CSRF tokenak
- [x] Cookie seguruak
- [x] Sarrera balidazioa
- [x] Tasa mugatzea
- [x] MFA inplementatuta
- [x] JWT autentifikazioa
- [x] Pasahitz hashing-a
- [x] Errore kudeaketa
- [x] Erregistroa (Logging)

## Etorkizuneko Hobekuntzak

- PWA (Progressive Web App)
- WebSocket denbora errealeko eguneraketak
- Ordainketa pasabide integrazioa
- Bilaketa eta iragazketa aurreratua
- Erabiltzaile iritziak eta balorazioak
- Eskaera jarraipena
- Desio zerrenda funtzionalitatea
- Hizkuntza anitzeko euskarria
- Modu iluna gaia
- Analitika panela

## Laguntza

Arazo edo galderetarako:

- Dokumentazioa: `IMPLEMENTATION_SUMMARY.md`
- API Dokumentazioa: `src/api/middleware/auth.js`
- Segurtasun SOP: `security/web_hardening_sop.md`
- CI/CD: `.github/workflows/ci-cd.yml`

## Lizentzia

ISC
