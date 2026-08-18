<div class="admin-page-header">
    <h2 class="admin-page-title"><i class="fas fa-book-open"></i>Course Management</h2>
    <a href="<?= app_url('admin/courses/create') ?>" class="admin-btn admin-btn-primary">
        <i class="fas fa-plus"></i> Add Course
    </a>
</div>

<div class="admin-card">
    <div style="overflow-x:auto;">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Price</th>
                    <th>Description</th>
                    <th style="text-align:right;">Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($courses as $course): ?>
            <tr>
                <td style="font-weight:600;color:#0f172a;"><?= e($course['title']) ?></td>
                <td><span style="font-weight:600;color:#16a34a;"><?= format_price((float) $course['price']) ?></span></td>
                <td style="color:#64748b;max-width:300px;"><?= e(mb_strimwidth($course['description'] ?? '', 0, 80, '...')) ?></td>
                <td style="text-align:right;white-space:nowrap;">
                    <a href="<?= app_url('admin/lessons?course_id=' . $course['id']) ?>" class="admin-btn admin-btn-outline-primary admin-btn-sm">
                        <i class="fas fa-list-ol"></i> Lessons
                    </a>
                    <a href="<?= app_url('admin/courses/edit?id=' . $course['id']) ?>" class="admin-btn admin-btn-outline admin-btn-sm">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    <form method="POST" action="<?= app_url('admin/courses/delete') ?>" class="d-inline" style="display:inline;" onsubmit="return confirm('Delete this course?')">
                        <?= csrf_field() ?>
                        <input type="hidden" name="id" value="<?= (int) $course['id'] ?>">
                        <button class="admin-btn admin-btn-outline-danger admin-btn-sm">
                            <i class="fas fa-trash"></i> Delete
                        </button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
            <?php if (empty($courses)): ?>
            <tr>
                <td colspan="4">
                    <div class="admin-empty">
                        <i class="fas fa-book"></i> No courses found. Click "Add Course" to create one.
                    </div>
                </td>
            </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
