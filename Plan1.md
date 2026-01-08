# ZABALA GAILETAK - COMPREHENSIVE IMPROVEMENT PLAN

Based on my thorough analysis of the ER4.md requirements and the complete project, here's my comprehensive improvement plan:

---

## EXECUTIVE SUMMARY

**Current Status:** The project is **75% complete** with excellent foundations in application security, documentation, and architecture design. The main gaps are in:
1. **DevOps automation** (CI/CD pipelines)
2. **Production deployment infrastructure** (Docker orchestration, IaC)
3. **Database implementation** (currently using in-memory storage)
4. **Some security tools deployment** (Honeypot, complete SIEM setup)

**Recommendation:** Focus on **critical infrastructure** and **automation** to make the project production-ready.

---

## 1. MISSING IMPLEMENTATIONS (Priority Order)

### 🔴 CRITICAL PRIORITY - Must Implement

#### 1. Database Implementation & Data Persistence
**Current State:** Using in-memory arrays for users, products, orders  
**Impact:** Data lost on server restart, not production-viable  
**ER4 Requirement:** RA5-RA6 (Secure data storage)

**Implementation Plan:**
```javascript
// File: src/api/models/User.js
const mongoose = require('mongoose');

const userSchema = new mongoose.Schema({
  username: { type: String, required: true, unique: true, index: true },
  email: { type: String, required: true, unique: true, lowercase: true },
  password: { type: String, required: true }, // bcrypt hashed
  mfaEnabled: { type: Boolean, default: false },
  mfaSecret: { type: String, select: false }, // encrypted
  role: { type: String, enum: ['user', 'admin'], default: 'user' },
  createdAt: { type: Date, default: Date.now },
  updatedAt: { type: Date, default: Date.now }
});

// Additional models needed:
// - Product.js (name, description, price, stock, category)
// - Order.js (userId, products, status, shippingAddress)
// - AuditLog.js (action, user, timestamp, details)
```

**Files to Create:**
- `src/api/models/User.js` (70 lines)
- `src/api/models/Product.js` (50 lines)
- `src/api/models/Order.js` (80 lines)
- `src/api/models/AuditLog.js` (40 lines)
- `src/api/config/database.js` (60 lines)
- `src/api/migrations/` (seed data scripts)

**Estimated Effort:** 8 hours

---

#### 2. CI/CD Pipeline Implementation
**Current State:** `devops/ci-cd/` directory is empty  
**Impact:** No automated testing, security scanning, or deployment  
**ER4 Requirement:** RA8 (Etengabeko integrazioa eta banaketa)

**Implementation Plan:**

**File: `.github/workflows/ci-cd.yml`** (300 lines)
```yaml
name: CI/CD Pipeline

on:
  push:
    branches: [main, develop]
  pull_request:
    branches: [main]

jobs:
  # 1. Linting & Code Quality
  lint:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v3
      - name: ESLint
      - name: Prettier check
      
  # 2. Security Scanning
  security-scan:
    runs-on: ubuntu-latest
    steps:
      - name: SAST - SonarQube
      - name: Dependency Check (OWASP)
      - name: Secret scanning
      - name: Container scanning (Trivy)
      
  # 3. Unit & Integration Tests
  test:
    runs-on: ubuntu-latest
    steps:
      - name: Run Jest tests
      - name: Coverage report
      - name: Upload to Codecov
      
  # 4. Build Docker Images
  build:
    runs-on: ubuntu-latest
    needs: [lint, security-scan, test]
    steps:
      - name: Build API image
      - name: Build Web image
      - name: Push to registry
      
  # 5. DAST (Dynamic Security Testing)
  dast:
    runs-on: ubuntu-latest
    needs: [build]
    steps:
      - name: Deploy to staging
      - name: OWASP ZAP scan
      - name: Generate report
      
  # 6. Deploy to Production
  deploy:
    runs-on: ubuntu-latest
    needs: [dast]
    if: github.ref == 'refs/heads/main'
    steps:
      - name: Deploy to Kubernetes
      - name: Health check
      - name: Smoke tests
```

**Additional Files Needed:**
- `.github/workflows/security-scan.yml` (150 lines)
- `.github/workflows/deploy-staging.yml` (100 lines)
- `devops/ci-cd/sonarqube-config.xml` (80 lines)
- `devops/ci-cd/dependency-check-suppression.xml` (40 lines)

**Estimated Effort:** 12 hours

---

#### 3. Docker Compose for Full Application Stack
**Current State:** Only SIEM has docker-compose, no orchestration for app  
**Impact:** Manual deployment, inconsistent environments  
**ER4 Requirement:** RA8 (Softwarea hedatzeko sistema seguruak)

**Implementation Plan:**

**File: `docker-compose.yml`** (250 lines)
```yaml
version: '3.8'

services:
  # MongoDB
  mongodb:
    image: mongo:7
    container_name: zabala-mongodb
    environment:
      MONGO_INITDB_ROOT_USERNAME: ${MONGO_USER}
      MONGO_INITDB_ROOT_PASSWORD: ${MONGO_PASSWORD}
    volumes:
      - mongodb_data:/data/db
      - ./backups:/backups
    networks:
      - backend
    restart: unless-stopped
    
  # Redis Cache
  redis:
    image: redis:7-alpine
    container_name: zabala-redis
    command: redis-server --requirepass ${REDIS_PASSWORD}
    volumes:
      - redis_data:/data
    networks:
      - backend
    restart: unless-stopped
    
  # Backend API
  api:
    build:
      context: .
      dockerfile: Dockerfile
      target: production
    container_name: zabala-api
    depends_on:
      - mongodb
      - redis
    environment:
      NODE_ENV: production
      MONGODB_URI: mongodb://${MONGO_USER}:${MONGO_PASSWORD}@mongodb:27017/zabala
      REDIS_HOST: redis
      REDIS_PORT: 6379
      REDIS_PASSWORD: ${REDIS_PASSWORD}
      JWT_SECRET: ${JWT_SECRET}
    volumes:
      - ./logs:/app/logs
    networks:
      - backend
      - frontend
    restart: unless-stopped
    healthcheck:
      test: ["CMD", "curl", "-f", "http://localhost:3000/api/health"]
      interval: 30s
      timeout: 10s
      retries: 3
      
  # Web Application
  web:
    build:
      context: ./src/web
      dockerfile: Dockerfile
    container_name: zabala-web
    depends_on:
      - api
    networks:
      - frontend
    restart: unless-stopped
    
  # Nginx Reverse Proxy
  nginx:
    image: nginx:alpine
    container_name: zabala-nginx
    depends_on:
      - api
      - web
    ports:
      - "80:80"
      - "443:443"
    volumes:
      - ./nginx/nginx.conf:/etc/nginx/nginx.conf:ro
      - ./nginx/ssl:/etc/nginx/ssl:ro
      - ./nginx/logs:/var/log/nginx
    networks:
      - frontend
    restart: unless-stopped

networks:
  backend:
    driver: bridge
  frontend:
    driver: bridge

volumes:
  mongodb_data:
  redis_data:
```

