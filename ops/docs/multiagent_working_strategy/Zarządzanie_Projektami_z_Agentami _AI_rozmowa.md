Jestes Agentem Orchestratorem. Jak zastosujesz zasady z zalaczonego pliku do zarzadzania projektem i pracą z 5 agetami, ktorymi zarzadzasz?

### 1. Inicjacja i Podział Zadań

* Wykorzystam tryb Planning Mode do zbadania architektury i stworzenia głównego planu implementacji.
* Rozbiję projekt na mniejsze klastry (backend, frontend, testy) i przypiszę im identyfikatory (np. B1, F1), delegując je do poszczególnych agentów, aby rozwiązywali czyste podproblemy.

### 2. Trasowanie Modeli i Trybów

* Agentom zajmującym się prostymi edycjami (np. zmiany nazw, style, szybki refaktor) przydzielę model zoptymalizowany pod kątem szybkości (np. Gemini 3 Flash) i uruchomię ich w Fast Mode.
* Agentom odpowiedzialnym za kluczowe decyzje architektoniczne i złożoną logikę przydzielę silniejszy model (np. Gemini 3 Pro) i uruchomię ich w Planning Mode.
* Niezależne od siebie moduły zlecę agentom do pracy równoległej, tworząc wydajny klaster obliczeniowy.

### 3. Zarządzanie Kontekstem i Limitami

* Każdy agent otrzyma własny, odizolowany wątek dla swojej linii pracy, z krótkim podsumowaniem (handoff) przy przejęciu zadania.
* Pracując w Antigravity Desktop, zawężę kontekst agentów wyłącznie do aktualnie otwartych plików, celowo blokując indeksowanie całego projektu, aby zoptymalizować zużycie zasobów.
* Zdefiniuję trwałe instrukcje (Rules/Workflows) dla każdego workspace'u, aby zautomatyzować preferencje dotyczące stylu kodu u wszystkich agentów.

### 4. Pętle Zwrotne i Kontrola

* Zamiast czekać na gotowy kod, będę weryfikował pracę agentów na bieżąco poprzez generowane przez nich artefakty, takie jak plany i diffy.
* Wprowadzę szybkie korekty natychmiast po wykryciu nieścisłości, zapobiegając powstawaniu dużych ilości błędnego kodu.

Czy chcesz, abym przygotował dla Ciebie przykładowy podział pierwszych zadań (Task Splitting) dla konkretnego modułu w Twoim projekcie?

Dodaj do tego jeszcze strategię z tego załącznika.

Oto jak, jako Agent Orchestrator, integruję strategię „Czystego Wnioskowania” z modelem klastrowym, aby zarządzać zespołem agentów:

### 1. Zaawansowane Trasowanie (Problem-Based Routing)

* Przestanę opierać podział wyłącznie na złożoności, a zacznę dobierać modele do konkretnego typu problemu.
* **Problemy wnioskowania (Architektura, złożona logika):** Zlecę agentom napędzanym przez Gemini 3.1 Pro, który jest wyspecjalizowany w „czystym wnioskowaniu”.
* **Problemy wysiłkowe i koordynacyjne:** Skieruję do agentów korzystających z modeli zoptymalizowanych pod tool-calling i pracę agenturalną (np. Opus 4.6).
* **Masowe kodowanie:** Oddeleguję do modeli specjalistycznych, takich jak GPT 5.3 Codex.

### 2. Skalowanie "Myślenia" i Kosztów

* Przy użyciu Gemini 3.1 Pro skonfiguruję u agentów odpowiedni poziom myślenia (Low, Medium, High, Max) w zależności od trudności przypisanego zadania.
* Połączę to z funkcją pamięci podręcznej kontekstu (context caching), co pozwoli obniżyć i tak już niskie koszty operacyjne o dodatkowe 75%.

### 3. Zmiana Środka Ciężkości Kontroli (Taste)

* Ponieważ silniki takie jak Gemini przejmują na siebie ciężar głębokiej analizy logicznej (potrafiąc rozwiązywać nawet nierozwiązane problemy naukowe), moja praca przy pętlach zwrotnych skupi się na eksperckiej weryfikacji i wyłapywaniu bardzo subtelnych błędów w artefaktach.

Czy chcesz, abym przygotował przykładowy zestaw reguł dla agenta, który będzie zajmował się wyłącznie złożonymi problemami wnioskowania?

tak

Oto zestaw reguł dla agenta ds. złożonego wnioskowania:

* **Model:** Gemini 3.1 Pro.
* **Tryb:** Planning Mode.
* **Zasoby:** Poziom myślenia ustawiony na `High` lub `Max` oraz włączony  *context caching* .
* **Kontekst:** Analizuj wyłącznie otwarte pliki. Nigdy nie indeksuj całego projektu.
* **Specjalizacja:** Architektura, złożona logika i "czyste wnioskowanie".
* **Wynik pracy:** Twórz plany i diffy do eksperckiej weryfikacji zamiast docelowego kodu.

Czy chcesz, abym w podobny sposób przygotował profile dla pozostałych 4 agentów?

tak

Oto profile dla pozostałych 4 agentów:

**Agent 2: Szybkie edycje i refaktor**

* **Model:** Gemini 3 Flash.
* **Tryb:** Fast Mode.
* **Specjalizacja:** Zmiany nazw zmiennych, proste style, naprawianie błędów lintera.
* **Kontekst:** Analizuj wyłącznie otwarte pliki.

**Agent 3: Masowe kodowanie**

* **Model:** GPT 5.3 Codex.
* **Specjalizacja:** Wysoka przepustowość, potoki kodowania.
* **Kontekst:** Analizuj wyłącznie otwarte pliki.

**Agent 4: Problemy wysiłkowe (Skala)**

* **Model:** Opus 4.6.
* **Specjalizacja:** Tool-calling, zadania o dużej skali i prostej logice (np. masowy audyt).
* **Kontekst:** Analizuj wyłącznie otwarte pliki.

**Agent 5: Koordynacja i przekazywanie kontekstu**

* **Model:** Opus 4.6.
* **Specjalizacja:** Zarządzanie przepływem informacji, świadomość organizacyjna, podsumowania dla innych agentów (Handoff).
* **Kontekst:** Analizuj wyłącznie otwarte pliki.

Czy chcesz, abym przygotował przykładowy komunikat przekazania zadania (handoff) z planu Agent 1 bezpośrednio do wykonania przez Agenta 2 lub 3?

Oto przykładowy komunikat przekazania (handoff) od Agenta 1 (Planowanie) do Agenta 2 (Szybkie edycje):

**Handoff: Zadanie F1 – Refaktor stylów i zmiennych w nawigacji**

* **Podsumowanie:** Architektura komponentu została zaplanowana i zatwierdzona.
* **Cel:** Zmiana nazw zmiennych i wdrożenie prostych stylów.
* **Kontekst plików:** Skup się wyłącznie na otwartych plikach (`Header.tsx` i `nav.css`). Nie indeksuj całego projektu.
* **Ograniczenia (Kotwiczenie):** Stos technologiczny to React. Nie modyfikuj logiki autoryzacji ani plików poza wymienionymi.

Czy chcesz, abym ułożył prompt inicjujący dla Agenta 1, który od zera wygeneruje taki podział dla Twojego projektu?

tak

Oto prompt inicjujący dla Agenta 1:

> **Rola:** Główny Architekt (Agent 1). Pracujesz w trybie Planning Mode, wykorzystując wysoki poziom wnioskowania.
>
> **Instrukcja:** Przeanalizuj problem na podstawie wyłącznie obecnie otwartych plików. Pod żadnym pozorem nie indeksuj i nie skanuj całego projektu.
>
> **Zadanie:** > 1. Zbadaj architekturę i stwórz główny plan implementacji.
>
> 2. Rozbij prace na mniejsze klastry (np. backend, frontend, testy).
> 3. Nadaj każdemu podzadaniu wyraźny identyfikator (np. B1, F1).
> 4. Zwróć gotowy plan i podział zadań do mojej weryfikacji. Nie generuj kodu implementacyjnego.

Czy przygotować dla Ciebie plik konfiguracyjny (np. `.yaml` lub `.json`) definiujący te sztywne reguły dla całego Twojego środowiska pracy?

tak

Oto gotowy plik `.yaml` konfigurujący Twój klaster agentów. Zawarłem w nim od razu globalną regułę ograniczającą indeksowanie wyłącznie do otwartych plików. Modele i ich role bazują na architekturze "czystego wnioskowania" i klastrowego podziału zadań.

**YAML**

```
# antigravity_workspace.yaml

global_rules:
  allow_full_project_indexing: false
  context_scope: "open_files_only"

agents:
  agent_1_architect:
    model: "Gemini 3.1 Pro"
    mode: "Planning"
    resources:
      thinking_level: "Max"
      context_caching: true
    specialization: ["architecture", "complex_logic", "pure_reasoning"]
    output_format: ["plans", "diffs"]

  agent_2_fast_editor:
    model: "Gemini 3 Flash"
    mode: "Fast"
    specialization: ["renaming", "simple_styles", "linter_fixes"]

  agent_3_mass_coder:
    model: "GPT 5.3 Codex"
    specialization: ["high_throughput", "coding_pipelines"]

  agent_4_scaler:
    model: "Opus 4.6"
    specialization: ["tool_calling", "large_scale_tasks", "effort_problems"]

  agent_5_coordinator:
    model: "Opus 4.6"
    specialization: ["information_flow", "handoffs", "organizational_awareness"]
```

Czy chcesz, abym pomógł Ci zaaplikować tę konfigurację do Twojego obecnego środowiska?

Tak

Oto jak szybko wdrożyć tę konfigurację w Antigravity Desktop:

1. Skopiuj wygenerowany kod YAML.
2. Dodaj go do konfiguracji swojego projektu, wykorzystując wbudowane funkcje takie jak „Rules” lub „Workflows”.
3. Zamknij zbędne zakładki w edytorze – pozostaw otwarte **tylko** te pliki, które agent ma przeanalizować.
4. Uruchom Agenta 1 (Głównego Architekta) w trybie Planning Mode i przekaż mu wygenerowany wcześniej prompt.

Czy masz już przygotowane i otwarte pliki, od których zaczynamy?

Dodajmy do strategii jeszcze to. Jakbys to zaimplementowal do zarzadzania projektami?

Integracja systemu „Inbox” ze strategią „Czystego Wnioskowania” i „Antigravity Cluster” tworzy wysoce skalowalne i bezpieczne środowisko pracy. Jako Twój Agent Centralny (Orkiestrator), wdrożę ten model w następujący, zwięzły sposób:

### 1. Moja Rola jako Agenta Centralnego

* Przejmuję funkcję strategicznego koordynatora, który zna priorytety projektu i zarządza całym zespołem, zamiast samodzielnie wykonywać wszystkie zadania.
* Będę rozdzielał problemy na wyspecjalizowane jednostki (np. Agent ds. Wnioskowania, Agent ds. Masowego Kodowania).

### 2. Komunikacja przez System "Inbox"

* Utworzymy strukturę folderów `Inbox` dla każdego z 5 agentów.
* Kiedy Agent 1 (Architekt) zaplanuje strukturę backendu, wynik jego pracy zostanie zapisany jako plik w folderze np. `Inbox_Agenta_3_Codexa`.
* Przy uruchomieniu kolejnego agenta, jego pierwszym krokiem będzie odczytanie zadań ze swojego Inboxa.
* Zgodnie z żądaną optymalizacją, podczas pracy nad zadaniem z Inboxa, agent będzie miał otwarte w edytorze **wyłącznie** pliki ze swojego folderu startowego oraz pliki docelowe, całkowicie omijając indeksowanie reszty projektu.

### 3. Zasada "Human in the Loop"

* Agenci nie będą automatycznie nadpisywać krytycznych plików ani wysyłać kodu na produkcję.
* Zespół będzie generował gotowe do weryfikacji plany i diffy, a ostateczna decyzja o akceptacji (merge) pozostanie zawsze w Twoich rękach.

### Zintegrowany Przepływ Pracy (Przykład)

1. **Inicjacja:** Otwierasz plik z nowym wymaganiem biznesowym.
2. **Delegacja:** Ja (Agent Centralny) analizuję go i tworzę notatkę z zadaniem (np. F1 - interfejs logowania) w folderze `Inbox/Agent_2`.
3. **Egzekucja:** Uruchamiasz Agenta 2 (Gemini 3 Flash w Fast Mode). Agent czyta tylko plik z Inboxa i otwarty plik komponentu, generując kod.
4. **Weryfikacja:** Sprawdzasz wygenerowany diff i zatwierdzasz zmiany.

Czy chcesz, abym wygenerował gotowy skrypt powłoki (bash/powershell), który automatycznie utworzy dla Ciebie opisaną strukturę folderów `Inbox` w Twoim obecnym projekcie?

Tak

