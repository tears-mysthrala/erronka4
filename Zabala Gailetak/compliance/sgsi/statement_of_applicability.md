# Statement of Applicability (SOA)
**Zabala Gailetak S.L.**  
**ISO/IEC 27001:2022**

**Version:** 1.0  
**Date:** January 8, 2026  
**Classification:** Internal - Confidential

---

## Document Control

| Version | Date | Author | Changes |
|---------|------|--------|---------|
| 1.0 | 2026-01-08 | CISO | Initial SOA creation |

**Approved by:** Chief Information Security Officer (CISO)  
**Review Date:** 2026-07-08 (6 months)

---

## 1. Introduction

### 1.1 Purpose
This Statement of Applicability (SOA) documents which controls from ISO/IEC 27001:2022 Annex A are applicable to Zabala Gailetak's Information Security Management System (ISMS) and justifies decisions for inclusion or exclusion.

### 1.2 Scope
The ISMS scope covers:
- **IT Infrastructure:** Servers, networks, databases, cloud services
- **OT Systems:** Production control systems, SCADA, PLCs
- **Applications:** Web applications, mobile apps, ERP systems
- **Data:** Customer data, business information, intellectual property
- **Personnel:** All 120 employees, contractors, third parties
- **Locations:** Main facility in Donostia and remote workers

### 1.3 ISMS Context
Zabala Gailetak is a cookie manufacturing company with integrated IT and OT systems. Our ISMS addresses:
- Protection of customer personal data (GDPR compliance)
- Operational technology security (production systems)
- Supply chain security
- Cybersecurity threats to manufacturing operations

---

## 2. Annex A Controls Assessment

### A.5 Organizational Controls (37 controls)

#### A.5.1 Policies for information security
**Status:** ✅ **Implemented**  
**Justification:** Information Security Policy established and approved by management.  
**Evidence:** Information Security Policy v1.0 (documented in this compliance package)  
**Implementation:** Management approved policy communicated to all staff via intranet and onboarding.

#### A.5.2 Information security roles and responsibilities
**Status:** ✅ **Implemented**  
**Justification:** Roles defined including CISO, Security Team, System Administrators.  
**Evidence:** Organizational chart, job descriptions, responsibility matrix  
**Implementation:** RACI matrix created, responsibilities assigned and documented.

#### A.5.3 Segregation of duties
**Status:** ✅ **Implemented**  
**Justification:** Critical roles separated to prevent fraud and errors.  
**Evidence:** Access control policies, approval workflows  
**Implementation:** Development, testing, and production environments segregated. No single person can deploy code and approve changes.

#### A.5.4 Management responsibilities
**Status:** ✅ **Implemented**  
**Justification:** Management committed to ISMS with resource allocation.  
**Evidence:** ISMS budget approval, management review meetings  
**Implementation:** Quarterly management reviews, annual ISMS budget.

#### A.5.5 Contact with authorities
**Status:** ✅ **Implemented**  
**Justification:** Contacts established with Spanish Data Protection Agency (AEPD), local police.  
**Evidence:** Contact list, incident response plan  
**Implementation:** Emergency contacts documented, relationships maintained.

#### A.5.6 Contact with special interest groups
**Status:** ✅ **Implemented**  
**Justification:** Participation in cybersecurity forums, industry groups.  
**Evidence:** Membership records, threat intelligence subscriptions  
**Implementation:** Member of Basque Cybersecurity Center, INCIBE alerts subscribed.

#### A.5.7 Threat intelligence
**Status:** ✅ **Implemented**  
**Justification:** Threat intelligence feeds integrated into SIEM.  
**Evidence:** SIEM configuration, threat intelligence subscriptions  
**Implementation:** MITRE ATT&CK framework used, automated threat feeds.

#### A.5.8 Information security in project management
**Status:** ✅ **Implemented**  
**Justification:** Security requirements included in project lifecycle.  
**Evidence:** Project templates, security checklists  
**Implementation:** Security review gates at planning, development, deployment phases.

#### A.5.9 Inventory of information and other associated assets
**Status:** ✅ **Implemented**  
**Justification:** Asset register maintained with classifications.  
**Evidence:** Asset register spreadsheet, CMDB  
**Implementation:** Database of all IT/OT assets, owners assigned, annual review.

