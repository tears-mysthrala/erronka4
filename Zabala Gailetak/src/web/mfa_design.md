# Web Segurtasuna eta MFA Diseinua - Zabala Gailetak

## 1. Webgune Berriaren Segurtasun Neurriak

Bezeroei zuzendutako atari berria segurtasun estandar altuenekin garatu da (OWASP ASVS).

- **HTTPS/TLS:** Komunikazio guztiak zifratuta (TLS 1.3).
- **WAF (Web Application Firewall):** SQL Injection eta XSS erasoak blokeatzeko.
- **Segurtasun Goiburuak (Headers):** `HSTS`, `CSP (Content Security Policy)`,
  `X-Frame-Options`.

## 2. Autentifikazioa eta MFA (Multi-Factor Authentication)

Erabiltzaileen kontuak babesteko, faktore anitzeko autentifikazioa ezarri dugu.

### Inplementazioa

1. **Pasahitza (Ezagutza):**
   - Gutxienez 12 karaktere.
   - Hizteki bidezko erasoen aurkako blokeoa.
2. **Bigarren Faktorea (Jabetza):**
   - **TOTP (Time-based One-Time Password):** Google Authenticator / Microsoft Authenticator
     estandarra (QR kode bidez aktibatua).
   - **SMS/Email:** Aukera gisa (baina TOTP lehenetsiz, seguruagoa baita).

### Login Fluxua

1. Erabiltzaileak erabiltzaile-izena eta pasahitza sartzen ditu.
2. Sistemak balioztatzen du. Zuzena bada ->
3. Sistemak 6 digituko kodea eskatzen du (TOTP).
4. Erabiltzaileak mugikorreko aplikazioko kodea sartzen du.
5. Sarbidea baimentzen da.

## 3. Saio Kudeaketa

- Saioak 15 minutuko inaktibitatearen ondoren iraungitzen dira.
- Saioa ixtean tokenak baliogabetzen dira.
