# Zabala Gailetak - Complete AI Agent Context & Security Compliance Guide

This comprehensive guide provides all necessary context for AI agents and developers interacting with the **Zabala Gailetak** project, including architecture, workflows, and detailed security/compliance requirements.

---

## 📋 Table of Contents

1. [Project Overview](#1-project-overview)
2. [System Architecture](#2-system-architecture)
3. [Directory Structure](#3-directory-structure)
4. [Development Workflow](#4-development-workflow)
5. [Security & Compliance Overview](#5-security--compliance-overview)
6. [Implementation Status](#6-current-implementation-status)
7. [AI Agent Guidelines](#7-ai-agent-guidelines)
8. [Detailed Compliance Requirements](#8-detailed-compliance-requirements)
9. [Quick Reference](#9-quick-reference-commands)

---

## 1. Project Overview

**Zabala Gailetak** is a comprehensive cybersecurity and infrastructure modernization project for a biscuit manufacturing company. Originally conceived as an e-commerce platform, it has evolved into a secure **Internal HR Portal** for managing the complete employee lifecycle with strict compliance requirements.

* **Context:** "Erronka 4" (Challenge 4) - Advanced Security Systems (Euskadi FP Challenge).
* **Primary Goal:** Modernize IT/OT infrastructure with a heavy focus on security (ISO 27001:2022, GDPR, IEC 62443) and build a secure internal management system.
* **Documentation Language:** Primary documentation is in **Spanish** and **Basque**. Technical comments and code are in **English**.
* **Timeline:** January 2026 - December 2026.
* **Current Status (Feb 2026):** Infrastructure deployed, core authentication implemented, security monitoring active.

---

## 2. System Architecture

The system follows a **Zero Trust** architecture with strict IT/OT segmentation and defense-in-depth security layers.

### A. Application Layer
* **Backend:** Pure PHP 8.4 REST API (PSR-compliant, no frameworks)
    - **Stack:** Nginx, PostgreSQL 16, Redis 7
    - **Standards:** PSR-1/4 (autoloading), PSR-7 (HTTP), PSR-11 (DI), PSR-15 (middleware)
    - **Security:** JWT with refresh tokens, TOTP MFA, WebAuthn (passkeys), RBAC (5 roles)
    - **Rate Limiting:** Redis-based with tiered limits per endpoint
    - **Session Management:** Secure session handling, device fingerprinting

* **Web Frontend:** React 18 SPA (`src/web/`)
    - **Build:** Vite 5 (HMR, optimized production builds)
    - **Styling:** Styled Components (CSS-in-JS, theme-aware)
    - **State:** Context API + custom hooks, SWR for server state
    - **Features:** Employee CRUD, Vacation Calendar, Document Management, Real-time Chat (WebSocket)

* **Mobile App:** Native Android (`android-app/` - currently in planning)
    - **Stack:** Kotlin 2.0, Jetpack Compose, Material 3 Design
    - **DI:** Hilt (compile-time dependency injection)
    - **Network:** Retrofit + OkHttp with certificate pinning
    - **Architecture:** Clean Architecture + MVI pattern

### B. Infrastructure Layer (`infrastructure/`)
* **Network Segmentation:** VLANs with strict firewall rules
    - VLAN 10: Management (restricted access)
    - VLAN 20: IT/Business applications
    - VLAN 30: DMZ (public-facing services)
    - VLAN 50: OT/Industrial (air-gapped from IT)
* **Load Balancing:** HAProxy with health checks and SSL termination
* **Container Orchestration:** Docker Compose (dev), Kubernetes (planned for production)

### C. OT (Operational Technology) (`infrastructure/ot/`)
* **Simulation:** Cookie production line (demo purposes)
* **Stack:** OpenPLC (Structured Text IEC 61131-3), ScadaBR (HMI), Node-RED
* **Network:** Isolated VLAN 50 with IEC 62443-compliant security zones
* **Security:** Conpot honeypots, Modbus IDS, unidirectional data diode
* **Compliance:** SL 2 (Security Level 2) targeting SL 3 for critical systems

### D. Security Layer (`security/`)
* **SIEM:** ELK Stack + Wazuh for log aggregation and threat detection
* **Honeypots:** T-Pot/Cowrie for threat intelligence
* **Forensics:** Toolkit with Volatility, Autopsy, YARA rules
* **Penetration Testing:** Regular assessments with OWASP ZAP, Burp Suite, Metasploit

---

## 3. Directory Structure & Key Locations

```text
/home/kalista/erronkak/erronka4/
├── Zabala Gailetak/
│   ├── hr-portal/                    # Main Application (PHP)
│   │   ├── src/                      # Backend source (Controllers, Models, Services, Middleware)
│   │   ├── public/                   # Web root (index.php, assets, views)
│   │   │   ├── views/                # PHP SSR templates (auth, dashboard, employees, vacations)
│   │   │   └── assets/               # CSS, JS, images, vendor libs (Bootstrap)
│   │   ├── config/                   # Application configuration
│   │   ├── migrations/               # SQL database migrations
│   │   ├── scripts/                  # CLI scripts (migrate, seed)
│   │   ├── tests/                    # PHPUnit tests
│   │   ├── storage/                  # File uploads, cache
│   │   └── logs/                     # Application logs
│   ├── android-app/                  # Native Android app (Kotlin)
│   ├── tests/                        # Testing Suites
│   │   ├── e2e/                      # Playwright end-to-end tests
│   │   └── load/                     # K6 performance tests
│   ├── compliance/                   # Compliance Documentation
│   │   ├── gdpr/                     # GDPR compliance docs
│   │   ├── sgsi/                     # ISO 27001 ISMS documentation
│   │   ├── iec62443/                 # IEC 62443 industrial security
│   │   └── nis2/                     # NIS2 directive compliance
│   ├── security/                     # Security Tools & Logs
│   │   ├── siem/                     # ELK Stack + Wazuh configs
│   │   ├── honeypot/                 # T-Pot deployment scripts
│   │   ├── forensics/                # Forensic tools and reports
│   │   ├── pentesting/               # Penetration test reports
│   │   ├── incidents/                # Incident response logs
│   │   └── audits/                   # Security audit trails
│   ├── infrastructure/               # Infrastructure as Code
│   │   ├── network/                  # Network topology and configs
│   │   ├── systems/                  # System architecture diagrams
│   │   └── ot/                       # Operational Technology setup
│   │       ├── openplc/              # PLC programs (Structured Text)
│   │       └── simulations/          # HMI and process simulations
│   ├── nginx/                        # Nginx configuration
│   └── docs/                         # Additional Documentation
│       └── network_diagrams/         # Network topology visuals
├── scripts/                          # Utility Scripts
│   └── verify_implementation.sh      # Compliance verification script
├── archive/                          # Archived migration docs
├── ER4.md                            # Core Academic Challenge Requirements
├── API_DOCUMENTATION.md              # REST API Reference
└── AGENTS.md                         # This file (Complete Guide)
```

---

## 4. Development Workflow

### Frontend Development (React SPA)
* **Location:** `Zabala Gailetak/src/web/`
* **Commands:**
    - `npm install` - Install dependencies
    - `npm run dev` - Start Vite dev server (HMR enabled, port 5173)
    - `npm run build` - Production build (minified, tree-shaken)
    - `npm run preview` - Preview production build
    - `npm run lint` - ESLint with React/security rules
    - `npm run format` - Prettier code formatting

### Backend Development (PHP API - In Progress)
* **Location:** `Zabala Gailetak/src/api/` (planned)
* **Current Status:** Backend being migrated from legacy structure
* **Future Commands:**
    - `composer install` - Install PHP dependencies
    - `php artisan serve` or Nginx config for local dev
    - `vendor/bin/phpunit` - Run unit tests (PHPUnit)
    - `vendor/bin/phpcs` - Code style check (PSR-12)
    - Database migrations via custom migration system

### Testing
* **E2E Testing:** `Zabala Gailetak/tests/e2e/`
    - Playwright tests for web flows
    - Command: `npx playwright test`
* **Load Testing:** `Zabala Gailetak/tests/load/`
    - K6 performance tests
    - Command: `k6 run load_test.js`

### Infrastructure & Security
* **SIEM:** `security/siem/` - ELK Stack + Wazuh
    - Access: Kibana dashboard on port 5601
* **Honeypot:** `security/honeypot/` - T-Pot/Cowrie
    - Deployed on isolated DMZ network segment
* **OT Simulation:** `infrastructure/ot/`
    - OpenPLC runtime on port 8080
    - ScadaBR HMI on port 9090

---

## 5. Security & Compliance Overview

**CRITICAL:** This project is a comprehensive security showcase. All code, configuration, and documentation must strictly adhere to:

### A. ISO 27001:2022 - Information Security Management System (ISMS)
- **Implementation Rate:** 87/93 controls (93% compliance)
- **Status:** Annex A controls detailed in Section 8
- **Key Requirements:**
  - Asset inventory and classification (A.5.9, A.5.12)
  - Access control and identity management (A.5.15-5.18)
  - Incident management procedures (A.5.24-5.28)
  - Business continuity planning (A.5.29-5.30)
  - Regular security audits and reviews (A.5.35)

### B. GDPR (General Data Protection Regulation)
- **Data Protection Principles:** Lawfulness, purpose limitation, data minimization, accuracy, storage limitation, integrity
- **Legal Bases:** Consent, contract, legal obligation, legitimate interests
- **Data Subject Rights:** Access, rectification, erasure ("right to be forgotten"), portability, objection
- **Key Requirements:**
  - Privacy by design and default
  - Data Protection Impact Assessments (DPIA)
  - 72-hour breach notification
  - Records of Processing Activities (RoPA)
  - Data Protection Officer (DPO) designation

### C. IEC 62443 - Industrial Control Systems Security
- **Target Security Level:** SL 2 (current), SL 3 (for critical systems)
- **Zone/Conduit Model:** Strict network segmentation between IT and OT
- **Key Requirements:**
  - Network segmentation and firewalling (SR 5.1, SR 5.2)
  - Authentication and authorization (SR 1.1, SR 1.2, SR 2.1)
  - Malicious code protection (SR 3.1)
  - Audit logging and monitoring (SR 6.1, SR 6.2)
  - Secure development lifecycle (IEC 62443-4-1)

### D. OWASP Top 10 (2021)
- A01: Broken Access Control → RBAC implementation with 5 roles
- A02: Cryptographic Failures → TLS 1.3, AES-256-GCM at rest
- A03: Injection → Parameterized queries, input validation
- A04: Insecure Design → Threat modeling during design phase
- A05: Security Misconfiguration → Automated security scanning (SonarQube)
- A07: Authentication Failures → JWT + TOTP MFA + WebAuthn
- A08: Software/Data Integrity → SRI, dependency scanning (npm audit, Snyk)
- A09: Logging/Monitoring Failures → Centralized SIEM (ELK + Wazuh)

**Compliance Verification:** Run `./scripts/verify_implementation.sh` for automated compliance checks.

---

## 6. Current Implementation Status (February 2026)

### ✅ Completed (Production Ready)
* **Infrastructure:**
    - Network segmentation (4 VLANs with firewall rules)
    - Docker containerization for services
    - PostgreSQL 16 database schema
    - Redis 7 for caching and rate limiting
* **Security Baseline:**
    - JWT authentication with refresh tokens
    - RBAC with 5 roles (ADMIN, RRHH_MGR, JEFE_SECCION, EMPLEADO, AUDITOR)
    - TOTP MFA implementation (RFC 6238 compliant)
    - TLS 1.3 encryption for all traffic
    - SIEM deployment (ELK + Wazuh)
* **Documentation:**
    - API documentation (REST endpoints)
    - Network topology diagrams
    - ISO 27001 ISMS documentation (87/93 controls)
    - GDPR compliance records (RoPA)

### 🚧 In Progress (Active Development)
* **Application Features:**
    - Employee management CRUD (80% complete)
    - Vacation request system with approval workflow (60%)
    - Document management with encryption (40%)
    - Real-time chat via WebSocket (30%)
* **Advanced Security:**
    - WebAuthn (passkeys) integration (70%)
    - Forensic analysis toolkit refinement (50%)
    - Honeypot tuning and threat intelligence (60%)

### 📋 Next Steps (Q2 2026)
* **Testing & Validation:**
    - Load testing with K6 (target: 1000 concurrent users)
    - E2E testing with Playwright (coverage > 80%)
    - Penetration testing (internal assessment)
* **OT Integration:**
    - Finalize OpenPLC cookie production simulation
    - Implement unidirectional data diode for OT telemetry
    - Deploy Conpot honeypots in OT zone
* **Compliance:**
    - Complete remaining ISO 27001 controls (6 pending)
    - Third-party audit preparation
    - DPIA for new features

### ⏳ Planned (Q3-Q4 2026)
* **Mobile Application:** Native Android app with biometric auth
* **Advanced Analytics:** Employee performance dashboards
* **Backup & DR:** Automated disaster recovery testing
* **Certification:** ISO 27001 external audit

---

## 7. AI Agent Guidelines

### A. Context Management
* **Source of Truth:** This document (AGENTS.md) contains architectural context and all security/compliance requirements.
* **API Reference:** Check `API_DOCUMENTATION.md` for REST endpoint specifications and authentication flows.
* **Migration History:** Archived migration docs in `archive/migration/` for reference only.

### B. Security-First Development
* **Never Bypass Security:** Do not suggest code that circumvents authentication, authorization, or encryption.
* **Validate Segmentation:** Ensure IT/OT separation is maintained (no direct connections between VLANs 20 and 50).
* **Data Minimization:** Only suggest collecting/storing data absolutely necessary for the feature.
* **Input Validation:** Always validate and sanitize user input (use parameterized queries, escaping, type checking).
* **Secure Defaults:** Prefer secure-by-default configurations (e.g., HTTPS only, strict CSP headers, HttpOnly cookies).

### C. Code Quality Standards
* **PHP Backend:** PSR-1/PSR-4/PSR-12 compliance mandatory. No frameworks (custom PSR implementation).
* **PHP Frontend (SSR):** Clean view templates, Bootstrap 5 components, progressive enhancement.
* **Comments:** Use English for technical comments. Spanish/Basque only in user-facing strings.
* **Testing:** Suggest unit tests (PHPUnit, Jest) and E2E tests (Playwright) for new features.

### D. Bilingual & Cultural Awareness
* **Primary Languages:** Documentation may be in Spanish or Basque (Euskara). Be prepared to read both.
* **Technical Output:** Provide technical explanations in English unless explicitly requested otherwise.
* **User-Facing Content:** Respect bilingual requirements (es-ES and eu-ES) for UI strings.
* **Cultural Context:** Basque Country FP vocational training project with strong regional identity.

### E. File Path Precision
* **Project Root:** `/home/kalista/erronkak/erronka4/`
* **Active Codebase:** `Zabala Gailetak/hr-portal/` (main PHP application)
* **No Assumptions:** Always use absolute paths or confirm current working directory before file operations.

### F. Compliance Verification
* **Before Committing:** Run `./scripts/verify_implementation.sh` to check compliance status.
* **Documentation Updates:** Update relevant compliance docs (SOA, RoPA, risk register) when adding features.
* **Audit Trail:** Log significant changes in `security/audits/` for traceability.

### G. Error Handling & Debugging
* **Detailed Logging:** Suggest structured logging (JSON format) with severity levels.
* **No Sensitive Data in Logs:** Never log passwords, tokens, PII, or cryptographic keys.
* **Graceful Degradation:** Ensure services fail securely (e.g., deny access on auth failure, not grant it).

---

## 8. Detailed Compliance Requirements

## 🔒 Security & Compliance Requirements

### Information Security Management System (ISO 27001:2022)

Based on the Statement of Applicability (SOA), Zabala Gailetak implements 87 out of 93 ISO 27001:2022 controls (93% compliance rate):

**ISMS Core Components:**
- **Information Security Policies**: Comprehensive security policies for all aspects
- **Organization of Information Security**: Roles, responsibilities, and authorities
- **Human Resource Security**: Employee screening, training, and termination procedures
- **Asset Management**: Asset register, classification, and handling procedures
- **Access Control**: Business requirements, user access management, user responsibilities
- **Cryptography**: Policy on use of cryptographic controls
- **Physical and Environmental Security**: Secure areas, equipment security
- **Operations Security**: Operational procedures, protection against malware, backup procedures
- **Communications Security**: Network security management, information transfer
- **System Acquisition, Development and Maintenance**: Security requirements, security in development, supplier relationships
- **Supplier Relationships**: Information security in supplier agreements
- **Information Security Incident Management**: Reporting, assessment, response, learning
- **Information Security Aspects of Business Continuity**: Continuity planning, redundancies
- **Compliance**: Compliance with legal, regulatory, and contractual requirements

**Required Controls (Annex A) - Implementation Status:**

#### A.5 Organizational Controls (37 controls - 100% implemented)
- **A.5.1 Information security policies** ✅
- **A.5.2 Information security roles and responsibilities** ✅
- **A.5.3 Segregation of duties** ✅
- **A.5.4 Management responsibilities** ✅
- **A.5.5 Contact with authorities** ✅
- **A.5.6 Contact with special interest groups** ✅
- **A.5.7 Threat intelligence** ✅
- **A.5.8 Information security in project management** ✅
- **A.5.9 Inventory of assets** ✅
- **A.5.10 Acceptable use of information and other associated assets** ✅
- **A.5.11 Return of assets** ✅
- **A.5.12 Classification of information** ⚠️ Partially implemented
- **A.5.13 Labelling of information** ⚠️ Partially implemented
- **A.5.14 Information transfer** ✅
- **A.5.15 Access control** ✅
- **A.5.16 Identity management** ✅
- **A.5.17 Authentication information** ✅
- **A.5.18 Access rights** ✅
- **A.5.19 Information security in supplier relationships** ✅
- **A.5.20 Addressing information security within supplier agreements** ✅
- **A.5.21 Managing information security in the ICT supply chain** ✅
- **A.5.22 Monitoring, review and change management of supplier services** ✅
- **A.5.23 Information security for use of cloud services** ✅
- **A.5.24 Information security incident management planning and preparation** ✅
- **A.5.25 Assessment and decision on information security events** ✅
- **A.5.26 Response to information security incidents** ✅
- **A.5.27 Learning from information security incidents** ✅
- **A.5.28 Collection of evidence** ✅
- **A.5.29 Information security during disruption** ✅
- **A.5.30 ICT readiness for business continuity** ✅
- **A.5.31 Legal, statutory, regulatory and contractual requirements** ✅
- **A.5.32 Intellectual property rights** ✅
- **A.5.33 Protection of records** ✅
- **A.5.34 Privacy and protection of PII** ✅
- **A.5.35 Independent review of information security** ✅
- **A.5.36 Compliance with policies and standards of information security** ✅
- **A.5.37 Documented operating procedures** ✅

#### A.6 People Controls (8 controls - 100% implemented)
- **A.6.1 Screening** ✅
- **A.6.2 Terms and conditions of employment** ✅
- **A.6.3 Information security awareness, education and training** ✅
- **A.6.4 Disciplinary process** ✅
- **A.6.5 Responsibilities after termination or change of employment** ✅
- **A.6.6 Confidentiality or non-disclosure agreements** ✅
- **A.6.7 Remote working** ✅
- **A.6.8 Information security event reporting** ✅

#### A.7 Physical Controls (14 controls - 100% implemented)
- **A.7.1 Physical security perimeter** ✅
- **A.7.2 Physical entry controls** ✅
- **A.7.3 Securing offices, rooms and facilities** ✅
- **A.7.4 Physical security monitoring** ✅
- **A.7.5 Protecting against physical and environmental threats** ✅
- **A.7.6 Working in secure areas** ✅
- **A.7.7 Clear desk and clear screen policy** ⚠️ Partially implemented
- **A.7.8 Equipment siting and protection** ✅
- **A.7.9 Security of assets off-premises** ✅
- **A.7.10 Storage media** ✅
- **A.7.11 Supporting utilities** ✅
- **A.7.12 Cabling security** ✅
- **A.7.13 Equipment maintenance** ✅
- **A.7.14 Secure disposal or re-use of equipment** ✅

#### A.8 Technological Controls (34 controls - 94% implemented)
- **A.8.1 User endpoint devices** ✅
- **A.8.2 Privileged access rights** ✅
- **A.8.3 Information access restriction** ✅
- **A.8.4 Access to source code** ✅
- **A.8.5 Secure authentication** ✅
- **A.8.6 Capacity management** ✅
- **A.8.7 Protection against malware** ✅
- **A.8.8 Management of technical vulnerabilities** ✅
- **A.8.9 Configuration management** ✅
- **A.8.10 Information deletion** ✅
- **A.8.11 Data masking** ⚠️ Partially implemented
- **A.8.12 Data leakage prevention** ⚠️ Partially implemented
- **A.8.13 Information backup** ✅
- **A.8.14 Redundancy of information processing facilities** ⚠️ Partially implemented
- **A.8.15 Logging** ✅
- **A.8.16 Monitoring activities** ✅
- **A.8.17 Clock synchronization** ✅
- **A.8.18 Use of privileged utility programs** ✅
- **A.8.19 Installation of software on operational systems** ✅
- **A.8.20 Network security** ✅
- **A.8.21 Security of network services** ✅
- **A.8.22 Segregation of networks** ✅
- **A.8.23 Web filtering** ✅
- **A.8.24 Use of cryptography** ✅
- **A.8.25 Secure development lifecycle** ✅
- **A.8.26 Application security requirements** ✅
- **A.8.27 Secure system engineering principles** ✅
- **A.8.28 Secure coding** ✅
- **A.8.29 Security testing in development and acceptance** ✅
- **A.8.30 Outsourced development** ✅
- **A.8.31 Separation of development, test and production environments** ✅
- **A.8.32 Change management** ✅
- **A.8.33 Test information** ✅
- **A.8.34 Protection of information systems during audit testing** ✅

### General Data Protection Regulation (GDPR) Compliance

**Data Protection Principles:**
- **Lawfulness, Fairness and Transparency**: Processing must be lawful, fair, and transparent
- **Purpose Limitation**: Collected for specified, explicit, and legitimate purposes
- **Data Minimization**: Adequate, relevant, and limited to what's necessary
- **Accuracy**: Accurate and kept up to date
- **Storage Limitation**: Kept in a form allowing identification only as long as necessary
- **Integrity and Confidentiality**: Processed securely with appropriate protection
- **Accountability**: Controller responsible for compliance and ability to demonstrate compliance

**Lawful Bases for Processing:**
- **Consent**: Individual has given clear consent
- **Contract**: Processing necessary for performance of contract
- **Legal Obligation**: Processing necessary for compliance with legal obligation
- **Vital Interests**: Processing necessary to protect vital interests
- **Public Task**: Processing necessary for performance of task in public interest
- **Legitimate Interests**: Processing necessary for legitimate interests (unless overridden)

**Data Subject Rights:**
- **Right to Information**: Transparent information about processing
- **Right of Access**: Confirmation whether personal data processed, access to data
- **Right to Rectification**: Rectification of inaccurate personal data
- **Right to Erasure ("Right to be Forgotten")**: Erasure of personal data in certain circumstances
- **Right to Restriction of Processing**: Restriction of processing in certain circumstances
- **Right to Data Portability**: Receive and reuse personal data across services
- **Right to Object**: Object to processing based on legitimate interests or direct marketing
- **Rights Related to Automated Decision Making**: Not to be subject to automated decisions with significant effects

**Data Protection Impact Assessment (DPIA):**
- Required for high-risk processing activities
- Must be conducted prior to processing
- Assess necessity and proportionality
- Consider risks to rights and freedoms
- Identify measures to address risks
- Consult supervisory authority where necessary

**Data Breach Notification:**
- Notify supervisory authority within 72 hours of becoming aware of breach
- Communicate to individuals without undue delay where risk to rights and freedoms
- Document all breaches with facts relating to breach, effects, remedial action taken
- Maintain breach register

**Data Protection Officer (DPO):**
- Designated where processing likely to result in high risk to rights and freedoms
- Expert in data protection law and practices
- Involved in all issues relating to data protection
- Reports directly to highest level of management
- Contact point for supervisory authority and data subjects

**Records of Processing Activities:**
- Maintained by all controllers and processors
- Include purposes of processing, categories of data subjects and personal data
- Recipients or categories of recipients
- Transfers to third countries and safeguards
- Retention periods
- Technical and organizational security measures

**Data Protection by Design and Default:**
- Data protection principles integrated into processing
- Both at time of determination of means and at time of processing
- Appropriate technical and organizational measures implemented
- Only personal data necessary for each specific purpose processed
- Data protection throughout entire lifecycle

**Processing Activities (from Records of Processing):**
1. **Customer Management**: Order processing, invoicing, shipping, loyalty programs
2. **Human Resources**: Payroll, contracts, occupational health
3. **Video Surveillance**: Facility security monitoring

### IEC 62443 Industrial Automation and Control Systems Security

**Security Levels:**
- **SL 0**: No specific security requirements
- **SL 1**: Prevention of accidental or unintentional violations
- **SL 2**: Prevention of deliberate violations using simple means with low resources
- **SL 3**: Prevention of deliberate violations using sophisticated means with moderate resources
- **SL 4**: Prevention of deliberate violations using sophisticated means with extended resources

**IEC 62443-3-3: System Security Requirements and Security Levels:**
- **SR 1.1: Identification and Authentication Control (IAC)**: Human user identification and authentication
- **SR 1.2: Identification and Authentication Control (IAC)**: Software process and device identification and authentication
- **SR 2.1: Use Control (UC)**: Authorization enforcement
- **SR 2.2: Use Control (UC)**: Wireless use control
- **SR 2.3: Use Control (UC)**: Zone boundary protection
- **SR 2.4: Use Control (UC)**: Device resource protection
- **SR 3.1: System Integrity (SI)**: Malicious code protection
- **SR 3.2: System Integrity (SI)**: Memory protection
- **SR 3.3: System Integrity (SI)**: Serializing
- **SR 3.4: System Integrity (SI)**: Domain isolation
- **SR 3.5: System Integrity (SI)**: Access point protection
- **SR 4.1: Data Confidentiality (DC)**: Data confidentiality
- **SR 4.2: Data Confidentiality (DC)**: Cryptographic key management
- **SR 4.3: Data Confidentiality (DC)**: Communications confidentiality
- **SR 5.1: Restricted Data Flow (RDF)**: Network segmentation
- **SR 5.2: Restricted Data Flow (RDF)**: Zone segmentation
- **SR 5.3: Restricted Data Flow (RDF)**: Segregation of duties
- **SR 6.1: Timely Response to Events (TRE)**: Audit log accessibility
- **SR 6.2: Timely Response to Events (TRE)**: Continuous auditing
- **SR 7.1: Resource Availability (RA)**: Denial of service protection

**IEC 62443-4-1: Secure Development Lifecycle Requirements:**
- **SDLC Requirements**: Security management during system development
- **Patch Management**: Timely application of security patches
- **Vulnerability Management**: Identification and remediation of vulnerabilities
- **Security Updates**: Regular security updates and patches
- **Change Management**: Controlled changes to industrial systems
- **Configuration Management**: Secure configuration of industrial systems

**Zone and Conduit Model:**
- **Zones**: Group of logically associated assets with common security requirements
- **Conduits**: Mechanisms that provide controlled communications between zones
- **Security Levels**: Different security level requirements for different zones
- **Sub-zones**: Further subdivision within zones for additional security controls

**Industrial Control System (ICS) Specific Requirements:**
- **Availability**: Critical systems must maintain availability (99.9%+ uptime)
- **Real-time Operations**: Security controls must not impact real-time performance
- **Legacy Systems**: Secure integration of legacy industrial systems
- **Operational Technology (OT) Security**: Specialized security for OT environments
- **Supply Chain Security**: Security requirements for industrial suppliers

### Access Control & Authentication

**Multi-Factor Authentication (MFA):**
- Required for all remote access and privileged accounts
- TOTP (Time-based One-Time Password) implementation with RFC 6238 compliance
- Recovery codes and backup authentication methods
- MFA bypass prevention and monitoring
- Integration with directory services (LDAP/Active Directory)

**Role-Based Access Control (RBAC):**
- **ADMIN**: Full system access, configuration management, user administration
- **RRHH MGR**: Employee management, approvals, reports, HR data access
- **JEFE SECCIÓN**: Department team management, departmental reporting
- **EMPLEADO**: Personal data access only, self-service functions
- **AUDITOR**: Read-only access to audit logs and compliance reports

**Privileged Access Management:**
- Just-in-time access for administrative functions
- Session recording and monitoring for privileged sessions
- Automated deprovisioning of access rights
- Approval workflows for privilege escalation
- Time-based access restrictions

### Data Classification & Handling

**Classification Levels:**
- **Public**: Marketing materials, general company information
- **Internal**: Non-sensitive business data, internal communications
- **Confidential**: Employee personal data, financial information, business plans
- **Highly Confidential**: Trade secrets, critical security data, PII, financial records

**Data Handling Procedures:**
- **Labeling**: All data must be labeled according to classification
- **Storage**: Appropriate storage media based on classification
- **Transmission**: Secure transmission methods (encryption, secure protocols)
- **Destruction**: Secure destruction methods (cryptographic erasure, physical destruction)
- **Backup**: Encrypted backups with retention policies
- **Archival**: Long-term archival with integrity protection

**Encryption Requirements:**
- **At Rest**: AES-256-GCM for all sensitive data storage
- **In Transit**: TLS 1.3 minimum with certificate-based authentication
- **Passwords**: bcrypt with cost factor 12+ or Argon2
- **Key Management**: Hardware Security Modules (HSM) for critical keys
- **Key Rotation**: Automatic rotation of encryption keys

### Incident Response & Management

**Incident Response Plan:**
1. **Preparation**: Incident response team, tools, communication plans
2. **Identification**: Incident detection through monitoring and reporting
3. **Containment**: Short-term and long-term containment strategies
4. **Eradication**: Remove root cause and prevent recurrence
5. **Recovery**: Restore systems and validate integrity
6. **Lessons Learned**: Post-incident review and process improvement

**Incident Classification:**
- **Critical**: System-wide compromise, data breach affecting >100 individuals
- **High**: Significant system disruption, data breach affecting <100 individuals
- **Medium**: Limited system impact, potential security weakness
- **Low**: Minor security events, false positives

**Response Times (per ISO 27001):**
- **Critical**: Response within 15 minutes, resolution within 4 hours
- **High**: Response within 1 hour, resolution within 24 hours
- **Medium**: Response within 4 hours, resolution within 72 hours
- **Low**: Response within 24 hours, resolution within 1 week

**Evidence Collection and Chain of Custody:**
- **Digital Evidence**: Volatile memory, disk images, network logs
- **Documentation**: Incident timeline, actions taken, evidence collected
- **Chain of Custody**: Document who collected, handled, or analyzed evidence
- **Forensic Tools**: Certified tools for evidence collection and analysis

### Secure Development Lifecycle (SSDLC)

**Security Gates:**
1. **Planning**: Threat modeling, security requirements definition, risk assessment
2. **Design**: Secure architecture review, threat modeling validation, security design patterns
3. **Coding**: SAST scanning, secure coding practices
4. **Testing**: DAST scanning, penetration testing, dependency checks
5. **Deployment**: Security configuration validation
6. **Operations**: Continuous monitoring, vulnerability management, security updates

**Required Security Testing:**
- **SAST (Static Application Security Testing)**: SonarQube, Checkmarx, or equivalent
- **DAST (Dynamic Application Security Testing)**: OWASP ZAP, Burp Suite
- **SCA (Software Composition Analysis)**: OWASP Dependency Check, Snyk
- **Penetration Testing**: Annual external assessments, quarterly internal testing
- **Container Security**: Image scanning with Trivy or Clair
- **Infrastructure as Code Security**: Checkov or Terrascan

**Code Review Requirements:**
- **Automated Checks**: ESLint security rules, SonarQube quality gates
- **Manual Review**: Security-focused code review checklist
- **Peer Review**: All changes reviewed by at least one other developer
- **Security Champions**: Designated security reviewers for complex changes

### Physical and Environmental Security

**Secure Areas:**
- **Data Centers**: Biometric access, CCTV surveillance, environmental controls
- **Server Rooms**: Restricted access, fire suppression, uninterruptible power supply
- **Workstations**: Clean desk policy, screen locks, secure disposal procedures

**Environmental Controls:**
- **Temperature and Humidity**: Monitoring and alerting for optimal conditions
- **Fire Detection and Suppression**: FM-200 or equivalent clean agent systems
- **Power Protection**: UPS systems with automatic failover
- **Redundancy**: Backup power generators and redundant cooling systems

**Asset Management:**
- **Asset Register**: Complete inventory of all information assets
- **Asset Classification**: Security classification and handling requirements
- **Asset Tracking**: Movement tracking and secure disposal procedures
- **Mobile Device Management**: MDM policies for company devices

### Supplier and Third-Party Risk Management

**Supplier Assessment:**
- **Security Questionnaires**: Standardized security assessment questionnaires
- **On-site Audits**: Physical security and process audits for critical suppliers
- **Contractual Requirements**: Security clauses in all supplier contracts
- **Continuous Monitoring**: Ongoing security monitoring of supplier performance

**Third-Party Access:**
- **Access Reviews**: Regular review of third-party access rights
- **Monitoring**: Logging and monitoring of third-party activities
- **Termination Procedures**: Secure removal of access upon contract termination
- **Background Checks**: Security clearance for personnel with privileged access

### Business Continuity and Disaster Recovery

**Business Impact Analysis (BIA):**
- **Critical Business Functions**: Identification of essential business processes
- **Maximum Tolerable Period of Disruption (MTPD)**: Maximum downtime acceptable
- **Recovery Time Objectives (RTO)**: Time to restore critical functions
- **Recovery Point Objectives (RPO)**: Maximum data loss acceptable

**Business Continuity Plan:**
- **Emergency Response**: Immediate response procedures for various disaster scenarios
- **Alternate Work Arrangements**: Remote work capabilities and procedures
- **Communications Plan**: Internal and external communication procedures
- **Plan Testing**: Regular testing and updating of continuity plans

**Disaster Recovery Plan:**
- **Backup Procedures**: Regular backups with encryption and off-site storage
- **Recovery Procedures**: Step-by-step system recovery procedures
- **Failover Systems**: Redundant systems and automatic failover capabilities
- **Testing**: Regular disaster recovery testing and validation

### Compliance Monitoring and Reporting

**Continuous Compliance Monitoring:**
- **Automated Controls**: Technical controls monitored continuously
- **Manual Controls**: Periodic manual verification and testing
- **Exception Management**: Process for handling control exceptions
- **Corrective Actions**: Timely remediation of compliance gaps

**Compliance Reporting:**
- **Internal Reporting**: Regular reports to management and board
- **External Reporting**: Regulatory reporting as required
- **Audit Preparation**: Documentation and evidence for external audits
- **Compliance Dashboard**: Real-time compliance status monitoring

**Independent Audits:**
- **Internal Audits**: Quarterly internal compliance assessments
- **External Audits**: Annual ISO 27001 certification audits
- **Regulatory Audits**: As required by specific regulations
- **Penetration Testing**: Annual external penetration testing

### Training and Awareness

**Security Awareness Training:**
- **New Employee Training**: Security basics during onboarding
- **Annual Refresher Training**: Comprehensive security awareness annually
- **Role-Specific Training**: Specialized training for security roles
- **Incident Response Training**: Practical training for incident response team

**Training Effectiveness:**
- **Knowledge Assessments**: Pre and post-training assessments
- **Phishing Simulations**: Regular phishing awareness campaigns
- **Metrics Tracking**: Training completion rates and effectiveness measures
- **Continuous Improvement**: Training program updates based on incidents and threats

### Risk Management

**Risk Assessment Methodology:**
- **Asset Identification**: Comprehensive asset inventory
- **Threat Identification**: Current and emerging threats
- **Vulnerability Assessment**: Technical and organizational vulnerabilities
- **Impact Assessment**: Business impact of security incidents
- **Risk Calculation**: Quantitative risk scoring (Likelihood × Impact)

**Risk Treatment:**
- **Risk Acceptance**: Documented acceptance of residual risks
- **Risk Mitigation**: Implementation of controls to reduce risk
- **Risk Transfer**: Insurance or contractual risk transfer
- **Risk Avoidance**: Elimination of risky activities

**Risk Monitoring:**
- **Key Risk Indicators (KRIs)**: Metrics to monitor risk levels
- **Risk Reporting**: Regular risk reports to management
- **Risk Appetite**: Defined risk tolerance levels
- **Risk Register**: Comprehensive risk tracking and management

---

## 9. Quick Reference Commands

```bash
# Application Development
cd "Zabala Gailetak/hr-portal"
composer install                     # Install PHP dependencies
php -S localhost:8080 -t public/     # Start local dev server
vendor/bin/phpunit                   # Run unit tests
vendor/bin/phpcs                     # Code style check

# Testing
cd "Zabala Gailetak/tests/e2e"
npx playwright test                 # Run E2E tests

cd "Zabala Gailetak/tests/load"
k6 run load_test.js                 # Run load tests

# Compliance Check
./scripts/verify_implementation.sh   # Verify ISO 27001 compliance

# Security Services
# SIEM: http://localhost:5601 (Kibana)
# OpenPLC: http://localhost:8080
# ScadaBR: http://localhost:9090
```

---

**Last Updated:** 2026-02-06 | **Version:** 3.0 (Cleaned & Consolidated)