#### A.5.10 Acceptable use of information and other associated assets
**Status:** ✅ **Implemented**  
**Justification:** Acceptable Use Policy (AUP) defined.  
**Evidence:** AUP document (in this package)  
**Implementation:** All employees sign AUP during onboarding.

#### A.5.11 Return of assets
**Status:** ✅ **Implemented**  
**Justification:** Termination checklist ensures asset return.  
**Evidence:** HR termination process, asset tracking  
**Implementation:** IT equipment, access cards, keys collected on last day.

#### A.5.12 Classification of information
**Status:** ⚠️ **Partially Implemented**  
**Justification:** Basic classification (Public, Internal, Confidential, Restricted).  
**Evidence:** Classification policy draft  
**Implementation:** Policy defined but not fully rolled out. Action: Complete classification marking of all documents by Q2 2026.

#### A.5.13 Labelling of information
**Status:** ⚠️ **Partially Implemented**  
**Justification:** Digital labels on some systems, physical labels missing.  
**Evidence:** Email footers, document templates  
**Implementation:** Email classification tags implemented. Action: Implement document classification watermarks.

#### A.5.14 Information transfer
**Status:** ✅ **Implemented**  
**Justification:** Secure file transfer procedures established.  
**Evidence:** Data transfer policy, encryption standards  
**Implementation:** SFTP, HTTPS only. USB drives encrypted. Email encryption available.

#### A.5.15 Access control
**Status:** ✅ **Implemented**  
**Justification:** Role-Based Access Control (RBAC) implemented.  
**Evidence:** Access control policy, Active Directory groups  
**Implementation:** Least privilege principle, regular access reviews.

#### A.5.16 Identity management
**Status:** ✅ **Implemented**  
**Justification:** Centralized identity management via Active Directory.  
**Evidence:** Identity management procedures  
**Implementation:** Unique accounts for all users, service accounts managed separately.

#### A.5.17 Authentication information
**Status:** ✅ **Implemented**  
**Justification:** Strong password policy, MFA for administrators.  
**Evidence:** Password policy (in this package), MFA logs  
**Implementation:** 12 character minimum, complexity required, MFA mandatory for admin accounts.

#### A.5.18 Access rights
**Status:** ✅ **Implemented**  
**Justification:** Formal access provisioning and deprovisioning process.  
**Evidence:** Access request forms, approval workflows  
**Implementation:** Manager approval required, quarterly access reviews.

#### A.5.19 Information security in supplier relationships
**Status:** ✅ **Implemented**  
**Justification:** Vendor security assessment process.  
**Evidence:** Vendor management SOP (in this package), security questionnaires  
**Implementation:** Security requirements in contracts, annual vendor reviews.

#### A.5.20 Addressing information security within supplier agreements
**Status:** ✅ **Implemented**  
**Justification:** Security clauses in all vendor contracts.  
**Evidence:** Contract templates, legal review  
**Implementation:** NDA, security standards, audit rights, incident notification requirements.

#### A.5.21 Managing information security in the ICT supply chain
**Status:** ✅ **Implemented**  
**Justification:** Software supply chain security considered.  
**Evidence:** Dependency scanning (OWASP Dependency-Check), SBOMs  
**Implementation:** Automated vulnerability scanning of dependencies in CI/CD pipeline.

#### A.5.22 Monitoring, review and change management of supplier services
**Status:** ✅ **Implemented**  
**Justification:** Vendor performance monitoring.  
**Evidence:** Vendor scorecard, quarterly reviews  
**Implementation:** SLA monitoring, security incident tracking per vendor.

#### A.5.23 Information security for use of cloud services
**Status:** ✅ **Implemented**  
**Justification:** Cloud security policy for AWS/Azure usage.  
**Evidence:** Cloud security policy, CSA STAR assessment  
**Implementation:** Encryption at rest/transit, cloud security posture management.

#### A.5.24 Information security incident management planning and preparation
**Status:** ✅ **Implemented**  
**Justification:** Incident response plan documented.  
**Evidence:** Incident Response Plan (in security/incident_response/)  
**Implementation:** IR team identified, playbooks created, quarterly tabletop exercises.

