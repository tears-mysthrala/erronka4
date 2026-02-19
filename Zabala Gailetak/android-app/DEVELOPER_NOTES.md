# Ohar Teknikoak Garatzaileentzat

**Migrazioa Kotlin 2.0 + AGP 9 + KSP**
**Data:** 2026-01-23

---

## 🔧 Tooling Aldaketak

### Gradle Configuration Cache

**Egoera:** Orain **AKTIBATUTA** (lehenago desgaituta)

```properties
# gradle.properties
org.gradle.configuration-cache=true
org.gradle.configuration-cache.problems=warn
```

**Eragina:**
- ⚡ Build-ak ~% 50 azkarragoak cache hit-aren kasuan
- ⚠️ Abisu batzuk erakutsi ditzake cache-friendly ez diren zereginetan
- 🔧 Arazo iraunkorrak badaude, aldi baterako desaktibatu

**Komando erabilgarriak:**
```bash
# Configuration cache baliogabetu
./gradlew --stop
rm -rf .gradle/configuration-cache

# Cache arazoak ikusi
./gradlew :app:assembleDebug --configuration-cache-problems=warn
```

### KSP vs KAPT

**Aldaketa:** Kode sortzaile guztiak KAPT-etik → KSP-ra migratuta

| Alderdia | KAPT (lehenago) | KSP (orain) |
|---------|--------------|-------------|
| Sortutako path-a | `build/generated/source/kapt/` | `build/generated/ksp/` |
| Abiadura | Oinarria | % 30-40 azkarragoa |
| Incremental | Mugatua | Euskarri hobea |
| Kotlin 2 bateragarritasuna | Degradatua | Natiboa |

**Garapenerako ondorioak:**

1. **Clean build-ak garrantzitsuagoak:**
   - Akats arraroak ikusten badituzu klaseak ez direla aurkitzen, egin clean
   - KSP inkrementala da baina batzuetan berregite osoa behar du

2. **IDE indexing:**
   - Lehen aldiz clean egin ondoren denbora gehiago har dezake
   - Android Studio-k path berriak indexatu behar ditu

3. **Debugging:**
   - Sortutako kodea path berrian dago
   - `Find Usages` anotatutako klaseetan lehen aldiz denbora gehiago har dezake

**Kodearen sorkuntza egiaztatu:**
```bash
# Hilt osagaiak
find app/build/generated/ksp -name "*Hilt*"

# Room inplementazioak
find app/build/generated/ksp -name "*_Impl.kt"

# Ikusi sortutako guztia
ls -R app/build/generated/ksp/debug/kotlin/
```

---

## 🎨 Compose Compiler

### Lehenago (Kotlin 1.9.x)

```kotlin
// app/build.gradle.kts
composeOptions {
    kotlinCompilerExtensionVersion = "1.5.14"
}
```

### Orain (Kotlin 2.0.x)

```kotlin
// build.gradle.kts (root)
plugins {
    id("org.jetbrains.kotlin.plugin.compose") version "2.0.21" apply false
}

// app/build.gradle.kts
plugins {
    id("org.jetbrains.kotlin.plugin.compose")
}

// composeOptions dagoeneko EZ da beharrezkoa
```

**Abantailak:**
- ✅ Automatikoa: plugin-ak bertsio bateragarria hautatzen du
- ✅ Ez da behar compatibility matrix-ez kezkatu
- ✅ Optimizazio hobea Kotlin 2.0-rekin

**Compose Compiler Reports (aukerakoa):**

Compose metrikak ikusteko:
```kotlin
// app/build.gradle.kts
android {
    kotlinOptions {
        freeCompilerArgs += listOf(
            "-P",
            "plugin:androidx.compose.compiler.plugins.kotlin:reportsDestination=" +
                "${layout.buildDirectory.get()}/compose_metrics",
            "-P",
            "plugin:androidx.compose.compiler.plugins.kotlin:metricsDestination=" +
                "${layout.buildDirectory.get()}/compose_metrics"
        )
    }
}
```

