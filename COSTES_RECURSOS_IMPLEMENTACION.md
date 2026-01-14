# 💰 Análisis de Costes, Recursos y Salarios - Zabala Gailetak

## Plataforma de E-commerce Segura con SIEM, OT/PLC e Infraestructura de Honeypots

**Versión**: 1.0  
**Fecha**: 12 de Enero 2026  
**Empresa**: Zabala Gailetak Security Solutions  

---

## 📊 RESUMEN EJECUTIVO

Este documento detalla los costes completos de implementación del sistema Zabala Gailetak, incluyendo:

- Infraestructura hardware/cloud
- Licencias de software
- Recursos humanos (salarios)
- Costes operacionales
- Oferta comercial para cliente empresarial

**Inversión Total (Implementación)**: €187,950 - €257,250  
**Coste Operacional Anual**: €348,000 - €456,000  
**Precio de Venta al Cliente**: €425,000 - €650,000 (primera instalación + año 1)

---

## 🖥️ PARTE 1: COSTES DE INFRAESTRUCTURA

### Opción A: Infraestructura On-Premise (CAPEX)

#### Hardware - Configuración de 4 Servidores

| Servidor | Especificaciones | Precio Unitario | Cantidad | Total |
|----------|------------------|-----------------|----------|-------|
| **Servidor Producción** | Dell PowerEdge R750, 8 cores, 32 GB RAM, 2x 960GB SSD RAID1 | €4,200 | 2 (HA) | €8,400 |
| **Servidor SIEM** | Dell PowerEdge R750, 16 cores, 64 GB RAM, 4x 960GB SSD RAID10 | €7,800 | 2 (cluster) | €15,600 |
| **Servidor OT/PLC** | Dell PowerEdge R650, 6 cores, 16 GB RAM, 2x 480GB SSD RAID1 | €3,200 | 1 | €3,200 |
| **Servidor Honeypot** | Dell PowerEdge R650, 12 cores, 32 GB RAM, 4x 480GB SSD RAID10 | €5,400 | 1 | €5,400 |
| **Firewall/Router** | Fortinet FortiGate 200F, 20 Gbps, SSL inspection, IPS/IDS | €8,500 | 2 (HA pair) | €17,000 |
| **Switch Core** | Cisco Catalyst 9300-48U, 48 puertos, VLAN, ACL, QoS, PoE+ | €6,500 | 2 (stack) | €13,000 |
| **UPS** | APC Smart-UPS SRT 6kVA, 15 min autonomía, double-conversion | €3,200 | 2 | €6,400 |
| **Rack 42U** | Incluye PDUs, bandejas, ventilación | €1,200 | 1 | €1,200 |
| **Cableado estructurado** | Cat6a, latiguillos, patch panel | €800 | 1 | €800 |

**Subtotal Hardware**: **€71,000**

#### Storage Adicional

| Item | Especificaciones | Precio |
|------|------------------|--------|
| **NAS Backup/Archive** | Synology RS2421RP+, 12 bahías, 12x 8TB SATA, RAID6, 10Gbe | €8,500 |
| **Cloud Backup** | AWS S3 Glacier<br>- 100 TB/año<br>- Replicación off-site | €2,400/año |

**Subtotal Storage**: **€8,500 + €2,400/año**

#### Software y Licencias (3 años)

| Software | Tipo | Coste |
|----------|------|-------|
| **Red Hat Enterprise Linux** | 10 suscripciones Standard (3 años) | €15,000 |
| **MongoDB Enterprise** | Advanced features, HA, Support (3 años) | €18,000 |
| **Fortinet FortiCare** | 24x7 support + updates (3 años) | €12,000 |
| **Elastic Stack Enterprise** | Gold license SIEM (3 años) | €24,000 |
| **SSL Certificates** | Wildcard + EV (3 años) | €1,800 |
| **Conpot/T-Pot** | Open source (€0) | €0 |
| **OpenPLC** | Open source (€0) | €0 |