#### A.5.25 Assessment and decision on information security events
**Status:** ✅ **Implemented**  
**Justification:** Event classification criteria defined.  
**Evidence:** Event classification matrix, SIEM alert rules  
**Implementation:** Automated event correlation in SIEM, severity ratings assigned.

#### A.5.26 Response to information security incidents
**Status:** ✅ **Implemented**  
**Justification:** Incident response procedures documented.  
**Evidence:** IR playbooks, communication templates  
**Implementation:** Containment, eradication, recovery procedures. Forensics toolkit available.

#### A.5.27 Learning from information security incidents
**Status:** ✅ **Implemented**  
**Justification:** Post-incident reviews conducted.  
**Evidence:** Incident reports with lessons learned  
**Implementation:** Root cause analysis, corrective actions tracked, knowledge base updated.

#### A.5.28 Collection of evidence
**Status:** ✅ **Implemented**  
**Justification:** Evidence collection procedures for forensics.  
**Evidence:** Forensic SOPs (in security/forensics/)  
**Implementation:** Chain of custody forms, evidence preservation tools, hash verification.

#### A.5.29 Information security during disruption
**Status:** ✅ **Implemented**  
**Justification:** Business Continuity Plan addresses security.  
**Evidence:** BCP (in this package)  
**Implementation:** Backup sites, failover procedures, crisis communication plan.

#### A.5.30 ICT readiness for business continuity
**Status:** ✅ **Implemented**  
**Justification:** IT disaster recovery capabilities.  
**Evidence:** DR plan, backup test logs  
**Implementation:** Daily backups, quarterly DR tests, RTO/RPO defined.

#### A.5.31 Legal, statutory, regulatory and contractual requirements
**Status:** ✅ **Implemented**  
**Justification:** Compliance register maintained.  
**Evidence:** Compliance register spreadsheet  
**Implementation:** GDPR, LOPD, sector-specific regulations tracked. Legal review quarterly.

#### A.5.32 Intellectual property rights
**Status:** ✅ **Implemented**  
**Justification:** IP protection measures in place.  
**Evidence:** Software licensing register, IP policy  
**Implementation:** Licensed software tracked, open source licenses verified.

#### A.5.33 Protection of records
**Status:** ✅ **Implemented**  
**Justification:** Record retention policy defined.  
**Evidence:** Record retention schedule  
**Implementation:** Financial records 10 years, HR 5 years, logs 2 years per GDPR.

#### A.5.34 Privacy and protection of personal information
**Status:** ✅ **Implemented**  
**Justification:** GDPR compliance program.  
**Evidence:** GDPR documentation package, DPO appointed  
**Implementation:** Privacy by design, data processing agreements, subject rights procedures.

#### A.5.35 Independent review of information security
**Status:** ✅ **Implemented**  
**Justification:** Annual external audit planned.  
**Evidence:** Audit contract, previous audit reports  
**Implementation:** Internal audits quarterly, external audit annually.

#### A.5.36 Compliance with policies, rules and standards for information security
**Status:** ✅ **Implemented**  
**Justification:** Policy compliance monitoring.  
**Evidence:** Compliance audit reports, policy attestations  
**Implementation:** Annual policy review, employee attestation, automated compliance checks.

#### A.5.37 Documented operating procedures
**Status:** ✅ **Implemented**  
**Justification:** SOPs documented for all critical processes.  
**Evidence:** SOP library in infrastructure/systems/  
**Implementation:** 20+ SOPs covering backup, patching, user management, incident response.

---

### A.6 People Controls (8 controls)

#### A.6.1 Screening
**Status:** ✅ **Implemented**  
**Justification:** Background checks for all employees.  
**Evidence:** HR hiring procedures  
**Implementation:** Criminal background check, reference verification, employment history.

#### A.6.2 Terms and conditions of employment
**Status:** ✅ **Implemented**  
**Justification:** Security responsibilities in employment contracts.  
**Evidence:** Employment contract template  
**Implementation:** Confidentiality clause, security obligations, acceptable use acknowledgment.

#### A.6.3 Information security awareness, education and training
**Status:** ✅ **Implemented**  
**Justification:** Security awareness program established.  
**Evidence:** Training materials, attendance records  
**Implementation:** Annual mandatory training, phishing simulations quarterly, security tips monthly.

