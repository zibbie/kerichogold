# Dokumentacja SEO i Marketing - Nevro-Shop

## 1. Techniczne SEO

### Meta Tagi
Aplikacja dynamicznie generuje tagi `title`, `description` oraz `canonical` dla kluczowych podstron.

*   **Strona Produktu:**
    *   `title`: Pobierany z pola `meta_title` w modelu `Product`. Jeśli puste, używana jest nazwa produktu + przyrostek " | Nevro-Shop".
    *   `description`: Pobierany z `meta_description`. Jeśli puste, system generuje automatyczny opis na podstawie treści produktu (limit 160 znaków).
    *   `canonical`: Generowany automatycznie na podstawie aktualnego URL produktu.
*   **Strona Kategorii:**
    *   `title`: Nazwa kategorii + przyrostek.
    *   `description`: Opis kategorii lub `meta_description`.

### Stabilność Adresów URL (Slugs)
Zastosowano zoptymalizowany algorytm generowania slugów, który:
- Zapewnia unikalność adresu URL nawet przy identycznych nazwach produktów (dodawanie przyrostków numerycznych).
- Zapobiega powstawaniu błędów 404 podczas masowych aktualizacji cen/zdjęć (slug jest chroniony przed nieplanowaną zmianą).
- Wspiera kanonikalizację poprzez stałą strukturę adresów.

### JSON-LD (Dane Strukturalne)
Wszystkie dane strukturalne są zarządzane przez klasę `App\Services\SeoService`. Obsługiwane schematy:
*   `Organization`: Globalne informacje o firmie, logo i kontakt.
*   `WebSite`: Implementacja wyszukiwarki Sitelinks Searchbox.
*   `Product`: Pełne dane o produkcie (cena, waluta, dostępność, marka).
*   `BreadcrumbList`: Ścieżka nawigacji dla Google.
*   `CollectionPage`: Optymalizacja list produktów w kategoriach.

### Pliki Robotów i Mapy Stron
*   **Sitemap:** Dostępna pod adresem `/sitemap.xml`. Generowana dynamicznie w `SitemapController`. Zawiera produkty, kategorie oraz strony CMS.
*   **Robots.txt:** Znajduje się w `/public/robots.txt`. Skonfigurowany, aby blokować boty szkolące AI (GPTBot, CCBot) oraz chronić ścieżki prywatne (checkout, admin).

---

## 2. Analityka i Śledzenie (GA4)

System wykorzystuje `gtag.js` do śledzenia zdarzeń eCommerce.

### Kluczowe zdarzenia:
1.  **`view_item_list`**: Wyświetlenie listy produktów (strona główna, kategorie, wyniki wyszukiwania).
2.  **`view_item`**: Wejście na kartę produktu.
3.  **`add_to_cart`**: Dodanie produktu do koszyka (zarówno z karty produktu, jak i z list).
4.  **`view_cart`**: Otwarcie panelu bocznego koszyka.
5.  **`begin_checkout`**: Przejście do procesu płatności.
6.  **`purchase`**: Finalizacja transakcji (po potwierdzeniu statusu płatności).

### Spójność Danych:
Wszystkie zdarzenia używają **Database ID** jako `item_id`. Jest to kluczowe dla poprawnego działania remarketingu dynamicznego w Google Ads.

---

## 3. Google Merchant Center (GMC)

### Feed Produktowy
Feed XML jest dostępny pod adresem `/feed/google`.

**Funkcje feedu:**
*   **Filtracja:** Eksportowane są tylko produkty, które mają zaznaczoną opcję "Eksportuj do Google Merchant Center" w panelu admina.
*   **Google Product Category (GPC):** Możliwość zdefiniowania specyficznej kategorii Google dla każdej kategorii w sklepie.
*   **Atrybuty:** System przesyła `g:id`, `g:title`, `g:description`, `g:link`, `g:image_link`, `g:availability`, `g:price`, `g:brand` oraz `g:mpn`.

---

## 4. Administracja (Panel Filament)

W panelu administracyjnym dodano dedykowane pola ułatwiające zarządzanie marketingiem:
*   **Produkty:** Przełącznik "GMC" sterujący obecnością w feedzie.
*   **Kategorie:** Pole "Google Product Category ID" do mapowania kategorii.
*   **Ustawienia:** Możliwość zmiany identyfikatorów GTM, GA4 i Google Ads bez ingerencji w kod.
