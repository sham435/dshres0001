-- ============================================================
-- School Management System - PostgreSQL 18.6 Schema
-- ============================================================
-- Optimized indexing, foreign keys, and relational integrity
-- ============================================================

-- Enable UUID extension for primary keys
CREATE EXTENSION IF NOT EXISTS "uuid-ossp";
CREATE EXTENSION IF NOT EXISTS "pgcrypto";

-- ============================================================
-- ENUMS
-- ============================================================

CREATE TYPE user_role AS ENUM (
    'admin', 'teacher', 'student', 'parent', 'staff'
);

CREATE TYPE gender_type AS ENUM ('male', 'female', 'other');

CREATE TYPE attendance_status AS ENUM ('present', 'absent', 'late', 'excused');

CREATE TYPE leave_status AS ENUM ('pending', 'approved', 'rejected', 'cancelled');

CREATE TYPE leave_type AS ENUM ('sick', 'casual', 'earned', 'maternity', 'paternity', 'unpaid');

CREATE TYPE exam_type AS ENUM ('midterm', 'final', 'quiz', 'assignment', 'practical');

CREATE TYPE grade_letter AS ENUM ('A+', 'A', 'A-', 'B+', 'B', 'B-', 'C+', 'C', 'C-', 'D', 'F');

CREATE TYPE transport_status AS ENUM ('active', 'inactive', 'maintenance');

CREATE TYPE notice_priority AS ENUM ('low', 'normal', 'high', 'urgent');

CREATE TYPE notice_audience AS ENUM ('all', 'students', 'teachers', 'parents', 'staff');

-- ============================================================
-- USERS & AUTHENTICATION
-- ============================================================

CREATE TABLE users (
    id              UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
    username        VARCHAR(50)  NOT NULL UNIQUE,
    email           VARCHAR(255) NOT NULL UNIQUE,
    password_hash   VARCHAR(255) NOT NULL,
    role            user_role    NOT NULL DEFAULT 'student',
    is_active       BOOLEAN      NOT NULL DEFAULT TRUE,
    email_verified  BOOLEAN      NOT NULL DEFAULT FALSE,
    verification_token VARCHAR(255),
    reset_token     VARCHAR(255),
    reset_token_expires TIMESTAMPTZ,
    last_login      TIMESTAMPTZ,
    created_at      TIMESTAMPTZ  NOT NULL DEFAULT NOW(),
    updated_at      TIMESTAMPTZ  NOT NULL DEFAULT NOW()
);

CREATE INDEX idx_users_email ON users(email);
CREATE INDEX idx_users_username ON users(username);
CREATE INDEX idx_users_role ON users(role);
CREATE INDEX idx_users_reset_token ON users(reset_token) WHERE reset_token IS NOT NULL;

-- ============================================================
-- STUDENTS
-- ============================================================

CREATE TABLE students (
    id              UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
    user_id         UUID NOT NULL UNIQUE REFERENCES users(id) ON DELETE CASCADE,
    student_id      VARCHAR(20) NOT NULL UNIQUE,
    first_name      VARCHAR(100) NOT NULL,
    last_name       VARCHAR(100) NOT NULL,
    date_of_birth   DATE,
    gender          gender_type,
    blood_group     VARCHAR(5),
    religion        VARCHAR(50),
    nationality     VARCHAR(50) DEFAULT 'Unknown',
    phone           VARCHAR(20),
    address         TEXT,
    city            VARCHAR(100),
    state           VARCHAR(100),
    zip_code        VARCHAR(20),
    photo_url       VARCHAR(500),
    admission_date  DATE NOT NULL DEFAULT CURRENT_DATE,
    class_id        UUID,
    section_id      UUID,
    roll_number     VARCHAR(20),
    is_active       BOOLEAN NOT NULL DEFAULT TRUE,
    created_at      TIMESTAMPTZ NOT NULL DEFAULT NOW(),
    updated_at      TIMESTAMPTZ NOT NULL DEFAULT NOW()
);

