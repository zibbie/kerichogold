# 🏗️ WIELKA KSIĘGA ZARZĄDZANIA: NEVRO-SHOP v2
## Kompletny przewodnik operacyjny dla właściciela sklepu

Witaj w kompletnym manualu Twojego systemu e-commerce. Ten dokument został stworzony, abyś nigdy nie musiała zgadywać "do czego służy ten przycisk". Opisuje on każdy element panelu administracyjnego (Filament) w sposób precyzyjny i wyczerpujący.

---

## 🏛️ ROZDZIAŁ 1: KOMPLEKSOWA OBSŁUGA ASORTYMENTU (PRODUKTY)
Moduł: **Zarządzanie sklepem -> Produkty**

Ten moduł to serce Twojego sklepu. Każda karta produktu została zaprojektowana tak, aby maksymalnie wspierać sprzedaż oraz automatyzację logistyki i marketingu Google.

### 1.1 Sekcja: Podstawowe Informacje (Fundament Produktu)
Tu definiujesz to, co klient widzi jako pierwsze.
*   **Nazwa (Wymagane)**: Pełna nazwa produktu. 
    *   *Dobra praktyka:* Używaj schematu: [Produkt] + [Model/Typ] + [Kluczowy parametr] (np. "Zbiornik IBC 1000l na palecie hybrydowej"). Pamiętaj, że system na tej podstawie automatycznie utworzy adres URL (slug).
*   **SKU (Kod produktu)**: Unikalny identyfikator towaru (np. `IBC-1000-NEW`). Musi być unikalny w skali całego sklepu. Jest niezbędny do poprawnej synchronizacji z systemami magazynowymi i Google Merchant Center.
*   **Opis**: To tutaj budujesz wartość produktu. Używaj Edytora (Rich Text), aby:
    *   Dodawać pogrubienia dla najważniejszych cech.
    *   Tworzyć listy punktowe (bullet points), które klienci czytają najchętniej.
    *   Wstawiać linki do powiązanych produktów lub instrukcji PDF.

### 1.2 Sekcja: Cena i Magazyn (Logika Finansowa)
*   **Cena**: Cena brutto sprzedaży w PLN. System automatycznie przelicza ją na potrzeby feedu Google.
*   **Ilość**: Aktualny stan magazynowy. 
    *   *Inteligentne powiadomienia:* Jeśli stan spadnie poniżej 5 sztuk, na liście produktów zobaczysz czerwoną liczbę – to sygnał, że pora na dostawę.
*   **Widoczny w sklepie (Status)**: Główny wyłącznik. Jeśli produkt jest "nieaktywny", znika z wyszukiwarki i menu, ale nadal istnieje w panelu.
*   **Bestseller (Hit)**: Włączenie tej opcji dodaje elegancką etykietę "HIT" na zdjęciu produktu i promuje go w specjalnych sekcjach na stronie głównej.
*   **Eksportuj do Google Merchant Center**: 
    *   **WAŻNE:** Jeśli zaznaczysz tę opcję, produkt trafi do pliku XML, który Google pobiera do Twoich reklam Zakupy. Wyłącz to tylko dla produktów, których nie chcesz reklamować (np. części zamienne lub produkty o zerowej marży).
*   **Kategoria**: Wybór kategorii głównej. Produkt może należeć do jednej kategorii, która definiuje jego położenie w menu i stawkę wysyłkową.

### 1.3 Sekcja: Media (Galeria i Wizerunek)
*   **Zdjęcie główne**: To "twarz" produktu. Najlepiej wgrywać zdjęcia na białym tle (wymóg Google Ads). System automatycznie skompresuje je do formatu WebP, aby sklep działał szybko.
*   **Galeria zdjęć**: Pozwala dodać wiele ujęć. Możesz tu dodawać zdjęcia detali, certyfikatów czy tabliczek znamionowych.
    *   *Reorder:* Możesz złapać dowolne zdjęcie w galerii i zmienić jego kolejność. Pierwsze zdjęcie z galerii pojawi się jako drugie po najechaniu myszką na produkt na liście (tzw. hover effect).

