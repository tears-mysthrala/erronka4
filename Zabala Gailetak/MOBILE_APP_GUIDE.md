# Zabala Gailetak Android Aplikazioaren Gida

**Plataforma:** Android (Native)
**Teknologia:** Kotlin 2.0, Jetpack Compose, Material 3
**Arkitektura:** Clean Architecture + MVI
**Bertsioa:** 2.0 (Migrazioa Osatua)

---

## 1. Orokorra

Zabala Gailetak Android aplikazioa enpresako langileentzat diseinatutako tresna da, HR kudeaketa errazteko.

### 1.1 Ezaugarri Nagusiak
- ✅ Autentifikazioa (JWT + MFA TOTP)
- ✅ Langileen Zerrenda eta Xehetasunak
- ✅ Oporren Kudeaketa (Egutegia eta Eskaerak)
- ✅ Dokumentuen Kudeaketa (Igoera eta Deskarga)
- ✅ Nominak (PDF Ikusteko)
- ✅ Barne Txata (Denbora errealean)

---

## 2. Arkitektura Teknikoa

Aplikazioak Clean Architecture printzipioak jarraitzen ditu:

- **Domain Geruza:** Negozio logika, Modeloak, Erabilera Kasuak.
- **Data Geruza:** Repository inplementazioak, Retrofit (API), Room (Datu-base Lokala).
- **Presentation Geruza:** Jetpack Compose UI, ViewModels (MVI eredua).

### 2.1 Tech Stack
- **DI:** Hilt
- **Networking:** Retrofit + OkHttp
- **Async:** Coroutines + Flow
- **JSON:** Kotlinx Serialization
- **Irudi Karga:** Coil

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
- **EncryptedSharedPreferences:** Token-ak eta informazio sentikorra gordetzeko.
- **Certificate Pinning:** API komunikazioa zifratzeko eta MITM erasoak saihesteko.

### 4.2 Autentifikazioa
- **MFA:** TOTP bidezko bigarren faktorea.
- **Biometria:** Hatz-marka edo Face ID integrazioa saioa hasteko.

---

## 5. Komando Erabilgarriak

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
