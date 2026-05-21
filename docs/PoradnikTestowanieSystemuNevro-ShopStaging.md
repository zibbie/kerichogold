# Poradnik: Testowanie Systemu Nevro-Shop (Staging)

Ten poradnik zawiera komplet danych i instrukcji, które pozwolą na przetestowanie wszystkich systemów płatności i dostaw w bezpiecznym środowisku testowym (Staging).

---

## 1. Dostęp do środowiska testowego
*   **Adres sklepu (Staging):** [https://shop.nevro-wm.pl/](https://shop.nevro-wm.pl/)
*   **Dostęp i Zabezpieczenia:**
    *   **Brak blokady hasłem (Basic Auth):** Witryna jest publicznie dostępna bez uciążliwego hasła Basic Auth. Było to niezbędne, aby roboty **Google Merchant Center** mogły bez przeszkód weryfikować ceny i dostępność produktów do celów reklamowych (PMax/Ads).
    *   **Zabezpieczenie przed Google Search (SEO):** Aby zapobiec indeksowaniu wersji testowej w wynikach organicznych i uniknąć duplikowania treści (*duplicate content*), wdrożyliśmy dynamiczny tag `<meta name="robots" content="noindex, nofollow">` serwowany wyłącznie dla domeny `shop.nevro-wm.pl`.
*   **Panel Administratora (Filament v3):**
    *   **Adres logowania:** [https://shop.nevro-wm.pl/admin/login](https://shop.nevro-wm.pl/admin/login)
    *   **Użytkownik:** `zbyszeklupikasza@gmail.com`
    *   **Odzyskiwanie hasła:** Na stronie logowania dodaliśmy w pełni spolszczoną funkcję **"Zapomniałeś hasła?"**. Umożliwia ona bezpieczne zresetowanie hasła za pomocą linku wysyłanego na Twój adres e-mail (wszystkie maile i komunikaty są w 100% w języku polskim!).

---

## 2. Płatności Przelewy24 (Sandbox) — [AKTYWNY]
Wszystkie płatności na Stagingu zostały pomyślnie przełączone w tryb **Sandbox** (serwer testowy). Skonfigurowaliśmy Twoje dedykowane klucze testowe (Sandbox CRC: `9d97111aabdddd06` oraz klucz API `65724a12`). Dzięki temu podczas testów **nie są pobierane żadne realne pieniądze**, a system jest w 100% bezpieczny!

Możesz natychmiast przejść cały proces płatności w koszyku za pomocą poniższych scenariuszy testowych:

### A. Testowy BLIK
Podczas wyboru płatności BLIK, po przekierowaniu na stronę Przelewy24, użyj jednego z kodów:
*   **Kod BLIK:** `777123` (zawsze przechodzi pomyślnie)
*   **Kod BLIK (Błąd):** `777000` (symuluje odrzucenie płatności)

### B. Testowa Karta Płatnicza
Jeśli wybierzesz płatność kartą w panelu Przelewy24, użyj poniższych danych:
*   **Numer karty:** `4000 0000 0000 0002`
*   **Data ważności:** Dowolna data w przyszłości (np. `12 / 2026`)
*   **Kod CVV:** `123`

### C. Szybkie Przelewy Online
Po wybraniu dowolnego banku na liście testowej, zostaniesz przekierowany na stronę symulacji banku. Wystarczy kliknąć przycisk **"Potwierdź płatność / Success"**.

---

## 3. Płatności Odroczone PayPo (Tryb Testowy)
PayPo na stagingu pozwala na przejście całego procesu bez weryfikacji kredytowej.

*   **Dane klienta:** Możesz użyć swoich realnych danych lub testowych.
*   **Weryfikacja SMS:** W trybie testowym PayPo zazwyczaj akceptuje dowolny kod SMS (np. `1111`) lub automatycznie zatwierdza wniosek.
*   **UWAGA:** System PayPo na stagingu jest skonfigurowany tak, aby akceptować wnioski dla kwot od 10 PLN do 1000 PLN.

---

## 4. Dostawy InPost (Paczkomaty)
Integracja InPost na Stagingu jest w trybie symulacji.

*   **Wybór Paczkomatu:** Możesz wybrać dowolny paczkomat na mapie (np. `KRA01M`).
*   **Numer telefonu:** Dla testów InPost najlepiej podawać numer w formacie `500 000 000`.
*   **Etykiety:** System wygeneruje "testową etykietę" w BaseLinkerze (jeśli jest podłączony do stagingu), która nie zostanie wysłana do kuriera.

---

## 5. Scenariusze Testowe (Checklista)
Zalecam wykonanie poniższych 4 testów, aby mieć pewność, że wszystko działa:

1.  **Standardowy Zakup (BLIK):**
    *   Wybierz produkt -> Koszyk -> Dane (system sam wstawi myślnik w kodzie!) -> BLIK -> Kod `777123`.
    *   *Sprawdź: Czy przyszedł e-mail potwierdzający do klienta i do biura?*

2.  **Zakup z Fakturą (Przelew):**
    *   Zaznacz "Chcę otrzymać fakturę VAT" -> Wpisz NIP (10 cyfr).
    *   *Sprawdź: Czy dane NIP są widoczne w panelu zamówienia?*

3.  **Wybór Paczkomatu:**
    *   Wybierz dostawę InPost -> Kliknij "Wybierz na mapie" -> Wybierz punkt.
    *   *Sprawdź: Czy nazwa punktu (np. WAW123) zapisała się w zamówieniu?*

4.  **Pobranie (COD):**
    *   Wybierz "Za pobraniem".
    *   *Sprawdź: Czy kwota zamówienia powiększyła się o opłatę pobraniową (5 zł)?*

---

## 6. Pomoc techniczna
Jeśli podczas testów zobaczysz błąd:
1. Zrób zrzut ekranu.
2. Zapisz numer zamówienia (jeśli został nadany).
3. Prześlij informację do dewelopera.

---
*Dokument przygotowany dla: Nevro-Shop v2 / 2026*
