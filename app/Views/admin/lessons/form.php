<div class="admin-page-header">
    <h2 class="admin-page-title">
        <i class="fas fa-list-ol"></i><?= $lesson ? 'Edit Lesson' : 'Create Lesson' ?> — <?= e($course['title']) ?>
    </h2>
    <a href="<?= app_url('admin/lessons?course_id=' . $course['id']) ?>" class="admin-btn admin-btn-outline">
        <i class="fas fa-arrow-left"></i> Back to Lessons
    </a>
</div>

<div class="admin-card">
    <div class="admin-card-body">
        <form method="POST" action="<?= app_url($lesson ? 'admin/lessons/update' : 'admin/lessons/create') ?>">
            <?= csrf_field() ?>
            <input type="hidden" name="course_id" value="<?= (int) $course['id'] ?>">
            <?php if ($lesson): ?>
            <input type="hidden" name="id" value="<?= (int) $lesson['id'] ?>">
            <?php endif; ?>

            <div style="margin-bottom:16px;">
                <label class="admin-form-label">Lesson Number</label>
                <select name="lesson_number" class="form-select" required>
                    <option value="0" <?= ($lesson['lesson_number'] ?? '') == '0' ? 'selected' : '' ?>>0 — Intro</option>
                    <?php for ($i = 1; $i <= 15; $i++): ?>
                    <option value="<?= $i ?>" <?= ($lesson['lesson_number'] ?? '') == $i ? 'selected' : '' ?>>Lesson <?= $i ?></option>
                    <?php endfor; ?>
                </select>
            </div>
            <div style="margin-bottom:20px;">
                <label class="admin-form-label">Title</label>
                <input type="text" name="title" class="form-control" value="<?= e($lesson['title'] ?? '') ?>" required>
            </div>

            <div style="display:flex;gap:10px;">
                <button type="submit" class="admin-btn admin-btn-primary">
                    <i class="fas fa-save"></i> <?= $lesson ? 'Update' : 'Create' ?> Lesson
                </button>
                <a href="<?= app_url('admin/lessons?course_id=' . $course['id']) ?>" class="admin-btn admin-btn-outline">Cancel</a>
            </div>
        </form>
    </div>
</div>
