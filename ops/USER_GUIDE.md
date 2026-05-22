# Instrukcja Pracy z Orchestratorem i Agentami (Kericho Gold - Mesh 2.0)

## 1. Topologia Systemu
- **Orchestrator (VPS Main)**: `85.215.169.120`
- **Agenci Lokalni**: Mesh 2.0 na `kerichogold`.

## 2. Kluczowe Wzorce Inżynieryjne (Maj 2026)

### A. GMC Image Sanitization Standard
Zawsze używaj wzorca `p[ID].[ext]` dla obrazków w feedzie. Zapobiega to błędom crawlowania spowodowanym znakami specjalnymi. Skrypt naprawczy znajduje się w `App\Http\Controllers\GoogleFeedController`.

### B. Dynamiczne Ustawienia (Settings Model)
Zamiast hardkodować parametry (prowizje, emaile, włączenie PayPo), używaj modelu `Setting`. Panel zarządzania: `App\Filament\Pages\GeneralSettings`.

### C. Narzędzia Konwersji (A/B Testing)
Moduł `ConversionTools` pozwala na dynamiczną zmianę UI koszyka (przyciski, paski dostawy) bez edycji kodu. Wszystkie komponenty Livewire powinny reagować na te ustawienia.

## 3. Procedura Końcowa Sesji
Każda sesja musi zakończyć się:
1.  **GMC Audit**: Weryfikacja liczby zatwierdzonych produktów.
2.  **Backup**: Pobranie zrzutu bazy i synchronizacja plików do `backups/`.
3.  **Docs Update**: Synchronizacja stanu technicznego z plikami w `docs/`.

## 4. Plan Ekspansji (Next Steps)
- **BaseLinker API**: Integracja pełnego dwukierunkowego syncu zamówień (Skills: `baselinker_sync`).
- **PMax Optimization**: Skalowanie kampanii dla kategorii "Osprzęt do Mauzerów" (trend +91%).
- **Mobile UX**: Dalsza optymalizacja Core Web Vitals (LCP/INP).
