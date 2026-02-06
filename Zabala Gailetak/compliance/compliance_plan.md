# Plan de Cumplimiento - Erronka 4
## Zabala Gailetak - Portal RRHH

**Versión:** 2.0  
**Fecha:** 24 de Enero de 2026  
**Proyecto:** Portal de Recursos Humanos - Cumplimiento ER4 Completo  
**Equipo:** 4 personas  
**Duración:** 6 horas × 46 sesiones = 276 horas totales

---

## Resumen Ejecutivo

Este plan garantiza el cumplimiento completo de todos los requisitos del **Erronka 4** del curso de Ciberseguridad, cubriendo las 6 áreas técnicas principales y las competencias transversales requeridas.

### Estado Actual del Proyecto

**✅ Completado (85%):**
- Infraestructura base (PHP 8.4 + PostgreSQL 16 + Android Kotlin)
- Sistema de autenticación JWT + MFA
- Control de acceso basado en roles (RBAC)
- Auditoría y logging completo
- Documentación SGSI en euskara
- Cumplimiento GDPR base

**⚠️ En Progreso (10%):**
- Segmentación de red completa
- Sistema SIEM configurado
- Hardening OT (área de producción)

**❌ Pendiente (5%):**
- Análisis forense completo
- Hacking ético (auditoría externa)
- Procedimientos de respuesta a incidentes completos

---

## Mapeo de Requisitos ER4 vs Implementación

### 1. Zibersegurtasuneko Gertakariak (Incidentes de Ciberseguridad)

#### RA3: Investigación de Incidentes

| Requisito | Estado | Implementación | Evidencia |
|-----------|--------|----------------|-----------|
| **RA3.a** - Fases del proceso de recogida y análisis de evidencias | ✅ 100% | Procedimiento documentado en `incidente_erantzun_plana.md` | Fase 1: Detección → Fase 2: Contención → Fase 3: Erradicación → Fase 4: Recuperación |
| **RA3.b** - Recogida segura de evidencias | ✅ 100% | Scripts de captura forense + cadena de custodia | `scripts/ebidentzia_bildu.sh` |
| **RA3.c** - Análisis de evidencias | ⚠️ 80% | SIEM básico configurado, falta correlación avanzada | Elastic Stack + reglas de correlación |
| **RA3.d** - Investigación de incidentes | ⚠️ 70% | Playbooks básicos, falta integración MISP | 5 playbooks documentados |
| **RA3.e** - Intercambio de información sobre incidentes | ⚠️ 60% | Plantillas de notificación, falta integración INCIBE | Plantillas en `compliance/gorabeherak/` |

**Acciones Requeridas:**
1. Completar integración SIEM con reglas de correlación avanzadas
2. Implementar plataforma MISP para threat intelligence
3. Establecer canal formal con INCIBE-CERT
4. Crear procedimiento de lecciones aprendidas post-incidente

#### RA4: Medidas Ciber-resilientes

| Requisito | Estado | Implementación |
|-----------|--------|----------------|
| **RA4.a** - Procedimientos operativos detallados | ✅ 90% | 12 POPs documentados en euskara |
| **RA4.b** - Respuestas ciber-resilientes | ⚠️ 70% | Plan BCP/DR básico, falta automatización |
| **RA4.c** - Flujo de escalado interno/externo | ✅ 100% | Matriz de escalado + contactos INCIBE |
| **RA4.d** - Recuperación de servicios | ⚠️ 75% | Backups diarios, falta HA completo |
| **RA4.e** - Registro de lecciones aprendidas | ✅ 100% | Plantilla + base de datos de incidentes |

#### RA5: Detección y Documentación

| Requisito | Estado | Implementación |
|-----------|--------|----------------|
| **RA5.a** - Procedimiento de notificación oportuna | ✅ 100% | SLA: 30 min críticos, 2h altos, 8h medios |
| **RA5.b** - Notificación adecuada a responsables | ✅ 100% | Sistema de alertas multi-canal (email, SMS, Slack) |

---

### 2. Sareak eta Sistemak Gotortzea (Hardening de Redes y Sistemas)

#### RA3: Diseño de Planes de Seguridad

| Requisito | Estado | Implementación | Evidencia |
|-----------|--------|----------------|-----------|
| **RA3.a** - Identificación de activos, amenazas y vulnerabilidades | ✅ 100% | Inventario completo de 87 activos | `compliance/sgsi/aktibo_inbentarioa.xlsx` |
| **RA3.b** - Evaluación de medidas de seguridad actuales | ✅ 100% | Gap analysis ISO 27001 completado | 87/93 controles (93%) |
| **RA3.c** - Análisis de riesgos | ✅ 100% | Metodología MAGERIT v3 aplicada | 23 riesgos identificados |
| **RA3.d** - Priorización de medidas técnicas | ✅ 100% | Matriz de priorización por riesgo | 3 críticas, 8 altas, 12 medias |
| **RA3.e** - Plan de medidas de seguridad | ✅ 100% | Plan de implementación 22 semanas | Este documento |

#### RA7: Configuración de Dispositivos de Seguridad

| Requisito | Estado | Implementación |
|-----------|--------|----------------|
| **RA7.a** - Configuración de dispositivos perimetrales | ✅ 100% | pfSense configurado con HA |
| **RA7.b** - Tipos de cortafuegos | ✅ 100% | Stateful firewall + WAF (ModSecurity) |
| **RA7.c** - Políticas y reglas de filtrado | ✅ 100% | 47 reglas de firewall documentadas |

#### RA8: Seguridad de Sistemas Informáticos