**Subtotal Licencias (3 años)**: **€70,800** (€23,600/año amortizado)

#### Instalación y Configuración

| Concepto | Coste |
|----------|-------|
| Instalación física (rack, cableado) | €2,500 |
| Configuración inicial servidores | €4,000 |
| Configuración red, firewall, VLANs | €6,000 |
| Migración de datos (si aplica) | €3,000 |
| Pruebas de aceptación (1 semana) | €5,000 |

**Subtotal Instalación**: **€20,500**

### **TOTAL OPCIÓN A (On-Premise)**

- **CAPEX inicial**: €170,800
- **OPEX anual**: €2,400 (cloud backup)
- **Licencias anuales** (tras 3 años): €23,600/año

---

### Opción B: Infraestructura Cloud (AWS) - OPEX

#### Configuración AWS (Región eu-west-1 - Irlanda)

| Servicio | Especificación | Coste Mensual | Coste Anual |
|----------|----------------|---------------|-------------|
| **EC2 - Producción API** | 2x c6i.2xlarge (8 vCPU, 16GB)<br>Application Load Balancer | €520 | €6,240 |
| **EC2 - MongoDB** | 3x r6i.xlarge (4 vCPU, 32GB)<br>Replica Set | €780 | €9,360 |
| **ElastiCache Redis** | cache.r6g.large (2 vCPU, 13GB)<br>Multi-AZ | €180 | €2,160 |
| **EC2 - SIEM Elasticsearch** | 3x r6i.2xlarge (8 vCPU, 64GB)<br>Cluster | €1,560 | €18,720 |
| **EC2 - Logstash** | 2x c6i.xlarge (4 vCPU, 8GB) | €260 | €3,120 |
| **EC2 - Kibana** | 1x t3.large (2 vCPU, 8GB) | €65 | €780 |
| **EC2 - OT/PLC** | 1x t3.xlarge (4 vCPU, 16GB) | €120 | €1,440 |
| **EC2 - Honeypots** | 1x c6i.2xlarge (8 vCPU, 16GB) | €260 | €3,120 |
| **EBS Storage** | 2 TB gp3 SSD (Producción)<br>6 TB gp3 SSD (SIEM)<br>500 GB gp3 (OT/Honeypot) | €520 | €6,240 |
| **S3 Storage** | 10 TB logs archive<br>Lifecycle to Glacier | €240 | €2,880 |
| **RDS Backup** | Automated backups MongoDB<br>30 días retención | €150 | €1,800 |
| **VPC, NAT Gateway** | 3 AZ, redundancia | €180 | €2,160 |
| **CloudWatch** | Logs, métricas, alarmas | €120 | €1,440 |
| **WAF + Shield** | Protección DDoS, bot filtering | €350 | €4,200 |
| **Data Transfer OUT** | 5 TB/mes tráfico salida | €450 | €5,400 |

**Subtotal AWS Mensual**: **€5,755**  
**Subtotal AWS Anual**: **€69,060**

#### Servicios Gestionados AWS (Alternativa)

| Servicio | Reemplazo | Coste Mensual | Coste Anual |
|----------|-----------|---------------|-------------|
| **Amazon OpenSearch** | Reemplaza ELK Stack<br>3 nodos r6g.2xlarge.search | €2,100 | €25,200 |
| **DocumentDB** | Reemplaza MongoDB<br>3 nodos r6g.xlarge | €1,200 | €14,400 |
| **GuardDuty** | Threat detection nativa | €150 | €1,800 |
| **Security Hub** | Compliance checks | €50 | €600 |

**Subtotal Managed Services**: **+€3,500/mes** (€42,000/año)

### **TOTAL OPCIÓN B (AWS Cloud)**

- **Infraestructura Self-Managed**: €69,060/año
- **Infraestructura Managed Services**: €111,060/año
- **Sin CAPEX inicial** (solo migration ~€5,000)

---

### Opción C: Híbrido (On-Premise Producción + Cloud SIEM/Backup)

