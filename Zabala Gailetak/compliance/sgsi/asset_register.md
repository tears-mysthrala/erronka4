# Asset Register
## Zabala Gailetak S.A. - Information Security Management System

**Document ID:** ASR-001  
**Version:** 1.0  
**Date:** January 8, 2026  
**Classification:** Highly Confidential  
**Owner:** Chief Information Security Officer (CISO)  
**Review Frequency:** Quarterly  
**Next Review Date:** April 8, 2026

---

## 1. Document Control

### 1.1 Version History

| Version | Date | Author | Changes |
|---------|------|--------|---------|
| 1.0 | 2026-01-08 | CISO | Initial asset register creation |

### 1.2 Purpose

This Asset Register is a comprehensive inventory of information assets owned or controlled by Zabala Gailetak. It supports:
- ISO/IEC 27001:2022 compliance (Annex A control 5.9)
- Risk assessment and management
- Business continuity planning
- Incident response
- Asset lifecycle management
- Insurance and financial reporting

### 1.3 Scope

This register includes:
- **IT Assets:** Servers, network equipment, workstations, mobile devices, software
- **OT Assets:** PLCs, SCADA systems, industrial equipment with embedded systems
- **Data Assets:** Databases, files, backups, intellectual property
- **Physical Assets:** Facilities, equipment (where relevant to information security)
- **Human Assets:** Key personnel and roles critical to information security
- **Services:** Third-party services and cloud platforms

### 1.4 Asset Classification

Assets are classified by:
- **Confidentiality:** Public, Internal, Confidential, Highly Confidential
- **Integrity:** Low, Medium, High, Critical
- **Availability:** Low (72h downtime acceptable), Medium (24h), High (8h), Critical (4h)

---

## 2. IT Assets

### 2.1 Servers and Infrastructure

| Asset ID | Asset Name | Type | Location | Owner | Custodian | Classification (C/I/A) | Purpose | Value (€) | Last Updated |
|----------|------------|------|----------|-------|-----------|------------------------|---------|-----------|--------------|
| SRV-001 | Web Application Server | Virtual (AWS EC2) | eu-west-1a | IT Manager | IT Team | HC/H/C | Order management web app | 15,000 | 2026-01-08 |
| SRV-002 | Database Server (Primary) | Virtual (AWS RDS) | eu-west-1a | IT Manager | IT Team | HC/C/C | Customer/order database (MongoDB) | 25,000 | 2026-01-08 |
| SRV-003 | Database Server (Replica) | Virtual (AWS RDS) | eu-west-1b | IT Manager | IT Team | HC/C/H | Database replication | 25,000 | 2026-01-08 |
| SRV-004 | File Server | Physical | Data Center | IT Manager | IT Team | C/H/H | Document storage, shared drives | 8,000 | 2026-01-08 |
| SRV-005 | SCADA Server | Physical | Data Center | OT Manager | OT Team | C/C/C | Production monitoring | 20,000 | 2026-01-08 |
| SRV-006 | Backup Server | Physical | Data Center | IT Manager | IT Team | HC/C/H | Backup storage | 12,000 | 2026-01-08 |
| SRV-007 | Domain Controller | Virtual (on-prem) | Data Center | IT Manager | IT Team | HC/C/C | Active Directory, authentication | 10,000 | 2026-01-08 |
| SRV-008 | Email Server (M365) | Cloud (Microsoft) | Global | IT Manager | Microsoft | C/M/H | Email and collaboration | 5,000/year | 2026-01-08 |
| SRV-009 | Development Server | Virtual (on-prem) | Data Center | IT Manager | Dev Team | I/M/L | Testing and development | 5,000 | 2026-01-08 |
| SRV-010 | ELK Stack (SIEM) | Virtual (AWS) | eu-west-1a | CISO | Security Team | HC/H/H | Security monitoring and logging | 18,000 | 2026-01-08 |

**Classification Legend:**
- **C (Confidentiality):** P=Public, I=Internal, C=Confidential, HC=Highly Confidential
- **I (Integrity):** L=Low, M=Medium, H=High, C=Critical
- **A (Availability):** L=Low (72h), M=Medium (24h), H=High (8h), C=Critical (4h)

**Total IT Server Value:** €143,000

### 2.2 Network Equipment