### 1.4 Sekcja: Dostawa i Wysyłka (Logistyka)
Ten moduł automatyzuje obliczanie kosztów wysyłki w koszyku.
*   **Czas dostawy**: Wybierz jedną z opcji (24h, 3 dni, 14 dni). Informacja ta wyświetla się bezpośrednio przy przycisku "Do koszyka", budując zaufanie klienta.
*   **Klasa wysyłkowa**: Kluczowy parametr dla koszyka. Wybierz odpowiedni gabaryt:
    *   `InPost Gabaryt A/B/C`: Dla przesyłek paczkomatowych.
    *   `Kurier Standard/Ciężki`: Dla standardowych paczek.
    *   `Paleta (260.00 zł)`: Wybierz to dla zbiorników IBC i ciężkich towarów. Koszt zostanie doliczony do całego zamówienia.
*   **Sztuk w paczce**: Określ, ile sztuk tego produktu mieści się w jednej paczce o danej klasie wysyłkowej. Jeśli klient kupi więcej, system automatycznie podwoi lub potroi koszt wysyłki.

### 1.5 Sekcja: SEO (Widoczność w Google)
Sekcja domyślnie zwinięta, dla zaawansowanych użytkowników.
*   **URL (slug)**: Końcówka adresu (np. `moj-produkt-ibc`). System generuje ją z nazwy, ale możesz ją skrócić dla lepszego efektu.
*   **Tytuł SEO**: Jeśli chcesz, by w Google produkt nazywał się inaczej niż w sklepie (np. krócej).
*   **Opis SEO (Meta Description)**: To tekst pod linkiem w wyszukiwarce. Napisz tu coś, co przebije ofertę konkurencji (np. "Dostawa 24h. Sprawdź cenę!").
*   **Słowa kluczowe**: Oddzielone przecinkami frazy pomocnicze dla wyszukiwarek.
*   **Canonical URL**: Używaj tylko jeśli ten sam produkt występuje pod dwoma adresami (zapobiega to karom od Google za powieloną treść).

### 1.6 Działania Masowe (Praca na zbiorach)
Na liście produktów możesz zaznaczyć wiele pozycji i użyć menu "Akcje masowe":
*   **Zmień kategorię**: Pozwala przenieść 100 produktów do nowej kategorii jednym kliknięciem.
*   **Usuń zaznaczone**: Masowe czyszczenie asortymentu.

---

## 🛍️ ROZDZIAŁ 2: OPERACYJNA OBSŁUGA ZAMÓWIEŃ I PŁATNOŚCI
Moduł: **Zarządzanie sklepem -> Zamówienia**

Ten moduł służy do zarządzania relacjami z klientem od momentu złożenia zamówienia do jego finalizacji. System automatyzuje większość procesów, ale daje Ci pełną kontrolę nad edycją danych w sytuacjach wyjątkowych.

### 2.1 Cykl Życia Zamówienia (Statusy)
Status zamówienia definiuje, na jakim etapie znajduje się transakcja i jakie powiadomienia otrzymuje klient.
*   **Oczekujące (Pending)**: Zamówienie zostało złożone, ale nie ma jeszcze potwierdzenia płatności. System rezerwuje towar w magazynie na określony czas.
*   **W trakcie realizacji (Processing)**: Płatność została potwierdzona (lub wybrano pobranie). To sygnał dla Ciebie, że należy skompletować towar i przygotować paczkę.
*   **Wysłane (Shipped)**: Towar opuścił magazyn. W tym momencie klient otrzymuje e-mail z potwierdzeniem wysyłki.
*   **Zakończone (Completed)**: Paczka została odebrana, a transakcja jest sfinalizowana.
*   **Anulowane (Cancelled)**: Używaj tego statusu, jeśli klient zrezygnował lub płatność nie została dokonana w terminie. Stan magazynowy produktów zostanie automatycznie przywrócony.

