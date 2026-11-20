<?php
declare(strict_types=1);

// Local development: `php -S localhost:8000 -t www www/index.php`
if (PHP_SAPI === 'cli-server') {
    $file = __DIR__ . (parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/');
    if ($file !== __DIR__ . '/' && is_file($file) && !str_ends_with($file, '.php')) {
        return false;
    }
}

// The application code normally sits next to www/ (outside the web root).
$bootstrap = is_file(__DIR__ . '/../app/bootstrap.php')
    ? __DIR__ . '/../app/bootstrap.php'
    : __DIR__ . '/app/bootstrap.php';
require $bootstrap;

$path = request_path();
if ($path === '/admin' || str_starts_with($path, '/admin/')) {
    require APP_DIR . '/admin.php';
} else {
    require APP_DIR . '/site.php';
}
