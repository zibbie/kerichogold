# Poradnik SEO: Budowa Modułu Graph-First Architecture

Ten dokument opisuje standard wdrożenia modułu SEO zorientowanego na nowoczesne wyszukiwarki (Google, Bing) oraz systemy AI (GEO - Generative Engine Optimization / AEO - Answer Engine Optimization).

## 1. Filozofia: Graph-First & BLUF

Tradycyjne SEO (meta tagi) to tylko fundament. Nowoczesny moduł musi opierać się na dwóch filarach:
1.  **Graph-First**: Budowanie grafu wiedzy o witrynie za pomocą ustrukturyzowanych danych JSON-LD.
2.  **BLUF (Bottom Line Up Front)**: Podawanie najważniejszych informacji technicznych na samym początku treści w formacie łatwym do sparsowania przez LLM.

---

## 2. Implementacja Usługi SEO (`SeoService`)

Centralny punkt logiki SEO. Usługa powinna generować tablice zgodne ze standardem [Schema.org](https://schema.org).

### Plik: `app/Services/SeoService.php`

```php
namespace App\Services;

use App\Models\Product;
use App\Models\Category;

class SeoService
{
    /**
     * Generuje JSON-LD dla Organizacji (globalne)
     */
    public function organizationSchema(): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            '@id' => url('/') . '#organization',
            'name' => config('app.name'),
            'url' => url('/'),
            'logo' => asset('storage/logo.png'),
            'contactPoint' => [
                '@type' => 'ContactPoint',
                'email' => 'kontakt@twojadomena.pl',
                'contactType' => 'customer service'
            ]
        ];
    }

    /**
     * Generuje JSON-LD dla Produktu
     */
    public function productSchema(Product $product): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'Product',
            'name' => $product->name,
            'description' => strip_tags($product->description),
            'sku' => $product->sku,
            'offers' => [
                '@type' => 'Offer',
                'price' => $product->price,
                'priceCurrency' => 'PLN',
                'availability' => $product->quantity > 0 ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock',
            ]
        ];
    }

    /**
     * Renderowanie tagu <script>
     */
    public function renderJsonLd(array $schema): string
    {
        return '<script type="application/ld+json">' . 
               json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) . 
               '</script>';
    }
}
```

---

## 3. Optymalizacja Treści: Metoda BLUF

Boty AI (np. OpenAI-SearchBot, Perplexity) szukają konkretnych danych technicznych. Zamiast ukrywać je w długich opisach, użyj struktury `<dl>` (Description List) na początku sekcji opisu.

### Przykład w widoku Blade:

```html
<!-- Sekcja BLUF -->
<div class="product-specs-top">
    <h3 class="sr-only">Kluczowa specyfikacja</h3>
    <dl class="grid grid-cols-2 gap-4 border-b pb-4 mb-4">
        <dt class="font-bold text-gray-700">Producent:</dt>
        <dd>{{ $product->brand }}</dd>

        <dt class="font-bold text-gray-700">Model:</dt>
        <dd>{{ $product->model_number }}</dd>

        <dt class="font-bold text-gray-700">Materiał:</dt>
        <dd>{{ $product->material }}</dd>
    </dl>
</div>

<!-- Dalsza część opisu -->
<div class="product-description">
    {!! $product->description !!}
</div>
```

**Dlaczego to działa?** Tagi `<dt>` i `<dd>` są natywnie rozumiane przez parsery jako pary klucz-wartość, co zwiększa szansę na pojawienie się w "Featured Snippets" i odpowiedziach AI.

---

---

## 6. AI-Native Readiness: WebMCP & ai.txt

W dobie agentów AI (Claude Code, GPT-4o), samo indeksowanie treści nie wystarcza. Sklep musi udostępniać interfejsy dla systemów autonomicznych.

### WebMCP (Web Model Context Protocol)
Wdrożyliśmy endpoint `/api/mcp`, który pozwala agentom AI na interakcję z katalogiem produktów w formacie JSON-RPC.
- **Tool-based discovery**: Agenci mogą wywoływać "narzędzia" takie jak `search_products` czy `get_product`.
- **Zaleta**: Twoje produkty pojawiają się w odpowiedziach asystentów AI z kompletnymi danymi technicznymi i linkami do zakupu.

### Manifest `ai.txt`
Stworzyliśmy plik `/public/ai.txt`, który jest standardem komunikacji z modelami LLM:
- Definiuje zakres dopuszczalnego skanowania.
- Wskazuje na endpoint WebMCP.
- Podaje kontekst rynkowy (IBC Tanks, Poland, PLN), co zapobiega halucynacjom modeli na temat Twojej oferty.

---

## 7. Performance jako Czynnik Rankingowy (SXP)

Szybkość to nie tylko wygoda, to Search Experience (SXP).
- **Meilisearch**: Zastąpienie wolnego `LIKE` w SQL silnikiem Meilisearch drastycznie obniża Time to First Byte (TTFB) przy wyszukiwaniu.
- **PWA & Caching**: Service Worker cache'uje assety, co sprawia, że powtórne wizyty są niemal natychmiastowe (LCP < 1s).
- **GTM Server-Side**: Przeniesienie analityki na serwer odciąża procesor przeglądarki klienta, co poprawia wyniki w Google PageSpeed Insights.

---

## 8. Optymalizacja pod Płatności Odroczone (BNPL SEO)

Frazy typu "kup teraz zapłać później" mają ogromny potencjał wyszukiwania.
- Dodanie widgetów PayPo z odpowiednimi atrybutami `alt` i `title` pozwala na pozycjonowanie się na zapytania o zakupy na raty/odroczone w Twojej branży.

---

## Checklista SEO 2026:
1. [ ] Zweryfikuj dostępność `/ai.txt` i poprawność tagów `<link rel="mcp">`.
2. [ ] Monitoruj w panelu Filament, które boty AI (GPTBot, ClaudeBot) najczęściej skanują Twój katalog.
3. [ ] Używaj testów A/B do sprawdzania, które warianty tekstów przycisków generują wyższy CTR z wyników organicznych.
4. [ ] Regularnie sprawdzaj indeks Meilisearch (`php artisan scout:import`), aby dane dla AI były zawsze aktualne.

---

## 5. Integracja z Layoutem

W głównym pliku `app.blade.php` przygotuj miejsce na JSON-LD:

```html
<head>
    <!-- Standardowe meta tagi -->
    <title>@yield('seo_title', config('app.name'))</title>
    <meta name="description" content="@yield('seo_description')">
    
    <!-- Globalne JSON-LD -->
    @php $seo = app(\App\Services\SeoService::class); @endphp
    {!! $seo->renderJsonLd($seo->organizationSchema()) !!}
    {!! $seo->renderJsonLd($seo->webSiteSchema()) !!}

    <!-- Dynamiczne JSON-LD z widoków -->
    @stack('jsonld')
</head>
```

W widoku produktu:

```php
@push('jsonld')
    {!! $seo->renderJsonLd($seo->productSchema($product)) !!}
@endpush
```

---

## Checklista dla Agenta AI:
1. [ ] Utwórz `SeoService` i zdefiniuj schematy dla kluczowych modeli.
2. [ ] Dodaj `@stack('jsonld')` do nagłówka layoutu.
3. [ ] Zaimplementuj widoki produktów z użyciem struktury `<dl>` (BLUF).
4. [ ] Skonfiguruj middleware `TrackBotActivity` i zarejestruj go w potoku HTTP.
5. [ ] Zweryfikuj poprawność JSON-LD w [Rich Results Test](https://search.google.com/test/rich-results).