| Componente | Ubicación | Coste |
|------------|-----------|-------|
| Servidores Producción + OT | On-premise | €48,000 CAPEX |
| SIEM (OpenSearch Service) | AWS | €25,200/año |
| Honeypots | AWS | €3,600/año |
| Backup/DR | AWS S3 + Glacier | €4,800/año |
| Conectividad AWS Direct Connect | 1Gbps | €3,600/año |

### **TOTAL OPCIÓN C (Híbrido)**

- **CAPEX inicial**: €48,000
- **OPEX anual**: €37,200

---

## 👥 PARTE 2: RECURSOS HUMANOS Y SALARIOS

### Fase 1: Implementación (6 meses)

#### Equipo de Proyecto

| Rol | Dedicación | Salario Bruto Anual | Coste 6 meses | Cantidad | Total |
|-----|------------|---------------------|---------------|----------|-------|
| **Project Manager Senior** | 100% | €65,000 | €32,500 | 1 | €32,500 |
| **Arquitecto de Seguridad** | 100% | €75,000 | €37,500 | 1 | €37,500 |
| **DevOps Engineer Senior** | 100% | €60,000 | €30,000 | 2 | €60,000 |
| **Backend Developer (Node.js)** | 100% | €50,000 | €25,000 | 2 | €50,000 |
| **Frontend Developer (React)** | 100% | €48,000 | €24,000 | 1 | €24,000 |
| **QA/Security Tester** | 100% | €45,000 | €22,500 | 1 | €22,500 |
| **DBA/Data Engineer** | 50% | €55,000 | €13,750 | 1 | €13,750 |
| **OT/SCADA Specialist** | 50% | €70,000 | €17,500 | 1 | €17,500 |
| **SIEM Analyst** | 75% | €52,000 | €19,500 | 1 | €19,500 |

**Subtotal Salarios (6 meses implementación)**: **€277,250**

**+ Cargas sociales (30%)**: **€83,175**

**Total Recursos Humanos Implementación**: **€360,425**

---

### Fase 2: Operación Continua (Anual)

#### Equipo Operacional

| Rol | Dedicación | Salario Bruto Anual | Cantidad | Total Anual |
|-----|------------|---------------------|----------|-------------|
| **IT Manager/CISO** | 100% | €70,000 | 1 | €70,000 |
| **DevOps Engineer** | 100% | €55,000 | 2 | €110,000 |
| **Backend Developer** | 100% | €48,000 | 1 | €48,000 |
| **SOC Analyst (SIEM)** | 100% | €45,000 | 2 | €90,000 |
| **SOC Analyst (turnos 24x7)** | 100% | €42,000 | 2 | €84,000 |
| **DBA (MongoDB/Redis)** | 50% | €55,000 | 1 | €27,500 |
| **OT Security Engineer** | 75% | €60,000 | 1 | €45,000 |
| **Incident Responder** | On-call | €50,000 | 1 | €50,000 |

**Subtotal Salarios Anuales**: **€524,500**

**+ Cargas sociales (30%)**: **€157,350**

**Total Recursos Humanos Operación Anual**: **€681,850**

---

### Costes Indirectos de Personal

| Concepto | Coste Anual |
|----------|-------------|
| Formación y certificaciones (CISSP, CEH, GIAC) | €15,000 |
| Herramientas de desarrollo (IDE, licencias) | €5,000 |
| Hardware para equipo (portátiles, monitores) | €25,000 (amortizado 3 años = €8,333/año) |
| Viajes y desplazamientos | €8,000 |
| Conferencias y eventos (Black Hat, RSA) | €12,000 |

**Subtotal Indirectos**: **€48,333/año**

---

## 📈 PARTE 3: COSTES OPERACIONALES ANUALES

### Operación y Mantenimiento (Año 1+)

