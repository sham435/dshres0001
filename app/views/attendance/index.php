<div class="page-header">
    <h2><i class="bi bi-calendar-check"></i> <?= lang('attendance') ?></h2>
</div>

<div class="card mb-3">
    <div class="card-body">
        <form method="GET" action="/attendance" class="row g-3">
            <div class="col-md-3">
                <label class="form-label"><?= lang('class') ?></label>
                <select class="form-select" name="class_id" onchange="this.form.submit()">
                    <option value="">Select Class</option>
                    <?php foreach ($classes as $c): ?>
                        <option value="<?= $c['id'] ?>" <?= $selectedClass === $c['id'] ? 'selected' : '' ?>><?= htmlspecialchars($c['class_name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label"><?= lang('section') ?></label>
                <select class="form-select" name="section_id" onchange="this.form.submit()">
                    <option value="">Select Section</option>
                    <?php foreach ($sections as $s): ?>
                        <option value="<?= $s['id'] ?>" <?= $selectedSection === $s['id'] ? 'selected' : '' ?>><?= htmlspecialchars($s['section_name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label"><?= lang('date') ?></label>
                <input type="date" class="form-control" name="date" value="<?= htmlspecialchars($date) ?>" onchange="this.form.submit()">
            </div>
        </form>
    </div>
</div>

<?php if (!empty($students)): ?>
    <form method="POST" action="/attendance">
        <?= csrf_field() ?>
        <input type="hidden" name="date" value="<?= htmlspecialchars($date) ?>">
        <input type="hidden" name="class_id" value="<?= htmlspecialchars($selectedClass) ?>">
        <input type="hidden" name="section_id" value="<?= htmlspecialchars($selectedSection) ?>">

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered attendance-matrix">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th><?= lang('name') ?></th>
                                <th>Roll</th>
                                <th class="text-center">Present</th>
                                <th class="text-center">Absent</th>
                                <th class="text-center">Late</th>
                                <th class="text-center">Excused</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($students as $i => $student): ?>
                                <tr>
                                    <td><?= $i + 1 ?></td>
                                    <td><?= htmlspecialchars($student['first_name'] . ' ' . $student['last_name']) ?></td>
                                    <td><?= htmlspecialchars($student['roll_number'] ?? '-') ?></td>
                                    <?php foreach (['present', 'absent', 'late', 'excused'] as $status): ?>
                                        <td class="text-center">
                                            <div class="form-check d-inline-block">
                                                <input class="form-check-input" type="radio"
                                                       name="attendance[<?= $student['id'] ?>]"
                                                       value="<?= $status ?>"
                                                       <?= ($student['status'] ?? 'present') === $status ? 'checked' : '' ?>>
                                            </div>
                                        </td>
                                    <?php endforeach; ?>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg"></i> Save Attendance</button>
                </div>
            </div>
        </div>
    </form>
<?php elseif ($selectedClass && $selectedSection): ?>
    <div class="empty-state">
        <i class="bi bi-people"></i>
        <p>No students found in this class/section.</p>
    </div>
<?php else: ?>
    <div class="empty-state">
        <i class="bi bi-calendar-check"></i>
        <p>Select a class and section to mark attendance.</p>
    </div>
<?php endif; ?>