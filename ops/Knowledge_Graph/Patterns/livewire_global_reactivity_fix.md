# Livewire 3: Wymuszanie Globalnej Reaktywności w Komponentach Layoutu (np. Liczniki Koszyka)

## Opis Problemu
W aplikacjach e-commerce częstym problemem jest brak aktualizacji komponentów znajdujących się w głównym layoucie (takich jak licznik koszyka w nagłówku strony, `<livewire:cart-counter />`), po tym jak akcja modyfikująca stan (np. dodanie produktu) zajdzie w zagnieżdżonym komponencie głównej zawartości (np. `ProductDetail` lub `Cart`).

Użycie samego atrybutu `#[On('nazwa-eventu')]` w komponencie licznika często zawodzi, jeśli komponent znajduje się poza cyklem życia nawigacji SPA (`wire:navigate`) lub gdy zdarzenia Livewire nie propagują się poprawnie "w górę" do korzenia DOM layoutu.

## Rozwiązanie: Hybryda Alpine.js + Livewire `$refresh`

Najbardziej niezawodnym wzorcem w Livewire 3 na odświeżanie "oderwanych" komponentów layoutu jest sprzężenie nasłuchiwaczy zdarzeń globalnych (`window`) w Alpine.js z wymuszeniem odświeżenia Livewire za pomocą `$wire.$refresh()`.

### 1. Krok Pierwszy: Dispatch z komponentu modyfikującego (np. ProductCard.php)
Zdarzenie musi zostać wyemitowane globalnie przy dodawaniu/usuwaniu:
```php
public function addToCart(CartService $cartService)
{
    $cartService->addProduct($this->product->id, 1);
    
    // Livewire 3 automatycznie wysyła to jako zdarzenie przeglądarki (browser event)
    $this->dispatch('cart-updated'); 
    $this->dispatch('product-added');
}
```

### 2. Krok Drugi: Backend nasłuchującego komponentu (np. CartCounter.php)
Komponent musi odbierać nowe dane przy każdym cyklu renderowania.
```php
class CartCounter extends Component
{
    public $count = 0;

    // Atrybuty On() są przydatne jako fallback, jednak same mogą nie wystarczyć
    #[On('cart-updated')]
    #[On('product-added')]
    #[On('cart-item-removed')]
    public function updateCount(CartService $cartService)
    {
        $this->count = $cartService->getCartSummary()['item_count'];
    }

    public function render(CartService $cartService)
    {
        // Upewnienie się, że render zawsze pobiera świeże dane ze zaktualizowanej sesji
        $this->count = $cartService->getCartSummary()['item_count'];
        return view('livewire.cart-counter');
    }
}
```

### 3. Krok Trzeci (Kluczowy): Frontend nasłuchującego komponentu (cart-counter.blade.php)
Główny div komponentu musi posiadać dyrektywy `x-on` nasłuchujące na obiekcie `window`. Gdy przeglądarka przechwyci zdarzenie wysłane przez `dispatch`, Alpine.js natychmiast wywołuje `$wire.$refresh()`, zmuszając komponent Livewire do wywołania metody `render()` i pobrania najnowszych danych.

```html
<div x-on:cart-updated.window="$wire.$refresh()" x-on:product-added.window="$wire.$refresh()">
    @if($count > 0)
        <span class="badge">
            {{ $count }}
        </span>
    @endif
</div>
```

## Case Study: Synchronizacja Strony Zamówienia (Checkout)
**Problem**: Użytkownik na stronie `/checkout` widzi sumę np. 100 zł. Otwiera boczny koszyk (sidebar), usuwa produkt, boczny koszyk pokazuje 0 zł, ale strona zamówienia nadal uparcie wyświetla 100 zł.

**Rozwiązanie**: Komponent `Checkout` musi jawnie nasłuchiwać na zdarzenie `cart-updated` i wymuszać przeliczenie metod dostawy oraz sum:

```php
#[On('cart-updated')]
public function refreshCart(CartService $cartService)
{
    // Jeśli koszyk stał się pusty - przekieruj na stronę główną
    if ($cartService->getCart()->items->isEmpty()) {
        return redirect('/');
    }
    
    // Odśwież metody wysyłki i sumy
    $this->updateShippingMethods($cartService);
}
```

## Konkluzja
Wzorzec ten gwarantuje 100% niezawodność przy aktualizacji wskaźników UI w nagłówkach, stopkach oraz na krytycznych etapach ścieżki zakupowej, całkowicie omijając problemy z "gubieniem" zdarzeń na styku drzew zagnieżdżeń komponentów Livewire.
