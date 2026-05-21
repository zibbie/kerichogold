# Dokumentacja Integracji: Google Merchant Center (GMC) - Stan na 15.05.2026

Sklep Nevro-Shop v2 osiągnął pełną stabilizację w ekosystemie reklamowym Google.

## 1. Status Zatwierdzenia
*   **Zatwierdzone:** Pełna zgodność z polityką Google (brak placeholderów).
*   **GMC Feed URL:** `http://localhost:8080/feed/google` (produkcja: `https://nevro-wm.pl/feed/google`)
*   **Ostatnia duża aktualizacja:** Wdrożenie "Żelaznej Tarczy" (Hardening).

## 2. Rozwiązane Problemy Techniczne (Post-Audit 15.05.2026)
### A. "Nieobsługiwany typ zdjęcia" (image_link) - FIXED
*   **Problem:** Znaki specjalne i zewnętrzne adresy (Unsplash) powodowały odrzucenia.
*   **Rozwiązanie:** Skrypt `gmc:sanitize-images` automatycznie pobiera, optymalizuje i nazywa zdjęcia według formatu `p[ID].ext`. 100% plików serwowanych lokalnie.

### B. "Brak wagi przesyłki" (shipping_weight) - FIXED
*   **Rozwiązanie:** Fallback `0.50 kg` w `GoogleFeedController` dla produktów bez wagi.

### C. Błędne kody GTIN - FIXED
*   **Rozwiązanie:** Flaga `g:identifier_exists = no` dla produktów bez EAN.

### D. Błędy GTM i Consent Mode v2 - FIXED
*   **Problem:** Race condition i uszkodzony kod GTM blokowały analitykę.
*   **Rozwiązanie:** Przebudowa skryptu w `app.blade.php`. Inicjalizacja zgód (Consent Mode) odbywa się teraz natychmiastowo z `localStorage` przed załadowaniem tagów.

### E. Atrybucja Serwerowa (GA4) - FIXED
*   **Rozwiązanie:** Persystencja `ga_client_id` w tabeli `orders`. Przechwytywanie ID metodą hybrydową (JS + Cookie) zapewnia poprawną atrybucję nawet przy płatnościach zewnętrznych.

## 3. Optymalizacja Wydajności
*   **Cache Feed:** Zredukowano `max-age` z 3600s do 300s (5 minut). Zapewnia to niemal natychmiastową synchronizację cen i stanów magazynowych z Google Ads.
*   **Soft 404:** Wszystkie nieistniejące stare linki zwracają teraz status 404 zamiast przekierowania 301 do sklepu, co chroni autorytet domeny (Crawl Budget).

## 4. Ostrzeżenia "Obraz jest za mały"
Google zaleca zdjęcia o rozdzielczości min. 800x800px. System akceptuje mniejsze wymiary, ale oznacza je jako ostrzeżenia (nie blokuje sprzedaży).

## 3. Optymalizacja Google Ads - "Produkty Duchy"
W panelu Google Ads mogą pojawiać się błędy dla produktów, których nie ma w aktualnym feedzie (stare ID z poprzedniej platformy). 
*   **Zalecenie:** W GMC należy ustawić opcję "Nie zezwalaj na reklamy produktów znalezionych przez Google", aby skupić budżet wyłącznie na zweryfikowanym feedzie XML (PRODUCTS SOURCE 2).

## 4. Ostrzeżenia "Obraz jest za mały"
Google zaleca zdjęcia o rozdzielczości min. 800x800px. Część obecnych zdjęć ma mniejsze wymiary, co generuje ostrzeżenia (Warning), ale **nie blokuje** wyświetlania reklam. Przy dodawaniu nowych produktów zaleca się stosowanie zdjęć o wysokiej rozdzielczości.
