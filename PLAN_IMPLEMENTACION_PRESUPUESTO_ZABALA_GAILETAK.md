
# PRESUPUESTO ZABALA GAILETAK — SEGURIDAD INTEGRAL IT/OT (2026)

Documento de propuesta y plan de implementación para la mejora integral de seguridad de Zabala Gailetak S.A. (IT + OT/ICS), incluyendo SOC/SIEM, honeypots e implantación de Portal RRHH.

**Cliente**: Zabala Gailetak S.A. (Panificadora industrial, 120 empleados, País Vasco)  
**Alcance**: Seguridad OT/ICS + SIEM/SOC + Honeypots + Portal RRHH  
**Inversión (Year 1)**: €733.950  
**Coste recurrente (Year 2+)**: €129.000/año (servicios y mantenimiento)  
**Calendario**: 10 meses (Enero–Diciembre 2026)  
**ROI estimado**: 137,6% a 3 años (beneficios estimados: €786.000/año)

---

## Índice

- Parte I — Resumen Ejecutivo
   - 1. Contexto y motivación
   - 2. Objetivos y alcance
   - 3. Situación actual y riesgos
   - 4. Solución propuesta (pilares)
   - 5. Inversión, retorno y modelo de contratación
   - 6. Plan de implementación (alto nivel)
- Parte II — Propuesta Comercial
   - 7. Paquetes de servicio
   - 8. Precios detallados e hitos de pago
   - 9. Términos comerciales
   - 10. Casos de éxito y referencias
   - 11. Acuerdos de nivel de servicio (SLA)
   - 12. Propuesta de valor y diferenciación
- Parte III — Especificaciones Técnicas
   - 13. Arquitectura IT/OT (Modelo Purdue)
   - 14. Implementación de seguridad OT
   - 15. SIEM & SOC (Wazuh vs ELK)
   - 16. Arquitectura y despliegue de honeypots
   - 17. Especificaciones técnicas del HR Portal
   - 18. Mapeo de cumplimiento (ISO/IEC/GDPR/NIS2)
   - 19. Plan de implementación detallado
   - 20. Gestión de riesgos y FMEA
   - 21. Anexos técnicos

---

# PARTE I — RESUMEN EJECUTIVO

## 1. Contexto y motivación

Zabala Gailetak opera una planta industrial con dependencia elevada de automatización (SCADA/HMI/PLC). La convergencia IT/OT incrementa la exposición a incidentes (ransomware, interrupción de producción, accesos indebidos a redes industriales) y genera presión adicional por cumplimiento (GDPR y NIS2), así como por requisitos de clientes B2B.

Este documento propone una iniciativa de seguridad integral para:

- Reducir el riesgo de parada de producción y pérdida de trazabilidad.
- Aumentar la capacidad de detección y respuesta ante incidentes.
- Mejorar la postura de cumplimiento y auditoría.
- Digitalizar y securizar procesos de RRHH mediante un portal y aplicación móvil.

## 2. Objetivos y alcance

### Objetivos (qué se consigue)

- **Continuidad operativa**: minimizar interrupciones de planta mediante segmentación, hardening y procedimientos de backup/DR.
- **Detección temprana**: centralizar logs y correlación (SIEM) con casos de uso OT/ICS.
- **Respuesta y gobierno**: capacidad SOC (8x5 base, 24x7 opcional) y procedimientos de respuesta.
- **Disuasión y visibilidad de amenazas**: honeypots aislados e integrados con SIEM.
- **Digitalización RRHH**: HR Portal seguro (web + Android) con RBAC, MFA, auditoría y controles GDPR.

### Alcance (qué entra)

El proyecto se organiza en **4 pilares** más gestión de proyecto y auditoría:

1. **Seguridad OT/ICS** (inventario, arquitectura Purdue, segmentación, hardening PLC, jump host, procedimientos).
2. **SIEM & SOC** (Wazuh recomendado; integración de fuentes IT/OT/aplicación; alertas; dashboards; operación).
3. **Honeypots** (T-Pot/Conpot; red aislada; pipeline de análisis e inteligencia).
4. **HR Portal** (backend PHP, frontend web, app Android, despliegue, mantenimiento).

Quedan fuera del alcance (salvo contratación adicional): renovación masiva de PLCs/SCADA legacy, sustitución total de ERP, y certificaciones formales completas (p. ej. ISO 27001 certificación final) sin auditoría externa específica.

## 3. Situación actual y principales riesgos

### Riesgos operativos (OT)

- **Ransomware/IT-to-OT pivoting**: compromiso en IT que derive en afectación de SCADA/PLC.
- **Parada de producción** por misconfiguraciones, fallos de segmentación o cambios no controlados.
- **Obsolescencia y entornos legacy**: equipos con soporte limitado, dificultando parches.

### Riesgos de seguridad y cumplimiento (IT + RRHH)

- **Exposición de datos personales** (empleados, nóminas, documentación) y obligaciones GDPR.
- **Requisitos NIS2**: gestión de riesgos, incidentes, continuidad, cadena de suministro.

### Riesgos de visibilidad

- Sin telemetría centralizada y correlación, los incidentes pueden detectarse tarde.
- Ausencia de “señal” de ataques externos: los honeypots añaden inteligencia y disuasión.

## 4. Solución propuesta (visión de alto nivel)

La propuesta implementa un modelo de arquitectura y operación que separa IT y OT (Modelo Purdue), habilita una **DMZ industrial (nivel 3.5)**, añade un **jump host con MFA** como vía controlada, y centraliza telemetría mediante **SIEM Wazuh**.

Elementos clave:

- **Segmentación y control de flujos**: VLAN/ACL + firewalls con enfoque “whitelist”.
- **Hardening OT**: procedimientos concretos por fabricante (Siemens/Rockwell) y control de cambios.
- **SIEM**: integración de 30 fuentes de logs (IT/OT/aplicación), 50+ reglas, dashboards por rol.
- **Honeypots**: red aislada y pipeline de logs hacia SIEM para correlación e IOC.
- **HR Portal**: seguridad por diseño (RBAC, MFA, auditoría, validaciones, cifrado en tránsito, backups).

## 5. Inversión, retorno y modelo de contratación

### Resumen económico

- **Inversión Year 1**: €733.950
- **Recurrente Year 2+**: €129.000/año
- **ROI estimado**: 137,6% a 3 años

La inversión se justifica por la reducción de riesgo de parada (impacto anual estimado), mitigación de incidentes y mejora de eficiencia administrativa en RRHH.

### Modelo de pago por hitos

Se recomienda contratación escalonada por hitos, vinculada a entregables verificables (auditoría OT, SIEM operativo, segmentación y entregables del HR Portal, go-live y aceptación).

## 6. Plan de implementación (alto nivel)

El proyecto se ejecuta en 10 meses, con entregas incrementales y ventanas de cambio coordinadas con producción:

- Meses 1–2: discovery, inventario, arquitectura, riesgos, bases HR Portal.
- Meses 2–4: SIEM inicial, segmentación, jump host, HR Portal (auth + CRUD base).
- Meses 4–6: hardening PLC, honeypots, tuning SOC/SIEM, HR Portal (empleados completo).
- Meses 6–8: módulos HR (vacaciones, nóminas), pruebas integradas.
- Meses 8–10: pruebas de seguridad y rendimiento, formación, go-live y soporte.

Entregables transversales: documentación, procedimientos de operación, formación (120h) y criterios de aceptación por pilar.

