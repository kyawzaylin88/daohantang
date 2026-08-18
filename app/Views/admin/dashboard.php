<?php
$pendingEnrollments = array_filter($enrollments, fn($e) => $e['status'] === 'pending');
?>

<!-- Stat Cards -->
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:16px;margin-bottom:28px;">

    <a href="<?= app_url('admin/users') ?>" class="admin-stat-card" style="text-decoration:none;">
        <div class="admin-stat-icon blue"><i class="fas fa-users"></i></div>
        <div>
            <div class="admin-stat-value"><?= (int) $stats['users'] ?></div>
            <div class="admin-stat-label">Total Students</div>
        </div>
    </a>

    <a href="<?= app_url('admin/courses') ?>" class="admin-stat-card" style="text-decoration:none;">
        <div class="admin-stat-icon red"><i class="fas fa-book-open"></i></div>
        <div>
            <div class="admin-stat-value"><?= (int) $stats['courses'] ?></div>
            <div class="admin-stat-label">Active Courses</div>
        </div>
    </a>

    <a href="<?= app_url('admin/users') ?>" class="admin-stat-card" style="text-decoration:none;">
        <div class="admin-stat-icon amber"><i class="fas fa-clock"></i></div>
        <div>
            <div class="admin-stat-value"><?= (int) $stats['pending'] ?></div>
            <div class="admin-stat-label">Pending Requests</div>
        </div>
    </a>

</div>

<!-- Two-column grid -->
<div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">

    <!-- Enrollment Requests -->
    <div class="admin-card">
        <div class="admin-card-header">
            <span><i class="fas fa-user-check" style="color:#c41e3a;margin-right:8px;"></i>Access Requests</span>
            <a href="<?= app_url('admin/users') ?>" class="admin-btn admin-btn-outline admin-btn-sm">Manage All</a>
        </div>
        <div style="overflow-x:auto;">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Student</th>
                        <th>Course</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach (array_slice($enrollments, 0, 8) as $e): ?>
                <tr>
                    <td><?= e($e['user_name']) ?></td>
                    <td style="color:#64748b;font-size:12px;"><?= e($e['course_title']) ?></td>
                    <td>
                        <span class="admin-badge <?= e($e['status']) ?>">
                            <?= e($e['status']) ?>
                        </span>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($enrollments)): ?>
                <tr><td colspan="3"><div class="admin-empty"><i class="fas fa-inbox"></i>No enrollment requests yet</div></td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Student Progress -->
    <div class="admin-card">
        <div class="admin-card-header">
            <span><i class="fas fa-chart-line" style="color:#2563eb;margin-right:8px;"></i>Student Progress</span>
        </div>
        <div style="overflow-x:auto;">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Student</th>
                        <th>Course</th>
                        <th>Current</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($progressData as $p): ?>
                <tr>
                    <td><?= e($p['user_name']) ?></td>
                    <td style="color:#64748b;font-size:12px;"><?= e($p['course_title']) ?></td>
                    <td>
                        <?php if ($p['current'] === 'Completed'): ?>
                            <span class="admin-badge approved"><i class="fas fa-check"></i> Completed</span>
                        <?php else: ?>
                            <span style="font-size:12px;color:#374151;"><?= e($p['current']) ?></span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($progressData)): ?>
                <tr><td colspan="3"><div class="admin-empty"><i class="fas fa-chart-bar"></i>No progress data yet</div></td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

<!-- Quick Actions -->
<div style="margin-top:20px;display:flex;gap:12px;flex-wrap:wrap;">
    <a href="<?= app_url('admin/courses') ?>" class="admin-btn admin-btn-primary">
        <i class="fas fa-book-open"></i> Manage Courses
    </a>
    <a href="<?= app_url('admin/users') ?>" class="admin-btn admin-btn-outline">
        <i class="fas fa-users"></i> Manage Users
    </a>
    <?php if ((int)$stats['pending'] > 0): ?>
    <a href="<?= app_url('admin/users') ?>" class="admin-btn" style="background:#fef9c3;color:#854d0e;border:1px solid #fde68a;">
        <i class="fas fa-bell"></i> <?= (int)$stats['pending'] ?> Pending Approval
    </a>
    <?php endif; ?>
</div>
