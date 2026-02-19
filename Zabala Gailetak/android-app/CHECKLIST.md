# ✅ Checklist Post-Migrazioa Kotlin 2.0

**Urrats hauek ordenan exekutatu aldaketak egin ondoren**

---

## 📋 Pre-Build (prestaketa)

- [ ] **Itxi Android Studio** (irekita badago)
- [ ] **Egin pull** azken aldaketekin
- [ ] **Nabigatu direktoriora:**
  ```bash
  cd Zabala\ Gailetak/android-app
  ```

---

## 🔍 Egiaztapen Automatikoa

- [ ] **Exekutatu egiaztapen script-a:**
  ```bash
  ./post-migration-check.sh
  ```
  - ✅ Dena ongi badoa: jarraitu
  - ❌ Huts egiten badu: berrikusi irteerak eta zuzendu jarraitu aurretik

---

## 🏗️ Build Hasiera

- [ ] **Ireki Android Studio**
- [ ] **Invalidate Caches:**
  - `File` → `Invalidate Caches / Restart`
  - Hautatu "Invalidate and Restart"
  - Itxaron berrabiarazte arte

- [ ] **Gradle Sync:**
  - Itxaron sinkronizazio automatikora
  - Edo eskuz: `File` → `Sync Project with Gradle Files`
  - ⏱️ Lehen aldiz denbora gehiago beharko du (AGP 9, Kotlin 2.0.21, etab. deskargatzeko)

- [ ] **Rebuild Project:**
  - `Build` → `Rebuild Project`
  - ⏱️ Hasierako build-a motelago izango da (~5-10 min)

---

## 🧪 Testing

- [ ] **Unit Tests:**
  ```bash
  ./gradlew :app:testDebugUnitTest
  ```

- [ ] **Konpilatu Release:**
  ```bash
  ./gradlew :app:assembleRelease
  ```

- [ ] **Sortu API 24 emuladorea** (bateragarritasun berria):
  - Tools → Device Manager → Create Device
  - API Level: 24 (Android 7.0)
  - Exekutatu app emuladorean

---

## 🔒 Segurtasuna (KRITIKOA)

- [ ] **Bilatu security-crypto erabilera:**
  ```bash
  grep -r "EncryptedSharedPreferences\|EncryptedFile\|MasterKey" app/src/
  ```

- [ ] **Emaitzak badaude:**
  - [ ] Irakurri "Migración de security-crypto" atala [MIGRATION_KOTLIN_2.0.md](MIGRATION_KOTLIN_2.0.md)-n
  - [ ] Inplementatu Android Keystore alternatiboa
  - [ ] Probatu datuen migrazioa (zifratutako datuak badaude)
  - [ ] Exekutatu segurtasun testak

- [ ] **Emaitzarik EZ badago:**
  - ✅ Ez da ekintzarik behar

---

## 📊 Hobekuntzen Balidazioa

- [ ] **Egiaztatu build speed:**
  ```bash
  # Build profiling-ekin
  ./gradlew :app:assembleDebug --profile

  # Ikusi sortutako txostena
  open build/reports/profile/profile-*.html
  ```

- [ ] **Konparatu denborak:**
  - Idatzi incremental build denbora
  - Egin aldaketa txiki bat kodean
  - Berriro konpilatu eta konparatu (% 30-40 azkarragoa izan beharko luke)

---

## 🎯 Azken Egiaztapenak

- [ ] **Auto-completion funtzionatzen du** Android Studio-n:
  - [ ] `@HiltViewModel`-ekin anotatutako klaseak
  - [ ] Room DAOak (KSP-k sortutakoak)
  - [ ] Inject constructors

- [ ] **Ez dago konpilazio akatsik**:
  - [ ] Activities/Fragments
  - [ ] ViewModels Hilt-ekin
  - [ ] Room DAOs/Entities
  - [ ] Retrofit interfaces

- [ ] **App-a behar bezala funtzionatzen du:**
  - [ ] Login/autentifikazioa
  - [ ] Pantailen arteko nabigazioa
  - [ ] Datuen persistentzia (Room)
  - [ ] API deiak (Retrofit)
  - [ ] Biometria (badagokio)

---

## 📝 Arazoak Jakinarazi

Arazoak aurkitzen badituzu:

1. **Egiaztatu Android Studio bertsioa:**
   - Help → About
   - **Ladybug (2024.2)** edo berriagoa izan behar du

2. **Egiaztatu JDK:**
   - File → Project Structure → SDK Location
   - **JDK 17** izan behar du

3. **Garbitu eta rebuild:**
   ```bash
   ./gradlew clean
   ./gradlew :app:assembleDebug --info
   ```

4. **Berrikusi log xehatuak:**
   - Build Output Android Studio-n
   - Edo terminalean: `./gradlew :app:assembleDebug --stacktrace`

5. **Kontsultatu dokumentazioa:**
   - [MIGRATION_KOTLIN_2.0.md](MIGRATION_KOTLIN_2.0.md) - Gida osoa
   - [UPGRADE_SUMMARY.md](UPGRADE_SUMMARY.md) - Aldaketen laburpena

---

## ✅ Amaituta!

Check guztiak markatuta badaude, migrazioa arrakastatsua izan da.

**Hurrengo urratsak:**
- Jarraitu garapen normalarekin
- Gozatu KSP-rekin build azkarragoekin
- Monitorizatu abiaduraren hobekuntzak `--profile`-rekin

---

**Azken eguneraketa:** 2026-01-23
