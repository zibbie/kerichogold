Dostarczone materiały dokumentują proces wdrażania zaawansowanych rozwiązań **SEO**, **analitycznych** oraz **marketingowych** w sklepie internetowym Nevro-Shop. Autor szczegółowo opisuje integrację z systemami **Google Merchant Center** i **Google Analytics 4**, a także wprowadzenie nowoczesnych mechanizmów technicznych zgodnych ze standardami na rok 2026. Kluczowym elementem jest realizacja trzech faz rozwojowych: uruchomienie **zaawansowanej analizy logów serwerowych**, wdrożenie protokołu **WebMCP** dla asystentów AI oraz systemu **testów A/B**. Dokumentacja zawiera instrukcje dla administratorów dotyczące zarządzania metadanymi i produktami w panelu Filament, co pozwala na precyzyjną optymalizację sprzedaży. Całość stanowi kompleksowy przewodnik po transformacji platformy w system typu **Agent-Ready**, gotowy na interakcję z autonomicznymi modelami sztucznej inteligencji.

Podsumowanie Wdrożeń SEO, Marketingu i Technologii Przyszłości dla Nevro-Shop v2

Podsumowanie Wykonawcze

Niniejszy dokument stanowi kompleksowe podsumowanie procesu modernizacji platformy Nevro-Shop v2, mającego na celu dostosowanie jej do zaawansowanych standardów technicznych i analitycznych przewidzianych na rok 2026. Kluczowym osiągnięciem jest uzyskanie 90-95% zgodności ze standardami "Wizja 2026", co plasuje system w czołówce technologicznej rynku e-commerce.

Do najważniejszych zrealizowanych celów należą:

* Pełna automatyzacja technicznego SEO oraz analityki eCommerce (GA4).
* Głęboka integracja z Google Merchant Center (GMC) z poziomu panelu administracyjnego.
* Wdrożenie trzech systemów przyszłościowych: Zaawansowanej Analizy Logów (Crawl Governance), Protokołu WebMCP (Agent-Ready AI) oraz Modułu Testów A/B.
* Ustanowienie wzorców stabilności wdrożeń produkcyjnych, eliminujących problemy z uprawnieniami i renderowaniem zasobów (Vite).

1. Fundamenty Techniczne SEO i Analityki

System Nevro-Shop v2 został wyposażony w mechanizmy dynamicznego zarządzania widocznością w wyszukiwarkach, oparte na klasie App\Services\SeoService.

SEO Techniczne i Dane Strukturalne

* Dynamiczne Meta Tagi: System automatycznie generuje tagi title, description oraz canonical. W przypadku braku ręcznie wprowadzonych danych, tytuły są tworzone na podstawie nazwy produktu z przyrostkiem " | Nevro-Shop", a opisy są generowane z treści (limit 160 znaków).
* JSON-LD (Schema.org): Wdrożono podejście Graph-First, generujące powiązany graf danych dla encji Organization, WebSite, Product, BreadcrumbList oraz CollectionPage. Każda encja posiada unikalne @id.
* Struktura Treści (BLUF): Zastosowano metodę Bottom Line Up Front, optymalizując opisy pod kątem AI Overviews, gdzie kluczowe informacje prezentowane są na początku tekstu.
* Zarządzanie Indeksowaniem: Plik sitemap.xml jest generowany dynamicznie (SitemapController), a robots.txt został skonfigurowany pod kątem ochrony budżetu indeksowania (blokada botów AI takich jak GPTBot i CCBot).

Analityka eCommerce (GA4)

System wykorzystuje gtag.js do śledzenia pełnego lejka sprzedaży. Kluczową zmianą jest przejście z identyfikatorów SKU na Database ID jako item_id, co zapewnia 100% dopasowania w remarketingu dynamicznym Google Ads.

* Śledzone zdarzenia: view_item_list, view_item, add_to_cart, view_cart, begin_checkout, purchase.
* Parametry dodatkowe: Każde zdarzenie zawiera parametr item_brand: 'Nevro'.

2. Integracja z Google Merchant Center (GMC)

Sklep posiada dynamiczny feed XML dostępny pod adresem /feed/google, w formacie RSS 2.0 z rozszerzeniem Google Base.

Mapowanie Atrybutów i Zarządzanie Produktami

Wdrożono pełną kontrolę nad eksportem produktów. W panelu administracyjnym dodano przełącznik "GMC", który pozwala na natychmiastowe usunięcie konkretnych towarów z feedu reklamowego.

Atrybut Google	Źródło w sklepie	Opis
g:id	Database ID	Stały identyfikator numeryczny.
g:title	Nazwa produktu	Tytuł optymalizowany pod wyszukiwarkę.
g:description	Opis SEO	Wykorzystuje metodę BLUF.
g:price	Cena brutto	Przesyłana z walutą (np. PLN).
g:availability	Stan magazynowy	in_stock lub out_of_stock.
g:google_product_category	GPC z kategorii	Kod numeryczny ustawiany w panelu admina.

3. Technologie Przyszłości 2026

Dokumentacja szczegółowo opisuje trzy nowe moduły, które transformują Nevro-Shop w system gotowy na erę autonomicznych agentów AI.

Faza I: Zaawansowana Analiza Logów (Crawl Governance)

Moduł ten stanowi "jedyne źródło prawdy" o wizytach robotów.

