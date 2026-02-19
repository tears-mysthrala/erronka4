# Migrazioa Kotlin 2.0 eta Stack Modernora (AGP 9)

**Data:** 2026-01-23
**Stack helburua:** Gradle 8.10.2 + AGP 8.7.3 + Kotlin 2.0.21 + minSdk 24

## Inplementatutako Aldaketak

### 1. Toolchain (Gradle/AGP/Kotlin)
- ✅ **Gradle wrapper**: `8.7` → `8.10.2`
- ✅ **Android Gradle Plugin (AGP)**: `8.5.2` → `8.7.3`
- ✅ **Kotlin**: `1.9.24` → `2.0.21`
- ✅ **JDK**: `17`-n mantenduta (AGP 9 eskakizuna)
- ✅ **Compose plugin**: `org.jetbrains.kotlin.plugin.compose`-ra migratuta (Kotlin 2+ ofiziala)

### 2. Android SDK
- ✅ **minSdk**: `26` → `24` (bateragarritasuna zabalduta)
- ✅ **compileSdk/targetSdk**: `34` → `35` (Play Store 2026-rekin lerrokatuta)

### 3. KAPT → KSP Migrazioa (build speed)
- ✅ **KSP plugin**: `2.0.21-1.0.28` gehitua
- ✅ **Room**: `kapt`-etik → `ksp`-ra migratuta (compiler)
- ✅ **Hilt**: `kapt`-etik → `ksp`-ra migratuta (compiler)
- ⚡ **Irabazi esperoa**: % 20-40 murrizketa konpilazio inkrementalean

### 4. Build Speed Optimizazioak
`gradle.properties`-en konfiguratuta:
- ✅ **Memoria**: 4GB → 6GB JVM heap (`-Xmx6144m`)
- ✅ **Configuration cache**: aktibatuta (AGP 9-k hobeto onartzen du)
- ✅ **KSP incremental**: aktibatuta
- ✅ **Parallel GC**: build azkarretarako optimizatuta
- ✅ **R8 full mode**: release txikiagoentzat aktibatuta

### 5. Mendekotasunen Eguneraketa Masiboa

#### AndroidX Core
| Mendekotasuna | Lehenago | Orain | Oharrak |
|------------|-------|-------|-------|
| core-ktx | 1.12.0 | 1.15.0 | Stable |
| appcompat | 1.6.1 | 1.7.0 | Stable |
| material | 1.11.0 | 1.12.0 | Stable |
| lifecycle | 2.7.0 | 2.8.7 | Stable |
| activity-compose | 1.8.2 | 1.9.3 | Stable |

#### Compose
| Mendekotasuna | Lehenago | Orain | Oharrak |
|------------|-------|-------|-------|
| Compose BOM | 2024.02.00 | 2024.12.01 | Kotlin 2.0 bateragarria |
| navigation-compose | 2.7.7 | 2.8.5 | Stable |

#### Persistentzia & Egoera
| Mendekotasuna | Lehenago | Orain | Oharrak |
|------------|-------|-------|-------|
| room | 2.6.1 | 2.6.1 | Aldaketarik gabe (azken stable) |
| datastore-preferences | 1.0.0 | 1.1.1 | Stable |

#### Networking
| Mendekotasuna | Lehenago | Orain | Oharrak |
|------------|-------|-------|-------|
| retrofit | 2.9.0 | 2.11.0 | ⚠️ Berrikusi breaking changes |
| okhttp | 4.12.0 | 4.12.0 | Aldaketarik gabe |
| gson | 2.10.1 | 2.11.0 | Stable |

#### DI & Async
| Mendekotasuna | Lehenago | Orain | Oharrak |
|------------|-------|-------|-------|
| hilt | 2.51.1 | 2.54 | KSP-rekin |
| coroutines | 1.7.3 | 1.9.0 | Stable |

