# Babeskopia eta Berreskuratze Script-ak (Kopiatu eta Itsatsi)

**Helburua:** ZG-Data VM
**Erabiltzailea:** Root

## 1. Babeskopia Script-aren Instalazioa

Kopiatu eta itsatsi bloke hau babeskopia script-a sortzeko.

```bash
# Sortu Script-a
cat <<EOF > /usr/local/bin/backup-db.sh
#!/bin/bash

# KONFIGURAZIOA
BACKUP_DIR="/backups/postgres"
CONTAINER_NAME="zabala-postgres"
DB_USER="zabala_user"
DB_NAME="zabala_db"
RETENTION_DAYS=7

# EXEKUZIOA
mkdir -p \$BACKUP_DIR
TIMESTAMP=\$(date +%Y%m%d_%H%M%S)
FILENAME="db_\$TIMESTAMP.sql.gz"

echo "[\$(date)] Babeskopia hasten..."

# Dump eta Zip
docker exec \$CONTAINER_NAME pg_dump -U \$DB_USER \$DB_NAME | gzip > "\$BACKUP_DIR/\$FILENAME"

if [ \$? -eq 0 ]; then
  echo "[\$(date)] Babeskopia arrakastatsua: \$FILENAME"
else
  echo "[\$(date)] Babeskopia HUTSITU DA"
  exit 1
fi

# Zaharrak Garbitu
find \$BACKUP_DIR -name "db_*.sql.gz" -mtime +\$RETENTION_DAYS -delete
echo "[\$(date)] Garbiketa burutua"
EOF

# Exekutagarri Bihurtu
chmod +x /usr/local/bin/backup-db.sh
```

## 2. Antolatu Babeskopia Egunero (Cron)

```bash
# Gehitu Crontab-era (Egunero 2:00etan exekutatzen da)
(crontab -l 2>/dev/null; echo "0 2 * * * /usr/local/bin/backup-db.sh >> /var/log/db-backup.log 2>&1") | crontab -
```

## 3. Berreskuratze Script-a

Erabili hau fitxategi zehatz bat berreskuratzeko.

```bash
cat <<EOF > /usr/local/bin/restore-db.sh
#!/bin/bash

if [ -z "\$1" ]; then
  echo "Erabilera: \$0 <backup_file.sql.gz>"
  exit 1
fi

BACKUP_FILE=\$1
CONTAINER_NAME="zabala-postgres"
DB_USER="zabala_user"
DB_NAME="zabala_db"

echo "ABISUA: Honek \$DB_NAME datu-basea gainidatziko du. Jarraitu? (b/e)"
read confirm
if [ "\$confirm" != "b" ]; then exit; fi

echo "Berreskuratzen \$BACKUP_FILE-tik..."
gunzip -c \$BACKUP_FILE | docker exec -i \$CONTAINER_NAME psql -U \$DB_USER -d \$DB_NAME

echo "Berreskuratzea burutua."
EOF

chmod +x /usr/local/bin/restore-db.sh
```
