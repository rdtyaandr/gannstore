<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center justify-center px-5 py-2.5 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md font-medium text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
