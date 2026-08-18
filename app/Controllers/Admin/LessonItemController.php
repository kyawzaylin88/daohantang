<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Auth;
use App\Core\Controller;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\LessonItem;
use App\Models\Quiz;

class LessonItemController extends Controller
{
    public function __construct()
    {
        Auth::requireAdmin();
    }

    public function createForm(): void
    {
        $lessonId = (int) ($_GET['lesson_id'] ?? 0);
        $lesson = Lesson::find($lessonId);

        if (!$lesson) {
            flash('error', 'Lesson not found.');
            redirect('admin/courses');
        }

        $course = Course::find((int) $lesson['course_id']);
        $this->view('admin.items.form', [
            'course' => $course,
            'lesson' => $lesson,
            'item'   => null,
        ]);
    }

    public function create(): void
    {
        if (!verify_csrf()) {
            flash('error', 'Invalid request.');
            redirect('admin/courses');
        }

        $data = $this->extractItemData();
        if ($data === null) {
            redirect('admin/items/create?lesson_id=' . ($_POST['lesson_id'] ?? 0));
        }

        $itemId = LessonItem::create($data);
        $this->saveQuizzes($itemId);

        flash('success', 'Video & quiz item created.');
        redirect('admin/lessons/items?lesson_id=' . $data['lesson_id']);
    }

    public function editForm(): void
    {
        $id = (int) ($_GET['id'] ?? 0);
        $item = LessonItem::find($id);

        if (!$item) {
            flash('error', 'Item not found.');
            redirect('admin/courses');
        }

        $lesson = Lesson::find((int) $item['lesson_id']);
        $course = Course::find((int) $lesson['course_id']);
        $quizzes = Quiz::byLessonItem($id);

        $this->view('admin.items.form', [
            'course'  => $course,
            'lesson'  => $lesson,
            'item'    => $item,
            'quizzes' => $quizzes,
        ]);
    }

    public function update(): void
    {
        if (!verify_csrf()) {
            flash('error', 'Invalid request.');
            redirect('admin/courses');
        }

        $id = (int) ($_POST['id'] ?? 0);
        $item = LessonItem::find($id);

        if (!$item) {
            flash('error', 'Item not found.');
            redirect('admin/courses');
        }

        $data = $this->extractItemData();
        if ($data === null) {
            redirect('admin/items/edit?id=' . $id);
        }

        LessonItem::update($id, $data);
        Quiz::deleteByLessonItem($id);
        $this->saveQuizzes($id);

        flash('success', 'Video & quiz item updated.');
        redirect('admin/lessons/items?lesson_id=' . $data['lesson_id']);
    }

    public function delete(): void
    {
        if (!verify_csrf()) {
            flash('error', 'Invalid request.');
            redirect('admin/courses');
        }

        $id = (int) ($_POST['id'] ?? 0);
        $item = LessonItem::find($id);
        $lessonId = $item ? (int) $item['lesson_id'] : 0;

        LessonItem::delete($id);
        flash('success', 'Item deleted.');
        redirect('admin/lessons/items?lesson_id=' . $lessonId);
    }

    private function extractItemData(): ?array
    {
        $lessonId = (int) ($_POST['lesson_id'] ?? 0);
        $itemOrder = (int) ($_POST['item_order'] ?? 1);
        $videoUrl = trim($_POST['video_url'] ?? '');

        if ($lessonId === 0 || $videoUrl === '') {
            flash('error', 'Lesson and video URL are required.');
            return null;
        }

        if ($itemOrder < 1 || $itemOrder > 5) {
            flash('error', 'Item order must be between 1 and 5.');
            return null;
        }

        return [
            'lesson_id'  => $lessonId,
            'item_order' => $itemOrder,
            'video_url'  => $videoUrl,
        ];
    }

    private function saveQuizzes(int $itemId): void
    {
        $questions = $_POST['questions'] ?? [];
        $optionsA = $_POST['options_a'] ?? [];
        $optionsB = $_POST['options_b'] ?? [];
        $optionsC = $_POST['options_c'] ?? [];
        $optionsD = $_POST['options_d'] ?? [];
        $corrects = $_POST['correct_options'] ?? [];

        foreach ($questions as $i => $question) {
            $question = trim($question);
            if ($question === '') {
                continue;
            }

            Quiz::create([
                'lesson_item_id' => $itemId,
                'question'       => $question,
                'option_a'       => trim($optionsA[$i] ?? ''),
                'option_b'       => trim($optionsB[$i] ?? ''),
                'option_c'       => trim($optionsC[$i] ?? ''),
                'option_d'       => trim($optionsD[$i] ?? ''),
                'correct_option' => strtolower(trim($corrects[$i] ?? 'a')),
            ]);
        }
    }
}
