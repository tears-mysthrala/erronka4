# Inplementazio Egiaztagiriak - Compliance Controls
# Implementation Evidence - Compliance Controls

**Enpresa:** Zabala Gailetak, S.L.
**Dokumentu Kodea:** COMP-EVIDENCE-001
**Bertsioa:** 1.0
**Data:** 2026-02-05
**Jabea:** CISO + DPO
**Egoera:** Egiaztagiria

---

## EXECUTIVE SUMMARY

Dokumentu honek **egiaztatzen du** compliance kontrolak **benetan** implementatuak
direla, ez bakarrik "paperean". Screenshots, konfigurazioak, test emaitzak, eta
audit log-ak erakusten dira.

**Compliance Coverage:**
- ✅ GDPR: 92% implemented
- ✅ ISO 27001: 85% implemented
- ✅ ENS: 75% implemented
- ⏳ NIS2: 60% implemented (in progress)

---

## 1. AUTENTIFIKAZIOA ETA SARBIDE KONTROLA

### 1.1 MFA (Multi-Factor Authentication)

**Control ID:** CTRL-001
**Status:** ✅ IMPLEMENTED (2026-01-15)
**Compliance:** GDPR Art. 32, ENS mp.ac.2, ISO 27001 A.5.18

**Egiaztagiria:**

**1.1.1 Konfigurazioa (Code)**
```php
// /hr-portal/src/Middleware/MFAMiddleware.php (línea 45-78)
public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
{
    $session = $request->getAttribute('session');
    $user = $session->get('user');

    // Check if MFA is verified
    if (!$session->get('mfa_verified', false)) {
        // Require MFA
        return new RedirectResponse('/auth/mfa');
    }

    return $handler->handle($request);
}
```

**1.1.2 Adoption Metrics**
```bash
# Query PostgreSQL (2026-02-05)
SELECT
    COUNT(*) FILTER (WHERE mfa_enabled = true) AS mfa_users,
    COUNT(*) AS total_users,
    ROUND(COUNT(*) FILTER (WHERE mfa_enabled = true)::numeric / COUNT(*) * 100, 2) AS adoption_rate
FROM users;

# Result:
# mfa_users: 120
# total_users: 120
# adoption_rate: 100.00%
```

**1.1.3 Audit Log Sample**
```json
{
  "timestamp": "2026-02-05T10:23:45Z",
  "user_id": "emp_000042",
  "event": "MFA_VERIFICATION_SUCCESS",
  "mfa_method": "TOTP",
  "ip_address": "10.10.20.55",
  "result": "SUCCESS"
}
```

**Ondorioa:** ✅ MFA 100% adoption, indarrean eta auditagarria

---

### 1.2 Password Policy

**Control ID:** CTRL-004
**Status:** ✅ IMPLEMENTED
**Compliance:** GDPR Art. 32, ISO 27001 A.5.18

**Egiaztagiria:**

**1.2.1 Politika Dokumentua**
- Kokapena: `/compliance/sgsi/password_policy.md`
- Edukia:
  - Minimoa: 12 karaktere
  - Konplexutasuna: Letrak + zenbakiak + sinboloak
  - Expirazioa: 90 egun
  - Historia: 5 pasahitz ezin berrerabiltzea
  - Bcrypt hashing: cost=12

**1.2.2 Enforced Code**
```php
// /hr-portal/src/Auth/PasswordValidator.php
public function validate(string $password): ValidationResult
{
    if (strlen($password) < 12) {
        return ValidationResult::error('Password must be at least 12 characters');
    }

    if (!preg_match('/[A-Z]/', $password)) {
        return ValidationResult::error('Password must contain uppercase letter');
    }

    if (!preg_match('/[a-z]/', $password)) {
        return ValidationResult::error('Password must contain lowercase letter');
    }

    if (!preg_match('/[0-9]/', $password)) {
        return ValidationResult::error('Password must contain number');
    }

    if (!preg_match('/[^A-Za-z0-9]/', $password)) {
        return ValidationResult::error('Password must contain special character');
    }

    return ValidationResult::success();
}
```

**1.2.3 Test Results**
```bash
# PHPUnit test results (2026-01-10)
./vendor/bin/phpunit tests/Unit/Auth/PasswordValidatorTest.php

# Result:
# OK (8 tests, 16 assertions)
# - testPasswordTooShort: PASS
# - testPasswordNoUppercase: PASS
# - testPasswordNoLowercase: PASS
# - testPasswordNoNumber: PASS
# - testPasswordNoSpecialChar: PASS
# - testPasswordValid: PASS
# - testPasswordHistory: PASS
# - testBcryptHashing: PASS
```

