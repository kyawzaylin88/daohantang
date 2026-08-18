<?php

declare(strict_types=1);

require __DIR__ . '/bootstrap.php';

$router = require __DIR__ . '/routes/web.php';
$router->dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
