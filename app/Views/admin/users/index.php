<h2 class="mb-4"><i class="fas fa-users me-2"></i>User Management</h2>

<div class="card shadow-sm mb-4">
    <div class="card-header">Registered Students</div>
    <div class="table-responsive">
        <table class="table mb-0">
            <thead><tr><th>ID</th><th>Name</th><th>Email</th><th>Registered</th></tr></thead>
            <tbody>
            <?php foreach ($users as $user): ?>
            <tr>
                <td><?= (int) $user['id'] ?></td>
                <td><?= e($user['name']) ?></td>
                <td><?= e($user['email']) ?></td>
                <td><?= e($user['created_at']) ?></td>
            </tr>
            <?php endforeach; ?>
            <?php if (empty($users)): ?>
            <tr><td colspan="4" class="text-center text-muted">No students registered</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-header">Course Access Management</div>
    <div class="table-responsive">
        <table class="table mb-0">
            <thead><tr><th>User</th><th>Course</th><th>Status</th><th>Action</th></tr></thead>
            <tbody>
            <?php foreach ($enrollments as $e): ?>
            <tr>
                <td><?= e($e['user_name']) ?><br><small class="text-muted"><?= e($e['user_email']) ?></small></td>
                <td><?= e($e['course_title']) ?></td>
                <td><span class="badge bg-<?= $e['status'] === 'approved' ? 'success' : ($e['status'] === 'pending' ? 'warning text-dark' : 'danger') ?>"><?= e($e['status']) ?></span></td>
                <td>
                    <form method="POST" action="<?= app_url('admin/users/enrollment') ?>" class="d-inline">
                        <?= csrf_field() ?>
                        <input type="hidden" name="enrollment_id" value="<?= (int) $e['id'] ?>">
                        <input type="hidden" name="status" value="approved">
                        <button class="btn btn-sm btn-success" <?= $e['status'] === 'approved' ? 'disabled' : '' ?>>Approve</button>
                    </form>
                    <form method="POST" action="<?= app_url('admin/users/enrollment') ?>" class="d-inline">
                        <?= csrf_field() ?>
                        <input type="hidden" name="enrollment_id" value="<?= (int) $e['id'] ?>">
                        <input type="hidden" name="status" value="declined">
                        <button class="btn btn-sm btn-danger" <?= $e['status'] === 'declined' ? 'disabled' : '' ?>>Decline</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
            <?php if (empty($enrollments)): ?>
            <tr><td colspan="4" class="text-center text-muted">No enrollment requests</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<a href="<?= app_url('admin') ?>" class="btn btn-outline-secondary mt-3"><i class="fas fa-arrow-left me-1"></i>Back to Dashboard</a>