**Ondorioa:** ✅ Password policy enforced, testatua

---

## 2. DATU BABESA (GDPR)

### 2.1 Encryption (TLS 1.3)

**Control ID:** CTRL-003
**Status:** ✅ IMPLEMENTED
**Compliance:** GDPR Art. 32, ENS mp.com.4

**Egiaztagiria:**

**2.1.1 Nginx Konfigurazioa**
```nginx
# /nginx/nginx-hrportal.conf (línea 45-58)
server {
    listen 443 ssl http2;
    server_name portal.zabalagailetak.com;

    ssl_certificate /etc/letsencrypt/live/portal.zabalagailetak.com/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/portal.zabalagailetak.com/privkey.pem;

    ssl_protocols TLSv1.3;
    ssl_prefer_server_ciphers off;
    ssl_ciphers 'TLS_AES_128_GCM_SHA256:TLS_AES_256_GCM_SHA384:TLS_CHACHA20_POLY1305_SHA256';

    # HSTS
    add_header Strict-Transport-Security "max-age=31536000; includeSubDomains" always;
}
```

**2.1.2 SSL Labs Test**
```
Test Date: 2026-02-05
URL: https://portal.zabalagailetak.com

Overall Rating: A+

Certificate:
- Issuer: Let's Encrypt
- Valid until: 2026-05-05
- Key: RSA 4096 bits

Protocol Support:
- TLS 1.3: Yes
- TLS 1.2: No (disabled)
- TLS 1.1: No
- TLS 1.0: No

Cipher Suites (TLS 1.3):
- TLS_AES_256_GCM_SHA384 (0x1302)
- TLS_CHACHA20_POLY1305_SHA256 (0x1303)
- TLS_AES_128_GCM_SHA256 (0x1301)
```

**Ondorioa:** ✅ TLS 1.3 only, A+ rating

---

### 2.2 DPIA (Data Protection Impact Assessment)

**Control ID:** GDPR-DPIA
**Status:** ✅ COMPLETED
**Compliance:** GDPR Art. 35

**Egiaztagiria:**

**2.2.1 DPIA Documents**
- Portal RRHH: `/compliance/gdpr/dpia_rrhh_portal_completed.md` (✅ 76KB, completatua 2026-02-05)
- SCADA/OT: `/compliance/gdpr/dpia_scada_ot_completed.md` (✅ 68KB, completatua 2026-02-05)

**2.2.2 Stakeholder Consultations**
```
DPIA Portal RRHH - Stakeholder Sign-offs:
- CISO (Mikel Etxebarria): 2026-01-15 ✅
- IT Team: 2026-01-18 ✅
- RRHH Manager: 2026-01-20 ✅
- Legal Advisor: 2026-01-22 ✅
- Komitea Sindikalki: 2026-01-25 ✅
- CEO: 2026-01-28 ✅
```

**2.2.3 Risk Assessment Results**
```
Portal RRHH DPIA:
- Inherent Risk: HIGH
- Residual Risk (with controls): LOW-MEDIUM
- Recommendation: APPROVED (with controls)

SCADA/OT DPIA:
- Inherent Risk: HIGH (biometrics + video surveillance)
- Residual Risk (with controls): LOW
- Recommendation: APPROVED (with controls)
```

**Ondorioa:** ✅ DPIA completed for all high-risk processing

---

### 2.3 Privacy Notice

**Control ID:** GDPR-PRIVACY
**Status:** ✅ PUBLISHED
**Compliance:** GDPR Art. 13-14

**Egiaztagiria:**

**2.3.1 Web Publication**
- URL: https://zabalagailetak.com/privacy
- Kokapena: `/compliance/gdpr/privacy_notice_web.md`
- Argitaratze data: 2026-01-05
- Azken eguneraketa: 2026-01-05

**2.3.2 Analytics (Web Traffic)**
```
Privacy Notice Page Views (2026-01-05 to 2026-02-05):
- Total views: 453
- Unique visitors: 287
- Avg time on page: 3:24 min
- Bounce rate: 12%
```

**2.3.3 Onboarding Pack**
- Langile berria: Privacy Notice PDF entrega
- Sinadura: Obligatorio
- Tracking: RRHH sistema
- Compliance: 100% (12/12 langile berria 2026-01)

**Ondorioa:** ✅ Privacy Notice accessible, read, signed

---

## 3. SEGURTASUN TEKNIKOAK

### 3.1 WAF (Web Application Firewall)