| Asset ID | Asset Name | Type | Location | IP Address | Owner | Classification (C/I/A) | Purpose | Value (€) | Last Updated |
|----------|------------|------|----------|------------|-------|------------------------|---------|-----------|--------------|
| NET-001 | Core Switch | Cisco Catalyst 9300 | Data Center | 10.0.0.1 | IT Manager | C/C/C | Core network routing | 15,000 | 2026-01-08 |
| NET-002 | Firewall (Primary) | Fortinet FortiGate 200F | Data Center | 10.0.0.2 | IT Manager | C/C/C | Perimeter security | 12,000 | 2026-01-08 |
| NET-003 | Firewall (Backup) | Fortinet FortiGate 200F | Data Center | 10.0.0.3 | IT Manager | C/C/H | Failover firewall | 12,000 | 2026-01-08 |
| NET-004 | Office Switch | Cisco SG350 | Office Building | 10.1.0.1 | IT Manager | I/M/M | Office network | 2,500 | 2026-01-08 |
| NET-005 | Production Switch | Cisco IE-3400 | Production Floor | 10.2.0.1 | OT Manager | C/C/C | OT network (industrial grade) | 8,000 | 2026-01-08 |
| NET-006 | WiFi Controller | Ubiquiti UniFi Dream Machine Pro | Office | 10.1.0.10 | IT Manager | C/M/M | WiFi management | 1,500 | 2026-01-08 |
| NET-007 | WiFi AP-1 | Ubiquiti UAP-AC-Pro | Office Floor 1 | 10.1.1.101 | IT Manager | I/M/M | Office WiFi coverage | 300 | 2026-01-08 |
| NET-008 | WiFi AP-2 | Ubiquiti UAP-AC-Pro | Office Floor 2 | 10.1.1.102 | IT Manager | I/M/M | Office WiFi coverage | 300 | 2026-01-08 |
| NET-009 | WiFi AP-3 (Guest) | Ubiquiti UAP-AC-Pro | Reception | 10.1.2.101 | IT Manager | P/L/M | Guest WiFi (isolated) | 300 | 2026-01-08 |
| NET-010 | OT Firewall | Fortinet FortiGate 100F | Production | 10.2.0.2 | OT Manager | C/C/C | IT/OT segmentation | 8,000 | 2026-01-08 |
| NET-011 | IDS/IPS | Suricata (software) | Virtual | 10.0.0.10 | CISO | C/H/H | Intrusion detection | 0 (OSS) | 2026-01-08 |
| NET-012 | VPN Gateway | OpenVPN | Virtual | VPN endpoint | IT Manager | HC/H/H | Remote access | 0 (OSS) | 2026-01-08 |

**Total Network Equipment Value:** €59,900

### 2.3 Workstations and Laptops

| Asset ID | Asset Type | Quantity | Location | Owner | Classification (C/I/A) | OS | Value (€) | Notes |
|----------|------------|----------|----------|-------|------------------------|----|-----------|-|
| WRK-001-050 | Desktop - Office | 50 | Office | IT Manager | C/M/M | Windows 11 Pro | 50,000 | Standard employee workstations |
| WRK-051-065 | Laptop - Management | 15 | Mobile | IT Manager | HC/H/H | Windows 11 Pro | 22,500 | Executive and manager laptops |
| WRK-066-070 | Laptop - Developer | 5 | Office/Mobile | IT Manager | C/H/M | Windows 11 Pro / macOS | 10,000 | Development team |
| WRK-071-080 | Thin Client - Production | 10 | Production Floor | OT Manager | C/M/H | Linux (embedded) | 8,000 | SCADA HMI terminals |
| WRK-081-090 | Industrial Tablet | 10 | Production Floor | OT Manager | C/C/H | Windows 10 IoT | 15,000 | Mobile production monitoring |

**Total Workstation Value:** €105,500

### 2.4 Mobile Devices

| Asset ID | Asset Type | Quantity | Owner | Classification (C/I/A) | OS | MDM Enrolled | Value (€) | Notes |
|----------|------------|----------|-------|------------------------|----|--------------|-----------|-------|
| MOB-001-030 | Smartphone (Company) | 30 | IT Manager | C/M/M | iOS / Android | Yes | 24,000 | Company-owned, assigned to employees |
| MOB-031-050 | Smartphone (BYOD) | 20 | Employees | C/L/L | iOS / Android | Partial | 0 | Employee-owned with company email access |
| MOB-051-055 | Tablet (iPad) | 5 | IT Manager | C/M/M | iOS | Yes | 4,000 | Sales and executive use |

**Total Mobile Device Value:** €28,000

### 2.5 Software and Licenses

| Asset ID | Software Name | Type | Vendor | License Type | Quantity | Owner | Classification (C/I/A) | Value (€/year) | Renewal Date | Notes |
|----------|---------------|------|--------|--------------|----------|-------|------------------------|----------------|--------------|-------|
| SW-001 | Microsoft 365 E3 | Productivity | Microsoft | Subscription | 80 users | IT Manager | C/M/H | 12,800 | Monthly | Email, Office, OneDrive |
| SW-002 | Windows Server 2022 | OS | Microsoft | Perpetual | 5 licenses | IT Manager | C/H/C | 5,000 | N/A | Server operating system |
| SW-003 | MongoDB Enterprise | Database | MongoDB Inc. | Subscription | 2 servers | IT Manager | HC/C/C | 15,000 | 2026-06-01 | Database platform |
| SW-004 | Fortinet FortiCare | Security Support | Fortinet | Subscription | 3 devices | IT Manager | C/C/C | 4,500 | 2026-12-31 | Firewall support and updates |
| SW-005 | Veeam Backup | Backup | Veeam | Perpetual | 10 VMs | IT Manager | HC/C/H | 3,000 | 2026-09-15 | Backup software |
| SW-006 | Node.js | Runtime | OpenJS Foundation | Open Source | Unlimited | IT Manager | C/H/C | 0 | N/A | Web app backend |
| SW-007 | React | Framework | Meta | Open Source | Unlimited | IT Manager | I/M/M | 0 | N/A | Web app frontend |
| SW-008 | ELK Stack (Elastic, Logstash, Kibana) | SIEM | Elastic | Open Source | Self-hosted | CISO | HC/H/H | 0 | N/A | Security monitoring |
| SW-009 | Siemens TIA Portal | PLC Programming | Siemens | Perpetual | 2 seats | OT Manager | C/C/H | 8,000 | N/A | PLC development |
| SW-010 | WinCC SCADA | HMI/SCADA | Siemens | Perpetual | 1 server + 10 clients | OT Manager | C/C/C | 25,000 | 2026-08-01 | Production monitoring |
| SW-011 | Symantec Endpoint Protection | Antivirus | Broadcom | Subscription | 100 endpoints | IT Manager | C/H/H | 3,500 | 2026-10-15 | Endpoint security |
| SW-012 | Slack | Communication | Slack | Subscription | 80 users | IT Manager | C/M/M | 4,800 | Monthly | Team collaboration (optional) |
| SW-013 | GitHub Enterprise | Version Control | GitHub | Subscription | 10 users | IT Manager | C/H/M | 2,500 | 2026-11-20 | Code repository |
| SW-014 | SonarQube | Code Quality | SonarSource | Open Source | Self-hosted | IT Manager | I/M/M | 0 | N/A | Static code analysis |