CREATE INDEX idx_students_user_id ON students(user_id);
CREATE INDEX idx_students_student_id ON students(student_id);
CREATE INDEX idx_students_class_id ON students(class_id);
CREATE INDEX idx_students_section_id ON students(section_id);
CREATE INDEX idx_students_name ON students(first_name, last_name);

-- Emergency Contacts
CREATE TABLE emergency_contacts (
    id              UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
    student_id      UUID NOT NULL REFERENCES students(id) ON DELETE CASCADE,
    contact_name    VARCHAR(200) NOT NULL,
    relationship    VARCHAR(50) NOT NULL,
    phone           VARCHAR(20) NOT NULL,
    email           VARCHAR(255),
    address         TEXT,
    is_primary      BOOLEAN NOT NULL DEFAULT FALSE,
    created_at      TIMESTAMPTZ NOT NULL DEFAULT NOW()
);

CREATE INDEX idx_emergency_contacts_student ON emergency_contacts(student_id);

-- ============================================================
-- TEACHERS
-- ============================================================

CREATE TABLE teachers (
    id              UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
    user_id         UUID NOT NULL UNIQUE REFERENCES users(id) ON DELETE CASCADE,
    teacher_id      VARCHAR(20) NOT NULL UNIQUE,
    first_name      VARCHAR(100) NOT NULL,
    last_name       VARCHAR(100) NOT NULL,
    date_of_birth   DATE,
    gender          gender_type,
    phone           VARCHAR(20),
    address         TEXT,
    city            VARCHAR(100),
    state           VARCHAR(100),
    zip_code        VARCHAR(20),
    photo_url       VARCHAR(500),
    qualification   VARCHAR(255),
    specialization  VARCHAR(255),
    department      VARCHAR(100),
    joining_date    DATE NOT NULL DEFAULT CURRENT_DATE,
    salary          DECIMAL(12,2),
    is_active       BOOLEAN NOT NULL DEFAULT TRUE,
    created_at      TIMESTAMPTZ NOT NULL DEFAULT NOW(),
    updated_at      TIMESTAMPTZ NOT NULL DEFAULT NOW()
);

CREATE INDEX idx_teachers_user_id ON teachers(user_id);
CREATE INDEX idx_teachers_teacher_id ON teachers(teacher_id);
CREATE INDEX idx_teachers_department ON teachers(department);
CREATE INDEX idx_teachers_name ON teachers(first_name, last_name);

-- ============================================================
-- ACADEMICS: CLASSES, SECTIONS, SUBJECTS
-- ============================================================

CREATE TABLE classes (
    id              UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
    class_name      VARCHAR(50) NOT NULL,
    class_numeric   INTEGER NOT NULL,
    description     TEXT,
    is_active       BOOLEAN NOT NULL DEFAULT TRUE,
    created_at      TIMESTAMPTZ NOT NULL DEFAULT NOW(),
    updated_at      TIMESTAMPTZ NOT NULL DEFAULT NOW(),
    UNIQUE (class_name, class_numeric)
);

CREATE INDEX idx_classes_numeric ON classes(class_numeric);

CREATE TABLE sections (
    id              UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
    section_name    VARCHAR(10) NOT NULL,
    class_id        UUID NOT NULL REFERENCES classes(id) ON DELETE CASCADE,
    capacity        INTEGER DEFAULT 40,
    is_active       BOOLEAN NOT NULL DEFAULT TRUE,
    created_at      TIMESTAMPTZ NOT NULL DEFAULT NOW(),
    UNIQUE (section_name, class_id)
);

CREATE INDEX idx_sections_class_id ON sections(class_id);

CREATE TABLE subjects (
    id              UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
    subject_name    VARCHAR(100) NOT NULL,
    subject_code    VARCHAR(20) NOT NULL UNIQUE,
    description     TEXT,
    is_active       BOOLEAN NOT NULL DEFAULT TRUE,
    created_at      TIMESTAMPTZ NOT NULL DEFAULT NOW()
);

