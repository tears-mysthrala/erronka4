# Gida - Aplikazioaren Eguneraketak / Guía de Actualizaciones

## Euskara

### Zergatik ezin dut aplikazioa eguneratu?

Androiden aplikazioak eguneratzeko bi arazo nagusi egon daitezke:

#### 1. **Sinadura desberdina (Ohikoena)**
- Androidek eskatzen du APK berriak eta instalatutakoak **sinadura berdina** izatea
- Debug bertsioak eta release bertsioak sinadura desberdinak dituzte
- **Konponbidea:** Desinstalatu aplikazio zaharra eta instalatu berria

#### 2. **Bertsio kodea txikiagoa**
- Androidek eskatzen du bertsio kode berriak handiagoa izatea
- Gure workflow-a automatikoki bertsio kode handiagoak sortzen ditu

### Nola eguneratu zuzen

#### Metodoa 1: Eguneratze zuzena (datuak mantentzen ditu)

1. **Ziurtatu APK mota berdina dela:**
   - Debug bertsio bat baduzu → Deskargatu `-debug.apk`
   - Release bertsio bat baduzu → Deskargatu `-release.apk`

2. **Ireki APK fitxategia**
   - Androidek "Eguneratu" aukera erakutsi behar luke
   - Sakatu "Eguneratu"
   - Zure saioa eta datuak mantenduko dira

#### Metodoa 2: Garbiketa instalazioa (datuak galtzen dira)

1. **Babeskopia egin (aukerakoa)**
2. **Desinstalatu aplikazio zaharra:**
   - Settings > Apps > Zabala Gailetak > Uninstall
3. **Instalatu APK berria**
4. **Saioa hasi berriro**

### APK Motak

| Fitxategiaren izena | Mota | Noiz erabili |
|---------------------|------|--------------|
| `*-release.apk` | Produkzioa | Erabiltzaile gehienentzat |
| `*-debug-signed.apk` | Debug sinadura | Proba egiteko soilik |
| `*-debug.apk` | Debug build | Garatzaileentzat soilik |

---

## Español

### ¿Por qué no puedo actualizar la aplicación?

Hay dos problemas principales al actualizar apps en Android:

#### 1. **Firma diferente (Más común)**
- Android requiere que el nuevo APK y el instalado tengan la **misma firma**
- Las versiones debug y release tienen firmas diferentes
- **Solución:** Desinstala la app antigua e instala la nueva

#### 2. **Version code menor**
- Android requiere que el nuevo version code sea mayor
- Nuestro workflow genera automáticamente códigos mayores

### Cómo actualizar correctamente

#### Método 1: Actualización directa (conserva datos)

1. **Asegúrate de usar el mismo tipo de APK:**
   - Si tienes versión debug → Descarga `-debug.apk`
   - Si tienes versión release → Descarga `-release.apk`

2. **Abre el archivo APK**
   - Android debería mostrar la opción "Actualizar"
   - Toca "Actualizar"
   - Tu sesión y datos se mantendrán

#### Método 2: Instalación limpia (pierdes datos)

1. **Haz backup (opcional)**
2. **Desinstala la app antigua:**
   - Ajustes > Aplicaciones > Zabala Gailetak > Desinstalar
3. **Instala el nuevo APK**
4. **Inicia sesión de nuevo**

### Tipos de APK

| Nombre del archivo | Tipo | Cuándo usar |
|--------------------|------|-------------|
| `*-release.apk` | Producción | Para la mayoría de usuarios |
| `*-debug-signed.apk` | Firma debug | Solo para pruebas |
| `*-debug.apk` | Build debug | Solo para desarrolladores |

---

## Técnico / Teknikoa

### Version Code Generation

El workflow genera automáticamente version codes usando:

**Para tags (producción):**
```bash
VERSION_CODE=$(git rev-list --count HEAD)
```

**Para builds manuales:**
```bash
VERSION_CODE=$(date +%y%m%d%H%M)  # Ej: 2502121430
```

Esto garantiza que cada build tenga un version code único y creciente.

### Keystore Management

Para que las actualizaciones funcionen correctamente:

1. **Mismo keystore siempre:** Configura `ANDROID_KEYSTORE_BASE64` en GitHub Secrets
2. **Keystore consistente:** No regeneres el keystore entre versiones
3. **Backup del keystore:** Guarda una copia segura del archivo `.jks`

### Verificar firma de un APK

```bash
# Ver información de firma
apksigner verify -v app.apk

# Ver certificado
keytool -printcert -jarfile app.apk
```

### Errores comunes

| Error | Causa | Solución |
|-------|-------|----------|
| "App not installed" | Firma diferente | Desinstalar primero |
| "Update not installed" | Version code menor | Usar build más reciente |
| "Parse error" | APK corrupto | Descargar de nuevo |
| "Blocked by Play Protect" | APK no firmado por Google | Instalar de todos modos |
