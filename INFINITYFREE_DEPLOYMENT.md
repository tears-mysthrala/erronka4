# 🚀 InfinityFree Datu-basearen Despliegue Gida

**Zabala Gailetak HR Ataria - Produkzio Desplieguea**

---

## 📋 Aurrebaldintzak

Hasi aurretik, ziurtatu hau dituzula:
- ✅ InfinityFree hosting kontu bat PHP 8.3+ euskarriarekin
- ✅ phpMyAdmin-era sarbidea (kontrol panelaren bidez)
- ✅ Datu-basearen kredentzialak InfinityFree-tik
- ✅ Datu-basearen babeskopia existitzen dena (eguneratzen ari bazara)

**InfinityFree datu-basearen xehetasunak:**
- Host: `sql107.infinityfree.com`
- Database: `if0_40982238_zabala_gailetak`
- Username: `if0_40982238`
- Port: `3306`
- Engine: MariaDB 11.4.9

---

## 🗄️ Datu-basearen konfigurazio aukerak

### 1. aukera: Instalazio garbia (Gomendatua instalazio berrietarako)

Erabili hau lehen aldiz instalatzen ari bazara edo datu existenteak mantendu nahi ez badituzu.

**Fitxategia:** `scripts/mysql_zabala_gailetak_fresh_install.sql`

**Zer sartzen den:**
- ✅ Datu-base eskema osoa (20 taula)
- ✅ Datu laginak (sailak, administratzaile erabiltzailea)
- ✅ Opor sistema trigger-ekin
- ✅ Administratzaile lehenetsia: `admin@zabalagailetak.com` / `Admin123!`

**Pausoak:**
1. Sartu InfinityFree kontrol panelera
2. Zoaz **phpMyAdmin**-era
3. Hautatu `if0_40982238_zabala_gailetak` datu-basea
4. Egin klik **SQL** fitxan
5. Kopiatu eta itsatsi `mysql_zabala_gailetak_fresh_install.sql`-ren edukia
6. Egin klik **Go** exekutatzeko
7. Itxaron osatzea (10-30 segundo beharko ditu)
8. Egiaztatu: 20 taula sortu direla ikusi beharko zenuke

⚠️ **KONTUZ:** Honek existitzen diren taula eta datu guztiak EZABATUKO DITU!

### 2. aukera: Migrazioa datu-base existentetik

Erabili hau jada datuak dituzula produkzioan eta hauek mantendu nahi badituzu.

**Fitxategia:** `scripts/mysql_migration_fix_vacation_system.sql`

**Zer egiten duen:**
- ✅ Babeskopia automatikoak sortzen ditu (*_backup taulak)
- ✅ Eguneratzen du eskema datu galderik gabe
- ✅ Konpontzen ditu opor balantzeak eta trigger-ak
- ✅ Berriro kalkulatzen ditu zain dauden/erabilitako egunak

**Pausoak:**
1. **LEHENIK:** Esportatu uneko datu-basea babeskopia gisa
   - phpMyAdmin → Export → Format: SQL → Save file
2. Hautatu zure datu-basea
3. Zoaz **SQL** fitxan
4. Kopiatu eta itsatsi `mysql_migration_fix_vacation_system.sql`-ren edukia
5. Egin klik **Go**
6. Berrikusi amaierako egiaztapen txostena
7. Probatu sakonki backup taulak ezabatu aurretik

---

## 🔧 Eginbeharrzko patxak azken ezaugarrientzako

Oinarrizko eskema behar ditu eguneraketa hauek Payslips eta Documents modulu berrientzako:

### Patch 1: Konpondu Documents taula dokumentu publikoentzako

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

### Patch 2: Gehitu falta diren indizeak (Aukerakoa, errendimendurako)

