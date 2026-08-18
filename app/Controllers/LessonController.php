<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Lesson;
use App\Models\LessonItem;
use App\Models\Progress;
use App\Models\Quiz;

class LessonController extends Controller
{
    public function show(): void
    {
        Auth::requireLogin();

        $lessonId = (int) ($_GET['id'] ?? 0);
        $lesson = Lesson::find($lessonId);

        if (!$lesson) {
            flash('error', 'Lesson not found.');
            redirect('');
        }

        $course = Course::find((int) $lesson['course_id']);
        $userId = Auth::id();

        if (!Enrollment::isApproved($userId, (int) $lesson['course_id'])) {
            flash('error', 'You do not have access to this course yet.');
            redirect('course?id=' . $lesson['course_id']);
        }

        $allLessons = Lesson::byCourse((int) $lesson['course_id']);
        if (!Progress::isLessonUnlocked($userId, $lesson, $allLessons)) {
            flash('error', 'This lesson is locked. Complete the previous lesson first.');
            redirect('course?id=' . $lesson['course_id']);
        }

        $items = LessonItem::byLesson($lessonId);
        $step = max(1, min((int) ($_GET['step'] ?? 1), count($items) ?: 1));

        $itemStates = [];
        foreach ($items as $index => $item) {
            $order = $index + 1;
            $completed = Progress::isItemCompleted($userId, (int) $item['id']);
            $itemStates[$order] = [
                'completed' => $completed,
                'unlocked'  => $order === 1 || Progress::isItemCompleted($userId, (int) $items[$index - 1]['id']),
            ];
        }

        if (!empty($items) && !$itemStates[$step]['unlocked']) {
            $step = 1;
            foreach ($itemStates as $order => $state) {
                if ($state['unlocked'] && (!$state['completed'] || $order === count($items))) {
                    $step = $order;
                    break;
                }
            }
        }

        $currentItem = $items[$step - 1] ?? null;
        $quizzes = $currentItem ? Quiz::byLessonItem((int) $currentItem['id']) : [];

        $lessonStates = [];
        foreach ($allLessons as $l) {
            $lid = (int) $l['id'];
            $lessonStates[$lid] = [
                'completed' => Progress::isLessonCompleted($userId, $lid),
                'unlocked'  => Progress::isLessonUnlocked($userId, $l, $allLessons),
            ];
        }

        $this->view('lesson.show', [
            'course'       => $course,
            'lesson'       => $lesson,
            'lessons'      => $allLessons,
            'lessonStates' => $lessonStates,
            'items'        => $items,
            'itemStates'   => $itemStates,
            'step'         => $step,
            'currentItem'  => $currentItem,
            'quizzes'      => $quizzes,
            'totalSteps'   => count($items),
        ]);
    }

    public function submitQuiz(): void
    {
        Auth::requireLogin();

        if (!verify_csrf()) {
            $this->json(['success' => false, 'message' => 'Invalid request.'], 403);
        }

        $lessonItemId = (int) ($_POST['lesson_item_id'] ?? 0);
        $answers = $_POST['answers'] ?? [];

        $item = LessonItem::find($lessonItemId);
        if (!$item) {
            $this->json(['success' => false, 'message' => 'Lesson item not found.'], 404);
        }

        $lesson = Lesson::find((int) $item['lesson_id']);
        $userId = Auth::id();

        if (!Enrollment::isApproved($userId, (int) $lesson['course_id'])) {
            $this->json(['success' => false, 'message' => 'Access denied.'], 403);
        }

        $quizzes = Quiz::byLessonItem($lessonItemId);

        if (count($quizzes) < 1) {
            $this->json(['success' => false, 'message' => 'No quiz questions found.'], 400);
        }

        $correct = 0;
        foreach ($quizzes as $quiz) {
            $answer = strtolower(trim($answers[$quiz['id']] ?? ''));
            if ($answer === $quiz['correct_option']) {
                $correct++;
            }
        }

        $passed = $correct === count($quizzes);

        if (!$passed) {
            $this->json([
                'success' => false,
                'message' => "You got {$correct}/" . count($quizzes) . " correct. Please review the video and try again.",
                'correct' => $correct,
                'total'   => count($quizzes),
            ]);
        }

        Progress::markItemComplete($userId, $lessonItemId);

        $allItems = LessonItem::byLesson((int) $lesson['id']);
        $completedCount = 0;
        foreach ($allItems as $i) {
            if (Progress::isItemCompleted($userId, (int) $i['id'])) {
                $completedCount++;
            }
        }

        $isLessonComplete = $completedCount >= count($allItems);
        Progress::updateLessonProgress($userId, (int) $lesson['id'], $completedCount, $isLessonComplete);

        $nextStep = null;
        foreach ($allItems as $index => $i) {
            if (!Progress::isItemCompleted($userId, (int) $i['id'])) {
                $nextStep = $index + 1;
                break;
            }
        }

        $this->json([
            'success'         => true,
            'message'         => $isLessonComplete
                ? 'Congratulations! You completed this lesson.'
                : 'Quiz passed! Continue to the next video.',
            'lessonComplete'  => $isLessonComplete,
            'nextStep'        => $nextStep,
            'completedCount'  => $completedCount,
            'totalItems'      => count($allItems),
        ]);
    }
}
