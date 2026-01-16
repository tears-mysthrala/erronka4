# PRESUPUESTO ZABALA GAILETAK — SEGURIDAD INTEGRAL IT/OT (2026)

**Cliente**: Zabala Gailetak S.A. (Panificadora industrial, 120 empleados, País Vasco)  
**Alcance**: Seguridad OT/ICS + SIEM/SOC + Honeypots + Portal RRHH  
**Inversión (Year 1)**: €733.950  
**Coste recurrente (Year 2+)**: €129.000/año (servicios y mantenimiento)  
**Calendario**: 10 meses (Enero–Diciembre 2026)  
**ROI estimado**: 137,6% a 3 años (beneficios estimados: €786.000/año)

**Versión**: 1.0 (borrador de propuesta)  
**Fecha**: 16/01/2026  

---

## Aviso de confidencialidad

Este documento contiene información sensible de arquitectura y seguridad. Su distribución queda restringida a Zabala Gailetak S.A. y a terceros autorizados.

---

## Índice

- Parte I — Resumen Ejecutivo
  - 1. Contexto y motivación
  - 2. Objetivos y alcance
  - 3. Situación actual y riesgos
  - 4. Solución propuesta (pilares)
  - 5. Inversión, retorno y modelo de contratación
  - 6. Plan de implementación (alto nivel)
  - 7. Decisión requerida
- Parte II — Propuesta Comercial
  - 8. Paquetes de servicio
  - 9. Precios detallados e hitos de pago
  - 10. Términos comerciales
  - 11. Acuerdos de nivel de servicio (SLA)
  - 12. Propuesta de valor y diferenciación
  - 13. Entregables (resumen comercial)
- Parte III — Especificaciones Técnicas
  - 14. Arquitectura IT/OT (Modelo Purdue)
  - 15. Implementación de seguridad OT
  - 16. SIEM & SOC (Wazuh vs ELK)
  - 17. Arquitectura y despliegue de honeypots
  - 18. Especificaciones técnicas del HR Portal
  - 19. Mapeo de cumplimiento (ISO/IEC/GDPR/NIS2)
  - 20. Plan de implementación detallado
  - 21. Gestión de riesgos y FMEA
  - 22. Anexos técnicos

---

# PARTE I — RESUMEN EJECUTIVO

## 1. Contexto y motivación

Zabala Gailetak opera una planta industrial con dependencia elevada de automatización (SCADA/HMI/PLC). La convergencia IT/OT incrementa la exposición a incidentes (ransomware, interrupción de producción, accesos indebidos a redes industriales) y genera presión adicional por cumplimiento (GDPR y NIS2), así como por requisitos de clientes B2B.

Este programa aborda dos necesidades estratégicas simultáneas:

- **Seguridad y continuidad operativa**: proteger la producción y reducir riesgo de parada.
- **Digitalización y gobierno**: mejorar trazabilidad y protección de datos, especialmente en RRHH.

## 2. Objetivos y alcance

### 2.1. Objetivos

- **Continuidad operativa**: reducir probabilidad e impacto de interrupciones de planta mediante segmentación, control de accesos, hardening y procedimientos de backup/DR.
- **Detección temprana**: centralizar logs y correlación (SIEM) con casos de uso OT/ICS y telemetría de seguridad.
- **Respuesta y gobierno**: operación SOC (8x5 base; 24x7 opcional), playbooks de respuesta y reporting ejecutivo.
- **Visibilidad de amenazas externas**: honeypots aislados para capturar TTPs y alimentar inteligencia (IOC) al SIEM.
- **Portal RRHH seguro**: HR Portal (web + Android) con RBAC, MFA, auditoría y controles GDPR (Art. 32/35/88).

### 2.2. Alcance del programa

El programa se organiza en **4 pilares**, más gestión/auditoría:

1. **Seguridad OT/ICS**: inventario, arquitectura Purdue, segmentación, hardening PLC, jump host, procedimientos y continuidad.
2. **SIEM & SOC**: Wazuh recomendado; integración de fuentes IT/OT/aplicación; alertas; dashboards; operación.
3. **Honeypots**: T-Pot + Conpot; red aislada; pipeline de análisis e inteligencia.
4. **HR Portal**: backend PHP, frontend web, app Android, despliegue y mantenimiento.

Fuera de alcance (salvo contratación adicional): sustitución masiva de PLC/SCADA legacy, renovación total de ERP, certificación final de ISO 27001 sin auditoría externa específica.

