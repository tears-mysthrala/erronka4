# Índice de Entregables Erronka 4

## Zabala Gailetak - Sistema Aurreratuak

---

**Fecha:** 2026-02-12  
**Versión:** 1.0 - Final  
**Equipo:** Talde 4

---

## Estructura de Carpetas

```
entregables/
├── 00_INDICE_ENTREGABLES.pdf           ← Este documento
├── 00_README_ENTREGABLES.pdf           ← Guía de uso
├── AAI_Auzitegi_Analisia/
│   └── 01_Analisis_Forense_Practico.pdf
├── ESJ_Ekoizpen_Segurua/
│   └── 01_CI_CD_Pipeline_Segurua.pdf
├── Hacking_Etikoa/
│   └── 01_Informe_Pentesting.pdf
├── Proiektu_Orokorra/
│   └── 01_Resumen_Ejecutivo.pdf
├── Sareak_Sistemak/
│   └── 01_Infraestructura_Segura_IaC.pdf
├── ZAA_Araudia/
│   └── 01_Compliance_ISO27001_GPDR.pdf
└── ZG_Gorabeherak/
    └── 01_Gestion_Incidentes_SOAR.pdf
```

---

## Contenido por Asignatura

### 📊 AAI - Auzitegi Analisia Izan Artua

**Fichero:** `AAI_Auzitegi_Analisia/01_Analisis_Forense_Practico.pdf`

| Contenido | Líneas Rúbrica |
|-----------|----------------|
| Análisis forense con Volatility Framework | 83-90 |
| Memory acquisition con LiME | 83-90 |
| Análisis de red con Wireshark/NetworkMiner | 83-90 |
| IoT Forensics (HMI/PLC) | 83-90 |
| Cadena de custodia digital | 83-90 |

**Herramientas documentadas:**
- Volatility 3 (24+ plugins configurados)
- Autopsy (ingest modules personalizados)
- LiME (Linux Memory Extractor)
- Wireshark (dissectors ICS)
- NetworkMiner (análisis pcap)

---

### 🔧 ESJ - Ekoizpen Seguruan Jartzea

**Fichero:** `ESJ_Ekoizpen_Segurua/01_CI_CD_Pipeline_Segurua.pdf`

| Contenido | Líneas Rúbrica |
|-----------|----------------|
| Pipeline CI/CD con 10+ jobs | 111-117 |
| SAST (SonarQube, Semgrep) | 111-117 |
| DAST (OWASP ZAP) | 111-117 |
| SCA (Dependency-Check) | 111-117 |
| Tests E2E (Playwright) | 111-117 |

**Jobs implementados:**
1. Code Quality (PHP CodeSniffer, PHPStan)
2. SAST (Semgrep, SonarCloud)
3. SCA (OWASP Dependency-Check)
4. Secrets Scanning (TruffleHog)
5. Unit Tests (PHPUnit, >80% cobertura)
6. Container Security (Trivy)
7. Deploy Staging
8. DAST (OWASP ZAP)
9. E2E Tests (Playwright)
10. Deploy Production

---

### 🔍 Hacking Etikoa

**Fichero:** `Hacking_Etikoa/01_Informe_Pentesting.pdf`

| Contenido | Líneas Rúbrica |
|-----------|----------------|
| Informe PTES completo (5 fases) | 118-122 |
| OSINT (TheHarvester, Shodan) | 118-122 |
| Vulnerability Scanning (Nessus) | 118-122 |
| Explotación (SQLMap, Metasploit) | 118-122 |
| Post-explotación y pivoting | 118-122 |
| CVSS v3.1 scoring | 118-122 |

**Metodología:** Penetration Testing Execution Standard (PTES)
- Fase 1: Pre-engagement & Reconnaissance
- Fase 2: Vulnerability Discovery
- Fase 3: Exploitation & Post-exploitation
- Fase 4: Reporting

---

### 🌐 Sareak eta Sistemak Gotortzea

**Fichero:** `Sareak_Sistemak/01_Infraestructura_Segura_IaC.pdf`

| Contenido | Líneas Rúbrica |
|-----------|----------------|
| Ansible IaC - hardening CIS | 68-73 |
| 40+ controles de seguridad | 68-73 |
| Segmentación IT/OT | 68-73 |
| Firewall UFW/Iptables | 68-73 |
| Auditoría auditd | 68-73 |
| Nginx/PostgreSQL hardening | 68-73 |

