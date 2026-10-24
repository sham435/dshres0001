# Backup and Restore Procedures

## Date: 2026-10-24

## PostgreSQL Backup

### Full Database Backup
```bash
# Create backup directory
mkdir -p /backups/school_db

# Full backup with custom format (recommended)
pg_dump -U sham4 -d school_db -Fc -f /backups/school_db/school_db_$(date +%Y%m%d_%H%M%S).dump

# SQL format backup (for portability)
pg_dump -U sham4 -d school_db -f /backups/school_db/school_db_$(date +%Y%m%d_%H%M%S).sql
```

### Backup Verification
```bash
# List backup files
ls -la /backups/school_db/

# Verify backup integrity
pg_restore --list /backups/school_db/school_db_*.dump | head -20
```

### Restore Procedure (TEST ONLY - never on production without authorization)
```bash
# Create test database
createdb -U sham4 school_db_restore_test

# Restore from custom format
pg_restore -U sham4 -d school_db_restore_test /backups/school_db/school_db_latest.dump

# Verify restoration
psql -U sham4 -d school_db_restore_test -c "SELECT COUNT(*) FROM users;"
psql -U sham4 -d school_db_restore_test -c "SELECT COUNT(*) FROM students;"
psql -U sham4 -d school_db_restore_test -c "SELECT COUNT(*) FROM teachers;"

# Cleanup test database
dropdb -U sham4 school_db_restore_test
```

### RPO/RTO Targets
- **RPO (Recovery Point Objective):** 24 hours (daily backups)
- **RTO (Recovery Time Objective):** 1 hour (restore from backup)

### Retention Policy
- Daily backups: 7 days
- Weekly backups: 4 weeks
- Monthly backups: 12 months

### Security
- Backup files must have permissions 600 (owner read/write only)
- Store backups in a separate location from the database server
- Encrypt backups at rest using gpg or similar
- Never store backup credentials in scripts