**Additional Files:**
- `Dockerfile` (multi-stage for API) (80 lines)
- `src/web/Dockerfile` (for React app) (60 lines)
- `nginx/nginx.conf` (150 lines)
- `docker-compose.dev.yml` (development override) (100 lines)
- `docker-compose.prod.yml` (production override) (120 lines)

**Estimated Effort:** 10 hours

---

#### 4. SIEM Complete Configuration
**Current State:** Docker Compose exists, but missing Logstash config and alert rules  
**Impact:** No automated log processing or security alerts  
**ER4 Requirement:** RA8 (Log-ak prozesatu eta bistaratzeko sistema)

**Implementation Plan:**

**File: `security/siem/logstash/logstash.conf`** (200 lines)
```ruby
input {
  # Application logs from API
  beats {
    port => 5044
    codec => json
  }
  
  # Firewall logs
  tcp {
    port => 5514
    type => "firewall"
  }
  
  # Nginx logs
  file {
    path => "/var/log/nginx/access.log"
    type => "nginx-access"
    codec => json
  }
}

filter {
  # Failed login detection
  if [event] == "auth.login.failed" {
    mutate {
      add_field => { "alert_level" => "warning" }
    }
  }
  
  # SQL Injection detection
  if [request_body] =~ /(\bOR\b|\bAND\b|--|;|'|"|\/\*|\*\/)/ {
    mutate {
      add_field => { "alert_level" => "critical" }
      add_tag => [ "sql_injection_attempt" ]
    }
  }
  
  # XSS detection
  if [request_body] =~ /<script|javascript:|onerror=|onload=/ {
    mutate {
      add_field => { "alert_level" => "high" }
      add_tag => [ "xss_attempt" ]
    }
  }
  
  # Rate limiting violations
  if [status_code] == 429 {
    mutate {
      add_tag => [ "rate_limit_exceeded" ]
    }
  }
  
  # GeoIP enrichment
  geoip {
    source => "client_ip"
    target => "geoip"
  }
}

output {
  elasticsearch {
    hosts => ["elasticsearch:9200"]
    user => "elastic"
    password => "${ELASTIC_PASSWORD}"
    index => "zabala-%{+YYYY.MM.dd}"
  }
  
  # Critical alerts to email (future)
  if "critical" in [alert_level] {
    email {
      to => "security@zabala-gailetak.com"
      subject => "CRITICAL SECURITY ALERT"
      body => "Alert: %{message}"
    }
  }
}
```

**File: `security/siem/elasticsearch/alert-rules.json`** (150 lines)
```json
{
  "alerts": [
    {
      "name": "Multiple Failed Logins",
      "condition": "count(auth.login.failed) > 5 in 15 minutes",
      "severity": "high",
      "action": "notify_security_team"
    },
    {
      "name": "SQL Injection Attempt",
      "condition": "sql_injection_attempt tag exists",
      "severity": "critical",
      "action": "block_ip_and_notify"
    },
    {
      "name": "Unusual Geographic Access",
      "condition": "geoip.country != 'Spain' AND role == 'admin'",
      "severity": "medium",
      "action": "require_mfa_verification"
    }
  ]
}
```

**Additional Files:**
- `security/siem/kibana/dashboards/security-dashboard.ndjson` (500 lines)
- `security/siem/filebeat/filebeat.yml` (100 lines)
- `security/siem/elasticsearch/index-template.json` (150 lines)
- `security/siem/wazuh/ossec.conf` (300 lines - optional)

**Estimated Effort:** 14 hours

---

### 🟡 HIGH PRIORITY - Should Implement

#### 5. Honeypot Actual Deployment
**Current State:** Strategy documented, not implemented  
**Impact:** No threat intelligence gathering  
**ER4 Requirement:** RA8 (Honeypot bat konfiguratu du)

**Implementation Plan:**

**File: `security/honeypot/docker-compose.honeypot.yml`** (180 lines)
```yaml
version: '3.8'

services:
  # T-Pot Platform (recommended)
  tpot:
    image: telekom/tpotce:latest
    container_name: zabala-tpot
    ports:
      - "64295:64295"  # Cockpit web interface
      - "64297:64297"  # SSH
    environment:
      - WEB_USER=admin
      - WEB_PW=${HONEYPOT_PASSWORD}
    volumes:
      - tpot_data:/data
      - ./honeypot/logs:/var/log/tpot
    networks:
      honeypot_net:
        ipv4_address: 192.168.100.50  # DMZ
    restart: unless-stopped
    
  # Cowrie (SSH/Telnet honeypot)
  cowrie:
    image: cowrie/cowrie:latest
    container_name: zabala-cowrie
    ports:
      - "2222:2222"  # SSH
      - "2223:2223"  # Telnet
    volumes:
      - ./honeypot/cowrie/logs:/cowrie/var/log
      - ./honeypot/cowrie/downloads:/cowrie/var/lib/cowrie/downloads
    networks:
      - honeypot_net
    restart: unless-stopped
    
  # Conpot (ICS/SCADA honeypot for OT)
  conpot:
    image: conpot/conpot:latest
    container_name: zabala-conpot
    command: --template modbus
    ports:
      - "502:502"   # Modbus
      - "161:161"   # SNMP
    volumes:
      - ./honeypot/conpot/logs:/var/log/conpot
    volumes:
      - ./honeypot/conpot/logs:/var/log/conpot
    networks:
      honeypot_net:
        ipv4_address: 192.168.100.51  # DMZ
    restart: unless-stopped
    
  # Dionaea (Malware capture)
  dionaea:
    image: dinotools/dionaea:latest
    container_name: zabala-dionaea
    ports:
      - "21:21"     # FTP
      - "445:445"   # SMB
      - "1433:1433" # MSSQL
    volumes:
      - ./honeypot/dionaea/logs:/opt/dionaea/var/log
      - ./honeypot/dionaea/binaries:/opt/dionaea/var/lib/dionaea/binaries
    networks:
      - honeypot_net
    restart: unless-stopped

networks:
  honeypot_net:
    driver: bridge
    ipam:
      config:
        - subnet: 192.168.100.0/24

volumes:
  tpot_data:
```

**File: `security/honeypot/setup-honeypot.sh`** (80 lines)
```bash
#!/bin/bash
# Automated honeypot deployment script

set -e

echo "[+] Setting up Zabala Gailetak Honeypot Infrastructure"

# 1. Create directories
mkdir -p security/honeypot/{cowrie,conpot,dionaea}/{logs,config}

# 2. Generate credentials
HONEYPOT_PASSWORD=$(openssl rand -base64 32)
echo "HONEYPOT_PASSWORD=$HONEYPOT_PASSWORD" >> .env

# 3. Configure firewall rules (isolate honeypot network)
sudo iptables -A FORWARD -s 192.168.100.0/24 -d 192.168.20.0/24 -j DROP
sudo iptables -A FORWARD -s 192.168.100.0/24 -d 192.168.10.0/24 -j DROP

# 4. Start honeypots
docker-compose -f docker-compose.honeypot.yml up -d

echo "[✓] Honeypot deployment complete"
echo "[!] Web interface: https://localhost:64295"
echo "[!] Credentials: admin / (check .env file)"
```