---

# PARTE II — PROPUESTA COMERCIAL

Esta parte describe las opciones de contratación, el desglose económico y los términos de servicio. La propuesta está diseñada para permitir una adopción incremental (por paquetes) o una implantación integral (Enterprise).

## 7. Paquetes de servicio

### 7.1. Resumen de paquetes

| Paquete | Denominación | Duración estimada | Inversión (Year 1) | Perfil recomendado |
|---|---|---:|---:|---|
| Básico | OT Foundation | 3 meses | €180.000 | Primer salto en OT, visibilidad y formación |
| Profesional | OT Advanced | 5 meses | €324.000 | Segmentación + detección ampliada + honeypots |
| Empresarial | OT Enterprise | 10 meses | €733.950 | Programa completo IT/OT + HR Portal + SOC ampliado |

### 7.2. Contenido por paquete

**Paquete Básico — OT Foundation (€180.000)**

- Auditoría de seguridad OT y toma de inventario de activos.
- SIEM base con operación 8x5 (monitorización y reporting).
- Formación base (40h) para IT/OT.
- Soporte estándar (email/telefónico) y entrega de documentación.

**Paquete Profesional — OT Advanced (€324.000)**

- Incluye OT Foundation.
- Segmentación de red IT/OT (VLAN/ACL y firewalls) y jump host con MFA.
- Despliegue de honeypots aislados e integración con SIEM.
- SIEM avanzado con casos de uso OT (reglas y dashboards por rol).
- Formación avanzada (80h) y soporte prioritario.

**Paquete Empresarial — OT Enterprise (€733.950)**

- Incluye OT Advanced.
- Desarrollo e implantación del HR Portal (web + Android) con controles GDPR.
- Servicio SOC ampliado (24x7 durante 6 meses) como opción de arranque.
- Integración completa IT/OT, pruebas de seguridad y preparación de auditoría.
- Formación completa (120h) y soporte 24x7 según alcance contratado.

### 7.3. Matriz comparativa

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

### 7.4. Ruta de migración

- **Básico → Profesional**: cuando se apruebe inversión de segmentación IT/OT y operación de detección ampliada.
- **Profesional → Empresarial**: cuando se incorpore HR Portal, SOC ampliado y la integración completa de gobierno/operación.

## 8. Precios detallados e hitos de pago

### 8.1. Desglose económico por pilar (Year 1)

| Pilar | Alcance resumido | Importe |
|---|---|---:|
| Pilar 1 — Seguridad OT/ICS | Inventario/auditoría, segmentación, hardening PLC, jump host, documentación y formación | €180.000 |
| Pilar 2 — SIEM & SOC | Plataforma, integración de logs, casos de uso/alertas y servicio SOC inicial | €120.000 |
| Pilar 3 — Honeypots | Plataforma, honeypots ICS y su integración/correlación con SIEM | €24.000 |
| Pilar 4 — HR Portal | Backend, web frontend, Android app y despliegue | €300.000 |
| PM & Auditoría | Gestión de proyecto, auditoría de cumplimiento y evaluación de riesgos | €110.000 |

Nota: algunas partidas están **redondeadas**; el **total contractual** aplicable a hitos es **€733.950**.

### 8.2. Hitos de pago (contrato escalonado)

| Hito | Condición de facturación (resumen) | % | Importe |
|---|---|---:|---:|
| Hito 1 | Firma de contrato y plan de proyecto aprobado | 30% | €220.185 |
| Hito 2 | Auditoría OT completa + SIEM inicial operativo | 20% | €146.790 |
| Hito 3 | Segmentación y jump host + HR Portal fase 1 | 20% | €146.790 |
| Hito 4 | Implementación completa (pilares) + UAT superada | 20% | €146.790 |
| Hito 5 | Go-live + aceptación formal | 10% | €73.395 |

### 8.3. Costes recurrentes (Year 2+)

**Total recurrente estimado**: €129.000/año

- SIEM monitoring: €24.000/año
- SOC services (opcional): €60.000/año
- HR Portal maintenance: €30.000/año
- Security updates: €15.000/año

### 8.4. Add-ons opcionales

- Penetration testing anual: €12.000
- Incident response retainer: €15.000
- Formación adicional: €1.500/día
- Auditorías on-site trimestrales: €8.000/año

## 9. Términos comerciales

### 9.1. Garantías

- Software (HR Portal): garantía de 12 meses sobre defectos.
- Consultoría: garantía de 6 meses sobre entregables.
- Hardware: 3 años según garantía del fabricante.
- Ajustes de configuración de seguridad: ventana de 90 días para refinamientos.

### 9.2. Condiciones de pago

- Pago neto a 30 días desde factura.
- Penalización por mora: 1,5% mensual.
- Descuento por pronto pago: 3% si se paga en 10 días.
- Métodos aceptados: transferencia bancaria y cheque corporativo.

### 9.3. Duración

- Year 1: contrato de implementación (10 meses).
- Year 2+: contrato de operación/mantenimiento (opcional).
- Descuento renovación: 10% por compromiso 3 años anticipado.

### 9.4. Terminación

- Cancelación por el cliente: liquidación por hitos y trabajo efectivamente realizado.
- Fuerza mayor: exención recíproca.
- Incumplimiento: periodo de remediación de 30 días.

### 9.5. Responsabilidad

- Límite general: €733.950 (valor contractual Year 1).
- Daños consecuenciales: excluidos, salvo negligencia grave.
- Seguro ciber: cobertura €2M mantenida.

### 9.6. Propiedad intelectual

- Código del HR Portal: propiedad de Zabala Gailetak tras pago completo.
- Configuraciones de seguridad: licenciadas para uso interno de Zabala.
- Materiales de formación: licencia perpetua de uso interno.

## 10. Casos de éxito y referencias (anónimos)

**Caso 1 — Industria láctea (Navarra)**

- Perfil: 200 empleados, automatización en producción.
- Reto: ausencia de seguridad OT; evento de ransomware (casi incidente).
- Solución: auditoría OT + SIEM + segmentación.
- Resultado: 0 incidentes 24 meses, ISO 27001, ROI 180%.

**Caso 2 — Panificadora industrial (perfil similar)**

- Perfil: 80 empleados, líneas robotizadas.
- Reto: SCADA legacy y ausencia de trazabilidad de accesos.
- Solución: modelo Purdue + jump hosts.
- Resultado: auditoría IEC 62443 SL2, evitada parada estimada €300.000.

**Caso 3 — Digitalización RRHH en pyme industrial**

- Perfil: 150 empleados.
- Reto: procesos en papel y gaps de GDPR.
- Solución: HR Portal custom + app móvil.
- Resultado: 60% reducción de tiempo administrativo, €50.000/año en ahorros.

## 11. Acuerdos de nivel de servicio (SLA)

### 11.1. Tiempos de respuesta SIEM/SOC

| Severidad | Detección | Respuesta | Resolución |
|---|---:|---:|---:|
| Crítico | 5 min | 15 min | 4 horas |
| Alto | 15 min | 1 hora | 24 horas |
| Medio | 1 hora | 4 horas | 5 días |
| Bajo | 4 horas | 24 horas | 30 días |

### 11.2. Disponibilidad

- SIEM: 99,5%.
- HR Portal: 99,0% (en horario 07:00–23:00).
- Honeypots: 95% (sistemas aislados).
- Red OT: 99,9% (objetivo; sujeto a ventanas de mantenimiento coordinadas).