```sql
-- Add index for document queries filtering by public/private
ALTER TABLE `documents` 
ADD KEY `idx_documents_public` (`employee_id`, `is_archived`);

-- Add composite index for payroll queries
ALTER TABLE `payroll`
ADD KEY `idx_payroll_employee_period` (`employee_id`, `period_start`, `period_end`);
```

---

## 🔐 Ingurune konfigurazioa

### 1. urratsa: Sortu `.env` fitxategia

Sortu `.env` izeneko fitxategi bat InfinityFree web direktorioaren erroan (`htdocs/` edo `public_html/`):

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

### 2. urratsa: Sortu sekretu seguruak

**JWT_SECRET eta PASSWORD_PEPPER:**

Erabili metodo hauetako bat kate aleatorio seguruak sortzeko:

**Aukera A: Online (kontuz erabili)**
```
Visit: https://www.random.org/strings/
- Length: 64 characters
- Digits: Yes
- Uppercase/Lowercase: Yes
- Special characters: No (for compatibility)
```

**Aukera B: Linux/Mac Terminala**
```bash
openssl rand -base64 48
```

**Aukera C: PHP Komandoa**
```php
<?php echo bin2hex(random_bytes(32)); ?>
```

### 3. urratsa: Sortu beharrezko direktorioak

FTP edo File Manager bidez, sortu direktorio hauek:

```
htdocs/
├── storage/
│   ├── documents/      (chmod 755)
│   └── uploads/        (chmod 755)
└── logs/               (chmod 755)
```

**Baimenak:**
- Direktorioak: `755` (rwxr-xr-x)
- Fitxategiak: `644` (rw-r--r--)

---

## ✅ Despliegue osteko baliozkotzea

### 1. Probatu datu-basearen konexioa

Bisitatu: `https://zabala-gailetak.infinityfreeapp.com/api/test/db`

Eskatutako erantzuna:
```json
{
  "status": "success",
  "message": "Database connection successful",
  "users_count": 1,
  "admin_exists": true
}
```

### 2. Probatu administratzailearen saioa hasiera

1. Zoaz: `https://zabala-gailetak.infinityfreeapp.com/login`
2. Emaila: `admin@zabalagailetak.com`
3. Pasahitza: `Admin123!`
4. ✅ Aginte-panelera berbideratu beharko zaitu

### 3. Probatu modulu berriak

**Payslips:**
- Nabigatu `/payslips`-era
- Zerrenda hutsa ikusi beharko zenuke (edo datu laginak seed-eatuta badaude)
- Saiatu payslip probak sortzen (administratzaileak bakarrik)

**Documents:**
- Nabigatu `/documents`-era
- Bi fitxa ikusi beharko zenituzke: "Nire dokumentuak" eta "Dokumentu publikoak"
- Saiatu dokumentu proba bat igotzen (administratzaileak bakarrik)

### 4. Egiaztatu fitxategi igotzeak

Igo dokumentu txiki bat eta egiaztatu:
- Fitxategia `storage/documents/` direktorioan agertzen dela
- Deskargak ondo funtzionatzen duela
- MIME mota eta fitxategi tamaina zuzenak direla

### 5. Egiaztatu datu-base trigger-ak

```sql
-- Check vacation triggers are installed
SHOW TRIGGERS LIKE 'vacation_requests';

-- Expected: 3 triggers
-- 1. before_vacation_request_insert
-- 2. after_vacation_request_approved
-- 3. after_vacation_request_cancelled
```

---

## 🐛 Arazoen konponketa

### Arazoa: "Ezin da datu-basearekin konektatu"

**Konponbideak:**
1. Egiaztatu `.env` fitxategiak kredentzial zuzenak dituela
2. Ziurtatu datu-basea existitzen dela phpMyAdmin-en
3. Egiaztatu DB_HOST `sql107.infinityfree.com` dela (ez localhost)
4. Ziurtatu InfinityFree datu-base zerbitzua aktiboa dela

### Arazoa: "'documents' taula ez da existitzen"

