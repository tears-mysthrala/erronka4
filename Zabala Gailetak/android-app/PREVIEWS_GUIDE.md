# 📱 Android Studio Preview Gida

## Zer dira Preview-ak?

Jetpack Compose-ko **Preview-ek** zure osagaiak diseinuaren denboran bistaratzeko aukera ematen dizute aplikazioa exekutatu gabe. UI garatzeko eta probatzeko modu azkar eta eraginkorra da.

## 📍 Non daude Preview-ak

Preview-ak pantaila hauetan konfiguratu ditugu:

### 🔐 Autentifikazioa
- **LoginScreen.kt**
  - `LoginPreview` - Egoera normala
  - `LoginPreviewLoading` - Karga egoera
  - `LoginPreviewError` - Errore mezuarekin

### 📊 Dashboard
- **DashboardScreen.kt**
  - `DashboardScreenPreview` - Dashboard-aren ikuspegi nagusia

### 📄 Dokumentuak
- **DocumentsScreen.kt**
  - `DocumentsScreenPreview` - Dokumentu zerrenda

### 💰 Payslips (Nominak)
- **PayslipsScreen.kt**
  - `PayslipsScreenPreview` - Ikuspegi lehenetsia
  - `PayslipsScreenEmptyPreview` - Egoera hutsa

### 👤 Profila
- **ProfileScreen.kt**
  - `ProfileScreenPreview` - Profil pantaila

### 🏖️ Vacation (Oporrak)
- **VacationDashboardScreen.kt**
  - `VacationDashboardScreenPreview` - Oporretako dashboard-a

- **NewVacationRequestScreen.kt**
  - `NewVacationRequestScreenPreview` - Eskaera formularioa
  - `NewVacationRequestScreenErrorPreview` - Errorearekin formularioa

## 🚀 Nola erabili Preview-ak Android Studio-n

### Aukera 1: Integratutako Preview panela
1. Ireki `@Preview` duen edozein `.kt` fitxategi
2. Egin klik editorearen eskuineko aldeko **Preview** botoian
3. Osagaiaren bistaratzea duen panel bat irekiko da
4. Kodeko aldaketak denbora errealean islatuko dira

### Aukera 2: Gutter Icons
1. Bilatu **aurrebistaren** ikonoa (telefono txikia) ezkerreko ertzean
2. Egin klik preview-a panelean irekitzeko

### Aukera 3: Split View
1. Joan **View** > **Split Editor** goiko menuan
2. Ireki preview-ak dituen fitxategia
3. Kodea ezkerrean eta aurrebista eskuinean ikusiko dituzu

## 🎨 Preview-en Ezaugarriak

- ✅ Aldaera anitzak (egoera desberdinak)
- ✅ Pantaila-atzeko planoa gaituta bistaratzea hobetzeko
- ✅ Gaia behar bezala konfiguratua (ZabalaGaileTakHRTheme)
- ✅ Probetarako datu simulatuak (mock data)
- ✅ Izen deskribatzaileak aldaera bakoitza identifikatzeko

## ⚙️ Gaiaren Konfigurazioa

Preview guztiek **ZabalaGaileTakHRTheme** gaia erabiltzen dute, hauek barne hartzen dituena:
- Zabala Gailetak-en kolore pertsonalizatuak
- Tipografia koherentea
- Gailuaren konfigurazioarekiko gai ilun/argi automatikoa

## 📱 Preview Motak

### Preview Sinplea
```kotlin
@Preview(showBackground = true)
@Composable
fun MyComponentPreview() {
    ZabalaGaileTakHRTheme {
        MyComponent()
    }
}
```

### Preview Anitza (aldaerak)
```kotlin
@Preview(showBackground = true, name = "Light")
@Composable
fun MyComponentLightPreview() { ... }

@Preview(showBackground = true, name = "Dark")
@Composable
fun MyComponentDarkPreview() { ... }
```

## 🔍 Aholku erabilgarriak

1. **Preview-ak eguneratu**: Aldaketak ikusten ez badituzu, sakatu `Ctrl+Alt+B` (edo `Cmd+Option+B` Mac-en) berreraikitzeko
2. **Zoom**: Erabili saguaren gurpila zoom egiteko preview-an
3. **Aztertu**: Pasatu kurtsorea elementuen gainetik padding, tamaina, etab. xehetasunak ikusteko
4. **Interaktibitaterako mugatua**: Preview-ak estatikoak dira, ez dute klik-ik onartzen (erabili emuladorea proba interaktiboetarako)

## 🚨 Arazo-konponketa

### Preview-a ez da agertzen
- Ziurtatu fitxategia gordeta dagoela
- Berreraikitu proiektua: **Build** > **Rebuild Project**
- Egiaztatu funtzioak `@Preview` eta `@Composable` dituela

### Konpilazio errorea
- Egiaztatu `androidx.compose.ui.tooling.preview.Preview` inportatu duzula
- Egiaztatu `ZabalaGaileTakHRTheme` gaia existitzen dela

### Preview oso motela
- Desaktibatu denbora errealeko eguneraketa panelaren aukeretan
- Murriztu osagaiaren konplexutasuna proba azkarretarako

## 📚 Erreferentziak

- [Android Compose Preview Documentation](https://developer.android.com/jetpack/compose/tooling/previews)
- [Jetpack Compose Best Practices](https://developer.android.com/jetpack/compose/hands-on)

---

**Gozatu garapen azkarrarekin Preview-ekin! 🎉**
