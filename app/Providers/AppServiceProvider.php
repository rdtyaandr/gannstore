<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\URL;

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
        // Memaksa penggunaan HTTPS untuk semua URL yang dihasilkan
        if (env('APP_ENV') === 'production' || env('VERCEL_DEPLOYMENT') === true) {
            URL::forceScheme('https');
        }

        // Menangani asset Vite di Vercel
        if (env('VERCEL_DEPLOYMENT') === true && !function_exists('vite_assets')) {
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
    }
}
