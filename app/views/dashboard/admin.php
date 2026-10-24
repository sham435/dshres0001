<div class="page-header">
    <h2><i class="bi bi-speedometer2"></i> <?= lang('dashboard') ?></h2>
    <span class="badge bg-primary"><?= lang('admin_panel') ?></span>
</div>

<div class="dashboard-grid">
    <div class="card stat-card">
        <div class="card-body">
            <h6 class="text-muted"><?= lang('total_students') ?></h6>
            <h2 class="mb-0"><?= $stats['total_students'] ?? 0 ?></h2>
            <i class="bi bi-people text-primary" style="font-size: 2rem; float: right; margin-top: -40px; opacity: 0.3;"></i>
        </div>
    </div>
    <div class="card stat-card success">
        <div class="card-body">
            <h6 class="text-muted"><?= lang('total_teachers') ?></h6>
            <h2 class="mb-0"><?= $stats['total_teachers'] ?? 0 ?></h2>
            <i class="bi bi-person-badge text-success" style="font-size: 2rem; float: right; margin-top: -40px; opacity: 0.3;"></i>
        </div>
    </div>
    <div class="card stat-card warning">
        <div class="card-body">
            <h6 class="text-muted"><?= lang('total_classes') ?></h6>
            <h2 class="mb-0"><?= $stats['total_classes'] ?? 0 ?></h2>
            <i class="bi bi-building text-warning" style="font-size: 2rem; float: right; margin-top: -40px; opacity: 0.3;"></i>
        </div>
    </div>
    <div class="card stat-card info">
        <div class="card-body">
            <h6 class="text-muted">Subjects</h6>
            <h2 class="mb-0"><?= $stats['total_subjects'] ?? 0 ?></h2>
            <i class="bi bi-book text-info" style="font-size: 2rem; float: right; margin-top: -40px; opacity: 0.3;"></i>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header"><h5 class="mb-0"><i class="bi bi-megaphone"></i> <?= lang('recent_notices') ?></h5></div>
            <div class="card-body">
                <?php if (empty($notices)): ?>
                    <div class="empty-state"><i class="bi bi-inbox"></i><p><?= lang('no_records') ?></p></div>
                <?php else: ?>
                    <?php foreach ($notices as $notice): ?>
                        <div class="border-bottom pb-3 mb-3">
                            <h6>
                                <?php if ($notice['priority'] === 'urgent'): ?>
                                    <span class="badge bg-danger">Urgent</span>
                                <?php elseif ($notice['priority'] === 'high'): ?>
                                    <span class="badge bg-warning text-dark">High</span>
                                <?php endif; ?>
                                <?= htmlspecialchars($notice['title']) ?>
                            </h6>
                            <p class="text-muted small mb-1"><?= htmlspecialchars(substr($notice['content'], 0, 150)) ?>...</p>
                            <small class="text-muted"><i class="bi bi-clock"></i> <?= format_date($notice['publish_date'], 'M d, Y H:i') ?></small>
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
                <a href="/students/create" class="btn btn-outline-primary"><i class="bi bi-person-plus"></i> Add Student</a>
                <a href="/teachers/create" class="btn btn-outline-success"><i class="bi bi-person-plus"></i> Add Teacher</a>
                <a href="/attendance" class="btn btn-outline-info"><i class="bi bi-calendar-check"></i> Mark Attendance</a>
                <a href="/notices/create" class="btn btn-outline-warning"><i class="bi bi-megaphone"></i> Create Notice</a>
                <a href="/exams/create" class="btn btn-outline-danger"><i class="bi bi-file-earmark-text"></i> Create Exam</a>
            </div>
        </div>
    </div>
</div>