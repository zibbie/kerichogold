# Instrukcja Obsługi Modułu SEO i Marketingu (Panel Filament)

Niniejsza instrukcja opisuje, jak zarządzać nowymi funkcjami SEO i Google Merchant Center w panelu administracyjnym sklepu Nevro-Shop.

## 1. Zarządzanie Produktami (GMC)

Każdy produkt w systemie posiada teraz indywidualną kontrolę nad eksportem do Google Ads.

### Jak wykluczyć produkt z reklam?
1.  Wejdź w zakładkę **Produkty**.
2.  Wybierz edycję konkretnego produktu.
3.  W sekcji **Cena i Magazyn** znajdziesz przełącznik: **"Eksportuj do Google Merchant Center"**.
4.  Wyłącz przełącznik i zapisz zmiany.
5.  Produkt zostanie natychmiast usunięty z pliku XML feedu i przestanie być wyświetlany w kampaniach produktowych Google Ads.

### Monitorowanie statusu na liście
Na głównej liście produktów znajduje się kolumna **GMC**. 
*   Zielony ptaszek: Produkt jest eksportowany.
*   Czerwony krzyżyk: Produkt jest wykluczony.

---

## 2. Zarządzanie Kategoriami (GPC)

Google Merchant Center wymaga przypisania produktów do ich oficjalnej taksonomii (Google Product Category).

### Jak ustawić kategorię Google?
1.  Wejdź w zakładkę **Kategorie**.
2.  Edytuj wybraną kategorię.
3.  Znajdź pole: **"Google Product Category ID"**.
4.  Wpisz odpowiedni kod numeryczny kategorii Google.
    *   *Przykład:* Dla osprzętu do zbiorników IBC wpisz `505315`.
5.  Zapisz zmiany. Wszystkie produkty przypisane do tej kategorii automatycznie otrzymają ten kod w pliku feed.

---

## 3. Optymalizacja SEO (Meta Tagi)

W edycji produktów i kategorii dostępna jest sekcja **SEO**.

### Tytuły i Opisy
*   **Tytuł SEO:** Jeśli pozostawisz go pustym, system użyje nazwy produktu. Warto jednak wpisać tu frazę kluczową (np. "Zbiornik IBC 1000l - Nowy, Atestowany").
*   **Opis SEO (Meta Description):** To tekst, który wyświetla się w Google pod linkiem. Powinien być zachęcający i zawierać tzw. Call to Action (np. "Sprawdź najwyższej jakości osprzęt do zbiorników IBC. Szybka wysyłka, niskie ceny. Zamów online!").

---

## 4. Ustawienia Ogólne

W zakładce **Ustawienia Ogólne** (Zarządzanie sklepem) możesz zarządzać kluczowymi parametrami operacyjnymi:
*   **Koszt pobrania (COD):** Kwota doliczana automatycznie do zamówień "za pobraniem".
*   **Emaile powiadomień:** Lista adresów (oddzielona przecinkiem), na które przychodzą maile o nowych zamówieniach.
*   **Przełącznik PayPo:** Pozwala jednym kliknięciem ukryć lub pokazać płatności odroczone w całym sklepie.

---

## 5. Narzędzia Konwersji (Testy A/B)

W zakładce **Narzędzia Konwersji** (Marketing i SEO) znajdują się gotowe mechanizmy poprawiające sprzedaż:
*   **Przycisk Finalizacji:** Wybór między standardowym "Zapłać i zamów" a motywującym "Odbierz zamówienie".
*   **Licznik Darmowej Dostawy:** Pasek postępu w koszyku informujący ile brakuje do darmowej wysyłki (można tu też zmienić kwotę progu).
*   **Odznaki Zaufania:** Włączanie/wyłączanie ikon bezpieczeństwa (SSL, Gwarancja zwrotu) w koszyku.

*Każde z powyższych narzędzi można uruchomić w trybie **Testu A/B** – system sam sprawdzi, która wersja przynosi więcej zamówień.*

---

## 6. Zarządzanie Wysyłką (Zaawansowane)

Koszty wysyłki oraz logika pakowania są teraz odseparowane od kodu źródłowego i znajdują się w pliku konfiguracyjnym.

### Jak zmienić ceny wysyłki?
1. Poproś administratora technicznego o edycję pliku `config/shipping.php`.
2. W sekcji `rates` można zmienić ceny dla poszczególnych klas (np. `courier_standard`, `inpost`).
3. W sekcji `accessory_categories` można zdefiniować, które kategorie produktów mają być traktowane jako akcesoria (stała opłata za paczkę niezależnie od ilości).

### Logika "Double-Charging"
System automatycznie grupuje produkty tak, aby klient nie płacił podwójnie za wysyłkę, jeśli przedmioty mieszczą się w jednej paczce (zgodnie z limitem `items_per_package` w ustawieniach produktu).
