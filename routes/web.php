<?php

declare(strict_types=1);

use App\Controllers\Admin\CourseController as AdminCourseController;
use App\Controllers\Admin\DashboardController;
use App\Controllers\Admin\LessonController as AdminLessonController;
use App\Controllers\Admin\LessonItemController;
use App\Controllers\Admin\UserController;
use App\Controllers\AuthController;
use App\Controllers\CourseController;
use App\Controllers\HomeController;
use App\Controllers\LessonController;
use App\Core\Router;

$router = new Router();

// Public routes
$router->get('/', [HomeController::class, 'index']);
$router->get('/login', [AuthController::class, 'loginForm']);
$router->post('/login', [AuthController::class, 'login']);
$router->get('/register', [AuthController::class, 'registerForm']);
$router->post('/register', [AuthController::class, 'register']);
$router->get('/logout', [AuthController::class, 'logout']);

// Student course routes
$router->get('/course', [CourseController::class, 'show']);
$router->post('/course/request-access', [CourseController::class, 'requestAccess']);
$router->get('/lesson', [LessonController::class, 'show']);
$router->post('/lesson/submit-quiz', [LessonController::class, 'submitQuiz']);

// Admin routes
$router->get('/admin', [DashboardController::class, 'index']);
$router->get('/admin/users', [UserController::class, 'index']);
$router->post('/admin/users/enrollment', [UserController::class, 'updateEnrollment']);

$router->get('/admin/courses', [AdminCourseController::class, 'index']);
$router->get('/admin/courses/create', [AdminCourseController::class, 'createForm']);
$router->post('/admin/courses/create', [AdminCourseController::class, 'create']);
$router->get('/admin/courses/edit', [AdminCourseController::class, 'editForm']);
$router->post('/admin/courses/update', [AdminCourseController::class, 'update']);
$router->post('/admin/courses/delete', [AdminCourseController::class, 'delete']);

$router->get('/admin/lessons', [AdminLessonController::class, 'index']);
$router->get('/admin/lessons/create', [AdminLessonController::class, 'createForm']);
$router->post('/admin/lessons/create', [AdminLessonController::class, 'create']);
$router->get('/admin/lessons/edit', [AdminLessonController::class, 'editForm']);
$router->post('/admin/lessons/update', [AdminLessonController::class, 'update']);
$router->post('/admin/lessons/delete', [AdminLessonController::class, 'delete']);
$router->get('/admin/lessons/items', [AdminLessonController::class, 'items']);

$router->get('/admin/items/create', [LessonItemController::class, 'createForm']);
$router->post('/admin/items/create', [LessonItemController::class, 'create']);
$router->get('/admin/items/edit', [LessonItemController::class, 'editForm']);
$router->post('/admin/items/update', [LessonItemController::class, 'update']);
$router->post('/admin/items/delete', [LessonItemController::class, 'delete']);

return $router;
