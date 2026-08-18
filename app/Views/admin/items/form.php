<div class="admin-page-header">
    <h2 class="admin-page-title">
        <i class="fas fa-video"></i><?= $item ? 'Edit' : 'Create' ?> Video & Quiz — <?= e(lesson_label((int) $lesson['lesson_number'])) ?>
    </h2>
    <a href="<?= app_url('admin/lessons/items?lesson_id=' . $lesson['id']) ?>" class="admin-btn admin-btn-outline">
        <i class="fas fa-arrow-left"></i> Back to Items
    </a>
</div>

<div class="admin-card">
    <div class="admin-card-header">
        <span><i class="fas fa-play-circle" style="color:#2563eb;margin-right:8px;"></i>Step Video Configuration</span>
    </div>
    <div class="admin-card-body">
        <form method="POST" action="<?= app_url($item ? 'admin/items/update' : 'admin/items/create') ?>" id="itemForm">
            <?= csrf_field() ?>
            <input type="hidden" name="lesson_id" value="<?= (int) $lesson['id'] ?>">
            <?php if ($item): ?>
            <input type="hidden" name="id" value="<?= (int) $item['id'] ?>">
            <?php endif; ?>

            <div class="row mb-3">
                <div class="col-md-3">
                    <label class="admin-form-label">Step Order (1-5)</label>
                    <input type="number" name="item_order" class="form-control" min="1" max="5"
                           value="<?= e((string) ($item['item_order'] ?? '1')) ?>" required>
                </div>
                <div class="col-md-9">
                    <label class="admin-form-label">Video URL (YouTube or direct MP4)</label>
                    <input type="url" name="video_url" class="form-control"
                           value="<?= e($item['video_url'] ?? '') ?>" required
                           placeholder="https://www.youtube.com/watch?v=...">
                </div>
            </div>
        </form>
    </div>
</div>

<div class="admin-card">
    <div class="admin-card-header">
        <span><i class="fas fa-question-circle" style="color:#c41e3a;margin-right:8px;"></i>Quiz Questions (minimum 1)</span>
        <div style="display:flex;gap:8px;">
            <button type="button" class="admin-btn admin-btn-primary admin-btn-sm" id="toggleAiDrawerBtn" style="background:#7c3aed;border-color:#7c3aed;">
                <i class="fas fa-robot"></i> AI Quiz Generator
            </button>
            <button type="button" class="admin-btn admin-btn-outline-primary admin-btn-sm" id="addQuestionBtn">
                <i class="fas fa-plus"></i> Add Question
            </button>
        </div>
    </div>

    <!-- AI Generator Collapsible Panel -->
    <div id="aiDrawer" style="display:none;background:#f8fafc;border-bottom:1px solid #e2e8f0;padding:20px;">
        <div style="font-weight:700;color:#0f172a;margin-bottom:12px;display:flex;align-items:center;gap:8px;font-size:14px;">
            <i class="fas fa-robot" style="color:#7c3aed;"></i> AI Quiz Generator (OpenRouter)
        </div>
        <div class="row g-3">
            <div class="col-md-7">
                <label class="admin-form-label">Topic / Lesson Content Prompt</label>
                <input type="text" id="aiTopicInput" class="form-control"
                       value="<?= e($lesson['title']) ?> (<?= e($course['title']) ?>)"
                       placeholder="e.g. HSK 1 Greetings, Ni Hao, Xie Xie, basic vocabulary">
            </div>
            <div class="col-md-5">
                <label class="admin-form-label">Number of Questions</label>
                <select id="aiNumQuestions" class="form-select">
                    <option value="3" selected>3 Questions</option>
                    <option value="5">5 Questions</option>
                    <option value="8">8 Questions</option>
                </select>
            </div>
            <div class="col-12">
                <label class="admin-form-label">OpenRouter API Key <span style="color:#64748b;font-weight:400;">(Optional if set in server config)</span></label>
                <input type="password" id="aiApiKeyInput" class="form-control" placeholder="sk-or-v1-..." autocomplete="off">
            </div>
        </div>
        <div style="margin-top:16px;display:flex;gap:10px;align-items:center;flex-wrap:wrap;">
            <button type="button" class="admin-btn admin-btn-primary" id="startAiGenerateBtn" style="background:#7c3aed;border-color:#7c3aed;">
                <i class="fas fa-magic"></i> Generate Quizzes
            </button>
            <button type="button" class="admin-btn admin-btn-outline" id="closeAiDrawerBtn">Cancel</button>
            <span id="aiStatusText" style="font-size:13px;font-weight:500;color:#64748b;"></span>
        </div>
    </div>

    <div class="admin-card-body" id="questionsContainer">
        <?php
        $existingQuizzes = $quizzes ?? [];
        if (empty($existingQuizzes)) {
            $existingQuizzes = [['question' => '', 'option_a' => '', 'option_b' => '', 'option_c' => '', 'option_d' => '', 'correct_option' => 'a']];
        }
        foreach ($existingQuizzes as $qi => $q):
        ?>
        <div class="question-block border rounded p-3 mb-3" style="background:#fafafa;border-color:#e2e8f0 !important;">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <strong class="q-label" style="color:#0f172a;font-size:13px;">Question <?= $qi + 1 ?></strong>
                <button type="button" class="admin-btn admin-btn-outline-danger admin-btn-sm remove-question">&times; Remove</button>
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
                <label class="admin-form-label" style="font-size:11px;">Correct Answer</label>
                <select form="itemForm" name="correct_options[]" class="form-select form-select-sm" style="max-width:120px;">
                    <?php foreach (['a', 'b', 'c', 'd'] as $opt): ?>
                    <option value="<?= $opt ?>" <?= ($q['correct_option'] ?? 'a') === $opt ? 'selected' : '' ?>><?= strtoupper($opt) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <div class="admin-card-header" style="background:#fff;">
        <div style="display:flex;gap:10px;">
            <button type="submit" form="itemForm" class="admin-btn admin-btn-primary">
                <i class="fas fa-save"></i> <?= $item ? 'Update' : 'Create' ?> Item
            </button>
            <a href="<?= app_url('admin/lessons/items?lesson_id=' . $lesson['id']) ?>" class="admin-btn admin-btn-outline">Cancel</a>
        </div>
    </div>
