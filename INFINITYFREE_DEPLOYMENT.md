# 🚀 InfinityFree Database Deployment Guide

**Zabala Gailetak HR Portal - Production Deployment**

---

## 📋 Prerequisites

Before starting, ensure you have:
- ✅ InfinityFree hosting account with PHP 8.3+ support
- ✅ Access to phpMyAdmin (via control panel)
- ✅ Database credentials from InfinityFree
- ✅ Backup of existing database (if upgrading)

**InfinityFree Database Details:**
- Host: `sql107.infinityfree.com`
- Database: `if0_40982238_zabala_gailetak`
- Username: `if0_40982238`
- Port: `3306`
- Engine: MariaDB 11.4.9

---

## 🗄️ Database Setup Options

### Option 1: Fresh Installation (Recommended for New Deployments)

Use this if you're deploying for the first time or don't need to keep existing data.

**File:** `scripts/mysql_zabala_gailetak_fresh_install.sql`

**What it includes:**
- ✅ Complete database schema (20 tables)
- ✅ Sample data (departments, admin user)
- ✅ Vacation system with triggers
- ✅ Default admin: `admin@zabalagailetak.com` / `Admin123!`

**Steps:**
1. Log into InfinityFree control panel
2. Go to **phpMyAdmin**
3. Select database `if0_40982238_zabala_gailetak`
4. Click **SQL** tab
5. Copy and paste contents of `mysql_zabala_gailetak_fresh_install.sql`
6. Click **Go** to execute
7. Wait for completion (should take 10-30 seconds)
8. Verify: You should see 20 tables created

⚠️ **WARNING:** This will DROP all existing tables and data!

### Option 2: Migration from Existing Database

Use this if you already have data in production and want to preserve it.

**File:** `scripts/mysql_migration_fix_vacation_system.sql`

**What it does:**
- ✅ Creates automatic backups (*_backup tables)
- ✅ Updates schema without data loss
- ✅ Fixes vacation balances and triggers
- ✅ Recalculates pending/used days

**Steps:**
1. **FIRST:** Export current database as backup
   - phpMyAdmin → Export → Format: SQL → Save file
2. Select your database
3. Go to **SQL** tab
4. Copy and paste contents of `mysql_migration_fix_vacation_system.sql`
5. Click **Go**
6. Review verification report at end
7. Test thoroughly before removing backup tables

---

## 🔧 Required Patches for Recent Features

The base schema needs these updates for the new Payslips and Documents modules:

### Patch 1: Fix Documents Table for Public Documents

```sql
-- Allow NULL employee_id for public documents
ALTER TABLE `documents` 
MODIFY COLUMN `employee_id` VARCHAR(36) DEFAULT NULL;

-- Update foreign key constraint
ALTER TABLE `documents` 
DROP FOREIGN KEY `documents_ibfk_1`;

ALTER TABLE `documents`
ADD CONSTRAINT `documents_ibfk_1` 
FOREIGN KEY (`employee_id`) 
REFERENCES `employees`(`id`) 
ON DELETE CASCADE;
```

### Patch 2: Add Missing Indexes (Optional, for Performance)

```sql
-- Add index for document queries filtering by public/private
ALTER TABLE `documents` 
ADD KEY `idx_documents_public` (`employee_id`, `is_archived`);

-- Add composite index for payroll queries
ALTER TABLE `payroll`
ADD KEY `idx_payroll_employee_period` (`employee_id`, `period_start`, `period_end`);
```

---

## 🔐 Environment Configuration

### Step 1: Create `.env` File

Create a file named `.env` in the root of your InfinityFree web directory (`htdocs/` or `public_html/`):

```env
# Application
APP_ENV=production
APP_DEBUG=false
APP_NAME="Zabala Gailetak HR Portal"
APP_URL=https://zabala-gailetak.infinityfreeapp.com

# Database (InfinityFree)
DB_DRIVER=mysql
DB_HOST=sql107.infinityfree.com
DB_PORT=3306
DB_NAME=if0_40982238_zabala_gailetak
DB_USER=if0_40982238
DB_PASSWORD=your_database_password_here

# Security (IMPORTANT: Generate strong random strings!)
JWT_SECRET=CHANGE_THIS_TO_A_VERY_LONG_RANDOM_STRING_AT_LEAST_64_CHARACTERS
SESSION_LIFETIME=28800
PASSWORD_PEPPER=CHANGE_THIS_TO_ANOTHER_LONG_RANDOM_STRING

# File Storage (InfinityFree paths)
UPLOAD_MAX_SIZE=10485760
UPLOAD_ALLOWED_TYPES=pdf,doc,docx,jpg,jpeg,png
UPLOAD_PATH=/home/vol12_4/infinityfree.com/if0_40982238/htdocs/storage/documents

# Logging
LOG_LEVEL=error
LOG_PATH=/home/vol12_4/infinityfree.com/if0_40982238/htdocs/logs
```

### Step 2: Generate Secure Secrets