**Total Software Annual Cost:** €84,100  
**Total Software Perpetual Value:** €38,000

### 2.6 Cloud Services

| Asset ID | Service Name | Provider | Service Type | Owner | Classification (C/I/A) | Monthly Cost (€) | Annual Cost (€) | Data Location | Notes |
|----------|--------------|----------|--------------|-------|------------------------|------------------|-----------------|---------------|-------|
| CLD-001 | EC2 Instances | AWS | IaaS | IT Manager | HC/H/C | 800 | 9,600 | eu-west-1 | Web application servers |
| CLD-002 | RDS (MongoDB) | AWS | DBaaS | IT Manager | HC/C/C | 1,200 | 14,400 | eu-west-1 | Managed database |
| CLD-003 | S3 Storage | AWS | Object Storage | IT Manager | HC/C/H | 150 | 1,800 | eu-west-1 | Backups and static files |
| CLD-004 | CloudFront CDN | AWS | CDN | IT Manager | I/M/H | 100 | 1,200 | Global | Content delivery |
| CLD-005 | Route 53 DNS | AWS | DNS | IT Manager | C/C/C | 25 | 300 | Global | Domain name service |
| CLD-006 | CloudWatch | AWS | Monitoring | IT Manager | C/M/H | 80 | 960 | eu-west-1 | Infrastructure monitoring |
| CLD-007 | Microsoft 365 | Microsoft | SaaS | IT Manager | C/M/H | 1,067 | 12,800 | EU | Email and productivity |
| CLD-008 | GitHub | GitHub | SaaS | IT Manager | C/H/M | 208 | 2,500 | Global | Code repository |

**Total Cloud Services Annual Cost:** €43,560

---

## 3. Operational Technology (OT) Assets

### 3.1 Programmable Logic Controllers (PLCs)

| Asset ID | Asset Name | Manufacturer | Model | Location | Purpose | Owner | Classification (C/I/A) | Installation Date | Last Maintenance | Value (€) |
|----------|------------|--------------|-------|----------|---------|-------|------------------------|-------------------|------------------|-----------|
| PLC-001 | Mixing Line PLC | Siemens | S7-1500 | Production Line 1 | Ingredient mixing control | OT Manager | C/C/C | 2023-03-15 | 2025-11-20 | 12,000 |
| PLC-002 | Baking Oven PLC | Siemens | S7-1200 | Production Line 1 | Oven temperature/timing | OT Manager | C/C/C | 2022-08-10 | 2025-10-05 | 8,000 |
| PLC-003 | Packaging Line PLC-A | Siemens | S7-1500 | Packaging Area | Packaging automation | OT Manager | C/C/C | 2024-01-20 | 2025-12-10 | 12,000 |
| PLC-004 | Packaging Line PLC-B | Siemens | S7-1500 | Packaging Area | Packaging automation | OT Manager | C/C/C | 2024-01-20 | 2025-12-10 | 12,000 |
| PLC-005 | Conveyor System PLC | Siemens | S7-1200 | Production Line 1 | Material transport | OT Manager | C/H/C | 2022-06-01 | 2025-09-15 | 8,000 |
| PLC-006 | Utility Management PLC | Siemens | S7-1200 | Utility Room | HVAC, compressed air | OT Manager | I/M/H | 2021-11-10 | 2025-08-22 | 8,000 |

**Total PLC Value:** €60,000

**Spare Parts Inventory:**
- Siemens S7-1500 CPU: 1 unit (€3,000)
- Siemens S7-1200 CPU: 1 unit (€1,500)
- I/O modules (various): €5,000
- **Total Spare Parts Value:** €9,500

### 3.2 SCADA and HMI Systems

