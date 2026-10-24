<div class="page-header">
    <h2><i class="bi bi-people"></i> <?= lang('students') ?></h2>
    <a href="/students/create" class="btn btn-primary"><i class="bi bi-plus-lg"></i> <?= lang('create') ?></a>
</div>

<div class="card mb-3">
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <input type="text" id="searchInput" class="form-control" placeholder="<?= lang('search') ?>...">
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <?php if (empty($students)): ?>
            <div class="empty-state">
                <i class="bi bi-people"></i>
                <p><?= lang('no_records') ?></p>
                <a href="/students/create" class="btn btn-primary"><?= lang('create') ?> <?= lang('students') ?></a>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th><?= lang('name') ?></th>
                            <th>Student ID</th>
                            <th><?= lang('class') ?></th>
                            <th><?= lang('section') ?></th>
                            <th><?= lang('phone') ?></th>
                            <th><?= lang('status') ?></th>
                            <th><?= lang('actions') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($students as $i => $student): ?>
                            <tr>
                                <td><?= $i + 1 ?></td>
                                <td>
                                    <a href="/students/<?= $student['id'] ?>">
                                        <?= htmlspecialchars($student['first_name'] . ' ' . $student['last_name']) ?>
                                    </a>
                                </td>
                                <td><span class="badge bg-secondary"><?= htmlspecialchars($student['student_id']) ?></span></td>
                                <td><?= htmlspecialchars($student['class_name'] ?? '-') ?></td>
                                <td><?= htmlspecialchars($student['section_name'] ?? '-') ?></td>
                                <td><?= htmlspecialchars($student['phone'] ?? '-') ?></td>
                                <td>
                                    <?php if ($student['is_active']): ?>
                                        <span class="badge bg-success">Active</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger">Inactive</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="/students/<?= $student['id'] ?>/edit" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
                                    <form method="POST" action="/students/<?= $student['id'] ?>/delete" style="display:inline;">
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