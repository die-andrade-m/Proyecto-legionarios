<?php

// Force HTTPS recognition behind Vercel edge proxy
if (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') {
    $_SERVER['HTTPS'] = 'on';
    $_SERVER['SERVER_PORT'] = 443;
}

// Sanitize environment variables (remove UTF-8 BOM, whitespace, quotes)
foreach (['APP_KEY', 'APP_ENV', 'APP_DEBUG', 'DB_CONNECTION', 'DB_HOST', 'DB_PORT', 'DB_DATABASE', 'DB_USERNAME', 'DB_PASSWORD', 'DB_URL', 'SESSION_DRIVER'] as $var) {
    $val = getenv($var);
    if ($val === false && isset($_ENV[$var])) {
        $val = $_ENV[$var];
    }
    if ($val === false && isset($_SERVER[$var])) {
        $val = $_SERVER[$var];
    }
    if ($val !== false && $val !== null && is_string($val)) {
        $cleanVal = trim(preg_replace('/^\xEF\xBB\xBF/', '', $val));
        putenv("{$var}={$cleanVal}");
        $_ENV[$var] = $cleanVal;
        $_SERVER[$var] = $cleanVal;
    }
}

// Configure Vercel serverless environment paths to writable /tmp
putenv('VIEW_COMPILED_PATH=/tmp/views');
putenv('APP_STORAGE=/tmp/storage');
putenv('APP_SERVICES_CACHE=/tmp/services.php');
putenv('APP_PACKAGES_CACHE=/tmp/packages.php');
putenv('APP_CONFIG_CACHE=/tmp/config.php');
putenv('APP_ROUTES_CACHE=/tmp/routes.php');
putenv('APP_EVENTS_CACHE=/tmp/events.php');

$_ENV['VIEW_COMPILED_PATH'] = '/tmp/views';
$_ENV['APP_STORAGE'] = '/tmp/storage';
$_ENV['APP_SERVICES_CACHE'] = '/tmp/services.php';
$_ENV['APP_PACKAGES_CACHE'] = '/tmp/packages.php';
$_ENV['APP_CONFIG_CACHE'] = '/tmp/config.php';
$_ENV['APP_ROUTES_CACHE'] = '/tmp/routes.php';
$_ENV['APP_EVENTS_CACHE'] = '/tmp/events.php';

// Clean any stale cached bootstrap files in /bootstrap/cache
if (file_exists(__DIR__ . '/../bootstrap/cache/packages.php')) {
    @unlink(__DIR__ . '/../bootstrap/cache/packages.php');
}
if (file_exists(__DIR__ . '/../bootstrap/cache/services.php')) {
    @unlink(__DIR__ . '/../bootstrap/cache/services.php');
}

// Create temporary directories in /tmp if missing
$tmpDirs = [
    '/tmp/views',
    '/tmp/storage',
    '/tmp/storage/framework',
    '/tmp/storage/framework/views',
    '/tmp/storage/framework/cache',
    '/tmp/storage/framework/sessions',
    '/tmp/storage/logs',
];

foreach ($tmpDirs as $dir) {
    if (!is_dir($dir)) {
        @mkdir($dir, 0755, true);
    }
}

// Forward Vercel Serverless Function requests to Laravel entry point
require __DIR__ . '/../public/index.php';