CREATE INDEX idx_subjects_code ON subjects(subject_code);

-- Class-Subject mapping (which subjects are taught in which class)
CREATE TABLE class_subjects (
    id              UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
    class_id        UUID NOT NULL REFERENCES classes(id) ON DELETE CASCADE,
    subject_id      UUID NOT NULL REFERENCES subjects(id) ON DELETE CASCADE,
    teacher_id      UUID REFERENCES teachers(id) ON DELETE SET NULL,
    created_at      TIMESTAMPTZ NOT NULL DEFAULT NOW(),
    UNIQUE (class_id, subject_id)
);

CREATE INDEX idx_class_subjects_class ON class_subjects(class_id);
CREATE INDEX idx_class_subjects_subject ON class_subjects(subject_id);
CREATE INDEX idx_class_subjects_teacher ON class_subjects(teacher_id);

-- ============================================================
-- TIMETABLE
-- ============================================================

CREATE TABLE timetables (
    id              UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
    class_id        UUID NOT NULL REFERENCES classes(id) ON DELETE CASCADE,
    section_id      UUID NOT NULL REFERENCES sections(id) ON DELETE CASCADE,
    subject_id      UUID NOT NULL REFERENCES subjects(id) ON DELETE CASCADE,
    teacher_id      UUID NOT NULL REFERENCES teachers(id) ON DELETE CASCADE,
    day_of_week     SMALLINT NOT NULL CHECK (day_of_week BETWEEN 1 AND 7),
    period_number   SMALLINT NOT NULL CHECK (period_number BETWEEN 1 AND 12),
    start_time      TIME NOT NULL,
    end_time        TIME NOT NULL,
    room_number     VARCHAR(20),
    is_active       BOOLEAN NOT NULL DEFAULT TRUE,
    created_at      TIMESTAMPTZ NOT NULL DEFAULT NOW(),
    updated_at      TIMESTAMPTZ NOT NULL DEFAULT NOW(),
    UNIQUE (class_id, section_id, day_of_week, period_number)
);

CREATE INDEX idx_timetables_class ON timetables(class_id, section_id);
CREATE INDEX idx_timetables_teacher ON timetables(teacher_id);
CREATE INDEX idx_timetables_day ON timetables(day_of_week);

-- ============================================================
-- ATTENDANCE
-- ============================================================

CREATE TABLE student_attendance (
    id              UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
    student_id      UUID NOT NULL REFERENCES students(id) ON DELETE CASCADE,
    class_id        UUID NOT NULL REFERENCES classes(id) ON DELETE CASCADE,
    section_id      UUID NOT NULL REFERENCES sections(id) ON DELETE CASCADE,
    attendance_date DATE NOT NULL,
    status          attendance_status NOT NULL DEFAULT 'present',
    remarks         TEXT,
    marked_by       UUID REFERENCES users(id) ON DELETE SET NULL,
    created_at      TIMESTAMPTZ NOT NULL DEFAULT NOW(),
    UNIQUE (student_id, attendance_date)
);

CREATE INDEX idx_student_attendance_date ON student_attendance(attendance_date);
CREATE INDEX idx_student_attendance_student ON student_attendance(student_id);
CREATE INDEX idx_student_attendance_class ON student_attendance(class_id, section_id, attendance_date);

CREATE TABLE staff_attendance (
    id              UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
    user_id         UUID NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    attendance_date DATE NOT NULL,
    check_in        TIMESTAMPTZ,
    check_out       TIMESTAMPTZ,
    status          attendance_status NOT NULL DEFAULT 'present',
    remarks         TEXT,
    created_at      TIMESTAMPTZ NOT NULL DEFAULT NOW(),
    UNIQUE (user_id, attendance_date)
);

