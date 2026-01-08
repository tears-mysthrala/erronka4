# Data Retention Schedule
## Zabala Gailetak S.A. - GDPR Compliance

**Document ID:** DRS-001  
**Version:** 1.0  
**Date:** January 8, 2026  
**Classification:** Internal Use  
**Owner:** Data Protection Officer (DPO)  
**Review Frequency:** Annual  
**Next Review Date:** January 8, 2027

---

## 1. Document Control

### 1.1 Version History

| Version | Date | Author | Changes |
|---------|------|--------|---------|
| 1.0 | 2026-01-08 | DPO | Initial retention schedule creation |

### 1.2 Purpose

This Data Retention Schedule establishes how long Zabala Gailetak retains different categories of personal data and the criteria for determining retention periods. It ensures compliance with:
- **GDPR Article 5(1)(e):** Storage limitation principle (data kept no longer than necessary)
- **GDPR Article 5(2):** Accountability principle (demonstrate compliance)
- **LOPDGDD:** Spanish data protection requirements
- **Spanish legal obligations:** Tax, accounting, labor, and commercial law

### 1.3 Scope

This schedule applies to all personal data processed by Zabala Gailetak, including:
- Customer data
- Employee data
- Supplier and partner data
- Website visitor data
- Marketing and communication data

---

## 2. Retention Principles

### 2.1 Legal Requirements

**GDPR Storage Limitation (Article 5(1)(e)):**
Personal data shall be kept in a form which permits identification of data subjects for no longer than is necessary for the purposes for which the personal data are processed.

**Exceptions:**
- Archiving in the public interest, scientific or historical research, or statistical purposes (with appropriate safeguards)
- Longer retention where required by law

### 2.2 Retention Criteria

Retention periods are determined based on:

1. **Purpose:** How long is data needed for the original processing purpose?
2. **Legal Obligation:** Are we legally required to retain the data?
3. **Limitation Periods:** How long could legal claims arise? (statute of limitations)
4. **Business Need:** Is there a legitimate business reason for retention?
5. **Data Subject Expectations:** What would data subjects reasonably expect?
6. **Data Sensitivity:** More sensitive data requires stronger justification for extended retention

**Balancing Factors:**
- Shorter retention periods for sensitive or high-risk data
- Minimum retention to comply with legal obligations
- Clear deletion procedures when retention period expires

### 2.3 Retention Period Triggers

**Event-Based Triggers:**
- Contract termination
- Account closure
- Employment termination
- Order completion
- Consent withdrawal
- Service completion

**Time-Based Triggers:**
- Fixed period from data collection
- Fixed period from last activity
- Fixed period from event trigger

### 2.4 Legal Hold Exception

**Override:** If data is subject to legal proceedings, regulatory investigation, or audit, retention period is suspended until matter is resolved (legal hold).

**Process:**
1. Legal Counsel identifies data subject to legal hold
2. IT implements technical hold (data flagged, deletion disabled)
3. Affected data owners notified
4. Legal hold removed only after Legal Counsel approval
5. Normal retention schedule resumes

---

## 3. Customer Data Retention

### 3.1 Account and Profile Data

| Data Category | Retention Period | Legal Basis | Trigger | Deletion Method |
|---------------|------------------|-------------|---------|-----------------|
| **Active Customer Account** | Until account deletion requested or 2 years of inactivity | Contract, Legitimate Interests | Account closure or 2 years no login | Hard delete from production database |
| **Name, Email, Phone** | 2 years after last activity | Contract, Legitimate Interests | Last purchase, login, or contact | Hard delete |
| **Billing/Shipping Address** | 7 years after last order (tied to transaction records) | Legal Obligation (tax/accounting) | Last order date | Archived, then deleted |
| **Password (hashed)** | Until account deletion | Contract | Account closure | Securely wiped |
| **Account Preferences** | Until account deletion or 2 years inactivity | Legitimate Interests | Account closure or inactivity | Hard delete |
| **Inactive Accounts** | Automatically deleted after 2 years of no login | Data minimization | Last login date | Automated deletion script |

**Notification:** Customers notified 60 days before deletion of inactive accounts (via email to last known address).

**Exception:** If customer has unfulfilled obligations (unpaid invoices, pending returns), account retained until resolved.

### 3.2 Transaction and Order Data