### 11.3. Canales de soporte

- Hotline 24x7: +34 XXX XXX XXX (Paquete Empresarial).
- Sistema de tickets: respuesta <2h en horario laboral.
- Email: respuesta <8h en horario laboral.
- Soporte on-site: <4h para incidentes críticos en País Vasco (según contrato).

### 11.4. Gestión de parches

- Críticos: <72h.
- Altos: <7 días.
- Regulares: ventana de mantenimiento mensual.

### 11.5. Créditos SLA

- 99,5%–99,0%: 10% crédito mensual.
- 99,0%–95,0%: 25% crédito mensual.
- <95,0%: 50% crédito + plan de remediación.

## 12. Propuesta de valor y diferenciación

### 12.1. Diferenciadores

- Especialización OT/ICS con enfoque IEC 62443.
- Experiencia en el sector alimentario e integración con necesidades de producción.
- Presencia local (Bilbao) y disponibilidad on-site rápida.
- Enfoque dual de cumplimiento (ISO 27001 + IEC 62443) para optimizar costes.
- Soporte de documentación y formación en euskera.

### 12.2. Ventajas técnicas

- Simulación con Factory I/O + OpenPLC para pruebas seguras previas.
- Honeypots Conpot orientados a protocolos industriales.
- HR Portal con controles específicos para datos de empleados (GDPR Art. 88).
- PostgreSQL + Redis con patrón enterprise y eficiencia de coste.

### 12.3. Valor de negocio

- Mitigación del riesgo: reducción del coste esperado de incidente (ransomware, interrupción).
- Continuidad operativa: prevención de paradas de producción.
- Cumplimiento: reducción del riesgo sancionador y mantenimiento de requisitos B2B.
- Eficiencia RRHH: reducción de carga administrativa y mejora de trazabilidad.

### 12.4. Métricas de éxito

- 0 incidentes con impacto en producción.
- <5% de falsos positivos en SIEM tras 90 días.
- 95%+ de adopción del HR Portal en 6 meses.
- Preparación para ISO 27001 + IEC 62443 en 12 meses (según auditoría externa).

---

# PARTE III — ESPECIFICACIONES TÉCNICAS

Esta parte define la arquitectura de referencia, los requisitos técnicos y los criterios de despliegue/operación para los cuatro pilares (OT/ICS, SIEM/SOC, honeypots y HR Portal), así como el mapeo de cumplimiento, la planificación detallada y la gestión de riesgos.

## 13. Arquitectura IT/OT (Modelo Purdue)

Objetivo: definir una arquitectura de segmentación IT/OT con DMZ industrial, control de flujos y rutas de acceso gobernadas.
1. **Modelo Purdue Implementado** (diagrama ASCII):
   ```
   ┌─────────────────────────────────────────────────────────────┐
   │ NIVEL 4: Red Empresa (IT)                                   │
   │ - ERP System (Odoo/SAP)                                     │
   │ - Email Server (Exchange/Postfix)                           │
   │ - File Servers (NAS)                                        │
   │ - HR Portal (PHP + PostgreSQL + Redis)                      │
   │ - Workstations Oficina (120 usuarios)                       │
   └──────────────────┬──────────────────────────────────────────┘
                      │ Firewall A (Fortinet/Palo Alto)
                      │ Reglas: Allow HTTP/HTTPS, Block SMB/RDP
   ┌──────────────────▼──────────────────────────────────────────┐
   │ NIVEL 3.5: DMZ Industrial                                    │
   │ - SIEM Server (Wazuh Manager + ELK Stack)                   │
   │ - Patch Management Server (WSUS/Landscape)                  │
   │ - Jump Host (Bastion con MFA)                               │
   │ - Historian DB (InfluxDB/TimescaleDB)                       │
   │ - Honeypot Network (T-Pot, Conpot, Cowrie) - AISLADO       │
   └──────────────────┬──────────────────────────────────────────┘
                      │ Firewall B (Firewall Industrial)
                      │ Reglas: Whitelist only, Modbus/Profinet inspection
   ┌──────────────────▼──────────────────────────────────────────┐
   │ NIVEL 3: Operaciones (OT)                                   │
   │ - SCADA Server (Ignition/WinCC)                             │
   │ - HMI Panels (3x Siemens TP1200)                            │
   │ - Engineering Workstation (TIA Portal, Factory I/O)         │
   │ - OpenPLC Runtime (Simulación)                              │
   └──────────────────┬──────────────────────────────────────────┘
                      │ Switch Managed (Segmentación VLAN)
   ┌──────────────────▼──────────────────────────────────────────┐
   │ NIVEL 2: Red Control                                        │
   │ - PLCs (5x Siemens S7-1500, 3x Allen-Bradley CompactLogix) │
   │ - RTUs (Remote Terminal Units)                              │
   └──────────────────┬──────────────────────────────────────────┘
                      │ Ethernet Industrial (Profinet/EtherNet/IP)
   ┌──────────────────▼──────────────────────────────────────────┘
   │ NIVEL 1/0: Dispositivos Campo                               │
   │ - Mezcladoras (3x con VFDs)                                 │
   │ - Hornos (4x industriales con PID)                         │
   │ - Robots Embalaje (2x ABB IRB 1200)                        │
   │ - Sensores (Temperatura, Presión, Flujo - 50+ I/O points)  │
   │ - Actuadores (Válvulas, Motores, Transportadores)          │
   └─────────────────────────────────────────────────────────────┘
   ```

2. **Diseño VLAN**:
   ```
   VLAN 10: Red IT Oficina (192.168.10.0/24)
   VLAN 20: DMZ Industrial (10.10.20.0/24)
   VLAN 30: Red SCADA/HMI (10.10.30.0/24)
   VLAN 40: Red Control PLC (10.10.40.0/24)
   VLAN 50: Dispositivos Campo (10.10.50.0/24)
   VLAN 99: Red Honeypot (172.16.99.0/24) - AISLADA
   ```

3. **Reglas Firewall Resumidas** (Firewall B - frontera IT/OT):
   ```
   Allow: Jump Host (DMZ) → SCADA (port 135 RDP, MFA requerido)
   Allow: SIEM (DMZ) → PLC (port 102 S7Comm, read-only)
   Allow: Historian (DMZ) ← SCADA (port 8088 InfluxDB write)
   Deny: VLAN IT → VLAN OT (todo tráfico directo)
   Deny: VLAN OT → Internet (todo outbound)
   Alert: Cualquier tráfico Modbus fuera VLAN 40/50
   ```

4. **Placeholders Diagramas**:
   - **[DIAGRAMA A]**: Topología física (racks, switches, firewalls)
   - **[DIAGRAMA B]**: Arquitectura lógica VLAN
   - **[DIAGRAMA C]**: Diagrama flujo datos (SCADA → Historian → SIEM)

## 14. Implementación de seguridad OT

Objetivo: concretar metodología, procedimientos y controles para inventario, hardening, segmentación y continuidad en OT/ICS.
1. **Metodología Inventario Activos**:
   - **Herramientas**: Nmap 7.94, Nessus Industrial Edition, Claroty CTD
   - **Proceso**: Discovery pasiva (span port switch core), escaneo activo (ventana mantenimiento), verificación manual (dibujos ingeniería)
   - **Entregable**: Excel/CSV con MAC, IP, vendor, firmware, score criticidad