CREATE INDEX idx_staff_attendance_date ON staff_attendance(attendance_date);
CREATE INDEX idx_staff_attendance_user ON staff_attendance(user_id);

-- ============================================================
-- LEAVE MANAGEMENT
-- ============================================================

CREATE TABLE leave_requests (
    id              UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
    user_id         UUID NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    leave_type      leave_type NOT NULL,
    start_date      DATE NOT NULL,
    end_date        DATE NOT NULL,
    reason          TEXT NOT NULL,
    status          leave_status NOT NULL DEFAULT 'pending',
    approved_by     UUID REFERENCES users(id) ON DELETE SET NULL,
    approved_at     TIMESTAMPTZ,
    rejection_reason TEXT,
    created_at      TIMESTAMPTZ NOT NULL DEFAULT NOW(),
    updated_at      TIMESTAMPTZ NOT NULL DEFAULT NOW(),
    CONSTRAINT chk_leave_dates CHECK (end_date >= start_date)
);

CREATE INDEX idx_leave_requests_user ON leave_requests(user_id);
CREATE INDEX idx_leave_requests_status ON leave_requests(status);
CREATE INDEX idx_leave_requests_dates ON leave_requests(start_date, end_date);

-- ============================================================
-- EXAMS & RESULTS
-- ============================================================

CREATE TABLE exams (
    id              UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
    exam_name       VARCHAR(200) NOT NULL,
    exam_type       exam_type NOT NULL,
    class_id        UUID NOT NULL REFERENCES classes(id) ON DELETE CASCADE,
    subject_id      UUID NOT NULL REFERENCES subjects(id) ON DELETE CASCADE,
    exam_date       DATE NOT NULL,
    start_time      TIME,
    end_time        TIME,
    total_marks     DECIMAL(6,2) NOT NULL DEFAULT 100,
    passing_marks   DECIMAL(6,2) NOT NULL DEFAULT 40,
    is_published    BOOLEAN NOT NULL DEFAULT FALSE,
    created_by      UUID REFERENCES users(id) ON DELETE SET NULL,
    created_at      TIMESTAMPTZ NOT NULL DEFAULT NOW(),
    updated_at      TIMESTAMPTZ NOT NULL DEFAULT NOW()
);

CREATE INDEX idx_exams_class ON exams(class_id);
CREATE INDEX idx_exams_subject ON exams(subject_id);
CREATE INDEX idx_exams_date ON exams(exam_date);

CREATE TABLE exam_results (
    id              UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
    exam_id         UUID NOT NULL REFERENCES exams(id) ON DELETE CASCADE,
    student_id      UUID NOT NULL REFERENCES students(id) ON DELETE CASCADE,
    marks_obtained  DECIMAL(6,2) NOT NULL,
    grade           grade_letter,
    gpa             DECIMAL(4,2),
    remarks         TEXT,
    submitted_by    UUID REFERENCES users(id) ON DELETE SET NULL,
    submitted_at    TIMESTAMPTZ NOT NULL DEFAULT NOW(),
    updated_at      TIMESTAMPTZ NOT NULL DEFAULT NOW(),
    UNIQUE (exam_id, student_id)
);

CREATE INDEX idx_exam_results_exam ON exam_results(exam_id);
CREATE INDEX idx_exam_results_student ON exam_results(student_id);

-- ============================================================
-- SYLLABUS & ACADEMIC NOTES
-- ============================================================

CREATE TABLE syllabi (
    id              UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
    title           VARCHAR(255) NOT NULL,
    class_id        UUID NOT NULL REFERENCES classes(id) ON DELETE CASCADE,
    subject_id      UUID NOT NULL REFERENCES subjects(id) ON DELETE CASCADE,
    file_path       VARCHAR(500) NOT NULL,
    file_name       VARCHAR(255) NOT NULL,
    file_size       BIGINT,
    description     TEXT,
    uploaded_by     UUID REFERENCES users(id) ON DELETE SET NULL,
    is_active       BOOLEAN NOT NULL DEFAULT TRUE,
    created_at      TIMESTAMPTZ NOT NULL DEFAULT NOW(),
    updated_at      TIMESTAMPTZ NOT NULL DEFAULT NOW()
);