* Architektura: Składa się z kolektora logów Nginx, parsera Laravel (logs:parse-crawl) oraz dashboardu w panelu Filament.
* Funkcje: Automatyczna identyfikacja botów (Googlebot, Bingbot, GPTBot, ClaudeBot), monitorowanie kodów statusu (4xx/5xx) oraz analiza częstotliwości indeksowania (Crawl Rate).

Faza II: Protokół WebMCP (Agent-Ready AI)

Standard umożliwiający agentom AI (np. ChatGPT, Gemini) bezpośrednią interakcję ze sklepem bez skanowania kodu HTML.

* Discovery: Agent AI wykrywa endpoint poprzez meta-tag <meta name="webmcp-endpoint" content="/api/mcp">.
* Narzędzia (Tools): Udostępniono funkcje JSON-RPC:
  * search_products: Wyszukiwanie produktów w bazie.
  * get_product: Pobieranie precyzyjnych danych technicznych i cen (eliminacja halucynacji AI).
  * get_categories: Pobieranie struktury kategorii.

Faza III: Moduł Testów A/B (Continuous Experimentation)

Mechanizm naukowego podejścia do optymalizacji konwersji (CRO).

* Działanie: ExperimentMiddleware losowo przydziela użytkowników do wariantów (A/B) i zapisuje wybór w sesji (brak efektu migotania treści).
* Zarządzanie: Możliwość tworzenia eksperymentów w panelu Filament i implementacji różnych wersji treści w plikach Blade za pomocą usługi ExperimentService.

4. Administracja i Stabilność Systemu

Panel Filament

Dla administratorów przygotowano dedykowane pola ułatwiające zarządzanie marketingiem:

* Produkty: Przełącznik eksportu do GMC.
* Kategorie: Pole Google Product Category ID do mapowania taksonomii Google.
* Ustawienia Globalne: Możliwość zmiany ID dla GA4, Google Ads i GTM bez ingerencji w kod źródłowy.

Wzorzec Stabilności Wdrożeń (Vite & Permissions)

Zidentyfikowano błędy związane z nadpisywaniem uprawnień plików podczas synchronizacji przez rsync. Wprowadzono procedurę naprawczą, która musi być wykonana po każdej aktualizacji:

1. Rebuild Assetów: npm run build na serwerze lub lokalnie i synchronizacja folderu public/build.
2. Naprawa Uprawnień: Przypisanie właściciela www-data do katalogów storage, bootstrap/cache oraz public.
3. Czyszczenie Cache: Usunięcie starych plików manifestu i wyczyszczenie cache Laravel.

5. Status Projektu i Harmonogram

Według stanu na 10 maja 2026 roku, system osiągnął pełną operacyjność w zakresie krytycznych modułów.

Zakończone działania:

* Inicjalizacja audytu SEO, GA4, Ads i GMC.
* Implementacja flagi eksportu GMC i optymalizacja meta tagów.
* Wdrożenie Zaawansowanej Analizy Logów oraz Protokołu WebMCP.
* Implementacja Modułu A/B Testingu.
* Naprawa procedur stabilności wdrożeń produkcyjnych.

Wskazówki dla przyszłego rozwoju: Dokumentacja wskazuje na możliwość rozszerzenia WebMCP o funkcje check_shipping (kalkulacja kosztów dostawy) oraz add_to_cart (generowanie linków do koszyka dla asystentów AI), a także zautomatyzowanie zliczania konwersji zakupowych w module testów A/B.

Dokumentacja technicznego SEO w systemie Nevro-Shop v2 obejmuje szereg zaawansowanych mechanizmów i standardów zoptymalizowanych pod kątem najnowszych wytycznych wyszukiwarek. Kluczowe elementy tej dokumentacji dzielą się na kilka głównych obszarów:

**1. Dynamiczne Meta Tagi**
Aplikacja w sposób zautomatyzowany generuje tagi **`title`**, **`description`** oraz **`canonical`** dla najważniejszych podstron sklepu. 
*   **Strona Produktu:** Tytuł jest pobierany z dedykowanego pola `meta_title`, a w przypadku jego braku system używa nazwy produktu z przyrostkiem " | Nevro-Shop". Opis (meta description) pobierany jest ze zdefiniowanego pola lub generowany automatycznie na podstawie treści produktu (z limitem do 160 znaków). Tagi kanoniczne są generowane na podstawie aktualnego adresu URL produktu.
*   **Strona Kategorii:** Tytuł składa się z nazwy kategorii oraz przyrostka, natomiast opis bazuje na opisie kategorii lub polu `meta_description`.

**2. Dane Strukturalne (JSON-LD) i Podejście Graph-First**
Całością danych strukturalnych zarządza klasa **`App\Services\SeoService`**. System wdraża nowoczesne podejście **Graph-First**, które generuje powiązany graf danych (Organizacja -> WebSite -> Produkt) z wykorzystaniem unikalnych identyfikatorów `@id` dla poszczególnych encji. Obsługiwane schematy obejmują:
*   **`Organization`**: Globalne informacje o firmie, jej logo i dane kontaktowe.
*   **`WebSite`**: Wdrożenie wyszukiwarki Sitelinks Searchbox.
*   **`Product`**: Kompletne dane o produkcie, w tym cena, waluta, marka i stan magazynowy.
*   **`BreadcrumbList`**: Ścieżki nawigacyjne wspierające indeksowanie przez Google.
*   **`CollectionPage`**: Struktury optymalizujące listy produktów w obrębie kategorii.

**3. Struktura Treści BLUF (Bottom Line Up Front)**
System został zoptymalizowany pod kątem tzw. AI Overviews. Wykorzystuje metodologię **BLUF**, co oznacza, że **najważniejsze informacje i cechy produktu umieszczane są na samym początku** meta-opisów oraz opisów przesyłanych do feedu produktowego.