| Concepto | Opción A (On-Prem) | Opción B (AWS) | Opción C (Híbrido) |
|----------|-------------------|----------------|-------------------|
| **Infraestructura** | €2,400 | €69,060 | €37,200 |
| **Licencias software** | €23,600 | €0 (incluido) | €11,800 |
| **Electricidad** (30 kW, 24x7) | €18,000 | €0 | €9,000 |
| **Refrigeración** | €6,000 | €0 | €3,000 |
| **Mantenimiento hardware** | €7,100 (10% hardware) | €0 | €4,800 |
| **Soporte técnico externo** | €12,000 | €8,000 | €10,000 |
| **Auditorías de seguridad** (trimestral) | €16,000 | €16,000 | €16,000 |
| **Penetration Testing** (anual) | €8,000 | €8,000 | €8,000 |
| **Threat Intelligence feeds** | €12,000 | €12,000 | €12,000 |
| **Backup offsite** | Incluido arriba | Incluido | Incluido |
| **Seguro ciberseguridad** | €15,000 | €15,000 | €15,000 |
| **Renovación certificados/compliance** | €5,000 | €5,000 | €5,000 |

**Subtotal OPEX Infraestructura**:

- **Opción A**: €125,100/año
- **Opción B**: €133,060/año
- **Opción C**: €131,800/año

---

## 💼 PARTE 4: RESUMEN DE COSTES TOTALES

### Costes de Implementación (Año 0)

| Concepto | Opción A (On-Prem) | Opción B (AWS) | Opción C (Híbrido) |
|----------|-------------------|----------------|-------------------|
| **CAPEX Hardware** | €79,500 | €0 | €48,000 |
| **Licencias (3 años)** | €70,800 | €0 | €35,400 |
| **Instalación/Setup** | €20,500 | €5,000 | €12,500 |
| **Recursos Humanos (6 meses)** | €360,425 | €360,425 | €360,425 |
| **TOTAL AÑO 0** | **€531,225** | **€365,425** | **€456,325** |

### Costes Operacionales (Año 1 en adelante)

| Concepto | Opción A (On-Prem) | Opción B (AWS) | Opción C (Híbrido) |
|----------|-------------------|----------------|-------------------|
| **OPEX Infraestructura** | €125,100 | €133,060 | €131,800 |
| **Personal (8 FTE)** | €681,850 | €681,850 | €681,850 |
| **Indirectos Personal** | €48,333 | €48,333 | €48,333 |
| **TOTAL ANUAL** | **€855,283** | **€863,243** | **€861,983** |

### Coste Total 3 Años (TCO)

| Concepto | Opción A (On-Prem) | Opción B (AWS) | Opción C (Híbrido) |
|----------|-------------------|----------------|-------------------|
| Año 0 (Implementación) | €531,225 | €365,425 | €456,325 |
| Año 1 (Operación) | €855,283 | €863,243 | €861,983 |
| Año 2 (Operación) | €855,283 | €863,243 | €861,983 |
| Año 3 (Operación) | €855,283 | €863,243 | €861,983 |
| **TCO 3 AÑOS** | **€3,097,074** | **€2,955,154** | **€3,042,274** |

**Conclusión TCO**: Cloud (Opción B) es **€141,920 más económico** en 3 años.

---

## 🎯 PARTE 5: OFERTA COMERCIAL PARA CLIENTE

### Modelo de Negocio: Proyecto Llave en Mano + Soporte Anual

#### Paquete 1: BÁSICO (Opción B - AWS Cloud)

**Incluye:**

- ✅ Plataforma e-commerce completa (API + Web + Mobile)
- ✅ SIEM centralizado (ELK Stack en AWS)
- ✅ Honeypot para threat intelligence
- ✅ Simulación OT/PLC básica
- ✅ Implementación en 6 meses
- ✅ Formación al equipo del cliente (40 horas)
- ✅ Documentación completa
- ✅ 3 meses de soporte post-lanzamiento

**Precio**: **€425,000** (una vez)

**Soporte Anual (opcional)**: **€120,000/año**

- Mantenimiento 8x5
- Actualizaciones de seguridad
- Monitoreo SIEM (horario laboral)
- 2 auditorías anuales

---

