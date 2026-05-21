<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;

class CategoryDetail extends Component
{
    use WithPagination;

    public $category;

    protected $paginationTheme = 'tailwind';

    public function mount($slug)
    {
        $this->category = Category::where('slug', $slug)->where('status', true)->firstOrFail();
    }

    #[\Livewire\Attributes\Computed]
    public function products()
    {
        $categoryIds = Category::where('id', $this->category->id)
            ->orWhere('parent_id', $this->category->id)
            ->pluck('id');

        return Product::whereIn('category_id', $categoryIds)
            ->where('status', true)
            ->paginate(12);
    }

    public function render()
    {
        $categories = Category::where('status', true)
            ->whereNull('parent_id')
            ->orderBy('position', 'asc')
            ->get();

        return view('livewire.category-detail', [
            'categories' => $categories
        ])
        ->layout('layouts.app')
        ->title($this->category->meta_title ?: $this->category->name . ' | Nevro-Shop');
    }
}
