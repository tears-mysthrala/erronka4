# 🎬 Hasiera Azkarra - Android Preview-ak

## ⚡ Preview-ak ikusteko 3 urrats

### 1. Urratsa: Ireki Android Studio
Ziurtatu Android proiektua irekita duzula Android Studio-n.

### 2. Urratsa: Ireki fitxategi hauetako bat
```
app/src/main/java/com/zabalagailetak/hrapp/presentation/
├── auth/LoginScreen.kt
├── dashboard/DashboardScreen.kt
├── documents/DocumentsScreen.kt
├── payslips/PayslipsScreen.kt
├── profile/ProfileScreen.kt
└── vacation/
    ├── VacationDashboardScreen.kt
    └── NewVacationRequestScreen.kt
```

### 3. Urratsa: Egin klik "Preview"-n
Editorearen goiko eskuineko aldean, **"Preview"** botoia ikusiko duzu.
Egin klik eta bistaratzea duen panela irekiko da.

---

## 🔍 Preview botoiaren kokapena

```
┌─────────────────────────────────────────────────────────┐
│  LoginScreen.kt                     [Code] [Preview] ← ← ← HEMEN!
├─────────────────────────────────────────────────────────┤
│                                                           │
│  package com.zabalagailetak.hrapp.presentation.auth      │
│                                                           │
│  import androidx.compose.material3.*                     │
│  ...                                                      │
│                                                           │
│  @Composable                                             │
│  fun LoginScreen(...) { ... }                            │
│                                                           │
│  @Preview(showBackground = true, name = "Light")       │
│  @Composable                                             │
│  fun LoginPreview() { ... }                              │
│                                                           │
└─────────────────────────────────────────────────────────┘
```

---

## 🎨 Preview ikonoa ertzean

Telefono txiki baten 📱 ikonoa ere ikusiko duzu ezkerreko ertzean:

```
│  328    @Preview(showBackground = true, name = "Light")      📱
│  329    @Composable
│  330    fun LoginPreview() {
│  331        ZabalaGaileTakHRTheme {
│  332            LoginContent(...)
```

**Egin klik ikono horretan** preview-a zuzenean irekitzeko.

---

## 📱 Preview Panelaren Aukerak

Panela irekita dagoenean:

### Zoom
- **Saguaren gurpila**: Zoom in/out
- **Ctrl + Gurpila**: Zoom azkarragoa

### Aldaera aldatu
Hainbat `@Preview` badaude, zerrendan agertuko dira:
```
□ LoginPreview - Light
□ LoginPreviewLoading - With Loading
□ LoginPreviewError - With Error
```

### Interakzioa (mugatua)
- Preview-ak estatikoak dira
- **Ez dute klik-ik onartzen** (erabili emuladorea proba interaktiboetarako)

### Konfigurazioa
- **Show wireframe**: Erakutsi layout-en ertzak
- **Show grid**: Erakutsi sareta
- **Device**: Aukeratu gailua aurreikusteko

---

## ⚙️ Preview-a Eguneratu

Aldaketak ez badituzu ikusten:

**Aukera 1**: Sakatu `Ctrl+Alt+B` (Rebuild)
**Aukera 2**: Gorde fitxategia (Ctrl+S) eta itxaron
**Aukera 3**: Berrabiarazi Android Studio

---

## 📊 Split Panela (Kodea + Preview)

Kodea eta preview-a alboz albo ikusteko:

1. Joan **View** menura
2. Egin klik **Split Editor**-en
3. Ireki preview-ak dituen fitxategia
4. Kodea ezkerrean eta preview-a eskuinean ikusiko dituzu!

```
┌─────────────────────────┬─────────────────────────┐
│   LoginScreen.kt        │      Preview            │
│                         │                         │
│ @Preview               │   📱 [Login Screen]     │
│ @Composable            │   ┌─────────────────┐   │
│ fun LoginPreview() {   │   │  Egun on        │   │
│   ZabalaGaileTakHRTheme│   │                 │   │
│   LoginContent(...)    │   │ Email:          │   │
│ }                      │   │ ┌──────────────┐│   │
│                         │   │ │              ││   │
│                         │   │ └──────────────┘│   │
│                         │   │                 │   │
│                         │   │ Pasahitza:      │   │
│                         │   │ ┌──────────────┐│   │
│                         │   │ │●●●●●●●●●●●● ││   │
│                         │   │ └──────────────┘│   │
│                         │   │                 │   │
│                         │   │ [    SAIO     ]│   │
│                         │   └─────────────────┘   │
└─────────────────────────┴─────────────────────────┘
```

---

## 🚀 Lasterbide erabilgarriak

| Ekintza | Lasterbidea (Windows/Linux) | Lasterbidea (Mac) |
|--------|----------------------|------------|
| Rebuild | `Ctrl+Alt+B` | `Cmd+Option+B` |
| Gorde | `Ctrl+S` | `Cmd+S` |
| Split Editor | View menua | View menua |
| Bilatu | `Ctrl+F` | `Cmd+F` |

---

## 💡 Pro Aholkuak

1. **Garatu azkar**: Ez duzu aplikazioa emuladorean etengabe exekutatu beharrik
2. **Aldaera anitzak**: `@Preview` bakoitza egoera desberdina da
3. **Mock datuak**: Dagoeneko preview-etan sartuta daude
4. **Gai automatikoa**: Automatikoki aplikatzen da (ZabalaGaileTakHRTheme)

---

## ❓ Arazo ohikoak

### "Ez dut Preview botoia ikusten"
- Ziurtatu fitxategiak `@Preview` duela
- Fitxategiak `@Preview` duen funtzio Composable bat izan behar du

### "Preview-a ez da eguneratzen"
- Gorde fitxategia: `Ctrl+S`
- Rebuild: `Ctrl+Alt+B`
- Itxaron 2-3 segundo

### "Konpilazio errorea preview-an"
- Egiaztatu inportatu duzula: `androidx.compose.ui.tooling.preview.Preview`
- Egiaztatu `ZabalaGaileTakHRTheme` existitzen dela

---

## 📚 Informazio gehiago

- [PREVIEWS_GUIDE.md](PREVIEWS_GUIDE.md) - Gida osoa
- [PREVIEWS_IMPLEMENTATION_SUMMARY.md](PREVIEWS_IMPLEMENTATION_SUMMARY.md) - Aldaketen laburpena
- [README.md](README.md) - Proiektuaren informazio orokorra

---

**Gozatu Preview-ekin garatzen! 🎉**

Galderak dituzu? Irakurri dokumentazio osoa **PREVIEWS_GUIDE.md**-n