### 2.2 Szczegółowa Karta Zamówienia
W edycji zamówienia znajdziesz cztery główne sekcje:

#### A. Podsumowanie zamówienia
*   **Numer zamówienia**: Unikalny kod (np. `ORD-2026-0001`). Jest generowany automatycznie i nie można go zmieniać. Służy jako tytuł przelewu.
*   **Suma**: Łączna kwota do zapłaty (produkty + wysyłka). Pole zablokowane do edycji, aby zachować spójność z płatnością.
*   **Status**: Tutaj ręcznie zmieniasz etap zamówienia.

#### B. Dane Klienta i Adresy
*   **Dane kontaktowe**: Imię, nazwisko, e-mail oraz telefon. 
    *   *Uwaga:* E-mail musi być poprawny, bo system wysyła na niego automatyczne potwierdzenia i linki do płatności.
*   **Adres dostawy**: Pełne dane adresowe. Możesz je edytować, jeśli klient zadzwoni i zgłosi pomyłkę w numerze domu lub kodzie pocztowym.
*   **Miasto i Kod pocztowy**: Pola te są kluczowe dla kurierów – upewnij się, że są wypełnione przed wysyłką.

#### C. Płatność i Wysyłka
*   **Metoda płatności**: Informacja, czy wybrano Przelewy24, przelew tradycyjny czy pobranie.
*   **Status płatności**: 
    *   `Oczekuje`: Brak środków.
    *   `Opłacone`: Środki są w systemie. 
    *   `Błąd`: Klient przerwał płatność lub została ona odrzucona przez bank.
*   **Metoda wysyłki**: Np. "InPost Paczkomat" lub "Kurier Paleta".
*   **Koszt wysyłki**: Kwota naliczona automatycznie na podstawie klas wysyłkowych z Rozdziału 1.

#### D. Dokument Sprzedaży (Faktura VAT)
*   **Prośba o fakturę VAT**: Jeśli klient zaznaczył to w koszyku, przełącznik będzie aktywny.
*   **NIP**: Pole widoczne tylko przy prośbie o fakturę. Zawiera numer NIP podany przez klienta. Jest to sygnał, że należy wystawić fakturę firmową zamiast paragonu imiennego.

### 2.3 System Płatności Przelewy24 (Automatyzacja)
Twój sklep jest zintegrowany z bramką Przelewy24 za pomocą tzw. "Webhooków".
*   **Jak to działa?**: Gdy klient płaci BLIK-iem, system Przelewy24 wysyła "ukryty sygnał" do Twojego sklepu. System natychmiast zmienia status płatności na `Opłacone` i status zamówienia na `Realizacja`.
*   **Troubleshooting**: Jeśli klient twierdzi, że zapłacił, a status to nadal `Oczekujące`, może to oznaczać opóźnienie w banku. Sprawdź panel Przelewy24. Jeśli wpłata tam jest, możesz ręcznie zmienić status w panelu sklepu.

### 2.4 Wysyłka InPost i Kurierzy
*   **InPost Paczkomat**: Jeśli klient wybrał tę opcję, w danych adresowych lub w uwagach znajdziesz kod paczkomatu (np. `KRA14A`). Ten kod musisz wpisać w Managerze Paczek InPost przy generowaniu etykiety.
*   **Wysyłki Paletowe**: Dla produktów oznaczonych klasą `Paleta`, koszt wysyłki jest stały i wysoki. Upewnij się, że przy takich zamówieniach masz poprawny numer telefonu klienta dla kuriera.

### 2.5 Pozycje Zamówienia (Relacje)
W dolnej części karty zamówienia znajduje się lista produktów, które kupił klient.
*   Możesz sprawdzić jednostkową cenę zakupu oraz ilość.
*   Jeśli klient chce zmienić zamówienie (np. dołożyć drugą sztukę), możesz edytować listę produktów bezpośrednio w tym miejscu (jeśli status na to pozwala).

---

## 📁 ROZDZIAŁ 3: ARCHITEKTURA KATEGORII I TAKSONOMIA GOOGLE
Moduł: **Zarządzanie sklepem -> Kategorie**

