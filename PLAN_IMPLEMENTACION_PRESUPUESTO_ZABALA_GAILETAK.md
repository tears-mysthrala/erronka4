# 📋 PLAN COMPLETO DE IMPLEMENTACIÓN - PRESUPUESTO ZABALA GAILETAK SEGURIDAD INTEGRAL

## 🎯 **VISIÓN GENERAL DEL PROYECTO**

**Cliente**: Zabala Gailetak S.A. - Panificadora industrial (120 empleados, País Vasco)  
**Alcance**: Seguridad OT/ICS + SIEM/SOC + Honeypots + Portal RRHH  
**Presupuesto Total**: €733,950 Year 1 + €129K/año recurrente  
**Timeline**: 10 meses implementación (Enero-Diciembre 2026)  
**ROI**: 137.6% sobre 3 años (€786K/año beneficios)  

**Resultado esperado**: Documento profesional IT/OT de 35-40 páginas siguiendo estándares de consultoría Accenture/Deloitte, dividido en 3 partes para diferentes audiencias.

---

## 📄 **ESTRUCTURA DEL DOCUMENTO FINAL**

```
PRESUPUESTO_ZABALA_GAILETAK_SEGURIDAD_INTEGRAL.md
├── Parte I: Resumen Ejecutivo (6 páginas) ⏳ PENDIENTE
├── Parte II: Propuesta Comercial (8-10 páginas) ⏳ PENDIENTE  
│   ├── Sección 7: Paquetes de Servicio (3 niveles)
│   ├── Sección 8: Precios Detallados con Hitos
│   ├── Sección 9: Términos Comerciales
│   ├── Sección 10: Casos de Éxito y Referencias
│   ├── Sección 11: Acuerdos de Nivel de Servicio
│   └── Sección 12: Propuesta de Valor y Diferenciación
└── Parte III: Especificaciones Técnicas (20-25 páginas) ⏳ PENDIENTE
    ├── Sección 13: Arquitectura IT/OT (Modelo Purdue)
    ├── Sección 14: Implementación Seguridad OT
    ├── Sección 15: SIEM & SOC (Wazuh vs ELK)
    ├── Sección 16: Arquitectura Despliegue Honeypots
    ├── Sección 17: Especificaciones Técnicas HR Portal
    ├── Sección 18: Mapeo Cumplimiento (ISO/IEC/GDPR)
    ├── Sección 19: Plan de Implementación Detallado
    ├── Sección 20: Gestión de Riesgos & FMEA
    └── Sección 21: Anexos Técnicos
```

---

## 📝 **PARTE II: PROPUESTA COMERCIAL** (Páginas 7-16)

### **Sección 7: Paquetes de Servicio** (Páginas 7-9)
**Objetivo**: Presentar 3 niveles de servicio para diferentes necesidades y presupuestos

**Contenido detallado**:
1. **Paquete Básico - "OT Foundation"** (€180,000)
   - OT Security audit completo
   - SIEM básico (8x5 monitoring)
   - Formación básica (40 horas)
   - Soporte email/telefónico
   - Duración: 3 meses

2. **Paquete Profesional - "OT Advanced"** (€324,000) ⭐ **RECOMENDADO**
   - Todo del Básico +
   - Segmentación completa de red
   - Honeypots desplegados
   - SIEM avanzado con alertas OT
   - Formación avanzada (80 horas)
   - Soporte prioritario
   - Duración: 5 meses

3. **Paquete Empresarial - "OT Enterprise"** (€733,950)
   - Todo del Profesional +
   - HR Portal completo
   - SOC 24x7 durante 6 meses
   - Integración completa IT/OT
   - Soporte 24x7
   - Duración: 10 meses

4. **Matriz Comparativa de Características**
   ```
   | Característica | Básico | Profesional | Empresarial |
   |----------------|--------|-------------|-------------|
   | OT Audit | ✅ | ✅ | ✅ |
   | SIEM 8x5 | ✅ | ✅ | ✅ |
   | Segmentación Red | ❌ | ✅ | ✅ |
   | Honeypots | ❌ | ✅ | ✅ |
   | HR Portal | ❌ | ❌ | ✅ |
   | SOC 24x7 | ❌ | ❌ | ✅ |
   | Soporte | Email | Prioritario | 24x7 |
   | Formación | 40h | 80h | 120h |
   ```

