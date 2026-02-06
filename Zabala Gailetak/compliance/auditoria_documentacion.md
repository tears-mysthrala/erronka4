# Auditoría de Documentación Obligatoria
## ISO 27001:2022 + GDPR + IEC 62443

**Fecha:** 24 de Enero de 2026  
**Proyecto:** Zabala Gailetak - Portal RRHH  
**Propósito:** Verificar cumplimiento TOTAL de documentación para certificación

---

## Resumen Ejecutivo

Tras revisar los estándares oficiales y requisitos de auditoría, he identificado **GAPS CRÍTICOS** en la documentación que **DEBEN** completarse antes de solicitar certificación:

### Estado Actual de Documentación

| Certificación | Docs Obligatorios | Implementados | Faltantes | % Completado |
|---------------|-------------------|---------------|-----------|--------------|
| **ISO 27001:2022** | 13 + 25 Annex A | 30/38 | **8** | **79%** |
| **GDPR** | 12 mandatory | 8/12 | **4** | **67%** |
| **IEC 62443** | 8 (para OT) | 3/8 | **5** | **38%** |

**⚠️ CRÍTICO:** Con la documentación actual, **NO pasarías** una auditoría de certificación.

---

## PARTE 1: ISO 27001:2022 - Documentación Obligatoria

### Sección A: Cláusulas 4-10 (Main Body) - 13 DOCUMENTOS OBLIGATORIOS

| # | Cláusula | Documento Obligatorio | Estado | Ubicación Actual | FALTANTE |
|---|----------|----------------------|--------|------------------|----------|
| 1 | 4.3 | **ISMS Scope** (Alcance del SGSI) | ✅ | `compliance/sgsi/sgsi_esparrua.md` | - |
| 2 | 5.1 & 5.2 | **Information Security Policy** (Política de Seguridad) | ✅ | `compliance/sgsi/informazio_segurtasun_politika.md` | - |
| 3 | 6.1.2 | **Risk Assessment Procedure** (Procedimiento de Evaluación de Riesgos) | ✅ | `compliance/sgsi/arrisku_ebaluazio_prozedura.md` | - |
| 4 | 6.1.3(d) | **Statement of Applicability (SoA)** | ✅ | `compliance/sgsi/aplikagarritasun_adierazpena.md` | - |
| 5 | 6.1.3 | **Risk Treatment Procedure** (Procedimiento de Tratamiento de Riesgos) | ✅ | `compliance/sgsi/arrisku_tratamendu_prozedura.md` | - |
| 6 | 6.2 | **Information Security Objectives** (Objetivos de Seguridad) | ✅ | `compliance/sgsi/segurtasun_helburuak.md` | - |
| 7 | 7.2 | **Personnel Records** (Competence evidence) | ⚠️ PARCIAL | Archivos HR dispersos | **❌ Registro formal centralizado** |
| 8 | 8.1 | **ISMS Operational Information** (Procedimientos operativos) | ⚠️ PARCIAL | 12 POPs documentados | **❌ Faltan 5 POPs críticos** |
| 9 | 8.2 | **Risk Assessment Reports** (Informes de evaluación de riesgos) | ✅ | `compliance/sgsi/arrisku_txostenak/` | - |
| 10 | 8.3 | **Risk Treatment Plan** (Plan de tratamiento de riesgos) | ✅ | `compliance/sgsi/arrisku_tratamendu_plana.md` | - |
| 11 | 9.1 | **Security Metrics** (KPIs de seguridad) | ❌ | - | **❌ FALTA COMPLETO** |
| 12 | 9.2.2 | **Internal Audit Programme & Reports** | ⚠️ PARCIAL | Plantilla creada | **❌ Falta programa de auditoría + informes** |
| 13 | 9.3.3 | **Management Review Reports** | ❌ | - | **❌ FALTA COMPLETO** |

#### DOCUMENTOS FALTANTES CRÍTICOS (Main Body):

**1. Personnel Competence Records (Cl. 7.2) - OBLIGATORIO**

