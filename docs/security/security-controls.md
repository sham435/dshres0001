# Security Controls

## Date: 2026-10-24

## Implemented Controls

### Authentication
- Password hashing: bcrypt (cost 12) for admin, Argon2ID for new users
- Session regeneration on login (session_regenerate_id(true))
- Session timeout: 2 hours idle
- CSRF token generation: bin2hex(random_bytes(32))
- Flash messages for auth feedback

### Session Security
- HttpOnly cookies: enabled
- SameSite: Lax
- Secure flag: false (needs HTTPS in production)
- Session name: school_session
- Idle timeout: 7200 seconds (2 hours)

### Security Headers (in public/index.php)
- X-Content-Type-Options: nosniff
- X-Frame-Options: SAMEORIGIN
- X-XSS-Protection: 1; mode=block
- Referrer-Policy: strict-origin-when-cross-origin
- Permissions-Policy: camera=(), microphone=(), geolocation=()
- Content-Security-Policy: configured

### Input Validation
- Server-side sanitization via htmlspecialchars()
- PDO prepared statements for all database queries
- File upload size limits (5MB)
- Allowed file extensions list

### CSRF Protection
- Token-based CSRF on all state-changing forms
- hash_equals() for timing-safe comparison
- Token stored in session

### Database Security
- PDO with ERRMODE_EXCEPTION
- EMULATE_PREPARES = false (real prepared statements)
- Separate DB connection per application

## Missing Controls
1. Rate limiting on login
2. MFA for admin users
3. HTTPS enforcement
4. File upload content validation
5. Audit logging
6. WAF integration
7. Dependency vulnerability scanning

## Inventory System Security Concerns
1. Uses MySQL with root user (no password)
2. Uses mysqli_real_escape_string (not prepared statements)
3. No CSRF protection
4. No session security configuration
5. No input validation on some endpoints