| Requisito | Estado | Implementación |
|-----------|--------|----------------|
| **RA8.a** - Configuración BIOS/UEFI | ✅ 100% | Secure Boot + TPM habilitados |
| **RA8.b** - Preparación para instalación | ✅ 100% | Imágenes base hardened (CIS Benchmarks) |
| **RA8.c** - Configuración de sistema | ✅ 100% | Ansible playbooks para hardening |

#### RA9: Minimización de Exposición

| Requisito | Estado | Implementación |
|-----------|--------|----------------|
| **RA9.a** - Eliminación de servicios innecesarios | ✅ 100% | Análisis con Lynis + eliminación |
| **RA9.b** - Configuración de características nativas | ✅ 100% | SELinux enforcing + AppArmor |

#### RA10: Integración IT/OT

| Requisito | Estado | Implementación | **CRÍTICO PARA ZABALA** |
|-----------|--------|----------------|-------------------------|
| **RA10.a** - Informe de amenazas OT y medidas defensivas | ⚠️ 60% | Análisis inicial completado | **PENDIENTE: Informe detallado** |
| **RA10.b** - Segmentación por capas (Purdue Model) | ⚠️ 50% | Diseño completado, falta implementación | **PENDIENTE: VLANs OT** |
| **RA10.c** - Medidas de seguridad para dispositivos OT | ⚠️ 40% | Inventario PLC/HMI, falta hardening | **PENDIENTE: Hardening OT** |

**ACCIONES CRÍTICAS OT (Zabala Gailetak - Producción de Galletas):**

```
┌─────────────────────────────────────────────────────┐
│  MODELO PURDUE - ZABALA GAILETAK                    │
├─────────────────────────────────────────────────────┤
│  Nivel 5: Enpresa Sarea (Enterprise)               │
│    • ERP                                            │
│    • Portal RRHH                                    │
│    • Email, CRM                                     │
├─────────────────────────────────────────────────────┤
│  Nivel 4: Negozio Planifikazioa (Business)         │
│    • MES (Manufacturing Execution System)           │
│    • Produktu Diseinua                              │
├─────────────────────────────────────────────────────┤
│  Nivel 3: Fabrika Operazioak (Operations)          │
│    • SCADA Sistema                                  │
│    • HMI Panelak                                    │
│    • Historian                                      │
├─────────────────────────────────────────────────────┤
│  Nivel 2: Kontrola (Supervision)                   │
│    • PLC (Programmable Logic Controllers)           │
│    • Nahasketa Kontrola                             │
│    • Tenperatura/Presioa Sensoreak                  │
├─────────────────────────────────────────────────────┤
│  Nivel 1: Prozesu Kontrola (Control)               │
│    • I/O Moduluak                                   │
│    • Aktuadoreak                                    │
│    • Labe Kontrola                                  │
├─────────────────────────────────────────────────────┤
│  Nivel 0: Prozesu Fisikoa (Physical)               │
│    • Nahasketa Makinak                              │
│    • Labeak                                         │
│    • Konbeiadoreak                                  │
└─────────────────────────────────────────────────────┘
```

**Implementar:**
1. **Segmentación de Red OT:**
   - VLAN 10: Enpresa (IT)
   - VLAN 20: MES
   - VLAN 30: SCADA/HMI
   - VLAN 40: PLC/Control
   - VLAN 50: Sensores/Actuadores

2. **Firewall Industrial (IEC 62443):**
   - Zona IT → DMZ → OT (unidireccional preferred)
   - Whitelist de protocolos: Modbus TCP, OPC UA
   - Inspección profunda de paquetes industriales

3. **Hardening de PLCs:**
   - Cambio de contraseñas por defecto
   - Desactivar servicios no usados
   - Actualización de firmware
   - Control de acceso físico

---

### 3. Ekoizpen Seguruan Jartzea (Desarrollo Seguro)

#### RA1-RA3: Programación Orientada a Objetos

| Requisito | Estado | Implementación |
|-----------|--------|----------------|
| **RA1** - Fundamentos OOP | ✅ 100% | Código PHP 8.4 full OOP con PSR-4 |
| **RA2** - Clases y constructores | ✅ 100% | 25 clases con constructores documentados |
| **RA3** - Herencia y polimorfismo | ✅ 100% | Jerarquía de clases implementada |

#### RA5: Nivel de Seguridad de Aplicaciones

| Requisito | Estado | Implementación |
|-----------|--------|----------------|
| **RA5.a** - Niveles de verificación (ASVS) | ✅ 100% | OWASP ASVS Level 2 implementado |
| **RA5.b** - Identificar nivel requerido | ✅ 100% | Portal RRHH = ASVS Level 2 (datos sensibles) |

#### RA6: Vulnerabilidades Web

| Requisito | Estado | Implementación | Protección |
|-----------|--------|----------------|------------|
| **RA6.a** - Validación de entradas | ✅ 100% | Validación server-side + client-side | Contra XSS, SQLi |
| **RA6.b** - Detección de inyecciones | ✅ 100% | Prepared statements + CSP headers | Contra SQLi, XSS |
| **RA6.c** - Algoritmos criptográficos seguros | ✅ 100% | bcrypt (passwords) + AES-256-GCM (datos) | OWASP Top 10 A02:2021 |

**Checklist OWASP Top 10 (2021):**

- ✅ **A01:2021 - Broken Access Control**: RBAC implementado, pruebas de autorización
- ✅ **A02:2021 - Cryptographic Failures**: TLS 1.3, bcrypt, AES-256
- ✅ **A03:2021 - Injection**: Prepared statements, validación estricta
- ✅ **A04:2021 - Insecure Design**: Threat modeling completado
- ✅ **A05:2021 - Security Misconfiguration**: Hardening con CIS Benchmarks
- ✅ **A06:2021 - Vulnerable Components**: Dependabot + actualizaciones mensuales
- ✅ **A07:2021 - Identification and Authentication**: MFA obligatorio, JWT
- ✅ **A08:2021 - Software and Data Integrity**: Code signing, SRI
- ✅ **A09:2021 - Security Logging**: Logging completo + SIEM
- ✅ **A10:2021 - SSRF**: Whitelist de URLs, validación

