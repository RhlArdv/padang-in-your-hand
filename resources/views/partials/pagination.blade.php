@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" class="flex flex-col items-center gap-6">
        
        {{-- Desktop View (Full Pagination) --}}
        <div class="flex flex-col md:flex-row items-center justify-between w-full gap-4">
            <div class="order-2 md:order-1">
                <p class="text-xs font-bold text-navy-500 uppercase tracking-widest">
                    Menampilkan
                    <span class="text-navy-900">{{ $paginator->firstItem() }}</span>
                    -
                    <span class="text-navy-900">{{ $paginator->lastItem() }}</span>
                    dari
                    <span class="text-navy-900">{{ $paginator->total() }}</span>
                </p>
            </div>

            <div class="order-1 md:order-2">
                <ul class="flex items-center gap-2">
                    {{-- Previous Page Link --}}
                    @if ($paginator->onFirstPage())
                        <li aria-disabled="true" aria-label="&laquo; Sebelumnya">
                            <span class="w-10 h-10 flex items-center justify-center rounded-xl bg-navy-50 text-navy-300 cursor-not-allowed border border-navy-100" aria-hidden="true">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"></path></svg>
                            </span>
                        </li>
                    @else
                        <li>
                            <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="w-10 h-10 flex items-center justify-center rounded-xl bg-white text-navy-700 border border-navy-100 hover:border-gold-400 hover:text-gold-600 hover:bg-gold-50 shadow-sm transition-all duration-300 active:scale-90" aria-label="&laquo; Sebelumnya">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"></path></svg>
                            </a>
                        </li>
                    @endif

                    {{-- Pagination Elements --}}
                    @foreach ($elements as $element)
                        {{-- "Three Dots" Separator --}}
                        @if (is_string($element))
                            <li aria-disabled="true">
                                <span class="w-10 h-10 flex items-center justify-center text-navy-400 text-sm font-black">{{ $element }}</span>
                            </li>
                        @endif

                        {{-- Array Of Links --}}
                        @if (is_array($element))
                            @foreach ($element as $page => $url)
                                @if ($page == $paginator->currentPage())
                                    <li aria-current="page">
                                        <span class="w-10 h-10 flex items-center justify-center rounded-xl bg-navy-900 text-white font-black text-sm shadow-[0_8px_16px_rgba(15,36,64,0.2)]">{{ $page }}</span>
                                    </li>
                                @else
                                    <li>
                                        <a href="{{ $url }}" class="w-10 h-10 flex items-center justify-center rounded-xl bg-white text-navy-700 border border-navy-100 hover:border-gold-400 hover:text-gold-600 hover:bg-gold-50 shadow-sm font-bold text-sm transition-all duration-300 active:scale-90">{{ $page }}</a>
                                    </li>
                                @endif
                            @endforeach
                        @endif
                    @endforeach

                    {{-- Next Page Link --}}
                    @if ($paginator->hasMorePages())
                        <li>
                            <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="w-10 h-10 flex items-center justify-center rounded-xl bg-white text-navy-700 border border-navy-100 hover:border-gold-400 hover:text-gold-600 hover:bg-gold-50 shadow-sm transition-all duration-300 active:scale-90" aria-label="Selanjutnya &raquo;">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path></svg>
                            </a>
                        </li>
                    @else
                        <li aria-disabled="true" aria-label="Selanjutnya &raquo;">
                            <span class="w-10 h-10 flex items-center justify-center rounded-xl bg-navy-50 text-navy-300 cursor-not-allowed border border-navy-100" aria-hidden="true">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path></svg>
                            </span>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>
@endif