5. **Ruta de Migración**: Cómo crecer de Básico → Profesional → Empresarial según presupuesto disponible

### **Sección 8: Precios Detallados con Hitos** (Páginas 10-11)
**Objetivo**: Desglose transparente de costos y pagos

**Contenido detallado**:
1. **Desglose por Pilar** (basado en Parte I):
   - **Pilar 1: OT Security** €180,000 (25%)
     - Asset inventory & audit: €40K
     - Network segmentation: €60K
     - PLC hardening: €35K
     - Jump host setup: €25K
     - Documentation & training: €20K

   - **Pilar 2: SIEM & SOC** €120,000 (16%)
     - Platform setup: €50K
     - Log integration: €30K
     - Alert development: €20K
     - SOC staffing (6 months): €20K

   - **Pilar 3: Honeypots** €24,000 (3%)
     - T-Pot platform: €8K
     - Conpot ICS: €10K
     - Integration: €6K

   - **Pilar 4: HR Portal** €300,000 (41%)
     - Backend development: €120K
     - Web frontend: €60K
     - Android app: €80K
     - Deployment: €40K

   - **PM & Audit** €110,000 (15%)
     - Project management: €50K
     - Compliance audit: €30K
     - Risk assessment: €30K

2. **Hitos de Pago** (contrato escalonado):
   ```
   Hito 1 (30%): Firma contrato - €220,185
   Hito 2 (20%): OT Audit completo + SIEM setup - €146,790
   Hito 3 (20%): Segmentación + HR Portal Fase 1 - €146,790
   Hito 4 (20%): Implementación completa - €146,790
   Hito 5 (10%): Go-live + aceptación - €73,395
   ```

3. **Costos Recurrentes Year 2+** (€129,000/año):
   - SIEM monitoring: €24K/año
   - SOC services (opcional): €60K/año
   - HR Portal maintenance: €30K/año
   - Security updates: €15K/año

4. **Add-ons Opcionales**:
   - Penetration testing anual: €12K
   - Incident response retainer: €15K
   - Additional training: €1,500/día
   - On-site audits trimestrales: €8K/año

### **Sección 9: Términos Comerciales** (Páginas 12-13)
**Objetivo**: Términos contractuales claros y profesionales

**Contenido detallado**:
1. **Garantías**:
   - Software (HR Portal): 12 meses defect warranty
   - Consultoría: 6 meses warranty on deliverables
   - Hardware: 3 años manufacturer warranty
   - Configuraciones seguridad: 90 días adjustment period

2. **Términos de Pago**:
   - Net 30 días desde factura
   - Penalización mora: 1.5% mensual
   - Descuento pronto pago: 3% si paga en 10 días
   - Métodos aceptados: Transferencia bancaria, cheque corporativo

3. **Duración del Contrato**:
   - **Year 1**: Contrato implementación (10 meses)
   - **Year 2-3**: Contrato mantenimiento (opcional)
   - **Descuento renovación**: 10% descuento por compromiso 3 años anticipado

4. **Cláusulas de Terminación**:
   - Cancelación cliente: Reembolsos basados en hitos completados menos trabajo realizado
   - Fuerza mayor: Ambas partes exentas
   - No rendimiento: Período remediation de 30 días

5. **Límites de Responsabilidad**:
   - Cap general: €733,950 (valor contrato)
   - Daños consecuenciales: Excluidos (excepto negligencia grave)
   - Seguro ciber: Cobertura €2M mantenida

6. **Propiedad Intelectual**:
   - Código HR Portal: Propiedad Zabala Gailetak tras pago completo
   - Configuraciones seguridad: Licenciadas para uso Zabala
   - Materiales formación: Licencia perpetua

### **Sección 10: Casos de Éxito y Referencias** (Página 14)
**Objetivo**: Credibilidad mediante casos similares anónimos

**Contenido detallado**:
1. **Caso de Estudio 1: Industria Láctea (Navarra)** (Anónimo)
   - **Perfil**: 200 empleados, automatización producción
   - **Desafío**: Sin seguridad OT, incidente ransomware scare
   - **Solución**: Similar audit OT + SIEM + segmentación
   - **Resultados**: 0 incidentes en 24 meses, certificación ISO 27001, ROI 180%