| Asset ID | Asset Name | Type | Manufacturer | Location | Purpose | Owner | Classification (C/I/A) | Value (€) |
|----------|------------|------|--------------|----------|---------|-------|------------------------|-----------|
| SCADA-001 | Main SCADA Server | Server | Siemens WinCC | Data Center | Central monitoring | OT Manager | C/C/C | 25,000 |
| HMI-001 | Production Floor HMI-1 | Panel PC | Siemens | Line 1 Control Room | Local operator interface | OT Manager | C/C/H | 4,000 |
| HMI-002 | Production Floor HMI-2 | Panel PC | Siemens | Packaging Control | Local operator interface | OT Manager | C/C/H | 4,000 |
| HMI-003 | Manager Monitoring Station | Workstation | Siemens | Production Office | Supervisor monitoring | OT Manager | C/M/M | 2,000 |

**Total SCADA/HMI Value:** €35,000

### 3.3 Sensors and Instrumentation

| Asset Type | Quantity | Purpose | Location | Classification (C/I/A) | Unit Value (€) | Total Value (€) |
|------------|----------|---------|----------|------------------------|----------------|-----------------|
| Temperature Sensors (PT100) | 25 | Oven temperature monitoring | Production | C/C/C | 200 | 5,000 |
| Humidity Sensors | 10 | Environmental monitoring | Production | C/M/H | 300 | 3,000 |
| Pressure Sensors | 8 | Compressed air, mixing | Production | C/H/M | 400 | 3,200 |
| Load Cells (Scales) | 12 | Ingredient weighing | Mixing Area | C/C/M | 800 | 9,600 |
| Proximity Sensors | 40 | Conveyor positioning | Throughout | I/M/M | 100 | 4,000 |
| Safety Light Curtains | 6 | Personnel safety | Production | C/C/C | 1,500 | 9,000 |

**Total Sensor Value:** €33,800

### 3.4 Production Equipment (with embedded systems)

| Asset ID | Equipment Name | Type | Location | Embedded System | Owner | Classification (C/I/A) | Value (€) | Installation Date |
|----------|----------------|------|----------|-----------------|-------|------------------------|-----------|-------------------|
| PROD-001 | Industrial Mixer A | Mixing Equipment | Line 1 | PLC-controlled | OT Manager | C/C/C | 45,000 | 2023-03 |
| PROD-002 | Industrial Mixer B | Mixing Equipment | Line 1 | PLC-controlled | OT Manager | C/C/C | 45,000 | 2023-03 |
| PROD-003 | Tunnel Oven | Baking Oven | Line 1 | PLC + proprietary controller | OT Manager | C/C/C | 120,000 | 2022-08 |
| PROD-004 | Cooling Conveyor | Cooling System | Line 1 | PLC-controlled | OT Manager | C/H/H | 25,000 | 2022-06 |
| PROD-005 | Packaging Machine A | Packaging | Packaging Area | PLC + HMI | OT Manager | C/C/C | 80,000 | 2024-01 |
| PROD-006 | Packaging Machine B | Packaging | Packaging Area | PLC + HMI | OT Manager | C/C/C | 80,000 | 2024-01 |
| PROD-007 | Palletizer | Material Handling | Warehouse | PLC-controlled | OT Manager | C/M/M | 35,000 | 2021-05 |

**Total Production Equipment Value:** €430,000

---

## 4. Data Assets

### 4.1 Databases

| Asset ID | Database Name | Type | Contents | Owner | Custodian | Classification (C/I/A) | Records | Size (GB) | Backup Frequency | Location |
|----------|---------------|------|----------|-------|-----------|------------------------|---------|-----------|------------------|----------|
| DB-001 | Production Database | MongoDB | Customers, orders, products, users | IT Manager | IT Team | HC/C/C | 500,000 | 50 | Hourly | AWS RDS eu-west-1 |
| DB-002 | SCADA Historian | Time-series DB | Production metrics, sensor data | OT Manager | OT Team | C/C/H | 10M+ | 200 | Daily | On-premises |
| DB-003 | Audit Log Database | Elasticsearch | Security events, access logs | CISO | Security Team | HC/H/H | 5M+ | 100 | Hourly | AWS eu-west-1 |
| DB-004 | Development Database | MongoDB | Test data (anonymized) | IT Manager | Dev Team | I/M/L | 10,000 | 2 | Weekly | On-premises |

**Total Data Volume:** 352 GB  
**Total Records:** 15.5M+

### 4.2 Critical Data Files and Repositories

