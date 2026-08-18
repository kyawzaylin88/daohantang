<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Auth;
use App\Core\Controller;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\LessonItem;
use App\Models\Quiz;

class LessonController extends Controller
{
    public function __construct()
    {
        Auth::requireAdmin();
    }

    public function index(): void
    {
        $courseId = (int) ($_GET['course_id'] ?? 0);
        $course = Course::find($courseId);

        if (!$course) {
            flash('error', 'Course not found.');
            redirect('admin/courses');
        }

        $lessons = Lesson::byCourse($courseId);
        $this->view('admin.lessons.index', [
            'course'  => $course,
            'lessons' => $lessons,
        ], 'admin');
    }

    public function createForm(): void
    {
        $courseId = (int) ($_GET['course_id'] ?? 0);
        $course = Course::find($courseId);

        if (!$course) {
            flash('error', 'Course not found.');
            redirect('admin/courses');
        }

        $this->view('admin.lessons.form', [
            'course' => $course,
            'lesson' => null,
        ], 'admin');
    }

    public function create(): void
    {
        if (!verify_csrf()) {
            flash('error', 'Invalid request.');
            redirect('admin/courses');
        }

        $data = $this->extractLessonData();
        if ($data === null) {
            redirect('admin/lessons/create?course_id=' . ($_POST['course_id'] ?? 0));
        }

        Lesson::create($data);
        flash('success', 'Lesson created.');
        redirect('admin/lessons?course_id=' . $data['course_id']);
    }

    public function editForm(): void
    {
        $id = (int) ($_GET['id'] ?? 0);
        $lesson = Lesson::find($id);

        if (!$lesson) {
            flash('error', 'Lesson not found.');
            redirect('admin/courses');
        }

        $course = Course::find((int) $lesson['course_id']);
        $this->view('admin.lessons.form', [
            'course' => $course,
            'lesson' => $lesson,
        ], 'admin');
    }

    public function update(): void
    {
        if (!verify_csrf()) {
            flash('error', 'Invalid request.');
            redirect('admin/courses');
        }

        $id = (int) ($_POST['id'] ?? 0);
        $lesson = Lesson::find($id);

        if (!$lesson) {
            flash('error', 'Lesson not found.');
            redirect('admin/courses');
        }

        $data = $this->extractLessonData();
        if ($data === null) {
            redirect('admin/lessons/edit?id=' . $id);
        }

        Lesson::update($id, $data);
        flash('success', 'Lesson updated.');
        redirect('admin/lessons?course_id=' . $data['course_id']);
    }

    public function delete(): void
    {
        if (!verify_csrf()) {
            flash('error', 'Invalid request.');
            redirect('admin/courses');
        }

        $id = (int) ($_POST['id'] ?? 0);
        $lesson = Lesson::find($id);
        $courseId = $lesson ? (int) $lesson['course_id'] : 0;

        Lesson::delete($id);
        flash('success', 'Lesson deleted.');
        redirect('admin/lessons?course_id=' . $courseId);
    }

    public function items(): void
    {
        $lessonId = (int) ($_GET['lesson_id'] ?? 0);
        $lesson = Lesson::find($lessonId);

        if (!$lesson) {
            flash('error', 'Lesson not found.');
            redirect('admin/courses');
        }

        $course = Course::find((int) $lesson['course_id']);
        $items = LessonItem::byLesson($lessonId);

        foreach ($items as &$item) {
            $item['quiz_count'] = count(Quiz::byLessonItem((int) $item['id']));
        }

        $this->view('admin.lessons.items', [
            'course' => $course,
            'lesson' => $lesson,
            'items'  => $items,
        ], 'admin');
    }

    private function extractLessonData(): ?array
    {
        $courseId = (int) ($_POST['course_id'] ?? 0);
        $title = trim($_POST['title'] ?? '');
        $lessonNumber = (int) ($_POST['lesson_number'] ?? -1);

        if ($title === '' || $courseId === 0) {
            flash('error', 'Title and course are required.');
            return null;
        }

        if ($lessonNumber < 0 || $lessonNumber > 15) {
            flash('error', 'Lesson number must be between 0 (Intro) and 15.');
            return null;
        }

        return [
            'course_id'     => $courseId,
            'title'         => $title,
            'lesson_number' => $lessonNumber,
        ];
    }
}