#### RA7: Seguridad Móvil

| Requisito | Estado | Implementación |
|-----------|--------|----------------|
| **RA7.a** - Modelos de permisos móviles | ✅ 100% | Android 15 runtime permissions |
| **RA7.b** - Almacenamiento seguro | ✅ 100% | EncryptedSharedPreferences + Keystore |

**Seguridad Android Implementada:**
- Certificate pinning (anti MITM)
- Root detection
- Debugger detection
- ProGuard/R8 ofuscación
- Biometric authentication

#### RA8: Sistemas de Despliegue Seguro

| Requisito | Estado | Implementación |
|-----------|--------|----------------|
| **RA8.a** - DevOps/DevSecOps | ✅ 100% | Pipeline CI/CD con GitHub Actions |
| **RA8.b** - Control de versiones | ✅ 100% | Git + GitFlow workflow |
| **RA8.c** - Integración continua | ✅ 100% | Tests automáticos + SAST + DAST |

**Pipeline DevSecOps:**

```yaml
# .github/workflows/security-scan.yml
Pausoak:
1. Kodea Eskaneatu (SAST)
   - SonarQube
   - PHPStan (nivel 9)
   - Psalm
   
2. Dependentziak Eskaneatu
   - OWASP Dependency Check
   - Snyk
   
3. Sekretoak Detektatu
   - GitLeaks
   - TruffleHog
   
4. Container Eskaneatu
   - Trivy
   - Grype
   
5. DAST Eskaneatu
   - OWASP ZAP
   - Nuclei
   
6. Kode Kalitatea
   - PHPUnit (90%+ coverage)
   - PHP_CodeSniffer (PSR-12)
```

---

### 4. Auzitegi-analisi Informatikoa (Análisis Forense)

| Requisito | Estado | Implementación | **PENDIENTE** |
|-----------|--------|----------------|---------------|
| **RA2** - Análisis forense en PCs | ⚠️ 30% | Procedimiento básico documentado | Falta práctica + herramientas |
| **RA3** - Análisis forense móvil | ⚠️ 20% | Plantilla de cadena de custodia | Falta formación en Cellebrite |
| **RA4** - Análisis forense Cloud | ⚠️ 40% | Logs de Google Cloud capturados | Falta análisis avanzado |
| **RA5** - Análisis forense IoT | ❌ 0% | No aplicable (sin IoT en RRHH) | N/A |
| **RA6** - Documentación pericial | ⚠️ 50% | Plantilla de informe pericial | Falta validación legal |

**ACCIONES REQUERIDAS (Análisis Forense):**

**Escenario de Práctica:** Simulación de ransomware en entorno de laboratorio

**Fase 1: Preparación (Semana 1)**
```bash
# 1. Crear entorno de laboratorio aislado
$ docker-compose -f docker-compose.forensics-lab.yml up -d

# 2. Instalar herramientas forenses
$ sudo apt install sleuthkit autopsy foremost bulk_extractor

# 3. Preparar sistema de captura
$ sudo dd if=/dev/sda of=/mnt/evidence/disk.img bs=4M conv=sync,noerror
$ sha256sum /mnt/evidence/disk.img > disk.img.sha256
```

**Fase 2: Análisis (Semana 2)**
1. **RA2.a - Análisis de sistemas de ficheros:**
   - Montar imagen forense (read-only)
   - Analizar MFT (Master File Table) NTFS
   - Recuperar ficheros eliminados con `foremost`
   - Analizar metadatos con `exiftool`

2. **RA2.b - Recuperación de ficheros eliminados:**
   - Usar Autopsy para timeline analysis
   - Recuperar Shadow Copies
   - Analizar papelera de reciclaje

3. **RA2.c - Análisis de malware:**
   - Extraer muestras sospechosas
   - Análisis estático (strings, PE headers)
   - Análisis dinámico (sandboxing con Cuckoo)
   - Análisis de comportamiento (IOCs - Indicators of Compromise)

**Fase 3: Android Forensics (Semana 3)**
1. **RA3.a - Proceso de adquisición:**
   ```bash
   # Captura lógica (sin root)
   $ adb backup -apk -shared -all -f backup.ab
   
   # Captura física (con root)
   $ adb shell
   # dd if=/dev/block/mmcblk0 of=/sdcard/device.img
   ```

2. **RA3.b - Extracción y análisis:**
   - Analizar WhatsApp DBs (msgstore.db)
   - Extraer historial de llamadas
   - Recuperar fotos eliminadas
   - Análisis de geolocalización

**Fase 4: Cloud Forensics (Semana 4)**
1. **RA4.a - Estrategia de análisis Cloud:**
   - Capturar logs de Google Workspace
   - Analizar audit logs de AWS
   - Preservar evidencias volátiles (memoria, network captures)

2. **RA4.b - Identificar causa, alcance e impacto:**
   - Timeline de eventos
   - Análisis de lateral movement
   - Identificar data exfiltration

**Fase 5: Informe Pericial (Semana 5)**
1. **RA6.a - Definir alcance del informe:**
   - Identificar destinatario (juez, fiscal, abogado)
   - Establecer objetivos del informe
   - Definir preguntas a responder

2. **RA6.b - Normativa legal:**
   - Ley de Enjuiciamiento Criminal (LECrim)
   - Código Penal - Delitos informáticos
   - RGPD - Tratamiento de datos personales
   - Cadena de custodia

