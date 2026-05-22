# Product Requirements - Audit SEO & Marketing

## Cel Projektu
- Przeprowadzenie pełnego audytu technicznego i marketingowego sklepu Kericho Gold pod kątem SEO, Google Analytics, Google Ads oraz Google Merchant Center.
- Identyfikacja błędów w śledzeniu konwersji i brakujących atrybutów w feedach produktowych.

## Wymagania Funkcjonalne
- [ ] Poprawność danych w `sitemap.xml`.
- [ ] Weryfikacja unikalności meta tagów dla wszystkich produktów i kategorii.
- [ ] Pełne śledzenie eCommerce w GA4 (wszystkie zdarzenia lejka zakupowego).
- [ ] Poprawny feed Google Merchant Center zgodny ze specyfikacją 2024+.

## Wymagania Techniczne i Stabilność
- [x] **Bezpieczeństwo (IDOR):** Wszystkie serwisy operujące na koszykach/zamówieniach muszą weryfikować własność obiektu (Auth ID).
- [x] **Wydajność:** Każdy Global Scope musi być wsparty indeksem w bazie danych.
- [x] **Optymalizacja N+1:** Zakaz używania zapytań w pętlach podczas generowania slugów i przeliczania koszyka.
- [x] **Architektura:** Logika stawek wysyłkowych musi być odseparowana od kodu serwisu (Config pattern).

## Szare Strefy (Decyzje do podjęcia)
- Czy implementujemy śledzenie konwersji Google Ads bezpośrednio w kodzie, czy przenosimy wszystko do GTM (jeśli ID GTM zostanie podane)?
- Czy dodajemy dodatkowe pola SEO do modelu produktu (np. `seo_title`, `seo_description`) jeśli domyślne są niewystarczające?

## Edge Cases
- Brak zdjęć dla niektórych produktów w feedzie.
- Produkty o zerowej cenie lub ujemnym stanie magazynowym (obsługa w GMC).
