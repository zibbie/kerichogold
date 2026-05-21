<?php

use App\Models\Order;
use App\Models\Product;
use App\Models\OrderItem;
use App\Mail\OrderConfirmationMail;
use App\Mail\AdminOrderNotificationMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "--- FINAL LIVE TEST: SENDING REAL EMAILS ---\n";

$clientEmail = 'zbyszeklupikasza@gmail.com';
$adminEmail = 'united@staites.com';

DB::beginTransaction();
try {
    $product = Product::first();
    if (!$product) throw new Exception("No products found for testing");

    $orderNumber = 'FINAL-TEST-' . time();
    $order = new Order();
    $order->fill([
        'order_number' => $orderNumber,
        'email' => $clientEmail,
        'name' => 'Zibi Testowy',
        'phone' => '123456789',
        'city' => 'Warszawa',
        'zip' => '00-001',
        'payment_method' => 'COD',
        'billing_address' => [],
        'shipping_address' => [],
    ]);
    $order->total = 299.99;
    $order->status = 'pending';
    $order->save();

    // Create a dummy item
    OrderItem::create([
        'order_id' => $order->id,
        'product_id' => $product->id,
        'product_name' => $product->name,
        'product_sku' => $product->sku ?? 'TEST-SKU',
        'quantity' => 1,
        'price' => 299.99,
        'total' => 299.99,
    ]);

    echo "Created live test order $orderNumber\n";
    echo "Payment method label: " . $order->payment_method_label . "\n";

    // 1. Send to Customer
    echo "Sending Confirmation to Customer ($clientEmail)...\n";
    Mail::to($clientEmail)->send(new OrderConfirmationMail($order));
    echo "Sent.\n";

    // 2. Send to Admin
    echo "Sending Notification to Admin ($adminEmail)...\n";
    Mail::to($adminEmail)->send(new AdminOrderNotificationMail($order));
    echo "Sent.\n";

    // We COMMIT this time so the user can see it in the admin panel
    DB::commit();
    echo "Transaction COMMITTED. Order is live in Admin Panel.\n";

} catch (Exception $e) {
    DB::rollBack();
    echo "ERROR: " . $e->getMessage() . "\n";
}

echo "--- FINAL LIVE TEST END ---\n";
