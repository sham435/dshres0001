<div class="page-header">
    <h2><i class="bi bi-megaphone"></i> Create Notice</h2>
    <a href="/notices" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Back</a>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="/notices">
            <?= csrf_field() ?>
            <div class="mb-3">
                <label class="form-label">Title <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="title" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Content <span class="text-danger">*</span></label>
                <textarea class="form-control" name="content" rows="6" required></textarea>
            </div>
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Priority</label>
                    <select class="form-select" name="priority">
                        <option value="low">Low</option>
                        <option value="normal" selected>Normal</option>
                        <option value="high">High</option>
                        <option value="urgent">Urgent</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Audience</label>
                    <select class="form-select" name="audience">
                        <option value="all" selected>All</option>
                        <option value="students">Students</option>
                        <option value="teachers">Teachers</option>
                        <option value="parents">Parents</option>
                        <option value="staff">Staff</option>
                    </select>
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg"></i> Publish</button>
                <a href="/notices" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>