| Asset ID | Asset Name | Type | Contents | Owner | Classification (C/I/A) | Location | Backup | Size |
|----------|------------|------|----------|-------|------------------------|----------|--------|------|
| DATA-001 | Customer Database | Database | Customer PII, contact info, order history | Sales Manager | HC/C/C | AWS RDS | Hourly | 20 GB |
| DATA-002 | Product Recipes | Documents | Cookie recipes, formulations (trade secrets) | Production Manager | HC/C/M | Encrypted file server | Daily | 500 MB |
| DATA-003 | Financial Records | Documents | Invoices, payroll, accounting | CFO | HC/H/M | Accounting software + file server | Daily | 10 GB |
| DATA-004 | PLC Programs | Code | Production automation programs | OT Manager | C/C/C | Version control + USB backup | Before changes | 200 MB |
| DATA-005 | Source Code Repository | Code | Web application source code | IT Manager | C/H/M | GitHub Enterprise | Continuous | 1 GB |
| DATA-006 | HR Records | Documents | Employee records, contracts, evaluations | HR Manager | HC/H/M | Encrypted file server | Daily | 5 GB |
| DATA-007 | Quality Control Data | Documents | Test results, certifications, compliance | Quality Manager | C/C/M | File server + SCADA | Daily | 15 GB |
| DATA-008 | Security Policies and Procedures | Documents | ISMS documentation, policies | CISO | C/H/M | Document management system | Daily | 100 MB |
| DATA-009 | Supplier Contracts | Documents | NDA, agreements, specifications | Procurement Manager | C/H/M | File server | Daily | 2 GB |
| DATA-010 | Customer Contracts | Documents | Sales agreements, SLAs | Sales Manager | C/H/M | CRM + file server | Daily | 3 GB |

**Total Critical Data:** ~56.8 GB

### 4.3 Backups

| Backup Set | Contents | Type | Frequency | Retention | Location | Encryption | Owner | Value |
|------------|----------|------|-----------|-----------|----------|------------|-------|-------|
| BACKUP-001 | Production Database | Full + Incremental | Hourly (incr), Daily (full) | 30 days online, 1 year archive | AWS S3 + On-premises | AES-256 | IT Manager | Critical |
| BACKUP-002 | File Server | Full + Incremental | Daily (incr), Weekly (full) | 30 days online, 1 year archive | AWS S3 + On-premises | AES-256 | IT Manager | Critical |
| BACKUP-003 | SCADA Historian | Full | Daily | 90 days online, 3 years archive | On-premises NAS | AES-256 | OT Manager | High |
| BACKUP-004 | PLC Configurations | Full | Before changes + Weekly | 10 versions + 5 years | USB drives (2 copies) + file server | AES-256 | OT Manager | Critical |
| BACKUP-005 | Email (M365) | Continuous | Continuous | Unlimited (cloud) | Microsoft Azure | Microsoft-managed | IT Manager | High |
| BACKUP-006 | Source Code | Continuous | Git push | Unlimited | GitHub + AWS | Git-managed | IT Manager | High |

**Backup Storage Capacity:**
- AWS S3: 500 GB allocated
- On-premises NAS: 2 TB capacity, 800 GB used
- USB Backup Drives: 10x 256 GB drives

---

## 5. Physical Assets

### 5.1 Facilities

| Asset ID | Facility Name | Type | Address | Size (m²) | Owner/Lease | Classification (C/I/A) | Value (€) | Security Features |
|----------|---------------|------|---------|-----------|-------------|------------------------|-----------|-------------------|
| FAC-001 | Main Production Facility | Manufacturing | [Address], [City] | 3,000 | Owned | C/C/C | 2,500,000 | Perimeter fence, cameras, access control, fire suppression |
| FAC-002 | Office Building | Office | [Address], [City] | 800 | Owned | C/M/M | 800,000 | Access control, cameras, alarm system |
| FAC-003 | Warehouse | Storage | [Address], [City] | 1,200 | Leased | C/M/M | N/A (lease) | Roller doors, cameras, security guard |
| FAC-004 | Data Center Room | IT Infrastructure | Within FAC-001 | 40 | Owned | HC/C/C | 150,000 | Card access, biometric, cameras, fire suppression (FM-200), climate control, UPS |

**Total Facility Value:** €3,450,000 (owned facilities)

### 5.2 Physical Security Systems

| Asset ID | Asset Name | Type | Location | Owner | Purpose | Value (€) | Installation Date |
|----------|------------|------|----------|-------|---------|-----------|-------------------|
| SEC-001 | Access Control System | Honeywell Pro-Watch | All Facilities | Facilities Manager | Entry control | 25,000 | 2023-01 |
| SEC-002 | CCTV System | Hikvision NVR + 32 cameras | All Facilities | Facilities Manager | Surveillance | 15,000 | 2022-06 |
| SEC-003 | Intrusion Alarm | Bosch | All Facilities | Facilities Manager | Intrusion detection | 8,000 | 2021-09 |
| SEC-004 | Fire Alarm System | Siemens | All Facilities | Facilities Manager | Fire detection | 20,000 | 2020-11 |
| SEC-005 | FM-200 Fire Suppression | Fike | Data Center | Facilities Manager | Fire suppression | 35,000 | 2023-01 |

**Total Physical Security Value:** €103,000

### 5.3 Power and Environmental

| Asset ID | Asset Name | Type | Location | Capacity | Owner | Purpose | Value (€) | Last Maintenance |
|----------|------------|------|----------|----------|-------|---------|-----------|------------------|
| PWR-001 | Main UPS | APC Symmetra | Data Center | 20 kVA, 30 min | IT Manager | Power backup | 25,000 | 2025-10-15 |
| PWR-002 | Backup Generator | Caterpillar | Exterior | 1000 kVA, 72h | Facilities Manager | Extended power backup | 150,000 | 2025-09-01 |
| PWR-003 | Production UPS | APC Smart-UPS | Production | 10 kVA, 15 min | OT Manager | PLC power backup | 8,000 | 2025-11-20 |
| ENV-001 | Data Center HVAC | Liebert | Data Center | 15 kW cooling | Facilities Manager | Climate control | 30,000 | 2025-08-10 |
| ENV-002 | Production HVAC | Daikin | Production | 50 kW cooling | Facilities Manager | Climate control | 80,000 | 2025-07-05 |

