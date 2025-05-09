<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Konfigurasi direktori penyimpanan untuk Vercel
if (isset($_ENV['VERCEL_DEPLOYMENT']) && $_ENV['VERCEL_DEPLOYMENT'] === 'true') {
    // Untuk Vercel, gunakan /tmp untuk penyimpanan
    $_ENV['APP_STORAGE'] = '/tmp';

    // Buat direktori tmp jika belum ada
    if (!is_dir('/tmp/storage')) {
        mkdir('/tmp/storage/framework/views', 0755, true);
        mkdir('/tmp/storage/framework/cache', 0755, true);
        mkdir('/tmp/storage/framework/sessions', 0755, true);
        mkdir('/tmp/storage/logs', 0755, true);
    }
}

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require __DIR__.'/../vendor/autoload.php';

// Bootstrap Laravel and handle the request...
/** @var Application $app */
$app = require_once __DIR__.'/../bootstrap/app.php';

$app->handleRequest(Request::capture());
