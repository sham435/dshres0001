<div class="page-header">
    <h2><i class="bi bi-person-badge"></i> <?= htmlspecialchars($teacher['first_name'] . ' ' . $teacher['last_name']) ?></h2>
    <a href="/teachers" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Back</a>
</div>

<div class="card">
    <div class="card-body">
        <table class="table table-borderless">
            <tr><th>Teacher ID</th><td><?= htmlspecialchars($teacher['teacher_id']) ?></td></tr>
            <tr><th>Email</th><td><?= htmlspecialchars($teacher['email'] ?? '-') ?></td></tr>
            <tr><th>Department</th><td><?= htmlspecialchars($teacher['department'] ?? '-') ?></td></tr>
            <tr><th>Qualification</th><td><?= htmlspecialchars($teacher['qualification'] ?? '-') ?></td></tr>
            <tr><th>Specialization</th><td><?= htmlspecialchars($teacher['specialization'] ?? '-') ?></td></tr>
            <tr><th>Phone</th><td><?= htmlspecialchars($teacher['phone'] ?? '-') ?></td></tr>
            <tr><th>Address</th><td><?= htmlspecialchars($teacher['address'] ?? '-') ?></td></tr>
            <tr><th>Joining Date</th><td><?= format_date($teacher['joining_date'] ?? '') ?></td></tr>
            <tr><th>Status</th><td><?= $teacher['is_active'] ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-danger">Inactive</span>' ?></td></tr>
        </table>
    </div>
</div>