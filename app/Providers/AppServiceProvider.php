<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Events\QueryExecuted;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Deteksi Vercel deployment secara lebih explisit
        $isVercelDeployment = $this->isVercelDeployment();

        // Memaksa penggunaan HTTPS untuk semua URL yang dihasilkan
        if (env('APP_ENV') === 'production' || $isVercelDeployment) {
            URL::forceScheme('https');
        }

        // Menangani asset Vite di Vercel
        if ($isVercelDeployment && !function_exists('vite_assets')) {
            $this->app->singleton('vite_assets', function () {
                return function () {
                    $manifest = json_decode(file_get_contents(public_path('build/manifest.json')), true);

                    echo '<link rel="stylesheet" href="/build/' . $manifest['resources/css/app.css']['file'] . '">';
                    echo '<script type="module" src="/build/' . $manifest['resources/js/app.js']['file'] . '"></script>';
                };
            });

            Blade::directive('vite', function ($expression) {
                return "<?php echo app('vite_assets')(); ?>";
            });
        }

        // HANYA untuk lingkungan NON-VERCEL - tambahkan pemeriksaan tambahan
        if (!$isVercelDeployment && $this->canAccessFilesystem()) {
            try {
                // Membuat direktori log jika belum ada
                $logPath = config('logging.channels.single.path');
                $directory = dirname($logPath);
                if (!File::exists($directory)) {
                    File::makeDirectory($directory, 0755, true);
                }
            } catch (\Exception $e) {
                // Log error ke stderr
                error_log('Error creating directory: ' . $e->getMessage());
            }
        }

        // Konfigurasi untuk Vercel dengan filesystem yang read-only
        if ($isVercelDeployment) {
            // Mengatur direktori penyimpanan ke /tmp tanpa membuat direktori
            $this->app['path.storage'] = '/tmp/storage';

            // Mengatur konfigurasi path tanpa mencoba membuat direktori
            Config::set('view.compiled', '/tmp/storage/framework/views');
            Config::set('cache.stores.file.path', '/tmp/storage/framework/cache/data');
            Config::set('session.files', '/tmp/storage/framework/sessions');
            Config::set('logging.channels.single.path', '/tmp/storage/logs/laravel.log');

            // Nonaktifkan koneksi database dan gunakan sqlite in-memory untuk Vercel
            if (env('DB_CONNECTION') === 'mysql' &&
                (strpos(env('DB_HOST', ''), 'infinityfree.com') !== false ||
                 strpos(env('DB_HOST', ''), 'freesqldatabase.com') !== false)) {

                // Ubah ke SQLite in-memory untuk deployment Vercel
                Config::set('database.default', 'sqlite');
                Config::set('database.connections.sqlite.database', ':memory:');

                // Nonaktifkan fitur yang memerlukan database
                Config::set('session.driver', 'array');
                Config::set('cache.default', 'array');
            }
        }

        // Optimasi untuk database InfinityFree
        if (env('DB_HOST') === 'sql112.infinityfree.com') {
            // Atur batas waktu koneksi lebih singkat
            Config::set('database.connections.mysql.options.0', \PDO::ATTR_TIMEOUT, 5);

            // Gunakan persistent connection untuk mengurangi koneksi baru
            Config::set('database.connections.mysql.options.' . \PDO::ATTR_PERSISTENT, true);

            // Atur default string length untuk MySQL versi lama
            Schema::defaultStringLength(191);

            // Implementasi caching sederhana untuk query
            DB::listen(function (QueryExecuted $query) {
                // Cache hanya untuk query SELECT
                if (str_starts_with(strtolower($query->sql), 'select')) {
                    $key = 'db_query_' . md5($query->sql . json_encode($query->bindings));

                    // Cek cache
                    if (Cache::has($key)) {
                        return Cache::get($key);
                    }

                    // Cache query yang lambat
                    if ($query->time > 50) {
                        // Simpan hasil query ke cache
                        try {
                            $result = DB::selectOne($query->sql, $query->bindings);
                            Cache::put($key, $result, 300); // Cache selama 5 menit
                        } catch (\Exception $e) {
                            // Jika gagal, jangan cache
                        }
                    }
                }
            });

            // Gunakan mode LAZY-LOADING untuk model relationship untuk mengurangi query
            Config::set('database.model_lazy_loading', true);

            // Force single connection untuk semua query
            DB::connection()->setReconnector(function ($connection) {
                // Jangan membuat koneksi baru, gunakan yang sudah ada
            });

            // Tutup koneksi di akhir request
            app()->terminating(function () {
                DB::disconnect();
            });
        }
    }

    /**
     * Deteksi apakah berjalan di Vercel dengan lebih explisit
     */
    private function isVercelDeployment(): bool
    {
        // Periksa beberapa cara untuk mendeteksi Vercel
        return !empty($_SERVER['VERCEL']) ||
               !empty($_ENV['VERCEL']) ||
               !empty($_SERVER['VERCEL_DEPLOYMENT']) ||
               !empty($_ENV['VERCEL_DEPLOYMENT']) ||
               env('VERCEL_DEPLOYMENT') === 'true' ||
               env('VERCEL_DEPLOYMENT') === true;
    }

    /**
     * Periksa apakah filesystem dapat diakses
     */
    private function canAccessFilesystem(): bool
    {
        // Coba buat direktori test di /tmp
        try {
            $testDir = sys_get_temp_dir() . '/laravel_test_' . time();
            if (!is_dir($testDir)) {
                mkdir($testDir, 0777);
                rmdir($testDir);
            }
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}