Txostenak ikusi:
```bash
./gradlew :app:assembleDebug
open app/build/compose_metrics/
```

---

## 📦 Mendekotasunak: Breaking Changes

### Retrofit 2.9 → 2.11

**Aldaketa txikiak, gehienbat bateragarriak**

- Kotlin Coroutines euskarria hobetua
- `null` kudeaketa hobea response-etan
- Serialization plugin-ak eguneratuta

**Egiaztatu:**
```kotlin
// Custom converter-ak erabiltzen badituzu, egiaztatu sinadura
interface CustomConverter : Converter.Factory {
    // API-a berdina izan beharko litzateke, baina berrikusi motak
}
```

### Coroutines 1.7.3 → 1.9.0

**Aldaketa garrantzitsuak:**

1. **`Flow.collect` zorrotzagoa:**
   ```kotlin
   // Lehenago: agian ez zuen behar bezala bertan behera utzi
   flow.collect { value ->
       // ...
   }

   // Orain: bertan behera uztea aurreikusgarriagoa
   // (dagoen kodeak funtzionatu beharko luke, baina testak modu desberdinean joka dezakete)
   ```

2. **`TestDispatcher` API-a aldatu da:**
   ```kotlin
   // Lehenago (agian zure testetan)
   @Before
   fun setup() {
       Dispatchers.setMain(TestCoroutineDispatcher())
   }

   // Orain (gomendatua)
   @Before
   fun setup() {
       Dispatchers.setMain(StandardTestDispatcher())
   }
   ```

3. **`runTest` sendoagoa:**
   ```kotlin
   @Test
   fun myTest() = runTest {
       // Denbora birtualaren kudeaketa hobea
       // Lehenago detektatu ez zituen leak-ak detekta ditzake
   }
   ```

### Compose BOM 2024.02 → 2024.12

**Osagai berri eskuragarriak:**

```kotlin
// DatePicker modernoa
import androidx.compose.material3.DatePicker
import androidx.compose.material3.rememberDatePickerState

@Composable
fun MyDatePicker() {
    val state = rememberDatePickerState()
    DatePicker(state = state)
}

// TimeInput
import androidx.compose.material3.TimeInput
import androidx.compose.material3.rememberTimePickerState

// ModalBottomSheet hobetua
import androidx.compose.material3.ModalBottomSheet
```

**Breaking change txikiak:**
- `BottomSheetScaffold`-ek parametro aukerako berriak ditu
- `Snackbar` iraupen koherenteagoak
- `TextField` outline rendering hobetua

---

## 🔒 Segurtasuna: Keystore vs security-crypto

### Gomendatutako Patroia

**SharedPreferences zifratuetarako:**

