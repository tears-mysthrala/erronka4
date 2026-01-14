# Zabala Gailetak Mugikorrerako Aplikazioaren Gida

**Plataforma:** iOS & Android  
**Teknologia:** React Native  
**Bertsioa:** 1.0  
**Egoera:** Produkziorako Prest

---

## Aurkibidea

1. [Orokorra](#1-orokorra)
2. [Ezaugarriak](#2-ezaugarriak)
3. [Arkitektura](#3-arkitektura)
4. [Garapen Konfigurazioa](#4-garapen-konfigurazioa)
5. [Eraikuntza eta Hedapena](#5-eraikuntza-eta-hedapena)
6. [Segurtasun Inplementazioa](#6-segurtasun-inplementazioa)
7. [Probak](#7-probak)
8. [Arazoak Konpontzea](#8-arazoak-konpontzea)

---

## 1. Orokorra

### 1.1 Helburua

Zabala Gailetak mugikorrerako aplikazioak plataforma seguru eta erabilerraza eskaintzen du
produktuak arakatzeko eta iOS eta Android gailuetatik eskaerak egiteko.

### 1.2 Ezaugarri Nagusiak

- ✅ Autentifikazio segurua MFArekin
- ✅ Produktu katalogoa
- ✅ Eskaera egitea
- ✅ Autentifikazio biometrikoa (hatz-marka/Face ID)
- ✅ Lineaz kanpoko euskarria (etorkizunean)
- ✅ Push jakinarazpenak (etorkizunean)
- ✅ Eskaera jarraipena (etorkizunean)

### 1.3 Helburu Plataformak

| Plataforma | Gutxieneko Bertsioa | Merkatu Kuota |
|------------|---------------------|---------------|
| Android | API Level 21 (5.0 Lollipop) | %99+ |
| iOS | iOS 13+ | %95+ |

---

## 2. Ezaugarriak

### 2.1 Autentifikazioa

#### 2.1.1 Saio-hasiera Fluxua

```text
1. Erabiltzaileak kredentzialak sartzen ditu
   ↓
2. APIak erabiltzailea/pasahitza balidatzen du
   ↓
3. MFA gaituta badago → MFA pantaila
   ↓
4. Sartu TOTP kodea (6 digitu)
   ↓
5. Autentifikazioa osatuta
   ↓
6. Produktuetara nabigatu
```

#### 2.1.2 Autentifikazio Biometrikoa

**Onartutako Metodoak:**

- Android: Hatz-marka
- iOS: Touch ID / Face ID

**Inplementazioa:**

```javascript
import TouchID from 'react-native-touch-id';

const authenticate = async () => {
  try {
    const success = await TouchID.authenticate(
      'Autentikazioa behar da'
    );
    return success;
  } catch (error) {
    console.error('Biometric auth failed');
    return false;
  }
};
```

### 2.2 Produktu Katalogoa

**Ezaugarriak:**

- Sareta diseinua produktu txartelekin
- Produktu irudiak
- Prezioak EUR-tan
- Produktu deskribapenak
- Kategoria iragazketa (etorkizunean)

### 2.3 Eskaera Egitea

**Eskaera Formulario Eremuak:**

- Kantitatea (1-10)
- Bezeroaren izena
- Posta elektronikoa
- Bidalketa helbidea

**Balidazioa:**

- Denbora errealeko balidazioa
- Errore mezuak
- Prezio kalkulua
- Eskaera baieztapena

### 2.4 Erabiltzaile Panela

**Ezaugarriak:**

- Estatistika bistaratzea
- Ekintza azkarrak
- MFA kudeaketa
- Saioa itxi

---

## 3. Arkitektura

### 3.1 Osagai Egitura

```text
App (Erroa)
├── AuthProvider
├── NavigationContainer
│   ├── Stack Navigator
│   │   ├── Login Screen
│   │   ├── MFA Screen
│   │   ├── Products Screen
│   │   ├── Order Screen
│   │   └── Dashboard Screen
```

### 3.2 Egoera Kudeaketa

**Autentifikazio Testuingurua (Context):**

- Erabiltzaile egoera
- Auth egoera
- MFA egoera
- Login/Logout funtzioak

**Egoera Lokala:**

- Formulario sarrerak
- Produktu zerrenda
- Eskaera datuak
- Kargatzen egoerak
- Errore mezuak

### 3.3 Nabigazioa

**Nabigazio Pila (Stack):**

1. **Login** → (autentifikatua) → **Products**
2. **Login** → (MFA behar da) → **MFA** → **Products**
3. **Products** → **Order** (produktu datuekin)
4. **Order** → (arrakasta) → **Products**
5. **Products** → **Dashboard**
6. **Dashboard** → **Logout** → **Login**

---

## 4. Garapen Konfigurazioa

### 4.1 Aurrebaldintzak

#### 4.1.1 Software Baldintzak

| Softwarea | Bertsioa | Helburua |
|-----------|----------|----------|
| Node.js | 18+ | Runtime |
| npm | 9+ | Pakete kudeatzailea |
| React Native CLI | 12+ | CLI tresnak |
| Android Studio | Hedgehog | Android garapena |
| Xcode | 14+ | iOS garapena (macOS soilik) |

#### 4.1.2 Hardware Baldintzak

| Plataforma | CPU | RAM | Biltegiratzea |
|------------|-----|-----|---------------|
| Android | 4 nukleo | 8GB | 50GB |
| iOS (macOS) | 4 nukleo | 8GB | 50GB |

### 4.2 Instalazio Urratsak

#### 4.2.1 Errepositorioa Klonatu

```bash
git clone <repository-url>
cd erronkak/Zabala Gailetak/src/mobile
```

#### 4.2.2 Dependentziak Instalatu

```bash
npm install
```

#### 4.2.3 iOS Konfigurazioa (macOS soilik)

```bash
# Pods instalatu
cd ios
pod install
cd ..

# Xcode ireki
open ios/ZabalaGailetak.xcworkspace
```

#### 4.2.4 Android Konfigurazioa

```bash
# local.properties sortu
echo "sdk.dir=$HOME/Android/Sdk" > android/local.properties

# Eraiki eta exekutatu
npx react-native run-android
```

### 4.3 Konfigurazioa

#### 4.3.1 Ingurune Aldagaiak

Sortu `.env` fitxategia:

```env
# API Konfigurazioa
API_URL=http://localhost:3000/api

# Segurtasuna
ENABLE_MFA=true
ENABLE_BIOMETRICS=true
```

#### 4.3.2 Aplikazio Konfigurazioa

**Android:** `android/app/build.gradle`

```gradle
android {
    compileSdkVersion 33
    buildToolsVersion "33.0.0"
    
    defaultConfig {
        applicationId "com.zabalagailetak"
        minSdkVersion 21
        targetSdkVersion 33
        versionCode 1
        versionName "1.0.0"
    }
}
```

**iOS:** `ios/ZabalaGailetak/Info.plist`

```xml
<key>API_URL</key>
<string>https://api.zabala-gailetak.com/api</string>
```

---

## 5. Eraikuntza eta Hedapena

### 5.1 Garapen Eraikuntza

#### 5.1.1 Android-en exekutatu

```bash
npm run android
```

#### 5.1.2 iOS-en exekutatu

```bash
npm run ios
```

#### 5.1.3 Metro-rekin exekutatu

```bash
npm start
# Beste terminal batean:
npm run android  # edo npm run ios
```

### 5.2 Produkzio Eraikuntza

#### 5.2.1 Android APK

```bash
cd android
./gradlew assembleRelease
```

Irteera: `android/app/build/outputs/apk/release/app-release.apk`

#### 5.2.2 Android AAB (Google Play)

```bash
cd android
./gradlew bundleRelease
```

Irteera: `android/app/build/outputs/bundle/release/app-release.aab`

#### 5.2.3 iOS IPA (App Store)

```bash
# Xcode-n:
# 1. Aukeratu "Any iOS Device" helburu gisa
# 2. Product → Archive
# 3. Distribute App
```

### 5.3 App Store Hedapena

#### 5.3.1 Google Play Store

**Urratsak:**

1. Sortu aplikazioa Google Play Console-n
2. Igo AAB fitxategia
3. Bete dendako fitxa
4. Eman pantaila-argazkiak
5. Bidali berrikuspetera

**Beharrezko Aktiboak:**

- Ikonoa: 512x512px
- Ezaugarri grafikoa: 1024x500px
- Pantaila-argazkiak: Hainbat tamaina
- Bannerra: 180x120px

#### 5.3.2 Apple App Store

**Urratsak:**

1. Sortu aplikazioa App Store Connect-en
2. Igo IPA Xcode edo Transporter bidez
3. Bete aplikazioaren informazioa
4. Eman pantaila-argazkiak
5. Bidali berrikuspetera

**Beharrezko Aktiboak:**

- Ikonoa: 1024x1024px
- Pantaila-argazkiak: Hainbat tamaina
- Aplikazioaren aurrebista (aukerakoa)

---

## 6. Segurtasun Inplementazioa

### 6.1 Autentifikazio Segurtasuna

#### 6.1.1 Token Biltegiratze Segurua

**Android:**

```javascript
import EncryptedStorage from 'react-native-encrypted-storage';

const storeToken = async (token) => {
  try {
    await EncryptedStorage.setItem(
      'auth_token',
      token
    );
  } catch (error) {
    console.error('Error storing token');
  }
};
```

**iOS:**

```javascript
import * as Keychain from 'react-native-keychain';

const storeCredentials = async (username, password) => {
  try {
    await Keychain.setGenericPassword(
      username,
      password
    );
  } catch (error) {
    console.error('Error storing credentials');
  }
};
```

#### 6.1.2 Sare Segurtasuna

**HTTPS Soilik:**

```javascript
const apiClient = axios.create({
  baseURL: 'https://api.zabala-gailetak.com',
  timeout: 10000,
  httpsAgent: new https.Agent({
    rejectUnauthorized: true
  })
});
```

**Ziurtagiri Pinning-a (Aukerakoa):**

```javascript
import { Pinning } from 'react-native-ssl-pinning';

Pinning.enableCertificatePinning(
  'api.zabala-gailetak.com',
  ['certificate_hash'],
  () => {
    console.log('Certificate pinning enabled');
  }
);
```

### 6.2 Datu Segurtasuna

#### 6.2.1 Sarrera Balidazioa

```javascript
const validateEmail = (email) => {
  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  return emailRegex.test(email);
};

const validateQuantity = (qty) => {
  const num = parseInt(qty);
  return num >= 1 && num <= 100;
};
```

#### 6.2.2 Irteera Kodetzea

```javascript
import DOMPurify from 'dompurify';

const sanitizeInput = (input) => {
  return DOMPurify.sanitize(input);
};
```

### 6.3 Baimenak

#### 6.3.1 Android Baimenak

```xml
<uses-permission android:name="android.permission.INTERNET" />
<uses-permission android:name="android.permission.USE_FINGERPRINT" />
<uses-permission android:name="android.permission.USE_BIOMETRIC" />
```

#### 6.3.2 iOS Baimenak

```xml
<key>NSFaceIDUsageDescription</key>
<string>Autentikazio biometrikoa erabiltzeko</string>
```

### 6.4 Deep Link Segurtasuna

```javascript
const handleOpenURL = async (url) => {
  // Validate URL
  const allowedDomains = [
    'https://zabala-gailetak.com',
    'https://www.zabala-gailetak.com'
  ];
  
  const isValidURL = allowedDomains.some(domain => 
    url.startsWith(domain)
  );
  
  if (!isValidURL) {
    Alert.alert('Security Alert', 'Invalid URL');
    return;
  }
  
  // Process URL
};
```

---

## 7. Probak

### 7.1 Unitate Probak

**Konfigurazioa:**

```javascript
import { render, fireEvent } from '@testing-library/react-native';
import Login from '../screens/LoginScreen';

describe('Login Screen', () => {
  it('renders correctly', () => {
    const { getByPlaceholderText } = render(<Login />);
    expect(
      getByPlaceholderText('Erabiltzailea')
    ).toBeTruthy();
  });
  
  it('handles login submission', () => {
    const { getByPlaceholderText, getByText } = render(<Login />);
    
    fireEvent.changeText(
      getByPlaceholderText('Erabiltzailea'),
      'testuser'
    );
    fireEvent.changeText(
      getByPlaceholderText('Pasahitza'),
      'testpass'
    );
    fireEvent.press(getByText('Saioa Hasi'));
  });
});
```

### 7.2 E2E Probak

**Konfigurazioa (Detox):**

```javascript
describe('Login Flow', () => {
  beforeAll(async () => {
    await device.launchApp();
  });
  
  it('should login successfully', async () => {
    await element(by.id('username')).typeText('testuser');
    await element(by.id('password')).typeText('testpass');
    await element(by.id('loginButton')).tap();
    
    await expect(element(by.id('products'))).toBeVisible();
  });
});
```

### 7.3 Proba Exekuzioa

```bash
# Unitate probak
npm test

# E2E probak
npm run test:e2e

# Proba estaldura
npm run test:coverage
```

---

## 8. Arazoak Konpontzea

### 8.1 Ohiko Arazoak

#### 8.1.1 Eraikuntza Erroreak

**Arazoa:** Gradle sync-ak huts egiten du

**Irtenbidea:**

```bash
cd android
./gradlew clean
./gradlew build
```

#### 8.1.2 Metro Bundler Arazoak

**Arazoa:** Metro bundler ez da hasten

**Irtenbidea:**

```bash
npm start -- --reset-cache
```

#### 8.1.3 iOS Eraikuntza Erroreak

**Arazoa:** CocoaPods-ek ez du funtzionatzen

**Irtenbidea:**

```bash
cd ios
pod deintegrate
pod install
```

### 8.2 Errendimendu Arazoak

#### 8.2.1 Nabigazio Motela

**Irtenbidea:** Erabili React Navigation-en lazy loading

```javascript
const Products = React.lazy(() =>
  import('./screens/ProductsScreen')
);
```

#### 8.2.2 Bundle Tamaina Handia

**Irtenbidea:**

- Erabili kode zatiketa
- Optimizatu irudiak
- Kendu erabiltzen ez diren dependentziak

### 8.3 Segurtasun Arazoak

#### 8.3.1 Tokena ez da gordetzen

**Irtenbidea:** Egiaztatu biltegiratze baimenak

```javascript
// Android
<uses-permission android:name="android.permission.READ_EXTERNAL_STORAGE" />

// iOS
Check Info.plist for proper keys
```

---

## Eranskina A: Erreferentzia Azkarra

### A.1 Komandoak

| Komandoa | Deskribapena |
|----------|--------------|
| `npm start` | Hasi Metro bundler |
| `npm run android` | Android-en exekutatu |
| `npm run ios` | iOS-en exekutatu |
| `npm test` | Probak exekutatu |
| `npm run lint` | Kodea lint-eatu |
| `npm run android:release` | Android release eraiki |

### A.2 Ingurune Aldagaiak

| Aldagaia | Deskribapena | Lehenetsia |
|----------|--------------|------------|
| `API_URL` | API endpoint | <http://localhost:3000/api> |
| `ENABLE_MFA` | MFA gaitu | true |
| `ENABLE_BIOMETRICS` | Biometrikoa gaitu | true |
| `LOG_LEVEL` | Erregistro maila | info |

### A.3 Esteka Erabilgarriak

- **React Native Dokumentazioa:** <https://reactnative.dev>
- **React Navigation:** <https://reactnavigation.org>
- **Axios Dokumentazioa:** <https://axios-http.com>
- **Detox:** <https://wix.github.io/Detox/>

---

**Dokumentu Bertsioa:** 1.0  
**Azken Eguneratzea:** 2024-01-08  
**Mantentzailea:** Zabala Gailetak Mobile Team
