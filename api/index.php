<?php

// Configure Vercel serverless environment paths to writable /tmp
putenv('VIEW_COMPILED_PATH=/tmp/views');
putenv('APP_STORAGE=/tmp/storage');

$_ENV['VIEW_COMPILED_PATH'] = '/tmp/views';
$_ENV['APP_STORAGE'] = '/tmp/storage';

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