```kotlin
// KeystoreManager.kt
object KeystoreManager {
    private const val KEY_ALIAS = "app_master_key"
    private const val KEYSTORE = "AndroidKeyStore"

    fun getOrCreateKey(): SecretKey {
        val keyStore = KeyStore.getInstance(KEYSTORE).apply { load(null) }

        return if (keyStore.containsAlias(KEY_ALIAS)) {
            keyStore.getKey(KEY_ALIAS, null) as SecretKey
        } else {
            KeyGenerator.getInstance(KeyProperties.KEY_ALGORITHM_AES, KEYSTORE)
                .apply {
                    init(KeyGenParameterSpec.Builder(
                        KEY_ALIAS,
                        KeyProperties.PURPOSE_ENCRYPT or KeyProperties.PURPOSE_DECRYPT
                    )
                        .setBlockModes(KeyProperties.BLOCK_MODE_GCM)
                        .setEncryptionPaddings(KeyProperties.ENCRYPTION_PADDING_NONE)
                        .setKeySize(256)
                        .setUserAuthenticationRequired(false) // Edo true biometric nahi baduzu
                        .build()
                    )
                }
                .generateKey()
        }
    }
}

// CryptoHelper.kt
object CryptoHelper {
    private const val TRANSFORMATION = "AES/GCM/NoPadding"

    fun encrypt(plaintext: String): EncryptedData {
        val cipher = Cipher.getInstance(TRANSFORMATION)
        cipher.init(Cipher.ENCRYPT_MODE, KeystoreManager.getOrCreateKey())

        val ciphertext = cipher.doFinal(plaintext.toByteArray(Charsets.UTF_8))
        val iv = cipher.iv

        return EncryptedData(
            ciphertext = Base64.encodeToString(ciphertext, Base64.NO_WRAP),
            iv = Base64.encodeToString(iv, Base64.NO_WRAP)
        )
    }

    fun decrypt(encrypted: EncryptedData): String {
        val cipher = Cipher.getInstance(TRANSFORMATION)
        val iv = Base64.decode(encrypted.iv, Base64.NO_WRAP)
        val ciphertext = Base64.decode(encrypted.ciphertext, Base64.NO_WRAP)

        cipher.init(
            Cipher.DECRYPT_MODE,
            KeystoreManager.getOrCreateKey(),
            GCMParameterSpec(128, iv)
        )

        val plaintext = cipher.doFinal(ciphertext)
        return String(plaintext, Charsets.UTF_8)
    }
}

data class EncryptedData(val ciphertext: String, val iv: String)

// SecureStorage.kt (DataStore-rekin)
class SecureStorage(context: Context) {
    private val dataStore = context.dataStore

    suspend fun saveSecure(key: String, value: String) {
        val encrypted = CryptoHelper.encrypt(value)
        dataStore.edit { prefs ->
            prefs[stringPreferencesKey("${key}_data")] = encrypted.ciphertext
            prefs[stringPreferencesKey("${key}_iv")] = encrypted.iv
        }
    }

    suspend fun getSecure(key: String): String? {
        val prefs = dataStore.data.first()
        val ciphertext = prefs[stringPreferencesKey("${key}_data")] ?: return null
        val iv = prefs[stringPreferencesKey("${key}_iv")] ?: return null

        return try {
            CryptoHelper.decrypt(EncryptedData(ciphertext, iv))
        } catch (e: Exception) {
            Log.e("SecureStorage", "Decryption failed", e)
            null
        }
    }
}
```

**Zifratze kodearen testeak:**

```kotlin
@Test
fun `encrypt and decrypt returns original value`() = runTest {
    val original = "sensitive_token_123"
    val encrypted = CryptoHelper.encrypt(original)
    val decrypted = CryptoHelper.decrypt(encrypted)

    assertEquals(original, decrypted)
    assertNotEquals(original, encrypted.ciphertext)
}

@Test
fun `different encryptions of same value produce different ciphertexts`() = runTest {
    val value = "test"
    val encrypted1 = CryptoHelper.encrypt(value)
    val encrypted2 = CryptoHelper.encrypt(value)

    // IV desberdinak = ciphertext desberdinak (segurtasuna)
    assertNotEquals(encrypted1.ciphertext, encrypted2.ciphertext)
    assertNotEquals(encrypted1.iv, encrypted2.iv)

    // Baina biak jatorrizkora deszifratzen dira
    assertEquals(value, CryptoHelper.decrypt(encrypted1))
    assertEquals(value, CryptoHelper.decrypt(encrypted2))
}
```

---

## 🧪 Testing Kotlin 2.0-rekin

### TestDispatcher Aldaketak

```kotlin
// MainDispatcherRule.kt (eguneratua)
class MainDispatcherRule(
    private val dispatcher: TestDispatcher = StandardTestDispatcher()
) : TestWatcher() {
    override fun starting(description: Description) {
        Dispatchers.setMain(dispatcher)
    }

    override fun finished(description: Description) {
        Dispatchers.resetMain()
    }
}

// Testetan erabilera
class ViewModelTest {
    @get:Rule
    val mainDispatcherRule = MainDispatcherRule()

    @Test
    fun `test with coroutines`() = runTest {
        // Erabili advanceUntilIdle() argiago
        viewModel.loadData()
        advanceUntilIdle()

        // Assertions
        assertEquals(expected, viewModel.state.value)
    }
}
```