**Total Power/Environmental Value:** €293,000

---

## 6. Human Assets

### 6.1 Key Personnel

| Role | Name/Position | Department | Criticality | Security Clearance | Backup Person | Key Responsibilities |
|------|---------------|------------|-------------|--------------------| --------------|----------------------|
| CEO | [CEO Name] | Executive | Critical | Full | CFO | Strategic decisions, final authority |
| CISO | [CISO Name] | IT/Security | Critical | Full | IT Manager | Information security, ISMS, incident response |
| IT Manager | [IT Name] | IT | Critical | Full | Senior Sysadmin | IT operations, systems administration |
| OT Manager | [OT Name] | Operations | Critical | OT-specific | Senior Technician | Production systems, PLCs, SCADA |
| DPO (Data Protection Officer) | [DPO Name] | Legal/Compliance | High | Full | External DPO service | GDPR compliance, privacy |
| CFO | [CFO Name] | Finance | High | Full | Controller | Financial operations, budgets |
| Production Manager | [Prod Manager] | Operations | High | Standard | Shift Supervisor | Production planning, quality |
| QA Manager | [QA Name] | Quality | High | Standard | Senior QA Analyst | Quality control, compliance testing |
| Senior Developer | [Dev Name] | IT | Medium | Development | Junior Developer | Application development, security patches |
| Network Administrator | [Net Admin] | IT | Medium | IT-specific | IT Manager | Network operations, firewall management |

**Total Key Personnel:** 10 critical/high criticality roles

**Training and Awareness:**
- All personnel: Annual security awareness training (mandatory)
- IT/OT staff: Role-specific technical training (16 hours/year)
- Developers: Secure coding training (8 hours/year)
- Management: Risk management and compliance (4 hours/year)

---

## 7. Third-Party Services and Suppliers

### 7.1 Critical Service Providers

| Provider ID | Provider Name | Service Type | Service Description | Classification (C/I/A) | Contract End | Annual Cost (€) | Data Access | SLA |
|-------------|---------------|--------------|---------------------|------------------------|--------------|-----------------|-------------|-----|
| 3RD-001 | Amazon Web Services (AWS) | Cloud Infrastructure | IaaS hosting for web app, database | HC/C/C | Ongoing | 28,000 | Yes (customer data) | 99.99% uptime |
| 3RD-002 | Microsoft Corporation | SaaS | Microsoft 365 (email, productivity) | C/M/H | Annual renewal | 12,800 | Yes (employee email) | 99.9% uptime |
| 3RD-003 | Telefonica | ISP | Primary internet connection (fiber 1 Gbps) | C/M/C | 2027-06-30 | 12,000 | No | 99.5% uptime |
| 3RD-004 | Vodafone | ISP | Backup internet (fiber 500 Mbps + 4G failover) | C/M/H | 2026-12-31 | 8,000 | No | 99.0% uptime |
| 3RD-005 | GitHub Inc. | SaaS | Code repository and version control | C/H/M | 2026-11-20 | 2,500 | Yes (source code) | 99.95% uptime |
| 3RD-006 | [Insurance Company] | Insurance | Cyber insurance and business interruption | C/M/M | 2026-08-15 | 15,000 | Limited (risk assessment) | N/A |
| 3RD-007 | [Law Firm] | Legal | Legal counsel, contract review | HC/H/L | Ongoing | 10,000 | Yes (confidential business info) | N/A |
| 3RD-008 | [IT Security Firm] | Security Services | Penetration testing (biannual) | C/H/M | Per-project | 12,000 | Yes (test environment) | N/A |
| 3RD-009 | [Waste Management] | Physical Security | Secure document destruction | C/M/L | 2027-03-31 | 2,000 | No | N/A |
| 3RD-010 | Siemens | OT Support | PLC and SCADA support and maintenance | C/C/H | 2026-08-01 | 8,000 | Limited (OT systems) | 24h response |

**Total Third-Party Annual Cost:** €110,300

### 7.2 Critical Suppliers (Raw Materials)

| Supplier ID | Supplier Name | Product | Criticality | Alternate Supplier | Lead Time | Classification |
|-------------|---------------|---------|-------------|--------------------| ----------|----------------|
| SUP-001 | [Flour Supplier A] | Wheat flour (primary) | Critical | SUP-002 | 3 days | C/C/H |
| SUP-002 | [Flour Supplier B] | Wheat flour (backup) | High | SUP-001 | 5 days | C/C/H |
| SUP-003 | [Sugar Supplier] | Sugar | Critical | Regional alternatives | 7 days | C/M/M |
| SUP-004 | [Butter Supplier] | Butter | Critical | Regional alternatives | 5 days | C/M/M |
| SUP-005 | [Packaging Supplier] | Boxes, wrapping | High | Multiple alternatives | 10 days | I/M/M |
| SUP-006 | [Utility - Electric] | Electricity | Critical | Generator backup | N/A | N/A |
| SUP-007 | [Utility - Water] | Water supply | Critical | Municipal backup | N/A | N/A |