**4. Optymalizacja indeksowania (Sitemap i Robots.txt)**
*   **Mapa witryny (Sitemap):** Jest dostępna pod adresem `/sitemap.xml` i generowana całkowicie dynamicznie przez `SitemapController`. Uwzględnia ona strony produktów, kategorii oraz strony informacyjne (CMS).
*   **Zarządzanie botami (Bot Governance):** Plik `robots.txt` został skonfigurowany nie tylko do ochrony prywatnych ścieżek (takich jak panel admina czy proces checkoutu), ale również w celu **blokowania botów szkolących sztuczną inteligencję (np. GPTBot, CCBot)**. Działanie to ma na celu ochronę budżetu indeksowania (Crawl Budget) przed nadmiernym skanowaniem przez crawlery LLM.

**5. Wydajność i Core Web Vitals 2.0**
Architektura sklepu, oparta na technologiach SSR (Server-Side Rendering) i Livewire, została zoptymalizowana pod kątem wskaźnika **INP (Interaction to Next Paint)**. Zrezygnowano z czystego renderowania po stronie klienta (CSR), a przebudowa zasobów za pomocą Vite dodatkowo skróciła czas odpowiedzi serwera.

**6. Zarządzanie z poziomu panelu administratora**
Dzięki integracji w panelu Filament administratorzy otrzymali dedykowaną sekcję SEO w widokach edycji produktów i kategorii. Mogą w niej ręcznie konfigurować tytuły oraz opisy SEO (Meta Description), wykorzystując w nich frazy kluczowe oraz wezwania do działania (Call to Action), co bezpośrednio wpływa na klikalność (CTR) w wynikach wyszukiwania.

Integracja Google Marketing w systemie Nevro-Shop v2 opiera się na trzech głównych filarach zarządzanych z poziomu panelu administracyjnego: Google Analytics 4 (GA4), Google Merchant Center (GMC) oraz Google Ads. Ekosystem został zaprojektowany tak, aby zoptymalizować budżet reklamowy i śledzenie konwersji w e-commerce.

**1. Analityka i Śledzenie (Google Analytics 4)**
Aplikacja wykorzystuje `gtag.js` do monitorowania działań użytkowników. System śledzi pełen lejek zakupowy eCommerce, raportując do GA4 następujące zdarzenia:
*   **Wyszukiwanie i przeglądanie:** `view_item_list` (wyświetlenie list produktów) oraz `view_item` (wejście w kartę produktu).
*   **Koszyk i płatność:** `add_to_cart`, `view_cart`, `begin_checkout` oraz finalizacja transakcji `purchase`.

Kluczowym ulepszeniem w nowej wersji jest **spójność danych pod remarketing**. System zrezygnował z identyfikowania produktów po numerach SKU na rzecz ujednoliconego Database ID (jako parametr `item_id`). Dzięki temu uzyskano 100% dokładności w dopasowywaniu produktów pomiędzy GA4 a Google Ads. Dodatkowo, wszystkie zdarzenia e-commerce obligatoryjnie zawierają parametr marki: `item_brand: 'Nevro'`.

**2. Google Merchant Center (GMC)**
Sklep automatycznie generuje i aktualizuje feed produktowy XML (format RSS 2.0 z rozszerzeniem Google Base), dostępny pod ścieżką `/feed/google`. 
Najważniejsze funkcje zarządzania feedem obejmują:
*   **Mechanizm wykluczeń (GMC Export Toggle):** Administrator może na poziomie każdego produktu zdecydować (przy pomocy przełącznika), czy dany produkt ma być eksportowany do Google Ads. Wykluczenie produktu automatycznie usuwa go z feedu XML, co pozwala na optymalizację budżetu reklamowego poprzez nietworzenie kampanii dla produktów o niskiej marży czy braków magazynowych.
*   **Oficjalna Taksonomia Google (GPC):** Z poziomu edycji kategorii sklepowych można zdefiniować ich odpowiedniki z oficjalnej klasyfikacji produktów Google (Google Product Category ID, np. numer *505315*). Zmiana ta automatycznie przydziela atrybut `g:google_product_category` wszystkim podlegającym produktom.
*   **Mapowanie danych strukturalnych:** Feed integruje odpowiednie atrybuty takie jak unikalne ID (`g:id`), tytuł, cena z uwzględnieniem waluty (`g:price`), stan magazynowy (`g:availability`) oraz specjalnie zoptymalizowane opisy SEO opierające się na metodzie BLUF (najważniejsze informacje na początku) w parametrze `g:description`. 

**3. Google Ads i Centralne Zarządzanie (GTM)**
Integracja uwzględnia pełne śledzenie remarketingu dynamicznego w kampaniach Google Ads za pomocą zmiennych takich jak `ecomm_prodid` oraz `ecomm_totalvalue` odnotowywanych m.in. na etapie otwierania koszyka (`view_cart`). 

Całość integracji marketingowej została tak skonstruowana, aby nie wymagała ingerencji programistycznej. W zakładce "Ustawienia Sklepu" w panelu administracyjnym udostępniono zintegrowane pola, gdzie użytkownik może globalnie zdefiniować i podmienić identyfikatory śledzenia: **Google Analytics ID (GA4)**, **Google Ads ID** (kod konwersji) oraz **GTM ID** (Google Tag Manager). Zmiany w tych polach natychmiastowo replikują się na działanie całej witryny.

