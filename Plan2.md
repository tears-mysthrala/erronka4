# Plan2.md - Remaining Tasks for Zabala Gailetak

## Executive Summary

**Current Status:** ~83% complete  
**Remaining Work:** ~17% (Priority tasks for project completion)

---

## 1. LOAD TESTING IMPLEMENTATION

### File: `tests/load/api-load-test.js`
```javascript
import http from 'k6/http';
import { check, sleep } from 'k6';

export let options = {
  stages: [
    { duration: '2m', target: 100 },
    { duration: '5m', target: 100 },
    { duration: '2m', target: 200 },
    { duration: '5m', target: 200 },
    { duration: '2m', target: 0 },
  ],
  thresholds: {
    http_req_duration: ['p(95)<500'],
    http_req_failed: ['rate<0.01'],
  },
};

export default function () {
  let loginRes = http.post('http://localhost:3000/api/auth/login', {
    username: 'loadtest',
    password: 'LoadTest123!'
  });
  
  check(loginRes, {
    'login status is 200': (r) => r.status === 200,
    'login response time < 500ms': (r) => r.timings.duration < 500,
  });
  
  let token = loginRes.json('token');
  
  let productsRes = http.get('http://localhost:3000/api/products', {
    headers: { Authorization: `Bearer ${token}` }
  });
  
  check(productsRes, {
    'products status is 200': (r) => r.status === 200,
  });
  
  sleep(1);
}
```

### File: `tests/load/websocket-load-test.js`
```javascript
import ws from 'k6/ws';
import { check } from 'k6';

export let options = {
  stages: [
    { duration: '1m', target: 50 },
    { duration: '5m', target: 50 },
    { duration: '1m', target: 0 },
  ],
  maxVUs: 100,
};

export default function () {
  let url = 'ws://localhost:3000/api/ws';
  let res = ws.connect(url, {});

  check(res, { 'websocket connected': (r) => r && r.status === 101 });
  
  res.close();
}
```

**Effort:** 4 hours

---

## 2. END-TO-END TESTING (Playwright)

### File: `tests/e2e/web/auth.spec.js`
```javascript
const { test, expect } = require('@playwright/test');

test.describe('Authentication Flow', () => {
  test('should complete full login with MFA', async ({ page }) => {
    await page.goto('http://localhost:3001/login');
    await page.fill('input[name="username"]', 'testuser');
    await page.fill('input[name="password"]', 'TestPass123!');
    await page.click('button[type="submit"]');
    await expect(page).toHaveURL(/.*mfa/);
    const totpCode = generateTestTOTP();
    await page.fill('input[name="mfaCode"]', totpCode);
    await page.click('button[type="submit"]');
    await expect(page).toHaveURL(/.*dashboard/);
  });
  
  test('should reject invalid MFA code', async ({ page }) => {
    // Test invalid code handling
  });
});

test.describe('Product Browsing', () => {
  test('should display products from API', async ({ page }) => {
    await page.goto('http://localhost:3001/products');
    await expect(page.locator('.product-card')).toHaveCount(10);
  });
});

test.describe('Order Flow', () => {
  test('should complete order placement', async ({ page }) => {
    // Login, add product to cart, checkout
  });
});
```

### File: `playwright.config.js`
```javascript
module.exports = {
  testDir: './tests/e2e',
  timeout: 30000,
  use: {
    baseURL: 'http://localhost:3001',
    headless: true,
  },
  projects: [
    { name: 'chromium', use: { browserName: 'chromium' } },
    { name: 'firefox', use: { browserName: 'firefox' } },
  ],
};
```

**Effort:** 8 hours

---

## 3. OT PRACTICAL IMPLEMENTATION

### File: `infrastructure/ot/docker-compose.ot.yml`
```yaml
version: '3.8'

services:
  openplc:
    image: openplcproject/openplc:v3
    container_name: zabala-openplc
    ports:
      - "8080:8080"
      - "502:502"
    volumes:
      - ./openplc/programs:/programs
      - openplc_data:/persistent
    networks:
      ot_network:
        ipv4_address: 192.168.50.10
    restart: unless-stopped
    
  scadabr:
    image: scadabr/scadabr:latest
    container_name: zabala-scadabr
    ports:
      - "9090:8080"
    depends_on:
      - openplc
    networks:
      - ot_network
    restart: unless-stopped

networks:
  ot_network:
    driver: bridge
    internal: true
    ipam:
      config:
        - subnet: 192.168.50.0/24

volumes:
  openplc_data:
```

### File: `infrastructure/ot/openplc/programs/cookie_production.st`
```structured-text
PROGRAM CookieProduction
  VAR
    StartButton : BOOL;
    StopButton : BOOL;
    EmergencyStop : BOOL;
    OvenTemperature : REAL;
    ConveyorSpeed : REAL;
    OvenHeater : BOOL;
    ConveyorMotor : BOOL;
    AlarmLight : BOOL;
  END_VAR
  
  IF EmergencyStop THEN
    OvenHeater := FALSE;
    ConveyorMotor := FALSE;
    AlarmLight := TRUE;
    RETURN;
  END_IF;
  
  IF StartButton AND NOT StopButton THEN
    IF OvenTemperature < 180 THEN
      OvenHeater := TRUE;
    ELSE
      OvenHeater := FALSE;
    END_IF;
    ConveyorMotor := TRUE;
  ELSE
    OvenHeater := FALSE;
    ConveyorMotor := FALSE;
  END_IF;
END_PROGRAM
```