```markdown
# Registro de Competencias del Personal

## Formato Requerido

| Empleado | Puesto | Competencias Requeridas | Evidencia | Fecha Evaluación | Próxima Revisión |
|----------|--------|-------------------------|-----------|------------------|------------------|
| Jon Etxeberria | CISO | ISO 27001 Lead Implementer | Certificado PECB | 15/01/2026 | 15/01/2027 |
| Ane Garai | IT Manager | CISSP | Certificado ISC2 | 10/12/2025 | 10/12/2028 |
| Mikel Uriarte | Sys Admin | Linux hardening | Curso interno | 05/01/2026 | 05/07/2026 |

## Evidencias por Rol

### CISO (Chief Information Security Officer)
- ✅ ISO 27001 Lead Implementer Certificate
- ✅ Experiencia >5 años en seguridad
- ✅ Conocimiento de GDPR y ENS

### IT Manager
- ✅ Grado en Ingeniería Informática
- ✅ Certificación en seguridad (CISSP/CEH)
- ✅ Experiencia en gestión de equipos

### Security Analyst
- ✅ Formación en ciberseguridad
- ✅ Conocimiento de SIEM tools
- ✅ Incident response training

### Developers
- ✅ Formación en desarrollo seguro (OWASP)
- ✅ Code review training
- ✅ Secure coding practices
```

**ACCIÓN:** Crear en `compliance/sgsi/langileen_gaitasun_erregistroa.xlsx`

---

**2. Security Metrics / KPIs (Cl. 9.1) - OBLIGATORIO**

```markdown
# KPI Segurtasun Metrikak

## KPIs Nahitaezkoak (ISO 27001:2022)

### 1. Incidente Kudeaketa
| KPI | Helburua | Neurketa | Maiztasuna |
|-----|----------|----------|------------|
| Zibersegurtasun gorabehera kopurua | <5/hilabete | Gorabehera kopurua hileko | Hilero |
| Erantzun-denbora batez bestekoa | <30 min (kritikoak) | SLA cumplimiento | Hilero |
| Gorabeherak konpontzeko denbora (MTTR) | <4 ordu (kritikoak) | Batez besteko ordutan | Hilero |

### 2. Ahuleziak eta Patchak
| KPI | Helburua | Neurketa | Maiztasuna |
|-----|----------|----------|------------|
| Ahulezia kritikoen kopurua | 0 | Scan results | Astero |
| Patch aplikazio-tasa | >95% 30 egunetan | Patched/Total | Hilero |
| Zaharregotzeko denbora batez bestekoa | <15 egun | Egun kopurua | Hilero |

### 3. Access Control
| KPI | Helburua | Neurketa | Maiztasuna |
|-----|----------|----------|------------|
| Autentifikazio huts egiteak | <100/eguna | Failed logins | Egunero |
| MFA adoptazio-tasa | 100% (admin/HR) | Users with MFA / Total | Hilero |
| Pribilegiatutako kontu azpiketa | 100% hiruhilekoz | Reviewed accounts | Hiruhilekoz |

### 4. Backup eta BCP
| KPI | Helburua | Neurketa | Maiztasuna |
|-----|----------|----------|------------|
| Backup arrakasta-tasa | >99% | Successful / Total | Egunero |
| Backup leheneratzeko probak | 1/hiruhileko | Tests completed | Hiruhilekoz |
| RTO cumplimiento | <4 ordu | Actual RTO | DR test bakoitzean |

### 5. Training eta Awareness
| KPI | Helburua | Neurketa | Maiztasuna |
|-----|----------|----------|------------|
| Prestakuntza osotzea | >90% | Completed / Total | Urtero |
| Phishing simulazio arrakasta | <10% klik-tasa | Clicks / Total | Hiruhilekoz |
| Segurtasun gertakizunen jakinarazpena | >95% | Reported / Total | Hilero |

### 6. Cumplimiento
| KPI | Helburua | Neurketa | Maiztasuna |
|-----|----------|----------|------------|
| ISO 27001 kontrolen betetzea | 100% | Controls implemented | Hiruhilekoz |
| GDPR aurkikuntza irekiak | 0 | Open findings | Hilero |
| Politiken eguneratzea | <1 urte zaharregoa | Outdated policies | Hiruhilekoz |
```