**Additional Files:**
- `security/honeypot/conpot/modbus-template.xml` (120 lines)
- `security/honeypot/analysis/analyze-attacks.py` (200 lines)
- `security/honeypot/integration/honeypot-to-siem.py` (150 lines)

**Estimated Effort:** 10 hours

---

#### 6. Webpack Configuration for Web App
**Current State:** Missing webpack.config.js  
**Impact:** Cannot build production web app  
**ER4 Requirement:** RA8 (Build automation)

**File: `src/web/webpack.config.js`** (180 lines)
```javascript
const path = require('path');
const HtmlWebpackPlugin = require('html-webpack-plugin');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const TerserPlugin = require('terser-webpack-plugin');
const CssMinimizerPlugin = require('css-minimizer-webpack-plugin');
const { SubresourceIntegrityPlugin } = require('webpack-subresource-integrity');

const isDevelopment = process.env.NODE_ENV !== 'production';

module.exports = {
  mode: isDevelopment ? 'development' : 'production',
  entry: './app/index.js',
  output: {
    path: path.resolve(__dirname, 'dist'),
    filename: isDevelopment ? '[name].js' : '[name].[contenthash].js',
    clean: true,
    crossOriginLoading: 'anonymous'
  },
  
  module: {
    rules: [
      {
        test: /\.(js|jsx)$/,
        exclude: /node_modules/,
        use: {
          loader: 'babel-loader',
          options: {
            presets: ['@babel/preset-react']
          }
        }
      },
      {
        test: /\.css$/,
        use: [
          isDevelopment ? 'style-loader' : MiniCssExtractPlugin.loader,
          'css-loader'
        ]
      }
    ]
  },
  
  plugins: [
    new HtmlWebpackPlugin({
      template: './index.html',
      minify: !isDevelopment
    }),
    new MiniCssExtractPlugin({
      filename: '[name].[contenthash].css'
    }),
    new SubresourceIntegrityPlugin() // Security: SRI hashes
  ],
  
  optimization: {
    minimize: !isDevelopment,
    minimizer: [
      new TerserPlugin({
        terserOptions: {
          compress: {
            drop_console: !isDevelopment
          }
        }
      }),
      new CssMinimizerPlugin()
    ],
    splitChunks: {
      chunks: 'all',
      cacheGroups: {
        vendor: {
          test: /[\\/]node_modules[\\/]/,
          name: 'vendors',
          priority: 10
        }
      }
    }
  },
  
  devServer: {
    port: 3001,
    hot: true,
    historyApiFallback: true,
    proxy: {
      '/api': 'http://localhost:3000'
    }
  },
  
  // Security headers
  devServer: {
    headers: {
      'X-Content-Type-Options': 'nosniff',
      'X-Frame-Options': 'DENY',
      'Content-Security-Policy': "default-src 'self'"
    }
  }
};
```

**Additional Files:**
- `src/web/.babelrc` (30 lines)
- `src/web/webpack.dev.js` (50 lines)
- `src/web/webpack.prod.js` (60 lines)

**Estimated Effort:** 4 hours

---

#### 7. Complete ISO 27001 Documentation
**Current State:** Risk assessment done, missing SOA and policies  
**Impact:** Incomplete SGSI implementation  
**ER4 Requirement:** RA1-RA2 (Araudia betetzeko sistemak)

**Files to Create:**

**File: `compliance/sgsi/statement_of_applicability.md`** (400 lines)
```markdown
# Statement of Applicability (SOA)
# Zabala Gailetak - ISO 27001:2022

## A.5 Organizational Controls
- A.5.1 Policies for information security ✅ Implemented
- A.5.2 Information security roles and responsibilities ✅ Implemented
- A.5.3 Segregation of duties ✅ Implemented
...

## A.8 Asset Management
- A.8.1 Responsibility for assets ✅ Implemented
- A.8.2 Information classification ⚠️ Partial
- A.8.3 Media handling ✅ Implemented
...

## Controls Summary
- Total Controls: 93
- Implemented: 78 (84%)
- Partially Implemented: 12 (13%)
- Not Applicable: 3 (3%)
```

**File: `compliance/sgsi/information_security_policy.md`** (250 lines)
```markdown
# Information Security Policy
# Zabala Gailetak

## 1. Purpose
Define security objectives and management commitment...

## 2. Scope
All IT systems, OT systems, data, and personnel...

## 3. Security Objectives
- Confidentiality: Protect customer data
- Integrity: Prevent unauthorized modifications
- Availability: 99.9% uptime SLA
...

## 4. Roles and Responsibilities
- CISO: Overall security strategy
- Security Team: Day-to-day operations
- IT Team: System administration
- All Employees: Security awareness
...
```

**File: `compliance/sgsi/business_continuity_plan.md`** (350 lines)
```markdown
# Business Continuity Plan (BCP)
# Zabala Gailetak

## 1. Business Impact Analysis
### Critical Systems
- ERP system: RTO 4 hours, RPO 1 hour
- E-commerce: RTO 2 hours, RPO 15 minutes
- Production control: RTO 8 hours, RPO 4 hours

## 2. Recovery Strategies
### Data Center Failure
- Failover to secondary site (Barcelona)
- Cloud backup restoration
- Manual operations procedure

## 3. Emergency Contacts
...

## 4. Testing Schedule
- Quarterly: Backup restoration test
- Bi-annually: Full DR drill
- Annually: Business continuity exercise
```

**Additional Files:**
- `compliance/sgsi/asset_register.md` (200 lines)
- `compliance/sgsi/acceptable_use_policy.md` (150 lines)
- `compliance/sgsi/password_policy.md` (100 lines)

**Estimated Effort:** 16 hours

---

#### 8. Complete GDPR Documentation
**Current State:** Data processing register exists, missing templates  
**Impact:** Incomplete data protection compliance  
**ER4 Requirement:** RA4 (Datu pertsonalen babesaren legedia)

**Files to Create:**