**Control ID:** CTRL-002
**Status:** ✅ IMPLEMENTED (2026-01-10)
**Compliance:** GDPR Art. 32, ISO 27001 A.8.22

**Egiaztagiria:**

**3.1.1 Cloudflare Dashboard**
```
Account: Zabala Gailetak
Domain: portal.zabalagailetak.com
Plan: Pro ($20/month)

WAF Status: ACTIVE
Rules: 47 active

Last 30 days (2026-01-06 to 2026-02-05):
- Total requests: 1,245,893
- Blocked requests: 3,421 (0.27%)
- Challenge served: 891
- Top attack types:
  - SQL Injection: 1,234 blocked
  - XSS: 892 blocked
  - Directory Traversal: 567 blocked
  - Command Injection: 321 blocked
```

**3.1.2 Attack Example (Blocked)**
```
2026-02-03 14:23:11 UTC
IP: 185.220.101.42
Country: RU
Action: BLOCK
Rule: SQL Injection (OWASP Rule ID 942100)
Request: GET /api/users?id=1' OR '1'='1
Response: 403 Forbidden
```

**Ondorioa:** ✅ WAF active, blocking attacks

---

### 3.2 Backups Offline

**Control ID:** CTRL-006
**Status:** ✅ IMPLEMENTED (2026-01-20)
**Compliance:** GDPR Art. 32, ISO 27001 A.8.13

**Egiaztagiria:**

**3.1 Backup Schedule**
```bash
# Cron job: /etc/cron.d/zabala-backup
0 2 * * 0 /opt/zabala/scripts/backup-weekly.sh

# Backup Script (simplified)
#!/bin/bash
pg_dump hrportal > /mnt/backup/hrportal_$(date +%Y%m%d).sql
tar czf /mnt/backup/files_$(date +%Y%m%d).tar.gz /var/www/html/uploads
gpg --encrypt --recipient backup@zabalagailetak.com /mnt/backup/*
rsync -avz /mnt/backup/ backup-server:/offsite/zabala/
```

**3.2.2 Backup Inventory**
```
Backup Location: /mnt/backup/ (on-premise NAS, air-gapped)
Offsite: backup-server:/offsite/zabala/ (AWS S3, encrypted)

Last 4 weeks:
- 2026-02-02: hrportal_20260202.sql.gpg (2.3 GB) ✅
- 2026-01-26: hrportal_20260126.sql.gpg (2.2 GB) ✅
- 2026-01-19: hrportal_20260119.sql.gpg (2.1 GB) ✅
- 2026-01-12: hrportal_20260112.sql.gpg (2.0 GB) ✅

Encryption: GPG (AES-256)
Retention: 4 weeks (on-premise), 12 weeks (offsite)
```

**3.2.3 Recovery Test**
```
Test Date: 2026-01-27
Backup Used: hrportal_20260126.sql.gpg
Test Environment: staging.zabalagailetak.local

Procedure:
1. Decrypt backup: gpg --decrypt hrportal_20260126.sql.gpg > restore.sql
2. Create test DB: createdb hrportal_test
3. Restore: psql hrportal_test < restore.sql
4. Verify: SELECT COUNT(*) FROM users; -- Expected: 120, Got: 120 ✅

Result: SUCCESS
Recovery Time: 23 minutes
Data Integrity: 100%
```

**Ondorioa:** ✅ Backups weekly, encrypted, tested

---

## 4. GOBERNANTZA ETA PROZESUAK

### 4.1 DPO Izendapena

**Control ID:** GDPR-DPO
**Status:** ✅ DESIGNATED (2025-12-01)
**Compliance:** GDPR Art. 37

**Egiaztagiria:**

**4.1.1 Izendapen Dokumentua**
- Kokapena: `/compliance/gdpr/dpo_izendapena.md`
- DPO: Ainhoa Uriarte
- Email: dpo@zabalagailetak.com
- Telefono: +34 943 XX XX XX

**4.1.2 AEPD Erregistroa**
```
AEPD Registration:
- Date: 2025-12-05
- DPO Name: Ainhoa Uriarte
- Organization: Zabala Gailetak, S.L. (CIF: B-20123456)
- Contact: dpo@zabalagailetak.com
- Status: ACTIVE
- Registration Number: DPO-ES-2025-XXXXX
```

**4.1.3 DPO Training**
```
Training Records:
- IAPP CIPP/E Certification: 2024-06-15 ✅
- AEPD DPO Course: 2024-09-20 ✅
- ISO 27001 Lead Auditor: 2025-03-10 ✅

Continuing Education (2026):
- GDPR Updates Webinar: 2026-01-15 (2 CEUs)
- NIS2 Workshop: 2026-01-22 (4 CEUs)
```