| Data Category | Retention Period | Legal Basis | Legal Reference | Deletion Method |
|---------------|------------------|-------------|-----------------|-----------------|
| **Order Details** (order ID, products, quantities, prices) | **7 years** | Legal Obligation | Spanish General Tax Law (Art. 29) | Archived (restricted access), then deleted |
| **Invoices** | **7 years** | Legal Obligation | Spanish Commercial Code (Art. 30), VAT Law | Archived (paper and electronic) |
| **Payment Records** | **7 years** | Legal Obligation | Accounting regulations | Archived |
| **Delivery Information** | 7 years (tied to orders) | Legal Obligation | Tied to invoice retention | Archived, then deleted |
| **Returns and Refunds** | 7 years | Legal Obligation, Legal Claims | Warranty claims, consumer protection | Archived, then deleted |
| **Order Communications** | 3 years | Legal Claims | Customer disputes | Deleted after period |

**7-Year Rule:** Spanish law requires retention of accounting and tax records for 7 years from the end of the tax year. This is the primary driver for transaction data retention.

**Calculation:** For an order placed on March 15, 2025 (tax year 2025), retention period ends December 31, 2032 (7 years after end of tax year 2025).

**Archive:** After 1 year, order data moved to archive storage with restricted access (only for legal/audit purposes).

### 3.3 Payment Information

| Data Category | Retention Period | Legal Basis | Notes |
|---------------|------------------|-------------|-------|
| **Credit/Debit Card Numbers** | **Not stored** | Security best practice | Processed by payment provider (Stripe), we never store full card numbers |
| **Last 4 Digits of Card** | Until account deletion or 2 years inactivity | Legitimate Interests | For order identification only |
| **Payment Method Type** | 7 years (tied to transaction records) | Legal Obligation | Part of accounting records |
| **Payment Transaction ID** | 7 years | Legal Obligation | Link to payment provider records |
| **PCI DSS Compliance** | Not applicable | N/A | We do not handle card data directly (outsourced to PCI-compliant provider) |

**Security:** Payment card data handled exclusively by PCI DSS Level 1 compliant payment processor (Stripe). We do not store, process, or transmit full card numbers.

### 3.4 Customer Service and Support Data

| Data Category | Retention Period | Legal Basis | Trigger | Deletion Method |
|---------------|------------------|-------------|---------|-----------------|
| **Support Tickets** | 3 years after closure | Legitimate Interests, Legal Claims | Ticket closed date | Hard delete |
| **Email Communications** | 3 years after last communication | Legitimate Interests, Legal Claims | Last email date | Hard delete |
| **Phone Call Recordings** | 90 days | Consent, Legitimate Interests | Call date | Automated deletion |
| **Chat Transcripts** | 1 year | Legitimate Interests | Chat date | Hard delete |
| **Complaint Records** | 5 years | Legal Claims | Complaint date | Hard delete |

**Rationale:** 3-year retention aligns with statute of limitations for consumer protection claims in Spain.

**Call Recordings:** If recorded, customers are notified at start of call and can opt out. Used for quality assurance and training.

---

## 4. Marketing and Communication Data

### 4.1 Marketing Consent and Preferences

| Data Category | Retention Period | Legal Basis | Trigger | Deletion Method |
|---------------|------------------|-------------|---------|-----------------|
| **Email Marketing List** | Until consent withdrawn or 2 years of no engagement | Consent | Unsubscribe or 2 years no opens/clicks | Hard delete from marketing platform |
| **Marketing Consent Records** | 3 years after consent withdrawn | Accountability (prove consent was obtained) | Consent withdrawal date | Hard delete |
| **Marketing Preferences** | Until consent withdrawn | Consent | Unsubscribe | Hard delete |
| **Suppression List** (unsubscribed emails) | **Indefinitely** | Legitimate Interests | N/A | Retained to prevent re-adding |
| **Email Campaign History** | 2 years | Legitimate Interests | Campaign sent date | Aggregated, then deleted |

**Inactive Subscribers:** If subscriber has not opened or clicked any email in 2 years, we send re-engagement campaign. If no response, consent considered withdrawn and data deleted.

**Suppression List:** Unsubscribed email addresses retained permanently in suppression list to ensure we never re-add them (even if they re-register for an account). Only email address retained, no other data.

### 4.2 Marketing Campaign Data

| Data Category | Retention Period | Legal Basis | Deletion Method |
|---------------|------------------|-------------|-----------------|
| **Campaign Performance Metrics** | 2 years | Legitimate Interests | Deleted after 2 years |
| **A/B Test Results** | 1 year | Legitimate Interests | Deleted after analysis complete |
| **Individual Engagement Data** (opens, clicks) | Aggregated after 2 years | Legitimate Interests | Aggregated (anonymized), raw data deleted |