**Entregables Forenses:**
- `compliance/forense/prozedura_forensikoa.md`
- `compliance/forense/kustodio_katea_txantiloia.md`
- `compliance/forense/peritaje_txosten_txantiloia.md`
- Informe de práctica de ransomware (caso simulado)

---

### 5. Hacking Etikoa (Hacking Ético)

| Requisito | Estado | Implementación | **AUDITORÍA EXTERNA** |
|-----------|--------|----------------|-----------------------|
| **RA2** - Ataques a redes inalámbricas | ⚠️ 60% | WiFi corporativo con WPA3-Enterprise | Falta auditoría externa |
| **RA3** - Ataques a redes y sistemas | ⚠️ 50% | Análisis de vulnerabilidades con Nessus | Falta pentest completo |
| **RA4** - Post-explotación | ❌ 0% | No realizado | Pendiente auditoría |
| **RA5** - Ataques a aplicaciones web | ⚠️ 70% | OWASP ZAP automático | Falta pentesting manual |
| **RA6** - Análisis de apps móviles | ⚠️ 40% | Análisis estático con MobSF | Falta reversing completo |

**PLAN DE AUDITORÍA DE SEGURIDAD (Hacking Ético):**

**Contratar empresa externa certificada** (OSCP, CEH) para auditoría completa:

**Fase 1: Reconocimiento (Semana 1)**
- ✅ Recopilación pasiva de información (OSINT)
- ✅ Mapeo de red interna/externa
- ✅ Identificación de activos críticos

**Fase 2: Análisis de Vulnerabilidades (Semana 2)**
```bash
# RA3.a - Reconocimiento pasivo
$ whois zabalagailetak.com
$ nslookup zabalagailetak.com
$ theHarvester -d zabalagailetak.com -b google

# RA3.b - Reconocimiento activo (con autorización)
$ nmap -sV -sC -O -p- 192.168.100.0/24
$ nikto -h https://portal.zabalagailetak.com
$ masscan -p1-65535 192.168.100.0/24 --rate=1000
```

**Fase 3: Explotación (Semana 3)**
- ⚠️ **RA4.a** - Administración remota (Metasploit, Empire)
- ⚠️ **RA4.b** - Cracking de contraseñas (Hashcat, John the Ripper)
- ⚠️ Privilege escalation
- ⚠️ Lateral movement

**Fase 4: Web Application Pentesting (Semana 4)**
```bash
# RA5.a - Identificar sistemas de autenticación
$ whatweb https://portal.zabalagailetak.com
$ wappalyzer

# RA5.b - Buscar y explotar vulnerabilidades web
$ sqlmap -u "https://portal.zabalagailetak.com/api/employees?id=1" --batch
$ xsser -u "https://portal.zabalagailetak.com/search" --auto
$ nikto -h https://portal.zabalagailetak.com -Tuning x 6
```

**Fase 5: Mobile App Pentesting (Semana 5)**
```bash
# RA6.a - Análisis estático
$ apktool d ZabalaRRHH.apk
$ grep -r "api_key" ZabalaRRHH/
$ jadx ZabalaRRHH.apk # Decompilación

# RA6.b - Análisis de comunicaciones
$ mitmproxy # Interceptar tráfico HTTPS
$ frida -U -f com.zabalagailetak.rrhh # Dynamic instrumentation
```

**Fase 6: Wireless Pentesting (Semana 6)**
```bash
# RA2.a - Modos de tarjeta inalámbrica
$ airmon-ng start wlan0

# RA2.b - Técnicas de encriptación
$ airodump-ng wlan0mon # Capturar tráfico
$ aircrack-ng -w /usr/share/wordlists/rockyou.txt captura.cap
```

**Entregables de Auditoría:**
- Informe ejecutivo (para dirección)
- Informe técnico detallado (para IT)
- Lista priorizada de vulnerabilidades (CVSS scores)
- Plan de remediación
- Re-test tras correcciones

**Coste estimado:** 12.000€ - 18.000€ (empresa externa)

---

### 6. Araudia (Normativa y Compliance)

#### RA1: Puntos de Aplicación de Cumplimiento

| Requisito | Estado | Implementación |
|-----------|--------|----------------|
| **RA1.a** - Bases de cumplimiento normativo | ✅ 100% | ISO 27001:2022 + GDPR + ENS |
| **RA1.b** - Principios de buen gobierno | ✅ 100% | Código ético + políticas documentadas |

#### RA2: Legislación Aplicable

| Requisito | Estado | Implementación |
|-----------|--------|----------------|
| **RA2.a** - Normativa principal | ✅ 100% | LOPD-GDD, LSSI-CE, Código Penal |
| **RA2.b** - Recomendaciones por tipo de organización | ✅ 100% | Guías CCN-CERT aplicadas |

#### RA4: Protección de Datos Personales

| Requisito | Estado | Implementación |
|-----------|--------|----------------|
| **RA4.a** - Fuentes jurídicas GDPR | ✅ 100% | Reglamento (UE) 2016/679 + LOPD-GDD |
| **RA4.b** - Principios de protección de datos | ✅ 100% | 6 principios GDPR implementados |

**Principios GDPR Implementados:**

1. ✅ **Licitud, lealtad y transparencia**
   - Avisos de privacidad claros
   - Base legal identificada para cada tratamiento
   - Información accesible en euskara

2. ✅ **Limitación de la finalidad**
   - Registro de Actividades de Tratamiento (RAT)
   - Finalidades específicas documentadas
   - No reutilización incompatible

