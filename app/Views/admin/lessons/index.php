<div class="admin-page-header">
    <h2 class="admin-page-title">
        <i class="fas fa-list-ol"></i>Lessons: <?= e($course['title']) ?>
    </h2>
    <div style="display:flex;gap:10px;">
        <a href="<?= app_url('admin/courses') ?>" class="admin-btn admin-btn-outline">
            <i class="fas fa-arrow-left"></i> Back to Courses
        </a>
        <a href="<?= app_url('admin/lessons/create?course_id=' . $course['id']) ?>" class="admin-btn admin-btn-primary">
            <i class="fas fa-plus"></i> Add Lesson
        </a>
    </div>
</div>

<div class="admin-card">
    <div style="overflow-x:auto;">
        <table class="admin-table">
            <thead>
                <tr>
                    <th style="width:100px;">#</th>
                    <th>Title</th>
                    <th style="text-align:right;">Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($lessons as $lesson): ?>
            <tr>
                <td>
                    <span style="font-weight:700;color:var(--accent);background:var(--accent-soft);padding:4px 10px;border-radius:6px;font-size:12px;">
                        <?= e(lesson_label((int) $lesson['lesson_number'])) ?>
                    </span>
                </td>
                <td style="font-weight:600;color:#0f172a;"><?= e($lesson['title']) ?></td>
                <td style="text-align:right;white-space:nowrap;">
                    <a href="<?= app_url('admin/lessons/items?lesson_id=' . $lesson['id']) ?>" class="admin-btn admin-btn-outline-primary admin-btn-sm">
                        <i class="fas fa-video"></i> Videos & Quizzes
                    </a>
                    <a href="<?= app_url('admin/lessons/edit?id=' . $lesson['id']) ?>" class="admin-btn admin-btn-outline admin-btn-sm">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    <form method="POST" action="<?= app_url('admin/lessons/delete') ?>" style="display:inline;" onsubmit="return confirm('Delete this lesson?')">
                        <?= csrf_field() ?>
                        <input type="hidden" name="id" value="<?= (int) $lesson['id'] ?>">
                        <button class="admin-btn admin-btn-outline-danger admin-btn-sm">
                            <i class="fas fa-trash"></i> Delete
                        </button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
            <?php if (empty($lessons)): ?>
            <tr>
                <td colspan="3">
                    <div class="admin-empty">
                        <i class="fas fa-list-ol"></i> No lessons yet. Add Intro (0) and Lessons 1-15.
                    </div>
                </td>
            </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