---

## 5. Employee Data Retention

### 5.1 Current Employees

| Data Category | Retention Period | Legal Basis | Deletion Method |
|---------------|------------------|-------------|-----------------|
| **Personnel File** (application, CV, offer letter, contract) | Duration of employment + 4 years | Legal Obligation, Legal Claims | Securely shredded (paper), deleted (electronic) |
| **Payroll Records** | Duration of employment + **7 years** | Legal Obligation | Spanish Labor Law, Tax Law | Archived |
| **Time and Attendance** | Duration of employment + 4 years | Legal Obligation | Spanish Labor Law | Archived, then deleted |
| **Performance Reviews** | Duration of employment + 4 years | Legal Claims | Labor disputes | Deleted |
| **Disciplinary Records** | Duration of employment + 4 years | Legal Claims | Labor disputes | Deleted |
| **Training Records** | Duration of employment + 4 years | Legitimate Interests | Skills verification | Deleted |
| **Health and Safety Records** | Duration of employment + **40 years** | Legal Obligation | Spanish health and safety regulations (long latency occupational diseases) | Archived long-term |

**Rationale:** Spanish labor law requires retention of employment records for 4 years after termination for potential labor disputes. Payroll records retained 7 years per tax law. Health/safety records retained 40 years due to long latency periods for occupational diseases.

### 5.2 Former Employees

| Data Category | Retention Period | Legal Basis | Trigger | Deletion Method |
|---------------|------------------|-------------|---------|-----------------|
| **Basic Employment Records** | 4 years after termination | Legal Claims | Termination date | Securely deleted |
| **Payroll and Tax Records** | 7 years after termination | Legal Obligation | Tax year + 7 years | Archived, then deleted |
| **Reference Requests** | 2 years after termination | Legitimate Interests | Termination date | Deleted |
| **Final Settlements** | 7 years | Legal Obligation | Tax retention | Archived, then deleted |
| **Exit Interview Notes** | 1 year | Legitimate Interests | Termination date | Deleted |

**Calculation Example:** Employee terminates on June 15, 2025. Basic records retained until June 15, 2029. Payroll records retained until December 31, 2032 (7 years after tax year 2025).

### 5.3 Job Applicants (Not Hired)

| Data Category | Retention Period | Legal Basis | Trigger | Deletion Method |
|---------------|------------------|-------------|---------|-----------------|
| **CVs and Application Materials** | 6 months after recruitment process ends | Consent, Legitimate Interests | Recruitment close date | Securely shredded/deleted |
| **Interview Notes** | 6 months | Legitimate Interests, Legal Claims | Recruitment close date | Securely shredded/deleted |
| **Assessment Results** | 6 months | Legitimate Interests, Legal Claims | Recruitment close date | Deleted |
| **Diversity Monitoring Data** | Aggregated immediately, individual data deleted | Legal Obligation (equality monitoring) | Immediately after use | Aggregated (anonymized) |

**Rationale:** 6-month retention allows us to defend against discrimination claims while respecting data minimization.

**Consent:** If applicant consents to be kept on file for future positions, data retained up to 2 years with annual consent renewal.

---

## 6. Supplier and Partner Data

### 6.1 Supplier and Contractor Records

| Data Category | Retention Period | Legal Basis | Trigger | Deletion Method |
|---------------|------------------|-------------|---------|-----------------|
| **Supplier Contact Information** | Duration of relationship + 2 years | Contract, Legitimate Interests | Contract end date | Deleted |
| **Contracts and Agreements** | Duration + 7 years | Legal Obligation, Legal Claims | Contract end + accounting retention | Archived, then deleted |
| **Invoices and Payment Records** | **7 years** | Legal Obligation | Tax year + 7 years | Archived, then deleted |
| **Purchase Orders** | 7 years | Legal Obligation | Accounting retention | Archived, then deleted |
| **Performance Evaluations** | Duration + 2 years | Legitimate Interests | Contract end | Deleted |
| **Due Diligence Records** | Duration + 3 years | Legal Claims, Compliance | Contract end | Deleted |

**Rationale:** 7-year accounting retention applies to supplier transactions. Additional retention justified by potential contract disputes (6-year statute of limitations in Spain).

---

## 7. Website and Analytics Data

### 7.1 Website Usage Data