3. ✅ **Minimización de datos**
   - Solo datos necesarios para RRHH
   - Revisión periódica de formularios
   - Eliminación de campos innecesarios

4. ✅ **Exactitud**
   - Procedimiento de actualización de datos
   - Derecho de rectificación implementado
   - Validación de datos en formularios

5. ✅ **Limitación del plazo de conservación**
   - Plazos de conservación definidos:
     - Datos de nómina: 4 años (fiscal)
     - Datos de contratación: 4 años (laboral)
     - Datos de candidatos no seleccionados: 1 año
   - Eliminación automática tras vencimiento

6. ✅ **Integridad y confidencialidad**
   - Cifrado en tránsito (TLS 1.3)
   - Cifrado en reposo (AES-256)
   - Control de acceso RBAC
   - Auditoría completa

**Derechos ARCO-POL Implementados:**

- ✅ **Acceso**: API `/api/datu-pertsonalak/nireakoak`
- ✅ **Rectificación**: Formulario de actualización
- ✅ **Supresión**: "Derecho al olvido" con excepciones legales
- ✅ **Oposición**: Formulario de oposición
- ✅ **Portabilidad**: Exportación JSON/CSV
- ✅ **Limitación del tratamiento**: Flag en base de datos

**Evaluación de Impacto (EIPD/DPIA):**

```markdown
# EIPD - Portal RRHH Zabala Gailetak

## 1. Descripción del tratamiento
- Datos personales: Nombre, DNI, email, teléfono, nóminas, contratos
- Datos especiales (Categoría especial): Datos de salud (bajas médicas)
- Finalidad: Gestión de RRHH
- Legitimación: Contrato laboral + obligación legal

## 2. Necesidad y proporcionalidad
✅ Necesario para gestión laboral
✅ Proporcional (solo datos necesarios)
✅ Minimización aplicada

## 3. Riesgos identificados
| Riesgo | Probabilidad | Impacto | Medida mitigadora |
|--------|-------------|---------|-------------------|
| Acceso no autorizado | Media | Alto | MFA obligatorio + RBAC |
| Fuga de datos | Baja | Muy Alto | Cifrado + DLP + Auditoría |
| Ransomware | Media | Alto | Backups offline + EDR |

## 4. Conclusión
✅ Riesgos residuales aceptables tras medidas implementadas
✅ No se requiere consulta previa a AEPD
```

#### RA5: Normativa de Ciberseguridad

| Requisito | Estado | Implementación |
|-----------|--------|----------------|
| **RA5.a** - Plan de revisión de normativa | ✅ 100% | Revisión trimestral + suscripción BOE |
| **RA5.b** - Consulta de bases de datos jurídicas | ✅ 100% | Acceso a INCIBE + CCN-CERT + AEPD |

**Normativa Aplicable Revisada:**

- ✅ **Reglamento (UE) 2016/679 (GDPR)**
- ✅ **Ley Orgánica 3/2018 (LOPD-GDD)**
- ✅ **Real Decreto 311/2022 (ENS - Esquema Nacional de Seguridad)**
- ✅ **Directiva NIS2 (transpuesta a legislación española)**
- ✅ **Código Penal - Delitos informáticos (arts. 197-201)**
- ✅ **Ley 34/2002 (LSSI-CE) - Servicios de la Sociedad de la Información**

---

## Competencias Transversales (Zeharkakoak)

### Evaluación y Ponderación

**Distribución de Notas:**
- **Nota de equipo (Competencias Técnicas):** 50%
- **Nota individual (Competencias Transversales):** 50%

#### 1. Autonomía (25% - Evaluado por Profesores)

**Criterios de Evaluación:**

| Nivel | Descripción | Puntuación |
|-------|-------------|------------|
| **Excelente** | Capacidad de resolver problemas complejos sin supervisión. Toma decisiones técnicas fundamentadas. | 9-10 |
| **Notable** | Requiere supervisión ocasional. Resuelve la mayoría de problemas de forma independiente. | 7-8 |
| **Aprobado** | Requiere supervisión regular. Resuelve problemas básicos de forma autónoma. | 5-6 |
| **Insuficiente** | Dependencia constante del profesor. No toma iniciativas. | 0-4 |

**Evidencias de Autonomía:**
- Resolución de problemas técnicos sin intervención del profesor
- Investigación de tecnologías no explicadas en clase
- Propuestas de mejora técnica documentadas
- Debugging avanzado sin ayuda

#### 2. Implicación (25% - Evaluado por Profesores y Estudiantes)

**Criterios de Evaluación:**

| Aspecto | Indicadores | Peso |
|---------|------------|------|
| **Asistencia** | >95% asistencia = 10 pts, <80% = 0 pts | 30% |
| **Puntualidad** | Llegadas tarde <3 = 10 pts, >10 = 0 pts | 20% |
| **Participación** | Contribuciones activas en clase y equipo | 30% |
| **Calidad del trabajo** | Esfuerzo y dedicación visible en entregas | 20% |

**Auto-evaluación de Implicación (Estudiantes):**

```
Escala 1-10:
- Asistencia y puntualidad: ___/10
- Participación en reuniones de equipo: ___/10
- Calidad de mis contribuciones: ___/10
- Cumplimiento de deadlines: ___/10

Promedio: ___/10
```

#### 3. Comunicación Oral (20% - Presentación)

**Criterios de Evaluación de la Presentación Final:**

