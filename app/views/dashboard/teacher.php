<div class="page-header">
    <h2><i class="bi bi-speedometer2"></i> <?= lang('dashboard') ?></h2>
    <span class="badge bg-success"><?= lang('teacher_panel') ?></span>
</div>

<div class="dashboard-grid">
    <div class="card stat-card">
        <div class="card-body">
            <h6 class="text-muted"><?= lang('total_students') ?></h6>
            <h2 class="mb-0"><?= Database::fetchColumn("SELECT COUNT(*) FROM students WHERE is_active = TRUE") ?></h2>
        </div>
    </div>
    <div class="card stat-card success">
        <div class="card-body">
            <h6 class="text-muted">My Classes</h6>
            <h2 class="mb-0"><?= Database::fetchColumn("SELECT COUNT(DISTINCT class_id) FROM class_subjects WHERE teacher_id = (SELECT id FROM teachers WHERE user_id = :uid)", ['uid' => $_SESSION['user_id']]) ?></h2>
        </div>
    </div>
    <div class="card stat-card warning">
        <div class="card-body">
            <h6 class="text-muted">Pending Leaves</h6>
            <h2 class="mb-0"><?= Database::fetchColumn("SELECT COUNT(*) FROM leave_requests WHERE status = 'pending'") ?></h2>
        </div>
    </div>
    <div class="card stat-card info">
        <div class="card-body">
            <h6 class="text-muted">Notices</h6>
            <h2 class="mb-0"><?= Database::fetchColumn("SELECT COUNT(*) FROM notices WHERE is_published = TRUE") ?></h2>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header"><h5 class="mb-0"><i class="bi bi-megaphone"></i> <?= lang('recent_notices') ?></h5></div>
            <div class="card-body">
                <?php
                $notices = Database::fetchAll("SELECT * FROM notices WHERE is_published = TRUE ORDER BY publish_date DESC LIMIT 5");
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
            <div class="card-header"><h5 class="mb-0"><i class="bi bi-lightning"></i> Quick Actions</h5></div>
            <div class="card-body d-grid gap-2">
                <a href="/attendance" class="btn btn-outline-primary"><i class="bi bi-calendar-check"></i> Mark Attendance</a>
                <a href="/exams/create" class="btn btn-outline-success"><i class="bi bi-file-earmark-text"></i> Create Exam</a>
                <a href="/notices/create" class="btn btn-outline-warning"><i class="bi bi-megaphone"></i> Create Notice</a>
                <a href="/leaves/create" class="btn btn-outline-info"><i class="bi bi-calendar-plus"></i> Request Leave</a>
            </div>
        </div>
    </div>
</div>