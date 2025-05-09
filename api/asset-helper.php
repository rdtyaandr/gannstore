<?php

/**
 * Helper file untuk menangani aset statis di Vercel
 */

// Cek jika ini adalah request untuk file statis (gambar, css, js, dll)
$requestUri = $_SERVER['REQUEST_URI'] ?? '';
$path = parse_url($requestUri, PHP_URL_PATH);
$fileExtension = pathinfo($path, PATHINFO_EXTENSION);

// Daftar ekstensi file statis
$staticFileExtensions = ['png', 'jpg', 'jpeg', 'gif', 'svg', 'ico', 'css', 'js', 'ttf', 'woff', 'woff2', 'eot', 'pdf'];

// Jika ini request untuk file statis
if (in_array(strtolower($fileExtension), $staticFileExtensions)) {
    $filePath = __DIR__ . '/../public' . $path;

    // Cek jika file ada
    if (file_exists($filePath)) {
        // Set content type yang sesuai
        $contentTypes = [
            'png' => 'image/png',
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'gif' => 'image/gif',
            'svg' => 'image/svg+xml',
            'ico' => 'image/x-icon',
            'css' => 'text/css',
            'js' => 'application/javascript',
            'ttf' => 'font/ttf',
            'woff' => 'font/woff',
            'woff2' => 'font/woff2',
            'eot' => 'application/vnd.ms-fontobject',
            'pdf' => 'application/pdf'
        ];

        $contentType = $contentTypes[strtolower($fileExtension)] ?? 'application/octet-stream';
        header("Content-Type: $contentType");

        // Output file
        readfile($filePath);
        exit;
    }
}

// Jika bukan file statis atau file tidak ditemukan, lanjutkan ke index.php normal
