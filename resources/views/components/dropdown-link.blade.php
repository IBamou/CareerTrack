<a {{ $attributes->merge(['class' => 'flex items-center gap-2 px-4 py-2.5 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700/50 hover:text-gray-900 dark:hover:text-white transition-colors']) }}>
    {{ $slot }}
</a>
