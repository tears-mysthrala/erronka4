# Information Security Policy
## Zabala Gailetak S.A.

**Document ID:** ISP-001  
**Version:** 1.0  
**Date:** January 8, 2026  
**Classification:** Internal Use  
**Owner:** Chief Information Security Officer (CISO)  
**Review Frequency:** Annual  
**Next Review Date:** January 8, 2027

---

## 1. Document Control

### 1.1 Version History

| Version | Date | Author | Changes |
|---------|------|--------|---------|
| 1.0 | 2026-01-08 | CISO | Initial policy creation |

### 1.2 Approval

| Role | Name | Signature | Date |
|------|------|-----------|------|
| Chief Executive Officer | [Name] | | |
| Chief Information Security Officer | [Name] | | |
| Legal Counsel | [Name] | | |

### 1.3 Distribution

This policy is distributed to:
- All employees (permanent and temporary)
- Contractors and consultants
- Third-party service providers with system access
- Board of Directors (executive summary)

---

## 2. Purpose and Scope

### 2.1 Purpose

This Information Security Policy establishes the framework for protecting Zabala Gailetak's information assets, including IT systems, operational technology (OT), business data, customer information, and intellectual property. It ensures compliance with ISO/IEC 27001:2022, GDPR, and relevant Spanish legislation (LOPDGDD).

### 2.2 Scope

This policy applies to:
- All information assets owned or controlled by Zabala Gailetak
- All employees, contractors, consultants, and third parties accessing company information
- All IT systems (web applications, databases, cloud services)
- All OT systems (PLCs, SCADA, manufacturing equipment)
- Physical facilities and paper-based information
- Remote work environments and BYOD (Bring Your Own Device) scenarios

### 2.3 Exclusions

This policy does not cover:
- Personal information systems not used for business purposes
- Information systems owned by third parties where Zabala Gailetak is a client (covered under separate agreements)

---

## 3. Policy Statement

Zabala Gailetak is committed to protecting the confidentiality, integrity, and availability of its information assets. Information security is a business enabler, not a barrier, and is essential for maintaining customer trust, regulatory compliance, and competitive advantage in the cookie manufacturing industry.

### 3.1 Management Commitment

The Executive Management commits to:
- Provide adequate resources for information security
- Ensure compliance with legal and regulatory requirements
- Foster a security-aware culture throughout the organization
- Regularly review and improve the Information Security Management System (ISMS)
- Lead by example in following security policies and procedures

### 3.2 Information Security Objectives

1. **Confidentiality:** Protect sensitive information from unauthorized disclosure
2. **Integrity:** Ensure accuracy and completeness of information and systems
3. **Availability:** Ensure authorized access to information when needed (99.5% uptime target)
4. **Compliance:** Meet all legal, regulatory, and contractual obligations
5. **Resilience:** Maintain business operations during and after security incidents
6. **Continuous Improvement:** Regularly assess and enhance security posture

---

## 4. Roles and Responsibilities

### 4.1 Chief Executive Officer (CEO)

- Ultimate responsibility for information security
- Approve information security policy and budget
- Ensure business continuity and disaster recovery plans
- Report security matters to the Board of Directors

### 4.2 Chief Information Security Officer (CISO)

- Develop, implement, and maintain the ISMS
- Conduct risk assessments and security audits
- Manage security incidents and coordinate response
- Provide security awareness training
- Report security metrics to executive management
- Liaise with regulatory authorities and external auditors

### 4.3 IT Manager

- Implement technical security controls
- Manage access control and authentication systems
- Maintain system patches and updates
- Perform regular backups and recovery testing
- Monitor system logs and security events

### 4.4 OT/Production Manager

- Secure industrial control systems (PLCs, SCADA)
- Implement network segmentation between IT and OT
- Manage physical access to production areas
- Coordinate OT security with IT security teams
- Ensure safety and security in manufacturing processes

### 4.5 Data Protection Officer (DPO)

- Ensure GDPR compliance
- Handle data subject rights requests
- Maintain records of processing activities (ROPA)
- Conduct Data Protection Impact Assessments (DPIA)
- Report data breaches to AEPD (Spanish Data Protection Authority)

### 4.6 Department Managers

- Ensure employees follow security policies
- Identify and classify departmental information assets
- Report security incidents immediately
- Participate in business continuity planning
- Approve access requests for their team members

### 4.7 All Employees

- Comply with all information security policies and procedures
- Complete mandatory security awareness training
- Report security incidents and vulnerabilities
- Protect passwords and authentication credentials
- Use information assets only for authorized business purposes
- Sign and adhere to the Acceptable Use Policy