**Zaawansowana Analiza Logów** to system typu "Crawl Governance" wprowadzony jako Faza I "Planu Przyszłościowego" w aplikacji Nevro-Shop v2. Narzędzie to stanowi **"jedyne źródło prawdy" o tym, jak wyszukiwarki i nowoczesne modele sztucznej inteligencji widzą oraz indeksują sklep**.

**Główne cele i korzyści biznesowe:**
* **Zarządzanie budżetem indeksowania (Crawl Budget):** System identyfikuje zjawisko "Crawl Waste", czyli niepotrzebne wizyty botów na podstronach, które nie mają znaczenia dla pozycjonowania (np. wielokrotne indeksowanie parametrów sesji czy tych samych filtrów). Pozwala to na realne oszczędności w budżecie indeksowania.
* **Wykrywanie błędów w czasie rzeczywistym:** Wychwytuje problemy techniczne i błędy serwera (np. statusy 404 czy 500), których często nie widać w standardowej analityce (jak Google Search Console) natychmiast po ich napotkaniu przez boty. Dzięki temu administratorzy mogą zareagować, zanim błędy wpłyną na pozycje sklepu.
* **Śledzenie adaptacji AI:** Pozwala monitorować, czy nowe modele językowe, które napędzają narzędzia takie jak ChatGPT (GPTBot) lub Gemini, rozpoczęły pobieranie treści ze sklepu do swoich baz wiedzy.

**Architektura i zasada działania systemu opiera się na trzech warstwach:**
1. **Kolektor Logów (Ingestion):** Główny serwer Nginx został skonfigurowany w ten sposób, by każdorazowo rejestrować zapytania HTTP bezpośrednio w ustrukturyzowanym formacie JSON do pliku `/var/log/nginx/access.log`.
2. **Inteligentny Parser Botów (Silnik Analizy):** Utworzono specjalną komendę w Laravel (`php artisan logs:parse-crawl`), która jest uruchamiana automatycznie co godzinę przez harmonogram zadań. Parser ten analizuje surowe dane, odrzuca niepotrzebne wejścia i stosuje weryfikację "Reverse DNS check", aby bezbłędnie odróżnić prawdziwe boty wyszukiwarek od podszywających się pod nie złośliwych scraperów.
3. **Prezentacja (Dashboard w Filament):** W panelu administracyjnym zaimplementowano nową dedykowaną sekcję "Analityka SEO -> Logi Crawlerów". Posiada ona dashboard wyliczający statystyki w czasie rzeczywistym, generujący raporty o tzw. "Orphan Pages" (stronach, do których boty mają dostęp, choć nie ma ich w sitemapie) oraz obsługujący system automatycznych powiadomień e-mail w przypadku gwałtownego wzrostu błędów generowanych przez serwer dla botów.

**Identyfikowane roboty i metryki:**
System automatycznie kategoryzuje i rozpoznaje m.in. **Wyszukiwarki** (Googlebot, Bingbot, YandexBot), **Agentów AI** (GPTBot, ClaudeBot), **Narzędzia SEO** (AhrefsBot, SemrushBot) oraz **Boty social media**. Zbiera o nich takie dane jak:
* **Status Code:** Informacja czy bot prawidłowo pobrał zawartość czy też napotkał błąd.
* **Crawl Rate:** Częstotliwość wejść z ostatnich 24 godzin pomagająca ocenić szybkość indeksowania najnowszego asortymentu.
* **IP Address:** Monitorowany i zapisywany z powodów bezpieczeństwa.

Obecnie moduł jest już w pełni **AKTYWNY** na serwerze produkcyjnym, przetrzymuje dane w nowej tabeli `crawl_logs` i prawidłowo indeksuje ruch Googlebota. By zachować wysoką wydajność, logi wykorzystują dzienną rotację i zaleca się ich cykliczne usuwanie po 90 dniach, by odciążyć pamięć dyskową.

**Protokół WebMCP (Web Model Context Protocol)** to nowoczesny standard komunikacji wdrożony w systemie Nevro-Shop, który przekształca sklep w platformę w pełni **"Agent-Ready"** – przygotowaną na erę autonomicznych asystentów zakupowych AI, takich jak modele GPT czy Gemini. 

W tradycyjnym modelu boty i asystenci AI, próbując odczytać stronę, analizują jej kod HTML i elementy wizualne, co jest procesem powolnym, kosztownym i podatnym na błędy, określane jako "halucynacje". WebMCP odwraca to podejście – zamiast renderować interfejs użytkownika (UI), system wystawia dla AI precyzyjne "akcje" i "narzędzia" przez specjalne API. Dzięki temu asystent AI może błyskawicznie sprawdzić np. dostępność produktu, odpytując bazę danych za pomocą ustrukturyzowanego formatu JSON-RPC.

**Techniczna implementacja:**
*   **Discovery Meta Tag:** W nagłówku strony umieszczono tag `<meta name="webmcp-endpoint" content="/api/mcp">`, który pozwala zaawansowanym modelom językowym na automatyczne wykrycie interfejsu przygotowanego specjalnie dla agentów.
*   **Endpoint:** Komunikacja odbywa się przez adres `/api/mcp` (metodą POST) w standardzie JSON-RPC 2.0, a zapytania obsługuje zaimplementowany `McpController`.