2. **Procedimientos Endurecimiento PLC**:
   
   **Siemens S7-1500**:
   ```
   - Deshabilitar servicios innecesarios (FTP, HTTP server)
   - Habilitar protección password (nivel acceso PLC 3+)
   - Configurar listas acceso IP (whitelist workstation ingeniería)
   - Deshabilitar PUT/GET operations excepto IPs autorizadas
   - Habilitar logging auditoría (syslog → SIEM)
   - Update firmware: TIA Portal v18 → Aplicar parches Junio 2024
   ```
   
   **Allen-Bradley CompactLogix**:
   ```
   - Set security mode to "Enhanced" (CIP Security)
   - Crear cuentas usuario con least privilege
   - Habilitar CIP Security con TLS 1.2+
   - Deshabilitar HTTP/Telnet (usar HTTPS/SSH only)
   - Configurar políticas FactoryTalk Security
   ```

3. **Segmentación de Red**:
   - **Segmentación física**: Switches separados para IT/OT
   - **Segmentación lógica**: VLANs con ACLs
   - **Colocación firewall**: Entre niveles 3.5/3 y 3/2
   - **IDS/IPS**: Aware de protocolo industrial (colocación Claroty/Nozomi)

4. **Configuración Jump Host**:
   ```
   Hardware: Servidor dedicado (Dell PowerEdge R250 o equivalente)
   SO: Ubuntu 24.04 LTS Server (hardened con benchmark CIS)
   Acceso: OpenSSH con MFA (Google Authenticator/Duo)
   Sesión Recording: Auditd + Teleport para captura pantalla
   Outbound permitido: RDP → SCADA, S7Comm → PLCs (logged)
   Gestión usuarios: Integración LDAP con Active Directory
   ```

5. **Seguridad Protocolos ICS**:
   
   **Modbus TCP (Port 502)**:
   - Inspección profunda paquetes en firewall
   - Funciones código read-only desde SIEM (0x01-0x04)
   - Bloquear comandos write (0x05, 0x06, 0x0F, 0x10) desde IPs no ingeniería
   
   **Profinet (capa Ethernet)**:
   - Seguridad 802.1X en switches
   - Aislamiento VLAN por zona producción
   - Switches Siemens Scalance con capacidad NAT/firewall

6. **Backup & Disaster Recovery**:
   - Programas PLC: Backup semanal vía TIA Portal (almacenado encriptado en NAS)
   - DB SCADA: Incremental diario, full semanal (retención: 90 días)
   - Recovery Time Objective (RTO): 4h SCADA, 8h PLCs
   - Recovery Point Objective (RPO): 24h máximo data loss

## 15. SIEM & SOC (Wazuh vs ELK)

Objetivo: especificar plataforma SIEM/SOC, fuentes de logs, casos de uso y operación.
1. **Matriz Comparación Plataforma**:
   
   | Criterio | Wazuh (Recomendado) | ELK Stack (Alternativa) | AlienVault OSSIM |
   |-----------|---------------------|-------------------------|------------------|
   | Costo | €0 (código abierto) | €0 (core) | €0 (código abierto) |
   | Soporte OT/ICS | ✅ Parsers Modbus/S7Comm | ⚠️ Plugins Logstash requeridos | ⚠️ Limitado OT |
   | Escalabilidad | ✅ 10K+ agentes | ✅ Excelente (Elasticsearch) | ❌ Límite 1 nodo |
   | Curva Aprendizaje | Medio | Alto | Medio |
   | Comunidad | ✅ Activa | ✅ Muy activa | ⚠️ Declinante |
   | Capacidad EDR | ✅ Built-in | ❌ Add-ons requeridos | ❌ No EDR |
   | RBAC | ✅ Granular | ✅ Con X-Pack (paid) | ✅ Básico |
   | Compliance | ✅ PCI-DSS, GDPR reports | ✅ Custom | ✅ Pre-built |
   
   Decisión: **Wazuh** por soporte OT, simplicidad, EDR y coste (€0 en licencias).

2. **Integración Fuentes Log** (30 total):
   
   **Fuentes IT (15)**:
   - Firewalls: FortiGate/Palo Alto (syslog UDP/514)
   - Domain Controllers: Windows Event Logs (agente Wazuh)
   - Web Servers: Apache/Nginx access/error logs (Filebeat)
   - Linux Servers: auditd, syslog (agente Wazuh)
   - Email Gateway: Postfix logs (Filebeat)
   
   **Fuentes OT (10)**:
   - Firewall Industrial: Syslog (alertas Claroty/Nozomi)
   - SCADA Server: Logs aplicación + audit trail DB
   - HMI Panels: Eventos login (syslog)
   - PLCs: Logs S7Comm vía gateway OPC UA
   - Jump Host: Logs SSH session + auditd
   
   **Fuentes Aplicación (5)**:
   - HR Portal: Logs aplicación PHP + PostgreSQL audit
   - Autenticación: Eventos LDAP/AD login
   - VPN Concentrator: Logs OpenVPN/IPSec
   - Backup System: Logs Veeam/Bacula job
   - Honeypots: Logs JSON T-Pot (Cowrie, Conpot, Dionaea)

3. **Reglas Alerta & Casos Uso** (50+ escenarios):
   
   **Categoría: Autenticación (10 reglas)**:
   - Failed login >5 en 5 min desde IP → Alert
   - Successful login desde geolocation mismatch → Alert
   - Login fuera horas business (cuentas admin) → Alert
   - Intento bypass MFA → Critical Alert
   - Lockout account triggered → Alert
   
   **Categoría: OT-Específicas (15 reglas)**:
   - Comando Modbus write no autorizado → Critical Alert
   - Modificación firmware PLC detectada → Critical Alert
   - Conexión SCADA → PLC desde IP desconocida → Alert
   - Escaneo Modbus detectado (múltiples function codes) → Alert
   - Comando stop CPU PLC → Critical Alert
   - Login HMI con credenciales default → Alert
   
   **Categoría: Malware (8 reglas)**:
   - File integrity monitoring cambio en /bin, /sbin → Alert
   - Proceso spawn desde PHP (webshell) → Critical Alert
   - Hash malware conocido (API VirusTotal) → Critical Alert
   - Movimiento lateral (PSExec, WMI abuse) → Alert
   
   **Categoría: Exfiltración Datos (7 reglas)**:
   - Transferencia outbound grande (>1GB) → Alert
   - Comando dump DB ejecutado → Alert
   - Export bulk datos empleados HR Portal → Alert
   - Dispositivo USB conectado a workstation OT → Critical Alert

4. **Diseño Dashboards**:
   
   **Dashboard Ejecutivo** (para CEO/CFO):
   - Score postura seguridad (1-100)
   - Alertas críticas últimas 7 días (trend)
   - Status compliance (controles ISO 27001 implementados %)
   - Top 5 actores amenaza (datos honeypot)
   
   **Dashboard SOC Analyst**:
   - Queue alertas (ordenadas por priority)
   - Top attackers por IP (mapa GeoIP)
   - Heat map criticidad assets
   - Status workflow response incident
   
   **Dashboard OT Engineer**:
   - Status health PLC (CPU, memoria, errores comm)
   - Métricas uptime SCADA
   - Intentos acceso no autorizados (red OT)
   - Anomalías protocolo (Modbus/Profinet)

