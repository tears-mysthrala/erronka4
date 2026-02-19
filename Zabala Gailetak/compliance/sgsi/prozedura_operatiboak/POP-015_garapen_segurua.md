# POP-015: Garapen Segurua (SDLC)

**Helburua:** Segurtasuna softwarearen garapen bizi-ziklo osoan integratzea.
**Arduraduna:** Garapen Arduraduna (Development Lead)

## 1. Diseinua

### 1.1 Threat Modeling
- Diseinu fasean Threat Modeling egitea derrigorrezkoa da aplikazio kritikoentzat.
- **STRIDE metodologia** erabili mehatxuak identifikatzeko:
  - **S**poofing (Identitate iruzurra)
  - **T**ampering (Manipulazioa)
  - **R**epudiation (Ukatzea)
  - **I**nformation Disclosure (Informazio filtrazioa)
  - **D**enial of Service (Zerbitzu ukapena)
  - **E**levation of Privilege (Pribilegioaren eskuratzea)

### 1.2 Security by Design Printzipioak
- **Gutxieneko Pribilegioaren Printzipioa (Least Privilege):** Erabiltzaileek gutxieneko eskubideak izan behar dituzte.
- **Hutsegite Segurua (Fail Secure):** Erroreak gertatzen direnean, sistemak modu seguruan huts egin behar du.
- **Sakoneko Defentsa (Defense in Depth):** Hainbat segurtasun geruza inplementatu.
- **Lehenetsitako Segurtasuna (Secure by Default):** Konfigurazio lehenetsiak seguruak izan behar dira.

## 2. Garapena

### 2.1 Coding Standards
- **OWASP Top 10** kontuan hartu:
  - Broken Access Control
  - Cryptographic Failures
  - Injection
  - Insecure Design
  - Security Misconfiguration
  - Vulnerable and Outdated Components
  - Identification and Authentication Failures
  - Software and Data Integrity Failures
  - Security Logging and Monitoring Failures
  - Server-Side Request Forgery (SSRF)

### 2.2 Garatzeko Tresnak
- **IDE Plugin-ak:** SonarLint, ESLint, PHPStan erabili IDE-an segurtasun akatsen detektatzeko.
- **Linter-ak:** Kode kalitatea eta segurtasuna egiaztatzeko automatikoki.
- **Pre-commit Hook-ak:** Git hook-ak konfiguratu kode txarra commit-eatzen ez dela bermatzeko.

### 2.3 Debekatutako Praktikak
- **Hardcoded Credentials:** Pasahitzak, API gakoak edo beste kredentzialik ez gordetzea kodean.
- **SQL Injection:** Prepared statements erabili beti.
- **XSS (Cross-Site Scripting):** Sarrerak sanitizatu eta ihes karaktereak erabili.
- **CSRF (Cross-Site Request Forgery):** Token-ak erabili formularioetan.

### 2.4 Dependentzia Kudeaketa
- Liburutegi eta dependentzia guztiak npm audit, pip-audit edo composer audit bezalako tresnak erabiliz aztertu.
- Dependentzia zahartuak eguneratu erregularki.

## 3. Testing (SAST/DAST/IAST)

### 3.1 SAST (Static Application Security Testing)
- **SonarQube edo Semgrep:** Kode estatikoa aztertu ahultasunak aurkitzeko.
- **Frekuentzia:** Commit bakoitzean (CI/CD pipeline-an integratua).
- **Akatsak huts egin behar dute:** Kritikoak edo altuko ahultasunak aurkitzen badira, build-ak huts egin behar du.

### 3.2 DAST (Dynamic Application Security Testing)
- **OWASP ZAP edo Burp Suite:** Aplikazio exekutatzen ari den azterketa dinamikoa.
- **Frekuentzia:** Staging ingurunean astero.
- **Test Motak:** Injection, XSS, CSRF, autentifikazioko ahultasunak.

