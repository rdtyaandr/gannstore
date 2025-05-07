@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block w-full ps-3 pe-4 py-2 border-l-4 border-indigo-500 dark:border-indigo-400 text-start text-base font-medium text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-900/20 focus:outline-none focus:text-indigo-700 dark:focus:text-indigo-300 focus:bg-indigo-100 dark:focus:bg-indigo-900/30 focus:border-indigo-600 dark:focus:border-indigo-300 transition duration-150 ease-in-out'
            : 'block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium text-gray-600 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-gray-50 dark:hover:bg-gray-800/60 hover:border-indigo-300 dark:hover:border-indigo-600 focus:outline-none focus:text-indigo-600 dark:focus:text-indigo-400 focus:bg-gray-50 dark:focus:bg-gray-800/70 focus:border-indigo-300 dark:focus:border-indigo-600 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
