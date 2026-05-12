@props(['active'])

@php
$baseClasses = 'relative flex mb-1 items-center gap-2 hover:bg-gray-100 rounded-md py-2 px-3 font-medium transition duration-200 ease-in-out';
$activeClasses = 'bg-gray-100 text-black-900 dark:text-black-900';
$inactiveClasses = 'text-black hover:text-black-500';

$classes = ($active ?? false) 
    ? "{$baseClasses} {$activeClasses}"
    : "{$baseClasses} {$inactiveClasses}";
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
    <span class="hidden lg:block absolute bottom-0 left-0 w-full h-0.5 bg-transparent">
        @if($active)
            <span class="absolute inset-0 bg-black-600"></span>
        @endif
    </span>
</a>