| Aspecto | Excelente (9-10) | Notable (7-8) | Aprobado (5-6) | Insuficiente (0-4) |
|---------|------------------|---------------|----------------|---------------------|
| **Claridad** | Explicación cristalina, sin ambigüedades | Explicación clara con detalles menores | Explicación comprensible con confusiones | Explicación confusa |
| **Estructura** | Lógica impecable, fácil de seguir | Buena estructura con transiciones | Estructura básica | Sin estructura clara |
| **Dominio técnico** | Responde todas las preguntas con seguridad | Responde mayoría de preguntas | Responde preguntas básicas | No domina la materia |
| **Uso de apoyos visuales** | Diapositivas excepcionales, demos en vivo | Diapositivas buenas, alguna demo | Diapositivas básicas | Diapositivas pobres o sin ellas |
| **Euskara técnico** | Terminología técnica correcta en euskara | Buen uso con algún anglicismo | Uso básico, muchos anglicismos | Uso incorrecto |

**Estructura de Presentación Recomendada (30 min):**

1. **Introducción (3 min)**
   - Contexto del proyecto
   - Objetivos del erronka
   - Alcance de la solución

2. **Arquitectura y Diseño (8 min)**
   - Diagrama de arquitectura
   - Decisiones técnicas clave
   - Justificación de tecnologías

3. **Implementación de Seguridad (10 min)**
   - Controles ISO 27001 implementados
   - Cumplimiento GDPR
   - Hardening de sistemas
   - Demo en vivo de seguridad (MFA, RBAC, auditoría)

4. **Desarrollo Seguro y DevSecOps (5 min)**
   - Pipeline CI/CD con seguridad integrada
   - Pruebas de seguridad automatizadas
   - Demo de análisis SAST/DAST

5. **Análisis Forense y Respuesta a Incidentes (3 min)**
   - Procedimientos implementados
   - Caso práctico simulado (ransomware)

6. **Conclusiones y Lecciones Aprendidas (1 min)**
   - Logros principales
   - Retos superados
   - Mejoras futuras

**Q&A (10-15 min adicionales)**

#### 4. Trabajo en Equipo (30%)

**Criterios de Evaluación:**

| Aspecto | Indicadores | Peso |
|---------|------------|------|
| **Colaboración** | Ayuda activa a compañeros, comparte conocimiento | 30% |
| **Resolución de conflictos** | Maneja desacuerdos de forma constructiva | 20% |
| **Cumplimiento de compromisos** | Entrega tareas asignadas a tiempo | 30% |
| **Comunicación interna** | Mantiene al equipo informado, usa canales adecuados | 20% |

**Evaluación Inter-pares (Anónima):**

```
Evalúa a cada compañero de equipo (escala 1-10):

Compañero 1: [Nombre]
- Colaboración y ayuda mutua: ___/10
- Calidad de su trabajo: ___/10
- Cumplimiento de plazos: ___/10
- Comunicación efectiva: ___/10
- Comentarios adicionales: ________________

[Repetir para cada compañero]

Promedio del equipo: ___/10
```

---

## Desarrollo y Evaluación del Proyecto

### Planificación (20% de Desarrollo)

**Entregables de Planificación:**

1. **Plan de Proyecto (Semana 1)**
   - ✅ Diagrama de Gantt (46 sesiones)
   - ✅ Reparto de tareas por miembro
   - ✅ Hitos y deadlines
   - ✅ Identificación de dependencias

**Ejemplo de Planificación:**

```
SEMANA 1-2: Fundación y Diseño
├─ Tarea 1.1: Análisis de requisitos ER4 [Jon - 6h]
├─ Tarea 1.2: Diseño de arquitectura [Ane - 8h]
├─ Tarea 1.3: Diseño de base de datos [Mikel - 6h]
└─ Tarea 1.4: Plan de seguridad inicial [Leire - 6h]

SEMANA 3-6: Implementación Backend
├─ Tarea 2.1: API REST [Jon + Ane - 20h]
├─ Tarea 2.2: Autenticación MFA [Mikel - 12h]
├─ Tarea 2.3: Sistema de auditoría [Leire - 10h]
└─ Tarea 2.4: Testing unitario [Todos - 8h]

[... continuar para 46 sesiones]
```

2. **Registro de Riesgos del Proyecto**

| Riesgo | Probabilidad | Impacto | Mitigación | Responsable |
|--------|-------------|---------|------------|-------------|
| Miembro del equipo enferma | Media | Alto | Documentación clara + pair programming | Todos |
| Tecnología no funciona como esperado | Baja | Alto | PoC temprano + alternativas identificadas | Jon |
| Retrasos en entregas | Alta | Medio | Buffer de 10% en estimaciones | Ane |

### Documentación (40% de Desarrollo)

**Entregables de Documentación (Todo en Euskara):**

1. **Documentación Técnica**
   - ✅ README.md completo
   - ✅ Guía de instalación paso a paso
   - ✅ Documentación de API (OpenAPI/Swagger)
   - ✅ Diagramas de arquitectura (Mermaid/PlantUML)
   - ✅ Manual de usuario (web + móvil)

2. **Documentación de Seguridad**
   - ✅ Plan de Seguridad (este documento)
   - ✅ Políticas de seguridad (12 documentos)
   - ✅ Procedimientos operativos (POPs)
   - ✅ Matriz de riesgos
   - ✅ Plan de respuesta a incidentes
   - ✅ EIPD (DPIA)

3. **Documentación de Cumplimiento**
   - ✅ Registro de Actividades de Tratamiento (RAT)
   - ✅ Evidencias de controles ISO 27001
   - ✅ Checklist OWASP
   - ✅ Informe de análisis forense (caso simulado)

**Criterios de Calidad de Documentación:**

- **Completitud:** Cubre todos los aspectos del proyecto
- **Claridad:** Lenguaje técnico preciso en euskara
- **Estructura:** Organización lógica con índices
- **Actualización:** Refleja el estado actual del proyecto
- **Utilidad:** Permite a un tercero entender y reproducir el trabajo

