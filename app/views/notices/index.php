<div class="page-header">
    <h2><i class="bi bi-megaphone"></i> <?= lang('notice_board') ?></h2>
    <?php if (has_role(['admin', 'teacher'])): ?>
        <a href="/notices/create" class="btn btn-primary"><i class="bi bi-plus-lg"></i> <?= lang('create') ?></a>
    <?php endif; ?>
</div>

<?php if (empty($notices)): ?>
    <div class="empty-state">
        <i class="bi bi-megaphone"></i>
        <p><?= lang('no_records') ?></p>
    </div>
<?php else: ?>
    <div class="row g-3">
        <?php foreach ($notices as $notice): ?>
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h6 class="mb-0"><?= htmlspecialchars($notice['title']) ?></h6>
                        <?php if ($notice['priority'] === 'urgent'): ?>
                            <span class="badge bg-danger">Urgent</span>
                        <?php elseif ($notice['priority'] === 'high'): ?>
                            <span class="badge bg-warning text-dark">High</span>
                        <?php elseif ($notice['priority'] === 'normal'): ?>
                            <span class="badge bg-info">Normal</span>
                        <?php else: ?>
                            <span class="badge bg-secondary">Low</span>
                        <?php endif; ?>
                    </div>
                    <div class="card-body">
                        <p><?= nl2br(htmlspecialchars(substr($notice['content'], 0, 200))) ?>...</p>
                        <small class="text-muted">
                            <i class="bi bi-person"></i> <?= htmlspecialchars($notice['author'] ?? 'System') ?> |
                            <i class="bi bi-clock"></i> <?= format_date($notice['publish_date'], 'M d, Y') ?>
                        </small>
                    </div>
                    <div class="card-footer">
                        <a href="/notices/<?= $notice['id'] ?>" class="btn btn-sm btn-outline-primary">Read More</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>