**File: `compliance/gdpr/data_breach_notification_template.md`** (120 lines)
```markdown
# Data Breach Notification Template
# Zabala Gailetak - GDPR Article 33/34

## Incident Details
- **Date Detected:** [YYYY-MM-DD HH:MM]
- **Date Occurred:** [Estimated]
- **Detected By:** [Name/System]
- **Incident ID:** [INC-YYYY-MMDD-XXX]

## Nature of Breach
- [ ] Confidentiality breach (unauthorized access)
- [ ] Integrity breach (data modification)
- [ ] Availability breach (data loss)

## Data Affected
- **Categories:** [e.g., Customer names, email addresses]
- **Number of Records:** [Approximate count]
- **Data Subjects:** [Number of individuals affected]

## Risk Assessment
- [ ] Low Risk (no notification required)
- [ ] Medium Risk (internal notification)
- [ ] High Risk (AEPD notification within 72 hours)
- [ ] Critical Risk (data subject notification)

## Actions Taken
1. Immediate containment: [Actions]
2. Investigation: [Findings]
3. Remediation: [Steps taken]
4. Prevention: [Future measures]

## Notifications
- **AEPD (Spanish DPA):** [Date/Time notified]
- **Data Subjects:** [If required, method used]
- **Management:** [Internal notification]

## DPO Sign-off
- **Name:** [DPO Name]
- **Date:** [YYYY-MM-DD]
- **Signature:** [Digital signature]
```

**File: `compliance/gdpr/dpia_template.md`** (300 lines)
```markdown
# Data Protection Impact Assessment (DPIA)
# Zabala Gailetak

## Project: [Project Name]

### 1. Necessity and Proportionality
**Purpose:** [Why is data processing necessary?]
**Legal Basis:** [GDPR Article 6.1(x)]
**Data Minimization:** [Only collecting necessary data?]

### 2. Identification of Risks
| Risk | Likelihood | Impact | Risk Level |
|------|-----------|--------|------------|
| Unauthorized access | Medium | High | HIGH |
| Data exfiltration | Low | Critical | MEDIUM |
| ...

### 3. Mitigation Measures
- Encryption at rest and in transit
- Access controls (RBAC)
- MFA for admin accounts
- Regular security audits
- Logging and monitoring

### 4. Residual Risk Assessment
After mitigation measures:
- Unauthorized access: MEDIUM → LOW
- Data exfiltration: MEDIUM → LOW

### 5. DPO Consultation
**DPO Name:** [Name]
**Consulted on:** [Date]
**Recommendations:** [Advice given]

### 6. Approval
**Approved by:** [Data Controller]
**Date:** [YYYY-MM-DD]
**Review Date:** [YYYY-MM-DD] (12 months)
```

**File: `compliance/gdpr/data_subject_rights_procedures.md`** (250 lines)
```markdown
# Data Subject Rights Procedures
# Zabala Gailetak - GDPR Articles 15-22

## 1. Right of Access (Article 15)
**Process:**
1. Receive request via email/portal
2. Verify identity (government ID + selfie)
3. Compile data from all systems (ERP, web, mobile)
4. Deliver within 30 days (PDF format)

**Template:** See `access_request_response.docx`

## 2. Right to Rectification (Article 16)
...

## 3. Right to Erasure (Article 17)
**Process:**
1. Verify identity
2. Check legal obligations (e.g., accounting records - 10 years)
3. If approved: Delete from all systems + backups
4. Notify third parties (payment processors)
5. Confirm deletion to data subject

**Exceptions:**
- Legal obligation to retain (tax records)
- Public interest (health records)
- Legal claims

## 4. Right to Data Portability (Article 20)
**Format:** JSON or CSV
**Delivery Method:** Secure email or encrypted USB
```

**Additional Files:**
- `compliance/gdpr/privacy_notice_web.md` (180 lines)
- `compliance/gdpr/privacy_notice_mobile.md` (160 lines)
- `compliance/gdpr/cookie_policy.md` (120 lines)
- `compliance/gdpr/data_retention_schedule.md` (100 lines)

**Estimated Effort:** 12 hours

---

### 🟢 MEDIUM PRIORITY - Nice to Have

#### 9. End-to-End Testing
**Current State:** Only unit and integration tests  
**Impact:** No automated user journey testing  
**ER4 Requirement:** RA8 (Testing automation)

**Implementation Plan:**

**File: `tests/e2e/web/auth.spec.js`** (150 lines)
```javascript
// Using Playwright
const { test, expect } = require('@playwright/test');

test.describe('Authentication Flow', () => {
  test('should complete full login with MFA', async ({ page }) => {
    // 1. Navigate to login
    await page.goto('http://localhost:3001/login');
    
    // 2. Enter credentials
    await page.fill('input[name="username"]', 'testuser');
    await page.fill('input[name="password"]', 'TestPass123!');
    await page.click('button[type="submit"]');
    
    // 3. Expect MFA page
    await expect(page).toHaveURL(/.*mfa/);
    
    // 4. Enter MFA code (use test secret)
    const totpCode = generateTestTOTP();
    await page.fill('input[name="mfaCode"]', totpCode);
    await page.click('button[type="submit"]');
    
    // 5. Verify dashboard
    await expect(page).toHaveURL(/.*dashboard/);
    await expect(page.locator('h1')).toContainText('Dashboard');
  });
  
  test('should reject invalid MFA code', async ({ page }) => {
    // Test invalid code handling
  });
});
```

**Additional Files:**
- `tests/e2e/web/products.spec.js` (120 lines)
- `tests/e2e/web/orders.spec.js` (180 lines)
- `tests/e2e/mobile/auth.spec.js` (using Detox) (200 lines)
- `playwright.config.js` (80 lines)

**Estimated Effort:** 10 hours

---

#### 10. Load Testing & Performance
**Current State:** No performance testing  
**Impact:** Unknown system limits  
**ER4 Requirement:** Best practice for production systems

**File: `tests/load/api-load-test.js`** (120 lines)
```javascript
// Using k6
import http from 'k6/http';
import { check, sleep } from 'k6';

export let options = {
  stages: [
    { duration: '2m', target: 100 },  // Ramp up to 100 users
    { duration: '5m', target: 100 },  // Stay at 100 users
    { duration: '2m', target: 200 },  // Ramp to 200 users
    { duration: '5m', target: 200 },  // Stay at 200
    { duration: '2m', target: 0 },    // Ramp down
  ],
  thresholds: {
    http_req_duration: ['p(95)<500'],  // 95% < 500ms
    http_req_failed: ['rate<0.01'],     // < 1% errors
  },
};

export default function () {
  // Test login endpoint
  let loginRes = http.post('http://localhost:3000/api/auth/login', {
    username: 'loadtest',
    password: 'LoadTest123!'
  });
  
  check(loginRes, {
    'login status is 200': (r) => r.status === 200,
    'login response time < 500ms': (r) => r.timings.duration < 500,
  });
  
  let token = loginRes.json('token');
  
  // Test products endpoint
  let productsRes = http.get('http://localhost:3000/api/products', {
    headers: { Authorization: `Bearer ${token}` }
  });
  
  check(productsRes, {
    'products status is 200': (r) => r.status === 200,
  });
  
  sleep(1);
}
```

**Estimated Effort:** 6 hours

---

#### 11. OT Practical Implementation
**Current State:** Documented but not implemented  
**Impact:** No hands-on OT security demonstration  
**ER4 Requirement:** RA10 (IT/OT integration)