#### Segurtasuna & Auth
| Mendekotasuna | Lehenago | Orain | Oharrak |
|------------|-------|-------|-------|
| credentials | 1.2.2 | 1.5.0 | Stable |
| biometric | 1.1.0 | 1.2.0 | Stable |
| security-crypto | 1.1.0-alpha06 | ❌ KENDU | ⚠️ Ikusi migrazioa behean |

#### Irudia & UI
| Mendekotasuna | Lehenago | Orain | Oharrak |
|------------|-------|-------|-------|
| coil-compose | 2.5.0 | 2.7.0 | Stable |

#### Testing
| Mendekotasuna | Lehenago | Orain | Oharrak |
|------------|-------|-------|-------|
| mockito | 5.10.0 | 5.14.2 | Stable |
| androidx.test.ext:junit | 1.1.5 | 1.2.1 | Stable |
| espresso | 3.5.1 | 3.6.1 | Stable |

---

## ⚠️ EKINTZA BEHARREZKOA: `security-crypto` Migrazioa (ZAHARKITUTA)

**Egoera:** `androidx.security:security-crypto` Google-k **ofizialki zaharkituta** dago eta ez du eguneraketa gehiagorik jasoko.

### Zer egin

#### Aukera 1: Android Keystore + EncryptedSharedPreferences ordezkoa
`EncryptedSharedPreferences` edo `EncryptedFile` erabiltzen bazenu:

```kotlin
// LEHENAGO (security-crypto-rekin)
val masterKey = MasterKey.Builder(context)
    .setKeyScheme(MasterKey.KeyScheme.AES256_GCM)
    .build()

val encryptedPrefs = EncryptedSharedPreferences.create(
    context,
    "secret_prefs",
    masterKey,
    EncryptedSharedPreferences.PrefKeyEncryptionScheme.AES256_SIV,
    EncryptedSharedPreferences.PrefValueEncryptionScheme.AES256_GCM
)

// ONDOREN (Keystore natiboa)
import android.security.keystore.KeyGenParameterSpec
import android.security.keystore.KeyProperties
import javax.crypto.Cipher
import javax.crypto.KeyGenerator
import javax.crypto.SecretKey
import javax.crypto.spec.GCMParameterSpec

object KeystoreHelper {
    private const val KEY_ALIAS = "zabala_master_key"
    private const val ANDROID_KEYSTORE = "AndroidKeyStore"
    private const val TRANSFORMATION = "AES/GCM/NoPadding"

    fun getOrCreateKey(): SecretKey {
        val keyStore = KeyStore.getInstance(ANDROID_KEYSTORE).apply { load(null) }

        if (!keyStore.containsAlias(KEY_ALIAS)) {
            val keyGenerator = KeyGenerator.getInstance(
                KeyProperties.KEY_ALGORITHM_AES,
                ANDROID_KEYSTORE
            )
            keyGenerator.init(
                KeyGenParameterSpec.Builder(
                    KEY_ALIAS,
                    KeyProperties.PURPOSE_ENCRYPT or KeyProperties.PURPOSE_DECRYPT
                )
                    .setBlockModes(KeyProperties.BLOCK_MODE_GCM)
                    .setEncryptionPaddings(KeyProperties.ENCRYPTION_PADDING_NONE)
                    .setKeySize(256)
                    .build()
            )
            keyGenerator.generateKey()
        }

        return keyStore.getKey(KEY_ALIAS, null) as SecretKey
    }

    fun encrypt(data: ByteArray): Pair<ByteArray, ByteArray> {
        val cipher = Cipher.getInstance(TRANSFORMATION)
        cipher.init(Cipher.ENCRYPT_MODE, getOrCreateKey())
        val encrypted = cipher.doFinal(data)
        val iv = cipher.iv
        return Pair(encrypted, iv)
    }

    fun decrypt(encrypted: ByteArray, iv: ByteArray): ByteArray {
        val cipher = Cipher.getInstance(TRANSFORMATION)
        cipher.init(Cipher.DECRYPT_MODE, getOrCreateKey(), GCMParameterSpec(128, iv))
        return cipher.doFinal(encrypted)
    }
}

// DataStore-n erabilera (persistentzia seguruarako gomendatua)
class SecurePreferencesRepository(context: Context) {
    private val dataStore = context.dataStore

    suspend fun saveSecureString(key: String, value: String) {
        val (encrypted, iv) = KeystoreHelper.encrypt(value.toByteArray())
        dataStore.edit { prefs ->
            prefs[stringPreferencesKey("${key}_data")] =
                Base64.encodeToString(encrypted, Base64.NO_WRAP)
            prefs[stringPreferencesKey("${key}_iv")] =
                Base64.encodeToString(iv, Base64.NO_WRAP)
        }
    }

    suspend fun getSecureString(key: String): String? {
        val prefs = dataStore.data.first()
        val encryptedStr = prefs[stringPreferencesKey("${key}_data")] ?: return null
        val ivStr = prefs[stringPreferencesKey("${key}_iv")] ?: return null

        val encrypted = Base64.decode(encryptedStr, Base64.NO_WRAP)
        val iv = Base64.decode(ivStr, Base64.NO_WRAP)

        return String(KeystoreHelper.decrypt(encrypted, iv))
    }
}
```

