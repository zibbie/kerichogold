<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Category;
use App\Models\Product;
use App\Models\Cart;
use App\Models\CartItem;

echo "--- Cleaning up Test Data ---\n";

$deletedProducts = Product::where('name', 'LIKE', 'Test Product%')->orWhere('name', 'LIKE', 'P1 1%')->orWhere('name', 'LIKE', 'P2 1%')->delete();
echo "Deleted {$deletedProducts} test products.\n";

$deletedCategories = Category::where('name', 'LIKE', 'Test Category%')->delete();
echo "Deleted {$deletedCategories} test categories.\n";

$deletedCartItems = CartItem::whereHas('cart', function($q) {
    $q->where('session_id', 'LIKE', 'session-%')->orWhere('session_id', 'test-session');
})->delete();
echo "Deleted {$deletedCartItems} test cart items.\n";

$deletedCarts = Cart::where('session_id', 'LIKE', 'session-%')->orWhere('session_id', 'test-session')->delete();
echo "Deleted {$deletedCarts} test carts.\n";

echo "--- Cleanup Completed ---\n";