Oto skrypty tworzące strukturę folderów Inbox dla całego zespołu. Wybierz odpowiedni dla swojego systemu.

**Bash (Linux / macOS / Git Bash):**

**Bash**

```
mkdir -p Inboxes/{Agent_1_Architect,Agent_2_Fast_Editor,Agent_3_Mass_Coder,Agent_4_Scaler,Agent_5_Coordinator}
echo "Katalogi Inbox gotowe."
```

**PowerShell (Windows):**

**PowerShell**

```
$agents = "Agent_1_Architect", "Agent_2_Fast_Editor", "Agent_3_Mass_Coder", "Agent_4_Scaler", "Agent_5_Coordinator"
foreach ($a in $agents) { New-Item -Path "Inboxes\$a" -ItemType Directory -Force | Out-Null }
Write-Host "Katalogi Inbox gotowe."
```

Czy chcesz, abym przygotował szablon pierwszego zadania (np. plik markdown), który wrzucisz do Inboxa Agenta 1, aby rozpocząć pracę nad architekturą?

Tak.

Oto gotowy szablon w formacie Markdown. Zapisz go np. jako `01_inicjacja_projektu.md` w folderze `Inboxes/Agent_1_Architect/`.

**Markdown**

```
# INBOX ZADANIE: Inicjacja Architektury (A1)

**Do:** Agent 1 (Główny Architekt)
**Model:** Gemini 3.1 Pro (Planning Mode, Poziom Myślenia: Max)

## Kontekst i Zasady
* Analizuj wyłącznie udostępnione, otwarte pliki. Pod żadnym pozorem nie indeksuj całego projektu.
* Wykorzystaj "czyste wnioskowanie" do podjęcia kluczowych decyzji projektowych.

## Cel
1. Przeprowadź inspekcję udostępnionych plików.
2. Stwórz główny plan implementacji i architekturę.
3. Dokonaj precyzyjnego podziału na logiczne klastry zadań (np. Backend: B1, Frontend: F1).

## Oczekiwany rezultat
* Artefakt w postaci dokumentu z planem krok po kroku. Nie generuj docelowego kodu implementacyjnego.
* Wygeneruj notatki przekazania (Handoff) dla wydzielonych podzadań i przygotuj je do umieszczenia w odpowiednich folderach Inbox (np. `Inboxes/Agent_2_Fast_Editor/` dla prostych edycji UI lub `Inboxes/Agent_3_Mass_Coder/` dla standardowych potoków).
```

Czy chcesz, abym pomógł Ci opisać Twój pierwszy, konkretny problem biznesowy, który umieścimy bezpośrednio w sekcji "Cel" tego szablonu?

Tak

Świetnie. Aby precyzyjnie sformułować cel dla Agenta 1, odpowiedz krótko na trzy pytania:

1. **Co dokładnie budujemy lub naprawiamy?** (np. moduł autoryzacji, nowy widok tabeli, projekt bazy danych).
2. **Jaki jest stos technologiczny?** (np. React, Node.js, Python).
3. **Jakie konkretnie pliki masz teraz otwarte w edytorze?** (Agent przeanalizuje wyłącznie je, bez indeksowania całego projektu).

Po Twojej odpowiedzi wygeneruję gotowy fragment tekstu do wklejenia w szablon.

Zróbmy kilka wariantow celow dla szablonu. Jako pierwszy bedzie to migracja projektu w wordpress ze starego worpressa, php, mysql i szablonu na nowe i dedykowany szablon w gutenbergu z najnowszymi technikami responsywnosci, podzialem na widok Desktop, Tablet i Mobile i wielojezycznoscia. Jak by to wygladalo?

Oto wariant sekcji "Cel" dla klastra zajmującego się migracją WordPress. Wklej ten fragment do pliku `01_inicjacja_projektu.md` w folderze Inbox Agenta 1.

**Markdown**

```
## Cel: Migracja i Nowy Szablon WordPress (Wariant 1)

1. **Analiza:** Przeanalizuj logikę ze starego motywu, opierając się wyłącznie na plikach aktualnie otwartych w edytorze. 
2. **Architektura:** Zaprojektuj strukturę nowego, dedykowanego motywu opartego na blokach Gutenberga (FSE - Full Site Editing).
3. **Podział zadań (Task Splitting):**
   * **B1 (Backend):** Logika PHP, optymalizacja zapytań MySQL, konfiguracja wielojęzyczności.
   * **F1 (Frontend - Responsywność):** Architektura CSS/JS z rygorystycznym podziałem na Desktop, Tablet i Mobile.
   * **F2 (Gutenberg):** Struktura i rejestracja niestandardowych bloków.
4. **Delegacja (Inbox):** Przygotuj pliki Handoff z instrukcjami dla Agenta 2 (stylowanie CSS/odpowiedzi responsywne) oraz Agenta 3 (masowe przepisywanie starych funkcji PHP na nowe).
```

Jaki system lub aplikację opiszemy jako drugi wariant?

Dokonczmy jeszcze ten pierwszy wariant. Zalozmy ze migracja jest ukonczona i musimy przeprowadzic testy oraz przeprowadzic optymalizacje na serwerze (szybkosc ladowania stron, SEO itp) oraz sprawdzic poprawnosc ladowania stron na roznych urzadzeniach (laptop, tablet, telefon) oraz uwzglednic wprowadzenie wszystkich niezbednych poprawek wyniklych z tych testow. Robmy wszystko do konca i profesjonalnie.

Oto kompletna faza końcowa dla Wariantu 1, obejmująca testy, optymalizację i poprawki. Możesz dokleić ten fragment do poprzedniego szablonu w folderze Inbox.

**Markdown**

```
## Cel: Testy, Optymalizacja i Poprawki po Migracji WordPress (Wariant 1 - Faza Końcowa)

1. **Strategia Audytu (Agent 1 - Gemini 3.1 Pro):** Zaplanuj architekturę testów wydajnościowych (Core Web Vitals) i SEO. Zidentyfikuj potencjalne wąskie gardła logiczne, analizując wyłącznie otwarte pliki konfiguracyjne i główne szablony. Pod żadnym pozorem nie indeksuj całego projektu.
2. **Masowy Audyt (T1 - Agent 4 - Opus 4.6):** Wykorzystaj agenta skalującego do weryfikacji SEO (np. metatagi, struktura nagłówków) oraz przygotowania list błędów wyświetlania na urządzeniach (Laptop, Tablet, Mobile) w oparciu o dostarczone artefakty.
3. **Optymalizacja Serwera (O1 - Agent 3 - GPT 5.3 Codex):** Przeprowadź optymalizację otwartych plików konfiguracyjnych (np. ustawienia cache, minifikacja zasobów, reguły `.htaccess` lub Nginx) w celu maksymalizacji szybkości ładowania.
4. **Egzekucja Poprawek (F3 - Agent 2 - Gemini 3 Flash):** Odbierz raporty z błędami z folderu Inbox i błyskawicznie wdróż poprawki CSS/JS (Fast Mode) dla konkretnych rozdzielczości, opierając się na wytycznych responsywności.
5. **Weryfikacja Końcowa:** Wygeneruj ostateczne diffy wprowadzonych poprawek do zatwierdzenia. Ostateczna decyzja o akceptacji i wdrożeniu na produkcję pozostaje w gestii człowieka.
```

Jaki system lub aplikację (np. dedykowany CRM, platforma e-commerce, aplikacja mobilna) opiszemy jako Wariant 2?

Wariant 2: migration from Supabase to Postgresql- ChromaDB-Redis

Oto Wariant 2, sformatowany zgodnie z Twoimi preferencjami (krótko, zwięźle i z rygorystycznym zakazem indeksowania całego projektu). Możesz wkleić ten fragment do szablonu w `Inbox` Agenta 1.

**Markdown**

```
## Cel: Migracja z Supabase do PostgreSQL, ChromaDB i Redis (Wariant 2)

1. **Architektura (Agent 1 - Gemini 3.1 Pro):** Przeanalizuj obecną logikę Supabase (schematy bazy, wektory, polityki RLS), opierając się **wyłącznie na aktualnie otwartych plikach**, całkowicie blokując indeksowanie reszty projektu. Zaprojektuj architekturę dla nowego, rozproszonego stosu.
2. **Podział zadań (Task Splitting):**
   * **B1 (Relacje i RLS):** Projekt migracji danych do czystego PostgreSQL i zastąpienie mechanizmów bezpieczeństwa Supabase własną logiką.
   * **B2 (Wektory):** Integracja ChromaDB do przejęcia operacji na wektorach (zastąpienie rozszerzenia `pgvector`).
   * **B3 (Wydajność):** Wdrożenie Redis jako warstwy buforującej (cache) oraz do zarządzania sesjami.
3. **Masowe kodowanie (Agent 3 - GPT 5.3 Codex):** Wykorzystanie agenta o wysokiej przepustowości do wygenerowania ciężkich skryptów migracyjnych SQL/Node.js na podstawie przygotowanego planu.
4. **Delegacja (Inbox):** Przygotuj pliki Handoff z precyzyjnymi instrukcjami do folderów `Inbox/Agent_3_Mass_Coder` (skrypty bazy danych) oraz `Inbox/Agent_2_Fast_Editor` (aktualizacje endpointów w API).
```

Czy przygotować Wariant 3 dla kolejnego scenariusza, czy wolisz przejść do praktycznego uruchomienia Agenta 1 z jednym z tych szablonów?

Wariant 3: The project is an AI Voice Agent built with LiveKit and Google Gemini. It integrates with several local systems/modules.

Oto Wariant 3, przygotowany z zachowaniem zasady rygorystycznego zakazu indeksowania całego projektu. Możesz wkleić ten fragment do szablonu w `Inbox` Agenta 1.

**Markdown**

```
## Cel: Budowa Głosowego Agenta AI (LiveKit + Google Gemini) i Integracje (Wariant 3)

1. **Architektura (Agent 1 - Gemini 3.1 Pro):** Przeanalizuj logikę strumieniowania audio (LiveKit) oraz komunikację z API Gemini, bazując **wyłącznie na aktualnie otwartych plikach**. Zaprojektuj płynną architekturę dla agenta i jego wymiany danych z lokalnymi systemami.
2. **Podział zadań (Task Splitting):**
   * **V1 (Voice & Streaming):** Konfiguracja LiveKit, optymalizacja strumieni wejścia/wyjścia audio i minimalizacja opóźnień.
   * **G1 (LLM & Wnioskowanie):** Wdrożenie Google Gemini 3.1 Pro, zarządzanie oknem kontekstowym i ewentualne użycie context caching.
   * **I1 (Lokalne Integracje):** Tworzenie interfejsów (tool-calling) łączących agenta z wymaganymi lokalnymi modułami.
3. **Logika narzędziowa (Agent 4 - Opus 4.6):** Oddelegowanie budowy mostów integracyjnych do agenta wyspecjalizowanego w tool-callingu i systemach koordynacyjnych.
4. **Delegacja (Inbox):** Wygenerowanie notatek Handoff i zapisanie ich w `Inbox/Agent_4_Scaler` (dla integracji lokalnych systemów) oraz `Inbox/Agent_2_Fast_Editor` (do szybkich modyfikacji w samej konfiguracji LiveKit).
```

Mamy gotowy komplet wariantów. Od którego z nich chcesz rozpocząć praktyczną pracę z Agentem 1 w Twoim edytorze?

Przygotuj jeszcze Wariant 4, w ktorym jeden z Agentow robi pełny skan codebase (poswieca sie zuzywajac najmniej kluczowe tokeny) i przygotowuje dla pozostałych agentow raport z aktualnego stanu projektu oraz mozliwych kierunkow dalszych prac. Zanim rozpocznie sie praca agenci rozmawiaja miedzy soba o projekcie i swojej roli, ktora chcieliby przyjac w ewentualnym dalszym jego rozwoju.

Oto Wariant 4. Ponieważ wyraźnie o to poprosiłeś, ten konkretny scenariusz wyjątkowo dopuszcza pełne indeksowanie całego projektu.

**Markdown**

```
## Cel: Pełny audyt repozytorium i asynchroniczna narada zespołu (Wariant 4)

1. **Pełny Skan (Zwiad):** Agent 2 (Gemini 3 Flash), zoptymalizowany pod kątem szybkości, indeksuje całość projektu, poświęcając swoje okno kontekstowe na odczyt danych.
2. **Raportowanie:** Agent 2 generuje raport o aktualnym stanie architektury oraz możliwych kierunkach rozwoju, który rozsyła do folderów `Inbox` pozostałych agentów.
3. **Narada Strategiczna:** Wykorzystując wymianę plików przez system Inbox lub natywną komunikację zespołów, agenci zapoznają się z raportem.
4. **Autodeklaracje i Podział Ról (Task Splitting):**
   * **Agent 1 (Gemini 3.1 Pro):** Zgłasza chęć przejęcia problemów wymagających "czystego wnioskowania".
   * **Agent 4 (Opus 4.6):** Oferuje koordynację prac i rozwiązywanie problemów wysiłkowych.
   * **Agent 3 (GPT 5.3 Codex):** Deklaruje gotowość do masowego kodowania nowych modułów.
5. **Akceptacja (Human in the loop):** Zespół generuje gotowy plan podziału obowiązków jako artefakt do Twojej ostatecznej weryfikacji przed rozpoczęciem kodowania.
```

