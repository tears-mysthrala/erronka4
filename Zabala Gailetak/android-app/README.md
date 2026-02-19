# Zabala Gailetak HR - Android App

Zabala Gailetak-en giza baliabideen kudeaketa sistemaren Android aplikazio mugikorra.

## ⚡ Stack Eguneratua (2026-01-23)

- **Gradle**: 8.10.2
- **Android Gradle Plugin**: 8.7.3
- **Kotlin**: 2.0.21 (Compose plugin ofizialarekin)
- **KSP**: 2.0.21-1.0.28 (KAPT-etik migratuta)
- **Min SDK**: 24 (Android 7.0) - bateragarritasuna zabalduta
- **Target SDK**: 35 (Android 15)
- **JDK**: 17

## 🚀 Teknologiak

- **Hizkuntza**: Kotlin 2.0.21
- **UI**: Jetpack Compose (BOM 2024.12.01) + Material 3
- **Arkitektura**: Clean Architecture + MVI
- **DI**: Hilt 2.54 (KSP-rekin)
- **Networking**: Retrofit 2.11.0 + OkHttp 4.12.0
- **Local DB**: Room 2.6.1 (KSP-rekin)
- **Async**: Coroutines 1.9.0 + Flow
- **Segurtasuna**: Credential Manager API 1.5.0, Biometric 1.2.0, Android Keystore

> ⚠️ **Oharra**: `androidx.security:security-crypto` kendu egin da (zaharkituta). Ikusi [MIGRATION_KOTLIN_2.0.md](MIGRATION_KOTLIN_2.0.md) migrazioa egiteko.

## 📋 Eskakizunak

- **Android Studio**: Koala (2024.1) edo berriagoa
- **JDK**: 17
- **Android SDK**: 35
- **Gradle**: 8.10.2 (wrapper-ean sartuta)

## 🏗️ Proiektuaren Setup

### Lehen aldiz (edo eguneraketa ondoren)

1. Klonatu biltegia
2. Ireki proiektua Android Studio-n
3. **Garrantzitsua**: Bertsio zaharretik bazatoz, exekutatu migrazio script-a:
   ```bash
   cd android-app
   ./post-migration-check.sh
   ```
4. Android Studio-n: **File → Invalidate Caches / Restart**
5. Sync Gradle fitxategiak (AGP 9-rekin lehen sinkronizazioak denbora hartu dezake)
6. Konfiguratu emuladorea edo gailu fisikoa
7. Exekutatu app-a

### Kotlin 2.0 / AGP 9 eguneraketa ondoren

Pull egin ondoren arazoak izaten badituzu:
- Exekutatu `./post-migration-check.sh` konfigurazioa egiaztatzeko
- Garbitu cache-ak: `./gradlew clean`
- Baliogabetu Android Studio cache-ak
- Kontsultatu [MIGRATION_KOTLIN_2.0.md](MIGRATION_KOTLIN_2.0.md) xehetasun osorako

## 📁 Proiektuaren Egitura

```
app/
├── src/main/
│   ├── java/com/zabalagailetak/hrapp/
│   │   ├── HrApplication.kt                  # Application klase
│   │   │
│   │   ├── data/                             # Datu geruza
│   │   │   ├── local/                        # Datu lokalak (Room)
│   │   │   ├── remote/                       # Urruneko datuak (Retrofit)
│   │   │   └── repository/                   # Repository inplementazioak
│   │   │
│   │   ├── di/                               # Menpekotasun injekzioa
│   │   │   ├── AppModule.kt
│   │   │   ├── NetworkModule.kt
│   │   │   ├── DatabaseModule.kt
│   │   │   └── RepositoryModule.kt
│   │   │
│   │   ├── domain/                           # Dominio geruza
│   │   │   ├── model/                        # Dominio modeloak
│   │   │   ├── repository/                   # Repository interfazeak
│   │   │   └── usecase/                      # Use case-ak
│   │   │
│   │   ├── presentation/                     # Aurkezpen geruza
│   │   │   ├── MainActivity.kt
│   │   │   ├── navigation/                   # Nabigazioa
│   │   │   ├── ui/                           # UI osagaiak & pantailak
│   │   │   └── viewmodel/                    # ViewModels
│   │   │
│   │   ├── security/                         # Segurtasun utilitateak
│   │   └── util/                             # Utilitateak
│   │
│   ├── res/                                  # Baliabideak
│   │   ├── values/
│   │   ├── drawable/
│   │   └── xml/
│   │
│   └── AndroidManifest.xml
│
└── build.gradle.kts
```

