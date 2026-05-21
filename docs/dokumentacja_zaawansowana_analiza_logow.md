# Dokumentacja: Zaawansowana Analiza Logów (Crawl Governance)

Moduł Zaawansowanej Analizy Logów jest systemem typu "Crawl Governance", który pozwala na monitorowanie aktywności robotów wyszukiwarek (SEO) oraz agentów sztucznej inteligencji (AI) w czasie rzeczywistym.

## 1. Architektura systemu

System składa się z trzech warstw:
1.  **Kolektor (Nginx):** Rejestruje każde zapytanie HTTP do pliku `/var/log/nginx/access.log`.
2.  **Parser (Laravel Command):** Komenda `logs:parse-crawl` analizuje logi, identyfikuje boty na podstawie wzorców User-Agent i zapisuje dane do bazy.
3.  **Prezentacja (Filament Dashboard):** Interfejs graficzny wyświetlający statystyki i alerty techniczne.

## 2. Identyfikowane Roboty

System automatycznie rozpoznaje i kategoryzuje następujące klasy botów:
*   **Search Engines:** Googlebot, Bingbot, YandexBot.
*   **AI Agents:** GPTBot (OpenAI), ClaudeBot (Anthropic).
*   **SEO Tools:** AhrefsBot, SemrushBot.
*   **Social Media:** FacebookExternalHit, Twitterbot.

## 3. Kluczowe Metryki

W panelu administracyjnym (**Analityka SEO -> Logi Crawlerów**) dostępne są następujące dane:
*   **Status Code:** Informuje, czy robot pomyślnie zaindeksował stronę (200) czy napotkał błąd (4xx/5xx).
*   **Crawl Rate:** Częstotliwość wizyt w ciągu ostatnich 24h. Pozwala ocenić, jak szybko nowe produkty pojawiają się w wynikach wyszukiwania.
*   **IP Address:** Pozwala zweryfikować, czy bot nie jest złośliwym scraperem podszywającym się pod Googlebota.

## 4. Harmonogram i Konserwacja

*   **Automatyzacja:** Parser uruchamia się automatycznie **co godzinę**.
*   **Rotacja logów:** Logi Nginx są rotowane codziennie, aby nie zajmowały nadmiernej ilości miejsca na dysku.
*   **Czyszczenie bazy:** Zaleca się okresowe usuwanie logów starszych niż 90 dni, aby utrzymać wydajność bazy danych.

## 5. Korzyści biznesowe

Dzięki temu modułowi:
1.  **Wykrywasz błędy 404/500** natychmiast po ich napotkaniu przez Googlebota.
2.  **Oszczędzasz budżet indeksowania**, identyfikując niepotrzebne ścieżki (np. parametry sesji), które boty indeksują bez sensu.
3.  **Śledzisz adaptację AI**, wiedząc, czy Twoje treści są już pobierane przez modele językowe zasilające ChatGPT lub Gemini.