#### Paquete 2: PROFESIONAL (Opción C - Híbrido)

**Incluye todo lo del Básico +**

- ✅ Servidores on-premise para producción (alta disponibilidad)
- ✅ SIEM avanzado con respuesta automatizada
- ✅ Honeypot multi-capa (T-Pot completo)
- ✅ Simulación OT/PLC avanzada (OpenPLC + ScadaBR)
- ✅ Integración IT/OT con Purdue Model
- ✅ Formación avanzada (80 horas)
- ✅ 6 meses de soporte post-lanzamiento

**Precio**: **€575,000** (una vez)

**Soporte Anual (obligatorio)**: **€180,000/año**

- Mantenimiento 24x7
- SOC gestionado (horario extendido)
- Incident response (4h SLA)
- 4 auditorías anuales + 1 pentest

---

#### Paquete 3: ENTERPRISE (Opción A - On-Premise Total)

**Incluye todo lo del Profesional +**

- ✅ Infraestructura on-premise completa (cliente posee hardware)
- ✅ Alta disponibilidad en todos los componentes
- ✅ SOC 24x7 gestionado por Zabala Gailetak
- ✅ Respuesta a incidentes garantizada (2h SLA)
- ✅ Simulación completa de planta industrial
- ✅ Integración con sistemas legacy del cliente
- ✅ Disaster Recovery site secundario
- ✅ Formación intensiva (120 horas)
- ✅ 12 meses de soporte incluido

**Precio**: **€850,000** (una vez)

**Soporte Anual (incluido año 1, renovable)**: **€240,000/año**

- SOC 24x7x365 con equipo dedicado
- Threat hunting proactivo
- Incident response ilimitado (1h SLA crítico)
- 6 auditorías anuales + 2 pentests
- Red team exercises
- Actualizaciones hardware cada 3 años incluidas

---

### Desglose de Márgenes (Ejemplo Paquete 2 - Profesional)

| Concepto | Coste Real | Precio Venta | Margen |
|----------|------------|--------------|--------|
| **Implementación** | €456,325 | €575,000 | **€118,675 (26%)** |
| **Soporte Año 1** | €861,983 | €180,000 | **-€681,983** ⚠️ |

**Nota sobre el margen de soporte**: El margen negativo del primer año se explica porque:

1. El cliente **no paga el equipo completo** de 8 FTE; nosotros amortizamos el equipo entre **múltiples clientes**
2. Con **5 clientes simultáneos**, el coste de personal se reparte:
   - Coste real por cliente: €861,983 / 5 = **€172,397/año**
   - Precio venta: **€180,000/año**
   - **Margen real: €7,603/cliente (4%)**

3. La rentabilidad real viene de tener **cartera de clientes recurrentes**

---

### Modelo de Precios por Módulos (À la Carte)

Si el cliente quiere seleccionar componentes:

| Módulo | Precio |
|--------|--------|
| **Core E-commerce** (API + DB + Web) | €180,000 |
| **SIEM básico** (30 días logs) | €80,000 |
| **SIEM avanzado** (90 días logs, alertas avanzadas) | €150,000 |
| **Honeypot single** (Cowrie SSH) | €25,000 |
| **Honeypot multi-layer** (T-Pot completo) | €65,000 |
| **Simulación OT/PLC básica** | €45,000 |
| **Simulación OT/PLC avanzada + IT/OT integration** | €95,000 |
| **Mobile App (iOS + Android)** | €60,000 |
| **Disaster Recovery setup** | €40,000 |
| **Formación (por día)** | €2,500/día |

---

## 📊 PARTE 6: ANÁLISIS DE RENTABILIDAD (Para Zabala Gailetak)

### Escenario: 5 Clientes Paquete Profesional (3 años)

