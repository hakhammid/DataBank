@props(['active' => false])

<span class="relative">
    <a class="flex w-full {{ $active ? 'bg-primary text-white' : 'text-zinc-950/[80%] hover:bg-zinc-950/[4%]' }} items-center gap-3 rounded-lg px-2 py-2.5 text-left text-base/6 font-medium sm:py-2 sm:text-sm/5
            data-[slot=icon]:*:size-6 data-[slot=icon]:*:shrink-0
            data-[slot=icon]:*:fill-zinc-500 hover:data-[slot=icon]:*:fill-white
            sm:data-[slot=icon]:*:size-5 data-[slot=icon]:last:*:ml-auto data-[slot=icon]:last:*:size-5 sm:data-[slot=icon]:last:*:size-4
            data-[slot=avatar]:*:-m-0.5 data-[slot=avatar]:*:size-7 data-[slot=avatar]:*:[--ring-opacity:10%] sm:data-[slot=avatar]:*:size-6
            data-[hover]:bg-zinc-950/5 data-[slot=icon]:*:data-[hover]:fill-zinc-950
            data-[active]:bg-zinc-950/5 data-[slot=icon]:*:data-[active]:fill-zinc-950
            data-[slot=icon]:*:data-[current]:fill-zinc-950"
        {{ $active ? 'data-current="true"' : 'type="button"'}} aria-current="false" {{ $attributes }}>
        <span
            class="absolute left-1/2 top-1/2 size-[max(100%,2.75rem)] -translate-x-1/2 -translate-y-1/2 [@media(pointer:fine)]:hidden"
            aria-hidden="true">
        </span>
        {{ $icon }}
        <span class="truncate">{{ $slot }}</span>
    </a>
</span>
