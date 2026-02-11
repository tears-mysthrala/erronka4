# 📱 Android Aplikazioaren Argitalpen Gida

**Zabala Gailetak HR Aplikazioa - Argitalpen Kudeaketa**

---

## 🎯 Ikuspegi Orokorra

Gida honek Android aplikazioa GitHub Actions erabiliz eraikitzea, sinatzea eta argitaratzea azaltzen du.

### Argitalpen Lan-fluxuaren Ezaugarriak

- ✅ Bertsio etiketen bidezko eraikuntza automatizatua (`v1.0.0`, `v1.2.3`, etab.)
- ✅ Eskuzko eraikuntzak GitHub Actions UI bidez
- ✅ APK sinadura argitalpen keystore-arekin
- ✅ GitHub Releases APK deskargaketaekin
- ✅ SHA-256 egiaztapeneko checksum-ak
- ✅ Debug eta Release aldaerak
- ✅ Bertsio kudeaketa automatikoa

---

## 🔐 Konfigurazioa: Android Keystore

### 1. urratsa: Argitalpen Keystore-a Sortu

**Proiektu bakoitzeko behin bakarrik beharrezkoa**

```bash
# Navigatu android-app direktoriora
cd "Zabala Gailetak/android-app"

# Sortu keystore (elkarrekintza moduan)
keytool -genkey -v \
  -keystore zabala-gailetak-release.keystore \
  -alias zabala-gailetak-hrapp \
  -keyalg RSA \
  -keysize 2048 \
  -validity 10000 \
  -storepass YOUR_STORE_PASSWORD \
  -keypass YOUR_KEY_PASSWORD

# Galdera hauek egingo zaizkizu:
# - Zure izena: Zabala Gailetak
# - Unitate organizatiboa: IT Saila
# - Erakundea: Zabala Gailetak
# - Hiria: [Zure hiria]
# - Estatua: Euskal Herria
# - Herrialde kodea: ES
```

**Adibidez elkarrekintza galderak:**
```
What is your first and last name?
  [Unknown]:  Zabala Gailetak
What is the name of your organizational unit?
  [Unknown]:  IT Department
What is the name of your organization?
  [Unknown]:  Zabala Gailetak S.L.
What is the name of your City or Locality?
  [Unknown]:  Donostia
What is the name of your State or Province?
  [Unknown]:  Gipuzkoa
What is the two-letter country code for this unit?
  [Unknown]:  ES
Is CN=Zabala Gailetak, OU=IT Department, O=Zabala Gailetak S.L., L=Donostia, ST=Gipuzkoa, C=ES correct?
  [no]:  yes
```

### 2. urratsa: Egiaztatu Keystore-a

```bash
# Zerrendatu keystore edukia
keytool -list -v -keystore zabala-gailetak-release.keystore

# Espero den irteera:
# Alias name: zabala-gailetak-hrapp
# Creation date: [data]
# Entry type: PrivateKeyEntry
# Certificate chain length: 1
# ...
```

### 3. urratsa: Bihurtu Keystore Base64-ra

```bash
# Bihurtu base64-ra GitHub Secrets-erako
base64 -w 0 zabala-gailetak-release.keystore > keystore.base64

# Edo macOS-en:
base64 -i zabala-gailetak-release.keystore -o keystore.base64
```

### 4. urratsa: Gehitu GitHub Secrets

Joan GitHub biltegira → Settings → Secrets and variables → Actions

Gehitu sekretu hauek:

| Sekretuaren Izena | Balioa | Azalpena |
|-------------------|--------|----------|
| `ANDROID_KEYSTORE_BASE64` | [keystore.base64 edukia] | Base64-an kodetutako keystore fitxategia |
| `ANDROID_KEYSTORE_PASSWORD` | YOUR_STORE_PASSWORD | Keystore pasahitza |
| `ANDROID_KEY_ALIAS` | zabala-gailetak-hrapp | Key alias izena |
| `ANDROID_KEY_PASSWORD` | YOUR_KEY_PASSWORD | Key pasahitza |

**Segurtasun Oharrak:**
- ⚠️ **INOIZ ez commit-eatu keystore fitxategia git-era**
- ⚠️ **Egin keystore fitxategiaren babeskopia segurua** (enkriptatutako biltegia)
- ⚠️ **Gorde pasahitzak pasahitz kudeatzailean**
- ⚠️ Keystore-a galduz gero, ezin duzu aplikazioa Play Store-n eguneratu