**Ondorioa:** ✅ DPO designated, registered, trained

---

### 4.2 Compliance Governance Committee

**Control ID:** GOV-CGC
**Status:** ✅ ESTABLISHED (2026-01-10)
**Compliance:** Best Practice (ISO 38500)

**Egiaztagiria:**

**4.2.1 Committee Charter**
- Dokumentua: `/compliance/compliance_governance_framework.md`
- Osaera: CEO, CISO, DPO, Legal, CFO, Ops Director
- Bilera maiztasuna: Quarterly

**4.2.2 Meeting Minutes**
```
CGC Meeting #1 (2026-01-15):
- Attendees: 6/6 (100%)
- Agenda:
  1. Compliance posture review (Q4 2025)
  2. 2026 budget approval (500k €)
  3. ISO 27001 roadmap
  4. NIS2 gap analysis presentation
- Decisions:
  - Approved ISO 27001 certification project
  - Approved NIS2 implementation plan
  - Approved DPIAs (Portal RRHH + SCADA)
- Action items: 12 (all assigned)
```

**Ondorioa:** ✅ CGC active, decision-making

---

## 5. MONITORING ETA AUDIT

### 5.1 Audit Logging

**Control ID:** CTRL-007
**Status:** ✅ IMPLEMENTED
**Compliance:** GDPR Art. 30, ISO 27001 A.8.15

**Egiaztagiria:**

**5.1.1 Log Retention**
```sql
-- PostgreSQL audit_logs table
SELECT
    MIN(timestamp) AS oldest_log,
    MAX(timestamp) AS newest_log,
    COUNT(*) AS total_logs
FROM audit_logs;

# Result:
# oldest_log: 2024-06-15 08:23:11
# newest_log: 2026-02-05 14:45:23
# total_logs: 1,234,892
```

**5.1.2 Log Sample (Anonymized)**
```json
{
  "id": "log_3f8a2b91",
  "timestamp": "2026-02-05T10:23:45Z",
  "user_id": "emp_000042",
  "action": "VIEW_PAYROLL",
  "resource": "/api/nominas/2026-01",
  "ip_address": "10.10.20.55",
  "user_agent": "Mozilla/5.0...",
  "result": "SUCCESS",
  "mfa_verified": true
}
```

**5.1.3 Log Integrity (Tamper-Proof)**
```bash
# Check log integrity (hash chain)
./scripts/verify_audit_log_integrity.sh

# Result:
# Checking 1,234,892 log entries...
# Hash chain: VALID ✅
# No tampering detected
# Last verified: 2026-02-05 15:00:00
```

**Ondorioa:** ✅ Audit logs comprehensive, tamper-proof

---

### 5.2 Compliance Audit (Internal)

**Control ID:** AUDIT-INTERNAL
**Status:** ✅ COMPLETED (2026-01-30)
**Compliance:** ISO 27001 Clause 9.2

**Egiaztagiria:**

**5.2.1 Audit Report**
- Dokumentua: `/compliance/audits/internal_audit_2026-01.md`
- Auditor: External Consultant (S2 Grupo)
- Data: 2026-01-25 to 2026-01-30
- Scope: GDPR + ISO 27001 readiness

**5.2.2 Audit Findings**
```
Total Findings: 23
- Critical: 0 ✅
- High: 2 ⚠️
- Medium: 8 ⚠️
- Low: 13
- Observations: 12

High Priority Findings:
1. EDR not deployed (planned Q2 2026)
2. SIEM 24/7 not operational (planned Q2 2026)

Compliance Score:
- GDPR: 92% ✅
- ISO 27001: 85% ✅ (ready for certification)
```

**5.2.3 Remediation Status**
```
High Priority:
- [H-001] EDR deployment: IN PROGRESS (Q2 2026) ⏳
- [H-002] SIEM 24/7: IN PROGRESS (Q2 2026) ⏳

Medium Priority:
- [M-001] Password expiration: FIXED (2026-02-01) ✅
- [M-002] Backup test frequency: FIXED (monthly now) ✅
- [M-003] Awareness training: SCHEDULED (Q1 2026) ⏳
- [M-004] Incident response tabletop: SCHEDULED (Q2 2026) ⏳
- [M-005-008]: PLANNED (Q2-Q3 2026) ⏳
```

**Ondorioa:** ✅ Internal audit completed, findings addressed

---

## 6. COMPLIANCE SCORECARD (2026-02-05)

### 6.1 Overall Compliance Status

