<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Course;
use App\Models\Enrollment;

class HomeController extends Controller
{
    public function index(): void
    {
        $courses = Course::all();
        $userId = \App\Core\Auth::id();

        $enrollments = [];
        if ($userId) {
            foreach ($courses as $course) {
                $enrollments[$course['id']] = Enrollment::find($userId, (int) $course['id']);
            }
        }

        $this->view('home.index', [
            'courses'     => $courses,
            'enrollments' => $enrollments,
        ]);
    }
}
