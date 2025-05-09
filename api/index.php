<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Set VERCEL flag explisit
$_ENV['VERCEL'] = true;
$_ENV['VERCEL_DEPLOYMENT'] = 'true';
$_SERVER['VERCEL'] = true;
$_SERVER['VERCEL_DEPLOYMENT'] = 'true';
putenv('VERCEL=true');
putenv('VERCEL_DEPLOYMENT=true');

// Atur session & cache ke array - bisa diubah oleh service provider nanti
$_ENV['SESSION_DRIVER'] = 'cookie';
$_ENV['CACHE_DRIVER'] = 'array';
putenv('SESSION_DRIVER=cookie');
putenv('CACHE_DRIVER=array');

// Path bootstrap cache yang benar untuk Vercel
putenv('APP_BOOTSTRAP_CACHE=/tmp/bootstrap/cache');
$_ENV['APP_BOOTSTRAP_CACHE'] = '/tmp/bootstrap/cache';
$_SERVER['APP_BOOTSTRAP_CACHE'] = '/tmp/bootstrap/cache';

// Force set bootstrap cache paths
putenv('APP_SERVICES_CACHE=/tmp/bootstrap/cache/services.php');
putenv('APP_PACKAGES_CACHE=/tmp/bootstrap/cache/packages.php');
putenv('APP_CONFIG_CACHE=/tmp/bootstrap/cache/config.php');
putenv('APP_ROUTES_CACHE=/tmp/bootstrap/cache/routes-v7.php');
putenv('APP_EVENTS_CACHE=/tmp/bootstrap/cache/events.php');
$_ENV['APP_SERVICES_CACHE'] = '/tmp/bootstrap/cache/services.php';
$_ENV['APP_PACKAGES_CACHE'] = '/tmp/bootstrap/cache/packages.php';
$_ENV['APP_CONFIG_CACHE'] = '/tmp/bootstrap/cache/config.php';
$_ENV['APP_ROUTES_CACHE'] = '/tmp/bootstrap/cache/routes-v7.php';
$_ENV['APP_EVENTS_CACHE'] = '/tmp/bootstrap/cache/events.php';

// Tangani aset statis terlebih dahulu
require __DIR__ . '/asset-helper.php';

// Inisialisasi view helper untuk menangani directory cache view
require __DIR__ . '/view-helper.php';

// Set direktori storage ke /tmp untuk Vercel
putenv('APP_STORAGE=/tmp/storage');
$_ENV['APP_STORAGE'] = '/tmp/storage';
$_SERVER['APP_STORAGE'] = '/tmp/storage';

// Direktori yang perlu dibuat di /tmp
$dirs = [
    '/tmp/storage',
    '/tmp/storage/app',
    '/tmp/storage/app/public',
    '/tmp/storage/framework',
    '/tmp/storage/framework/cache',
    '/tmp/storage/framework/cache/data',
    '/tmp/storage/framework/sessions',
    '/tmp/storage/framework/views',
    '/tmp/storage/logs',
    '/tmp/bootstrap',
    '/tmp/bootstrap/cache'
];

// Buat direktori secara dinamis
foreach ($dirs as $dir) {
    try {
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
    } catch (\Exception $e) {
        // Jika gagal, log tapi lanjutkan
        error_log("Warning: Could not create directory: {$dir} - {$e->getMessage()}");
    }
}

// Direktori logs khusus untuk Laravel
try {
    file_put_contents('/tmp/storage/logs/laravel.log', '');
} catch (\Exception $e) {
    // Ignore
}

// Force set environment variables
putenv("VIEW_COMPILED_PATH=/tmp/storage/framework/views");
$_ENV['VIEW_COMPILED_PATH'] = '/tmp/storage/framework/views';
$_SERVER['VIEW_COMPILED_PATH'] = '/tmp/storage/framework/views';

// PENTING: Cek dan buat symlink jika direktori bootstrap/cache tidak ada
$bootstrapCachePath = '/var/task/user/bootstrap/cache';
if (!is_dir($bootstrapCachePath)) {
    try {
        // Coba membuat symlink dari bootstrap/cache ke /tmp/bootstrap/cache
        if (!is_dir(dirname($bootstrapCachePath))) {
            mkdir(dirname($bootstrapCachePath), 0777, true);
        }
        symlink('/tmp/bootstrap/cache', $bootstrapCachePath);
    } catch (\Exception $e) {
        error_log("Warning: Could not create symlink for bootstrap/cache: {$e->getMessage()}");

        // Jika gagal membuat symlink, coba buat direktori langsung
        try {
            if (!is_dir($bootstrapCachePath)) {
                mkdir($bootstrapCachePath, 0777, true);
            }
        } catch (\Exception $e2) {
            error_log("Critical: Could not create bootstrap/cache directory: {$e2->getMessage()}");
        }
    }
}

// Forward request ke public/index.php
try {
    require __DIR__ . '/../public/index.php';
} catch (\Throwable $e) {
    // Tangkap error database secara khusus
    $errorMessage = $e->getMessage();
    $isDbError = false;

    if ($e instanceof \PDOException || strpos($errorMessage, 'SQLSTATE') !== false ||
        strpos($errorMessage, 'Database') !== false || strpos($errorMessage, 'SQL') !== false ||
        $e instanceof \Illuminate\Database\QueryException) {
        $isDbError = true;
    }

    // Log error ke stderr
    file_put_contents('php://stderr', 'Vercel Error: ' . $e->getMessage() . "\n" . $e->getTraceAsString());

    // Tampilkan error dengan format yang lebih user-friendly
    http_response_code(500);
    if ($isDbError) {
        echo "<!DOCTYPE html>
        <html>
        <head>
            <title>Database Error</title>
            <style>
                body { font-family: Arial, sans-serif; padding: 20px; text-align: center; }
                h1 { color: #e74c3c; }
                .container { max-width: 800px; margin: 0 auto; }
                .error-details { background: #f8f9fa; padding: 15px; text-align: left; border-radius: 5px; overflow-x: auto; }
                .warning { background: #fff3cd; padding: 15px; margin: 20px 0; border-radius: 5px; text-align: left; }
                code { white-space: pre-wrap; font-family: monospace; }
            </style>
        </head>
        <body>
            <div class='container'>
                <h1>500 - Database Error</h1>
                <div class='warning'>
                    <p><strong>PENTING:</strong> Terjadi kesalahan saat menghubungi database.</p>
                    <p>Pastikan database FreeDB mengizinkan koneksi dari luar dan kredensial yang digunakan sudah benar.</p>
                </div>
                <div class='error-details'>
                    <p><strong>Error:</strong> " . htmlspecialchars($e->getMessage()) . "</p>
                </div>
            </div>
        </body>
        </html>";
    } else {
        echo "<!DOCTYPE html>
        <html>
        <head>
            <title>Server Error</title>
            <style>
                body { font-family: Arial, sans-serif; padding: 20px; text-align: center; }
                h1 { color: #e74c3c; }
                .container { max-width: 800px; margin: 0 auto; }
                .error-details { background: #f8f9fa; padding: 15px; text-align: left; border-radius: 5px; overflow-x: auto; }
                code { white-space: pre-wrap; font-family: monospace; }
            </style>
        </head>
        <body>
            <div class='container'>
                <h1>500 - Server Error</h1>
                <p>Terjadi kesalahan saat menjalankan aplikasi.</p>
                <div class='error-details'>
                    <p><strong>Error:</strong> " . htmlspecialchars($e->getMessage()) . "</p>
                    <code>" . htmlspecialchars($e->getTraceAsString()) . "</code>
                </div>
            </div>
        </body>
        </html>";
    }
}
