<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>GannStore</title>

        <!-- Favicon -->
        <link rel="icon" href="{{ asset('images/logo.png') }}" type="image/png">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=outfit:400,500,600,700" rel="stylesheet" />

        <!-- Scripts -->
        @if(env('VERCEL_DEPLOYMENT') && function_exists('vercel_vite_assets'))
            @php vercel_vite_assets() @endphp
        @else
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif
    </head>
    <body class="font-sans text-gray-800 antialiased bg-gray-50 dark:bg-gray-900 dark:text-gray-200">
        <div class="min-h-screen flex flex-col sm:justify-center items-center px-4 py-8 sm:pt-0">
            <div class="mb-6">
                <a href="/" class="flex items-center text-xl font-bold text-indigo-600 dark:text-indigo-400">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-8 w-auto mr-2">
                    GannStore
                </a>
            </div>

            <div class="w-full sm:max-w-md px-6 py-6 bg-white dark:bg-gray-800 shadow-md rounded-lg border border-gray-100 dark:border-gray-700">
                {{ $slot }}
            </div>

            <div class="mt-6 text-center">
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    &copy; {{ date('Y') }} GannStore. All rights reserved.
                </p>
            </div>
        </div>
    </body>
</html>
