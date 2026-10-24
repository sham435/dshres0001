# Threat Model

## Date: 2026-10-24

## Attack Surface
1. Web application (HTTP/HTTPS)
2. Database (PostgreSQL)
3. File uploads
4. Authentication endpoints
5. API endpoints (future)

## Threats Identified

### Critical
| ID | Threat | Impact | Mitigation |
|----|--------|--------|------------|
| T1 | SQL Injection | Data breach | PDO prepared statements (implemented) |
| T2 | Session Fixation | Account takeover | session_regenerate_id on login (implemented) |
| T3 | CSRF | Unauthorized actions | CSRF tokens on forms (implemented) |
| T4 | XSS | Script injection | htmlspecialchars() on output (implemented) |

### High
| ID | Threat | Impact | Mitigation |
|----|--------|--------|------------|
| T5 | Brute force login | Account compromise | Rate limiting (TODO) |
| T6 | Session hijacking | Account takeover | HttpOnly, Secure, SameSite cookies (implemented) |
| T7 | File upload abuse | RCE, storage exhaustion | Size/type validation (TODO) |
| T8 | Information disclosure | Data leak | Disable debug mode (implemented) |

### Medium
| ID | Threat | Impact | Mitigation |
|----|--------|--------|------------|
| T9 | Open redirect | Phishing | Validate redirect URLs (TODO) |
| T10 | Clickjacking | UI redress | X-Frame-Options (implemented) |
| T11 | MIME sniffing | XSS | X-Content-Type-Options (implemented) |

### Low
| ID | Threat | Impact | Mitigation |
|----|--------|--------|------------|
| T12 | Directory listing | Information disclosure | Options -Indexes (.htaccess) |
| T13 | Verbose errors | Information disclosure | display_errors=0 (implemented) |

## Residual Risks
1. No MFA for admin users
2. No rate limiting on login endpoint
3. No WAF (Web Application Firewall)
4. Single-school (no multi-tenancy)
5. No automated security scanning in CI/CD