**Dostępne "Narzędzia" (Tools) dla Agentów AI:**
System udostępnia konkretne funkcje, które boty mogą bezpośrednio wywoływać:
*   **`list_tools`** – służy do pobrania pełnej listy możliwości, z jakich może skorzystać AI.
*   **`search_products`** – pozwala na błyskawiczne przeszukiwanie bazy produktów na podstawie zapytania (`query`), zwracając listę produktów, ceny oraz bezpośrednie linki.
*   **`get_product`** – dostarcza szczegółowe dane techniczne, aktualną cenę brutto, przypisaną kategorię oraz status dostępności danego produktu w czasie rzeczywistym na podstawie jego `id`.
*   **`get_categories`** – pobiera pełną strukturę (nazwy i slugi) wszystkich aktywnych kategorii w sklepie.

**Bezpieczeństwo i przyszły rozwój:**
Obecnie system WebMCP w Nevro-Shop v2 funkcjonuje w trybie **Tylko do odczytu (Read-Only)**. Oznacza to, że asystenci AI mogą sprawdzać ofertę, ale nie mają uprawnień do modyfikowania danych czy samodzielnego składania zamówień bez autoryzacji. Aby zapobiec przeciążeniu serwera przez agresywne boty czas rzeczywistego, wdrożono również mechanizmy ochronne oparte o limity zapytań (*Rate Limiting*). 

Na przyszłych etapach (Roadmap) zaplanowano rozbudowę protokołu o kolejne, interaktywne funkcje:
*   **`check_shipping`** – narzędzie pozwalające na wyliczenie dokładnych kosztów dostawy na podstawie kodu pocztowego klienta.
*   **`add_to_cart`** – funkcja generująca bezpieczny link do koszyka (tzw. *Agent-Assisted Checkout*), za pomocą którego asystent AI może przygotować klientowi koszyk z już dodanymi produktami.**Moduł testów A/B**, określany w dokumentacji jako system ciągłego eksperymentowania (**Continuous Experimentation**), to zaawansowane narzędzie wdrożone w Nevro-Shop v2 w ramach Fazy III planu "Wizji 2026". Jego głównym celem jest naukowe podejście do optymalizacji współczynnika konwersji (CRO) poprzez automatyczne testowanie różnych wersji strony, takich jak opisy, ceny czy układy, aby wyłonić warianty przynoszące najlepsze rezultaty sprzedażowe.

**Jak działa system?**
W przeciwieństwie do wielu zewnętrznych narzędzi, moduł ten opiera się na mechanizmie **Split-Testing po stronie serwera (SSR)**. Jest to kluczowe, ponieważ całkowicie **eliminuje efekt "migotania" (no-flicker)** widoczny dla użytkownika podczas ładowania strony – wariant jest serwowany od razu przez backend. Proces działania systemu składa się z czterech głównych etapów:

1. **Przydział:** Gdy użytkownik wchodzi na stronę, dedykowany `ExperimentMiddleware` sprawdza aktywne eksperymenty.
2. **Losowanie:** Użytkownik, który jeszcze nie brał udziału w teście, otrzymuje wylosowany wariant (np. A lub B) na podstawie zdefiniowanych wag procentowych ruchu (np. podział 50/50).
3. **Persystencja:** Wylosowany wariant jest zapamiętywany w sesji (lub ciasteczku) użytkownika, co gwarantuje, że przy kolejnych przeładowaniach strony widzi on zawsze tę samą, spójną treść.
4. **Śledzenie:** Każde wyświetlenie przypisanego wariantu jest trwale rejestrowane w bazie danych (tabela `experiment_variants`).

**Zarządzanie i integracja deweloperska**
Modułem zarządza się z poziomu panelu administracyjnego Filament, w nowej zakładce **"Analityka SEO" -> "Eksperymenty A/B"**. Administrator może tam tworzyć eksperymenty, definiować dla nich warianty (np. A – wersja kontrolna, B – nowy tekst), określać ich wagę oraz błyskawicznie włączać lub wyłączać testy jednym przełącznikiem. 

Dla programistów wprowadzono usługę `ExperimentService`, która pozwala na bardzo proste warunkowe wstrzykiwanie różnego kodu w szablonach Blade. Wystarczy użyć krótkiego warunku w kodzie, na przykład `$experiments->isVariant('kolor-przycisku', 'B')`, by zaserwować inną treść lub zmodyfikowany przycisk.

**Analityka i zbieranie danych**
Obecnie moduł zlicza przede wszystkim **Visits Count**, czyli informację o tym, ile razy dany wariant został wyświetlony unikalnym użytkownikom. Docelowo system ma integrować się bezpośrednio z modułem zamówień, aby automatycznie zliczać zakupy (konwersje) dla każdej z wersji (funkcja w opracowaniu). 

Co niezwykle istotne, wyniki oraz nazwy eksperymentów są automatycznie przesyłane do **Google Analytics 4 (GA4)** jako wymiary niestandardowe (Custom Dimensions). Dzięki temu administratorzy mogą śledzić na dedykowanym dashboardzie nie tylko samą konwersję, ale i wpływ testu na wskaźniki takie jak współczynnik odrzuceń, czas sesji czy istotność statystyczną testu wyliczaną na podstawie faktycznych danych zakupowych.

