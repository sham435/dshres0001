<div class="page-header">
    <h2><i class="bi bi-person-plus"></i> <?= lang('create') ?> <?= lang('students') ?></h2>
    <a href="/students" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Back</a>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="/students" data-validate>
            <?= csrf_field() ?>

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label"><?= lang('first_name') ?> <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="first_name" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label"><?= lang('last_name') ?> <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="last_name" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label"><?= lang('email') ?> <span class="text-danger">*</span></label>
                    <input type="email" class="form-control" name="email" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label"><?= lang('phone') ?></label>
                    <input type="text" class="form-control" name="phone">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Date of Birth</label>
                    <input type="date" class="form-control" name="date_of_birth">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Gender</label>
                    <select class="form-select" name="gender">
                        <option value="">Select...</option>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                        <option value="other">Other</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label"><?= lang('class') ?></label>
                    <select class="form-select" name="class_id">
                        <option value="">Select...</option>
                        <?php foreach ($classes as $c): ?>
                            <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['class_name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label"><?= lang('section') ?></label>
                    <select class="form-select" name="section_id">
                        <option value="">Select...</option>
                        <?php foreach ($sections as $s): ?>
                            <option value="<?= $s['id'] ?>"><?= htmlspecialchars($s['section_name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-12">
                    <label class="form-label"><?= lang('address') ?></label>
                    <textarea class="form-control" name="address" rows="3"></textarea>
                </div>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg"></i> <?= lang('save') ?></button>
                <a href="/students" class="btn btn-outline-secondary"><?= lang('cancel') ?></a>
            </div>
        </form>
    </div>
</div>