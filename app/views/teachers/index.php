<div class="page-header">
    <h2><i class="bi bi-person-badge"></i> Teachers</h2>
    <a href="/teachers/create" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Add Teacher</a>
</div>

<div class="card">
    <div class="card-body">
        <?php if (empty($teachers)): ?>
            <div class="empty-state"><i class="bi bi-person-badge"></i><p>No teachers found.</p></div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr><th>#</th><th>Name</th><th>ID</th><th>Department</th><th>Qualification</th><th>Status</th><th>Actions</th></tr>
                    </thead>
                    <tbody>
                        <?php foreach ($teachers as $i => $t): ?>
                            <tr>
                                <td><?= $i + 1 ?></td>
                                <td><a href="/teachers/<?= $t['id'] ?>"><?= htmlspecialchars($t['first_name'] . ' ' . $t['last_name']) ?></a></td>
                                <td><span class="badge bg-secondary"><?= htmlspecialchars($t['teacher_id']) ?></span></td>
                                <td><?= htmlspecialchars($t['department'] ?? '-') ?></td>
                                <td><?= htmlspecialchars($t['qualification'] ?? '-') ?></td>
                                <td><?= $t['is_active'] ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-danger">Inactive</span>' ?></td>
                                <td>
                                    <a href="/teachers/<?= $t['id'] ?>/edit" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
                                    <form method="POST" action="/teachers/<?= $t['id'] ?>/delete" style="display:inline;">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="btn btn-sm btn-outline-danger btn-delete"><i class="bi bi-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>