## 3. Situación actual y principales riesgos

### 3.1. Riesgos OT

- **Pivot IT→OT**: compromiso en IT que afecte SCADA/PLC.
- **Parada de producción** por cambios no controlados o segmentación deficiente.
- **Legacy/obsolescencia**: limitación de parches y controles nativos.

### 3.2. Riesgos IT/RRHH

- **Exposición de datos personales** (empleados, nóminas, documentos) y obligaciones GDPR.
- **Obligaciones NIS2**: medidas de riesgo, incidentes, continuidad y cadena de suministro.

### 3.3. Riesgos de detección

- Sin telemetría centralizada y correlación, los incidentes se detectan tarde.
- Sin señales externas (telemetría de ataque), se pierde inteligencia útil para prevención.

## 4. Solución propuesta (visión de alto nivel)

La propuesta implementa una arquitectura que separa IT y OT (Modelo Purdue), habilita una **DMZ industrial (nivel 3.5)**, añade un **jump host con MFA** como vía controlada, y centraliza telemetría mediante **SIEM Wazuh**.

Elementos clave:

- Segmentación y control de flujos: VLAN/ACL + firewalls con enfoque “whitelist”.
- Hardening OT: procedimientos por fabricante (Siemens/Rockwell) y control de cambios.
- SIEM: 30 fuentes de logs (IT/OT/aplicación), 50+ reglas, dashboards por rol.
- Honeypots: red aislada, correlación con SIEM e IOC.
- HR Portal: seguridad por diseño (RBAC, MFA, auditoría, validaciones, cifrado, backups).

## 5. Inversión, retorno y modelo de contratación

### 5.1. Resumen económico

- Inversión Year 1: **€733.950**
- Coste recurrente Year 2+: **€129.000/año**
- ROI estimado: **137,6% a 3 años** (beneficios estimados: **€786.000/año**)

La inversión se justifica por reducción de riesgo (coste esperado de incidentes y paradas) y mejora de eficiencia administrativa en RRHH.

### 5.2. Modelo de contratación

- Contratación por **paquetes** (Básico/Profesional/Empresarial) o como programa integral.
- Pago por **hitos** vinculados a entregables verificables (auditoría OT, SIEM operativo, segmentación, entregables HR Portal, go-live).

## 6. Plan de implementación (alto nivel)

Calendario de 10 meses con entregas incrementales y ventanas de cambio coordinadas con producción:

- Meses 1–2: discovery, inventario, arquitectura, riesgos, bases HR Portal.
- Meses 2–4: SIEM inicial, segmentación, jump host, HR Portal (auth + CRUD base).
- Meses 4–6: hardening PLC, honeypots, tuning SIEM/SOC, HR Portal (empleados completo).
- Meses 6–8: módulos HR (vacaciones, nóminas), pruebas integradas.
- Meses 8–10: pruebas de seguridad/rendimiento, formación, go-live y soporte.

## 7. Decisión requerida

Para iniciar el programa se requiere:

1. Confirmación de **modalidad**: paquete seleccionado o implantación integral.
2. Validación de **ventanas de mantenimiento** y disponibilidad del equipo OT.
3. Nombramiento de **responsables** (IT/OT/RRHH) para aceptación de hitos.

---

# PARTE II — PROPUESTA COMERCIAL

## 8. Paquetes de servicio

### 8.1. Resumen

| Paquete | Denominación | Duración estimada | Inversión (Year 1) | Perfil recomendado |
|---|---|---:|---:|---|
| Básico | OT Foundation | 3 meses | €180.000 | Primer salto en OT, visibilidad y formación |
| Profesional | OT Advanced | 5 meses | €324.000 | Segmentación + detección ampliada + honeypots |
| Empresarial | OT Enterprise | 10 meses | €733.950 | Programa completo IT/OT + HR Portal + SOC ampliado |

### 8.2. Alcance por paquete

**Básico — OT Foundation (€180.000)**

- Auditoría OT y toma de inventario.
- SIEM base con operación 8x5 (monitorización + reporting).
- Formación base (40h).
- Soporte estándar y documentación.

**Profesional — OT Advanced (€324.000)**

- Incluye Básico.
- Segmentación IT/OT (VLAN/ACL, firewalls) y jump host con MFA.
- Honeypots aislados e integración con SIEM.
- SIEM avanzado (casos de uso OT, dashboards por rol).
- Formación avanzada (80h) y soporte prioritario.

**Empresarial — OT Enterprise (€733.950)**

