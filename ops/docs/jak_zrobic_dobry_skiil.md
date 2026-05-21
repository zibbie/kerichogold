Tworzenie pliku `skill.markdown` (lub `skill.mmarkdown`) polega na zapisaniu zestawu instrukcji, które posłużą jako przewodnik dla agenta AI, pozwalając mu na przewidywalne i skuteczne wykonanie konkretnego zadania. Fizycznie jest to po prostu plik tekstowy umieszczony w dedykowanym folderze, który składa się z dwóch głównych części: metadanych na górze oraz metodologii i instrukcji poniżej.

Aby stworzyć skuteczny plik `skill.markdown` zoptymalizowany dla agentów AI, należy trzymać się następujących zasad:

**1. Zadbaj o precyzyjny opis (Metadane / Trigger)**
Opis jest najważniejszym elementem, na którym "wykłada się" większość umiejętności (skilli). W przypadku agentów AI opis działa jak sygnał trasowania (routing signal), mówiący agentowi, kiedy powinien użyć danej umiejętności.
*   **Unikaj ogólników:** Opis taki jak "pomaga w analizie konkurencji" jest zbyt rozmyty.
*   **Bądź konkretny:** Nazwij typy dokumentów lub artefaktów, które skill ma wyprodukować, określ format wyjściowy i podaj konkretne "frazy wyzwalające" (np. "przeanalizuj naszych konkurentów").
*   **Kluczowy wymóg techniczny:** Opis umiejętności **musi znajdować się w jednej linijce**. Jeśli zostanie rozbity na kilka linii (np. przez formater kodu), model AI nie odczyta go poprawnie.
*   Autor materiału zaleca poświęcenie aż 80% uwagi na dopracowanie pola opisu, aby upewnić się, że umiejętność uruchomi się w odpowiednim momencie.

**2. Skonstruuj odpowiednią metodologię (Treść instrukcji)**
Sekcja instrukcji określa, co agent ma zrobić po wywołaniu umiejętności. Dobrze napisana metodologia powinna zawierać pięć elementów:
*   **Sposób rozumowania (Reasoning):** Nie podawaj wyłącznie liniowych instrukcji krok po kroku, ponieważ tworzy to bardzo "kruchą" umiejętność. Zamiast tego dostarcz modelowi AI swoje ramy koncepcyjne (frameworki), kryteria jakości i zasady, którymi kierujesz się przy podejmowaniu decyzji.
*   **Określony format wyjściowy:** Zdefiniuj precyzyjnie, czy wynikiem ma być plik Markdown, Excel, PDF, oraz jakie dokładnie sekcje lub pola ma zawierać.
*   **Wyraźne przypadki brzegowe (Edge cases):** Zapisz wszystko to, co człowiek rozwiązałby za pomocą zdrowego rozsądku. Nie zakładaj, że sztuczna inteligencja domyśli się, jak poradzić sobie z nietypowymi sytuacjami.
*   **Przykłady:** Dostarcz agentowi przykład pokazujący, jak wygląda poprawnie wykonane zadanie, aby mógł dopasować do niego wzorzec (przykład ten może znajdować się w osobnym pliku w tym samym folderze).
*   **Zachowaj zwięzłość:** Krótki, niezawodny skill działa lepiej niż długi tekst ze sprzecznymi instrukcjami. Główny plik nie powinien zazwyczaj przekraczać 100-150 linijek.

**3. Projektuj pod kątem agentów (Agent-First Design)**
Skoro docelowym użytkownikiem skilla jest autonomiczny agent AI, a nie człowiek, musisz wziąć pod uwagę jego specyfikę:
*   **Traktuj wynik jako kontrakt:** Agent musi wiedzieć, co dokładnie otrzyma, a czego nie otrzyma po użyciu tej umiejętności. Opisz to na zasadzie kontraktu API, deklarując jasne warunki (SLA) dla agenta.
*   **Kompozycyjność (Composability):** Umiejętność rzadko rozwiązuje problem w całości. Pomyśl o niej jako o etapie procesu – wygenerowany wynik musi być w takiej formie, aby mógł zostać płynnie przekazany do kolejnego agenta w łańcuchu zadań.

**Jak najłatwiej zacząć?**
Jeśli chcesz stworzyć swój pierwszy plik `skill.markdown`, zidentyfikuj zadanie, które często powtarzasz (np. kilka razy w tygodniu), a następnie **poproś swój ulubiony model AI o pomoc**. Możesz nakarmić go historią Waszych poprzednich konwersacji na ten temat, wskazać, co poszło dobrze, a co źle, i poprosić o wygenerowanie pliku na tej podstawie. Następnie testuj swój skill ilościowo i udoskonalaj go (jak oprogramowanie z systemem kontroli wersji), by z czasem działał coraz lepiej.


W dostarczonych źródłach nie ma dosłownego, skopiowanego w całości przykładu gotowego pliku `skill.markdown` (lub `skill.mmarkdown`). Autor materiału podaje jednak bardzo szczegółowe wytyczne, z jakich elementów musi się on składać. 

Aby ułatwić Ci zadanie, **stworzyłem poniższy przykład na podstawie reguł opisanych w źródłach**. *Uwaga: Sama treść tego przykładu jest moją inwencją (informacją spoza źródeł), ale jego struktura rygorystycznie i bezpośrednio odzwierciedla zasady omówione w materiale.*

