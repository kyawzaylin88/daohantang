<?php $appConfig = require base_path('config/app.php'); ?>

<div class="hero-section text-center mb-5 p-5 rounded-3 bg-light">
    <h1 class="display-4 fw-bold text-primary">Welcome to DaoHanTang</h1>
    <p class="lead text-muted"><?= e($appConfig['tagline']) ?></p>
    <p class="text-secondary">Structured HSK courses with video lessons, interactive quizzes, and sequential learning paths.</p>
</div>

<h2 class="mb-4"><i class="fas fa-book-open me-2"></i>Available Courses</h2>

<?php if (empty($courses)): ?>
<div class="alert alert-info">No courses available yet. Check back soon!</div>
<?php else: ?>
<div class="row g-4">
    <?php foreach ($courses as $course): ?>
    <div class="col-md-6 col-lg-4">
        <div class="card course-card h-100 shadow-sm">
            <div class="course-thumb bg-gradient-primary d-flex align-items-center justify-content-center">
                <i class="fas fa-graduation-cap fa-4x text-white opacity-75"></i>
            </div>
            <div class="card-body d-flex flex-column">
                <h5 class="card-title"><?= e($course['title']) ?></h5>
                <p class="card-text text-muted flex-grow-1"><?= e(mb_strimwidth($course['description'] ?? '', 0, 120, '...')) ?></p>
                <div class="d-flex justify-content-between align-items-center mt-2">
                    <span class="h5 text-primary mb-0"><?= format_price((float) $course['price']) ?></span>
                    <?php
                    $enrollment = $enrollmfents[$course['id']] ?? null;
                    if ($currentUser && $currentUser['role'] !== 'admin'):
                        if ($enrollment && $enrollment['status'] === 'approved'): ?>
                            <a href="<?= app_url('course?id=' . $course['id']) ?>" class="btn btn-success btn-sm">
                                <i class="fas fa-play me-1"></i>Continue
                            </a>
                        <?php elseif ($enrollment && $enrollment['status'] === 'pending'): ?>
                            <span class="badge bg-warning text-dark">Pending Approval</span>
                        <?php else: ?>
                            <a href="<?= app_url('course?id=' . $course['id']) ?>" class="btn btn-outline-primary btn-sm">View Course</a>
                        <?php endif;
                    elseif ($currentUser && $currentUser['role'] === 'admin'): ?>
                        <a href="<?= app_url('admin/lessons?course_id=' . $course['id']) ?>" class="btn btn-outline-secondary btn-sm">Manage</a>
                    <?php else: ?>
                        <a href="<?= app_url('login') ?>" class="btn btn-primary btn-sm">Enroll</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>