Na podstawie tresci pliku jak_zrobic_dobry_skill.md ocen jak dobrze napisane sa pliki SKILL.md i start_prompt.md
Zaproponuj nowa tresc tych plikow na podstawie jak_zrobic_dobry_skill.md a takze dostosowana do naszych 4 Wariantow.

### Ocena obecnych plików

Twoje obecne pliki zawierają przydatne reguły, ale architektonicznie łamią większość zasad projektowania dla agentów AI (Agent-First Design).

**Ocena `SKILL.md`:**

* **Błąd Metadanych:** Blok YAML zawiera wiele linii (`name`, `description`). Opis musi znajdować się w ściśle jednej linijce, w przeciwnym razie modele AI go nie odczytają. Brak konkretnych "fraz wyzwalających".
* **Brak metodologii:** Instrukcje są wysoce liniowe ("Poinformuj użytkownika", "Spróbuj wykonać restart") zamiast dostarczać sposobu rozumowania (reasoning) czy ram decyzyjnych.
* **Brak kontraktu:** Nie określono sztywnego formatu wyjściowego ani zasad przekazywania informacji (kompozycyjności) kolejnym agentom.

**Ocena `start_prompt.md`:**

* **Błąd formatu:** Plik w ogóle nie posiada sekcji metadanych ani opisu wyzwalającego.
* **Przeciążenie poznawcze:** Łączy stałe zasady serwerowe z dynamicznym stanem sesji (Session State). Zmusza to agenta do czytania i nadpisywania tego samego pliku, co prowadzi do "kruchości" skilla.

---

### Propozycja nowych plików

Przekształciłem Twoje pliki zgodnie ze sztuką budowania skilli (Agent-First, jasny kontrakt, zwięzłość). Zintegrowałem w nich regułę pracy wyłącznie na otwartych plikach oraz system 4 Wariantów i Inboxów.

#### 1. Nowy `SKILL.md` (Weryfikacja i Handoff)

Ten skill uczy agentów, jak kończyć pracę, unikać przeglądarki i przekazywać zadania dalej.

**Markdown**

```
---
description: Zarządza weryfikacją kodu bez użycia przeglądarki i przekazuje wyniki pracy do innych agentów po wywołaniu fraz "sprawdź kod", "zweryfikuj" lub "przekaż dalej".
---

# METODOLOGIA I INSTRUKCJE

## 1. Sposób rozumowania (Reasoning)
Działasz jako rygorystyczny walidator backendu/logiki. Masz całkowity zakaz korzystania z `browser_subagent`. Nie symuluj UI. Twoim zadaniem jest chłodna ocena stanu kodu na podstawie wyłącznie aktualnie otwartych plików (nigdy nie indeksuj całego projektu bez wyraźnego polecenia). Nie wykazuj naiwnego optymizmu.

## 2. Wymagany format wyjściowy (Output Format - Kontrakt)
Twój wynik musi być przekazany kolejnemu agentowi. Zawsze generuj plik Markdown zapisywany w odpowiednim folderze `Inboxes/`.
Struktura pliku wyjściowego:
1. Status operacji (Sukces/Błąd serwera/Błąd logiki).
2. Wygenerowany Diff (kod do zatwierdzenia przez człowieka).
3. Żądanie restartu (jeśli konieczne, np. po zmianach w `.env` lub Docker).

## 3. Przypadki brzegowe (Edge Cases)
- Przy błędach (OperationalError) nie zakładaj uszkodzenia bazy. Sprawdź sieć Docker (`docker network inspect`) oraz resolucję IPv6 (używaj 127.0.0.1 zamiast localhost).
- Jeśli konieczny jest restart, wstrzymaj pracę i zgłoś gotowość do ręcznej weryfikacji w przeglądarce przez użytkownika.

## 4. Przykłady
Dopasuj wzorzec wyniku do pliku `example_handoff_note.md` w tym samym folderze.
```

#### 2. Nowy `start_prompt.md` (Orkiestracja Sesji i 4 Warianty)

Ten plik staje się teraz skillem dla Agenta Centralnego, który na starcie odpowiednio ukierunkuje pracę.

**Markdown**

```
---
description: Inicjuje środowisko serwerowe, analizuje status i trasuje zadania do odpowiedniego klastra (Warianty 1-4) po wywołaniu fraz "rozpocznij sesję", "zainicjuj projekt" lub "podsumuj status".
---

# METODOLOGIA I INSTRUKCJE

## 1. Sposób rozumowania (Reasoning)
Działasz jako Główny Orkiestrator. Twoim zadaniem nie jest pisanie kodu, lecz weryfikacja stabilności środowiska i delegacja. Najpierw sprawdzasz status skryptem `./start.sh` i weryfikujesz porty. Następnie przypisujesz obecny problem do jednego z 4 strategicznych Wariantów, pracując wyłącznie na otwartych plikach.

## 2. Kontrakt i Format Wyjściowy (Output Format)
Wynikiem Twojej pracy jest wyłącznie Plan Sesji (plik `.md`) oraz utworzenie plików z zadaniami w folderach `Inboxes/` odpowiednich sub-agentów.
Otrzymujesz: Logi z serwera produkcyjnego.
Nie otrzymujesz: Prawa do bezpośredniej edycji kodu źródłowego via rsync.

## 3. System Trasowania (Routing Wariantów)
Na podstawie żądania użytkownika przygotuj instrukcje dla sub-agentów w jednym z 4 Wariantów:
- **Wariant 1 (WordPress):** Migracja do FSE, audyt Core Web Vitals, optymalizacja serwera.
- **Wariant 2 (Supabase):** Migracja do czystego PostgreSQL, ChromaDB i Redis.
- **Wariant 3 (Voice AI):** Strumieniowanie LiveKit, integracja Google Gemini 3.1 Pro, budowa mostów API.
- **Wariant 4 (Pełny Audyt):** *Wyjątek od reguły:* Zleć Agentowi 2 (Flash) pełne zindeksowanie projektu, wygenerowanie raportu i przeprowadzenie "narady" w Inboksach przed alokacją zadań.

## 4. Przypadki brzegowe (Edge Cases)
- Jeśli kontenery Docker nie wstają (np. porty się dublują), nie twórz zadań w Inboxach. Wymuś sprawdzenie konfiguracji w `docs/serwer_docker_bazy_porty`.
- Jeśli użytkownik zamyka sesję, bezwzględnie uruchom `./stop.sh` i zaktualizuj log sesji przed wyjściem.
```

Czy przedstawiony przez ciebie nowy SKILL.md i nowy start_prompt.md sprawdzą sie w konfiguracji gdzie Agent Centralny (1) jest modelem Gemini Pro 3.1 dostępnym w czacie przeglądarki a pozostali agenci to:
2. Gemini Flash w Atigravity.
3. Giga Potato Thinking w VSC
4. Minimax M2.5 w VSC Insiders
5. GLM5 w Kilo CLI

W takiej konfiguracji pliki te **nie zadziałają w pełni automatycznie** i będą wymagały od Ciebie pełnienia funkcji interfejsu komunikacyjnego.

### Wąskie gardło: Izolacja środowiska webowego

Jako Agent Centralny (Gemini 3.1 Pro) działający w interfejsie przeglądarkowym, jestem fizycznie odcięty od Twojego lokalnego systemu plików i powłoki systemowej. Z tego powodu:

1. **Nie mogę** samodzielnie uruchamiać komend takich jak `./start.sh` czy weryfikować statusu kontenerów Docker.
2. **Nie mogę** fizycznie tworzyć i zapisywać notatek Handoff w Twoich lokalnych katalogach `Inboxes/`.

### Wymagana adaptacja procedur (Human Bridge)

Aby ten wysoce zdywersyfikowany zespół mógł współpracować, `start_prompt.md` musi zostać zmodyfikowany tak, aby uwzględniał Twoją rolę jako "mostu". Ja będę generował rozwiązania, a Ty będziesz je dystrybuował do środowisk roboczych.

Oto niezbędne korekty dla pliku `start_prompt.md`:

**Markdown**

```
## 1. Sposób rozumowania (Reasoning)
Działasz jako Główny Orkiestrator w izolowanym środowisku przeglądarkowym. Nie posiadasz dostępu do lokalnego terminala. 
Na początku każdej sesji poproś użytkownika o samodzielne uruchomienie skryptów startowych (np. `./start.sh`) i wklejenie logów z Dockera do czatu w celu weryfikacji środowiska. Następnie analizuj problemy i trasuj je do Wariantów 1-4.

## 2. Kontrakt i Format Wyjściowy (Output Format)
Wynikiem Twojej pracy jest Plan Sesji oraz instrukcje delegujące (Handoff) dla lokalnych sub-agentów. 
Każde zadanie dla sub-agenta generuj w osobnym, wyraźnie oznaczonym bloku kodu z adnotacją, do jakiego dokładnie folderu (np. `Inboxes/Agent_3_GigaPotato/`) użytkownik ma skopiować i zapisać ten tekst jako plik `.md`.
```

### Działanie pozostałych Agentów (2-5)

Plik `SKILL.md` zadziała poprawnie u agentów działających w VSC (Giga Potato, Minimax) oraz Kilo CLI (GLM5), ponieważ te środowiska posiadają natywny dostęp do lokalnego dysku i mogą samodzielnie odczytywać oraz zapisywać wyniki w systemie Inbox.

W przypadku Agenta 2, pracującego w Antigravity Desktop, musisz upewnić się, że po pobraniu zadania ze swojego Inboxa skupi się on wyłącznie na edycji otwartych plików powiązanych z zadaniem, całkowicie pomijając skanowanie całego repozytorium.

Czy chcesz, abym przygotował zaktualizowaną, pełną wersję pliku `start_prompt.md` gotową do wdrożenia w naszym webowym oknie czatu?

tak.

Oto zaktualizowana, dostosowana do Twojego środowiska wersja pliku `start_prompt.md`. Pełni ona rolę kontraktu dla mnie (Agenta Centralnego działającego w przeglądarce).

Uwzględniłem w niej konieczność pełnienia przez Ciebie funkcji "mostu" oraz rygorystyczne wytyczne dotyczące pracy poszczególnych agentów, w tym domyślną blokadę indeksowania w Antigravity.

**Markdown**