| Concepto | Año 0 | Año 1 | Año 2 | Año 3 | Total 3 años |
|----------|-------|-------|-------|-------|--------------|
| **Ingresos (5 clientes)** | €2,875,000 | €900,000 | €900,000 | €900,000 | €5,575,000 |
| **Costes Personal (compartido)** | €360,425 | €681,850 | €681,850 | €681,850 | €2,405,975 |
| **Costes Infraestructura** | €456,325 x5 | €131,800 x5 | €131,800 x5 | €131,800 x5 | €3,260,625 |
| **TOTAL COSTES** | €2,641,550 | €1,340,850 | €1,340,850 | €1,340,850 | €6,664,100 |
| **Beneficio Bruto** | €233,450 | -€440,850 | -€440,850 | -€440,850 | **-€1,089,100** ⚠️ |

**Problema**: Modelo no rentable si infraestructura es **dedicada por cliente**.

---

### Escenario Corregido: Infraestructura Multi-Tenant

**Hipótesis realista**:

- 1 infraestructura cloud AWS **compartida** para 5 clientes (aislamiento por VPC/tenant)
- Escalado según uso
- Costes infraestructura **únicos**, no x5

| Concepto | Año 0 | Año 1 | Año 2 | Año 3 | Total 3 años |
|----------|-------|-------|-------|-------|--------------|
| **Ingresos (5 clientes)** | €2,875,000 | €900,000 | €900,000 | €900,000 | €5,575,000 |
| **Costes Personal** | €360,425 | €681,850 | €681,850 | €681,850 | €2,405,975 |
| **Costes Infraestructura** (compartida x1.5 capacidad) | €68,487 | €197,700 | €197,700 | €197,700 | €661,587 |
| **Overhead (oficinas, legal, ventas)** | €100,000 | €150,000 | €150,000 | €150,000 | €550,000 |
| **TOTAL COSTES** | €528,912 | €1,029,550 | €1,029,550 | €1,029,550 | €3,617,562 |
| **Beneficio Bruto** | €2,346,088 | -€129,550 | -€129,550 | -€129,550 | **€1,957,438** ✅ |
| **Margen Bruto** | 81.6% | -14.4% | -14.4% | -14.4% | **35.1%** |

**Conclusión**: Rentable con modelo **SaaS/Multi-Tenant** y volumen de clientes.

---

### Break-Even Analysis

Con modelo multi-tenant:

| Nº Clientes | Ingresos Anuales (soporte) | Costes Fijos | Beneficio/Pérdida |
|-------------|----------------------------|--------------|-------------------|
| 1 | €180,000 | €1,029,550 | **-€849,550** |
| 2 | €360,000 | €1,029,550 | **-€669,550** |
| 3 | €540,000 | €1,029,550 | **-€489,550** |
| 4 | €720,000 | €1,029,550 | **-€309,550** |
| 5 | €900,000 | €1,029,550 | **-€129,550** |
| 6 | €1,080,000 | €1,029,550 | **+€50,450** ✅ |
| 10 | €1,800,000 | €1,150,000 | **+€650,000** ✅ |

**Break-Even**: **6 clientes activos** en soporte anual.

---

## 🎁 PARTE 7: OFERTA PROMOCIONAL DE LANZAMIENTO

### Campaña: "Early Adopter Program"

**Condiciones especiales para los primeros 3 clientes:**

| Beneficio | Valor Normal | Valor Promocional | Ahorro |
|-----------|--------------|-------------------|--------|
| Paquete Profesional | €575,000 | €475,000 | **€100,000** |
| Soporte Año 1 | €180,000/año | €150,000/año | **€30,000** |
| Formación adicional | €10,000 (4 días) | **GRATIS** | **€10,000** |
| Auditoría trimestral extra | €4,000 | **GRATIS** | **€4,000** |
| **AHORRO TOTAL PRIMER AÑO** | | | **€144,000** |

**Precio Promocional Total (Año 0 + Año 1)**: **€625,000** (vs €755,000 normal)

**Condiciones**:

- Válido hasta 31/03/2026
- Compromiso mínimo de 3 años de soporte
- Cliente actúa como caso de éxito (testimonial + logo)
- Participación en webinar público (opcional)

---

## 📝 PARTE 8: TÉRMINOS Y CONDICIONES CONTRACTUALES

