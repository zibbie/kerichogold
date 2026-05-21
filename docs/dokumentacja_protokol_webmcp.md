# Dokumentacja: Protokół WebMCP (Agent-Ready AI)

Protokół WebMCP (Web Model Context Protocol) to nowoczesny standard komunikacji, który przygotowuje sklep Nevro-Shop na erę autonomicznych agentów zakupowych AI.

## 1. Idea "Agent-Ready"

W tradycyjnym modelu, asystent AI (np. ChatGPT z funkcją przeglądania sieci) próbuje odczytać stronę tak jak człowiek – analizując kod HTML i szukając przycisków. Jest to proces wolny, kosztowny i podatny na błędy (halucynacje).

**WebMCP** odwraca tę relację: sklep jawnie deklaruje "narzędzia" (Tools), których agent AI może użyć, aby pobrać dane bezpośrednio z bazy danych w ustrukturyzowanym formacie JSON-RPC.

## 2. Implementacja techniczna

*   **Discovery:** Agent AI wykrywa protokół poprzez meta-tag w nagłówku strony:
    `<meta name="webmcp-endpoint" content="/api/mcp">`
*   **Endpoint:** `/api/mcp` (Metoda POST).
*   **Format:** JSON-RPC 2.0.

## 3. Dostępne Narzędzia (Tools)

System Nevro-Shop udostępnia następujące funkcje dla modeli AI:

### `search_products`
Pozwala na przeszukiwanie katalogu produktów.
*   **Parametry:** `query` (fraza wyszukiwania).
*   **Zwraca:** Listę produktów z cenami i linkami.

### `get_product`
Pobiera pełne dane o konkretnym produkcie.
*   **Parametry:** `id` (identyfikator bazy danych).
*   **Zwraca:** Opis, aktualną cenę brutto, status dostępności w czasie rzeczywistym oraz kategorię.

### `get_categories`
Pobiera strukturę kategorii sklepu.
*   **Zwraca:** Listę nazw i slugów wszystkich aktywnych kategorii.

## 4. Bezpieczeństwo

*   Integracja WebMCP w Nevro-Shop v2 jest **tylko do odczytu** (Read-Only) w obecnej fazie. Agenty AI nie mogą samodzielnie składać zamówień ani modyfikować danych bez autoryzacji.
*   System chroniony jest przez standardowe mechanizmy Rate Limitingu, zapobiegające przeciążeniu serwera przez agresywne boty.

## 5. Przyszłość (Roadmap)

W kolejnych etapach planowane jest dodanie funkcji:
*   `check_shipping` – kalkulacja kosztów dostawy na podstawie kodu pocztowego.
*   `add_to_cart` – generowanie bezpiecznego linku do koszyka z już dodanymi produktami (Agent-Assisted Checkout).
