<?php

// Membuat direktori cache yang diperlukan
$dirs = ['/tmp/views'];
foreach ($dirs as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
}

// Set variabel lingkungan
putenv('VERCEL_DEPLOYMENT=true');
$_ENV['VERCEL_DEPLOYMENT'] = true;

// Tangani kasus ketika manifest Vite perlu diakses langsung
if (preg_match('/\/build\/(.+)$/', $_SERVER['REQUEST_URI'], $matches)) {
    $filePath = __DIR__ . '/../public/build/' . $matches[1];
    if (file_exists($filePath)) {
        $extension = pathinfo($filePath, PATHINFO_EXTENSION);

        // Set content type yang sesuai
        switch ($extension) {
            case 'js':
                header('Content-Type: application/javascript');
                break;
            case 'css':
                header('Content-Type: text/css');
                break;
            case 'json':
                header('Content-Type: application/json');
                break;
        }

        echo file_get_contents($filePath);
        exit;
    }
}

// Jalankan aplikasi Laravel
require __DIR__ . "/../public/index.php";