---

## 🚀 Bertsio Berri Bat Argitaratzea

### 1. metodoa: Argitalpen Automatikoa (Etiketa Push)

**Onena ekoizpen argitalpenetarako**

```bash
# 1. Eguneratu bertsioa build.gradle.kts-en (aukerakoa - workflow-a automatikoki eguneratzen du)
cd "Zabala Gailetak/android-app/app"
# Editatu versionCode eta versionName

# 2. Commit aldaketak
git add .
git commit -m "chore: Prepare release v1.0.0"

# 3. Sortu eta push-atu bertsio etiketa
git tag -a v1.0.0 -m "Release version 1.0.0"
git push origin v1.0.0

# 4. GitHub Actions automatikoki egingo du:
# - Debug eta release APK-ak eraiki
# - Release APK-a sinatu
# - GitHub Release bat sortu deskarga esteketekin
# - Checksum-ak sortu
```

### 2. metodoa: Argitalpen Eskuzkoa (GitHub UI)

**Onena probak edo patch argitalpenetarako**

1. Joan GitHub biltegira → Actions
2. Aukeratu **"Android App Release"** workflow-a
3. Klik egin **"Run workflow"** beheragotuan
4. Bete parametroak:
   - **Version name:** `1.0.0` (bertsio semantikoa)
   - **Version code:** `1` (zenbaki osoa, eraikuntza bakoitzarekin inkrementatzen da)
   - **Release notes:** Aldaketen azalpen laburra
5. Klik egin **"Run workflow"**
6. Itxaron eraikuntza osotu arte (~5-10 minutu)
7. Deskargatu APK "Artifacts" ataletik

---

## 📦 Eraikuntza Artifact-ak

Eraikuntza bakoitzaren ondoren, artifact hauek eskuragarri daude:

### Debug APK
- **Fitxategi izena:** `zabala-gailetak-hrapp-vX.X.X-debug.apk`
- **Xedea:** Garapena eta probak
- **Sinadura:** Debug keystore (automatikoki sortua)
- **Epea:** 30 egun

### Release APK (Sinatu Gabea)
- **Fitxategi izena:** `zabala-gailetak-hrapp-vX.X.X-release-unsigned.apk`
- **Xedea:** Eskuzko sinadura edo probak
- **Sinadura:** Sinatu gabe
- **Epea:** 30 egun
- **Sortua:** Eskuzko workflow exekuzioetan soilik

### Release APK (Sinatua)
- **Fitxategi izena:** `zabala-gailetak-hrapp-vX.X.X-signed.apk`
- **Xedea:** Ekoizpen banaketa
- **Sinadura:** Argitalpen keystore-a
- **Epea:** 90 egun
- **Sortua:** Etiketa push-ean soilik

### Checksum-ak
- **Fitxategi izena:** `checksums.txt`
- **Xedea:** APK osotasuna egiaztatu
- **Edukia:** APK guztien SHA-256 hash-ak
- **Epea:** 90 egun

---

## 📥 Aplikazioa Instalatzea

### Erabiltzaile Amaierarako