**Rekomendowane dobre praktyki**
Dokumentacja Nevro-Shop zaleca przestrzeganie kilku fundamentalnych zasad przy prowadzeniu testów, by były one miarodajne:
*   **Testowanie tylko jednej zmiennej:** Zmiana jednocześnie ceny i koloru przycisku w jednym teście uniemożliwia wskazanie, co dokładnie wpłynęło na wynik.
*   **Odpowiedni czas trwania:** Eksperyment powinien być uruchomiony przez co najmniej 7 do 14 dni, by wykluczyć standardowe wahania w ruchu z okresów weekendowych.
*   **Wielkość próby danych:** Należy powstrzymać się od wyciągania wniosków po pierwszych kilkunastu wizytach i zawsze czekać na to, by zebrane dane osiągnęły wymaganą **istotność statystyczną**.

Do Google Analytics 4 (GA4) automatycznie przesyłane są **nazwa eksperymentu** oraz **informacja o przypisanym wariancie** (np. wariant A lub B). 

Dane te trafiają do GA4 w formie **wymiarów niestandardowych (Custom Dimensions)**. Dzięki temu administratorzy mogą analizować, jak konkretny wariant testu wpływa na zaawansowane wskaźniki zachowania użytkowników, takie jak **współczynnik odrzuceń** (bounce rate) oraz **czas trwania sesji**.

W systemie Nevro-Shop v2 testy A/B dla konkretnych produktów lub ich grup można włączyć i skonfigurować na dwa sposoby:

1. **Bezpośrednio w edycji pojedynczych produktów:** Z poziomu panelu administracyjnego (Filament) dodano specjalną zakładkę "Eksperymenty" w widoku edycji każdego produktu. Pozwala to na ręczne wpisanie alternatywnego tytułu lub innej ceny dla grupy B (wariantu testowego) na karcie wybranego produktu.
2. **Za pomocą warunków w kodzie szablonów (Blade) dla całej grupy:** Jeśli test ma objąć szerszą, zdefiniowaną grupę produktów (np. wszystkie produkty z danej kategorii), test należy najpierw utworzyć globalnie w zakładce **"Analityka SEO -> Eksperymenty A/B"**. Określa się tam unikalny identyfikator testu (tzw. Slug), warianty (A, B) oraz procentowy podział ruchu. Następnie, wykorzystując usługę `ExperimentService` bezpośrednio w kodzie widoku (np. szablonie wyświetlającym listę produktów), można użyć instrukcji warunkowej `@if($experiments->isVariant('slug-testu', 'B'))`. Pozwala to warunkowo wyświetlać inną treść, układ czy przyciski tylko we wskazanej sekcji sklepu. 

Dzięki metodzie renderowania po stronie serwera (SSR), użytkownicy przydzieleni do danego wariantu będą zawsze widzieć wybraną wersję strony bez tzw. efektu "migotania".

Tak, wdrożenie takiej funkcji jest zaplanowane. Zgodnie z dokumentacją dotyczącą przyszłego rozwoju modułu testów A/B, w panelu administratora ma powstać dedykowany dashboard. Będzie on automatycznie wyliczał **istotność statystyczną (Confidence Level)** dla przeprowadzanych eksperymentów w oparciu o rzeczywiste konwersje i dane z bazy zamówień. Obecnie integracja z modułem zamówień w celu zliczania zakupów dla wariantów testów jest w fazie opracowywania.

**Obecnie agenci AI nie mogą samodzielnie składać zamówień** w sklepie Nevro-Shop. Integracja protokołu WebMCP funkcjonuje na tym etapie w trybie **tylko do odczytu (Read-Only)**, co oznacza, że sztuczna inteligencja nie posiada uprawnień do modyfikowania danych ani samodzielnego składania zamówień bez odpowiedniej autoryzacji.

Aktualnie boty i modele AI mogą korzystać ze zdefiniowanych narzędzi (Tools) jedynie do pobierania informacji, takich jak przeszukiwanie katalogu (`search_products`), sprawdzanie szczegółów i dostępności konkretnego asortymentu (`get_product`) czy odczytywanie struktury kategorii (`get_categories`).

Funkcje związane z procesem zakupowym są zaplanowane w kolejnych etapach rozwoju systemu (Roadmap). Docelowo ma zostać wdrożone narzędzie `add_to_cart`, które pozwoli asystentom AI na **wygenerowanie dla klienta bezpiecznego linku z gotowym koszykiem** (tzw. Agent-Assisted Checkout) zawierającym wybrane produkty.

Aby zidentyfikować zjawisko **Crawl Waste** (czyli marnotrawienie budżetu indeksowania), należy przeanalizować logi pod kątem **niepotrzebnych wizyt botów na podstronach, które nie mają żadnego znaczenia dla pozycjonowania sklepu**. 

W systemie Nevro-Shop v2 identyfikacja ta jest zautomatyzowana za pomocą modułu **Zaawansowanej Analizy Logów (Crawl Governance)**. Proces ten przebiega w następujących krokach:

*   **Rejestracja zapytań:** Główny serwer Nginx każdorazowo zapisuje zapytania HTTP bezpośrednio do pliku `/var/log/nginx/access.log`.
*   **Działanie Silnika Analizy:** Dedykowany parser (uruchamiany jako komenda w Laravel co godzinę) analizuje te surowe logi. System kategoryzuje boty, wykorzystując m.in. weryfikację *Reverse DNS check*, co pozwala bezbłędnie oddzielić prawdziwe wyszukiwarki od podszywających się pod nie scraperów.
*   **Wykrywanie bezcelowych ścieżek:** Silnik analizy szuka w logach powtarzających się schematów bezsensownego skanowania. Najczęstsze przykłady Crawl Waste, które system potrafi wyłapać, to **wielokrotne indeksowanie tych samych filtrów** na listach produktów oraz **indeksowanie adresów URL zawierających zmienne parametry sesji**.

