<div x-on:cart-updated.window="$wire.$refresh()" x-on:product-added.window="$wire.$refresh()">
    @if($count > 0)
        <span class="absolute -top-1 -right-1 bg-sage-600 text-white text-[10px] font-heading font-bold rounded-full h-5 w-5 flex items-center justify-center shadow-sm">
            {{ $count }}
        </span>
    @endif
</div>
