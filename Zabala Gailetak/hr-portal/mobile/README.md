# Zabala Gailetak HR Portal - Mobile App

Aplikazio mugikorrera ongi etorri (iOS/Android) HR portalera sartzeko.

## 🚀 Ezaugarriak

- ✅ **Autentifikazioa**: Login segurua JWT tokenekin
- ✅ **Langileen Zerrenda**: Ikusi langile guztiak pull-to-refresh-ekin
- ✅ **Langile Xehetasunak**: Ikusi informazio osoa eta aldaketa historia
- ✅ **CRUD Eragiketak**: Sortu, editatu eta ezabatu langileak
- ✅ **Audit Trail**: Ikusi aldaketa guztien historia
- ✅ **Euskarazko Interfazea**: UI osoa euskaraz

## 📱 Teknologia Stack-a

- **React Native**: 0.73.2
- **Expo**: ~50.0.0
- **React Navigation**: 6.1.9 (Native Stack)
- **AsyncStorage**: Token persistentziarako
- **Axios**: API komunikaziorako

## 🛠️ Instalazio Urratsak

### 1. Dependentziak Instalatu

```bash
cd mobile
npm install
```

### 2. Konfiguratu API URL-a

Editatu `src/services/api.js` fitxategia eta aldatu `API_BASE_URL`:

```javascript
const API_BASE_URL = 'http://YOUR_LOCAL_IP:8080/api';
// Adibidez: http://192.168.1.100:8080/api
```

**Garrantzitsua**: Erabili zure ordenagailuaren IP helbidea sare lokalean, ez `localhost`.

### 3. Abiarazi Backend API-a

Ziurtatu backend API-a martxan dagoela:

```bash
cd ../api
php -S localhost:8080 -t public
```

### 4. Abiarazi Aplikazioa

#### iOS (macOS besterik ez)

```bash
npm run ios
```

#### Android

```bash
npm run android
```

**Oharra**: Beharrezkoa duzu Android Studio/Xcode instalatuta edukitzea.

#### Expo Go Erabiliz (Erraza)

1. Instalatu Expo Go zure mugikorrean (App Store/Play Store)
2. Abiarazi dev server-a:

```bash
npm start
```

3. Eskaneatu QR kodea Expo Go aplikazioarekin

## 📂 Proiektuaren Egitura

```
mobile/
├── App.js                          # Aplikazioaren sarrera puntua + Nabigazio konfigurazioa
├── app.json                        # Expo konfigurazioa
├── package.json                    # Dependentziak
├── babel.config.js                 # Babel konfigurazioa
└── src/
    ├── context/
    │   └── AuthContext.js          # Autentifikazio kudeaketa (React Context)
    ├── services/
    │   └── api.js                  # API bezero metodoak (Axios)
    └── screens/
        ├── LoginScreen.js          # Login pantaila
        ├── EmployeeListScreen.js   # Langileen zerrenda (FlatList)
        ├── EmployeeDetailScreen.js # Langilearen xehetasunak + historia
        └── EmployeeFormScreen.js   # Langilea sortu/editatu formularioa
```

## 🔐 Autentifikazioa

Aplikazioak AsyncStorage erabiltzen du JWT tokenak gordetzeko:

1. **Login**: Erabiltzaileak email eta pasahitza sartzen ditu
2. **Token Gorde**: JWT tokena AsyncStorage-n gordetzen da
3. **Auto-login**: Tokena automatikoki kargatzen da app abiaraztean
4. **Interceptors**: Axios-ek automatikoki gehitzen du tokena API deialdi guztietan
5. **Logout**: Tokena AsyncStorage-tik ezabatzen da

## 📱 Pantailak

### 1. LoginScreen

- Email eta pasahitza inputak
- Loading egoera (ActivityIndicator)
- Erroreen kudeaketa (Alert)
- Gradient urdina background

### 2. EmployeeListScreen

- FlatList langileen zerrenda bistaratzeko
- Pull-to-refresh funtzionalitatea
- Langile bakoitzeko karta (izena, emaila, kargua)
- Floating Action Button (FAB) langile berriak sortzeko
- Navegazioa: Detailera eta Formulariora