#### A.6.4 Disciplinary process
**Status:** ✅ **Implemented**  
**Justification:** Disciplinary procedures for security violations.  
**Evidence:** HR policy manual  
**Implementation:** Progressive discipline policy, security violations addressed per severity.

#### A.6.5 Responsibilities after termination or change of employment
**Status:** ✅ **Implemented**  
**Justification:** Termination checklist ensures access revocation.  
**Evidence:** Termination SOP  
**Implementation:** Access removed on last day, exit interview, return of assets verified.

#### A.6.6 Confidentiality or non-disclosure agreements
**Status:** ✅ **Implemented**  
**Justification:** NDAs signed by employees and third parties.  
**Evidence:** NDA templates, signed copies  
**Implementation:** All employees, contractors, visitors handling sensitive data sign NDA.

#### A.6.7 Remote working
**Status:** ✅ **Implemented**  
**Justification:** Remote work security policy.  
**Evidence:** Remote work policy, VPN usage guidelines  
**Implementation:** VPN mandatory, encrypted laptops, security awareness for home workers.

#### A.6.8 Information security event reporting
**Status:** ✅ **Implemented**  
**Justification:** Reporting channels established.  
**Evidence:** Incident reporting procedure, hotline  
**Implementation:** Security incident email, phone hotline, web form, no-blame culture promoted.

---

### A.7 Physical and Environmental Security Controls (14 controls)

#### A.7.1 Physical security perimeters
**Status:** ✅ **Implemented**  
**Justification:** Facility access controls.  
**Evidence:** Physical security assessment  
**Implementation:** Fenced perimeter, security guards, access card readers.

#### A.7.2 Physical entry
**Status:** ✅ **Implemented**  
**Justification:** Controlled entry to facilities.  
**Evidence:** Access control logs  
**Implementation:** Badge access, visitor registration, escort policy for sensitive areas.

#### A.7.3 Securing offices, rooms and facilities
**Status:** ✅ **Implemented**  
**Justification:** Server room and network closets secured.  
**Evidence:** Security inspection reports  
**Implementation:** Locked server room, key card access, CCTV monitoring.

#### A.7.4 Physical security monitoring
**Status:** ✅ **Implemented**  
**Justification:** CCTV surveillance system.  
**Evidence:** CCTV system documentation  
**Implementation:** 24 cameras covering entry points and sensitive areas, 30-day retention.

#### A.7.5 Protecting against physical and environmental threats
**Status:** ✅ **Implemented**  
**Justification:** Environmental controls in data center.  
**Evidence:** Facility management procedures  
**Implementation:** Fire suppression, HVAC monitoring, UPS power, water detection.

#### A.7.6 Working in secure areas
**Status:** ✅ **Implemented**  
**Justification:** Clean desk policy for sensitive areas.  
**Evidence:** Security policy, periodic audits  
**Implementation:** Clear desk enforced in server room, production floor access controlled.

#### A.7.7 Clear desk and clear screen
**Status:** ⚠️ **Partially Implemented**  
**Justification:** Policy exists but compliance varies.  
**Evidence:** Clear desk policy  
**Implementation:** Screen lock timeouts enforced (10 min). Action: Improve compliance through audits.

#### A.7.8 Equipment siting and protection
**Status:** ✅ **Implemented**  
**Justification:** Equipment positioned to prevent unauthorized access.  
**Evidence:** Equipment layout diagrams  
**Implementation:** Servers in locked room, network equipment in secure closets.

#### A.7.9 Security of assets off-premises
**Status:** ✅ **Implemented**  
**Justification:** Laptop encryption, mobile device management.  
**Evidence:** MDM policy, encryption verification  
**Implementation:** Full disk encryption mandatory, remote wipe capability.

#### A.7.10 Storage media
**Status:** ✅ **Implemented**  
**Justification:** Media handling and disposal procedures.  
**Evidence:** Media management SOP  
**Implementation:** USB drives encrypted, backup tapes stored securely, media sanitization before disposal.

