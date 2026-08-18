<div class="admin-page-header">
    <h2 class="admin-page-title">
        <i class="fas fa-book-open"></i><?= $course ? 'Edit Course' : 'Create Course' ?>
    </h2>
    <a href="<?= app_url('admin/courses') ?>" class="admin-btn admin-btn-outline">
        <i class="fas fa-arrow-left"></i> Back to Courses
    </a>
</div>

<div class="admin-card">
    <div class="admin-card-body">
        <form method="POST" action="<?= app_url($course ? 'admin/courses/update' : 'admin/courses/create') ?>">
            <?= csrf_field() ?>
            <?php if ($course): ?>
            <input type="hidden" name="id" value="<?= (int) $course['id'] ?>">
            <?php endif; ?>

            <div style="margin-bottom:16px;">
                <label class="admin-form-label">Title</label>
                <input type="text" name="title" class="form-control" value="<?= e($course['title'] ?? '') ?>" required>
            </div>
            <div style="margin-bottom:16px;">
                <label class="admin-form-label">Description</label>
                <textarea name="description" class="form-control" rows="4"><?= e($course['description'] ?? '') ?></textarea>
            </div>
            <div style="margin-bottom:16px;">
                <label class="admin-form-label">Price (Ks)</label>
                <input type="number" name="price" class="form-control" step="1" min="0" value="<?= e((string) ($course['price'] ?? '0')) ?>" required>
            </div>
            <div style="margin-bottom:20px;">
                <label class="admin-form-label">Thumbnail URL</label>
                <input type="text" name="thumbnail" class="form-control" value="<?= e($course['thumbnail'] ?? '') ?>" placeholder="assets/images/course.jpg">
            </div>

            <div style="display:flex;gap:10px;">
                <button type="submit" class="admin-btn admin-btn-primary">
                    <i class="fas fa-save"></i> <?= $course ? 'Update' : 'Create' ?> Course
                </button>
                <a href="<?= app_url('admin/courses') ?>" class="admin-btn admin-btn-outline">Cancel</a>
            </div>
        </form>
    </div>
</div>