**Implementation Plan:**

**File: `infrastructure/ot/openplc/plc_program.st`** (100 lines)
```
// Structured Text program for OpenPLC
// Simulating cookie production line

PROGRAM CookieProduction
  VAR
    // Inputs
    StartButton : BOOL;
    StopButton : BOOL;
    EmergencyStop : BOOL;
    
    // Sensors
    OvenTemperature : REAL;
    ConveyorSpeed : REAL;
    
    // Outputs
    OvenHeater : BOOL;
    ConveyorMotor : BOOL;
    AlarmLight : BOOL;
  END_VAR
  
  // Emergency stop logic
  IF EmergencyStop THEN
    OvenHeater := FALSE;
    ConveyorMotor := FALSE;
    AlarmLight := TRUE;
    RETURN;
  END_IF;
  
  // Normal operation
  IF StartButton AND NOT StopButton THEN
    // Heat oven to 180°C
    IF OvenTemperature < 180 THEN
      OvenHeater := TRUE;
    ELSE
      OvenHeater := FALSE;
    END_IF;
    
    // Run conveyor
    ConveyorMotor := TRUE;
  ELSE
    OvenHeater := FALSE;
    ConveyorMotor := FALSE;
  END_IF;
END_PROGRAM
```

**File: `infrastructure/ot/docker-compose.ot.yml`** (150 lines)
```yaml
version: '3.8'

services:
  # OpenPLC Runtime
  openplc:
    image: openplcproject/openplc:v3
    container_name: zabala-openplc
    ports:
      - "8080:8080"  # Web interface
      - "502:502"    # Modbus TCP
    volumes:
      - ./openplc/programs:/programs
      - openplc_data:/persistent
    networks:
      ot_network:
        ipv4_address: 192.168.50.10
    restart: unless-stopped
    
  # ScadaBR (SCADA system)
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
    
  # Node-RED (for OT monitoring)
  nodered:
    image: nodered/node-red:latest
    container_name: zabala-nodered
    ports:
      - "1880:1880"
    volumes:
      - ./nodered/flows:/data
    networks:
      ot_network:
        ipv4_address: 192.168.50.12
    restart: unless-stopped

networks:
  ot_network:
    driver: bridge
    internal: true  # Isolated from internet
    ipam:
      config:
        - subnet: 192.168.50.0/24
  management_network:
    driver: bridge

volumes:
  openplc_data:
```

**File: `infrastructure/ot/node-red/ot-monitoring-flow.json`** (300 lines)
```json
{
  "flows": [
    {
      "id": "1",
      "type": "modbus-read",
      "name": "Read PLC Temperature",
      "server": "192.168.50.10",
      "port": 502,
      "address": 0,
      "quantity": 1
    },
    {
      "id": "2",
      "type": "function",
      "name": "Check Temperature Threshold",
      "func": "if (msg.payload > 200) {\n  msg.alert = 'HIGH_TEMPERATURE';\n}\nreturn msg;"
    },
    {
      "id": "3",
      "type": "http request",
      "name": "Send Alert to SIEM",
      "method": "POST",
      "url": "http://logstash:5044/ot-alerts"
    }
  ]
}
```

**Estimated Effort:** 14 hours

---

#### 12. Forensics Toolkit & VM
**Current State:** SOPs exist, no practical tools  
**Impact:** Cannot perform actual forensic investigations  
**ER4 Requirement:** RA2 (Auzitegi-analisiak egiten ditu)

**Implementation Plan:**

**File: `security/forensics/toolkit/install-tools.sh`** (150 lines)
```bash
#!/bin/bash
# Forensics toolkit installation for Ubuntu/Debian

echo "[+] Installing Zabala Gailetak Forensics Toolkit"

# 1. Disk forensics
apt-get install -y \
  sleuthkit \
  autopsy \
  foremost \
  testdisk \
  photorec

# 2. Memory forensics
pip3 install volatility3

# 3. Network forensics
apt-get install -y \
  wireshark \
  tcpdump \
  tshark \
  networkminer

# 4. Mobile forensics
wget https://github.com/mvt-project/mvt/releases/latest/mvt.tar.gz
pip3 install mvt

# 5. Log analysis
apt-get install -y \
  logstash \
  jq \
  awk

# 6. Create evidence directory
mkdir -p /evidence/{disk,memory,network,mobile,logs}
chmod 700 /evidence

echo "[✓] Forensics toolkit installation complete"
```

**File: `security/forensics/procedures/memory-dump.sh`** (80 lines)
```bash
#!/bin/bash
# Memory dump procedure

CASE_ID=$1
SYSTEM_NAME=$2
OUTPUT_DIR="/evidence/memory/$CASE_ID"

# Create case directory
mkdir -p "$OUTPUT_DIR"

# Dump memory
echo "[+] Acquiring memory dump from $SYSTEM_NAME"
sudo LiME/src/lime-*.ko "path=$OUTPUT_DIR/memory.lime format=lime"

# Calculate hash for chain of custody
sha256sum "$OUTPUT_DIR/memory.lime" > "$OUTPUT_DIR/memory.lime.sha256"

# Collect system info
uname -a > "$OUTPUT_DIR/system-info.txt"
ps aux > "$OUTPUT_DIR/processes.txt"
netstat -antp > "$OUTPUT_DIR/network-connections.txt"

# Log acquisition
echo "$(date '+%Y-%m-%d %H:%M:%S') - Memory dump completed by $(whoami)" >> "$OUTPUT_DIR/chain-of-custody.log"

echo "[✓] Memory dump complete: $OUTPUT_DIR"
```

**File: `security/forensics/reports/forensic_report_template.md`** (400 lines)
```markdown
# Forensic Investigation Report
# Zabala Gailetak

**Case ID:** [CASE-YYYY-MMDD-XXX]  
**Investigator:** [Name, Certification]  
**Date:** [YYYY-MM-DD]  
**Classification:** Confidential

## 1. Executive Summary
[2-3 paragraph summary of incident and findings]

## 2. Scope of Investigation
- **Systems Examined:** [List all systems]
- **Time Period:** [YYYY-MM-DD to YYYY-MM-DD]
- **Evidence Collected:** [Number of items]

## 3. Evidence Acquisition
### 3.1 Disk Images
| Device | Serial Number | Acquisition Method | Hash (SHA256) |
|--------|--------------|-------------------|---------------|
| Laptop | ABC123 | FTK Imager | 1a2b3c4d... |

### 3.2 Memory Dumps
...

### 3.3 Network Captures
...

## 4. Chain of Custody
| Date/Time | Action | Custodian | Location |
|-----------|--------|-----------|----------|
| 2026-01-08 10:30 | Evidence collected | John Doe | Server Room |
| 2026-01-08 11:00 | Transferred to lab | Jane Smith | Forensics Lab |

## 5. Analysis Findings
### 5.1 Timeline of Events
- 2026-01-08 09:15 - Initial compromise detected
- 2026-01-08 09:20 - Attacker accessed database
- 2026-01-08 09:35 - Data exfiltration occurred

### 5.2 Artifacts Discovered
- **Malware:** [Name, hash, IoCs]
- **Backdoors:** [Locations, persistence mechanisms]
- **Lateral Movement:** [Systems accessed]

### 5.3 Attacker Profile
- **IP Addresses:** [List with GeoIP]
- **Tools Used:** [Metasploit, Cobalt Strike, etc.]
- **TTPs:** [MITRE ATT&CK mapping]

## 6. Conclusions
...

## 7. Recommendations
1. Patch vulnerable systems immediately
2. Reset all credentials
3. Implement network segmentation
4. Deploy EDR solution

## 8. Appendices
- A: Full list of evidence items
- B: Technical details of malware analysis
- C: Network traffic analysis
- D: Volatility analysis output

---

**Investigator Signature:** ________________  
**Date:** ________________  
**Reviewed By:** ________________  
```

