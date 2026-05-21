<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Setting;

class Home extends Component
{
    public function addToCart($productId, \App\Services\CartService $cartService)
    {
        $product = \App\Models\Product::find($productId);
        if (!$product) return;

        $cartService->addProduct($product->id, 1);
        
        $this->dispatch('cart-updated');
        $this->dispatch('product-added');
        
        $this->dispatch('gtag-event', [
            'event' => 'add_to_cart',
            'data' => [
                'currency' => 'PLN',
                'value' => (float) $product->price,
                'items' => [
                    [
                        'item_id' => (string) $product->id,
                        'item_name' => $product->name,
                        'price' => (float) $product->price,
                        'quantity' => 1,
                    ]
                ]
            ]
        ]);
        
        session()->flash('message', $product->name . ' dodano do koszyka!');
    }

    public function render()
    {
        $settings = [
            'hero_is_visible' => Setting::get('hero_is_visible', true),
            'hero_title' => Setting::get('hero_title', 'Zadbaj o swój ogród z Nevro-Shop'),
            'hero_description' => Setting::get('hero_description', 'Odkryj naszą ofertę zbiorników IBC i akcesoriów ogrodowych.'),
            'hero_button_text' => Setting::get('hero_button_text', 'Odkryj ofertę'),
            'hero_button_link' => Setting::get('hero_button_link', '/sklep'),
            'hero_image_url' => Setting::get('hero_image_url'),
            'hero_title_color' => Setting::get('hero_title_color', '#ffffff'),
            'hero_description_color' => Setting::get('hero_description_color', '#ffffff'),
            'hero_button_bg_color' => Setting::get('hero_button_bg_color', '#ffffff'),
            'hero_button_text_color' => Setting::get('hero_button_text_color', '#566e5a'),
            'hero_text_bg' => Setting::get('hero_text_bg', ['color' => '#000000', 'opacity' => 0]),
            'cta_home_is_visible' => Setting::get('cta_home_is_visible', true),
            'cta_home_title' => Setting::get('cta_home_title', 'Potrzebujesz pomocy?'),
            'cta_home_description' => Setting::get('cta_home_description', 'Nasi eksperci doradzą Ci w wyborze odpowiedniego osprzętu.'),
            'cta_home_button_text' => Setting::get('cta_home_button_text', 'Skontaktuj się'),
            'cta_home_button_link' => Setting::get('cta_home_button_link', '/contact'),
            'cta_home_bg_color' => Setting::get('cta_home_bg_color', '#566e5a'),
            'cta_home_text_color' => Setting::get('cta_home_text_color', '#ffffff'),
        ];

        return view('livewire.home', [
            'hero' => [
                'visible' => false,
                'title' => $settings['hero_title'] ?? 'Zadbaj o swój ogród z Nevro-Shop',
                'description' => $settings['hero_description'] ?? 'Odkryj naszą ofertę zbiorników IBC i akcesoriów ogrodowych.',
                'button_text' => $settings['hero_button_text'] ?? 'Odkryj ofertę',
                'button_link' => $settings['hero_button_link'] ?? '/sklep',
                'image' => $settings['hero_image_url'] ?? 'https://lh3.googleusercontent.com/aida-public/AB6AXuDgWv9KS-hPHuh5egM4qGzxabvc2h-ZWLigFvYfrWcNrK8XDsnIbSOWz_eO4lt-b_Z5s3lve5lvXFvTbC6qvOhDEnG3yrIPRFW6c5z7vT7Uw56zntVR55YfQNcQIIJOSjSD9OaWf_ugwHkMdVNQX4-wMVbL0s5MYa0V66dTxN2NuqnbwciyGL7CUSm900B6uhFjPb6wMo1vJxTfGvJDwU5kp-8c9Y05RnrycXz65ECe_rupN0xUvGe9S8lDrpOxyt7oyU181v03iH06',
                'title_color' => $settings['hero_title_color'] ?? '#ffffff',
                'description_color' => $settings['hero_description_color'] ?? '#ffffff',
                'button_bg_color' => $settings['hero_button_bg_color'] ?? '#ffffff',
                'button_text_color' => $settings['hero_button_text_color'] ?? '#566e5a',
                'text_bg' => $settings['hero_text_bg'],
            ],
            'cta' => [
                'visible' => (bool) ($settings['cta_home_is_visible'] ?? true),
                'title' => $settings['cta_home_title'],
                'description' => $settings['cta_home_description'],
                'button_text' => $settings['cta_home_button_text'],
                'button_link' => $settings['cta_home_button_link'],
                'bg_color' => $settings['cta_home_bg_color'],
                'text_color' => $settings['cta_home_text_color'],
            ],
            'categories' => \App\Models\Category::where('status', true)->whereNull('parent_id')->orderBy('position', 'asc')->take(8)->get(),
            'hits' => \App\Models\Product::where('status', true)->where('is_hit', true)->take(4)->get(),
            'products' => \App\Models\Product::where('status', true)
                ->where('is_hit', true)
                ->take(10)
                ->get()
        ])->layout('layouts.app');
    }
}