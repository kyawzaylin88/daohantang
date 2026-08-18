<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Lesson;
use App\Models\Progress;

class CourseController extends Controller
{
    public function show(): void
    {
        Auth::requireLogin();

        $courseId = (int) ($_GET['id'] ?? 0);
        $course = Course::find($courseId);

        if (!$course) {
            flash('error', 'Course not found.');
            redirect('');
        }

        $userId = Auth::id();
        $enrollment = Enrollment::find($userId, $courseId);
        $lessons = Lesson::byCourse($courseId);

        $lessonStates = [];
        $hasAccess = Enrollment::isApproved($userId, $courseId);

        foreach ($lessons as $lesson) {
            $lessonId = (int) $lesson['id'];
            $completed = Progress::isLessonCompleted($userId, $lessonId);
            $unlocked = $hasAccess && Progress::isLessonUnlocked($userId, $lesson, $lessons);

            $lessonStates[$lessonId] = [
                'completed' => $completed,
                'unlocked'  => $unlocked,
            ];
        }

        $this->view('course.show', [
            'course'       => $course,
            'lessons'      => $lessons,
            'lessonStates' => $lessonStates,
            'enrollment'   => $enrollment,
            'hasAccess'    => $hasAccess,
        ]);
    }

    public function requestAccess(): void
    {
        Auth::requireLogin();

        if (!verify_csrf()) {
            flash('error', 'Invalid request.');
            redirect('');
        }

        $courseId = (int) ($_POST['course_id'] ?? 0);
        $course = Course::find($courseId);

        if (!$course) {
            flash('error', 'Course not found.');
            redirect('');
        }

        Enrollment::request(Auth::id(), $courseId);
        flash('success', 'Access request submitted! Please send your payment screenshot via Telegram. An admin will review your request.');
        redirect('course?id=' . $courseId);
    }
}
