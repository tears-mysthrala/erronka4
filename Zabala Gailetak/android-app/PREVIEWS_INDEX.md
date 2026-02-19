# 📱 Android Preview-ak - Dokumentazio Indizea

## 🚀 Hasi hemen!

### Denbora gutxi baduzu ⚡
→ Irakurri [QUICK_PREVIEW_START.md](QUICK_PREVIEW_START.md) (5 minutu)

### Gida osoa nahi baduzu 📖
→ Irakurri [PREVIEWS_GUIDE.md](PREVIEWS_GUIDE.md) (15 minutu)

### Xehetasun teknikoak behar badituzu 🔍
→ Irakurri [PREVIEWS_IMPLEMENTATION_SUMMARY.md](PREVIEWS_IMPLEMENTATION_SUMMARY.md) (10 minutu)

---

## 📚 Eskuragarri dagoen Dokumentazioa

### 1. [QUICK_PREVIEW_START.md](QUICK_PREVIEW_START.md)
**Norentzat**: Azkar hasi nahi duten erabiltzaileak
**Denbora**: ~5 minutu
**Edukia**:
- Preview-ak ikusteko 3 urrats
- Botoien eta ikonoen kokapena
- Preview panelaren aukerak
- Lasterbide erabilgarriak
- Pro aholkuak

✅ **Irakurri hau lehenengo inoiz preview-ak erabili ez badituzu**

---

### 2. [PREVIEWS_GUIDE.md](PREVIEWS_GUIDE.md)
**Norentzat**: Gida osoa eta erreferentzia
**Denbora**: ~15 minutu
**Edukia**:
- Preview-en azalpena
- Non dauden preview-ak
- Preview-ak erabiltzeko 3 modu
- Preview-en ezaugarriak
- Gaiaren konfigurazioa
- Preview motak (sinplea, anitza)
- Aholku erabilgarriak
- Arazo-konponketa
- Kanpoko erreferentziak

✅ **Irakurri hau dena xehetasunez ulertzeko**

---

### 3. [PREVIEWS_IMPLEMENTATION_SUMMARY.md](PREVIEWS_IMPLEMENTATION_SUMMARY.md)
**Norentzat**: Aldatu denaren laburpen teknikoa
**Denbora**: ~10 minutu
**Edukia**:
- Laburpen exekutiboa
- Estatistikak
- Egindako aldaketak (pantailaka)
- Sortutako dokumentazio fitxategiak
- Inplementatutako ezaugarriak
- Nola erabili Android Studio-n
- Preview-ekin pantailak
- Preview-en egiaztapena
- Hurrengo urrats aukerakorrak
- Fitxategien kokapena

✅ **Irakurri hau aldatu dena ikusteko**

---

## 🔧 Egiaztapen Script-a

### [verify-previews.sh](verify-previews.sh)
Egiaztatu fitxategi guztiek preview-ak konfiguratuta dituztela.

**Erabilera:**
```bash
./verify-previews.sh
```

**Espero den emaitza:**
```
🎉 Bikain! Fitxategi guztiek preview-ak konfiguratuta dituzte
```

---

## 📍 Preview-ak dituzten Fitxategiak

| Fitxategia | Bidea | Preview-ak | Preview |
|---------|------|----------|---------|
| LoginScreen | `auth/LoginScreen.kt` | 3 | ✅ |
| DashboardScreen | `dashboard/DashboardScreen.kt` | 2 | ✅ |
| DocumentsScreen | `documents/DocumentsScreen.kt` | 2 | ✅ |
| PayslipsScreen | `payslips/PayslipsScreen.kt` | 4 | ✅ |
| ProfileScreen | `profile/ProfileScreen.kt` | 1 | ✅ |
| VacationDashboardScreen | `vacation/VacationDashboardScreen.kt` | 2 | ✅ |
| NewVacationRequestScreen | `vacation/NewVacationRequestScreen.kt` | 3 | ✅ |

**Guztira**: 7 fitxategi, 17 preview, 100% estaldura ✅

---

## 🎓 Gomendatutako Ikasketa Gida

### Maila 1: Oinarrizkoa (30 min)
1. Irakurri [QUICK_PREVIEW_START.md](QUICK_PREVIEW_START.md)
2. Ireki Android Studio
3. Ireki `LoginScreen.kt`
4. Egin klik "Preview"-n
5. Probatu kodea aldatzea eta aldaketak denbora errealean ikustea

