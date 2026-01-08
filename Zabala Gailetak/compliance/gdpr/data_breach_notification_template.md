# Data Breach Notification Template
## Zabala Gailetak S.A. - GDPR Compliance

**Document ID:** DBN-001  
**Version:** 1.0  
**Date:** January 8, 2026  
**Classification:** Highly Confidential  
**Owner:** Data Protection Officer (DPO)  
**Review Frequency:** Annual  
**Next Review Date:** January 8, 2027

---

## 1. Document Control

### 1.1 Version History

| Version | Date | Author | Changes |
|---------|------|--------|---------|
| 1.0 | 2026-01-08 | DPO | Initial template creation |

### 1.2 Purpose

This template provides standardized procedures and notification forms for reporting personal data breaches in compliance with:
- **GDPR Article 33:** Notification to supervisory authority (AEPD)
- **GDPR Article 34:** Communication to data subjects
- **LOPDGDD (Spanish Organic Law 3/2018):** Spanish data protection requirements

### 1.3 Scope

This template applies to all personal data breaches involving:
- Customer data (names, emails, addresses, order history)
- Employee data (HR records, payroll, performance reviews)
- Supplier/partner data (contact information, contracts)
- Any special category data (health data, biometric data, if applicable)

---

## 2. Legal Requirements Overview

### 2.1 GDPR Article 33 - Notification to Supervisory Authority

**Timeline:** Within **72 hours** of becoming aware of the breach

**Threshold:** Notification required when breach is **likely to result in a risk** to the rights and freedoms of individuals

**Content Required:**
1. Nature of the personal data breach
2. Categories and approximate number of data subjects affected
3. Categories and approximate number of personal data records affected
4. Contact details of DPO or other contact point
5. Description of likely consequences
6. Description of measures taken or proposed to address the breach

**Authority:** Agencia Española de Protección de Datos (AEPD)

### 2.2 GDPR Article 34 - Communication to Data Subjects

**Timeline:** **Without undue delay** after becoming aware of the breach

**Threshold:** Notification required when breach is **likely to result in a high risk** to the rights and freedoms of individuals

**Exceptions (notification not required):**
- Appropriate technical and organizational protection measures applied (e.g., encryption)
- Subsequent measures taken to ensure high risk no longer likely to materialize
- Would involve disproportionate effort (public communication acceptable)

**Content Required:**
1. Nature of the personal data breach in clear and plain language
2. Contact details of DPO or other contact point
3. Description of likely consequences
4. Description of measures taken or proposed to address the breach

### 2.3 Key Definitions

**Personal Data Breach:** A breach of security leading to:
- Accidental or unlawful destruction
- Loss, alteration
- Unauthorized disclosure of, or access to
- Personal data transmitted, stored, or otherwise processed

**Becoming Aware:** When the organization has a reasonable degree of certainty that a security incident has occurred that has led to compromise of personal data

**Risk Assessment:**
- **Low Risk:** No notification required (document internally)
- **Risk:** Notify AEPD within 72 hours
- **High Risk:** Notify AEPD within 72 hours + notify data subjects

---

## 3. Data Breach Response Process

### 3.1 Immediate Response (0-1 hour)

**Step 1: Detection and Reporting**
- Any employee discovering potential breach reports immediately to:
  - **DPO:** dpo@zabalagailetak.com | +34 XXX XXX XXX
  - **Security Team:** security@zabalagailetak.com | +34 XXX XXX XXX (24/7)
  - **CISO:** ciso@zabalagailetak.com | +34 XXX XXX XXX

**Step 2: Initial Assessment**
- DPO and CISO conduct rapid assessment:
  - What happened?
  - What data is affected?
  - How many people affected?
  - Is breach ongoing?
  - Preliminary risk level?

**Step 3: Activate Response Team**
- Notify Data Breach Response Team:
  - DPO (Lead)
  - CISO
  - IT Manager
  - Legal Counsel
  - CEO (if high-risk breach)
  - PR/Communications (if public notification needed)

### 3.2 Containment and Investigation (1-24 hours)

**Step 4: Contain the Breach**
- IT/Security team takes immediate containment actions:
  - Isolate affected systems
  - Revoke compromised credentials
  - Block unauthorized access
  - Preserve evidence (forensic images, logs)

