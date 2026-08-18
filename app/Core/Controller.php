<?php

declare(strict_types=1);

namespace App\Core;

class Controller
{
    protected function view(string $view, array $data = [], string $layout = 'default'): void
    {
        extract($data);
        $appConfig = require dirname(__DIR__, 2) . '/config/app.php';
        $currentUser = Auth::user();
        $flash = getFlash();

        $viewPath = dirname(__DIR__) . '/Views/' . str_replace('.', '/', $view) . '.php';

        if (!file_exists($viewPath)) {
            http_response_code(500);
            die("View not found: {$view}");
        }

        if ($layout === 'admin') {
            $content = function () use ($viewPath) {
                require $viewPath;
            };
            require dirname(__DIR__) . '/Views/layouts/admin.php';
        } else {
            require dirname(__DIR__) . '/Views/layouts/header.php';
            require $viewPath;
            require dirname(__DIR__) . '/Views/layouts/footer.php';
        }
    }

    protected function json(array $data, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    protected function redirect(string $path): void
    {
        redirect($path);
    }
}
