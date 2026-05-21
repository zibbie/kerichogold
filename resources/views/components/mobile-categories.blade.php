@props(['categories' => null, 'activeCategory' => null, 'activeCategoryId' => null])

@php
    $categories = $categories ?? $nav_categories ?? collect();
    $activeCategory = $activeCategory ?? ($activeCategoryId ? $categories->firstWhere('id', $activeCategoryId) : null);
@endphp

<div class="md:hidden pt-4 mb-0 w-full overflow-x-hidden" wire:ignore>
    <style>
        @keyframes bounce-horizontal {
            0%, 100% { transform: translateX(0); }
            50% { transform: translateX(4px); }
        }
        .animate-bounce-horizontal {
            animation: bounce-horizontal 0.8s ease-in-out infinite;
        }
    </style>
    
    <!-- Section Title with PRZESUŃ suggestion -->
    <div class="flex justify-between items-center mb-4 px-4">
        <h2 class="text-xs font-heading font-bold text-charcoal-900/40 uppercase tracking-widest">Kategorie</h2>
        <span class="text-[9px] font-heading font-bold text-sage-600/70 uppercase tracking-wider animate-pulse flex items-center gap-1">
            Przesuń <span class="material-symbols-outlined text-[10px] animate-bounce-horizontal leading-none" aria-hidden="true">arrow_forward</span>
        </span>
    </div>

    <div class="relative w-full">
        <!-- Subtle Edge Gradients -->
        <div class="absolute left-0 top-0 bottom-0 w-8 bg-gradient-to-r from-oatmeal-100 to-transparent pointer-events-none z-10"></div>
        <div class="absolute right-0 top-0 bottom-0 w-8 bg-gradient-to-l from-oatmeal-100 to-transparent pointer-events-none z-10"></div>
        
        <nav class="flex flex-row gap-2 overflow-x-auto scrollbar-hide px-4 relative z-0">
            @foreach($categories as $cat)
            @php
                $isActive = $activeCategory && $activeCategory->id === $cat->id;
            @endphp
            <a href="{{ route('category.details', $cat->slug) }}#listing-content" 
               onclick="this.classList.add('opacity-50', 'pointer-events-none'); document.body.style.cursor='wait';"
               class="flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs font-heading font-bold whitespace-nowrap transition-all shrink-0 {{ $isActive ? 'bg-sage-600 text-white shadow-lg' : 'text-charcoal-900/70 bg-white border border-oatmeal-200' }}">
                <span class="material-symbols-outlined text-sm {{ $isActive ? 'text-white' : 'text-sage-600' }}" aria-hidden="true" data-icon="{{ $cat->icon ?? 'potted_plant' }}"></span>
                <span>{{ $cat->name }}</span>
            </a>
            @endforeach
        </nav>
    </div>
</div>

