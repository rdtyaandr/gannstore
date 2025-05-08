<?php

/**
 * Helper untuk meload assets Vite di Vercel
 */
if (!function_exists('vercel_vite_assets')) {
    function vercel_vite_assets() {
        $manifest_path = public_path('build/manifest.json');

        if (file_exists($manifest_path)) {
            $manifest = json_decode(file_get_contents($manifest_path), true);

            echo '<link rel="stylesheet" href="/build/' . $manifest['resources/css/app.css']['file'] . '">';
            echo '<script type="module" src="/build/' . $manifest['resources/js/app.js']['file'] . '"></script>';
            return true;
        }

        return false;
    }
}