Kategorie w Twoim sklepie pełnią potrójną rolę: porządkują asortyment dla klienta, definiują strukturę menu oraz dostarczają danych dla algorytmów Google.

### 3.1 Zarządzanie Strukturą i Hierarchią
System obsługuje drzewiastą strukturę kategorii (kategorie nadrzędne i podkategorie).
*   **Kategoria Nadrzędna**: Pozwala grupować mniejsze działy. Jeśli kategoria nie ma wybranego "Rodzica", jest traktowana jako kategoria główna.
*   **Aktywność**: Przełącznik "Aktywna" pozwala ukryć całe działy sklepu (np. ofertę sezonową) bez usuwania produktów.
*   **Slug (URL)**: Podobnie jak przy produktach, system tworzy adres automatycznie. 
    *   *Uwaga:* Kategorie często pozycjonują się w Google lepiej niż pojedyncze produkty, dlatego raz ustalonego adresu kategorii (np. `zbiorniki-ibc`) nie należy zmieniać.

### 3.2 Taksonomia Google (Google Product Category ID)
To pole jest kluczem do sukcesu Twoich kampanii reklamowych.
*   **Czym jest GPC?**: To oficjalna lista tysięcy kategorii zdefiniowanych przez Google. Każdy produkt wysyłany do reklam musi mieć przypisany taki kod.
*   **Jak to działa w Nevro-Shop?**: Zamiast ustawiać kod dla każdego produktu z osobna, ustawiasz go raz w kategorii głównej. Wszystkie produkty w tej kategorii automatycznie "odziedziczą" ten kod.
*   **Przykład**: Dla kategorii "Akcesoria IBC" wpisujesz kod `505315`. Dzięki temu Google wie, że Twoje produkty to "Części hydrauliczne", a nie np. zabawki, co drastycznie zwiększa skuteczność reklam.

### 3.3 Wizualizacja i Menu
*   **Ikona**: Możesz wybrać ikonę (np. `faucet`, `truck`, `cog`), która pojawi się w menu bocznym obok nazwy kategorii. Ikony sprawiają, że sklep wygląda nowocześnie i jest bardziej czytelny.
*   **Zdjęcie kategorii**: Możesz wgrać grafikę, która pojawi się na górze strony danej kategorii. To idealne miejsce na profesjonalne zdjęcie produktowe "w akcji".
*   **Opis kategorii (Rich Text)**: Sekcja ta służy do opisu działu. 
    *   *Dobra praktyka:* Umieść tutaj tekst o długości 200-300 słów zawierający słowa kluczowe. To właśnie ten tekst sprawia, że kategoria pojawia się wysoko w Google na ogólne zapytania (np. "akcesoria do zbiorników").

### 3.4 Zarządzanie Kolejnością (Nawigacja)
System automatycznie generuje menu górne na podstawie Twoich kategorii.
*   **Zasada 8 kategorii**: Sklep wyświetla w menu głównym maksymalnie 8 pierwszych aktywnych kategorii głównych.
*   **Przeciągnij i upuść (Drag & Drop)**: Na liście kategorii w panelu Filament, po lewej stronie nazwy, znajduje się ikona trzech kresek. 
    *   Złap ją myszką i przesuń kategorię w górę lub w dół. 
    *   Kolejność, którą ustawisz w panelu, zostanie natychmiast odzwierciedlona w menu górnym sklepu.

### 3.5 SEO Kategorii
Kategorie to "fundamenty" Twojej widoczności.
*   **Meta Title**: Powinien być szerszy niż nazwa kategorii, np. "Akcesoria do Zbiorników IBC 1000l - Sklep Internetowy".
*   **Meta Description**: Napisz, co wyróżnia Twoją ofertę w tym dziale: "Szeroki wybór zaworów, kranów i redukcji do paletopojemników IBC. Wszystko dostępne od ręki z wysyłką 24h!".

---

