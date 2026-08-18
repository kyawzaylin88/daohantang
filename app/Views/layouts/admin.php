<?php
// Determine active nav based on current URL path
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$base = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
$path = '/' . trim(str_replace($base, '', $uri), '/');

function isActive(string $check, string $path): string {
    return str_starts_with($path, $check) ? 'active' : '';
}

// Page title mapping
$pageTitles = [
    '/admin/courses' => 'Course Management',
    '/admin/lessons' => 'Lesson Management',
    '/admin/users'   => 'User Management',
    '/admin/items'   => 'Video & Quiz Items',
    '/admin'         => 'Dashboard',
];

$pageTitle = 'Admin Panel';
foreach ($pageTitles as $prefix => $title) {
    if (str_starts_with($path, $prefix)) {
        $pageTitle = $title;
        break;
    }
}

$userInitial = strtoupper(substr($currentUser['name'] ?? 'A', 0, 1));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin — <?= e($pageTitle) ?> | <?= e($appConfig['name']) ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link href="<?= app_url('assets/css/admin.css') ?>" rel="stylesheet">
</head>
<body class="admin-body">

<!-- Overlay for mobile -->
<div class="admin-overlay" id="adminOverlay"></div>

<!-- ─── Sidebar ─── -->
<aside class="admin-sidebar" id="adminSidebar">

    <a href="<?= app_url('admin') ?>" class="admin-sidebar-brand">
        <div class="brand-icon"><i class="fas fa-dragon"></i></div>
        <div>
            <div class="brand-text"><?= e($appConfig['name']) ?></div>
            <div class="brand-sub">Admin Panel</div>
        </div>
    </a>

    <nav class="admin-nav">
        <div class="admin-nav-label">Overview</div>

        <a href="<?= app_url('admin') ?>" class="admin-nav-link <?= $path === '/admin' ? 'active' : '' ?>">
            <i class="fas fa-chart-pie"></i> Dashboard
        </a>

        <hr class="admin-nav-divider">
        <div class="admin-nav-label">Content</div>

        <a href="<?= app_url('admin/courses') ?>" class="admin-nav-link <?= isActive('/admin/courses', $path) ?>">
            <i class="fas fa-book-open"></i> Courses
        </a>

        <a href="<?= app_url('admin/lessons') ?>" class="admin-nav-link <?= isActive('/admin/lessons', $path) ?> <?= isActive('/admin/items', $path) ?>">
            <i class="fas fa-list-ol"></i> Lessons & Videos
        </a>

        <hr class="admin-nav-divider">
        <div class="admin-nav-label">Students</div>

        <a href="<?= app_url('admin/users') ?>" class="admin-nav-link <?= isActive('/admin/users', $path) ?>">
            <i class="fas fa-users"></i> Users & Enrollments
        </a>
    </nav>

    <div class="admin-back-link">
        <a href="<?= app_url('') ?>">
            <i class="fas fa-arrow-left"></i> Back to Site
        </a>
    </div>

</aside>

<!-- ─── Main ─── -->
<div class="admin-main">

    <!-- Top Bar -->
    <header class="admin-topbar">
        <div style="display:flex;align-items:center;gap:12px;">
            <button class="admin-mobile-toggle" id="sidebarToggle">
                <i class="fas fa-bars"></i>
            </button>
            <span class="admin-topbar-title"><?= e($pageTitle) ?></span>
        </div>
        <div class="admin-topbar-right">
            <div class="admin-user-badge">
                <div class="admin-avatar"><?= $userInitial ?></div>
                <span><?= e($currentUser['name'] ?? 'Admin') ?></span>
            </div>
            <a href="<?= app_url('logout') ?>" class="admin-logout-btn">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </div>
    </header>

    <!-- Content -->
    <main class="admin-content">

        <?php if ($flash): ?>
        <div class="admin-flash <?= e($flash['type']) ?>">
            <i class="fas fa-<?= $flash['type'] === 'success' ? 'check-circle' : 'exclamation-circle' ?>"></i>
            <?= e($flash['message']) ?>
        </div>
        <?php endif; ?>

        <?php ($content)(); ?>

    </main>
</div>

<script>
// Mobile sidebar toggle
const sidebar = document.getElementById('adminSidebar');
const overlay = document.getElementById('adminOverlay');
const toggle  = document.getElementById('sidebarToggle');

toggle?.addEventListener('click', () => {
    sidebar.classList.toggle('open');
    overlay.classList.toggle('active');
});
overlay.addEventListener('click', () => {
    sidebar.classList.remove('open');
    overlay.classList.remove('active');
});
</script>

</body>
</html>
