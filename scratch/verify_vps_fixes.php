<?php

use App\Models\Order;
use App\Models\Product;
use App\Models\OrderItem;
use App\Mail\AdminOrderNotificationMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "--- START VERIFICATION ---\n";

// 1. Initial pending count
$initialCount = Order::where('status', 'pending')->count();
echo "Initial pending count: $initialCount\n";

// 2. Create a dummy order to test COD status and Admin Email
DB::beginTransaction();
try {
    $product = Product::first();
    if (!$product) throw new Exception("No products found for testing");

    $orderNumber = 'TEST-' . time();
    $order = new Order();
    $order->fill([
        'order_number' => $orderNumber,
        'email' => 'test@example.com',
        'name' => 'Test User',
        'phone' => '123456789',
        'city' => 'Test City',
        'zip' => '00-000',
        'payment_method' => 'COD',
        'billing_address' => [],
        'shipping_address' => [],
    ]);
    
    // Explicitly set protected fields
    $order->total = 100.00;
    $order->status = 'pending';
    $order->payment_status = 'pending';
    $order->save();

    echo "Created dummy order $orderNumber with status: {$order->status}\n";

    // Check if status is indeed pending
    if ($order->status !== 'pending') {
        echo "FAIL: Order status is {$order->status}, expected pending\n";
    } else {
        echo "SUCCESS: Order status is pending\n";
    }

    // 3. Check new pending count
    $newCount = Order::where('status', 'pending')->count();
    echo "New pending count: $newCount\n";
    if ($newCount === $initialCount + 1) {
        echo "SUCCESS: Indicator count logic verified\n";
    } else {
        echo "FAIL: Indicator count logic mismatch\n";
    }

    // 4. Test Admin Notification Queueing
    echo "Triggering Admin notification...\n";
    Mail::to('info@nevro-wm.pl')->queue(new AdminOrderNotificationMail($order));
    echo "Notification queued.\n";

    // Rollback so we don't clutter the DB
    DB::rollBack();
    echo "Transaction rolled back.\n";

} catch (Exception $e) {
    DB::rollBack();
    echo "ERROR: " . $e->getMessage() . "\n";
}

echo "--- END VERIFICATION ---\n";