## 🔧 API Konfigurazioa

API endpoint-a `build.gradle.kts`-en konfiguratzen da:

- **Debug**: `http://10.0.2.2:8080/api/` (localhost emuladoretik)
- **Release**: `http://zabalagailetak.rf.gd/api/` (InfinityFree)

Garapenean URL-a aldatzeko, editatu `API_BASE_URL` aldagaia debug buildType-an.

## 🧪 Testing

```bash
# Unit testak
./gradlew test

# Instrumented testak
./gradlew connectedAndroidTest

# Lint egiaztapena
./gradlew lint
```

## 🔒 Segurtasuna

- ✅ Network Security Config (produkzioan HTTPS soilik)
- ✅ Certificate pinning
- ✅ EncryptedSharedPreferences datu sentikorrentzat
- ✅ Credential Manager API passkey-rentzat
- ✅ Biometric autentifikazioa
- ✅ ProGuard/R8 ofuskazioa release-an
- ✅ Cleartext traffic-ik ez

## 📱 Inplementatutako Funtzionalitate

- [ ] Autentifikazioa (Login/Logout)
- [ ] MFA (TOTP)
- [ ] Passkey autentifikazioa
- [ ] Langileen kudeaketa
- [ ] Opor eskaerak
- [ ] Nominen kontsulta
- [ ] Dokumentuen kudeaketa
- [ ] Barne txata
- [ ] Kexen sistema
- [ ] Push jakinarazpenak

## 🚀 Build & Deploy

### Debug Build

```bash
./gradlew assembleDebug
```

### Release Build

```bash
./gradlew assembleRelease
```

APK-a sortuko da hemen: `app/build/outputs/apk/release/app-release.apk`

### Signing

Release-an app-a sinatzeko:

1. Sortu keystore
2. Konfiguratu signing `build.gradle.kts`-en
3. Build release

## 🎨 Android Studio-n Preview-ak

App honek pantaila nagusi guztientzat **Compose Preview-ak** ditu, aplikazioa exekutatu gabe UI-a bistaratzeko aukera emanez.

### Preview-etarako Sarbide Azkarra

- ✅ **LoginScreen**: 3 aldaera (normala, kargatzen, errorea)
- ✅ **DashboardScreen**: Ikuspegi nagusia
- ✅ **DocumentsScreen**: Dokumentu zerrenda
- ✅ **PayslipsScreen**: Nominak (2 aldaera)
- ✅ **ProfileScreen**: Erabiltzaile profila
- ✅ **VacationDashboardScreen**: Oporretako dashboard-a
- ✅ **NewVacationRequestScreen**: Eskaera berria (2 aldaera)

**Dokumentazio osoa**: Ikusi [PREVIEWS_GUIDE.md](PREVIEWS_GUIDE.md)

### Preview-ak Egiaztatu

```bash
./verify-previews.sh
```

Script honek fitxategi guztiek preview-ak behar bezala konfiguratuta dituztela egiaztatzen du.

## 📚 Dokumentazioa

- [API Documentation](/docs/API.md)
- [Security Guidelines](/docs/SECURITY.md)
- [Architecture](/docs/ARCHITECTURE.md)
- [Previews Guide](PREVIEWS_GUIDE.md) - **Nola erabili Compose Preview-ak**

## 👥 Lagundu

Ikusi [CONTRIBUTING.md](/docs/CONTRIBUTING.md)

## 📝 Lizentzia

Jabetza - Zabala Gailetak

---

**Bertsioa**: 1.0.0
**Azken eguneraketa**: Otsaila 2026
