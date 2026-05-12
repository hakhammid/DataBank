@props(['active'])

@php
$baseClasses = 'relative flex items-center gap-2 rounded-md py-2 px-3 font-semibold transition duration-200 ease-in-out';
$activeClasses = 'text-zinc-900';
$inactiveClasses = 'text-zinc-500 hover:text-zinc-900 ';

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
