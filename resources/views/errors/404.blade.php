<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - Halaman Tidak Ditemukan</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        @media (max-width: 640px) {
            .error-container {
                padding-left: 1rem;
                padding-right: 1rem;
            }
            .error-illustration {
                width: 200px;
                height: 200px;
            }
        }

        @media (max-width: 768px) {
            .button-container {
                flex-direction: column;
                gap: 0.75rem;
            }
            .error-button {
                width: 100%;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            .hover-scale {
                transition: none;
            }
        }

        .hover-scale {
            transition: transform 0.2s ease;
        }

        .hover-scale:hover {
            transform: scale(1.05);
        }
    </style>
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100 dark:bg-gray-900 flex flex-col justify-center items-center px-4 py-8 md:p-8 error-container">
        <div class="w-full max-w-lg bg-white dark:bg-gray-800 rounded-lg shadow-xl overflow-hidden">
            <!-- Header Section -->
            <div class="bg-blue-600 dark:bg-blue-800 p-4 md:p-6">
                <h1 class="text-white text-center text-xl md:text-2xl lg:text-3xl font-bold tracking-tight">
                    404 - Halaman Tidak Ditemukan
                </h1>
            </div>

            <!-- Illustration Section -->
            <div class="flex justify-center p-4 md:p-6 bg-blue-50 dark:bg-gray-700">
                <div class="w-48 h-48 md:w-56 md:h-56 error-illustration">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" class="w-full h-full text-blue-600 dark:text-blue-400">
                        <circle cx="12" cy="12" r="10" stroke-width="1.5" />
                        <path d="M9 10.5C9 10.5 9 9 10 9C11 9 11 10.5 11 10.5" stroke-width="1.5" stroke-linecap="round" />
                        <path d="M13 10.5C13 10.5 13 9 14 9C15 9 15 10.5 15 10.5" stroke-width="1.5" stroke-linecap="round" />
                        <path d="M9 16C9 16 10 14 12 14C14 14 15 16 15 16" stroke-width="1.5" stroke-linecap="round" />
                        <path d="M7.5 8.5L5.5 6.5M16.5 8.5L18.5 6.5" stroke-width="1.5" stroke-linecap="round" />
                    </svg>
                </div>
            </div>

            <!-- Message Section -->
            <div class="p-4 md:p-6 text-center">
                <h2 class="text-lg md:text-xl lg:text-2xl font-bold text-gray-800 dark:text-gray-200 mb-3 md:mb-4">
                    Waduh, Jalan Buntu!
                </h2>
                <p class="text-sm md:text-base text-gray-600 dark:text-gray-400 mb-6 md:mb-8 max-w-md mx-auto">
                    Halaman yang Anda cari tidak ditemukan atau mungkin telah dipindahkan.
                    Silakan kembali ke beranda atau hubungi administrator jika Anda yakin ini adalah kesalahan.
                </p>

                <!-- Buttons Section -->
                <div class="flex flex-col sm:flex-row justify-center gap-3 button-container">
                    <a href="{{ route('dashboard') }}" class="inline-flex items-center justify-center px-4 py-2 md:px-6 md:py-3 bg-blue-600 border border-transparent rounded-md font-semibold text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-150 hover-scale error-button">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
                        </svg>
                        <span class="text-sm md:text-base">Kembali ke Beranda</span>
                    </a>
                    <button onclick="history.back()" class="inline-flex items-center justify-center px-4 py-2 md:px-6 md:py-3 bg-gray-200 dark:bg-gray-700 border border-transparent rounded-md font-semibold text-gray-700 dark:text-gray-300 hover:bg-gray-300 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors duration-150 hover-scale error-button">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                        </svg>
                        <span class="text-sm md:text-base">Kembali</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Footer Message -->
        <p class="mt-6 md:mt-8 text-gray-500 dark:text-gray-400 text-center text-xs md:text-sm">
            &copy; {{ date('Y') }} {{ config('app.name', 'Laravel') }}. Semua hak dilindungi.
        </p>
    </div>
</body>
</html>