## 🖼️ ROZDZIAŁ 4: WIZERUNEK, DESIGN I TREŚCI (CMS)
Moduł: **Zarządzanie treścią -> Strony CMS / Banery / CTA**

Wizerunek Twojego sklepu buduje zaufanie. Ten rozdział opisuje, jak zarządzać elementami wizualnymi i treściami informacyjnymi, aby sklep zawsze wyglądał profesjonalnie i aktualnie.

### 4.1 Strony CMS (Instrukcje, Regulaminy, O nas)
Zakładka **Strony CMS** służy do tworzenia treści, które nie są produktami, ale są niezbędne dla klienta.
*   **Tworzenie nowej strony**: System automatycznie stworzy adres URL (slug) na podstawie tytułu.
*   **Zaawansowany Edytor (Rich Editor)**: 
    *   To narzędzie pozwala Ci na pełną swobodę. Możesz wklejać teksty z Worda, dodawać zdjęcia bezpośrednio do treści, a nawet tworzyć tabele.
    *   *Tip:* Używaj nagłówków (H2, H3), aby długie teksty (np. Regulamin) były czytelne dla klienta i botów Google.
*   **Widoczność (Gdzie pojawi się link?)**: 
    *   **Widoczna w stopce**: Link do strony trafi do dolnej części sklepu (np. Polityka Prywatności).
    *   **Automatyczne Menu**: System wybiera dwie pierwsze aktywne strony z tym znacznikiem i umieszcza je w górnym menu obok kategorii (zazwyczaj są to "Kontakt" i "O nas").
*   **SEO Strony**: Każda strona CMS ma własne meta-tagi. Jeśli tworzysz np. stronę o serwisie zbiorników, zadbaj o unikalny Meta Title, aby klienci szukający serwisu trafili prosto do Ciebie.

### 4.2 Hero Banner (Wielka Witryna Sklepu)
To pierwszy element, który widzi klient po wejściu na `nevro-wm.pl`.
*   **Zarządzanie grafiką**: Wgrywaj zdjęcia w wysokiej rozdzielczości (rekomendowane 1920x600 px). 
*   **Efekt Glassmorphism**: To unikalna funkcja Twojego sklepu. Jeśli Twoje zdjęcie banerowe jest bardzo jasne lub wielokolorowe, włącz tę opcję. Pod tekstem pojawi się elegancki efekt "szronionego szkła", który sprawi, że napisy będą zawsze czytelne i ekskluzywne.
*   **Teksty i Przyciski**: 
    *   **Tytuł i Podtytuł**: Powinny być krótkie i uderzające (np. "Wiosenne Promocje na IBC").
    *   **CTA (Call to Action)**: Tekst na przycisku (np. "Sprawdź teraz") oraz link, gdzie klient ma trafić po kliknięciu.

### 4.3 Boksy Pomocy (Home CTA)
W pasku bocznym lub na dole strony głównej znajdują się boksy zachęcające do kontaktu.
*   **Ikona**: Możesz wybrać ikonę słuchawek, e-maila lub mapy.
*   **Treść**: Krótkie hasło (np. "Potrzebujesz pomocy technicznej?").
*   **Przycisk**: Link prowadzący bezpośrednio do Twojej strony kontaktowej lub numeru telefonu.

### 4.4 Zarządzanie Stopką (Footer)
W sekcji **Footer** możesz zarządzać informacjami prawnymi i linkami społecznościowymi.
*   **Dane firmy**: Upewnij się, że NIP i adres są zawsze aktualne.
*   **Social Media**: Możesz włączyć/wyłączyć ikony Facebooka czy Instagrama, podając linki do swoich profili.

---

---
## 📊 ROZDZIAŁ 5: ANALITYKA SEO, LOGI AI I EKSPERYMENTY A/B
Moduł: **Analityka SEO -> Logi Crawlerów / Eksperymenty A/B**

Ten rozdział opisuje najbardziej zaawansowane narzędzia Twojego sklepu. Dzięki nim nie musisz zgadywać, czy Twoje działania marketingowe działają – system dostarcza Ci twardych danych prosto z "pierwszej linii frontu" internetu.

