<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-list-ol me-2"></i>Lessons: <?= e($course['title']) ?></h2>
    <a href="<?= app_url('admin/lessons/create?course_id=' . $course['id']) ?>" class="btn btn-primary"><i class="fas fa-plus me-1"></i>Add Lesson</a>
</div>

<div class="card shadow-sm">
    <div class="table-responsive">
        <table class="table mb-0">
            <thead><tr><th>#</th><th>Title</th><th>Actions</th></tr></thead>
            <tbody>
            <?php foreach ($lessons as $lesson): ?>
            <tr>
                <td><?= e(lesson_label((int) $lesson['lesson_number'])) ?></td>
                <td><?= e($lesson['title']) ?></td>
                <td>
                    <a href="<?= app_url('admin/lessons/items?lesson_id=' . $lesson['id']) ?>" class="btn btn-sm btn-outline-primary">Videos & Quizzes</a>
                    <a href="<?= app_url('admin/lessons/edit?id=' . $lesson['id']) ?>" class="btn btn-sm btn-outline-secondary">Edit</a>
                    <form method="POST" action="<?= app_url('admin/lessons/delete') ?>" class="d-inline" onsubmit="return confirm('Delete this lesson?')">
                        <?= csrf_field() ?>
                        <input type="hidden" name="id" value="<?= (int) $lesson['id'] ?>">
                        <button class="btn btn-sm btn-outline-danger">Delete</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
            <?php if (empty($lessons)): ?>
            <tr><td colspan="3" class="text-center text-muted">No lessons yet. Add Intro (0) and Lessons 1-15.</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<a href="<?= app_url('admin/courses') ?>" class="btn btn-outline-secondary mt-3"><i class="fas fa-arrow-left me-1"></i>Back to Courses</a>
