<h2 class="mb-4"><?= $lesson ? 'Edit Lesson' : 'Create Lesson' ?> — <?= e($course['title']) ?></h2>

<div class="card shadow-sm">
    <div class="card-body">
        <form method="POST" action="<?= app_url($lesson ? 'admin/lessons/update' : 'admin/lessons/create') ?>">
            <?= csrf_field() ?>
            <input type="hidden" name="course_id" value="<?= (int) $course['id'] ?>">
            <?php if ($lesson): ?>
            <input type="hidden" name="id" value="<?= (int) $lesson['id'] ?>">
            <?php endif; ?>

            <div class="mb-3">
                <label class="form-label">Lesson Number</label>
                <select name="lesson_number" class="form-select" required>
                    <option value="0" <?= ($lesson['lesson_number'] ?? '') == '0' ? 'selected' : '' ?>>0 — Intro</option>
                    <?php for ($i = 1; $i <= 15; $i++): ?>
                    <option value="<?= $i ?>" <?= ($lesson['lesson_number'] ?? '') == $i ? 'selected' : '' ?>>Lesson <?= $i ?></option>
                    <?php endfor; ?>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Title</label>
                <input type="text" name="title" class="form-control" value="<?= e($lesson['title'] ?? '') ?>" required>
            </div>

            <button type="submit" class="btn btn-primary"><?= $lesson ? 'Update' : 'Create' ?> Lesson</button>
            <a href="<?= app_url('admin/lessons?course_id=' . $course['id']) ?>" class="btn btn-outline-secondary">Cancel</a>
        </form>
    </div>
</div>