2. **Caso de Estudio 2: Panificadora Industrial** (Similar Zabala)
   - **Perfil**: 80 empleados, líneas producción robotizadas
   - **Desafío**: SCADA legacy, sin trail auditoría
   - **Solución**: Modelo Purdue + jump hosts
   - **Resultados**: Audit IEC 62443 SL2 aprobado, €300K parada producción evitada

3. **Caso de Estudio 3: Digitalización RRHH SME**
   - **Perfil**: 150 empleados, fabricante industrial
   - **Desafío**: Procesos RRHH papel, gaps compliance GDPR
   - **Solución**: Portal HR custom con app móvil
   - **Resultados**: 60% reducción tiempo admin RRHH, €50K ahorros anuales

4. **Referencias** (con permiso):
   - Información de contacto: Nombre, empresa, teléfono
   - Recomendaciones LinkedIn
   - Certificaciones: ISO 27001 Lead Auditor, CISSP, IEC 62443 Certified

### **Sección 11: Acuerdos de Nivel de Servicio** (Página 15)
**Objetivo**: Compromisos de rendimiento medibles

**Contenido detallado**:
1. **Tiempos Respuesta SIEM/SOC**:
   ```
   | Severidad | Detección | Respuesta | Resolución |
   |-----------|-----------|-----------|------------|
   | Crítico   | 5 min     | 15 min    | 4 horas    |
   | Alto      | 15 min    | 1 hora    | 24 horas   |
   | Medio     | 1 hora    | 4 horas   | 5 días     |
   | Bajo      | 4 horas   | 24 horas  | 30 días    |
   ```

2. **Disponibilidad del Sistema**:
   - SIEM: 99.5% uptime (máx 3.65h downtime/mes)
   - HR Portal: 99.0% uptime (horas business 7am-11pm)
   - Honeypots: 95% uptime (sistemas aislados)
   - Red OT: 99.9% uptime (máx 43 min/mes)

3. **Canales de Soporte**:
   - **Hotline 24x7**: +34 XXX XXX XXX (paquete Empresarial)
   - **Sistema Ticketing**: Respuesta <2h business
   - **Email Support**: Respuesta <8h business
   - **Soporte On-site**: <4h para incidentes críticos (País Vasco)

4. **Gestión de Parches**:
   - Críticos: <72h
   - Alto: <7 días
   - Regulares: Ventana mantenimiento mensual

5. **Créditos SLA** (si SLA no cumplido):
   - 99.5%-99.0%: 10% crédito mensual
   - 99.0%-95.0%: 25% crédito mensual
   - <95%: 50% crédito + plan remediation

### **Sección 12: Propuesta de Valor y Diferenciación** (Página 16)
**Objetivo**: Por qué elegirnos vs competencia

**Contenido detallado**:
1. **Diferenciadores Clave**:
   - ✅ **Especialización OT/ICS**: Una de las pocas firmas con expertise IEC 62443 en País Vasco
   - ✅ **Experiencia Sector Alimentario**: Entendimiento integración HACCP + ciberseguridad
   - ✅ **Presencia Local**: Equipo Bilbao, disponibilidad on-site <2 horas
   - ✅ **Apoyo Dual Compliance**: Enfoque combinado ISO 27001 + IEC 62443 (ahorro costos)
   - ✅ **Soporte Euskera**: Documentación y formación en euskera nativo

2. **Ventajas Técnicas**:
   - Simulación Factory I/O + OpenPLC (pruebas seguras antes producción)
   - Honeypots Conpot especializados para protocolos alimentación
   - HR Portal construido desde cero para GDPR Art. 88 (protección datos empleados)
   - Stack PostgreSQL + Redis (grado enterprise, costo eficiente open-source)

3. **Valor de Negocio**:
   - **Mitigación Riesgo**: Evitación costo ransomware promedio €1.2M
   - **Continuidad Operativa**: Prevención parada producción €500K/año
   - **Compliance**: Evitar multas GDPR €20M, mantener contratos B2B requiriendo ISO 27001
   - **Eficiencia RRHH**: Reducción 60% overhead admin (ahorros €44K/año)

