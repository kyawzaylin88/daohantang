<h2 class="mb-4"><?= $item ? 'Edit' : 'Create' ?> Video & Quiz — <?= e(lesson_label((int) $lesson['lesson_number'])) ?></h2>

<div class="card shadow-sm mb-4">
    <div class="card-body">
        <form method="POST" action="<?= app_url($item ? 'admin/items/update' : 'admin/items/create') ?>" id="itemForm">
            <?= csrf_field() ?>
            <input type="hidden" name="lesson_id" value="<?= (int) $lesson['id'] ?>">
            <?php if ($item): ?>
            <input type="hidden" name="id" value="<?= (int) $item['id'] ?>">
            <?php endif; ?>

            <div class="row mb-3">
                <div class="col-md-3">
                    <label class="form-label">Step Order (1-5)</label>
                    <input type="number" name="item_order" class="form-control" min="1" max="5"
                           value="<?= e((string) ($item['item_order'] ?? '1')) ?>" required>
                </div>
                <div class="col-md-9">
                    <label class="form-label">Video URL (YouTube or direct MP4)</label>
                    <input type="url" name="video_url" class="form-control"
                           value="<?= e($item['video_url'] ?? '') ?>" required
                           placeholder="https://www.youtube.com/watch?v=...">
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="fas fa-question-circle me-2"></i>Quiz Questions (minimum 1, recommend 5+)</span>
        <button type="button" class="btn btn-sm btn-outline-primary" id="addQuestionBtn"><i class="fas fa-plus me-1"></i>Add Question</button>
    </div>
    <div class="card-body" id="questionsContainer">
        <?php
        $existingQuizzes = $quizzes ?? [];
        if (empty($existingQuizzes)) {
            $existingQuizzes = [['question' => '', 'option_a' => '', 'option_b' => '', 'option_c' => '', 'option_d' => '', 'correct_option' => 'a']];
        }
        foreach ($existingQuizzes as $qi => $q):
        ?>
        <div class="question-block border rounded p-3 mb-3">
            <div class="d-flex justify-content-between mb-2">
                <strong>Question <?= $qi + 1 ?></strong>
                <button type="button" class="btn btn-sm btn-outline-danger remove-question">&times;</button>
            </div>
            <div class="mb-2">
                <input type="text" form="itemForm" name="questions[]" class="form-control" placeholder="Question text" value="<?= e($q['question']) ?>" required>
            </div>
            <div class="row g-2">
                <?php foreach (['a', 'b', 'c', 'd'] as $opt): ?>
                <div class="col-md-6">
                    <input type="text" form="itemForm" name="options_<?= $opt ?>[]" class="form-control form-control-sm"
                           placeholder="Option <?= strtoupper($opt) ?>" value="<?= e($q['option_' . $opt]) ?>" required>
                </div>
                <?php endforeach; ?>
            </div>
            <div class="mt-2">
                <label class="form-label small">Correct Answer</label>
                <select form="itemForm" name="correct_options[]" class="form-select form-select-sm">
                    <?php foreach (['a', 'b', 'c', 'd'] as $opt): ?>
                    <option value="<?= $opt ?>" <?= ($q['correct_option'] ?? 'a') === $opt ? 'selected' : '' ?>><?= strtoupper($opt) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <div class="card-footer">
        <button type="submit" form="itemForm" class="btn btn-primary"><?= $item ? 'Update' : 'Create' ?> Item</button>
        <a href="<?= app_url('admin/lessons/items?lesson_id=' . $lesson['id']) ?>" class="btn btn-outline-secondary">Cancel</a>
    </div>
</div>

<template id="questionTemplate">
    <div class="question-block border rounded p-3 mb-3">
        <div class="d-flex justify-content-between mb-2">
            <strong class="q-label">Question</strong>
            <button type="button" class="btn btn-sm btn-outline-danger remove-question">&times;</button>
        </div>
        <div class="mb-2">
            <input type="text" form="itemForm" name="questions[]" class="form-control" placeholder="Question text" required>
        </div>
        <div class="row g-2">
            <div class="col-md-6"><input type="text" form="itemForm" name="options_a[]" class="form-control form-control-sm" placeholder="Option A" required></div>
            <div class="col-md-6"><input type="text" form="itemForm" name="options_b[]" class="form-control form-control-sm" placeholder="Option B" required></div>
            <div class="col-md-6"><input type="text" form="itemForm" name="options_c[]" class="form-control form-control-sm" placeholder="Option C" required></div>
            <div class="col-md-6"><input type="text" form="itemForm" name="options_d[]" class="form-control form-control-sm" placeholder="Option D" required></div>
        </div>
        <div class="mt-2">
            <label class="form-label small">Correct Answer</label>
            <select form="itemForm" name="correct_options[]" class="form-select form-select-sm">
                <option value="a">A</option><option value="b">B</option><option value="c">C</option><option value="d">D</option>
            </select>
        </div>
    </div>
</template>

<script>
document.getElementById('addQuestionBtn').addEventListener('click', function() {
    const tpl = document.getElementById('questionTemplate');
    const clone = tpl.content.cloneNode(true);
    document.getElementById('questionsContainer').appendChild(clone);
    renumberQuestions();
});
document.getElementById('questionsContainer').addEventListener('click', function(e) {
    if (e.target.classList.contains('remove-question')) {
        const blocks = document.querySelectorAll('.question-block');
        if (blocks.length > 1) {
            e.target.closest('.question-block').remove();
            renumberQuestions();
        }
    }
});
function renumberQuestions() {
    document.querySelectorAll('.question-block .q-label, .question-block strong').forEach(function(el, i) {
        if (el.textContent.startsWith('Question')) el.textContent = 'Question ' + (i + 1);
    });
}
</script>
