# Zabala Gailetak HR Portal - Quick Start Guide

**Version:** 1.0
**Date:** January 23, 2026

## ⚡ Setup in 5 Minutes

### Prerequisites

Ensure you have installed:
- ✅ **Docker Desktop** (Windows/Mac) or Docker Engine (Linux)
- ✅ **Git** for repository cloning

### 1. Clone and Configure

```bash
# Clone the repository
git clone <repository-url> zabala-gailetak-hr
cd zabala-gailetak-hr/"Zabala Gailetak"

# Configure backend environment
cd hr-portal
cp .env.example .env

# Edit essential environment variables (optional for development)
# DB_PASSWORD=your_secure_db_password
# JWT_SECRET=your_256_bit_jwt_secret
# MFA_SECRET=your_secure_mfa_secret
```

### 2. Start Services

```bash
# Return to project root
cd ..

# Start all services with Docker Compose
docker-compose -f docker-compose.hrportal.yml up -d

# Monitor startup logs
docker-compose -f docker-compose.hrportal.yml logs -f
```

### 3. Run Database Migrations

```bash
# Execute database setup
cd "Zabala Gailetak/hr-portal"
chmod +x scripts/migrate.sh
./scripts/migrate.sh
```

### 4. Access the Application

- **🌐 Web Portal**: http://localhost:8080
- **🔍 API Health Check**: http://localhost:8080/api/health
- **📱 Mobile App**: Build and run the Android app

**Default Administrator Account:**
- **Email**: `admin@zabalagailetak.com`
- **Password**: `Admin123!`
- **Role**: System Administrator

### 5. Next Steps

1. **Change Default Password**: Update admin credentials immediately
2. **Create Test Users**: Add sample employees for testing
3. **Explore API**: Test endpoints at http://localhost:8080/api/employees
4. **Review Documentation**: See [PROJECT_DOCUMENTATION.md](PROJECT_DOCUMENTATION.md)

---

## 🏗️ Architecture Overview

### System Components

```
Internet → Nginx (DMZ) → PHP App → PostgreSQL
                    ↓
             Redis (Cache) ← SIEM (ELK)
                    ↓
            OT Network (Isolated)
```

### Key Technologies

- **Backend**: PHP 8.4 with PSR standards
- **Database**: PostgreSQL 16 with encryption
- **Cache**: Redis 7 for sessions
- **Web Server**: Nginx with SSL/TLS
- **Security**: MFA, JWT, RBAC, SIEM
- **Mobile**: Kotlin Android app

### Network Zones

- **DMZ (192.168.100.0/24)**: Public web access
- **User Network (192.168.10.0/24)**: Employee workstations
- **Server Network (192.168.20.0/24)**: Application servers
- **Management (192.168.200.0/24)**: Admin access, monitoring
- **OT Network (192.168.50.0/24)**: Industrial systems (isolated)

---

## 🚀 Development Setup

### Backend Development

```bash
# Install PHP dependencies
cd "Zabala Gailetak/hr-portal"
composer install

# Start development server
php -S localhost:8000 -t public/

# Run tests
./vendor/bin/phpunit

# Code quality checks
./vendor/bin/phpcs --standard=PSR12 src/
./vendor/bin/phpstan analyse src/
```

### Frontend Development

```bash
# Install web dependencies
cd "Zabala Gailetak/hr-portal/web"
npm install

# Start development server
npm run dev

# Build for production
npm run build

# Run linting
npm run lint
```

### Mobile Development

```bash
# Open Android project
cd "Zabala Gailetak/android-app"

# Using Android Studio:
# 1. File → Open → Select android-app folder
# 2. Wait for Gradle sync
# 3. Run → Run 'app' (Shift+F10)

# Build APK
./gradlew assembleDebug
```

### Full Development Environment

```bash
# Start all services
docker-compose -f docker-compose.hrportal.yml up -d

# Run development servers
# Backend: localhost:8080 (via Docker)
# Frontend: localhost:3000 (npm run dev)
# Mobile: Android Studio emulator
```

---

## 🔧 Configuration

### Environment Variables