4. **Posicionamiento Competitivo**:
   ```
   | Factor | Zabala Security Project | Firma IT Genérica | Consultoría Grande |
   |--------|-------------------------|-------------------|---------------------|
   | Expertise OT | ✅ IEC 62443 certified | ❌ Solo IT | ✅ Pero cara |
   | Sector Alimentario | ✅ Especializada | ⚠️ Genérica | ⚠️ Genérica |
   | Disponibilidad Local | ✅ <2h | ❌ Remoto solo | ❌ HQ Madrid |
   | Costo | €733K Year 1 | €500K (IT solo) | €1.2M+ |
   | Soporte Euskera | ✅ Nativo | ❌ Español solo | ❌ Español solo |
   | HR Portal Incluido | ✅ Custom-built | ❌ Fuera scope | ✅ Pero COTS |
   ```

5. **Métricas de Éxito** (rastreadas en dashboard):
   - 0 incidentes impacting producción
   - <5% false positive rate SIEM tras 90 días
   - 95%+ adopción HR Portal en 6 meses
   - Certificación ISO 27001 + IEC 62443 en 12 meses

---

## 🛠️ **PARTE III: ESPECIFICACIONES TÉCNICAS** (Páginas 17-42)

### **Sección 13: Arquitectura IT/OT (Modelo Purdue)** (Páginas 17-19)
**Objetivo**: Diseño técnico de la arquitectura segura

**Contenido detallado**:
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

### **Sección 14: Implementación Seguridad OT** (Páginas 20-22)
**Objetivo**: Detalles técnicos implementación OT

**Contenido detallado**:
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

### **Sección 15: SIEM & SOC (Wazuh vs ELK)** (Páginas 23-25)
**Objetivo**: Especificaciones plataforma SIEM/SOC

**Contenido detallado**:
1. **Matriz Comparación Plataforma**:
   
   | Criterio | Wazuh (Recomendado) | ELK Stack (Alternativa) | AlienVault OSSIM |
   |-----------|---------------------|-------------------------|------------------|
   | Costo | €0 (open-source) | €0 (core) | €0 (open-source) |
   | Soporte OT/ICS | ✅ Parsers Modbus/S7Comm | ⚠️ Plugins Logstash requeridos | ⚠️ Limitado OT |
   | Escalabilidad | ✅ 10K+ agentes | ✅ Excelente (Elasticsearch) | ❌ Límite 1 nodo |
   | Curva Aprendizaje | Medio | Alto | Medio |
   | Comunidad | ✅ Activa | ✅ Muy activa | ⚠️ Declinante |
   | Capacidad EDR | ✅ Built-in | ❌ Add-ons requeridos | ❌ No EDR |
   | RBAC | ✅ Granular | ✅ Con X-Pack (paid) | ✅ Básico |
   | Compliance | ✅ PCI-DSS, GDPR reports | ✅ Custom | ✅ Pre-built |
   
   **DECISIÓN**: **Wazuh** por soporte OT + simplicidad + EDR + costo = €0 licensing

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
   
   **Opción A: SOC 8x5** (€20K/6 meses, incluido presupuesto):
   - Cobertura: L-V 8am-5pm
   - Staffing: 1 analista L1 + 1 L2 (cobertura part-time)
   - Escalación: Ingeniero on-call para alertas críticas
   
   **Opción B: SOC 24x7** (+€60K/año, paquete Empresarial):
   - Cobertura: 24h, 7 días, 365 días
   - Staffing: 3 shifts x 2 analistas = 6 FTE
   - Escalación: Equipo response incident dedicado

### **Sección 16: Arquitectura Despliegue Honeypots** (Páginas 26-27)
**Objetivo**: Diseño técnico honeypots

**Contenido detallado**:
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

### **Sección 17: Especificaciones Técnicas HR Portal** (Páginas 28-31)
**Objetivo**: Detalles técnicos portal HR

