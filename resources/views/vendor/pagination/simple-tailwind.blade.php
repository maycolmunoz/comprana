@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="flex items-center justify-center gap-2">
        {{-- Previous --}}
        @if ($paginator->onFirstPage())
            <span class="w-10 h-10 flex items-center justify-center rounded-xl bg-base-content/5 text-base-content/30 cursor-not-allowed">
                <x-mary-icon name="o-chevron-left" class="w-4 h-4" />
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="w-10 h-10 flex items-center justify-center rounded-xl bg-base-100 border border-base-content/5 text-base-content/60 hover:bg-red-600 hover:text-white hover:border-red-600 transition-all duration-300">
                <x-mary-icon name="o-chevron-left" class="w-4 h-4" />
            </a>
        @endif

        {{-- Numbered pages --}}
        @foreach ($elements as $element)
            @if (is_string($element))
                <span class="w-10 h-10 flex items-center justify-center text-xs font-bold text-base-content/30">
                    {{ $element }}
                </span>
            @endif

            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="w-10 h-10 flex items-center justify-center rounded-xl bg-red-600 text-white text-xs font-black uppercase tracking-widest shadow-lg shadow-red-600/20">
                            {{ $page }}
                        </span>
                    @else
                        <a href="{{ $url }}" class="w-10 h-10 flex items-center justify-center rounded-xl bg-base-100 border border-base-content/5 text-base-content/60 text-xs font-bold hover:bg-red-600 hover:text-white hover:border-red-600 transition-all duration-300">
                            {{ $page }}
                        </a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="w-10 h-10 flex items-center justify-center rounded-xl bg-base-100 border border-base-content/5 text-base-content/60 hover:bg-red-600 hover:text-white hover:border-red-600 transition-all duration-300">
                <x-mary-icon name="o-chevron-right" class="w-4 h-4" />
            </a>
        @else
            <span class="w-10 h-10 flex items-center justify-center rounded-xl bg-base-content/5 text-base-content/30 cursor-not-allowed">
                <x-mary-icon name="o-chevron-right" class="w-4 h-4" />
            </span>
        @endif
    </nav>
@endif