5. **Feeds Inteligencia Amenazas**:
   - AlienVault OTX (exchange amenazas open)
   - MISP (Plataforma Sharing Malware Info)
   - ICS-CERT advisories (US-CERT)
   - Inteligencia honeypot interna (firmas ataque)
   - API VirusTotal (reputación file hash)

6. **Modelo Staffing SOC**:
   
   **Opción A: SOC 8x5** (€20.000/6 meses, incluido presupuesto):
   - Cobertura: L-V 8am-5pm
   - Staffing: 1 analista L1 + 1 L2 (cobertura part-time)
   - Escalación: Ingeniero on-call para alertas críticas
   
   **Opción B: SOC 24x7** (+€60.000/año, paquete Empresarial):
   - Cobertura: 24h, 7 días, 365 días
   - Staffing: 3 shifts x 2 analistas = 6 FTE
   - Escalación: Equipo response incident dedicado

## 16. Arquitectura y despliegue de honeypots

Objetivo: desplegar honeypots de forma aislada, legalmente alineada e integrada con el SIEM para generar inteligencia accionable.
1. **Plataforma T-Pot All-in-One**:
   ```
   Hardware: Servidor dedicado (bare-metal o VM)
   Specs: 8 vCPU, 16GB RAM, 500GB SSD
   SO: Debian 12 (installer T-Pot auto-configura)
   Honeypots incluidos:
   - Cowrie: SSH/Telnet honeypot (ports 22, 23)
   - Dionaea: Multi-protocolo (SMB, FTP, MySQL, MSSQL)
   - Conpot: ICS/SCADA (Modbus, S7Comm, BACnet)
   - Honeytrap: Low-interaction (todos ports)
   - Glutton: Todos TCP/UDP ports
   ```

2. **Honeypots ICS Conpot**:
   ```
   Template 1: PLC Siemens S7-300
   - Protocolo: S7Comm (ISO-TSAP)
   - Datos expuestos: Sensores temperatura fake, estados motor
   - Propósito: Detectar scanners ICS automatizados (Shodan, ZoomEye)
   
   Template 2: Gateway Modbus RTU
   - Protocolo: Modbus TCP (port 502)
   - Registros: 100 coils/holding registers fake
   - Propósito: Capturar herramientas escaneo Modbus
   
   Template 3: Guardian AST Tank Gauging
   - Protocolo: Guardian AST (port 10001)
   - Propósito: Atraer attackers sector oil/gas
   ```

3. **Diseño Aislamiento Red**:
   ```
   ┌────────────────────────────────────────┐
   │ Internet (IP Pública: XXX.XXX.XXX.XXX) │
   └───────────────┬────────────────────────┘
                   │ Port forwarding only
   ┌───────────────▼────────────────────────┐
   │ VLAN 99: Red Honeypot (aislada)        │
   │ - Server T-Pot (172.16.99.10)          │
   │ - No route a redes IT/OT                │
   │ - Reglas firewall:                     │
   │   Allow: Inbound 22,23,80,502,102...   │
   │   Deny: Outbound a 192.168.0.0/16      │
   │   Allow: Outbound HTTP (descarga malware)│
   │   Allow: Syslog → SIEM (10.10.20.5)    │
   └────────────────────────────────────────┘
   ```

4. **Pipeline Recolección & Análisis Datos**:
   ```
   Ataque Honeypot → ELK Stack T-Pot (dashboard Kibana)
                    ↓
   Logs JSON → Logstash → SIEM Wazuh (correlación)
                    ↓
   Intel Amenazas → Update blocklists firewall
                    ↓
   Samples malware → Submission VirusTotal → DB IOC
   ```

5. **Integración SIEM**:
   - Forwarder Logstash en T-Pot → manager Wazuh
   - Alert on: Patrones brute force SSH, intentos write Modbus, descarga malware
   - Enrichment: GeoIP (ubicación attacker), ASN (proveedor hosting), reputación (VirusTotal)

6. **Consideraciones Legales & Éticas**:
   - **Disclosure**: Presencia honeypot NO divulgada (legal en España para investigación seguridad)
   - **Retención datos**: Logs ataque retenidos 90 días (GDPR Art. 6(1)(f) interés legítimo)
   - **Manejo malware**: Análisis sandboxed únicamente, no re-distribución
   - **Law enforcement**: Coordinar con INCIBE para amenazas significativas

## 17. Especificaciones técnicas del HR Portal

Objetivo: describir arquitectura, módulos, controles de seguridad, despliegue y DR del portal de RRHH.
1. **Arquitectura Sistema**:
   ```
   ┌────────────────────────────────────────────────────────┐
   │ Clientes                                               │
   │ ┌──────────────┐        ┌──────────────┐              │
   │ │ Web Browser  │        │ Android App  │              │
   │ │ (React 18.2) │        │ (Kotlin)     │              │
   │ └──────┬───────┘        └──────┬───────┘              │
   └────────┼───────────────────────┼────────────────────────┘
            │ HTTPS                 │ HTTPS
            └───────────┬───────────┘
   ┌────────────────────▼───────────────────────────────────┐
   │ Reverse Proxy Nginx (SSL Termination)                  │
   │ - TLS 1.3, HTTP/2                                      │
   │ - Rate limiting: 100 req/min per IP                    │
   │ - Headers CSP, HSTS                                    │
   └────────────────────┬───────────────────────────────────┘
   ┌────────────────────▼───────────────────────────────────┐
   │ API Backend (PHP 8.4 + Slim Framework)                 │
   │ ┌────────────────────────────────────────────────────┐ │
   │ │ Stack Middleware:                                   │ │
   │ │ - Authentication (validación JWT)                   │ │
   │ │ - Authorization (check RBAC)                        │ │
   │ │ - CSRF Protection                                   │ │
   │ │ - Rate Limiting (per user)                          │ │
   │ │ - Logging (Monolog → Wazuh)                         │ │
   │ └────────────────────────────────────────────────────┘ │
   │ ┌────────────────────────────────────────────────────┐ │
   │ │ Controllers:                                        │ │
   │ │ - AuthController (login, MFA, refresh JWT)          │ │
   │ │ - EmployeeController (CRUD + audit trail) ✅        │ │
   │ │ - VacationController (request, approve, calendar)   │ │
   │ │ - PayrollController (list, download PDF)            │ │
   │ │ - DocumentController (upload, request, download)    │ │
   │ │ - ChatController (bridge WebSocket)                 │ │
   │ └────────────────────────────────────────────────────┘ │
   └────────────────────┬───────────────────────────────────┘
            ┌───────────┴───────────┐
            │                       │
   ┌────────▼────────┐    ┌────────▼────────┐
   │ PostgreSQL 16   │    │ Redis 7         │
   │ (DB Primaria)   │    │ (Sesiones)      │
   │ - Employees ✅   │    │ - JWT tokens    │
   │ - Vacations     │    │ - Cache         │
   │ - Payroll       │    │ - Rate limits   │
   │ - Documents     │    │ - WebSocket (opc)│
   │ - Audit logs ✅  │    └─────────────────┘
   │ - Chat messages │
   │ - Etc.          │    └─────────────────┘
   └─────────────────┘
   ```

