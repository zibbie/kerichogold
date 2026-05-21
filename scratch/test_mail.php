<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\Mail;
use App\Mail\OrderConfirmationMail;
use App\Models\Order;

$order = Order::latest()->first();
if (!$order) {
    echo "No orders found.\n";
    exit;
}

echo "Testing mail to: " . $order->email . "\n";
try {
    Mail::to($order->email)
        ->bcc('info@nevro-wm.pl')
        ->send(new OrderConfirmationMail($order));
    echo "Mail sent successfully.\n";
} catch (\Exception $e) {
    echo "Mail failed: " . $e->getMessage() . "\n";
}