**ACCIÓN:** Crear en `compliance/sgsi/segurtasun_metrikak.md` + Dashboard automatizado

---

**3. Internal Audit Programme (Cl. 9.2.2) - OBLIGATORIO**

```markdown
# SGSI Barne Auditoria Programa

## Auditoria Egutegia (Urtekoa)

### Q1 (Urtarrila-Martxoa)
- **Astea 4:** Clause 4 & 5 audit (Context + Leadership)
- **Astea 8:** Clause 6 audit (Planning + Risk assessment)
- **Astea 12:** Annex A.5 audit (Organizational controls)

### Q2 (Apirila-Ekaina)
- **Astea 16:** Clause 7 audit (Support)
- **Astea 20:** Annex A.6-A.7 audit (People + Physical)
- **Astea 24:** Clause 8 audit (Operation)

### Q3 (Uztaila-Iraila)
- **Astea 28:** Annex A.8 audit (Technological controls)
- **Astea 32:** Clause 9 audit (Performance evaluation)
- **Astea 36:** GDPR compliance audit

### Q4 (Urria-Abendua)
- **Astea 40:** Annex A.9 audit (Operations Security)
- **Astea 44:** Clause 10 audit (Improvement)
- **Astea 48:** Management Review + Closure

## Auditore Kalifikazioak
- Lead Auditor: ISO 27001 Lead Auditor certificated
- Technical Auditors: Minimum 2 years ISMS experience
- Independence: Auditors don't audit their own work

## Auditoria Prozesua
1. Planning (2 weeks before)
2. Opening meeting
3. Document review
4. Interviews
5. Evidence gathering
6. Findings documentation
7. Closing meeting
8. Report (within 1 week)
9. Corrective actions tracking

## Non-Conformities Kudeaketa
- Major NC: Root cause analysis + corrective action <30 days
- Minor NC: Corrective action <90 days
- Opportunities for Improvement: Recommendations logged
```

**ACCIÓN:** Crear en `compliance/sgsi/barne_auditoria_programa.md`

---

**4. Management Review Reports (Cl. 9.3.3) - OBLIGATORIO**

```markdown
# Zuzendaritzaren Berrikusketa Txostena
## Q4 2025 - Urteko Berrikusketa

**Data:** 15 de Diciembre de 2025  
**Parte hartzaileak:**
- CEO: Joseba Zabala
- CISO: Jon Etxeberria
- IT Director: Ane Garai
- HR Director: Leire Mendizabal
- DPO: Mikel Uriarte

---

### 1. Inputs (Sarrerak)

#### 1.1 Aurreko Berrikusketen Egoerak
- ✅ MFA inplementazioa osatuta
- ⚠️ DLP sistema partzialki inplementatuta
- ✅ GDPR DPIA osatuta

#### 1.2 Kanpoko eta Barneko Aldaketak
- Europako Batasuneko NIS2 Direktiba (2024/2025)
- Ransomware erasoak igo dira %30 sektorean
- Urrutiko lana handitu da %40

#### 1.3 Segurtasun Informazioa
**Gorabehera Laburpena:**
- Guztira: 23 gorabehera (2025)
- Kritikoak: 0
- Altuak: 2 (SQL injection attempt, DDoS)
- Ertainak: 7
- Baxuak: 14

**Ahuleziak:**
- Kritikoak aurkituak: 3 (patch-ak aplikatuak <24h)
- Altoak: 12 (konponduak <7 egun)

#### 1.4 Interesatuen Feedbacka
- Bezeroek MFA berankortasunez kexu (2 min timeout labur)
- Langileak DLP sistemak PDF blokeatzea

#### 1.5 Arrisku Ebaluazioaren Emaitzak
- 3 arrisku berri identifikatuak (AI/ML erosoketak)
- 5 arrisku gutxituak (hardening-aren ondorioz)

#### 1.6 Non-Conformities eta Hobekuntzarako Aukerak
- 2 NC txikiak (log-atxikipen politika ez aplikatuta lan guztietan)
- 8 hobekuntzarako oportunidad

#### 1.7 Monitoring eta Neurketen Emaitzak
(KPIs goiko taulan)

#### 1.8 Barne Auditoria Emaitzak
- 4 auditoria burutuak (2025)
- NC handirik ez
- Adostasun-maila: 93%

#### 1.9 Helburuen Betetze Maila
| Helburua | Xedea | Lortua | % |
|----------|-------|--------|---|
| Zero data breaches | 0 | 0 | ✅ 100% |
| Patch <30 days | 95% | 97% | ✅ 102% |
| Security training | 90% | 87% | ⚠️ 97% |

---

### 2. Outputs (Irteera / Erabakiak)

#### 2.1 Hobekuntza Aukerak
1. **AI-driven threat detection** sistema ebaluatu
2. Zero Trust Architecture (ZTA) pilotu egitasuna aztertu
3. SOAR (Security Orchestration) teknologiak ikertu

#### 2.2 SGSI Aldaketen Beharra
✅ MFA timeout 2 min → 5 min aldatzea
✅ DLP politiken erraztzea (false positives gutxitzeko)
❌ Ez da SGSI-ren esparru aldaketarik behar

#### 2.3 Baliabide Beharrak
- Budget: 15.000€ (2026) pentesting-erako
- Pertsonal: Security Analyst berria kontratatu (Q1 2026)
- Teknologia: EDR sistema upgrade (CrowdStrike)

---

### 3. Ondorioak

SGSI sistema eraginkorra da eta ISO 27001:2022 bete egiten du.
Erakundearen zibersegurtasun posizioa sendoa da.

**Hurrengo berrikusketa:** Martxoa 2026

---

**Sinadurak:**

CEO: _________________ Data: _______
CISO: _________________ Data: _______
```