### 3.3 IAST (Interactive Application Security Testing)
- **Contrast Security edo Hdiv:** Aplikazioan agente bat instalatzen da test-etik eta ekoizpen trafiko errealaren artean azterketa egiteko.
- **Frekuentzia:** Etengabe staging ingurunean.

### 3.4 Dependentzia Analisia (SCA - Software Composition Analysis)
- **Snyk edo Dependency-Check:** Dependentzien ahultasunak detektatu.
- **Frekuentzia:** Commit bakoitzean (CI/CD).
- **Ahultasun kritikoak:** Berehala konpondu (<7 egun).

### 3.5 Penetration Testing
- Kanpoko enpresa batek urtean behin Penetration Testing egin behar du aplikazio kritikoentzat.

## 4. Deployment

### 4.1 CI/CD Pipeline Automatizatua
- **Gitlab CI, GitHub Actions edo Jenkins** erabili deploy automatikoa egiteko.
- **Pipeline Pausoak:**
  1. Kode Compile
  2. Unit Tests
  3. SAST (SonarQube)
  4. Dependentzia Analisia (Snyk)
  5. Build Docker Irudia
  6. Deploy Staging-era
  7. DAST (OWASP ZAP)
  8. Onarpena
  9. Deploy Produkzio-ra

### 4.2 Onarpena Beharrezkoa
- **Staging-etik Produkzio-ra:** Onarpen testak pasatu behar dituzte.
- **Onartzaileak:** Garapen Arduraduna eta CISO (aplikazio kritikoetarako).

### 4.3 Rollback Plana
- Deploy batek huts egiten badu, Rollback automatikoa egon behar da aurreko bertsiora itzultzeko.

## 5. Produkzioa

### 5.1 Monitorizazio eta Logging
- **Segurtasun Log-ak:** Autentifikazioko saiakerak, sarbide ahaleginak, akatsak erregistratu.
- **SIEM Integrazioa:** Log-ak Elastic Stack edo Splunk bezalako SIEM-era bidali.
- **Alertak:** Segurtasun gertaeren bat detektatzen bada, berehala jakinarazi.

### 5.2 Ahultasun Monitorizazioa
- Produkzio ingurunean etengabeko monitorizazioa egin mehatxu berrientzat.
- CVE alertak jarraitu eta berehala patch-ak aplikatu.

## 6. Prestakuntza

### 6.1 Garatzaileentzat
- **Urteroko Prestakuntza:** Garapen seguruko ikastaroa (8 ordu gutxienez).
- **OWASP Top 10:** Aztertu eta adibideak ikusi.
- **Secure Coding Workshop:** Praktikak egin ahultasunak identifikatzeko eta konpontzeko.

### 6.2 Code Review Training
- Garatzaile guztiek Code Review prestakuntza jaso behar dute.
- Code Review-ek segurtasuna kontuan hartu behar dute, ez bakarrik funtzionalitateak.

## 7. Code Review Prozedura

### 7.1 Peer Review
- Kode aldaketa guztiak beste garatzaile batek berrikusi behar ditu.
- Pull Request-etan segurtasun arloko iruzkinak sartu behar dira.

### 7.2 Security-Focused Review
- Aldaketa kritikoetan (autentifikazioa, baimenak, datu sentikorra) CISO edo Segurtasun Analistak ere berrikusi behar dute.

## 8. Erantzukizunak

- **Garapen Arduraduna:** SDLC seguru prozesua gainbegiratu, garatzaileei prestakuntza eman.
- **Garatzaileak:** Secure Coding praktikak jarraitu, ahultasunak konpondu.
- **CISO:** Segurtasun testak eta politikak definitu.
- **QA Taldea:** DAST testak egin, ahultasunak identifikatu.

## 9. Berrikuste Plana

- Garapen seguru prozesua urtero berrikusi.
- OWASP Top 10 berrikuspena urtero egin.

---
**Lotutako Araudia:** ISO 27001:2022 A.8.25 (Garapen Bizi-ziklo Segurua)
**Erantzukizuna:** Garapen Arduraduna + CISO
**Berrikuste Maiztasuna:** Urtero
