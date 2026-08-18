<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-book me-2"></i>Course Management</h2>
    <a href="<?= app_url('admin/courses/create') ?>" class="btn btn-primary"><i class="fas fa-plus me-1"></i>Add Course</a>
</div>

<div class="card shadow-sm">
    <div class="table-responsive">
        <table class="table mb-0">
            <thead><tr><th>Title</th><th>Price</th><th>Description</th><th>Actions</th></tr></thead>
            <tbody>
            <?php foreach ($courses as $course): ?>
            <tr>
                <td><?= e($course['title']) ?></td>
                <td><?= format_price((float) $course['price']) ?></td>
                <td><?= e(mb_strimwidth($course['description'] ?? '', 0, 80, '...')) ?></td>
                <td>
                    <a href="<?= app_url('admin/lessons?course_id=' . $course['id']) ?>" class="btn btn-sm btn-outline-primary">Lessons</a>
                    <a href="<?= app_url('admin/courses/edit?id=' . $course['id']) ?>" class="btn btn-sm btn-outline-secondary">Edit</a>
                    <form method="POST" action="<?= app_url('admin/courses/delete') ?>" class="d-inline" onsubmit="return confirm('Delete this course?')">
                        <?= csrf_field() ?>
                        <input type="hidden" name="id" value="<?= (int) $course['id'] ?>">
                        <button class="btn btn-sm btn-outline-danger">Delete</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
            <?php if (empty($courses)): ?>
            <tr><td colspan="4" class="text-center text-muted">No courses yet</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<a href="<?= app_url('admin') ?>" class="btn btn-outline-secondary mt-3"><i class="fas fa-arrow-left me-1"></i>Back to Dashboard</a>