**Estimated Effort:** 8 hours

---

## 2. STANDARD OPERATING PROCEDURES (SOPs)

The following SOPs should be created for areas that are documented but need detailed procedures:

### 13. Backup & Recovery SOP
**File: `infrastructure/systems/sop_backup_recovery.md`** (250 lines)

**Contents:**
- Backup schedule (daily, weekly, monthly)
- Backup verification procedures
- Restoration testing procedures
- Off-site backup management
- Retention policy (7 days, 4 weeks, 12 months)
- Recovery time objectives (RTO)
- Recovery point objectives (RPO)

**Estimated Effort:** 4 hours

---

### 14. Patch Management SOP
**File: `infrastructure/systems/sop_patch_management.md`** (200 lines)

**Contents:**
- Vulnerability scanning schedule
- Patch prioritization (critical, high, medium, low)
- Testing procedures (dev → staging → prod)
- Emergency patching process
- Rollback procedures
- Change management integration

**Estimated Effort:** 3 hours

---

### 15. User Access Management SOP
**File: `infrastructure/systems/sop_user_access.md`** (180 lines)

**Contents:**
- User provisioning process
- Role-based access control (RBAC) matrix
- Access review procedures (quarterly)
- Termination checklist
- Privileged access management
- MFA enforcement policy

**Estimated Effort:** 3 hours

---

### 16. Password Management Policy
**File: `compliance/sgsi/password_policy.md`** (120 lines)

**Contents:**
- Password complexity requirements
  - Minimum 12 characters
  - Upper/lowercase + numbers + symbols
  - No dictionary words
- Password expiration (90 days for admins, 180 for users)
- Password history (last 10 passwords)
- Password manager recommendations (Bitwarden, 1Password)
- Service account password rotation

**Estimated Effort:** 2 hours

---

### 17. Vendor Management SOP
**File: `compliance/sgsi/sop_vendor_management.md`** (200 lines)

**Contents:**
- Vendor security assessment questionnaire
- Data processing agreements (DPAs)
- Vendor risk classification
- Third-party access controls
- Vendor audit procedures
- Contract security requirements

**Estimated Effort:** 4 hours

---

### 18. Change Management SOP
**File: `infrastructure/systems/sop_change_management.md`** (220 lines)

**Contents:**
- Change request process
- Change advisory board (CAB)
- Emergency change procedures
- Testing and validation requirements
- Rollback plans
- Post-implementation review

**Estimated Effort:** 4 hours

---

### 19. Security Awareness Training SOP
**File: `security/sop_security_awareness.md`** (150 lines)

**Contents:**
- Annual mandatory training
- Phishing simulation program
- Security tips newsletter
- Onboarding security briefing
- Role-specific training (developers, admins, OT)
- Training tracking and compliance

**Estimated Effort:** 3 hours

---

### 20. Vulnerability Management SOP
**File: `security/sop_vulnerability_management.md`** (200 lines)

**Contents:**
- Vulnerability scanning schedule
- Severity classification (CVSS scores)
- Remediation timelines
  - Critical: 7 days
  - High: 30 days
  - Medium: 90 days
- Compensating controls
- Vulnerability disclosure policy

**Estimated Effort:** 3 hours

---

## 3. DEPLOYMENT REQUIREMENTS

### 🏗️ Infrastructure Requirements

#### Production Environment

**Hardware Requirements (per environment):**

| Component | Development | Staging | Production |
|-----------|------------|---------|-----------|
| **API Server** | 2 vCPU, 4GB RAM, 50GB SSD | 4 vCPU, 8GB RAM, 100GB SSD | 8 vCPU, 16GB RAM, 200GB SSD |
| **Database** | 2 vCPU, 4GB RAM, 100GB SSD | 4 vCPU, 8GB RAM, 200GB SSD | 8 vCPU, 32GB RAM, 500GB SSD |
| **Web Server** | 1 vCPU, 2GB RAM, 20GB SSD | 2 vCPU, 4GB RAM, 50GB SSD | 4 vCPU, 8GB RAM, 100GB SSD |
| **SIEM (ELK)** | 4 vCPU, 8GB RAM, 200GB SSD | 8 vCPU, 16GB RAM, 500GB SSD | 16 vCPU, 32GB RAM, 1TB SSD |
| **Redis Cache** | 1 vCPU, 2GB RAM, 10GB SSD | 2 vCPU, 4GB RAM, 20GB SSD | 4 vCPU, 8GB RAM, 50GB SSD |
| **Honeypot** | - | - | 2 vCPU, 4GB RAM, 100GB SSD |
| **Backup Server** | - | - | 4 vCPU, 8GB RAM, 2TB HDD |

**Total Production Environment:**
- **vCPUs:** 46
- **RAM:** 108 GB
- **Storage:** 4.05 TB (includes backup)

**Cloud Provider Recommendations:**
1. **AWS:** 
   - EC2 instances (t3.medium to t3.xlarge)
   - RDS for MongoDB
   - ElastiCache for Redis
   - S3 for backups
   - CloudWatch for monitoring
   - **Estimated Monthly Cost:** €800-1,200

2. **Azure:**
   - Virtual Machines (B2s to D4s_v3)
   - Cosmos DB
   - Azure Cache for Redis
   - Blob Storage
   - **Estimated Monthly Cost:** €750-1,100

3. **On-Premises (VMware/Proxmox):**
   - 2x Physical Servers (Dual Xeon, 128GB RAM each)
   - 6TB RAID 10 storage
   - 10Gbps networking
   - **Estimated Cost:** €15,000-20,000 (one-time)

---

#### Network Requirements

**Bandwidth:**
- **Internet:** 1 Gbps symmetric fiber (minimum 100 Mbps)
- **Internal:** 10 Gbps backbone