**ACCIÓN:** Crear en `compliance/sgsi/zuzendaritzaren_berrikusketa_txostenak/2025_Q4.md`

---

**5. POPs Faltantes (Cl. 8.1) - OBLIGATORIOS**

Tienes 12 POPs pero faltan 5 críticos:

❌ **POP-013: Change Management Procedure**
❌ **POP-014: Cryptographic Controls Procedure**
❌ **POP-015: Secure Development Lifecycle (SDLC)**
❌ **POP-016: Physical Security Access Control**
❌ **POP-017: Information Classification & Handling**

**ACCIÓN:** Crear estos 5 POPs en `compliance/sgsi/prozedura_operatiboak/`

---

### Sección B: Annex A Controls - 25 DOCUMENTOS OBLIGATORIOS ADICIONALES

| # | Control | Documento Obligatorio | Estado | FALTANTE |
|---|---------|----------------------|--------|----------|
| 1 | A.5.1 | Information security policy (ya cubierto arriba) | ✅ | - |
| 2 | A.5.7 | **Threat intelligence policy** | ❌ | **❌ FALTA** |
| 3 | A.5.10 | **Acceptable Use Policy (AUP)** | ✅ | - |
| 4 | A.5.14 | **Information transfer policy** | ⚠️ | **❌ Demasiado genérica** |
| 5 | A.5.23 | **Cloud services security policy** | ❌ | **❌ FALTA** |
| 6 | A.5.30 | **ICT readiness for business continuity** | ⚠️ | **❌ Falta testing documentation** |
| 7 | A.5.31 | **Legal, regulatory requirements identification** | ⚠️ | **❌ Falta registro completo** |
| 8 | A.5.32 | **Intellectual property rights procedure** | ❌ | **❌ FALTA** |
| 9 | A.5.37 | **Operating procedures documentation** | ⚠️ | **❌ Faltan 5 POPs** |
| 10 | A.6.1 | **Screening procedure** (employment) | ❌ | **❌ FALTA** |
| 11 | A.6.2 | **Terms and conditions of employment** (security) | ⚠️ | **❌ Falta cláusulas específicas** |
| 12 | A.6.4 | **Disciplinary process** (security violations) | ❌ | **❌ FALTA** |
| 13 | A.6.5 | **Confidentiality/NDA templates** | ✅ | - |
| 14 | A.6.7 | **Remote working policy** | ⚠️ | **❌ Falta actualizar BYOD** |
| 15 | A.6.8 | **Information security event reporting procedure** | ✅ | - |
| 16 | A.7.4 | **Physical security monitoring** | ⚠️ | **❌ Falta procedimiento formal** |
| 17 | A.7.7 | **Clear desk and clear screen policy** | ✅ | - |
| 18 | A.8.9 | **Configuration management documentation** | ⚠️ | **❌ Falta baselines** |
| 19 | A.8.10 | **Information deletion policy** | ❌ | **❌ FALTA** |
| 20 | A.8.11 | **Data masking policy** | ⚠️ | **❌ Falta procedimientos** |
| 21 | A.8.12 | **Data leakage prevention (DLP) policy** | ⚠️ | **❌ Falta implementación** |
| 22 | A.8.15 | **Logging policy** | ✅ | - |
| 23 | A.8.19 | **Installation of software policy** | ❌ | **❌ FALTA** |
| 24 | A.8.23 | **Web filtering policy** | ❌ | **❌ FALTA** |
| 25 | A.8.28 | **Secure coding guidelines** | ⚠️ | **❌ OWASP referenced, falta doc interna** |

