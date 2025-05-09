<?php

/**
 * Helper untuk memastikan direktori cache view tersedia
 */

// Direktori yang diperlukan untuk view compiled
$viewCachePath = '/tmp/storage/framework/views';

// Buat direktori jika belum ada
if (!is_dir($viewCachePath)) {
    mkdir($viewCachePath, 0777, true);

    // Buat file .gitignore di direktori
    file_put_contents($viewCachePath . '/.gitignore', "*\n!.gitignore\n");
}

// Definisikan konstanta untuk kompiler view
if (!defined('VIEW_COMPILED_PATH')) {
    define('VIEW_COMPILED_PATH', $viewCachePath);
}

// Set environment variable
putenv("VIEW_COMPILED_PATH={$viewCachePath}");
$_ENV['VIEW_COMPILED_PATH'] = $viewCachePath;
$_SERVER['VIEW_COMPILED_PATH'] = $viewCachePath;
