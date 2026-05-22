# Wzorce Rozwiązań (Kericho Gold / Laravel 11)

Ten dokument gromadzi "trajektorie sukcesu" wypracowane podczas budowy i stabilizacji nowego sklepu opartego na Laravel.

## 1. Wzorzec "Eloquent Switch"
**Problem**: Błędy autentykacji po migracji bazy danych ("Credentials do not match").
**Rozwiązanie**: 
1. Upewnij się, że w `config/auth.php` driver dla providera `users` jest ustawiony na `eloquent` (nie `legacy` lub `database`).
2. Sprawdź, czy model `User` używa rzutowania `hashed` dla hasła:
   ```php
   protected $casts = [
       'password' => 'hashed',
   ];
   ```

## 2. Wzorzec "Double Hashing Prevention" (Filament)
**Problem**: Hasło zmieniane w panelu Filament nie pozwala na zalogowanie.
**Rozwiązanie**: 
- Jeśli model `User` ma cast `'password' => 'hashed'`, Filament automatycznie zahashuje hasło przy zapisie.
- W `UserResource` NIE używaj manualnego `Hash::make()` w `dehydrate` lub `mutateFormDataBeforeSave`, bo spowoduje to podwójne zahashowanie.

## 3. Wzorzec "Production CSS Masking"
**Problem**: Elementy UI (np. przyciski) widoczne lokalnie, ale niewidoczne na produkcji mimo braku błędów w konsoli.
**Rozwiązanie**: 
- Niektóre style Tailwind mogą być nadpisywane przez globalne style lub mechanizmy optymalizacji CSS.
- Stosuj klasy `!important` lub inline styles dla krytycznych przycisków akcji (np. "Finalizuj zamówienie"), aby wymusić widoczność.

## 4. Wzorzec "Docker Permission Jail"
**Problem**: Laravel nie może zapisać logów lub plików cache wewnątrz kontenera.
**Rozwiązanie**: 
- Upewnij się, że foldery `storage` i `bootstrap/cache` mają uprawnienia zapisu dla użytkownika `www-data`.
- Komenda naprawcza na VPS:
  ```bash
  docker exec -u root kericho-app chown -R www-data:www-data storage bootstrap/cache
  ```

## 5. Wzorzec "Case-Sensitive Routing"
**Problem**: Linki działają lokalnie (macOS/Windows), ale rzucają 404 na produkcji (Linux).
**Rozwiązanie**: 
- Linux rozróżnia wielkość liter. Sprawdź, czy nazwy widoków w `return view('path.to.View')` dokładnie odpowiadają nazwom plików `.blade.php`.
- Zawsze używaj `snake_case` dla plików widoków i `PascalCase` dla komponentów Livewire.

## 6. Wzorzec "Vite Asset Compilation on VPS"
**Problem**: Zmiany w CSS (np. Tailwind v4) nie są widoczne na produkcji mimo `git pull`.
**Rozwiązanie**: 
- Po każdej zmianie w `app.css` lub dodaniu nowych klas Tailwind, należy przebudować assets na serwerze:
  ```bash
  docker compose exec app npm run build
  ```
- Sam `php artisan optimize` czyści tylko cache Laravela, nie rekompiluje plików Vite.

## 7. Wzorzec "Reliable Nginx Upstream"
**Problem**: Kontener `web` (Nginx) nie wstaje po restarcie całego stosu (`host not found in upstream`).
**Rozwiązanie**: 
- W konfiguracji Nginx (`fastcgi_pass`) używaj nazwy **usługi** (service name) z `docker-compose.yml` (np. `app`), a nie nazwy kontenera (`v2-app`). 
- Docker Compose gwarantuje, że nazwa usługi jest zawsze rozwiązywalna wewnątrz sieci.

## 8. Wzorzec "Livewire Home Routing"
**Problem**: Edycja `welcome.blade.php` nie zmienia strony głównej.
**Rozwiązanie**: 
- Sprawdź `routes/web.php`. Jeśli `/` wskazuje na komponent Livewire (`Route::get('/', Home::class)`), to właściwym widokiem jest `resources/views/livewire/home.blade.php`.
- `welcome.blade.php` jest domyślnym widokiem Laravela, ale Livewire Full-Page Components mają własne renderowanie.

## 9. Wzorzec "Horizontal Scroll Suppression"
**Problem**: Strona "rozkracza się" (poziomy suwak) na urządzeniach mobilnych lub przy specyficznych układach Grid/Flex.
**Rozwiązanie**: 
- W głównym pliku CSS (`app.css`) dodaj globalne zabezpieczenie:
  ```css
  html, body { overflow-x: hidden; width: 100%; position: relative; }
  ```