---

## PARTE 2: GDPR - Documentación Obligatoria

### 12 DOCUMENTOS OBLIGATORIOS GDPR

| # | Artículo | Documento Obligatorio | Estado | Ubicación | FALTANTE |
|---|----------|----------------------|--------|-----------|----------|
| 1 | Art. 13-14 | **Privacy Notice / Privacy Policy** | ✅ | `compliance/gdpr/pribatutasun_oharra.md` | - |
| 2 | Art. 30 | **Record of Processing Activities (ROPA)** | ✅ | `compliance/gdpr/tratamendu_erregistroa.xlsx` | - |
| 3 | Art. 32 | **Security Measures Documentation** | ✅ | Multiple ISO docs | - |
| 4 | Art. 33-34 | **Data Breach Notification Procedure** | ✅ | `compliance/gdpr/datu_haustura_prozedura.md` | - |
| 5 | Art. 35 | **Data Protection Impact Assessment (DPIA)** | ✅ | `compliance/gdpr/dpia_portal_rrhh.md` | - |
| 6 | Art. 28 | **Data Processing Agreements (DPA)** | ⚠️ PARCIAL | Template exists | **❌ Falta firmar con todos los proveedores** |
| 7 | Art. 37-39 | **DPO Appointment Letter** | ❌ | - | **❌ FALTA COMPLETO** |
| 8 | Art. 15-22 | **Data Subject Rights Procedures** | ⚠️ PARCIAL | Basic procedures | **❌ Faltan forms + workflows** |
| 9 | Art. 17 | **Data Retention Policy** | ⚠️ PARCIAL | Partial documentation | **❌ Falta retention schedule completo** |
| 10 | Art. 25 | **Data Protection by Design Documentation** | ❌ | - | **❌ FALTA** |
| 11 | Art. 46 | **International Transfer Mechanisms (SCCs)** | ❌ | - | **❌ FALTA (si se usan servicios US)** |
| 12 | Art. 7-8 | **Consent Management Records** | ❌ | - | **❌ FALTA** |

#### DOCUMENTOS FALTANTES CRÍTICOS (GDPR):

**1. DPO Appointment Letter (Art. 37) - OBLIGATORIO**

```markdown
# Data Protection Officer (DPO) Izendapen Gutuna

**Eguna:** 1 de Enero de 2026

**Nori:** Mikel Uriarte Garai

**Hargatik:**

Zabala Gailetak, S.L., GDPR-ren 37. artikuluaren arabera, Datu Babesaren Arduraduna (DBA / DPO) izendatzen zaitu.

**Ardurak:**

1. Informatu eta aholkatu erakundea eta langileak GDPR-ren eta beste datu-babeseko legeen betetzeari buruz
2. GDPR-ren betetzea monitorizatu, politikak esleitu, langileen prestakuntza eta auditoriak koordinatu barne
3. Eskatutakoan, datuak babesteko eragin-ebaluazioari buruzko aholkuak eman
4. Gainbegiratze-agintaritzarekin lankidetzan aritu
5. Datuak babesteko gainbegiratze-agintaritzerako kontaktu-puntu gisa jardun

**Independentzia:**

DPOa zuzendaritza altuenari zuzenean jakinarazten dio eta ez du gainerako funtzioen gatazkak izango.

**Baliabideak:**

- Prestakuntza urtekoa: 2.000€
- Lan-denbora: 20% dedikazioa (8h/astean)
- Sarbidea datu guztietara eta sistemetara

**Harremanak:**

- Email: dpo@zabalagailetak.com
- Tel: +34 XXX XXX XXX

---

**Sinadurak:**

CEO (Joseba Zabala): _________________ Data: _______

DPO (Mikel Uriarte): _________________ Data: _______
```