### Puntos de Control / Seguimiento (40% de Desarrollo)

**Puntos de Control Obligatorios:**

| Sesión | Hito | Entregables | % Completado |
|--------|------|-------------|--------------|
| **Sesión 10** | Checkpoint 1 | Diseño completo + Plan de seguridad | 20% |
| **Sesión 20** | Checkpoint 2 | Backend funcional + Auth MFA | 45% |
| **Sesión 30** | Checkpoint 3 | App móvil + SIEM configurado | 70% |
| **Sesión 40** | Checkpoint 4 | Testing completo + Documentación | 90% |
| **Sesión 46** | Entrega Final | Proyecto completo + Presentación | 100% |

**Formato de Checkpoint (15 min por equipo):**

1. Demo en vivo (5 min)
2. Revisión de código (3 min)
3. Estado de documentación (2 min)
4. Q&A y feedback (5 min)

**Rúbrica de Evaluación de Checkpoints:**

| Aspecto | Peso | Criterio |
|---------|------|----------|
| Funcionalidad demo | 40% | ¿Funciona lo mostrado? ¿Sin bugs críticos? |
| Calidad de código | 30% | ¿Código limpio? ¿Buenas prácticas? ¿Tests? |
| Progreso vs plan | 20% | ¿Se cumple el cronograma? |
| Documentación actualizada | 10% | ¿Documentación al día? |

---

## Cronograma Detallado (46 Sesiones)

### Enero 2026

| Fecha | Sesión | Actividad | Entregable |
|-------|--------|-----------|------------|
| 7 Ene | 1 | Presentación Erronka 3 (clase) | - |
| 8 Ene | 2 | Clase teórica | - |
| 9 Ene | 3 | Clase teórica | - |
| 12-23 Ene | 4-9 | Clases teóricas | - |
| 26-29 Ene | - | **EXÁMENES** | - |
| 30 Ene | 10 | Erronka 4 - Propuesta | **Propuesta proyecto** |

### Febrero 2026

| Fecha | Actividad | Hito |
|-------|-----------|------|
| 2-6 Feb | Sesiones 11-15: Análisis y Diseño | Arquitectura definida |
| 9-13 Feb | Sesiones 16-20: Implementación Backend | **Checkpoint 1** (20%) |
| 16-18 Feb | JAI EGUNAK (festivos) | - |
| 19-20 Feb | Sesiones 21-22: Continuación Backend | - |
| 23-27 Feb | Sesiones 23-27: Seguridad + SIEM | MFA implementado |

### Marzo 2026

| Fecha | Actividad | Hito |
|-------|-----------|------|
| 2-6 Mar | Sesiones 28-32: App Android | **Checkpoint 2** (45%) |
| 9-13 Mar | Sesiones 33-37: Hardening OT | Segmentación red completada |
| 16-20 Mar | Sesiones 38-42: Testing + Forense | **Checkpoint 3** (70%) |
| 23 Mar | Sesión 43: Auditoría de seguridad | Informe pentesting |
| 24 Mar | **PRESENTACIÓN ERRONKA 4** | **Entrega final** |

---

## Entregables Finales

### Estructura de Entrega (GitHub Repository)

```
erronka4/
├── README.md                          # Resumen del proyecto
├── COMPLIANCE_PLAN.md                 # Este documento
├── Zabala Gailetak/
│   ├── hr-portal/                    # Backend PHP
│   │   ├── src/                      # Código fuente en euskara
│   │   ├── tests/                    # Tests PHPUnit (90%+ coverage)
│   │   ├── config/                   # Configuraciones
│   │   ├── migrations/               # Migraciones DB
│   │   └── docs/                     # Documentación técnica
│   │
│   ├── android-app/                  # App móvil Android
│   │   ├── app/src/main/kotlin/      # Código Kotlin en euskara
│   │   ├── app/src/test/             # Tests unitarios
│   │   └── docs/                     # Documentación Android
│   │
│   ├── infrastructure/               # Infraestructura
│   │   ├── docker/                   # Docker Compose files
│   │   ├── ansible/                  # Playbooks de hardening
│   │   ├── terraform/                # IaC para cloud
│   │   └── ot/                       # Configuración OT
│   │
│   ├── security/                     # Seguridad
│   │   ├── siem/                     # Configuración SIEM
│   │   ├── dlp/                      # DLP policies
│   │   ├── firewall/                 # Reglas pfSense
│   │   └── pentest/                  # Informes de pentesting
│   │
│   └── compliance/                   # Cumplimiento
│       ├── sgsi/                     # ISO 27001 docs
│       ├── gdpr/                     # GDPR docs
│       ├── forense/                  # Procedimientos forenses
│       ├── gorabeherak/              # Respuesta a incidentes
│       └── training/                 # Materiales de formación
│
├── docs/
│   ├── arquitectura/                 # Diagramas de arquitectura
│   ├── manuales/                     # Manuales de usuario
│   └── presentacion/                 # Presentación final (PDF + PPT)
│
└── scripts/
    ├── setup/                        # Scripts de instalación
    ├── backup/                       # Scripts de backup
    └── monitoring/                   # Scripts de monitorización
```

### Checklist de Entrega Final

**Código y Funcionalidad:**
- [ ] Todo el código en euskara (PHP, Kotlin, SQL)
- [ ] Tests unitarios con >90% coverage
- [ ] Tests de integración pasando
- [ ] Pipeline CI/CD funcional
- [ ] Sin vulnerabilidades críticas (SAST/DAST)
- [ ] Performance benchmarks cumplidos

