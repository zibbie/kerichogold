# Docker Proxy & Git: Rozwiązywanie Konfliktów Bazy SQLite (Nginx Proxy Manager)

## Opis Problemu
Podczas próby wdrożenia zmian na produkcyjnym serwerze VPS poprzez komendę `git pull origin master`, Git zablokował operację z powodu błędu konfliktu nadpisywania plików (Merge Abort). 

Błąd dotyczył plików:
- `docker/proxy/data/database.sqlite`
- `docker/proxy/data/keys.json`

## Przyczyna
System na serwerze produkcyjnym (VPS) korzysta ze wbudowanego mechanizmu odwrotnego proxy (Nginx Proxy Manager). Jego kontener generuje w locie lokalną bazę SQLite (`database.sqlite`) do przechowywania certyfikatów SSL, zapytań i konfiguracji routingu.

W jednej z lokalnych aktualizacji na środowisku deweloperskim, pliki te nie zostały poprawnie wykluczone przez plik `.gitignore`, zostały zatwierdzone komendą `git add .` i trafiły do gałęzi `master`. 

Przy próbie `git pull` na VPS, Git próbował zastąpić aktywnie działającą bazę produkcyjnego serwera proxy (zawierającą faktyczne certyfikaty) pustą (lub deweloperską) bazą z repozytorium, co zablokowało wdrożenie.

## Rozwiązanie (Wzorzec)

Aby rozwiązać ten problem i odblokować CD/CI (Deployment), bez niszczenia funkcjonującego proxy:

### 1. Odpięcie plików z repozytorium, bez usuwania ich z dysku:
W środowisku lokalnym (lub na serwerze deweloperskim) wykonano polecenie, które usunęło śledzenie tych konkretnych plików z Git, ale pozostawiło je na fizycznym dysku:
```bash
git rm --cached docker/proxy/data/database.sqlite docker/proxy/data/keys.json
```

### 2. Zabezpieczenie przed powrotem błędu:
Dodano regułę ignorującą wyżej wymieniony katalog do pliku `.gitignore`:
```text
docker/proxy/data/
```

### 3. Zakończenie procesu:
Wykonano standardowy commit:
```bash
git commit -m "Fix: usunięcie plików proxy z repozytorium (SQLite tracking issue)"
git push
```
Po tej akcji komenda `git pull` na serwerze produkcyjnym VPS wykonała się poprawnie, pozostawiając lokalną produkcyjną bazę Nginx Proxy Managera w stanie nienaruszonym. 

## Zasada na Przyszłość (DevOps)
Nigdy nie należy dodawać do repozytorium katalogów związanych z wolumenami danych kontenerów Docker, w szczególności baz danych `*.sqlite`, `.json`, `.pem` z katalogów proxy lub db. Zawsze weryfikuj stan `git status` lub precyzuj `git add` zamiast ślepego `git add .`.