**Konponbideak:**
1. Berriro exekutatu `mysql_zabala_gailetak_fresh_install.sql`
2. Egiaztatu datu-base zuzena hautatu duzula SQL exekutatu aurretik
3. Bilatu errore mezuak phpMyAdmin exekuzio log-ean

### Arazoa: "Ezin da fitxategiak igo" / "Baimena ukatua"

**Konponbideak:**
1. Sortu `storage/documents/` direktorioa falta bada
2. Ezarri direktorio baimenak `755`:
   ```bash
   chmod 755 storage
   chmod 755 storage/documents
   ```
3. Egiaztatu `UPLOAD_PATH` `.env`-an bide absolutu zuzenera apuntatzen duela
4. Ziurtatu InfinityFree diskoko kuota gainditu ez dela

### Arazoa: "Atzako gakoaren murrizketa huts egiten du"

**Konponbideak:**
1. Exekutatu SQL ordena zuenean (gurasoak seme-alabak baino lehen)
2. InfinityFree-k InnoDB ez badu onartzen, aldatu MyISAM-era:
   ```sql
   -- Find and replace in SQL file:
   ENGINE=InnoDB → ENGINE=MyISAM
   ```
3. Kendu FOREIGN KEY murrizketak beharrezkoa bada (ez da ideala)

### Arazoa: "UUID kate hutsa edo NULL da"

**Kausa:** MySQL-k ez du UUID sorrerarik natiboa PostgreSQL bezala.

**Konponbidea:** Ziurtatu PHP kodeak UUID-ak sortzen dituela INSERT baino lehen:
```php
// In controllers:
$id = $this->generateUUID(); // Must be called before INSERT
```

### Arazoa: "Opor trigger-ak ez dute funtzionatzen"

**Diagnostikoa:**
```sql
-- Check triggers exist
SHOW TRIGGERS LIKE 'vacation_requests';

-- If empty, install triggers
-- Run: scripts/mysql_vacation_triggers.sql
```

### Arazoa: "Dokumentu publikoak ez dira agertzen"

**Konponbidea:** Exekutatu Patch 1 (Onartu NULL employee_id):
```sql
ALTER TABLE `documents` 
MODIFY COLUMN `employee_id` VARCHAR(36) DEFAULT NULL;
```

---

## 📊 Datu-basearen mantentzea

### Babeskopia erregularrak

**Automatizatua (Gomendatua):**
1. Erabili InfinityFree-ren babeskopia funtzio integratua
2. Ordutegia: Egunero edo astero
3. Gorde babeskopiak kanpoko leku batean (Google Drive, Dropbox)

**Eskuzko esportazioa:**
```
phpMyAdmin → Export → Custom → 
- Format: SQL
- Structure: Include
- Data: Include
- Save as file
```

**Gomendatutako ordutegia:**
- Eguneraketa garrantzitsuak baino lehen
- Asteko babeskopia automatikoak
- Migrazioak exekutatu aurretik
- Datu garrantzitsuak gehitu ondoren

### Datu-basearen optimizazioa

Exekutatu hilero errendimendua mantentzeko:

```sql
-- Analyze tables
ANALYZE TABLE users, employees, documents, payroll, vacation_requests;

-- Optimize tables
OPTIMIZE TABLE users, employees, documents, payroll, vacation_requests;

-- Check table integrity
CHECK TABLE users, employees, documents, payroll;
```

### Garbitu datu zaharrak

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

## 🔄 Berrespen prozedurak

### Instalazio garbiak huts egiten badu

1. Sortutako taula guztiak ezabatu
2. Berrikusi errore mezuak phpMyAdmin-en
3. Konpondu sintaxi erroreak SQL fitxategian
4. Berriro exekutatu instalazioa

### Migrazioak huts egiten badu

1. Berreskuratu babeskopiatik:
   ```sql
   DROP DATABASE if0_40982238_zabala_gailetak;
   CREATE DATABASE if0_40982238_zabala_gailetak;
   ```