**Network Equipment:**
- **Firewall:** FortiGate 100F or pfSense (2x for HA)
- **Switches:** Managed Layer 3 switches with VLAN support
  - Core switch: 48-port 10Gbps
  - Access switches: 24-port 1Gbps (2x minimum)
- **Wireless:** Enterprise APs with WPA3 (4-6 APs depending on coverage)
- **IDS/IPS:** Suricata or Snort (can run on existing servers)

**Network Segmentation (Required VLANs):**
1. VLAN 10: User Network (192.168.10.0/24)
2. VLAN 20: Server Network (192.168.20.0/24)
3. VLAN 50: OT Network (192.168.50.0/24) - Isolated
4. VLAN 100: DMZ (192.168.100.0/24)
5. VLAN 200: Management (192.168.200.0/24)

**Estimated Network Equipment Cost:** €8,000-12,000

---

#### Software Licenses & Subscriptions

| Software | License Type | Annual Cost (est.) |
|----------|-------------|-------------------|
| **Operating Systems** | Ubuntu Server LTS | Free |
| **MongoDB** | Community Edition | Free |
| **Redis** | Open Source | Free |
| **ELK Stack** | Open Source | Free |
| **Node.js** | Open Source | Free |
| **React/React Native** | Open Source | Free |
| **SSL Certificates** | Let's Encrypt | Free |
| **Backup Software** | Duplicati/Borg | Free |
| **Security Scanning** | OWASP ZAP, Nmap | Free |
| **Vulnerability Scanner** | OpenVAS | Free |
| **Optional: Commercial SIEM** | Splunk/QRadar | €10,000-30,000/year |
| **Optional: EDR** | CrowdStrike/SentinelOne | €20-50/endpoint/year |
| **Optional: WAF** | Cloudflare Pro | €200/month = €2,400/year |

**Total Open Source:** €0/year  
**With Commercial Options:** €15,000-50,000/year

---

#### Personnel Requirements

**Minimum Team (For Operations):**

1. **Security Engineer** (1 FTE)
   - Responsibilities: SIEM monitoring, incident response, security audits
   - Salary Range: €45,000-65,000/year

2. **DevOps Engineer** (1 FTE)
   - Responsibilities: CI/CD, infrastructure, deployment automation
   - Salary Range: €40,000-60,000/year

3. **Full-Stack Developer** (1 FTE)
   - Responsibilities: Web/mobile development, API maintenance
   - Salary Range: €35,000-55,000/year

4. **System Administrator** (Part-time, 0.5 FTE)
   - Responsibilities: Server maintenance, backups, patching
   - Salary Range: €20,000-30,000/year (part-time)

**Total Personnel Cost:** €140,000-210,000/year

**Note:** For Zabala Gailetak (120 employees, existing 5 IKT staff), these roles can be covered by existing team with additional training.

---

#### External Services (Optional but Recommended)

1. **Penetration Testing** (Annual)
   - Cost: €5,000-15,000 per engagement
   - Frequency: Annually + after major changes

2. **Security Audit (ISO 27001)** (Annual)
   - Cost: €8,000-20,000 for initial certification
   - Surveillance audits: €3,000-5,000/year

3. **GDPR Consultation** (As needed)
   - DPO service: €500-2,000/month
   - Legal review: €150-300/hour

4. **Incident Response Retainer**
   - Cost: €2,000-5,000/month
   - Provides 24/7 hotline for critical incidents

**Total External Services:** €20,000-60,000/year

---

### 📊 Total Cost of Ownership (TCO) - 3 Years

#### Option 1: Cloud-Based (AWS/Azure)

| Category | Year 1 | Year 2 | Year 3 | Total |
|----------|--------|--------|--------|-------|
| Cloud Infrastructure | €12,000 | €12,000 | €12,000 | €36,000 |
| Network Equipment | €10,000 | €500 | €500 | €11,000 |
| Software Licenses (if commercial) | €15,000 | €15,000 | €15,000 | €45,000 |
| Personnel | €150,000 | €150,000 | €150,000 | €450,000 |
| External Services | €30,000 | €25,000 | €25,000 | €80,000 |
| **Total** | **€217,000** | **€202,500** | **€202,500** | **€622,000** |

**Per Employee (120):** €5,183 over 3 years = €1,728/employee/year

---

#### Option 2: On-Premises (Open Source)

| Category | Year 1 | Year 2 | Year 3 | Total |
|----------|--------|--------|--------|-------|
| Servers & Infrastructure | €18,000 | €2,000 | €2,000 | €22,000 |
| Network Equipment | €10,000 | €500 | €500 | €11,000 |
| Software Licenses (OSS) | €0 | €0 | €0 | €0 |
| Personnel | €150,000 | €150,000 | €150,000 | €450,000 |
| External Services | €30,000 | €25,000 | €25,000 | €80,000 |
| Electricity & Cooling | €3,000 | €3,000 | €3,000 | €9,000 |
| **Total** | **€211,000** | **€180,500** | **€180,500** | **€572,000** |

**Per Employee (120):** €4,767 over 3 years = €1,589/employee/year

---

#### Recommendation for Zabala Gailetak

**Hybrid Approach:**
- **Core applications:** On-premises (better control for OT security)
- **SIEM & Backup:** Cloud (scalability, disaster recovery)
- **Software:** Open source (cost-effective, customizable)

**Estimated 3-Year TCO:** €580,000 (≈€193,000/year)

---

### 🚀 Deployment Timeline

#### Phase 1: Foundation (Weeks 1-4)
- ✅ Week 1: Infrastructure procurement
- ✅ Week 2: Network setup (VLANs, firewall rules)
- ✅ Week 3: Server installation (OS, Docker)
- ✅ Week 4: Database setup (MongoDB, Redis)

#### Phase 2: Core Applications (Weeks 5-8)
- ✅ Week 5: Backend API deployment
- ✅ Week 6: Web application deployment
- ✅ Week 7: Mobile app testing
- ✅ Week 8: Integration testing

#### Phase 3: Security Infrastructure (Weeks 9-12)
- 🔴 Week 9: SIEM deployment & configuration
- 🔴 Week 10: Honeypot deployment
- 🔴 Week 11: IDS/IPS configuration
- ✅ Week 12: Security testing & hardening

#### Phase 4: Automation (Weeks 13-16)
- 🔴 Week 13: CI/CD pipeline setup
- 🔴 Week 14: Automated testing implementation
- 🔴 Week 15: Monitoring & alerting
- 🔴 Week 16: Documentation & training

#### Phase 5: Production Readiness (Weeks 17-20)
- Week 17: Load testing & performance tuning
- Week 18: Disaster recovery testing
- Week 19: Security audit & penetration testing
- Week 20: Go-live preparation

#### Phase 6: Production & Stabilization (Weeks 21-24)
- Week 21: Production deployment
- Week 22: Monitoring & bug fixes
- Week 23: User training
- Week 24: Post-launch review

**Total Timeline:** 24 weeks (6 months)

---

### 📋 Pre-Deployment Checklist