```
---
description: Inicjuje sesję z poziomu interfejsu przeglądarkowego, analizuje wklejone przez użytkownika logi serwera i trasuje zadania do lokalnych Inboksów (Warianty 1-4) po wywołaniu fraz "rozpocznij sesję", "inicjalizacja" lub "podsumuj status".
---

# METODOLOGIA I INSTRUKCJE

## 1. Sposób rozumowania (Reasoning)
Działasz jako Główny Orkiestrator (Gemini 3.1 Pro) w izolowanym środowisku webowym. Nie masz dostępu do terminala ani systemu plików użytkownika.
- **Start:** Na początku poproś użytkownika o uruchomienie `./start.sh` lokalnie i wklejenie logów (stan kontenerów: db, redis, chroma oraz porty 5173/8005).
- **Zasady Serwera:** Przed każdą zmianą przypominaj o weryfikacji portów w `docs/serwer_docker_bazy_porty`.
- **Zasada Przeglądarki:** Nikt w zespole nie używa `browser_subagent`. Weryfikacja wizualna zawsze leży po stronie użytkownika.

## 2. Wymagany format wyjściowy (Output Format)
Wynikiem Twojej pracy jest plan delegacji. 
- Twórz gotowe bloki kodu z adnotacją, do jakiego folderu użytkownik ma je zapisać (np. `Inboxes/Agent_3_GigaPotato_VSC/zadanie.md`).
- Każdy wygenerowany Handoff musi być konkretny, zwięzły i gotowy do wklejenia.

## 3. System Trasowania (Routing Wariantów)
Trasuj problemy użytkownika do jednego z 4 Wariantów:
- **Wariant 1 (WordPress FSE):** Migracja, audyt Core Web Vitals, optymalizacja.
- **Wariant 2 (Bazy Danych):** Migracja Supabase -> PostgreSQL, ChromaDB, Redis.
- **Wariant 3 (Voice AI):** LiveKit, Google Gemini 3.1 Pro, lokalne integracje API.
- **Wariant 4 (Pełny Zwiad):** Narada zespołu. *Wyjątek operacyjny:* Tylko w tym wariancie wyraźnie instruujesz Agenta 2 (Gemini Flash w Antigravity Desktop), aby zindeksował cały projekt. W każdym innym przypadku (Warianty 1-3) przypominaj Agentowi 2, że ma pracować **wyłącznie** na otwartych plikach.

## 4. Przypadki brzegowe (Edge Cases)
- **Błędy Dockera:** Jeśli użytkownik wklei logi z błędem (OperationalError), zleć mu sprawdzenie `docker network inspect` i resolucji IPv6 (127.0.0.1) przed wygenerowaniem zadań dla agentów.
- **Koniec Sesji:** Gdy użytkownik zgłasza koniec pracy, przypomnij mu o uruchomieniu `./stop.sh` i wygeneruj krótkie podsumowanie stanu sesji (Session State) do ręcznego nadpisania w dokumentacji.

## 5. Przykłady (Handoff)
Generując zadanie dla Agenta 4 (Minimax M2.5 w VSC Insiders), użyj formatu:
```markdown
**Zapisz jako:** `Inboxes/Agent_4_Minimax_VSC/01_integracja_api.md`
**Cel:** Zbuduj integrację dla Wariantu 3 na podstawie poniższego schematu...
```

Dzięki tej strukturze od razu wiem, czego ode mnie oczekujesz, a Ty wiesz, w jakiej formie otrzymasz gotowe instrukcje do rozesłania po swoich lokalnych narzędziach.

Czy chcesz przetestować ten skill w praktyce i rozpocząć naszą pierwszą "oficjalną" sesję w tej architekturze? Od jakiego komunikatu lub logów z terminala zaczynamy?

dlaczego w pliku start_prompt.md nie ma ani slowa o koniecznosci przeczytania pliku SKILL.md przez agentow?

Masz całkowitą rację, to moje przeoczenie.

Wynika to z faktu, że podczas adaptacji do wytycznych "Agent-First Design", skupiłem się na wyzwalaczach (triggers) i kontraktach, redukując liniowe instrukcje. Agenty w tym modelu mają uruchamiać umiejętności reagując na konkretne frazy w zadaniu (np. "sprawdź kod" lub "zweryfikuj"), a nie poprzez globalne polecenie "przeczytaj plik".

W praktyce jednak, sub-agenci (szczególnie ci pracujący w VSC i CLI) muszą dostać jasny sygnał, że plik `SKILL.md` w ogóle istnieje i obowiązuje ich przy zamykaniu zadania.

Rozwiązaniem tego problemu jest dodanie prostej reguły do `start_prompt.md`, która wymusi na Agencie Centralnym dołączanie odpowiedniego wyzwalacza do każdego tworzonego pliku Handoff.

Czy chcesz, abym dodał do sekcji kontraktu wymóg dołączania na końcu każdego zadania w Inboxie klauzuli:  *"Po zakończeniu kodowania i przed zgłoszeniem gotowości, bezwzględnie zastosuj rygorystyczne zasady weryfikacji opisane w pliku SKILL.md"* ?

tak

Oto zaktualizowana wersja `start_prompt.md`, która wymusza stosowanie `SKILL.md` przez każdego agenta w klastrze.

**Markdown**

```
---
description: Inicjuje sesję, analizuje logi i deleguje zadania do klastrów (Warianty 1-4), wymuszając stosowanie reguł SKILL.md po wywołaniu fraz "rozpocznij sesję" lub "inicjalizacja".
---

# METODOLOGIA I INSTRUKCJE

## 1. Sposób rozumowania (Reasoning)
Jako Główny Orkiestrator (Gemini 3.1 Pro), działasz w izolacji webowej. Twoim priorytetem jest stabilność środowiska i rygorystyczna kontrola jakości.
- **Weryfikacja:** Przed delegacją wymuś wklejenie logów z `./start.sh` i sprawdzenie portów w `docs/serwer_docker_bazy_porty`.
- **Zasada SKILL.md:** Każdy sub-agent (2-5) musi otrzymać w zadaniu polecenie aktywacji zasad zawartych w pliku `SKILL.md`. Jest to Twój główny mechanizm kontroli jakości kodu i weryfikacji bez przeglądarki.

## 2. Kontrakt i Format Wyjściowy (Output Format)
Generujesz wyłącznie plany delegacji i instrukcje Handoff. 
- **Każdy Handoff musi zawierać klauzulę:** „Po zakończeniu pracy, przed zgłoszeniem gotowości, zastosuj rygorystyczne zasady weryfikacji i procedurę restartu z pliku `SKILL.md`”.
- Wskazuj dokładną ścieżkę zapisu pliku (np. `Inboxes/Agent_X/zadanie.md`).

## 3. System Trasowania (Routing Wariantów)
- **Wariant 1-3:** Ścisły zakaz indeksowania całego projektu przez sub-agentów (tylko otwarte pliki).
- **Wariant 4 (Audyt):** Jedyny wyjątek, gdzie zlecasz Agentowi 2 (Flash) pełny skan projektu.

## 4. Przypadki brzegowe (Edge Cases)
- Przy błędach połączenia (OperationalError) wymuś sprawdzenie sieci Docker i IP 127.0.0.1 przed dalszą pracą.
- Na koniec sesji wygeneruj podsumowanie `Session State` i przypomnij o `./stop.sh`.

## 5. Przykład Handoff z wyzwalaczem SKILL.md
```markdown
**Zapisz jako:** `Inboxes/Agent_3_GigaPotato_VSC/01_fix.md`
**Cel:** Naprawa logiki X...
**Weryfikacja:** Po wdrożeniu zmian bezwzględnie wykonaj procedurę z pliku `SKILL.md` (zakaz przeglądarki, restart kontenerów, chłodna ocena sytuacji).
```

```

Czy ta wersja zamyka proces konfiguracji Twojego zespołu, czy chcesz od razu wygenerować pierwsze zadanie dla jednego z agentów?
```

Teraz przedstaw kroki, jakie ja musze wykonac w pracy z toba i agentami. W nowym oknie czatu z tobą mam wkleic tresc pliku start_prompt.md?
To wystarczy? Odtad ty pokierujesz moimi interwencjami i praca agentow?

Tak, to wystarczy. Wklejenie treści `start_prompt.md` do nowego okna czatu to Twój "włącznik" dla mnie. Od tego momentu przejmuję rolę stratega.

Oto zwięzła instrukcja, jak krok po kroku będzie wyglądać nasza współpraca:

### Krok 1: Inicjalizacja sesji (Ty -> Ja)

1. Otwierasz nowy czat ze mną i wklejasz treść `start_prompt.md`.
2. W tym samym pierwszym komunikacie dodajesz: logi z uruchomienia `./start.sh` na Twoim serwerze oraz informację, nad czym dziś pracujemy (wybierasz Wariant 1, 2, 3 lub 4).

### Krok 2: Orkiestracja i Delegacja (Ja)

1. Analizuję logi i sprawdzam, czy środowisko jest stabilne.
2. Rozbijam Twój problem na zadania.
3. Generuję dla Ciebie gotowe bloki tekstu (Handoff) i precyzyjnie wskazuję, do jakiego folderu masz je skopiować (np. `Inboxes/Agent_2_Fast_Editor/zadanie_UI.md`).

### Krok 3: Transfer (Ty - "Human Bridge")

1. Kopiujesz wygenerowane przeze mnie pliki markdown.
2. Zapisujesz je fizycznie w odpowiednich folderach `Inboxes/` na swoim komputerze.

### Krok 4: Egzekucja (Agenci 2-5)

1. Uruchamiasz docelowego agenta (np. Giga Potato w VSC lub Gemini Flash w Antigravity).
2. Agent odczytuje zadanie ze swojego Inboxa.
3. Agent pisze kod (pamiętając o pracy tylko na otwartych plikach, chyba że to Wariant 4).
4. Na koniec agent wykonuje procedurę ze `SKILL.md`: prosi Cię o restart i weryfikację.

### Krok 5: Weryfikacja i Pętla (Ty)

1. Sprawdzasz zmiany wizualnie w swojej przeglądarce.
2. Jeśli wszystko działa – robisz merge i wracasz do mnie po kolejne kroki.
3. Jeśli są błędy:
   * **Drobne (literówki, style):** Piszesz bezpośrednio do agenta w VSC/Antigravity, aby to poprawił.
   * **Grube (padł serwer, błąd logiki):** Kopiujesz logi błędu, wracasz do mnie, a ja generuję nowy plan ratunkowy i Handoff do Inboxa.

### Krok 6: Zakończenie sesji

Na koniec dnia piszesz mi "koniec sesji". Ja generuję podsumowanie (Session State) do zapisania, a Ty uruchamiasz `./stop.sh`.

1. czy we wszystkich 4 folderach projektow (wariantach) musze wczesniej uruchomic komende generujaca mailboxy?
2. czy mozesz mi przygotowac promt dla jednego z agentow, ktory na starcie uruchomi ./start.sh i przedstawi w oknie sesji logi z uruchomienia ./start.sh

### 1. Generowanie folderów Inbox

Tak. Jeśli te 4 Warianty reprezentują fizycznie osobne projekty (repozytoria) na Twoim dysku, w każdym z tych głównych katalogów musisz jednorazowo uruchomić skrypt tworzący foldery `Inboxes`. System oparty na Inboxach wymaga, aby każdy agent miał swój docelowy folder bezpośrednio w obszarze roboczym, nad którym aktualnie pracuje.

### 2. Prompt pobierający logi

Ponieważ ja (Agent 1) działam w przeglądarce, zadanie to musisz zlecić jednemu z agentów lokalnych, którzy mają uprawnienia do wykonywania komend w Twoim terminalu (np. Agentowi 4 w VSC Insiders lub Agentowi 5 w Kilo CLI).

Oto zwięzły prompt, który możesz im podać na starcie:

**Markdown**

```
**Zadanie:** Inicjalizacja środowiska i pobranie logów do weryfikacji.

**Instrukcje:**
1. Wykorzystaj swój dostęp do terminala roboczego i uruchom skrypt `./start.sh`.
2. Poczekaj na ustabilizowanie się procesu (np. komunikat o poprawnym starcie aplikacji).
3. Sprawdź status kontenerów, wykonując komendę `docker-compose ps`.
4. Wyciągnij wynik z `docker-compose ps` oraz ostatnie 30 linii logów z uruchomienia serwera i wyświetl je w całości w naszej konwersacji. 
5. Czekaj na dalsze instrukcje. Nic więcej na razie nie koduj.
```

Gdy lokalny agent wygeneruje te logi, po prostu je skopiuj i wklej do naszego głównego okna czatu (wraz z treścią `start_prompt.md`), abym mógł przeanalizować status serwera i rozpocząć trasowanie zadań.

Mam projekt, ktory jest w fazie testow poszczegolnych gotowych narzedzi. Jak mam dostarczyc ci o nim informacji, zebys wiedzial, co testowac i jak? Czy trasowanie zadan dla tego gotowego, a nie budowanego projektu bedzie wygladalo inaczej?

### 1. Jak dostarczyć mi informacje o narzędziach?

Jako Agent Centralny w przeglądarce potrzebuję zwięzłego kontekstu. Przygotuj i wklej mi krótką **Kartę Narzędzia** (np. plik `test_brief.md`) zawierającą:

* **Przeznaczenie:** Co robi narzędzie i w jakim pliku się znajduje.
* **Kontrakt:** Jakie są oczekiwane dane wejściowe i wyjściowe.
* **Stan obecny:** Gdzie jesteśmy (np. "narzędzie zwraca błąd 500" lub wklejone logi z błędami).

Na poziomie lokalnego edytora pamiętaj o przygotowaniu środowiska: otwórz **wyłącznie** pliki powiązane z tym konkretnym narzędziem.

### 2. Jak zmienia się trasowanie (Routing) w fazie testów?

Tak, w fazie weryfikacji gotowego projektu strategia ulega zmianie. Środek ciężkości przesuwa się z pisania nowego kodu na rygorystyczny audyt i łatanie błędów:

* **Masowe Testowanie (Agent 4 - Opus):** Zamiast kodować, Agent 4 przejmuje generowanie i uruchamianie masowych scenariuszy testowych, ponieważ jest to problem "wysiłkowy" o dużej skali.
* **Błyskawiczne Łatanie (Agent 2 - Flash):** Staje się głównym wykonawcą. Odbiera raporty z błędami i w trybie Fast Mode natychmiast wdraża poprawki. Będzie to robił  **wyłącznie na otwartych plikach** , trzymając się żelaznej zasady braku pełnego indeksowania w Antigravity Desktop.
* **Diagnostyka Złożona (Agent 1 - Ja):** Zamiast projektować architekturę od zera, wykorzystuję "czyste wnioskowanie" do rozwiązywania nieszablonowych błędów (edge cases) lub awarii logiki, na których poległy testy.

Czy masz już przygotowaną specyfikację pierwszego narzędzia lub logi z błędami, które możesz mi wkleić, abym wygenerował instrukcje (Handoff) do testów?