- Dla kontenerów głównych używaj `width: 100%` obok `max-width` oraz klasy `min-w-0` dla elementów `flex-1`, aby zapobiec ich wypychaniu przez treść.

## 10. Wzorzec "Gross Price Tax Extraction"
**Problem**: Podatek VAT doliczany do sumy produktów, które są już cenami brutto (podwójne opodatkowanie).
**Rozwiązanie**: 
- Zdefiniuj jawnie w modelu `Cart`, że `subtotal` zawiera VAT.
- Wyliczaj `tax_total` metodą "w stu": `$tax = $subtotal - ($subtotal / 1.23)`.
- Suma końcowa (`total`) powinna ignorować zmienną `tax_total`, aby nie doliczać jej powtórnie.

## 11. Wzorzec "Webhook URL Service-Route Sync"
**Problem**: Webhooki (np. Przelewy24) zwracają 404, bo serwis generuje adresy niepasujące do `routes/api.php` (np. dodaje segment `/p24/`).
**Rozwiązanie**: 
- Sprawdź metodę `registerTransaction` w serwisie płatności. Adres `urlStatus` musi być identyczny z tym zdefiniowanym w ruterze.
- Unikaj "magicznego" dodawania segmentów przez biblioteki zewnętrzne – zawsze buduj pełny URL w oparciu o `config('app.url')`.

## 12. Wzorzec "Order Property Persistence"
**Problem**: Wybrane opcje zamówienia (np. `payment_method`) są puste w bazie danych mimo poprawnego wyboru w UI.
**Rozwiązanie**: 
- W metodzie `convertToOrder` (CartService) upewnij się, że zmienne przekazane jako argumenty są faktycznie przypisywane do tablicy `$orderData`.
- Nigdy nie polegaj wyłącznie na wartościach domyślnych argumentów funkcji, jeśli dane mają trafić do bazy.

## 13. Wzorzec "Consolidated Shipping Calculation" (Double-Charging Prevention)
**Problem**: Klient jest obciążany za każdą pozycję w koszyku oddzielnie, co drastycznie zawyża koszty wysyłki.
**Rozwiązanie**: 
- Grupuj produkty według `shipping_class` przed obliczeniem kosztów.
- Sumuj ilości (`quantity`) w obrębie tej samej klasy.
- Wylicz liczbę paczek: `ceil(total_quantity / items_per_package)`.
- Akcesoria (np. drobne części) powinny być liczone jako stała opłata za grupę (max), a nie za sztukę.

## 14. Wzorzec "Performance-Ready Slug Generation"
**Problem**: Generowanie slugów w pętli `while(exists)` powoduje wiele zapytań synchronicznych przy zapisie (N+1).
**Rozwiązanie**: 
- Użyj zapytania `LIKE` z prefixem sluga i wykonaj `count()`.
- Nowy slug: `test-slug` + `(count + 1)`.
- Zapewnia to stałą liczbę zapytań (1) niezależnie od liczby duplikatów.

## 15. Wzorzec "Service Layer IDOR Defense"
**Problem**: Możliwość manipulacji ID obiektu (np. `cart_id`) w żądaniach API/Livewire pozwalająca na dostęp do danych innych użytkowników.
**Rozwiązanie**: 
- W każdym serwisie przyjmującym model z zewnątrz (np. `ShippingService`), zaimplementuj metodę `validateOwnership`.
- Sprawdzaj: `if ($model->user_id && $model->user_id !== Auth::id()) throw new AuthorizationException;`.
- Pamiętaj o obsłudze koszyków gości (np. via session ID).

## 16. Wzorzec "Legacy Password Migration"
**Problem**: Użytkownicy ze starej platformy (v1) mają hasła zahashowane w formacie MD5 (często z solą), których nie da się bezpośrednio zweryfikować przez `Hash::check()`.
**Rozwiązanie**: 
- Dodaj kolumny `legacy_password` i `legacy_salt` do tabeli `users`.
- W mechanizmie logowania (np. `Fortify::authenticateUsing`), jeśli standardowy `Hash::check()` zawiedzie:
    1. Sprawdź, czy `legacy_password` jest wypełnione.
    2. Zweryfikuj hasło manualnie: `md5($salt . $password) === $legacy_password`.
    3. Jeśli zgodne: zaktualizuj `password` używając `Hash::make()`, wyczyść pola legacy i zaloguj użytkownika.

## 17. Wzorzec "Secure Webhooks (JWS Signature)"
**Problem**: Atakujący może sfingować powiadomienie o płatności (np. Tpay), wysyłając fałszywy POST na endpoint webhooka.
**Rozwiązanie**: 
- Zrezygnuj z weryfikacji MD5 na rzecz JWS (JSON Web Signature).
- Użyj biblioteki `firebase/php-jwt` do walidacji nagłówka `X-Tpay-Signature`.
- Klucz publiczny pobieraj automatycznie z endpointu JWKS dostawcy i cachuj lokalnie.
- Nigdy nie ufaj danym z `$_POST` przed pełną walidacją kryptograficzną sygnatury.

