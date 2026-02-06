# 📱 Android App Release Guide

**Zabala Gailetak HR App - Release Management**

---

## 🎯 Overview

This guide explains how to build, sign, and release the Android app using GitHub Actions.

### Release Workflow Features

- ✅ Automated builds on version tags (`v1.0.0`, `v1.2.3`, etc.)
- ✅ Manual builds via GitHub Actions UI
- ✅ APK signing with release keystore
- ✅ GitHub Releases with APK downloads
- ✅ SHA-256 checksums for verification
- ✅ Debug and Release variants
- ✅ Automatic version management

---

## 🔐 Setup: Android Keystore

### Step 1: Generate Release Keystore

**Only needed once per project**

```bash
# Navigate to android-app directory
cd "Zabala Gailetak/android-app"

# Generate keystore (interactive)
keytool -genkey -v \
  -keystore zabala-gailetak-release.keystore \
  -alias zabala-gailetak-hrapp \
  -keyalg RSA \
  -keysize 2048 \
  -validity 10000 \
  -storepass YOUR_STORE_PASSWORD \
  -keypass YOUR_KEY_PASSWORD

# You'll be prompted for:
# - Your name: Zabala Gailetak
# - Organizational unit: IT Department
# - Organization: Zabala Gailetak
# - City: [Your city]
# - State: Basque Country
# - Country code: ES
```

**Example interactive prompts:**
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

### Step 2: Verify Keystore

```bash
# List keystore contents
keytool -list -v -keystore zabala-gailetak-release.keystore

# Expected output:
# Alias name: zabala-gailetak-hrapp
# Creation date: [date]
# Entry type: PrivateKeyEntry
# Certificate chain length: 1
# ...
```

### Step 3: Convert Keystore to Base64

```bash
# Convert to base64 for GitHub Secrets
base64 -w 0 zabala-gailetak-release.keystore > keystore.base64

# Or on macOS:
base64 -i zabala-gailetak-release.keystore -o keystore.base64
```

### Step 4: Add GitHub Secrets

Go to GitHub repository → Settings → Secrets and variables → Actions

Add the following secrets:

| Secret Name | Value | Description |
|-------------|-------|-------------|
| `ANDROID_KEYSTORE_BASE64` | [content of keystore.base64] | Base64-encoded keystore file |
| `ANDROID_KEYSTORE_PASSWORD` | YOUR_STORE_PASSWORD | Keystore password |
| `ANDROID_KEY_ALIAS` | zabala-gailetak-hrapp | Key alias name |
| `ANDROID_KEY_PASSWORD` | YOUR_KEY_PASSWORD | Key password |

**Security Notes:**
- ⚠️ **NEVER commit the keystore file to git**
- ⚠️ **Backup the keystore file securely** (encrypted storage)
- ⚠️ **Store passwords in password manager**
- ⚠️ If keystore is lost, you cannot update the app on Play Store

---

## 🚀 Releasing a New Version

### Method 1: Automatic Release (Tag Push)

**Best for production releases**

```bash
# 1. Update version in build.gradle.kts (optional - workflow updates automatically)
cd "Zabala Gailetak/android-app/app"
# Edit versionCode and versionName

# 2. Commit changes
git add .
git commit -m "chore: Prepare release v1.0.0"

# 3. Create and push version tag
git tag -a v1.0.0 -m "Release version 1.0.0"
git push origin v1.0.0

# 4. GitHub Actions will automatically:
# - Build debug and release APKs
# - Sign the release APK
# - Create a GitHub Release with download links
# - Generate checksums
```

### Method 2: Manual Release (GitHub UI)

**Best for testing or patch releases**

1. Go to GitHub repository → Actions
2. Select **"Android App Release"** workflow
3. Click **"Run workflow"** dropdown
4. Fill in parameters:
   - **Version name:** `1.0.0` (semantic version)
   - **Version code:** `1` (integer, increments with each build)
   - **Release notes:** Brief description of changes
5. Click **"Run workflow"**
6. Wait for build to complete (~5-10 minutes)
7. Download APK from "Artifacts" section

---