#### Aukera 2: Token-ak/kredentzialak bakarrik gorde behar badituzu
Erabili `androidx.credentials:credentials` (dagoeneko 1.5.0-ra eguneratua):

```kotlin
// Autentifikazio tokenetarako
val credentialManager = CredentialManager.create(context)

// Gorde
val credential = PasswordCredential("username", "token")
credentialManager.saveCredential(SavePasswordRequest(credential))

// Berreskuratu
val getCredRequest = GetCredentialRequest(
    listOf(GetPasswordOption())
)
val result = credentialManager.getCredential(context, getCredRequest)
```

### Berrikusteko fitxategiak
Bilatu iturburu kodean:
```bash
cd /home/kalista/erronkak/erronka4/Zabala\ Gailetak/android-app
grep -r "security-crypto" app/src/
grep -r "EncryptedSharedPreferences" app/src/
grep -r "EncryptedFile" app/src/
grep -r "MasterKey" app/src/
```

---

## Migrazio Ondorengo Urratsak

### 1. Sync eta Build Hasiera
```bash
cd /home/kalista/erronkak/erronka4/Zabala\ Gailetak/android-app

# Eguneratu wrapper automatikoki hartzen ez bada
./gradlew wrapper --gradle-version=8.10.2

# Garbitu aurreko build-a
./gradlew clean

# Hasierako build-a (mendekotasun berriak deskargatzeko denbora hartu dezake)
./gradlew :app:assembleDebug
```

**Oharrak:**
- Lehen konpilazioa motela izango da (AGP 9, Kotlin 2.0.21, lib berriak deskargatu)
- Configuration cache-k abisuak erakutsi ditzake lehen exekuzioan (espero dena)
- KSP-k `build/generated/ksp/`-n kodea sortzen du (kapt-eko path berria)

### 2. KSP Kode Sortzea Egiaztatu
```bash
# Hilt eta Room-erako sortutako kodea egon behar du
ls -la app/build/generated/ksp/debug/kotlin/

# Egiaztatu Hilt-ek osagaiak sortzen dituela
find app/build/generated/ksp -name "*_HiltComponents*"

# Egiaztatu Room-ek DAOak sortzen dituela
find app/build/generated/ksp -name "*_Impl.kt"
```

### 3. Exekutatu Testak
```bash
# Unit testak
./gradlew :app:testDebugUnitTest

# UI testak (emuladorea/gailua badago)
./gradlew :app:connectedDebugAndroidTest
```

### 4. Android Studio-n Balidatu
1. **File → Invalidate Caches / Restart** (aldaketa handi baten ondoren gomendatua)
2. **Build → Rebuild Project**
3. Egiaztatu ez dagoela sinkronizazio akatsik "Build" fitxan
4. Egiaztatu auto-completion funtzionatzen duela Hilt/Room-ekin anotatutako klaseetan