## 18. Wzorzec "Mobile Sidebar Cleanup"
**Problem**: Puste zaokrąglone ramki (kontenery `aside`) widoczne na telefonach mimo braku treści (treść przeniesiona do horyzontalnego scrolla).
**Rozwiązanie**: 
- Zawsze ukrywaj główny kontener `aside` na Mobile używając klas `hidden md:block`.
- Nowoczesna nawigacja kategorii na Mobile powinna być osobnym modułem (komponentem) umieszczonym powyżej głównego kontenera treści (`container-custom`), aby zapewnić jej pełną szerokość i czystość wizualną.

## 19. Wzorzec "Filament Array Property Binding" (Zapobieganie "[object Object]")
**Problem**: Rzutowanie kolumny bazy danych jako `array` lub `json` powoduje, że Filament przy próbie powiązania jej bezpośrednio z polem typu `Textarea` lub `TextInput` przekazuje tablicę PHP jako obiekt JavaScript, co skutkuje wyświetleniem tekstu `"[object Object]"` w formularzu i ryzykiem nadpisania danych w bazie przy zapisie.
**Rozwiązanie**:
- Użyj metod `formatStateUsing()` oraz `dehydrateStateUsing()` na komponencie formularza, aby kontrolować konwersję między formatem bazodanowym (tablica) a prezentacyjnym (tekst).
- Przykład:
  ```php
  Forms\Components\Textarea::make('shipping_address')
      ->label('Adres dostawy')
      ->rows(3)
      ->formatStateUsing(fn ($state) => is_array($state) ? ($state['address'] ?? '') : $state)
      ->dehydrateStateUsing(function ($state, $record, Forms\Get $get) {
          $oldAddress = is_array($record?->shipping_address) ? $record->shipping_address : [];
          return array_merge($oldAddress, [
              'address' => $state,
              'name' => $get('name'),
              'city' => $get('city'),
              'zip' => $get('zip'),
              'phone' => $get('phone'),
              'email' => $get('email'),
          ]);
      })
  ```

## 20. Wzorzec "COD Fee Server-Side Calculation Sync"
**Problem**: Klient podczas checkoutu wybiera płatność przy odbiorze (COD), a frontend/koszyk prawidłowo dolicza opłatę pobraniową (np. +5 zł) do kosztów wysyłki i sumy. Jednak podczas konwersji koszyka na zamówienie (`convertToOrder()`), system recalculuje koszty wysyłki na serwerze (aby zapobiec manipulacji cenami), co resetuje opłatę pobraniową do podstawowej stawki, przez co zamówienie w bazie danych ma zaniżoną sumę.
**Rozwiązanie**:
- W metodzie konwersji koszyka na zamówienie (`CartService->convertToOrder()`), po pobraniu bazowego kosztu wysyłki z `ShippingService`, należy sprawdzić wybraną metodę płatności i jeśli wynosi `COD`, dodać dynamicznie pobraną opłatę pobraniową ze słownika ustawień (`Setting::get('cod_fee')`).
- Przykład:
  ```php
  $shippingCost = $shippingService->calculate($cart, $cart->shipping_method ?? 'courier');
  
  if ($paymentMethod === 'COD') {
      $shippingCost += (float) \App\Models\Setting::get('cod_fee', 10.00);
  }
  ```

## 21. Wzorzec "Material Symbols Font-FOUT and CLS Prevention"
**Problem**: Ikony oparte na czcionkach ligaturowych (np. Google Material Symbols Outlined) podczas ładowania strony lub odświeżenia wyświetlają surowy tekst (np. "shopping_cart", "menu", "close"), co wygląda dla użytkownika jak chwilowy błąd kodu strony (FOUT - Flash of Unstyled Text) i wywołuje skoki układu (CLS - Content Layout Shift).
**Rozwiązanie**:
1. Zmień parametr ładowania fontu w adresie Google Fonts z `display=swap` (który pozwala na natychmiastowe pokazanie tekstu zastępczego) na `display=block` (który ukrywa tekst dopóki czcionka nie zostanie pobrana):
   ```html
   <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=block" rel="stylesheet"/>
   ```
2. Zdefiniuj w głównym arkuszu stylów CSS sztywne, ograniczone gabaryty dla klasy ikon z `overflow: hidden`, co zapobiega rozciąganiu przycisków przez surowy tekst przed załadowaniem fontu:
   ```css
    .material-symbols-outlined {
        font-size: 24px;
        width: 24px;
        height: 24px;
        display: inline-block;
        overflow: hidden;
        vertical-align: middle;
        white-space: nowrap;
    }
    ```