### Estructura de Pagos (Paquete Profesional €575,000)

| Hito | % | Importe | Entregables |
|------|---|---------|-------------|
| **Firma de contrato** | 20% | €115,000 | Inicio de proyecto, kick-off |
| **Diseño aprobado** | 15% | €86,250 | Arquitectura, diseño UX/UI |
| **Desarrollo 50%** | 20% | €115,000 | Backend + Frontend funcional |
| **Pre-producción (UAT)** | 20% | €115,000 | Testing completo, staging |
| **Go-Live** | 15% | €86,250 | Producción activa, handover |
| **Fin garantía (3 meses)** | 10% | €57,500 | Cierre proyecto, documentación final |

### SLA (Service Level Agreement) - Soporte Profesional

| Prioridad | Descripción | Tiempo de Respuesta | Tiempo de Resolución |
|-----------|-------------|---------------------|----------------------|
| **P1 - Crítico** | Sistema caído, pérdida de datos, brecha de seguridad | 1 hora | 4 horas |
| **P2 - Alto** | Funcionalidad mayor no disponible | 4 horas | 24 horas |
| **P3 - Medio** | Funcionalidad menor afectada | 8 horas | 3 días |
| **P4 - Bajo** | Consultas, mejoras | 24 horas | 10 días |

**Penalizaciones por incumplimiento SLA**:

- P1: Crédito del 5% cuota mensual por cada hora de retraso
- P2: Crédito del 2% cuota mensual por cada 4 horas de retraso
- Máximo penalización mensual: 25% de la cuota

### Garantías

- **Funcionalidad**: 12 meses desde go-live
- **Seguridad**: 6 meses sin vulnerabilidades críticas (CVSS >7.0)
- **Disponibilidad**: 99.5% uptime mensual (excluido mantenimiento programado)
- **Backup/Restore**: RTO 8 horas, RPO 24 horas

---

## 💡 PARTE 9: RECOMENDACIONES PARA EL CLIENTE

### Opción Recomendada según Perfil

#### Cliente Pequeño (50-200 empleados, <€10M facturación)

- **Recomendación**: Paquete BÁSICO (AWS Cloud)
- **Justificación**:
  - Bajo CAPEX inicial
  - Escalabilidad elástica
  - Sin necesidad de equipo IT interno grande
- **Inversión Año 1**: €425,000 + €120,000 = **€545,000**

#### Cliente Mediano (200-1000 empleados, €10-50M facturación)

- **Recomendación**: Paquete PROFESIONAL (Híbrido)
- **Justificación**:
  - Balance coste/control
  - Datos sensibles on-premise
  - SIEM y backup en cloud
  - Compliance (GDPR, PCI-DSS)
- **Inversión Año 1**: €575,000 + €180,000 = **€755,000**

#### Cliente Enterprise (>1000 empleados, >€50M facturación)

- **Recomendación**: Paquete ENTERPRISE (On-Premise Total)
- **Justificación**:
  - Control total de datos
  - Integración con infraestructura existente
  - Regulación industrial (OT/ICS)
  - Soberanía de datos
- **Inversión Año 1**: €850,000 + €240,000 = **€1,090,000**

---

## 📞 PARTE 10: INFORMACIÓN DE CONTACTO

### Zabala Gailetak Security Solutions

**Dirección Comercial**:  
Polígono Industrial Garaia, Nave 12  
20140 Andoain, Gipuzkoa  
País Vasco, España

**Contactos**:

- **Ventas**: <ventas@zabalagailetak.eus> | +34 943 XXX XXX
- **Soporte**: <soporte@zabalagailetak.eus> | +34 943 XXX XXX
- **Emergencias 24/7**: +34 600 XXX XXX

**Web**: <https://www.zabalagailetak.eus>

**Certificaciones**:

- ISO 27001 (Gestión de Seguridad de la Información)
- ISO 22301 (Continuidad de Negocio)
- ENS Alto (Esquema Nacional de Seguridad)
- IEC 62443 (Seguridad OT/ICS)