2. Inportatu babeskopia fitxategia phpMyAdmin bidez
3. Analizatu zer joan zen oker
4. Konpondu migrazio script-a
5. Saiatu berriro

### Larrialdiko berrespena

Datu-basea erabat apurtuta badago:

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

## 📞 Laguntza eta baliabideak

### Dokumentazioa
- [Database README](scripts/DATABASE_README.md) - Eskema dokumentazio xehea
- [API Documentation](API_DOCUMENTATION.md) - API endpoint erreferentzia
- [AGENTS.md](AGENTS.md) - Proiektuaren testuinguru osoa

### Egiaztapen kontsultak

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

## 🎯 Despliegue zerrenda

Inprimatu zerrenda hau eta markatu elementu bakoitza:

### Despliegue aurretik
- [ ] Babeskopia existitzen den datu-basea (egokia bada)
- [ ] Deskargatu `mysql_zabala_gailetak_fresh_install.sql`
- [ ] Izan InfinityFree kredentzialak prest
- [ ] Sortu JWT_SECRET eta PASSWORD_PEPPER
- [ ] Berrikusi eta pertsonalizatu `.env` fitxategia

### Datu-basearen konfigurazioa
- [ ] Sartu phpMyAdmin-era
- [ ] Hautatu datu-base zuzena
- [ ] Exekutatu SQL instalazio script-a
- [ ] Egiaztatu 20 taula sortu direla
- [ ] Exekutatu Patch 1 (documents NULL konponketa)
- [ ] Exekutatu Patch 2 (aukerako indizeak)
- [ ] Egiaztatu trigger-ak instalatuta daudela

### Fitxategi sistema
- [ ] Sortu `storage/documents/` direktorioa
- [ ] Sortu `logs/` direktorioa
- [ ] Ezarri baimen zuzenak (755)
- [ ] Igo `.env` fitxategia erroan
- [ ] Egiaztatu `.gitignore`-k `.env` baztertzen duela

### Konfigurazioa
- [ ] Eguneratu `APP_URL` `.env`-an
- [ ] Ezarri `APP_DEBUG=false`
- [ ] Konfiguratu datu-base kredentzialak
- [ ] Ezarri JWT_SECRET indartsua
- [ ] Ezarri PASSWORD_PEPPER indartsua
- [ ] Konfiguratu igoera bideak

### Baliozkotzea
- [ ] Probatu `/api/test/db` endpoint-a
- [ ] Probatu administratzailearen saioa hasiera
- [ ] Probatu payslips modulua
- [ ] Probatu dokumentuen igoera
- [ ] Probatu fitxategi deskargak
- [ ] Egiaztatu trigger-ak funtzionatzen dutela
- [ ] Egiaztatu log-ak erroreetarako

### Segurtasuna
- [ ] Aldatu administratzailearen pasahitza lehenetsia
- [ ] Kendu probako kontuak
- [ ] Berrikusi erabiltzaileen baimenak
- [ ] Gaitu HTTPS (InfinityFree-k hornitzen du)
- [ ] Ezarri saio cookie seguruak
- [ ] Probatu tasa muga

### Dokumentazioa
- [ ] Dokumentatu aldaketa pertsonalizatuak
- [ ] Gorde despliegue data/ordua
- [ ] Grabatu datu-basearen bertsioa
- [ ] Ohartu aurkitutako arazoak
- [ ] Eguneratu taldea despliegueari buruz

---

## 📅 Bertsioen historia

- **v2.0.0** (2026-02-06) - Payslips eta documents moduluen euskarria gehituta
- **v1.5.0** (2026-02-05) - Opor trigger-ak konponduak InfinityFree-rako
- **v1.0.0** (2026-01-14) - Hasierako produkzio desplieguea

---

**Azken eguneraketa:** 2026-02-06  
**Mantenduta:** Zabala Gailetak DevTeam  
**Egoera:** Produkziorako prest ✅