| Data Category | Retention Period | Legal Basis | Deletion Method |
|---------------|------------------|-------------|-----------------|
| **Web Server Logs** (IP addresses, user agent, pages visited) | 1 year | Legitimate Interests (security, debugging) | Automated deletion |
| **Analytics Data** (Google Analytics, Matomo) | 26 months (then aggregated) | Legitimate Interests, Consent | Aggregated (anonymized), raw data deleted |
| **Cookies** | See Cookie Policy | Varies by cookie type | See Cookie Policy |
| **Heatmaps and Session Recordings** | 6 months | Consent | Automated deletion |
| **Search Query History** | Aggregated after 90 days | Legitimate Interests | Aggregated (anonymized) |

**IP Anonymization:** IP addresses anonymized (last octet masked) in analytics to reduce personal data scope.

**Aggregation:** After retention period, data aggregated for statistical purposes (cannot identify individuals).

### 7.2 Security and Audit Logs

| Data Category | Retention Period | Legal Basis | Deletion Method |
|---------------|------------------|-------------|-----------------|
| **Authentication Logs** (login attempts, IP addresses) | 1 year | Legitimate Interests (security) | Automated deletion |
| **Security Incident Logs** | 3 years | Legitimate Interests, Legal Claims | Deleted after 3 years |
| **Audit Logs** (access to personal data) | 1 year | Accountability (GDPR Article 5(2)) | Automated deletion |
| **Backup Logs** | 1 year | Legitimate Interests | Automated deletion |
| **Firewall and IDS Logs** | 1 year | Legitimate Interests (security) | Automated deletion |

**Exception:** Logs related to data breaches or security incidents retained for duration of incident investigation + 3 years.

---

## 8. Backup Data Retention

### 8.1 Backup Strategy

**Backup Types:**
- **Daily Incremental:** Changes since last backup
- **Weekly Full:** Complete data snapshot
- **Monthly Archive:** Long-term storage

**Retention:**
- **Daily Backups:** 30 days
- **Weekly Backups:** 12 weeks (3 months)
- **Monthly Backups:** 12 months
- **Annual Backups:** 7 years (for accounting data only)

### 8.2 Personal Data in Backups

**Challenge:** Backups may contain personal data that should be deleted per retention schedule, but backups are not practical to edit.

**Approach:**
1. **Production Deletion:** Delete data from production systems per retention schedule
2. **Backup Retention:** Data remains in backups for backup retention period (30-90 days)
3. **Backup Rotation:** Old backups automatically deleted, removing outdated data
4. **Acceptable Delay:** Up to 90 days between production deletion and backup deletion is acceptable for business continuity purposes

**Documentation:** We document that deleted data may persist in backups for up to 90 days and inform data subjects in erasure responses.

**No Restoration:** Deleted data in backups is not restored to production unless business continuity emergency requires full system restoration.

---

## 9. Data Anonymization and Pseudonymization

### 9.1 Anonymization

**Definition:** Data processed so it can no longer identify individuals (irreversible).

**Use Cases:**
- Analytics and reporting beyond retention period
- Research and statistics
- Historical records

**Techniques:**
- Aggregation (e.g., "100 orders placed" instead of individual order details)
- Data masking (removing direct identifiers)
- Generalization (e.g., age ranges instead of exact age)

**GDPR Status:** Anonymized data is no longer personal data and not subject to GDPR (but must be truly irreversible).

### 9.2 Pseudonymization

**Definition:** Data processed so it cannot identify individuals without additional information kept separately (reversible with key).

**Use Cases:**
- Analytics with ability to link back to individuals if needed (e.g., customer ID instead of name)
- Testing and development environments
- Research where re-identification may be needed

**Security:** Pseudonymization keys stored separately with strict access controls.

**GDPR Status:** Pseudonymized data is still personal data and subject to GDPR, but considered a security measure that may extend retention periods where justified.

---

## 10. Deletion Procedures

### 10.1 Deletion Methods

**Secure Deletion Standards:**
- Follow NIST SP 800-88 guidelines for media sanitization
- Appropriate method based on storage type and sensitivity

| Storage Type | Deletion Method | Standard |
|--------------|-----------------|----------|
| **Hard Disk Drives (HDD)** | 3-pass overwrite (DoD 5220.22-M) or physical destruction | NIST SP 800-88 |
| **Solid State Drives (SSD)** | ATA Secure Erase or cryptographic erasure (destroy encryption key) | NIST SP 800-88 |
| **Database Records** | Hard delete (DELETE statement, not just flag) + vacuum | Database best practice |
| **Backup Tapes** | Degaussing or physical destruction (shredding, incineration) | NIST SP 800-88 |
| **Paper Documents** | Cross-cut shredding (minimum DIN P-4 for confidential data) | ISO 21964 |
| **Cloud Storage** | Provider deletion + verification | Cloud provider standards |