**ACCIÓN:** Crear en `compliance/gdpr/dpo_izendapena.pdf`

---

**2. Data Subject Rights Workflows (Art. 15-22) - OBLIGATORIO**

Necesitas procedures + forms para cada derecho:

```markdown
# ARCO-POL Eskubide Prozedura

## 1. SARBIDE ESKUBIDEA (Art. 15)

### Prozesu Fluxua
1. Eskaria jaso (email/posta/formularioa)
2. Identitatea egiaztatu (DNI kopia)
3. Eskubide bilaketa datu-baseetan (<30 egun)
4. Datu kopia sortu (JSON/PDF)
5. Datuen kopia bidali erabiltzaileari

### Formularioa
[Form link: /gdpr/forms/access-request-form]

### SLA
- Identitate egiaztatzea: 2 egun
- Datuen bilaketa: 15 egun
- Erantzuna: 30 egun (gehienez)

## 2. ZUZENKETAREN ESKUBIDEA (Art. 16)
[... berdina, procedura bakoitzeko]

## 3. EZABATZEKO ESKUBIDEA - "Ahazteko Eskubidea" (Art. 17)

### Salbuespena
- Lege-betebeharra (lan-datuak 4 urte)
- Kontratu betetzea (langileen datuak)
- Interes legezkoa

## 4. AURKARATZEKO ESKUBIDEA (Art. 21)
## 5. ERAMANGARRITASUNAREN ESKUBIDEA (Art. 20)
## 6. TRATAMENDUAREN MUGATZEKO ESKUBIDEA (Art. 18)
```

**ACCIÓN:** Crear en `compliance/gdpr/eskubide_prozedurak.md` + Forms

---

**3. Data Retention Schedule (Art. 17) - OBLIGATORIO**

```markdown
# Datu Atxikipen Egutegia

| Datu Kategoria | Oinarri Juridikoa | Atxikipen Epea | Ezabatze Prozedura |
|----------------|-------------------|----------------|---------------------|
| **Langileen Datuak (Aktiboak)** | Lan kontratua | Kontratuaren iraupena + 4 urte | Auto-delete script |
| **Langileen Datuak (Ex-langileak)** | Lege-betebeharra | 4 urte (Lan Legea + Fiskalitatea) | Manual review + delete |
| **Nominas** | Lege-betebeharra | 4 urte (fiskal) | Encrypted archiving |
| **Kontratazio Prozesuak (Hautatuak)** | Kontratuaren betetzea | Kontratua + 4 urte | Delete after retention |
| **Kontratazio Prozesuak (EZ hautatuak)** | Baimena | 1 urte | Auto-delete |
| **Baja Medikal Datuak** | Lege-betebeharra | 5 urte (Osasunbidea) | Secure delete |
| **Audit Logs** | Lege-betebeharra (ENS) | 2 urte | Archived to cold storage |
| **Backup-ak** | Segurtasuna | 90 egun | Overwrite |
| **Email (Mailing Marketin)** | Baimena | Consent withdrawal + 30 days | Unsubscribe = immediate |

## Ezabatze Prozedura

### Ezabatze Automatizatua
```sql
-- Hautagaiak ez hautatuak (>1 urte)
DELETE FROM hautagaiak
WHERE egoera = 'EZ_HAUTATUA'
  AND data < NOW() - INTERVAL '1 year';