- Incluye Profesional.
- HR Portal (web + Android) con seguridad y auditoría.
- SOC 24x7 durante 6 meses (arranque) y continuidad opcional.
- Integración completa IT/OT, pruebas y preparación de auditoría.
- Formación completa (120h) y soporte 24x7 (según contrato).

### 8.3. Matriz comparativa

| Característica | Básico | Profesional | Empresarial |
|---|:---:|:---:|:---:|
| Auditoría OT / inventario | Sí | Sí | Sí |
| SIEM 8x5 | Sí | Sí | Sí |
| Segmentación y jump host | No | Sí | Sí |
| Honeypots (aislados) | No | Sí | Sí |
| HR Portal | No | No | Sí |
| SOC 24x7 (arranque) | No | No | Sí |
| Soporte | Estándar | Prioritario | 24x7 (según contrato) |
| Formación | 40h | 80h | 120h |

## 9. Precios detallados e hitos de pago

### 9.1. Desglose por pilar (Year 1)

| Pilar | Alcance resumido | Importe |
|---|---|---:|
| Pilar 1 — Seguridad OT/ICS | Inventario/auditoría, segmentación, hardening PLC, jump host, documentación y formación | €180.000 |
| Pilar 2 — SIEM & SOC | Plataforma, integración de logs, casos de uso/alertas y servicio SOC inicial | €120.000 |
| Pilar 3 — Honeypots | Plataforma, honeypots ICS y su integración/correlación con SIEM | €24.000 |
| Pilar 4 — HR Portal | Backend, web frontend, Android app y despliegue | €300.000 |
| PM & Auditoría | Gestión de proyecto, auditoría de cumplimiento y evaluación de riesgos | €110.000 |

Nota: algunas partidas se presentan redondeadas; el total contractual aplicable a hitos es **€733.950**.

### 9.2. Hitos de pago

| Hito | Condición de facturación (resumen) | % | Importe |
|---|---|---:|---:|
| Hito 1 | Firma de contrato y plan de proyecto aprobado | 30% | €220.185 |
| Hito 2 | Auditoría OT completa + SIEM inicial operativo | 20% | €146.790 |
| Hito 3 | Segmentación y jump host + HR Portal fase 1 | 20% | €146.790 |
| Hito 4 | Implementación completa + UAT superada | 20% | €146.790 |
| Hito 5 | Go-live + aceptación formal | 10% | €73.395 |

### 9.3. Costes recurrentes (Year 2+)

**Total estimado**: €129.000/año

- SIEM monitoring: €24.000/año
- SOC services (opcional): €60.000/año
- HR Portal maintenance: €30.000/año
- Security updates: €15.000/año

### 9.4. Add-ons opcionales

- Penetration testing anual: €12.000
- Incident response retainer: €15.000
- Formación adicional: €1.500/día
- Auditorías on-site trimestrales: €8.000/año

## 10. Términos comerciales

### 10.1. Garantías

- Software (HR Portal): 12 meses sobre defectos.
- Consultoría: 6 meses sobre entregables.
- Hardware: 3 años según fabricante.
- Ajustes de configuración: 90 días.

### 10.2. Condiciones de pago

- Pago neto a 30 días.
- Mora: 1,5% mensual.
- Pronto pago: 3% si se paga en 10 días.

### 10.3. Duración

- Year 1: 10 meses de implementación.
- Year 2+: operación/mantenimiento opcional.

### 10.4. Responsabilidad y propiedad

- Límite de responsabilidad: €733.950.
- PI del HR Portal: propiedad Zabala tras pago completo.

## 11. SLA

### 11.1. Tiempos de respuesta SIEM/SOC

| Severidad | Detección | Respuesta | Resolución |
|---|---:|---:|---:|
| Crítico | 5 min | 15 min | 4 horas |
| Alto | 15 min | 1 hora | 24 horas |
| Medio | 1 hora | 4 horas | 5 días |
| Bajo | 4 horas | 24 horas | 30 días |

### 11.2. Disponibilidad

- SIEM: 99,5%.
- HR Portal: 99,0% (07:00–23:00).
- Honeypots: 95%.
- Red OT: 99,9% (objetivo).

## 12. Propuesta de valor y diferenciación

- Especialización OT/ICS (IEC 62443).
- Experiencia sector alimentario.
- Presencia local y capacidad on-site.
- Enfoque dual de cumplimiento (ISO 27001 + IEC 62443).
- Documentación y formación en euskera.

## 13. Entregables (resumen)