## 📦 Build Artifacts

After each build, the following artifacts are available:

### Debug APK
- **Filename:** `zabala-gailetak-hrapp-vX.X.X-debug.apk`
- **Purpose:** Development and testing
- **Signing:** Debug keystore (automatically generated)
- **Retention:** 30 days

### Release APK (Unsigned)
- **Filename:** `zabala-gailetak-hrapp-vX.X.X-release-unsigned.apk`
- **Purpose:** Manual signing or testing
- **Signing:** Not signed
- **Retention:** 30 days
- **Generated:** Only for manual workflow runs

### Release APK (Signed)
- **Filename:** `zabala-gailetak-hrapp-vX.X.X-signed.apk`
- **Purpose:** Production distribution
- **Signing:** Release keystore
- **Retention:** 90 days
- **Generated:** Only for tag pushes

### Checksums
- **Filename:** `checksums.txt`
- **Purpose:** Verify APK integrity
- **Contains:** SHA-256 hashes of all APKs
- **Retention:** 90 days

---

## 📥 Installing the App

### For End Users

1. **Download APK:**
   - Go to [Releases](https://github.com/tears-mysthrala/erronka4/releases)
   - Find the latest version
   - Download `zabala-gailetak-hrapp-vX.X.X-signed.apk`

2. **Verify Integrity (Optional but Recommended):**
   ```bash
   # Download checksums.txt
   # Calculate SHA-256 of downloaded APK
   sha256sum zabala-gailetak-hrapp-v1.0.0-signed.apk
   
   # Compare with checksums.txt
   # Should match exactly
   ```

3. **Enable Unknown Sources:**
   - Settings → Security → Install from Unknown Sources
   - Or Settings → Apps → Special access → Install unknown apps
   - Enable for your browser or file manager

4. **Install APK:**
   - Tap the downloaded APK file
   - Follow installation prompts
   - Grant required permissions

5. **Launch App:**
   - Find "Zabala Gailetak HR" in app drawer
   - Login with your credentials

### For Developers/Testers

```bash
# Install via ADB (USB debugging enabled)
adb install zabala-gailetak-hrapp-v1.0.0-signed.apk

# Or replace existing installation
adb install -r zabala-gailetak-hrapp-v1.0.0-signed.apk

# Uninstall
adb uninstall com.zabalagailetak.hrapp
```

---

## 🔍 Verifying the Build

### 1. Check APK Signature

```bash
# Using apksigner (Android SDK)
apksigner verify --verbose zabala-gailetak-hrapp-v1.0.0-signed.apk

# Expected output:
# Verifies
# Verified using v1 scheme (JAR signing): true
# Verified using v2 scheme (APK Signature Scheme v2): true
# Number of signers: 1
```

### 2. Verify Keystore Match

```bash
# Get certificate fingerprint from APK
keytool -printcert -jarfile zabala-gailetak-hrapp-v1.0.0-signed.apk

# Compare with keystore certificate
keytool -list -v -keystore zabala-gailetak-release.keystore

# SHA-256 fingerprints should match
```

### 3. Check APK Contents

```bash
# List files in APK
unzip -l zabala-gailetak-hrapp-v1.0.0-signed.apk | grep -E "(dex|so|xml)"

# Extract and examine manifest
unzip zabala-gailetak-hrapp-v1.0.0-signed.apk AndroidManifest.xml
aapt dump xmltree zabala-gailetak-hrapp-v1.0.0-signed.apk AndroidManifest.xml
```

---

## 🐛 Troubleshooting

### Build Fails: "Gradle version"

**Error:**
```
Gradle version X.X is required. Current version is Y.Y.
```

**Solution:**
Update `gradle/wrapper/gradle-wrapper.properties`:
```properties
distributionUrl=https\://services.gradle.org/distributions/gradle-8.7-bin.zip
```

### Build Fails: "Java version"

**Error:**
```
Android Gradle plugin requires Java 17 to run. You are currently using Java 11.
```

**Solution:**
Workflow already uses Java 21. Check local development environment:
```bash
java -version
# Should be Java 21
```

### APK Signing Fails

**Error:**
```
Failed to sign APK: Keystore password incorrect
```

**Solutions:**
1. Verify GitHub Secrets are set correctly
2. Check password doesn't have special characters causing issues
3. Regenerate keystore if password is lost

### Version Code Conflicts

**Error:**
```
INSTALL_FAILED_VERSION_DOWNGRADE
```

**Solution:**
Each release must have a higher versionCode:
```kotlin
// In app/build.gradle.kts
versionCode = 2  // Increment from previous
versionName = "1.0.1"
```

### "Unknown Sources" Not Available

On Android 8.0+, the setting is per-app:
- Settings → Apps → Special access → Install unknown apps
- Select your browser/file manager
- Enable "Allow from this source"

---

## 📊 Version Management

### Semantic Versioning

Follow [SemVer](https://semver.org/): `MAJOR.MINOR.PATCH`

- **MAJOR:** Breaking changes, major redesign
- **MINOR:** New features, backward compatible
- **PATCH:** Bug fixes, small improvements

**Examples:**
- `1.0.0` - Initial release
- `1.1.0` - Added documents module
- `1.1.1` - Fixed login bug
- `2.0.0` - Complete UI redesign

### Version Code

Integer that increments with every release:

```
v1.0.0 → versionCode: 1
v1.0.1 → versionCode: 2
v1.1.0 → versionCode: 3
v2.0.0 → versionCode: 4
```

**Automatic:** Workflow uses git commit count if not specified

---

## 🔒 Security Best Practices

### Keystore Management

1. **Backup Strategy:**
   - Store keystore in encrypted cloud storage (1Password, Bitwarden)
   - Keep offline backup on encrypted USB drive
   - Document password recovery process
   - Test recovery process annually

2. **Access Control:**
   - Limit keystore access to release managers only
   - Use separate keystores for debug vs release
   - Rotate keys if compromised (requires new app listing)

3. **GitHub Secrets:**
   - Never print secrets in logs
   - Rotate secrets if exposed
   - Use organization secrets for team access
   - Review secret access regularly

### APK Distribution

1. **Official Channels Only:**
   - GitHub Releases (primary)
   - Internal file server (secondary)
   - Never use public file sharing sites

2. **Verification:**
   - Always provide SHA-256 checksums
   - Sign release notes with GPG key (optional)
   - Use HTTPS for all download links

3. **Communication:**
   - Announce releases via official channels
   - Include changelog and known issues
   - Provide support contact information

---

## 📝 Release Checklist

Print and check off before each release:

### Pre-Release
- [ ] All tests passing (unit, integration, UI)
- [ ] No critical bugs in issue tracker
- [ ] Code reviewed and approved
- [ ] Version numbers updated
- [ ] Changelog written
- [ ] Release notes prepared
- [ ] Keystore accessible
- [ ] GitHub Secrets verified

### Build
- [ ] Create version tag
- [ ] Push tag to trigger workflow
- [ ] Monitor build progress
- [ ] Download signed APK
- [ ] Verify APK signature
- [ ] Test APK on physical device
- [ ] Verify checksums match

### Post-Release
- [ ] GitHub Release published
- [ ] Release notes complete
- [ ] Team notified
- [ ] Documentation updated
- [ ] Users informed (email, announcement)
- [ ] Monitor crash reports
- [ ] Respond to user feedback

---

## 🎓 Learning Resources

- [Android App Signing](https://developer.android.com/studio/publish/app-signing)
- [GitHub Actions for Android](https://github.com/actions/setup-java)
- [Gradle Build Configuration](https://developer.android.com/studio/build)
- [ProGuard/R8](https://developer.android.com/studio/build/shrink-code)
- [Semantic Versioning](https://semver.org/)

---

## 📞 Support

**Questions about releases?**
- Internal: it@zabalagailetak.com
- GitHub Issues: [Report a problem](https://github.com/tears-mysthrala/erronka4/issues)

---

**Last Updated:** 2026-02-06  
**Maintained By:** Zabala Gailetak DevTeam  
**Workflow Version:** 1.0.0