2. **Esquema Base Datos** (de migrations/001_init_schema.sql):
   
   **Tablas Principales** (7 Phase 3 + 15+ planificadas):
   - `users` (autenticación, MFA, roles) - ✅ Completo
   - `employees` (datos perfil, NIF, IBAN, contacto) - ✅ Completo
   - `departments` (jerarquía, manager assignment)
   - `vacations` (solicitudes, aprobaciones, balance) - Schema listo
   - `documents` (metadata files, tracking upload)
   - `payroll` (cálculos salary, deducciones, net pay)
   - `complaints` (canal whistleblower anónimo)
   - `chat_messages` (mensajería real-time)
   - `audit_logs` (tracking cambios inmutable) - ✅ Completo
   - `notifications` (alerts, recordatorios)
   
   **Extracto Schema**:
   ```sql
   CREATE TABLE employees (
       id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
       user_id UUID REFERENCES users(id),
       employee_number VARCHAR(20) UNIQUE NOT NULL,
       first_name VARCHAR(100) NOT NULL,
       last_name VARCHAR(100) NOT NULL,
       nif_nie VARCHAR(10) UNIQUE NOT NULL, -- Validado con checksum
       iban VARCHAR(24), -- Validado con mod-97
       phone VARCHAR(15), -- Formato español +34XXXXXXXXX
       hire_date DATE NOT NULL,
       department_id UUID REFERENCES departments(id),
       position VARCHAR(100),
       is_active BOOLEAN DEFAULT TRUE,
       created_at TIMESTAMP DEFAULT NOW(),
       updated_at TIMESTAMP DEFAULT NOW()
   );
   ```

3. **Documentación Endpoints API**:
   
   **Autenticación** (3 endpoints):
   ```
   POST /api/auth/login
   Body: { "email": "user@zabala.eus", "password": "...", "mfa_code": "123456" }
   Response: { "token": "JWT...", "refresh_token": "...", "user": {...} }
   
   POST /api/auth/refresh
   Body: { "refresh_token": "..." }
   Response: { "token": "new_JWT..." }
   
   POST /api/auth/logout
   Headers: Authorization: Bearer {token}
   Response: 204 No Content
   ```
   
   **Empleados** (8 endpoints) - ✅ **COMPLETADO EN PHASE 3**:
   ```
   GET    /api/employees                  → List (paginado, 10/página)
   GET    /api/employees/{id}             → Detail con history auditoría
   POST   /api/employees                  → Create (RBAC: admin, hr_manager)
   PUT    /api/employees/{id}             → Update (audit logged)
   DELETE /api/employees/{id}             → Soft delete (is_active=false)
   POST   /api/employees/{id}/restore     → Restore empleado eliminado
   GET    /api/employees/{id}/history     → Audit trail (timeline)
   GET    /api/audit/user/{userId}        → Log actividad usuario
   ```
   
   **Vacaciones** (6 endpoints) - ⏳ **PLANIFICADO**:
   ```
   GET    /api/vacations                  → List (filter por status, año)
   GET    /api/vacations/{id}             → Detail
   POST   /api/vacations                  → Request vacaciones
   PUT    /api/vacations/{id}/approve     → Approve (RBAC: manager+)
   PUT    /api/vacations/{id}/reject      → Reject con reason
   GET    /api/vacations/calendar/{year}  → Vista calendario
   ```
   
   **Nóminas** (3 endpoints) - ⏳ **PLANIFICADO**:
   ```
   GET    /api/payroll                    → List propias nóminas (o todas si hr_manager)
   GET    /api/payroll/{id}               → Detail
   GET    /api/payroll/{id}/download      → Download PDF
   ```

4. **Controles Seguridad**:
   
   **Autenticación**:
   - JWT access tokens: Expiración 1 hora
   - Refresh tokens: Expiración 7 días, almacenados en Redis
   - MFA/TOTP: Compatible Google Authenticator (códigos 30-seg)
   - Política password: 8+ chars, mayúscula, minúscula, número, especial
   
   **Autorización (RBAC)**:
   ```php
   Roles:
   - admin:          43 permisos (acceso completo)
   - hr_manager:     31 permisos (operaciones HR)
   - department_head: 15 permisos (equipo propio)
   - employee:        7 permisos (self-service)
   
   Check permiso ejemplo:
   if (!$user->hasPermission('employees.create')) {
       return $response->withStatus(403);
   }
   ```
   
   **Validación Input** (de Phase 3):
   - NIF/NIE: Validación ID español con checksum letra
   - IBAN: Validación checksum mod-97
   - Teléfono: Formato español `+34XXXXXXXXX` (9 dígitos)
   - Código postal: Rango 00000-52999
   - Email: Compliant RFC5322
   - XSS sanitization: DOMPurify (client-side), `htmlspecialchars()` (server-side)
   
   **Protección Base Datos**:
   - Prepared statements PDO (prevención SQL injection)
   - Soft deletes (no eliminación física empleados)
   - Audit trail (todas operaciones CUD logged con user_id, timestamp, cambios JSON)

5. **Especificaciones Módulos**:

   **Módulo Empleados** (Fase 3 — completado):
   - Líneas de código: ~5.500 (backend + web + móvil)
   - Tests: 82/82 pasando (PHPUnit)
   - Funcionalidades: CRUD, validación, auditoría, paginación, borrado lógico y restauración

   **Módulo Nóminas** (Planificado — Fase 5):
   - Motor de cálculo: salario base + horas extra + bonus − deducciones − impuestos
   - Retenciones IRPF: tablas IRPF España 2024
   - Seguridad Social: cálculo automático (aportaciones empresa + empleado)
   - Generación PDF: librería TCPDF con plantilla de nómina
   - LOC estimado: ~3.000

   **Módulo Vacaciones** (Planificado — Fase 4):
   - Cálculo anual: 22 días laborables/año (marco legal España)
   - Flujo de solicitud: empleado → responsable → RRHH (opcional)
   - Integración calendario: export iCal para Google Calendar/Outlook
   - Detección de conflictos: evitar solapes dentro del mismo departamento
   - LOC estimado: ~2.500

   **Módulo Documentos** (Planificado — Fase 6):
   - Almacenamiento: cifrado en reposo (AES-256), organizado por `employee_id`
   - Formatos: PDF, JPG, PNG (máx 10MB por fichero)
   - Solicitudes: RRHH → empleado (ej. “subir NIF actualizado”)
   - Control de acceso: empleados (solo propios), RRHH (todos)
   - LOC estimado: ~2.000

   **Módulo Chat** (Planificado — Fase 7):
   - Tiempo real: WebSocket vía librería Ratchet (PHP)
   - Canales: RRHH (1:1) y departamento (grupo)
   - Tipos de mensaje: texto, emojis y adjuntos
   - Retención: 90 días (minimización GDPR)
   - LOC estimado: ~3.500

   **Módulo Quejas** (Planificado — Fase 8):
   - Anonimato: envío anónimo opcional (alineado con GDPR Art. 88)
   - Categorías: acoso, discriminación, seguridad y ética
   - Flujo: abierto → en curso → resuelto → cerrado
   - Acceso: solo RRHH Manager y Admin
   - LOC estimado: ~1.500

6. **Arquitectura Despliegue**:
   ```
   Docker Compose Stack:
   
   services:
     nginx:
       image: nginx:alpine
       ports: 8080:80, 8443:443
       volumes: Certs SSL, nginx.conf
     
     php:
       image: php:8.4-fpm-alpine
       volumes: /app/src
       depends_on: postgres, redis
     
     postgres:
       image: postgres:16-alpine
       volumes: /var/lib/postgresql/data
       healthcheck: pg_isready
     
     redis:
       image: redis:7-alpine
       healthcheck: redis-cli ping
   
   Hosting:
   - On-premise: 3x Dell PowerEdge R250 servers (nginx, php, postgres)
   - Cloud alternative: AWS (EC2 t3.medium x3 + RDS PostgreSQL + ElastiCache Redis)
   ```

