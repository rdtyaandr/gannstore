<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Config;

class SessionServiceProvider extends ServiceProvider
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
        // Deteksi Vercel
        $isVercel = !empty($_SERVER['VERCEL']) ||
                   !empty($_ENV['VERCEL']) ||
                   !empty($_SERVER['VERCEL_DEPLOYMENT']) ||
                   !empty($_ENV['VERCEL_DEPLOYMENT']) ||
                   env('VERCEL_DEPLOYMENT') === 'true' ||
                   env('VERCEL_DEPLOYMENT') === true;

        // Jika di Vercel, ubah konfigurasi session agar tidak menggunakan database
        if ($isVercel) {
            Config::set('session.driver', 'array');
            Config::set('session.expire_on_close', true);
        }
    }
}