CREATE INDEX idx_syllabi_class ON syllabi(class_id);
CREATE INDEX idx_syllabi_subject ON syllabi(subject_id);

CREATE TABLE academic_notes (
    id              UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
    title           VARCHAR(255) NOT NULL,
    description     TEXT,
    class_id        UUID NOT NULL REFERENCES classes(id) ON DELETE CASCADE,
    subject_id      UUID NOT NULL REFERENCES subjects(id) ON DELETE CASCADE,
    teacher_id      UUID NOT NULL REFERENCES teachers(id) ON DELETE CASCADE,
    file_path       VARCHAR(500) NOT NULL,
    file_name       VARCHAR(255) NOT NULL,
    file_size       BIGINT,
    file_type       VARCHAR(50),
    is_published    BOOLEAN NOT NULL DEFAULT FALSE,
    created_at      TIMESTAMPTZ NOT NULL DEFAULT NOW(),
    updated_at      TIMESTAMPTZ NOT NULL DEFAULT NOW()
);

CREATE INDEX idx_academic_notes_class ON academic_notes(class_id);
CREATE INDEX idx_academic_notes_subject ON academic_notes(subject_id);
CREATE INDEX idx_academic_notes_teacher ON academic_notes(teacher_id);

-- ============================================================
-- TRANSPORT / BUS SERVICE
-- ============================================================

CREATE TABLE bus_routes (
    id              UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
    route_name      VARCHAR(100) NOT NULL,
    route_number    VARCHAR(20) NOT NULL UNIQUE,
    start_point     VARCHAR(255) NOT NULL,
    end_point       VARCHAR(255) NOT NULL,
    stops           TEXT,  -- JSON array of stops
    distance_km     DECIMAL(8,2),
    estimated_time  INTEGER,  -- in minutes
    is_active       BOOLEAN NOT NULL DEFAULT TRUE,
    created_at      TIMESTAMPTZ NOT NULL DEFAULT NOW(),
    updated_at      TIMESTAMPTZ NOT NULL DEFAULT NOW()
);

CREATE INDEX idx_bus_routes_number ON bus_routes(route_number);

CREATE TABLE buses (
    id              UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
    bus_number      VARCHAR(20) NOT NULL UNIQUE,
    license_plate   VARCHAR(20) NOT NULL UNIQUE,
    capacity        INTEGER NOT NULL DEFAULT 40,
    route_id        UUID REFERENCES bus_routes(id) ON DELETE SET NULL,
    driver_name     VARCHAR(200),
    driver_phone    VARCHAR(20),
    driver_license  VARCHAR(50),
    status          transport_status NOT NULL DEFAULT 'active',
    created_at      TIMESTAMPTZ NOT NULL DEFAULT NOW(),
    updated_at      TIMESTAMPTZ NOT NULL DEFAULT NOW()
);

CREATE INDEX idx_buses_route ON buses(route_id);
CREATE INDEX idx_buses_status ON buses(status);

CREATE TABLE student_transport (
    id              UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
    student_id      UUID NOT NULL REFERENCES students(id) ON DELETE CASCADE,
    route_id        UUID NOT NULL REFERENCES bus_routes(id) ON DELETE CASCADE,
    bus_id          UUID REFERENCES buses(id) ON DELETE SET NULL,
    pickup_stop     VARCHAR(255),
    drop_stop       VARCHAR(255),
    is_active       BOOLEAN NOT NULL DEFAULT TRUE,
    created_at      TIMESTAMPTZ NOT NULL DEFAULT NOW(),
    UNIQUE (student_id)
);

