<div class="page-header">
    <h2><i class="bi bi-pencil"></i> Edit Student</h2>
    <a href="/students" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Back</a>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="/students/<?= $student['id'] ?>">
            <?= csrf_field() ?>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">First Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="first_name" value="<?= htmlspecialchars($student['first_name']) ?>" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Last Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="last_name" value="<?= htmlspecialchars($student['last_name']) ?>" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Date of Birth</label>
                    <input type="date" class="form-control" name="date_of_birth" value="<?= htmlspecialchars($student['date_of_birth'] ?? '') ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Gender</label>
                    <select class="form-select" name="gender">
                        <option value="">Select...</option>
                        <option value="male" <?= ($student['gender'] ?? '') === 'male' ? 'selected' : '' ?>>Male</option>
                        <option value="female" <?= ($student['gender'] ?? '') === 'female' ? 'selected' : '' ?>>Female</option>
                        <option value="other" <?= ($student['gender'] ?? '') === 'other' ? 'selected' : '' ?>>Other</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Phone</label>
                    <input type="text" class="form-control" name="phone" value="<?= htmlspecialchars($student['phone'] ?? '') ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Class</label>
                    <select class="form-select" name="class_id">
                        <option value="">Select...</option>
                        <?php foreach ($classes as $c): ?>
                            <option value="<?= $c['id'] ?>" <?= ($student['class_id'] ?? '') === $c['id'] ? 'selected' : '' ?>><?= htmlspecialchars($c['class_name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Section</label>
                    <select class="form-select" name="section_id">
                        <option value="">Select...</option>
                        <?php foreach ($sections as $s): ?>
                            <option value="<?= $s['id'] ?>" <?= ($student['section_id'] ?? '') === $s['id'] ? 'selected' : '' ?>><?= htmlspecialchars($s['section_name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-12">
                    <label class="form-label">Address</label>
                    <textarea class="form-control" name="address" rows="3"><?= htmlspecialchars($student['address'] ?? '') ?></textarea>
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg"></i> Update</button>
                <a href="/students" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>