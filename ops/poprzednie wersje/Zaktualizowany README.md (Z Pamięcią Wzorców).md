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
