@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="flex flex-col items-center gap-6 mt-12">
        {{-- Mobile Navigation --}}
        <div class="flex justify-between w-full md:hidden">
            @if ($paginator->onFirstPage())
                <span class="px-4 py-2 text-sm font-medium text-charcoal-900/30 bg-white border border-oatmeal-200 rounded-xl cursor-default">
                    {!! __('pagination.previous') !!}
                </span>
            @else
                <button wire:click="previousPage" wire:loading.attr="disabled" wire:key="paginator-prev-mobile" rel="prev" class="px-4 py-2 text-sm font-medium text-charcoal-900 bg-white border border-oatmeal-200 rounded-xl hover:bg-sage-50 transition-colors shadow-sm cursor-pointer">
                    {!! __('pagination.previous') !!}
                </button>
            @endif

            @if ($paginator->hasMorePages())
                <button wire:click="nextPage" wire:loading.attr="disabled" wire:key="paginator-next-mobile" rel="next" class="px-4 py-2 text-sm font-medium text-charcoal-900 bg-white border border-oatmeal-200 rounded-xl hover:bg-sage-50 transition-colors shadow-sm cursor-pointer">
                    {!! __('pagination.next') !!}
                </button>
            @else
                <span class="px-4 py-2 text-sm font-medium text-charcoal-900/30 bg-white border border-oatmeal-200 rounded-xl cursor-default">
                    {!! __('pagination.next') !!}
                </span>
            @endif
        </div>

        {{-- Desktop Navigation --}}
        <div class="hidden md:flex md:items-center md:justify-between w-full">
            <div>
                <p class="text-sm text-charcoal-900/60 font-heading">
                    {!! __('pagination.showing') !!}
                    <span class="font-bold text-charcoal-900">{{ $paginator->firstItem() }}</span>
                    -
                    <span class="font-bold text-charcoal-900">{{ $paginator->lastItem() }}</span>
                    {!! __('pagination.of') !!}
                    <span class="font-bold text-charcoal-900">{{ $paginator->total() }}</span>
                    {!! __('pagination.results') !!}
                </p>
            </div>

            <div>
                <span class="relative z-0 inline-flex shadow-sm rounded-xl overflow-hidden border border-oatmeal-200">
                    {{-- Previous Page Link --}}
                    @if ($paginator->onFirstPage())
                        <span aria-disabled="true" aria-label="{{ __('pagination.previous') }}">
                            <span class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-charcoal-900/20 bg-white cursor-default" aria-hidden="true">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                            </span>
                        </span>
                    @else
                        <button wire:click="previousPage" wire:key="paginator-prev-desktop" rel="prev" class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-charcoal-900 bg-white hover:bg-sage-50 transition-colors focus:z-10 focus:outline-none focus:ring-1 focus:ring-sage-500 cursor-pointer" aria-label="{{ __('pagination.previous') }}">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    @endif

                    {{-- Pagination Elements --}}
                    @foreach ($elements as $element)
                        {{-- "Three Dots" Separator --}}
                        @if (is_string($element))
                            <span aria-disabled="true">
                                <span class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-charcoal-900/40 bg-white cursor-default">{{ $element }}</span>
                            </span>
                        @endif

                        {{-- Array Of Links --}}
                        @if (is_array($element))
                            @foreach ($element as $page => $url)
                                @if ($page == $paginator->currentPage())
                                    <span aria-current="page" wire:key="paginator-page-{{ $page }}">
                                        <span class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-bold text-white bg-sage-600 cursor-default">{{ $page }}</span>
                                    </span>
                                @else
                                    <button wire:click="gotoPage({{ $page }})" wire:key="paginator-page-{{ $page }}" class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-charcoal-900 bg-white hover:bg-sage-50 transition-colors focus:z-10 focus:outline-none focus:ring-1 focus:ring-sage-500 cursor-pointer" aria-label="{{ __('Go to page :page', ['page' => $page]) }}">
                                        {{ $page }}
                                    </button>
                                @endif
                            @endforeach
                        @endif
                    @endforeach

                    {{-- Next Page Link --}}
                    @if ($paginator->hasMorePages())
                        <button wire:click="nextPage" wire:key="paginator-next-desktop" rel="next" class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-charcoal-900 bg-white hover:bg-sage-50 transition-colors focus:z-10 focus:outline-none focus:ring-1 focus:ring-sage-500 cursor-pointer" aria-label="{{ __('pagination.next') }}">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    @else
                        <span aria-disabled="true" aria-label="{{ __('pagination.next') }}">
                            <span class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-charcoal-900/20 bg-white cursor-default" aria-hidden="true">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                </svg>
                            </span>
                        </span>
                    @endif
                </span>
            </div>
        </div>
    </nav>
@endif
