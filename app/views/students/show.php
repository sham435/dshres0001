<div class="page-header">
    <h2><i class="bi bi-person"></i> <?= htmlspecialchars($student['first_name'] . ' ' . $student['last_name']) ?></h2>
    <div>
        <a href="/students/<?= $student['id'] ?>/edit" class="btn btn-outline-primary"><i class="bi bi-pencil"></i> Edit</a>
        <a href="/students" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Back</a>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body text-center">
                <i class="bi bi-person-circle" style="font-size: 5rem; color: #0d6efd;"></i>
                <h5 class="mt-2"><?= htmlspecialchars($student['first_name'] . ' ' . $student['last_name']) ?></h5>
                <span class="badge bg-secondary"><?= htmlspecialchars($student['student_id']) ?></span>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card">
            <div class="card-header"><h5 class="mb-0">Student Details</h5></div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr><th>Email</th><td><?= htmlspecialchars($student['email'] ?? '-') ?></td></tr>
                    <tr><th>Username</th><td><?= htmlspecialchars($student['username'] ?? '-') ?></td></tr>
                    <tr><th>Date of Birth</th><td><?= format_date($student['date_of_birth'] ?? '') ?></td></tr>
                    <tr><th>Gender</th><td><?= htmlspecialchars(ucfirst($student['gender'] ?? '-')) ?></td></tr>
                    <tr><th>Phone</th><td><?= htmlspecialchars($student['phone'] ?? '-') ?></td></tr>
                    <tr><th>Address</th><td><?= htmlspecialchars($student['address'] ?? '-') ?></td></tr>
                    <tr><th>Class</th><td><?= htmlspecialchars($student['class_name'] ?? '-') ?></td></tr>
                    <tr><th>Section</th><td><?= htmlspecialchars($student['section_name'] ?? '-') ?></td></tr>
                    <tr><th>Roll Number</th><td><?= htmlspecialchars($student['roll_number'] ?? '-') ?></td></tr>
                    <tr><th>Admission Date</th><td><?= format_date($student['admission_date'] ?? '') ?></td></tr>
                </table>
            </div>
        </div>
    </div>
</div>