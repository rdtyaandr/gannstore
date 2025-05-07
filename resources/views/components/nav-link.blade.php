@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center px-1 pt-1 border-b-2 border-indigo-500 dark:border-indigo-400 text-sm font-medium leading-5 text-indigo-600 dark:text-indigo-400 focus:outline-none focus:border-indigo-700 dark:focus:border-indigo-300 transition duration-150 ease-in-out'
            : 'inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-600 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-indigo-400 hover:border-gray-300 dark:hover:border-gray-700 focus:outline-none focus:text-indigo-600 dark:focus:text-indigo-400 focus:border-gray-300 dark:focus:border-gray-700 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
