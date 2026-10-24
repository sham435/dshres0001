#!/bin/bash
# PostgreSQL Backup Script
# Usage: ./scripts/backup.sh

set -euo pipefail

# Configuration
DB_NAME="${DB_NAME:-school_db}"
DB_USER="${DB_USER:-sham4}"
BACKUP_DIR="${BACKUP_DIR:-/backups/school_db}"
RETENTION_DAYS=7
TIMESTAMP=$(date +%Y%m%d_%H%M%S)
BACKUP_FILE="$BACKUP_DIR/school_db_$TIMESTAMP.dump"

# Create backup directory
mkdir -p "$BACKUP_DIR"

# Set restrictive permissions
chmod 700 "$BACKUP_DIR"

# Perform backup
echo "Starting backup at $(date)"
if pg_dump -U "$DB_USER" -d "$DB_NAME" -Fc -f "$BACKUP_FILE"; then
    echo "Backup successful: $BACKUP_FILE"
    chmod 600 "$BACKUP_FILE"
    
    # Cleanup old backups
    find "$BACKUP_DIR" -name "school_db_*.dump" -mtime +$RETENTION_DAYS -delete
    echo "Old backups cleaned up (retention: $RETENTION_DAYS days)"
    
    # Log success
    echo "$(date): Backup successful - $BACKUP_FILE" >> "$BACKUP_DIR/backup.log"
    exit 0
else
    echo "Backup FAILED at $(date)"
    echo "$(date): Backup FAILED" >> "$BACKUP_DIR/backup.log"
    exit 1
fi