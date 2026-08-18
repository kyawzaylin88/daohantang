<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($appConfig['name']) ?> - <?= e($appConfig['tagline']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link href="<?= app_url('assets/css/style.css') ?>" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold" href="<?= app_url('') ?>">
            <i class="fas fa-dragon me-2"></i><?= e($appConfig['name']) ?>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="mainNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link" href="<?= app_url('') ?>">Courses</a>
                </li>
                <?php if ($currentUser && $currentUser['role'] === 'admin'): ?>
                <li class="nav-item">
                    <a class="nav-link" href="<?= app_url('admin') ?>">Admin Dashboard</a>
                </li>
                <?php endif; ?>
            </ul>
            <ul class="navbar-nav">
                <?php if ($currentUser): ?>
                <li class="nav-item">
                    <span class="nav-link"><i class="fas fa-user me-1"></i><?= e($currentUser['name']) ?></span>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= app_url('logout') ?>">Logout</a>
                </li>
                <?php else: ?>
                <li class="nav-item">
                    <a class="nav-link" href="<?= app_url('login') ?>">Login</a>
                </li>
                <li class="nav-item">
                    <a class="btn btn-light btn-sm ms-2 mt-1" href="<?= app_url('register') ?>">Register</a>
                </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<main class="py-4">
    <div class="container">
        <?php if ($flash): ?>
        <div class="alert alert-<?= $flash['type'] === 'error' ? 'danger' : e($flash['type']) ?> alert-dismissible fade show">
            <?= e($flash['message']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>
