# Wzorce Stabilizacji Środowiska Docker & Kontenerów (Meilisearch, Vite, VM Lock)

## 1. Wymóg Walidacji Klucza Meilisearch (Production Mode)

### Problem
Gdy obraz `getmeili/meilisearch` uruchomiony jest ze zmienną `MEILI_ENV=production`, kontener natychmiast wyłącza się ze statusem błędu, jeśli zmienna `MEILI_MASTER_KEY` (`MEILISEARCH_KEY`) jest krótsza niż 16 bajtów lub używa słabego klucza fabrycznego (np. `masterKey`).

### Rozwiązanie (Pattern)
- Zawsze generuj bezpieczny klucz o długości co najmniej 16 bajtów w pliku `.env`:
  ```env
  MEILISEARCH_KEY=masterKeySecure12345
  ```
- Upewnij się, że klucz nie zawiera znaków specjalnych powodujących błędne parsowanie przez powłokę kontenera, jeśli nie są one w cudzysłowie.

---

## 2. Uprawnienia Roota przy Budowaniu Assetów Node/Vite w Kontenerze

### Problem
Uruchomienie `npm install` oraz `npm run build` wewnątrz kontenera aplikacyjnego (np. `kericho-app`) często kończy się błędem `EACCES: permission denied` przy próbie zapisu do katalogów pamięci podręcznej (np. `/var/www/.npm` lub `node_modules`). Dzieje się tak, ponieważ kontener domyślnie działa jako użytkownik o niższych uprawnieniach (`www-data`), podczas gdy foldery systemowe lub wolumeny mogą należeć do `root` (współdzielenie plików na hostach macOS/Linux).

### Rozwiązanie (Pattern)
Wymuś uruchomienie procesu budowania z flagą użytkownika `-u root` w `docker compose exec`:
```bash
# Instalacja zależności z uprawnieniami roota
docker compose exec -u root app npm install

# Kompilacja assetów (Vite)
docker compose exec -u root app npm run build
```
Zapewnia to poprawne wygenerowanie pliku `public/build/manifest.json` bez kolizji uprawnień systemowych.

---

## 3. Rozwiązywanie Blokad Maszyny Wirtualnej OrbStack (vmgr lock)

### Problem
Komunikaty o błędzie typu:
```
level=fatal msg="vmgr is already running (wait lock): context deadline exceeded"
```
wskazują na zakleszczenie (deadlock) w procesie demona zarządzającego maszyną wirtualną OrbStack. Zazwyczaj dzieje się tak w przypadku niepełnego zamknięcia usług lub kolizji wątków przy nagłym zamknięciu hosta.

### Rozwiązanie (Pattern)
1. Zamknij wszystkie procesy Docker na hoście:
   ```bash
   docker compose down --remove-orphans
   ```
2. Jeśli demon GUI nadal wisi, zrestartuj usługi OrbStack za pomocą pętli kontrolnej CLI lub interfejsu GUI macOS:
   - Zamknij aplikację OrbStack w systemie macOS.
   - Uruchom ponownie silnik poleceniem:
     ```bash
     orb restart
     ```
3. W przypadku automatycznego wykrycia błędu przez system OrbStack (jak w logach `gui.log`), pozwól demonowi przejść przez pełną procedurę ponownego uruchomienia i zweryfikuj status maszyn komendą `orb list`.