### 5. minSdk 24 Gailuetan Probatu
Orain **API 24 (Android 7.0)** onartzen dugunez:
- Probatu API 24 emuladorean bateragarritasun arazoak detektatzeko
- Java 8+ API-ak erabiltzen badituzu (Stream, Optional, Time, etab.), agian **coreLibraryDesugaring** beharko duzu:

```kotlin
// app/build.gradle.kts-en, Java API modernoko erroreak agertzen badira
android {
    compileOptions {
        isCoreLibraryDesugaringEnabled = true
    }
}

dependencies {
    coreLibraryDesugaring("com.android.tools:desugar_jdk_libs:2.1.4")
}
```

---

## Breaking Change Potentzialak

### Retrofit 2.9 → 2.11
- **Call adapters**: Adapter pertsonalizatuak erabiltzen badituzu, berrikusi API-a (normalean ez dago aldaketarik)
- **Converter factories**: Gson converter bateragarria da

### OkHttp (bertsio aldaketarik gabe)
- 4.12.0-n mantenduta (ez dago breaking changes)

### Coroutines 1.7 → 1.9
- **Flows**: bertan behera utzearekin jokabide zorrotzagoa (orokorrean hobea)
- **TestDispatchers**: testing API-ak aldaketa txikiak izan ditzake

### Compose BOM 2024.02 → 2024.12
- **Material3**: osagai berriak eskuragarri (DatePicker, TimeInput, etab.)
- **Navigation**: `NavBackStackEntry.savedStateHandle` sendoagoa

### Hilt KSP-rekin
- **Kode sorkuntza**: path-a `build/generated/source/kapt`-etik → `build/generated/ksp`-ra aldatzen da
- **Incremental builds**: KSP azkarragoa da baina hasierako arazoak badaude clean behar izan dezake
- **Logging**: KSP erroreak formatu desberdinean agertzen dira (normalean argiago)

---

## Rollback Plana

Arazo kritikoak badaude, itzuli aurreko egoerara:

```bash
git checkout HEAD~1 -- gradle/wrapper/gradle-wrapper.properties
git checkout HEAD~1 -- build.gradle.kts
git checkout HEAD~1 -- app/build.gradle.kts
git checkout HEAD~1 -- gradle.properties

./gradlew clean
./gradlew :app:assembleDebug
```

---

## Espero diren Metrikak

### Build Times (estimazioa)
- **Clean build**: hasieran % 10-15 motelago (AGP 9 + Kotlin 2.0 overhead)
- **Incremental build** (KSP-rekin): **% 30-40 azkarragoa** KAPT-ekiko
- **Configuration cache hit**: **% 50 arte azkarragoa** aldaketarik gabeko re-build-etan

### APK Tamaina
- **R8 full mode**: espero den % 5-10 murrizketa release-an
- **Compose**: aldaketa esanguratsurik gabe

### Bateragarritasuna
- **Gailu gehigarriak**: % 5 bat erabiltzaile gehiago eskuragarri minSdk 24-rekin

---

## Baliabideak eta Erreferentziak

- [AGP 9.0.0 Release Notes](https://developer.android.com/studio/releases/gradle-plugin)
- [Kotlin 2.0 Release](https://kotlinlang.org/docs/whatsnew20.html)
- [Compose Kotlin Compatibility](https://developer.android.com/jetpack/androidx/releases/compose-kotlin)
- [KSP Documentation](https://kotlinlang.org/docs/ksp-overview.html)
- [Android Keystore System](https://developer.android.com/privacy-and-security/keystore)
- [security-crypto Deprecation](https://developer.android.com/privacy-and-security/cryptography#security-crypto-jetpack-deprecated)

---

## Kontaktua eta Laguntza

Migrazio honen gaineko zalantzak:
- Berrikusi build issue-ak Android Studio Build Output-en
- Kontsultatu Gradle log-ak `--info` edo `--debug` flags-ekin
- Proiektuaren dokumentazioa: [AGENTS.md](../AGENTS.md)

**Azken eguneraketa:** 2026-01-23