1. **Deskargatu APK:**
   - Joan [Releases](https://github.com/tears-mysthrala/erronka4/releases)-era
   - Bilatu azken bertsioa
   - Deskargatu `zabala-gailetak-hrapp-vX.X.X-signed.apk`

2. **Egiaztatu Osotasuna (Aukerakoa baina Gomendatua):**
   ```bash
   # Deskargatu checksums.txt
   # Kalkulatu deskargatutako APK-aren SHA-256
   sha256sum zabala-gailetak-hrapp-v1.0.0-signed.apk
   
   # Konparatu checksums.txt-rekin
   # Bat etorri beharko lukete zehazki
   ```

3. **Gaitu Iturburu Ezezagunak:**
   - Settings → Security → Install from Unknown Sources
   - Edo Settings → Apps → Special access → Install unknown apps
   - Gaitu zure nabigatzaile edo fitxategi kudeatzailearentzat

4. **Instalatu APK:**
   - Sakatu deskargatutako APK fitxategia
   - Jarraitu instalazioko galderak
   - Eman beharrezko baimenak

5. **Abiarazi Aplikazioa:**
   - Bilatu "Zabala Gailetak HR" app drawer-an
   - Saioa hasi zure kredentzialekin

### Garatzaile/Probatzaileentzako

```bash
# Instalatu ADB bidez (USB debugging gaituta)
adb install zabala-gailetak-hrapp-v1.0.0-signed.apk

# Edo ordeztu instalazio existentea
adb install -r zabala-gailetak-hrapp-v1.0.0-signed.apk

# Desinstalatu
adb uninstall com.zabalagailetak.hrapp
```

---

## 🔍 Eraikuntza Egiaztatzea

### 1. Egiaztatu APK Sinadura

```bash
# apksigner erabiliz (Android SDK)
apksigner verify --verbose zabala-gailetak-hrapp-v1.0.0-signed.apk

# Espero den irteera:
# Verifies
# Verified using v1 scheme (JAR signing): true
# Verified using v2 scheme (APK Signature Scheme v2): true
# Number of signers: 1
```

### 2. Egiaztatu Keystore Batuketa

```bash
# Lortu ziurtagiriaren hatz-marka APK-tik
keytool -printcert -jarfile zabala-gailetak-hrapp-v1.0.0-signed.apk

# Konparatu keystore-eko ziurtagiriarekin
keytool -list -v -keystore zabala-gailetak-release.keystore

# SHA-256 hatz-markak bat etorri beharko lukete
```

### 3. Egiaztatu APK Edukia

```bash
# Zerrendatu fitxategiak APK-an
unzip -l zabala-gailetak-hrapp-v1.0.0-signed.apk | grep -E "(dex|so|xml)"

# Atera eta aztertu manifest-a
unzip zabala-gailetak-hrapp-v1.0.0-signed.apk AndroidManifest.xml
aapt dump xmltree zabala-gailetak-hrapp-v1.0.0-signed.apk AndroidManifest.xml
```

---

## 🐛 Arazoak Konpontzea

### Eraikuntzak Huts Egiten Du: "Gradle bertsioa"

**Errorea:**
```
Gradle version X.X is required. Current version is Y.Y.
```

**Konponbidea:**
Eguneratu `gradle/wrapper/gradle-wrapper.properties`:
```properties
distributionUrl=https\://services.gradle.org/distributions/gradle-8.7-bin.zip
```

### Eraikuntzak Huts Egiten Du: "Java bertsioa"

**Errorea:**
```
Android Gradle plugin requires Java 17 to run. You are currently using Java 11.
```

**Konponbidea:**
Workflow-ak Java 21 erabiltzen du. Egiaztatu garapen ingurune lokala:
```bash
java -version
# Java 21 izan beharko litzateke
```

### APK Sinadurak Huts Egiten Du

**Errorea:**
```
Failed to sign APK: Keystore password incorrect
```

**Konponbideak:**
1. Egiaztatu GitHub Secrets zuzen konfiguratuta daudela
2. Egiaztatu pasahitzak ez duela arazoak sortzen dituzten karaktere berezirik
3. Berriz sortu keystore-a pasahitza galdu bada

### Bertsio Kode Gatazkak

**Errorea:**
```
INSTALL_FAILED_VERSION_DOWNGRADE
```

**Konponbidea:**
Argitalpen bakoitzak bertsio kode altuagoa izan behar du:
```kotlin
// app/build.gradle.kts-en
versionCode = 2  // Inkrementatu aurrekoarekiko
versionName = "1.0.1"
```

### "Iturburu Ezezagunak" Ez Dago Eskuragarri

Android 8.0+, ezarpena aplikazioko da:
- Settings → Apps → Special access → Install unknown apps
- Aukeratu zure nabigatzaile/fitxategi kudeatzailea
- Gaitu "Allow from this source"

---

## 📊 Bertsio Kudeaketa

### Bertsio Semantikoa

Jarraitu [SemVer](https://semver.org/): `MAJOR.MINOR.PATCH`

- **MAJOR:** Aldaketa haustreak, diseinu nagusia
- **MINOR:** Ezaugarri berriak, atzeraka bateragarria
- **PATCH:** Akatsen konponketak, hobekuntza txikiak

**Adibideak:**
- `1.0.0` - Hasierako argitalpena
- `1.1.0` - Dokumentu modulua gehituta
- `1.1.1` - Saioa hasteko akatsa konponduta
- `2.0.0` - UI diseinu osoa aldatuta

### Bertsio Kodea

Zenbaki osoa argitalpen bakoitzarekin inkrementatzen dena:

```
v1.0.0 → versionCode: 1
v1.0.1 → versionCode: 2
v1.1.0 → versionCode: 3
v2.0.0 → versionCode: 4
```

**Automatikoa:** Workflow-ak git commit kontagailua erabiltzen du zehaztugabe dagoenean

---

## 🔒 Segurtasun Praktika Onenak

### Keystore Kudeaketa

1. **Babeskopia Estrategia:**
   - Gorde keystore biltegi hodei enkriptatuan (1Password, Bitwarden)
   - Mantendu lineaz kanpoko babeskopia USB enkriptatuan
   - Dokumentatu pasahitz berreskuratze prozesua
   - Probak egin berreskuratze prozesuarekin urtero

2. **Sarbide Kontrola:**
   - Mugatu kestore sarbidea argitalpen kudeatzaileei soilik
   - Erabili keystore desberdinak debug vs release-rako
   - Biratu gakoak konprometituta badaude (aplikazio zerrenda berria behar du)

3. **GitHub Secrets:**
   - Inoiz ez imprimatu sekretuak log-etetan
   - Biratu sekretuak agerian utziz gero
   - Erabili erakunde sekretuak talde sarbiderako
   - Berrikusi sekretuen sarbidea maiz

### APK Banaketa

1. **Bide Ofizialak Soilik:**
   - GitHub Releases (lehenetsia)
   - Fitxategi zerbitzari barnekoa (bigarren mailakoa)
   - Inoiz ez erabili fitxategi partekatze gune publikoak

2. **Egiaztapena:**
   - Bete eskaini SHA-256 checksum-ak
   - Sinatu argitalpen oharrak GPG gakoarekin (aukerakoa)
   - Erabili HTTPS deskarga esteka guztietarako

3. **Komunikazioa:**
   - Iragarri argitalpenak bide ofizialen bidez
   - Sartu changelog eta arazo ezagunak
   - Eman laguntzarekin harremanetan jartzeko informazioa

---

## 📝 Argitalpen Egiaztapen Zerrenda

Inprimatu eta markatu argitalpen bakoitza aurretik:

### Argitalpen Aurretik
- [ ] Proba guztiak gaindituta (unitatea, integrazioa, UI)
- [ ] Akats kritikorik ez issue tracker-ean
- [ ] Kodea berrikusia eta onartua
- [ ] Bertsio zenbakiak eguneratuta
- [ ] Changelog idatzita
- [ ] Argitalpen oharrak prestatuta
- [ ] Keystore eskuragarri
- [ ] GitHub Secrets egiaztatuta

### Eraikuntza
- [ ] Sortu bertsio etiketa
- [ ] Push-atu etiketa workflow-a abiarazteko
- [ ] Monitorizatu eraikuntza aurrerapena
- [ ] Deskargatu sinatutako APK
- [ ] Egiaztatu APK sinadura
- [ ] Probatu APK gailu fisikoan
- [ ] Egiaztatu checksum-ak bat datozela

### Argitalpen Ondoren
- [ ] GitHub Release argitaratuta
- [ ] Argitalpen oharrak osatuta
- [ ] Taldeari jakinarazita
- [ ] Dokumentazioa eguneratuta
- [ ] Erabiltzaileei jakinarazita (email, iragarkia)
- [ ] Monitorizatu crash txostenak
- [ ] Erantzun erabiltzaileen iritziei

---

## 🎓 Ikaskuntza Baliabideak

- [Android App Signing](https://developer.android.com/studio/publish/app-signing)
- [GitHub Actions for Android](https://github.com/actions/setup-java)
- [Gradle Build Configuration](https://developer.android.com/studio/build)
- [ProGuard/R8](https://developer.android.com/studio/build/shrink-code)
- [Semantic Versioning](https://semver.org/)

---

## 📞 Laguntza

**Galderak argitalpenei buruz?**
- Barnekoa: it@zabalagailetak.com
- GitHub Issues: [Salatu arazoa](https://github.com/tears-mysthrala/erronka4/issues)

---

**Azken Eguneratzea:** 2026-02-06  
**Mantentzailea:** Zabala Gailetak DevTeam  
**Workflow Bertsioa:** 1.0.0
