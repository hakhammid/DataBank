@props(['active'])

@php
$baseClasses = 'group relative inline-flex items-center px-1 pt-1 text-sm font-medium transition-all duration-300 ease-in-out';
$activeClasses = 'text-primary font-semibold';
$inactiveClasses = 'text-gray-600 hover:text-primary';

$classes = ($active ?? false)
    ? "$baseClasses $activeClasses"
    : "$baseClasses $inactiveClasses";
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    <span class="relative">
        {{ $slot }}
        {{-- Animated underline --}}
        <span class="absolute left-0 -bottom-1 h-[2px] w-full bg-primary transform transition-transform duration-300 ease-in-out origin-left
            {{ $active ? 'scale-x-100' : 'scale-x-0 group-hover:scale-x-100' }}">
        </span>
    </span>
</a>