**Roles Ansible implementados:**
- `common` - Configuración base
- `security_hardening` - Hardening CIS Benchmark
- `nginx` - Web server hardening
- `postgresql` - Database hardening
- `ot_security` - Seguridad industrial IEC 62443

---

### 📜 ZAA - Zibersegurtasun Araudia eta Antolakuntza

**Fichero:** `ZAA_Araudia/01_Compliance_ISO27001_GDPR.pdf`

| Contenido | Líneas Rúbrica |
|-----------|----------------|
| ISO 27001:2022 - 93 controles | 44-59 |
| GDPR - DPIA completo | 44-59 |
| NIS2 - cumplimiento directiva | 44-59 |
| Privacy by Design/Default | 44-59 |
| RoPA (Registro Actividades) | 44-59 |

**Estándares cubiertos:**
- ISO/IEC 27001:2022 (100% controles implementados)
- Reglamento General de Protección de Datos (GDPR)
- Directiva NIS2 (Redes y Sistemas de Información)
- IEC 62443 (Seguridad Industrial)

---

### 🚨 ZG - Zibersegurtasun Gorabeherak

**Fichero:** `ZG_Gorabeherak/01_Gestion_Incidentes_SOAR.pdf`

| Contenido | Líneas Rúbrica |
|-----------|----------------|
| Organización SGSI | 98-108 |
| Detección automática (Wazuh) | 98-108 |
| Playbooks SOAR (YAML) | 98-108 |
| Respuesta NIS2 (24h/72h) | 98-108 |
| Grafana dashboards | 98-108 |
| Clasificación NIST | 98-108 |

**Dashboards implementados:**
- Panel de Alertas de Seguridad (7 paneles)
- Incidentes en Tiempo Real
- Cumplimiento Normativo NIS2
- Telemetría OT (HMI/PLC)
- Tendencias de Amenazas

**Playbooks SOAR:**
- Phishing Response
- Malware Containment
- DDoS Mitigation

---

### 📋 Proiektu Orokorra

**Fichero:** `Proiektu_Orokorra/01_Resumen_Ejecutivo.pdf`

| Contenido | Líneas Rúbrica |
|-----------|----------------|
| Resumen ejecutivo del proyecto | 3-7, 14-24, 31-42 |
| Arquitectura de seguridad | 3-7, 14-24, 31-42 |
| Estado de cumplimiento | 3-7, 14-24, 31-42 |

---

## Resumen de Entregables

| Asignatura | Documento | PDF | Líneas Rúbrica |
|------------|-----------|-----|----------------|
| **AAI** | Análisis Forense Práctico | ✅ | 83-90 |
| **ESJ** | Pipeline CI/CD Seguro | ✅ | 111-117 |
| **Hacking Etikoa** | Informe Pentesting PTES | ✅ | 118-122 |
| **Sareak** | Infraestructura Segura IaC | ✅ | 68-73 |
| **ZAA** | Compliance ISO 27001/GDPR | ✅ | 44-59 |
| **ZG** | Gestión Incidentes SOAR | ✅ | 98-108 |
| **Proiektu Orokorra** | Resumen Ejecutivo | ✅ | - |

**Total:** 7 documentos PDF profesionales

---

## Instrucciones de Uso

1. **Navegación:** Cada PDF es independiente y contiene toda la documentación de su asignatura
2. **Formato:** Los PDFs mantienen el formato Markdown original con estilos profesionales
3. **Hiperenlaces:** Los enlaces internos entre documentos funcionan en la versión HTML
4. **Código:** Los bloques de código incluyen syntax highlighting
5. **Tablas:** Todas las tablas están optimizadas para impresión A4

---

## Validación de Cumplimiento

- ✅ Todos los documentos generados en Markdown
- ✅ Todos los documentos convertidos a PDF
- ✅ Código implementado y documentado
- ✅ Configuraciones y scripts incluidos
- ✅ Diagramas de arquitectura
- ✅ Matrices de cumplimiento normativo
- ✅ Notas técnicas y procedimientos

---

## Contacto y Soporte

**Proyecto:** Zabala Gailetak - Erronka 4  
**Sistema:** HR Atari Seguru  
**Equipo:** Talde 4 - Sistema Aurreratuak  

**Repositorio:** `/home/kalista/erronkak/erronka4/`

---

*Documento generado automáticamente el 2026-02-12*