**Soft Delete vs. Hard Delete:**
- **Soft Delete:** Mark record as deleted but data remains (NOT sufficient for GDPR compliance)
- **Hard Delete:** Permanently remove data from database (required for retention schedule compliance)

### 10.2 Deletion Verification

**Process:**
1. **Deletion Request:** Triggered by retention period expiration, data subject request, or manual review
2. **Approval:** Verified no legal hold or exception applies
3. **Execution:** Deletion script/procedure executed
4. **Verification:** Confirm data no longer present in systems
5. **Logging:** Document what was deleted, when, by whom, and method used
6. **Certificate:** For sensitive data, certificate of destruction issued

**Audit Trail:** All deletions logged in secure, tamper-proof audit log retained for 3 years.

### 10.3 Automated Deletion

**Automated Processes:**
- Inactive customer accounts: Script runs monthly, deletes accounts with no activity for 2+ years
- Expired cookies: Browser automatic deletion
- Old backups: Automated rotation deletes backups older than retention period
- Security logs: Automated deletion after 1 year

**Manual Review Required:**
- Employee data (verify no legal hold)
- Data subject erasure requests (identity verification, legal basis check)
- Data tied to ongoing contracts or legal claims

---

## 11. Exceptions and Extensions

### 11.1 Legal Hold

**Trigger:** Legal proceedings, regulatory investigation, audit, or potential litigation

**Process:**
1. Legal Counsel identifies data subject to hold
2. IT implements technical hold (deletion disabled)
3. Data owners notified
4. Hold documented with reason and scope
5. Regular review of active holds
6. Hold released only by Legal Counsel

**Duration:** Until legal matter resolved + any appeal periods

### 11.2 Retention Extensions

**Grounds for Extension:**
- New legal obligation requires longer retention
- Ongoing legal claims or disputes
- Regulatory investigation
- Data subject consents to longer retention
- Archiving in public interest, scientific/historical research (with safeguards)

**Approval:** DPO and Legal Counsel must approve extensions beyond standard retention periods.

**Documentation:** Justification, new retention period, and safeguards documented.

### 11.3 Shortened Retention (Data Subject Request)

**Data Subject Rights:**
- Right to erasure (Article 17) may require deletion before retention period expires
- Right to restriction (Article 18) may limit processing during retention period

**Assessment:** Evaluate erasure request against exceptions (legal obligation, legal claims, etc.).

**Outcome:**
- **Granted:** Delete data before standard retention period
- **Refused:** Explain legal obligation or exception requiring continued retention

---

## 12. Monitoring and Compliance

### 12.1 Retention Schedule Reviews

**Annual Review:**
- DPO reviews entire retention schedule
- Update for new data types, legal changes, business changes
- Verify deletion processes functioning correctly

**Triggers for Ad-Hoc Review:**
- New legal requirements
- New data processing activities
- Data breaches or incidents
- AEPD guidance updates
- Audit findings

### 12.2 Compliance Audits

**Internal Audits:**
- Quarterly spot checks of deletion processes
- Sample verification (e.g., check if data deleted per schedule)
- Review deletion logs
- Test automated deletion scripts

**External Audits:**
- ISO 27001 audits verify information security controls
- GDPR compliance audits verify data retention practices
- Regulatory audits (AEPD) if subject to investigation

### 12.3 Metrics and Reporting

**Track:**
- Number of records deleted per category (monthly)
- Compliance rate (% of data deleted on time)
- Exceptions and legal holds (active count)
- Data subject erasure requests (number, average time to fulfill)
- Deletion errors or failures

**Reporting:**
- Quarterly report to management
- Annual report to Board (if applicable)
- DPO report for Records of Processing Activities (ROPA)

---

## 13. Training and Awareness

### 13.1 Staff Training

**All Staff:**
- Basic awareness: Don't keep personal data longer than needed
- How to identify data subject to retention schedule
- How to request data deletion

**Data Owners and Processors:**
- Detailed training on retention schedule for their data categories
- How to implement deletion procedures
- How to handle legal holds

**IT and Security Staff:**
- Technical deletion methods
- Automated deletion script management
- Backup rotation and data lifecycle

