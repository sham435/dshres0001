<div class="page-header">
    <h2><i class="bi bi-speedometer2"></i> <?= lang('dashboard') ?></h2>
    <span class="badge bg-info"><?= lang('student_panel') ?></span>
</div>

<div class="dashboard-grid">
    <div class="card stat-card">
        <div class="card-body">
            <h6 class="text-muted">My Class</h6>
            <h2 class="mb-0">
                <?php
                $student = Database::fetchOne("SELECT s.*, c.class_name, sec.section_name FROM students s LEFT JOIN classes c ON s.class_id = c.id LEFT JOIN sections sec ON s.section_id = sec.id WHERE s.user_id = :uid", ['uid' => $_SESSION['user_id']]);
                echo htmlspecialchars($student['class_name'] ?? '-') . ' ' . htmlspecialchars($student['section_name'] ?? '');
                ?>
            </h2>
        </div>
    </div>
    <div class="card stat-card success">
        <div class="card-body">
            <h6 class="text-muted">Attendance</h6>
            <h2 class="mb-0">
                <?php
                $present = Database::fetchColumn("SELECT COUNT(*) FROM student_attendance WHERE student_id = :sid AND status = 'present'", ['sid' => $student['id'] ?? '']);
                $total = Database::fetchColumn("SELECT COUNT(*) FROM student_attendance WHERE student_id = :sid", ['sid' => $student['id'] ?? '']);
                echo $total > 0 ? round(($present / $total) * 100) . '%' : 'N/A';
                ?>
            </h2>
        </div>
    </div>
    <div class="card stat-card warning">
        <div class="card-body">
            <h6 class="text-muted">Latest GPA</h6>
            <h2 class="mb-0">
                <?php
                $gpa = Database::fetchColumn("SELECT AVG(gpa) FROM exam_results WHERE student_id = :sid", ['sid' => $student['id'] ?? '']);
                echo $gpa ? number_format((float)$gpa, 2) : 'N/A';
                ?>
            </h2>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header"><h5 class="mb-0"><i class="bi bi-megaphone"></i> <?= lang('recent_notices') ?></h5></div>
            <div class="card-body">
                <?php
                $notices = Database::fetchAll("SELECT * FROM notices WHERE is_published = TRUE AND (audience = 'all' OR audience = 'students') ORDER BY publish_date DESC LIMIT 5");
                if (empty($notices)): ?>
                    <div class="empty-state"><i class="bi bi-inbox"></i><p><?= lang('no_records') ?></p></div>
                <?php else: ?>
                    <?php foreach ($notices as $notice): ?>
                        <div class="border-bottom pb-3 mb-3">
                            <h6><?= htmlspecialchars($notice['title']) ?></h6>
                            <p class="text-muted small mb-1"><?= htmlspecialchars(substr($notice['content'], 0, 100)) ?>...</p>
                            <small class="text-muted"><i class="bi bi-clock"></i> <?= format_date($notice['publish_date'], 'M d, Y') ?></small>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-header"><h5 class="mb-0"><i class="bi bi-lightning"></i> Quick Links</h5></div>
            <div class="card-body d-grid gap-2">
                <a href="/attendance" class="btn btn-outline-primary"><i class="bi bi-calendar-check"></i> My Attendance</a>
                <a href="/exams" class="btn btn-outline-success"><i class="bi bi-file-earmark-text"></i> My Results</a>
                <a href="/timetable" class="btn btn-outline-info"><i class="bi bi-calendar-week"></i> My Timetable</a>
                <a href="/notices" class="btn btn-outline-warning"><i class="bi bi-megaphone"></i> Notices</a>
            </div>
        </div>
    </div>
</div>