- Arquitectura Purdue, VLAN/ACL y reglas de firewall.
- Inventario OT y hardening PLC (procedimientos).
- SIEM operativo (integraciones, reglas, dashboards, reporting).
- Honeypots operativos e integrados.
- HR Portal (web + Android) con módulos acordados, auditoría y backups.
- Mapeo de cumplimiento y evidencias.
- Formación (40h/80h/120h según paquete).

---

# PARTE III — ESPECIFICACIONES TÉCNICAS

## 14. Arquitectura IT/OT (Modelo Purdue)

### 14.1. Principios de diseño

- Separación de dominios IT/OT y reducción de superficie de ataque.
- Control de accesos a OT únicamente mediante vías gobernadas (jump host).
- DMZ industrial como zona de servicios compartidos (SIEM, bastión, gestión de parches).
- Enfoque “deny by default” con listas blancas por flujo.

### 14.2. Modelo Purdue (referencia)

El diseño propuesto separa claramente IT y OT, e introduce una **DMZ industrial (Nivel 3.5)** como punto de control para:

- Servicios de monitorización y gobierno (SIEM/SOC).
- Acceso remoto controlado (jump host con MFA y registro de sesión).
- Servicios intermedios (historiador, patch management).

Diagrama de referencia:

```text
┌─────────────────────────────────────────────────────────────┐
│ NIVEL 4: Red Empresa (IT)                                   │
│ - ERP (Odoo/SAP)                                            │
│ - Correo (Exchange/Postfix)                                 │
│ - File servers / NAS                                        │
│ - HR Portal (web + API)                                     │
│ - Puestos oficina (120 usuarios)                            │
└──────────────────┬──────────────────────────────────────────┘
             │ Firewall A (IT)
             │ Política: allow solo servicios necesarios; logging activado
┌──────────────────▼──────────────────────────────────────────┐
│ NIVEL 3.5: DMZ Industrial                                   │
│ - SIEM (Wazuh + stack de visualización)                     │
│ - Patch management (WSUS/Landscape)                         │
│ - Jump host (bastión con MFA + session recording)           │
│ - Historian (InfluxDB/TimescaleDB, si aplica)               │
│ - Honeypots (T-Pot / Conpot) — AISLADOS                     │
└──────────────────┬──────────────────────────────────────────┘
             │ Firewall B (industrial)
             │ Política: whitelist de flujos OT; inspección protocolos
┌──────────────────▼──────────────────────────────────────────┐
│ NIVEL 3: Operaciones (OT)                                   │
│ - SCADA (Ignition/WinCC)                                    │
│ - HMIs (p. ej. Siemens TP1200)                              │
│ - Estación de ingeniería (TIA Portal / Factory I/O)         │
│ - OpenPLC (simulación, si aplica)                           │
└──────────────────┬──────────────────────────────────────────┘
             │ Switches gestionables (segmentación)
┌──────────────────▼──────────────────────────────────────────┐
│ NIVEL 2: Control                                            │
│ - PLCs (Siemens S7-1500, Allen-Bradley CompactLogix)        │
│ - RTUs (si aplica)                                          │
└──────────────────┬──────────────────────────────────────────┘
             │ Ethernet industrial (Profinet/EtherNet/IP)
┌──────────────────▼──────────────────────────────────────────┐
│ NIVEL 1/0: Campo                                            │
│ - Variadores, hornos, robots, sensores y actuadores         │
└─────────────────────────────────────────────────────────────┘
```

### 14.3. VLAN y direccionamiento

- VLAN 10: IT oficina (192.168.10.0/24)
- VLAN 20: DMZ industrial (10.10.20.0/24)
- VLAN 30: SCADA/HMI (10.10.30.0/24)
- VLAN 40: PLC/control (10.10.40.0/24)
- VLAN 50: campo (10.10.50.0/24)
- VLAN 99: honeypot (172.16.99.0/24) — aislada

Reglas de diseño:

- Subredes OT y DMZ con direccionamiento independiente y documentación de rutas.
- VLAN de honeypot sin routing hacia redes IT/OT (solo salida controlada e imprescindible).
- Gestión de red (switches/firewalls) con acceso restringido y auditado.

### 14.4. Control de flujos (ejemplos)

- Permitir: Jump host (DMZ) → SCADA (RDP con MFA y registro).
- Permitir: SIEM (DMZ) ← logs (syslog/agents) desde IT/OT.
- Denegar: IT → PLC directo.
- Denegar: OT → Internet (salvo excepciones controladas).

