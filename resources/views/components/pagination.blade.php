@props(['paginator'])

@if($paginator->hasPages())
<div class="mt-6 flex items-center justify-center">
    {{-- <div class="text-sm text-gray-500">
        Showing {{ $paginator->firstItem() }} to {{ $paginator->lastItem() }} of {{ $paginator->total() }} results
    </div> --}}
    <div class="flex space-x-2">
        @if ($paginator->onFirstPage())
        <span
            class="flex px-2 py-1 rounded border border-zinc-400 text-zinc-400 cursor-not-allowed items-center justify-center text-center">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="w-5 h-5">
                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                <path d="M15 6l-6 6l6 6" />
            </svg>
        </span>
        @else
        <a href="{{ $paginator->previousPageUrl() }}"
            class="flex px-2 py-1 rounded border border-zinc-900 text-zinc-900 items-center justify-center text-center hover:bg-zinc-50 transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="w-5 h-5">
                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                <path d="M15 6l-6 6l6 6" />
            </svg>
        </a>
        @endif

        @foreach ($paginator->getUrlRange(1, $paginator->lastPage()) as $page => $url)
        @if ($page == $paginator->currentPage())
        <span
            class="w-10 h-10 flex items-center justify-center rounded bg-zinc-900 text-white font-semibold">
            {{ $page }}
        </span>
        @else
        <a href="{{ $url }}"
            class="w-10 h-10 flex items-center justify-center rounded border border-zinc-900 text-zinc-900 hover:text-white hover:bg-zinc-900 transition">
            {{ $page }}
        </a>
        @endif
        @endforeach

        @if ($paginator->hasMorePages())
        <a href="{{ $paginator->nextPageUrl() }}"
            class="flex px-2 py-1 rounded border border-zinc-900 text-zinc-900 items-center justify-center text-center hover:bg-zinc-50 transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5">
                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                <path d="M9 6l6 6l-6 6" />
            </svg>
        </a>
        @else
        <span
            class="flex px-2 py-1 rounded border border-zinc-400 text-zinc-400 cursor-not-allowed items-center justify-center text-center">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5">
                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                <path d="M9 6l6 6l-6 6" />
            </svg>
        </span>
        @endif
    </div>
</div>
@endif