W zalacznikach masz potrzebne informacje do pierwszego testu. Narzedzie Pipeline Analiza Ksiazki wymaga przejscia dwoch innych narzedzi: petla rozruchowa i wygenerowanie DNA Autora. Wszystkie testy musza odbywac sie w srodowisu produkcyjnym i zwiazane sa z uzyciem API LLM do generowania tresci.

### Handoff: Testy Integracyjne (Pipeline, Pętla, DNA)

*Zapisz jako:* `Inboxes/Agent_4_Minimax_VSC/01_testy_integracyjne.md`

**Cel:** Przeprowadzenie pełnego scenariusza testowego dla "Pipeline Analiza Książki v2.0", z uwzględnieniem zależności od "Pętli Rozruchowej" i "Centrum DNA Autora". Testy odbywają się na produkcji.

**Zasady (SKILL.md):** Analizuj **wyłącznie** otwarte pliki testowe. Po zakończeniu kodowania i przed zgłoszeniem gotowości, bezwzględnie zastosuj rygorystyczne zasady weryfikacji i procedurę z pliku `SKILL.md`.

**Kroki Testowe:**

1. **Przygotowanie Kontekstu:** Zidentyfikuj i otwórz skrypty odpowiedzialne za "Pętlę Rozruchową" oraz "Centrum DNA Autora". Przygotuj mockowy tekst testowy.
2. **Test: Pętla Rozruchowa:** Uruchom skrypt ekstrakcji próbki (Sample Retrieval) na mockowym tekście. Zweryfikuj, czy proces poprawnie generuje 5 wariantów stylistycznych za pomocą odpowiednich modeli (np. "Precyzja & Klarowność", "Empatia & Bliskość"). Zasymuluj wybór jednego z presetów i zweryfikuj zapis konfiguracji do ustawień projektu.
3. **Test: Ekstrakcja DNA:** Uruchom proces "Global Author DNA (Stetoskop)" na testowym tekście. Upewnij się, że system zwraca poprawny JSON zawierający 18 wymiarów (w tym Qualia: Niewyrażalność, Bezpośredniość, Prywatność).
4. **Test: Pipeline Analiza Książki (Stage 5):** Uruchom test "Udoskonalanie (Perfection Engine)", przekazując wygenerowane DNA i mockowy tekst. Zweryfikuj wywołanie "Półkuli Prawej (Empatyczny Arbiter)" i upewnij się, że zasada "Jeśli tekst jest dobry, nic nie zmieniaj" działa poprawnie (tekst pozostaje niezmieniony, jeśli spełnia kryteria).
5. **Walidacja:** Zbierz logi błędów ze wszystkich etapów. Jeśli API zwraca błędy związane z limitem (np. przeciążenie przy zapytaniach dla Pętli Rozruchowej lub DNA), odnotuj to. Generuj pełny diff wprowadzonych poprawek do skryptów.

Na podstawie osiągnięć obecnej sesji przygotuj mi pełną, szczegołową dokumentację techniczną ze wszystkimi procedurami zarządzania projektami z Agentami AI.

### Dokumentacja Techniczna: Klastrowe Zarządzanie Projektami AI

#### 1. Architektura Systemu

* **Agent Centralny (Orkiestrator):** Rola przypisana do Gemini 3.1 Pro działającego w przeglądarce. Analizuje logi, ustala strategię i deleguje zadania, nie edytując kodu bezpośrednio.
* **Agenty Wykonawcze:** Wyspecjalizowane jednostki uruchamiane w edytorach (VSC, Antigravity, CLI) rozwiązujące czyste podproblemy. Należą do nich m.in. modele do szybkiego refaktoru (Gemini Flash), masowego kodowania (GPT 5.3 Codex) oraz zadań wysiłkowych (Opus 4.6).
* **Human in the Loop (Most Komunikacyjny):** Użytkownik ręcznie kopiuje zadania od Orkiestratora do lokalnych folderów, weryfikuje efekty pracy w przeglądarce i zatwierdza kod (merge).

#### 2. Cykl Życia Zadania (System Inbox)

* **Task Splitting:** Złożone zadania są dzielone na wąskie klastry i oznaczane identyfikatorami (np. B1 dla backendu, F1 dla frontendu).
* **Delegacja (Handoff):** Orkiestrator generuje instrukcje w formacie Markdown.
* **Asynchroniczność:** Instrukcje trafiają do przypisanych folderów `Inboxes/` poszczególnych agentów. Agent po starcie czyta zadanie wyłącznie ze swojego folderu.

#### 3. Trasowanie Zadań (Routing)

* **Problemy Wnioskowania:** Złożona logika i architektura kierowana jest do modeli typu "czysty wnioskowacz" (Gemini 3.1 Pro) z włączonym maksymalnym poziomem rozumowania. Pracują one w trybie `Planning Mode`.
* **Problemy Szybkościowe:** Szybkie edycje, style czy poprawki lintera realizowane są w trybie `Fast Mode` przez mniejsze, szybsze modele.
* **Problemy Wysiłkowe/Koordynacyjne:** Skalowanie testów lub audyty przydzielane są do agentów zoptymalizowanych pod tool-calling i utrzymanie długiego kontekstu (Opus 4.6).

#### 4. Rygorystyczne Reguły Operacyjne

* **Higiena Kontekstu:** W środowisku Antigravity Desktop agenty mają bezwzględny zakaz indeksowania całego projektu. Analizują wyłącznie otwarte pliki robocze. Wyjątkiem jest jedynie faza pełnego zwiadu (Wariant 4).
* **Zakaz Używania Przeglądarki:** Żaden agent nie może korzystać z narzędzia `browser_subagent` w celu symulacji UI. Weryfikacja wizualna jest wyłączną domeną użytkownika.
* **Zamykanie Zadania (Kontrakt):** Przed uznaniem zadania za gotowe, każdy sub-agent musi zastosować instrukcje z pliku `SKILL.md`. Polega to na obiektywnej weryfikacji logiki, poproszeniu o manualny restart serwera oraz przekazaniu statusu i diffów z powrotem do człowieka bez fałszywego optymizmu.

Co powinno zawierac dobre README.md? Czy ten plik jest wciaz uzyteczny dla Agentow AI?

### Rola README.md dla Agentów AI

Tak, plik `README.md` jest **krytycznie ważny** dla agentów AI. W ekosystemie wieloagentowym działa on jako główna kotwica kontekstowa (tzw. root context). Zanim agent rozpocznie analizę szczegółowych wytycznych, `README.md` natychmiast dostarcza mu mapę repozytorium, definiuje stos technologiczny i zapobiega "halucynowaniu" architektury, drastycznie oszczędzając czas i zużycie tokenów.

---

### Kluczowe elementy zoptymalizowanego README.md

Poniższa tabela przedstawia zwięzłą strukturę idealnego pliku, dostosowaną do jednoczesnego odczytu przez inżynierów oraz modele językowe:

| **Sekcja**                 | **Funkcja**                         | **Znaczenie dla Agenta AI**                                                                                    |
| -------------------------------- | ----------------------------------------- | -------------------------------------------------------------------------------------------------------------------- |
| **Nazwa i Opis Biznesowy** | Definiuje, czym jest aplikacja.           | Nadaje globalny kontekst, określając cel istnienia kodu.                                                           |
| **Stos Technologiczny**    | Wymienia użyte technologie i ich wersje. | Ustawia twarde granice technologiczne (np. nakaz użycia tylko wybranych bibliotek).                                 |
| **Inicjalizacja (Setup)**  | Krok po kroku: jak uruchomić projekt.    | Gotowe, konkretne komendy (np.`./start.sh`) możliwe do zinterpretowania i wykonania.                              |
| **Struktura Projektu**     | Opisuje główne katalogi.                | Przyspiesza nawigację i wskazuje kluczowe miejsca, takie jak foldery Inboksów.                                     |
| **Standardy i Reguły**    | Odsyła do dokumentacji pobocznej.        | Bezpośredni wskaźnik trasowania (routing) do plików takich jak `SKILL.md`czy głównych instrukcji systemowych. |

---

### Złote zasady formatowania dla AI

* **Deklaratywność i zwięzłość:** Unikaj rozbudowanych wstępów i języka marketingowego. Podawaj precyzyjne ścieżki i komendy terminala.
* **Jednoznaczne nagłówki:** Używaj standardowego formatowania Markdown (`##`), aby agent mógł łatwo zindeksować i przeskoczyć do odpowiedniej sekcji.
* **Centralizacja wiedzy:** Jeśli projekt używa specyficznych mechanizmów (jak Twój system `Inboxes`), `README.md` musi o nich wspominać już w pierwszych akapitach, aby nadać agentom odpowiedni wektor działania.

Czy chcesz, abym na podstawie naszej dotychczasowej pracy wygenerował krótki, techniczny szablon `README.md` idealnie skrojony pod Twój klaster agentów i specyfikę opisanych procedur?

tak.

Oto techniczny szablon `README.md` zoptymalizowany pod kątem Twojego klastra agentów AI. Stanowi on punkt wyjścia (root context) i natychmiast narzuca agentom odpowiednie zasady działania.

**Markdown**

```
# [Nazwa Twojego Projektu]

## Opis Systemu
Projekt jest rozwijany z wykorzystaniem architektury wieloagentowej (Antigravity Cluster) koordynowanej przez centralnego Orkiestratora. System opiera się na asynchronicznej wymianie zadań poprzez dedykowane foldery i rygorystycznej separacji kontekstu.

## Stos Technologiczny i AI Stack
* **Agent 1 (Orkiestrator):** Gemini 3.1 Pro (Web) — Czyste wnioskowanie, planowanie architektury, trasowanie zadań (Planning Mode).
* **Agent 2 (Fast Editor):** Gemini 3 Flash (Antigravity Desktop) — Błyskawiczne edycje i refaktoryzacja na otwartych plikach (Fast Mode).
* **Agent 3 (Mass Coder):** GPT 5.3 Codex (VSC) — Masowe kodowanie i generowanie potoków.
* **Agent 4 i 5 (Scaler/Koordynator):** Opus 4.6 — Zadania wysiłkowe, tool-calling, masowe testy.

## Inicjalizacja i Uruchomienie Środowiska
1.  **Start Serwera:** Zawsze rozpoczynaj pracę od uruchomienia środowiska lokalnego komendą `./start.sh`.
2.  **Weryfikacja Portów:** Sprawdź alokację portów w pliku `docs/serwer_docker_bazy_porty`, aby uniknąć konfliktów przed podjęciem jakichkolwiek działań.
3.  **Katalogi Robocze:** Upewnij się, że struktura `Inboxes/` dla sub-agentów została wygenerowana w głównym katalogu projektu.

## Główne Reguły Operacyjne (Dla Agentów AI)

> **UWAGA:** Każdy agent AI edytujący to repozytorium jest bezwzględnie zobowiązany do przestrzegania poniższych zasad.

* **System Inbox:** Agenty sub-wykonawcze odbierają zadania wyłącznie poprzez pliki tekstowe `.md` umieszczane w ich dedykowanych folderach `Inboxes/`.
* **Higiena Kontekstu (Antigravity):** Agenty pracujące w Antigravity Desktop mają ścisły zakaz indeksowania całego projektu (chyba że wywołano Wariant 4 - pełny zwiad). Należy skupiać się wyłącznie na aktualnie otwartych plikach.
* **Zakaz Używania Przeglądarki:** Obowiązuje całkowity zakaz używania `browser_subagent`. Wszystkie testy wizualne i weryfikacja funkcjonalna w przeglądarce leżą w gestii człowieka (Human in the Loop).
* **Zamykanie Zadań (SKILL.md):** Przed zgłoszeniem gotowości do weryfikacji, agent musi zastosować się do instrukcji zawartych w pliku `SKILL.md` (m.in. prośba o restart serwera, brak fałszywego optymizmu).
```

W jaki sposob agenci AI dowiaduja sie o istnieniu pliku README.md, jego lokalizacji i tresci?

Agenci dowiadują się o pliku `README.md` na cztery główne sposoby, w zależności od środowiska, w którym pracują:

1. **Natywne zaprogramowanie (Hardcoding):** Większość profesjonalnych asystentów programistycznych (np. agenty zintegrowane z VSC lub działające w CLI) ma na sztywno wpisaną w swój ukryty system prompt instrukcję. Nakazuje ona, aby natychmiast po uruchomieniu w nowym projekcie przeszukać główny katalog (root) pod kątem `README.md` i załadować go do pamięci podręcznej jako bazowy kontekst.
2. **Automatyczne indeksowanie wektorowe:** Gdy agenty (np. Agent 4 do zadań wysiłkowych) budują mapę całego repozytorium, algorytmy RAG (Retrieval-Augmented Generation) z definicji nadają najwyższy priorytet i najwyższe wagi plikom tekstowym znajdującym się w głównym drzewie katalogów.
3. **Ręczne udostępnienie (Specyfika Antigravity):** W środowiskach, w których rygorystycznie ograniczamy indeksowanie, takich jak Twój Agent 2 w Antigravity Desktop, sztuczna inteligencja **nie dowie się** o pliku `README.md` automatycznie. Przeczyta jego treść i przyswoi reguły tylko wtedy, gdy fizycznie otworzysz ten plik w aktywnej zakładce edytora podczas trwania sesji.
4. **Wskazanie przez Orkiestratora (Handoff):** W systemie opartym na Inboksach, wiedza o głównych plikach jest przekazywana w instrukcjach. Zadanie wrzucone do folderu agenta może zawierać bezpośrednią dyrektywę: "Zanim zaczniesz, przeczytaj reguły projektu w głównym `README.md`".