#### A.7.11 Supporting utilities
**Status:** ✅ **Implemented**  
**Justification:** Redundant power and cooling.  
**Evidence:** UPS maintenance logs, generator tests  
**Implementation:** UPS for critical systems, diesel generator, monthly generator tests.

#### A.7.12 Cabling security
**Status:** ✅ **Implemented**  
**Justification:** Network cabling protected.  
**Evidence:** Network diagrams, cable management  
**Implementation:** Cables in conduits or overhead trays, labeled, fiber optic for sensitive links.

#### A.7.13 Equipment maintenance
**Status:** ✅ **Implemented**  
**Justification:** Preventive maintenance schedule.  
**Evidence:** Maintenance contracts, service logs  
**Implementation:** Annual hardware maintenance, firmware updates, vendor support agreements.

#### A.7.14 Secure disposal or re-use of equipment
**Status:** ✅ **Implemented**  
**Justification:** Sanitization before disposal/reuse.  
**Evidence:** Asset disposal records  
**Implementation:** NIST 800-88 compliant data sanitization, certificates of destruction.

---

### A.8 Technological Controls (34 controls)

#### A.8.1 User endpoint devices
**Status:** ✅ **Implemented**  
**Justification:** Endpoint security standards.  
**Evidence:** Endpoint security policy  
**Implementation:** Antivirus mandatory, endpoint detection and response (EDR) deployed, patch management.

#### A.8.2 Privileged access rights
**Status:** ✅ **Implemented**  
**Justification:** Privileged accounts managed separately.  
**Evidence:** PAM solution configuration  
**Implementation:** Admin accounts separate from user accounts, MFA required, session recording.

#### A.8.3 Information access restriction
**Status:** ✅ **Implemented**  
**Justification:** Need-to-know access control.  
**Evidence:** Access control lists, permission matrix  
**Implementation:** RBAC implemented, least privilege, regular access reviews.

#### A.8.4 Access to source code
**Status:** ✅ **Implemented**  
**Justification:** Source code repository access controlled.  
**Evidence:** Git access logs, developer accounts  
**Implementation:** GitHub with branch protection, code review required, signed commits.

#### A.8.5 Secure authentication
**Status:** ✅ **Implemented**  
**Justification:** Strong authentication mechanisms.  
**Evidence:** Authentication policy, MFA deployment  
**Implementation:** Password complexity, MFA for all admin and remote access, SSO where possible.

#### A.8.6 Capacity management
**Status:** ✅ **Implemented**  
**Justification:** Resource monitoring and planning.  
**Evidence:** Monitoring dashboards, capacity reports  
**Implementation:** Prometheus/Grafana monitoring, quarterly capacity reviews, scaling plans.

#### A.8.7 Protection against malware
**Status:** ✅ **Implemented**  
**Justification:** Antimalware deployed across estate.  
**Evidence:** Antivirus deployment reports  
**Implementation:** Endpoint AV, email gateway filtering, web filtering, definitions auto-updated.

#### A.8.8 Management of technical vulnerabilities
**Status:** ✅ **Implemented**  
**Justification:** Vulnerability management program.  
**Evidence:** Vulnerability management SOP, scan reports  
**Implementation:** Weekly vulnerability scans, 7-day patching for critical, risk-based prioritization.

#### A.8.9 Configuration management
**Status:** ✅ **Implemented**  
**Justification:** Configuration baselines and change control.  
**Evidence:** Configuration management database (CMDB)  
**Implementation:** Infrastructure as Code, Ansible playbooks, change approval process.

#### A.8.10 Information deletion
**Status:** ✅ **Implemented**  
**Justification:** Secure deletion procedures.  
**Evidence:** Data deletion SOP  
**Implementation:** Multi-pass overwrite, cryptographic erasure, verification, GDPR right to erasure process.

#### A.8.11 Data masking
**Status:** ⚠️ **Partially Implemented**  
**Justification:** Production data masked in test environments.  
**Evidence:** Test data management procedures  
**Implementation:** Database anonymization for testing. Action: Expand masking to all non-prod environments by Q2 2026.

#### A.8.12 Data leakage prevention
**Status:** ⚠️ **Partially Implemented**  
**Justification:** Basic controls in place.  
**Evidence:** Email gateway rules, USB policies  
**Implementation:** Email DLP rules, USB ports disabled on most PCs. Action: Deploy full DLP solution.

