<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Cache;

class DatabaseServiceProvider extends ServiceProvider
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
        // Mendengarkan query untuk menambahkan caching pada query select yang berulang
        DB::listen(function ($query) {
            if (config('app.env') === 'production' && strpos(strtolower($query->sql), 'select') === 0) {
                $key = 'query_' . md5($query->sql . serialize($query->bindings));

                // Cek apakah query yang sama sudah dijalankan dalam 5 menit terakhir
                if (Cache::has($key)) {
                    // Gunakan hasil caching jika tersedia
                    return Cache::get($key);
                }

                // Cache hasil query selama 5 menit
                if ($query->time > 100) { // Query yang lambat saja (> 100ms)
                    Cache::put($key, $query->result, 300);
                }
            }
        });

        // Tutup koneksi setelah setiap permintaan selesai
        Event::listen('kernel.handled', function () {
            DB::disconnect();
        });

        // Tutup koneksi jika terjadi error
        Event::listen('kernel.exception', function () {
            DB::disconnect();
        });
    }
}
