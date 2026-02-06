# 🚀 Quick Reference - Deployment & Releases

**Zabala Gailetak HR Portal - Essential Commands**

---

## 🗄️ InfinityFree Database Deployment

### First Time Setup

```bash
# 1. Access phpMyAdmin
URL: https://cpanel.infinityfree.com → phpMyAdmin
Database: if0_40982238_zabala_gailetak

# 2. Run SQL Scripts (in this order)
- mysql_zabala_gailetak_fresh_install.sql  (fresh install)
- infinityfree_patches_v2.sql              (patches for new features)

# 3. Verify Installation
SELECT COUNT(*) FROM users;  -- Should return 1 (admin user)
SHOW TABLES;                  -- Should show 20 tables
SHOW TRIGGERS;                -- Should show 3 vacation triggers
```

### Environment Setup

```env
# Create .env file in htdocs/
DB_DRIVER=mysql
DB_HOST=sql107.infinityfree.com
DB_NAME=if0_40982238_zabala_gailetak
DB_USER=if0_40982238
DB_PASSWORD=your_password_here
JWT_SECRET=generate_random_64_chars
```

### Validation

```bash
# Test API endpoint
curl https://zabala-gailetak.infinityfreeapp.com/api/test/db

# Expected: {"status":"success","users_count":1}
```

---

## 📱 Android App Release

### Generate Keystore (One-Time)

```bash
cd "Zabala Gailetak/android-app"

keytool -genkey -v \
  -keystore zabala-gailetak-release.keystore \
  -alias zabala-gailetak-hrapp \
  -keyalg RSA -keysize 2048 \
  -validity 10000

# Convert to base64
base64 -w 0 zabala-gailetak-release.keystore > keystore.base64
```

### Configure GitHub Secrets

```
Settings → Secrets → Actions → New repository secret

ANDROID_KEYSTORE_BASE64    = [paste keystore.base64 content]
ANDROID_KEYSTORE_PASSWORD  = [your keystore password]
ANDROID_KEY_ALIAS          = zabala-gailetak-hrapp
ANDROID_KEY_PASSWORD       = [your key password]
```

### Release Methods

**Method 1: Automatic (Tag Push)**
```bash
git tag -a v1.0.0 -m "Release v1.0.0"
git push origin v1.0.0
# GitHub Actions builds and publishes automatically
```

**Method 2: Manual (GitHub UI)**
```
1. Go to Actions → Android App Release
2. Click "Run workflow"
3. Enter version: 1.0.0
4. Click "Run workflow"
5. Download APK from Artifacts
```

### Verify APK

```bash
# Check signature
apksigner verify zabala-gailetak-hrapp-v1.0.0-signed.apk

# Verify checksum
sha256sum zabala-gailetak-hrapp-v1.0.0-signed.apk
# Compare with checksums.txt
```

### Install APK

```bash
# Via ADB
adb install zabala-gailetak-hrapp-v1.0.0-signed.apk

# Or download to phone and tap to install
# (Enable "Install from Unknown Sources" first)
```

---

## 🔧 Troubleshooting

### Database Issues

| Problem | Solution |
|---------|----------|
| Can't connect | Check DB_HOST, DB_NAME, DB_PASSWORD in .env |
| Table doesn't exist | Re-run mysql_zabala_gailetak_fresh_install.sql |
| Public documents fail | Run infinityfree_patches_v2.sql (Patch 1) |
| Triggers not working | Run: `SHOW TRIGGERS; -- Should see 3` |

### Android Build Issues

| Problem | Solution |
|---------|----------|
| Build fails | Check GitHub Secrets are set correctly |
| Signing fails | Verify keystore password is correct |
| Version conflict | Increment versionCode in build.gradle.kts |
| Install fails | Enable "Install from Unknown Sources" |

---

## 📞 Quick Links

- **Full Database Guide:** [INFINITYFREE_DEPLOYMENT.md](INFINITYFREE_DEPLOYMENT.md)
- **Full Android Guide:** [ANDROID_RELEASE_GUIDE.md](ANDROID_RELEASE_GUIDE.md)
- **GitHub Actions:** [.github/workflows/android-release.yml](.github/workflows/android-release.yml)
- **SQL Patches:** [scripts/infinityfree_patches_v2.sql](scripts/infinityfree_patches_v2.sql)

---

## ✅ Pre-Deployment Checklist

### Database
- [ ] Backup existing database (if any)
- [ ] Have InfinityFree credentials ready
- [ ] Generated strong JWT_SECRET (64+ chars)
- [ ] Generated strong PASSWORD_PEPPER
- [ ] Reviewed .env.example file

### Android
- [ ] Generated release keystore
- [ ] Backed up keystore securely
- [ ] Added all 4 GitHub Secrets
- [ ] Updated versionCode and versionName
- [ ] Tested app on physical device

---

## 🆘 Emergency Contacts

**Database Issues:**  
- InfinityFree Support: https://forum.infinityfree.com
- Internal: it@zabalagailetak.com

**Android Issues:**  
- GitHub Actions Docs: https://docs.github.com/actions
- Android Signing: https://developer.android.com/studio/publish/app-signing
- Internal: dev@zabalagailetak.com

---

**Last Updated:** 2026-02-06  
**Version:** 1.0  
**Print this page and keep it handy!** 📄