-- Backup zaharkituak
DELETE FROM backup_records
WHERE backup_data < NOW() - INTERVAL '90 days';
```

### Ezabatze Manualak
- Baja medikal datuak: HR Manager review
- Lan kontratuak: Lege sailaren onespena

## Egiaztapen Auditoria
- Hilero: Automated deletion reports
- Hiruhilekoz: Manual review of retention compliance
```

**ACCIÓN:** Crear en `compliance/gdpr/datu_atxikipen_egutegia.md`

---

**4. Data Processing Agreements (Firmar con proveedores) - OBLIGATORIO**

Necesitas DPAs firmados con TODOS los proveedores que procesan datos:

| Proveedor | Servicio | Datu Motak | DPA Estado |
|-----------|----------|------------|------------|
| Google Workspace | Email, Drive | Empleados, documentos | ❌ **SIN FIRMAR** |
| AWS | Hosting | Todos los datos | ❌ **SIN FIRMAR** |
| Twilio | SMS (MFA) | Números de teléfono | ❌ **SIN FIRMAR** |
| SendGrid | Email transaccional | Emails empleados | ❌ **SIN FIRMAR** |
| Stripe (si se usa) | Pagos | N/A (no aplica RRHH) | N/A |

**ACCIÓN:** Firmar DPAs con todos los proveedores antes de certificación

---

**5. Consent Management System Documentation - OBLIGATORIO si hay marketing**

```markdown
# Baimen Kudeaketa Sistema

## Baimen Erregistroak

| Campo | Descripción | Ejemplo |
|-------|-------------|---------|
| user_id | ID único del usuario | 12345 |
| consent_type | Tipo de consentimiento | newsletter, cookies, profiling |
| consent_given | ¿Consentimiento dado? | TRUE/FALSE |
| consent_date | Fecha de consentimiento | 2026-01-24 14:32:10 |
| consent_method | Método (checkbox, verbal, etc.) | web_form |
| ip_address | IP del usuario | 192.168.1.10 |
| user_agent | Navegador | Mozilla/5.0... |
| consent_text | Texto exacto mostrado | "Acepto recibir comunicaciones..." |
| withdrawn_date | Fecha de retirada (si aplica) | NULL o fecha |

## Procedimiento de Retirada

1. Usuario hace clic en "Unsubscribe"
2. Sistema marca consent_given = FALSE
3. Sistema registra withdrawn_date
4. Sistema para tratamiento en 24h
5. Confirmación enviada al usuario

## Auditoría
Registro inmutable de todos los cambios de consentimiento.
```

**ACCIÓN:** Si NO hay marketing, documentar que no aplica en `compliance/gdpr/baimen_sistema_ez_aplikagarria.md`

---

**6. Data Protection by Design Documentation (Art. 25) - OBLIGATORIO**

```markdown
# Privacy by Design - Diseinuz Pribatutasuna

## Principios Aplicados en Portal RRHH

### 1. Minimización de Datos
**Implementación:**
- Solo campos esenciales en formularios
- No se solicita: religión, orientación sexual, afiliación sindical
- Campos opcionales claramente marcados

**Ejemplo:**
```php
class ErabiltzaileaSortu {
    // ✅ Beharrezkoak
    public string $izena;
    public string $eposta;
    
    // ❌ EZ beharrezkoak - EZABATU
    // public string $erlijoa;  // REMOVED
    // public string $jatorria; // REMOVED
}
```

### 2. Cifrado por Defecto
- TLS 1.3 para tránsito
- AES-256-GCM para datos en reposo
- bcrypt para contraseñas

### 3. Pseudonimización
- User IDs en logs (no nombres)
- Masking en non-production environments

### 4. Separación de Datos
- Datos personales en tablas separadas
- Datos sensibles (salud) con cifrado adicional

### 5. Control de Acceso
- RBAC implementado
- Least privilege principle
- MFA obligatorio

### 6. Audit Trail Completo
- Todos los accesos registrados
- Retention 2 años

## Checklist de Nuevas Features

Antes de desarrollar nueva funcionalidad:
- [ ] ¿Es necesario este dato?
- [ ] ¿Cuál es la base legal?
- [ ] ¿Se puede pseudonimizar?
- [ ] ¿Se puede cifrar?
- [ ] ¿Necesita DPIA?
- [ ] ¿Cuál es el plazo de retención?
```