#### A.8.13 Information backup
**Status:** ✅ **Implemented**  
**Justification:** Comprehensive backup strategy.  
**Evidence:** Backup policy, test restore logs  
**Implementation:** Daily incremental, weekly full, offsite replication, quarterly restore tests.

#### A.8.14 Redundancy of information processing facilities
**Status:** ⚠️ **Partially Implemented**  
**Justification:** Some redundancy in place.  
**Evidence:** High availability configuration  
**Implementation:** Database clustering, load balancers, cloud failover. Action: Implement full geographic redundancy.

#### A.8.15 Logging
**Status:** ✅ **Implemented**  
**Justification:** Centralized logging via SIEM.  
**Evidence:** SIEM configuration, log retention policy  
**Implementation:** All systems send logs to ELK stack, 2-year retention, tamper-proof.

#### A.8.16 Monitoring activities
**Status:** ✅ **Implemented**  
**Justification:** Security monitoring via SIEM.  
**Evidence:** SIEM dashboards, alert rules  
**Implementation:** Real-time monitoring, 15+ alert rules, 24/7 alerting to on-call team.

#### A.8.17 Clock synchronization
**Status:** ✅ **Implemented**  
**Justification:** NTP time synchronization.  
**Evidence:** NTP server configuration  
**Implementation:** All systems sync to internal NTP servers, NTP servers sync to stratum 1 sources.

#### A.8.18 Use of privileged utility programs
**Status:** ✅ **Implemented**  
**Justification:** Admin tools controlled and logged.  
**Evidence:** Privileged access logs  
**Implementation:** Admin tools restricted, usage logged, session recording for high-risk actions.

#### A.8.19 Installation of software on operational systems
**Status:** ✅ **Implemented**  
**Justification:** Software installation controlled.  
**Evidence:** Change management records  
**Implementation:** Standard user accounts can't install software, whitelist approach, change requests.

#### A.8.20 Networks security
**Status:** ✅ **Implemented**  
**Justification:** Network segmentation and security controls.  
**Evidence:** Network architecture diagrams, firewall rules  
**Implementation:** VLANs (user, server, OT, DMZ, mgmt), firewalls between segments, IDS/IPS.

#### A.8.21 Security of network services
**Status:** ✅ **Implemented**  
**Justification:** Network services hardened.  
**Evidence:** Network security policy, hardening checklists  
**Implementation:** Unnecessary services disabled, secure protocols only (SSH not Telnet), regular reviews.

#### A.8.22 Segregation of networks
**Status:** ✅ **Implemented**  
**Justification:** Network segmentation implemented.  
**Evidence:** Network diagrams showing VLANs  
**Implementation:** 5 VLANs: User (10), Server (20), OT (50), DMZ (100), Management (200).

#### A.8.23 Web filtering
**Status:** ✅ **Implemented**  
**Justification:** Web proxy with content filtering.  
**Evidence:** Proxy logs, blocked site reports  
**Implementation:** Squid proxy, category-based blocking, malware/phishing protection.

#### A.8.24 Use of cryptography
**Status:** ✅ **Implemented**  
**Justification:** Encryption policy defined.  
**Evidence:** Cryptography policy, key management procedures  
**Implementation:** TLS 1.3 for web, AES-256 for data at rest, SSH for remote access.

#### A.8.25 Secure development life cycle
**Status:** ✅ **Implemented**  
**Justification:** Security integrated into SDLC.  
**Evidence:** SDLC documentation, security gates  
**Implementation:** Threat modeling, secure coding training, SAST/DAST in CI/CD, code review.

#### A.8.26 Application security requirements
**Status:** ✅ **Implemented**  
**Justification:** Security requirements defined for applications.  
**Evidence:** Security requirements checklist  
**Implementation:** OWASP Top 10 addressed, input validation, output encoding, authentication/authorization.

#### A.8.27 Secure system architecture and engineering principles
**Status:** ✅ **Implemented**  
**Justification:** Defense in depth, least privilege.  
**Evidence:** Architecture documentation  
**Implementation:** Layered security, fail secure, separation of duties, security by design.