### 3. EmployeeDetailScreen

- ScrollView langilearen informazio osoarekin
- Informazio sekzioak: Oinarrizko, Datak
- Editatu eta Ezabatu botoiak
- Aldaketa historia audit trail-ekin
- Badge aktiboa/inaktiboa

### 4. EmployeeFormScreen

- KeyboardAvoidingView formularioetarako
- Baliozkotze live-a
- Errore mezuak eremu bakoitzeko
- Sortu/Eguneratu moduak
- Utzi eta Gorde botoiak

## 🎨 Estilo Gidalerro

Aplikazioak StyleSheet.create erabiltzen du:

- **Kolore nagusiak**:
  - Primary: `#0066cc` (urdin iluna)
  - Success: `#28a745` (berdea)
  - Danger: `#dc3545` (gorria)
  - Background: `#f5f5f5` (gris argia)

- **Tipografia**:
  - Izenburuak: 18-24px, bold
  - Gorputza: 14-16px
  - Oin-oharrak: 12px

## 🔄 API Integrazioa

Aplikazioak `src/services/api.js` erabiltzen du API deialdiak egiteko:

```javascript
// Adibidea
import api from '../services/api';

// Login
await api.login(email, password);

// Lortu langileak
const data = await api.getEmployees({ page: 1, limit: 20 });

// Sortu langilea
await api.createEmployee(employeeData);
```

### Erabilgarri dauden metodoak:

- `login(email, password)` - Autentifikatu
- `logout()` - Itxi saioa
- `getMe()` - Lortu erabiltzaile datuak
- `getEmployees(params)` - Lortu langileen zerrenda
- `getEmployee(id)` - Lortu langile bat
- `createEmployee(data)` - Sortu langile berria
- `updateEmployee(id, data)` - Eguneratu langilea
- `deleteEmployee(id)` - Ezabatu langilea
- `restoreEmployee(id)` - Berreskuratu langilea
- `getEmployeeHistory(id)` - Lortu aldaketa historia

## 🐛 Debugging

### Console Logs

```bash
# Ikusi console logs Metro bundler-ean
npm start
```

### React Native Debugger

1. Instalatu React Native Debugger
2. Sakatu `Cmd+D` (iOS) edo `Cmd+M` (Android) simulatzailean
3. Hautatu "Debug"

### API Errore Arrunta

**ERRO "Network request failed"**:
- Ziurtatu backend API-a martxan dagoela
- Egiaztatu `API_BASE_URL` zuzena dela (ez erabili `localhost`)
- Probatu: `http://YOUR_LOCAL_IP:8080/api`

**ERRO "401 Unauthorized"**:
- Tokena iraungita dago
- Saioa itxi eta berriz hasi

## 📦 Build Produkziorako

### Android APK

```bash
# EAS Build erabiliz (Expo)
npm install -g eas-cli
eas build --platform android
```

### iOS IPA

```bash
# EAS Build erabiliz (beharrezkoa Apple Developer Account)
eas build --platform ios
```

## 🧪 Probak

```bash
# Unit test-ak (konfiguratu behar)
npm test
```

## 📝 Oharrak

- Aplikazioa Expo managed workflow-a erabiltzen du erraztasunarengatik
- AsyncStorage erabiltzen da (produkzioan SecureStore komenigarriagoa da)
- Formularietako datak `YYYY-MM-DD` formatuan sartu behar dira
- Pull-to-refresh 20 langile gehienez kargatzen ditu

## 🔒 Segurtasun Kontuan Hartzekoak

- JWT tokenak AsyncStorage-n gordetzen dira (ez da oso segurua)
- **Produkziorako**: Erabili `expo-secure-store` token sensibleak gordetzeko
- API deialdi guztiak HTTPS bidez egin behar dira produkzioan
- Input guztiak balidatzen dira zerbitzarian

## 🤝 Laguntza

Arazoak? Ikusi dokumentazio nagusia edo zabaldu issue bat.

---

**Garatzaileak**: Zabala Gailetak IT Team  
**Bertsioa**: 1.0.0  
**Azken Eguneraketa**: 2024
