# Backup & Recovery Scripts (Copy-Paste)

**Target:** ZG-Data VM
**User:** Root

## 1. Backup Script Installation

Copy and paste this block to create the backup script.

```bash
# Create Script
cat <<EOF > /usr/local/bin/backup-db.sh
#!/bin/bash

# CONFIGURATION
BACKUP_DIR="/backups/postgres"
CONTAINER_NAME="zabala-postgres"
DB_USER="zabala_user"
DB_NAME="zabala_db"
RETENTION_DAYS=7

# EXECUTION
mkdir -p \$BACKUP_DIR
TIMESTAMP=\$(date +%Y%m%d_%H%M%S)
FILENAME="db_\$TIMESTAMP.sql.gz"

echo "[\$(date)] Starting Backup..."

# Dump and Zip
docker exec \$CONTAINER_NAME pg_dump -U \$DB_USER \$DB_NAME | gzip > "\$BACKUP_DIR/\$FILENAME"

if [ \$? -eq 0 ]; then
  echo "[\$(date)] Backup Success: \$FILENAME"
else
  echo "[\$(date)] Backup FAILED"
  exit 1
fi

# Cleanup Old Backups
find \$BACKUP_DIR -name "db_*.sql.gz" -mtime +\$RETENTION_DAYS -delete
echo "[\$(date)] Cleanup Complete"
EOF

# Make Executable
chmod +x /usr/local/bin/backup-db.sh
```

## 2. Schedule Daily Backup (Cron)

```bash
# Add to Crontab (Runs daily at 2:00 AM)
(crontab -l 2>/dev/null; echo "0 2 * * * /usr/local/bin/backup-db.sh >> /var/log/db-backup.log 2>&1") | crontab -
```

## 3. Restore Script

Use this to restore a specific file.

```bash
cat <<EOF > /usr/local/bin/restore-db.sh
#!/bin/bash

if [ -z "\$1" ]; then
  echo "Usage: \$0 <backup_file.sql.gz>"
  exit 1
fi

BACKUP_FILE=\$1
CONTAINER_NAME="zabala-postgres"
DB_USER="zabala_user"
DB_NAME="zabala_db"

echo "WARNING: This will OVERWRITE database \$DB_NAME. Continue? (y/n)"
read confirm
if [ "\$confirm" != "y" ]; then exit; fi

echo "Restoring from \$BACKUP_FILE..."
gunzip -c \$BACKUP_FILE | docker exec -i \$CONTAINER_NAME psql -U \$DB_USER -d \$DB_NAME

echo "Restore Complete."
EOF

chmod +x /usr/local/bin/restore-db.sh
```