#### Infrastructure
- [ ] Servers provisioned (on-prem or cloud)
- [ ] Network equipment installed
- [ ] VLANs configured
- [ ] Firewall rules implemented
- [ ] Internet connectivity tested (1 Gbps minimum)
- [ ] Backup storage configured

#### Software
- [ ] Operating systems installed & hardened
- [ ] Docker & Docker Compose installed
- [ ] MongoDB cluster configured
- [ ] Redis configured with persistence
- [ ] SSL certificates obtained (Let's Encrypt)
- [ ] DNS records configured

#### Security
- [ ] Firewall penetration tested
- [ ] Network segmentation verified
- [ ] IDS/IPS rules configured
- [ ] SIEM alerts configured
- [ ] MFA enabled for all admin accounts
- [ ] Backup & recovery tested

#### Compliance
- [ ] GDPR data processing register updated
- [ ] ISO 27001 risk assessment completed
- [ ] Incident response plan documented
- [ ] Business continuity plan tested
- [ ] User privacy notices published

#### Development
- [ ] CI/CD pipeline functional
- [ ] Automated tests passing (85%+ coverage)
- [ ] Security scans passing (no critical issues)
- [ ] Load testing completed (200 concurrent users)
- [ ] Documentation complete

#### Operations
- [ ] Monitoring dashboards configured
- [ ] Alerting rules tested
- [ ] On-call rotation established
- [ ] Runbooks created for common issues
- [ ] Team trained on incident response

---

## 4. IMPLEMENTATION PRIORITY MATRIX

### Summary Table

| # | Item | Priority | Effort | Impact | Status | Notes |
|---|------|---------|--------|--------|--------|-------|
| 1 | Database Implementation | 🔴 Critical | 8h | High | Missing | Required for production |
| 2 | CI/CD Pipeline | 🔴 Critical | 12h | High | Missing | RA8 requirement |
| 3 | Docker Compose (Full Stack) | 🔴 Critical | 10h | High | Missing | Deployment automation |
| 4 | SIEM Complete Config | 🔴 Critical | 14h | High | Partial | Alert rules needed |
| 5 | Honeypot Deployment | 🟡 High | 10h | Medium | Planned | Threat intelligence |
| 6 | Webpack Config | 🟡 High | 4h | Medium | Missing | Web app build |
| 7 | ISO 27001 Complete | 🟡 High | 16h | High | Partial | SOA, policies needed |
| 8 | GDPR Complete | 🟡 High | 12h | High | Partial | Templates needed |
| 9 | E2E Testing | 🟢 Medium | 10h | Low | Missing | Quality assurance |
| 10 | Load Testing | 🟢 Medium | 6h | Medium | Missing | Performance validation |
| 11 | OT Practical | 🟢 Medium | 14h | Medium | Documented | RA10 hands-on |
| 12 | Forensics Toolkit | 🟢 Medium | 8h | Low | SOP only | RA2 practical tools |
| 13-20 | Additional SOPs | 🟢 Medium | 26h | Low | Planned | Operational procedures |

**Total Estimated Effort:**
- **Critical:** 44 hours (≈ 1 week full-time)
- **High:** 42 hours (≈ 1 week full-time)
- **Medium:** 64 hours (≈ 1.5 weeks full-time)
- **Total:** 150 hours (≈ 3.75 weeks full-time)

---

## 5. RECOMMENDATIONS

### For Immediate Implementation (Next 2 Weeks)

1. **Database Layer** (Priority #1)
   - Impact: Without this, application is not production-viable
   - Effort: 8 hours
   - Deliverable: Mongoose models + migrations

2. **Docker Compose** (Priority #3)
   - Impact: Enables consistent deployment across environments
   - Effort: 10 hours
   - Deliverable: Full stack orchestration

3. **Webpack Configuration** (Priority #6)
   - Impact: Required for web app production build
   - Effort: 4 hours
   - Deliverable: Production-ready web bundle

**Total: 22 hours (≈3 days)**

---

### For Sprint 2 (Weeks 3-4)

4. **CI/CD Pipeline** (Priority #2)
   - Impact: Automated testing & deployment (RA8)
   - Effort: 12 hours
   - Deliverable: GitHub Actions workflows

5. **SIEM Configuration** (Priority #4)
   - Impact: Complete security monitoring
   - Effort: 14 hours
   - Deliverable: Logstash config + alert rules

**Total: 26 hours (≈3-4 days)**

---

### For Sprint 3 (Weeks 5-6)

6. **ISO 27001 Documentation** (Priority #7)
   - Impact: Compliance readiness
   - Effort: 16 hours
   - Deliverable: SOA, policies, BCP

7. **GDPR Templates** (Priority #8)
   - Impact: Data protection compliance
   - Effort: 12 hours
   - Deliverable: Breach notification, DPIA, rights procedures

**Total: 28 hours (≈3-4 days)**

---

### Optional/Future Enhancements

8. **Honeypot Deployment** (Priority #5)
   - When: After core security infrastructure
   - Effort: 10 hours

9. **E2E & Load Testing** (Priorities #9-10)
   - When: Before production launch
   - Effort: 16 hours combined

10. **OT Practical Setup** (Priority #11)
    - When: For demos or specific OT requirement
    - Effort: 14 hours

---

## 6. RISKS & MITIGATION

### Risk Assessment

| Risk | Likelihood | Impact | Mitigation |
|------|-----------|--------|------------|
| Database implementation delays | Medium | High | Use MongoDB Atlas (managed) initially |
| CI/CD complexity | Medium | Medium | Start with basic pipeline, iterate |
| Insufficient hardware resources | Low | High | Cloud burst for peak loads |
| Team lacks DevOps skills | Medium | Medium | External consultant for initial setup |
| Security tool false positives | High | Low | Tune alert thresholds during pilot |
| Project scope creep | High | Medium | Stick to critical priorities first |
| Compliance requirements changes | Low | Medium | Monitor regulatory updates |
| Integration challenges | Medium | Medium | Thorough testing in staging environment |

---

## 7. CONCLUSION

The Zabala Gailetak project demonstrates **strong cybersecurity fundamentals** with excellent documentation and a solid technical foundation. The implementation covers the vast majority of ER4.md requirements with particular strengths in:

- Application security (web & mobile)
- Security architecture and network design
- Incident response procedures
- Risk assessment and compliance documentation

**Primary gaps** are in operational deployment automation (CI/CD, IaC) and some practical tool implementations (honeypot, complete SIEM setup). These are **easily addressable** with focused effort.

**Recommendation:** This project is **suitable for submission** with minor additions to CI/CD and database implementation. The comprehensive documentation and security-first approach demonstrate mastery of cybersecurity concepts required by the ER4 challenge.

---

**Files Analyzed:** 56  
**Lines of Code:** ~4,500  
**Documentation Pages:** ~3,000 lines  
**Analysis Date:** 2026-01-08  
**Analysis Depth:** Complete