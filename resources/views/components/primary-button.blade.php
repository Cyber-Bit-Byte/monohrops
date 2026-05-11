<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 bg-cyan-600 dark:bg-cyan-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-cyan-800 uppercase tracking-widest hover:bg-cyan-700 dark:hover:bg-cyan-100 focus:bg-cyan-700 dark:focus:bg-cyan-100 active:bg-cyan-800 dark:active:bg-cyan-300 focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
