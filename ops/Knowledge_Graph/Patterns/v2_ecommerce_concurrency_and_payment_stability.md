# E-commerce Concurrency & Payment Stability Patterns (v2)

## 1. Pessimistic Locking & State Isolation w Koszykach (Livewire)
Aby zapobiec wyścigom procesów (race conditions) w operacjach na koszykach, gdzie asynchroniczne żądania Livewire mogą się nakładać i nadpisywać stany ilościowe produktów, należy stosować **Pessimistic Locking** (`lockForUpdate()`) na poziomie bazy danych oraz operować na **względnych (relatywnych) zmianach wartości** zamiast bezwzględnych stanach przekazywanych z frontu.

**Zasada działania:**
1. Zawsze otwieraj transakcję bazodanową (`DB::transaction()`).
2. Pobieraj rekord koszyka i produktu używając `lockForUpdate()`.
3. Dokonuj inkrementacji/dekrementacji o relatywną wartość (`+1` / `-1`) zamiast przypisywania nowej wartości ze stanu komponentu Livewire.
4. Przeliczaj sumy po zatwierdzeniu blokady.

*Przykład bezpiecznej aktualizacji ilości:*
```php
public function updateQuantity(int $itemId, int $change)
{
    DB::transaction(function () use ($itemId, $change) {
        $item = CartItem::where('id', $itemId)
            ->lockForUpdate()
            ->firstOrFail();

        $newQuantity = $item->quantity + $change;

        if ($newQuantity <= 0) {
            $item->delete();
            return;
        }

        // Sprawdzenie stanu magazynowego z blokadą
        $product = Product::where('id', $item->product_id)
            ->lockForUpdate()
            ->firstOrFail();

        if ($product->quantity < $newQuantity) {
            throw new Exception('Insufficient stock');
        }

        $item->quantity = $newQuantity;
        $item->save();
    });
}
```

## 2. Obsługa Błędów Biznesowych w Livewire (Zapobieganie 500 HTTP)
Rzucanie wyjątków (np. `Insufficient stock`) bezpośrednio z serwisu bez przechwycenia ich w komponencie Livewire powoduje załamanie cyklu życia Livewire i zwrócenie błędu `500 Internal Server Error` na produkcji.

**Zasada działania:**
Metody komponentów Livewire modyfikujące stan (np. dodawanie do koszyka, zmiana ilości) MUSZĄ przechwytywać wyjątki biznesowe w bloku `try-catch`, wyłączać stany procesowania i przekazywać przyjazny komunikat o błędzie do widoku za pomocą komunikatów sesyjnych (`session()->flash()`) lub dynamicznych banerów ostrzegawczych (Alerts).

*Przykład obsługi w komponencie:*
```php
public function increment($itemId)
{
    try {
        $this->cartService->updateQuantity($itemId, 1);
    } catch (\Exception $e) {
        session()->flash('error', 'Nie można zwiększyć ilości: ' . $e->getMessage());
    }
}
```

## 3. Blokowanie Podwójnego Składania Zamówień (Double-Submit Lock)
Aby zapobiec tworzeniu zduplikowanych zamówień w bazie danych oraz wielokrotnemu rejestrowaniu transakcji w bramkach płatniczych (np. Przelewy24, Tpay) na skutek wielokrotnego kliknięcia przycisku "Kupuję i płacę" przez klienta, należy wdrożyć blokadę po stronie serwera i klienta.

**Zasada działania:**
1. Dodaj flagę procesowania w komponencie Livewire (np. `$isProcessing = false`).
2. W metodzie `placeOrder` sprawdź flagę. Jeśli jest `true`, natychmiast przerwij wykonanie.
3. Przed jakimikolwiek zapytaniami sieciowymi lub bazodanowymi ustaw flagę na `true`.
4. W widoku HTML zablokuj przycisk (`disabled`) oraz zmień jego etykietę na "Przetwarzanie..." gdy Livewire wysyła żądanie (`wire:loading.attr="disabled"`).

*Przykład blokady:*
```php
public function placeOrder()
{
    if ($this->isProcessing) {
        return;
    }
    $this->isProcessing = true;

    try {
        // Logika walidacji i zapisu zamówienia
    } catch (\Exception $e) {
        $this->isProcessing = false;
        session()->flash('error', $e->getMessage());
    }
}
```

## 4. Diagnostyka Porzuconych Zamówień i Błędów Bramki Płatniczej
Gdy rejestracja transakcji w zewnętrznej bramce płatniczej (Przelewy24/Tpay) nie powiedzie się z powodu błędów autoryzacji (np. błąd HTTP 401) lub złej konfiguracji, nowo utworzone zamówienie o statusie `pending` (BLIK) pozostaje w bazie danych. 

**Analiza i diagnoza:**
- Jeśli w bazie danych powstaje seria zamówień `pending` dla tego samego użytkownika w odstępie kilkunastu sekund (z metodami płatności online), a na końcu pojawia się udane zamówienie za pobraniem (`COD`), oznacza to awarię bramki płatniczej (np. błąd autoryzacji 401).
- Klient widzi błąd na ekranie, jego koszyk nie jest czyszczony, co prowokuje go do ponownych kliknięć (generujących kolejne zamówienia `pending`), aż w końcu wybiera pobranie (COD), które omija bramkę płatniczą i kończy proces z sukcesem.
- **Strategia diagnozy:** Zachowanie tych "osieroconych" zamówień w bazie danych jest cennym źródłem informacji diagnostycznych o błędach bramki i nie należy ich usuwać automatycznie na etapie stabilizacji systemu. W fazie produkcyjnej można rozważyć opcję usuwania zamówienia z bazy danych w bloku `catch` przy błędzie rejestracji transakcji (`$order->delete()`), aby nie zanieczyszczać panelu Filament.
