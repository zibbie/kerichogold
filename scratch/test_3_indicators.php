<?php

use App\Models\Order;
use Illuminate\Support\Facades\DB;

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "--- TEST 3: Dashboard Indicators ---\n";

// 1. Initial pending count
$initialCount = Order::where('status', 'pending')->count();
echo "Current pending orders in DB: $initialCount\n";

// 2. Create a dummy order
DB::beginTransaction();
try {
    $orderNumber = 'INDICATOR-TEST-' . time();
    $order = new Order();
    $order->fill([
        'order_number' => $orderNumber,
        'email' => 'indicator@test.com',
        'name' => 'Indicator Test',
        'phone' => '000000000',
        'city' => 'Test',
        'zip' => '00-000',
        'payment_method' => 'COD',
        'billing_address' => [],
        'shipping_address' => [],
    ]);
    $order->total = 1.00;
    $order->status = 'pending';
    $order->save();

    echo "Created dummy order with status: {$order->status}\n";

    // 3. New pending count
    $newCount = Order::where('status', 'pending')->count();
    echo "New pending count in DB: $newCount\n";

    if ($newCount === $initialCount + 1) {
        echo "SUCCESS: Dashboard/Badge indicator logic verified (Count increased).\n";
    } else {
        echo "FAIL: Count did not increase correctly.\n";
    }

    // Rollback
    DB::rollBack();
    echo "Transaction rolled back.\n";

} catch (Exception $e) {
    DB::rollBack();
    echo "ERROR: " . $e->getMessage() . "\n";
}

echo "--- TEST 3 END ---\n";