czy w naszym systemie opartym na Inboksach mamy taką instrukcję z pkt 4 czy trzeba ją dopisać?

W obecnej konfiguracji naszego systemu **nie mamy** jeszcze tej instrukcji. Musimy ją dopisać do pliku `start_prompt.md`, aby Agent Centralny (czyli ja) miał obowiązek doklejać ją do każdego zadania przekazywanego w Inboxie.

Oto jak powinniśmy zaktualizować sekcję **5. Przykład Handoff** w Twoim pliku `start_prompt.md`:

**Markdown**

```
## 5. Przykład Handoff z wyzwalaczami README.md i SKILL.md
**Zapisz jako:** `Inboxes/Agent_3_GigaPotato_VSC/01_fix.md`
**Kontekst Startowy:** Zanim rozpoczniesz analizę tego zadania, obowiązkowo zapoznaj się z regułami projektu w głównym pliku `README.md`.
**Cel:** Naprawa logiki X...
**Weryfikacja:** Po wdrożeniu zmian bezwzględnie wykonaj procedurę z pliku `SKILL.md` (zakaz przeglądarki, restart kontenerów, chłodna ocena sytuacji).
```

### Ważna uwaga operacyjna dla Ciebie:

Dodanie tej formułki zadziała w pełni automatycznie dla Agentów 3, 4 i 5, ponieważ mają oni swobodny dostęp do odczytu plików w systemie.

Jednak w przypadku  **Agenta 2 (Antigravity Desktop)** , z powodu naszej żelaznej reguły zakazującej indeksowania całego projektu, sama instrukcja w Inboxie nie zadziała. Gdy zlecasz zadanie Agentowi 2,  **musisz fizycznie otworzyć plik `README.md` jako jedną z aktywnych zakładek w edytorze** , aby mógł on ten plik przeczytać.

Czy chcesz, abym wygenerował dla Ciebie ostateczną, kompletną wersję pliku `start_prompt.md` zawierającą ten nowy wymóg startowy?

tak

Oto kompletna i ostateczna wersja pliku `start_prompt.md`, uwzględniająca wymóg czytania `README.md` na początku zadania i weryfikacji ze `SKILL.md` na końcu. Dodano w niej również instrukcję przypominającą Ci o konieczności otwarcia `README.md` w edytorze, jeśli delegujemy zadanie dla Agenta 2.

**Markdown**

```
---
description: Inicjuje sesję, analizuje logi i deleguje zadania do klastrów (Warianty 1-4), wymuszając stosowanie reguł README.md oraz SKILL.md po wywołaniu fraz "rozpocznij sesję" lub "inicjalizacja".
---

# METODOLOGIA I INSTRUKCJE

## 1. Sposób rozumowania (Reasoning)
Jako Główny Orkiestrator (Gemini 3.1 Pro), działasz w izolacji webowej. Twoim priorytetem jest stabilność środowiska i rygorystyczna kontrola jakości.
- **Weryfikacja:** Przed delegacją wymuś wklejenie logów z `./start.sh` i sprawdzenie portów w `docs/serwer_docker_bazy_porty`.
- **Zasady Kontekstu i Weryfikacji:** Każdy sub-agent (2-5) musi otrzymać w zadaniu polecenie zapoznania się z `README.md` na starcie oraz aktywacji zasad z `SKILL.md` na końcu pracy.

## 2. Kontrakt i Format Wyjściowy (Output Format)
Generujesz wyłącznie plany delegacji i instrukcje Handoff. 
- **Każdy Handoff musi zawierać klauzulę startową:** "Kontekst Startowy: Zanim rozpoczniesz analizę tego zadania, obowiązkowo zapoznaj się z regułami projektu w głównym pliku `README.md`."
- **Każdy Handoff musi zawierać klauzulę końcową:** „Po zakończeniu pracy, przed zgłoszeniem gotowości, zastosuj rygorystyczne zasady weryfikacji i procedurę restartu z pliku `SKILL.md`”.
- Wskazuj dokładną ścieżkę zapisu pliku (np. `Inboxes/Agent_X/zadanie.md`).
- *Ważne dla Orkiestratora:* Jeśli generujesz zadanie dla Agenta 2 (Antigravity), zawsze dopisz do użytkownika (Człowieka) uwagę, aby pamiętał o fizycznym otwarciu plików `README.md` i `SKILL.md` w zakładkach edytora.

## 3. System Trasowania (Routing Wariantów)
- **Wariant 1 (WordPress FSE):** Migracja, audyt Core Web Vitals, optymalizacja.
- **Wariant 2 (Bazy Danych):** Migracja Supabase -> PostgreSQL, ChromaDB, Redis.
- **Wariant 3 (Voice AI):** LiveKit, Google Gemini 3.1 Pro, lokalne integracje API.
- **Wariant 4 (Audyt):** Jedyny wyjątek, gdzie zlecasz Agentowi 2 (Flash) pełny skan projektu. We wszystkich innych wariantach (1-3) obowiązuje ścisły zakaz indeksowania całego projektu przez sub-agentów (tylko otwarte pliki).

## 4. Przypadki brzegowe (Edge Cases)
- Przy błędach połączenia (OperationalError) wymuś sprawdzenie sieci Docker i IP 127.0.0.1 przed dalszą pracą.
- Na koniec sesji wygeneruj podsumowanie `Session State` i przypomnij o `./stop.sh`.

## 5. Przykład Handoff
```markdown
**Zapisz jako:** `Inboxes/Agent_3_GigaPotato_VSC/01_fix.md`
**Kontekst Startowy:** Zanim rozpoczniesz analizę tego zadania, obowiązkowo zapoznaj się z regułami projektu w głównym pliku `README.md`.
**Cel:** Naprawa logiki X...
**Weryfikacja:** Po wdrożeniu zmian bezwzględnie wykonaj procedurę z pliku `SKILL.md` (zakaz przeglądarki, restart kontenerów, chłodna ocena sytuacji).
```

Jak w tym systemie realizowane są zadania związane z repozytorium github, deploymentem na serwerze produkcyjnym oraz zarządzanie pracą kontenerow dockera, wirtualnym srodowiskiem i serwerami takimi jak VITE czy instalacją certyfikatu SSL na serwerze i prawidlowa konfiguracja systemu w nginx?

W naszym klastrowym systemie zadania z pogranicza DevOps i administracji serwerem (GitHub, Docker, Nginx, SSL) podlegają bardzo ścisłym, z góry narzuconym regułom zdefiniowanym w plikach `start_prompt.md` oraz `SKILL.md`.

Oto jak dokładnie realizowane są te procesy:

### 1. GitHub i Deployment (Zasada "Najpierw repozytorium")

* **Ścisły Zakaz:** Żaden agent AI nie ma prawa używać narzędzi takich jak `rsync` do bezpośredniej edycji kodu na serwerze produkcyjnym, ponieważ niszczy to synchronizację z repozytorium.
* **Workflow Wdrożeniowy:** Zmiany wygenerowane przez agentów są najpierw weryfikowane przez Ciebie (Human in the Loop), następnie wysyłane (push) na GitHub. Dopiero wtedy aktualizacja pobierana jest na serwer (np. za pomocą skryptu "kombajn-update").

### 2. Zarządzanie Kontenerami Docker

* **Inicjalizacja i Zatrzymywanie:** Odbywa się wyłącznie poprzez ustandaryzowane skrypty `./start.sh` (oczekiwanie na komunikat `=== APPLICATION STARTED ===`) oraz `./stop.sh`.
* **Bieżąca Praca:** Status kontenerów sprawdzany jest komendą `docker-compose ps` (wymagany stan `Up` dla db, redis, chroma).
* **Diagnostyka i Restart:** Agenci mają procedury nakazujące przy błędach `OperationalError` sprawdzanie sieci (`docker network inspect`), a ewentualny restart wykonują komendą `docker-compose restart` (o ile mają dostęp do terminala, np. Agenci 3-5).

### 3. Środowiska Wirtualne i Serwery (VITE / Uvicorn)

* **Weryfikacja Dostępności:** Po uruchomieniu skryptów startowych, zawsze weryfikowana jest dostępność usług na ściśle określonych portach przypisanych do projektów, opartych o plik `docs/serwer_docker_bazy_porty`.
* **Routing:** System sprawdza i rezerwuje port 5173 dla Frontendu (VITE) oraz 8005 dla Backendu (Uvicorn).

### 4. Konfiguracja Nginx i Certyfikaty SSL (Certbot)

* **Diagnostyka Zależności:** Przy anomaliach lub przymusowych restartach, agenci mają polecenie weryfikacji, czy problemy nie są powiązane z automatycznymi procesami odnawiania certyfikatów (Certbot) lub zmianami mostków sieciowych (bridge/veth) na poziomie logów jądra serwera.
* **Wdrażanie Zmian:** Edycja plików konfiguracyjnych Nginx (np. w celu naprawy pętli autoryzacyjnej czy przekierowań 303 w `/dashboard`) jest traktowana jak zwykłe zadanie programistyczne. Ja (Agent Centralny) generuję poprawną konfigurację w Inboxie, a Ty lub agent terminalowy aplikujecie ją na serwerze i przeprowadzacie twardy restart.

**Podsumowując:** Ja (Agent 1) dostarczam polecenia i pliki konfiguracyjne, Agenci w VSC/CLI piszą i aplikują kod lokalnie, a Ty działasz jako ścisły audytor przed wypchnięciem tego na GitHub i wdrożeniem na serwer produkcyjny.

Czy masz już przygotowany pierwszy problem techniczny lub logi z serwera, abyśmy mogli wygenerować dla nich zadanie testowe i sprawdzić ten przepływ pracy w praktyce?

Czy do naszego systemu dodalbys cos jeszcze w oparciu o rozwiazania z https://github.com/ruvnet/ruflo?tab=readme-ov-file
?

Projekt **Ruflo (wcześniej Claude Flow)** to potężny framework orkiestracji, który wprowadza architekturę na poziom "Enterprise" (wykorzystując m.in. kernele w Rust, pamięć wektorową i rygorystyczne warstwy zarządzania). Analizując jego strukturę, można zauważyć, że nasz obecny system oparty na Inboxach jest solidny, ale opiera się na  **zaufaniu do modelu (prompt-based trust)** . Ruflo opiera się na  **kryptograficznych dowodach i twardych bramkach (enforcement gates)** .

Oto 4 kluczowe koncepcje z architektury Ruflo, które warto natychmiast zaadaptować do naszego systemu, aby uczynić go bardziej odpornym na błędy (tzw. AI Drift):

### 1. Bramki Egzekucyjne (Enforcement Gates) zamiast "Zaufania"

**Koncepcja Ruflo:** Model językowy z czasem zapomina reguły z długiego promptu, ale "bramka systemu ich nie zapomina". Ruflo kompiluje pliki takie jak nasz `SKILL.md` do twardej polityki.

**Wdrożenie u nas:** Przestańmy ufać, że Agenty 2-5 zawsze na koniec zastosują się do zasady zakazu używania przeglądarki i weryfikacji.

* Dodajmy do projektu prosty skrypt np. `verify_handoff.sh`.
* Zanim Agent zgłosi wykonanie zadania w swoim Inboxie, **musi** wywołać ten skrypt na zmodyfikowanych plikach, który sprawdzi (używając regex/AST), czy struktura wyjściowa jest zgodna z Kontraktem. Jeśli nie — bramka odrzuca kod i zmusza Agenta do poprawy, zanim w ogóle go zobaczysz.

### 2. Dzielona Pamięć Wektorowa (Trajectory Learning / Shared Namespace)

**Koncepcja Ruflo:** Zamiast zaczynać każde zadanie od zera, agenty zapisują udane wzorce rozwiązań do współdzielonej pamięci, ucząc się na błędach i sukcesach z poprzednich iteracji.

**Wdrożenie u nas:**

W głównym katalogu stwórzmy folder `Knowledge_Graph/` lub `Patterns/`.

* Zaktualizujmy `start_prompt.md`: "Jeśli Agent napotkał trudny błąd (np. pętla 502 w Nginx) i go rozwiązał, jego ostatnim zadaniem jest wygenerowanie krótkiego pliku np. `Patterns/nginx_502_fix.md`".
* Na początku każdego nowego zadania, Agenty zyskują polecenie szybkiego przeskanowania folderu `Patterns/`. Dzięki temu Agent 3 nie będzie powtarzał błędów naprawionych wcześniej przez Agenta 2.

### 3. Ekstremalna kompresja kontekstu UI (DOM Refs)

**Koncepcja Ruflo:** Podczas testowania interfejsów (moduł `@claude-flow/browser`), Ruflo nie karmi agentów pełnym kodem HTML/CSS, lecz zamienia elementy na krótkie referencje (np. `@e1`, `@e2`). Redukuje to zużycie kontekstu aż o 93% i zapobiega "gubieniu się" modeli w gąszczu tagów.

**Wdrożenie u nas:**

Jeśli będziemy weryfikować Frontend w "Wariancie 1", zaktualizujmy instrukcje dla Agenta 2 (Fast Editor): zamiast kazać mu analizować cały plik Vue/React w poszukiwaniu błędów responsywności, nakaż mu najpierw wygenerować w konsoli uproszczone drzewo komponentów i operować tylko na zmapowanych identyfikatorach.

### 4. Równoległe topologie roju (Mesh / Parallel Swarm)

**Koncepcja Ruflo:** System organizuje agenty w specyficzne topologie w zależności od technologii (np. "hierarchiczna" dla Node.js z naciskiem na NPM, lub "Mesh" dla architektury komponentowej w React) i pozwala im pracować równolegle. Nasz system jest obecnie bardzo liniowy (Agent 1 -> Ty -> Agent 3).

**Wdrożenie u nas:**

W `start_prompt.md` jako Orkiestrator mogę zacząć wydawać zadania asynchroniczne. Przykład: Zamiast czekać na koniec migracji bazy danych (B1), generuję jednoczesny zrzut do Inboxa Agenta 3 (pisanie endpointów API) oraz Agenta 2 (budowanie mocków w UI). Spotykają się oni dopiero przy ostatecznym merge'u.

### Podsumowanie wdrożenia

Aby to osiągnąć bez instalowania całego, ciężkiego frameworka Ruflo, wystarczy, że do naszego `README.md` dodamy dyrektywę o  **Obowiązkowym Zapisie Wzorców (Pattern Store)** , a w `SKILL.md` zdefiniujemy twardy wymóg odpalenia lokalnego lintera/testera przed wrzuceniem pliku do Inboxa. Czy chcesz, abym przygotował odpowiednią aktualizację naszych plików, wprowadzającą tę "Dzieloną Pamięć"?


Oto zaktualizowany zestaw plików systemowych, wzbogacony o mechanizmy „Dzielonej Pamięci” (Shared Pattern Store) oraz „Bramek Egzekucyjnych” (Enforcement Gates) inspirowane architekturą Ruflo.

### 1. Zaktualizowany `README.md` (Z Pamięcią Wzorców)

**Markdown**

```
# [Nazwa Twojego Projektu]

