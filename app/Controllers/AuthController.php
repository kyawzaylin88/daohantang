<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Models\User;

class AuthController extends Controller
{
    public function loginForm(): void
    {
        if (Auth::check()) {
            redirect('');
        }
        $this->view('auth.login');
    }

    public function login(): void
    {
        if (!verify_csrf()) {
            flash('error', 'Invalid request.');
            redirect('login');
        }

        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        $user = User::findByEmail($email);

        if (!$user || !password_verify($password, $user['password'])) {
            set_old(['email' => $email]);
            flash('error', 'Invalid email or password.');
            redirect('login');
        }

        clear_old();
        Auth::login($user);
        flash('success', 'Welcome back, ' . $user['name'] . '!');

        if ($user['role'] === 'admin') {
            redirect('admin');
        }

        redirect('');
    }

    public function registerForm(): void
    {
        if (Auth::check()) {
            redirect('');
        }
        $this->view('auth.register');
    }

    public function register(): void
    {
        if (!verify_csrf()) {
            flash('error', 'Invalid request.');
            redirect('register');
        }

        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirm = $_POST['password_confirm'] ?? '';

        if ($name === '' || $email === '' || $password === '') {
            set_old(compact('name', 'email'));
            flash('error', 'All fields are required.');
            redirect('register');
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            set_old(compact('name', 'email'));
            flash('error', 'Please enter a valid email address.');
            redirect('register');
        }

        if (strlen($password) < 6) {
            set_old(compact('name', 'email'));
            flash('error', 'Password must be at least 6 characters.');
            redirect('register');
        }

        if ($password !== $confirm) {
            set_old(compact('name', 'email'));
            flash('error', 'Passwords do not match.');
            redirect('register');
        }

        if (User::findByEmail($email)) {
            set_old(compact('name', 'email'));
            flash('error', 'An account with this email already exists.');
            redirect('register');
        }

        User::create($name, $email, $password);
        clear_old();
        flash('success', 'Registration successful! Please log in.');
        redirect('login');
    }

    public function logout(): void
    {
        Auth::logout();
        flash('success', 'You have been logged out.');
        redirect('');
    }
}