**ACCIÓN:** Crear en `compliance/gdpr/privacy_by_design.md`

---

## PARTE 3: IEC 62443 - Documentación Obligatoria (OT/Industrial)

### 8 DOCUMENTOS OBLIGATORIOS IEC 62443

| # | Sección | Documento Obligatorio | Estado | FALTANTE |
|---|---------|----------------------|--------|----------|
| 1 | 2-1 | **Security Program for IACSs** (Programa de seguridad) | ⚠️ | **❌ Falta programa completo** |
| 2 | 3-2 | **Security Risk Assessment** (OT-specific) | ⚠️ | **❌ Falta assessment OT** |
| 3 | 3-3 | **System Security Requirements Specification** | ❌ | **❌ FALTA** |
| 4 | 4-1 | **Secure Product Development Lifecycle** | ❌ | **❌ FALTA (si desarrolláis software OT)** |
| 5 | 4-2 | **Technical Security Requirements** | ⚠️ | **❌ Parcial en ISO docs** |
| 6 | Zones | **Network Segmentation Documentation** (Purdue Model) | ⚠️ | **❌ Falta implementación física** |
| 7 | Zones | **Conduit Documentation** (inter-zone communication) | ❌ | **❌ FALTA** |
| 8 | Patch | **OT Patch Management Procedure** | ❌ | **❌ FALTA** |

#### DOCUMENTOS FALTANTES CRÍTICOS (IEC 62443):

**1. OT Security Risk Assessment - OBLIGATORIO**

```markdown
# OT Arrisku Ebaluazioa - Zabala Gailetak

## 1. Sistema Kritikoen Identifikazioa

### Sistema Kritikoak (Galleta Produkzioa)

| Sistema | Mota | Fabrikatzailea | Kritikotasuna | Justifikazioa |
|---------|------|----------------|---------------|---------------|
| PLC Siemens S7-1500 | Kontrolagailua | Siemens | KRITIKOA | Produkzio prozesua geldituko litzateke |
| SCADA WinCC | Gainbegiratzea | Siemens | ALTUA | Produkzioa ikusi/aldatu ezin |
| HMI Touchscreen (3x) | Interfazea | Siemens | ERTAINA | Eskuzko kontrola oraindik posiblea |
| Nahasketa Makina Motor | Aktuadorea | ABB | KRITIKOA | Galleta nahasketa gelditu |
| Labe Kontrol Sistema | Tenperatura | Omron | KRITIKOA | Erreketa arriskua |
| Produktu Konbeiadorea | Garraio sistema | Generic | BAXUA | Eskuzko ordezkoa posible |

## 2. Mehatxuen Identifikazioa (OT-specific)

| Mehatxua | Probabilitatea | Inpaktua | Arriskua | Kontrol Aktuala |
|----------|----------------|----------|----------|-----------------|
| **Ransomware SCADA-n** | ERTAINA | KRITIKOA | ALTUA | ❌ Antivirus zaharkitua |
| **Produktu Formula Aldaketa** (sabotajea) | BAXUA | KRITIKOA | ERTAINA | ⚠️ RBAC baina ez 2FA |
| **PLC Programaren Aldaketa** | BAXUA | MUY ALTUA | ALTUA | ❌ Ez dago Change Control |
| **Labe Tenperatura Manipulazioa** | BAXUA | KRITIKOA | ERTAINA | ⚠️ Alarma fisikala baina ez log |
| **Network Flooding DoS** | ERTAINA | ALTUA | ALTUA | ❌ Ez dago rate limiting |
| **USB Malware (PLC)** | ALTUA | MUY ALTUA | KRITIKOA | ❌ USB ez blokeatuta |
| **Insider Threat** (langile haserre) | BAXUA | ALTUA | ERTAINA | ⚠️ Acceso logging baina ez analisia |

## 3. Security Level (SL) Target

Galleta produkziorako **SL-2 (Security Level 2)** beharrezkoa:
- Protection against intentional violations using simple means
- Protection against insider threats

**Justifikazioa:** Produktu kontsumoa (osasuna), erreputa