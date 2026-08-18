<div class="mb-3">
    <a href="<?= app_url('course?id=' . $course['id']) ?>" class="text-decoration-none">
        <i class="fas fa-arrow-left me-1"></i>Back to <?= e($course['title']) ?>
    </a>
</div>

<div class="row">
    <div class="col-lg-3 mb-4">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white py-2">
                <small class="fw-bold"><?= e($course['title']) ?></small>
            </div>
            <div class="list-group list-group-flush lesson-sidebar">
                <?php foreach ($lessons as $l):
                    $state = $lessonStates[(int) $l['id']];
                    $isCurrent = (int) $l['id'] === (int) $lesson['id'];
                ?>
                <div class="list-group-item d-flex justify-content-between align-items-center py-2 <?= $isCurrent ? 'active' : '' ?> <?= !$state['unlocked'] ? 'locked-lesson' : '' ?>">
                    <?php if ($state['unlocked']): ?>
                    <a href="<?= app_url('lesson?id=' . $l['id']) ?>"
                       class="text-decoration-none flex-grow-1 <?= $isCurrent ? 'text-white' : '' ?>">
                        <small><?= e(lesson_label((int) $l['lesson_number'])) ?></small>
                    </a>
                    <?php else: ?>
                    <span class="text-muted flex-grow-1"><small><?= e(lesson_label((int) $l['lesson_number'])) ?></small></span>
                    <?php endif; ?>

                    <?php if ($state['completed']): ?>
                        <i class="fas fa-check-circle <?= $isCurrent ? 'text-white' : 'text-success' ?>"></i>
                    <?php elseif (!$state['unlocked']): ?>
                        <i class="fas fa-lock text-secondary"></i>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <div class="col-lg-9">
        <div class="card shadow-sm mb-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="mb-0"><?= e(lesson_label((int) $lesson['lesson_number'])) ?>: <?= e($lesson['title']) ?></h4>
                <?php if ($totalSteps > 0): ?>
                <span class="badge bg-secondary">Step <?= $step ?> of <?= $totalSteps ?></span>
                <?php endif; ?>
            </div>

            <?php if ($currentItem): ?>
            <div class="card-body">
                <!-- Video Player -->
                <div class="video-container mb-4 rounded overflow-hidden bg-dark">
                    <?php if (str_contains($currentItem['video_url'], 'youtube.com') || str_contains($currentItem['video_url'], 'youtu.be')):
                        preg_match('/(?:youtube\.com\/(?:watch\?v=|embed\/)|youtu\.be\/)([\w-]+)/', $currentItem['video_url'], $m);
                        $videoId = $m[1] ?? '';
                    ?>
                    <div class="ratio ratio-16x9">
                        <iframe src="https://www.youtube.com/embed/<?= e($videoId) ?>" allowfullscreen></iframe>
                    </div>
                    <?php else: ?>
                    <div class="ratio ratio-16x9">
                        <video controls class="w-100" id="lessonVideo">
                            <source src="<?= e($currentItem['video_url']) ?>" type="video/mp4">
                            Your browser does not support video playback.
                        </video>
                    </div>
                    <?php endif; ?>
                </div>

                <!-- Quiz Section -->
                <div id="quizSection">
                    <h5 class="mb-3"><i class="fas fa-question-circle me-2"></i>Quiz (<?= count($quizzes) ?> questions — all must be correct)</h5>

                    <?php if (empty($quizzes)): ?>
                    <div class="alert alert-info">No quiz questions configured for this step yet.</div>
                    <?php else: ?>
                    <form id="quizForm">
                        <?= csrf_field() ?>
                        <input type="hidden" name="lesson_item_id" value="<?= (int) $currentItem['id'] ?>">

                        <?php foreach ($quizzes as $qi => $quiz): ?>
                        <div class="quiz-question mb-4 p-3 border rounded">
                            <p class="fw-semibold"><?= ($qi + 1) ?>. <?= e($quiz['question']) ?></p>
                            <?php foreach (['a', 'b', 'c', 'd'] as $opt): ?>
                            <div class="form-check">
                                <input class="form-check-input" type="radio"
                                       name="answers[<?= (int) $quiz['id'] ?>]"
                                       id="q<?= $quiz['id'] ?>_<?= $opt ?>"
                                       value="<?= $opt ?>" required>
                                <label class="form-check-label" for="q<?= $quiz['id'] ?>_<?= $opt ?>">
                                    <?= strtoupper($opt) ?>. <?= e($quiz['option_' . $opt]) ?>
                                </label>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        <?php endforeach; ?>

                        <div id="quizFeedback" class="alert d-none"></div>

                        <button type="submit" class="btn btn-primary" id="submitQuizBtn">
                            <i class="fas fa-paper-plane me-1"></i>Submit Quiz
                        </button>
                    </form>
                    <?php endif; ?>
                </div>

                <!-- Step Navigation -->
                <div class="d-flex justify-content-between mt-4 pt-3 border-top">
                    <?php if ($step > 1): ?>
                    <a href="<?= app_url('lesson?id=' . $lesson['id'] . '&step=' . ($step - 1)) ?>" class="btn btn-outline-secondary">
                        <i class="fas fa-chevron-left me-1"></i>Previous
                    </a>
                    <?php else: ?>
                    <span></span>
                    <?php endif; ?>

                    <?php
                    $currentCompleted = $itemStates[$step]['completed'] ?? false;
                    if ($currentCompleted && $step < $totalSteps): ?>
                    <a href="<?= app_url('lesson?id=' . $lesson['id'] . '&step=' . ($step + 1)) ?>" class="btn btn-success" id="nextStepBtn">
                        Next <i class="fas fa-chevron-right ms-1"></i>
                    </a>
                    <?php elseif ($currentCompleted && $step >= $totalSteps): ?>
                    <a href="<?= app_url('course?id=' . $course['id']) ?>" class="btn btn-success">
                        <i class="fas fa-flag-checkered me-1"></i>Back to Course
                    </a>
                    <?php else: ?>
                    <button class="btn btn-secondary" disabled id="nextStepBtn">
                        Next <i class="fas fa-chevron-right ms-1"></i>
                    </button>
                    <?php endif; ?>
                </div>
            </div>
            <?php else: ?>
            <div class="card-body">
                <div class="alert alert-info">No video content has been added to this lesson yet.</div>
            </div>
            <?php endif; ?>
        </div>

        <!-- Step progress indicators -->
        <?php if ($totalSteps > 1): ?>
        <div class="d-flex gap-2 justify-content-center">
            <?php for ($i = 1; $i <= $totalSteps; $i++):
                $st = $itemStates[$i] ?? ['completed' => false, 'unlocked' => false];
                $class = 'bg-secondary';
                if ($st['completed']) $class = 'bg-success';
                elseif ($i === $step) $class = 'bg-primary';
                elseif ($st['unlocked']) $class = 'bg-info';
            ?>
            <a href="<?= $st['unlocked'] ? app_url('lesson?id=' . $lesson['id'] . '&step=' . $i) : '#' ?>"
               class="step-dot rounded-circle d-inline-block <?= $class ?>"
               title="Step <?= $i ?>"></a>
            <?php endfor; ?>
        </div>
        <?php endif; ?>
    </div>
</div>

<script>
window.LESSON_CONFIG = {
    submitUrl: '<?= app_url('lesson/submit-quiz') ?>',
    lessonId: <?= (int) $lesson['id'] ?>,
    courseUrl: '<?= app_url('course?id=' . $course['id']) ?>',
    currentStep: <?= $step ?>,
    baseUrl: '<?= app_url('lesson?id=' . $lesson['id']) ?>'
};
</script>