**Required for Production:**
```env
# Application
APP_ENV=production
APP_DEBUG=false

# Database
DB_HOST=192.168.20.20
DB_PORT=5432
DB_NAME=hr_portal
DB_USER=hr_user
DB_PASSWORD=secure_password_here
DB_SSL_MODE=require

# Security
JWT_SECRET=your_256_bit_secret_key_here
JWT_EXPIRES_IN=1h
MFA_ISSUER=Zabala Gailetak
MFA_SECRET=secure_mfa_secret_here

# Redis
REDIS_HOST=192.168.20.30
REDIS_PORT=6379
REDIS_PASSWORD=secure_redis_password
REDIS_SSL=true

# Email (for notifications)
SMTP_HOST=your_smtp_server
SMTP_PORT=587
SMTP_USER=your_email@domain.com
SMTP_PASS=your_email_password
SMTP_ENCRYPTION=tls

# File Storage
UPLOAD_PATH=/var/www/uploads
MAX_FILE_SIZE=10485760  # 10MB
ALLOWED_EXTENSIONS=pdf,doc,docx,jpg,jpeg,png

# Security Headers
CSP_DEFAULT_SRC=self
HSTS_MAX_AGE=31536000
```

### SSL/TLS Setup

```bash
# Generate self-signed certificate (development)
openssl req -x509 -newkey rsa:4096 -keyout key.pem -out cert.pem -days 365 -nodes -subj "/C=ES/ST=Basque Country/L=Donostia/O=Zabala Gailetak/CN=localhost"

# For production - Let's Encrypt
certbot certonly --standalone -d hr.zabalagailetak.com

# Configure Nginx
server {
    listen 443 ssl http2;
    server_name hr.zabalagailetak.com;

    ssl_certificate /etc/letsencrypt/live/hr.zabalagailetak.com/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/hr.zabalagailetak.com/privkey.pem;
    ssl_protocols TLSv1.2 TLSv1.3;
    ssl_ciphers HIGH:!aNULL:!MD5;

    location / {
        proxy_pass http://localhost:8080;
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto $scheme;
    }
}
```

---

## 🧪 Testing

### Unit Tests

```bash
# PHP unit tests
cd "Zabala Gailetak/hr-portal"
./vendor/bin/phpunit --verbose

# With coverage
./vendor/bin/phpunit --coverage-html coverage/

# Specific test file
./vendor/bin/phpunit tests/Controllers/AuthControllerTest.php
```

### Integration Tests

```bash
# API integration tests
./vendor/bin/phpunit tests/Integration/

# Database integration
./vendor/bin/phpunit tests/Database/
```

### End-to-End Tests

```bash
# Install Playwright
npm install -g @playwright/test

# Install browsers
npx playwright install

# Run E2E tests
npx playwright test

# With browser visible
npx playwright test --headed

# Specific test
npx playwright test tests/e2e/auth.spec.js
```

### Security Testing

```bash
# OWASP ZAP scan
docker run -t owasp/zap2docker-stable zap-baseline.py \
  -t http://localhost:8080 \
  -r zap_report.html

# Dependency check
./vendor/bin/composer audit

# Container security
docker run --rm -v $(pwd):/src aquasecurity/trivy fs .
```

---

## 🔒 Security Features

### Authentication & Authorization

**Multi-Factor Authentication (MFA):**
- TOTP via Google Authenticator/Authy
- WebAuthn (Passkeys) support
- Recovery codes for account recovery

**Role-Based Access Control (RBAC):**
- **ADMIN**: Full system access
- **RRHH MGR**: HR management functions
- **JEFE SECCIÓN**: Department management
- **EMPLEADO**: Personal access only

### Security Controls

**Input Validation:**
- Comprehensive server-side validation
- SQL injection prevention (prepared statements)
- XSS protection (output encoding)
- CSRF protection (double-submit cookies)

**Encryption:**
- AES-256-GCM at rest
- TLS 1.3 in transit
- Password hashing (bcrypt, cost factor 12+)

**Monitoring:**
- SIEM integration (ELK Stack)
- Real-time alerting
- Comprehensive audit logging
- Honeypot threat detection

---

## 📊 Monitoring & Logs

### Application Logs

```bash
# View application logs
docker-compose -f docker-compose.hrportal.yml logs -f hr-portal

# PHP error logs
docker exec -it hr-portal tail -f /var/log/php/error.log

# Nginx access logs
docker exec -it nginx tail -f /var/log/nginx/access.log
```

### SIEM Dashboard

```bash
# Access Kibana
open http://localhost:5601

# Default credentials
# Username: elastic
# Password: changeme (configure in environment)
```

### Health Checks

```bash
# Application health
curl http://localhost:8080/api/health

# Database connectivity
docker exec -it postgres pg_isready -U hr_user -d hr_portal

# Redis connectivity
docker exec -it redis redis-cli ping
```

---

## 🚨 Troubleshooting

### Common Issues

#### Port 8080 Already in Use

```bash
# Change port in docker-compose.hrportal.yml
ports:
  - "8081:80"  # Change from 8080:80

# Restart services
docker-compose -f docker-compose.hrportal.yml down
docker-compose -f docker-compose.hrportal.yml up -d
```

#### Database Connection Failed

