# Password Policy
## Zabala Gailetak S.A.

**Document ID:** PWD-001  
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
| IT Manager | [Name] | | |

---

## 2. Purpose and Scope

### 2.1 Purpose

This Password Policy establishes requirements for creating, managing, and protecting passwords used to access Zabala Gailetak's information systems. Strong password practices are critical for:
- Preventing unauthorized access to systems and data
- Protecting customer and employee information
- Ensuring compliance with ISO/IEC 27001:2022 and GDPR
- Reducing risk of credential theft and account compromise
- Meeting regulatory and contractual security requirements

### 2.2 Scope

This policy applies to:
- **All Users:** Employees, contractors, consultants, temporary staff, and third parties
- **All Systems:** IT systems (workstations, servers, applications, cloud services) and OT systems (PLCs, SCADA, HMI)
- **All Access Methods:** Local login, remote access (VPN), web applications, mobile devices, administrative interfaces
- **All Password Types:** User passwords, administrative passwords, service accounts, API keys, encryption keys

### 2.3 Compliance

Compliance with this policy is mandatory. Violations may result in:
- Account suspension
- Forced password reset
- Disciplinary action (per Acceptable Use Policy)
- Termination of employment or contract

---

## 3. Password Requirements

### 3.1 Standard User Accounts

**Minimum Requirements:**
- **Length:** Minimum 12 characters
- **Complexity:** Must contain at least three of the following four categories:
  - Uppercase letters (A-Z)
  - Lowercase letters (a-z)
  - Numbers (0-9)
  - Special characters (!@#$%^&*()_+-=[]{}|;:,.<>?)
- **Expiration:** 90 days (180 days if Multi-Factor Authentication enabled)
- **History:** Cannot reuse last 12 passwords
- **Lockout:** Account locked after 5 failed login attempts for 30 minutes
- **Change Requirements:** Must differ significantly from previous password (not just incrementing numbers)

**Example Strong Passwords:**
- `Coffee&Cookies2026!`
- `Zabala$Gailetak#456`
- `MyD0g!LovesCookies`
- `Spain2026@Barcelona`

**Recommended:** Use passphrases (4+ random words):
- `correct-horse-battery-staple`
- `BlueElephant!DancesSalsa77`
- `Pizza$Oven#Baker$2026`

### 3.2 Privileged and Administrative Accounts

**Elevated Requirements:**
- **Length:** Minimum 14 characters
- **Complexity:** Must contain all four character categories (upper, lower, number, special)
- **Expiration:** 60 days (90 days if MFA enabled)
- **History:** Cannot reuse last 24 passwords
- **Lockout:** Account locked after 3 failed login attempts for 1 hour
- **Separate Accounts:** Administrators must have separate admin account (e.g., `admin_jsmith`) distinct from regular user account (`jsmith`)
- **Session Timeout:** Automatic logout after 15 minutes of inactivity

**Scope:**
- Domain Administrators
- Server administrators (root, Administrator accounts)
- Database administrators
- Network administrators (firewall, switch, router access)
- Cloud platform administrators (AWS, Azure, M365 Global Admin)
- SCADA/OT system administrators
- Security system administrators

### 3.3 Service Accounts

**Requirements:**
- **Length:** Minimum 20 characters (randomly generated)
- **Complexity:** Maximum randomness (all character types)
- **Expiration:** 365 days or never (if rotation disruptive to services)
- **Storage:** Encrypted password vault (HashiCorp Vault, CyberArk, or approved equivalent)
- **Usage:** Automated processes only (no interactive login)
- **Monitoring:** Service account activity logged and monitored
- **Least Privilege:** Permissions limited to exact requirements

**Examples:**
- Database service account for application connections
- Backup service account
- Monitoring agent accounts
- API integration accounts

### 3.4 Shared and Group Accounts

**Policy:** Shared accounts are **prohibited** except where technically unavoidable.

**Exceptions Require:**
- CISO approval with business justification
- Enhanced logging (track which individual used shared account)
- Password change after each authorized user's access ends
- Regular access reviews (monthly)

**Typical Exceptions:**
- Emergency "break-glass" administrator account (sealed envelope in safe)
- Specific industrial equipment with single local console (supplement with individual remote access)
- Legacy systems without multi-user support (scheduled for replacement)

### 3.5 Temporary and Guest Accounts

**Requirements:**
- **Default Password:** Randomly generated (minimum 12 characters)
- **Force Change:** Must change password on first login
- **Expiration:** Account expires automatically after defined period (default: 30 days)
- **Approval:** Requires manager and IT approval
- **Monitoring:** Activity logged and reviewed

**Use Cases:**
- Contractors and consultants (short-term projects)
- Temporary employees
- Auditors
- Vendors (limited-duration system access)

---

## 4. Password Creation Guidelines

### 4.1 Strong Password Characteristics

**DO Create Passwords That:**
- Are long (12+ characters for users, 14+ for admins)
- Use mix of character types (upper, lower, number, special)
- Are easy for you to remember but hard for others to guess
- Are unique to each system (never reuse passwords)
- Use passphrases (multiple random words)

**Techniques for Memorable Strong Passwords:**
1. **Passphrase Method:** String of random words
   - Example: `Purple!Elephant$Dances#Tango`
2. **Sentence Method:** First letters of sentence + modifications
   - Sentence: "I love eating Zabala cookies at 3pm every Friday!"
   - Password: `IleZc@3peF!`
3. **Character Substitution:** Replace letters with similar characters
   - Word: "Chocolate Chip Cookie"
   - Password: `Ch0c0l@t3Ch1pC00k!e`

### 4.2 Prohibited Password Practices

**DO NOT Create Passwords That:**
- Contain dictionary words (single words easily cracked)
- Contain personal information:
  - Your name, username, employee ID
  - Family member or pet names
  - Birth dates, anniversaries
  - Phone numbers, addresses
- Contain company information:
  - Company name ("Zabala", "Gailetak")
  - Department names
  - Product names
- Are simple patterns:
  - `123456`, `password`, `qwerty`, `abc123`
  - Keyboard patterns (`qwertyuiop`, `asdfghjkl`)
  - Sequences (`abcdefgh`, `12345678`)
  - Repeating characters (`aaaaaaaa`, `11111111`)
- Are slight variations of old passwords:
  - Incrementing numbers (`Password1`, `Password2`, `Password3`)
  - Seasonal changes (`Summer2025`, `Fall2025`, `Winter2026`)
  - Just adding `!` or `1` to old password

**Examples of WEAK Passwords (Never Use):**
- `Zabala2026` (company name + year)
- `Password123!` (common word + pattern)
- `Gailetak!` (company name + special char)
- `Admin@123` (role + simple pattern)
- `123456` (sequence)
- `qwerty` (keyboard pattern)
- `JohnSmith1975` (name + birth year)
- `Summer2026!` (season + year)

---

## 5. Password Management

### 5.1 Password Protection

**NEVER:**
- Share your password with anyone (including managers, IT staff, colleagues)
- Write passwords on paper (sticky notes, notebooks, whiteboards)
- Store passwords in unencrypted files (Word docs, Excel spreadsheets, text files)
- Send passwords via email or instant message
- Say password out loud where others can hear
- Enter password while someone is watching (shoulder surfing)
- Use the same password for work and personal accounts

**ALWAYS:**
- Keep passwords confidential
- Change password immediately if compromise suspected
- Use different passwords for different systems
- Log out when leaving workstation unattended
- Lock screen when stepping away (Windows+L)

### 5.2 Password Managers (Approved)

**Organization-Approved Password Managers:**
- **1Password Business** (primary recommendation)
- **LastPass Enterprise**
- **Dashlane Business**
- **KeePass** (open-source, for specific use cases)

**Benefits:**
- Generate strong random passwords
- Store passwords encrypted
- Auto-fill credentials (reduce typing errors, phishing resistance)
- Unique password for every account
- Secure password sharing (when necessary)
- Audit trail and reporting

**Usage Requirements:**
- Use strong master password (minimum 16 characters)
- Enable Multi-Factor Authentication for password manager
- Do not share master password
- Regularly review and update stored passwords
- Remove old/unused credentials

**Prohibited:**
- Browser built-in password managers (Chrome, Firefox "Save Password") for work accounts
- Personal/consumer password managers for work passwords
- Unencrypted password storage (text files, spreadsheets)

### 5.3 Password Changes

**Routine Changes:**
- Standard users: Every 90 days (automatic expiration)
- Administrators: Every 60 days
- Service accounts: Every 365 days (or as practical)
- Users with MFA: Every 180 days (extended due to MFA protection)

**Forced Changes Required When:**
- Initial/temporary password (first login)
- Password reset by IT
- Suspected or confirmed compromise
- Departure of person with shared/delegated access
- Security incident affecting authentication system
- After extended leave (>90 days)

**Change Process:**
- Users notified 14 days before expiration (daily reminders in final 3 days)
- Self-service password change via portal or Ctrl+Alt+Del → Change Password
- Helpdesk support available for issues
- Cannot reuse last 12 passwords (last 24 for admins)

### 5.4 Password Resets

**Self-Service Reset:**
- Available through: https://password.zabalagailetak.com
- Requires multi-factor authentication:
  - Security questions (set during account creation)
  - Email verification (to registered email)
  - SMS code (to registered mobile)

**Helpdesk Reset:**
- Contact IT helpdesk: helpdesk@zabalagailetak.com | +34 XXX XXX XXX
- Identity verification required:
  - Employee ID
  - Personal information verification
  - Manager confirmation (if remote)
- Temporary password provided (must change on first login)
- Reset logged and monitored

**Security Measures:**
- Password reset links expire after 1 hour
- Reset link single-use only
- Account lockout reset requires approval (after repeated failed logins)
- Administrator password reset requires CISO approval

---

## 6. Multi-Factor Authentication (MFA)

### 6.1 MFA Requirements

**MFA Required For:**
- Remote access (VPN)
- Administrative and privileged accounts
- Access to systems containing Highly Confidential data:
  - Customer databases
  - Financial systems
  - HR systems
- Cloud services:
  - Microsoft 365 admin accounts
  - AWS root and administrative accounts
  - GitHub admin accounts
- External access to internal systems

**MFA Recommended For:**
- All user accounts (standard workstations)
- Mobile device access to company email
- Any system accessing customer or employee PII

**MFA Benefits:**
- Passwords alone are vulnerable (phishing, theft, guessing)
- MFA adds second verification factor (something you have)
- Reduces account compromise risk by 99.9%
- Allows longer password expiration (less frequent changes)

### 6.2 MFA Methods

**Approved MFA Methods (in order of preference):**

1. **Authenticator App (TOTP - Time-based One-Time Password):**
   - Recommended: Microsoft Authenticator, Google Authenticator, Authy
   - Generates 6-digit code every 30 seconds
   - Works offline
   - Most secure for day-to-day use

2. **Hardware Token (FIDO2/U2F):**
   - YubiKey, Titan Security Key
   - Physical device inserted into USB port or tapped via NFC
   - Highest security (phishing-resistant)
   - Required for highest privilege accounts (domain admins, root)

3. **Push Notification:**
   - Microsoft Authenticator, Duo Push
   - Approve login request on mobile device
   - Convenient but requires internet connectivity
   - Verify login details before approving (number matching)

4. **SMS Code (Least Preferred):**
   - 6-digit code sent via text message
   - Use only if other methods unavailable
   - Vulnerable to SIM swapping attacks
   - Not approved for administrative accounts

**Prohibited MFA Methods:**
- Email-based codes (same channel as primary authentication)
- Voice calls (social engineering risk)
- Unverified mobile apps

### 6.3 MFA Setup and Backup

**Initial Setup:**
- All users must enroll in MFA within 7 days of account creation
- IT provides setup instructions and support
- Test MFA login before disabling alternative access

**Backup Methods:**
- Enroll at least two MFA devices (primary and backup)
- Examples: Authenticator app on phone + YubiKey
- Store backup codes in secure location (password manager)
- Register backup phone number

**Lost or Stolen MFA Device:**
- Report immediately to IT helpdesk
- Use backup MFA method to access accounts
- IT can temporarily disable MFA for account recovery (with verification)
- Re-enroll new device within 24 hours

---

## 7. Account Lockout and Security

### 7.1 Account Lockout Policy

**Standard Accounts:**
- **Failed Login Threshold:** 5 incorrect attempts
- **Lockout Duration:** 30 minutes (automatic unlock)
- **Manual Unlock:** IT helpdesk (with identity verification)

**Administrative Accounts:**
- **Failed Login Threshold:** 3 incorrect attempts
- **Lockout Duration:** 1 hour (automatic unlock)
- **Manual Unlock:** CISO approval required

**Rationale:**
- Prevents brute-force password guessing
- Balances security with usability
- Logs all lockout events for monitoring

**If Locked Out:**
1. Wait for automatic unlock (30 or 60 minutes)
2. OR contact IT helpdesk for identity verification and manual unlock
3. Verify you're using correct username and password
4. Check for Caps Lock, keyboard layout (EN vs ES)
5. If repeated lockouts, change password (may be compromised)

### 7.2 Password Attacks and Detection

**Common Password Attack Types:**
- **Brute Force:** Trying all possible combinations (mitigated by complexity + lockout)
- **Dictionary Attack:** Trying common words and passwords (mitigated by complexity)
- **Credential Stuffing:** Using passwords leaked from other breaches (mitigated by unique passwords + MFA)
- **Phishing:** Tricking users into revealing passwords (mitigated by training + MFA)
- **Keylogging:** Malware capturing keystrokes (mitigated by endpoint protection + MFA)
- **Social Engineering:** Manipulating users to share passwords (mitigated by training + "never share" policy)

**Detection and Response:**
- SIEM monitors for:
  - Multiple failed login attempts
  - Impossible travel (logins from distant locations within short time)
  - Login from unusual IP addresses or countries
  - Login outside normal hours (for specific users)
  - Multiple account lockouts
- Automated alerts to security team
- Automated response: Block IP address, force password reset, account suspension
- User notification of suspicious activity

---

## 8. Special Use Cases

### 8.1 OT Systems (PLCs, SCADA, HMI)

**Challenges:**
- Legacy systems with limited password capabilities
- Systems that cannot be easily rebooted (production impact)
- Local console access only (no network authentication)

**Requirements:**
- **Minimum Length:** 12 characters (if system supports)
- **Complexity:** Maximum possible given system limitations
- **Change Frequency:** 180 days (or per vendor recommendations)
- **Documentation:** Password stored in encrypted vault
- **Access Control:** Physical access to OT area restricted
- **Backup Authentication:** Separate remote access with MFA where possible
- **Monitoring:** All OT authentication logged

**Procedure:**
- OT password changes coordinated with maintenance windows
- Two authorized personnel present for changes (dual control)
- Test authentication before and after changes
- Update password vault immediately
- Document change in change management system

### 8.2 Emergency Access ("Break-Glass")

**Purpose:**
- Emergency administrator access when primary authentication unavailable
- Last resort during disaster recovery or major incident

**Controls:**
- Password sealed in envelope, stored in physical safe
- Safe requires two keys (CEO and CISO)
- Envelope seal broken only in documented emergency
- Password changed immediately after use
- All actions logged and reviewed
- Incident report required explaining use

**Break-Glass Scenarios:**
- Primary authentication system failure (Active Directory down)
- Administrator account lockout during critical incident
- Disaster recovery (all administrators unavailable)
- Ransomware affecting authentication systems

### 8.3 API Keys and Application Secrets

**Not Traditional Passwords But Similar Protection:**
- **API Keys:** Credentials for application-to-application communication
- **Database Connection Strings:** Include username/password
- **Encryption Keys:** Protect encrypted data
- **SSH Private Keys:** Authenticate SSH connections
- **Certificates and Private Keys:** TLS/SSL certificates

**Requirements:**
- **Generation:** Cryptographically random (minimum 32 characters for API keys)
- **Storage:** Encrypted vault or secrets management system (HashiCorp Vault, AWS Secrets Manager, Azure Key Vault)
- **Transmission:** Encrypted channels only (TLS, SSH)
- **Access Control:** Least privilege (only applications/users needing access)
- **Rotation:** Regular rotation (90-365 days depending on use)
- **Monitoring:** Log all access and usage
- **Revocation:** Immediately revoke if compromised

**Prohibited:**
- Hardcoding secrets in source code
- Storing secrets in version control (Git repositories)
- Sending secrets via email or chat
- Storing secrets in unencrypted files

---

## 9. Password Policy Enforcement

### 9.1 Technical Controls

**Active Directory / LDAP:**
- Password complexity enforced via Group Policy
- Password history (last 12 passwords)
- Minimum/maximum password age
- Account lockout policy
- Fine-Grained Password Policy for privileged accounts

**Web Applications:**
- Password strength meter (visual feedback during creation)
- Complexity validation (frontend and backend)
- Password breach check (compare against known breached passwords database - Have I Been Pwned API)
- Session timeout enforcement
- MFA enforcement for sensitive actions

**Password Hashing:**
- Passwords never stored in plaintext
- Hashing algorithms:
  - **Preferred:** bcrypt (cost factor 12+), Argon2id
  - **Acceptable:** PBKDF2 (100,000+ iterations), scrypt
  - **Prohibited:** MD5, SHA1, plain SHA256 (no salt/iterations)
- Salted hashes (unique salt per password)
- Pepper (global secret) for additional protection

**Implementation Reference:**
```javascript
// Example from User model (see src/api/models/User.js)
const bcrypt = require('bcryptjs');
const saltRounds = 12;

// Hash password before storing
userSchema.pre('save', async function(next) {
  if (!this.isModified('password')) return next();
  this.password = await bcrypt.hash(this.password, saltRounds);
  next();
});

// Compare password for authentication
userSchema.methods.comparePassword = async function(candidatePassword) {
  return await bcrypt.compare(candidatePassword, this.password);
};
```

### 9.2 Monitoring and Auditing

**Password-Related Events Logged:**
- Password changes (successful and failed)
- Password resets (self-service and helpdesk)
- Failed login attempts
- Account lockouts and unlocks
- MFA enrollment and changes
- Privilege escalation (sudo, run as administrator)
- Password policy violations

**SIEM Alerts:**
- Multiple failed logins (potential brute force)
- Multiple account lockouts (potential password spray attack)
- Login from unusual location or device
- Administrative password reset (require justification)
- Privileged account usage outside normal hours
- Multiple users locked out simultaneously (potential attack)

**Regular Reviews:**
- Quarterly access review (verify users still require access)
- Monthly privileged account review (verify admin access still needed)
- Immediate review after security incidents
- Annual comprehensive audit (password policy compliance)

---

## 10. User Education and Awareness

### 10.1 Training

**New Employees:**
- Password policy overview during onboarding (30 minutes)
- How to create strong passwords
- Password manager setup and usage
- MFA enrollment
- Phishing awareness (password theft)

**All Employees (Annual Refresher):**
- Password best practices reminder
- Latest threats (credential stuffing, phishing trends)
- MFA importance
- Incident case studies (anonymized)

**Specialized Training:**
- Administrators: Privileged access management, break-glass procedures
- Developers: Secrets management, secure password storage
- Managers: Enforcement responsibilities, suspicious activity recognition

### 10.2 Awareness Materials

**Available Resources:**
- Password policy quick reference guide (1-page PDF)
- Strong password creation guide
- Password manager setup videos
- MFA enrollment instructions
- Phishing identification tips
- Security awareness posters (break rooms, near workstations)

**Communication Channels:**
- Monthly security newsletter
- Intranet security page
- Email reminders before password expiration
- Login screen password tips
- IT helpdesk knowledge base

---

## 11. Exceptions and Waivers

### 11.1 Exception Process

**When Exceptions May Be Granted:**
- Legacy system technical limitations
- Vendor-required password formats
- Specific compliance requirements
- Emergency business need

**Exception Request:**
1. Submit written request to CISO
2. Include:
   - System/account affected
   - Specific requirement that cannot be met
   - Business justification
   - Proposed compensating controls
   - Risk assessment
   - Duration of exception (maximum 6 months)
3. CISO review and risk evaluation
4. Approval or denial with documentation
5. If approved: Implement compensating controls, schedule review

**Compensating Controls:**
- Enhanced monitoring
- Restricted access (IP whitelisting, network segmentation)
- Additional authentication factor
- Reduced permission scope
- Frequent password changes
- Regular audits

**Example Exceptions:**
- OT system supporting maximum 8-character passwords → Compensate with physical access control + isolated network
- Service account requiring static password → Compensate with secrets vault + enhanced logging
- Legacy application without MFA support → Compensate with IP whitelisting + frequent password changes

### 11.2 Exception Review

- Exceptions reviewed quarterly
- Renewal required every 6 months (justify ongoing need)
- Exceptions revoked when:
  - Compensating controls fail
  - System upgrade enables compliance
  - Business need no longer exists
  - Risk level becomes unacceptable

---

## 12. Password Policy Violations

### 12.1 Common Violations

**Severity: Low (Warning)**
- Weak password not meeting complexity (detected by system)
- Writing password down (not in secure location)
- Using same password for internal and external accounts (detected via breach monitoring)

**Severity: Medium (Written Warning, Retraining)**
- Sharing password with colleague (even with good intentions)
- Reusing old passwords (attempting to circumvent history)
- Not changing password after suspected compromise
- Storing passwords in unencrypted file

**Severity: High (Suspension, Possible Termination)**
- Sharing administrative password
- Intentionally weak password for convenience after multiple warnings
- Failing to report known compromise
- Malicious password sharing (enabling unauthorized access)

### 12.2 Remediation

**Immediate Actions:**
- Force password reset
- Suspend compromised account
- Review account activity (identify unauthorized actions)
- Notify affected users/systems
- Disciplinary process per HR policy

**Follow-up:**
- Mandatory retraining
- Enhanced monitoring (90 days)
- Report to management
- Document in employee file

---

## 13. Related Policies and Standards

**Internal Documents:**
- Information Security Policy (ISP-001)
- Acceptable Use Policy (AUP-001)
- Access Control Policy
- Incident Response Procedure

**External Standards:**
- ISO/IEC 27001:2022 - Annex A 5.17 (Authentication Information)
- NIST SP 800-63B - Digital Identity Guidelines (Authentication and Lifecycle Management)
- CIS Controls v8 - Control 6 (Access Control Management)
- GDPR Article 32 - Security of Processing

---

## 14. Policy Review and Updates

**Review Frequency:** Annual or when triggered by:
- Security incidents involving passwords
- Changes in threat landscape
- Technology updates (new authentication methods)
- Regulatory changes
- User feedback and usability concerns

**Update Process:**
1. CISO initiates review
2. Consultation with IT, security team, users
3. Draft updates
4. Management approval
5. User communication
6. Training updates
7. Technical implementation
8. Monitoring and feedback

---

## 15. Contact Information

**Questions or Issues:**
- **IT Helpdesk:** helpdesk@zabalagailetak.com | +34 XXX XXX XXX
- **Password Resets:** https://password.zabalagailetak.com
- **CISO:** ciso@zabalagailetak.com | +34 XXX XXX XXX
- **Security Incidents:** security@zabalagailetak.com | +34 XXX XXX XXX (24/7)

---

## Appendix A: Password Policy Quick Reference

### For Standard Users:
- ✅ **Minimum 12 characters** (longer is better!)
- ✅ **Mix of uppercase, lowercase, numbers, special characters**
- ✅ **Unique password for each system**
- ✅ **Use password manager**
- ✅ **Enable MFA everywhere possible**
- ✅ **Change every 90 days** (automatic reminder)
- ❌ **Never share passwords with anyone**
- ❌ **Never write passwords down**
- ❌ **Never reuse old passwords**
- ❌ **Never use personal info (name, birth date)**

### For Administrators:
- ✅ **Minimum 14 characters**
- ✅ **All character types required**
- ✅ **Separate admin account**
- ✅ **Hardware MFA token (YubiKey)**
- ✅ **Change every 60 days**
- ❌ **Never share admin passwords**
- ❌ **Never use admin account for daily work**

---

## Appendix B: Password Strength Examples

| Password | Strength | Why? |
|----------|----------|------|
| `password` | ❌ Very Weak | Common word, in dictionary |
| `Password123` | ❌ Weak | Common pattern, predictable |
| `Zabala2026!` | ⚠️ Poor | Company name, year, single special char |
| `JohnSmith1975` | ⚠️ Poor | Personal info (name, birth year) |
| `C0ff33&C00k!es` | ✅ Good | 14 chars, mixed case, numbers, special, but predictable substitutions |
| `Blue#Elephant$Runs77` | ✅ Strong | 21 chars, random words, numbers, special |
| `MyD0gLovesZabalaCookies!` | ✅ Strong | 24 chars, passphrase, memorable |
| `Xk9$mP2#vQ7!nR5@wL3%` | ✅ Very Strong | 20 chars, fully random (use password manager) |

---

## Appendix C: Password Manager Setup Guide

### 1Password Setup (Recommended):
1. IT admin creates 1Password Business account for you
2. You receive invitation email
3. Click link and create Master Password:
   - Minimum 16 characters
   - Use passphrase or very strong password
   - Write down ONLY master password in secure location (home safe)
   - Enable MFA on 1Password account
4. Install 1Password app:
   - Desktop: Windows/Mac/Linux
   - Browser extension: Chrome/Firefox/Edge
   - Mobile: iOS/Android
5. Store work passwords in "Work" vault (shared with IT if needed)
6. Store personal passwords in "Personal" vault (private)
7. Generate new strong passwords when changing work passwords

### Using 1Password:
- **Auto-fill:** Browser extension detects login pages, offers to fill credentials
- **Generate:** Click "Generate Password" when creating new accounts
- **Security Check:** Identifies weak, reused, or compromised passwords
- **Emergency Access:** Designate trusted person (IT manager) as emergency contact

---

## Appendix D: MFA Enrollment Instructions

### Microsoft Authenticator Setup:
1. Install Microsoft Authenticator app on smartphone:
   - iOS: App Store
   - Android: Google Play Store
2. Open app, click "+" to add account
3. Select "Work or school account"
4. Scan QR code displayed during enrollment:
   - Visit https://aka.ms/mfasetup
   - Or navigate to Account Settings → Security Info → Add Method
5. App displays 6-digit code (changes every 30 seconds)
6. Enter code to verify setup
7. Enable push notifications for easier login approval
8. **IMPORTANT:** Save backup codes in password manager (for device loss)

### Backup Methods:
- Enroll second device (tablet, second phone)
- Add backup phone number for SMS (use only as last resort)
- Print backup codes and store securely at home

---

## Appendix E: Incident Response - Compromised Password

**If you suspect your password is compromised:**

1. **STOP using the account immediately**
2. **CHANGE password immediately:**
   - Self-service: https://password.zabalagailetak.com
   - Or call IT helpdesk: +34 XXX XXX XXX
3. **REPORT to security team:**
   - Email: security@zabalagailetak.com
   - Phone: +34 XXX XXX XXX (24/7)
4. **PROVIDE details:**
   - When did you suspect compromise?
   - How did you discover it? (unusual account activity, phishing email, malware alert)
   - What systems/accounts affected?
   - What actions might attacker have taken?
5. **COOPERATE with investigation:**
   - Security team may ask for:
     - Recent login history review
     - Device forensic scan
     - Access to related accounts
   - Do not delete anything (evidence preservation)
6. **FOLLOW-UP:**
   - Change passwords on ALL accounts using same password (work and personal)
   - Review account activity for unauthorized actions
   - Enable MFA if not already enabled
   - Enroll in password manager

**Signs your password may be compromised:**
- Unexplained account lockouts
- Login notifications from unusual locations or times
- Account activity you don't recognize (emails sent, files accessed)
- Unusual system behavior
- Received email saying "Your password was changed" (but you didn't change it)
- Found username/password in data breach notification

---

**END OF PASSWORD POLICY**

---

**ACKNOWLEDGMENT**

I acknowledge that I have read and understood the Zabala Gailetak Password Policy (PWD-001). I agree to comply with all password requirements and understand that violations may result in disciplinary action.

**Employee Name:** ___________________________  
**Employee ID:** ___________________________  
**Signature:** ___________________________  
**Date:** ___________________________
