<?php

// Cek apakah direktori storage sudah ada di /tmp, jika tidak, buat direktori
$storage_path = '/tmp/storage';
$dirs = [
    $storage_path,
    "$storage_path/app",
    "$storage_path/app/public",
    "$storage_path/framework",
    "$storage_path/framework/cache",
    "$storage_path/framework/cache/data",
    "$storage_path/framework/sessions",
    "$storage_path/framework/views",
    "$storage_path/logs",
    '/tmp/bootstrap',
    '/tmp/bootstrap/cache'
];

foreach ($dirs as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0777, true);
    }
}

// Set environment variables for Laravel
putenv('APP_STORAGE=' . $storage_path);
$_ENV['APP_STORAGE'] = $storage_path;
$_SERVER['APP_STORAGE'] = $storage_path;

// Override storage path untuk Laravel
$app = require __DIR__ . '/../bootstrap/app.php';
$app->useStoragePath($storage_path);

return $app;