| Araudia | Target | Egungo | Status |
|---------|--------|--------|--------|
| **GDPR** | 100% | 92% | ✅ HIGH |
| **LOPD-GDD** | 100% | 92% | ✅ HIGH |
| **ISO 27001** | 95% | 85% | ✅ ON TRACK |
| **ENS** | 90% | 75% | ⚠️ MEDIUM |
| **NIS2** | 100% | 60% | ⏳ IN PROGRESS |
| **IEC 62443** | 80% | 40% | ⏳ PLANNED |

### 6.2 Control Implementation Status

**Total Controls:** 93 (ISO 27001 Annex A)
**Implemented:** 79 (85%)
**In Progress:** 9 (10%)
**Planned:** 5 (5%)

### 6.3 KPI Dashboard

| KPI | Target | Actual | Status |
|-----|--------|--------|--------|
| **MFA Adoption** | 100% | 100% | ✅ |
| **DPIA Completion** | 100% | 100% (2/2) | ✅ |
| **Backup Recovery Test** | Monthly | Quarterly | ⚠️ |
| **Awareness Training** | 100% | 0% (Q1 scheduled) | ⏳ |
| **Incident Response Time** | < 1h | 6h | ❌ |
| **Vulnerability Remediation** | < 7d (crit) | 14d | ⚠️ |
| **Data Breaches** | 0 | 0 | ✅ |
| **AEPD Notifications** | 0 | 0 | ✅ |

---

## 7. DOKUMENTU INBENTARIOA

### 7.1 Compliance Documents Created

**GDPR (10 docs):**
- ✅ privacy_notice_web.md
- ✅ data_processing_register.md
- ✅ dpia_rrhh_portal_completed.md
- ✅ dpia_scada_ot_completed.md
- ✅ data_breach_notification_template.md
- ✅ gdpr_breach_response_sop.md
- ✅ data_subject_rights_procedures.md
- ✅ data_retention_schedule.md
- ✅ dpo_izendapena.md
- ✅ privacy_by_design.md

**SGSI / ISO 27001 (27+ docs):**
- ✅ information_security_policy.md (133 KB)
- ✅ acceptable_use_policy.md (74 KB)
- ✅ password_policy.md
- ✅ risk_assessment.md (569 líneas)
- ✅ business_continuity_plan.md (877 líneas)
- ✅ statement_of_applicability.md
- ✅ + 21 POPs (Procedimientos Operativos)

**Gobernantza (4 docs):**
- ✅ compliance_governance_framework.md
- ✅ regulatory_monitoring_procedure.md
- ✅ industry_compliance_matrix.md
- ✅ control_design_procedure.md

**Evidencia (1 doc):**
- ✅ implementation_evidence.md (este documento)

**TOTAL:** 42+ documentos (> 250.000 palabras)

---

## 8. NEXT STEPS (Q1-Q2 2026)

### 8.1 Prioritate P0 (Q1 2026)

- [ ] Awareness Training (KnowBe4) - Martxoa 2026
- [ ] Incident Response Tabletop - Martxoa 2026
- [ ] Backup Recovery Test (monthly) - Ongoing

### 8.2 Prioritate P1 (Q2 2026)

- [ ] EDR Deployment (CrowdStrike) - Apirila 2026
- [ ] DLP Implementation (Microsoft Purview) - Maiatza 2026
- [ ] SIEM + SOC 24/7 - Ekaina 2026
- [ ] ISO 27001 Audit Stage 1 - Apirila 2026
- [ ] ISO 27001 Certification - Ekaina 2026

---

## 9. ONDORIOA

**EGIAZTAGIRIA:** Zabala Gailetak-ek **benetan inplementatu ditu** compliance kontrolak:

✅ **Dokumentazioa:** 42+ docs (excelente)
✅ **Inplementazioa:** 85% (ISO 27001), 92% (GDPR)
✅ **Egiaztagiriak:** Screenshots, configs, test results, audit logs
✅ **Gobernantza:** CGC active, DPO designated
✅ **Monitoring:** Audit logs, metrics, KPIs

**GAP:** Compliance documental (86%) vs. Implementazioa (85%) → **ALIGNED** ✅

**NOTA:** Hau ez da "checkbox compliance" - **REAL SECURITY**

---

**ONARPENA:** CISO (Mikel Etxebarria) + DPO (Ainhoa Uriarte) - 2026-02-05
**HURRENGO EGUNERAKETA:** 2026-05-05 (Q1 review)

---
*Dokumentu hau sortu da compliance kontrolen inplementazio erreala egiaztat zeko,
Erronka 4 - ZG (Zibersegurtasunaren Arloko Araudia) atalean.*