Requisitos de control y trazabilidad:

- Logging en firewalls A/B: permitir auditoría de flujos, y alimentar SIEM.
- En jump host: MFA obligatorio, registro de sesión (comandos/pantalla) y trazabilidad por usuario.
- Revisión periódica (mensual) de reglas whitelist con IT/OT.

Entregables de esta sección:

- Diagrama lógico VLAN y diagrama de flujos (IT↔DMZ↔OT).
- Matriz de comunicaciones permitidas (origen/destino/puerto/justificación/owner).
- Plantillas de reglas (firewall A/B) con criterios de logging.

## 15. Implementación de seguridad OT

### 15.1. Inventario de activos (metodología)

- Descubrimiento pasivo (SPAN en core switch) + activo (ventana de mantenimiento).
- Enriquecimiento con documentación de ingeniería y verificación manual.

Herramientas recomendadas (ajustables a disponibilidad):

- Nmap 7.94 (descubrimiento y fingerprinting controlado).
- Nessus Industrial Edition (scanning OT en ventanas).
- Claroty CTD / Nozomi (si se dispone) para visibilidad pasiva.

Entregables:

- Inventario (CSV/Excel) con: IP/MAC/vendor/modelo/firmware, criticidad, propietario, ubicación.
- Mapa de comunicaciones (qué habla con qué, por qué y por qué puerto).
- “Lista de oro” (golden baseline) de activos críticos y su configuración.

### 15.2. Hardening de PLC (procedimientos)

**Siemens S7-1500**

Checklist operativo:

```text
- Deshabilitar servicios innecesarios (según entorno).
- Habilitar protección por contraseña y nivel de acceso (>= 3 si aplica).
- Restringir a IPs autorizadas (whitelist estación de ingeniería).
- Deshabilitar operaciones PUT/GET salvo excepciones justificadas.
- Activar auditoría/logging y exportar a syslog/SIEM cuando el stack lo soporte.
- Planificar actualización de firmware con rollback y backup de proyecto.
```

**Allen-Bradley CompactLogix**

Checklist operativo:

```text
- Configurar modo de seguridad “Enhanced”.
- Crear cuentas y roles con mínimo privilegio.
- Habilitar CIP Security/TLS si el entorno y versiones lo soportan.
- Deshabilitar servicios no necesarios (p. ej. HTTP/Telnet) y priorizar HTTPS/SSH.
- Integrar con políticas FactoryTalk Security si aplica.
```

Entregables:

- Procedimiento de hardening por familia de PLC.
- Evidencias de aplicación (capturas/config, fechas y responsables).
- Matriz de excepciones (lo que no se puede endurecer y por qué).

### 15.3. Gestión de cambios

- Ventanas de cambio acordadas con producción.
- Plan de rollback por componente.
- Registro de cambios (quién, qué, cuándo, evidencia).

Buenas prácticas incluidas:

- Cambios OT “en dos pasos”: simulación (Factory I/O/OpenPLC) → ventana de cambio en planta.
- Validación conjunta IT/OT post-cambio (pruebas de comunicación y operación segura).
- Control de versiones de configuraciones críticas (firewalls, jump host, reglas SIEM).

### 15.4. Backup y DR

- Backups de programas PLC semanales.
- Backups SCADA (incremental diario + full semanal).
- Objetivos: RTO 4h SCADA, 8h PLCs; RPO 24h.

Entregables:

- Política de backup OT (frecuencias, retención, cifrado, custodios).
- Procedimiento de restauración (runbook) y al menos un simulacro de recuperación.

## 16. SIEM & SOC (Wazuh vs ELK)

### 16.1. Comparativa

| Criterio | Wazuh (recomendado) | ELK (alternativa) | OSSIM |
|---|---|---|---|
| Coste | €0 (código abierto) | €0 (core) | €0 (código abierto) |
| OT/ICS | Alto (parsers/integ.) | Medio (plugins) | Bajo |
| EDR | Incluido | Requiere componentes | No |

Criterios adicionales:

- RBAC y segregación por perfiles (IT/SOC/OT).
- Informes de cumplimiento (GDPR/ISO) y trazabilidad.
- Facilidad de operación (carga operativa de administración).

Decisión: **Wazuh** por soporte OT, simplicidad, EDR y coste (€0 en licencias).

### 16.2. Fuentes de logs

Objetivo: consolidar ~30 fuentes iniciales (ampliable).

Fuentes IT (ejemplos):

