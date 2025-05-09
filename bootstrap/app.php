<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Providers\VercelServiceProvider;

// Deteksi Vercel environment dan set storage path dengan lebih robust
$isVercelDeployment = !empty($_SERVER['VERCEL']) ||
                      !empty($_ENV['VERCEL']) ||
                      !empty($_SERVER['VERCEL_DEPLOYMENT']) ||
                      !empty($_ENV['VERCEL_DEPLOYMENT']) ||
                      getenv('VERCEL_DEPLOYMENT') === 'true' ||
                      getenv('VERCEL_DEPLOYMENT') === '1';

// Jika berjalan di Vercel, gunakan direktori /tmp untuk storage
if ($isVercelDeployment) {
    $_ENV['APP_STORAGE'] = '/tmp/storage';
    putenv('APP_STORAGE=/tmp/storage');

    // Pastikan view compiled path diatur dengan benar
    if (!isset($_ENV['VIEW_COMPILED_PATH']) || empty($_ENV['VIEW_COMPILED_PATH'])) {
        $_ENV['VIEW_COMPILED_PATH'] = '/tmp/storage/framework/views';
        putenv('VIEW_COMPILED_PATH=/tmp/storage/framework/views');
    }

    // Atur bootstrap cache path juga
    $_ENV['APP_BOOTSTRAP_CACHE'] = '/tmp/bootstrap/cache';
    putenv('APP_BOOTSTRAP_CACHE=/tmp/bootstrap/cache');

    // Set path untuk file cache konfigurasi
    $_ENV['APP_SERVICES_CACHE'] = '/tmp/bootstrap/cache/services.php';
    $_ENV['APP_PACKAGES_CACHE'] = '/tmp/bootstrap/cache/packages.php';
    $_ENV['APP_CONFIG_CACHE'] = '/tmp/bootstrap/cache/config.php';
    $_ENV['APP_ROUTES_CACHE'] = '/tmp/bootstrap/cache/routes-v7.php';
    $_ENV['APP_EVENTS_CACHE'] = '/tmp/bootstrap/cache/events.php';
    putenv('APP_SERVICES_CACHE=/tmp/bootstrap/cache/services.php');
    putenv('APP_PACKAGES_CACHE=/tmp/bootstrap/cache/packages.php');
    putenv('APP_CONFIG_CACHE=/tmp/bootstrap/cache/config.php');
    putenv('APP_ROUTES_CACHE=/tmp/bootstrap/cache/routes-v7.php');
    putenv('APP_EVENTS_CACHE=/tmp/bootstrap/cache/events.php');

    // Buat direktori bootstrap cache di tmp
    $bootstrapCacheDir = '/tmp/bootstrap/cache';
    if (!is_dir($bootstrapCacheDir)) {
        @mkdir($bootstrapCacheDir, 0777, true);
    }
}

// Set bootstrap path untuk aplikasi jika berjalan di Vercel
$bootstrapPath = $isVercelDeployment ? '/tmp/bootstrap' : null;
$storagePath = $isVercelDeployment ? '/tmp/storage' : null;

// Buat instance aplikasi
$app = Application::configure(basePath: dirname(__DIR__))
    ->withProviders([
        // Daftarkan VercelServiceProvider untuk deployment di Vercel
        $isVercelDeployment ? VercelServiceProvider::class : null,
    ])
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->trustProxies(at: '*');
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();

// Atur bootstrap path setelah membuat aplikasi
if ($isVercelDeployment) {
    $app->useBootstrapPath($bootstrapPath);
    $app->useStoragePath($storagePath);
}

return $app;
