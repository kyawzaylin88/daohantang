<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Videos & Quizzes — <?= e(lesson_label((int) $lesson['lesson_number'])) ?>: <?= e($lesson['title']) ?></h2>
    <a href="<?= app_url('admin/items/create?lesson_id=' . $lesson['id']) ?>" class="btn btn-primary"><i class="fas fa-plus me-1"></i>Add Video/Quiz</a>
</div>

<div class="card shadow-sm">
    <div class="table-responsive">
        <table class="table mb-0">
            <thead><tr><th>Step</th><th>Video URL</th><th>Quiz Questions</th><th>Actions</th></tr></thead>
            <tbody>
            <?php foreach ($items as $item): ?>
            <tr>
                <td>Step <?= (int) $item['item_order'] ?></td>
                <td><small><?= e(mb_strimwidth($item['video_url'], 0, 60, '...')) ?></small></td>
                <td><?= (int) ($item['quiz_count'] ?? 0) ?></td>
                <td>
                    <a href="<?= app_url('admin/items/edit?id=' . $item['id']) ?>" class="btn btn-sm btn-outline-secondary">Edit</a>
                    <form method="POST" action="<?= app_url('admin/items/delete') ?>" class="d-inline" onsubmit="return confirm('Delete this item?')">
                        <?= csrf_field() ?>
                        <input type="hidden" name="id" value="<?= (int) $item['id'] ?>">
                        <button class="btn btn-sm btn-outline-danger">Delete</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
            <?php if (empty($items)): ?>
            <tr><td colspan="4" class="text-center text-muted">No video/quiz items. Add 2-5 items per lesson.</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<a href="<?= app_url('admin/lessons?course_id=' . $course['id']) ?>" class="btn btn-outline-secondary mt-3"><i class="fas fa-arrow-left me-1"></i>Back to Lessons</a>