### 4.8 Third Parties and Contractors

- Sign confidentiality agreements (NDA)
- Comply with Zabala Gailetak security policies
- Undergo security vetting before system access
- Report any security concerns during engagement
- Return all information assets upon contract termination

---

## 5. Information Security Framework

### 5.1 Information Security Management System (ISMS)

Zabala Gailetak operates an ISMS based on ISO/IEC 27001:2022, implementing the Plan-Do-Check-Act (PDCA) cycle:

- **Plan:** Risk assessment, security objectives, treatment plans
- **Do:** Implement controls, policies, procedures, training
- **Check:** Internal audits, monitoring, metrics, management review
- **Act:** Corrective actions, continuous improvement, lessons learned

### 5.2 Risk Management

Risk assessment is conducted annually or when significant changes occur:

1. **Asset Identification:** Inventory of information assets (see Asset Register)
2. **Threat Assessment:** Identify potential threats (cyberattacks, natural disasters, human error)
3. **Vulnerability Analysis:** Assess weaknesses in systems and processes
4. **Risk Evaluation:** Calculate risk level (Likelihood × Impact)
5. **Risk Treatment:** Accept, mitigate, transfer, or avoid risks
6. **Residual Risk:** Document and approve remaining risks

**Risk Appetite:** Zabala Gailetak accepts low and medium risks with appropriate controls. High risks require executive approval and mitigation plans.

### 5.3 Security Controls

Security controls are selected from ISO/IEC 27001:2022 Annex A (see Statement of Applicability). Controls are categorized as:

- **Preventive:** Stop security incidents before they occur (firewalls, access control)
- **Detective:** Identify security incidents when they occur (SIEM, IDS, audits)
- **Corrective:** Minimize impact and recover from incidents (backups, incident response)

---

## 6. Key Security Policies

This overarching Information Security Policy is supported by specific policies and procedures:

### 6.1 Access Control Policy

- **Principle of Least Privilege:** Users receive minimum access needed for job functions
- **Segregation of Duties:** Critical functions require multiple persons
- **User Account Management:** Provisioning, modification, deprovisioning procedures
- **Password Policy:** See dedicated Password Policy document
- **Multi-Factor Authentication (MFA):** Required for remote access and privileged accounts
- **Access Review:** Quarterly review of user access rights

### 6.2 Acceptable Use Policy

- See dedicated Acceptable Use Policy document
- Covers proper use of IT resources, email, internet, and mobile devices
- Prohibits unauthorized software, illegal activities, and personal use beyond reasonable limits

### 6.3 Data Classification and Handling

Information is classified into four levels:

| Classification | Definition | Examples | Handling Requirements |
|----------------|------------|----------|----------------------|
| **Public** | Information intended for public disclosure | Marketing materials, press releases | No special restrictions |
| **Internal** | Information for internal use only | Internal memos, meeting notes | Email encryption, internal networks only |
| **Confidential** | Sensitive business information | Customer data, financial reports, recipes | Encryption at rest and in transit, access control |
| **Highly Confidential** | Critical information causing severe damage if disclosed | Trade secrets, PII, payment card data | Strong encryption, MFA, audit logging, DLP |

**Handling Requirements:**
- Label all documents with appropriate classification
- Encrypt Confidential and Highly Confidential data
- Use secure file transfer for external sharing
- Shred physical documents containing sensitive data
- Report data breaches within 1 hour of discovery

### 6.4 Data Protection and Privacy Policy

- Compliance with GDPR (Regulation EU 2016/679) and LOPDGDD (Spanish Organic Law 3/2018)
- Lawful basis for processing: consent, contract, legal obligation, legitimate interest
- Data minimization: collect only necessary data
- Purpose limitation: use data only for stated purposes
- Data subject rights: access, rectification, erasure, portability, objection (see Data Subject Rights Procedures)
- Data retention: see Data Retention Schedule
- International transfers: use Standard Contractual Clauses (SCC) for non-EU transfers
- Data breach notification: report to AEPD within 72 hours if risk to individuals

### 6.5 Network Security Policy

**IT Network:**
- Firewall protection on all network perimeters
- Network segmentation: production, office, guest WiFi, DMZ
- Intrusion Detection/Prevention Systems (IDS/IPS)
- VPN required for remote access (AES-256 encryption)
- Disable unused network services and ports
- Regular vulnerability scanning (monthly)

**OT Network:**
- Air-gap or strict firewall rules between IT and OT networks
- No direct internet access for OT systems
- Whitelisting approach for OT communications
- Physical access control to OT network equipment
- Change management for all OT network modifications