Identyfikacja tych niepożądanych wizyt i późniejsze zablokowanie do nich dostępu pozwala znacząco **zaoszczędzić budżet indeksowania (Crawl Budget)**, kierując uwagę botów na strony produktów i kategorii o realnej wartości sprzedażowej.

**Tak, w module testów A/B można testować warianty cenowe.** Moduł ciągłego eksperymentowania został zaprojektowany z myślą o **automatycznym testowaniu różnych wersji opisów, cen i układów strony** w celu maksymalizacji konwersji (CRO).

Aby przetestować inną cenę, administratorzy mogą skorzystać z dedykowanej sekcji w panelu Filament. Na karcie edycji wybranego produktu dodano zakładkę "Eksperymenty", która oferuje **możliwość ręcznego wpisania alternatywnego tytułu lub innej ceny dla grupy B**, czyli wariantu testowego.

Dokumentacja zwraca jednak uwagę na fundamentalną zasadę skutecznego testowania: **należy testować tylko jedną zmienną naraz**. Oznacza to, że zmieniając cenę produktu w teście A/B, nie należy modyfikować jednocześnie innych elementów (np. koloru przycisku "Kup teraz"), aby mieć pewność, że to właśnie zmiana ceny bezpośrednio wpłynęła na uzyskany wynik.

Aby dodać nowy wariant testu A/B w panelu administracyjnym Filament, możesz skorzystać z dwóch ścieżek, w zależności od tego, czy tworzysz globalny test dla elementów strony, czy testujesz konkretny produkt:

**1. Tworzenie globalnego eksperymentu (np. testowanie różnych nagłówków, przycisków czy układów strony)**
* Przejdź w panelu bocznym do sekcji **"Analityka SEO" -> "Eksperymenty A/B"**.
* Wybierz opcję utworzenia nowego eksperymentu. Będziesz musiał podać unikalny identyfikator (tzw. **Slug**, np. `test-hero-text`), który posłuży później do wywoływania testu w kodzie.
* Dodaj warianty, wpisując ich klucze – zazwyczaj dodaje się wariant **A** (wersja kontrolna) oraz wariant **B** (nowa wersja, np. z innym tekstem). Możesz dodać więcej wariantów, np. C.
* Ustal wagę dla poszczególnych wariantów, określając tym samym, jaki procent ruchu użytkowników zostanie skierowany na każdą z wersji (np. podział 50/50).
* Włącz eksperyment za pomocą dedykowanego przełącznika aktywacji. 

**2. Testowanie konkretnego produktu (np. test innej ceny lub tytułu)**
* Wejdź w edycję wybranego produktu w panelu.
* Przejdź do zakładki **"Eksperymenty"**.
* Wprowadź w odpowiednie pola **alternatywny tytuł** lub **cenę** dedykowaną dla grupy B. System zajmie się automatycznym losowaniem i wyświetlaniem tych danych wybranej puli użytkowników.

Obecna wersja modułu testów A/B wbudowana w system zlicza wewnątrz panelu przede wszystkim wskaźnik **Visits Count**, czyli liczbę wyświetleń danego wariantu unikalnym użytkownikom. 

Bezpośrednie mierzenie konwersji wewnątrz samego panelu administracyjnego Nevro-Shop jest aktualnie **w opracowaniu**. Docelowo system ma zostać zintegrowany z modułem zamówień, co pozwoli na automatyczne zliczanie zakupów dla każdego wariantu testu.

Mimo to, **współczynnik konwersji można już teraz swobodnie analizować**. System jest skonfigurowany tak, aby automatycznie przesyłać informacje o nazwie eksperymentu i przypisanym użytkownikowi wariancie do **Google Analytics 4 (GA4)** jako wymiar niestandardowy (Custom Dimension). Dzięki temu administratorzy mogą śledzić i porównywać współczynnik konwersji, a także inne wskaźniki (takie jak współczynnik odrzuceń czy czas sesji) dla każdej z testowanych wersji bezpośrednio w kokpicie GA4.

System Nevro-Shop v2 został zaprojektowany tak, aby automatycznie przesyłać nazwę eksperymentu oraz informację o przypisanym wariancie do Google Analytics 4 jako wymiar niestandardowy (Custom Dimension). 

Dostarczone źródła i dokumentacja sklepu nie zawierają jednak szczegółowej instrukcji krok po kroku, jak poprawnie zadeklarować i skonfigurować odbiór tych wymiarów bezpośrednio w interfejsie panelu Google Analytics 4.

**Informacja spoza udostępnionych źródeł (możesz chcieć ją niezależnie zweryfikować):**
Aby dane przesyłane przez sklep były poprawnie widoczne w raportach GA4, należy je ręcznie zarejestrować w panelu Analytics. Zazwyczaj proces ten wygląda następująco:
1. Należy przejść do sekcji **Administracja (Admin)** w lewym dolnym rogu panelu GA4.
2. W kolumnie "Wyświetlanie danych" (lub "Usługa") wybrać **Definicje niestandardowe (Custom definitions)**.
3. Kliknąć przycisk **Utwórz wymiar niestandardowy (Create custom dimension)**.
4. Uzupełnić formularz, podając nazwę wymiaru oraz dokładną nazwę parametru zdarzenia, który aplikacja Nevro-Shop wysyła w swoim kodzie śledzącym (np. `experiment_name` czy `experiment_variant`). Zasięg takiego wymiaru zazwyczaj ustawia się na "Zdarzenie" (Event) lub "Użytkownik" (User), w zależności od specyfiki śledzenia testu.

