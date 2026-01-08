#!/bin/bash
#
# MongoDB Backup Script
# Performs automated database backups with compression and retention management
#

set -e

# Configuration
BACKUP_DIR="${BACKUP_DIR:-/backups}"
MONGO_HOST="${MONGO_HOST:-mongodb}"
MONGO_PORT="${MONGO_PORT:-27017}"
MONGO_USER="${MONGO_USER:-admin}"
MONGO_PASSWORD="${MONGO_PASSWORD}"
RETENTION_DAYS="${RETENTION_DAYS:-7}"

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Logging functions
log_info() {
    echo -e "${GREEN}[INFO]${NC} $(date '+%Y-%m-%d %H:%M:%S') - $1"
}

log_error() {
    echo -e "${RED}[ERROR]${NC} $(date '+%Y-%m-%d %H:%M:%S') - $1"
}

log_warn() {
    echo -e "${YELLOW}[WARN]${NC} $(date '+%Y-%m-%d %H:%M:%S') - $1"
}

# Check prerequisites
check_prerequisites() {
    if ! command -v mongodump &> /dev/null; then
        log_error "mongodump command not found"
        exit 1
    fi
    
    if [ -z "$MONGO_PASSWORD" ]; then
        log_error "MONGO_PASSWORD is not set"
        exit 1
    fi
    
    if [ ! -d "$BACKUP_DIR" ]; then
        log_info "Creating backup directory: $BACKUP_DIR"
        mkdir -p "$BACKUP_DIR"
    fi
}

# Perform backup
perform_backup() {
    local timestamp=$(date +%Y%m%d_%H%M%S)
    local backup_name="zabala-gailetak-${timestamp}"
    local backup_path="${BACKUP_DIR}/${backup_name}"
    
    log_info "Starting MongoDB backup..."
    log_info "Backup name: ${backup_name}"
    
    # Perform mongodump
    mongodump \
        --host="${MONGO_HOST}" \
        --port="${MONGO_PORT}" \
        --username="${MONGO_USER}" \
        --password="${MONGO_PASSWORD}" \
        --authenticationDatabase=admin \
        --out="${backup_path}" \
        --gzip \
        2>&1
    
    if [ $? -eq 0 ]; then
        log_info "MongoDB dump completed successfully"
        
        # Create tarball
        log_info "Creating compressed archive..."
        tar -czf "${backup_path}.tar.gz" -C "${BACKUP_DIR}" "${backup_name}"
        
        if [ $? -eq 0 ]; then
            # Calculate size
            local size=$(du -h "${backup_path}.tar.gz" | cut -f1)
            log_info "Backup archive created: ${backup_path}.tar.gz (${size})"
            
            # Remove uncompressed dump
            rm -rf "${backup_path}"
            
            # Calculate checksum
            local checksum=$(sha256sum "${backup_path}.tar.gz" | cut -d' ' -f1)
            echo "${checksum}  ${backup_name}.tar.gz" > "${backup_path}.tar.gz.sha256"
            log_info "Checksum: ${checksum}"
            
            return 0
        else
            log_error "Failed to create backup archive"
            return 1
        fi
    else
        log_error "MongoDB dump failed"
        return 1
    fi
}

# Clean old backups
clean_old_backups() {
    log_info "Cleaning backups older than ${RETENTION_DAYS} days..."
    
    local deleted_count=0
    
    # Find and delete old backups
    find "${BACKUP_DIR}" -name "zabala-gailetak-*.tar.gz" -type f -mtime +${RETENTION_DAYS} | while read -r file; do
        log_info "Deleting old backup: $(basename "$file")"
        rm -f "$file"
        rm -f "${file}.sha256"
        deleted_count=$((deleted_count + 1))
    done
    
    if [ $deleted_count -gt 0 ]; then
        log_info "Deleted ${deleted_count} old backup(s)"
    else
        log_info "No old backups to delete"
    fi
}

# List backups
list_backups() {
    log_info "Current backups:"
    echo ""
    
    local backup_count=0
    
    for file in "${BACKUP_DIR}"/zabala-gailetak-*.tar.gz; do
        if [ -f "$file" ]; then
            local size=$(du -h "$file" | cut -f1)
            local date=$(stat -c %y "$file" | cut -d' ' -f1,2 | cut -d'.' -f1)
            echo "  - $(basename "$file") (${size}, ${date})"
            backup_count=$((backup_count + 1))
        fi
    done
    
    if [ $backup_count -eq 0 ]; then
        echo "  No backups found"
    else
        echo ""
        log_info "Total backups: ${backup_count}"
    fi
}

# Send notification (optional)
send_notification() {
    local status=$1
    local message=$2
    
    # Add notification logic here (email, Slack, etc.)
    # For now, just log
    if [ "$status" = "success" ]; then
        log_info "Notification: $message"
    else
        log_error "Notification: $message"
    fi
}

# Main execution
main() {
    log_info "=== MongoDB Backup Script ==="
    log_info "Host: ${MONGO_HOST}:${MONGO_PORT}"
    log_info "Backup Directory: ${BACKUP_DIR}"
    log_info "Retention: ${RETENTION_DAYS} days"
    echo ""
    
    check_prerequisites
    
    if perform_backup; then
        clean_old_backups
        list_backups
        send_notification "success" "MongoDB backup completed successfully"
        log_info "=== Backup completed successfully ==="
        exit 0
    else
        send_notification "failure" "MongoDB backup failed"
        log_error "=== Backup failed ==="
        exit 1
    fi
}

# Run main function
main