Oto jak mógłby wyglądać plik `skill.mmarkdown` dla agenta wykonującego analizę konkurencji (oparty na wskazówkach ze źródeł):

***

```markdown
---
description: Przeprowadza analizę konkurencji, identyfikuje graczy, ocenia ich strategię i generuje raport w formacie Markdown z tabelą po wywołaniu fraz "przeanalizuj naszych konkurentów" lub "kto jest graczem na tym rynku".
---

# METODOLOGIA I INSTRUKCJE

## 1. Sposób rozumowania (Reasoning)
Twoim celem jest działanie jako analityk biznesowy. Zamiast tworzyć proste podsumowania, stosuj ramy oceny wartości (Value Proposition Canvas). Oceniaj rynek przez pryzmat barier wejścia i unikalnej propozycji wartości (UVP) każdej firmy. Szukaj luk w strategiach konkurentów, które nasza firma mogłaby wykorzystać. 

## 2. Wymagany format wyjściowy (Output Format)
Zawsze generuj wynik jako plik Markdown. 
Dokument musi zawierać dokładnie te sekcje:
1. Podsumowanie wykonawcze (maksymalnie 3 zdania).
2. Tabela Głównych Graczy (kolumny: Firma, Główny Produkt, Mocne Strony, Słabe Strony).
3. Luki rynkowe i Rekomendacje.

## 3. Przypadki brzegowe (Edge Cases)
- Jeśli w udostępnionych danych brakuje informacji o zarobkach/finansach danej firmy, nie próbuj ich zgadywać ani halucynować. Wpisz w tabeli "Brak danych publicznych".
- Jeśli zapytanie dotyczy bardzo niszowego rynku i znajdziesz tylko jednego konkurenta, nie szukaj na siłę innych. Przeprowadź głęboką analizę tego jednego podmiotu.

## 4. Przykłady (Examples)
Aby upewnić się, jak wygląda poprawnie sformatowany raport, dopasuj swój wzorzec do pliku `example_competitive_analysis.md` znajdującego się w tym samym folderze.
```

***

### Dlaczego ten przykład jest zbudowany właśnie w ten sposób (zgodnie ze źródłami)?

*   **Jedna linijka metadanych:** Opis (sekcja `description`) znajduje się na samej górze i zajmuje **ściśle jedną linijkę**. Źródła ostrzegają, że jeśli formater kodu rozbije opis na kilka linii, model Claude (lub inny) nie przeczyta go poprawnie i umiejętność nie zadziała.
*   **Wyzwalacze (Triggers):** Opis zawiera konkretne frazy wyzwalające, takie jak *"przeanalizuj naszych konkurentów"* lub *"kto jest graczem na tym rynku"*, co jest podane w materiale jako przykład dobrej praktyki, w przeciwieństwie do ogólnikowego *"pomaga w analizie konkurencji"*.
*   **Wymagane 5 elementów metodologii:** Poniżej opisu znajduje się metodologia, która zawiera:
    1.  **Rozumowanie:** Zamiast liniowych instrukcji, podane są zasady i kryteria jakości, którymi agent ma się kierować.
    2.  **Format:** Określono dokładnie, że wynikiem ma być plik Markdown z konkretnymi sekcjami.
    3.  **Przypadki brzegowe:** Opisano sytuacje niestandardowe (np. brak danych), by agent nie musiał polegać na "zdrowym rozsądku", którego nie posiada.
    4.  **Przykłady:** Wskazano plik referencyjny do dopasowania wzorca (pattern matching).
    5.  **Zwięzłość:** Całość jest krótka (lean). Autor zaznacza, że rdzeń pliku nie powinien przekraczać 100-150 linijek, aby nie przeciążać okna kontekstowego modelu i zapewnić niezawodne działanie skilla.

    Zdefiniowanie kontraktu dla agenta polega na sformułowaniu **wyniku działania umiejętności (skilla) w formie jasnej umowy** – analogicznej do kontraktu API lub umowy SLA (Service Level Agreement) znanej programistom. 

Ponieważ agenty AI rozwiązują problemy, "myśląc w kategoriach kontraktów", taka deklaratywna umowa pozwala im w łatwy sposób zweryfikować przeznaczenie skilla i z pełnym przekonaniem podjąć poprawną decyzję o jego użyciu. Aby właściwie zdefiniować ten kontrakt, musisz precyzyjnie opisać w instrukcjach następujące elementy:

*   **Co agent otrzyma:** Precyzyjne wskazanie, co dokładnie jest wynikiem działania tej umiejętności.
*   **Czego agent NIE otrzyma:** Jasne postawienie granic i określenie braków, aby agent nie opierał się na błędnych założeniach.
*   **Cel i możliwości:** Zdefiniowanie, co dana umiejętność pozwala agentowi osiągnąć i jak można ją wykorzystać do realizacji konkretnego, nadrzędnego zadania.
*   **Kontrolowane pola (controllable fields):** Wskazanie konkretnych parametrów i struktur, na których agent będzie operował.

Takie podejście do projektowania instrukcji jest fundamentem tzw. **kompozycyjności (composability)**. Definiując kontrakt wyjściowy, nie powinieneś traktować skilla jako czegoś, co samodzielnie rozwiązuje cały problem. Zamiast tego, wynik opisany w kontrakcie musi być sformatowany w taki sposób, aby mógł zostać bezbłędnie i przewidywalnie **przekazany kolejnemu agentowi lub sub-agentowi** na dalszym etapie procesu biznesowego.