<div class="admin-page-header">
    <h2 class="admin-page-title"><i class="fas fa-users"></i>Users & Course Access Management</h2>
</div>

<!-- Registered Students Table -->
<div class="admin-card">
    <div class="admin-card-header">
        <span><i class="fas fa-user-graduate" style="color:#2563eb;margin-right:8px;"></i>Registered Students</span>
        <span style="font-size:12px;color:#64748b;"><?= count($users) ?> registered</span>
    </div>
    <div style="overflow-x:auto;">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Registered Date</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($users as $user): ?>
            <tr>
                <td><span style="font-weight:600;color:#64748b;">#<?= (int) $user['id'] ?></span></td>
                <td style="font-weight:600;color:#0f172a;"><?= e($user['name']) ?></td>
                <td style="color:#64748b;"><?= e($user['email']) ?></td>
                <td style="color:#64748b;font-size:12px;"><?= e($user['created_at']) ?></td>
            </tr>
            <?php endforeach; ?>
            <?php if (empty($users)): ?>
            <tr>
                <td colspan="4">
                    <div class="admin-empty">
                        <i class="fas fa-users"></i> No students registered yet.
                    </div>
                </td>
            </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Course Access Requests Table -->
<div class="admin-card">
    <div class="admin-card-header">
        <span><i class="fas fa-key" style="color:#c41e3a;margin-right:8px;"></i>Course Access Requests</span>
    </div>
    <div style="overflow-x:auto;">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>User</th>
                    <th>Course</th>
                    <th>Status</th>
                    <th style="text-align:right;">Action</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($enrollments as $e): ?>
            <tr>
                <td>
                    <div style="font-weight:600;color:#0f172a;"><?= e($e['user_name']) ?></div>
                    <small style="color:#64748b;"><?= e($e['user_email']) ?></small>
                </td>
                <td style="font-weight:500;"><?= e($e['course_title']) ?></td>
                <td>
                    <span class="admin-badge <?= e($e['status']) ?>">
                        <?= e($e['status']) ?>
                    </span>
                </td>
                <td style="text-align:right;white-space:nowrap;">
                    <form method="POST" action="<?= app_url('admin/users/enrollment') ?>" style="display:inline;">
                        <?= csrf_field() ?>
                        <input type="hidden" name="enrollment_id" value="<?= (int) $e['id'] ?>">
                        <input type="hidden" name="status" value="approved">
                        <button class="admin-btn admin-btn-success admin-btn-sm" <?= $e['status'] === 'approved' ? 'disabled' : '' ?>>
                            <i class="fas fa-check"></i> Approve
                        </button>
                    </form>
                    <form method="POST" action="<?= app_url('admin/users/enrollment') ?>" style="display:inline;">
                        <?= csrf_field() ?>
                        <input type="hidden" name="enrollment_id" value="<?= (int) $e['id'] ?>">
                        <input type="hidden" name="status" value="declined">
                        <button class="admin-btn admin-btn-danger admin-btn-sm" <?= $e['status'] === 'declined' ? 'disabled' : '' ?>>
                            <i class="fas fa-times"></i> Decline
                        </button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
            <?php if (empty($enrollments)): ?>
            <tr>
                <td colspan="4">
                    <div class="admin-empty">
                        <i class="fas fa-inbox"></i> No enrollment requests found.
                    </div>
                </td>
            </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