---

## 8. Asset Valuation Summary

### 8.1 Total Asset Value

| Asset Category | Count | Total Value (€) | Percentage |
|----------------|-------|-----------------|------------|
| **IT Assets** | | | |
| Servers and Infrastructure | 10 | 143,000 | 2.6% |
| Network Equipment | 12 | 59,900 | 1.1% |
| Workstations and Laptops | 90 | 105,500 | 1.9% |
| Mobile Devices | 55 | 28,000 | 0.5% |
| Software (perpetual) | 14 | 38,000 | 0.7% |
| **IT Subtotal** | **181** | **374,400** | **6.8%** |
| **OT Assets** | | | |
| PLCs and Spare Parts | 6 + spares | 69,500 | 1.3% |
| SCADA and HMI | 4 | 35,000 | 0.6% |
| Sensors and Instrumentation | 101 | 33,800 | 0.6% |
| Production Equipment | 7 | 430,000 | 7.9% |
| **OT Subtotal** | **118** | **568,300** | **10.4%** |
| **Physical Assets** | | | |
| Facilities | 4 | 3,450,000 | 63.0% |
| Physical Security Systems | 5 | 103,000 | 1.9% |
| Power and Environmental | 5 | 293,000 | 5.4% |
| **Physical Subtotal** | **14** | **3,846,000** | **70.3%** |
| **GRAND TOTAL (Capital Assets)** | **313** | **€4,788,700** | **87.5%** |

### 8.2 Annual Operating Costs

| Category | Annual Cost (€) | Percentage |
|----------|-----------------|------------|
| Software Subscriptions | 84,100 | 12.3% |
| Cloud Services | 43,560 | 6.4% |
| Third-Party Services | 110,300 | 16.1% |
| Maintenance Contracts | 15,000 | 2.2% |
| Insurance | 15,000 | 2.2% |
| Personnel (Security-related) | 420,000 | 61.4% |
| **Total Annual Operating Cost** | **€687,960** | **100%** |

### 8.3 Risk-Weighted Asset Value

Assets weighted by potential business impact if compromised:

| Asset | Business Impact if Lost/Compromised | Risk-Weighted Value (€) |
|-------|--------------------------------------|-------------------------|
| Customer Database (DB-001) | Revenue loss, legal liability, reputation damage | 5,000,000 |
| Product Recipes (DATA-002) | Competitive advantage loss, trade secret theft | 3,000,000 |
| Production PLCs (PLC-001 to PLC-006) | Production stoppage (€20K/day) | 2,000,000 |
| Web Application (SRV-001) | Order processing halt, customer impact | 1,500,000 |
| SCADA System (SCADA-001) | Production monitoring loss, safety risk | 800,000 |
| Financial Records (DATA-003) | Regulatory non-compliance, fraud risk | 500,000 |
| Source Code (DATA-005) | Intellectual property theft, security vulnerabilities | 400,000 |

**Total Risk-Weighted Value:** €13,200,000

---

## 9. Asset Management Procedures

### 9.1 Asset Lifecycle

**Acquisition:**
1. Identify business need
2. Security requirements defined (classification, controls)
3. Vendor security assessment (for IT/OT systems)
4. Procurement approval
5. Receipt and inventory registration
6. Configuration and hardening
7. Testing and acceptance
8. Production deployment
9. Asset register entry

**Operation:**
1. Assign asset owner and custodian
2. Apply security controls per classification
3. Regular maintenance (patch management, updates)
4. Performance monitoring
5. Access control enforcement
6. Quarterly asset review (verify location, status, classification)

**Decommissioning:**
1. Decommissioning request and approval
2. Data backup (if needed)
3. Secure data sanitization (NIST SP 800-88 standards):
   - HDDs: 3-pass overwrite or physical destruction
   - SSDs: ATA Secure Erase or physical destruction
   - Mobile devices: Factory reset + encryption key deletion
   - Paper documents: Cross-cut shredding
4. Physical disposal or sale
5. Asset register update (status: decommissioned)
6. License/subscription cancellation
7. Certificate of destruction (for sensitive assets)

### 9.2 Asset Classification Review

**Review Triggers:**
- Annual review (all assets)
- Change in asset use or data stored
- Security incident involving asset
- Regulatory changes
- Business impact changes

**Classification Changes:**
- Requires approval from asset owner and CISO
- Security controls adjusted accordingly
- Asset register updated
- Users notified if handling requirements change

### 9.3 Asset Register Maintenance

**Update Frequency:**
- Real-time: New acquisitions, decommissioning
- Monthly: Verify IT asset inventory (automated scanning)
- Quarterly: Full asset register review (all categories)
- Annually: Comprehensive audit with physical verification

**Update Process:**
1. Change identified (new asset, modification, disposal)
2. Asset owner notifies CISO
3. CISO updates asset register
4. Change logged in version history
5. Quarterly management review of changes