**WiFi Security:**
- WPA3 encryption for corporate WiFi
- Separate guest WiFi with captive portal and internet-only access
- MAC address filtering for OT wireless devices
- Regular rogue access point scanning

### 6.6 System Development and Maintenance Policy

- Secure Software Development Lifecycle (SSDLC)
- Development, testing, and production environments separated
- Code review and static/dynamic security testing
- Dependency scanning for vulnerabilities (OWASP Dependency-Check)
- Change management process for all system modifications
- Security testing before production deployment
- Vendor security assessment for third-party software

### 6.7 Backup and Recovery Policy

- **Backup Frequency:**
  - Critical systems: Daily incremental, weekly full
  - Databases: Real-time replication + daily backups
  - OT configurations: Before and after changes
- **Backup Storage:** Encrypted, off-site storage (3-2-1 rule: 3 copies, 2 media types, 1 off-site)
- **Retention:** 30 days online, 1 year archive
- **Recovery Testing:** Quarterly recovery drills
- **Recovery Time Objective (RTO):** 4 hours for critical systems
- **Recovery Point Objective (RPO):** 1 hour data loss maximum

### 6.8 Incident Management Policy

- **Incident Definition:** Any event that threatens confidentiality, integrity, or availability
- **Reporting:** All incidents reported within 1 hour via security@zabalagailetak.com or incident hotline
- **Classification:** Critical, High, Medium, Low based on impact and urgency
- **Response Team:** CISO, IT Manager, Legal, DPO, PR (for critical incidents)
- **Response Process:**
  1. Detection and reporting
  2. Initial assessment and containment
  3. Investigation and evidence collection
  4. Eradication and recovery
  5. Post-incident review and lessons learned
- **Communication:** Notify affected parties, regulators (if required), and management
- **Documentation:** Maintain incident log and forensic evidence (see Audit Log)

### 6.9 Business Continuity and Disaster Recovery Policy

- See dedicated Business Continuity Plan document
- Business Impact Analysis (BIA) conducted annually
- Maximum Tolerable Period of Disruption (MTPD): 24 hours for critical processes
- Disaster recovery procedures for IT and OT systems
- Alternative work arrangements (remote work, backup facilities)
- Annual tabletop exercises and biennial full-scale drills

### 6.10 Physical and Environmental Security Policy

**Data Center and Server Room:**
- Access control (badge + biometric)
- 24/7 video surveillance (90-day retention)
- Environmental monitoring (temperature, humidity)
- Fire suppression system (FM-200 or equivalent)
- Uninterruptible Power Supply (UPS) and backup generator

**Production Facilities:**
- Perimeter security (fencing, lighting, cameras)
- Visitor management (sign-in, escorts)
- Secure disposal of waste containing sensitive information
- Clear desk and clear screen policy

**Mobile Devices and Laptops:**
- Full disk encryption (BitLocker, FileVault)
- Mobile Device Management (MDM) for company devices
- Remote wipe capability for lost/stolen devices
- Automatic screen lock after 5 minutes

### 6.11 Third-Party and Supplier Security Policy

- Security assessment before engagement
- Contractual security requirements in all agreements
- Non-Disclosure Agreements (NDA) for access to Confidential data
- Regular security reviews for critical suppliers
- Right to audit supplier security controls
- Termination procedures for access and data return

### 6.12 Cryptographic Controls Policy

- **Encryption Standards:**
  - Data at rest: AES-256
  - Data in transit: TLS 1.3 or higher
  - VPN: AES-256 with IKEv2/IPsec
- **Key Management:**
  - Centralized key management system
  - Key rotation every 12 months
  - Secure key storage (HSM for critical keys)
  - Key escrow for business continuity
- **Digital Signatures:** RSA 2048-bit or ECDSA P-256
- **Hashing:** SHA-256 or higher (no MD5 or SHA-1)
- **Password Storage:** bcrypt with 12+ rounds or Argon2

### 6.13 Security Monitoring and Logging Policy

- **Logging Requirements:** Authentication, access control, system changes, security events
- **Log Retention:** 1 year minimum (legal requirement), 3 years for critical systems
- **SIEM (Security Information and Event Management):** Real-time log correlation and alerting
- **Log Protection:** Tamper-proof, encrypted, access-controlled
- **Review Frequency:** Real-time automated analysis, weekly manual review
- **Alerts:** Critical alerts to security team 24/7

---

## 7. Compliance and Legal Requirements

### 7.1 Regulatory Compliance

Zabala Gailetak complies with:

- **GDPR (EU 2016/679):** General Data Protection Regulation
- **LOPDGDD (Spain Organic Law 3/2018):** Spanish data protection law
- **ePrivacy Directive (2002/58/EC):** Electronic communications privacy
- **PCI DSS:** Payment Card Industry Data Security Standard (if processing card payments)
- **ISO/IEC 27001:2022:** Information Security Management certification target
- **Spanish Penal Code (Article 197):** Unlawful disclosure of personal data
- **Spanish Workers' Statute:** Employee privacy and monitoring
- **Food Safety Regulations:** GMP, HACCP (indirectly affects data integrity)

### 7.2 Contractual Obligations

- Customer data protection clauses in sales agreements
- Supplier security requirements in procurement contracts
- Insurance policy compliance (cyber insurance requirements)
- Bank and payment processor security standards

### 7.3 Intellectual Property Protection

- Trade secret protection for cookie recipes and manufacturing processes
- Copyright protection for software, documentation, marketing materials
- Trademark protection for Zabala Gailetak brand
- Patent protection for innovative manufacturing techniques (if applicable)

---

## 8. Security Awareness and Training

### 8.1 Training Program

All employees receive:

- **New Hire Training:** Security basics within first week
- **Annual Refresher Training:** Mandatory for all employees (2 hours)
- **Role-Specific Training:**
  - Developers: Secure coding, OWASP Top 10 (8 hours annually)
  - System Administrators: Hardening, monitoring, incident response (16 hours annually)
  - Management: Risk management, legal obligations (4 hours annually)
  - Production Staff: OT security, physical security (4 hours annually)
- **Phishing Simulations:** Quarterly simulated phishing campaigns
- **Security Newsletter:** Monthly security tips and threat updates

### 8.2 Training Topics

- Password security and MFA
- Phishing and social engineering
- Data classification and handling
- Acceptable use of IT resources
- Incident reporting procedures
- GDPR and data protection
- Physical security and clean desk
- Secure remote work practices

### 8.3 Training Metrics

- Training completion rate: 100% target
- Phishing simulation click rate: <10% target
- Incident reporting response time: <1 hour target
- Training effectiveness assessment through quizzes and practical exercises

---

## 9. Monitoring and Review

### 9.1 Performance Measurement

Key Performance Indicators (KPIs):

| KPI | Target | Measurement Frequency |
|-----|--------|----------------------|
| System availability | 99.5% | Monthly |
| Security incidents | <10 medium/year | Quarterly |
| Patch compliance | 95% critical patches within 7 days | Monthly |
| Access review completion | 100% quarterly | Quarterly |
| Training completion | 100% annually | Annually |
| Backup success rate | 99% | Daily |
| Phishing simulation click rate | <10% | Quarterly |
| Mean Time to Detect (MTTD) | <2 hours | Per incident |
| Mean Time to Respond (MTTR) | <4 hours | Per incident |

### 9.2 Internal Audits

- Annual internal audit of ISMS against ISO 27001
- Quarterly technical vulnerability assessments
- Biannual penetration testing by external auditors
- Audit findings tracked and remediated within agreed timelines

### 9.3 Management Review

- Quarterly security metrics presented to executive management
- Annual ISMS review by CEO and senior management
- Review agenda:
  - Audit results and corrective actions
  - Security incidents and trends
  - Performance against KPIs
  - Risk assessment updates
  - Changes in legal/regulatory requirements
  - Resource requirements and budget
  - Continuous improvement opportunities

---

## 10. Policy Enforcement

### 10.1 Compliance Monitoring

- Security team monitors policy compliance through:
  - Automated security tools (SIEM, DLP, endpoint protection)
  - Access control logs and audit trails
  - Regular access reviews
  - Spot checks and inspections
  - Employee reporting and whistleblowing mechanisms

### 10.2 Violations and Disciplinary Action

Violations of this policy may result in:

1. **First Violation (Minor):** Verbal warning and retraining
2. **Second Violation or Serious Violation:** Written warning and performance improvement plan
3. **Severe or Repeated Violations:** Suspension, termination, or legal action

**Examples of Violations:**
- Minor: Weak password, unattended unlocked workstation
- Serious: Sharing passwords, unauthorized software installation, policy non-compliance after warning
- Severe: Intentional data breach, malicious activity, fraud, theft of information

All disciplinary actions comply with Spanish labor law and employment contracts.

### 10.3 Exceptions and Waivers

- Policy exceptions require written approval from CISO and relevant department manager
- Exceptions are time-limited (maximum 6 months) and documented
- Compensating controls must be implemented
- Exceptions reviewed quarterly

