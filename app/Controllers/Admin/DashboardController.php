<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Auth;
use App\Core\Controller;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Progress;
use App\Models\User;

class DashboardController extends Controller
{
    public function __construct()
    {
        Auth::requireAdmin();
    }

    public function index(): void
    {
        $stats = [
            'users'        => count(User::allStudents()),
            'courses'      => count(Course::all()),
            'pending'      => count(array_filter(
                Enrollment::allWithDetails(),
                fn($e) => $e['status'] === 'pending'
            )),
        ];

        $enrollments = Enrollment::allWithDetails();
        $progressData = $this->buildProgressReport();

        $this->view('admin.dashboard', [
            'stats'        => $stats,
            'enrollments'  => $enrollments,
            'progressData' => $progressData,
        ]);
    }

    private function buildProgressReport(): array
    {
        $rows = Progress::allStudentProgress();
        $report = [];

        foreach ($rows as $row) {
            if (empty($row['course_id'])) {
                continue;
            }

            $key = $row['user_id'] . '-' . $row['course_id'];
            if (!isset($report[$key])) {
                $report[$key] = [
                    'user_name'    => $row['name'],
                    'user_email'   => $row['email'],
                    'course_title' => $row['course_title'],
                    'current'      => 'Not started',
                ];
            }

            if ($row['lesson_number'] !== null) {
                if (!$row['is_lesson_completed']) {
                    $report[$key]['current'] = lesson_label((int) $row['lesson_number']);
                } elseif ((int) $row['lesson_number'] === 15) {
                    $report[$key]['current'] = 'Completed';
                }
            }
        }

        foreach ($report as &$entry) {
            if ($entry['current'] === 'Not started') {
                $userId = null;
                foreach ($rows as $r) {
                    if ($r['name'] === $entry['user_name'] && $r['course_title'] === $entry['course_title']) {
                        $userId = (int) $r['user_id'];
                        $courseId = (int) $r['course_id'];
                        break;
                    }
                }
                if ($userId) {
                    $current = Progress::getCurrentLessonForUser($userId, $courseId);
                    if ($current) {
                        $entry['current'] = lesson_label((int) $current['lesson_number']);
                    }
                }
            }
        }

        return array_values($report);
    }
}