CREATE INDEX idx_student_transport_route ON student_transport(route_id);
CREATE INDEX idx_student_transport_bus ON student_transport(bus_id);

-- ============================================================
-- NOTICES / NOTICE BOARD
-- ============================================================

CREATE TABLE notices (
    id              UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
    title           VARCHAR(255) NOT NULL,
    content         TEXT NOT NULL,
    priority        notice_priority NOT NULL DEFAULT 'normal',
    audience        notice_audience NOT NULL DEFAULT 'all',
    target_class_id UUID REFERENCES classes(id) ON DELETE SET NULL,
    file_path       VARCHAR(500),
    file_name       VARCHAR(255),
    is_published    BOOLEAN NOT NULL DEFAULT TRUE,
    publish_date    TIMESTAMPTZ NOT NULL DEFAULT NOW(),
    expiry_date     TIMESTAMPTZ,
    created_by      UUID REFERENCES users(id) ON DELETE SET NULL,
    created_at      TIMESTAMPTZ NOT NULL DEFAULT NOW(),
    updated_at      TIMESTAMPTZ NOT NULL DEFAULT NOW()
);

CREATE INDEX idx_notices_published ON notices(is_published, publish_date);
CREATE INDEX idx_notices_audience ON notices(audience);
CREATE INDEX idx_notices_priority ON notices(priority);

-- ============================================================
-- SESSIONS (for PHP session tracking)
-- ============================================================

CREATE TABLE sessions (
    id              VARCHAR(128) PRIMARY KEY,
    user_id         UUID REFERENCES users(id) ON DELETE CASCADE,
    ip_address      INET,
    user_agent      TEXT,
    payload         TEXT,
    last_activity   TIMESTAMPTZ NOT NULL DEFAULT NOW()
);

CREATE INDEX idx_sessions_user ON sessions(user_id);
CREATE INDEX idx_sessions_activity ON sessions(last_activity);

-- ============================================================
-- CSRF TOKENS
-- ============================================================

CREATE TABLE csrf_tokens (
    id              UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
    user_id         UUID REFERENCES users(id) ON DELETE CASCADE,
    token           VARCHAR(255) NOT NULL UNIQUE,
    expires_at      TIMESTAMPTZ NOT NULL,
    created_at      TIMESTAMPTZ NOT NULL DEFAULT NOW()
);

CREATE INDEX idx_csrf_tokens_token ON csrf_tokens(token);
CREATE INDEX idx_csrf_tokens_expires ON csrf_tokens(expires_at);

-- ============================================================
-- SCHOOL SETTINGS / CONFIGURATION
-- ============================================================

CREATE TABLE school_settings (
    id              UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
    setting_key     VARCHAR(100) NOT NULL UNIQUE,
    setting_value   TEXT,
    setting_type    VARCHAR(20) DEFAULT 'string',
    description     TEXT,
    updated_at      TIMESTAMPTZ NOT NULL DEFAULT NOW()
);

CREATE INDEX idx_school_settings_key ON school_settings(setting_key);

-- Insert default settings
INSERT INTO school_settings (setting_key, setting_value, setting_type, description) VALUES
    ('school_name', 'My School', 'string', 'Name of the school'),
    ('school_address', '', 'string', 'School address'),
    ('school_phone', '', 'string', 'School phone number'),
    ('school_email', '', 'string', 'School email'),
    ('school_logo', '', 'string', 'Path to school logo'),
    ('academic_year', '2026-2027', 'string', 'Current academic year'),
    ('date_format', 'Y-m-d', 'string', 'Date display format'),
    ('time_format', 'H:i', 'string', 'Time display format'),
    ('default_language', 'en', 'string', 'Default language code'),
    ('theme', 'light', 'string', 'Default theme (light/dark)'),
    ('currency', 'USD', 'string', 'Currency code'),
    ('max_upload_size', '5242880', 'integer', 'Max file upload size in bytes');