### Maila 2: Tartekoa (1 ordu)
1. Irakurri [PREVIEWS_GUIDE.md](PREVIEWS_GUIDE.md)
2. Ireki preview-ak dituen fitxategi bakoitza
3. Esperimentatu aldaera desberdinekin
4. Probatu Split View modua (kodea + preview)
5. Aldatu kodea eta behatu aldaketak

### Maila 3: Aurreratua (pertsonala)
1. Irakurri [PREVIEWS_IMPLEMENTATION_SUMMARY.md](PREVIEWS_IMPLEMENTATION_SUMMARY.md)
2. Gehitu preview gehiago osagaiei
3. Sortu preview parametrizatuak
4. Integratu UI testing-ekin

---

## ❓ Maiz egiten diren galderak

### Nondik hasi?
→ Ireki [QUICK_PREVIEW_START.md](QUICK_PREVIEW_START.md)

### Nola ireki preview bat?
→ "Preview-ak ikusteko 3 urrats" atala [QUICK_PREVIEW_START.md](QUICK_PREVIEW_START.md)-n

### Zein pantailetan daude preview-ak?
→ Irakurri goiko "Preview-ak dituzten Fitxategiak" taula

### Preview-a ez da agertzen, zer egin?
→ Joan "Arazo ohikoak" atalera [PREVIEWS_GUIDE.md](PREVIEWS_GUIDE.md)-n

### Zenbat preview daude?
→ Guztira: **17 preview** **7 pantailatan** **100% estaldurarekin** ✅

### Klik egin dezaket preview-ean?
→ Ez, preview-ak estatikoak dira. Erabili emuladorea proba interaktiboetarako.

### Nola egiaztatu dena ongi dagoela?
→ Exekutatu `./verify-previews.sh`

---

## 🎯 Erabilera Kasuak

### "Login pantaila azkar bistaratu nahi dut"
1. Ireki `auth/LoginScreen.kt`
2. Egin klik "Preview"-n
3. 3 aldaera ikusiko dituzu: normala, kargatzen, errorea

### "Egoera desberdinak probatu behar ditut"
1. Ireki preview-ak dituen edozein fitxategi
2. Bilatu `@Preview(name = "...")`
3. Egoera desberdinekin aldaera anitzak ikusiko dituzu

### "Diseinua aldatu nahi dut aplikazioa exekutatu gabe"
1. Egin klik "Split Editor"-en (View → Split Editor)
2. Ireki preview-ak dituen fitxategia
3. Editatu kodea ezkerrean
4. Ikusi aldaketak denbora errealean eskuinean

### "Dena funtzionatzen duela egiaztatu behar dut"
1. Exekutatu `./verify-previews.sh`
2. Egiaztatu irteera "100%" erakusten duela

---

## 📊 Estatistikak

- **Estalitako pantailak**: 7/7 (100%)
- **Preview guztira**: 17 aldaera
- **Dokumentazioa**: 4 fitxategi
- **Balidazio script-ak**: 1
- **Hasteko denbora**: < 5 minutu

---

## 🔗 Esteka erabilgarriak

### Biltegi honetan
- [README.md](README.md) - Proiektuaren informazio orokorra
- [MIGRATION_KOTLIN_2.0.md](MIGRATION_KOTLIN_2.0.md) - Kotlin 2.0-ra migrazioa
- [DEVELOPER_NOTES.md](DEVELOPER_NOTES.md) - Garatzaileentzat oharrak

### Kanpokoak
- [Android Compose Preview Documentation](https://developer.android.com/jetpack/compose/tooling/previews)
- [Jetpack Compose Best Practices](https://developer.android.com/jetpack/compose/hands-on)
- [Material 3 Design](https://m3.material.io/)

---

## 📞 Laguntza

Galderak edo arazoak dituzu?

1. **Lehenik**, kontsultatu "Arazo-konponketa" atala [PREVIEWS_GUIDE.md](PREVIEWS_GUIDE.md)-n
2. **Ondoren**, exekutatu `./verify-previews.sh` egoera egiaztatzeko
3. **Azkenik**, berrikusi `.kt` fitxategiak behar bezala konfiguratutako preview-en adibideak ikusteko

---

## 📝 Laburpen azkarra

| Zeregina | Dokumentua | Denbora |
|-------|-----------|--------|
| Azkar hasi | QUICK_PREVIEW_START.md | 5 min |
| Gida osoa | PREVIEWS_GUIDE.md | 15 min |
| Xehetasun teknikoak | PREVIEWS_IMPLEMENTATION_SUMMARY.md | 10 min |
| Egoera egiaztatu | `./verify-previews.sh` | < 1 min |

---

**Gozatu Preview-ekin garatzen! 🎉**

Azken eguneraketa: Otsaila 3, 2026
