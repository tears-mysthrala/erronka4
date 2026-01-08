# Mobil Aplikazio Segurtasun Finkapena - SOP

## Helburua

Mobil aplikazioa (React Native) segurtasun estandarren arabera finkatzeko prozedura, OWASP Mobile Top 10 arauak kontuan hartuta.

## Aurrebaldintzak

- React Native proiektua eskuragarria
- Android Studio eta Xcode instalatuta (berrikuspenetarako)
- MobSF (Mobile Security Framework) edo antzeko tresnak

## 1. Garapen Ingurune Segurua

### 1.1 Gradle Seguruak (Android)

```gradle
android {
    compileSdkVersion 33
    buildToolsVersion "33.0.0"
    
    defaultConfig {
        minSdkVersion 21
        targetSdkVersion 33
        versionCode 1
        versionName "1.0"
    }
    
    buildTypes {
        release {
            minifyEnabled true
            proguardFiles getDefaultProguardFile('proguard-android.txt'), 'proguard-rules.pro'
            debuggable false
        }
    }
}
```

### 1.2 Info.plist Segurua (iOS)

```xml
<key>NSAppTransportSecurity</key>
<dict>
    <key>NSAllowsArbitraryLoads</key>
    <false/>
    <key>NSExceptionDomains</key>
    <dict>
        <key>api.zabala-gailetak.com</key>
        <dict>
            <key>NSExceptionRequiresForwardSecrecy</key>
            <false/>
            <key>NSIncludesSubdomains</key>
            <true/>
            <key>NSTemporaryExceptionAllowsInsecureHTTPLoads</key>
            <false/>
        </dict>
    </dict>
</dict>
```

## 2. Datu Biltegiratze Segurua

### 2.1 Sensitive Data Ez Gorde Local-ki

- Pasahitzak ez gorde
- API gakoak ez gorde kodean
- Token seguruak erabili

### 2.2 AsyncStorage Encrypted Erabili

```javascript
import EncryptedStorage from 'react-native-encrypted-storage';

const storeToken = async (token) => {
  try {
    await EncryptedStorage.setItem('user_token', token);
  } catch (error) {
    console.error('Errorea tokena gordetzerakoan');
  }
};

const getToken = async () => {
  try {
    return await EncryptedStorage.getItem('user_token');
  } catch (error) {
    console.error('Errorea tokena irakurtzean');
  }
};
```

### 2.3 Keychain Erabili (iOS)

```javascript
import * as Keychain from 'react-native-keychain';

const storeCredentials = async (username, password) => {
  try {
    await Keychain.setGenericPassword(username, password);
  } catch (error) {
    console.error('Errorea kredentzialak gordetzerakoan');
  }
};
```

## 3. Komunikazio Segurua

### 3.1 HTTPS Soilik Erabili

```javascript
const apiClient = axios.create({
  baseURL: 'https://api.zabala-gailetak.com',
  timeout: 10000,
  httpsAgent: new https.Agent({
    rejectUnauthorized: true
  })
});
```

### 3.2 Certificate Pinning

```bash
npm install react-native-ssl-pinning
```

```javascript
import { Pinning } from 'react-native-ssl-pinning';

Pinning.enableCertificatePinning('api.zabala-gailetak.com', ['cert1'], () => {
  console.log('Certificate pinning enabled');
});
```

## 4. Autentikazio Segurua

### 4.1 MFA Implementatu

```javascript
const handleMFA = async (token) => {
  try {
    const response = await apiClient.post('/auth/mfa/verify', { token });
    // Prozesatu erantzuna
  } catch (error) {
    Alert.alert('Errorea', 'MFA kodea baliogabea');
  }
};
```

### 4.2 Biometrikoak Erabili

```javascript
import TouchID from 'react-native-touch-id';

const authenticate = async () => {
  try {
    const success = await TouchID.authenticate('Autentikazioa behar da');
    return success;
  } catch (error) {
    console.error('Biometrik autentikazioa huts egin du');
    return false;
  }
};
```

## 5. Sarrera Kontrola

### 5.1 Permissions Balidatu

