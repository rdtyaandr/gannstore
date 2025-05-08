<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>GannStore</title>

        <!-- Favicon -->
        <link rel="icon" href="{{ asset('images/logo.png') }}" type="image/png">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=outfit:400,500,600,700" rel="stylesheet" />

        <!-- Styles / Scripts -->
        @if(env('VERCEL_DEPLOYMENT') && function_exists('vercel_vite_assets'))
            @php vercel_vite_assets() @endphp
        @else
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif
    </head>
    <body class="bg-gray-50 dark:bg-gray-900 text-gray-800 dark:text-gray-200 min-h-screen font-sans">
        <!-- Header -->
        <header class="fixed top-0 left-0 right-0 z-50 bg-white dark:bg-gray-800 shadow-sm">
            <div class="container mx-auto px-4 py-3">
                <nav class="flex items-center justify-between">
                    <div class="flex items-center">
                        <a href="/" class="flex items-center text-xl font-bold text-indigo-600 dark:text-indigo-400">
                            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-6 w-auto mr-2">
                            GannStore
                        </a>
                    </div>
                    <div class="flex items-center gap-4">
                        @if (Route::has('login'))
                            @auth
                                <a href="{{ url('/dashboard') }}" class="inline-block px-4 py-1.5 text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-900/30 rounded-md text-sm font-medium hover:bg-indigo-100 dark:hover:bg-indigo-900/50 transition-colors">
                                    Dashboard
                                </a>
                            @else
                                <a href="{{ route('login') }}" class="inline-block px-4 py-1.5 text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-900/30 rounded-md text-sm font-medium hover:bg-indigo-100 dark:hover:bg-indigo-900/50 transition-colors">
                                    Masuk
                                </a>
                            @endauth
                        @endif
                    </div>
                </nav>
            </div>
        </header>

        <!-- Hero Section -->
        <section class="pt-24 pb-16 px-4">
            <div class="container mx-auto">
                <div class="flex flex-col lg:flex-row items-center gap-10 max-w-4xl mx-auto">
                    <div class="flex-1 text-center lg:text-left">
                        <h1 class="text-3xl lg:text-4xl font-bold mb-4 text-gray-900 dark:text-white">
                            <span class="text-indigo-600 dark:text-indigo-400">GannStore</span> Aplikasi Struk & Keuangan
                        </h1>
                        <p class="text-base mb-6 text-gray-600 dark:text-gray-300">
                            Solusi sederhana untuk membuat struk penjualan dan menghitung pendapatan bisnis Anda.
                        </p>
                        <div class="flex flex-col sm:flex-row gap-3 justify-center lg:justify-start">
                            <a href="{{ route('login') }}" class="inline-block px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-md text-sm font-medium transition-colors">
                                Mulai Sekarang
                            </a>
                        </div>
                    </div>
                    <div class="flex-1 mt-6 lg:mt-0">
                        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
                            <div class="aspect-video bg-gray-100 dark:bg-gray-700 rounded flex items-center justify-center">
                                <svg class="w-16 h-16 text-indigo-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Features Section -->
        <section id="fitur" class="py-16 px-4 bg-gray-100 dark:bg-gray-800/50">
            <div class="container mx-auto max-w-4xl">
                <h2 class="text-2xl font-bold text-center mb-10 text-gray-900 dark:text-white">
                    Fitur Utama
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="bg-white dark:bg-gray-800 p-5 rounded-lg shadow-sm hover:shadow-md transition-shadow">
                        <div class="w-10 h-10 bg-indigo-100 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 rounded-md flex items-center justify-center mb-4">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium mb-2 text-gray-900 dark:text-white">Pembuatan Struk</h3>
                        <p class="text-gray-600 dark:text-gray-300 text-sm">Buat dan cetak struk penjualan dengan mudah dan cepat.</p>
                    </div>
                    <div class="bg-white dark:bg-gray-800 p-5 rounded-lg shadow-sm hover:shadow-md transition-shadow">
                        <div class="w-10 h-10 bg-indigo-100 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 rounded-md flex items-center justify-center mb-4">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium mb-2 text-gray-900 dark:text-white">Hitung Pendapatan</h3>
                        <p class="text-gray-600 dark:text-gray-300 text-sm">Pantau dan hitung pendapatan bisnis Anda dengan tepat.</p>
                    </div>
                    <div class="bg-white dark:bg-gray-800 p-5 rounded-lg shadow-sm hover:shadow-md transition-shadow">
                        <div class="w-10 h-10 bg-indigo-100 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 rounded-md flex items-center justify-center mb-4">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium mb-2 text-gray-900 dark:text-white">Laporan Sederhana</h3>
                        <p class="text-gray-600 dark:text-gray-300 text-sm">Dapatkan laporan bisnis sederhana yang mudah dipahami.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 py-6">
            <div class="container mx-auto px-4 max-w-4xl">
                <div class="flex flex-col md:flex-row justify-between items-center">
                    <div class="mb-4 md:mb-0">
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            &copy; {{ date('Y') }} GannStore. All rights reserved.
                        </p>
                    </div>
                    <div class="flex gap-4">
                        <a href="#" class="text-sm text-gray-500 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400">
                            Tentang Kami
                        </a>
                        <a href="#" class="text-sm text-gray-500 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400">
                            Kebijakan Privasi
                        </a>
                        <a href="#" class="text-sm text-gray-500 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400">
                            Syarat & Ketentuan
                        </a>
                    </div>
                </div>
            </div>
        </footer>
    </body>
</html>