- Firewalls perimetrales (FortiGate/Palo Alto) — syslog.
- Domain Controllers — eventos Windows.
- Web servers (Apache/Nginx) — access/error logs.
- Linux servers — syslog + auditd.
- Email gateway — logs de MTA.

Fuentes OT (ejemplos):

- Firewall industrial — eventos y alertas.
- SCADA — logs de aplicación y trail de auditoría.
- HMIs — eventos de login (si disponible).
- Jump host — eventos SSH/MFA + session recording metadata.
- Gateways/OPC UA (si aplica) — eventos de acceso.

Fuentes de aplicación (ejemplos):

- HR Portal — logs de aplicación + auditoría PostgreSQL.
- VPN concentrator — logs de conexión.
- Sistema de backups — logs de jobs.
- Honeypots — logs estructurados (JSON).

### 16.3. Casos de uso (ejemplos)

- Brute force / intentos MFA.
- Comandos Modbus write no autorizados.
- Cambios de firmware/config en PLC.
- Anomalías de protocolo (S7Comm/Modbus) fuera de zona.
- Señales de webshell o ejecución sospechosa en HR Portal.

Catálogo inicial recomendado (50+ reglas), agrupado por categoría:

- Autenticación (umbral de fallos, accesos fuera de horario, bypass MFA, lockouts).
- OT/ICS (Modbus write, stop CPU, conexiones SCADA→PLC desde IP no autorizada).
- Malware/EDR (integridad de ficheros, procesos anómalos, hashes conocidos).
- Exfiltración (transferencias anómalas, export masivo de datos RRHH).

### 16.4. Operación SOC

- Opción A (base): SOC 8x5 (€20.000/6 meses).
- Opción B (ampliada): SOC 24x7 (+€60.000/año).

Modelo operativo:

- Gestión de alertas (triage L1, investigación L2, escalado a IT/OT).
- Playbooks (contención, erradicación, recuperación) para incidentes tipo.
- Reporting: dashboard ejecutivo (semanal/mensual) y KPIs (MTTD/MTTR, FP rate).

Entregables:

- Plataforma SIEM desplegada (infra + hardening + backup).
- Integraciones de log y documentación de parsers.
- Reglas/casos de uso y dashboards por rol.
- Procedimientos SOC y matriz de escalado.

## 17. Arquitectura y despliegue de honeypots

### 17.1. Diseño

Objetivo: desplegar honeypots de forma **aislada** y **controlada** para mejorar detección, inteligencia de amenazas y validación de controles.

Plataforma recomendada: **T-Pot All-in-One**

```text
Specs recomendadas: 8 vCPU, 16GB RAM, 500GB SSD
SO: Debian 12 (instalador T-Pot)
Honeypots típicos:
- Cowrie (SSH/Telnet)
- Dionaea (SMB/FTP/MySQL/MSSQL)
- Conpot (ICS/SCADA: Modbus, S7Comm, BACnet)
```

Aislamiento y segmentación:

- VLAN 99 aislada sin rutas a IT/OT.
- Exposición controlada (port forwarding) solo de servicios honeypot.
- Salida restringida: permitir únicamente lo imprescindible (p. ej. syslog a SIEM).

Integración con SIEM:

- Forwarder (Logstash/Filebeat) → SIEM para correlación.
- Enrichment: GeoIP, ASN y reputación (según disponibilidad).

Pipeline de análisis (alto nivel):

```text
Ataque → Honeypot → logs JSON → SIEM (correlación) → IOC/acciones (blocklists)
```

### 17.2. Consideraciones legales

- Base jurídica: interés legítimo (GDPR 6(1)(f)) para seguridad.
- Retención de logs: 90 días.
- Manejo de malware: análisis en sandbox; no redistribución.

Recomendación adicional:

- Coordinar con INCIBE para eventos relevantes y definir un protocolo interno de custodia de evidencias.

Entregables:

- Diseño de red honeypot y reglas de firewall.
- Lista de servicios expuestos y justificación.
- Integración SIEM + dashboards básicos (top attackers, intentos, protocolos).

## 18. Especificaciones técnicas del HR Portal

### 18.1. Arquitectura

- Reverse proxy Nginx (TLS 1.3, HSTS, CSP, rate limiting).
- Backend PHP 8.4 (Slim) + PostgreSQL 16 + Redis 7.
- App Android (Kotlin) y web (React).

Diagrama lógico (referencia):

