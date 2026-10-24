<div class="page-header">
    <h2><i class="bi bi-megaphone"></i> <?= htmlspecialchars($notice['title']) ?></h2>
    <a href="/notices" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Back</a>
</div>

<div class="card">
    <div class="card-body">
        <div class="mb-3">
            <?php if ($notice['priority'] === 'urgent'): ?>
                <span class="badge bg-danger">Urgent</span>
            <?php elseif ($notice['priority'] === 'high'): ?>
                <span class="badge bg-warning text-dark">High</span>
            <?php endif; ?>
            <span class="badge bg-info"><?= ucfirst($notice['audience']) ?></span>
        </div>
        <p><?= nl2br(htmlspecialchars($notice['content'])) ?></p>
        <hr>
        <small class="text-muted">
            <i class="bi bi-person"></i> <?= htmlspecialchars($notice['author'] ?? 'System') ?> |
            <i class="bi bi-clock"></i> <?= format_date($notice['publish_date'], 'M d, Y H:i') ?>
        </small>
    </div>
</div>