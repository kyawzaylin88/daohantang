<h2 class="mb-4"><i class="fas fa-tachometer-alt me-2"></i>Admin Dashboard</h2>

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card text-center shadow-sm">
            <div class="card-body">
                <i class="fas fa-users fa-2x text-primary mb-2"></i>
                <h3><?= $stats['users'] ?></h3>
                <p class="text-muted mb-0">Students</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-center shadow-sm">
            <div class="card-body">
                <i class="fas fa-book fa-2x text-success mb-2"></i>
                <h3><?= $stats['courses'] ?></h3>
                <p class="text-muted mb-0">Courses</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-center shadow-sm">
            <div class="card-body">
                <i class="fas fa-clock fa-2x text-warning mb-2"></i>
                <h3><?= $stats['pending'] ?></h3>
                <p class="text-muted mb-0">Pending Requests</p>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-6">
        <div class="card shadow-sm">
            <div class="card-header d-flex justify-content-between">
                <span><i class="fas fa-user-check me-2"></i>Access Requests</span>
                <a href="<?= app_url('admin/users') ?>" class="btn btn-sm btn-outline-primary">Manage All</a>
            </div>
            <div class="table-responsive">
                <table class="table table-sm mb-0">
                    <thead><tr><th>User</th><th>Course</th><th>Status</th></tr></thead>
                    <tbody>
                    <?php foreach (array_slice($enrollments, 0, 8) as $e): ?>
                    <tr>
                        <td><?= e($e['user_name']) ?></td>
                        <td><?= e($e['course_title']) ?></td>
                        <td><span class="badge bg-<?= $e['status'] === 'approved' ? 'success' : ($e['status'] === 'pending' ? 'warning text-dark' : 'danger') ?>"><?= e($e['status']) ?></span></td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if (empty($enrollments)): ?>
                    <tr><td colspan="3" class="text-muted text-center">No enrollments yet</td></tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card shadow-sm">
            <div class="card-header"><i class="fas fa-chart-line me-2"></i>Student Progress</div>
            <div class="table-responsive">
                <table class="table table-sm mb-0">
                    <thead><tr><th>Student</th><th>Course</th><th>Current</th></tr></thead>
                    <tbody>
                    <?php foreach ($progressData as $p): ?>
                    <tr>
                        <td><?= e($p['user_name']) ?></td>
                        <td><?= e($p['course_title']) ?></td>
                        <td><?= e($p['current']) ?></td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if (empty($progressData)): ?>
                    <tr><td colspan="3" class="text-muted text-center">No progress data yet</td></tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="mt-4">
    <a href="<?= app_url('admin/courses') ?>" class="btn btn-primary me-2"><i class="fas fa-book me-1"></i>Manage Courses</a>
    <a href="<?= app_url('admin/users') ?>" class="btn btn-outline-primary"><i class="fas fa-users me-1"></i>Manage Users</a>
</div>
