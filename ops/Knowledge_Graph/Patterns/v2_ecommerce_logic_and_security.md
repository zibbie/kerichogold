# E-commerce Logic, Security & Performance Patterns (v2)

## 1. Ochrona przed IDOR w Serwisach
Każdy serwis przyjmujący obiekt (np. `Cart`, `Order`) przekazany z warstwy klienta, MUSI walidować jego własność.

```php
protected function validateOwnership(Cart $cart) {
    if ($cart->user_id && $cart->user_id !== Auth::id()) {
        throw new AuthorizationException('Access denied.');
    }
}
```

## 2. Konsolidacja Wysyłki (Anti-Double-Charging)
Zabrania się naliczania opłaty za każdą pozycję w koszyku oddzielnie, jeśli mogą one zmieścić się w jednej paczce.

**Wzorzec:**
1. Grupuj produkty po `shipping_class`.
2. Sumuj ilości w obrębie grupy.
3. Wylicz liczbę paczek: `ceil(total_qty / items_per_package)`.

## 3. Optymalizacja Generowania Slugów
Nigdy nie używaj pętli `while(exists)` w hookach `saving`. Powoduje to serie synchronicznych zapytań (N+1).

**Zalecane rozwiązanie:**
Użyj zapytania `LIKE` z prefixem sluga i policz wystąpienia (`count()`).

```php
$count = static::where('slug', 'LIKE', "{$originalSlug}%")
    ->where('id', '!=', $model->id)
    ->count();
$model->slug = $count > 0 ? "{$originalSlug}-" . ($count + 1) : $originalSlug;
```

## 4. Wydajność Global Scope
Jeśli model posiada Global Scope wpływający na sortowanie (np. `orderBy('position')`), kolumna ta MUSI posiadać indeks w bazie danych. Brak indeksu powoduje `File Sort` i `Full Table Scan` przy każdym zapytaniu.

## 6. Optymalizacja Feedów Marketingowych (GMC)
Aby uniknąć odrzuceń w Google Merchant Center, należy stosować następujące techniki:

**A. Czyste Linki Zdjęć (Sanitized Links):**
Zabrania się wysyłania linków ze spacjami lub znakami specjalnymi. System powinien generować aliasy w formacie `products/p[ID].[ext]`, co gwarantuje poprawną indeksację przez Googlebot-Image.

**B. Fallback Wartości Krytycznych:**
Jeśli produkt nie posiada zdefiniowanej wagi, kontroler feedu MUSI wysłać wartość domyślną (np. `0.50 kg`), zamiast pustego pola, co zapobiega błędom walidacji.

**C. Obsługa Brakujących Kodów GTIN:**
Dla produktów bez fizycznego kodu EAN/GTIN, należy wymusić tag `<g:identifier_exists>no</g:identifier_exists>`. Pozwala to na uniknięcie błędów typu "Missing GTIN" w panelu GMC.