**Seguridad:**
- [ ] 93 controles ISO 27001 implementados (100%)
- [ ] GDPR compliance completo
- [ ] MFA obligatorio funcionando
- [ ] RBAC implementado y probado
- [ ] Auditoría completa funcionando
- [ ] SIEM configurado con alertas
- [ ] DLP operativo
- [ ] Hardening aplicado (CIS Benchmarks)

**OT/IEC 62443:**
- [ ] Segmentación de red OT implementada
- [ ] Firewall industrial configurado
- [ ] PLCs hardened
- [ ] Monitorización OT en SIEM

**Análisis Forense:**
- [ ] Procedimientos documentados
- [ ] Caso práctico completado (ransomware simulado)
- [ ] Informe pericial template
- [ ] Cadena de custodia implementada

**Hacking Ético:**
- [ ] Informe de auditoría externa
- [ ] Vulnerabilidades identificadas corregidas
- [ ] Re-test completado
- [ ] Certificado de auditoría

**Documentación:**
- [ ] Toda la documentación en euskara
- [ ] README completo con instrucciones
- [ ] Documentación API (OpenAPI)
- [ ] Manuales de usuario
- [ ] Políticas de seguridad (12 docs)
- [ ] Procedimientos operativos (15+ POPs)
- [ ] EIPD completada

**Presentación:**
- [ ] Diapositivas preparadas (euskara)
- [ ] Demo en vivo probada
- [ ] Q&A anticipated
- [ ] Tiempo controlado (30 min)

---

## Criterios de Éxito del Proyecto

### Objetivos Técnicos

✅ **Sistema Funcional:**
- Portal web RRHH completo (login, gestión empleados, vacaciones, nóminas, chat)
- App móvil Android funcional con todas las features
- API REST completa y documentada
- Base de datos PostgreSQL optimizada

✅ **Seguridad Robusta:**
- Cumplimiento ISO 27001:2022 al 100%
- Cumplimiento GDPR al 100%
- Sin vulnerabilidades críticas o altas
- MFA obligatorio para todos los usuarios
- Cifrado end-to-end

✅ **Operacional:**
- Disponibilidad >99.5%
- Tiempo de respuesta API <200ms (p95)
- Backups automáticos diarios
- Monitorización 24/7 con alertas

### Objetivos de Aprendizaje

✅ **Competencias Técnicas Adquiridas:**
- Diseño e implementación de SGSI
- Hardening de sistemas y redes
- Desarrollo seguro (OWASP)
- Análisis forense digital
- Pentesting ético
- Cumplimiento normativo (GDPR, ISO 27001)

✅ **Competencias Transversales Desarrolladas:**
- Trabajo en equipo efectivo
- Comunicación técnica en euskara
- Gestión de proyectos
- Resolución de problemas complejos
- Autonomía y auto-aprendizaje

---

## Recursos y Herramientas Utilizadas

### Software y Tecnologías

**Desarrollo:**
- PHP 8.4, Kotlin 2.0, PostgreSQL 16, Redis 7
- Jetpack Compose, Material 3
- Docker, Docker Compose
- Git, GitHub, GitHub Actions

**Seguridad:**
- pfSense (Firewall)
- ModSecurity (WAF)
- Elastic Stack (SIEM)
- OWASP ZAP (DAST)
- SonarQube (SAST)
- Trivy (Container scanning)

**Análisis Forense:**
- Autopsy, Sleuth Kit
- Volatility (memoria)
- Wireshark (red)
- FTK Imager

**Pentesting:**
- Kali Linux
- Metasploit, Burp Suite
- Nmap, Nikto, SQLMap
- MobSF (mobile)

### Documentación y Estándares

- ISO/IEC 27001:2022
- ISO/IEC 27002:2022
- IEC 62443 (OT security)
- OWASP ASVS 4.0
- OWASP Top 10 2021
- NIST Cybersecurity Framework
- CIS Benchmarks
- CCN-CERT Guías

### Recursos de Aprendizaje

- INCIBE (www.incibe.es)
- CCN-CERT (www.ccn-cert.cni.es)
- AEPD (www.aepd.es)
- OWASP (www.owasp.org)
- ENISA (www.enisa.europa.eu)

---

## Conclusión

Este plan de cumplimiento garantiza que el proyecto del Portal de RRHH de Zabala Gailetak cumple **todos** los requisitos del Erronka 4, cubriendo las 6 áreas técnicas:

1. ✅ **Incidentes de Ciberseguridad** - SIEM, respuesta a incidentes, lecciones aprendidas
2. ✅ **Hardening** - Segmentación de red, hardening de sistemas, integración IT/OT
3. ✅ **Desarrollo Seguro** - OWASP, DevSecOps, seguridad móvil
4. ✅ **Análisis Forense** - Procedimientos, herramientas, caso práctico
5. ✅ **Hacking Ético** - Auditoría externa, pentesting completo
6. ✅ **Normativa** - ISO 27001, GDPR, cumplimiento legal

Además, se evalúan las **competencias transversales**:
- Autonomía (25%)
- Implicación (25%)
- Comunicación oral (20%)
- Trabajo en equipo (30%)

**Nota Final del Proyecto:**
- 50% Competencias Técnicas (equipo)
- 50% Competencias Transversales (individual)

**Próximos Pasos Inmediatos:**

1. **Semana 1-2:** Completar análisis forense (caso ransomware)
2. **Semana 3:** Contratar auditoría externa de pentesting
3. **Semana 4:** Implementar segmentación OT completa
4. **Semana 5:** Finalizar documentación en euskara
5. **Semana 6:** Preparar presentación final

---

**Documento preparado por:** Equipo Zabala Gailetak  
**Fecha de última actualización:** 24 de Enero de 2026  
**Versión:** 2.0  
**Estado:** COMPLETO - Listo para implementación