<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Category;
use App\Models\Product;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\User;
use App\Services\ShippingService;
use Illuminate\Support\Facades\Auth;

echo "--- 1. Testing Slug Generation ---\n";
$suffix = time();
$catName = "Test Category $suffix";
$cat1 = Category::create(['name' => $catName]);
$cat2 = Category::create(['name' => $catName]);
echo "Category 1 Slug: {$cat1->slug}\n";
echo "Category 2 Slug: {$cat2->slug}\n";

echo "\n--- 2. Testing Shipping Consolidation ---\n";
$service = new ShippingService();

// Ensure users exist
$user1 = User::updateOrCreate(['id' => 1], ['name' => 'User 1', 'email' => 'user1@example.com', 'password' => bcrypt('password')]);
$user2 = User::updateOrCreate(['id' => 2], ['name' => 'User 2', 'email' => 'user2@example.com', 'password' => bcrypt('password')]);

$prod1 = new Product(['name' => "P1 $suffix"]);
$prod1->price = 10;
$prod1->quantity = 10;
$prod1->status = 1;
$prod1->sku = "s1-$suffix";
$prod1->shipping_class = 'courier_standard';
$prod1->items_per_package = 10;
$prod1->save();

$prod2 = new Product(['name' => "P2 $suffix"]);
$prod2->price = 10;
$prod2->quantity = 10;
$prod2->status = 1;
$prod2->sku = "s2-$suffix";
$prod2->shipping_class = 'courier_standard';
$prod2->items_per_package = 10;
$prod2->save();

$cart = Cart::create(['user_id' => 1, 'session_id' => "session-$suffix"]);
CartItem::create([
    'cart_id' => $cart->id,
    'product_id' => $prod1->id,
    'product_name' => $prod1->name,
    'product_sku' => $prod1->sku,
    'product_price' => $prod1->price,
    'quantity' => 1,
    'total' => $prod1->price,
]);

Auth::login($user1);
echo "Logged in as: " . Auth::id() . "\n";

$cart = $cart->fresh(['items.product']);
$cost = $service->calculate($cart, 'courier');
echo "Consolidated Cost (qty 1): {$cost}\n";

echo "\n--- 3. Testing Ownership Validation (IDOR) ---\n";
Auth::login($user2);
echo "Switched to User: " . Auth::id() . " (Current cart belongs to: {$cart->user_id})\n";

try {
    $service->calculate($cart, 'courier');
    echo "IDOR Test FAILED: Calculation allowed for foreign cart.\n";
} catch (\Illuminate\Auth\Access\AuthorizationException $e) {
    echo "IDOR Test PASSED: Caught AuthorizationException.\n";
}

echo "\n--- Tests Completed ---\n";