### Room Testing

```kotlin
// KSP-rekin, ziurtatu eskema sortzen dela
android {
    defaultConfig {
        javaCompileOptions {
            annotationProcessorOptions {
                arguments["room.schemaLocation"] = "$projectDir/schemas"
            }
        }
    }

    // KSP-rekin:
    ksp {
        arg("room.schemaLocation", "$projectDir/schemas")
    }
}
```

---

## 🚀 Performance Aholkuak

### 1. Erabili Build Analyzer

```bash
# Build analisiarekin
./gradlew :app:assembleDebug --profile --scan

# Android Studio-n:
# View → Tool Windows → Build Analyzer
```

### 2. Parallel Execution

```properties
# gradle.properties (dagoeneko konfiguratuta)
org.gradle.parallel=true
org.gradle.workers.max=8  # CPU-aren arabera doitu
```

### 3. Incremental Compilation

```properties
# gradle.properties (dagoeneko konfiguratuta)
kotlin.incremental=true
ksp.incremental=true
```

### 4. Avoid Full Rebuilds

```kotlin
// Honen ordez:
./gradlew clean build

// Egin:
./gradlew :app:assembleDebug

// Clean beharrezkoa denean soilik (akats arraroak)
```

---

## 📱 Bateragarritasuna minSdk 24

### API-ak kontuz

**Java Time API** (desugaring behar du):
```kotlin
// Erabiltzen baduzu:
import java.time.LocalDateTime
import java.time.ZonedDateTime

// Gehitu app/build.gradle.kts-n:
android {
    compileOptions {
        isCoreLibraryDesugaringEnabled = true
    }
}
dependencies {
    coreLibraryDesugaring("com.android.tools:desugar_jdk_libs:2.1.4")
}
```

**Notification Channels** (API 26+):
```kotlin
// Egiaztatu bertsioa erabili aurretik
if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.O) {
    createNotificationChannel()
}
```

**Adaptive Icons** (API 26+):
```xml
<!-- res/mipmap-anydpi-v26/ic_launcher.xml -->
<!-- Eman fallback API < 26-rako -->
```

---

## 🔍 Debugging Aholkuak

### AGP 9 Aldaketak

```bash
# Ikusi eskuragarri dauden zereginak
./gradlew tasks --all | grep app

# Mendekotasunen zuhaitza
./gradlew :app:dependencies

# Ikusi aplikatutako konfigurazioa
./gradlew :app:properties
```

### KSP Sortutako Kodea

```bash
# Berregeneratu kodea
./gradlew clean :app:kspDebugKotlin

# Ikusi sorkuntza log-ak
./gradlew :app:kspDebugKotlin --info | grep KSP
```

### Compose Debugging

```kotlin
// Ikusi recomposition-ak Logcat-en
import androidx.compose.runtime.SideEffect

@Composable
fun MyComposable() {
    SideEffect {
        Log.d("Compose", "MyComposable recomposed")
    }
    // ...
}
```

---

## 📚 Baliabide Gehigarriak

- [Kotlin 2.0 Migration Guide](https://kotlinlang.org/docs/kotlin-2-migration-guide.html)
- [AGP 9 Known Issues](https://developer.android.com/studio/releases/gradle-plugin#9-0-0-known-issues)
- [KSP vs KAPT Performance](https://kotlinlang.org/docs/ksp-why-ksp.html)
- [Android Keystore Best Practices](https://developer.android.com/privacy-and-security/keystore)

---

**Azken eguneraketa:** 2026-01-23
**Kontaktua:** Ikusi [AGENTS.md](../AGENTS.md) proiektuaren konbentzioentzat
