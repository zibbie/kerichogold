# Integracja AI i Agentic Commerce: Dokumentacja Techniczna

## 1. Wstęp i Filozofia "AI-Native"
Nevro-Shop v2 został zaprojektowany w paradygmacie **AI-Native**, co oznacza, że jego struktura jest optymalizowana nie tylko pod kątem ludzi, ale także agentów AI (LLM), botów zakupowych i systemów typu Search Generative Experience (SGE). Sklep aktywnie "rozmawia" z modelami takimi jak GPT-4, Claude czy Gemini, ułatwiając im indeksowanie i rozumienie oferty.

---

## 2. Protokół WebMCP (Web Model Context Protocol)
Jest to kluczowa innowacja w Nevro-Shop v2, pozwalająca na automatyczne odkrywanie zasobów sklepu przez agentów AI.

### Mechanizm Discovery
W sekcji `<head>` każdego dokumentu HTML zaimplementowano tagi discovery:
```html
<link rel="mcp" type="application/json" href="https://nevro-wm.pl/api/mcp">
<link rel="ai-discovery" type="text/plain" href="https://nevro-wm.pl/ai.txt">
```

### Endpoint API (`/api/mcp`)
Udostępnia ustrukturyzowany kontekst dla modeli AI, zawierający:
- **Capabilities:** Listę dostępnych akcji (wyszukiwanie, sprawdzanie stanów, checkout).
- **Product Schema:** Definicje pól produktów zgodne ze standardem Schema.org.
- **Constraints:** Zasady współpracy z botami.

---

## 3. Optymalizacja Semantyczna (LLM-Friendly)
Modele AI "widzą" stronę inaczej niż ludzie. Nevro-Shop v2 stosuje rygorystyczne standardy semantyki:

- **JSON-LD (Rich Snippets):** Każdy produkt posiada kompletny graf danych `Product` (cena, dostępność, marka, stan magazynowy, GTIN).
- **Semantic HTML5:** Użycie tagów `<article>`, `<section>`, `<nav>` oraz odpowiedniej hierarchii nagłówków `<h1>-<h3>` ułatwia parsowanie treści (Web Scraping/Parsing) przez algorytmy AI.
- **Microdata:** Dodatkowe atrybuty `itemprop` tam, gdzie JSON-LD jest niewystarczający.

---

## 4. Widoczność dla Agentów (AI Visibility)

### Plik `ai.txt`
Odpowiednik `robots.txt` dedykowany dla systemów AI. Określa on:
- Zakres dopuszczalnego scrapingu.
- Preferowane formaty danych.
- Kontakt techniczny dla twórców agentów.

### Sanitization dla NLP
Wszystkie opisy produktów w feedach (GMC) i API przechodzą przez proces sanityzacji, który usuwa zbędny szum HTML, pozostawiając czystą strukturę semantyczną (`<b>`, `<ul>`, `<li>`), co drastycznie zwiększa trafność wyników w modelach NLP.

---

## 5. Zastosowania Praktyczne (Use Cases)

### A. AI Shopping Assistants
Agenci AI (np. wtyczki do ChatGPT) mogą natychmiastowo uzyskać listę produktów spełniających konkretne kryteria (np. "znajdź mi zbiornik IBC z kranem 3/4 cala dostępny od ręki"), korzystając z endpointów MCP.

### B. Dynamiczne Rekomendacje LLM
Dzięki czystym danym, systemy zewnętrzne mogą generować spersonalizowane opisy i rekomendacje produktów w oparciu o naturalny język użytkownika.

### C. Voice Commerce
Struktura danych jest zoptymalizowana pod kątem czytników ekranowych i asystentów głosowych (Alexa, Google Assistant), co przygotowuje sklep na erę zakupów głosowych.

---

## 7. Przykłady Zapytań API (AI Queries)

Poniżej przedstawiono przykładowe zapytania, jakie agenci AI mogą kierować do systemu w celu ekstrakcji danych.

### A. Pobieranie kontekstu WebMCP
Agent AI wykonuje zapytanie GET, aby zrozumieć strukturę sklepu:
```http
GET /api/mcp HTTP/1.1
Accept: application/json
```
**Przykładowa odpowiedź:**
```json
{
  "version": "1.0",
  "name": "Nevro-Shop AI Gateway",
  "tools": [
    {
      "name": "search_products",
      "endpoint": "/api/search",
      "params": ["q", "limit", "filter"]
    },
    {
      "name": "get_product_schema",
      "endpoint": "/product/{slug}",
      "output": "application/ld+json"
    }
  ]
}
```

### B. Zapytanie do wyszukiwarki (Search Intent)
Agent mapuje prośbę użytkownika ("szukam kanistra 20L z atestem") na zapytanie Meilisearch:
```http
GET /api/search?q=kanister+20l+atest&limit=3 HTTP/1.1
```
Dzięki integracji z Meilisearch, system zwróci trafne wyniki nawet przy braku dosłownego dopasowania słów kluczowych.

### C. Ekstrakcja danych strukturalnych (JSON-LD)
Podczas parsowania strony produktu, agent LLM błyskawicznie "widzi" parametry techniczne:
```json
{
  "@context": "https://schema.org/",
  "@type": "Product",
  "name": "Kanister 20L HDPE z UN",
  "sku": "KAN20-UN",
  "offers": {
    "@type": "Offer",
    "price": "29.99",
    "priceCurrency": "PLN",
    "availability": "https://schema.org/InStock"
  }
}
```

---

## 8. Roadmapa Rozwoju AI
- **Vector Search (RAG):** Planowana integracja Meilisearch z embeddingami (wektorami), co pozwoli na wyszukiwanie semantyczne ("potrzebuję czegoś do zbierania deszczówki na małą działkę").
- **Agentic Checkout:** Rozszerzenie protokołu WebMCP o bezpieczne przekazywanie sesji zakupu do agentów AI.

---
*Dokumentacja przygotowana przez Antigravity dla Nevro-Shop v2.*