### 5.1 Monitoring Botów (Crawl Logs)
Każda wizyta robota (crawlera) na Twojej stronie jest rejestrowana. To pozwala Ci "widzieć" sklep oczami algorytmów.
*   **Kto Cię odwiedza?**: 
    *   **Googlebot**: Najważniejszy gość. Jeśli widzisz go często, oznacza to, że Twoje produkty są szybko indeksowane.
    *   **OpenAI (GPTBot)**: Robot zasilający ChatGPT. Dzięki jego wizytom Twoje produkty mogą być podpowiadane użytkownikom szukającym informacji w AI.
    *   **Bing/Ahrefs**: Inne wyszukiwarki i narzędzia analityczne.
*   **Interpretacja statusów (Kolumna Status)**:
    *   **200 (Zielony)**: Wszystko jest w porządku, robot pomyślnie pobrał stronę.
    *   **404 (Czerwony)**: Robot trafił na nieistniejącą stronę. Jeśli widzisz tu dużo błędów, sprawdź, czy nie usunęłaś jakiejś kategorii bez przekierowania.
    *   **500 (Ciemny czerwony)**: Błąd serwera. To sygnał do natychmiastowego kontaktu z działem technicznym.
*   **URL wizyty**: Zobaczysz dokładnie, które produkty boty sprawdzają najczęściej. To informacja, co Google uważa za najbardziej wartościowe w Twoim sklepie.

### 5.2 Eksperymenty A/B (Optymalizacja Konwersji)
Moduł ten pozwala na prowadzenie naukowych testów Twoich treści. Zamiast zastanawiać się, czy baner z napisem "Kup teraz" działa lepiej niż "Sprawdź cenę", możesz to po prostu sprawdzić.
*   **Tworzenie eksperymentu**: Nadajesz mu nazwę (np. "Kolor przycisku na start") i określasz, czy jest aktywny.
*   **Warianty A i B**:
    *   Definiujesz dwa (lub więcej) warianty treści.
    *   **Wagi (%)**: Możesz ustawić, aby połowa klientów (50%) widziała wariant A, a druga połowa (50%) wariant B.
*   **Jak czytać wyniki?**: System mierzy interakcje użytkowników z każdym wariantem. Po tygodniu lub miesiącu będziesz wiedziała, która wersja przynosi więcej kliknięć lub zamówień. Dzięki temu stale zwiększasz zysk bez podnoszenia wydatków na reklamy.

### 5.3 Strategia SEO Jutra (AI Search)
Tradycyjne wyszukiwarki zmieniają się w systemy odpowiedzi AI. Twoje logi crawlerów są dowodem na to, czy Twój sklep bierze udział w tej rewolucji.
*   Jeśli widzisz wizyty `OpenAI` lub `ClaudeBot`, oznacza to, że Twoje unikalne opisy produktów stają się częścią bazy wiedzy sztucznej inteligencji. 
*   **Wniosek:** Pisz opisy tak, jakbyś odpowiadała na pytania klienta. Zamiast suchych danych, używaj naturalnych zdań (np. "Ten zawór IBC pasuje idealnie do większości zbiorników 1000l dostępnych na rynku polskim").

---

## ⚙️ ROZDZIAŁ 6: USTAWIENIA GLOBALNE, PRYWATNOŚĆ I ADMINISTRACJA
Moduł: **Ustawienia -> Ustawienia Globalne / Cookie Consent / Użytkownicy**

Ten rozdział opisuje "panel sterowania" całego systemu. Zmiany tutaj wprowadzane wpływają na działanie techniczne całego sklepu oraz jego zgodność z przepisami prawa (RODO).

