<?php $appConfig = require base_path('config/app.php'); ?>

<div class="mb-3">
    <a href="<?= app_url('') ?>" class="text-decoration-none"><i class="fas fa-arrow-left me-1"></i>Back to Courses</a>
</div>

<div class="row">
    <div class="col-lg-4 mb-4">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-list me-2"></i>Course Content</h5>
            </div>
            <div class="list-group list-group-flush lesson-sidebar">
                <?php foreach ($lessons as $lesson):
                    $state = $lessonStates[(int) $lesson['id']];
                    $isActive = false;
                ?>
                <div class="list-group-item d-flex justify-content-between align-items-center <?= !$state['unlocked'] ? 'locked-lesson' : '' ?>">
                    <?php if ($state['unlocked'] && $hasAccess): ?>
                    <a href="<?= app_url('lesson?id=' . $lesson['id']) ?>" class="text-decoration-none flex-grow-1">
                        <?= e(lesson_label((int) $lesson['lesson_number'])) ?>: <?= e($lesson['title']) ?>
                    </a>
                    <?php else: ?>
                    <span class="text-muted flex-grow-1">
                        <?= e(lesson_label((int) $lesson['lesson_number'])) ?>: <?= e($lesson['title']) ?>
                    </span>
                    <?php endif; ?>

                    <?php if ($state['completed']): ?>
                        <i class="fas fa-check-circle text-success"></i>
                    <?php elseif (!$state['unlocked'] || !$hasAccess): ?>
                        <i class="fas fa-lock text-secondary"></i>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card shadow-sm">
            <div class="card-body">
                <h2><?= e($course['title']) ?></h2>
                <p class="text-muted"><?= e($course['description']) ?></p>
                <p class="h4 text-primary"><?= format_price((float) $course['price']) ?></p>

                <?php if (!$hasAccess): ?>
                <div class="alert alert-warning">
                    <h5><i class="fas fa-info-circle me-2"></i>Access Required</h5>
                    <p>To access this course, please pay via Telegram (<strong><?= e($appConfig['telegram']) ?></strong>) and send your payment screenshot to the admin.</p>
                    <?php if ($enrollment && $enrollment['status'] === 'pending'): ?>
                        <p class="mb-0"><span class="badge bg-warning text-dark">Your access request is pending admin approval.</span></p>
                    <?php elseif ($enrollment && $enrollment['status'] === 'declined'): ?>
                        <form method="POST" action="<?= app_url('course/request-access') ?>" class="mt-2">
                            <?= csrf_field() ?>
                            <input type="hidden" name="course_id" value="<?= (int) $course['id'] ?>">
                            <button type="submit" class="btn btn-primary">Re-request Access</button>
                        </form>
                    <?php else: ?>
                        <form method="POST" action="<?= app_url('course/request-access') ?>" class="mt-2">
                            <?= csrf_field() ?>
                            <input type="hidden" name="course_id" value="<?= (int) $course['id'] ?>">
                            <button type="submit" class="btn btn-primary">Request Course Access</button>
                        </form>
                    <?php endif; ?>
                </div>
                <?php else: ?>
                <div class="alert alert-success">
                    <i class="fas fa-check me-2"></i>You have access to this course. Select a lesson from the sidebar to begin.
                </div>
                <?php
                $firstUnlocked = null;
                foreach ($lessons as $l) {
                    if ($lessonStates[(int) $l['id']]['unlocked'] && !$lessonStates[(int) $l['id']]['completed']) {
                        $firstUnlocked = $l;
                        break;
                    }
                }
                if (!$firstUnlocked) {
                    foreach ($lessons as $l) {
                        if ($lessonStates[(int) $l['id']]['unlocked']) {
                            $firstUnlocked = $l;
                            break;
                        }
                    }
                }
                if ($firstUnlocked): ?>
                <a href="<?= app_url('lesson?id=' . $firstUnlocked['id']) ?>" class="btn btn-success btn-lg">
                    <i class="fas fa-play me-2"></i>
                    <?= $lessonStates[(int) $firstUnlocked['id']]['completed'] ? 'Review Lessons' : 'Start Learning' ?>
                </a>
                <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