**Step 5: Detailed Investigation**
- Determine full scope of breach:
  - **What data:** Categories of personal data affected
  - **How many:** Number of data subjects and records
  - **When:** Date and time of breach (or estimated range)
  - **How:** Attack vector or cause (hacking, malware, human error, physical theft)
  - **Who:** Potential attackers or responsible parties
  - **Why:** Motive (if determinable)
  - **Exfiltration:** Was data copied/removed or just accessed?

**Step 6: Risk Assessment**
- Assess risk to individuals (use Risk Assessment Matrix - Section 4)
- Consider:
  - Type of data (PII, financial, health, credentials)
  - Sensitivity and volume
  - Ease of identification of individuals
  - Consequences for individuals (identity theft, financial loss, discrimination, reputation damage)
  - Special characteristics of data subjects (children, vulnerable groups)
  - Number of affected individuals
  - Existing security measures (encryption, pseudonymization)

**Step 7: Documentation**
- Document all findings in Breach Log (see Section 7)
- Preserve all evidence
- Create timeline of events

### 3.3 Notification Decision (24-48 hours)

**Step 8: Notification Decision**

DPO determines notification requirements:

| Risk Level | AEPD Notification | Data Subject Notification | Timeline |
|------------|-------------------|---------------------------|----------|
| **Low Risk** | Not required | Not required | Document internally |
| **Risk** | **Required** | Not required (unless specific circumstances) | Within 72 hours |
| **High Risk** | **Required** | **Required** | Within 72 hours (AEPD) + without undue delay (subjects) |

**Step 9: Prepare Notifications**
- Draft notification to AEPD (use Template A - Section 5.1)
- Draft notification to data subjects if required (use Template B - Section 5.2)
- Legal counsel reviews all notifications
- CEO approves notifications (high-risk breaches)