## 22. Wzorzec "Ligature Icon Safari Reader Mode (a11y) Bypass"
**Problem**: Narzędzia ułatwień dostępu (screen readery) oraz automatyczne tryby czytnika (np. Safari Reader Mode pod skrótem CMD+SHIFT+R) ignorują style CSS i wyciągają bezpośrednią treść tekstową węzłów DOM. W przypadku ikon opartych na ligaturach (np. Material Symbols), skutkuje to czytaniem i wyświetlaniem surowych nazw ikon (np. `category Kategorie`, `inventory_2 Skrzynki magazynowe`) jako zwykłego tekstu, psując doświadczenie użytkownika.
**Rozwiązanie**:
- Do każdego znacznika `<span>` renderującego ikonę za pomocą ligatur tekstowych bezwzględnie dodaj atrybut `aria-hidden="true"`.
- Atrybut ten instruuje silnik ułatwień dostępu (oraz parser trybu czytnika w Safari), że element jest czysto dekoracyjny i należy go całkowicie pominąć podczas ekstrakcji tekstu.
- Przykład:
  ```html
  <span class="material-symbols-outlined" aria-hidden="true">shopping_cart</span>
  ```

## 23. Wzorzec "Livewire Relative Quantity Updates (Race Condition Prevention)"
**Problem**: Przycisk zmiany ilości w koszyku przesyła na sztywno nowo wyliczoną w Blade ilość (np. `wire:click="updateQuantity(ID, {{ $quantity + 1 }})"`). Przy szybkim kliknięciu (np. 5 razy), zapytania asynchroniczne wysyłają do serwera tę samą wartość (np. "2") zanim zaktualizowany stan wróci do klienta. Użytkownik nie może podbić ilości powyżej tej liczby.
**Rozwiązanie**:
- Zaimplementuj dedykowane metody serwerowe `incrementQuantity($itemId)` oraz `decrementQuantity($itemId)` w komponencie Livewire.
- Metody te powinny pobierać aktualną ilość z bazy danych / sesji koszyka w momencie wywołania żądania i dodawać/odejmować `1`.
- W widoku Blade odwołuj się do tych metod bez przekazywania wyliczonej na froncie ilości:
  ```html
  <button wire:click="incrementQuantity('{{ $item->id }}')">+</button>
  ```

## 24. Wzorzec "Chrome DevTools Allow Pasting"
**Problem**: Konsola Chrome blokuje wklejanie kodu (`Ctrl+V`/`Cmd+V` nie działa).
**Rozwiązanie**: Wklej w konsoli kod `allow pasting` (bez cudzysłowu), który tymczasowo odblokowuje wklejanie. Alternatywnie: wpisz w konsoli:
```js
document.querySelector('[wire\\:click*="toggleTableReordering"]')
```
Jeśli zwróci element — przycisk istnieje w DOM. Jeśli `null` — nie jest renderowany.

## 25. Wzorzec "Livewire Elegant Exception Handling (Insufficient Stock Prevention)"
**Problem**: Gdy system obsługuje stany magazynowe i zgłasza błędy walidacyjne w postaci wyjątków (`Exception('Insufficient stock')`), wywołanie tych metod bezpośrednio z poziomu Livewire powoduje, że nieobsłużony wyjątek skutkuje błędem 500 (Internal Server Error) w przeglądarce klienta (crash strony).
**Rozwiązanie**:
1. Dodaj właściwość publiczną `public $errorMessage = null;` do komponentu Livewire.
2. Zapakuj wszystkie metody modyfikujące koszyk w bloki `try-catch (\Exception $e)`.
3. W sekcji `catch` zmapuj komunikat wyjątku na przyjazny tekst i zapisz w `$errorMessage`.
4. Stwórz metodę pomocniczą `handleException(\Exception $e)` w komponencie:
   ```php
   protected function handleException(\Exception $e) {
       $message = $e->getMessage();
       if (str_contains($message, 'Insufficient stock')) {
           $this->errorMessage = 'Brak wystarczającej ilości produktu w magazynie.';
       } else {
           $this->errorMessage = 'Wystąpił błąd: ' . $message;
       }
   }
   ```
5. W widoku Blade wyświetl komunikat o błędzie na samej górze i dodaj przycisk do jego zamknięcia (czyszczenia stanu):
   ```html
   @if($errorMessage)
       <div class="alert alert-danger">
           <span>{{ $errorMessage }}</span>
           <button wire:click="$set('errorMessage', null)">x</button>
       </div>
   @endif
   ```

