<div class="page-header">
    <h2><i class="bi bi-pencil"></i> Edit Teacher</h2>
    <a href="/teachers" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Back</a>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="/teachers/<?= $teacher['id'] ?>">
            <?= csrf_field() ?>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">First Name</label>
                    <input type="text" class="form-control" name="first_name" value="<?= htmlspecialchars($teacher['first_name']) ?>" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Last Name</label>
                    <input type="text" class="form-control" name="last_name" value="<?= htmlspecialchars($teacher['last_name']) ?>" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Department</label>
                    <input type="text" class="form-control" name="department" value="<?= htmlspecialchars($teacher['department'] ?? '') ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Qualification</label>
                    <input type="text" class="form-control" name="qualification" value="<?= htmlspecialchars($teacher['qualification'] ?? '') ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Phone</label>
                    <input type="text" class="form-control" name="phone" value="<?= htmlspecialchars($teacher['phone'] ?? '') ?>">
                </div>
                <div class="col-md-12">
                    <label class="form-label">Address</label>
                    <textarea class="form-control" name="address" rows="3"><?= htmlspecialchars($teacher['address'] ?? '') ?></textarea>
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg"></i> Update</button>
                <a href="/teachers" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>