# POP-014: Kriptografia Kontrolen Prozedura

**Helburua:** Kriptografiaren erabilera egokia eta segurua bermatzea.
**Arduraduna:** CISO

## 1. Algoritmo Onartuak

### 1.1 Simetrikoa (Zifratzea Geldirik)
- **AES-256:** Datuak gordetzeko (disko, babeskopia, datu-base).
- **ChaCha20:** Gailu mugikorretarako alternatiba.

### 1.2 Asimetrikoa (Gako Trukerako eta Sinadurarako)
- **RSA-4096:** Gako publikoa/pribatua parekatze.
- **ECC (Elliptic Curve Cryptography):** P-256, P-384, P-521.
- **EdDSA (Ed25519):** Sinadura digital altua (SSH, Git).

### 1.3 Hash Algoritmoak
- **SHA-256 edo SHA-512:** Integritatea bermatzeko.
- **Debekatuta:** MD5, SHA-1 (ahulak dira).

### 1.4 Pasahitz Hashing
- **bcrypt (cost 12):** Pasahitzen hash-a.
- **Argon2id:** Gomendatutako aukera berria.
- **PBKDF2 (SHA-256, 100.000+ iterazio):** Alternatiba.
- **Salts (Gatzak):** Ausazkoak eta bakarrak erabiltzaile bakoitzeko.

## 2. Gakoen Kudeaketa

### 2.1 Sorkuntza
- **Ausazko Zenbaki Sortzaile Seguruak (CSPRNG)** erabili gako guztiak sortzeko.
- Inoiz ez erabili gako ahulek edo aurresangarriak (adib: "12345", "password").
- Gakoen luzera minimoa:
  - AES: 256 bit
  - RSA: 4096 bit
  - ECC: 256 bit

### 2.2 Biltegiratzea (Gakoen Gordetzea)
- **HSM (Hardware Security Module):** Gomendatutako aukera gako sentikorrretarako.
- **Key Management Service (KMS):** AWS KMS, Azure Key Vault, Google Cloud KMS.
- **Debekatuta:** Gakoak kodean (hardcoded) gordetzea, konfigurazio fitxategietan testu lisan.

### 2.3 Sarbide Kontrola
- Gakoak bakarrik beharrezkoa duten sistemak eta pertsonak atzitu behar dituzte.
- Gako pribilegiatuen sarbidea MFA (Autentifikazio Anitzeko Faktorea) bidez babestu.
- Log-ak gorde gakoak noiz atzitu diren.

### 2.4 Errotazioa (Gako Aldaketa)
- **Gako Simetrikoak:** Urtean behin aldatu.
- **Gako Asimetrikoak:** 2 urtean behin aldatu.
- **TLS/SSL Ziurtagiriak:** Iraungitze data baino lehen berritu (normalean urtean behin).
- **Pasahitzak:** 90 egunean behin aldatu (langile kritikoak).

### 2.5 Ezabatzea
- Gakoa iraungitzean, suntsitu behar da modu seguruan:
  - HSM: Gakoa ezabatu HSM-tik.
  - Software: Gakoa gainidatzi 3 aldiz ausazko datuekin.
- Gako zaharkituen erregistroa mantendu 6 urtez (auditoria).

## 3. Datuak Trantsitoan (Zifratzea Mugimenduan)

### 3.1 TLS/SSL Bertsioak
- **TLS 1.3:** Derrigorrezkoa zerbitzu publiko guztietarako (Web, API).
- **TLS 1.2:** Onargarria barne zerbitzuetarako, baina TLS 1.3 gomendatzen da.
- **Debekatuta:** TLS 1.0, TLS 1.1, SSLv3 (ahultasunak dituzte).

### 3.2 Ziurtagiri Kudeaketa
- Ziurtagiri publikoak erabiltzean (HTTPS), CA (Certificate Authority) fidagarri bat erabili (Let's Encrypt, DigiCert).
- Ziurtagiri autosinatuak bakarrik test ingurunetan erabili.
- Ziurtagiri iraungitze data monitorizatu eta berritu.

### 3.3 VPN Kriptografia
- **VPN Protokoloak:** OpenVPN (AES-256), WireGuard (ChaCha20).
- **Debekatuta:** PPTP (ez da segurua).

## 4. Datuak Atsedenean (Zifratzea Geldirik)

### 4.1 Disko Zifratzea
- **Ordenagailuak:** BitLocker (Windows), FileVault (macOS), LUKS (Linux).
- **Zerbitzariak:** LUKS edo dm-crypt (Linux), BitLocker (Windows Server).
- **Disko Kanpoak (USB, Disko Gogorra Eramangarria):** VeraCrypt, BitLocker To Go.

### 4.2 Datu-Base Zifratzea (TDE - Transparent Data Encryption)
- **MongoDB:** Encryption at Rest gaitu.
- **PostgreSQL:** pgcrypto erabili edo disko mailan zifratu.
- **MySQL/MariaDB:** InnoDB Encryption gaitu.

### 4.3 Babeskopia Zifratzea
- Babeskopia guztiak AES-256 zifratu behar dira.
- Zifratzeko gakoa babeskopia bera baino beste leku batean gorde (adib: KMS).

## 5. Kriptografia Erabilpen Kasuak

### 5.1 Email Zifratzea
- **S/MIME edo PGP:** Email sentikorra zifratzeko.
- **TLS:** Email zerbitzarien arteko komunikazioa (SMTP TLS).

### 5.2 Fitxategi Zifratzea
- Fitxategi konfidentzialak (dokumentuak, PDF, adibidez) zifratu GPG edo 7-Zip (AES-256) erabiliz.

### 5.3 Datu Pertsonalen Zifratzea (GDPR)
- Datu pertsonalek (PII) datu-basean zifratu behar dira.
- Datu sentikorra (pasahitzak, kreditu txartelak) inoiz ez gorde testu lisan.

## 6. Kriptografia Politiken Urraketak

### 6.1 Debekatutako Praktikak
- Gakoak kodean (hardcoded) gordetzea.
- Algoritmo ahulak erabiltzea (MD5, SHA-1, DES).
- Gakoak email edo Slack bidez partekatzea.
- Pasahitzak testu lisan gordetzea.

### 6.2 Zigorrak
- Gakoak kode batean aurkitzen badira: Diziplina espedientea garatzaileari.
- Datu zifratugabeak produkzioan aurkitzen badira: Berehalako zuzentzea derrigorrezkoa.

## 7. Erantzukizunak

- **CISO:** Kriptografia politika definitu, algoritmo onartuak berrikusi.
- **IT Kudeatzailea:** Gako kudeaketa sistema inplementatu, ziurtagiriak mantendu.
- **Garatzaileak:** Kriptografia egokiak aplikatu, gakoak ez gordetzea kodean.
- **Segurtasun Analistak:** Kriptografia politiken betetzea auditatu.

## 8. Berrikuste Plana

- Kriptografia politika urtero berrikusi.
- Algoritmo onartuak berrikusi teknologia berriak agertu ahala.

---
**Lotutako Araudia:** ISO 27001:2022 A.8.24 (Kriptografia), GDPR Artikulu 32
**Erantzukizuna:** CISO + IT Kudeatzailea
**Berrikuste Maiztasuna:** Urtero
