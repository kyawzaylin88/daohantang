<h2 class="mb-4"><?= $course ? 'Edit Course' : 'Create Course' ?></h2>

<div class="card shadow-sm">
    <div class="card-body">
        <form method="POST" action="<?= app_url($course ? 'admin/courses/update' : 'admin/courses/create') ?>">
            <?= csrf_field() ?>
            <?php if ($course): ?>
            <input type="hidden" name="id" value="<?= (int) $course['id'] ?>">
            <?php endif; ?>

            <div class="mb-3">
                <label class="form-label">Title</label>
                <input type="text" name="title" class="form-control" value="<?= e($course['title'] ?? '') ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control" rows="4"><?= e($course['description'] ?? '') ?></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Price (Ks)</label>
                <input type="number" name="price" class="form-control" step="1" min="0" value="<?= e((string) ($course['price'] ?? '0')) ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Thumbnail URL</label>
                <input type="text" name="thumbnail" class="form-control" value="<?= e($course['thumbnail'] ?? '') ?>" placeholder="assets/images/course.jpg">
            </div>

            <button type="submit" class="btn btn-primary"><?= $course ? 'Update' : 'Create' ?> Course</button>
            <a href="<?= app_url('admin/courses') ?>" class="btn btn-outline-secondary">Cancel</a>
        </form>
    </div>
</div>