---

## 11. Policy Maintenance

### 11.1 Review and Updates

- **Annual Review:** Policy reviewed every January
- **Ad-hoc Updates:** When significant changes occur:
  - New legal requirements
  - Major security incidents
  - Organizational changes
  - Technology changes
  - Audit findings requiring policy updates

### 11.2 Change Management

Policy changes follow this process:
1. Draft updates by CISO or policy owner
2. Review by Legal, DPO, IT Manager
3. Approval by CEO
4. Communication to all employees
5. Training updates if required
6. Version control and archiving

### 11.3 Document Storage

- Master copy stored in ISMS document repository
- Version-controlled and access-restricted
- Archived versions retained for 10 years (legal requirement)

---

## 12. Related Documents

- Statement of Applicability (SOA) - Document ID: SOA-001
- Acceptable Use Policy - Document ID: AUP-001
- Password Policy - Document ID: PWD-001
- Business Continuity Plan - Document ID: BCP-001
- Asset Register - Document ID: ASR-001
- Risk Assessment Report - Document ID: RAR-001
- Incident Response Procedure - Document ID: IRP-001
- Data Protection Impact Assessment Template - Document ID: DPIA-001
- Data Breach Notification Template - Document ID: DBN-001
- Data Subject Rights Procedures - Document ID: DSR-001

---

## 13. Contact Information

### 13.1 Security Team Contacts

**Chief Information Security Officer (CISO)**  
Email: ciso@zabalagailetak.com  
Phone: +34 XXX XXX XXX  
Office: Building A, Room 201

**IT Security Team**  
Email: security@zabalagailetak.com  
Phone: +34 XXX XXX XXX (24/7 Security Hotline)

**Data Protection Officer (DPO)**  
Email: dpo@zabalagailetak.com  
Phone: +34 XXX XXX XXX

**Incident Reporting**  
Email: incident@zabalagailetak.com  
Phone: +34 XXX XXX XXX (24/7)  
Internal Extension: 911

### 13.2 External Contacts

**Spanish Data Protection Authority (AEPD)**  
Website: www.aepd.es  
Phone: +34 901 100 099  
Address: C/ Jorge Juan, 6, 28001 Madrid

**INCIBE (National Cybersecurity Institute)**  
Phone: +34 017 (24/7 cybersecurity incidents)  
Website: www.incibe.es

---

## 14. Acknowledgment

All employees must acknowledge receipt and understanding of this policy:

**I acknowledge that I have received, read, and understood the Zabala Gailetak Information Security Policy. I agree to comply with all requirements and understand that violations may result in disciplinary action, up to and including termination of employment or contract.**

---

**Employee Name:** ___________________________

**Employee ID:** ___________________________

**Signature:** ___________________________

**Date:** ___________________________

---

## Appendix A: Definitions and Acronyms

**Information Asset:** Any data, system, or resource of value to the organization

**Information Security:** Protection of information from unauthorized access, use, disclosure, disruption, modification, or destruction

**Confidentiality:** Ensuring information is accessible only to authorized individuals

**Integrity:** Ensuring accuracy and completeness of information

**Availability:** Ensuring authorized access to information when needed

**CIA Triad:** Confidentiality, Integrity, Availability - the three pillars of information security

**ISMS:** Information Security Management System - systematic approach to managing information security

**Risk:** Potential for loss or damage when a threat exploits a vulnerability

**Threat:** Potential cause of an unwanted incident

**Vulnerability:** Weakness that can be exploited by a threat

**Control:** Measure that modifies risk

**PII:** Personally Identifiable Information

**GDPR:** General Data Protection Regulation

**AEPD:** Agencia Española de Protección de Datos (Spanish Data Protection Authority)

**DPO:** Data Protection Officer

**SIEM:** Security Information and Event Management

**IDS/IPS:** Intrusion Detection/Prevention System

**MFA:** Multi-Factor Authentication

**VPN:** Virtual Private Network

**DMZ:** Demilitarized Zone (network segment)

**OT:** Operational Technology (industrial control systems)

**PLC:** Programmable Logic Controller

**SCADA:** Supervisory Control and Data Acquisition

**RTO:** Recovery Time Objective

**RPO:** Recovery Point Objective

**MTPD:** Maximum Tolerable Period of Disruption

**BIA:** Business Impact Analysis

**DLP:** Data Loss Prevention

**MDM:** Mobile Device Management

**HSM:** Hardware Security Module

**OWASP:** Open Web Application Security Project

**PCI DSS:** Payment Card Industry Data Security Standard

---

**END OF DOCUMENT**
