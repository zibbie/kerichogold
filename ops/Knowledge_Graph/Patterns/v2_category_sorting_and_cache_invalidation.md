# Wzorzec "Category Sorting and Cache Invalidation" (Filament & Front-end Cache Sync)

Ten dokument opisuje wzorzec i praktyki radzenia sobie z problemami kolejności (sorting) elementów i ich synchronizacji z agresywnym cachowaniem danych w Laravel + Filament.

## 1. Opis Problemu (Problem Statement)
Gdy wdrażamy funkcjonalność przeciągania i sortowania w panelu administracyjnym (np. sortowanie kategorii, produktów za pomocą `reorderable()` w Filament), a na froncie strony korzystamy z cachowania (np. `Cache::remember`), pojawiają się dwa problemy:
1. **Pominięcie zdarzeń modelu (Eloquent Events Bypass):** Filament przy reorderowaniu tabeli wykonuje zoptymalizowane zapytania SQL bezpośrednio na bazie (np. `UPDATE ... CASE WHEN ...`), co oznacza, że zdarzenia modelu Eloquent (`saved`, `updated`, `saving`) NIE są wywoływane. Przez to standardowe hooki czyszczące cache w modelach nie zostaną uruchomione.
2. **Brak jawnego sortowania w zapytaniach:** Laravel/Eloquent nie narzuca domyślnego sortowania w relacjach ani zapytaniach. Brak jawnego `orderBy('position', 'asc')` w relacji `children()` oraz we wszystkich zapytaniach typu `Category::all()` powoduje, że baza danych zwraca wyniki w kolejności fizycznego zapisu, ignorując wprowadzone zmiany pozycji.

## 2. Rozwiązanie (Pattern Architecture)

### Krok A: Jawne sortowanie w relacjach i zapytaniach
1. W modelu definiującym relację jeden-do-wielu (lub wiele-do-wielu), która ma być posortowana, zawsze zdefiniuj porządek:
   ```php
   public function children()
   {
       return $this->hasMany(Category::class, 'parent_id')->orderBy('position', 'asc');
   }
   ```
2. W kontrolerach, komponentach Livewire i Service Providerach pobierających kolekcję, zawsze dopisz jawne sortowanie:
   ```php
   $categories = Category::where('status', true)->whereNull('parent_id')->orderBy('position', 'asc')->get();
   ```

### Krok B: Automatyczne czyszczenie Cache na poziomie modeli (Eloquent Observers)
Dla standardowych operacji CRUD (dodanie nowego elementu, edycja formularza, usunięcie), w metodzie `boot()` modeli powiązanych z cache zarejestruj czyszczenie klucza:
```php
protected static function boot()
{
    parent::boot();

    static::saved(function () {
        \Illuminate\Support\Facades\Cache::forget('global_view_data');
    });

    static::deleted(function () {
        \Illuminate\Support\Facades\Cache::forget('global_view_data');
    });
}
```

### Krok C: Ręczne czyszczenie Cache w widoku reorderowania Filament
Ponieważ przeciąganie w tabeli Filament nie wyzwoli powyższych zdarzeń modelu, należy przeciążyć lub rozszerzyć metodę `reorderTable` na odpowiedniej stronie panelu administracyjnego (np. `ManageCategories` lub `ListCategories`), aby wyczyścić cache po transakcji:
```php
public function reorderTable(array $order): void
{
    // Wykonanie zapisu pozycji
    DB::transaction(function () use ($order) {
        // ... standardowa logika Filament lub własny statement SQL
    });

    // Kluczowy moment: Wymuszenie czyszczenia cache
    \Illuminate\Support\Facades\Cache::forget('global_view_data');
}
```

## 3. Korzyści (Key Benefits)
- **100% Spójności:** Zmiana kolejności przeciąganiem w adminie jest widoczna natychmiast na stronie głównej dla klienta, bez konieczności czekania na wygaśnięcie TTL pamięci podręcznej (np. 1 godzina).
- **Zoptymalizowana Wydajność:** Zapytania bazy danych korzystają z indeksów na kolumnie `position` i sortują dane spójnie, zapobiegając fluktuacjom układu strony (CLS).