### 6.1 Ustawienia Globalne (Techniczne Serce Sklepu)
W tym module zarządzasz kluczowymi identyfikatorami i grafikami systemowymi.
*   **Google Analytics 4 (GA4) ID**: Tu wpisujesz swój kod w formacie `G-XXXXXXXXXX`. Po zapisaniu system automatycznie zacznie przesyłać dane o ruchu do Twojego panelu Google Analytics.
*   **Google Ads ID**: Kod konwersji (format `AW-XXXXXXXXX`). Niezbędny, jeśli chcesz mierzyć skuteczność płatnych kampanii reklamowych.
*   **Google Tag Manager (GTM) ID**: Opcjonalne pole, jeśli korzystasz z zaawansowanych tagów śledzących.
*   **Zarządzanie Logotypami**: 
    *   Możesz tu samodzielnie podmienić logo w nagłówku (format panoramiczny) oraz ikonę strony (favicon – widoczna na karcie przeglądarki).
    *   *Tip:* Używaj plików PNG z przezroczystym tłem, aby logo idealnie komponowało się z designem strony.

### 6.2 Prywatność i Cookie Consent (RODO)
Zgodnie z prawem UE każdy klient musi wyrazić zgodę na używanie ciasteczek (cookies). Twój sklep posiada wbudowany, inteligentny system zarządzania tymi zgodami.
*   **Aktywność**: Możesz włączyć lub wyłączyć cały banner (niezalecane – banner musi być aktywny dla legalnego zbierania danych).
*   **Tytuł i Treść komunikatu**: Możesz dowolnie edytować tekst, który widzi klient. Staraj się pisać przystępnym językiem.
*   **URL Polityki Prywatności**: Tutaj wklej link do strony CMS, na której opisałaś zasady przetwarzania danych (patrz: Rozdział 4).
*   **Jak to działa?**: System blokuje kody Google Analytics do momentu, aż klient kliknie "Akceptuję". Dzięki temu Twój sklep jest w 100% zgodny z RODO.

### 6.3 Zarządzanie Użytkownikami (Personel)
Jeśli zatrudniasz pracowników, możesz nadać im dostęp do panelu Filament.
*   **Tworzenie konta**: Wymagane jest podanie imienia, nazwiska oraz adresu e-mail.
*   **Hasło**: Każdy użytkownik powinien mieć własne, silne hasło. Jako administrator masz prawo resetować hasła swoim pracownikom.
*   **Bezpieczeństwo**: Nigdy nie pracuj na jednym wspólnym koncie. Indywidualne konta pozwalają uniknąć błędów i zapewniają przejrzystość zmian w systemie.

### 6.4 Ustawienia Regionalne i Kontaktowe
W dolnej części ustawień możesz zdefiniować:
*   **Dane kontaktowe w stopce**: Adres e-mail, numer telefonu i adres stacjonarny firmy. Zmiana tutaj automatycznie zaktualizuje te dane w całym sklepie.
*   **Social Media**: Linki do Twoich profili na Facebooku czy Instagramie. Jeśli zostawisz pole puste, ikona danego serwisu po prostu zniknie ze stopki.

---

## 🆘 ROZDZIAŁ 7: ROZWIĄZYWANIE PROBLEMÓW I SŁOWNIK POJĘĆ
Moduł: **Wsparcie Operacyjne i Baza Wiedzy**

Ten rozdział to Twój "zeszyt ratunkowy". Znajdziesz tu odpowiedzi na najczęstsze pytania oraz wyjaśnienie terminologii, która może pojawić się podczas zarządzania sklepem lub rozmów z zewnętrznymi partnerami.

### 7.1 CO ZROBIĆ, GDY...? (Troubleshooting)

#### Sytuacja 1: Klient zapłacił, ale zamówienie nadal ma status "Oczekujące"
*   **Przyczyna**: Systemy płatności (Przelewy24) czasami mają opóźnienie w wysyłaniu notyfikacji technicznej (tzw. Webhooka).
*   **Rozwiązanie**: Zaloguj się do panelu administracyjnego Przelewy24. Jeśli tam płatność ma status "Zakończona", wejdź w zamówienie w swoim sklepie i ręcznie zmień status płatności na `Opłacone`. System wtedy automatycznie aktywuje dalsze kroki (np. powiadomienie dla klienta).

