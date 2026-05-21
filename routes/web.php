<?php

use App\Livewire\Home;
use App\Livewire\Checkout;
use App\Livewire\PaymentStatus;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\ReceiptController;

require __DIR__.'/web_inpost.php';

Route::get('/', Home::class);
Route::get('/sklep', \App\Livewire\ProductListing::class)->name('shop');
Route::get('/nowosci', \App\Livewire\ProductListing::class, ['mode' => 'latest'])->name('new-arrivals');
Route::get('/cart', \App\Livewire\CartPage::class)->name('cart');
Route::get('/checkout', Checkout::class);
Route::get('/page/{slug}', \App\Livewire\PageDetail::class)->name('page.details');
Route::get('/category/{slug}', \App\Livewire\CategoryDetail::class)->name('category.details');
Route::get('/product/{slug}', \App\Livewire\ProductDetail::class)->name('product.details');

// Legacy ShopGold URLs
Route::get('/{slug}-p-{id}.html', function($slug, $id) {
    $product = \App\Models\Product::where('old_id', $id)->first();
    if (!$product) return redirect()->route('shop', [], 301);
    
    $target = route('product.details', ['slug' => $product->slug]);
    $qs = request()->getQueryString();
    return redirect()->to($target . ($qs ? '?' . $qs : ''), 301);
})->where('id', '[0-9]+');

Route::get('/{slug}-c-{id}.html', function($slug, $id) {
    $category = \App\Models\Category::where('old_id', $id)->first();
    if (!$category) return redirect()->route('shop', [], 301);
    
    $target = route('category.details', ['slug' => $category->slug]);
    $qs = request()->getQueryString();
    return redirect()->to($target . ($qs ? '?' . $qs : ''), 301);
})->where('id', '[0-9]+');

// Support for old query params
Route::get('/product_info.php', function() {
    $id = request('products_id');
    if (!$id) return redirect()->route('shop', [], 301);
    
    $product = \App\Models\Product::where('old_id', $id)->first();
    if (!$product) return redirect()->route('shop', [], 301);
    
    $target = route('product.details', ['slug' => $product->slug]);
    $qs = request()->getQueryString();
    return redirect()->to($target . ($qs ? '?' . $qs : ''), 301);
});

Route::get('/index.php', function() {
    $cPath = request('cPath');
    if (!$cPath) return redirect('/'); // Main page is fine
    
    $parts = explode('_', $cPath);
    $id = end($parts);
    $category = \App\Models\Category::where('old_id', $id)->first();
    
    if (!$category) return redirect()->route('shop', [], 301);
    
    $target = route('category.details', ['slug' => $category->slug]);
    $qs = request()->getQueryString();
    return redirect()->to($target . ($qs ? '?' . $qs : ''), 301);
});

// Legacy CMS Page Redirects
Route::get('/contact_us.php', fn() => redirect()->to(route('page.details', 'kontakt'), 301));
Route::get('/shipping.php', fn() => redirect()->to(route('page.details', 'dostawa'), 301));
Route::get('/privacy.php', fn() => redirect()->to(route('page.details', 'polityka-prywatnosci'), 301));
Route::get('/conditions.php', fn() => redirect()->to(route('page.details', 'regulamin'), 301));
Route::get('/about_us.php', fn() => redirect()->to(route('page.details', 'o-nas'), 301));

Route::get('/payment/status/{transactionId?}', PaymentStatus::class)->name('payment.status');

Route::get('/feed/google', [\App\Http\Controllers\GoogleFeedController::class, 'index']);
Route::get('/sitemap.xml', [\App\Http\Controllers\SitemapController::class, 'index']);

Route::get('/.well-known/mcp', function () {
    return redirect('/api/mcp');
});

Route::get('/admin/orders/{order}/receipt', ReceiptController::class)
    ->name('admin.orders.receipt')
    ->middleware('auth');

Route::get('/offline', function () {
    return view('offline');
})->name('offline');

// Temporary: reset admin password (remove after use)
Route::get('/dev/reset-password', function () {
    $u = \App\Models\User::where('email', 'admin@nevro-wm.pl')->first();
    if (!$u) return 'User not found';
    $u->password = \Illuminate\Support\Facades\Hash::make('admin123');
    $u->save();
    return 'Password reset to admin123';
});
