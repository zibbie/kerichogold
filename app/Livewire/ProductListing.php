<?php

namespace App\Livewire;

use App\Models\Product;
use App\Models\Category;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Url;

class ProductListing extends Component
{
    use WithPagination;
    
    #[Url]
    public $search = '';

    public $mode = 'all'; // 'all' or 'latest'
    public $title = 'Sklep';

    protected $paginationTheme = 'tailwind';

    public function mount($mode = 'all')
    {
        $this->mode = $mode;
        
        if ($this->mode === 'latest') {
            $this->title = 'Nowości';
        } else {
            $this->title = 'Wszystkie produkty';
        }

        if ($this->search) {
            $this->fireSearchEvent();
        }
    }

    public function updatedSearch()
    {
        $this->resetPage();
        if ($this->search) {
            $this->fireSearchEvent();
        }
    }

    private function fireSearchEvent()
    {
        $this->dispatch('gtag-event', [
            'event' => 'search',
            'data' => [
                'search_term' => $this->search
            ]
        ]);
    }

    #[\Livewire\Attributes\Computed]
    public function products()
    {
        $query = Product::where('status', true);

        if ($this->search) {
            $normalizedSearch = mb_strtolower($this->search);
            
            // Map common words to symbols/numbers
            $mappings = [
                'dziesięć' => '10', 'dziesiec' => '10',
                'pięć' => '5', 'piec' => '5',
                'dwadzieścia' => '20', 'dwadziescia' => '20',
                'trzydzieści' => '30', 'trzydziesci' => '30',
                'tysiąc' => '1000', 'tysiac' => '1000',
                'litrów' => 'l', 'litrow' => 'l', 'litry' => 'l', 'litra' => 'l'
            ];

            foreach ($mappings as $word => $replacement) {
                $normalizedSearch = preg_replace('/\b' . $word . '\b/u', $replacement, $normalizedSearch);
            }

            $words = explode(' ', $normalizedSearch);
            $query->where(function($q) use ($words) {
                foreach ($words as $word) {
                    $word = trim($word);
                    if (!$word) continue;
                    $q->where(function($sub) use ($word) {
                        $sub->where('name', 'ILIKE', '%' . $word . '%')
                            ->orWhere('description', 'ILIKE', '%' . $word . '%');
                        
                        if (mb_strlen($word) >= 3) {
                            $sub->orWhereRaw('word_similarity(?, lower(name)) > 0.4', [$word])
                                ->orWhereRaw('word_similarity(?, lower(description)) > 0.4', [$word]);
                        }

                        $sub->orWhereHas('category', function($catQuery) use ($word) {
                            $catQuery->where('name', 'ILIKE', '%' . $word . '%');
                            if (mb_strlen($word) >= 3) {
                                $catQuery->orWhereRaw('word_similarity(?, lower(name)) > 0.4', [$word]);
                            }
                        });
                    });
                }
            });

            // Add ranking for relevance
            $query->orderByRaw('CASE 
                WHEN lower(name) LIKE ? THEN 1 
                WHEN lower(name) LIKE ? THEN 2
                ELSE 3 END', [
                    $normalizedSearch, // Exact match
                    $normalizedSearch . '%' // Starts with
                ]);
            
            // Then order by similarity score if search is long enough
            if (mb_strlen($normalizedSearch) >= 3) {
                $query->orderByRaw('word_similarity(?, lower(name)) DESC', [$normalizedSearch]);
            }

            $this->title = 'Wyniki wyszukiwania: ' . $this->search;
        }

        if ($this->mode === 'latest') {
            $query->latest();
        } elseif (!$this->search) {
            $query->orderBy('name', 'asc');
        }

        return $query->paginate(15);
    }

    public function render()
    {
        $categories = Category::where('status', true)
            ->whereNull('parent_id')
            ->orderBy('position', 'asc')
            ->get();

        return view('livewire.product-listing', [
            'categories' => $categories
        ])->layout('layouts.app');
    }
}