**Step 10: Submit Notifications**
- AEPD notification: Via AEPD online portal (https://sedeagpd.gob.es)
- Data subject notification: Email, letter, or public communication (depending on circumstances)
- Document submission date and time (for 72-hour compliance)

### 3.4 Remediation and Follow-up (Ongoing)

**Step 11: Implement Corrective Actions**
- Address root cause of breach
- Implement additional security controls
- Update policies and procedures
- Provide additional training if needed

**Step 12: Monitor and Update**
- Monitor for further incidents
- Respond to AEPD requests for additional information
- Respond to data subject inquiries
- Update AEPD and data subjects if new information discovered

**Step 13: Post-Incident Review**
- Conduct lessons learned session (within 30 days)
- Update breach response procedures
- Update risk assessment
- Report to management

---

## 4. Risk Assessment Matrix

### 4.1 Risk Factors

| Factor | Low Risk | Medium Risk | High Risk |
|--------|----------|-------------|-----------|
| **Data Type** | Non-sensitive contact info | Email addresses, phone numbers, names | Passwords, financial data, health data, ID numbers, children's data |
| **Volume** | <100 individuals | 100-1,000 individuals | >1,000 individuals |
| **Encryption** | Encrypted (strong encryption, keys secure) | Weakly encrypted or partially encrypted | Unencrypted plaintext |
| **Ease of Identification** | Pseudonymized, difficult to identify | Some identifiers present | Full name + other identifiers (address, DOB, ID number) |
| **Likelihood of Misuse** | Low (accidental disclosure to trusted party) | Medium (potential for misuse) | High (known malicious actor, data exfiltrated) |
| **Consequences** | Minor inconvenience | Moderate impact (spam, phishing risk) | Severe impact (identity theft, financial loss, discrimination, physical harm) |
| **Vulnerable Groups** | General public | Employees | Children, minorities, vulnerable individuals |
| **Duration** | Brief exposure, quickly contained | Hours to days | Extended exposure, ongoing |

### 4.2 Overall Risk Determination

**Low Risk Examples:**
- Accidentally emailed internal document to wrong colleague
- Lost encrypted laptop (strong encryption, no evidence of access)
- Brief exposure of limited non-sensitive data

**Medium Risk Examples:**
- Unauthorized access to customer email addresses and names
- Loss of unencrypted device with limited personal data
- Accidental disclosure of employee contact information to external party

**High Risk Examples:**
- Database breach exposing passwords, financial data, or ID numbers
- Ransomware with data exfiltration
- Loss of unencrypted health data
- Breach affecting children's data
- Large-scale breach (>1,000 individuals)
- Breach enabling identity theft or financial fraud

### 4.3 Decision Tree

```
START: Personal data breach detected
│
├─ Is personal data involved? 
│  ├─ NO → Not a GDPR breach (may be security incident)
│  └─ YES → Continue
│
├─ Is data encrypted with secure keys?
│  ├─ YES → Likely LOW risk (but assess further)
│  └─ NO → Continue
│
├─ What type of data?
│  ├─ Non-sensitive contact info only → Likely MEDIUM risk
│  ├─ Passwords, financial, health, ID numbers → Likely HIGH risk
│  └─ Special category data (Article 9) → Likely HIGH risk
│
├─ How many individuals affected?
│  ├─ <100 → Risk level depends on data type
│  ├─ 100-1,000 → Increases risk level
│  └─ >1,000 → Likely HIGH risk
│
├─ Was data accessed by malicious actor?
│  ├─ YES → HIGH risk
│  ├─ UNKNOWN → Assume YES, HIGH risk
│  └─ NO (accidental disclosure to trusted party) → May be MEDIUM risk
│
RESULT:
├─ LOW Risk → Document internally (no notification)
├─ MEDIUM Risk → Notify AEPD within 72 hours
└─ HIGH Risk → Notify AEPD within 72 hours + Notify data subjects without undue delay
```

---

## 5. Notification Templates

### 5.1 Template A: Notification to AEPD (Article 33)

**[TO BE SUBMITTED VIA AEPD ONLINE PORTAL: https://sedeagpd.gob.es]**

---

**NOTIFICATION OF PERSONAL DATA BREACH**  
**GDPR Article 33 / LOPDGDD Article 33**

**Submitted by:**  
Zabala Gailetak S.A.  
[Company Address]  
[City, Postal Code], Spain  
CIF: [Tax ID]

**Data Protection Officer Contact:**  
Name: [DPO Name]  
Email: dpo@zabalagailetak.com  
Phone: +34 XXX XXX XXX

**Date of Notification:** [Submission Date]  
**Date Breach Discovered:** [Discovery Date]  
**Breach Reference Number:** ZG-BREACH-[YYYY]-[NNN]

---

### 1. DESCRIPTION OF THE BREACH

**1.1 Nature of the Personal Data Breach**

[Describe what happened in clear, factual terms. Examples:]

- *"On [Date], unauthorized access to our customer database was detected. Investigation revealed that an attacker exploited a vulnerability in our web application to gain access to customer records stored in our database."*

- *"On [Date], an employee laptop containing unencrypted customer data was stolen from [Location]. The laptop was not password-protected at the time of theft."*

- *"On [Date], a phishing email was inadvertently sent to all customers, exposing the email addresses of 5,000 customers in the CC field instead of BCC."*

**1.2 Date and Time of Breach**

- **Breach Occurred:** [Date and time, or estimated range if unknown]
- **Breach Discovered:** [Date and time]
- **Breach Contained:** [Date and time, or "Ongoing"]

**1.3 Categories of Data Subjects Affected**

[Select all that apply and provide approximate numbers:]

- [ ] Customers: Approximately _____ individuals
- [ ] Employees: Approximately _____ individuals
- [ ] Suppliers/Partners: Approximately _____ individuals
- [ ] Website visitors: Approximately _____ individuals
- [ ] Children (under 18): Approximately _____ individuals
- [ ] Other: ___________________________

**Total Approximate Number of Data Subjects Affected:** _____

**1.4 Categories of Personal Data Affected**

[Select all that apply and describe:]

**Identification Data:**
- [ ] Full names
- [ ] Email addresses
- [ ] Phone numbers
- [ ] Physical addresses (street, city, postal code)
- [ ] National ID numbers (DNI/NIE)
- [ ] Date of birth

**Financial Data:**
- [ ] Credit/debit card numbers [If yes: Last 4 digits only or full numbers? _____]
- [ ] Bank account numbers
- [ ] Payment history / transaction records
- [ ] Financial statements

**Authentication Data:**
- [ ] Usernames
- [ ] Passwords [If yes: Hashed or plaintext? Hash algorithm: _____]
- [ ] Security questions/answers

**Usage Data:**
- [ ] IP addresses
- [ ] Browsing history / cookies
- [ ] Purchase history / order details
- [ ] Account activity logs

**Special Category Data (Article 9 GDPR):**
- [ ] Health data
- [ ] Racial or ethnic origin
- [ ] Political opinions
- [ ] Religious or philosophical beliefs
- [ ] Trade union membership
- [ ] Genetic data
- [ ] Biometric data
- [ ] Sex life or sexual orientation

**Other Data:**
- [ ] Employment records
- [ ] Educational records
- [ ] Other: _____________________________

**Approximate Number of Records Affected:** _____

**1.5 How the Breach Occurred**

[Describe the attack vector or cause:]

- [ ] Cyberattack (specify type: ransomware, phishing, SQL injection, malware, DDoS, etc.)
- [ ] Unauthorized access (internal or external)
- [ ] Lost or stolen device (laptop, smartphone, USB drive, paper documents)
- [ ] Human error (accidental disclosure, misconfiguration, sent to wrong recipient)
- [ ] System malfunction or software bug
- [ ] Physical security breach (break-in, theft)
- [ ] Third-party/supplier breach
- [ ] Other: _____________________________

**Detailed Description:**

[Provide technical details of how the breach occurred, including any vulnerabilities exploited, attack timeline, and evidence of data exfiltration or access.]

---

### 2. LIKELY CONSEQUENCES OF THE BREACH

**2.1 Potential Impact on Data Subjects**

[Describe potential harm to individuals:]

- [ ] Risk of identity theft or fraud
- [ ] Financial loss
- [ ] Unauthorized access to accounts
- [ ] Spam, phishing, or other unwanted communications
- [ ] Reputational damage
- [ ] Discrimination
- [ ] Emotional distress
- [ ] Physical harm (if location data exposed)
- [ ] Other: _____________________________

**2.2 Severity Assessment**

- [ ] Low Risk: Minimal impact expected
- [ ] Medium Risk: Some potential for harm, but mitigated by circumstances
- [ ] High Risk: Significant potential for harm to individuals

**Justification for Risk Level:**

[Explain why this risk level was assigned, considering factors such as data type, encryption status, number of individuals, likelihood of misuse, etc.]

---

### 3. MEASURES TAKEN OR PROPOSED

**3.1 Immediate Containment Actions**

[Describe actions taken to stop the breach:]

- Date/Time of containment: [Date and time]
- Actions taken:
  - [ ] Affected systems isolated or taken offline
  - [ ] Compromised accounts disabled
  - [ ] Unauthorized access blocked (IP addresses, accounts)
  - [ ] Malware removed
  - [ ] Vulnerabilities patched
  - [ ] Credentials reset (passwords changed)
  - [ ] Other: _____________________________

**3.2 Remediation Measures**

[Describe measures to address the breach and prevent recurrence:]

**Technical Measures:**
- [ ] Security patches applied
- [ ] Additional security controls implemented (firewalls, encryption, MFA, etc.)
- [ ] System hardening and configuration review
- [ ] Enhanced monitoring and logging
- [ ] Penetration testing and vulnerability assessment
- [ ] Other: _____________________________

**Organizational Measures:**
- [ ] Employee training and awareness
- [ ] Policy and procedure updates
- [ ] Access control review
- [ ] Third-party security assessment
- [ ] Incident response procedure improvements
- [ ] Other: _____________________________

**3.3 Measures to Mitigate Impact on Data Subjects**

[Describe actions to minimize harm to individuals:]

- [ ] Notification to data subjects with guidance on protective actions
- [ ] Credit monitoring services offered (if financial data exposed)
- [ ] Password reset enforcement
- [ ] Enhanced account monitoring
- [ ] Identity theft protection resources provided
- [ ] Customer support hotline established
- [ ] Other: _____________________________

**3.4 Timeline for Remediation**

- Short-term actions (completed within 7 days): [List]
- Medium-term actions (completed within 30 days): [List]
- Long-term improvements (completed within 90 days): [List]

---

### 4. NOTIFICATION TO DATA SUBJECTS

**4.1 Will Data Subjects Be Notified?**

- [ ] YES - High risk to individuals, notification required
- [ ] NO - Risk does not meet high threshold, notification not required

**If YES:**
- **Notification Method:** [ ] Email [ ] Letter [ ] Phone [ ] Public Communication [ ] Other: _____
- **Notification Date:** [Date sent or planned date]
- **Number of Individuals Notified:** _____
- **Content:** [Attach copy of notification or summarize key points]

**If NO:**
- **Reason notification not required:**
  - [ ] Appropriate technical protection measures were in place (e.g., encryption)
  - [ ] Subsequent measures taken to ensure high risk no longer materializes
  - [ ] Would involve disproportionate effort (public communication alternative considered)
  - [ ] Risk does not meet high threshold

**Justification:**

[Provide detailed explanation for decision not to notify data subjects, demonstrating compliance with Article 34 requirements.]

---

### 5. ADDITIONAL INFORMATION

**5.1 Cross-Border Breach**

- [ ] Breach affects data subjects in other EU member states
  - Member states affected: _____________________________
  - Lead supervisory authority (if applicable): _____________________________

**5.2 Previous Breaches**

- [ ] This is our first notifiable breach
- [ ] We have reported previous breaches: [List dates and reference numbers of previous breaches in past 12 months]

**5.3 Third-Party Involvement**

- [ ] Breach involved a data processor or third-party service provider
  - Provider name: _____________________________
  - Provider has been notified: [ ] Yes [ ] No
  - Provider's role in breach: _____________________________

**5.4 Law Enforcement Notification**

- [ ] Law enforcement has been notified (Police, Civil Guard, etc.)
  - Date notified: _____
  - Case/Report number: _____

**5.5 Insurance Claim**

- [ ] Cyber insurance claim filed
  - Insurer: _____________________________
  - Claim number: _____________________________

---

### 6. DECLARATION

I, [DPO Name], Data Protection Officer of Zabala Gailetak S.A., declare that the information provided in this notification is accurate and complete to the best of my knowledge as of the date of this notification. We commit to providing updates to the AEPD as new information becomes available.

We understand that failure to notify a breach, or providing false or misleading information, may result in administrative fines and other sanctions under GDPR and LOPDGDD.

**Name:** [DPO Name]  
**Position:** Data Protection Officer  
**Signature:** _____________________________  
**Date:** [Date]

---

### 7. CONTACT INFORMATION

For questions or additional information regarding this breach notification:

**Data Protection Officer:**  
Name: [DPO Name]  
Email: dpo@zabalagailetak.com  
Phone: +34 XXX XXX XXX

**Company Representative:**  
Name: [CEO or CISO Name]  
Position: [Title]  
Email: [Email]  
Phone: +34 XXX XXX XXX

---

### 8. ATTACHMENTS

[List any attachments included with notification:]

- [ ] Detailed timeline of breach events
- [ ] Forensic investigation report (summary)
- [ ] Copy of notification sent to data subjects
- [ ] List of affected data fields (technical details)
- [ ] Evidence of containment actions
- [ ] Other: _____________________________

---

**END OF AEPD NOTIFICATION**

**Submission Date:** _____________________  
**AEPD Confirmation Number:** _____________________ (filled after submission)

---

### 5.2 Template B: Notification to Data Subjects (Article 34)

**[TO BE SENT VIA EMAIL OR LETTER TO AFFECTED INDIVIDUALS]**

---

**SUBJECT: Important Security Notice - Your Personal Data**

---

Dear [Customer/Employee/Partner],

We are writing to inform you of a security incident that may have affected your personal data. At Zabala Gailetak, we take the security and privacy of your information very seriously, and we want to provide you with full transparency about what happened, what information may have been affected, and what steps we are taking.

---

### WHAT HAPPENED

On [Date], we discovered [brief, clear description of what happened in plain language].

[Example: "we discovered that an unauthorized person gained access to our customer database through a vulnerability in our website. We immediately took action to secure our systems and began an investigation."]

We became aware of this incident on [Discovery Date] and took immediate steps to contain it and prevent further unauthorized access.

---

### WHAT INFORMATION WAS AFFECTED

Based on our investigation, the following information about you may have been accessed or disclosed:

[List specific data types affected - be clear and specific:]

- Your name
- Your email address
- Your mailing address
- Your phone number
- Your order history from [Date Range]
- [Other specific data]

[If passwords were affected:]
Your account password was [stored in encrypted/hashed format using industry-standard security measures / stored in a way that may allow it to be decoded]. Out of an abundance of caution, we have [automatically reset your password / recommend you change your password immediately].

[If payment card data was affected:]
[Explain what payment data was exposed - full number or last 4 digits, whether CVV or expiration date was included, etc.]

**What was NOT affected:**
[List categories of data that were NOT exposed, if this provides reassurance. Example: "Your payment card information was NOT affected as we do not store full credit card numbers in our database."]

---

### WHAT WE ARE DOING

We have taken the following actions:

**Immediate Actions:**
- [Specific action 1, e.g., "Secured the vulnerability that allowed unauthorized access"]
- [Specific action 2, e.g., "Reset all customer passwords"]
- [Specific action 3, e.g., "Engaged a cybersecurity firm to conduct a thorough investigation"]
- [Specific action 4, e.g., "Notified the Spanish Data Protection Authority (AEPD)"]
- [Specific action 5, e.g., "Implemented enhanced security monitoring"]

**Long-term Improvements:**
- [Actions to prevent future incidents, e.g., "Implementing additional security controls and encryption"]
- [e.g., "Conducting a comprehensive security audit"]
- [e.g., "Providing additional security training to our staff"]

---

### WHAT YOU SHOULD DO

To protect yourself, we recommend you take the following steps:

**Immediate Actions:**

1. **Change Your Password** (if applicable)
   - If you have an account with us, please change your password immediately at [URL]
   - If you used the same password for other websites, change those passwords as well
   - Choose a strong, unique password (at least 12 characters with letters, numbers, and symbols)

2. **Monitor Your Accounts**
   - Regularly check your bank and credit card statements for unauthorized transactions
   - Report any suspicious activity to your bank immediately
   - Check your email for suspicious messages claiming to be from us

3. **Be Alert for Phishing**
   - Be cautious of emails, phone calls, or texts asking for personal information
   - We will never ask you for your password, credit card number, or other sensitive information via email or phone
   - If you receive a suspicious communication claiming to be from us, contact us directly using the contact information below

[If high-risk breach involving financial or ID data:]

4. **Consider Credit Monitoring**
   - You may wish to place a fraud alert on your credit file with credit bureaus (Equifax, Experian, TransUnion equivalent in Spain)
   - Consider requesting a free credit report to check for suspicious activity
   - [If offering services:] We are offering [12 months] of free credit monitoring services through [Provider]. To enroll, visit [URL] or call [Phone] and use reference code [Code].

5. **Report Identity Theft** (if applicable)
   - If you believe you are a victim of identity theft, report it to local police and contact the relevant authorities

---

### MORE INFORMATION AND SUPPORT

We understand you may have questions or concerns. We are here to help.

**Dedicated Support:**
- **Email:** [security-incident@zabalagailetak.com] or [dpo@zabalagailetak.com]
- **Phone:** [+34 XXX XXX XXX] (Monday-Friday, 9:00-18:00 CET)
- **Frequently Asked Questions:** [URL to FAQ page about the incident]

**Your Rights:**
Under GDPR, you have the right to:
- Access your personal data
- Request correction of inaccurate data
- Request deletion of your data
- Object to processing of your data
- Lodge a complaint with the supervisory authority (AEPD)

To exercise these rights or for more information about how we process your personal data, please contact our Data Protection Officer:
- Email: dpo@zabalagailetak.com
- Phone: +34 XXX XXX XXX

**Spanish Data Protection Authority (AEPD):**
If you have concerns about how we have handled this incident, you have the right to lodge a complaint with the AEPD:
- Website: www.aepd.es
- Phone: +34 901 100 099
- Address: C/ Jorge Juan, 6, 28001 Madrid, Spain

---

### OUR COMMITMENT TO YOU

We sincerely apologize for this incident and any concern or inconvenience it may cause you. The security and privacy of your personal data is our highest priority. We are committed to:

- Continuing to investigate this incident thoroughly
- Implementing additional security measures to prevent future incidents
- Keeping you informed if we learn any new information that affects you
- Providing support and resources to help you protect yourself

We value your trust and are working diligently to ensure this does not happen again.

---

**Sincerely,**

[CEO Name]  
Chief Executive Officer  
Zabala Gailetak S.A.

[DPO Name]  
Data Protection Officer  
Zabala Gailetak S.A.

---

**Date:** [Date of notification]  
**Reference Number:** ZG-BREACH-[YYYY]-[NNN]

---

**Zabala Gailetak S.A.**  
[Company Address]  
[City, Postal Code], Spain  
CIF: [Tax ID]  
Email: info@zabalagailetak.com  
Website: www.zabalagailetak.com

---

**[Optional: Include resources/links section with helpful information about password security, identity theft protection, etc.]**

---

## 6. Alternative Notification Methods

### 6.1 Public Communication (When Individual Notification is Disproportionate)

**Subject to AEPD Approval**

**Press Release Template:**

---

**FOR IMMEDIATE RELEASE**

**Data Security Incident Notice**

**[City], [Date]** — Zabala Gailetak S.A. is issuing this public notice to inform individuals who may have been affected by a data security incident that occurred on [Date].

**What Happened:**
[Brief description of incident]

**What Information Was Involved:**
[Categories of data affected]

**What We Are Doing:**
[Summary of containment and remediation actions]

**What You Should Do:**
[Summary of recommended protective actions]

**For More Information:**
Individuals seeking more information should visit [dedicated webpage URL] or call our dedicated helpline at [Phone Number].

We sincerely apologize for this incident and are committed to protecting the security of personal information.

**Contact:**
[DPO Name], Data Protection Officer
Email: dpo@zabalagailetak.com
Phone: +34 XXX XXX XXX

---

**Additional Public Notification Channels:**
- Company website prominent banner
- Social media posts (Facebook, Twitter, LinkedIn)
- Industry associations or trade groups
- Local media outreach
- Customer email to mailing list (if emails not compromised)

---

## 7. Internal Documentation - Breach Log

### 7.1 Breach Register Entry

All breaches (including those not notified) must be documented in internal Breach Register:

---

**BREACH REGISTER ENTRY**

**Breach ID:** ZG-BREACH-[YYYY]-[NNN]

**Discovery Date:** _____________________  
**Discovery Method:** [ ] User Report [ ] Security Monitoring [ ] Audit [ ] External Notification [ ] Other: _____

**Reported By:** _____________________  
**DPO Notified:** _____________________ (Date/Time)

---

**BREACH DETAILS:**

**Date/Time of Breach:** _____________________ (or estimated range)

**Type of Breach:**
- [ ] Confidentiality breach (unauthorized disclosure or access)
- [ ] Integrity breach (unauthorized modification or alteration)
- [ ] Availability breach (loss, destruction, or unavailability)

**Cause:**
- [ ] Cyberattack
- [ ] Human error
- [ ] System failure
- [ ] Physical security breach
- [ ] Third-party breach
- [ ] Other: _____

**Detailed Description:**

[Comprehensive description of what happened, how it happened, and timeline of events]

---

**DATA AFFECTED:**

**Categories of Data Subjects:**
- Customers: _____ (approximate number)
- Employees: _____ (approximate number)
- Other: _____ (specify: _______________)

**Total Individuals Affected:** _____

**Categories of Personal Data:**
[List all categories of personal data affected with descriptions]

**Special Category Data:** [ ] Yes [ ] No  
If yes, specify: _____

---

**RISK ASSESSMENT:**

**Risk Level:** [ ] Low [ ] Medium [ ] High

**Risk to Individuals:**
[Describe potential impact on data subjects]

**Factors Considered:**
- Type and sensitivity of data: _____
- Volume of data and number of individuals: _____
- Ease of identification: _____
- Severity of consequences: _____
- Special characteristics of data subjects: _____
- Existing security measures: _____

---

**NOTIFICATION DECISIONS:**

**AEPD Notification:** [ ] Required [ ] Not Required  
**Reason:** _____

**If Required:**
- Notification Date: _____
- Within 72 hours: [ ] Yes [ ] No
- If No, reason for delay: _____
- AEPD Reference Number: _____

**Data Subject Notification:** [ ] Required [ ] Not Required  
**Reason:** _____

**If Required:**
- Notification Method: [ ] Email [ ] Letter [ ] Phone [ ] Public Communication
- Notification Date: _____
- Number of Individuals Notified: _____

---

**ACTIONS TAKEN:**

**Containment:**
[List immediate containment actions with dates]

**Investigation:**
[Summary of investigation findings]

**Remediation:**
[List remediation actions with dates and responsible parties]

---

**FOLLOW-UP:**

**Updates Provided to AEPD:** [ ] Yes [ ] No  
Dates: _____

**Updates Provided to Data Subjects:** [ ] Yes [ ] No  
Dates: _____

**Post-Incident Review Completed:** [ ] Yes [ ] No  
Date: _____

**Lessons Learned:**
[Summary of key takeaways and improvements identified]

**Policy/Procedure Updates:** [ ] Yes [ ] No  
Description: _____

---

**CLOSURE:**

**Breach Closed:** [ ] Yes [ ] No  
**Closure Date:** _____  
**Closure Approved By:** _____________________ (DPO Signature)

---

## 8. AEPD Communication Log

### 8.1 Initial Notification

**Date Submitted:** _____  
**Submission Method:** [ ] Online Portal [ ] Email [ ] Mail  
**AEPD Reference Number:** _____  
**Submitted By:** _____

### 8.2 Follow-up Communications

| Date | Type | Content Summary | AEPD Response | Action Required |
|------|------|-----------------|---------------|-----------------|
| | | | | |
| | | | | |

### 8.3 AEPD Requests for Information

| Date Received | Information Requested | Date Responded | Response Summary |
|---------------|----------------------|----------------|------------------|
| | | | |
| | | | |

### 8.4 AEPD Decision/Outcome

**Investigation Outcome:** [ ] No Action [ ] Warning [ ] Corrective Measures [ ] Fine  
**Date:** _____  
**Details:** _____

**Fine Amount (if applicable):** € _____  
**Payment Status:** [ ] Paid [ ] Appealed [ ] Pending

---

## 9. Data Subject Inquiry Log

### 9.1 Inquiries Received

| Date | Name | Contact | Question/Concern | Response | Resolved |
|------|------|---------|------------------|----------|----------|
| | | | | | [ ] Yes [ ] No |
| | | | | | [ ] Yes [ ] No |

### 9.2 Support Metrics

**Total Inquiries Received:** _____  
**Average Response Time:** _____  
**Resolution Rate:** _____%  
**Escalations:** _____

---

## 10. Lessons Learned and Corrective Actions

### 10.1 Post-Incident Review Meeting

**Date:** _____  
**Attendees:** DPO, CISO, CEO, IT Manager, Legal, [Others]

### 10.2 Key Findings

**What Went Well:**
1. _____
2. _____
3. _____

**What Needs Improvement:**
1. _____
2. _____
3. _____

**Root Cause Analysis:**
[Describe underlying causes of the breach]

### 10.3 Corrective Action Plan

| Action | Responsible | Target Date | Status | Completion Date |
|--------|-------------|-------------|--------|-----------------|
| | | | [ ] Pending [ ] In Progress [ ] Complete | |
| | | | [ ] Pending [ ] In Progress [ ] Complete | |
| | | | [ ] Pending [ ] In Progress [ ] Complete | |

---

## 11. Training and Awareness

### 11.1 Breach Response Training

**All Employees:**
- Annual GDPR training includes breach response procedures
- Know how to report suspected breaches
- Understand their role in containment

**Breach Response Team:**
- Detailed training on notification requirements
- Practice drills (tabletop exercises)
- Review of this template and procedures

**Training Records:**
- Date of last training: _____
- Next training scheduled: _____
- Attendance: _____%

### 11.2 Awareness Campaign

Following a breach, organization-wide awareness activities:
- Email to all employees explaining what happened (appropriate detail level)
- Reminder of security best practices
- Lessons learned (anonymized if involving employee error)
- Updated training materials

---

## 12. Related Documents

- Information Security Policy (ISP-001)
- Incident Response Procedure (IRP-001)
- Data Subject Rights Procedures (DSR-001)
- Privacy Notice (PN-001)
- Data Protection Impact Assessment Template (DPIA-001)
- Data Retention Schedule (DRS-001)
- Asset Register (ASR-001)
- Business Continuity Plan (BCP-001)

---

## 13. Regulatory References

**GDPR:**
- Article 4(12): Definition of personal data breach
- Article 33: Notification of breach to supervisory authority
- Article 34: Communication of breach to data subject
- Article 83(4)(a): Fines for failure to notify breach (up to €10M or 2% global annual turnover)
- Recitals 85-88: Additional context on breach notification

**LOPDGDD (Spanish Law):**
- Article 33: Implementation of GDPR Article 33 in Spain

**AEPD Guidelines:**
- Guide on Security Breach Notification (available at www.aepd.es)
- FAQs on breach notification

**WP29 Guidelines (now EDPB):**
- Guidelines on Personal Data Breach Notification under Regulation 2016/679 (WP250)

---

## 14. Contact Information

**Data Protection Officer (DPO):**  
Name: [DPO Name]  
Email: dpo@zabalagailetak.com  
Phone: +34 XXX XXX XXX

**CISO:**  
Email: ciso@zabalagailetak.com  
Phone: +34 XXX XXX XXX

**Legal Counsel:**  
Email: legal@zabalagailetak.com  
Phone: +34 XXX XXX XXX

**AEPD (Spanish Data Protection Authority):**  
Website: www.aepd.es  
Breach Notification Portal: https://sedeagpd.gob.es  
Phone: +34 901 100 099  
Address: C/ Jorge Juan, 6, 28001 Madrid, Spain

---

**END OF DATA BREACH NOTIFICATION TEMPLATE**

---

**Document Owner:** Data Protection Officer  
**Last Reviewed:** January 8, 2026  
**Next Review:** January 8, 2027
