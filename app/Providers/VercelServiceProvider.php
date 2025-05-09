<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

class VercelServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $isVercel = $this->isVercelEnvironment();

        if ($isVercel) {
            // Set bootstrap cache path saat registrasi
            if (method_exists($this->app, 'bootstrapPath')) {
                $this->app->bootstrapPath('/tmp/bootstrap');
            } else {
                $this->app['path.bootstrap'] = '/tmp/bootstrap';
            }

            // Set storage path saat registrasi
            if (method_exists($this->app, 'storagePath')) {
                $this->app->storagePath('/tmp/storage');
            } else {
                $this->app['path.storage'] = '/tmp/storage';
            }

            // Force mengatur path cache bootstrap
            $this->setBootstrapCachePaths();
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Deteksi Vercel
        $isVercel = $this->isVercelEnvironment();

        if (!$isVercel) {
            return;
        }

        // Force HTTPS
        URL::forceScheme('https');

        // Konfigurasi direktori penyimpanan
        $this->app['path.storage'] = '/tmp/storage';

        // Path konfigurasi Laravel
        Config::set('view.compiled', '/tmp/storage/framework/views');
        Config::set('cache.stores.file.path', '/tmp/storage/framework/cache/data');
        Config::set('session.files', '/tmp/storage/framework/sessions');
        Config::set('logging.channels.single.path', '/tmp/storage/logs/laravel.log');

        // Set bootstrap cache paths
        $this->setBootstrapCachePaths();

        // Atur session untuk bekerja dengan cookie
        Config::set('session.driver', 'cookie');

        // Jika database sedang digunakan (FreeDB)
        if (env('DB_HOST') === 'sql.freedb.tech') {
            // Tambahkan opsi PDO untuk meningkatkan koneksi database
            Config::set('database.connections.mysql.options.' . \PDO::ATTR_TIMEOUT, 5);
            Config::set('database.connections.mysql.options.' . \PDO::ATTR_PERSISTENT, true);

            // Verifikasi koneksi database diizinkan
            try {
                DB::connection()->getPdo();
                // Koneksi berhasil, gunakan cache database
                Config::set('cache.default', 'database');
                Config::set('session.driver', 'database');
            } catch (QueryException $e) {
                // Jika gagal, fallback ke pengaturan yang tidak menggunakan database
                Config::set('cache.default', 'array');
                Config::set('session.driver', 'cookie');

                // Log error
                $this->app->make('log')->error("Database connection failed: {$e->getMessage()}");
            }
        }
    }

    /**
     * Mengatur path cache bootstrap untuk Laravel
     */
    private function setBootstrapCachePaths(): void
    {
        $cacheDir = '/tmp/bootstrap/cache';

        // Atur path aplikasi cache
        Config::set('app.services_cache', "{$cacheDir}/services.php");
        Config::set('app.packages_cache', "{$cacheDir}/packages.php");
        Config::set('app.config_cache', "{$cacheDir}/config.php");
        Config::set('app.routes_cache', "{$cacheDir}/routes-v7.php");
        Config::set('app.events_cache', "{$cacheDir}/events.php");

        // Atur variabel lingkungan juga
        putenv("APP_SERVICES_CACHE={$cacheDir}/services.php");
        putenv("APP_PACKAGES_CACHE={$cacheDir}/packages.php");
        putenv("APP_CONFIG_CACHE={$cacheDir}/config.php");
        putenv("APP_ROUTES_CACHE={$cacheDir}/routes-v7.php");
        putenv("APP_EVENTS_CACHE={$cacheDir}/events.php");
    }

    /**
     * Cek apakah aplikasi berjalan di lingkungan Vercel.
     *
     * @return bool
     */
    private function isVercelEnvironment(): bool
    {
        return !empty($_SERVER['VERCEL']) ||
               !empty($_ENV['VERCEL']) ||
               !empty($_SERVER['VERCEL_DEPLOYMENT']) ||
               !empty($_ENV['VERCEL_DEPLOYMENT']) ||
               env('VERCEL_DEPLOYMENT') === 'true' ||
               env('VERCEL_DEPLOYMENT') === true;
    }
}
