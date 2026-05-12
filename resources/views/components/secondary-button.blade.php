{{-- <button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
 --}}

<button {{ $attributes->class([
    'type' => 'submit',
    'inline-flex rounded-full items-center justify-center gap-2 px-4 py-2 bg-[#F0F8FF] hover:bg-blue-100 hover:bg-opacity-60 border border-transparent font-medium text-sm text-blue-600 tracking-wide transition-all duration-200 ease-in-out disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:bg-blue-600',
]) }}>
    {{ $slot }}
</button>