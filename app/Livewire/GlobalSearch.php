<?php

namespace App\Livewire;

use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;

class GlobalSearch extends Component
{
    use WithPagination;

    public $query = '';

    public function updatedQuery()
    {
        $this->resetPage();
    }

    public function render()
    {
        $results = collect();

        if (strlen($this->query) >= 2) {
            $results = Product::search($this->query)
                ->where('status', true)
                ->take(8)
                ->get();
        }

        return view('livewire.global-search', [
            'results' => $results,
        ]);
    }
}
