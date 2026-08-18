<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Auth;
use App\Core\Controller;
use App\Models\Enrollment;
use App\Models\User;

class UserController extends Controller
{
    public function __construct()
    {
        Auth::requireAdmin();
    }

    public function index(): void
    {
        $users = User::allStudents();
        $enrollments = Enrollment::allWithDetails();

        $this->view('admin.users.index', [
            'users'       => $users,
            'enrollments' => $enrollments,
        ], 'admin');
    }

    public function updateEnrollment(): void
    {
        if (!verify_csrf()) {
            flash('error', 'Invalid request.');
            redirect('admin/users');
        }

        $id = (int) ($_POST['enrollment_id'] ?? 0);
        $status = $_POST['status'] ?? '';

        if (!in_array($status, ['approved', 'declined', 'pending'], true)) {
            flash('error', 'Invalid status.');
            redirect('admin/users');
        }

        Enrollment::updateStatus($id, $status);
        flash('success', 'Enrollment status updated.');
        redirect('admin/users');
    }
}