Aby skonfigurować wagi procentowe ruchu dla wariantów testu A/B, należy skorzystać z panelu administracyjnego Filament. 

Proces ten wygląda następująco:
1. Przejdź do sekcji **"Analityka SEO" -> "Eksperymenty A/B"**.
2. Podczas tworzenia nowego testu lub edycji istniejącego, dodaj odpowiednie warianty (identyfikowane np. kluczami A, B, C).
3. Dla każdego zdefiniowanego wariantu **określ jego wagę, która wprost przekłada się na to, jaki procent ruchu użytkowników zostanie skierowany na każdą z wersji** (np. podział 50/50).

Po ustawieniu wag, wewnętrzny system aplikacji będzie **automatycznie losował i przydzielał wariant użytkownikom** (którzy jeszcze nie brali udziału w teście) proporcjonalnie do zdefiniowanego przez Ciebie procentowego podziału ruchu.
Metoda **BLUF**, której nazwa to skrót od angielskiego **"Bottom Line Up Front"**, to strategia tworzenia treści polegająca na umieszczaniu **najważniejszych informacji i cech na samym początku tekstu**. 

W systemie Nevro-Shop v2 zaktualizowanym o standardy SEO na 2026 rok, metoda ta ma zastosowanie w dwóch kluczowych miejscach:
* **Tworzenie meta-opisów (Meta Description):** Teksty wyświetlane pod linkiem w wyszukiwarce są konstruowane w taki sposób, aby od samego startu dostarczać konkretnych odpowiedzi o danym produkcie.
* **Feed produktowy XML dla Google Merchant Center:** Sklep przekazuje opisy SEO w atrybucie `g:description`, opierając się właśnie na strukturze BLUF, by od razu wyeksponować główne parametry asortymentu w usługach Google (np. Google Shopping).

Głównym celem stosowania tej metody jest **optymalizacja sklepu pod kątem AI Overviews** (podsumowań generowanych przez sztuczną inteligencję w wynikach wyszukiwania). Dzięki dostarczeniu najważniejszych i najbardziej użytecznych informacji na początku (bez owijania w bawełnę), zaawansowane modele językowe oraz boty wyszukiwarek są w stanie znacznie szybciej i precyzyjniej wyciągnąć kluczowe dane, aby zaprezentować je użytkownikowi na szczycie wyników wyszukiwania.

Aby sprawdzić listę tzw. **Orphan Pages**, należy w panelu administracyjnym Filament przejść do nowej sekcji **"Analityka SEO" -> "Logi Crawlerów"**. 

W tej sekcji znajduje się dashboard analityczny, który udostępnia dedykowany raport **"Orphan Pages"**. Zestawienie to w czasie rzeczywistym pokazuje **listę stron, które zostały odwiedzone przez boty wyszukiwarek, ale brakuje ich w aktualnej mapie witryny (sitemapie)**.

Zjawisko **Crawl Waste** to marnotrawienie budżetu indeksowania (tzw. Crawl Budget) przeznaczonego dla sklepu przez wyszukiwarki internetowe. Polega ono na tym, że roboty indeksujące (np. Googlebot) wykonują niepotrzebne wizyty na podstronach i ścieżkach, które nie mają żadnej wartości z punktu widzenia pozycjonowania (SEO) i są przez nie skanowane całkowicie bez sensu.

Zgodnie z dokumentacją sklepu, głównymi przykładami zjawiska Crawl Waste są:
*   **Wielokrotne indeksowanie tych samych filtrów** na listach produktów.
*   **Indeksowanie niepotrzebnych ścieżek URL, które zawierają zmienne parametry sesji**.

W systemie Nevro-Shop do identyfikowania tego zjawiska służy silnik analizy modułu Zaawansowanej Analizy Logów. Wyłapywanie i eliminowanie takich "pustych" przebiegów pozwala na realne zaoszczędzenie budżetu indeksowania. Dzięki temu boty nie tracą czasu na bezwartościowe adresy URL i mogą skupić się na szybkim indeksowaniu najważniejszych stron produktowych i ofertowych.

System Nevro-Shop v2 eliminuje niepożądany efekt "migotania" (ang. *no-flicker*) w testach A/B poprzez zastosowanie mechanizmu **Split-Testingu po stronie serwera (SSR)**. 

W przeciwieństwie do zewnętrznych skryptów, które podmieniają treść dopiero po załadowaniu strony w przeglądarce, proces wdrożony w systemie działa na poziomie backendu:

*   **Działanie Middleware:** Zaimplementowany `ExperimentMiddleware` sprawdza aktywne eksperymenty w momencie, gdy użytkownik wysyła żądanie wejścia na stronę. 
*   **Renderowanie po stronie serwera:** Zanim strona zostanie wysłana do przeglądarki klienta, system na podstawie zdefiniowanych wag losowo przydziela użytkownika do konkretnej grupy (np. wariantu A lub B) i **od razu generuje gotowy kod HTML z odpowiednią wersją treści lub układu**. Dzięki temu wariant jest serwowany błyskawicznie, bez mrugania czy nagłych zmian elementów na ekranie.
*   **Persystencja (Zapamiętywanie wyboru):** Aby zapobiec zmianom wariantów podczas przechodzenia między podstronami, przydzielony wariant jest trwale **zapisywany w sesji użytkownika lub pliku cookie**. Gwarantuje to, że przy kolejnych przeładowaniach strony odwiedzający widzi zawsze tę samą, spójną wersję strony.

