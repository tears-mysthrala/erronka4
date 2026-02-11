# 🚀 Erreferentzia Azkarra - Ingurunea eta Argitalpenak

**Zabala Gailetak HR Ataria - Komando Ezinbestekoak**

---

## 🗄️ InfinityFree Datu-basearen Ingurunea

### Konfigurazioa Lehenengo Aldiz

```bash
# 1. Sartu phpMyAdmin-era
URL: https://cpanel.infinityfree.com → phpMyAdmin
Datu-basea: if0_40982238_zabala_gailetak

# 2. Exekutatu SQL Script-ak (orden honetan)
- mysql_zabala_gailetak_fresh_install.sql  (instalazio garbia)
- infinityfree_patches_v2.sql              (funtzio berrien adabakiak)

# 3. Egiaztatu Instalazioa
SELECT COUNT(*) FROM users;  -- 1 itzuli beharko luke (erabiltzaile administratzailea)
SHOW TABLES;                  -- 20 taula erakutsi beharko lituzke
SHOW TRIGGERS;                -- 3 oporretako trigger erakutsi beharko lituzke
```

### Ingurunearen Konfigurazioa

```env
# Sortu .env fitxategia htdocs/ karpetan
DB_DRIVER=mysql
DB_HOST=sql107.infinityfree.com
DB_NAME=if0_40982238_zabala_gailetak
DB_USER=if0_40982238
DB_PASSWORD=your_password_here
JWT_SECRET=generate_random_64_chars
```

### Baliozkotzea

```bash
# Probatu API endpoint-a
curl https://zabala-gailetak.infinityfreeapp.com/api/test/db

# Espero dena: {"status":"success","users_count":1}
```

---

## 📱 Android Aplikazioaren Argitalpena

### Sortu Keystore-a (Behin bakarrik)

```bash
cd "Zabala Gailetak/android-app"

keytool -genkey -v \
  -keystore zabala-gailetak-release.keystore \
  -alias zabala-gailetak-hrapp \
  -keyalg RSA -keysize 2048 \
  -validity 10000

# Bihurtu base64-ra
base64 -w 0 zabala-gailetak-release.keystore > keystore.base64
```

### Konfiguratu GitHub Sekretuak

```
Settings → Secrets → Actions → New repository secret

ANDROID_KEYSTORE_BASE64    = [itsatsi keystore.base64 edukia]
ANDROID_KEYSTORE_PASSWORD  = [zure keystore pasahitza]
ANDROID_KEY_ALIAS          = zabala-gailetak-hrapp
ANDROID_KEY_PASSWORD       = [zure gako pasahitza]
```

### Argitalpen Metodoak

**1. Metodoa: Automatikoa (Tag bultzatzea)**
```bash
git tag -a v1.0.0 -m "Release v1.0.0"
git push origin v1.0.0
# GitHub Actions-ek automatikoki eraikitzen eta argitaratzen du
```

**2. Metodoa: Eskuzkoa (GitHub UI)**
```
1. Joan Actions → Android App Release-ra
2. Egin klik "Run workflow"-n
3. Sartu bertsioa: 1.0.0
4. Egin klik "Run workflow"-n
5. Deskargatu APK Artifacts-etik
```

### Egiaztatu APK-a

```bash
# Egiaztatu sinadura
apksigner verify zabala-gailetak-hrapp-v1.0.0-signed.apk

# Egiaztatu kontrol-batura
sha256sum zabala-gailetak-hrapp-v1.0.0-signed.apk
# Konparatu checksums.txt-rekin
```

### Instalatu APK-a

```bash
# ADB bidez
adb install zabala-gailetak-hrapp-v1.0.0-signed.apk

# Edo deskargatu telefonora eta sakatu instalatzeko
# (Gaitu "Install from Unknown Sources" lehenengo)
```

---

## 🔧 Arazoen Konponketa

### Datu-basearen Arazoak

| Arazoa | Konponbidea |
|--------|-------------|
| Ezin da konektatu | Egiaztatu DB_HOST, DB_NAME, DB_PASSWORD .env fitxategian |
| Taula ez dago | Berriro exekutatu mysql_zabala_gailetak_fresh_install.sql |
| Dokumentu publikoak huts egiten dute | Exekutatu infinityfree_patches_v2.sql (1. adabakia) |
| Trigger-ak ez dabiltza | Exekutatu: `SHOW TRIGGERS; -- 3 ikusi beharko zenituzke` |

### Android Eraikuntzaren Arazoak

| Arazoa | Konponbidea |
|--------|-------------|
| Eraikuntzak huts egiten du | Egiaztatu GitHub Secrets ondo konfiguratuta daudela |
| Sinadurak huts egiten du | Egiaztatu keystore pasahitza zuzena dela |
| Bertsio gatazka | Handitu versionCode build.gradle.kts fitxategian |
| Instalazioak huts egiten du | Gaitu "Install from Unknown Sources" |

---

## 📞 Esteka Azkarrak

- **Datu-base Gida Osoa:** [INFINITYFREE_DEPLOYMENT.md](INFINITYFREE_DEPLOYMENT.md)
- **Android Gida Osoa:** [ANDROID_RELEASE_GUIDE.md](ANDROID_RELEASE_GUIDE.md)
- **GitHub Actions:** [.github/workflows/android-release.yml](.github/workflows/android-release.yml)
- **SQL Adabakiak:** [scripts/infinityfree_patches_v2.sql](scripts/infinityfree_patches_v2.sql)

---

## ✅ Ingurunea Egin Aurreko Egiaztapen Zerrenda

### Datu-basea
- [ ] Egin segurtasun kopia datu-base existitzen bada
- [ ] Izan InfinityFree kredentzialak prest
- [ ] Sortu JWT_SECRET indartsua (64+ karaktere)
- [ ] Sortu PASSWORD_PEPPER indartsua
- [ ] Berrikusi .env.example fitxategia

### Android
- [ ] Sortu argitalpen keystore-a
- [ ] Egin keystore-aren segurtasun kopia modu seguruan
- [ ] Gehitu 4 GitHub Secrets guztiak
- [ ] Eguneratu versionCode eta versionName
- [ ] Probatu aplikazioa gailu fisikoan

---

## 🆘 Larrialdi Kontaktuak

**Datu-base Arazoak:**  
- InfinityFree Laguntza: https://forum.infinityfree.com
- Barnekoa: it@zabalagailetak.com

**Android Arazoak:**  
- GitHub Actions Dokumentazioa: https://docs.github.com/actions
- Android Sinadura: https://developer.android.com/studio/publish/app-signing
- Barnekoa: dev@zabalagailetak.com

---

**Azken Eguneraketa:** 2026-02-06  
**Bertsioa:** 1.0  
**Inprimatu orri hau eta eduki eskura!** 📄
