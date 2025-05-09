<?php

// Buat direktori cache
if (!is_dir('/tmp/bootstrap/cache')) {
    mkdir('/tmp/bootstrap/cache', 0755, true);
}

// Symlink bootstrap/cache ke /tmp/bootstrap/cache
if (!file_exists(__DIR__ . '/../bootstrap/cache')) {
    symlink('/tmp/bootstrap/cache', __DIR__ . '/../bootstrap/cache');
}