```text
Clientes (Web/Android)
  |
  | HTTPS
  v
Nginx (TLS, rate limit, headers seguridad)
  |
  v
API PHP 8.4 (Slim)  <->  PostgreSQL 16 (datos + auditoría)
  |
  v
Redis 7 (sesiones, tokens, rate limits)
```

### 18.2. Seguridad

- Autenticación JWT + refresh tokens (Redis).
- MFA/TOTP.
- RBAC y auditoría (audit logs).
- Validación: NIF/NIE, IBAN (mod-97), email, teléfono.
- Cifrado en tránsito (HTTPS) y cifrado en reposo para datos sensibles cuando aplique.

Controles mínimos exigibles:

- Políticas de contraseña (8+ chars, complejidad) y MFA/TOTP.
- Rate limiting por IP/usuario en Nginx y en API.
- Auditoría: alta/baja/modificación de datos personales y accesos privilegiados.
- Protección contra OWASP Top 10 (validación, sanitización, CSRF donde aplique, sesiones).
- Logging estructurado y envío al SIEM.

### 18.3. Módulos

- Empleados (completo, con auditoría).
- Vacaciones, nóminas, documentos, chat, quejas (planificados por fases).

Detalle funcional (resumen):

- **Empleados**: CRUD completo, validaciones (NIF/NIE/IBAN/teléfono), borrado lógico, restauración, histórico.
- **Vacaciones**: solicitud/aprobación/rechazo, calendario anual, export iCal.
- **Nóminas**: listado, detalle y descarga de PDF (controlado por rol).
- **Documentos**: subida/descarga con cifrado en reposo, control de acceso por empleado.
- **Chat**: mensajería con retención limitada (90 días).
- **Quejas**: canal con envío anónimo opcional y flujo de estados.

### 18.4. Despliegue y DR

- Docker Compose (nginx, php-fpm, postgres, redis).
- Backups PostgreSQL diarios + WAL (PITR).

Requisitos de operación:

- Separación de entornos (dev/pre/prod) y secretos en variables seguras.
- Backups cifrados off-site (30 días on-site, 90 días off-site).
- Objetivos: RTO 2h, RPO 15 min (WAL/PITR) para datos RRHH.

Entregables:

- Arquitectura de despliegue (compose/infra), hardening Nginx y API.
- Políticas de backup/restore y procedimientos (runbooks).
- Checklist de go-live (seguridad, rendimiento, observabilidad).

## 19. Mapeo de cumplimiento (ISO/IEC/GDPR/NIS2)

Esta sección traduce requisitos de cumplimiento a **controles implementados** y **evidencias** generadas durante el proyecto.

### 19.1. ISO/IEC 27001:2022 (ejemplos de controles)

| Control | Descripción | Evidencia/entregable asociado |
|---|---|---|
| A.5.1 | Políticas de seguridad | Políticas SGSI + procedimiento de revisión |
| A.8.1 | Inventario de activos | Inventario OT/IT + owners + criticidad |
| A.8.9 | Gestión de configuración | Hardening PLC + baseline + control de cambios |
| A.12.4 | Logging y monitorización | SIEM centralizado + dashboards + retención |
| A.13.1 | Seguridad de red | Segmentación Purdue + reglas firewall |
| A.14.2 | Seguridad en desarrollo | SDLC seguro HR Portal + revisiones + tests |
| A.17.1 | Continuidad de negocio | Runbooks DR + simulacro de recuperación |

### 19.2. IEC 62443 (objetivo SL2)

Estado inicial estimado: SL0 (controles mínimos).  
Estado objetivo: SL2 (protección frente a ataques intencionales con medios simples).

| FR | Requisito | Implementación |
|---|---|---|
| FR1 | Identificación y autenticación | MFA en jump host/HR Portal; gestión de cuentas |
| FR2 | Control de uso | RBAC, mínimo privilegio, whitelists por IP |
| FR3 | Integridad del sistema | FIM/EDR en endpoints y servidores; control de cambios |
| FR4 | Confidencialidad de datos | TLS/SSH; cifrado en reposo en datos RRHH cuando aplique |
| FR5 | Flujo de datos restringido | Segmentación Purdue + reglas firewall whitelist |
| FR6 | Respuesta temprana | SIEM con alertas OT; playbooks SOC |
| FR7 | Disponibilidad de recursos | Backups y DR; ventanas de mantenimiento y rollback |

### 19.3. GDPR (Art. 32 / 35 / 88)