**Effort:** 10 hours

---

## 4. MISSING SOPs

### File: `infrastructure/systems/sop_backup_recovery.md`
```markdown
# Backup & Recovery SOP - Zabala Gailetak

## 1. Backup Schedule
| Type | Frequency | Retention |
|------|-----------|-----------|
| Full | Weekly (Sunday 2:00 AM) | 12 months |
| Incremental | Daily (2:00 AM) | 30 days |
| Transaction logs | Every 15 minutes | 7 days |

## 2. Backup Procedures
1. Verify sufficient disk space
2. Stop non-essential services
3. Create snapshots
4. Transfer to backup server
5. Verify checksums

## 3. Recovery Procedures
1. Assess damage and select restore point
2. Notify stakeholders
3. Restore from backup
4. Verify data integrity
5. Resume operations

## 4. Testing Schedule
- Weekly: Verify backup completion
- Monthly: Test restoration in dev environment
- Quarterly: Full DR drill
```

### File: `infrastructure/systems/sop_patch_management.md`
```markdown
# Patch Management SOP - Zabala Gailetak

## 1. Vulnerability Scanning
- Weekly scans using OpenVAS
- Critical findings reported immediately

## 2. Patch Classification
| Severity | CVSS | Response Time |
|----------|------|---------------|
| Critical | 9.0-10.0 | 72 hours |
| High | 7.0-8.9 | 7 days |
| Medium | 4.0-6.9 | 30 days |
| Low | 0.1-3.9 | 90 days |

## 3. Deployment Process
1. Test patches in development
2. Deploy to staging
3. Monitor for issues
4. Deploy to production
5. Document changes
```

### File: `infrastructure/systems/sop_user_access.md`
```markdown
# User Access Management SOP - Zabala Gailetak

## 1. User Provisioning
1. Receive request from manager
2. Verify identity documents
3. Create account with minimal privileges
4. Schedule security awareness training
5. Document in access log

## 2. Access Review
- Quarterly review of all accounts
- Monthly review of privileged accounts
- Immediate revocation upon termination

## 3. Role-Based Access Control
| Role | Permissions |
|------|-------------|
| User | Read own data, place orders |
| Manager | Read team data, approve requests |
| Admin | Full system access |
| Auditor | Read-only, all data |
```

### File: `infrastructure/systems/sop_change_management.md`
```markdown
# Change Management SOP - Zabala Gailetak

## 1. Change Request Process
1. Submit RFC via ticketing system
2. CAB review and approval
3. Schedule change window
4. Implement in dev → staging → prod
5. Post-implementation review

## 2. Emergency Changes
- Exempt from normal CAB process
- Must be documented within 24 hours
- Requires post-implementation review within 72 hours
```

### File: `security/sop_security_awareness.md`
```markdown
# Security Awareness Training SOP - Zabala Gailetak

## 1. Training Requirements
- All employees: Annual mandatory training
- Developers: Secure coding practices
- IT Staff: Advanced security topics
- Management: Risk awareness

## 2. Phishing Simulation
- Monthly simulated phishing emails
- Track click rates
- Provide immediate training for failures

## 3. Compliance Tracking
- Maintain training records
- Generate compliance reports
- Follow up with non-compliant employees
```

**Effort:** 16 hours

---

## 5. FORENSICS TOOLKIT

### File: `security/forensics/toolkit/install-tools.sh`
```bash
#!/bin/bash
set -e

echo "[+] Installing Zabala Gailetak Forensics Toolkit"

# Disk forensics
apt-get install -y sleuthkit autopsy foremost testdisk photorec

# Memory forensics
pip3 install volatility3

# Network forensics
apt-get install -y wireshark tcpdump tshark

# Log analysis
apt-get install -y jq

# Create evidence directory
mkdir -p /evidence/{disk,memory,network,mobile,logs}
chmod 700 /evidence

echo "[✓] Forensics toolkit installation complete"
```

### File: `security/forensics/toolkit/memory-dump.sh`
```bash
#!/bin/bash
CASE_ID=$1
OUTPUT_DIR="/evidence/memory/$CASE_ID"
mkdir -p "$OUTPUT_DIR"

echo "[+] Acquiring memory dump"
# LiME command for memory acquisition
# sha256sum for chain of custody

echo "[✓] Memory dump complete: $OUTPUT_DIR"
```

### File: `security/forensics/reports/forensic_report_template.md`
```markdown
# Forensic Investigation Report

**Case ID:** [CASE-YYYY-MMDD-XXX]  
**Investigator:** [Name, Certification]  
**Date:** [YYYY-MM-DD]  

## 1. Executive Summary
[Summary of incident and findings]

## 2. Scope of Investigation
- Systems Examined: [List]
- Time Period: [Dates]
- Evidence Collected: [Count]

## 3. Chain of Custody
[Table of evidence handling]

## 4. Analysis Findings
[Timeline, artifacts, attacker profile]

## 5. Conclusions and Recommendations
```

**Effort:** 8 hours

---

## Summary

| Task | Effort | Priority |
|------|--------|----------|
| Load Testing | 4h | High |
| E2E Testing | 8h | High |
| OT Implementation | 10h | High |
| Missing SOPs | 16h | Medium |
| Forensics Toolkit | 8h | Medium |

**Total Estimated Effort:** 46 hours
