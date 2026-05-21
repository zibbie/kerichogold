# Dokumentacja: Moduł Testów A/B (Continuous Experimentation)

Moduł Testów A/B pozwala na naukowe podejście do optymalizacji konwersji (CRO) w sklepie Nevro-Shop poprzez porównywanie różnych wersji strony i wybieranie tych, które przynoszą lepsze rezultaty.

## 1. Jak działają testy?

System wykorzystuje mechanizm **Split-Testing** po stronie serwera:
1.  **Przydział:** Kiedy użytkownik wchodzi na stronę, middleware sprawdza aktywne eksperymenty.
2.  **Losowanie:** Jeśli użytkownik nie brał jeszcze udziału w teście, zostaje mu przypisany wariant (np. A lub B) na podstawie zdefiniowanych wag (procent ruchu).
3.  **Persystencja:** Przypisany wariant jest zapisywany w sesji użytkownika, dzięki czemu przy kolejnych przeładowaniach strony widzi on zawsze tę samą wersję (brak efektu migotania).
4.  **Śledzenie:** Każda wizyta jest zliczana w bazie danych w tabeli `experiment_variants`.

## 2. Zarządzanie w Panelu Admina

W sekcji **Analityka SEO -> Eksperymenty A/B** możesz:
*   Tworzyć nowe eksperymenty (pamiętaj o unikalnym polu **Slug**, które jest używane w kodzie).
*   Dodawać warianty (klucze `A`, `B`, `C` itd.).
*   Aktywować/Dezaktywować testy jednym przełącznikiem.

## 3. Implementacja w kodzie (Blade)

Aby wyświetlić różną treść dla różnych grup, użyj usługi `ExperimentService`:

```html
@inject('experiments', 'App\Services\ExperimentService')

@if($experiments->isVariant('kolor-przycisku', 'B'))
    <button class="bg-red-600">Kup teraz (Wariant B)</button>
@else
    <button class="bg-sage-600">Kup teraz (Kontrola A)</button>
@endif
```

## 4. Analityka i Wyniki

Obecna wersja modułu zbiera dane o:
*   **Visits Count:** Ile razy dany wariant został wyświetlony unikalnym użytkownikom.
*   **Conversions:** (W opracowaniu) Integracja z modułem zamówień w celu automatycznego zliczania zakupów per wariant.

Zaleca się również przesyłanie informacji o wariancie do **Google Analytics 4** jako wymiar niestandardowy, aby móc analizować wpływ testów na współczynnik odrzuceń i czas sesji.

## 5. Dobre praktyki

1.  **Testuj jedną zmienną:** Nie zmieniaj jednocześnie ceny i koloru przycisku w jednym teście.
2.  **Czas trwania:** Test powinien trwać co najmniej 7-14 dni, aby wykluczyć wpływ wahań weekendowych.
3.  **Wielkość próby:** Nie wyciągaj wniosków po 10 wizytach – poczekaj na istotność statystyczną.
