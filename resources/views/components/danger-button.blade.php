{{-- <button {{ $attributes->class(['type' => 'submit', 'rounded-full inline-flex items-center px-4 py-4 bg-red-600 border border-transparent font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button> --}}


<button {{ $attributes->class([
    'type' => 'submit',
    'inline-flex rounded-full items-center justify-center gap-2 px-4 py-2 bg-red-600 hover:bg-red-700 border border-transparent font-medium text-sm text-white tracking-wide transition-all duration-200 ease-in-out disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:bg-red-600',
]) }}>
    {{ $slot }}
</button>