7. **Estrategia Backup & DR**:
   - PostgreSQL: Full backup diario + continuous WAL archiving (PITR capable)
   - Retención: 30 días on-site, 90 días off-site (encrypted S3/Azure Blob)
   - Uploads documentos: Rsync diario a NAS + backup semanal tape
   - RTO: 2 horas (restore desde backup a servidor standby)
   - RPO: 15 minutos (intervalo shipping WAL)

## 18. Mapeo de cumplimiento (ISO/IEC/GDPR/NIS2)

Objetivo: mapear controles y requisitos regulatorios a entregables y evidencias del proyecto.
1. **Implementación Controles ISO 27001:2022**:
   
   | Control | Título | Implementación | Status |
   |---------|--------|----------------|--------|
   | A.5.1 | Políticas seguridad información | Políticas SGSI documented | ✅ Done |
   | A.8.1 | Inventario assets | DB assets OT (machinery_inventory.md) | ⏳ In progress |
   | A.8.9 | Gestión configuración | Procedimientos hardening PLC, control cambios | ⏳ Phase 1 |
   | A.12.4 | Logging y monitoring | SIEM centralized (Wazuh) | ⏳ Phase 2 |
   | A.13.1 | Seguridad red | Segmentación Purdue, firewalls | ⏳ Phase 1 |
   | A.14.2 | Seguridad en desarrollo | HR Portal: SDLC seguro, code review, tests | ✅ Phase 3 |
   | A.17.1 | Continuidad negocio | Plan DR para SCADA, procedimientos backup | ⏳ Phase 1 |
   | A.18.1 | Compliance requerimientos legales | GDPR, LOPD-GDD, labor law | ✅ Ongoing |
   
   **Análisis Gap Summary**:
   - Controles total Annex A: 93
   - Actualmente implementados: 28 (30%)
   - Implementación planificada (este proyecto): +45 (48% → 78%)
   - Remaining (post-proyecto): 20 (requieren iniciativas separadas)

2. **Niveles Seguridad IEC 62443**:
   
   **Estado Actual Assessment**: SL0 (no medidas seguridad)
   
   **Estado Target**: SL2 (protección contra violación intencional usando medios simples)
   
   | Requisito Fundamental | Requisitos SL2 | Implementación |
   |-----------------------|----------------|----------------|
   | FR1: Identificación & Auth | Cuentas usuario, password policy, MFA | Jump host, LDAP, MFA |
   | FR2: Uso Control | Role-based access, least privilege | RBAC para SCADA, listas acceso PLC |
   | FR3: Integridad Sistema | Software whitelisting, change detection | File integrity monitoring (Wazuh FIM) |
   | FR4: Confidencialidad Datos | Encriptación in transit (TLS/SSH) | VPN, Modbus encriptado (si soportado) |
   | FR5: Flujo Datos Restringido | Segmentación red, firewalls | Modelo Purdue, VLANs, reglas firewall |
   | FR6: Respuesta Temprana | Event logging, alerting | SIEM con alertas OT-specific |
   | FR7: Disponibilidad Recursos | Redundancia, backup | Failover SCADA, backups diarios |
   
   **Consideraciones SL3** (enhancement futuro):
   - Autenticación avanzada (biometrics, smart cards)
   - Comunicación encriptada a nivel device field (actualmente no todos devices soportan)
   - Coste adicional estimado: +€80.000

3. **Compliance GDPR**:
   
   **Artículo 32: Seguridad del Procesamiento**:
   - ✅ Encriptación: HTTPS, encriptación DB at rest (pgcrypto)
   - ✅ Pseudonimización: IDs empleado (UUIDs), opción anonymous complaint
   - ✅ Confidencialidad: RBAC, acceso need-to-know
   - ✅ Integridad: Audit trail, logs inmutables
   - ✅ Disponibilidad: Backups diarios, 99% uptime SLA
   - ✅ Testing: Penetration testing (anual), drills DR (semi-anual)
   
   **Artículo 33: Notificación Breach**:
   - Alertas SIEM configuradas para intentos exfiltración datos
   - Plan response incident (template en compliance/gdpr/data_breach_notification_template.md)
   - Reloj 72h inicia en detection
   
   **Artículo 35: Data Protection Impact Assessment (DPIA)**:
   - DPIA completada para HR Portal (template en compliance/gdpr/dpia_template.md)
   - Procesamiento high-risk: Datos personales empleado, payroll (financial sensitive)
   - Mitigation: Encriptación, control acceso, logging auditoría
   
   **Artículo 88: Protección Datos Empleado**:
   - HR Portal compliance specific:
     - Procesamiento transparente (privacy notice presented en first login)
     - Minimización data (collect solo necessary fields)
     - Schedule retención (empleados: 10 años post-termination, payroll: 6 años)
     - Canal quejas anonymous (protege whistleblowers)

4. **Requisitos Directiva NIS2**:
   
   **Alcance**: Zabala Gailetak califica como **"entidad esencial"** (producción food, >50 empleados)
   
   **Obligaciones Clave**:
   - ✅ Medidas gestión riesgo (este proyecto implementa comprehensive risk assessment)
   - ✅ Manejo incidentes (SIEM + SOC + plan response incident)
   - ✅ Continuidad negocio (DR para SCADA, procedimientos backup)
   - ✅ Seguridad supply chain (assessment proveedores para PLC/SCADA suppliers)
   - ✅ Training seguridad (120 horas planificadas across todos empleados)
   - ✅ Uso criptografía (TLS, SSH, backups encriptados)
   - ✅ Gestión vulnerabilidades (scans Nessus mensuales)
   
   **Sanciones por Non-compliance**: Hasta €10M o 2% del turnover worldwide
   **Fecha Enforcement**: 17 octubre 2024 (transposición España diciembre 2024)

## 19. Plan de implementación detallado

Objetivo: definir el cronograma de 10 meses con hitos, dependencias, recursos y criterios de aceptación.
1. **Cronograma 10 Meses** (8 fases: Discovery → Go-live → Support)
   - **Fase 1 (Meses 1-2)**: Discovery & Planning
     - OT asset inventory completo
     - Arquitectura diseño (Purdue Model)
     - Risk assessment & gap analysis
     - HR Portal Phase 1 (foundation)
   
   - **Fase 2 (Meses 2-4)**: Foundation Setup
     - SIEM deployment & log sources integration
     - Network segmentation (firewalls, VLANs)
     - Jump host setup & MFA implementation
     - HR Portal Phase 2 (auth + basic CRUD)
   
   - **Fase 3 (Meses 4-6)**: OT Security Implementation
     - PLC hardening procedures
     - Honeypot deployment
     - SOC setup & alert tuning
     - HR Portal Phase 3 (full employee CRUD)
   
   - **Fase 4 (Meses 6-8)**: Application Development
     - HR Portal Phase 4 (vacations module)
     - HR Portal Phase 5 (payroll module)
     - Integration testing IT/OT
     - User acceptance testing
   
   - **Fase 5 (Meses 8-9)**: Testing & Hardening
     - Penetration testing
     - Performance testing
     - Security testing (ISO/IEC compliance)
     - Training delivery
   
   - **Fase 6 (Meses 9-10)**: Deployment & Go-live
     - Production deployment
     - Data migration
     - Parallel run (if required)
     - Go-live support
   
   - **Fase 7 (Meses 10-12)**: Post-Go-Live Support
     - 2-month warranty period
     - SOC monitoring
     - Incident response
     - Performance optimization
   
   - **Fase 8 (Meses 12+)**: Ongoing Operations
     - Quarterly security audits
     - Annual penetration testing
     - SOC services (if contracted)
     - HR Portal maintenance updates