**JWT_SECRET and PASSWORD_PEPPER:**

Use one of these methods to generate secure random strings:

**Option A: Online (use with caution)**
```
Visit: https://www.random.org/strings/
- Length: 64 characters
- Digits: Yes
- Uppercase/Lowercase: Yes
- Special characters: No (for compatibility)
```

**Option B: Linux/Mac Terminal**
```bash
openssl rand -base64 48
```

**Option C: PHP Command**
```php
<?php echo bin2hex(random_bytes(32)); ?>
```

### Step 3: Create Required Directories

Via FTP or File Manager, create these directories:

```
htdocs/
├── storage/
│   ├── documents/      (chmod 755)
│   └── uploads/        (chmod 755)
└── logs/               (chmod 755)
```

**Permissions:**
- Directories: `755` (rwxr-xr-x)
- Files: `644` (rw-r--r--)

---

## ✅ Post-Deployment Validation

### 1. Test Database Connection

Visit: `https://zabala-gailetak.infinityfreeapp.com/api/test/db`

Expected response:
```json
{
  "status": "success",
  "message": "Database connection successful",
  "users_count": 1,
  "admin_exists": true
}
```

### 2. Test Admin Login

1. Go to: `https://zabala-gailetak.infinityfreeapp.com/login`
2. Email: `admin@zabalagailetak.com`
3. Password: `Admin123!`
4. ✅ Should redirect to dashboard

### 3. Test New Modules

**Payslips:**
- Navigate to `/payslips`
- Should see empty list (or sample data if seeded)
- Try creating a test payslip (admin only)

**Documents:**
- Navigate to `/documents`
- Should see two tabs: "Mis documentos" and "Documentos públicos"
- Try uploading a test document (admin only)

### 4. Check File Uploads

Upload a small test document and verify:
- File appears in `storage/documents/` directory
- Download works correctly
- Correct MIME type and file size shown

### 5. Verify Database Triggers

```sql
-- Check vacation triggers are installed
SHOW TRIGGERS LIKE 'vacation_requests';

-- Expected: 3 triggers
-- 1. before_vacation_request_insert
-- 2. after_vacation_request_approved
-- 3. after_vacation_request_cancelled
```

---

## 🐛 Troubleshooting

### Issue: "Cannot connect to database"

**Solutions:**
1. Check `.env` file has correct credentials
2. Verify database exists in phpMyAdmin
3. Check DB_HOST is `sql107.infinityfree.com` (not localhost)
4. Ensure InfinityFree database service is active

### Issue: "Table 'documents' doesn't exist"

**Solutions:**
1. Re-run `mysql_zabala_gailetak_fresh_install.sql`
2. Check you selected the correct database before running SQL
3. Look for error messages in phpMyAdmin execution log

### Issue: "Cannot upload files" / "Permission denied"

**Solutions:**
1. Create `storage/documents/` directory if missing
2. Set directory permissions to `755`:
   ```bash
   chmod 755 storage
   chmod 755 storage/documents
   ```
3. Check `UPLOAD_PATH` in `.env` points to correct absolute path
4. Verify InfinityFree disk space quota not exceeded

### Issue: "Foreign key constraint fails"

**Solutions:**
1. Run SQL in correct order (parents before children)
2. If InfinityFree doesn't support InnoDB, change to MyISAM:
   ```sql
   -- Find and replace in SQL file:
   ENGINE=InnoDB → ENGINE=MyISAM
   ```
3. Remove FOREIGN KEY constraints if necessary (not ideal)

### Issue: "UUID is empty string or NULL"

**Cause:** MySQL doesn't have native UUID generation like PostgreSQL.

**Solution:** Ensure PHP code generates UUIDs before INSERT:
```php
// In controllers:
$id = $this->generateUUID(); // Must be called before INSERT
```

### Issue: "Vacation triggers not working"

**Diagnosis:**
```sql
-- Check triggers exist
SHOW TRIGGERS LIKE 'vacation_requests';

-- If empty, install triggers
-- Run: scripts/mysql_vacation_triggers.sql
```

### Issue: "Public documents not showing"

**Solution:** Run Patch 1 (Allow NULL employee_id):
```sql
ALTER TABLE `documents` 
MODIFY COLUMN `employee_id` VARCHAR(36) DEFAULT NULL;
```

---

## 📊 Database Maintenance

### Regular Backups

**Automated (Recommended):**
1. Use InfinityFree's built-in backup feature
2. Schedule: Daily or weekly
3. Store backups off-site (Google Drive, Dropbox)

**Manual Export:**
```
phpMyAdmin → Export → Custom → 
- Format: SQL
- Structure: Include
- Data: Include
- Save as file
```

**Recommended Schedule:**
- Before major updates
- Weekly automatic backups
- Before running migrations
- After adding significant data

### Database Optimization

Run monthly to maintain performance:

```sql
-- Analyze tables
ANALYZE TABLE users, employees, documents, payroll, vacation_requests;

-- Optimize tables
OPTIMIZE TABLE users, employees, documents, payroll, vacation_requests;

-- Check table integrity
CHECK TABLE users, employees, documents, payroll;
```

### Clean Up Old Data

```sql
-- Archive old payroll (keep 2 years)
UPDATE payroll 
SET is_archived = 1 
WHERE period_start < DATE_SUB(NOW(), INTERVAL 2 YEAR);

-- Archive old documents (keep 3 years)
UPDATE documents 
SET is_archived = 1 
WHERE created_at < DATE_SUB(NOW(), INTERVAL 3 YEAR);

-- Delete old audit logs (keep 1 year)
DELETE FROM audit_logs 
WHERE created_at < DATE_SUB(NOW(), INTERVAL 1 YEAR);
```

---

## 🔄 Rollback Procedures

### If Fresh Install Fails

1. Drop all tables created
2. Review error messages in phpMyAdmin
3. Fix syntax errors in SQL file
4. Re-run installation

### If Migration Fails

1. Restore from backup:
   ```sql
   DROP DATABASE if0_40982238_zabala_gailetak;
   CREATE DATABASE if0_40982238_zabala_gailetak;
   ```
2. Import backup file via phpMyAdmin
3. Analyze what went wrong
4. Fix migration script
5. Try again

### Emergency Recovery

If database is completely broken:

```sql
-- 1. Drop database
DROP DATABASE if0_40982238_zabala_gailetak;

-- 2. Recreate empty database
CREATE DATABASE if0_40982238_zabala_gailetak 
CHARACTER SET utf8mb4 
COLLATE utf8mb4_unicode_ci;

-- 3. Restore from last known good backup
-- (Import via phpMyAdmin)
```

---

## 📞 Support & Resources

### Documentation
- [Database README](scripts/DATABASE_README.md) - Detailed schema documentation
- [API Documentation](API_DOCUMENTATION.md) - API endpoint reference
- [AGENTS.md](AGENTS.md) - Complete project context

### Verification Queries

```sql
-- Check all tables exist
SHOW TABLES;

-- Count records in key tables
SELECT 
  (SELECT COUNT(*) FROM users) as users,
  (SELECT COUNT(*) FROM employees) as employees,
  (SELECT COUNT(*) FROM departments) as departments,
  (SELECT COUNT(*) FROM payroll) as payroll,
  (SELECT COUNT(*) FROM documents) as documents;

-- Verify admin user
SELECT id, email, role, account_locked 
FROM users 
WHERE email = 'admin@zabalagailetak.com';

-- Check for orphaned records
SELECT COUNT(*) 
FROM employees e 
LEFT JOIN users u ON e.user_id = u.id 
WHERE u.id IS NULL;
```

---

## 🎯 Deployment Checklist

Print this checklist and check off each item:

### Pre-Deployment
- [ ] Backup existing database (if applicable)
- [ ] Download `mysql_zabala_gailetak_fresh_install.sql`
- [ ] Have InfinityFree credentials ready
- [ ] Generate JWT_SECRET and PASSWORD_PEPPER
- [ ] Review and customize `.env` file

### Database Setup
- [ ] Access phpMyAdmin
- [ ] Select correct database
- [ ] Run SQL installation script
- [ ] Verify 20 tables created
- [ ] Run Patch 1 (documents NULL fix)
- [ ] Run Patch 2 (optional indexes)
- [ ] Verify triggers installed

### File System
- [ ] Create `storage/documents/` directory
- [ ] Create `logs/` directory
- [ ] Set correct permissions (755)
- [ ] Upload `.env` file to root
- [ ] Verify `.gitignore` excludes `.env`

### Configuration
- [ ] Update `APP_URL` in `.env`
- [ ] Set `APP_DEBUG=false`
- [ ] Configure database credentials
- [ ] Set strong JWT_SECRET
- [ ] Set strong PASSWORD_PEPPER
- [ ] Configure upload paths

### Validation
- [ ] Test `/api/test/db` endpoint
- [ ] Test admin login
- [ ] Test payslips module
- [ ] Test documents upload
- [ ] Test file downloads
- [ ] Verify triggers work
- [ ] Check logs for errors

### Security
- [ ] Change default admin password
- [ ] Remove test accounts
- [ ] Review user permissions
- [ ] Enable HTTPS (InfinityFree provides)
- [ ] Set secure session cookies
- [ ] Test rate limiting

### Documentation
- [ ] Document any custom changes
- [ ] Save deployment date/time
- [ ] Record database version
- [ ] Note any issues encountered
- [ ] Update team on deployment

---

## 📅 Version History

- **v2.0.0** (2026-02-06) - Added payslips and documents modules support
- **v1.5.0** (2026-02-05) - Fixed vacation triggers for InfinityFree
- **v1.0.0** (2026-01-14) - Initial production deployment

---

**Last Updated:** 2026-02-06  
**Maintained By:** Zabala Gailetak DevTeam  
**Status:** Production Ready ✅
