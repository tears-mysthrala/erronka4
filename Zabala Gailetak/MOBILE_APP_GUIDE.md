# Zabala Gailetak Mobile App Guide

**Platform:** iOS & Android  
**Technology:** React Native  
**Version:** 1.0  
**Status:** Production Ready  

---

## Table of Contents

1. [Overview](#1-overview)
2. [Features](#2-features)
3. [Architecture](#3-architecture)
4. [Development Setup](#4-development-setup)
5. [Building & Deployment](#5-building--deployment)
6. [Security Implementation](#6-security-implementation)
7. [Testing](#7-testing)
8. [Troubleshooting](#8-troubleshooting)

---

## 1. Overview

### 1.1 Purpose

Zabala Gailetak mobile application provides a secure, user-friendly platform for browsing products and placing orders from iOS and Android devices.

### 1.2 Key Features

- ✅ Secure authentication with MFA
- ✅ Product catalog
- ✅ Order placement
- ✅ Biometric authentication (fingerprint/Face ID)
- ✅ Offline support (future)
- ✅ Push notifications (future)
- ✅ Order tracking (future)

### 1.3 Target Platforms

| Platform | Minimum Version | Market Share |
|----------|---------------|--------------|
| Android | API Level 21 (5.0 Lollipop) | 99%+ |
| iOS | iOS 13+ | 95%+ |

---

## 2. Features

### 2.1 Authentication

#### 2.1.1 Login Flow

```
1. User enters credentials
   ↓
2. API validates username/password
   ↓
3. If MFA enabled → MFA screen
   ↓
4. Enter TOTP code (6 digits)
   ↓
5. Authentication complete
   ↓
6. Navigate to Products
```

#### 2.1.2 Biometric Authentication

**Supported Methods:**
- Android: Fingerprint
- iOS: Touch ID / Face ID

**Implementation:**
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

### 2.2 Product Catalog

**Features:**
- Grid layout with product cards
- Product images
- Prices in EUR
- Product descriptions
- Category filtering (future)

### 2.3 Order Placement

**Order Form Fields:**
- Quantity (1-10)
- Customer name
- Email address
- Shipping address

**Validation:**
- Real-time validation
- Error messages
- Price calculation
- Order confirmation

### 2.4 User Dashboard

**Features:**
- Statistics display
- Quick actions
- MFA management
- Logout

---

## 3. Architecture

### 3.1 Component Structure

```
App (Root)
├── AuthProvider
├── NavigationContainer
│   ├── Stack Navigator
│   │   ├── Login Screen
│   │   ├── MFA Screen
│   │   ├── Products Screen
│   │   ├── Order Screen
│   │   └── Dashboard Screen
```

### 3.2 State Management

**Authentication Context:**
- User state
- Auth status
- MFA status
- Login/Logout functions

**Local State:**
- Form inputs
- Product list
- Order data
- Loading states
- Error messages

### 3.3 Navigation

**Navigation Stack:**

1. **Login** → (authenticated) → **Products**
2. **Login** → (MFA required) → **MFA** → **Products**
3. **Products** → **Order** (with product data)
4. **Order** → (success) → **Products**
5. **Products** → **Dashboard**
6. **Dashboard** → **Logout** → **Login**

---

## 4. Development Setup

### 4.1 Prerequisites

#### 4.1.1 Software Requirements

| Software | Version | Purpose |
|----------|---------|---------|
| Node.js | 18+ | Runtime |
| npm | 9+ | Package manager |
| React Native CLI | 12+ | CLI tools |
| Android Studio | Hedgehog | Android dev |
| Xcode | 14+ | iOS dev (macOS only) |

#### 4.1.2 Hardware Requirements

| Platform | CPU | RAM | Storage |
|----------|-----|-----|---------|
| Android | 4 cores | 8GB | 50GB |
| iOS (macOS) | 4 cores | 8GB | 50GB |

### 4.2 Installation Steps

#### 4.2.1 Clone Repository

```bash
git clone <repository-url>
cd erronkak/Zabala Gailetak/src/mobile
```

#### 4.2.2 Install Dependencies

```bash
npm install
```

#### 4.2.3 iOS Setup (macOS only)

```bash
# Install pods
cd ios
pod install
cd ..

# Open Xcode
open ios/ZabalaGailetak.xcworkspace
```

#### 4.2.4 Android Setup

```bash
# Create local.properties
echo "sdk.dir=$HOME/Android/Sdk" > android/local.properties

# Build and run
npx react-native run-android
```

### 4.3 Configuration

#### 4.3.1 Environment Variables

Create `.env` file:

```env
# API Configuration
API_URL=http://localhost:3000/api

# Security
ENABLE_MFA=true
ENABLE_BIOMETRICS=true
```

#### 4.3.2 App Configuration

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

## 5. Building & Deployment

### 5.1 Development Build

#### 5.1.1 Run on Android

```bash
npm run android
```

#### 5.1.2 Run on iOS

```bash
npm run ios
```

#### 5.1.3 Run with Metro

```bash
npm start
# In another terminal:
npm run android  # or npm run ios
```

### 5.2 Production Build

#### 5.2.1 Android APK

```bash
cd android
./gradlew assembleRelease
```

Output: `android/app/build/outputs/apk/release/app-release.apk`

#### 5.2.2 Android AAB (Google Play)

```bash
cd android
./gradlew bundleRelease
```

Output: `android/app/build/outputs/bundle/release/app-release.aab`

#### 5.2.3 iOS IPA (App Store)

```bash
# In Xcode:
# 1. Select "Any iOS Device" as target
# 2. Product → Archive
# 3. Distribute App
```

### 5.3 App Store Deployment

#### 5.3.1 Google Play Store

**Steps:**
1. Create app in Google Play Console
2. Upload AAB file
3. Fill store listing
4. Provide screenshots
5. Submit for review

**Required Assets:**
- Icon: 512x512px
- Feature graphic: 1024x500px
- Screenshots: Various sizes
- Banner: 180x120px

#### 5.3.2 Apple App Store

**Steps:**
1. Create app in App Store Connect
2. Upload IPA via Xcode or Transporter
3. Fill app information
4. Provide screenshots
5. Submit for review

**Required Assets:**
- Icon: 1024x1024px
- Screenshots: Various sizes
- App preview (optional)

---

## 6. Security Implementation

### 6.1 Authentication Security

#### 6.1.1 Secure Token Storage

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

#### 6.1.2 Network Security

**HTTPS Only:**

```javascript
const apiClient = axios.create({
  baseURL: 'https://api.zabala-gailetak.com',
  timeout: 10000,
  httpsAgent: new https.Agent({
    rejectUnauthorized: true
  })
});
```

**Certificate Pinning (Optional):**

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

### 6.2 Data Security

#### 6.2.1 Input Validation

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

#### 6.2.2 Output Encoding

```javascript
import DOMPurify from 'dompurify';

const sanitizeInput = (input) => {
  return DOMPurify.sanitize(input);
};
```

### 6.3 Permissions

#### 6.3.1 Android Permissions

```xml
<uses-permission android:name="android.permission.INTERNET" />
<uses-permission android:name="android.permission.USE_FINGERPRINT" />
<uses-permission android:name="android.permission.USE_BIOMETRIC" />
```

#### 6.3.2 iOS Permissions

```xml
<key>NSFaceIDUsageDescription</key>
<string>Autentikazio biometrikoa erabiltzeko</string>
```

### 6.4 Deep Link Security

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

## 7. Testing

### 7.1 Unit Tests

**Setup:**

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

### 7.2 E2E Tests

**Setup (Detox):**

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

### 7.3 Test Execution

```bash
# Unit tests
npm test

# E2E tests
npm run test:e2e

# Test coverage
npm run test:coverage
```

---

## 8. Troubleshooting

### 8.1 Common Issues

#### 8.1.1 Build Errors

**Issue:** Gradle sync fails

**Solution:**
```bash
cd android
./gradlew clean
./gradlew build
```

#### 8.1.2 Metro Bundler Issues

**Issue:** Metro bundler not starting

**Solution:**
```bash
npm start -- --reset-cache
```

#### 8.1.3 iOS Build Errors

**Issue:** CocoaPods not working

**Solution:**
```bash
cd ios
pod deintegrate
pod install
```

### 8.2 Performance Issues

#### 8.2.1 Slow Navigation

**Solution:** Use React Navigation's lazy loading

```javascript
const Products = React.lazy(() =>
  import('./screens/ProductsScreen')
);
```

#### 8.2.2 Large Bundle Size

**Solution:**
- Use code splitting
- Optimize images
- Remove unused dependencies

### 8.3 Security Issues

#### 8.3.1 Token Not Persisting

**Solution:** Check storage permissions

```javascript
// Android
<uses-permission android:name="android.permission.READ_EXTERNAL_STORAGE" />

// iOS
Check Info.plist for proper keys
```

---

## Appendix A: Quick Reference

### A.1 Commands

| Command | Description |
|---------|-------------|
| `npm start` | Start Metro bundler |
| `npm run android` | Run on Android |
| `npm run ios` | Run on iOS |
| `npm test` | Run tests |
| `npm run lint` | Lint code |
| `npm run android:release` | Build Android release |

### A.2 Environment Variables

| Variable | Description | Default |
|----------|-------------|---------|
| `API_URL` | API endpoint | http://localhost:3000/api |
| `ENABLE_MFA` | Enable MFA | true |
| `ENABLE_BIOMETRICS` | Enable biometrics | true |
| `LOG_LEVEL` | Logging level | info |

### A.3 Useful Links

- **React Native Docs:** https://reactnative.dev
- **React Navigation:** https://reactnavigation.org
- **Axios Docs:** https://axios-http.com
- **Detox:** https://wix.github.io/Detox/

---

**Document Version:** 1.0  
**Last Updated:** 2024-01-08  
**Maintained By:** Zabala Gailetak Mobile Team