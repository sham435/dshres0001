# Remediation Plan

## Date: 2026-10-24

## Priority 1: Critical (Must fix before production)

### R1: Implement Rate Limiting on Login
- **Component:** AuthController
- **Effort:** Medium
- **Action:** Add IP-based rate limiting (5 attempts per 15 minutes)
- **Status:** TODO

### R2: HTTPS Configuration
- **Component:** Web server
- **Effort:** Low
- **Action:** Configure SSL certificate, enable HSTS
- **Status:** TODO

### R3: Session Secure Flag
- **Component:** config.php
- **Effort:** Low
- **Action:** Set session.secure=true when HTTPS is enabled
- **Status:** TODO

## Priority 2: High (Should fix before production)

### R4: Automated Test Suite
- **Component:** All
- **Effort:** High
- **Action:** Create PHPUnit tests for controllers, models, and security
- **Status:** TODO

### R5: Backup/Restore Verification
- **Component:** Database
- **Effort:** Medium
- **Action:** Test pg_dump/restore, document procedure
- **Status:** TODO

### R6: Password Complexity
- **Component:** AuthController
- **Effort:** Low
- **Action:** Enforce minimum 8 chars, mixed case, number, special char
- **Status:** TODO

### R7: Input Validation
- **Component:** All controllers
- **Effort:** Medium
- **Action:** Add server-side validation to all endpoints
- **Status:** TODO

## Priority 3: Medium (Nice to have)

### R8: MFA for Admins
- **Component:** AuthController
- **Effort:** High
- **Action:** Implement TOTP-based MFA
- **Status:** TODO

### R9: Monitoring & Logging
- **Component:** All
- **Effort:** Medium
- **Action:** Add structured logging, error tracking
- **Status:** TODO

### R10: CSP Header
- **Component:** public/index.php
- **Effort:** Low
- **Action:** Add Content-Security-Policy header
- **Status:** TODO

## Priority 4: Low (Future improvements)

### R11: Multi-tenancy
- **Component:** Database, Auth
- **Effort:** Very High
- **Action:** Add school_id to all tables, implement tenant isolation
- **Status:** FUTURE

### R12: API Layer
- **Component:** New
- **Effort:** High
- **Action:** REST API for mobile app
- **Status:** FUTURE