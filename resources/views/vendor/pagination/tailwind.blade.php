@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="flex flex-col sm:flex-row items-center justify-between gap-4">
        {{-- Mobile: Previous/Next --}}
        <div class="flex items-center gap-3 sm:hidden w-full">
            @if ($paginator->onFirstPage())
                <span class="flex-1 inline-flex items-center justify-center px-4 py-2.5 text-xs font-black uppercase tracking-widest bg-base-content/5 text-base-content/30 rounded-xl cursor-not-allowed">
                    <x-mary-icon name="o-chevron-left" class="w-4 h-4 mr-2" />
                    Anterior
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="flex-1 inline-flex items-center justify-center px-4 py-2.5 text-xs font-black uppercase tracking-widest bg-base-100 border border-base-content/5 text-base-content hover:bg-red-600 hover:text-white hover:border-red-600 rounded-xl transition-all duration-300">
                    <x-mary-icon name="o-chevron-left" class="w-4 h-4 mr-2" />
                    Anterior
                </a>
            @endif

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="flex-1 inline-flex items-center justify-center px-4 py-2.5 text-xs font-black uppercase tracking-widest bg-base-100 border border-base-content/5 text-base-content hover:bg-red-600 hover:text-white hover:border-red-600 rounded-xl transition-all duration-300">
                    Siguiente
                    <x-mary-icon name="o-chevron-right" class="w-4 h-4 ml-2" />
                </a>
            @else
                <span class="flex-1 inline-flex items-center justify-center px-4 py-2.5 text-xs font-black uppercase tracking-widest bg-base-content/5 text-base-content/30 rounded-xl cursor-not-allowed">
                    Siguiente
                    <x-mary-icon name="o-chevron-right" class="w-4 h-4 ml-2" />
                </span>
            @endif
        </div>

        {{-- Desktop: Full pagination --}}
        <div class="hidden sm:flex items-center justify-between w-full">
            {{-- Info --}}
            <div class="text-xs font-bold text-base-content/40">
                @if ($paginator->firstItem())
                    <span>Mostrando {{ $paginator->firstItem() }} - {{ $paginator->lastItem() }} de {{ $paginator->total() }}</span>
                @else
                    <span>{{ $paginator->count() }} resultados</span>
                @endif
            </div>

            {{-- Pages --}}
            <div class="flex items-center gap-1">
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
            </div>
        </div>
    </nav>
@endif