</div>

<template id="questionTemplate">
    <div class="question-block border rounded p-3 mb-3" style="background:#fafafa;border-color:#e2e8f0 !important;">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <strong class="q-label" style="color:#0f172a;font-size:13px;">Question</strong>
            <button type="button" class="admin-btn admin-btn-outline-danger admin-btn-sm remove-question">&times; Remove</button>
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
            <label class="admin-form-label" style="font-size:11px;">Correct Answer</label>
            <select form="itemForm" name="correct_options[]" class="form-select form-select-sm" style="max-width:120px;">
                <option value="a">A</option><option value="b">B</option><option value="c">C</option><option value="d">D</option>
            </select>
        </div>
    </div>
</template>

<script>
// Load saved API Key from localStorage
const savedApiKey = localStorage.getItem('daohantang_openrouter_key') || '';
if (savedApiKey) {
    document.getElementById('aiApiKeyInput').value = savedApiKey;
}

// AI Drawer Toggle
const aiDrawer = document.getElementById('aiDrawer');
document.getElementById('toggleAiDrawerBtn').addEventListener('click', function() {
    aiDrawer.style.display = aiDrawer.style.display === 'none' ? 'block' : 'none';
});
document.getElementById('closeAiDrawerBtn').addEventListener('click', function() {
    aiDrawer.style.display = 'none';
});

// Manual Add Question
document.getElementById('addQuestionBtn').addEventListener('click', function() {
    addQuestionBlock();
});

// Remove Question
document.getElementById('questionsContainer').addEventListener('click', function(e) {
    if (e.target.classList.contains('remove-question')) {
        const blocks = document.querySelectorAll('.question-block');
        if (blocks.length > 1) {
            e.target.closest('.question-block').remove();
            renumberQuestions();
        }
    }
});

function addQuestionBlock(data = null) {
    const tpl = document.getElementById('questionTemplate');
    const clone = tpl.content.cloneNode(true);
    const block = clone.querySelector('.question-block');

    if (data) {
        block.querySelector('input[name="questions[]"]').value = data.question || '';
        block.querySelector('input[name="options_a[]"]').value = data.option_a || '';
        block.querySelector('input[name="options_b[]"]').value = data.option_b || '';
        block.querySelector('input[name="options_c[]"]').value = data.option_c || '';
        block.querySelector('input[name="options_d[]"]').value = data.option_d || '';
        if (data.correct_option) {
            block.querySelector('select[name="correct_options[]"]').value = data.correct_option;
        }
    }

    document.getElementById('questionsContainer').appendChild(clone);
    renumberQuestions();
}

function renumberQuestions() {
    document.querySelectorAll('.question-block .q-label').forEach(function(el, i) {
        el.textContent = 'Question ' + (i + 1);
    });
}

// AI Generation Logic
document.getElementById('startAiGenerateBtn').addEventListener('click', async function() {
    const topic = document.getElementById('aiTopicInput').value.trim();
    const numQuestions = document.getElementById('aiNumQuestions').value;
    const apiKey = document.getElementById('aiApiKeyInput').value.trim();
    const statusText = document.getElementById('aiStatusText');
    const generateBtn = this;

    if (!topic) {
        statusText.style.color = '#dc2626';
        statusText.textContent = 'Please enter a topic prompt.';
        return;
    }

    if (apiKey) {
        localStorage.setItem('daohantang_openrouter_key', apiKey);
    }

    generateBtn.disabled = true;
    generateBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Generating...';
    statusText.style.color = '#64748b';
    statusText.textContent = 'Requesting OpenRouter AI model...';

    const formData = new FormData();
    formData.append('csrf_token', document.querySelector('input[name="csrf_token"]').value);
    formData.append('topic', topic);
    formData.append('num_questions', numQuestions);
    formData.append('api_key', apiKey);

    try {
        const response = await fetch('<?= app_url('admin/ai/generate-quiz') ?>', {
            method: 'POST',
            body: formData
        });

        const data = await response.json();

        if (!response.ok || !data.success) {
            throw new Error(data.message || 'Failed to generate quizzes');
        }

        // Check if there is only one empty initial question block, remove it
        const currentBlocks = document.querySelectorAll('.question-block');
        if (currentBlocks.length === 1) {
            const firstQuestionInput = currentBlocks[0].querySelector('input[name="questions[]"]');
            if (firstQuestionInput && !firstQuestionInput.value.trim()) {
                currentBlocks[0].remove();
            }
        }

        // Append generated questions
        data.quizzes.forEach(q => addQuestionBlock(q));

        statusText.style.color = '#16a34a';
        statusText.textContent = '✅ Generated ' + data.quizzes.length + ' questions successfully!';
        aiDrawer.style.display = 'none';

    } catch (err) {
        statusText.style.color = '#dc2626';
        statusText.textContent = '❌ Error: ' + err.message;
    } finally {
        generateBtn.disabled = false;
        generateBtn.innerHTML = '<i class="fas fa-magic"></i> Generate Quizzes';
    }
});
</script>