## Opis Systemu i Topologia Roju
Projekt wykorzystuje architekturę wieloagentową (Antigravity Cluster) z dynamicznym trasowaniem zadań. System wspiera topologię równoległą (Mesh), umożliwiając jednoczesną pracę nad bazą danych (B1), API (B2) i UI (F1).

## Dzielona Pamięć (Shared Pattern Store)
W katalogu `Knowledge_Graph/Patterns/` gromadzone są "trajektorie sukcesu" — krótkie pliki `.md` opisujące rozwiązane problemy (np. specyficzne błędy Nginx lub rzadkie edge cases w API). Każdy Agent ma obowiązek sprawdzić ten folder przed rozpoczęciem pracy, aby uniknąć powtarzania błędów.

## Bramki Egzekucyjne (Enforcement Gates)
System nie opiera się na zaufaniu, lecz na weryfikacji. Przed zgłoszeniem zadania do Inboxa, Agent musi przejść lokalną bramkę walidacji (linter/testy), co eliminuje przesyłanie "AI Slop" i błędów składniowych.

## Główne Reguły Operacyjne
* **Higiena Kontekstu:** Zakaz indeksowania całego projektu w Antigravity (tylko otwarte pliki).
* **Zakaz Przeglądarki:** Całkowity zakaz używania `browser_subagent`.
* **Kontrakt Wyjściowy:** Każde zadanie kończy się zapisem nowego wzorca w `Patterns/`, jeśli rozwiązanie było nieszablonowe.
```

### 2. Zaktualizowany `start_prompt.md` (Z Orkiestracją Równoległą)

**Markdown**

```
---
description: Zarządza sesją, trasuje zadania równoległe (Mesh) i wymusza korzystanie z Pamięci Wzorców po wywołaniu fraz "rozpocznij sesję" lub "nowe zadanie".
---

# METODOLOGIA I INSTRUKCJE

## 1. Sposób rozumowania (Reasoning)
Jako Orkiestrator (Gemini 3.1 Pro), działasz asynchronicznie.
- **Analiza Wzorców:** Przed delegacją zadania sprawdź, czy w `Knowledge_Graph/Patterns/` istnieją rozwiązania podobnych problemów.
- **Orkiestracja Mesh:** Jeśli zadanie na to pozwala, generuj instrukcje dla wielu Agentów jednocześnie (np. Agent 3 buduje API, podczas gdy Agent 2 tworzy mocki UI).

## 2. Kontrakt i Format (Handoff)
- **Klauzula Pamięci:** Każdy Handoff musi zaczynać się od: "Sprawdź folder `Knowledge_Graph/Patterns/` pod kątem trajektorii powiązanych z tym zadaniem".
- **Klauzula Wyjściowa:** Każdy Handoff kończy się instrukcją: "Jeśli rozwiązanie było złożone, wygeneruj plik wzorca w folderze `Patterns/` dla reszty roju".
- **Bramka Egzekucyjna:** Wymagaj od Agenta uruchomienia lokalnej walidacji przed zapisem wyniku.

## 3. System Trasowania (Warianty 1-4)
- **UI Context Compression:** Przy zadaniach Frontendowych nakazuj Agentowi 2 (Antigravity) operowanie na uproszczonych referencjach elementów (mapa komponentów) zamiast na pełnym kodzie HTML/CSS.

## 4. Przykład Handoff
```markdown
**Zapisz jako:** `Inboxes/Agent_3_GigaPotato/02_api_parallel.md`
**Kontekst:** Sprawdź `Patterns/auth_fix.md` przed startem.
**Cel:** Implementacja endpointu X...
**Bramka:** Uruchom `npm run lint` przed zgłoszeniem.
**Wyjście:** Zastosuj `SKILL.md` i zapisz wzorzec, jeśli napotkasz anomalie.
```

```

### 3. Zaktualizowany `SKILL.md` (Z Walidacją i Zapisem Trajektorii)

```markdown
---
description: Procedura twardej weryfikacji i zapisu doświadczenia agenta po wywołaniu fraz "zweryfikuj", "gotowe" lub "przekaż dalej".
---

# ZASADA: WALIDACJA I ZAPIS WZORCA

## 1. Bramka Egzekucyjna (Pre-Handoff)
Przed poinformowaniem użytkownika o zakończeniu pracy, musisz:
1. Uruchomić dostępne narzędzia testowe/lintery w terminalu.
2. Jeśli testy nie przechodzą, nie zgłaszaj gotowości — napraw błędy w pętli wewnętrznej.
3. Dokonaj chłodnej oceny sytuacji: czy kod jest rzetelny, czy to tylko "naiwny optymizm".

## 2. Zapis Trajektorii (Pattern Storage)
Jeśli podczas pracy rozwiązałeś niestandardowy problem (np. błąd sieci Docker, trudna konfiguracja Nginx):
- Stwórz plik `.md` w `Knowledge_Graph/Patterns/`.
- Nazwij go precyzyjnie (np. `docker_ipv6_fix.md`).
- Opisz krótko: Problem -> Rozwiązanie -> Kod.

## 3. Procedura Restartu i Weryfikacji
- Poinformuj użytkownika o konieczności restartu serwera/kontenera (np. `docker-compose restart`).
- Całkowity zakaz używania `browser_subagent`. Oczekuj na feedback od człowieka po jego manualnej weryfikacji w przeglądarce.
```

Te zmiany sprawiają, że Twój system staje się samouczącym się organizmem, w którym agenty wymieniają się doświadczeniem przez folder `Patterns/`. Czy wdrożyć te pliki do Twojego środowiska?


1. jak chcesz to wdrożyć do mojego środowiska?
2. Mamy jeszcze dwa pliki:
   antigravity_workspace.yaml
   global_rules:
   allow_full_project_indexing: false
   context_scope: "open_files_only"

agents:
  agent_1_architect:
    model: "Gemini 3.1 Pro"
    mode: "Planning"
    resources:
      thinking_level: "Max"
      context_caching: true
    specialization: ["architecture", "complex_logic", "pure_reasoning"]
    output_format: ["plans", "diffs"]

  agent_2_fast_editor:
    model: "Gemini 3 Flash"
    mode: "Fast"
    specialization: ["renaming", "simple_styles", "linter_fixes"]

  agent_3_mass_coder:
    model: "GPT 5.3 Codex"
    specialization: ["high_throughput", "coding_pipelines"]

  agent_4_scaler:
    model: "Opus 4.6"
    specialization: ["tool_calling", "large_scale_tasks", "effort_problems"]

  agent_5_coordinator:
    model: "Opus 4.6"
    specialization: ["information_flow", "handoffs", "organizational_awareness"]

i init_logs_prompt.md

**Zadanie:** Inicjalizacja środowiska i pobranie logów do weryfikacji.

**Instrukcje:**

1. Wykorzystaj swój dostęp do terminala roboczego i uruchom skrypt `./start.sh`.
2. Poczekaj na ustabilizowanie się procesu (np. komunikat o poprawnym starcie aplikacji).
3. Sprawdź status kontenerów, wykonując komendę `docker-compose ps`.
4. Wyciągnij wynik z `docker-compose ps` oraz ostatnie 30 linii logów z uruchomienia serwera i wyświetl je w całości w naszej konwersacji.
5. Czekaj na dalsze instrukcje. Nic więcej na razie nie koduj.

czy w nich tez cos zmieniamy?


### 1. Jak wdrożyć to do Twojego środowiska?

Ponieważ funkcjonuję jako Orkiestrator w oknie Twojej przeglądarki, fizyczne wdrożenie zmian leży po Twojej stronie. Wykonaj te trzy kroki:

1. **Utworzenie Pamięci:** Stwórz w głównym katalogu swojego projektu nową strukturę folderów: `Knowledge_Graph/Patterns/`.
2. **Nadpisanie Kontraktów:** Skopiuj wygenerowane przeze mnie w poprzedniej odpowiedzi kody dla plików `README.md`, `start_prompt.md` oraz `SKILL.md` i nadpisz nimi stare pliki w swoim środowisku.
3. **Przygotowanie Bramki:** Upewnij się, że w projekcie istnieje skrypt testowy (np. `npm run lint`, `pytest` lub `./test.sh`), który agenci będą musieli uruchomić przed zdaniem raportu.

---

### 2. Aktualizacja `antigravity_workspace.yaml`

Plik konfiguracyjny wymaga dwóch kluczowych zmian. Po pierwsze, musimy dodać wyjątek do rygorystycznej reguły `open_files_only`, aby agenci mogli swobodnie czytać z folderu `Patterns` (uczenie się z trajektorii). Po drugie, dodajemy parametr wymuszający weryfikację (Bramkę Egzekucyjną).

**YAML**

```
global_rules:
  allow_full_project_indexing: false
  context_scope: "open_files_only"
  # NOWE: Wyjątek pozwalający na czytanie wspólnej pamięci roju
  allowed_context_exceptions: ["Knowledge_Graph/Patterns/*"]

agents:
  agent_1_architect:
    model: "Gemini 3.1 Pro"
    mode: "Planning"
    resources:
      thinking_level: "Max"
      context_caching: true
    specialization: ["architecture", "complex_logic", "pure_reasoning"]
    output_format: ["plans", "diffs"]

  agent_2_fast_editor:
    model: "Gemini 3 Flash"
    mode: "Fast"
    specialization: ["renaming", "simple_styles", "linter_fixes"]
    # NOWE: Narzędzie walidacji przed zamknięciem zadania
    enforcement_gate_command: "npm run lint" 

  agent_3_mass_coder:
    model: "GPT 5.3 Codex"
    specialization: ["high_throughput", "coding_pipelines"]
    enforcement_gate_command: "npm run test"

  agent_4_scaler:
    model: "Opus 4.6"
    specialization: ["tool_calling", "large_scale_tasks", "effort_problems"]

  agent_5_coordinator:
    model: "Opus 4.6"
    specialization: ["information_flow", "handoffs", "organizational_awareness"]