- Art. 32: cifrado en tránsito, control de accesos, auditoría, backups y testing.
- Art. 35: DPIA del HR Portal (plantilla y evidencias).
- Art. 88: medidas específicas para datos de empleados (minimización, retención, trazabilidad).

### 19.4. NIS2

El proyecto contribuye a obligaciones clave: gestión de riesgos, manejo de incidentes (SIEM/SOC), continuidad (DR), formación y gestión de vulnerabilidades.

## 20. Plan de implementación detallado

### 20.1. Fases y entregables

- Fase 1 (Mes 1–2): inventario OT, arquitectura, riesgos, base HR Portal.
- Fase 2 (Mes 2–4): SIEM inicial, segmentación/jump host, HR Portal base.
- Fase 3 (Mes 4–6): hardening PLC, honeypots, tuning, HR Portal empleados.
- Fase 4 (Mes 6–8): módulos HR (vacaciones/nóminas) + pruebas.
- Fase 5 (Mes 8–10): pentest, rendimiento, formación, go-live.

Hitos de control (alto nivel):

- Mes 2: infraestructura base y arquitectura aprobada.
- Mes 4: segmentación + SIEM inicial operativo.
- Mes 6: hardening OT y HR Portal (empleados) listo.
- Mes 8: módulos HR planificados + pruebas integradas.
- Mes 10: go-live y aceptación formal.

Asignación de recursos (referencia):

- Consultoría IT/OT: 2 senior + 1 junior (meses 1–10).
- Consultoría RRHH/app: 1 senior + 1 junior (meses 4–10).
- Equipo Zabala: IT manager, 2 IT engineers, HR manager (parcial).

### 20.2. Criterios de aceptación

- OT: 0 vulnerabilidades críticas en auditoría de cierre; segmentación validada.
- SIEM: 30 fuentes integradas; <5% falsos positivos tras 90 días.
- Honeypots: operativos e integrados.
- HR Portal: batería de tests superada y migración validada.

Formación (120h total en programa Enterprise):

- Administradores (40h): operación SIEM, backups, hardening y runbooks.
- Ingenieros OT (40h): Purdue, hardening PLC, respuesta a incidentes OT.
- RRHH (40h): uso de HR Portal, privacidad y trazabilidad.

## 21. Gestión de riesgos y FMEA

### 21.1. Registro de riesgos (resumen)

- Parada de producción por cambio de red: mitigación con ventanas, pruebas y rollback.
- Compatibilidad legacy (PLC/SCADA): mitigación con assessment, piloto y excepciones controladas.
- Resistencia al cambio: mitigación con comunicación, formación y soporte.
- Adopción HR Portal: mitigación con champions, feedback y mejoras iterativas.

### 21.2. FMEA (ejemplos)

**Segmentación IT/OT**

```text
Función: Segmentación red OT/IT
Modo de falla: firewall mal configurado bloquea comunicaciones SCADA
Efectos: pérdida control proceso, parada de línea
Severidad: 9 | Ocurrencia: 3 | Detección: 2 | RPN: 54
Mitigación: pruebas en paralelo, rollback, monitorización reforzada
```

**Hardening PLC**

```text
Función: actualización/endur. PLC
Modo de falla: update falla, PLC inoperable
Efectos: pérdida control de máquina, impacto producción
Severidad: 8 | Ocurrencia: 2 | Detección: 3 | RPN: 48
Mitigación: backup completo, pruebas en laboratorio, procedimiento de rollback
```

### 21.3. Gestión del cambio

- Reuniones semanales de seguimiento (IT/OT/RRHH).
- Comunicación a producción y ventanas de mantenimiento.
- Soporte reforzado durante go-live.

## 22. Anexos técnicos

- Anexo A: diagramas (topología física, VLAN, flujos de datos).
- Anexo B: sizing hardware.
- Anexo C: BOQ (materiales) y supuestos.
- Anexo D: plantillas de reglas firewall.
- Anexo E: librería de reglas SIEM (50+).
- Anexo F: documentación API HR Portal.
- Anexo G: checklist de cumplimiento.

Anexos recomendados para entrega final:

- Anexo H: matriz de comunicaciones permitidas (whitelist) y owners.
- Anexo I: playbooks SOC (phishing, ransomware, acceso OT no autorizado, exfiltración).

---

## Próximos pasos (para decisión y arranque)

1. Validación de alcance (paquete vs integral).
2. Kick-off con IT/OT/RRHH y calendario.
3. Aprobación de hitos y responsables.
4. Inicio Fase 1.
