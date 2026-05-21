# Poradnik Wdrożeniowy: Kericho Gold & Antigravity

Witaj w świecie Kericho Gold! Ten dokument przeprowadzi Cię przez proces instalacji i uruchomienia Twojego nowego sklepu w Internecie. Zrobimy to w sposób nowoczesny, wykorzystując moc Twojego osobistego agenta AI — **Antigravity**.

---

## KROK 1: Instalacja programu Antigravity

Antigravity to Twój agent kodujący, który wykona za Ciebie 90% pracy technicznej.

### Na systemie MacOS:
1. Pobierz instalator `.dmg` ze strony [antigravity.ai](https://antigravity.ai) (lub oficjalnego źródła zakupu).
2. Otwórz plik i przeciągnij ikonę Antigravity do folderu **Applications**.
3. Uruchom program. Przy pierwszym uruchomieniu system może zapytać o uprawnienia (kliknij "Otwórz").

### Na systemie Windows:
1. Pobierz instalator `.exe`.
2. Uruchom go i postępuj zgodnie z instrukcjami na ekranie.
3. Antigravity automatycznie zainstaluje niezbędne komponenty (np. Git, jeśli ich nie masz).
4. Uruchom program z Menu Start.

---

## KROK 2: Przygotowanie plików sklepu

1. Rozpakuj archiwum `.zip` z kodem sklepu, które otrzymałeś po zakupie.
2. Uruchom program **Antigravity**.
3. Kliknij przycisk **"Open Folder"** (lub przeciągnij folder z kodem bezpośrednio do okna programu).
4. **Gratulacje!** Twój agent AI widzi teraz cały kod Twojego sklepu i jest gotowy do akcji.

---

## KROK 3: Wybór Hostingu (VPS)

Dla Kericho Gold (ze względu na silnik Meilisearch i Docker) **niezbędny jest serwer typu VPS** (np. DigitalOcean, Linode, Hetzner lub polskie OVH/cyber_Folks).
*   **Minimalne wymagania:** 2 vCPU, 4GB RAM, 40GB SSD.
*   **System operacyjny:** Ubuntu 22.04 LTS lub nowszy.

---

## KROK 4: Wielkie Wdrożenie (Magiczne Prompty)

Teraz zaczyna się magia. Nie musisz pisać kodu ani znać komend Linuxa. Po prostu skopiuj i wklej poniższe prompty do czatu w programie Antigravity.

### Prompt 1: Przygotowanie Serwera
Wpisz to jako pierwszą wiadomość po otwarciu folderu:
> "Cześć! Mam gotowy serwer VPS o adresie IP [WPISZ_TU_IP_SWOJEGO_SERWERA]. Chcę wdrożyć ten sklep Kericho Gold. Zacznij od sprawdzenia moich plików konfiguracyjnych i pomóż mi połączyć się z serwerem przez SSH, a następnie zainstaluj tam Dockera i wszystkie niezbędne zależności."

### Prompt 2: Konfiguracja Domeny i SSL
Gdy agent skończy pierwszy krok, wpisz:
> "Teraz skonfiguruj plik .env dla domeny [TWOJA_DOMENA.PL]. Ustaw poprawne adresy URL dla aplikacji, Meilisearch oraz GTM Server-Side. Następnie skonfiguruj Nginx (lub Traefik) na serwerze, aby sklep był dostępny pod moją domeną z certyfikatem SSL (Let's Encrypt)."

### Prompt 3: Uruchomienie Systemu
Gdy serwer będzie gotowy, wpisz:
> "Uruchom teraz wszystkie kontenery przez Docker Compose. Pamiętaj o migracji bazy danych, zasileniu jej startowymi danymi (seeders) oraz o pełnym zaindeksowaniu produktów w Meilisearch komendą php artisan scout:import."

### Prompt 4: Weryfikacja i Testy
Na koniec poproś o test:
> "Sprawdź teraz, czy wszystko działa poprawnie. Przetestuj działanie wyszukiwarki, dodawanie do koszyka oraz czy strona /offline i manifest PWA są widoczne. Wygeneruj raport z linkami do najważniejszych paneli (Admin, Meilisearch Dashboard)."

---

## Co jeśli napotkasz problemy?

Antigravity to inteligentny agent. Jeśli serwer zwróci błąd, po prostu skopiuj treść błędu do okna Antigravity i napisz:
> "Wystąpił błąd podczas [KROK]. Napraw to i spróbuj ponownie."

Agent sam zdiagnozuje problem, poprawi konfigurację i dokończy instalację.

---

## Podsumowanie Funkcji, które Właśnie Wdrożyłeś:
*   **Błyskawiczne wyszukiwanie** (Meilisearch).
*   **Płatności Odroczone PayPo** (Zintegrowane).
*   **Aplikacja Mobilna PWA** (Twój sklep na ekranie telefonu klienta).
*   **Analityka Marży** (Widzisz zysk na każdym zamówieniu).
*   **Agentic AI Support** (Twój sklep "rozmawia" z botami AI).

---
*Kericho Gold — Twój sukces zaczyna się tutaj.*