**Partners**:

- AWS Advanced Consulting Partner
- MongoDB Enterprise Partner
- Elastic Gold Partner
- Fortinet Expert Partner

---

## 📄 ANEXOS

### Anexo A: Comparativa de Competidores

| Proveedor | Solución Similar | Precio Estimado | Diferenciadores Zabala Gailetak |
|-----------|------------------|-----------------|--------------------------------|
| Accenture Security | Custom SIEM + ICS | €1.2M - €2M | **50% más económico**, especialización OT |
| Indra Minsait | Plataforma seguridad industrial | €900K - €1.5M | **Mayor flexibilidad**, cloud-ready |
| S21sec | SOC gestionado + plataforma | €500K - €800K | **Incluye honeypots**, simulación PLC real |
| Atos Cybersecurity | Suite seguridad empresarial | €1M - €1.8M | **Implementación más rápida** (6 vs 12 meses) |

### Anexo B: ROI para el Cliente (Caso de Éxito)

**Cliente ejemplo**: Empresa industrial 500 empleados, facturación €30M/año

**Antes de Zabala Gailetak**:

- 3 brechas de seguridad en 2 años (coste medio: €500K cada una)
- Downtime no planificado: 120 horas/año (€5K/hora pérdidas)
- **Coste total incidentes**: €2.1M en 2 años

**Después de Zabala Gailetak** (Año 1-2):

- 0 brechas de seguridad exitosas (35 intentos bloqueados)
- Downtime reducido a 12 horas/año
- **Ahorro**: €1.95M en 2 años

**ROI**:

- Inversión: €755K (año 1) + €180K (año 2) = €935K
- Ahorro: €1.95M
- **ROI neto: +€1.015M (108%)**

### Anexo C: Hoja de Ruta de Implementación (6 meses)

```text
Mes 1-2: Diseño y Preparación
├─ Semana 1-2: Kick-off, requisitos, arquitectura
├─ Semana 3-4: Diseño UX/UI, aprobación
├─ Semana 5-6: Setup infraestructura (AWS/On-prem)
└─ Semana 7-8: Configuración red, VLANs, firewall

Mes 3-4: Desarrollo e Integración
├─ Semana 9-12: Desarrollo Backend (API)
├─ Semana 13-14: Desarrollo Frontend (Web)
├─ Semana 15-16: Integración MongoDB, Redis
└─ Semana 17: Sprint review, ajustes

Mes 5: SIEM y Seguridad
├─ Semana 18-19: Despliegue ELK Stack
├─ Semana 20: Configuración alertas, dashboards
└─ Semana 21: Despliegue honeypots, OT/PLC

Mes 6: Testing y Go-Live
├─ Semana 22-23: QA completo, pentest
├─ Semana 24: UAT con cliente
├─ Semana 25: Migración datos, go-live
└─ Semana 26: Monitoreo post-lanzamiento
```

---

## ✅ RESUMEN FINAL

### Para el Cliente

| Paquete | Inversión Total 3 años | Beneficios Clave |
|---------|------------------------|------------------|
| **Básico** | €785,000 | E-commerce seguro + SIEM básico |
| **Profesional** | €1,115,000 | + OT/PLC + Honeypots + HA |
| **Enterprise** | €1,570,000 | + SOC 24x7 + Control total |

### Para Zabala Gailetak (Objetivo: 10 clientes activos)

| Métrica | Valor |
|---------|-------|
| **Ingresos Anuales** (10 clientes soporte) | €1,800,000 |
| **Costes Operacionales** | €1,150,000 |
| **Beneficio Bruto** | **€650,000/año (36% margen)** |
| **Break-Even** | 6 clientes |

---

**Documento preparado por**: Zabala Gailetak Security Solutions  
**Válido hasta**: 31/03/2026  
**Versión**: 1.0 - 12 Enero 2026

---

*Este documento contiene información confidencial. Prohibida su reproducción sin autorización.*