```bash
# Check PostgreSQL logs
docker-compose -f docker-compose.hrportal.yml logs postgres

# Verify environment variables
cat hr-portal/.env | grep DB_

# Test database connection
docker exec -it postgres psql -U hr_user -d hr_portal
```

#### Permission Errors

```bash
# Fix script permissions
chmod +x hr-portal/scripts/*.sh

# Fix file permissions
sudo chown -R $USER:$USER "Zabala Gailetak/"
```

#### SSL Certificate Issues

```bash
# Check certificate validity
openssl x509 -in cert.pem -text -noout

# Regenerate self-signed certificate
openssl req -x509 -newkey rsa:4096 -keyout key.pem -out cert.pem -days 365 -nodes
```

#### Memory Issues

```bash
# Increase Docker memory limit
# Docker Desktop: Settings → Resources → Memory (4GB+ recommended)

# Check container memory usage
docker stats
```

### Performance Issues

#### Slow Application Response

```bash
# Check PHP-FPM processes
docker exec -it hr-portal ps aux | grep php

# Monitor database queries
docker exec -it postgres psql -U hr_user -d hr_portal -c "SELECT * FROM pg_stat_activity;"

# Check Redis performance
docker exec -it redis redis-cli info stats
```

#### High CPU Usage

```bash
# Check system resources
docker stats

# Monitor application logs for errors
docker-compose -f docker-compose.hrportal.yml logs --tail=100 hr-portal
```

---

## 📞 Support & Resources

### Documentation

- **📋 Project Documentation**: [PROJECT_DOCUMENTATION.md](PROJECT_DOCUMENTATION.md)
- **🔐 Security Guide**: [AGENTS.md](AGENTS.md) (includes compliance details)
- **🚀 Implementation Summary**: [IMPLEMENTATION_SUMMARY.md](IMPLEMENTATION_SUMMARY.md)
- **📱 Web App Guide**: [WEB_APP_GUIDE.md](WEB_APP_GUIDE.md)
- **📲 Mobile App Guide**: [MOBILE_APP_GUIDE.md](MOBILE_APP_GUIDE.md)
- **🔗 API Documentation**: [API_DOCUMENTATION.md](API_DOCUMENTATION.md)

### Emergency Contacts

- **🚨 Security Incidents**: security@zabalagailetak.com | +34 XXX XXX XXX
- **🆘 Technical Support**: support@zabalagailetak.com | +34 XXX XXX XXX
- **📧 General Inquiries**: info@zabalagailetak.com

### Community Resources

- **OWASP**: https://owasp.org
- **PHP Documentation**: https://www.php.net/docs
- **PostgreSQL Manual**: https://www.postgresql.org/docs/
- **Docker Documentation**: https://docs.docker.com
- **Android Developers**: https://developer.android.com

---

## 🎯 Quick Commands Reference

```bash
# Start/Stop services
docker-compose -f docker-compose.hrportal.yml up -d    # Start
docker-compose -f docker-compose.hrportal.yml down     # Stop
docker-compose -f docker-compose.hrportal.yml logs -f  # Logs

# Database operations
cd "Zabala Gailetak/hr-portal"
./scripts/migrate.sh  # Run migrations

# Development
cd "Zabala Gailetak/hr-portal/web"
npm run dev          # Start frontend dev server

cd "Zabala Gailetak/android-app"
./gradlew assembleDebug  # Build Android APK

# Testing
./vendor/bin/phpunit                    # PHP tests
npm test                              # Frontend tests
./gradlew test                        # Android tests

# Code quality
./vendor/bin/phpcs src/               # PHP linting
npm run lint                          # JS linting
./gradlew lint                        # Android linting
```

---

## 🔄 Updates & Maintenance

### Regular Maintenance Tasks

**Daily:**
- Monitor system health and logs
- Review security alerts
- Verify backup completion

**Weekly:**
- Update dependencies (security patches)
- Review system performance
- Check disk space and resources

**Monthly:**
- Full backup testing
- Security patch deployment
- Performance optimization

**Quarterly:**
- Security assessments
- Compliance audits
- System updates

### Backup Verification

```bash
# Test database backup restoration
pg_restore --create --clean -d postgres /backups/hr_portal_backup.sql

# Verify file integrity
find /backups -name "*.tar.gz" -exec tar -tzf {} \; | head -10
```

---

**Ready to start developing with Zabala Gailetak HR Portal! 🚀**

For detailed technical documentation, see [PROJECT_DOCUMENTATION.md](PROJECT_DOCUMENTATION.md).

*Last updated: January 23, 2026*</content>
<parameter name="filePath">D:\erronka4\QUICK_START_GUIDE.md