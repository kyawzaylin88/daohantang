<div class="admin-page-header">
    <h2 class="admin-page-title">
        <i class="fas fa-video"></i>Videos & Quizzes — <?= e(lesson_label((int) $lesson['lesson_number'])) ?>: <?= e($lesson['title']) ?>
    </h2>
    <div style="display:flex;gap:10px;">
        <a href="<?= app_url('admin/lessons?course_id=' . $course['id']) ?>" class="admin-btn admin-btn-outline">
            <i class="fas fa-arrow-left"></i> Back to Lessons
        </a>
        <a href="<?= app_url('admin/items/create?lesson_id=' . $lesson['id']) ?>" class="admin-btn admin-btn-primary">
            <i class="fas fa-plus"></i> Add Video/Quiz
        </a>
    </div>
</div>

<div class="admin-card">
    <div style="overflow-x:auto;">
        <table class="admin-table">
            <thead>
                <tr>
                    <th style="width:100px;">Step</th>
                    <th>Video URL</th>
                    <th style="width:140px;">Quiz Questions</th>
                    <th style="text-align:right;">Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($items as $item): ?>
            <tr>
                <td>
                    <span style="font-weight:700;color:#2563eb;background:rgba(37,99,235,0.1);padding:4px 10px;border-radius:6px;font-size:12px;">
                        Step <?= (int) $item['item_order'] ?>
                    </span>
                </td>
                <td style="color:#64748b;font-family:monospace;font-size:12px;"><?= e(mb_strimwidth($item['video_url'], 0, 60, '...')) ?></td>
                <td>
                    <span class="admin-badge approved">
                        <i class="fas fa-question-circle"></i> <?= (int) ($item['quiz_count'] ?? 0) ?> Qs
                    </span>
                </td>
                <td style="text-align:right;white-space:nowrap;">
                    <a href="<?= app_url('admin/items/edit?id=' . $item['id']) ?>" class="admin-btn admin-btn-outline admin-btn-sm">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    <form method="POST" action="<?= app_url('admin/items/delete') ?>" style="display:inline;" onsubmit="return confirm('Delete this item?')">
                        <?= csrf_field() ?>
                        <input type="hidden" name="id" value="<?= (int) $item['id'] ?>">
                        <button class="admin-btn admin-btn-outline-danger admin-btn-sm">
                            <i class="fas fa-trash"></i> Delete
                        </button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
            <?php if (empty($items)): ?>
            <tr>
                <td colspan="4">
                    <div class="admin-empty">
                        <i class="fas fa-video"></i> No video/quiz items. Add 2-5 items per lesson.
                    </div>
                </td>
            </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