#### A.8.28 Secure coding
**Status:** ✅ **Implemented**  
**Justification:** Secure coding practices enforced.  
**Evidence:** Coding standards, linting rules  
**Implementation:** ESLint security rules, code review checklist, OWASP guidelines followed.

#### A.8.29 Security testing in development and acceptance
**Status:** ✅ **Implemented**  
**Justification:** Security testing mandatory before production.  
**Evidence:** CI/CD pipeline configuration, test reports  
**Implementation:** SAST (SonarQube, Semgrep), DAST (OWASP ZAP), dependency scanning, penetration testing.

#### A.8.30 Outsourced development
**Status:** ✅ **Implemented**  
**Justification:** Third-party development oversight.  
**Evidence:** Vendor contracts, code reviews  
**Implementation:** Security requirements in contracts, code escrow, security assessments, code review.

#### A.8.31 Separation of development, test and production environments
**Status:** ✅ **Implemented**  
**Justification:** Three-tier environment model.  
**Evidence:** Environment documentation, network diagrams  
**Implementation:** Dev, staging, production fully segregated. No production data in dev/test.

#### A.8.32 Change management
**Status:** ✅ **Implemented**  
**Justification:** Formal change control process.  
**Evidence:** Change management SOP, change tickets  
**Implementation:** RFC required for production changes, CAB approval, automated deployments, rollback plans.

#### A.8.33 Test information
**Status:** ✅ **Implemented**  
**Justification:** Test data protected or anonymized.  
**Evidence:** Test data management policy  
**Implementation:** Production data anonymized for testing, synthetic data generation, test data purged post-testing.

#### A.8.34 Protection of information systems during audit testing
**Status:** ✅ **Implemented**  
**Justification:** Audit testing controlled to prevent disruption.  
**Evidence:** Audit policy, audit schedules  
**Implementation:** Read-only access for auditors, testing in non-production where possible, change freeze during audits.

---

## 3. Summary Statistics

| Status | Count | Percentage |
|--------|-------|------------|
| ✅ Implemented | 87 | 93% |
| ⚠️ Partially Implemented | 6 | 7% |
| ❌ Not Implemented | 0 | 0% |
| N/A Not Applicable | 0 | 0% |
| **Total Controls** | **93** | **100%** |

---

## 4. Risk-Based Justification for Partial Implementation

### Partially Implemented Controls:

1. **A.5.12 & A.5.13** (Classification & Labelling)
   - **Risk:** Medium - Potential for data mishandling
   - **Mitigation:** Basic classification in place, sensitive data identified
   - **Action Plan:** Complete rollout by Q2 2026

2. **A.7.7** (Clear desk and screen)
   - **Risk:** Low - Physical security already strong
   - **Mitigation:** Screen locks enforced, periodic audits
   - **Action Plan:** Increase audit frequency, awareness campaigns

3. **A.8.11** (Data masking)
   - **Risk:** Medium - Test data exposure
   - **Mitigation:** Database anonymization in place
   - **Action Plan:** Extend to all non-prod environments by Q2 2026

4. **A.8.12** (Data leakage prevention)
   - **Risk:** Medium - Potential data exfiltration
   - **Mitigation:** Email rules, USB controls, monitoring
   - **Action Plan:** Deploy full DLP solution by Q3 2026

5. **A.8.14** (Redundancy)
   - **Risk:** Medium - Single point of failure
   - **Mitigation:** Local redundancy, cloud backup
   - **Action Plan:** Implement geo-redundant failover by Q4 2026

---

## 5. Continuous Improvement Plan

### Q2 2026:
- Complete data classification rollout
- Implement comprehensive data masking
- Enhance clear desk policy compliance

### Q3 2026:
- Deploy full DLP solution
- Conduct ISO 27001 pre-assessment audit

### Q4 2026:
- Implement geographic redundancy
- Complete ISO 27001 certification audit

---

## 6. Approval

**Prepared by:** CISO, Zabala Gailetak  
**Reviewed by:** IT Director, Legal Counsel, DPO  
**Approved by:** Chief Executive Officer  

**Signature:** _________________________  
**Date:** January 8, 2026

**Next Review:** July 8, 2026

---

*This document is confidential and proprietary to Zabala Gailetak S.L. Unauthorized distribution is prohibited.*
