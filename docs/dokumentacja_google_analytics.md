# Dokumentacja Integracji: Google Analytics 4 (GA4)

System Nevro-Shop v2 posiada natywną, zaawansowaną implementację śledzenia eCommerce dla GA4, zgodną ze standardami na rok 2026.

## 1. Konfiguracja

Śledzenie jest zaimplementowane poprzez bibliotekę `gtag.js`. Kod śledzenia jest ładowany dynamicznie na każdej stronie.

*   **Measurement ID:** Zarządzany w panelu admina (Ustawienia -> Google Analytics ID).
*   **Tryb śledzenia:** eCommerce Full Funnel.

## 2. Śledzone zdarzenia (Events)

System raportuje następujące interakcje użytkowników:

### Wyświetlenie list (`view_item_list`)
Wysyłane na stronie głównej, w kategoriach i wynikach wyszukiwania. Pozwala analizować, które produkty są najczęściej zauważane.

### Wyświetlenie produktu (`view_item`)
Wysyłane przy wejściu na kartę produktu. Przesyła:
*   `item_id`: ID produktu.
*   `item_name`: Nazwa.
*   `item_brand`: "Nevro".
*   `price`: Cena.

### Dodanie do koszyka (`add_to_cart`)
Wysyłane natychmiast po kliknięciu przycisku "Dodaj do koszyka" (również bez przeładowania strony dzięki integracji z Livewire).

### Otwarcie koszyka (`view_cart`)
Specyficzne zdarzenie wysyłane przy otwarciu bocznego panelu koszyka. Pozwala śledzić intencję zakupową przed przejściem do płatności.

### Rozpoczęcie checkoutu (`begin_checkout`)
Wysyłane w momencie przejścia do formularza danych adresowych.

### Zakup (`purchase`)
Wysyłane na stronie podziękowania po udanej płatności. Zawiera:
*   `transaction_id`: Numer zamówienia.
*   `value`: Całkowita kwota zamówienia.
*   `items`: Pełna lista zakupionych produktów.

## 3. Integracja z Google Ads (Remarketing Dynamiczny)

Dzięki ujednoliceniu identyfikatorów (`item_id` = Database ID), GA4 automatycznie przesyła dane do Google Ads dla remarketingu dynamicznego. Pozwala to na:
*   Wyświetlanie użytkownikom reklam dokładnie tych produktów, które oglądali w sklepie.
*   Automatyczne wykluczanie z reklam produktów już zakupionych.

## 4. Raportowanie w panelu GA4

Aby zobaczyć dane eCommerce:
1.  Zaloguj się do Google Analytics.
2.  Przejdź do: **Raporty -> Generowanie przychodów -> Zakupy e-commerce**.
3.  Możesz tam filtrować dane według kategorii produktów, nazw produktów lub marek.