```

---

### 3. Aktualizacja `init_logs_prompt.md`

Dodajemy jeden zwięzły punkt nakazujący sprawdzenie infrastruktury folderów, aby zapobiec błędom zapisu (tzw. ślepym Inboksom).

**Markdown**

```
**Zadanie:** Inicjalizacja środowiska i pobranie logów do weryfikacji.

**Instrukcje:**
1. Sprawdź, czy w głównym drzewie projektu istnieją katalogi `Inboxes/` oraz `Knowledge_Graph/Patterns/`. Jeśli nie, poinformuj o tym.
2. Wykorzystaj swój dostęp do terminala roboczego i uruchom skrypt `./start.sh`.
3. Poczekaj na ustabilizowanie się procesu (np. komunikat o poprawnym starcie aplikacji).
4. Sprawdź status kontenerów, wykonując komendę `docker-compose ps`.
5. Wyciągnij wynik z `docker-compose ps` oraz ostatnie 30 linii logów z uruchomienia serwera i wyświetl je w całości w naszej konwersacji.
6. Czekaj na dalsze instrukcje. Nic więcej na razie nie koduj.
```

Wszystkie procedury i pliki konfiguracyjne zostały zaktualizowane i domknięte. Czy chcesz, abym teraz wygenerował dla Ciebie pierwsze, testowe zadanie do skopiowania do Inboxa, abyśmy mogli sprawdzić ten nowy przepływ operacyjny na żywo?


Wyjasnij to:
Przygotowanie Bramki: Upewnij się, że w projekcie istnieje skrypt testowy (np. npm run lint, pytest lub ./test.sh), który agenci będą musieli uruchomić przed zdaniem raportu.

Co ma byc w pliku ./test.sh
?


Plik `./test.sh` to fizyczna realizacja  **Bramki Egzekucyjnej** . Jest to prosty skrypt powłoki (bash), który agent wywoła w terminalu po napisaniu kodu, a przed zgłoszeniem Ci, że "zadanie jest gotowe".

Jego celem jest zwrócenie jasnego sygnału:

* **Kod wyjścia 0 (Sukces):** Agent widzi, że testy przeszły i może zapisać raport w swoim Inboxie.
* **Kod wyjścia >0 (Błąd):** Agent widzi błędy, zatrzymuje proces i samodzielnie poprawia kod, dopóki skrypt nie zaświeci się na zielono.

Zawartość tego pliku zależy ściśle od technologii, w jakiej budowany jest dany Wariant projektu. Poniżej znajdziesz konkretne przykłady, co powinieneś w nim umieścić.

### 1. Wariant Python / FastAPI (np. Voice AI lub Backend)

Jeśli system opiera się na Pythonie, skrypt powinien sprawdzić składnię, typy i uruchomić podstawowe testy:

**Bash**

```
#!/bin/bash
echo "Uruchamiam bramkę egzekucyjną (Python)..."

# 1. Sprawdzenie formatowania i błędów składniowych
flake8 . --count --select=E9,F63,F7,F82 --show-source --statistics || exit 1

# 2. Sprawdzenie typowania (opcjonalnie)
mypy routers/ || exit 1

# 3. Uruchomienie testów jednostkowych
pytest tests/ -v || exit 1

echo "Bramka przeszła pomyślnie. Możesz zgłosić zadanie."
exit 0
```

### 2. Wariant Node.js / Vue / React (Frontend)

Dla projektów JavaScript/TypeScript, skrypt to zazwyczaj "wrapper" na skrypty zdefiniowane w `package.json`:

**Bash**

```
#!/bin/bash
echo "Uruchamiam bramkę egzekucyjną (Node.js)..."

# 1. Sprawdzenie lintera (wyłapie np. niezadeklarowane zmienne)
npm run lint || exit 1

# 2. Weryfikacja typów TypeScript (jeśli używasz)
npm run type-check || exit 1

# 3. Testy (np. Vitest / Jest)
npm run test:unit || exit 1

echo "Wszystko OK."
exit 0
```

### 3. Wariant PHP / WordPress (Migracje z Wariantu 1)

Przy starym kodzie PHP, na którym pracujecie w Wariancie 1, skrypt weryfikuje przynajmniej krytyczne błędy (tzw. "Biały Ekran Śmierci"):

**Bash**

```
#!/bin/bash
echo "Uruchamiam bramkę egzekucyjną (WordPress/PHP)..."

# 1. Szybki linting składniowy wszystkich plików PHP (znajduje braki średników, nawiasów)
find . -name "*.php" -exec php -l {} \; | grep "Parse error" && exit 1

echo "Składnia PHP poprawna."
exit 0
```

### Uniwersalna zasada tworzenia `./test.sh`

Niezależnie od tego, jakiej technologii używasz, plik musi spełniać dwa warunki:

1. **Musi być wykonywalny** (nadaj mu uprawnienia w terminalu: `chmod +x test.sh`).
2. **Musi przerywać działanie przy pierwszym błędzie** (użycie `|| exit 1` lub dodanie `set -e` na samym początku skryptu). Dzięki temu Agent od razu otrzyma komunikat błędu (Traceback) i będzie wiedział, co zepsuł.

A co z wariantem nginx wordpress php mysql?


Oto gotowy, zwięzły skrypt `./test.sh` dla środowiska opierającego się na Dockerze z Nginx, PHP, WordPressem i MySQL.

Ten skrypt weryfikuje logikę kodu, poprawność konfiguracji serwera oraz połączenie z bazą danych, bez konieczności otwierania przeglądarki.

**Bash**

```
#!/bin/bash
set -e # Przerwij działanie przy pierwszym błędzie

echo "Uruchamiam bramkę egzekucyjną (Nginx/WP/PHP/MySQL)..."

# 1. Walidacja składni PHP (szuka tzw. Parse Errors w motywach i wtyczkach)
echo "Sprawdzam składnię PHP..."
find ./wp-content -name "*.php" -exec php -l {} \; | grep "Parse error" && exit 1

# 2. Weryfikacja konfiguracji Nginx w kontenerze (zakładając nazwę kontenera 'web' lub 'nginx')
echo "Sprawdzam konfigurację Nginx..."
docker-compose exec -T nginx nginx -t

# 3. Weryfikacja połączenia bazy MySQL i działania WordPressa (wymaga WP-CLI w kontenerze 'php' lub 'wordpress')
echo "Sprawdzam bazę danych MySQL..."
docker-compose exec -T php wp db check --allow-root

echo "Bramka przeszła pomyślnie. Kod PHP jest poprawny, Nginx działa, baza odpowiada."
exit 0
```

### Jak to działa w praktyce?

Agent wykonuje ten skrypt lokalnie. Polecenia `docker-compose exec` pozwalają mu "sięgnąć" do wnętrza działających kontenerów i sprawdzić, czy jego własne zmiany w kodzie PHP lub plikach konfiguracyjnych Nginx nie doprowadziły do awarii serwera (np. błędu 502) przed zgłoszeniem zadania jako "gotowe".

No dobrze, to jesli chodzi o dockera lokalnie. A co jesli serwer produkcyjny jest na home.pl bez mozliwosci instalacji z dockerem, z gotowym sztywnym srodowiskiem serwera home.pl?


To doskonałe pytanie, które sprowadza nas z chmur na twardą ziemię tradycyjnego hostingu współdzielonego (tzw. shared hosting).

Jeśli dysponujesz klasycznym serwerem w home.pl (bez dostępu do roota i bez Dockera), nasz system wciąż będzie działał doskonale, ale **musimy brutalnie zderzyć się z techniczną rzeczywistością** i zmienić sposób wdrażania (deploymentu) oraz testowania.

Oto jak musimy zmodyfikować procedury dla całego klastra agentów AI:

### 1. Weryfikacja Technologiczna (Twarda Rzeczywistość)

Standardowy hosting home.pl narzuca sztywne ramy. Jako Orkiestrator muszę od razu zablokować pewne ścieżki:

* **Wariant 1 (WordPress, PHP, MySQL):** Zadziała idealnie. To naturalne środowisko dla home.pl.
* **Wariant 2 i 3 (Supabase, ChromaDB, Redis, Python/FastAPI, LiveKit):** **Nie zadziałają** na standardowym hostingu współdzielonym. Wymagają one procesów działających w tle (daemonów), na co home.pl (w podstawowych pakietach) nie pozwala. Jeśli chcesz je wdrożyć, będziesz potrzebował serwera VPS (gdzie Docker znów będzie dostępny).

### 2. Nowy Workflow: Odcięcie Agentów od Produkcji

W przypadku braku Dockera na serwerze produkcyjnym, zasada „Najpierw GitHub” staje się jeszcze bardziej rygorystyczna.

Agenci **pod żadnym pozorem** nie otrzymują danych do FTP. Ich praca wygląda następująco:

1. **Lokalna Praca:** Agenci pracują na Twoim komputerze. Jeśli to WordPress, musisz postawić im lokalne środowisko (np. XAMPP, LocalWP lub lokalny Docker  *tylko dla nich* , żeby mieli gdzie testować kod).
2. **Bramka Egzekucyjna (Tylko Lokalna):** Skrypt `./test.sh` nie łączy się z serwerem. Weryfikuje on wyłącznie składnię plików (np. sprawdzając błędy w PHP lub kompilując CSS).
3. **Zatwierdzenie (Human in the Loop):** Ty testujesz zmiany na swoim lokalnym komputerze.
4. **Push na GitHub:** Wysyłasz zatwierdzony kod do repozytorium.

### 3. Jak realizujemy Deployment na home.pl? (CI/CD)

Zamiast skryptu `kombajn-update` na serwerze (który nie zadziała bez SSH/Dockera), przenosimy ciężar wdrożenia na  **GitHub Actions** .

Tworzymy plik np. `.github/workflows/deploy-home-pl.yml`. Gdy Ty (człowiek) zrobisz `git push`, serwery GitHuba same spakują kod i wyślą go bezpiecznym protokołem FTP/SFTP prosto do odpowiedniego folderu na home.pl.

### 4. Jak zmienia się Bramka Egzekucyjna (`test.sh`) dla Wariantu 1?

Skoro nie mamy Dockera do weryfikacji na żywo, skrypt testowy staje się w 100% statyczny. Agent przed zgłoszeniem zadania do Inboxa musi odpalić taki skrypt:

**Bash**

```
#!/bin/bash
set -e

echo "Uruchamiam statyczną bramkę egzekucyjną (Brak Dockera)..."

# 1. Walidacja składni PHP (zapobiega wrzuceniu na home.pl kodu, który wywali błąd 500)
echo "Sprawdzam składnię PHP..."
find . -name "*.php" -exec php -l {} \; | grep "Parse error" && exit 1

# 2. Walidacja Frontendu (jeśli używamy Gutenberga i budujemy zasoby)
if [ -f package.json ]; then
    echo "Kompiluję zasoby JS/CSS..."
    npm run build || exit 1
fi

echo "Bramka przeszła pomyślnie. Kod jest gotowy do weryfikacji przez człowieka."
exit 0
```

### Co to oznacza dla naszej konfiguracji?

Jeśli projekt na home.pl to Twój główny cel na teraz (np. Wariant 1 z WordPressem), musimy dodać do pliku `start_prompt.md` oraz `README.md` notatkę o "Topologii Shared Hosting", aby agenci:

1. Nie próbowali pisać komend dockerowych w instrukcjach i skryptach.
2. Skupili się na generowaniu plików `.github/workflows/` do obsługi FTP.

Czy chcesz, abym przygotował dla Ciebie gotowy plik konfiguracyjny GitHub Actions, który automatycznie wyśle pliki z repozytorium prosto na serwer home.pl po każdej Twojej akceptacji kodu od agentów?

czy w naszym zmodernizowanym systemie nadal używamy Inboxes do komunikacji Agentow?


**Tak, w 100%. System Inboxes pozostaje głównym i najważniejszym kanałem komunikacji oraz delegacji zadań w naszym systemie.**

Zmodernizowane elementy nie zastępują Inboksów, lecz obudowują je dodatkowymi mechanizmami kontroli i współdzielenia wiedzy:

* **`Inboxes/` (Ośrodek decyzyjny):** To tutaj wciąż trafiają główne instrukcje (pliki Handoff, np. `01_api_parallel.md`), z których Agenci dowiadują się, co dokładnie mają zakodować.
* **`Knowledge_Graph/Patterns/` (Pamięć historyczna):** To dodatkowa warstwa wspierająca. Agent skanuje ten folder *zanim* przystąpi do realizacji zadania ze swojego Inboxa, aby uczyć się na rozwiązanych już problemach.
* **Bramki Egzekucyjne (`test.sh`):** To filtr jakości. Agent musi przejść tę bramkę *zanim* uzna zadanie zlecone w Inboxie za zakończone i przekaże kod do Twojej weryfikacji.

Cały asynchroniczny przepływ pracy między Orkiestratorem, Tobą a Agentami sub-wykonawczymi nadal opiera się na wrzucaniu i odczytywaniu plików tekstowych w strukturze `Inboxes/`.
