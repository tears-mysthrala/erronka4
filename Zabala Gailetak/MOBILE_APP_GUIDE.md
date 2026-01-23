# Zabala Gailetak Android Aplikazioaren Gida

**Plataforma:** Android (Native)
**Teknologia:** Kotlin 2.0, Jetpack Compose, Material 3
**Arkitektura:** Clean Architecture + MVI
**Bertsioa:** 2.0 (Migration Complete)

---

## 1. Orokorra

Zabala Gailetak Android aplikazioa enpresako langileentzat diseinatutako tresna da, HR kudeaketa errazteko.

### 1.1 Ezaugarri Nagusiak
- ✅ Autentifikazioa (JWT + MFA TOTP)
- ✅ Langileen Zerrenda eta Xehetasunak
- ✅ Oporren Kudeaketa (Egutegia eta Solicitudak)
- ✅ Dokumentuen Kudeaketa (Igoera eta Deskarga)
- ✅ Nominak (PDF Ikuslea)
- ✅ Barne Txata (Real-time)

---

## 2. Arkitektura Teknikoa

Aplikazioak Clean Architecture printzipioak jarraitzen ditu:

- **Domain Layer:** Business logic, Models, Use Cases.
- **Data Layer:** Repository implementations, Retrofit (API), Room (Local DB).
- **Presentation Layer:** Jetpack Compose UI, ViewModels (MVI pattern).

### 2.1 Tech Stack
- **DI:** Hilt
- **Networking:** Retrofit + OkHttp
- **Async:** Coroutines + Flow
- **JSON:** Kotlinx Serialization
- **Image Loading:** Coil

---

## 3. Garapen Konfigurazioa

### 3.1 Aurrebaldintzak
- Android Studio (Koala edo berriagoa)
- JDK 17
- Android SDK (API 34)

### 3.2 Instalazioa
1. Ireki `Zabala Gailetak/android-app` proiektua Android Studio-n.
2. Sinkronizatu Gradle fitxategiak.
3. Konfiguratu `local.properties`:
   ```properties
   sdk.dir=/your/path/to/android/sdk
   BASE_URL="http://192.168.20.10/api"
   ```

---

## 4. Segurtasuna

### 4.1 Datuen Biltegiratzea
- **EncryptedSharedPreferences:** Tokenak eta informazio sentikorra gordetzeko.
- **Certificate Pinning:** API komunikazioa zifratzeko eta MITM erasoak ekiditeko.

### 4.2 Autentifikazioa
- **MFA:** TOTP bidezko bigarren faktorea.
- **Biometria:** Hatza-marka edo Face ID integrazioa saioa hasteko.

---

## 5. Komando Baliagarriak

```bash
# Build Debug APK
./gradlew assembleDebug

# Run Unit Tests
./gradlew test

# Run Lint
./gradlew lint
```

---
**Mantentzailea:** Zabala Gailetak Mobile Team