-- ============================================================
-- SEED DATA: Default Admin User
-- ============================================================

-- Password: Admin@123 (hashed with bcrypt)
INSERT INTO users (username, email, password_hash, role, is_active, email_verified) VALUES
    ('admin', 'admin@school.com', '$2y$12$0Em.Vsk7CDOFr1/wRjA8PO2QbkbskU0WQ.IhFc2Mf/2SFg7HXpmGW', 'admin', TRUE, TRUE);

-- ============================================================
-- FUNCTIONS & TRIGGERS
-- ============================================================

-- Auto-update updated_at timestamp
CREATE OR REPLACE FUNCTION update_updated_at_column()
RETURNS TRIGGER AS $$
BEGIN
    NEW.updated_at = NOW();
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

-- Apply trigger to all tables with updated_at
DO $$
DECLARE
    t TEXT;
BEGIN
    FOR t IN
        SELECT table_name FROM information_schema.columns
        WHERE column_name = 'updated_at'
        AND table_schema = 'public'
    LOOP
        EXECUTE format('CREATE TRIGGER trg_%s_updated_at
            BEFORE UPDATE ON %I
            FOR EACH ROW
            EXECUTE FUNCTION update_updated_at_column()', t, t);
    END LOOP;
END;
$$;

-- ============================================================
-- VIEWS
-- ============================================================

-- Student full info view
CREATE VIEW v_student_info AS
SELECT
    s.id, s.student_id, s.first_name, s.last_name,
    s.date_of_birth, s.gender, s.phone, s.address,
    s.admission_date, s.roll_number, s.is_active,
    c.class_name, c.class_numeric,
    sec.section_name,
    u.username, u.email, u.last_login
FROM students s
LEFT JOIN classes c ON s.class_id = c.id
LEFT JOIN sections sec ON s.section_id = sec.id
LEFT JOIN users u ON s.user_id = u.id;

-- Teacher full info view
CREATE VIEW v_teacher_info AS
SELECT
    t.id, t.teacher_id, t.first_name, t.last_name,
    t.date_of_birth, t.gender, t.phone, t.address,
    t.qualification, t.specialization, t.department,
    t.joining_date, t.is_active,
    u.username, u.email, u.last_login
FROM teachers t
LEFT JOIN users u ON t.user_id = u.id;

-- Exam results with GPA calculation
CREATE VIEW v_exam_results AS
SELECT
    er.id, er.marks_obtained, er.grade, er.gpa, er.remarks,
    e.exam_name, e.exam_type, e.total_marks, e.passing_marks, e.exam_date,
    s.student_id, s.first_name || ' ' || s.last_name AS student_name,
    sub.subject_name, sub.subject_code,
    c.class_name, sec.section_name
FROM exam_results er
JOIN exams e ON er.exam_id = e.id
JOIN students s ON er.student_id = s.id
JOIN subjects sub ON e.subject_id = sub.id
JOIN classes c ON e.class_id = c.id
LEFT JOIN sections sec ON s.section_id = sec.id;

-- ============================================================
-- END OF SCHEMA
-- ============================================================
-- ============================================================
-- RATE LIMITING
-- ============================================================

CREATE TABLE IF NOT EXISTS rate_limits (
    id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
    identifier VARCHAR(255) NOT NULL,
    action VARCHAR(50) NOT NULL,
    attempts INTEGER NOT NULL DEFAULT 1,
    first_attempt TIMESTAMPTZ NOT NULL DEFAULT NOW(),
    last_attempt TIMESTAMPTZ NOT NULL DEFAULT NOW(),
    locked_until TIMESTAMPTZ,
    UNIQUE (identifier, action)
);

CREATE INDEX IF NOT EXISTS idx_rate_limits_identifier ON rate_limits(identifier, action);
CREATE INDEX IF NOT EXISTS idx_rate_limits_locked ON rate_limits(locked_until) WHERE locked_until IS NOT NULL;
