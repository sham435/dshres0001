# Deployment Runbook

## Date: 2026-10-24

## Prerequisites
- PHP 8.4+ with extensions: pdo, pdo_pgsql, mbstring, openssl, json
- PostgreSQL 18+
- Web server (Apache/Nginx) with mod_rewrite
- Composer (for Laravel backend)

## Deployment Steps

### 1. Environment Setup
```bash
# Copy environment template
cp .env.example .env

# Edit .env with production values
# Set APP_ENV=production
# Set APP_DEBUG=false
# Set DB credentials
# Set SESSION_SECURE=true
```

### 2. Database Setup
```bash
# Create database
createdb -U postgres school_db

# Load schema
psql -U postgres -d school_db -f schema.sql

# Create application user with limited permissions
psql -U postgres -d school_db -c "CREATE USER school_app WITH PASSWORD 'secure_password';"
psql -U postgres -d school_db -c "GRANT CONNECT ON DATABASE school_db TO school_app;"
psql -U postgres -d school_db -c "GRANT USAGE ON SCHEMA public TO school_app;"
psql -U postgres -d school_db -c "GRANT SELECT, INSERT, UPDATE, DELETE ON ALL TABLES IN SCHEMA public TO school_app;"
```

### 3. Web Server Configuration (Apache)
```apache
<VirtualHost *:443>
    ServerName school.example.com
    DocumentRoot /var/www/school/public

    SSLEngine on
    SSLCertificateFile /etc/ssl/certs/school.crt
    SSLCertificateKeyFile /etc/ssl/private/school.key

    <Directory /var/www/school/public>
        AllowOverride All
        Require all granted
    </Directory>

    # Security headers
    Header always set X-Content-Type-Options nosniff
    Header always set X-Frame-Options SAMEORIGIN
    Header always set Strict-Transport-Security "max-age=31536000; includeSubDomains"

    # PHP settings
    php_value display_errors Off
    php_value log_errors On
</VirtualHost>

# HTTP to HTTPS redirect
<VirtualHost *:80>
    ServerName school.example.com
    Redirect permanent / https://school.example.com/
</VirtualHost>
```

### 4. PHP Configuration (php.ini)
```ini
display_errors = Off
log_errors = On
error_log = /var/log/php/errors.log
session.cookie_httponly = 1
session.cookie_secure = 1
session.cookie_samesite = "Lax"
session.gc_maxlifetime = 7200
upload_max_filesize = 5M
post_max_size = 6M
```

### 5. File Permissions
```bash
# Set ownership
chown -R www-data:www-data /var/www/school

# Set permissions
find /var/www/school -type f -exec chmod 644 {} \;
find /var/www/school -type d -exec chmod 755 {} \;
chmod 600 /var/www/school/.env
chmod 755 /var/www/school/storage/uploads
```

### 6. Verification
```bash
# Syntax check
find /var/www/school -name "*.php" -exec php -l {} \;

# Database connectivity
psql -U school_app -d school_db -c "SELECT 1;"

# Application health
curl -s -o /dev/null -w "%{http_code}" https://school.example.com/

# Security headers
curl -sI https://school.example.com/ | grep -i "X-Frame\|X-Content\|Strict-Transport"
```

### 7. Rollback
```bash
# Restore from backup
pg_restore -U postgres -d school_db /backups/school_db_latest.dump

# Revert code (if using git)
git checkout <previous-commit>

# Restart web server
systemctl restart apache2
```