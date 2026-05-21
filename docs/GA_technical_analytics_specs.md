# Specyfikacja Techniczna Analityki: Nevro-Shop v2

Niniejszy dokument opisuje wdrożoną architekturę śledzenia, mechanizmy Consent Mode v2 oraz specyfikację zdarzeń e-commerce przesyłanych do Google Analytics 4 (GA4) i Google Tag Manager (GTM).

---

## 1. Architektura Hybrydowa (Client + Server)

Wdrożono system hybrydowy w celu zapewnienia 100% dokładności danych, nawet przy użyciu ad-blockerów lub przerwaniu sesji przez użytkownika.

- **Client-Side (GTM/GA4):** Obsługuje interakcje użytkownika (widok produktu, dodanie do koszyka).
- **Server-Side (AnalyticsService):** Obsługuje zdarzenie `purchase` (zakup) wysyłane bezpośrednio z serwera PHP do GTM Server-Side w momencie finalizacji zamówienia w bazie danych.

---

## 2. Google Consent Mode v2

Zaimplementowano pełne wsparcie dla Standardu Consent Mode v2 wymaganego w EOG (Europejskim Obszarze Gospodarczym).

### Inicjalizacja (Header)
Wszystkie zgody są domyślnie ustawione na `denied` przed załadowaniem jakichkolwiek skryptów zewnętrznych.
```javascript
gtag('consent', 'default', {
  'ad_storage': 'denied',
  'ad_user_data': 'denied',
  'ad_personalization': 'denied',
  'analytics_storage': 'denied',
  'wait_for_update': 500
});
```

### Aktualizacja Zgód
Zdarzenie `cookie_consent_updated` w `dataLayer` wyzwala re-inicjalizację tagów w GTM bez odświeżania strony.

---

## 3. Specyfikacja Zdarzeń E-commerce

### Zdarzenie: `purchase` (Server-Side)
Wysyłane automatycznie po poprawnym złożeniu zamówienia.

| Parametr | Typ | Opis |
| :--- | :--- | :--- |
| `transaction_id` | String | Numer zamówienia (np. ORD-20260515-XXXX) |
| `value` | Float | Całkowita wartość zamówienia brutto |
| `currency` | String | Stała wartość: `PLN` |
| `profit` | Float | **Custom Metric**: (Cena sprzedaży - Cena zakupu) |
| `client_id` | String | Wyekstrahowany GA Client ID (format: XXXXX.YYYYY) |
| `items` | Array | Lista obiektów produktów (id, name, price, brand, quantity) |

---

## 4. Parametry Niestandardowe (Custom Dimensions/Metrics)

Aby widzieć te dane w raportach GA4, należy je zarejestrować w panelu administracyjnym:

1.  **Metric: `profit`** (Typ: Waluta) - Pozwala na raportowanie realnego zysku zamiast samego przychodu.
2.  **Dimension: `item_brand`** - Pozwala na filtrowanie sprzedaży według producentów/marek.

---

## 5. Mechanizm Atrybucji (GA Client ID)

System automatycznie przechwytuje ciasteczko `_ga` i parsuje je za pomocą wyrażenia regularnego:
`/(?:GA1\.\d\.)?(\d+\.\d+)/`

Dzięki temu eliminujemy prefiks `GA1.1.` i przesyłamy do Google czyste ID sesji, co pozwala na poprawne połączenie kliknięcia w reklamę Google Ads z zakupem zarejestrowanym przez serwer.

---

## 6. Monitoring Błędów

Wszystkie błędy analityki są rejestrowane w logach serwera pod tagiem `[GA4-Tracking]`. 
W przypadku awarii serwera analitycznego (GTM Server-Side), proces zamówienia **nie zostaje przerwany** (bezpiecznik `try-catch`).

---
*Dokumentacja przygotowana przez Antigravity dla Nevro-Shop v2.*