#### Sytuacja 2: Zdjęcia produktów nie wyświetlają się (ikona "zepsutego" obrazka)
*   **Przyczyna**: Najczęściej jest to wynik błędnej ścieżki po masowym imporcie lub braku tzw. symlinku na serwerze.
*   **Rozwiązanie**: Sprawdź w edycji produktu, czy zdjęcie jest wgrane. Jeśli tak, a nadal go nie widać, skontaktuj się z administratorem, aby odświeżył "linkowanie plików" (komenda `storage:link`). Unikaj też wgrywania zdjęć z polskimi znakami lub spacjami w nazwie pliku.

#### Sytuacja 3: Produkt nie pojawia się w Google Zakupy (GMC)
*   **Przyczyna**: Google odrzuca produkt ze względu na brak wymaganych danych (np. numeru EAN, opisu lub ceny).
*   **Rozwiązanie**: Sprawdź w panelu Google Merchant Center sekcję "Diagnostyka". Upewnij się, że w Twoim sklepie przełącznik "Eksportuj do GMC" jest aktywny oraz że kategoria produktu ma przypisane poprawne `Google Product Category ID`.

#### Sytuacja 4: Nie mogę się zalogować do panelu
*   **Przyczyna**: Zapomniane hasło lub blokada IP po wielokrotnych błędnych próbach.
*   **Rozwiązanie**: Użyj opcji "Zapomniałem hasła" na ekranie logowania. Jeśli to nie zadziała, drugi administrator (lub Twój opiekun techniczny) może zresetować Twoje hasło w zakładce **Użytkownicy**.

### 7.2 SŁOWNIK PROFESJONALNEGO SPRZEDAWCY (E-commerce Glossary)

*   **Slug**: To przyjazna dla człowieka końcówka adresu URL (np. `zawor-ibc`). Ważna dla SEO.
*   **Meta Description**: Krótki opis strony widoczny w Google pod linkiem. Ma kluczowe znaczenie dla klikalności (CTR).
*   **Crawl / Crawler**: Robot (np. Googlebot), który "czyta" Twoją stronę i dodaje ją do wyszukiwarki.
*   **GMC (Google Merchant Center)**: Narzędzie Google, do którego Twój sklep wysyła listę produktów (feed), aby mogły one wyświetlać się jako reklamy ze zdjęciem i ceną.
*   **GPC (Google Product Category)**: Numeryczny kod kategorii według standardu Google. Precyzuje, czym dokładnie jest Twój produkt (np. częścią hydrauliczną).
*   **Webhook**: Automatyczny sygnał "komputer-do-komputera". Dzięki niemu sklep wie o wpłacie klienta bez Twojego udziału.
*   **SSL (Certyfikat)**: Zielona kłódka przy adresie strony. Gwarantuje, że dane Twoich klientów (hasła, adresy) są szyfrowane i bezpieczne.
*   **Canonical (Link Kanoniczny)**: Informacja dla Google, który adres strony jest nadrzędny. Zapobiega to problemom z "powieloną treścią".
*   **Conversion Rate (Współczynnik Konwersji)**: Procent osób odwiedzających sklep, które faktycznie dokonały zakupu. Twoim celem jest, aby był jak najwyższy.

### 7.3 Zasady Higieny Cyfrowej
1.  **Hasła**: Używaj unikalnych haseł do panelu sklepu, innych niż do Twojej prywatnej poczty czy banku.
2.  **Backupy**: System wykonuje kopie bazy danych automatycznie, ale po dużych zmianach (np. dodaniu 1000 produktów) warto poprosić o manualny zrzut bazy.
3.  **RODO**: Nigdy nie wysyłaj bazy e-maili klientów niezaszyfrowanymi kanałami. Pamiętaj, że dane Twoich klientów to Twoja odpowiedzialność prawna.

---
*Nevro-Shop v2 — Kompendium Wiedzy (Ostatnia aktualizacja: 11.05.2026).*