**Contenido detallado**:
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
   
   **✅ Módulo Empleados** (Phase 3 - COMPLETADO):
   - Líneas código: ~5,500 (backend + web + mobile)
   - Tests: 82/82 pasando (PHPUnit)
   - Features: CRUD, validación, audit trail, paginación, soft delete, restore
   
   **⏳ Módulo Nóminas** (Planificado - Phase 5):
   - Engine cálculo: Base salary + horas extra + bonuses - deducciones - taxes
   - Withholding tax: Tablas IRPF español 2024
   - Seguridad social: Cálculo automático (contribuciones employer + employee)
   - Generación PDF: Librería TCPDF con template nómina oficial
   - Estimated LOC: ~3,000
   
   **⏳ Módulo Vacaciones** (Planificado - Phase 4):
   - Balance anual cálculo: 22 días laborables/año (ley española)
   - Workflow solicitud: Empleado → Manager → HR (opcional)
   - Integración calendario: Export iCal para Google Calendar/Outlook
   - Detección conflicto: Prevenir vacaciones overlapping en mismo departamento
   - Estimated LOC: ~2,500
   
   **⏳ Módulo Documentos** (Planificado - Phase 6):
   - File storage: Encriptado at rest (AES-256), organizado por employee_id
   - Tipos soportados: PDF, JPG, PNG (máx 10MB por file)
   - Solicitudes documentos: HR → Empleado (ej. "Upload NIF scan updated")
   - Control acceso: Empleados ven documentos propios only, HR ve todos
   - Estimated LOC: ~2,000
   
   **⏳ Módulo Chat** (Planificado - Phase 7):
   - Real-time: WebSocket vía librería Ratchet PHP
   - Canales: Chat RRHH (1-on-1 con dept HR), Chat Departamento (group)
   - Tipos mensaje: Text, emoji, attachments file
   - Retención: 90 días (minimización data GDPR)
   - Estimated LOC: ~3,500
   
   **⏳ Módulo Quejas** (Planificado - Phase 8):
   - Anonimato: Opcional anonymous submission (compliance GDPR Art. 88)
   - Categorías: Harassment, discrimination, safety, ethics
   - Workflow: Open → In Progress → Resolved → Closed
   - Acceso: HR Manager + Admin only
   - Estimated LOC: ~1,500

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

### **Sección 18: Mapeo Cumplimiento (ISO/IEC/GDPR)** (Páginas 32-34)
**Objetivo**: Cómo el proyecto cumple requirements compliance

**Contenido detallado**:
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
   - Costo adicional estimated: +€80K

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

### **Sección 19: Plan Implementación Detallado** (Páginas 35-36)
**Objetivo**: Timeline detallado con dependencias

**Contenido detallado**:
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

### **Sección 20: Gestión de Riesgos & FMEA** (Página 37)
**Objetivo**: Identificación y mitigación riesgos

**Contenido detallado**:
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

### **Sección 21: Anexos Técnicos** (Páginas 38-42)
**Objetivo**: Documentación técnica detallada

**Contenido detallado**:
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
   - Software licenses: Wazuh Enterprise (€50K), Nessus Industrial (€10K)
   - Hardware: Servers Dell (€15K), switches industrial (€25K), firewalls (€30K)
   - Training: 120h @ €150/h = €18K
   - Travel/accommodation: €5K
   - **Total BOQ**: €153K (excluye labor consultores)

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

## 📊 **MÉTRICAS CALIDAD FINAL**

### **Consistencia**
- ✅ Figuras presupuesto: €733.95K Year 1, ROI 137.6%
- ✅ Timeline: 10 meses, 8 fases
- ✅ Alcance: OT + SIEM + Honeypots + HR Portal
- ✅ Tecnología: Wazuh SIEM, PHP 8.4, PostgreSQL 16

### **Profesionalismo**
- ✅ Estructura consultoría (Ejecutivo → Comercial → Técnico)
- ✅ Lenguaje formal, métricas cuantificables
- ✅ Riesgos identificados con mitigation
- ✅ Compliance legal completo (GDPR, NIS2, ISO, IEC)

### **Completitud**
- ✅ Fuentes: 158 archivos .md referenciados
- ✅ Detalles técnicos: comandos, configs, endpoints
- ✅ Casos reales: referencias sector alimentación
- ✅ Legal: contratos, garantías, SLA profesionales

---

## 🚀 **SIGUIENTE PASOS EJECUCIÓN**

1. **Empieza Sección 7** (paquetes) - establece tono comercial
2. **Sección 13** (arquitectura Purdue) - diagrama fundamental
3. **Sección 17** (HR Portal) - usa FASE_3_RESUMEN.md como base
4. **Revisa consistencia** cada 3-4 secciones
5. **Final**: formatea markdown profesional

**Tiempo estimado**: 4-6 horas escritura + 2-3 horas revisión
**Resultado**: Documento board-ready para presentación CEO/CFO

¿Te ayudo a empezar escribiendo alguna sección específica del plan? 🤔