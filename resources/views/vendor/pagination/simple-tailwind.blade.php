@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" class="flex justify-between items-center mt-8">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <span class="px-4 py-2 text-sm font-medium text-charcoal-900/30 bg-white border border-oatmeal-200 rounded-xl cursor-default">
                {!! __('pagination.previous') !!}
            </span>
        @else
            <button wire:click="previousPage" wire:loading.attr="disabled" rel="prev" class="px-4 py-2 text-sm font-medium text-charcoal-900 bg-white border border-oatmeal-200 rounded-xl hover:bg-sage-50 transition-colors shadow-sm">
                {!! __('pagination.previous') !!}
            </button>
        @endif

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <button wire:click="nextPage" wire:loading.attr="disabled" rel="next" class="px-4 py-2 text-sm font-medium text-charcoal-900 bg-white border border-oatmeal-200 rounded-xl hover:bg-sage-50 transition-colors shadow-sm">
                {!! __('pagination.next') !!}
            </button>
        @else
            <span class="px-4 py-2 text-sm font-medium text-charcoal-900/30 bg-white border border-oatmeal-200 rounded-xl cursor-default">
                {!! __('pagination.next') !!}
            </span>
        @endif
    </nav>
@endif
