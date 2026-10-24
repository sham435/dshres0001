# Current State Architecture Assessment

## Date: 2026-10-24

## System Overview

The platform is a School Management SaaS integrating three components:

### 1. School Management (Pure PHP MVC)
- **Location:** `/Users/sham4/dsh res/`
- **Entry point:** `public/index.php`
- **Architecture:** MVC (Model-View-Controller)
- **Database:** PostgreSQL 18.3 (`school_db`)
- **Routing:** Custom regex router (`core/Router.php`)
- **Auth:** Session-based with CSRF tokens
- **Frontend:** Bootstrap 5.3.8, jQuery, Vanilla JS, Tailwind CSS (standalone pages)

### 2. IT Lab Inventory System
- **Location:** `/Users/sham4/dsh res/inventory/`
- **Entry point:** `inventory/index.php`
- **Architecture:** Flat PHP (no MVC)
- **Database:** MySQL (assumed, needs verification)
- **Auth:** Session-based

### 3. Laravel Backend
- **Location:** `/Users/sham4/dsh res/` (merged)
- **Entry point:** `public/index.php` (Laravel) / `artisan serve`
- **Framework:** Laravel (version in composer.json)
- **Database:** Configured in `config/database.php`

## PHP Environment
- PHP 8.4.13 (CLI)
- Extensions: json, mbstring, openssl, PDO, pdo_pgsql, pdo_mysql, pdo_sqlite, pgsql
- PostgreSQL 18.3

## Application Structure
```
/Users/sham4/dsh res/
├── config/          # PHP MVC config + Laravel config
├── core/            # PHP MVC core (Database, Model, Controller, Router, Autoloader)
├── app/
│   ├── controllers/ # 14 PHP MVC controllers
│   ├── models/      # 33 Laravel Eloquent models + custom models
│   ├── views/       # 25 PHP MVC views
│   ├── Http/        # Laravel HTTP layer
│   ├── Console/     # Laravel console
│   └── Providers/   # Laravel service providers
├── public/          # Web root (PHP MVC + standalone HTML/PHP pages)
├── routes/          # web.php (PHP MVC routes)
├── helpers/         # Global helper functions
├── lang/            # i18n (en.php, es.php)
├── inventory/       # IT Lab Inventory System (copied)
├── storage/         # Uploads, logs, cache
├── database/        # Laravel migrations
├── docs/            # Documentation (newly created)
├── schema.sql       # PostgreSQL schema
└── .env             # Environment config
```

## Standalone Pages (19 files)
- about.html, en.html (homepage - Sinhala/English)
- album.php, album-en.php, album-ta.php (photo gallery)
- history-si.php, history-en.php, history-ta.php (history)
- clubs-si.php, clubs-ta.php (clubs)
- contact.php, contact-en.php, contact-ta.php (contact)
- login-si.php, login-en.php, login-ta.php (login)
- news-si.php, news-en.php, news-ta.php (news)
- anthem-si.php, anthem-en.php, anthem-ta.php (anthem)
- saas.php (SaaS hub)

## Database
- PostgreSQL 18.3
- Database: school_db
- 23 tables (users, students, teachers, classes, sections, subjects, timetables, attendance, exams, results, notices, leaves, transport, syllabi, notes, settings, sessions, csrf_tokens)
- 3 views (v_student_info, v_teacher_info, v_exam_results)

## Authentication
- Session-based with CSRF tokens
- Password hashing: bcrypt (PASSWORD_BCRYPT) for admin, Argon2ID for new users
- Role-based access control (admin, teacher, student, parent, staff)

## Security Concerns Identified
1. No password complexity enforcement
2. CSRF tokens not validated on all routes
3. No rate limiting on login
4. No input sanitization on all endpoints
5. Session fixation risk (session ID not regenerated on login)
6. No HTTPS enforcement
7. Debug mode enabled
8. No security headers configured
9. No prepared statements in inventory system (needs audit)
10. No tenant isolation (single-school)

## Known Issues
1. Inventory system uses MySQL, school system uses PostgreSQL
2. Laravel backend and PHP MVC share the same directory
3. No automated test suite
4. No CI/CD pipeline
5. No monitoring or logging
6. No backup/restore procedures documented
7. No staging environment

## Dependencies
- PHP 8.4.13
- PostgreSQL 18.3
- Bootstrap 5.3.8
- jQuery 3.7.1
- Tailwind CSS (CDN)
- Alpine.js 3.x
- Leaflet 1.9.4
- AOS 2.3.1
- Composer (Laravel)
- npm (Laravel frontend)