2. **Diagrama Gantt** (placeholder con dependencias)
   - **Hito 1 (Mes 2)**: Infraestructura base completa
   - **Hito 2 (Mes 4)**: Seguridad OT implementada
   - **Hito 3 (Mes 6)**: HR Portal funcional
   - **Hito 4 (Mes 8)**: Testing completo
   - **Hito 5 (Mes 10)**: Go-live exitoso

3. **Asignación Recursos** (consultores + equipo cliente):
   - **Consultores IT/OT**: 2 senior + 1 junior (full-time meses 1-10)
   - **Consultores HR**: 1 senior + 1 junior (part-time meses 4-10)
   - **Equipo Cliente**: 1 IT manager, 2 IT engineers, 1 HR manager (part-time)
   - **Vendors**: Siemens (PLC support), Rockwell (Allen-Bradley support)

4. **Criterios Go-live** por pilar:
   - **OT Security**: 0 vulnerabilidades críticas, segmentación tested
   - **SIEM**: <5% false positive rate, 30 log sources integrated
   - **Honeypots**: Operational, integrated con SIEM
   - **HR Portal**: 82+ tests passing, data migration successful

5. **Plan Formación** (120h total):
   - **Administradores Sistema** (40h): OT security, SIEM operation, backup procedures
   - **Ingenieros OT** (40h): PLC security, Purdue Model, incident response
   - **Equipo RRHH** (40h): HR Portal usage, compliance, data protection

## 20. Gestión de riesgos y FMEA

Objetivo: identificar riesgos de implantación/operación y acordar mitigaciones y planes de rollback.
1. **Riesgos Implementación**:
   - **Parada producción**: Durante segmentación OT (mitigación: ventanas mantenimiento, Factory I/O testing)
   - **Resistencia cambio**: Staff producción (mitigación: comunicación change management, training)
   - **Compatibilidad legacy**: PLCs antiguos (mitigación: assessment compatibility, upgrade plan)
   - **Adopción HR Portal**: Usuarios (mitigación: training, feedback loops, champions)

2. **Análisis FMEA Cambios OT** (Failure Mode Effects Analysis):
   ```
   Función: Segmentación red OT/IT
   Modo Falla: Firewall mal configurado bloquea comunicaciones SCADA
   Efectos: Pérdida control proceso producción, parada línea
   Severidad: 9 (Critical), Ocurrencia: 3 (Possible), Detección: 2 (Low)
   RPN: 54 (High)
   Acciones Mitigación: Testing paralelo, rollback plan, monitoring 24x7
   ```
   
   ```
   Función: Hardening PLC
   Modo Falla: Firmware update falla, PLC queda inoperable
   Efectos: Pérdida control máquina específica, impacto producción
   Severidad: 8 (High), Ocurrencia: 2 (Low), Detección: 3 (Medium)
   RPN: 48 (High)
   Acciones Mitigación: Backup completo, testing labor, procedure rollback
   ```

3. **Estrategias Mitigación**:
   - **Testing seguro**: Uso Factory I/O para simulación cambios OT
   - **Change management**: Comunicación stakeholder, training, soporte on-site
   - **Rollback procedures**: Documentadas para cada componente crítico
   - **Monitoring continuo**: Durante implementación, alertas automáticas

4. **Gestión Cambio**:
   - **Comunicación**: Stakeholder meetings semanales, newsletters
   - **Training**: Sesiones hands-on, materiales en euskera
   - **Support**: Help desk dedicado durante go-live
   - **Feedback**: Surveys post-training, adjustment procedures

## 21. Anexos técnicos

Objetivo: incluir material técnico complementario (diagramas, plantillas y checklists) para operación y auditoría.
1. **Anexo A: Diagramas Arquitectura**
   - Diagrama A: Topología física (racks, switches, firewalls)
   - Diagrama B: Arquitectura lógica VLAN
   - Diagrama C: Flujo datos (SCADA → Historian → SIEM)

2. **Anexo B: Especificaciones Hardware**
   - SIEM cluster: 3x Dell PowerEdge R250 (16GB RAM, 500GB SSD each)
   - Jump host: 1x Dell PowerEdge R250 (8GB RAM, 250GB SSD)
   - Honeypots: 1x Dell PowerEdge R250 (16GB RAM, 500GB SSD)
   - HR Portal: 3x Dell PowerEdge R250 (nginx + php + postgres)

3. **Anexo C: Lista Materiales (BOQ)**
   - Licencias software: Wazuh Enterprise (€50.000), Nessus Industrial (€10.000)
   - Hardware: servidores Dell (€15.000), switches industriales (€25.000), firewalls (€30.000)
   - Formación: 120h @ €150/h = €18.000
   - Viajes/alojamiento: €5.000
   - **Total BOQ**: €153.000 (excluye labor consultores)

4. **Anexo D: Plantillas Reglas Firewall**
   ```
   # IT/OT Boundary Firewall (Fortinet/Palo Alto)
   rule allow-scada-access
     source: VLAN10 (IT)
     destination: VLAN30 (SCADA)
     service: RDP
     action: allow
     log: enable
     user: authenticated
     mfa: required
   
   rule deny-ot-direct
     source: VLAN10 (IT)
     destination: VLAN40-50 (OT)
     action: deny
     log: enable
   ```

5. **Anexo E: Librería Reglas Alerta SIEM** (50+ casos uso)
   - Autenticación: 10 reglas con thresholds y severidades
   - OT: 15 reglas con protocolos ICS específicos
   - Malware: 8 reglas con indicadores comportamiento
   - Exfiltración: 7 reglas con límites data transfer

6. **Anexo F: Documentación API HR Portal**
   - Endpoints completos con request/response examples
   - Authentication flow con JWT
   - Error codes y handling
   - Rate limits y throttling

7. **Anexo G: Matrices Checklist Cumplimiento**
   - ISO 27001: 93 controles con status implementation
   - IEC 62443: SL2 requirements mapping
   - GDPR: Articles 32, 33, 35, 88 checklist
   - NIS2: Essential entity obligations

8. **Anexo H: Fichas Técnicos Proveedores**
   - Siemens TIA Portal: PLC programming software
   - Rockwell Studio 5000: Allen-Bradley control
   - Wazuh: SIEM platform specifications
   - T-Pot: Honeypot framework details

---

## Próximos pasos (para decisión y arranque)

1. Validación de alcance: confirmar si el programa se contrata como paquete (Básico/Profesional/Empresarial) o como implantación integral.
2. Taller de arranque (Kick-off): stakeholders IT/OT/RRHH, calendario de ventanas de cambio, y criterios de aceptación por pilar.
3. Confirmación de dependencias: accesos, listado de activos, disponibilidad de producción, y requisitos legales (DPIA/retención).
4. Aprobación del plan de hitos: fechas objetivo y responsables de validación por parte de Zabala Gailetak.
5. Inicio Fase 1: discovery + inventario OT + arquitectura + SIEM base.