**Data Quality:**
- Accuracy: ≥98% target (verified in audits)
- Completeness: All assets registered within 48 hours of acquisition
- Currency: Updates within 24 hours of changes

---

## 10. Related Documents

- **Information Security Policy (ISP-001):** Overall security framework
- **Risk Assessment Report (RAR-001):** Asset-based risk analysis
- **Business Continuity Plan (BCP-001):** Critical asset recovery priorities
- **Acceptable Use Policy (AUP-001):** Asset usage guidelines
- **Data Classification Policy:** Data handling requirements
- **Secure Disposal Procedure:** Asset decommissioning standards
- **Change Management Procedure:** Asset modification controls
- **Vendor Security Assessment Template:** Third-party risk assessment

---

## 11. Appendices

### Appendix A: Asset Classification Guidelines

**Confidentiality Classification:**

| Level | Criteria | Examples | Handling |
|-------|----------|----------|----------|
| **Public (P)** | Information intended for public release | Marketing materials, press releases, public website content | No restrictions |
| **Internal (I)** | Information for internal use, minimal damage if disclosed | Internal memos, non-sensitive reports, meeting notes | Email encryption, internal networks only |
| **Confidential (C)** | Sensitive business information, significant damage if disclosed | Customer lists, financial reports (non-critical), recipes (non-core) | Encryption at rest and in transit, access control, MFA |
| **Highly Confidential (HC)** | Critical information, severe damage if disclosed | PII, payment data, trade secrets (core recipes), security keys | Strong encryption, strict access control, MFA, audit logging, DLP |

**Integrity Classification:**

| Level | Criteria | Impact of Unauthorized Modification |
|-------|----------|-------------------------------------|
| **Low (L)** | Easily corrected, minimal impact | Minor inconvenience, quick correction |
| **Medium (M)** | Moderate effort to correct, some impact | Process disruption, manual correction needed |
| **High (H)** | Difficult to correct, significant impact | Production delays, financial loss, customer impact |
| **Critical (C)** | Very difficult or impossible to correct | Safety risks, major financial loss, regulatory violations |

**Availability Classification:**

| Level | Criteria | Maximum Tolerable Downtime |
|-------|----------|---------------------------|
| **Low (L)** | Can be unavailable for extended periods | 72 hours |
| **Medium (M)** | Must be available during business hours | 24 hours |
| **High (H)** | Must be available most of the time | 8 hours |
| **Critical (C)** | Must be available at all times | 4 hours |

### Appendix B: Asset ID Naming Convention

| Prefix | Asset Type | Example |
|--------|------------|---------|
| SRV- | Server | SRV-001 |
| NET- | Network Equipment | NET-001 |
| WRK- | Workstation/Laptop | WRK-001 |
| MOB- | Mobile Device | MOB-001 |
| SW- | Software | SW-001 |
| CLD- | Cloud Service | CLD-001 |
| PLC- | Programmable Logic Controller | PLC-001 |
| SCADA- | SCADA System | SCADA-001 |
| HMI- | Human-Machine Interface | HMI-001 |
| PROD- | Production Equipment | PROD-001 |
| DB- | Database | DB-001 |
| DATA- | Data/Files | DATA-001 |
| BACKUP- | Backup Set | BACKUP-001 |
| FAC- | Facility | FAC-001 |
| SEC- | Security System | SEC-001 |
| PWR- | Power System | PWR-001 |
| ENV- | Environmental System | ENV-001 |
| 3RD- | Third-Party Service | 3RD-001 |
| SUP- | Supplier | SUP-001 |

### Appendix C: Asset Owner Responsibilities

**Asset Owner (Business Role):**
- Define asset classification
- Approve access requests
- Ensure appropriate security controls
- Participate in risk assessments
- Budget for asset security
- Make disposal decisions
- Review asset quarterly

**Asset Custodian (Technical Role):**
- Implement security controls
- Perform daily operations and maintenance
- Monitor asset health and security
- Apply patches and updates
- Report incidents
- Execute backups
- Physical/logical access control

**CISO Responsibilities:**
- Maintain asset register
- Define security requirements
- Audit compliance with controls
- Incident response coordination
- Report security metrics to management

---

## 12. Signatures

**Document Review and Approval:**

| Name | Role | Signature | Date |
|------|------|-----------|------|
| [CISO Name] | Asset Register Owner | | 2026-01-08 |
| [IT Manager] | IT Asset Custodian | | 2026-01-08 |
| [OT Manager] | OT Asset Custodian | | 2026-01-08 |
| [CFO] | Financial Assets | | 2026-01-08 |
| [CEO] | Executive Approval | | 2026-01-08 |

**Next Review Date:** April 8, 2026 (Quarterly)

---

**END OF ASSET REGISTER**

---

**CONFIDENTIALITY NOTICE:**

This document contains detailed information about Zabala Gailetak's information assets, including systems, vulnerabilities, and security measures. Unauthorized disclosure could facilitate attacks against the organization. Handle with appropriate confidentiality controls. Distribution limited to:
- Executive Management
- CISO and Security Team
- IT/OT Managers
- Internal/External Auditors (under NDA)
- Insurance Assessors (redacted version)
