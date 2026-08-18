<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Auth;
use App\Core\Controller;
use App\Models\Course;

class CourseController extends Controller
{
    public function __construct()
    {
        Auth::requireAdmin();
    }

    public function index(): void
    {
        $this->view('admin.courses.index', ['courses' => Course::all()]);
    }

    public function createForm(): void
    {
        $this->view('admin.courses.form', ['course' => null]);
    }

    public function create(): void
    {
        if (!verify_csrf()) {
            flash('error', 'Invalid request.');
            redirect('admin/courses');
        }

        $data = $this->extractCourseData();
        if ($data === null) {
            redirect('admin/courses/create');
        }

        Course::create($data);
        flash('success', 'Course created successfully.');
        redirect('admin/courses');
    }

    public function editForm(): void
    {
        $id = (int) ($_GET['id'] ?? 0);
        $course = Course::find($id);

        if (!$course) {
            flash('error', 'Course not found.');
            redirect('admin/courses');
        }

        $this->view('admin.courses.form', ['course' => $course]);
    }

    public function update(): void
    {
        if (!verify_csrf()) {
            flash('error', 'Invalid request.');
            redirect('admin/courses');
        }

        $id = (int) ($_POST['id'] ?? 0);
        $course = Course::find($id);

        if (!$course) {
            flash('error', 'Course not found.');
            redirect('admin/courses');
        }

        $data = $this->extractCourseData();
        if ($data === null) {
            redirect('admin/courses/edit?id=' . $id);
        }

        Course::update($id, $data);
        flash('success', 'Course updated successfully.');
        redirect('admin/courses');
    }

    public function delete(): void
    {
        if (!verify_csrf()) {
            flash('error', 'Invalid request.');
            redirect('admin/courses');
        }

        $id = (int) ($_POST['id'] ?? 0);
        Course::delete($id);
        flash('success', 'Course deleted.');
        redirect('admin/courses');
    }

    private function extractCourseData(): ?array
    {
        $title = trim($_POST['title'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $price = (float) ($_POST['price'] ?? 0);
        $thumbnail = trim($_POST['thumbnail'] ?? '');

        if ($title === '') {
            flash('error', 'Title is required.');
            return null;
        }

        return [
            'title'       => $title,
            'description' => $description,
            'price'       => $price,
            'thumbnail'   => $thumbnail ?: 'assets/images/default-course.jpg',
        ];
    }
}