**Annual Refresher:** All staff complete annual training on data retention principles.

### 13.2 Resources

**Available to Staff:**
- Retention schedule summary (quick reference)
- Deletion request forms
- Legal hold notification templates
- Contact information (DPO, IT, Legal)

---

## 14. Related Documents

- **Privacy Notice:** Informs data subjects of retention periods
- **Data Subject Rights Procedures:** Erasure and restriction requests
- **Information Security Policy:** Data lifecycle management
- **Records of Processing Activities (ROPA):** Documents retention for each processing activity
- **DPIA Template:** Retention considered in impact assessments
- **Business Continuity Plan:** Backup retention and recovery procedures

---

## 15. Legal References

### 15.1 Spanish Legal Retention Requirements

**Tax and Accounting:**
- **General Tax Law (Ley General Tributaria - Ley 58/2003):** 7 years retention for tax records
- **Commercial Code (Código de Comercio - Art. 30):** 6 years retention for accounting books and records (7 years is safer standard)
- **VAT Law:** 7 years for VAT-related records

**Labor and Employment:**
- **Workers' Statute (Estatuto de los Trabajadores):** 4 years for labor-related documents
- **Social Security Law:** 4 years for social security records
- **Health and Safety:** 40 years for exposure records (long-latency occupational diseases)

**Other:**
- **Civil Code:** 5 years general statute of limitations for contract claims
- **Consumer Protection:** 3-5 years for consumer dispute records

### 15.2 GDPR and Data Protection

- **GDPR Article 5(1)(e):** Storage limitation
- **GDPR Article 17:** Right to erasure (exceptions listed in Article 17(3))
- **GDPR Article 30:** Records of Processing Activities (retention periods documented)
- **LOPDGDD (Organic Law 3/2018):** Spanish implementation of GDPR

---

## 16. Contact Information

**Data Protection Officer (DPO):**  
Name: [DPO Name]  
Email: dpo@zabalagailetak.com  
Phone: +34 XXX XXX XXX

**Questions About Retention:**
- Retention periods: dpo@zabalagailetak.com
- Deletion requests: dpo@zabalagailetak.com
- Legal holds: legal@zabalagailetak.com
- Technical deletion: it@zabalagailetak.com

**Supervisory Authority:**  
Agencia Española de Protección de Datos (AEPD)  
Website: www.aepd.es  
Phone: +34 901 100 099

---

## Appendix A: Quick Reference Retention Table

| Data Category | Retention Period | Legal Basis |
|---------------|------------------|-------------|
| **Customer Accounts** | 2 years inactivity | Legitimate Interests |
| **Order and Transaction Records** | **7 years** | Legal Obligation |
| **Invoices** | **7 years** | Legal Obligation |
| **Payment Information** | Not stored (Stripe handles) | N/A |
| **Marketing Lists** | Until consent withdrawn or 2 years no engagement | Consent |
| **Customer Support** | 3 years | Legitimate Interests |
| **Current Employee Records** | Duration + 4 years | Legal Obligation |
| **Payroll Records** | Duration + **7 years** | Legal Obligation |
| **Health & Safety Records** | Duration + **40 years** | Legal Obligation |
| **Job Applicants (not hired)** | 6 months | Legitimate Interests |
| **Supplier Invoices** | **7 years** | Legal Obligation |
| **Web Analytics** | 26 months (then aggregated) | Legitimate Interests |
| **Security Logs** | 1 year | Legitimate Interests |
| **Backups** | 30-90 days | Legitimate Interests |

---

## Appendix B: Deletion Checklist

**When deleting personal data, verify:**

- [ ] Retention period has expired
- [ ] No legal hold or ongoing legal claims
- [ ] No legal obligation requiring longer retention
- [ ] Data subject rights considered (if erasure request)
- [ ] All systems identified (production, backups, archives, third parties)
- [ ] Appropriate deletion method selected (per data sensitivity)
- [ ] Deletion executed in all systems
- [ ] Deletion verified (data no longer present)
- [ ] Deletion logged in audit trail
- [ ] Certificate of destruction issued (if required)
- [ ] Third parties notified to delete (if data was shared)
- [ ] Data subject notified (if erasure request)

---

**END OF DATA RETENTION SCHEDULE**

**Document Owner:** Data Protection Officer  
**Last Reviewed:** January 8, 2026  
**Next Review:** January 8, 2027

**© 2026 Zabala Gailetak S.A. All rights reserved.**