```javascript
import { PermissionsAndroid, Platform } from 'react-native';

const requestCameraPermission = async () => {
  if (Platform.OS === 'android') {
    try {
      const granted = await PermissionsAndroid.request(
        PermissionsAndroid.PERMISSIONS.CAMERA
      );
      return granted === PermissionsAndroid.RESULTS.GRANTED;
    } catch (err) {
      console.warn(err);
      return false;
    }
  }
  return true;
};
```

### 5.2 Permissionak Manifest-ean Bakarrik

AndroidManifest.xml eta Info.plist-ean soilik beharrezko permissionak erantsi

## 6. HTTPS eta Certificate Pinning

### 6.1 Network Security Config (Android)

```xml
<?xml version="1.0" encoding="utf-8"?>
<network-security-config>
    <domain-config>
        <domain includeSubdomains="true">api.zabala-gailetak.com</domain>
        <pin-set>
            <pin digest="SHA-256">BASE64_CERT_HASH</pin>
        </pin-set>
    </domain-config>
</network-security-config>
```

## 7. Interceptor Seguruak

### 7.1 Axios Interceptor

```javascript
apiClient.interceptors.request.use(async (config) => {
  const token = await getToken();
  if (token) {
    config.headers.Authorization = `Bearer ${token}`;
  }
  return config;
});

apiClient.interceptors.response.use(
  (response) => response,
  async (error) => {
    if (error.response?.status === 401) {
      // Logout eta login orrira bideratu
      await clearToken();
      navigation.navigate('Login');
    }
    return Promise.reject(error);
  }
);
```

## 8. Deep Link Segurua

### 8.1 Universal Links (iOS) eta App Links (Android)

- HTTPS soilik erabili deep link-etarako
- Balidatu jatorria

```javascript
import { Linking } from 'react-native';

const handleOpenURL = async (url) => {
  if (url.startsWith('https://zabala-gailetak.com/')) {
    // Prozesatu URL segurua
  } else {
    Alert.alert('Abisua', 'Link ez da segurua');
  }
};

Linking.addEventListener('url', (event) => {
  handleOpenURL(event.url);
});
```

## 9. Debug Mode Ez Produktuan

```javascript
if (__DEV__) {
  console.log('Debug mode - ez erabili produktuan');
} else {
  console.log = () => {};
}
```

## 10. Segurtasun Audit Tresnak

### 10.1 MobSF Erabili

```bash
docker run -it -p 8000:8000 opensecurity/mobile-security-framework-mobsf
```

- Kargatu APK/IPA
- Analizatu emaitzak
- Konpondu detektatutako ahultasunak

### 10.2 Frida Erabili

```bash
npm install -g frida
```

- Aplikazioaren dinamika aztertu
- Hook funtzioak

## 11. Kodearen Obfuskazioa

### 11.1 ProGuard (Android)

```bash
android {
    buildTypes {
        release {
            minifyEnabled true
            shrinkResources true
            proguardFiles getDefaultProguardFile('proguard-android.txt'), 'proguard-rules.pro'
        }
    }
}
```

### 11.2 Hermes (JavaScript)

```javascript
// App.js
import { enableHermes } from 'react-native/Libraries/Utilities/hermescfg';

enableHermes();
```

## 12. Test Case-ak

### 12.1 Unit Tests

```javascript
describe('Security Tests', () => {
  it('should not store password in plain text', () => {
    const password = 'Test123456';
    const storedPassword = storePassword(password);
    expect(storedPassword).not.toBe(password);
  });
});
```

### 12.2 E2E Tests

```javascript
describe('Security E2E', () => {
  it('should enforce rate limiting', async () => {
    for (let i = 0; i < 20; i++) {
      await loginAttempt('test', 'wrong');
    }
    const response = await loginAttempt('test', 'wrong');
    expect(response.status).toBe(429);
  });
});
```

## 13. Berrikuspen eta Eguneratzeak

- Bertsio bakoitzean segurtasun probak egin
- Hilabetero MobSF scan-ak
- dependentziak eguneratu
- Segurtasun bulletins jarraitu

## Erreferentziak

- OWASP Mobile Top 10: https://owasp.org/www-project-mobile-top-10/
- OWASP Mobile Security Testing Guide: https://owasp.org/www-project-mobile-security-testing-guide/
- React Native Security: https://reactnative.dev/docs/security